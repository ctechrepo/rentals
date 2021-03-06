<?php  

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js($this->default_theme_path.'/flexigrid/js/jquery.form.js');	
	$this->set_js($this->default_theme_path.'/flexigrid/js/flexigrid-add.js');

?>

<div class="flexigrid crud-form" style='width: 100%;'>	
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_add'); ?> <?php echo $subject?>
			</div>			
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $insert_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
		<div class='form-div'>
			<?php
			$counter = 0; 
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as; ?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>	
			</div>
			<?php }?>
			<!-- Start of hidden inputs -->
				<?php 
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			
			
			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>							
		</div>	
		<div class="pDiv">
			<div class='form-button-box'>
				<input type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large"/>
			</div>
<?php 	if(!$this->unset_back_to_list) { ?>				
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
			</div>					
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' onclick="javascript: goToList()"  class="btn btn-large" />
			</div>
<?php 	} ?>						
			<div class='form-button-box'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
			</div>
			<div class='clear'></div>	
		</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
	var message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>

<?php if( function_exists('get_dir_file_info') ): ?>
<div id="uploadsManager" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Uploaded Files</h3>
    </div>
    <div class="modal-body">
        <?php
        $fileArray = get_dir_file_info('assets/uploads/files');?>
        <?php foreach($fileArray as $file):
        //var_dump($file);
        $mime = get_mime_by_extension($file['name']);
        if (preg_match('/image/i',$mime) > 0)
        {
            $image_properties = array(
                'src' => 'assets/cache/thumbs/'.'150_150_'.$file['name'],
                'height' => '150',
                'width' => '150',
                'alt' => 'uploaded image'
            );
            echo '<div class="modal-file-images">';
            echo "<div style='width:150px; text-align: center; '>{$file['name']}</div>";
            echo img($image_properties);
            echo "<input type='hidden' value='{$file['name']}' /> ";
            echo '</div>';
        }
    endforeach;
        ?>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    </div>
</div>
<?php endif;?>





