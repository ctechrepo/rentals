<div class="row-fluid">
    <div class="admin-box">
        <h3>Rental Plan Management</h3>
        <!--tabs-->
        <ul class="nav nav-tabs">
            <li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/plans">Rental Plans</a></li>
            <li <?php echo $filter=='details' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/plans/details">Plan Details</a></li>
            <li <?php echo $filter=='instrument_rental' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/plans/instrument_rental">Instrument Rental</a></li>
            <li <?php echo $filter=='bravo_rental' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/plans/bravo_rental">Bravo Rental</a></li>
        </ul>
        <div>
            <?php echo $CRUD_table; ?>
        </div>

    </div>
</div>