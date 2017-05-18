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
 * Description: Xoops FAQ module admin language defines
 *
 * @package   module\xoopsfaq\language
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Bootstrap Defines
 */
define('_XO_LA_EDIT', 'Edit Item');
define('_XO_LA_DELETE', 'Delete Item');
define('_XO_LA_BACKTOTOP', 'Back to top');
define('_XO_LA_BACKTOINDEX', 'Back to Index');
define('_XO_LA_XOOPSFAQ', 'FAQs');
define('_XO_LA_MAIN', 'Main');
define('_XO_LA_TOC', 'Table of Contents');



/**
 * Content
 */
//define('_AM_XOOPSFAQ_CONTENTS_HEADER', 'FAQ Content Management');
//define('_AM_XOOPSFAQ_CONTENTS_SUBHEADER', '');
//define('_AM_XOOPSFAQ_CONTENTS_LIST_DESC', '');
define('_AM_XOOPSFAQ_CONTENTS_ID', 'Nº');
define('_AM_XOOPSFAQ_CONTENTS_TITLE', 'FAQ Title');
define('_AM_XOOPSFAQ_CONTENTS_WEIGHT', 'Order');
define('_AM_XOOPSFAQ_CONTENTS_PUBLISH', 'Published');
define('_AM_XOOPSFAQ_CONTENTS_ACTIVE', 'Active');
define('_AM_XOOPSFAQ_CONTENTS_VIEW', 'See question on user side');
define('_AM_XOOPSFAQ_ACTIONS', 'Actions');
define('_AM_XOOPSFAQ_E_CONTENTS_CATEGORY', 'Content Category:');
define('_AM_XOOPSFAQ_E_CONTENTS_CATEGORY_DESC', 'Select a category you wish this item to be placed under');
define('_AM_XOOPSFAQ_E_CONTENTS_TITLE', 'Content Title:');
define('_AM_XOOPSFAQ_E_CONTENTS_TITLE_DESC', 'Enter a title for this item.');
define('_AM_XOOPSFAQ_E_CONTENTS_CONTENT', 'Content Body:');
define('_AM_XOOPSFAQ_E_CONTENTS_CONTENT_DESC', '');
define('_AM_XOOPSFAQ_E_CONTENTS_PUBLISH', 'Publish Date:');
define('_AM_XOOPSFAQ_E_CONTENTS_PUBLISH_DESC', 'Select the date to publish content');
define('_AM_XOOPSFAQ_E_CONTENTS_WEIGHT', 'Content Order:');
define('_AM_XOOPSFAQ_E_CONTENTS_WEIGHT_DESC', 'Enter a value for the item order. ');
define('_AM_XOOPSFAQ_E_CONTENTS_ACTIVE', 'Content Active:');
define('_AM_XOOPSFAQ_E_CONTENTS_ACTIVE_DESC', 'Select whether this item will be hidden or not');
define('_AM_XOOPSFAQ_E_DOHTML', 'Show as HTML');
define('_AM_XOOPSFAQ_E_BREAKS', 'Convert Linebreaks to Xoops breaks');
define('_AM_XOOPSFAQ_E_DOIMAGE', 'Show Xoops Images');
define('_AM_XOOPSFAQ_E_DOXCODE', 'Show Xoops Codes');
define('_AM_XOOPSFAQ_E_DOSMILEY', 'Show Xoops Smilies');
define('_AM_XOOPSFAQ_CREATE_NEW', 'Create New Item');
define('_AM_XOOPSFAQ_MODIFY_ITEM', 'Modify Item: %s');

/**
 * Category
 */
define('_XO_XOOPSFAQ_ADDCAT', 'Add Category');
define('_AM_XOOPSFAQ_CATEGORY_HEADER', 'Faq Category Management');
//define('_AM_XOOPSFAQ_CATEGORY_SUBHEADER', '');
define('_AM_XOOPSFAQ_CATEGORY_DELETE_DESC', 'Delete Check! You are about to delete this item. You can cancel this action by clicking on the cancel button or you can choose to continue.<br><br>This action is not reversible.');
define('_AM_XOOPSFAQ_CATEGORY_EDIT_DESC', 'Edit Mode: You can edit item properties here. Click the submit button to make your changes permanent or click Cancel to return you were you were.');
define('_AM_XOOPSFAQ_CATEGORY_LIST_DESC', '');
define('_AM_XOOPSFAQ_CATEGORY_ID', 'Nº');
define('_AM_XOOPSFAQ_CATEGORY_TITLE', 'Category Title');
define('_AM_XOOPSFAQ_CATEGORY_ORDER', 'Order');
define('_AM_XOOPSFAQ_CATEGORY_GROUP_PERMS', 'Groups with View Category Permission');
//define('_XO_LA_ACTIONS', 'Actions');
define('_AM_XOOPSFAQ_E_CATEGORY_TITLE', 'Category Title:');
define('_AM_XOOPSFAQ_E_CATEGORY_TITLE_DESC', '');
define('_AM_XOOPSFAQ_E_CATEGORY_ORDER', 'Category Order:');
define('_AM_XOOPSFAQ_E_CATEGORY_ORDER_DESC', '');

