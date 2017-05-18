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
 * Description: RSS Definition file
 *
 * @package   module\xoopsfaq\rss
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see Xmf\Request
 * @see Xmf\Module\Helper
 */
function xoopsfaq_rss($max = 10)
{
    $moduleDirName = basename(dirname(__DIR__));
    xoops_load('constants', $moduleDirName);

    $xfHelper      = Xmf\Module\Helper::getHelper($moduleDirName);
    $xfCatHandler  = $xfHelper->getHandler('category');
    $xfFaqHandler  = $xfHelper->getHandler('contents');
    $catId         = Xmf\Request::getInt('categoryid', XoopsfaqConstants::DEFAULT_CATEGORY, 'GET');
    if ($catId > XoopsfaqConstants::DEFAULT_CATEGORY) {
        $categoryObj = $xfCatHandler->get($catId);
        $cat_title   = $categoryObj->getVar('category_title');
        unset($xfCatHandler, $categoryObj);
    } else {
        $cat_title = '';
    }

    $max = ((int)$max > 0) ? (int)$max : 10;
    $criteria = new CriteriaCompo();
    $criteria->setLimit($max);
    $criteria->add(new Criteria('contents_active', XoopsfaqConstants::ACTIVE, '='));
    $criteria->add(new Criteria('contents_publish', XoopsfaqConstants::NOT_PUBLISHED, '>'));
    $criteria->add(new Criteria('contents_publish', time(), '<='));
    if ($catId > XoopsfaqConstants::DEFAULT_CATEGORY) {
        $criteria->add(new Criteria('contents_cid', $catId, '='));
    }
    $contentObjs = $xfFaqHandler->getAll($criteria);

    $retVal = array();

    foreach ($contentObjs as $contentObj) {
        $retVal[] = array ('image' => '',
                           'title' => $contentObj->getVar('contents_title'),
                            'link' => $contentObj->getVar('contents_contents'),
                            'time' => $contentObj->getVar('contents_publish'),
                            'desc' => '',
                        'category' => $cat_title
        );
    }
    unset($xfFaqHandler, $contentObjs);
    return $retVal;
}
