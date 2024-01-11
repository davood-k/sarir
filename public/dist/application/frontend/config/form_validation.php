<?php 

/**
 * Config file for form validation
 * Reference: https://ellislab.com/codeigniter/user-guide/libraries/form_validation.html
 * (Under section "Creating Sets of Rules")
 */

$config = array(

	// Login
	'main/login' => array(
		array(
			'field'		=> 'u',
			'label'		=> 'نام کاربری',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'p',
			'label'		=> 'رمز عبور',
			'rules'		=> 'required',
		),
	),

);