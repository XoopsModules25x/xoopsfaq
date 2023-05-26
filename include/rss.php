<?php declare(strict_types=1);
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
 * @param int $max
 * @return array
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see       Xmf\Request
 * @see       \XoopsModules\Xoopsfaq\Helper
 * @author    XOOPS Module Development Team
 */

use XoopsModules\Xoopsfaq\{
    Constants,
    CategoryHandler,
    ContentsHandler,
    Helper
};

/**
 * @param int $max
 * @return array
 */
function xoopsfaq_rss(int $max = 10)
{
    /** @var CategoryHandler $categoryHandler */
    /** @var ContentsHandler $contentsHandler */
    /** @var Helper $helper */
    $helper          = Helper::getInstance();
    $categoryHandler = $helper->getHandler('Category');
    $contentsHandler = $helper->getHandler('Contents');
    $catId           = Xmf\Request::getInt('categoryid', Constants::DEFAULT_CATEGORY, 'GET');
    $cat_title       = '';
    if ($catId > Constants::DEFAULT_CATEGORY) {
        $categoryObj = $categoryHandler->get($catId);
        if ($categoryObj) {
            $cat_title = $categoryObj->getVar('category_title');
            unset($categoryHandler, $categoryObj);
        }
    }

    $max      = ($max > 0) ? $max : 10;
    $criteria = new \CriteriaCompo();
    $criteria->setLimit($max);
    $criteria->add(new \Criteria('contents_active', Constants::ACTIVE, '='));
    $criteria->add(new \Criteria('contents_publish', Constants::NOT_PUBLISHED, '>'));
    $criteria->add(new \Criteria('contents_publish', time(), '<='));
    if ($catId > Constants::DEFAULT_CATEGORY) {
        $criteria->add(new \Criteria('contents_cid', $catId, '='));
    }
    $contentObjs = $contentsHandler->getAll($criteria);

    $retVal = [];

    /** @var XoopsObject $contentObj */
    foreach ($contentObjs as $contentObj) {
        $retVal[] = [
            'image'    => '',
            'title'    => $contentObj->getVar('contents_title'),
            'link'     => $contentObj->getVar('contents_contents'),
            'time'     => $contentObj->getVar('contents_publish'),
            'desc'     => '',
            'category' => $cat_title,
        ];
    }
    unset($contentsHandler, $contentObjs);

    return $retVal;
}
