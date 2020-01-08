<div id="help-template" class="outer">
    <h1 class="head">Help:
        <a class="ui-corner-all tooltip" href="<{$xoops_url}>/modules/xoopsfaq/admin/index.php"
           title="Back to the administration of XOOPS FAQ"> XOOPS FAQ
            <img src="<{xoAdminIcons home.png}>"
                 alt="Back to the Administration of XOOPS FAQ">
        </a></h1>
    <!-- ===== Help Content ======= -->
    <h2 class="even">TIPS</h2>
    <h4 class="odd">Configuration</h4><br>

    <h5 class="even">Category Group Permissions</h5>
    <p style='margin: 1.4em 0;'>XOOPS FAQ allows the administrator to manage group
        permissions using XOOPS groups. Detailed instructions on configuring the access rights for user groups are available in the
        <a href="http://goo.gl/adT2i">XOOPS Operations Manual</a></p>
    <p style='margin: 1.4em 0;'>Selecting individual groups or categories - You
        can select individual groups by selecting each desired group with the mouse
        while at the same time holding down the CTRL key (Windows) or the COMMAND / Apple key (MAC).</p>
    <h4 class="odd">Operating Tips</h4>
    <h5 class="even bold">Category Order (weight)</h5>
    <p style='margin: 1.4em 0;'>You can re-order the display of categories by changing the order (weight) setting for the category. A
        category with a lower order (weight) will be displayed prior to a category with a higher order (weight) in both category listing(s)
        and in select boxes. So, for example, a category with an order of '10' will be displayed before a category with an order of '20'. If
        multiple categories have the same order then the categories set with that order will be displayed in alphabetical order. For example
        assume we have two (2) categories. One named 'Pens' and the other named 'Pencils'. Both categories are set with the order of 25. The
        'Pencils' category will be displayed in the categories listing (and select boxes) before the 'Pens' category.<br><br>
        <em>Categories can be arranged in alphabetical order by simply setting the order (weight) for all categories to the same value.</em></em></p>
    <h5 class="even bold">FAQ Order (weight)</h5>
    <p style='margin: 1.4em 0;'>You can re-order the display of FAQs within a category by changing the order (weight) setting for the FAQ.
        A FAQ with a lower order (weight) will be displayed prior to a FAQ with a higher order (weight). If multiple FAQs have the same order
        then the FAQs set with that order will be displayed in alphabetical order. Using the aforementioned behavior an administrator can set
        all of the weights to the same value, or leave them at the default of zero (0), to list FAQs in alphabetical order without having to
        change the weight for each FAQ to keep them in alphabetical order when new FAQs are added.</p>
    <h4 class="odd">Troubleshooting</h4>
    <h5 class="even bold">Why can't user xx see a Category?</h5>
    <p>There are several reasons a user might not be able to see the category depending on where the issue is occurring:</p>
    <ul style="margin-left: 3em;">
        <li style="padding-left: 15px; list-style-position: outside;">Ensure the user's group has view rights to the XoopsFaq module and blocks. Go to Administration->Module->System->Groups
            and verify the user's group has the appropriate Module Access and Block Access rights.
        </li>
        <li style="padding-left: 15px; list-style-position: outside;">Check group permissions for the category to make sure the user's group has permission to view the category. Go to
            Administration->Modules->Xoops FAQ->Category. Select the appropriate category and make sure the user's group is selected.
        </li>
        <li style="padding-left: 15px; list-style-position: outside;">Check the module cache settings. It may be the system cache is "delaying" the ability to see any recent updates if the cache is enabled. Go to Administration->Preferences->System Options->Module-wide Cache.</li>
        <li style="padding-left: 15px; list-style-position: outside;">Check the block cache settings. Again, the cache may be "delaying" the ability to see any recent updates if the cache is enabled. Go to Administration->Modules->Xoops FAQ->Home.
            Select the Blocks menu item in the menu near the top of the page. Then click the edit icon to edit the appropriate block. Check the cache lifetime setting (you may want to temporarily
            set it to 'No Cache' while you're making changes).
        </li>
        <li style="padding-left: 15px; list-style-position: outside;">If the user cannot see a category it may be because there are no FAQs in the category. Check the specific block settings to see if there is a configuration
            option to only show category if there are FAQs in the category. For example the Categories block has such an option.
        </li>
    </ul>
    <h5 class="even bold">Why can't user xx see a FAQ?</h5>
    <p>There are, once again, several reasons a user might not be able to see a FAQ depending on where the issue is occuring:
    <ul style="margin-left: 3em;">
        <li style="padding-left: 15px; list-style-position: outside;">First, check the category permissions as explained under <em>Why can't user xx see a Category?</em> above.</li>
        <li style="padding-left: 15px; list-style-position: outside;">Next check to verify the FAQ is active. Go to
            Administration->Modules->Xoops FAQ->FAQ and observe the Active state in the table. If the FAQ is not active (red ball in the Active column) then
            select the icon to edit the FAQ and make it active.
        </li>
    </ul>
    <h5 class="even bold">Why does a block only show up for certain users?</h5>
    <p>This is most likely a permissions setting. Check the category Permission setting to verify the user's group has view permissions to the category.</p>
    <h5 class="even bold">Why doesn't the FAQ HTML display in the block answer?</h5>
    <p>HTML is stripped in a block that shows a reduced number of characters. This is done to preserve the integrity
        of the HTML code so that we don't attempt to show <em>broken</em> HTML. If you want to display HTML in your block(s) you
        must set <strong>nn</strong> = 0 in the <em>Display <strong>nn</strong> characters of the FAQ answer</em> in the block's settings.</p>
    <h5 class="even bold">Why don't the changes made to the main templates effect the display of a FAQ/Category?</h5>
    <p>There are a couple of possible reasons changes you make to the main templates do not take effect:</p>
    <ul style="margin-left: 3em;">
        <li style="padding-left: 15px; list-style-position: outside;">Make sure that <em>Check for template changes</em> in Administration->Preferences->General is set to yes, at least
            temporarily so you can see your changes.
        </li>
        <li style="padding-left: 15px; list-style-position: outside;">Make sure you're editing the correct template(s). The templates you may have created/edited by editing the templates through
            the XOOPS Administration interface (Administration->Modules->System->Templates) may be over-riding the templates in the module
            directory (folder).
        </li>
        <li style="padding-left: 15px; list-style-position: outside;">The theme you are using may be over-riding the templates in the module/templates directory. Check the theme being used to see if
            there is a ./modules/xoopsfaq directory. If so, you will need to modify those templates to make the changes you want to affect.
        </li>
    </ul>

    <!-- ====== Help Content ======== -->

</div>
