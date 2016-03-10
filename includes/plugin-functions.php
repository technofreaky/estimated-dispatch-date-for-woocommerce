<?php
if(!function_exists('eddwc_option')){
	function eddwc_option($key){
		$value = EDDWC()->get_option($key);
		return $value;
	}
}
if(!function_exists('eddwc_update_variable')){
	function eddwc_update_variable($post_id,$value){
		update_post_meta( $post_id, EDDWCP_METAKEY.'_variable', wc_clean($value) );
	}
}
if(!function_exists('eddwc_update_simple')){
	function eddwc_update_simple($post_id,$value){
		update_post_meta( $post_id, EDDWCP_METAKEY.'_simple', wc_clean($value) );
	}
}
if(!function_exists('eddwc_update_variation')){
	function eddwc_update_variation($post_id,$value){
		update_post_meta( $post_id, EDDWCP_METAKEY.'_variation', wc_clean($value) );
	}
}
if(!function_exists('eddwc_get_variation')){
	function eddwc_get_variation($post_id){
		return get_post_meta($post_id,EDDWCP_METAKEY.'_variation',true);
	}
}
if(!function_exists('eddwc_get_variable')){
	function eddwc_get_variable($post_id){
		return get_post_meta($post_id,EDDWCP_METAKEY.'_variable',true);
	}
}
if(!function_exists('eddwc_get_simple')){
	function eddwc_get_simple($post_id){
		return get_post_meta($post_id,EDDWCP_METAKEY.'_simple',true);
	}
}
if(!function_exists('eddwc_get_external')){
	function eddwc_get_external($post_id){
		return get_post_meta($post_id,EDDWCP_METAKEY.'_external',true);
	}
}
if(!function_exists('eddwc_get_grouped')){
	function eddwc_get_grouped($post_id){
		return get_post_meta($post_id,EDDWCP_METAKEY.'_grouped',true);
	}
}
if(!function_exists('eddwc_get_actual_date')){
	function eddwc_get_actual_date($value){
		$date = eddwc_get_dispatch_date($value);
		return $date;
	}
}
if(!function_exists('eddwc_get_general_date')){
	function eddwc_get_general_date($value,$seperator = ' - '){
		$general_option = eddwc_option('product_general_date_settings');
		$val = explode(',', $value);
		$final_date = '';
		if(isset($val[0]) && !isset($val[1])){
			$final_date = $val[0];
		} else if(isset($val[0]) && isset($val[1])){
			if($val[0] == $val[1]){
				$final_date = $val[0];
			} else {
				if(isset($general_option['actual_date'])){
					$final_date = eddwc_get_dispatch_date($val[0]) .$seperator. eddwc_get_dispatch_date($val[1]);
				} else {
					$final_date = $val[0] .$seperator. $val[1];
				}
			}
		}
		
		return $final_date;
	}
}
if(!function_exists('eddwc_get_static_date')){
	function eddwc_get_static_date($date = ''){
		$eddwc_range = explode(',' , $date);
		if(count($eddwc_range) > 1){
			if(isset($eddwc_range[0]) && isset($eddwc_range[1]) &&  ($eddwc_range[0] > $eddwc_range[1]) ){ 
				$eddwc_range = $eddwc_range[0]; 
			} else { 
				$eddwc_range = $eddwc_range[1]; 
			}			
		} else {
			if(isset($eddwc_range[0])){
				$eddwc_range = $eddwc_range[0]; 
			}
		}

		return $eddwc_range;
	}
}
if(!function_exists('eddwc_get_dispatch_date')){
	function eddwc_get_dispatch_date($date) { 
		$eddwc_holiday = eddwc_option('holiday');
		$eddwc_holidays = array();
		foreach($eddwc_holiday as $eddwc_hday){
			$eddwc_holidays[] = $eddwc_hday['date'];
		}
		
		$eddwc_workdays = eddwc_option('operation_days');
		$cutOff = eddwc_option('day_cutoftime');
		list($cut_hrs,$cut_min) = explode(':',$cutOff);
		$cut_hrs = intval($cut_hrs);
		$cut_min = intval($cut_min);

		$wp_timezone_string = get_option('timezone_string');
		$wp_timezone_offset = get_option('gmt_offset');

		if ($wp_timezone_string) {
			$eddwc_timezone = $wp_timezone_string;
		} else {
			$eddwc_timezone = ini_get('date.timezone');
		}
		date_default_timezone_set($eddwc_timezone);

		$eddwc_date = new DateTime;
		$eddwc_cut_off = $cutOff;
		$eddwc_time = clone $eddwc_date;
		$eddwc_time->setTime($cut_hrs,$cut_min);

		$eddwc_next_date = clone $eddwc_date;

		if ($eddwc_date >= $eddwc_time){
			$eddwc_next_date->modify('+1 day');				
		}

		$i = 0;
		while ($i < $date){
			$eddwc_next_date->modify('+1 day');
			$ndate = strtolower($eddwc_next_date->format('D'));
			if (in_array($ndate, $eddwc_workdays)) {
				//$i++;
				if (in_array($eddwc_next_date->format('d-m-Y'), $eddwc_holidays) == false) {
					$i++;
				}
			}

		}
		return $eddwc_next_date->format(eddwc_option('date_display_format'));
	}
}
?>