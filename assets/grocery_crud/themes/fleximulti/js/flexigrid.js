$(function(){
	$('.quickSearchButton').on('click', function(){
        var par = $(this).closest('.flexigrid');
		$('.quickSearchBox', par).slideToggle('normal');
	});

	$('.ptogtitle').on('click', function() {
		var par = $(this).closest('.flexigrid');
		if($(this).hasClass('vsble'))
		{
			$(this).removeClass('vsble');
			$('.main-table-box', par).slideDown("slow");
		}
		else
		{
			$(this).addClass('vsble');
			$('.main-table-box', par).slideUp("slow");
		}		
	});

	//var getUrl = window.location;
	//var baseUrl = getUrl .protocol + "//" + getUrl.host;
	//$("script[src='"+baseUrl+"/assets/grocery_crud/themes/flexigrid/js/flexigrid-edit.js']").remove();
	//alert(baseUrl+"/assets/grocery_crud/themes/flexigrid/js/flexigrid-edit.js");

	$('.fund_delete').on('click', function(e) {
		var url = window.location.href;
		var cookieID = readCookie('projID');
		if( confirm( message_alert_delete ) )
		{
		$.ajax({
			url: $(this).attr('href'),
			dataType: 'json',
			success: function(data) {
				if(data.success) {
					window.location.href = url;
				}
			}
		});
		}
		return false;
	});

	$('.filtering_form').submit(function(){
        var par = $(this).parent('.flexigrid');

        var par_id = par.attr('id');
		var crud_page =  parseInt($('.crud_page', par).val());
		var last_page = parseInt($('.last-page-number', par).html());
		
		if(crud_page > last_page)
			$('.crud_page', par).val(last_page);
		if(crud_page <= 0)
			$('.crud_page', par).val('1');
		
		var this_form = $(this);

		$(this).ajaxSubmit({
			 url: ajax_list_info_urls[par_id],
			 dataType: 'json',
                beforeSend: function(){
                    $('.ajax_refresh_and_loading',par).addClass('loading');
                },
			 success:    function(data){
				$('.total_items', par).html( data.total_results);

                 if($('.crud_page', par).val() == 0)
                     $('.crud_page', par).val('1');

                 var crud_page 		= parseInt( $('.crud_page', par).val()) ;
                 var per_page	 	= parseInt( $('.per_page', par).val() );
                 var total_items 	= parseInt( $('.total_items', par).html() );

                 $('.last-page-number', par).html( Math.ceil( total_items / per_page) );

                 if(total_items == 0)
                     $('.page-starts-from', par).html( '0');
                 else
                     $('.page-starts-from', par).html( (crud_page - 1)*per_page + 1 );

                 if(crud_page*per_page > total_items)
                     $('.page-ends-to', par).html( total_items );
                 else
                     $('.page-ends-to', par).html( crud_page*per_page );

				this_form.ajaxSubmit({
                    beforeSend: function(){
                        $('.ajax_refresh_and_loading',par).addClass('loading');
                    },
					 success:    function(data){
						$('.ajax_list', par).html(data);
                         $('.ajax_refresh_and_loading',par).removeClass('loading');
					 }
				});
                 $('.ajax_refresh_and_loading',par).addClass('loading');
			 }
		});
		
		createCookie('crud_page_'+par_id+'_'+uniques_hash[par_id],crud_page,1);
		createCookie('per_page_'+par_id+'_'+uniques_hash[par_id],$('.per_page', par).val(),1);
		createCookie('hidden_ordering_'+par_id+'_'+uniques_hash[par_id],$('.hidden-ordering', par).val(),1);
		createCookie('hidden_sorting_'+par_id+'_'+uniques_hash[par_id],$('.hidden-sorting', par).val(),1);
		createCookie('search_text_'+par_id+'_'+uniques_hash[par_id],$('.search_text', par).val(),1);
		createCookie('search_field_'+par_id+'_'+uniques_hash[par_id],$('.search_field', par).val(),1);
		
		return false;
	});
	
	$('.crud_search').on('click', function(){
        var par = $(this).closest('.flexigrid');
		$('.crud_page',par).val('1');
		$('.filtering_form',par).trigger('submit');
	});
	
	$('.search_clear').on('click', function(){
        var par = $(this).closest('.flexigrid');
		$('.crud_page',par).val('1');
		$('.search_text',par).val('');
		$('.filtering_form',par).trigger('submit');
	});
	
	$('.per_page').change(function(){
        var par = $(this).closest('.flexigrid');
        $('.crud_page',par).val('1');
		$('.filtering_form',par).trigger('submit');
	});

	$('.ajax_refresh_and_loading').on('click', function(){
        var par = $(this).closest('.flexigrid');
		$('.filtering_form',par).trigger('submit');
	});
	
	$('.first-button').on('click', function(){
        var par = $(this).closest('.flexigrid');
        if( $('.crud_page',par).val() != "1")
        {
            $('.crud_page',par).val('1');
            $('.filtering_form',par).trigger('submit');
        }
	});
	
	$('.prev-button').on('click', function(){
        var par = $(this).closest('.flexigrid');
		if( $('.crud_page',par).val() != "1")
		{
			$('.crud_page',par).val( parseInt($('.crud_page',par).val()) - 1 );
			$('.crud_page',par).trigger('change');
		}
	});
	
	$('.last-button').on('click', function(){
        var par = $(this).closest('.flexigrid');
        if( $('.crud_page',par).val() != parseInt($('.last-page-number',par).html()))
        {
            $('.crud_page',par).val( $('.last-page-number',par).html());
            $('.filtering_form',par).trigger('submit');
        }
	});
	
	$('.next-button').on('click', function(){
        var par = $(this).closest('.flexigrid');
        if( $('.crud_page',par).val() != parseInt($('.last-page-number',par).html()))
        {
		    $('.crud_page',par).val( parseInt($('.crud_page',par).val()) + 1 );
		    $('.crud_page',par).trigger('change');
        }
	});
	
	$('.crud_page').change(function(){
        var par = $(this).closest('.flexigrid');
		$('.filtering_form',par).trigger('submit');
	});
	
	$('.ajax_list').on('click','.field-sorting', function(){
        var par = $(this).closest('.flexigrid');

		$('.hidden-sorting',par).val($(this).attr('rel'));
		
		if($(this).hasClass('asc'))
			$('.hidden-ordering',par).val('desc');
		else
			$('.hidden-ordering',par).val('asc');
		
		$('.crud_page',par).val('1');
		$('.filtering_form',par).trigger('submit');
	});
	
	$('.ajax_list').on('click','.delete-row', function(){
		var delete_url = $(this).attr('href');

        var par = $(this).closest('.flexigrid');
		
		if( confirm( message_alert_delete ) )
		{
			$.ajax({
				url: delete_url,
				dataType: 'json',
				success: function(data)
				{					
					if(data.success)
					{
						$('.ajax_refresh_and_loading',par).trigger('click');
						$('.report-success',par).html( data.success_message ).slideUp('fast').slideDown('slow');
						$('.report-error',par).html('').slideUp('fast');
					}
					else
					{
						$('.report-error',par).html( data.error_message ).slideUp('fast').slideDown('slow');
						$('.report-success',par).html('').slideUp('fast');
						
					}
				}
			});
		}
		
		return false;
	});
	
	$('.crud_page').numeric();

    $('.flexigrid').each(function(index) {

        var this_id = $(this).attr('id');

        var cookie_crud_page = readCookie('crud_page_'+this_id+'_'+uniques_hash[this_id]);
        var cookie_per_page  = readCookie('per_page_'+this_id+'_'+uniques_hash[this_id]);
        var hidden_ordering  = readCookie('hidden_ordering_'+this_id+'_'+uniques_hash[this_id]);
        var hidden_sorting  = readCookie('hidden_sorting_'+this_id+'_'+uniques_hash[this_id]);
        var cookie_search_text  = readCookie('search_text_'+this_id+'_'+uniques_hash[this_id]);
        var cookie_search_field  = readCookie('search_field_'+this_id+'_'+uniques_hash[this_id]);

        if(cookie_crud_page !== null && cookie_per_page !== null)
        {
            $('.crud_page',$(this)).val(cookie_crud_page);
            $('.per_page',$(this)).val(cookie_per_page);
            $('.hidden-ordering',$(this)).val(hidden_ordering);
            $('.hidden-sorting',$(this)).val(hidden_sorting);
            $('.search_text',$(this)).val(cookie_search_text);
            $('.search_field',$(this)).val(cookie_search_field);

            if(cookie_search_text !== '')
                $('.quickSearchButton',$(this)).trigger('click');

            $('.filtering_form',$(this)).trigger('submit');
        }
        else
        {
            $('.filtering_form',$(this)).trigger('submit');
        }

    });

});