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
 * @since::   1.23
 *
 * @see \Xmf\Module\Admin
 */
use Xmf\Module\Admin;
use Xmf\Module\Helper;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

$adminmenu = array(
    array('title' => _MI_XOOPSFAQ_MENU_ADMIN_INDEX,
           'link' => 'admin/index.php',
           'desc' => _MI_XOOPSFAQ_ADMIN_INDEX_DESC,
           'icon' => Admin::menuIconPath('home.png', '32')
    ),
    array('title' => _MI_XOOPSFAQ_MENU_ADMIN_CATEGORY,
           'link' => 'admin/category.php',
           'desc' => _MI_XOOPSFAQ_ADMIN_CATEGORY_DESC,
           'icon' => Admin::menuIconPath('category.png', '32')
    ),
    array('title' => _MI_XOOPSFAQ_MENU_ADMIN_FAQ,
           'link' => 'admin/main.php',
           'desc' => _MI_XOOPSFAQ_ADMIN_FAQ_DESC,
           'icon' => Admin::menuIconPath('faq.png', '32')
    ),
    array('title' => _MI_XOOPSFAQ_MENU_ADMIN_ABOUT,
           'link' => 'admin/about.php',
           'desc' => _MI_XOOPSFAQ_ADMIN_ABOUT_DESC,
           'icon' => Admin::menuIconPath('about.png', '32')
    )
);
