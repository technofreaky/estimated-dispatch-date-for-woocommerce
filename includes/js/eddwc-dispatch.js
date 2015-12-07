var cuzd_variation_data = '';
jQuery(document).ready(function($) {
    //Creates relevant fields for available variations
    $prod_label = $('.cuzd-days-v').text();
    $(document).on('change', '.variations select', function(event) {

        var $var_match = $('.single_variation_wrap').find('input[name="variation_id"]').val();
        cuzd_variation_data = $('.cuzd-days-v').data('cuzd-id');
        if (cuzd_variation_data.length != 0) {
            if ($var_match != '') {
				if ($('#cuzd-dispatch-general-v').length){
					$('#cuzd-dispatch-general-v').css('display', '');
				} else {
                	$('#cuzd-dispatch-date-v').css('display', '');
				}
                jQuery.each(cuzd_variation_data, function(var_id, var_val) {
					
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
                        $('.cuzd-days-v').html(prod_label_disp);
                    }
                });

            } else {
				if ($('#cuzd-dispatch-general-v').length){
					$('#cuzd-dispatch-general-v').css('display', 'none');
				} else {
                	$('#cuzd-dispatch-date-v').css('display', 'none');
				}
            }
        }
    });
	$grp_label = $('.cuzd-days-g').text();
	$(document).on('change', '.quantity', function(event) {
		if ($('#cuzd-dispatch-general-g').length){
			cuzd_grp_data =  $('.cuzd-days-g').data('cuzd-id');
			cuzd_grp_array = [];
			$cuzd_grp_min = [];
			$cuzd_grp_max = [];
			$i = 0;
			if (cuzd_grp_data.length != 0){
				$('.quantity').find('input[type="number"]').each( function() {					
					if ($(this).val() > 0) {
						$cuzd_grp_entry = $(this).attr('name').match(/\[(\d+)\]/);
						cuzd_grp_array[$i] = $cuzd_grp_entry[1];
						$i++;
					}
				});
				$.each(cuzd_grp_array, function(arr_id, arr_val) {
				
					$.each(cuzd_grp_data, function(grp_id, grp_val) {
						if (grp_id == arr_val) {
							range = grp_val.split(',');
							$cuzd_grp_min.push(parseInt(range[0]));
							$cuzd_grp_max.push(parseInt(range[1]));											
						}
					});
				});			
				$cuzd_grp_min.sort(function(a,b){return a - b;});
				$cuzd_grp_max.sort(function(a,b){return b - a;});
				$cuzd_range_set = $cuzd_grp_min[0] + ' - ' + $cuzd_grp_max[0];
				if ($cuzd_grp_min[0] == $cuzd_grp_max[0]) {
					$cuzd_range_set = $cuzd_grp_min[0];
				}
				if ($cuzd_grp_max[0] > 0){
					$('#cuzd-dispatch-general-g').css('display', '');
					display_label = $grp_label.replace('[range]', $cuzd_range_set);
					display_label = display_label.replace('[br]','<br>');
					$('.cuzd-days-g').html(display_label);
				} else {
					$('#cuzd-dispatch-general-g').css('display', 'none');
				}
				
			}
		}	
		if ($('#cuzd-dispatch-date-g').length){
			cuzd_grp_data =  $('.cuzd-days-g').data('cuzd-id');	
			cuzd_grp_array = [];
			$cuzd_max_date = [];
			$i = 0;
			if (cuzd_grp_data.length != 0){
				$('.quantity').find('input[type="number"]').each( function() {					
					if ($(this).val() > 0) {
						$cuzd_grp_entry = $(this).attr('name').match(/\[(\d+)\]/);
						cuzd_grp_array[$i] = $cuzd_grp_entry[1];
						$i++;
					}
				});
				$.each(cuzd_grp_array, function(arr_id, arr_val) {
				
					$.each(cuzd_grp_data, function(grp_id, grp_val) {
						if (grp_id == arr_val) {		
							if (grp_val.length <= 2){	
								$cuzd_max_date.push(parseInt(grp_val));
							} else {
								$cuzd_max_date.push(grp_val);
							}							
						}
					});
				});			
				$cuzd_max_date.sort(function(a,b){return a - b;});
				if ($cuzd_max_date[0]) {
					 if ($cuzd_max_date[0] > 1) {
                            days = 'days'
                        } else {
                            days = 'day'
                        }
					$('#cuzd-dispatch-date-g').css('display', '');
					display_label = $grp_label.replace('[number]', $cuzd_max_date[0]);
					display_label = display_label.replace('[days]', days);
					display_label = display_label.replace('[date]', $cuzd_max_date[0]);
					display_label = display_label.replace('[br]','<br>');
					$('.cuzd-days-g').html(display_label);
				} else {
					$('#cuzd-dispatch-date-g').css('display', 'none');
				}
			}
		}
			
	});
});