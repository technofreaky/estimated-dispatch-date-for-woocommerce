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



?>