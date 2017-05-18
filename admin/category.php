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
 * @since::   1.23
 *
 * @see Xmf\Request
 * @see Xmf\Module\Helper\Permission
 */
use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

/* @var $xfCatHandler XoopsfaqCategoryHandler */
/* @var $xfFaqHandler XoopsfaqContentsHandler */
/* @var $xfHelper Xmf\Module\Helper\GenericHelper */
$xfCatHandler = $xfHelper->getHandler('category');

$op = Request::getCmd('op', 'default');
switch ($op) {
    case 'edit':
        $adminObject->displayNavigation('category.php');
        $catId = Request::getInt('category_id', null);
        $obj   = $xfCatHandler->get($catId);
        if ($obj instanceof XoopsfaqCategory) {
          $obj->displayForm();
        } else {
          $xfCatHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_EDIT_CAT);
        }
        break;

    case 'delete':
        $ok    = Request::getInt('ok', XoopsfaqConstants::CONFIRM_NOT_OK);
        $catId = Request::getInt('category_id', XoopsfaqConstants::DEFAULT_CATEGORY);
        if (XoopsfaqConstants::CONFIRM_OK === (int)$ok) {
            // check to make sure this passes form submission security
            if ($GLOBALS['xoopsSecurity'] instanceof XoopsSecurity) {
                if (!$GLOBALS['xoopsSecurity']->check()) {
                    // failed xoops security check
                    $xfHelper->redirect('admin/index.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, $GLOBALS['xoopsSecurity']->getErrors(true));
                }
            } else {
                $xfHelper->redirect('admin/index.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _MD_XOOPSFAQ_INVALID_SECURITY_TOKEN);
            }

            $obj = $xfCatHandler->get($catId);
            if ($obj instanceof XoopsfaqCategory && !$obj->isNew()) {
                // Delete all FAQs in this category
                $xfFaqHandler = $xfHelper->getHandler('contents');
                $criteria     = new Criteria('contents_cid', $catId);
                $success      = $xfFaqHandler->deleteAll($criteria);
                // Delete the category
                if (true === $xfCatHandler->delete($obj)) {
                    // Delete comments
                    xoops_comment_delete($xfHelper->getModule()->getVar('mid'), $catId);
                    $xfHelper->redirect('admin/category.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
                    // Delete permissions
                    $permHelper = new Xmf\Module\Helper\Permission();
                    $permHelper->deletePermissionForItem('viewcat', $catId);
                }
            }
            $xfCatHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CAT);
        } else {
            $adminObject->displayNavigation('category.php');
            xoops_confirm(array('op' => 'delete', 'category_id' => $catId, 'ok' => XoopsfaqConstants::CONFIRM_OK), 'category.php', _AM_XOOPSFAQ_RUSURE_CAT);
        }
        break;

    case 'save':
        if ( ($GLOBALS['xoopsSecurity'] instanceof XoopsSecurity) ) {
            if ( !$GLOBALS['xoopsSecurity']->check() ) {
                // failed xoops security check
                $xfHelper->redirect('admin/index.php', 3, $GLOBALS['xoopsSecurity']->getErrors(true));
            }
        } else {
            $xfHelper->redirect('admin/index.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _MD_XOOPSFAQ_INVALID_SECURITY_TOKEN);
        }

        $catId = Request::getInt('category_id', XoopsfaqConstants::DEFAULT_CATEGORY, 'POST');
        $obj   = $xfCatHandler->get($catId); // creates category if catId = 0, else gets requested category
        if ($obj instanceof XoopsfaqCategory) {
            $obj->setVar('category_title', Request::getString('category_title', ''));
            $obj->setVar('category_order', Request::getInt('category_order', XoopsfaqConstants::DEFAULT_ORDER));
            if ($savedId = $xfCatHandler->insert($obj)) {
                // Save group permissions
                $permHelper = new Xmf\Module\Helper\Permission();
                $name       = $permHelper->defaultFieldName('viewcat', $catId);
                $groups     = Xmf\Request::getArray($name, array(), 'POST');
                $permHelper->savePermissionForItem('viewcat', $savedId, $groups);
                $xfHelper->redirect('admin/category.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
            }
        }
        $xfCatHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_ADD_CAT);
        break;

    case 'default':
    default:
        $adminObject->displayNavigation('category.php');
        $adminObject->addItemButton(_XO_XOOPSFAQ_ADDCAT, 'category.php?op=edit', 'add' , '');
        $adminObject->displayButton('left');
        $xfCatHandler->displayAdminListing('order');
        break;
}
include_once __DIR__ . '/admin_footer.php';
