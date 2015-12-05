<?php
global $section;

$section['settings_global'][] = array(
	'id'=>'global',
	'title'=>__('General :',EDDWC_TXT), 
	'validate_callback' =>array( $this, 'validate_section' )
); 


$section['settings_operations'][] = array(
	'id'=>'operations',
	'title'=>__('Operations :',EDDWC_TXT), 
	'validate_callback' =>array( $this, 'validate_section' )
);
