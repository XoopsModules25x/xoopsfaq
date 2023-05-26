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
 * XOOPS FAQ Category & Category Handler Class Definitions
 *
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 */

use Xmf\Module\Admin;

/**
 * CategoryHandler
 *
 * @author   ::    John Neill
 * @copyright:: Copyright (c) 2009
 */
final class CategoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'xoopsfaq_categories', Category::class, 'category_id', 'category_title');
    }

    /**
     * CategoryHandler::getObj()
     *
     * @param \CriteriaElement|string|null $sort order ('id', order', or 'title') - default: id
     *
     * @return array Category
     */
    public function getObj($sort = null): array
    {
        $obj = [];
        if ((null !== $sort) && (!$sort instanceof \CriteriaElement)) {
            $criteria        = new \CriteriaCompo();
            $obj['count']    = $this->getCount($criteria);
            $criteria->order = 'ASC';
            $sort            = \in_array(mb_strtolower($sort), ['id', 'order', 'title'], true) ? 'category_' . \mb_strtolower($sort) : 'category_id';
            $criteria->setSort($sort);
            $criteria->setStart(0);
            $criteria->setLimit(0);
        } else {
            $criteria = $sort;
        }
        $obj['list']  = $this->getObjects($criteria, false);
        $obj['count'] = (false != $obj['list']) ? \count($obj['list']) : 0;

        return $obj;
    }

    /**
     * CategoryHandler::displayAdminListing()
     *
     * @param string|null $sort
     */
    public function displayAdminListing(?string $sort = null): void
    {
        $sort ??= 'id';
        echo $this->renderAdminListing($sort);
    }

    /**
     * Display a Category listing for administrators
     *
     * @param string|null $sort listing order
     *
     * @return string HTML listing for Admin
     */
    public function renderAdminListing(?string $sort = null): string
    {
        $sort ??= 'id';
        //        if (!\class_exists('Xoopsfaq\Utility')) {
        //            \xoops_load('utility', \basename(\dirname(__DIR__)));
        //        }

        /** @var array $objects */
        $objects = $this->getObj($sort);

        $buttons = ['edit', 'delete'];

        $ret = '<table class="outer width100 bnone pad3 marg5">'
               . '  <thead>'
               . '  <tr class="xoopsCenter">'
               . '    <th class="width5">'
               . \_AM_XOOPSFAQ_CATEGORY_ORDER
               . '</th>'
               . '    <th class="width5">'
               . \_AM_XOOPSFAQ_CATEGORY_ID
               . '</th>'
               . '    <th class="txtleft">'
               . \_AM_XOOPSFAQ_CATEGORY_TITLE
               . '</th>'
               . '    <th class="width20">'
               . \_AM_XOOPSFAQ_ACTIONS
               . '</th>'
               . '  </tr>'
               . '  </thead>'
               . '  <tbody>';
        if ($objects['count'] > 0) {
            /** @var XoopsObject $object */
            foreach ($objects['list'] as $object) {
                $ret .= '  <tr class="xoopsCenter">'
                        . '    <td class="even txtcenter">'
                        . $object->getVar('category_order')
                        . '</td>'
                        . '    <td class="even txtcenter">'
                        . $object->getVar('category_id')
                        . '</td>'
                        . '    <td class="even txtleft">'
                        . $object->getVar('category_title')
                        . '</td>'
                        . '    <td class="even txtcenter">';
                $ret .= Utility::renderIconLinks($buttons, 'category_id', $object->getVar('category_id'));
                $ret .= '    </td>' . '  </tr>';
            }
        } else {
            $ret .= '  <tr class="txtcenter"><td colspan="4" class="even">' . \_AM_XOOPSFAQ_NOLISTING . '</td></tr>';
        }
        $ret .= '  </tbody>' . '</table>';

        return $ret;
    }

    /**
     * Display the class error(s) encountered
     *
     * @param array|string $errors the error(s) to be displayed
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
