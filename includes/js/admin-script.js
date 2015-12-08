
jQuery(document).ready(function(){
	change_data_range();
	setup_datepicker();
	jQuery('#settings_holiday table.form-table  tr > th').eq(0).remove();
	jQuery('#settings_holiday input#settings_holiday ').removeAttr('type').attr('type','button').val('Save Holidays');
	jQuery('#settings_holiday input[name="action"]').val('save_holiday_dates');
	jQuery('#settings_holiday button.edit').hide();
	jQuery('#settings_holiday button.save').hide();
	jQuery('form#settings_holiday table.form-table td:first').css('padding','0');
	jQuery('#variable_product_options').change( function() { change_data_range(); });
	
	jQuery('input[data-picker="time"]').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});
	
	jQuery('#settings_holiday').on('click','button.delete',function(){
		jQuery(this).parent().parent().fadeOut('fast',function(){
			jQuery(this).remove();
			jQuery('#settings_holiday table.widefat button.add').hide();
			jQuery('#settings_holiday table.widefat tr:last button.add').show();
		})
	});
	
	jQuery('#settings_holiday').on('click','button.add',function(){
		var template = jQuery('#settings_holiday table.widefat #template_settings').html();
		jQuery('#settings_holiday table.widefat tbody:last').append('<tr>'+template+'</tr>');
		jQuery('#settings_holiday table.widefat tbody:last').find('input[data-type="datepicker"]').removeAttr('id').removeClass('hasDatepicker');
		setup_datepicker();
		jQuery('#settings_holiday table.widefat button.add').hide();
		jQuery('#settings_holiday table.widefat tr:last button.add').show();
		
	});
	
	jQuery('#settings_holiday').on('blur','input.holiday_name_input',function(){
		var current_value = jQuery(this).val(); 
		jQuery(this).attr('name','eddwc_holidays['+current_value+'][name]'); 
		jQuery(this).parent().parent().find('input.holiday_date_input').attr('name','eddwc_holidays['+current_value+'][date]');
		
	});
	
	
	
	jQuery('#settings_holiday input#settings_holiday ').click(function(){
		var data = jQuery("form#settings_holiday").serialize();
		jQuery(this).parent().append('<span class="spinner is-active" style="float:none;margin-top:0;"> </span>');
		jQuery(this).removeClass('button-primary').attr('disabled',true);
		jQuery.post(ajaxurl, data, function(response) {
			 location.href = location.href;
		});
	})

});

function change_data_range(){
	jQuery('input[date-type="range_select"] ').jRange({
		from: 0,
		to: 30,
		step: 1,
		scale: [0,5,10,15,20,25,30],
		format: '%s',
		width: 200,
		showLabels: true,
		isRange : true,
	});	
}

function setup_datepicker(){ 
	jQuery('input[data-type="datepicker"]').datepicker({
		showButtonPanel: true,
		dateFormat: 'dd-mm-yy',
	});
}