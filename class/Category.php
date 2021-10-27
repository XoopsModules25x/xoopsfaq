<?php

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
 * @package   module\xoopsfaq\class
 * @author    John Neill
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since     ::   1.23
 *
 */

use Xmf\Module\Helper\Permission;
use XoopsFormButtonTray;
use XoopsFormHidden;
use XoopsFormText;
use XoopsModules\Xoopsfaq;
use XoopsObject;
use XoopsThemeForm;


/**
 * Category
 *
 * Class used to handle all of the CRUD actions for FAQ categories
 *
 * @author   ::    John Neill
 * @copyright:: Copyright (c) 2009
 */
class Category extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('category_id', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('category_title', \XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('category_order', \XOBJ_DTYPE_INT, 0, false);
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
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $permHelper = new Permission();
        \xoops_load('constants', \basename(\dirname(__DIR__)));

        $caption = ($this->isNew()) ? \_AM_XOOPSFAQ_CREATE_NEW : \sprintf(\_AM_XOOPSFAQ_MODIFY_ITEM, $this->getVar('category_title'));

        $form = new XoopsThemeForm($caption, 'content', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('category_id', $this->getVar('category_id')));
        // title
        $category_title = new XoopsFormText(\_AM_XOOPSFAQ_E_CATEGORY_TITLE, 'category_title', 50, 150, $this->getVar('category_title', 'e'));
        $category_title->setDescription(\_AM_XOOPSFAQ_E_CATEGORY_TITLE_DESC);
        $form->addElement($category_title, true);
        // order
        $category_order = new XoopsFormText(\_AM_XOOPSFAQ_E_CATEGORY_ORDER, 'category_order', 5, 5, $this->getVar('category_order', 'e'));
        $category_order->setDescription(\_AM_XOOPSFAQ_E_CATEGORY_ORDER_DESC);
        $form->addElement($category_order, false);
        $form->addElement($permHelper->getGroupSelectFormForItem('viewcat', $this->getVar('category_id'), \_AM_XOOPSFAQ_CATEGORY_GROUP_PERMS, '', Constants::INCLUDE_ANNON));
        $form->addElement(new XoopsFormButtonTray('category_form', _SUBMIT, 'submit'));

        return $form->render();
    }
}
