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
 * XOOPS FAQ Category & Category Handler Class Definitions
 *
 * @package   module\xoopsfaq\class
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since::   1.23
 *
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * XoopsfaqCategory
 *
 * Class used to handle all of the CRUD actions for FAQ categories
 *
 * @author::    John Neill
 * @copyright:: Copyright (c) 2009
 */
class XoopsfaqCategory extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('category_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('category_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('category_order', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * Display the category title
     *
     * @return string display the category title (filtered for display)
     */
    public function __toString()
    {
        return $this->getVar('category_title', 's');
    }

    /**
     * Display the category edit form
     *
     * @return void
     */
    public function displayForm()
    {
        echo $this->renderForm();
    }

    /**
     * Render the category edit form
     *
     * @return string HTML entities used to edit the catagory object
     */
    public function renderForm()
    {
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $permHelper = new Xmf\Module\Helper\Permission();
        xoops_load('constants', basename(dirname(__DIR__)));

        $caption = ($this->isNew()) ? _AM_XOOPSFAQ_CREATE_NEW : sprintf(_AM_XOOPSFAQ_MODIFY_ITEM, $this->getVar('category_title'));

        $form = new XoopsThemeForm($caption, 'content', xoops_getenv('PHP_SELF'), 'post', true);
        $form->addElement(new xoopsFormHidden('op', 'save'));
        $form->addElement(new xoopsFormHidden('category_id', $this->getVar('category_id')));
        // title
        $category_title = new XoopsFormText(_AM_XOOPSFAQ_E_CATEGORY_TITLE, 'category_title', 50, 150, $this->getVar('category_title', 'e'));
        $category_title->setDescription(_AM_XOOPSFAQ_E_CATEGORY_TITLE_DESC);
        $form->addElement($category_title, true);
        // order
        $category_order = new XoopsFormText(_AM_XOOPSFAQ_E_CATEGORY_ORDER, 'category_order', 5, 5, $this->getVar('category_order', 'e'));
        $category_order->setDescription(_AM_XOOPSFAQ_E_CATEGORY_ORDER_DESC);
        $form->addElement($category_order, false);
        $form->addElement($permHelper->getGroupSelectFormForItem('viewcat', $this->getVar('category_id'), _AM_XOOPSFAQ_CATEGORY_GROUP_PERMS, '', XoopsfaqConstants::INCLUDE_ANNON));
        $form->addElement(new XoopsFormButtonTray('category_form', _SUBMIT, 'submit'));

        return $form->render();
    }
}

/**
 * XoopsfaqCategoryHandler
 *
 * @package::   xoopsfaq
 * @author::    John Neill
 * @copyright:: Copyright (c) 2009
 */
class XoopsfaqCategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param mixed $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'xoopsfaq_categories', 'XoopsfaqCategory', 'category_id', 'category_title');
    }

    /**
     * XoopsfaqCategoryHandler::getObj()
     *
     * @param string $sort order ('id', order', or 'title') - default: id
     *
     * @return mixed XoopsfaqCategory | false on failure
     */
    public function getObj($sort = 'id')
    {
        #$myts = MyTextSanitizer::getInstance();
        $obj = false;
        if ((null !== $sort) && (!$sort instanceof CriteriaElement)) {
            $criteria = new CriteriaCompo();
            $obj['count'] = $this->getCount($criteria);
            $criteria->order = 'ASC';
            $sort = in_array(mb_strtolower($sort), array('id', 'order', 'title')) ? 'category_' . mb_strtolower($sort) : 'category_id';
            $criteria->setSort($sort);
            $criteria->setStart(0);
            $criteria->setLimit(0);
            $obj['list'] = $this->getObjects($criteria, false);
        } else {
            $criteria = $sort;
            $obj['list'] = $this->getObjects($criteria, false);
            $obj['count'] = count($obj['list']);
        }
        return $obj;
    }

    /**
     * XoopsfaqCategoryHandler::displayAdminListing()
     *
     * @param string $sort
     * @return void
     */
    public function displayAdminListing($sort = 'id')
    {
        echo $this->renderAdminListing($sort);
    }

    /**
     * Display a Category listing for administrators
     *
     * @param string $sort listing order
     *
     * @return string HTML listing for Admin
     */
    public function renderAdminListing($sort = 'id')
    {
        if (!class_exists('XoopsfaqUtility')) {
            xoops_load('utility', basename(dirname(__DIR__)));
        }

        $objects = $this->getObj($sort);

        $buttons = array('edit', 'delete');

        $ret = '<table class="outer width100 bnone pad3 marg5">'
             . '  <thead>'
             . '  <tr class="xoopsCenter">'
             . '    <th class="width5">' . _AM_XOOPSFAQ_CATEGORY_ORDER . '</th>'
             . '    <th class="width5">' . _AM_XOOPSFAQ_CATEGORY_ID . '</th>'
             . '    <th class="txtleft">' . _AM_XOOPSFAQ_CATEGORY_TITLE . '</th>'
             . '    <th class="width20">' . _AM_XOOPSFAQ_ACTIONS . '</th>'
             . '  </tr>'
             . '  </thead>'
             . '  <tbody>';
        if ($objects['count'] > 0) {
            /* @var $object XoopsObject */
            foreach ($objects['list'] as $object) {
                $ret .= '  <tr class="xoopsCenter">'
                      . '    <td class="even txtcenter">' . $object->getVar('category_order') . '</td>'
                      . '    <td class="even txtcenter">' . $object->getVar('category_id') . '</td>'
                      . '    <td class="even txtleft">' . $object->getVar('category_title') . '</td>'
                      . '    <td class="even txtcenter">';
                $ret .= XoopsfaqUtility::renderIconLinks($buttons, 'category_id', $object->getVar('category_id'));
                $ret .= '    </td>'
                      . '  </tr>';
            }
        } else {
            $ret .= '  <tr class="txtcenter"><td colspan="4" class="even">' . _AM_XOOPSFAQ_NOLISTING . '</td></tr>';
        }
        $ret .= '  </tbody>'
//              . '  <tfoot><tr class="txtcenter"><td colspan="4" class="head">&nbsp;</td></tr></tfoot>'
              . '</table>';
        return $ret;
    }

    /**
     * Display the class error(s) encountered
     *
     * @param array|string $errors the error(s) to be displayed
     *
     * @return void
     */
    public function displayError($errors = '')
    {
        if ('' !== $errors) {
            xoops_cp_header();
            $moduleAdmin = Xmf\Module\Admin::getInstance();
            $moduleAdmin->displayNavigation('index.php');
            xoops_error($errors, _AM_XOOPSFAQ_ERROR_SUB);
            xoops_cp_footer();
        }
        return;
    }
}
