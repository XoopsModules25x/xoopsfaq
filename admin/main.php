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
 * Admin Main Process File for Xoops FAQ Admin
 *
 * @package   module\xoopsfaq\admin
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see       Xmf\Request
 * @see       Xmf\Module\Admin
 */

use Xmf\Request;
use XoopsModules\Xoopsfaq;

require __DIR__ . '/admin_header.php';
xoops_cp_header();

/** @var Xoopsfaq\CategoryHandler $categoryHandler */
/** @var Xoopsfaq\ContentsHandler $contentsHandler */
/** @var Xoopsfaq\Helper $helper */

$contentsHandler = $helper->getHandler('Contents');
$adminObject     = Xmf\Module\Admin::getInstance();

$op = Request::getCmd('op', 'default');
switch ($op) {
    case 'edit':
        $faqId = Request::getInt('contents_id', Xoopsfaq\Constants::INVALID_FAQ_ID);
        $obj   = $contentsHandler->get($faqId); // creates obj if faqId is 0, else gets it
        if ($obj instanceof Xoopsfaq\Contents) {
            $adminObject->displayNavigation(basename(__FILE__));
            $obj->displayForm();
        } else {
            $contentsHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_EDIT_CAT);
        }
        break;

    case 'delete':
        $ok    = Request::getInt('ok', Xoopsfaq\Constants::CONFIRM_NOT_OK);
        $faqId = Request::getInt('contents_id', Xoopsfaq\Constants::INVALID_FAQ_ID);
        if (Xoopsfaq\Constants::CONFIRM_OK === (int)$ok) {
            $obj = $contentsHandler->get($faqId);
            if ($obj instanceof Xoopsfaq\Contents) {
                // Delete this FAQ
                if (true === $contentsHandler->delete($obj)) {
                    // Delete comments
                    xoops_comment_delete($helper->getModule()->getVar('mid'), $faqId);
                    $helper->redirect('admin/main.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
                }
            }
            $contentsHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CONTENTS);
        } else {
            $adminObject->displayNavigation(basename(__FILE__));
            xoops_confirm(['op' => 'delete', 'contents_id' => $faqId, 'ok' => Xoopsfaq\Constants::CONFIRM_OK], basename(__FILE__), _AM_XOOPSFAQ_RUSURE_CONTENTS);
        }
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/main.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, $GLOBALS['xoopsSecurity']->getErrors(true));
        }
        $faqId = Request::getInt('contents_id', Xoopsfaq\Constants::INVALID_FAQ_ID);
        $obj   = (Xoopsfaq\Constants::INVALID_FAQ_ID == $faqId) ? $contentsHandler->create() : $contentsHandler->get($faqId);
        // @todo - change MASK_ALLOW_RAW to MASK_ALLOW_HTML once module 'requires' XOOPS 2.5.9.1 FINAL with bug fixed
        $contents_contents = Request::getString('contents_contents', '', 'POST', Request::MASK_ALLOW_RAW);
        if (is_object($obj) && $obj instanceof Xoopsfaq\Contents) {
            $contents_publish = strtotime(Request::getString('contents_publish', time(), 'POST'));
            $obj->setVars(
                [
                    'contents_cid'      => Request::getInt('contents_cid', Xoopsfaq\Constants::DEFAULT_CATEGORY, 'POST'),
                    'contents_title'    => Request::getString('contents_title', '', 'POST'),
                    'contents_contents' => $contents_contents,
                    'contents_weight'   => Request::getInt('contents_weight', Xoopsfaq\Constants::DEFAULT_WEIGHT, 'POST'),
                    'contents_active'   => Request::getInt('contents_active', Xoopsfaq\Constants::ACTIVE, 'POST'),
                    'contents_publish'  => $contents_publish,
                    'dohtml'            => isset($_POST['dohtml']) ? Xoopsfaq\Constants::SET : Xoopsfaq\Constants::NOTSET,
                    'dosmiley'          => isset($_POST['dosmiley']) ? Xoopsfaq\Constants::SET : Xoopsfaq\Constants::NOTSET,
                    'doxcode'           => isset($_POST['doxcode']) ? Xoopsfaq\Constants::SET : Xoopsfaq\Constants::NOTSET,
                    'doimage'           => isset($_POST['doimage']) ? Xoopsfaq\Constants::SET : Xoopsfaq\Constants::NOTSET,
                    'dobr'              => isset($_POST['dobr']) ? Xoopsfaq\Constants::SET : Xoopsfaq\Constants::NOTSET,
                ]
            );
            $ret = $contentsHandler->insert($obj, true);
            if ($ret) {
                $helper->redirect('admin/main.php', Xoopsfaq\Constants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
            }
            $contentsHandler->displayError($ret);
        }
        break;

    case 'default':
    default:
        $categoryHandler = $helper->getHandler('Category');
        $catCount        = $categoryHandler->getCount();
        $adminObject->displayNavigation(basename(__FILE__));
        if (!empty($catCount)) {
            $adminObject->addItemButton(_AM_XOOPSFAQ_CREATE_NEW, basename(__FILE__) . '?op=edit', 'add', '');
            $adminObject->displayButton('left');
        }
        $contentsHandler->displayAdminListing('weight');
        break;
}
require_once __DIR__ . '/admin_footer.php';
