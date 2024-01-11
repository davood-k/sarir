<?php
/**
 * Helper class to handle form-related actions
 */
 // Shortcut function for validate form
// [Optional] set "form_url" for location of the form page
// [Optional] set "rule_set" for name of rule sets in config/form_validation.php (if empty, CodeIgniter will detect as "controller/method" pattern, e.g. "account/update")
function validate_form($type = 'json', $form_url = '', $rule_set = '') {
	$CI =& get_instance();
	$CI->load->library('form_validation');


	if ( $CI->form_validation->run($rule_set) == FALSE )
	{
		if ( validation_errors() )
		{
			if ($type == 'json') {
				result(false, validation_errors());
			}
			else {
				$CI->mViewData['Error'] = validation_errors();
			}
		}
		
		// display form
		return FALSE;
	}
	else
	{
		// success
		return TRUE;
	}
}

