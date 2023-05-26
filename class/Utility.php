<?php declare(strict_types=1);

namespace XoopsModules\Xoopsfaq;

/*
 Xoopsfaq Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module:  myQuiz
 *
 * @license      https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <zyspec@yahoo.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        ::      File available since version 4.10
 */

use Xmf\Module\Admin;
use XoopsModules\Xoopsfaq\{
    Common
};

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------
    /**
     * Render the icon links
     *
     * @param array $icon_array contains operation=>icon_name as key=>value
     * @param mixed $param      HTML parameter
     * @param mixed $value      HTML parameter value to set
     * @param mixed $extra      are any additional HTML attributes desired for the <a> tag
     * @return string
     */
    public static function renderIconLinks(array $icon_array, $param, $value = null, $extra = null): string
    {
        $moduleDirName = \basename(\dirname(__DIR__));
        \xoops_loadLanguage('admin', $moduleDirName);
        $ret = '';
        if (null !== $value) {
            foreach ($icon_array as $_op => $icon) {
                if (false !== \mb_strpos($icon, '.')) {
                    $iconName = \mb_substr($icon, 0, \mb_strlen($icon) - \mb_strrchr($icon, '.'));
                    $iconExt  = \mb_substr(\mb_strrchr($icon, '.'), 1);
                } else {
                    $iconName = $icon;
                    $iconExt  = 'png';
                }
                $url = (!\is_numeric($_op)) ? $_op . '?' . $param . '=' . $value : \xoops_getenv('SCRIPT_NAME') . '?op=' . $iconName . '&amp;' . $param . '=' . $value;
                if (null !== $extra) {
                    $url .= ' ' . $extra;
                }
                $title = \constant(\htmlspecialchars(mb_strtoupper('_XO_LA_' . $iconName), \ENT_QUOTES | \ENT_HTML5));
                $img   = '<img src="' . Admin::iconUrl($iconName . '.' . $iconExt, '16') . '"' . ' title ="' . $title . '"' . ' alt = "' . $title . '"' . ' class="bnone middle">';
                $ret   .= '<a href="' . $url . '"' . $extra . '>' . $img . '</a>';
            }
        }

        return $ret;
    }
}
