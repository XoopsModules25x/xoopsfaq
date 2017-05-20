<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: XoopsFAQ
 *
 * @package   module\xoopsfaq\include
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    trabis <lusopoemas@gmail.com>
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     File available since version 1.25
 */
//use Xmf\Debug;
#use Xmf\Database\Tables;
#use Xmf\Module\Helper;
#use Xmf\Request;

/**
 * @internal {Make sure you PROTECT THIS FILE}
 */

if ((!defined('XOOPS_ROOT_PATH'))
   || !($GLOBALS['xoopsUser'] instanceof XoopsUser)
   || !($GLOBALS['xoopsUser']->isAdmin()))
{
     exit("Restricted access" . PHP_EOL);
}

/**
 * Pre-installation checks before installation of Xoopsfaq
 *
 * @param XoopsModule $module
 * @param string $prev_version version * 100
 *
 * @see XoopsfaqUtility
 *
 * @return bool success ok to install
 *
 */
function xoops_module_pre_update_xoopsfaq(XoopsModule $module, $prev_version)
{
    /** @var XoopsfaqUtility $utilsClass */
    $utilsClass = ucfirst($module->dirname()) . 'Utility';
    if (!class_exists($utilsClass)) {
        xoops_load('utility', $module->dirname());
    }

    $xoopsSuccess = $utilsClass::checkVerXoops($module);
    $phpSuccess   = $utilsClass::checkVerPHP($module);
    return $xoopsSuccess && $phpSuccess;
}

/**
 * Upgrade works to update Xoopsfaq from previous versions
 *
 * @param XoopsModule $module
 * @param string $prev_version version * 100
 *
 * @see Xmf\Module\Admin
 * @see XoopsfaqUtility
 *
 * @return bool
 *
 */
function xoops_module_update_xoopsfaq(XoopsModule $module, $prev_version)
{
    $moduleDirName = $module->getVar('dirname');
    $xfHelper = Xmf\Module\Helper::getHelper($moduleDirName);
    if (!class_exists('XoopsfaqUtility')) {
        xoops_load('utility', $moduleDirName);
    }

    //----------------------------------------------------------------
    // Upgrade for Xoopsfaq < 1.25
    //----------------------------------------------------------------
    $success = true;

    $xfHelper->loadLanguage('modinfo');
    $xfHelper->loadLanguage('admin');

    if ($prev_version < 125) {
        //----------------------------------------------------------------
        // Remove previous .css, .js and .images directories since they've
        // been relocated to ./assets
        //----------------------------------------------------------------
        $old_directories = array($xfHelper->path('css/'),
                                 $xfHelper->path('js/'),
                                 $xfHelper->path('images/')
        );
        foreach ($old_directories as $old_dir) {
            $dirInfo = new SplFileInfo($old_dir);
            if ($dirInfo->isDir()) {
                // The directory exists so delete it
                if (false === XoopsfaqUtility::rrmdir($old_dir)) {
                    $module->setErrors(sprintf(_AM_XOOPSFAQ_ERROR_BAD_DEL_PATH, $old_dir));
                    return false;
                }
            }
            unset($dirInfo);
        }

        //-----------------------------------------------------------------------
        // Remove ./template/*.html (except index.html) files since they've
        // been replaced by *.tpl files
        //-----------------------------------------------------------------------
        $path       = $xfHelper->path('templates/');
        $unfiltered = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $iterator   = new RegexIterator($unfiltered, "/.*\.html/");
        foreach ($iterator as $name => $fObj) {
            if (($fObj->isFile()) && ('index.html' !== $fObj->getFilename())) {
                if (false === ($success = unlink($fObj->getPathname()))) {
                    $module->setErrors(sprintf(_AM_XOOPSFAQ_ERROR_BAD_REMOVE, $fObj->getPathname()));
                    return false;
                }
            }
        }

        //-----------------------------------------------------------------------
        // Now remove a some misc files that were renamed or deprecated
        //-----------------------------------------------------------------------
        $oldFiles = array($xfHelper->path('include/functions.php'),
                            $xfHelper->path('class/utilities.php')
        );
        foreach($oldFiles as $file) {
            if (is_file($file)) {
                if (false === ($delOk = unlink($file))) {
                    $module->setErrors(sprintf(_AM_XOOPSFAQ_ERROR_BAD_REMOVE, $file));
                }
                $success = $success && $delOk;
            }
        }
    }
    return $success;
}
