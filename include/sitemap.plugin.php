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
 * Module: XoopsFaq
 *
 * Module Configuration file
 *
 * @package         module\xoopsfaq\plugins
 * @author          ZySpec
 * @author          XOOPS Module Development Team
 * @copyright       http://xoops.org 2001-2017 &copy; XOOPS Project
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since           1.2.5
 */
function b_sitemap_xoopsfaq() {

    $myts = MyTextSanitizer::getInstance();

    $moduleDirName = basename(dirname(dirname(__FILE__))) ;
    $xfHelper      = Xmf\Module\Helper::getHelper($moduleDirName);
    $xfCatHandler  = $xfHelper->getHandler('category');
    $catList       = $xfCatHandler->getList();

    $retVal = array() ;
    foreach ($catList as $id=>$title){
        $retVal['parent'][] = array('id' => $id,
                                 'title' => $myts->htmlSpecialChars($title),
                                   'url' => $xfHelper->url('index.php?cat_id=' . $id)
        );
    }
    return $retVal;
}
