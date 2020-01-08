<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_XOOPSFAQ_HELP_HEADER}>

    <h2 class="even">OVERVIEW</h2>
    <h4 class="odd">Description</h4>
    <p style="margin: 1.4em 0;">XOOPS FAQ is a XOOPS module to create FAQ (Frequently Asked Questions) and organize
        them by categories. Each category allows the administrator to create an unlimited number of questions and answers.</p>

    <h4 class="odd">Features</h4>
    <ul class="line120" style="margin: 1.4em 0;">
        <li>FAQs can be organized into an unlimited number of categories</li>
        <li>Visibility of categories (and FAQs by inheritance) can be restricted using XOOPS Group Permissions</li>
        <li>Unlimited number of FAQs per category</li>
        <li>Display of FAQs uses XOOPS templating system</li>
        <li>Supports XOOPS commenting</li>
        <li>FAQ answer display is controlled by javascript (in non-bootsrap themes) to provide a show/hide accordion effect</li>
        <li>Uses XOOPS editors to allow for rich text display of FAQ answers.</li>
        <li>Three (3) cloneable blocks are available to display Categories, Random FAQ, and most Recent FAQ(s)</li>
        <li>Blocks allow varying visibility based on XOOPS Group Permissions</li>
    </ul>
    <h4 class="odd">Install/uninstall</h4>
    <p style="margin: 1.4em 0;">No special measures necessary, follow the standard installation process to
        extract the /xoopsfaq folder into the ./modules directory. Install the
        module through Admin -> System Module -> Modules.</p>
    <p style="margin: 1.4em 0;"><strong>Note:</strong> If you downloaded the module directly from GitHub the directory may
        be named 'xoopsfaq-master' (or another extended name). Please rename the top-level folder to 'xoopsfaq' (all lowercase,
        without the hyphen or extended name) BEFORE installing the module.</p>
    <p style="margin: 1.4em 0;">Detailed instructions on installing modules are available in the
        <a href="http://goo.gl/adT2i">XOOPS Operations Manual</a></p>

    <h4 class="odd">Operating instructions</h4>
    <p style="margin: 1.4em 0;">This operation and configuration for this module are both very simple:</p>
    <ol class="line120" style="margin: 1.4em 0; list-style-type: lower-roman;" type="i">
        <li>Configure module Preferences (e.g. select the text editor, enable/disable comments, etc.)</li>
        <li>Add one or more Categories and set XOOPS Group Permissions for the category</li>
        <li>Add FAQs and Answers</li>
        <li>Check that you have given your user groups the necessary module and block access rights to
            use this module. Group permissions are set through the Administration Menu->System->Groups
        </li>
        <li>Set the configuration (Preferences) for each block you wish to use and enable the block</li>
    </ol>
    <p style="margin: 1.4em 0;">Detailed instructions on configuring the access rights for XOOPS Group Permissions are available in the
        <a href="http://goo.gl/adT2i">XOOPS Operations Manual</a></p>

    <h4 class="odd">Tutorial</h4>
    <p class="italic" style="margin: 1.4em 0;">Not available yet</p>

    <!-- ====== Help Content ======== -->
</div>
