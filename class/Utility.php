<?php

namespace XoopsModules\Xoopsfaq;

/*
 Xoopsfaq Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  myQuiz
 *
 * @package      ::    \module\xoopsfaq\class
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @copyright    http://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       zyspec
 * @author       Mamba
 * @since        ::      File available since version 4.10
 */

use XoopsModules\Xoopsfaq;

/**
 * Xoopsfaq\Utility
 *
 * Static utility class to provide common functionality
 *
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    /**
     *
     * Verifies XOOPS version meets minimum requirements for this module
     * @static
     * @param \XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerXoops($module)
    {
        $currentVersion  = strtolower(str_replace('XOOPS ', '', XOOPS_VERSION));
        $requiredVersion = strtolower($module->getInfo('min_xoops'));
        $vc              = version_compare($currentVersion, $requiredVersion);
        $success         = ($vc >= 0);
        if (false === $success) {
            xoops_loadLanguage('admin', $module->dirname());
            $module->setErrors(sprintf(_AM_XOOPSFAQ_ERROR_BAD_XOOPS, $requiredVersion, $currentVersion));
        }

        return $success;
    }

    /**
     *
     * Verifies PHP version meets minimum requirements for this module
     * @static
     * @param \XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerPHP($module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        // check for minimum PHP version
        $success = true;
        $verNum  = PHP_VERSION;
        $reqVer  = $module->getInfo('min_php');
        if ((false !== $reqVer) && ('' !== $reqVer)) {
            if (version_compare($verNum, (string)$reqVer, '<')) {
                $module->setErrors(sprintf(_AM_XOOPSFAQ_ERROR_BAD_PHP, $reqVer, $verNum));
                $success = false;
            }
        }
        return $success;
    }

    /**
     *
     * Remove files and (sub)directories
     *
     * @param string $src source directory to delete
     *
     * @return bool true on success
     * @see \XoopsModules\Xoopsfaq\Helper::isUserAdmin()
     *
     * @see \XoopsModules\Xoopsfaq\Helper::getHelper()
     */
    public static function deleteDirectory($src)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        $success = true;
        // remove old files
        $dirInfo = new \SplFileInfo($src);
        // validate is a directory
        if ($dirInfo->isDir()) {
            $fileList = array_diff(scandir($src), ['..', '.']);
            foreach ($fileList as $k => $v) {
                $fileInfo = new \SplFileInfo($src . '/' . $v);
                if ($fileInfo->isDir()) {
                    // recursively handle subdirectories
                    if (!$success = static::deleteDirectory($fileInfo->getRealPath())) {
                        break;
                    }
                } elseif (!($success = unlink($fileInfo->getRealPath()))) {
                    break;
                }
            }
            // now delete this (sub)directory if all the files are gone
            if ($success) {
                $success = rmdir($dirInfo->getRealPath());
            }
        } else {
            // input is not a valid directory
            $success = false;
        }
        return $success;
    }

    /**
     *
     * Recursively remove directory
     *
     * @todo currently won't remove directories with hidden files, should it?
     *
     * @param string $src directory to remove (delete)
     *
     * @return bool true on success
     */
    public static function rrmdir($src)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // Open the source directory to read in files
        $iterator = new \DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                $filename = $fObj->getPathname();
                $fObj     = null; // clear this iterator object to close the file
                if (!unlink($filename)) {
                    return false; // couldn't delete the file
                }
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                // Try recursively on directory
                static::rrmdir($fObj->getPathname());
            }
        }
        $iterator = null;   // clear iterator Obj to close file/directory
        return rmdir($src); // remove the directory & return results
    }

    /**
     * Recursively move files from one directory to another
     *
     * @param String $src  - Source of files being moved
     * @param String $dest - Destination of files being moved
     *
     * @return bool true on success
     */
    public static function rmove($src, $dest)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist and could not be created stop processing
        if (!is_dir($dest) && !mkdir($dest, 0755)) {
            return false;
        }

        // Open the source directory to read in files
        $iterator = new \DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                rename($fObj->getPathname(), $dest . '/' . $fObj->getFilename());
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                // Try recursively on directory
                static::rmove($fObj->getPathname(), $dest . '/' . $fObj->getFilename());
            }
        }
        $iterator = null;   // clear iterator Obj to close file/directory
        return rmdir($src); // remove the directory & return results
    }

    /**
     * Recursively copy directories and files from one directory to another
     *
     * @param string $src  - Source of files being moved
     * @param string $dest - Destination of files being moved
     *
     * @return bool true on success
     * @see \XoopsModules\Xoopsfaq\Helper::isUserAdmin()
     *
     * @see \XoopsModules\Xoopsfaq\Helper::getHelper()
     */
    public static function rcopy($src, $dest)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist and could not be created stop processing
        if (!is_dir($dest) && !mkdir($dest, 0755)) {
            return false;
        }

        // Open the source directory to read in files
        $iterator = new \DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                copy($fObj->getPathname(), $dest . '/' . $fObj->getFilename());
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                static::rcopy($fObj->getPathname(), $dest . '/' . $fObj->getFilename());
            }
        }
        return true;
    }

    /**
     * Render the icon links
     *
     * @param array $icon_array contains operation=>icon_name as key=>value
     * @param mixed $param      HTML parameter
     * @param mixed $value      HTML parameter value to set
     * @param mixed $extra      are any additional HTML attributes desired for the <a> tag
     * @return string
     */
    public static function renderIconLinks($icon_array = [], $param, $value = null, $extra = null)
    {
        $moduleDirName = basename(dirname(__DIR__));
        xoops_loadLanguage('admin', $moduleDirName);
        $ret = '';
        if (null !== $value) {
            foreach ($icon_array as $_op => $icon) {
                if (false === strpos($icon, '.')) {
                    $iconName = $icon;
                    $iconExt  = 'png';
                } else {
                    $iconName = substr($icon, 0, strlen($icon) - strrchr($icon, '.'));
                    $iconExt  = substr(strrchr($icon, '.'), 1);
                }
                $url = (!is_numeric($_op)) ? $_op . '?' . $param . '=' . $value : xoops_getenv('SCRIPT_NAME') . '?op=' . $iconName . '&amp;' . $param . '=' . $value;
                if (null !== $extra) {
                    $url .= ' ' . $extra;
                }
                $title = constant(htmlspecialchars(mb_strtoupper('_XO_LA_' . $iconName), ENT_QUOTES | ENT_HTML5));
                $img   = '<img src="' . \Xmf\Module\Admin::iconUrl($iconName . '.' . $iconExt, '16') . '"' . ' title ="' . $title . '"' . ' alt = "' . $title . '"' . ' class="bnone middle">';
                $ret   .= '<a href="' . $url . '"' . $extra . '>' . $img . '</a>';
            }
        }
        return $ret;
    }
}
