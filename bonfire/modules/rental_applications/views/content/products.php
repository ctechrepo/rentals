<div class="row-fluid">
    <div class="admin-box">
        <h3>Products</h3>
        <!--tabs-->
        <ul class="nav nav-tabs">
            <li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/products">Instruments</a></li>
            <li <?php echo $filter=='accessories' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/products/accessories">Accessories</a></li>
            <li <?php echo $filter=='categories' ? 'class="active"' : ''; ?>><a href="<?php echo site_url(SITE_AREA.$module_baseurl); ?>/products/categories">Categories</a></li>
        </ul>
        <div>
            <?php echo $CRUD_table; ?>
        </div>

    </div>
</div>

<script>

</script>