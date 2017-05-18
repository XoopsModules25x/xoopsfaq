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
 * Admin display footer file
 *
 * @package   module\xoopsfaq\admin
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since::   1.23
 *
 * @see Xmf\Module\Admin
 * @see Xmf\Module\Helper
 */

$moduleDirName = basename(dirname(__DIR__));
require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once $GLOBALS['xoops']->path('include/cp_header.php');

$xfHelper    = Xmf\Module\Helper::getHelper($moduleDirName);
$adminObject = Xmf\Module\Admin::getInstance();
xoops_load('constants', $moduleDirName);

// Load language files
$xfHelper->loadLanguage('admin');
$xfHelper->loadLanguage('modinfo');
$xfHelper->loadLanguage('main');
