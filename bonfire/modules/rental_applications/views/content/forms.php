<div class="row-fluid">
    <div class="admin-box">
        <h3>Form Management</h3>
        <!--tabs-->
        <ul class="nav nav-tabs">
            <li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/forms">Forms</a></li>
            <li <?php echo $filter=='fields' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/forms/fields">Form Fields</a></li>
            <li <?php echo $filter=='sections' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/forms/sections">Form Sections</a></li>
        </ul>
        <div>
            <?php echo $CRUD_table; ?>
        </div>

    </div>
</div>