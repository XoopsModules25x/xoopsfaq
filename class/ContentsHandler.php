<?php declare(strict_types=1);

namespace XoopsModules\Xoopsfaq;

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
 * Contents (FAQ) and Handler Class Definitions
 *
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 */

use Criteria;
use CriteriaCompo;
use CriteriaElement;
use Xmf\Module\Admin;
use XoopsDatabase;
use XoopsPersistableObjectHandler;

/**
 * ContentsHandler
 *
 * @author   ::    John Neill
 * @copyright:: Copyright (c) 2009
 * @access::    public
 */
final class ContentsHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct(XoopsDatabase $db = null)
    {
        parent::__construct($db, 'xoopsfaq_contents', Contents::class, 'contents_id', 'contents_title');
    }

    /**
     * ContentsHandler::getObj()
     *
     * @param \CriteriaElement|string|null $sort sort order ('id', 'cid', 'title', 'publish', or 'weight') default: 'id'
     *
     * @return array Contents object | false on failure
     */
    public function getObj($sort = null)
    {
        $sort ??= 'id';
        $obj = [];
        if ($sort instanceof CriteriaElement) {
            $criteria = $sort;
        } else {
            $criteria = new CriteriaCompo();
            $sort     = \in_array(mb_strtolower($sort), ['id', 'cid', 'title', 'publish', 'weight'], true) ? 'contents_' . \mb_strtolower($sort) : 'contents_id';
            $criteria->setSort($sort);
            $criteria->order = 'ASC';
            $criteria->setStart(0);
            $criteria->setLimit(0);
        }
        $obj['list']  = $this->getObjects($criteria, false);
        $obj['count'] = (false !== $obj['list']) ? \count($obj['list']) : 0;

        return $obj;
    }

    /**
     * ContentsHandler::getPublished()
     *
     * @param string|null $id
     * @return array array of XoopsfaqContent objects
     */
    public function getPublished(?string $id = null)
    {
        $id ??= '';
        \xoops_load('constants', \basename(\dirname(__DIR__)));

        $obj               = [];
        $criteriaPublished = new CriteriaCompo();
        $criteriaPublished->add(new Criteria('contents_publish', Constants::NOT_PUBLISHED, '>'));
        $criteriaPublished->add(new Criteria('contents_publish', \time(), '<='));

        $criteria = new CriteriaCompo(new Criteria('contents_active', Constants::ACTIVE));
        if (!empty($id)) {
            $criteria->add(new Criteria('contents_cid', $id, '='));
        }
        $criteria->add($criteriaPublished);
        $criteria->order = 'ASC';
        $criteria->setSort('contents_weight');

        $obj['list']  = $this->getObjects($criteria, false);
        $obj['count'] = (false !== $obj['list']) ? \count($obj['list']) : 0;

        return $obj;
    }

    /**
     * Returns category ids of categories that have content
     *
     * @return array contains category ids
     */
    public function getCategoriesIdsWithContent()
    {
        $ret    = [];
        $sql    = 'SELECT contents_cid ';
        $sql    .= 'FROM `' . $this->table . '` ';
        $sql    .= 'WHERE (contents_active =\'' . Constants::ACTIVE . '\') ';
        $sql    .= 'GROUP BY contents_cid';
        $result = $this->db->query($sql);
        if ($this->db->isResultSet($result)) {
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[$myrow['contents_cid']] = $myrow['contents_cid'];
            }
        }

        return $ret;
    }

    /**
     * ContentsHandler::displayAdminListing()
     *
     * @param string|null $sort
     */
    public function displayAdminListing($sort = null): void
    {
        $sort ??= 'id';
        echo $this->renderAdminListing($sort);
    }

    /**
     * ContentsHandler::renderAdminListing()
     *
     * @param string|null $sort
     * @return string html listing of Contents (FAQ) for Admin
     * @see \XoopsModules\Xoopsfaq\Helper
     */
    public function renderAdminListing($sort = null)
    {
        $sort ??= 'id';
        //        if (!\class_exists('Xoopsfaq\Utility')) {
        //            \xoops_load('utility', \basename(\dirname(__DIR__)));
        //        }

        /** @var CategoryHandler $categoryHandler */
        $objects         = $this->getObj($sort);
        $helper          = Helper::getHelper(\basename(\dirname(__DIR__)));
        $categoryHandler = $helper->getHandler('Category');
        $catFields       = ['category_id', 'category_title'];
        $catArray        = $categoryHandler->getAll(null, $catFields, false);

        $buttons = ['edit', 'delete'];

        $ret = '<table class="outer width100 bnone pad3 marg5">'
               . '  <thead>'
               . '  <tr class="center">'
               . '    <th class="width5">'
               . \_AM_XOOPSFAQ_CONTENTS_ID
               . '</th>'
               . '    <th class="width5">'
               . \_AM_XOOPSFAQ_CONTENTS_ACTIVE
               . '</th>'
               . '    <th class="width5">'
               . \_AM_XOOPSFAQ_CONTENTS_WEIGHT
               . '</th>'
               . '    <th class="left">'
               . \_AM_XOOPSFAQ_CONTENTS_TITLE
               . '</th>'
               . '    <th class="left">'
               . \_AM_XOOPSFAQ_CATEGORY_TITLE
               . '</th>'
               . '    <th>'
               . \_AM_XOOPSFAQ_CONTENTS_PUBLISH
               . '</th>'
               . '    <th class="width20">'
               . \_AM_XOOPSFAQ_ACTIONS
               . '</th>'
               . '  </tr>'
               . '  </thead>'
               . '  <tbody>';
        if (is_array($objects) && ($objects['count'] > 0)) {
            $tdClass = 0;
            /** @var \Contents $object */
            foreach ($objects['list'] as $object) {
                $thisCatId        = $object->getVar('contents_cid');
                $thisCatTitle     = $catArray[$thisCatId]['category_title'];
                $thisContentTitle = '<a href="' . $helper->url('index.php?cat_id=' . $thisCatId . '#q' . $object->getVar('contents_id')) . '" title="' . \_AM_XOOPSFAQ_CONTENTS_VIEW . '">' . $object->getVar('contents_title') . '</a>';
                ++$tdClass;
                $dispClass = ($tdClass % 1) ? 'even' : 'odd';
                $ret       .= '  <tr class="center middle">'
                              . '    <td class="'
                              . $dispClass
                              . '">'
                              . $object->getVar('contents_id')
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . '">'
                              . $object->getActiveIcon()
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . '">'
                              . $object->getVar('contents_weight')
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . ' left">'
                              . $thisContentTitle
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . ' left">'
                              . $thisCatTitle
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . '">'
                              . $object->getPublished(_SHORTDATESTRING)
                              . '</td>'
                              . '    <td class="'
                              . $dispClass
                              . '">';
                $ret       .= Utility::renderIconLinks($buttons, 'contents_id', $object->getVar('contents_id')) . '</td>' . '  </tr>';
            }
        } else {
            $ret .= '  <tr class="center"><td colspan="7" class="even">' . \_AM_XOOPSFAQ_NOLISTING . '</td></tr>';
        }
        $ret .= '  </tbody>' . '</table>';

        return $ret;
    }

    /**
     * ContentsHandler::displayError()
     *
     * @param array|string $errors will display a page with the error(s)
     *
     * @see \Xmf\Module\Admin
     */
    public function displayError($errors = ''): void
    {
        if ('' !== $errors) {
            \xoops_cp_header();
            $moduleAdmin = Admin::getInstance();
            $moduleAdmin->displayNavigation(\basename(__FILE__));
            \xoops_error($errors, \_AM_XOOPSFAQ_ERROR_SUB);
            \xoops_cp_footer();
        }
    }
}
