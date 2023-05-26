<?php declare(strict_types=1);
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
 * XoopsFaq installation scripts
 *
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    ZySpec <zyspec@yahoo.com>
 * @copyright https://xoops.org 2001-2017 XOOPS Project
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @link      https://xoops.org XOOPS
 * @since     1.25
 */

use XoopsModules\Xoopsfaq\{
    Helper,
    Utility
};

/**
 * @internal {Make sure you PROTECT THIS FILE}
 */
if ((!defined('XOOPS_ROOT_PATH'))
    || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !($GLOBALS['xoopsUser']->isAdmin())) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * Prepares system prior to attempting to install module
 *
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_xoopsfaq(XoopsModule $module)
{
    /** @var Utility $utilsClass */
    $xoopsSuccess = Utility::checkVerXoops($module);
    $phpSuccess   = Utility::checkVerPhp($module);

    return $xoopsSuccess && $phpSuccess;
}

/**
 * Performs tasks required during installation of the module
 *
 * @param XoopsModule $module
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_xoopsfaq(\XoopsModule $module)
{
    return true;
}
