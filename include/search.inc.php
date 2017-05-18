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
 * Description: Search function for XOOPS FAQ Module
 *
 * @package   module\xoopsfaq\search
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see Xmf\Module\Helper
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * xoopsfaq_search()
 *
 * @param mixed $queryarray
 * @param mixed $andor
 * @param mixed $limit
 * @param mixed $offset
 * @param mixed $userid
 *
 * @return array results of search
 *
 * @see Xmf\Module\Helper
 */
function xoopsfaq_search($queryarray, $andor, $limit, $offset, $userid) {
    $ret = array();
    if (0 != $userid) {
        return $ret;
    }

    $moduleDirName = basename(dirname(__DIR__)) ;
    xoops_load('constants', $moduleDirName);

    $xfHelper = Xmf\Module\Helper::getHelper($moduleDirName);

    // Find the search term in the Category
    // Find the search term in the FAQ
    $xfFaqHandler  = $xfHelper->getHandler('category');
    $contentFields = array('category_id', 'category_title');
    $criteria      = new CriteriaCompo();
    $criteria->setSort('category_title');
    $criteria->order = 'ASC';
    $criteria->setLimit((int)$limit);
    $criteria->setStart((int)$offset);

    if ((is_array($queryarray)) && !empty($queryarray)) {
        $criteria->add(new Criteria('category_title', '%' . $queryarray[0] . '%', 'LIKE'));
        array_shift($queryarray); //get rid of first element
        foreach ($queryarray as $query) {
            $criteria->add(new Criteria('category_title', '%' . $query . '%', 'LIKE'), $andor);
        }
    }
    $catArray   = $xfFaqHandler->getAll($criteria, $contentFields, false);
    $catCount   = !empty($catArray) ? count($catArray) : 0;
    $totalLimit = (int)$limit - $catCount;
    foreach ($catArray as $cId => $cat) {
        $ret[] = array ('image' => 'assets/images/folder.png',
                         'link' => $xfHelper->url('index.php?cat_id=' . $cId),
                        'title' => $cat['category_title'],
        );
    }
    unset($catArray);


    // Find the search term in the FAQ
    $xfFaqHandler  = $xfHelper->getHandler('contents');
    $contentFields = array('contents_id', 'contents_cid', 'contents_title', 'contents_contents', 'contents_publish');
    $criteria      = new CriteriaCompo();
    $criteria->add(new Criteria('contents_active', XoopsfaqConstants::ACTIVE, '='));
    $criteria->setSort('contents_id');
    $criteria->order = 'DESC';
    $criteria->setLimit((int)$totalLimit);
    $criteria->setStart((int)$offset);

    if ((is_array($queryarray)) && !empty($queryarray)) {
        $criteria->add(new Criteria('contents_title', '%' . $queryarray[0] . '%', 'LIKE'));
        $criteria->add(new Criteria('contents_contents', '%' . $queryarray[0] . '%', 'LIKE'), 'OR');
        array_shift($queryarray); //get rid of first element

        foreach ($queryarray as $query) {
            $criteria->add(new Criteria('contents_title', '%' . $query . '%', 'LIKE'), $andor);
            $criteria->add(new Criteria('contents_contents', '%' . $query . '%', 'LIKE'), 'OR');
        }
    }
    $contentArray = $xfFaqHandler->getAll($criteria, $contentFields, false);
    foreach ($contentArray as $content) {
        $ret[] = array ('image' => 'assets/images/question2.gif',
                         'link' => $xfHelper->url('index.php?cat_id=' . $content['contents_cid'] . '#q' . $content['contents_id']),
                        'title' => $content['contents_title'],
                         'time' => $content['contents_publish'],
        );
    }
    unset($contentArray);
    return $ret;
}
