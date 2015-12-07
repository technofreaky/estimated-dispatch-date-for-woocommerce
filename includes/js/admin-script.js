jQuery(document).ready(function(){
	change_data_range();
	
	jQuery('#variable_product_options').change( function() {
		change_data_range();
	});
	
	jQuery('input[data-picker="time"]').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});


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