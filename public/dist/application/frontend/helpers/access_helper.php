<?php
function getCode($form) {
	$res = 0;

	switch (strtolower($form)) {
		case 'roles':
			$res = 1;
			break;
		case 'users':
			$res = 2;
			break;
		case 'teams':
			$res = 3;
			break;
		case 'config':
			$res = 4;
			break;
		case'customers':
			$res = 5;
			break;
		case'call_list':
			$res = 6;
			break;
		case'tel_tour_admin':
			$res = 7;
			break;
		case'tel_tour':
			$res = 8;
			break;
		case'visit_tour_admin':
			$res = 9;
			break;
		case'visit_tour':
			$res = 10;
			break;
		case'import':
			$res = 11;
			break;
		case 'products':
			$res = 12;
			break;
		case 'offers':
			$res = 13;
			break;
		case 'mali':
			$res = 14;
			break;
		case 'store':
			$res = 15;
			break;
		case 'distribute':
			$res = 16;
			break;
		case 'collector':
			$res = 17;
			break;
		case 'support':
			$res = 18;
			break;
		case 'target':
			$res = 19;
			break;
		case 'advisor':
			$res = 20;
			break;
	}

	return $res;
}

function hasAccess($pre, $form) {
	$CI =& get_instance();
	$code = getCode($form);

	if (strpos($CI->Access, '#' . $pre . $code . '#') === FALSE) {
		return false;
	}
	else
		return true;
}

function get_form() {
	$CI =& get_instance();

	return $CI->mCtrler . '/' . $CI->mAction;
}

function hasAdd($form = '') {
	if (empty($form)) 
		$form = get_form();

	return hasAccess('a', $form);
}

function hasView($form = '') {
	return hasAccess('', $form);
}

function hasDel($form = '') {
	return hasAccess('d', $form);
}

function hasEdit($form = '') {
	return hasAccess('e', $form);
}
?>