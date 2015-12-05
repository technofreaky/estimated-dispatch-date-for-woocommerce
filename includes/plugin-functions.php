<?php
if(!function_exists('eddwc_option')){
	function eddwc_option($key){
		$value = EDDWC()->get_option($key);
		return $value;
	}
}

?>