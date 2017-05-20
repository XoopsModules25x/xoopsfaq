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
 * Recent Term Block file
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
 * Display the most recent FAQs added
 *
 * @param array $options for display parameters
 *              [0] = num of FAQs to show
 *              [1] = num chars in answer(s) to show
 *              [2] = display date added
 *              [3] = FAQ categories to use
 *
 * @return array contains recent FAQ(s) parameters
 */
function b_xoopsfaq_recent_show($options)
{
    $moduleDirName = basename(dirname(__DIR__));
    xoops_load('constants', $moduleDirName);

    $myts         = MyTextSanitizer::getInstance();

    /** @var XoopsfaqCategoryHandler $xfCatHandler */
    /** @var XoopsfaqContentsHandler $xfFaqHandler */
    /** @var Xmf\Module\Helper\GenericHelper $xfHelper */
    $xfHelper     = Xmf\Module\Helper::getHelper($moduleDirName);
    $xfFaqHandler = $xfHelper->getHandler('contents');
    $permHelper   = new Xmf\Module\Helper\Permission($moduleDirName);
    $block        = array();

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('contents_active', XoopsfaqConstants::ACTIVE, '='));
    $criteria->setSort('contents_publish DESC, contents_weight');
    $criteria->order = 'ASC';

    $options[3] = isset($options[3]) ? $options[3] : array(0);
    $cTu = $catsToUse = (false === strpos($options[3], ',')) ? (array)$options[3] : explode(',', $options[3]);
    if (in_array(0, $catsToUse) || empty($catsToUse)) {
        // Get a list of all cats
        $xfCatHandler     = $xfHelper->getHandler('category');
        $catListArray     = $xfCatHandler->getList();
        $cTu = $catsToUse = array_keys($catListArray);
    }
    // Remove any cats this user doesn't have rights to view
    foreach ($cTu as $key => $thisCat) {
        if (false === $permHelper->checkPermission('viewcat', $thisCat)) {
            unset($catsToUse[$key]);
        }
    }
    if (!empty($catsToUse)) {
        $criteria->add(new Criteria('contents_cid', '(' . implode(',', $catsToUse) . ')', 'IN'));
    } else {
        return $block;
    }

    $fieldsArray = array('contents_cid', 'contents_title', 'contents_contents');
    $faqObjArray = $xfFaqHandler->getAll($criteria, $fieldsArray);
    $faqCount = is_array($faqObjArray) ? count($faqObjArray) : 0;
    if ($faqCount > 0) {
        $block['title'] = _MB_XOOPSFAQ_RECENT_TITLE;
        $block['show_date'] = (isset($options[2]) && $options[2] > 0) ? 1 : 0;
        foreach ($faqObjArray as $fId => $faqObj) {
            $faqTitle = $myts->displayTarea($faqObj->getVar('contents_title'));
            if (!empty($options[1])) {
                $txtAns   = strip_tags($faqObj->getVar('contents_contents')); // get rid of html for block
                $faqAns   = $myts->displayTarea(xoops_substr($txtAns, 0, $options[1]), 0, 0 ,0 ,0 ,0);
            } else {
                $faqAns   = $myts->displayTarea($faqObj->getVar('contents_contents'),
                            (int)$faqObj->getVar('dohtml'),
                            (int)$faqObj->getVar('dosmiley'),
                            (int)$faqObj->getVar('doxcode'),
                            1,
                            (int)$faqObj->getVar('dobr')
                );
            }
            $block['faq'][] = array('title' => $faqTitle,
                                      'ans' => $faqAns,
                                'published' => $faqObj->getPublished(_SHORTDATESTRING)
            );
        }
    }
    return $block;
}

/**
 * Edit the most recent FAQs block parameters
 *
 * @param array $options for display parameters
 *              [0] = num of FAQs to show
 *              [1] = num chars in answer(s) to show
 *              [2] = display date added
 *              [3] = FAQ categories to use
 *
 * @return string HTML to display to get input from user
 */
function b_xoopsfaq_recent_edit($options)
{
    $moduleDirName = basename(dirname(__DIR__));
    xoops_load('XoopsFormSelect');

    /** @var XoopsfaqCategoryHandler $xfCatHandler */
    /** @var Xmf\Module\Helper\GenericHelper $xfHelper */
    $xfHelper      = Xmf\Module\Helper::getHelper($moduleDirName);
    $xfCatHandler  = $xfHelper->getHandler('category');

    $catList     = $xfCatHandler->getList();
    $optionArray = array_merge(array(0 => _MB_XOOPSFAQ_ALL_CATS), $catList);
    $formSelect  = new XoopsFormSelect('category', 'options[3]', null, 3, true);
    $formSelect->addOptionArray($optionArray);
    $selOptions = (false === strpos($options[3], ',')) ? $options[3] : explode(',', $options[3]);
    $formSelect->setValue($selOptions);
    $selectCat   = $formSelect->render();

    $ychck = (isset($options[2]) && ($options[2] > 0)) ? ' checked' : '';
    $nchck = !empty($ychck) ? '' : ' checked';

    $form = '<div class="line140">'
          . _MB_XOOPSFAQ_NUM_FAQS . '&nbsp;'
          . '<input type="number" name="options[0]" value="' . $options[0] . '" style="width: 5em;" min="0" class="right"><br>'
          . _MB_XOOPSFAQ_CHARS . '&nbsp;<input type="number" name="options[1]" value="' . $options[1] . '" style="width: 5em;" min="0" class="right">&nbsp;'
          . _MB_XOOPSFAQ_LENGTH . '<br>' . _MB_XOOPSFAQ_SHOW_DATE . '&nbsp;'
          . '<label for="r0">' . _NO . '</label>'
          . '<input type="radio" name="options[2]" id="r0" value="0"' . $nchck . '>&nbsp;'
          . '<label for="r1">' . _YES . '</label>'
          . '<input type="radio" name="options[2]" id="r1" value="1"' . $ychck . '>'
          . '<br><br>' . _MB_XOOPSFAQ_ALL_CATS_INTRO . '&nbsp;&nbsp;' . $selectCat
          . '</div>';
    return $form;
}
