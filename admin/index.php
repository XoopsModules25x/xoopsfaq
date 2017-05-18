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
 * Admin index file
 *
 * @package   module\xoopsfaq\admin
 * @author    Raul Recio (aka UNFOR)
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2017 {@link http://xoops.org XOOPS Project}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 *
 * @see Xmf\Module\Admin
 */

include_once __DIR__ . '/admin_header.php';
xoops_cp_header();

//-----------------------
$xfFaqHandler = $xfHelper->getHandler('contents');
$totalFaqs    = $xfFaqHandler->getCount();

$criteriaPublished = new CriteriaCompo();
$criteriaPublished->add(new Criteria('contents_publish', XoopsfaqConstants::NOT_PUBLISHED, '>'));
$criteriaPublished->add(new Criteria('contents_publish', time(), '<='));

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('contents_active', XoopsfaqConstants::ACTIVE, '='));
$criteria->add($criteriaPublished);
$totalPublishedFaqs = $xfFaqHandler->getCount($criteria);

$xfCatHandler = $xfHelper->getHandler('category');
$totalCats    = $xfCatHandler->getCount();

$totalNonpublishedFaqs = $totalFaqs - $totalPublishedFaqs;

$adminObject->addInfoBox(_MD_XOOPSFAQ_FAQ_CONF);
$adminObject->AddInfoBoxLine(sprintf('<span class="infolabel">' . _MD_XOOPSFAQ_TOTAL_CATEGORIES . '</span>', '<span class="infotext green bold">' . $totalCats . '</span>'));
$adminObject->AddInfoBoxLine(sprintf('<span class="infolabel">' . _MD_XOOPSFAQ_TOTAL_PUBLISHED . '</span>', '<span class="infotext green bold">' . $totalPublishedFaqs . '</span>'));
$adminObject->AddInfoBoxLine(sprintf('<span class="infolabel">' . _MD_XOOPSFAQ_TOTAL_INACTIVE . '</span>', '<span class="infotext red bold">' . $totalNonpublishedFaqs . '</span>'));
$adminObject->AddInfoBoxLine(sprintf('<span class="infolabel">' . _MD_XOOPSFAQ_TOTAL_FAQS . '</span>', '<span class="infotext green bold">' . $totalFaqs . '</span>'));

$adminObject->displayNavigation('index.php');
$adminObject->displayIndex();

include __DIR__ . '/admin_footer.php';
