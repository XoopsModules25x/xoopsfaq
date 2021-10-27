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
 * Description: Display user side code, categories, and FAQ answers
 *
 * @package   module\xoopsfaq\frontside
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see       \XoopsModules\Xoopsfaq\Helper
 * @see       Xmf\Request
 */

use Xmf\Module\Helper\Permission;
use Xmf\Request;
use XoopsModules\Xoopsfaq\{
    Category,
    CategoryHandler,
    Constants,
    Helper
};

/** @var Xoopsfaq\CategoryHandler $categoryHandler */
/** @var Xoopsfaq\ContentsHandler $contentsHandler */
/** @var Xoopsfaq\Helper $helper */

require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);

$categoryHandler = $helper->getHandler('Category');
$contentsHandler = $helper->getHandler('Contents');

$helper->loadLanguage('admin');

$catId = Request::getInt('cat_id', Constants::DEFAULT_CATEGORY, 'GET');
if ($catId > Constants::DEFAULT_CATEGORY) {
    // Check to see if user has permission to view
    $permHelper = new Permission($moduleDirName);
    $permHelper->checkPermissionRedirect('viewcat', $catId, 'index.php', Constants::REDIRECT_DELAY_MEDIUM, _NOPERM);

    // Prepare the theme/template
    $GLOBALS['xoopsOption']['template_main'] = 'xoopsfaq_category.tpl';
    require_once $GLOBALS['xoops']->path('header.php');

    // Load jscript for accordian effect
    $GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/style.css'));
    $GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/jquery-ui.min.css'));
    $GLOBALS['xoTheme']->addStylesheet($helper->url('assets/css/jquery-ui.structure.min.css'));
    $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
    $GLOBALS['xoTheme']->addScript($helper->url('assets/js/jquery-ui.min.js')); // includes core, widget, accordion, base effects

    // Assign Cat image url & empty questions to template
    $GLOBALS['xoopsTpl']->assign('cat_image_url', Xmf\Module\Admin::iconUrl('topic.png', '16'));
    $GLOBALS['xoopsTpl']->assign('questions', []);

    // Display FAQs in a specific category
    $catObj = $categoryHandler->get($catId);
    if (!empty($catObj) && !$catObj->isNew()) {
        Xmf\Metagen::assignTitle($catObj->getVar('category_title'));
        $GLOBALS['xoopsTpl']->assign('category_name', $catObj->getVar('category_title'));

        // Get a list of the FAQs in this category
        $contentsObj = $contentsHandler->getPublished($catId);
        if (isset($contentsObj['count']) && (int)$contentsObj['count'] > 0) {
            $bodyWords = '';
            /** @var XoopsObject $obj */
            foreach ($contentsObj['list'] as $obj) {
                $question = [
                    'id'     => $obj->getVar('contents_id'),
                    'title'  => $obj->getVar('contents_title'),
                    'answer' => $obj->getVar('contents_contents'),
                ];
                $GLOBALS['xoopsTpl']->append('questions', $question);
                $bodyWords .= ' ' . $obj->getVar('contents_title') . ' ' . $obj->getVar('contents_contents');
            }
            $keywords = Xmf\Metagen::generateKeywords($bodyWords);
            Xmf\Metagen::assignKeywords($keywords);
        }
        require $GLOBALS['xoops']->path('include/comment_view.php');
    } else {
        // Passed an invalid cat_id so exit
        $helper->redirect('index.php', Constants::REDIRECT_DELAY_MEDIUM, _NOPERM);
    }
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'xoopsfaq_index.tpl';
    require_once $GLOBALS['xoops']->path('header.php');

    // Assign Cat image url to template
    $GLOBALS['xoopsTpl']->assign('cat_image_url', Xmf\Module\Admin::iconUrl('topic.png', '16'));
    // Setup the page title
    Xmf\Metagen::assignTitle(_MD_XOOPSFAQ_CAT_LISTING);

    // Display Categories & the list of FAQs
    // @todo make the number of questions/answers to display a module config option
    $catCriteria = new \CriteriaCompo();
    $catCriteria->setSort('category_order');
    $catCriteria->order = 'ASC';
    $objects            = $categoryHandler->getObj($catCriteria);
    if (isset($objects['count']) && ($objects['count'] > 0)) {
        $permHelper = new Permission($moduleDirName);
        $bodyWords  = '';
        /** @var \XoopsObject $object */
        foreach ($objects['list'] as $object) {
            // only list categories and/or FAQs if user has rights
            if (false !== $permHelper->checkPermission('viewcat', $object->getVar('category_id'))) {
                $category    = [
                    'id'   => $object->getVar('category_id'),
                    'name' => $object->getVar('category_title'),
                ];
                $bodyWords   .= ' ' . $object->getVar('category_title');
                $contentsObj = $contentsHandler->getPublished($object->getVar('category_id'));
                if ($contentsObj['count']) {
                    $category['questions'] = [];
                    /** @var XoopsObject $content */
                    foreach ($contentsObj['list'] as $content) {
                        $category['questions'][] = [
                            'link'  => $content->getVar('contents_id'),
                            'title' => $content->getVar('contents_title'),
                        ];
                        $bodyWords               .= ' ' . $content->getVar('contents_title');
                    }
                }
                $GLOBALS['xoopsTpl']->append_by_ref('categories', $category);
                unset($category);
            }
        }
        $keywords = Xmf\Metagen::generateKeywords($bodyWords);
        Xmf\Metagen::assignKeywords($keywords);
    }
}
require __DIR__ . '/footer.php';
