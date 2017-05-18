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
 * Display/Edit Category Block
 *
 * @package   module\xoopsfaq\blocks
 * @author    ZySpec
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since::   1.25
 *
 * @see Xmf\Module\Helper
 * @see MyTextSanitizer
 */

/**
 * Show FAQ Categories Block
 *
 * Display the most recent FAQs added
 *
 * @param array $options for display parameters
 *              [0] = only show cats with active FAQs
 *
 * @return array $block containing category titles and links
 */
function b_xoopsfaq_category_show($options)
{
    $moduleDirName = basename(dirname(__DIR__));
    xoops_load('constants', $moduleDirName);

    $myts         = MyTextSanitizer::getInstance();

    /* @var $xfCatHandler XoopsfaqCategoryHandler */
    /* @var $xfFaqHandler XoopsfaqContentsHandler */
    /* @var $xfHelper Xmf\Module\Helper\GenericHelper */
    $xfHelper     = Xmf\Module\Helper::getHelper($moduleDirName);
    $permHelper   = new Xmf\Module\Helper\Permission($moduleDirName);
    $xfCatHandler = $xfHelper->getHandler('category');
    $block        = array();

    // Get a list of all cats this user can access
    $catListArray     = $xfCatHandler->getList();
    $cTu = $catsToUse = array_keys($catListArray);
    // Remove any cats this user doesn't have rights to view
    foreach ($cTu as $key => $thisCat) {
        if (false === $permHelper->checkPermission('viewcat', $thisCat)) {
            unset($catsToUse[$key]);
        }
    }
    // catsToUse contains all categories user has rights to view

    $criteria = new CriteriaCompo();
    $criteria->setSort('category_order ASC, category_title');
    $criteria->order = 'ASC';
    if (!isset($options[0]) || 0 === (int)$options[0]) { // only show cats with FAQs
        $xfFaqHandler = $xfHelper->getHandler('contents');
        $faqCriteria = new CriteriaCompo(new Criteria('contents_active', XoopsfaqConstants::ACTIVE));
        $faqCriteria->setGroupBy('contents_cid');
        $faqCountArray = $xfFaqHandler->getCategoriesIdsWithContent();
        if (is_array($faqCountArray) && !empty($faqCountArray)) {
            $catsToShow = array_intersect($catsToUse, array_keys($faqCountArray));
            $criteria->add(new Criteria('category_id', '(' . implode(',', $catsToShow) . ')', 'IN'));
        } else {
            // there are no categories to show
            return $block;
        }
        unset($xfFaqHandler, $faqCriteria, $faqCountArray, $catsToShow);
    } else {
        $criteria->add(new Criteria('category_id', '(' . implode(',', $catsToUse) . ')', 'IN'));
    }
    $fieldsArray = array('category_id', 'category_title');
    $catObjArray = $xfCatHandler->getAll($criteria, $fieldsArray);
    $catCount    = is_array($catObjArray) ? count($catObjArray) : 0;
    if ($catCount > 0) {
        $block['title'] = _MB_XOOPSFAQ_CATTITLE;
        foreach ($catObjArray as $cId => $catObj) {
            $block['cat'][] = array('title' => $myts->displayTarea($catObj->getVar('category_title')),
                                     'link' => $xfHelper->url('index.php?cat_id=' . $cId),
            );
        }
    }
    unset($catObjArray, $xfCatHandler);
    return $block;
}

/**
 * Edit Recent FAQ Block preferences
 *
 * @param array $options for display parameters
 *              [0] = only show cats with active FAQs
 *
 * @return string HTML to display to get input from user
 */
function b_xoopsfaq_category_edit($options)
{
    $ychck = (isset($options[0]) && ($options[0] > 0)) ? ' checked' : '';
    $nchck = !empty($ychck) ? '' : ' checked';
    $form = '<div class="line140">' . _MB_XOOPSFAQ_SHOW_EMPTY . '&nbsp;<label for="r0">' . _NO . '</label><input type="radio" name="options[0]" id="r0" value="0"' . $nchck . '>&nbsp;<label for="r1">' . _YES . '</label><input type="radio" name="options[0]" id="r1" value="1"' . $ychck . '></div>' . "\n";
    return $form;
}
