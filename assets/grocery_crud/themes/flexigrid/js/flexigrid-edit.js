$(function(){
	
	var save_and_close = false;

    $('span.manage-uploads').click(function(){
        $('#uploadsModal').modal('show');
    });
	
	$('.ptogtitle').click(function(){
		if($(this).hasClass('vsble'))
		{
			$(this).removeClass('vsble');
			$('#main-table-box').slideDown("slow");
		}
		else
		{
			$(this).addClass('vsble');
			$('#main-table-box').slideUp("slow");
		}
	});	
	
	$('#save-and-go-back-button').click(function(){
		save_and_close = true;
		
		$('#crudForm').trigger('submit');
	});	
	
	$('#crudForm').submit(function(){
		$(this).ajaxSubmit({
			url: validation_url,
			dataType: 'json',
			cache: 'false',
			beforeSend: function(){
				$("#FormLoading").show();
			},
			success: function(data){
				$("#FormLoading").hide();
				if(data.success)
				{						
					$('#crudForm').ajaxSubmit({
						dataType: 'text',
						cache: 'false',
						beforeSend: function(){
							$("#FormLoading").show();
						},		
						success: function(result){
							
							$("#FormLoading").fadeOut("slow");
							data = $.parseJSON( result );
							if(data.success)
							{	
								if(save_and_close)
								{
									window.location = data.success_list_url;
									return true;
								}								
								
								$('#report-error').hide().html('');									
								$('.field_error').each(function(){
									$(this).removeClass('field_error');
								});									
								
								$('#report-success').html(data.success_message);
								$('#report-success').slideDown('slow');
							}
							else
							{
								alert( message_update_error );
							}
						},
						error: function(){
							alert( message_update_error );
						}
					});
				}
				else
				{
					$('.field_error').each(function(){
						$(this).removeClass('field_error');
					});
					$('#report-error').slideUp('fast');
					$('#report-error').html(data.error_message);
					$.each(data.error_fields, function(index,value){
						$('input[name='+index+']').addClass('field_error');
					});
							
					$('#report-error').slideDown('normal');
					$('#report-success').slideUp('fast').html('');
					
				}
			},
			error: function(){
				alert( message_update_error );
				$("#FormLoading").hide();
				
			}			
		});
		return false;
	});
});	

function goToList()
{
	if( confirm( message_alert_edit_form ) )
	{
		window.location = list_url;
	}

	return false;	
}

(function ($){
    $(".manage-uploads").click(function(){
        $("#uploadsManager").modal('show');
    });

    $(".modal-file-images").click(function(){

        var image = $(this).find('input:hidden').val();
        var src = $(this).find('img').attr('src');
        $('input.hidden-upload-input').val(image);
        $('.open-file.image-thumbnail').attr('href',src);

        var thumb = $('.open-file.image-thumbnail').find('img');

        if (thumb != undefined)
        {
            $(thumb).attr('src',src);
        } else {
            $('.open-file').html('<img src=" '+src+' " height="50" />');
            console.log($('.open-file').html());
            $('.open-file').attr('href',src);
        }

        $('.upload-success-url').css('display','block');
        $('#crudForm').submit();
        $("#uploadsManager").modal('hide');

    });
})(jQuery)

CKEDITOR.plugins.registered['save']=
{
    init : function( editor )
    {
        var command = editor.addCommand( 'save',
            {
                modes : { wysiwyg:1, source:1 },
                exec : function( editor ) {
                    //var fo=editor.element.$.form;
                    //editor.updateElement();
                    $('#crudForm').submit();
                }
            }
        );
        editor.ui.addButton( 'Save',
            {
            label : 'My Save',
            command : 'save'
            }
        );
    }
}