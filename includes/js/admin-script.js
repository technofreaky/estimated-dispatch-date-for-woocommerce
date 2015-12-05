jQuery(document).ready(function(){
	jQuery('#js_range_est_selector').jRange({
		from: 0,
		to: 30,
		step: 1,
		scale: [0,5,10,15,20,25,30],
		format: '%s',
		width: 200,
		showLabels: true,
		isRange : true,
		onstatechange :function (value){
			jQuery('#_est_dispatch_date').val(value);
		} 
	});	
	
	jQuery('input[data-picker="time"]').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});


});