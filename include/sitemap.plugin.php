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
 * Module: XoopsFaq
 *
 * Module Configuration file
 *
 * @author          ZySpec
 * @author          XOOPS Module Development Team
 * @copyright       https://xoops.org 2001-2017 &copy; XOOPS Project
 * @license         https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since           1.2.5
 */

use XoopsModules\Xoopsfaq;
use XoopsModules\Xoopsfaq\{
    Helper
};

/**
 * @return array
 */
function b_sitemap_xoopsfaq()
{
    /** @var Xoopsfaq\CategoryHandler $categoryHandler */
    /** @var Xoopsfaq\Helper $helper */
    $myts = \MyTextSanitizer::getInstance();

    $moduleDirName   = \basename(\dirname(__DIR__));
    $helper          = Helper::getInstance();
    $categoryHandler = $helper->getHandler('Category');
    $catList         = $categoryHandler->getList();

    $retVal = [];
    foreach ($catList as $id => $title) {
        $retVal['parent'][] = [
            'id'    => $id,
            'title' => htmlspecialchars($title, ENT_QUOTES | ENT_HTML5),
            'url'   => $helper->url('index.php?cat_id=' . $id),
        ];
    }

    return $retVal;
}
