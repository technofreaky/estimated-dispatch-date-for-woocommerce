<?php
global $send_fields;

if (empty($send_fields[EDDWC_DB.'payment_gateway'])) {
    add_settings_error(EDDWC_DB.'payment_gateway','', __( 'Error: Please Select Atlest 1 Payment Gateway.', EDDWC_TXT ),'error');
}