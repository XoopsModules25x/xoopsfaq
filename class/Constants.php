<?php

namespace XoopsModules\Xoopsfaq;

/*
               XOOPS - PHP Content Management System
                   Copyright (c) 2000 XOOPS.org
                      <https://xoops.org>
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting
 source code which is considered copyrighted (c) material of the
 original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/
/**
 * XoopsFaq module
 *
 * Class to define XOOPS FAQ constant values. These constants are
 * used to make the code easier to read and to keep values in central
 * location if they need to be changed.  These should not normally need
 * to be modified. If they are to be modified it is recommended to change
 * the value(s) before module installation. Additionally the module may not
 * work correctly if trying to upgrade if these values have been changed.
 *
 * @copyright::  {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license  ::    {@link https://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @author   ::     zyspec <owners@zyspec.com>
 * @package  ::    xoopsfaq
 * @since    ::      1.40
 **/
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Constants interface definitions
 *
 * These constants are used instead of hard coded constants to allow programmers
 * the ability to easily decipher various operating constants. Using an interface
 * class ensures these are set at module initialization and cannot be overwritten
 * during operation.
 *
 * @author ZySpec <owners@zyspec.com>
 *
 */
interface Constants
{
    /**#@+
     * Constant definition
     */
    /**
     * default category
     */
    const DEFAULT_CATEGORY = 0;
    /**
     * not published
     */
    const NOT_PUBLISHED = 0;
    /**
     * is published
     */
    const PUBLISHED = 1;
    /**
     * FAQ inactive
     */
    const INACTIVE = 0;
    /**
     * FAQ active
     */
    const ACTIVE = 1;
    /**
     * Invalid FAQ ID
     */
    const INVALID_FAQ_ID = 0;
    /**
     * no delay XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_NONE = 0;
    /**
     * short XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_SHORT = 1;
    /**
     * medium XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_MEDIUM = 3;
    /**
     * long XOOPS redirect delay (in seconds)
     */
    const REDIRECT_DELAY_LONG = 7;
    /**
     * default category order for display order
     */
    const DEFAULT_ORDER = 0;
    /**
     * default contents order for display weight
     */
    const DEFAULT_WEIGHT = 0;
    /**
     * confirm not ok to take action
     */
    const CONFIRM_NOT_OK = 0;
    /**
     * confirm ok to take action
     */
    const CONFIRM_OK = 1;
    /**
     * Set content editing flag
     */
    const SET = 1;
    /**
     * Unset content editing flag
     */
    const NOTSET = 0;
    /**
     * Include Annon Group
     */
    const INCLUDE_ANNON = 1;
    /**
     * Exclude Annon Group
     */
    const EXCLUDE_ANNON = 0;
    /**#@-*/
}
