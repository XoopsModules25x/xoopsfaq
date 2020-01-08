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
 * Category Admin file
 *
 * @package   module\xoopsfaq\admin
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 *
 * @see       Xmf\Request
 * @see       \Xmf\Module\Helper\Permission
 */

use Xmf\Request;
use XoopsModules\Xoopsfaq;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

/** @var Xoopsfaq\CategoryHandler $categoryHandler */
/** @var Xoopsfaq\ContentsHandler $contentsHandler */
/** @var Xoopsfaq\Helper $helper */
$categoryHandler = $helper->getHandler('Category');

$op = Request::getCmd('op', 'default');
switch ($op) {
    case 'edit':
        $adminObject->displayNavigation(basename(__FILE__));
        $catId = Request::getInt('category_id', null);
        $obj   = $categoryHandler->get($catId);
        if ($obj instanceof Xoopsfaq\Category) {
            $obj->displayForm();
        } else {
            $categoryHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_EDIT_CAT);
        }
        break;

    case 'delete':
        $ok    = Request::getInt('ok', Xoopsfaq\Constants::CONFIRM_NOT_OK);
        $catId = Request::getInt('category_id', Xoopsfaq\Constants::DEFAULT_CATEGORY);
        if (Xoopsfaq\Constants::CONFIRM_OK === (int)$ok) {
            // check to make sure this passes form submission security
            if ($GLOBALS['xoopsSecurity'] instanceof XoopsSecurity) {
                if (!$GLOBALS['xoopsSecurity']->check()) {
                    // failed xoops security check
                    $helper->redirect('admin/index.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, $GLOBALS['xoopsSecurity']->getErrors(true));
                }
            } else {
                $helper->redirect('admin/index.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_INVALID_SECURITY_TOKEN);
            }

            $obj = $categoryHandler->get($catId);
            if ($obj instanceof Xoopsfaq\Category && !$obj->isNew()) {
                // Delete all FAQs in this category
                $contentsHandler = $helper->getHandler('Contents');
                $criteria        = new \Criteria('contents_cid', $catId);
                $success         = $contentsHandler->deleteAll($criteria);
                // Delete the category
                if (true === $categoryHandler->delete($obj)) {
                    // Delete comments
                    xoops_comment_delete($helper->getModule()->getVar('mid'), $catId);
                    $helper->redirect('admin/category.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
                    // Delete permissions
                    $permHelper = new \Xmf\Module\Helper\Permission();
                    $permHelper->deletePermissionForItem('viewcat', $catId);
                }
            }
            $categoryHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CAT);
        } else {
            $adminObject->displayNavigation(basename(__FILE__));
            xoops_confirm(['op' => 'delete', 'category_id' => $catId, 'ok' => Xoopsfaq\Constants::CONFIRM_OK], 'category.php', _AM_XOOPSFAQ_RUSURE_CAT);
        }
        break;

    case 'save':
        if (($GLOBALS['xoopsSecurity'] instanceof XoopsSecurity)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                // failed xoops security check
                $helper->redirect('admin/index.php', 3, $GLOBALS['xoopsSecurity']->getErrors(true));
            }
        } else {
            $helper->redirect('admin/index.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_INVALID_SECURITY_TOKEN);
        }

        $catId = Request::getInt('category_id', Xoopsfaq\Constants::DEFAULT_CATEGORY, 'POST');
        $obj   = $categoryHandler->get($catId); // creates category if catId = 0, else gets requested category
        if ($obj instanceof Xoopsfaq\Category) {
            $obj->setVar('category_title', Request::getString('category_title', ''));
            $obj->setVar('category_order', Request::getInt('category_order', Xoopsfaq\Constants::DEFAULT_ORDER));
            $savedId = $categoryHandler->insert($obj);
            if ($savedId) {
                // Save group permissions
                $permHelper = new \Xmf\Module\Helper\Permission();
                $name       = $permHelper->defaultFieldName('viewcat', $catId);
                $groups     = Xmf\Request::getArray($name, [], 'POST');
                $permHelper->savePermissionForItem('viewcat', $savedId, $groups);
                $helper->redirect('admin/category.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
            }
        }
        $categoryHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_ADD_CAT);
        break;

    case 'default':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_XO_XOOPSFAQ_ADDCAT, 'category.php?op=edit', 'add', '');
        $adminObject->displayButton('left');
        $categoryHandler->displayAdminListing('order');
        break;
}
require_once __DIR__ . '/admin_footer.php';
