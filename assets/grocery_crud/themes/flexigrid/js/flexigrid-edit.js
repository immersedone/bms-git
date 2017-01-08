$(function(){


	$('.ptogtitle').click(function(){
		if($(this).hasClass('vsble'))
		{
			$(this).removeClass('vsble');
			$('#main-table-box #crudForm').slideDown("slow");
		}
		else
		{
			$(this).addClass('vsble');
			$('#main-table-box #crudForm').slideUp("slow");
		}
	});


	var save_and_close = false;
	var save_and_print = false;
	var save_and_print_no = false;

	$('#save-and-go-back-button').click(function(){
		save_and_close = true;

		$('#crudForm').trigger('submit');
	});

	$('#save-and-print-button').click(function(){
		save_and_close = true;
		save_and_print = true;

		$('#crudForm').trigger('submit');
	});

	$('#save-and-print-no-button').click(function(){
		save_and_close = true;
		save_and_print_no = true;

		$('#crudForm').trigger('submit');
	});

	$('#crudForm').submit(function(){
		var my_crud_form = $(this);

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
								var data_unique_hash = my_crud_form.closest(".flexigrid").attr("data-unique-hash");

								$('.flexigrid[data-unique-hash='+data_unique_hash+']').find('.ajax_refresh_and_loading').trigger('click');

								if(save_and_close)
								{
									if ($('#save-and-go-back-button').closest('.ui-dialog').length === 0) {
										//window.location = data.success_list_url;
											
										//Check to see if it is in Project View
										//and redirect to different URL if it is
										if (isProjectView !== false && save_and_print === false && save_and_print_no === false){
											window.location = success_list_url;
										} else if((isProjectView === false || isProjectView === true) && save_and_print === true && save_and_print_no === false) {
											window.location = data.success_url_reimb;
										} else if((isProjectView === false || isProjectView === true) && save_and_print === false && save_and_print_no === true) {
											window.location = data.success_url_reimb_no;
										} else {
											window.location = data.success_list_url;
										}
										//alert(data.success);
										
									} else {
										$(".ui-dialog-content").dialog("close");
										success_message(data.success_message);
									}

									return true;
								}

								form_success_message(data.success_message);

								if(isProjectView !== false) {
									$('#report-success > p > a').attr('href', success_list_url);	
								}
								
							}
							else
							{
								form_error_message(message_update_error);

								if(isProjectView !== false) {
									$('#report-error > p > a').attr('href', list_url);	
								}
							}
						},
						error: function(){
							form_error_message( message_update_error );

							if(isProjectView !== false) {
								$('#report-error > p > a').attr('href', list_url);	
							}
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

	if( $('#cancel-button').closest('.ui-dialog').length === 0 ) {

		$('#cancel-button').click(function(){
			if( $(this).hasClass('back-to-list') || confirm( message_alert_edit_form ) )
			{
				window.location = list_url;
			}

			return false;
		});

	}
});
