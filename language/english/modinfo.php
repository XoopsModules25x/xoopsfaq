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
 * XoopsFAQ module
 * Description: Module Init Language Definitions
 *
 * @package   module\xoopsfaq\language
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Module Version
 */
define('_MI_XOOPSFAQ_NAME', 'XOOPS FAQ');
define(
    '_MI_XOOPSFAQ_DESC',
    'This module is used to create Frequently Asked Questions (FAQs). You can use it to ' . 'provide information on your website for your users. The module if very simple, but ' . 'flexible enough to use for other purposes as well.'
);

/**
 * Module Menu
 */
//define('_MI_XOOPSFAQ_MENU_MODULEHOME', 'Module Home');
//define('_MI_XOOPSFAQ_MENU_MODULEBLOCKS', 'Blocks');
//define('_MI_XOOPSFAQ_MENU_MODULETEMPLATES', 'Templates');
//define('_MI_XOOPSFAQ_MENU_MODULECOMMENTS', 'Comments');
define('_MI_XOOPSFAQ_MENU_ADMIN_INDEX', 'Home');
define('_MI_XOOPSFAQ_MENU_ADMIN_CATEGORY', 'Category');
define('_MI_XOOPSFAQ_MENU_ADMIN_FAQ', 'FAQ');
define('_MI_XOOPSFAQ_MENU_ADMIN_MIGRATE', 'Migrate');
define('_MI_XOOPSFAQ_MENU_ADMIN_ABOUT', 'About');

// index.php
//define('_MI_XOOPSFAQ_ADMIN_HOME','Home');
define('_MI_XOOPSFAQ_ADMIN_INDEX', 'FAQ');
define('_MI_XOOPSFAQ_ADMIN_ABOUT', 'About');
//define('_MI_XOOPSFAQ_ADMIN_HELP','Help');
//define('_MI_XOOPSFAQ_ADMIN_PREFERENCES','Preferences');

//define('_MI_XOOPSFAQ_ADMIN_HOME_DESC','Home');
define('_MI_XOOPSFAQ_ADMIN_INDEX_DESC', 'FAQ');
define('_MI_XOOPSFAQ_ADMIN_CATEGORY_DESC', 'Category');
define('_MI_XOOPSFAQ_ADMIN_FAQ_DESC', '');
define('_MI_XOOPSFAQ_ADMIN_ABOUT_DESC', 'About');
//define('_MI_XOOPSFAQ_ADMIN_HELP_DESC','Help');

/**
 * Admin Help
 */
define('_MI_XOOPSFAQ_HELP_OVERVIEW', 'Overview');
define('_MI_XOOPSFAQ_HELP_TIPS', 'Tips');

/**
 * Blocks
 */
define('_MI_XOOPSFAQ_BNAME1', 'Random FAQ');
define('_MI_XOOPSFAQ_BNAME1_DESC', 'Shows a random FAQ');
define('_MI_XOOPSFAQ_BNAME2', 'Recent FAQ');
define('_MI_XOOPSFAQ_BNAME2_DESC', 'Shows most recent FAQ(s)');
define('_MI_XOOPSFAQ_BNAME3', 'FAQ Categories');
define('_MI_XOOPSFAQ_BNAME3_DESC', 'Shows FAQ categories');

/**
 * Template Descriptions
 */
define('_MI_XOOPSFAQ_TPL_INDEX_DESC', '');
define('_MI_XOOPSFAQ_TPL_CATEGORY_DESC', '');
//define('_MI_XOOPSFAQ_TPLDESC_ADMIN_ABOUT', '');
//define('_MI_XOOPSFAQ_TPLDESC_ADMIN_HELP', '');
//define('_MI_XOOPSFAQ_TPLDESC_ADMIN_INDEX', '');

/**
 * Module Prefs
 */
define('_MI_XOOPSFAQ_EDITORS', 'Select Editor:');
define('_MI_XOOPSFAQ_EDITORS_DESC', 'Please select the editor you would like to use.');
//1.25
//Help
define('_MI_XOOPSFAQ_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_XOOPSFAQ_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_XOOPSFAQ_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_XOOPSFAQ_OVERVIEW', 'Overview');

//define('_MI_XOOPSFAQ_HELP_DIR', __DIR__);

//help multi-page
define('_MI_XOOPSFAQ_DISCLAIMER', 'Disclaimer');
define('_MI_XOOPSFAQ_LICENSE', 'License');
define('_MI_XOOPSFAQ_SUPPORT', 'Support');
