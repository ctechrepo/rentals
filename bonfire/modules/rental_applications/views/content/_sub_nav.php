<ul class="nav nav-pills">
    <li <?php echo $this->uri->segment(4) == ''? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .$module_baseurl) ?>">Applications</a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'contacts'? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .$module_baseurl.'/contacts') ?>">Contacts</a>
    </li>

    <li <?php echo $this->uri->segment(4) == 'products'? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .$module_baseurl.'/products') ?>">Products</a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'forms' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .$module_baseurl.'/forms') ?>">Rental Forms</a>
    </li>
    <li <?php echo $this->uri->segment(4) == 'plans' ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url(SITE_AREA .$module_baseurl.'/plans') ?>">Rental Plans</a>
    </li>
</ul>