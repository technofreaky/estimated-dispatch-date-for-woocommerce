<?php
global $section;

$section['settings_global'][] = array(
	'id'=>'global',
	'title'=>__('General :',EDDWC_TXT), 
	'validate_callback' =>array( $this, 'validate_section' )
); 


$section['settings_holiday'][] = array(
	'id'=>'holiday',
	'validate_callback' =>array( $this, 'validate_section' )
);