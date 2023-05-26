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
 * Admin index file
 *
 * @author    Raul Recio (aka UNFOR)
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see       Xmf\Module\Admin
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Xoopsfaq\{
    CategoryHandler,
    Common,
    Common\TestdataButtons,
    ContentsHandler,
    Constants,
    Helper,
    Utility
};

/** @var Admin $adminObject */
/** @var Helper $helper */
/** @var Utility $utility */
require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

/** @var CategoryHandler $categoryHandler */
/** @var ContentsHandler $contentsHandler */

//-----------------------
$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$contentsHandler = $helper->getHandler('Contents');
$totalFaqs       = $contentsHandler->getCount();

$criteriaPublished = new \CriteriaCompo();
$criteriaPublished->add(new \Criteria('contents_publish', Constants::NOT_PUBLISHED, '>'));
$criteriaPublished->add(new \Criteria('contents_publish', time(), '<='));

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('contents_active', Constants::ACTIVE, '='));
$criteria->add($criteriaPublished);
$totalPublishedFaqs = $contentsHandler->getCount($criteria);

$categoryHandler = $helper->getHandler('Category');
$totalCats       = $categoryHandler->getCount();

$totalNonpublishedFaqs = $totalFaqs - $totalPublishedFaqs;

$adminObject->addInfoBox(_AM_XOOPSFAQ_FAQ_CONF);
$adminObject->addInfoBoxLine(sprintf('<span class="infolabel">' . _AM_XOOPSFAQ_TOTAL_CATEGORIES . '</span>', '<span class="infotext green bold">' . $totalCats . '</span>'));
$adminObject->addInfoBoxLine(sprintf('<span class="infolabel">' . _AM_XOOPSFAQ_TOTAL_PUBLISHED . '</span>', '<span class="infotext green bold">' . $totalPublishedFaqs . '</span>'));
$adminObject->addInfoBoxLine(sprintf('<span class="infolabel">' . _AM_XOOPSFAQ_TOTAL_INACTIVE . '</span>', '<span class="infotext red bold">' . $totalNonpublishedFaqs . '</span>'));
$adminObject->addInfoBoxLine(sprintf('<span class="infolabel">' . _AM_XOOPSFAQ_TOTAL_FAQS . '</span>', '<span class="infotext green bold">' . $totalFaqs . '</span>'));

$adminObject->displayNavigation(basename(__FILE__));

//check for latest release
//$newRelease = $utility->checkVerModule($helper);
//if (null !== $newRelease) {
//    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
//}

//------------- Test Data Buttons ----------------------------
if ($helper->getConfig('displaySampleButton')) {
    TestdataButtons::loadButtonConfig($adminObject);
    $adminObject->displayButton('left', '');
}
$op = Request::getString('op', 0, 'GET');
switch ($op) {
    case 'hide_buttons':
        TestdataButtons::hideButtons();
        break;
    case 'show_buttons':
        TestdataButtons::showButtons();
        break;
}
//------------- End Test Data Buttons ----------------------------

$adminObject->displayIndex();
echo $utility::getServerStats();

//codeDump(__FILE__);
require_once __DIR__ . '/admin_footer.php';
