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
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 *
 * @see       Xmf\Module\Admin
 * @see       \XoopsModules\Xoopsfaq\Helper
 */

use Xmf\Module\Admin;
use XoopsModules\Xoopsfaq\{
    Helper
};


require \dirname(__DIR__, 3) . '/include/cp_header.php';
require_once \dirname(__DIR__, 3) . '/class/xoopsformloader.php';

require \dirname(__DIR__) . '/include/common.php';

require \dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = \basename(\dirname(__DIR__));

/** @var \XoopsModules\Xoopsfaq\Helper $helper */
$helper = Helper::getInstance();
/** @var Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

