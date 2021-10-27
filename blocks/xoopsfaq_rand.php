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
 * Display/Edit Random Term Block file
 *
 * @package   module\xoopsfaq\blocks
 * @author    hsalazar
 * @author    ZySpec
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 *
 * @see       \XoopsModules\Xoopsfaq\Helper
 */

use Xmf\Module\Helper\Permission;
use XoopsModules\Xoopsfaq\{
    Constants,
    Helper
};

/**
 * Display Random FAQ Block
 *
 * @param array $options
 *              [0] - number of chars in title to show (0 = unlimited)
 *              [1] - comma separated list of categories to use/select from
 * @return array contains random FAQ parameters
 */
function b_xoopsfaq_random_show($options)
{
    $moduleDirName = \basename(\dirname(__DIR__));
    $myts          = \MyTextSanitizer::getInstance();

    /** @var Xoopsfaq\CategoryHandler $categoryHandler */
    /** @var Xoopsfaq\ContentsHandler $contentsHandler */
    /** @var Xoopsfaq\Helper $helper */
    $helper          = Helper::getInstance();
    $permHelper      = new Permission($moduleDirName);
    $contentsHandler = $helper->getHandler('Contents');
    $block           = [];

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('contents_active', Constants::ACTIVE, '='));

    // Filter out cats based on group permissions
    $options[1] = $options[1] ?? [0];
    $cTu        = $catsToUse = (false === strpos($options[1], ',')) ? (array)$options[1] : explode(',', $options[1]);
    if (in_array(0, $catsToUse) || empty($catsToUse)) {
        // Get a list of all cats
        $categoryHandler = $helper->getHandler('Category');
        $catListArray    = $categoryHandler->getList();
        $cTu             = $catsToUse = array_keys($catListArray);
    }
    // Remove any cats this user doesn't have rights to view
    foreach ($cTu as $key => $thisCat) {
        if (false === $permHelper->checkPermission('viewcat', $thisCat)) {
            unset($catsToUse[$key]);
        }
    }
    if (!empty($catsToUse)) {
        $criteria->add(new \Criteria('contents_cid', '(' . implode(',', $catsToUse) . ')', 'IN'));
    } else {
        return $block;
    }
    $xpFaqObjArray = $contentsHandler->getAll($criteria);
    $faqCount      = (is_array($xpFaqObjArray) && !empty($xpFaqObjArray)) ? count($xpFaqObjArray) : 0;

    if ($faqCount > 0) {
        $faqNum   = array_rand($xpFaqObjArray, 1);
        $xpFaqObj = $xpFaqObjArray[$faqNum];
        $faq      = $myts->displayTarea($xpFaqObj->getVar('contents_title'));
        $txtAns   = strip_tags($xpFaqObj->getVar('contents_contents')); // get rid of html for block
        $faqAns   = $myts->displayTarea(xoops_substr($txtAns, 0, $options[0]), 0, 0, 0, 0, 0);

        $categoryHandler = $helper->getHandler('Category');
        $catObj          = $categoryHandler->get($xpFaqObj->getVar('contents_cid'));
        $cid             = $catObj->getVar('category_id');
        $block           = [
            'title'    => _MB_XOOPSFAQ_RANDOM_TITLE,
            'faq'      => $faq,
            'faqans'   => $faqAns,
            'morelink' => $helper->url('index.php?cat_id=' . $cid . '#q' . $xpFaqObj->getVar('contents_id')),
            'linktxt'  => _MB_XOOPSFAQ_SEE_MORE,
            'catlink'  => $helper->url('index.php?cat_id=' . $cid),
            'cattxt'   => $catObj->getVar('category_title'),
        ];
        unset($xpFaqObj, $catObj);
    }
    return $block;
}

/**
 * Edit Random FAQ Block preferences
 *
 * @param array $options
 *              [0] - number of chars in title to show (0 = unlimited)
 *              [1] - comma separated list of categories to use/select from
 * @return string HTML entities to display for user input
 */
function b_xoopsfaq_rand_edit($options)
{
    $moduleDirName = \basename(\dirname(__DIR__));
    xoops_load('XoopsFormSelect');

    /** @var Xoopsfaq\CategoryHandler $categoryHandler */
    /** @var Xoopsfaq\Helper $helper */
    $helper          = Helper::getInstance();
    $categoryHandler = $helper->getHandler('Category');

    $catList     = $categoryHandler->getList();
    $optionArray = array_merge([0 => _MB_XOOPSFAQ_ALL_CATS], $catList);
    $formSelect  = new \XoopsFormSelect('category', 'options[1]', null, 3, true);
    $formSelect->addOptionArray($optionArray);
    $selOptions = (false === strpos($options[1], ',')) ? $options[1] : explode(',', $options[1]);
    $formSelect->setValue($selOptions);
    $selectCat = $formSelect->render();

    $form = '<div class="line140">' . _MB_XOOPSFAQ_CHARS . '&nbsp;' . '<input type="number" name="options[0]" value="' . $options[0] . '" style="width: 5em;" min="0" class="right">&nbsp;' . _MB_XOOPSFAQ_LENGTH . '<br><br>' . _MB_XOOPSFAQ_ALL_CATS_INTRO . '&nbsp;&nbsp;' . $selectCat . '</div>';
    return $form;
}
