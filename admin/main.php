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
 * @see Xmf\Request
 * @see Xmf\Module\Admin
 */
use Xmf\Request;
#use Xmf\Module\Admin;

include __DIR__ . '/admin_header.php';
xoops_cp_header();

/** @var XoopsfaqCategoryHandler $xfCatHandler */
/** @var XoopsfaqContentsHandler $xfFaqHandler */
/** @var Xmf\Module\Helper\GenericHelper $xfHelper */

$xfFaqHandler = $xfHelper->getHandler('contents');
$adminObject  = Xmf\Module\Admin::getInstance();

$op = Request::getCmd('op', 'default');
switch ($op) {
    case 'edit':
        $faqId = Request::getInt('contents_id', XoopsfaqConstants::INVALID_FAQ_ID);
        $obj   = $xfFaqHandler->get($faqId); // creates obj if faqId is 0, else gets it
        if ($obj instanceof XoopsfaqContents) {
            $adminObject->displayNavigation(basename(__FILE__));
            $obj->displayForm();
        } else {
            $xfFaqHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_EDIT_CAT);
        }
        break;

    case 'delete':
        $ok    = Request::getInt('ok', XoopsfaqConstants::CONFIRM_NOT_OK);
        $faqId = Request::getInt('contents_id', XoopsfaqConstants::INVALID_FAQ_ID);
        if (XoopsfaqConstants::CONFIRM_OK === (int)$ok) {
            $obj = $xfFaqHandler->get($faqId);
            if ($obj instanceof XoopsfaqContents) {
                // Delete this FAQ
                if (true === $xfFaqHandler->delete($obj)) {
                    // Delete comments
                    xoops_comment_delete($xfHelper->getModule()->getVar('mid'), $faqId);
                    $xfHelper->redirect('admin/main.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
                }
            }
            $xfFaqHandler->displayError(_AM_XOOPSFAQ_ERROR_COULD_NOT_DEL_CONTENTS);
        } else {
            $adminObject->displayNavigation(basename(__FILE__));
            xoops_confirm(array('op' => 'delete', 'contents_id' => $faqId, 'ok' => XoopsfaqConstants::CONFIRM_OK), basename(__FILE__), _AM_XOOPSFAQ_RUSURE_CONTENTS);
        }
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $xfHelper->redirect('admin/main.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, $GLOBALS['xoopsSecurity']->getErrors(true));
        }
        $faqId = Request::getInt('contents_id', XoopsfaqConstants::INVALID_FAQ_ID);
        $obj = (XoopsfaqConstants::INVALID_FAQ_ID == $faqId) ? $xfFaqHandler->create() : $xfFaqHandler->get($faqId);
// @todo - change MASK_ALLOW_RAW to MASK_ALLOW_HTML once module 'requires' XOOPS 2.5.9.1 FINAL with bug fixed
        $contents_contents = Request::getString('contents_contents', '', 'POST', Request::MASK_ALLOW_RAW);
        if (is_object($obj) && $obj instanceof XoopsfaqContents) {
            $contents_publish = strtotime(Request::getString('contents_publish', time(), 'POST'));
            $obj->setVars(array('contents_cid' => Request::getInt('contents_cid', XoopsfaqConstants::DEFAULT_CATEGORY, 'POST'),
                              'contents_title' => Request::getString('contents_title', '', 'POST'),
                           'contents_contents' => $contents_contents,
                             'contents_weight' => Request::getInt('contents_weight', XoopsfaqConstants::DEFAULT_WEIGHT, 'POST'),
                             'contents_active' => Request::getInt('contents_active', XoopsfaqConstants::ACTIVE, 'POST'),
                            'contents_publish' => $contents_publish,
                                      'dohtml' => isset($_POST['dohtml']) ? XoopsfaqConstants::SET : XoopsfaqConstants::NOTSET,
                                    'dosmiley' => isset($_POST['dosmiley']) ? XoopsfaqConstants::SET : XoopsfaqConstants::NOTSET,
                                     'doxcode' => isset($_POST['doxcode']) ? XoopsfaqConstants::SET : XoopsfaqConstants::NOTSET,
                                     'doimage' => isset($_POST['doimage']) ? XoopsfaqConstants::SET : XoopsfaqConstants::NOTSET,
                                        'dobr' => isset($_POST['dobr']) ? XoopsfaqConstants::SET : XoopsfaqConstants::NOTSET)
            );
            $ret = $xfFaqHandler->insert($obj, true);
            if ($ret) {
                $xfHelper->redirect('admin/main.php', XoopsfaqConstants::REDIRECT_DELAY_MEDIUM, _AM_XOOPSFAQ_DBSUCCESS);
            }
            $xfFaqHandler->displayError($ret);
        }
        break;

    case 'default':
    default:
        $xfCatHandler = $xfHelper->getHandler('category');
        $catCount = $xfCatHandler->getCount();
        $adminObject->displayNavigation(basename(__FILE__));
        if (!empty($catCount)) {
            $adminObject->addItemButton(_AM_XOOPSFAQ_CREATE_NEW, basename(__FILE__) . '?op=edit', 'add' , '');
            $adminObject->displayButton('left');
        }
        $xfFaqHandler->displayAdminListing('weight');
        break;
}
include_once __DIR__ . '/admin_footer.php';
