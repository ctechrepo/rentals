<div class="row-fluid">
    <div class="admin-box">
        <h3>Manage Contacts</h3>
        <!--tabs-->
        <ul class="nav nav-tabs">
            <li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/contacts">Contacts</a></li>
            <li <?php echo $filter=='organizations' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/contacts/organizations">Organizations</a></li>
            <li <?php echo $filter=='organization_recommendations' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/contacts/organization_recommendations">Organization Recommendations</a></li>
            <li <?php echo $filter=='contact_recommendations' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/contacts/contact_recommendations">Contact Recommendations</a></li>
        </ul>
        <div>
            <?php echo $CRUD_table; ?>
        </div>

    </div>
</div>