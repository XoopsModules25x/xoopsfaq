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
 * @see       Xmf\Module\Admin
 */

use XoopsModules\Xoopsfaq;

require __DIR__ . '/admin_header.php';
xoops_cp_header();

/** @var Xoopsfaq\CategoryHandler $categoryHandler */
/** @var Xoopsfaq\ContentsHandler $contentsHandler */
/** @var Xoopsfaq\Helper $helper */

//-----------------------
$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$contentsHandler = $helper->getHandler('Contents');
$totalFaqs       = $contentsHandler->getCount();

$criteriaPublished = new \CriteriaCompo();
$criteriaPublished->add(new \Criteria('contents_publish', Xoopsfaq\Constants::NOT_PUBLISHED, '>'));
$criteriaPublished->add(new \Criteria('contents_publish', time(), '<='));

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('contents_active', Xoopsfaq\Constants::ACTIVE, '='));
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
//if (!empty($newRelease)) {
//    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
//}

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    $yamlFile            = dirname(__DIR__) . '/config/admin.yml';
    $config              = loadAdminConfig($yamlFile);
    $displaySampleButton = $config['displaySampleButton'];

    if (1 == $displaySampleButton) {
        xoops_loadLanguage('admin/modulesadmin', 'system');
        require dirname(__DIR__) . '/testdata/index.php';

        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), './../testdata/index.php?op=load', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), './../testdata/index.php?op=save', 'add');
        //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), './../testdata/index.php?op=exportschema', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
    } else {
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
        $displaySampleButton = $config['displaySampleButton'];
    }
    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = \Xmf\Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app                        = [];
    $app['displaySampleButton'] = 0;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app                        = [];
    $app['displaySampleButton'] = 1;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = \Xmf\Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}

echo $utility::getServerStats();

//codeDump(__FILE__);
require __DIR__ . '/admin_footer.php';
