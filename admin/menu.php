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
 * Administration Menu for the Xoops FAQ Module
 *
 * @package   module\xoopsfaq\admin
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 *
 * @see       \Xmf\Module\Admin
 */

use Xmf\Module\Admin;
use XoopsModules\Xoopsfaq;

require dirname(__DIR__) . '/preloads/autoloader.php';

/** @var \XoopsModules\Xoopsfaq\Helper $helper */
$helper = Xoopsfaq\Helper::getInstance();
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

// get path to icons
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}


$adminmenu[] = [
    'title' => _MI_XOOPSFAQ_MENU_ADMIN_INDEX,
    'link'  => 'admin/index.php',
    'desc'  => _MI_XOOPSFAQ_ADMIN_INDEX_DESC,
    'icon'  => Admin::menuIconPath('home.png'),
];

$adminmenu[] = [
    'title' => _MI_XOOPSFAQ_MENU_ADMIN_CATEGORY,
    'link'  => 'admin/category.php',
    'desc'  => _MI_XOOPSFAQ_ADMIN_CATEGORY_DESC,
    'icon'  => Admin::menuIconPath('category.png'),
];

$adminmenu[] = [
    'title' => _MI_XOOPSFAQ_MENU_ADMIN_FAQ,
    'link'  => 'admin/main.php',
    'desc'  => _MI_XOOPSFAQ_ADMIN_FAQ_DESC,
    'icon'  => Admin::menuIconPath('mail_foward.png'),
];

if ($helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => _MI_XOOPSFAQ_MENU_ADMIN_MIGRATE,
        'link'  => 'admin/migrate.php',
        'icon'  => Admin::menuIconPath('database_go.png'),
    ];
}

$adminmenu[] = [
    'title' => _MI_XOOPSFAQ_MENU_ADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'desc'  => _MI_XOOPSFAQ_ADMIN_ABOUT_DESC,
    'icon'  => Admin::menuIconPath('about.png'),
];
