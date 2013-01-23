<div class="row-fluid">
<div class="admin-box">

    <h3><?php echo $toolbar_title ?></h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" autocomplete="off"'); ?>

    <fieldset>
        <legend><?php echo lang('mshoppe_instrument_details') ?></legend>

        <div class="control-group <?php echo form_error('email') ? 'error' : '' ?>">
            <label for="email" class="control-label"><?php echo lang('bf_email') ?></label>
            <div class="controls">
                <input type="email" name="email" id="email" value="<?php echo set_value('email', isset($user) ? $user->email : '') ?>">
                <?php if (form_error('email')) echo '<span class="help-inline">'. form_error('email') .'</span>'; ?>
            </div>
        </div>
    </fieldset>
    <?php echo form_close();?>
</div>
</div>