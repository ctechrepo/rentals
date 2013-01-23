<div class="row-fluid">
    <div class="admin-box">
        <h3>Applications</h3>
        <!--Tabs-->
        <ul class="nav nav-tabs">
            <li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/index">Band Applications</a></li>
            <li <?php echo $filter=='accessories' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/index/orchestra">Orchestra Applications</a></li>
            <li <?php echo $filter=='schools' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/index/bravo">Bravo Applications</a></li>
        </ul>
        <div>
            <?php echo $CRUD_table;?>
        </div>
    </div>
</div>