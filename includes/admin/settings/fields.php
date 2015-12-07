<?php
global $fields;

/** General Settings **/
$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'where_to_display',
    'type'    => 'multicheckbox',
    'label'   => __( 'Display', EDDWC_TXT),
    'desc'    => __( 'Where to Display',EDDWC_TXT),
    'size '   => 'small',
	'options' => array( // required for field type 'multicheckbox'
		'product_page'   => __( 'Product Page', EDDWC_TXT), // value => label
		'order_page'    => __( 'Order Pages', EDDWC_TXT),
	),
); 

$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'order_page_title',
    'type'    => 'text',
    'label'   => __(  'Order Page Title', EDDWC_TXT),
    'desc'    => __(  'This field is displayed on all order pages and emails.',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'Estimated Dispatch Date:',
); 


$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_page_title',
    'type'    => 'text',
    'label'   => __(  'Product Page Title', EDDWC_TXT),
    'desc'    => __(  'Enter your custom title for the product page.',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'Estimated Dispatch Date:',
); 
 
$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'display_type',
    'type'    => 'radio',
    'label'   => __( 'Product Display Type', EDDWC_TXT),
    'desc'    => __( 'Select which type of format the product page is displayed in.',EDDWC_TXT),
    'size '   => 'small',
	'fieldClass' => 'edd_wc_radio',
	'options' => array( // required for field type 'multicheckbox'
		'actual_date'  => __( 'Actual Date', EDDWC_TXT), // value => label
		'average_date'    => __( 'Average Date', EDDWC_TXT),
		'general_date'    => __( 'General Date', EDDWC_TXT),
		
	),
);



$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_actual_title',
    'type'    => 'text',
    'label'   => __(  'Product Actual Title', EDDWC_TXT),
    'desc'    => __(  'Enter your custom text for the product page, using the variable delimiter <code> [date] </code>',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'This product should be dispatched on [date].',
); 


$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_average_title',
    'type'    => 'text',
    'label'   => __(  'Product Average Title', EDDWC_TXT),
    'desc'    => __(  'Enter your custom text for the product page, using the variable delimiters <code>[number]</code> & <code>[days]</code>',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'This product is usually dispatched in [number] working [days].',
);
	
$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_average_day_trans',
    'type'    => 'text',
    'label'   => __(  'Product Average Days', EDDWC_TXT),
    'desc'    => __(  'Enter in the singular and plural translation for "day" and "days" seperated by a comma. <code>day,days</code>',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'day,days',
);


$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_general_title',
    'type'    => 'text',
    'label'   => __(  'Product General Title', EDDWC_TXT),
    'desc'    => __(  'Enter your custom text for the product page, using the variable delimiter <code>[range]</code>',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'Usually shipped in [range] working days.',
);
	

 
$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'product_general_date_settings',
    'type'    => 'multicheckbox',
    'label'   => __( 'General Date Settings', EDDWC_TXT),
    'desc'    => __( '',EDDWC_TXT),
    'size '   => 'large',
	'fieldClass' => '',
	'options' => array( // required for field type 'multicheckbox'
		'actual_date'  => __( 'General Actual Date  <small><i>  [Use the actual dates in range e.g. <code>01/12/2015 - 05/12/2015 </code>]</i> </small>', EDDWC_TXT), 
		'range_date_checkout'    => __( 'General Range Date Checkout <small> <i> [Use the range dates on cart, checkout and email pages]</i> </small>', EDDWC_TXT), 
		
	),
);


$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'general_range_title',
    'type'    => 'text',
    'label'   => __(  'General Range Checkout Field', EDDWC_TXT),
    'desc'    => __(  'Append the date range with the field in cart, checkout and email pages.',EDDWC_TXT),
    'size '   => 'large',
	'default' => 'Estimated Dispatch Date:',
); 


$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'day_cutoftime',
    'type'    => 'text',
    'label'   => __( 'Day Cut-off Time', EDDWC_TXT),
    'desc'    => __( 'Orders placed after this time will be processed the following day.',EDDWC_TXT),
    'size '   => 'small',
	'attr' => array('data-picker' => 'time'),
	 
);

$fields['settings_global']['global'][] = array(
    'id'      =>  EDDWC_DB.'date_display_format',
    'type'    => 'select',
    'label'   => __( 'Date Display', EDDWC_TXT),
    'desc'    => __( 'Choose the format that dates should be displayed in.',EDDWC_TXT),
    'size '   => 'small',
	'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;'),
	'options' =>array(
						'd/m/Y'	=>	'DD/MM/YYYY',
						'Y/m/d'	=>	'YYYY/MM/DD',
						'm/d/Y'	=>	'MM/DD/YYYY',
						'd-m-Y'	=>	'DD-MM-YYYY',
						'Y-m-d'	=>	'YYYY-MM-DD',
						'm-d-Y'	=>	'DD-MM-YYYY',
						'd.m.Y'	=>	'DD.MM.YYYY',
						'Y.m.d'	=>	'YYYY.MM.DD',
						'm.d.Y'	=>	'MM.DD.YYYY',
						'd M Y'	=>	'DD MON YYYYY'
					)
	 
);


$fields['settings_operations']['operations'][] = array(
    'id'      =>  EDDWC_DB.'operation_days',
    'type'    => 'select',
    'label'   => __( 'Working Days', EDDWC_TXT),
    'desc'    => __( 'Choose the format that dates should be displayed in.',EDDWC_TXT),
    'size '   => 'small',
	'attr'    => array('class' => 'wc-enhanced-select','multiple' => 'multiple','style' => 'width:auto;max-width:35%;'),
	'options' => array(
		'mon' => __('Monday',EDDWC_TXT),
		'tue' => __('Tuesday',EDDWC_TXT),
		'wed' => __('Wednesday',EDDWC_TXT),
		'thu' => __('Thursday',EDDWC_TXT),
		'fri' => __('Friday',EDDWC_TXT),
		'sat' => __('Saturday',EDDWC_TXT),
		'sun' => __('Sunday',EDDWC_TXT),
	)
);