//define('_XO_XOOPSFAQ_CREATENEW', 'Create New');
define('_AM_XOOPSFAQ_NOLISTING', 'No Items Found');

/**
 * Database and error
 */
define('_AM_XOOPSFAQ_ERROR_SUB', 'We have encountered an error');
define('_AM_XOOPSFAQ_RUSURE_CAT', 'Are you sure you want to delete this category and all of its FAQs?');
define('_AM_XOOPSFAQ_DBSUCCESS', 'Database updated successfully!');
define('_AM_XOOPSFAQ_ERROR_NO_CAT', 'Error: No category name given, please go back and enter a category name');
define('_AM_XOOPSFAQ_ERROR_COULD_NOT_ADD_CAT', 'Error: Could not add category to database.');
define('_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CAT', 'Error: Could not delete requested category.');
define('_AM_XOOPSFAQ_ERROR_COULD_NOT_EDIT_CAT', 'Error: Could not edit requested item.');
define('_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CONTENTS', 'Error: Could not delete FAQ contents.');
define('_AM_XOOPSFAQ_RUSURE_CONTENTS', 'Are you sure you want to delete this FAQ?');
//define('_AM_XOOPSFAQ_ERROR_COULD_NOT_UP_CONTENTS', 'Error: Could not update FAQ contents.');
//define('_AM_XOOPSFAQ_ERROR_COULD_NOT_ADD_CONTENTS', 'Error: Could not add FAQ contents.');
define('_AM_XOOPSFAQ_ERROR_NO_CATS_EXIST', 'Error: There are no categories created yet. Before you can create a new FAQ, you must create a category first.');
//define('_AM_XOOPSFAQ_NOTHING_TO_SHOW', 'No Items To Display');

/**
 * Install/Uninstall/Update
 */
define('_AM_XOOPSFAQ_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_XOOPSFAQ_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('_AM_XOOPSFAQ_ERROR_BAD_REMOVE', 'Could not delete %s');

//1.22

// About.php
//define('_AM_XOOPSFAQ_ABOUT_RELEASEDATE', 'Released: ');
//define('_AM_XOOPSFAQ_ABOUT_UPDATEDATE', 'Updated: ');
//define('_AM_XOOPSFAQ_ABOUT_AUTHOR', 'Author: ');
//define('_AM_XOOPSFAQ_ABOUT_CREDITS', 'Credits: ');
//define('_AM_XOOPSFAQ_ABOUT_LICENSE', 'License: ');
//define('_AM_XOOPSFAQ_ABOUT_MODULE_STATUS', 'Status: ');
//define('_AM_XOOPSFAQ_ABOUT_WEBSITE', 'Website: ');
//define('_AM_XOOPSFAQ_ABOUT_AUTHOR_NAME', 'Author name: ');
//define('_AM_XOOPSFAQ_ABOUT_CHANGELOG', 'Change Log');
//define('_AM_XOOPSFAQ_ABOUT_MODULE_INFO', 'Module Info');
//define('_AM_XOOPSFAQ_ABOUT_AUTHOR_INFO', 'Author Info');
//define('_AM_XOOPSFAQ_ABOUT_DESCRIPTION', 'Description: ');

//define('_AM_XOOPSFAQ_ADMIN_PREFERENCES', 'Settings');
//define('_AM_XOOPSFAQ_ADMIN_INDEX_TXT1', 'The XoopsFAQ module is used to create a list of Frequently Asked Questions (FAQs) for your website. It is typically used to create a list of common questions about your website, service or product(s), but you could use it to list questions and answers about anything really. FAQs can be organized into categories.');

// Text for Admin footer
//define('_AM_XOOPSFAQ_ADMIN_FOOTER', '<div class='center smallsmall italic pad5'>XOOPS FAQ is maintained by the <a class="tooltip" rel="external" href="http://xoops.org/" title="Visit XOOPS Community">XOOPS Community</a></div>');
