var eddwc_variation_data = '';
jQuery(document).ready(function($) {
    //Creates relevant fields for available variations
    $prod_label = $('.eddwc-days-v').text();
    $(document).on('change', '.variations select', function(event) {

        var $var_match = $('.single_variation_wrap').find('input[name="variation_id"]').val();
        eddwc_variation_data = $('.eddwc-days-v').data('eddwc-id');
        if (eddwc_variation_data.length != 0) {
            if ($var_match != '') {
				if ($('#eddwc-dispatch-general-v').length){
					$('#eddwc-dispatch-general-v').css('display', '');
				} else {
                	$('#eddwc-dispatch-date-v').css('display', '');
				}
                jQuery.each(eddwc_variation_data, function(var_id, var_val) {
					
					if ($var_match == var_id) {
						$day = '[days]';
						days = '[days]';
						if ($prod_label.indexOf('[days:') > -1){
						$day = $prod_label.match(/\:([^ ]*\])/);
						$days = $day[0].replace(':','');
						$days = $days.replace(']','');
						$days = $days.split(',');
						$day = '[days' + $day[0];						
                        if (var_val > 1) {
                            days = $days[1].replace(' ', '');
                        } else {
                            days = $days[0].replace(' ', '');
                        }
						}
                        prod_label_disp = $prod_label.replace('[number]', var_val);
                        prod_label_disp = prod_label_disp.replace($day, days);
                        prod_label_disp = prod_label_disp.replace('[date]', var_val);
						if (prod_label_disp.indexOf('[range]') > -1) {
							range = var_val.split(',');
							var_val = range[0] + ' - ' + range[1];
							if (range[0] == range[1]){
								var_val = range[0];
							}
							prod_label_disp = prod_label_disp.replace('[range]', var_val);
						}
						prod_label_disp = prod_label_disp.replace('[br]','<br>');
                        $('.eddwc-days-v').html(prod_label_disp);
                    }
                });

            } else {
				if ($('#eddwc-dispatch-general-v').length){
					$('#eddwc-dispatch-general-v').css('display', 'none');
				} else {
                	$('#eddwc-dispatch-date-v').css('display', 'none');
				}
            }
        }
    });
	$grp_label = $('.eddwc-days-g').text();
	$(document).on('change', '.quantity', function(event) {
		if ($('#eddwc-dispatch-general-g').length){
			eddwc_grp_data =  $('.eddwc-days-g').data('eddwc-id');
			eddwc_grp_array = [];
			$eddwc_grp_min = [];
			$eddwc_grp_max = [];
			$i = 0;
			if (eddwc_grp_data.length != 0){
				$('.quantity').find('input[type="number"]').each( function() {					
					if ($(this).val() > 0) {
						$eddwc_grp_entry = $(this).attr('name').match(/\[(\d+)\]/);
						eddwc_grp_array[$i] = $eddwc_grp_entry[1];
						$i++;
					}
				});
				$.each(eddwc_grp_array, function(arr_id, arr_val) {
				
					$.each(eddwc_grp_data, function(grp_id, grp_val) {
						if (grp_id == arr_val) {
							range = grp_val.split(',');
							$eddwc_grp_min.push(parseInt(range[0]));
							$eddwc_grp_max.push(parseInt(range[1]));											
						}
					});
				});			
				$eddwc_grp_min.sort(function(a,b){return a - b;});
				$eddwc_grp_max.sort(function(a,b){return b - a;});
				$eddwc_range_set = $eddwc_grp_min[0] + ' - ' + $eddwc_grp_max[0];
				if ($eddwc_grp_min[0] == $eddwc_grp_max[0]) {
					$eddwc_range_set = $eddwc_grp_min[0];
				}
				if ($eddwc_grp_max[0] > 0){
					$('#eddwc-dispatch-general-g').css('display', '');
					display_label = $grp_label.replace('[range]', $eddwc_range_set);
					display_label = display_label.replace('[br]','<br>');
					$('.eddwc-days-g').html(display_label);
				} else {
					$('#eddwc-dispatch-general-g').css('display', 'none');
				}
				
			}
		}	
		if ($('#eddwc-dispatch-date-g').length){
			eddwc_grp_data =  $('.eddwc-days-g').data('eddwc-id');	
			eddwc_grp_array = [];
			$eddwc_max_date = [];
			$i = 0;
			if (eddwc_grp_data.length != 0){
				$('.quantity').find('input[type="number"]').each( function() {					
					if ($(this).val() > 0) {
						$eddwc_grp_entry = $(this).attr('name').match(/\[(\d+)\]/);
						eddwc_grp_array[$i] = $eddwc_grp_entry[1];
						$i++;
					}
				});
				$.each(eddwc_grp_array, function(arr_id, arr_val) {
				
					$.each(eddwc_grp_data, function(grp_id, grp_val) {
						if (grp_id == arr_val) {		
							if (grp_val.length <= 2){	
								$eddwc_max_date.push(parseInt(grp_val));
							} else {
								$eddwc_max_date.push(grp_val);
							}							
						}
					});
				});			
				$eddwc_max_date.sort(function(a,b){return a - b;});
				if ($eddwc_max_date[0]) {
					 if ($eddwc_max_date[0] > 1) {
                            days = 'days'
                        } else {
                            days = 'day'
                        }
					$('#eddwc-dispatch-date-g').css('display', '');
					display_label = $grp_label.replace('[number]', $eddwc_max_date[0]);
					display_label = display_label.replace('[days]', days);
					display_label = display_label.replace('[date]', $eddwc_max_date[0]);
					display_label = display_label.replace('[br]','<br>');
					$('.eddwc-days-g').html(display_label);
				} else {
					$('#eddwc-dispatch-date-g').css('display', 'none');
				}
			}
		}
			
	});
});