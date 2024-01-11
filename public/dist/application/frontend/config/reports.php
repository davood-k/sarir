<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$CI =& get_instance();


	$config_country = array(
		'Type'		=> 'select',
		'Options'	=> 'SELECT ID, Name FROM tcountries',
		'DefaultOptions' => 'SELECT ID, Name FROM tcountries',
		'Where'		=> "Country IN (#value#)",
		'Title'		=> 'کشور',
		'ListTitle' => 'کشورها',
		'InRelation'=> array( 'State', 'City', 'Area', 'Block' ),
		'Default'	=> intval($CI->User['DefaultCountry'])	
	);

	$config_state = array(
		'Type'		=> 'select',
		'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
		'DefaultOptions' => 'SELECT ID, Name FROM bstates WHERE CountryID IN (' . intval($CI->User['DefaultCountry']) . ')',
		'Where'		=> $_GET['report_name'] == 'orders_to_verify' ? 'cu.State IN (#value#)' : "c.State IN (#value#)",
		'Title'		=> 'استان',
		'ListTitle' => 'استان ها',
		'Relation'	=> 'Country',
		'InRelation'=> array( 'City', 'Area', 'Block' ),
		'EmptyError'=> 'کشور را انتخاب نمایید',
		'Default'	=> intval($CI->User['DefaultState'])	
	);

	$config_city = array(
		'Type'		=> 'select',
		'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', t.Name) AS Name FROM bcities t INNER JOIN bstates s ON (t.SID = s.ID) WHERE t.SID IN (?)",
		'DefaultOptions' => "SELECT t.ID, CONCAT(s.Name, ' - ', t.Name) AS Name FROM bcities t INNER JOIN bstates s ON (t.SID = s.ID) WHERE t.SID IN (" . intval($CI->User['DefaultState']) . ')',
		'Where'		=> $_GET['report_name'] == 'orders_to_verify' ? 'cu.City IN (#value#)' : "c.City IN (#value#)",
		'Title'		=> 'شهر',
		'ListTitle' => 'شهر ها',
		'Relation'	=> 'State',
		'InRelation'=> array( 'Area', 'Block' ),
		'EmptyError'=> 'استان را انتخاب نمایید',
		'Default'	=> intval($CI->User['DefaultCity'])	
	);

	$config_area = array(
		'Type'		=> 'select',
		'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', c.Name, ' - ', t.Name) AS Name FROM bareas t INNER JOIN bcities c ON (t.CityID = c.ID) INNER JOIN bstates s ON (c.SID = s.ID) WHERE t.CityID IN (?)",
		'Where'		=> "Area IN (#value#)",
		'Title'		=> 'منطقه',
		'ListTitle' => 'منطقه ها',
		'Relation'	=> 'City',
		'InRelation'=> array('Block'),
		'EmptyError'=> 'شهر را انتخاب نمایید'
	);

	$config_block = array(
		'Type'		=> 'select',
		'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', c.Name, ' - ', a.Name, ' - ', t.Name) AS Name FROM bblocks t INNER JOIN bareas a ON (t.AreaID = a.ID) INNER JOIN bcities c ON (a.CityID = c.ID) INNER JOIN bstates s ON (c.SID = s.ID) WHERE t.AreaID IN (?)",
		'Where'		=> "Block IN (#value#)",
		'Title'		=> 'بلوک',
		'ListTitle' => 'بلوک ها',
		'Relation'	=> 'Area',
		'EmptyError'=> 'منطقه را انتخاب نمایید'
	);

	function calls_add_data() {
		$CI =& get_instance();

		$CI->mViewData['Results'] = $CI->db->query('SELECT ID, Title, Operation FROM ttour_call_results WHERE Type=1')->result();
		$CI->mViewData['T1Day'] = $CI->getShamsiDate(time() + (24 * 60 * 60));
		$CI->mViewData['T2Day'] = $CI->getShamsiDate(time() + (2 * 24 * 60 * 60));
		$CI->mViewData['T3Day'] = $CI->getShamsiDate(time() + (3 * 24 * 60 * 60));
		$CI->mViewData['T4Day'] = $CI->getShamsiDate(time() + (4 * 24 * 60 * 60));

	}

	function support_order_add_data() {
		$CI =& get_instance();
		$checks = $CI->db->query('SELECT ID, GroupID, Name FROM tcheck_lists WHERE Deleted=0 AND Active=1')->result();

		foreach ($checks as $check) {
			$check->Items = $CI->db->query('SELECT Value, Name FROM tcheck_list_items WHERE CheckID=? AND Deleted=0 ORDER BY Priority', array($check->ID))->result();
		}

		$CI->mViewData['Checks'] = $checks;

		$CI->mViewData['Results'] = $CI->db->query('SELECT ID, Name, Color FROM treview_results WHERE Deleted=0')->result();
	}

	$global_filters = $CI->config->item('global_filters');

	$tour_chart = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار تعدادی و مبلغی فروش تیم ها',
			'FirstType'  => 'all_teams',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'all_teams'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش تیم ها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'TeamID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'team_visitors',
							'Input'	=> 'tid'
						)
					),
					'Inputs' => array('id'),		
					'Query'	=> "SELECT t.TeamID, team.Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(orders.AllPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
								LEFT OUTER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.CallID AND orders.IsBuy=0)
								WHERE t.TourID=#id# 
								GROUP BY t.TeamID, team.Name ",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.CallID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# 
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'team_visitors'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش ویزیتورها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'VisitorID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'by_brand',
							'Input'	=> 'vid'
						)
					),
					'Inputs' => array('id', 'tid'),		
					'Query'	=> "SELECT t.VisitorID, CONCAT(u.FName, ' ', u.LName) AS Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(orders.AllPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.CallID)
								WHERE t.TourID=#id# AND t.TeamID=#tid#
								GROUP BY t.VisitorID, u.FName, u.LName ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.CallID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'by_brand'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش برندها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'NameFa',
						'FieldID'	=> 'BID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'items',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id', 'tid', 'vid'),		
					'Query'	=> "SELECT p.BID, b.NameFa, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(orders.AllPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.CallID)
								LEFT OUTER JOIN torder_items items ON (orders.ID = items.OID)
								LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
								LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
								WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) <> 0
								GROUP BY p.BID, b.NameFa ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.CallID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'items'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش کالاها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'PID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						)
					),
					'Inputs' => array('id', 'tid', 'vid', 'bid'),		
					'Query'	=> "SELECT p.ID AS PID, p.Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(items.TotalPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.CallID)
								LEFT OUTER JOIN torder_items items ON (orders.ID = items.OID)
								LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
								LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
								WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) =#bid#
								GROUP BY p.ID, p.Name ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.CallID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) =#bid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				)
			)
		)
	);

	$visit_chart = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار تعدادی و مبلغی فروش تیم ها',
			'FirstType'  => 'all_teams',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'all_teams'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش تیم ها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'TeamID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'team_visitors',
							'Input'	=> 'tid'
						)
					),
					'Inputs' => array('id'),		
					'Query'	=> "SELECT t.TeamID, team.Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(orders.AllPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
								LEFT OUTER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.VisitID AND orders.IsBuy=0)
								WHERE t.TourID=#id# 
								GROUP BY t.TeamID, team.Name ",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.VisitID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# 
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'team_visitors'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش ویزیتورها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'VisitorID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'by_brand',
							'Input'	=> 'vid'
						)
					),
					'Inputs' => array('id', 'tid'),		
					'Query'	=> "SELECT t.VisitorID, CONCAT(u.FName, ' ', u.LName) AS Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(orders.AllPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.VisitID)
								WHERE t.TourID=#id# AND t.TeamID=#tid#
								GROUP BY t.VisitorID, u.FName, u.LName ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.VisitID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'by_brand'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش برندها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'NameFa',
						'FieldID'	=> 'BID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'items',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id', 'tid', 'vid'),		
					'Query'	=> "SELECT p.BID, b.NameFa, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(items.TotalPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.VisitID)
								LEFT OUTER JOIN torder_items items ON (orders.ID = items.OID)
								LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
								LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
								WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) <> 0
								GROUP BY p.BID, b.NameFa ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.VisitID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'items'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش کالاها',
					'Colors' => array('#03A9F4', '#FFAB91'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'PID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						)
					),
					'Inputs' => array('id', 'tid', 'vid', 'bid'),		
					'Query'	=> "SELECT p.ID AS PID, p.Name, COUNT(DISTINCT orders.ID) AS `Count`, IFNULL(SUM(items.TotalPrice), 0) AS AllPrice
								FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID)
								LEFT OUTER JOIN ttour_visitor_customers to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
								LEFT OUTER JOIN torders orders ON (to_call.ID = orders.VisitID)
								LEFT OUTER JOIN torder_items items ON (orders.ID = items.OID)
								LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
								LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
								WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) =#bid#
								GROUP BY p.ID, p.Name ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT team.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست'
					FROM ttour_visitors t INNER JOIN tteams team ON (t.TeamID = team.ID)
					INNER JOIN ttour_list_to_call to_call ON (t.TourID = to_call.TourID AND t.VisitorID = to_call.VisitorID)
					INNER JOIN torders o ON (to_call.ID = o.CallID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = t.VisitorID)
					WHERE t.TourID=#id# AND t.TeamID=#tid# AND t.VisitorID=#vid# AND IFNULL(b.ID, 0) =#bid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				)
			)
		)
	);

	$team_chart = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار تعدادی و مبلغی فروش تیم ها',
			'FirstType'  => 'all_teams',
			'Queries'		=> array(
				'all_teams'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش تیم ها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'TeamID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'تعداد سفارشات',
								'Field' => 'Orders'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'team_visitors',
							'Input'	=> 'tid'
						)
					),
					'Inputs' => array('id'),		
					'Query'	=> "SELECT tu.TeamID, t.Name, COUNT(DISTINCT o.UID) AS `Count`, COUNT(DISTINCT o.ID) AS `Orders`, IFNULL(SUM(o.AllPrice), 0) AS AllPrice
								FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
								INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsBuy=0 AND o.IsReturn=0 AND o.FromCrm=1 AND o.OrderState=3)
								WHERE 1=1 #where#
								GROUP BY tu.TeamID, t.Name ",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT t.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'اپراتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', state.Name AS 'وضعیت درخواست', c.ShopName AS 'نام مشتری', o.ShSanad AS 'شماره سند مالی', o.TarikhSanad AS 'تاریخ فاکتور مالی'
					FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
					INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsBuy=0 AND o.IsReturn=0 AND o.FromCrm=1 AND o.OrderState=3)
					INNER JOIN tcustomers c ON (o.UID = c.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = tu.UserID)
					WHERE 1=1 #where# 
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'team_visitors'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش ویزیتورها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'UserID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'تعداد سفارشات',
								'Field' => 'Orders'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'by_brand',
							'Input'	=> 'uid'
						)
					),
					'Inputs' => array('id', 'tid'),		
					'Query'	=> "SELECT tu.UserID, CONCAT(u.FName, ' ', u.LName) AS Name, COUNT(DISTINCT o.UID) AS `Count`, COUNT(DISTINCT o.ID) AS `Orders`, IFNULL(SUM(o.AllPrice), 0) AS AllPrice
						FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
						INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
						INNER JOIN tuser u ON (tu.UserID = u.ID)
						WHERE t.ID=#tid# #where#
						GROUP BY tu.UserID, Name ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT t.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'اپراتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', state.Name AS 'وضعیت درخواست', c.ShopName AS 'نام مشتری', o.ShSanad AS 'شماره سند مالی', o.TarikhSanad AS 'تاریخ فاکتور مالی'
					FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
					INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
					INNER JOIN tcustomers c ON (o.UID = c.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = tu.UserID)
					WHERE t.ID=#tid# #where# 
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'by_brand'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش برندها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'NameFa',
						'FieldID'	=> 'BID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'تعداد سفارشات',
								'Field' => 'Orders'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						),
						'Click'	=> array(
							'Type'	=> 'items',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id', 'tid', 'uid'),		
					'Query'	=> "SELECT p.BID, b.NameFa, COUNT(DISTINCT o.UID) AS `Count`, COUNT(DISTINCT o.ID) AS `Orders`, IFNULL(SUM(items.TotalPrice), 0) AS AllPrice
					FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
					INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
					INNER JOIN tuser u ON (tu.UserID = u.ID)
					LEFT OUTER JOIN torder_items items ON (o.ID = items.OID)
					LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
					LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
					WHERE t.ID=#tid# AND tu.UserID=#uid# AND IFNULL(b.ID, 0) <> 0 #where#
					GROUP BY p.BID, b.NameFa",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT t.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست', cu.ShopName AS 'نام مشتری', o.ShSanad AS 'شماره سند مالی', o.TarikhSanad AS 'تاریخ فاکتور مالی'
					FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
					INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
					INNER JOIN tcustomers cu ON (o.UID = cu.ID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = tu.UserID)
					WHERE t.ID=#tid# AND tu.UserID=#uid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'items'	=> array(
					'Title'	 => 'آمار تعدادی و مبلغی فروش کالاها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Name',
						'FieldID'	=> 'PID',
						'Values'	=> array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Field' => 'Count'
							),
							array(
								'Title'	=> 'تعداد سفارشات',
								'Field' => 'Orders'
							),
							array(
								'Title'	=> 'مبلغ کل فروش',
								'Field'	=> 'AllPrice'
							)
						)
					),
					'Inputs' => array('id', 'tid', 'uid', 'bid'),		
					'Query'	=> "SELECT p.ID AS PID, p.Name, COUNT(DISTINCT o.UID) AS `Count`, COUNT(DISTINCT o.ID) AS `Orders`, IFNULL(SUM(items.TotalPrice), 0) AS AllPrice
								FROM tteam_users tu INNER JOIN tteams t ON (tu.TeamID = t.ID)
								LEFT OUTER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
								LEFT OUTER JOIN torder_items items ON (o.ID = items.OID)
								LEFT OUTER JOIN tproducts p ON (items.IID = p.ID)
								LEFT OUTER JOIN tbrands b ON (p.BID = b.ID)
								WHERE t.ID=#tid# AND tu.UserID=#uid# AND IFNULL(b.ID, 0)=#bid#
								GROUP BY p.ID, p.Name ",
					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT t.Name AS 'نام تیم', CONCAT(u.FName , ' ', u.LName) AS 'ویزیتور', o.ID AS 'شناسه فاکتور', CONCAT(o.ShamsiDate, ' ', SUBSTR(o.CreateDate, 10)) AS 'تاریخ درخواست', o.AllPrice AS 'مبلغ کل فاکتور', p.Code AS 'کد محصول', p.Name AS 'نام محصول', b.NameFa AS 'برند', c.Name AS 'دسته محصول', oi.Count AS 'تعداد', oi.Price AS 'قیمت محصول', oi.TotalPrice AS 'مبلغ کل محصول', state.Name AS 'وضعیت درخواست', cu.ShopName AS 'نام مشتری', o.ShSanad AS 'شماره سند مالی', o.TarikhSanad AS 'تاریخ فاکتور مالی'
					FROM tteam_users tu INNER JOIN tteams t ON (t.ID = tu.TeamID)
					INNER JOIN torders o ON (tu.UserID = o.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3)
					INNER JOIN tcustomers cu ON (o.UID = cu.ID)
					INNER JOIN torder_items oi ON (oi.OID = o.ID)
					INNER JOIN tproducts p ON (oi.IID = p.ID)
					INNER JOIN tcats c ON (p.CID = c.ID)
					INNER JOIN tbrands b ON (p.BID = b.ID)
					INNER JOIN torder_states state ON (o.OrderState = state.ID)
					INNER JOIN tuser u ON (u.ID = tu.UserID)
					WHERE t.ID=#tid# AND tu.UserID=#uid# AND IFNULL(b.ID, 0) =#bid#
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				)
			)
		)
	);

	$chart_by_visitor = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت بر اساس ویزیتور',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار فروش ویزیتورها',
			'FirstType'  => 'visitors',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'visitors'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک ویزیتورها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Visitor',
						'FieldID'	=> 'VisitorID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'brands',
							'Input'	=> 'vid'
						)
					),
					'Inputs' => array('id'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, VisitorID, Visitor, OldAverage)
						SELECT #uid#, o.UID, o.UserID, CONCAT(u.FName, ' ', u.LName), SUM(AllPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN tuser u ON (o.UserID=u.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# #where#
						GROUP BY o.UID, o.UserID, u.FName, u.LName",
						"INSERT INTO chart_customer_buys (UID, CustomerID, VisitorID, Visitor, NewBuys)
						SELECT #uid#, o.UID, o.UserID, CONCAT(u.FName, ' ', u.LName), SUM(AllPrice)
						FROM torders o INNER JOIN tuser u ON (o.UserID = u.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# #where#
						GROUP BY o.UID, o.UserID",
							),	
					'Query'	=> "SELECT VisitorID, Visitor, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY VisitorID, Visitor",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Visitor AS 'ویزیتور', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY VisitorID, Visitor
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'brands'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک برندها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Brand',
						'FieldID'	=> 'BrandID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'products',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id', 'vid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, OldAverage)
						SELECT #uid#, o.UID, b.NameFa, p.BID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND o.UserID=#vid# #where#
						GROUP BY o.UID, o.UserID, p.BID, b.NameFa",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, NewBuys)
						SELECT #uid#, o.UID, b.NameFa, p.BID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND o.UserID=#vid# #where#
						GROUP BY o.UID, o.UserID, p.BID, b.NameFa",
							),	
					'Query'	=> "SELECT BrandID, Brand, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Brand AS 'برند', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'products'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک محصولات',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Product',
						'FieldID'	=> 'ProductID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
					),
					'Inputs' => array('id', 'vid', 'bid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, OldAverage)
						SELECT #uid#, o.UID, p.Name, p.ID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND o.UserID=#vid# AND p.BID=#bid# #where#
						GROUP BY o.UID, o.UserID, p.ID, p.Name",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, NewBuys)
						SELECT #uid#, o.UID, p.Name, p.ID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN(1,9) AND o.FromCrm=1 AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND o.UserID=#vid# AND p.BID=#bid# #where#
						GROUP BY o.UID, o.UserID, p.ID, p.Name",
							),	
					'Query'	=> "SELECT ProductID, Product, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Product AS 'کالا', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
			)
		)
	);

	$chart_by_brand = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت بر اساس برند',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار فروش برندها',
			'FirstType'  => 'brands',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'brands'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک برندها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Brand',
						'FieldID'	=> 'BrandID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'products',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, OldAverage)
						SELECT #uid#, o.UID, b.NameFa, p.BID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1,9) AND o.FromCrm=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# #where#
						GROUP BY o.UID, o.UserID, p.BID, b.NameFa",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, NewBuys)
						SELECT #uid#, o.UID, b.NameFa, p.BID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# #where#
						GROUP BY o.UID, o.UserID, p.BID, b.NameFa",
							),	
					'Query'	=> "SELECT BrandID, Brand, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Brand AS 'برند', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'products'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک محصولات',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Product',
						'FieldID'	=> 'ProductID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
					),
					'Inputs' => array('id', 'bid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, OldAverage)
						SELECT #uid#, o.UID, p.Name, p.ID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND p.BID=#bid# #where#
						GROUP BY o.UID, o.UserID, p.ID, p.Name",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, NewBuys)
						SELECT #uid#, o.UID, p.Name, p.ID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND o.UID=#id# AND p.BID=#bid# #where#
						GROUP BY o.UID, o.UserID, p.ID, p.Name",
							),	
					'Query'	=> "SELECT ProductID, Product, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Product AS 'کالا', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
			)
		)
	);

	$chart_team_by_visitor = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت بر اساس ویزیتور',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار فروش ویزیتورها',
			'FirstType'  => 'visitors',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'visitors'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک ویزیتورها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Visitor',
						'FieldID'	=> 'VisitorID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'brands',
							'Input'	=> 'vid'
						)
					),
					'Inputs' => array('id'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, VisitorID, Visitor, OldAverage)
						SELECT #uid#, tu.TeamID, o.UserID, CONCAT(u.FName, ' ', u.LName), SUM(AllPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN tuser u ON (o.UserID=u.ID)
						INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# #where#
						GROUP BY tu.TeamID, o.UserID, u.FName, u.LName",
						"INSERT INTO chart_customer_buys (UID, CustomerID, VisitorID, Visitor, NewBuys)
						SELECT #uid#, tu.TeamID, o.UserID, CONCAT(u.FName, ' ', u.LName), SUM(AllPrice)
						FROM torders o INNER JOIN tuser u ON (o.UserID = u.ID)
						INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# #where#
						GROUP BY tu.TeamID, o.UserID",
							),	
					'Query'	=> "SELECT VisitorID, Visitor, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY VisitorID, Visitor",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Visitor AS 'ویزیتور', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY VisitorID, Visitor
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'brands'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک برندها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Brand',
						'FieldID'	=> 'BrandID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'products',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id', 'vid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, OldAverage)
						SELECT #uid#, tu.TeamID, b.NameFa, p.BID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND o.UserID=#vid# #where#
						GROUP BY tu.TeamID, o.UserID, p.BID, b.NameFa",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, NewBuys)
						SELECT #uid#, tu.TeamID, b.NameFa, p.BID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND o.UserID=#vid# #where#
						GROUP BY tu.TeamID, o.UserID, p.BID, b.NameFa",
							),	
					'Query'	=> "SELECT BrandID, Brand, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Brand AS 'برند', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'products'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک محصولات',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Product',
						'FieldID'	=> 'ProductID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
					),
					'Inputs' => array('id', 'vid', 'bid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, OldAverage)
						SELECT #uid#, tu.TeamID, p.Name, p.ID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND o.UserID=#vid# AND p.BID=#bid# #where#
						GROUP BY tu.TeamID, o.UserID, p.ID, p.Name",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, NewBuys)
						SELECT #uid#, tu.TeamID, p.Name, p.ID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tteam_users tu ON (tu.UserID = o.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND o.UserID=#vid# AND p.BID=#bid# #where#
						GROUP BY tu.TeamID, o.UserID, p.ID, p.Name",
							),	
					'Query'	=> "SELECT ProductID, Product, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Product AS 'کالا', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
			)
		)
	);

	$chart_team_by_brand = array(
		'Icon'	=> 'fas fa-search',
		'Title'	=> 'مشاهده چارت بر اساس برند',
		'Action'=> array(
			'Type'	=> 'chart',
			'ChartTitle' => 'آمار فروش برندها',
			'FirstType'  => 'brands',
			'Inputs' => array(
				'id'	=> 'ID'
			),
			'Queries'		=> array(
				'brands'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک برندها',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Brand',
						'FieldID'	=> 'BrandID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
						'Click'	=> array(
							'Type'	=> 'products',
							'Input'	=> 'bid'
						)
					),
					'Inputs' => array('id'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, OldAverage)
						SELECT #uid#, tu.TeamID, b.NameFa, p.BID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tteam_users tu ON (tu.UserID = o.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# #where#
						GROUP BY tu.TeamID, o.UserID, p.BID, b.NameFa",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Brand, BrandID, NewBuys)
						SELECT #uid#, tu.TeamID, b.NameFa, p.BID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tbrands b ON (p.BID = b.ID)
						INNER JOIN tteam_users tu ON (tu.UserID = o.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# #where#
						GROUP BY tu.TeamID, o.UserID, p.BID, b.NameFa",
							),	
					'Query'	=> "SELECT BrandID, Brand, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Brand AS 'برند', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY BrandID, Brand
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
				'products'	=> array(
					'Title'	 => 'آمار خرید مشتری به تفکیک محصولات',
					'Colors' => array('#03A9F4', '#FFAB91', '#4CAF50'),
					'Data' 	 => array(
						'Type'	=> 'by_row',
						'FieldName' => 'Product',
						'FieldID'	=> 'ProductID',
						'Values'	=> array(
							array(
								'Title'	=> 'مبلغ میانگین ماه های قبل',
								'Field' => 'OldAverage'
							),
							array(
								'Title'	=> 'مبلغ خرید ماه جاری',
								'Field' => 'NewBuys'
							),
						),
					),
					'Inputs' => array('id', 'bid'),
					"PreQueries" => array(
						"DELETE FROM chart_customer_buys WHERE UID=#uid#",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, OldAverage)
						SELECT #uid#, tu.TeamID, p.Name, p.ID, SUM(oi.TotalPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tteam_users tu ON (tu.UserID = o.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND p.BID=#bid# #where#
						GROUP BY tu.TeamID, o.UserID, p.ID, p.Name",
						"INSERT INTO chart_customer_buys (UID, CustomerID, Product, ProductID, NewBuys)
						SELECT #uid#, tu.TeamID, p.Name, p.ID, SUM(oi.TotalPrice)
						FROM torders o INNER JOIN torder_items oi ON (o.ID = oi.OID)
						INNER JOIN tproducts p ON (oi.IID = p.ID)
						INNER JOIN tteam_users tu ON (tu.UserID = o.UserID)
						INNER JOIN tcustomers c ON (o.UID = c.ID)
						WHERE o.Status IN (1, 9) AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND o.UserID<>0 AND tu.TeamID=#id# AND p.BID=#bid# #where#
						GROUP BY tu.TeamID, o.UserID, p.ID, p.Name",
							),	
					'Query'	=> "SELECT ProductID, Product, SUM(OldAverage) AS OldAverage, SUM(NewBuys) AS NewBuys
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product",

					'HasDetails'	=> true,
					'DetailsQuery'	=> "SELECT Product AS 'کالا', SUM(OldAverage) AS 'میانگین ماه های قبل', SUM(NewBuys) AS 'فروش ماه جاری'
					FROM chart_customer_buys
					WHERE UID=#uid#
					GROUP BY ProductID, Product
					",
					'DetailsFileName'	=> "",
					'ReportFileName'=> '',
					'CountPerPage'	=> 8
				),
			)
		)
	);

	function get_all_products_file() {
		$CI =& get_instance();

		$sql = "SELECT p.ID AS 'شناسه محصول', p.Name AS 'نام محصول', p.Code AS 'کد محصول', c.Name AS 'دسته', b.NameFa AS 'برند', u.Name AS 'واحد', m.Price AS 'قیمت', IFNULL(pm.Mojodi, 0) AS 'موجودی', p.Active AS 'وضعیت'";

		$groups = $CI->db->query('SELECT ID, Name FROM tgroups')->result();

		foreach ($groups as $item) {
			$sql .= ", IFNULL(g" . $item->ID . ".Price, 0) AS 'قیمت " . $item->Name . "'";
		}

		$sql .= " FROM tproducts p LEFT OUTER JOIN tcats c ON (p.CID = c.ID)
		INNER JOIN tproduct_mojodi m ON (p.ID = m.PID AND m.Active=1)
		INNER JOIN tbrands b ON (p.BID = b.ID)
		LEFT OUTER JOIN tunits u ON (p.Unit = u.ID)
		LEFT OUTER JOIN tproduct_temp_mojodi pm ON (p.ID = pm.ID AND pm.UID=#uid#)
		LEFT OUTER JOIN (SELECT Parent, COUNT(*) AS cnt FROM tproducts WHERE Deleted=0 GROUP BY Parent) p1 ON (p.ID = p1.Parent)
		LEFT OUTER JOIN tproducts parent ON (p.Parent = parent.ID)";
		foreach ($groups as $item) {
			$sql .= " LEFT OUTER JOIN tgroup_prices g" . $item->ID . " ON (p.ID = g{$item->ID}.ProductID AND g{$item->ID}.GroupID={$item->ID}) ";
		}


		$sql .= " WHERE p.Deleted=0 #where#";

		return $sql;
	}

	$config['reports'] = array(
		'customers' => array(
			'Title'		=> 'لیست مشتریان',
			'Access'	=> 'customers',
			'Controller'=> 'customers',
			'Action'	=> 'index',
			'ExtraView' => 'customers/list',
			'Filters'	=> array(
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'CustomerState' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcustomer_states WHERE Deleted=0',
					'Where'		=> "c.CustomerState IN (#value#)",
					'Title'		=> 'وضعیت مشتری',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد',
				),
				'User' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(u.FName, ' ', u.LName) AS Name FROM tuser u WHERE Deleted=0",
					'Where'		=> "c.UserID IN (#value#)",
					'Title'		=> 'کاربر ثبت کننده',
					'ListTitle' => 'کاربر ها',
					'EmptyError'=> 'کاربری پیدا نشد',
				),
				'Group' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, Name FROM tgroups",
					'Where'		=> "c.GroupID IN (#value#)",
					'Title'		=> 'گروه مشتری',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد',
				),
			),
			'Actions'	=> array(
				array(
					'Title'	=> 'مشتری جدید',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewCustomer();'
				)
			),
			'Query'		=> "SELECT c.ID, c.ShHesab AS Code, c.ShopName, c.Tel, gr.Name AS `Group`, c.CustomerState, IFNULL(s.Name, '') AS State, IFNULL(grade.Name, '') AS Grade, IFNULL(CONCAT(u.FName, ' ', u.LName), '') AS User, c.Address1 AS Address, a.Name as Area, c.Username, CASE WHEN c.Password <> '' THEN 1 ELSE 0 END AS Password, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, IFNULL(r.Title, '-') AS LastTelResult, IFNULL(v.Title, '-') AS LastVisitResult
							FROM tcustomers c " . ($CI->User['Admin'] == 1 ? '' : "INNER JOIN  tuser_access_groups g ON (c.GroupID = g.GroupID AND g.UID=#uid#)") . "
							LEFT OUTER JOIN tgroups gr ON (c.GroupID = gr.ID)
							LEFT OUTER JOIN tcustomer_states s ON (c.CustomerState = s.ID)
							LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
							LEFT OUTER JOIN tuser u ON (c.UserID = u.ID)
							LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
							LEFT OUTER JOIN tcustomer_persons p ON (c.MalekID = p.ID)
							LEFT OUTER JOIN ttour_call_results r ON (c.LastTelResult = r.ID)
							LEFT OUTER JOIN ttour_call_results v ON (c.LastVisitResult = r.ID)
							LEFT OUTER JOIN tuser ru ON (c.Agent = ru.ID)
							WHERE c.Deleted=0 AND c.IsShop=1 #where# ",
			'FileQuery'=> '',
			'FileSelect'=> "SELECT IFNULL(CONCAT(ru.FName, ' ', ru.LName), '') AS 'معرف', c.ShHesab AS 'کد مشتری', c.CodeMelli AS 'کد ملی', c.ShopName as 'نام ', CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS 'نام مالک', c.Tel as 'تلفن', gr.Name AS 'گروه مشتری', s.Name AS 'وضعیت مشتری', IFNULL(grade.Name, '') AS 'گرید', IFNULL(CONCAT(u.FName, ' ', u.LName), '') AS 'کاربر ثبت کننده', a.Name AS 'منطقه', c.Address1 AS 'آدرس', IFNULL(r.Title, '-') AS 'آخرین نتیجه تماس', IFNULL(v.Title, '-') AS 'آخرین نتیجه ویزیت' ",
			'FileName'	=> 'customers', 
			'KeyFilter' => 'AND (c.Code LIKE #key# OR c.ShHesab LIKE #key# OR c.ShopName LIKE #key# OR c.Tel LIKE #key# OR c.Phone LIKE #key# OR p.FName LIKE #key# OR p.LName LIKE #key#)',
			'Sort'		=> 'c.ID DESC',
			'Columns'	=> array(
				'User'		=> array(
					'Title'	=> 'کاربر ثبت کننده',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Code'		=> array(
					'Title'	=> 'کد مشتری',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'ShopName'	=> array(
					'Title'		=> 'نام',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Malek'	=> array(
					'Title'		=> 'نام مالک',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Group'		=> array(
					'Title'		=> 'گروه',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Tel'		=> array(
					'Title'		=> 'تلفن ثابت',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Grade'		=> array(
					'Title'		=> 'گرید',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Area'		=> array(
					'Title'		=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Address'		=> array(
					'Title'		=> 'آدرس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'LastTelResult'	=> array(
					'Title'		=> 'آخرین نتیجه تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'LastVisitResult'	=> array(
					'Title'		=> 'آخرین نتیجه ویزیت',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'State'		=> array(
					'Title'		=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "var type = 'default';
						if (row.CustomerState == 1) 
							type = 'info';
						else if (row.CustomerState == 2)
							type = 'success';
						else if (row.CustomerState == 3)
							type = 'danger';
	
	
						return '<div class=\"alert alert-' + type + '\" style=\"padding: 4px;\">' + row.State + '</div>';"
					)
				),
				'Options'	=> array(
					'Title'		=> '',
					'Width' => 200,
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> 'ViewCustomer(#index#)'
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Action'=> 'EditCust(#index#)',
								'Access'=> array(
									'Name'	=> 'customers',
									'Type'	=> 'Edit'
								)
							),
							array(
								'Icon'	=> 'fas fa-trash-alt',
								'Title'	=> 'حذف',
								'Action'=> 'DeleteCustomer(#index#)',
								'Access'=> array(
									'Name'	=> 'customers',
									'Type'	=> 'Del'
								)
							),
							array(
								'Icon'	=> 'fab fa-android',
								'Title'	=> 'دسترسی اپ',
								'Action'=> 'AccessApp(#index#)',
								'Access'=> array(
									'Name'	=> 'customers',
									'Type'	=> 'Edit'
								),
								'Color'	=> array(
									"row.Username!='' && row.Password==1" => '#D84315'
								)
							)
						)
					)
				)
			)
		),
		'customer_registers' => array(
			'Title'		=> 'ثبت نام های نرم افزار',
			'Access'	=> 'customers',
			'Controller'=> 'customers',
			'Action'	=> 'registers',
			'ExtraView' => 'customers/registers',
			'Filters'	=> array(
				'state' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'ثبت نام جدید',
						'1'	=> 'تایید شده',
						'2' => 'تایید نشده ها',
					),
					'Where'	=> array(
						'0'	=> 't.State=0',
						'1'	=> 't.State=1',
						'2' => 't.State=2',
					),
					'Title'	=> 'وضعیت',
					'ListTitle' => 'انواع وضعیت',
					'EmptyError' => 'نوع وضعیت پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),

			),
			'Query'		=> "SELECT t.ID, CONCAT(t.ShamsiDate, ' ', t.ShamsiTime) AS Tarikh, t.ShopName, t.Tel, t.Mobile, t.Address, t.State, t.Username, t.Password, t.Error, t.CustomerID, c.ShopName AS Name, CONCAT(u.FName, ' ', u.LName) AS NameProfile, IFNULL(u.ID, 0) AS Agent
							FROM tapp_registers t LEFT OUTER JOIN tcustomers c ON (t.CustomerID = c.ID)
							LEFT OUTER JOIN tuser u ON (t.Agent = u.ID)
							WHERE 1=1 #where# ",
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'معرف', CONCAT(t.ShamsiDate, ' ', t.ShamsiTime) AS 'تاریخ ثبت', t.ShopName AS 'نام', t.Tel AS 'تلفن ثابت', t.Mobile AS 'تلفن همراه', t.Address AS 'آدرس', t.State AS 'وضعیت', t.Username AS 'نام کاربری', t.Error AS  'توضیحات عدم تایید' ",
			'FileName'	=> 'customers', 
			'KeyFilter' => 'AND (t.ShopName LIKE #key# OR t.Mobile LIKE #key# OR t.Tel LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'NameProfile'	=> array(
					'Title'	=> 'معرف'
				),
				'Tarikh'		=> array(
					'Title'	=> 'تاریخ ثبت'
				),
				'ShopName'		=> array(
					'Title'	=> 'نام'
				),
				'Tel'	=> array(
					'Title'		=> 'تلفن ثابت'
				),
				'Mobile'		=> array(
					'Title'		=> 'تلفن همراه'
				),
				'Tel'		=> array(
					'Title'		=> 'تلفن ثابت'
				),
				'State'		=> array(
					'Title'		=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';
							if (row.State == 0)
								res = 'ثبت نام جدید';
							else if (row.State == 2)
								res = 'عدم تایید شده';
							else if (row.State == 1)
								res = 'تایید شده';

							return res;
						"
					)
				),
				'Customer'		=> array(
					'Title'		=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '-';

							if (row.CustomerID != 0)
								res = '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.Name + '</a>';

							return res;
						"
					)
				),
				'Options'	=> array(
					'Title'		=> '',
					'Width' => 50,
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> 'ViewRegister(#index#)'
							),
						)
					)
				)
			)
		),
		'user_customers' => array(
			'Title'		=> 'آمار مشتریان ثبت شده',
			'Access'	=> 'customers',
			'Controller'=> 'customers',
			'Action'	=> 'amar',
			'ExtraView' => '',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "c.CreateDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah'],
					'Converter' => 'shamsi_to_miladi_min'
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "c.CreateDate<='#value#'",
					'Converter' => 'shamsi_to_miladi_max'
				),
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'CustomerState' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcustomer_states WHERE Deleted=0',
					'Where'		=> "c.CustomerState IN (#value#)",
					'Title'		=> 'وضعیت مشتری',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد',
				),
				'User' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(u.FName, ' ', u.LName) AS Name FROM tuser u WHERE Deleted=0",
					'Where'		=> "c.UserID IN (#value#)",
					'Title'		=> 'کاربر ثبت کننده',
					'ListTitle' => 'کاربر ها',
					'EmptyError'=> 'کاربری پیدا نشد',
				),
				'Group' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID,  Name FROM tgroups",
					'Where'		=> "c.GroupID IN (#value#)",
					'Title'		=> ' گروه مشتری ',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد',
				),

			),
			'Query'		=> "SELECT u.ID, IFNULL(CONCAT(u.FName, ' ', u.LName), '') AS `User`, IFNULL(COUNT(DISTINCT c.ID), 0) AS cnt
							FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
							LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
							WHERE c.Deleted=0 AND c.IsShop=1 #where# 
							GROUP BY u.ID, u.FName, u.LName",
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'customers', 
			'KeyFilter' => 'AND (c.ShHesab LIKE #key# OR c.ShopName LIKE #key# OR c.Tel LIKE #key# OR c.Phone LIKE #key#)',
			'Sort'		=> 'c.ID DESC',
			'Columns'	=> array(
				'User'		=> array(
					'Title'	=> 'کاربر ثبت کننده'
				),
				'cnt'		=> array(
					'Title'	=> 'تعداد مشتری',
					'Formatter'	=> array(
						'Type'	=> 'price'
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'User ',
						'Title'	=> 'نمودار تعداد مشتریان',
						'Colors' => "'#03A9F4'"
					)
				),
				'Options'	=> array(
					'Title'	=> '',
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'Chart'	=> array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده چارت بر اساس تاریخ',
								'Action'=> array(
									'Type'	=> 'chart',
									'ChartTitle' => 'آمار ثبت مشتری ها',
									'FirstType'  => 'by_days',
									'Inputs' => array(
										'id'	=> 'ID'
									),
									'Queries'		=> array(
										'by_days'	=> array(
											'Title'	 => 'آمار ثبت مشتری ها بر اساس تاریخ ثبت',
											'Colors' => array('#03A9F4', '#FFAB91'),
											'Data' 	 => array(
												'Type'	=> 'by_row',
												'FieldName' => 'Date',
												'FieldID'	=> 'CreateDate',
												'Values'	=> array(
													array(
														'Title'	=> 'تعداد مشتری',
														'Field' => 'Count'
													)
												),
												'Click'	=> array(
													'Type'	=> 'by_groups',
													'Input'	=> 'date'
												)
											),
											'Inputs' => array('id'),		
											'Query'	=> "SELECT u.ID, SUBSTRING(PDATE(c.CreateDate), 1, 10) AS Date, c.CreateDate, IFNULL(COUNT(DISTINCT c.ID), 0) AS Count
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# #where# 
											GROUP BY u.ID, Date
											ORDER BY Date DESC
											LIMIT 10",
						
											'HasDetails'	=> true,
											'DetailsQuery'	=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'کاربر ثبت کننده', PDATE(c.CreateDate) AS 'تاریخ ثبت', c.ShHesab AS 'کد مشتری', c.ShopName as 'نام ', c.Tel as 'تلفن', IFNULL(gr.Name, '-') AS 'گروه مشتری', IFNULL(s.Name, '-') AS 'وضعیت مشتری', IFNULL(grade.Name, '') AS 'گرید', IFNULL(bc.Name, '') AS `شهر`, IFNULL(a.Name, '') AS `منطقه`
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											LEFT OUTER JOIN tgroups gr ON (c.GroupID = gr.ID)
											LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
											LEFT OUTER JOIN bcities bc ON (c.City = bc.ID)
											LEFT OUTER JOIN tcustomer_states s ON (c.CustomerState = s.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# #where# 
											",
											'DetailsFileName'	=> "",
											'ReportFileName'=> '',
											'CountPerPage'	=> 10
										),
										'by_groups'	=> array(
											'Title'	 => 'آمار ثبت مشتری ها بر اساس گروه مشتری',
											'Colors' => array('#03A9F4', '#FFAB91'),
											'Data' 	 => array(
												'Type'	=> 'by_row',
												'FieldName' => 'Name',
												'FieldID'	=> 'GroupID',
												'Values'	=> array(
													array(
														'Title'	=> 'تعداد مشتری',
														'Field' => 'Count'
													)
												),
												'Click'	=> array(
													'Type'	=> 'by_days',
													'Input'	=> 'gid'
												)
						
											),
											'Inputs' => array('id', 'date'),		
											'Query'	=> "SELECT u.ID, c.GroupID, g.Name, IFNULL(COUNT(DISTINCT c.ID), 0) AS Count
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											INNER JOIN tgroups g ON (c.GroupID = g.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# AND SUBSTRING(c.CreateDate, 1, 10)=SUBSTRING('#date#', 1, 10) #where# 
											GROUP BY u.ID, c.GroupID, g.Name
											",
						
											'HasDetails'	=> true,
											'DetailsQuery'	=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'کاربر ثبت کننده', PDATE(c.CreateDate) AS 'تاریخ ثبت', c.ShHesab AS 'کد مشتری', c.ShopName as 'نام ', c.Tel as 'تلفن', IFNULL(gr.Name, '-') AS 'گروه مشتری', IFNULL(s.Name, '-') AS 'وضعیت مشتری', IFNULL(grade.Name, '') AS 'گرید', IFNULL(bc.Name, '') AS `شهر`, IFNULL(a.Name, '') AS `منطقه`
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											LEFT OUTER JOIN tgroups gr ON (c.GroupID = gr.ID)
											LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
											LEFT OUTER JOIN bcities bc ON (c.City = bc.ID)
											LEFT OUTER JOIN tcustomer_states s ON (c.CustomerState = s.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# AND c.SUBSTRING(c.CreateDate, 1, 10)=SUBSTRING('#date#', 1, 10)  #where# 
											",
											'DetailsFileName'	=> "",
											'ReportFileName'=> '',
											'CountPerPage'	=> 10
										),
									)
								)
										),
							'Chart_by_group'	=> array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده چارت بر اساس گروه مشتری',
								'Action'=> array(
									'Type'	=> 'chart',
									'ChartTitle' => 'آمار ثبت مشتری ها بر اساس گروه مشتری',
									'FirstType'  => 'by_groups',
									'Inputs' => array(
										'id'	=> 'ID'
									),
									'Queries'		=> array(
										'by_groups'	=> array(
											'Title'	 => 'آمار ثبت مشتری ها بر اساس گروه مشتری',
											'Colors' => array('#03A9F4', '#FFAB91'),
											'Data' 	 => array(
												'Type'	=> 'by_row',
												'FieldName' => 'Name',
												'FieldID'	=> 'GroupID',
												'Values'	=> array(
													array(
														'Title'	=> 'تعداد مشتری',
														'Field' => 'Count'
													)
												),
												'Click'	=> array(
													'Type'	=> 'by_days',
													'Input'	=> 'gid'
												)
						
											),
											'Inputs' => array('id'),		
											'Query'	=> "SELECT u.ID, c.GroupID, g.Name, IFNULL(COUNT(DISTINCT c.ID), 0) AS Count
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											INNER JOIN tgroups g ON (c.GroupID = g.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# #where# 
											GROUP BY u.ID, c.GroupID, g.Name
											",
						
											'HasDetails'	=> true,
											'DetailsQuery'	=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'کاربر ثبت کننده', PDATE(c.CreateDate) AS 'تاریخ ثبت', c.ShHesab AS 'کد مشتری', c.ShopName as 'نام ', c.Tel as 'تلفن', IFNULL(gr.Name, '-') AS 'گروه مشتری', IFNULL(s.Name, '-') AS 'وضعیت مشتری', IFNULL(grade.Name, '') AS 'گرید', IFNULL(bc.Name, '') AS `شهر`, IFNULL(a.Name, '') AS `منطقه`
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											LEFT OUTER JOIN tgroups gr ON (c.GroupID = gr.ID)
											LEFT OUTER JOIN tcustomer_states s ON (c.CustomerState = s.ID)
											LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
											LEFT OUTER JOIN bcities bc ON (c.City = bc.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# #where# 
											",
											'DetailsFileName'	=> "",
											'ReportFileName'=> '',
											'CountPerPage'	=> 10
										),
										'by_days'	=> array(
											'Title'	 => 'آمار ثبت مشتری ها بر اساس تاریخ ثبت',
											'Colors' => array('#03A9F4', '#FFAB91'),
											'Data' 	 => array(
												'Type'	=> 'by_row',
												'FieldName' => 'Date',
												'FieldID'	=> 'ID',
												'Values'	=> array(
													array(
														'Title'	=> 'تعداد مشتری',
														'Field' => 'Count'
													)
												)
											),
											'Inputs' => array('id', 'gid'),		
											'Query'	=> "SELECT u.ID, SUBSTRING(PDATE(c.CreateDate), 1, 10) AS Date, IFNULL(COUNT(DISTINCT c.ID), 0) AS Count
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# AND c.GroupID=#gid# #where# 
											GROUP BY u.ID, Date
											ORDER BY Date DESC
											LIMIT 10",
						
											'HasDetails'	=> true,
											'DetailsQuery'	=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'کاربر ثبت کننده', PDATE(c.CreateDate) AS 'تاریخ ثبت', c.ShHesab AS 'کد مشتری', c.ShopName as 'نام ', c.Tel as 'تلفن', IFNULL(gr.Name, '-') AS 'گروه مشتری', IFNULL(s.Name, '-') AS 'وضعیت مشتری', IFNULL(grade.Name, '') AS 'گرید', IFNULL(bc.Name, '') AS `شهر`, IFNULL(a.Name, '') AS `منطقه`
											FROM tuser u INNER JOIN tcustomers c ON (u.ID = c.UserID) 
											LEFT OUTER JOIN tgrades grade ON (c.Grade = grade.ID) 
											LEFT OUTER JOIN tgroups gr ON (c.GroupID = gr.ID)
											LEFT OUTER JOIN tcustomer_states s ON (c.CustomerState = s.ID)
											LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
											LEFT OUTER JOIN bcities bc ON (c.City = bc.ID)
											WHERE c.Deleted=0 AND c.IsShop=1 AND c.UserID=#id# AND c.GroupID=#gid# #where# 
											",
											'DetailsFileName'	=> "",
											'ReportFileName'=> '',
											'CountPerPage'	=> 10
										)
									)
								)
							)
						)
					)
				)

			)
		),
		'calls'		=> array(
			'Title'		=> 'تماس های انجام شده',
			'Access'	=> 'tel_tour|tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'calls',
			'ExtraView' => 'tel_tour/call_result',			
			'AddData'	=> array(
				'function' => calls_add_data
			),
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.CallTarikh>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.CallTarikh<='#value#'"
				),
				'result' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Title AS Name FROM ttour_call_results WHERE Type=1',
					'Where'		=> "t.Result IN (#value#)",
					'Title'		=> 'نتیجه تماس',
					'ListTitle' => 'نتایج',
					'EmptyError'=> 'نتیجه ای پیدا نشد'
				),
			),
			'Query'		=> "SELECT t.ID, tour.Name AS Tour, call_list.Name AS CallList, CONCAT(user.FName, ' ', user.LName) AS Visitor, CONCAT(t.CallTarikh, ' ', t.CallTime) AS Tarikh, IFNULL(c.ShopName, temp.ShopName) AS ShopName, t.CustomerID, t.TempID, result.Title AS Result , t.Peigiri, t.PeigiriTarikh, t.PeigiriTime, IFNULL(CONCAT(IFNULL(p.FName, c.FName), ' ', IFNULL(p.LName, c.LName)), '-') AS Malek, c.Tel, c.Phone, t.CallListID AS CallID, t.ID AS EditID
			FROM ttour_calls t INNER JOIN ttours tour ON (t.TourID = tour.ID) 
			INNER JOIN tcall_lists call_list ON (t.ListID = call_list.ID) 
			INNER JOIN tuser user ON (t.VisitorID = user.ID) 
			INNER JOIN ttour_call_results result ON (t.Result = result.ID) 
			LEFT OUTER JOIN tcustomers c ON (t.CustomerID = c.ID) 
			LEFT OUTER JOIN temp_customers temp ON (t.TempID = temp.ID) 
			LEFT OUTER JOIN tcustomer_persons p ON (c.MalekID = p.ID)
			WHERE " . ($CI->User['Admin'] == 1 ? '1=1' : (hasView('tel_tour_admin') ? '(t.TourID IN (SELECT ID FROM ttours WHERE UserID=#uid#) OR t.VisitorID=#uid#)' : 't.VisitorID=#uid#')) . ' #where# ',
			'FileQuery'=> '',
			'FileSelect'=> "SELECT tour.Name AS 'تور', call_list.Name AS 'لیست تماس', CONCAT(user.FName, ' ', user.LName) AS 'ویزیتور', CONCAT(t.CallTarikh, ' ', t.CallTime) AS 'تاریخ تماس', IFNULL(c.ShopName, temp.ShopName) AS 'نام فروشگاه', IFNULL(c.Tel, temp.Tel) AS 'تلفن ثابت', IFNULL(c.Phone, temp.Phone) AS 'تلفن همراه', result.Title AS 'نتیجه تماس' , (CASE WHEN t.Peigiri = 0 THEN '-' ELSE CONCAT(t.PeigiriTarikh, ' ', t.PeigiriTime) END) AS 'پیگیری', t.Tozihat AS 'توضیحات'",
			'FileName'	=> 'calls', 
			'KeyFilter' => 'AND (IFNULL(c.ShopName, temp.ShopName) LIKE #key# OR tour.Name LIKE #key# OR result.Title LIKE #key# OR user.FName LIKE #key# OR user.LName LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> hasView('tel_tour_admin')
				),
				'Tour'	=> array(
					'Title'	=> 'نام تور'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ تماس'
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';
							else
								res += '<a href=\"javascript:\" onclick=\"ViewTempCustomer(' + row.TempID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Result'  => array(
					'Title' => 'نتیجه تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip',
					)
				),
				'Peigiri' => array(
					'Title' => 'پیگیری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Peigiri == 0)
								res = '-';
							else {
								res = row.PeigiriTarikh + ' ' + row.PeigiriTime + ':00';
							}

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width' => 150,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewCallData(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-headset',
								'Title'	=> 'ثبت نتیجه تماس',
								'Action'=> "CallResult(#index#, false)"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش نتیجه تماس',
								'Action'=> "CallResult(#index#, true)"
							),
						)
					)
				)
			)
		),
		'calls_success'	=> array(
			'Title'		=> 'تماس های انجام شده',
			'Access'	=> 'tel_tour|tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'calls_to_order',
			'ExtraView' => '',			
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "t.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.Tarikh>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.Tarikh<='#value#'"
				),
				'state' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM torder_states',
					'Where'		=> "t.OrderState IN (#value#)",
					'Title'		=> 'وضعیت',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد'
				),
			),
			'Query'		=> "SELECT t.ID, c.ShopName, c.ID AS CustomerID, t.AllPrice, CONCAT(t.ShamsiDate, ' ', SUBSTR(t.CreateDate, 10)) AS Tarikh, store.Name AS Store, IFNULL(tour.Name, '-') AS Tour, s.Name AS Status, CONCAT(u.FName, ' ', u.LName) AS Visitor
							FROM torders t INNER JOIN torder_states s ON (t.OrderState = s.ID)
							INNER JOIN tcustomers c ON (t.UID = c.ID)
							INNER JOIN tstores store ON (t.StoreID = store.ID)
							INNER JOIN tuser u ON (t.UserID = u.ID)
							LEFT OUTER JOIN ttour_list_to_call tcall ON (t.CallID = tcall.ID)
							LEFT OUTER JOIN ttours tour ON (IFNULL(tcall.TourID, 0) = tour.ID)
							WHERE " . (hasView('tel_tour_admin') ? 'tour.ID IN (SELECT ID FROM ttours WHERE  Type=1) ' : 't.UserID=#uid#') . ' AND t.IsBuy=0 #where# '
			,
			'FileQuery'=> "",
			'FileSelect'=> "SELECT t.ID as 'شناسه درخواست', c.ShopName as 'نام فروشگاه', CONCAT(t.ShamsiDate, ' ', SUBSTR(t.CreateDate, 10)) AS 'تاریخ درخواست', store.Name AS 'انبار', t.AllPrice as 'مبلغ درخواست', IFNULL(tour.Name, '-') AS 'نام تور', s.Name AS 'وضعیت', CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (c.ShopName LIKE #key# OR tour.Name LIKE #key#  OR u.FName LIKE #key# OR u.LName LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> hasView('tel_tour_admin')
				),
				'Tour'	=> array(
					'Title'	=> 'نام تور'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ تماس',
					'Formatter' => array('Type' => 'tooltip')
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';
							else
								res += '<a href=\"javascript:\" onclick=\"ViewTempCustomer(' + row.TempID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'AllPrice'=> array(
					'Title'	=> 'مبلغ درخواست',
					'Formatter' => array('Type'	=> 'price'),
					'Footer'	=> true,
				),
				'Status'	=> array(
					'Title'	=> 'وضعیت'
				),
				'Options'  => array(
					'Title'	=> '',
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
						)
					)
				)
			)
		),
		'tel_tour_admin_report' => array(
			'Title'		=> 'آنالیز تور ویزیت تلفنی',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'admin_report',
			'ExtraView' => '',			
			'Filters'	=> array(),
			'PreQueries'	=> array(
				"DELETE FROM RepAmarTourTel
				WHERE UID = #uid#",
				"INSERT INTO RepAmarTourTel (ID , Name , Active , StartTarikh , EndTarikh , AllCustomers , Days , UID)
				SELECT t.ID , t.Name , t.Active , t.StartTarikh , t.EndTarikh ,  lc.Customers , DATEDIFF(CASE WHEN t.EndDate>now() THEN now() ELSE t.EndDate END, t.StartDate) AS `Days` , #uid#
				FROM ttours t LEFT OUTER JOIN ttour_lists lists ON (t.ID = lists.TourID and lists.State = 1)
				LEFT OUTER JOIN tcall_lists lc ON (lc.ID = lists.ListID ) 
				WHERE t.Deleted=0 AND t.Type=1 #where# ",
				"INSERT INTO RepAmarTourTel (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , Calls , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 ,  0 ,COUNT(DISTINCT calls.ID) AS `Calls`, #uid#
				FROM ttours t LEFT OUTER JOIN ttour_calls calls ON (calls.TourID = t.ID AND calls.Result<>0)
				WHERE t.Deleted=0 AND t.Type=1  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh",
				"INSERT INTO RepAmarTourTel (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , AdamHamkari , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 , 0 ,COUNT(DISTINCT calls_no.ID) AS `AdamHamkari`, #uid#
				FROM ttours t LEFT OUTER JOIN ttour_calls calls_no ON (calls_no.TourID = t.ID AND calls_no.Result IN (SELECT ID FROM ttour_call_results WHERE Type=1 AND Operation=1))
				WHERE t.Deleted=0 AND t.Type=1  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh",
				"INSERT INTO RepAmarTourTel (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , Sells, AllPrice, Visitors , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 , 0 ,  COUNT(DISTINCT orders.UID) AS `Sells`, IFNULL(SUM(DISTINCT orders.AllPrice),0) AS `AllPrice`, COUNT(DISTINCT orders.UserID) AS `Visitors` , #uid#
				FROM ttours t LEFT OUTER JOIN ttour_list_to_call lcall ON (lcall.TourID = t.ID)
				LEFT OUTER JOIN torders orders ON (orders.CallID = lcall.ID AND orders.IsBuy=0) 
				WHERE t.Deleted=0 AND t.Type=1  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh"
			),
			'Query'		=> "Select ID , Name ,Active , StartTarikh , EndTarikh , SUM(AllCustomers) AllCustomers , SUM(Calls) Calls , SUM(Sells) Sells , SUM(AllPrice) AllPrice , SUM(AdamHamkari) AdamHamkari , SUM(Visitors ) Visitors , SUM(Days) Days , UID 
			from RepAmarTourTel 
			where UID = #uid# #where#
			group by ID , NAme ,Active , StartTarikh , EndTarikh
			 ",
			'FileQuery'=> "",
			'FileSelect'=> "",
			'FileName'	=> 'tel_tours', 
			'KeyFilter' => 'AND (t.Name LIKE #key# OR t.StartTarikh LIKE #key#  OR t.EndTarikh LIKE #key#)',
			'Sort'		=> 'Name , StartTarikh',
			'Columns'	=> array(
				'ID'		=> array(
					'Enabled' => false,
					'Title'	  => 'شناسه تور'
				),
				'Days'		  => array(
					'Enabled' => false,
					'Title'   => 'تعداد روز اجرای تور'
				),
				'Name'		=> array(
					'Title'	=> 'عنوان تور',
				),
				'Active'	=> array(
					'Title'	=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'true_false'
					)
				),
				'StartTarikh'	=> array(
					'Title'	=> 'تاریخ شروع'
				),
				'EndTarikh'		=> array(
					'Title'		=> 'تاریخ پایان'
				),
				'AllCustomers'	=> array(
					'Title'		=> 'تعداد کل مشتریان',
					'Footer' => true
				),
				'Calls'			=> array(
					'Title'		=> 'مشتریان تماس گرفته شده',
					'Footer' => true
				),
				'Sells'			=> array(
					'Title'		=> 'مشتریان منجر به فروش',
					'Footer' => true
				),
				'AllPrice'		=> array(
					'Title'		=> 'مجموع فروش',
					'Footer' => true,
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'AdamHamkari'	=> array(
					'Title'		=> 'عدم همکاری',
					'Footer' => true
				),
				'Visitors'		=> array(
					'Title'		=> 'ویزیتورهای دارای فروش موفق',
					'Footer' => true
				),
				'Average'		=> array(
					'Title'		=> 'متوسط تعداد تماس ها در روز',
					'Footer' 	=> array(
						'Code'	=> "
							var res = 0;

							return parseInt(report_total_Calls / report_total_Days);
						"
					),
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = parseInt(row.Calls) / parseInt(row.Days);

							res = parseInt(res);

							if (isNaN(res))
								res = 0;

							return res;
						"
					)
				),
				'Options'	=> array(
					'Title'	=> '',
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'Chart'	=> $tour_chart
						)
					)
				)
			)

		),
		'visit_tour_admin_report' => array(
			'Title'		=> 'آنالیز تور ویزیت حضوری',
			'Access'	=> 'visit_tour_admin',
			'Controller'=> 'visit_tour',
			'Action'	=> 'admin_report',
			'ExtraView' => '',			
			'Filters'	=> array(),
			'PreQueries'	=> array(
				"DELETE FROM RepAmarTourVisit
				WHERE UID = #uid#",
				"INSERT INTO RepAmarTourVisit (ID , Name , Active , StartTarikh , EndTarikh , AllCustomers , Days , UID)
				SELECT t.ID , t.Name , t.Active , t.StartTarikh , t.EndTarikh ,  lists.Customers , DATEDIFF(CASE WHEN t.EndDate>now() THEN now() ELSE t.EndDate END, t.StartDate) AS `Days` , #uid#
				FROM ttours t LEFT OUTER JOIN ttour_visitors lists ON (t.ID = lists.TourID and lists.State = 1)
				WHERE t.Deleted=0 AND t.Type=2 #where# ",
				"INSERT INTO RepAmarTourVisit (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , Visits , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 ,  0 , COUNT(DISTINCT calls.CustomerID) AS `Calls`, #uid#
				FROM ttours t LEFT OUTER JOIN ttour_calls calls ON (calls.TourID = t.ID AND calls.Result<>0)
				WHERE t.Deleted=0 AND t.Type=2  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh",
				"INSERT INTO RepAmarTourVisit (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , AdamHamkari , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 , 0 ,COUNT(DISTINCT calls_no.CustomerID) AS `AdamHamkari`, #uid#
				FROM ttours t LEFT OUTER JOIN ttour_calls calls_no ON (calls_no.TourID = t.ID AND calls_no.Result IN (SELECT ID FROM ttour_call_results WHERE Type=2 AND Operation=1))
				WHERE t.Deleted=0 AND t.Type=2  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh",
				"INSERT INTO RepAmarTourVisit (ID , Name , Active , StartTarikh , EndTarikh ,AllCustomers, Days , Sells, AllPrice, Visitors , UID)
				SELECT t.ID AS `ID`, t.Name AS `Name`, t.Active AS `Active`, t.StartTarikh AS `StartTarikh`, t.EndTarikh AS `EndTarikh`, 0 , 0 ,  COUNT(DISTINCT orders.UID) AS `Sells`, IFNULL(SUM(DISTINCT orders.AllPrice),0) AS `AllPrice`, COUNT(DISTINCT orders.UserID) AS `Visitors` , #uid#
				FROM ttours t LEFT OUTER JOIN ttour_visitor_customers lcall ON (lcall.TourID = t.ID)
				LEFT OUTER JOIN torders orders ON (orders.VisitID = lcall.ID AND orders.IsBuy=0) 
				WHERE t.Deleted=0 AND t.Type=2  #where#
				GROUP BY t.ID, t.Name, t.Active, t.StartTarikh, t.EndTarikh"
			),
			'Query'		=> "Select ID , Name ,Active , StartTarikh , EndTarikh , SUM(AllCustomers) AllCustomers , SUM(Visits) Calls , SUM(Sells) Sells , SUM(AllPrice) AllPrice , SUM(AdamHamkari) AdamHamkari , SUM(Visitors ) Visitors , SUM(Days) Days , UID 
			from RepAmarTourVisit 
			where UID = #uid# #where#
			group by ID , NAme ,Active , StartTarikh , EndTarikh
			ORDER BY Name , StartTarikh ",
			'FileQuery'=> "",
			'FileSelect'=> "",
			'FileName'	=> 'tel_tours', 
			'KeyFilter' => 'AND (t.Name LIKE #key# OR t.StartTarikh LIKE #key#  OR t.EndTarikh LIKE #key#)',
			'Sort'		=> '',
			'Columns'	=> array(
				'ID'		=> array(
					'Enabled' => false,
					'Title'	  => 'شناسه تور'
				),
				'Days'		  => array(
					'Enabled' => false,
					'Title'   => 'تعداد روز اجرای تور'
				),
				'Name'		=> array(
					'Title'	=> 'عنوان تور',
				),
				'Active'	=> array(
					'Title'	=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'true_false'
					)
				),
				'StartTarikh'	=> array(
					'Title'	=> 'تاریخ شروع'
				),
				'EndTarikh'		=> array(
					'Title'		=> 'تاریخ پایان'
				),
				'AllCustomers'	=> array(
					'Title'		=> 'تعداد کل مشتریان',
					'Footer' => true
				),
				'Calls'			=> array(
					'Title'		=> 'مشتریان ویزیت شده  ',
					'Footer' => true
				),
				'Sells'			=> array(
					'Title'		=> 'مشتریان منجر به فروش',
					'Footer' => true
				),
				'AllPrice'		=> array(
					'Title'		=> 'مجموع فروش',
					'Footer' => true,
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'AdamHamkari'	=> array(
					'Title'		=> 'عدم همکاری',
					'Footer' => true
				),
				'Visitors'		=> array(
					'Title'		=> 'ویزیتورهای دارای فروش موفق',
					'Footer' => true
				),
				'Average'		=> array(
					'Title'		=> 'متوسط تعداد ویزیت در روز',
					'Footer' 	=> array(
						'Code'	=> "
							var res = 0;

							return parseInt(report_total_Calls / report_total_Days);
						"
					),
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = parseInt(row.Calls) / parseInt(row.Days);

							res = parseInt(res);

							if (isNaN(res))
								res = 0;

							return res;
						"
					)
				),
				'Options'	=> array(
					'Title'	=> '',
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'Chart'	=> $visit_chart
						)
					)
				)
			)

		),
		'sales_manager_call_buy_report' => array(
			'Title'		=> 'گزارش تماس و فروش تلفنی',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_buy_report',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "o.ShamsiDate>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "o.ShamsiDate<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				)
			),
			'Query'		=> "select cl.Name AS `CallListName` , tour.Name AS `TourName` , concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , trim(cust.ShopName) AS `CustomerName` ,o.ShamsiDate AS `OrderDate`, o.AllPrice AS `JameKol`, o.TakhfifRial AS `Takhfif` ,o.AllPrice-o.TakhfifRial as `PriceKol`, o.UID, o.ID AS `OrderID`
				from tOrders o INNER JOIN ttour_list_to_call tlc ON (o.CallID = tlc.ID)
				LEFT JOIN tCustomers cust ON (o.UID = cust.ID)
				INNER JOIN tUser u ON (o.UserID = u.ID)
				INNER JOIN tTours tour ON (tlc.TourID = tour.ID)
				inner join tcall_lists cl ON (tlc.ListID = cl.ID)
				WHERE o.IsBuy=0 #where# ",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_buy_report',
			'KeyFilter' => '',
			'Sort'		=> 'o.ID DESC ',
			'Columns'	=> array(
				'CallListName'	=> array(
					'Title'	=> 'لیست تماس'
				),
				'TourName'	=> array(
					'Title'	=> 'نام تور'
				),
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور'
				),
				'CustomerName'	=> array(
					'Title'	=> 'نام مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.UID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.CustomerName + '</a>';

							return res;
						"
					)
				),
				'OrderDate'	=> array(
					'Title'	=> 'تاریخ درخواست'
				),
				'JameKol'	=> array(
					'Title'	=> 'جمع مبلغ درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true
				),
				'Takhfif'	=> array(
					'Title'	=> 'تخفیف درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true
				),
				'PriceKol'	=> array(
					'Title'	=> 'مبلغ نهایی درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true
				),
				'UID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				),
				'OrderID'	=> array(
					'Enabled' => false
				),
				'Options'	=> array(
					'Title'	=> '',
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده درخواست',
								'Action'=> "ViewOrder(' + row.OrderID + ')"
							),
						)
					)
				)
			)
		),
		'sales_manager_call_buy_amar' => array(
			'Title'		=> 'آمار تماس و فروش تلفنی',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_buy_amar',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "o.ShamsiDate>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "o.ShamsiDate<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				)
			),
			'Query'		=> "select cl.Name AS `CallListName` , tour.Name AS `TourName` , concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , o.ShamsiDate AS `OrderDate`, Count(distinct o.ID) AS `TedadSefaresh` , SUM(o.AllPrice) as `JameKol`, SUM(o.TakhfifRial) `Takhfif` , SUM(o.AllPrice - o.TakhfifRial) AS `PriceKol`
				from tOrders o INNER JOIN ttour_list_to_call tlc ON (o.CallID = tlc.ID)
				LEFT JOIN tCustomers cust ON (o.UID = cust.ID)
				INNER JOIN tUser u ON (o.UserID = u.ID)
				INNER JOIN tTours tour ON (tlc.TourID = tour.ID)
				inner join tcall_lists cl ON (tlc.ListID = cl.ID)
				WHERE o.Isbuy=0 #where#
				GROUP BY cl.Name , tour.Name , concat(trim(u.LName),' ',trim(u.FName)) , o.ShamsiDate ",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_buy_amar',
			'KeyFilter' => '',
			'Sort'		=> 'o.ShamsiDate , PriceKol Desc ',
			'Columns'	=> array(
				'CallListName'	=> array(
					'Title'	=> 'لیست تماس'
				),
				'TourName'	=> array(
					'Title'	=> 'نام تور'
				),
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور'
				),
				'OrderDate'	=> array(
					'Title'	=> 'تاریخ درخواست'
				),
				'TedadSefaresh'	=> array(
					'Title'	=> 'تعداد سفارش',
					'Formatter'	=> array(
						'Type'	=> 'price'
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'CallListName TourName VisitorName OrderDate',
						'Title'	=> 'نمودار تعداد سفارشات'
					)
				),
				'JameKol'	=> array(
					'Title'	=> 'جمع مبلغ درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'CallListName TourName VisitorName OrderDate',
						'Title'	=> 'نمودار جمع مبلغ درخواست '
					)

				),
				'Takhfif'	=> array(
					'Title'	=> 'تخفیف درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'CallListName TourName VisitorName OrderDate',
						'Title'	=> 'نمودار تخفیف درخواست'
					)
				),
				'PriceKol'	=> array(
					'Title'	=> 'مبلغ نهایی درخواست',
					'Formatter'	=> array(
						'Type'	=> 'price'	
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'CallListName TourName VisitorName OrderDate',
						'Title'	=> 'نمودار مبلغ نهایی درخواست'
					)
				),
				'UID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				),
			)
		),
		'sales_manager_call_no_cooperation_report' => array(
			'Title'		=> 'گزارش تماس و عدم همکاری',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_no_cooperation_report',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "ttc.CallTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "ttc.CallTarikh<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				),
				'result'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Title AS Name FROM ttour_call_results WHERE RepTagAdam=1 AND Type=1',
					'Where'		=> 'ttc.Result IN (#value#)',
					'Title'		=> 'نتیجه تماس',
					'ListTitle' => 'لیست نتایج',
					'EmptyError'=> ' نتیجه ای پیدا نشد'
				)
			),
			'Query'		=> "SELECT  
			cl.Name AS `CallListName` , tour.Name AS `TourName` , concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , 
			ttc.CallTarikh AS `CallTarikh`, ttc.CallTime AS `CallTime` , CASE WHEN not cust.id is Null THEN Cust.ShopName WHEN not tempcust.ID is Null THEN tempcust.ShopName END AS `ShopName` ,  tcr.Title AS `Title` , ttc.tozihat AS `Tozihat` , IFNULL(cust.ID, 0) AS CID, IFNULL(tempcust.ID, 0) AS TempID
			FROM ttour_calls ttc LEFT JOIN tCustomers cust ON (ttc.CustomerID = cust.ID)
			LEFT JOIN temp_customers tempcust ON (ttc.TempID = tempcust.ID)
			INNER JOIN tUser u ON (ttc.VisitorID = u.ID)
			INNER JOIN tTours tour ON (ttc.TourID = tour.ID)
			inner join tcall_lists cl ON (ttc.ListID = cl.ID)
			inner join ttour_call_results tcr ON (ttc.Result = tcr.ID)
			WHERE ttc.Type = 1 and tcr.RepTagAdam = 1 #where#",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_no_cooprate_report',
			'KeyFilter' => '',
			'Sort'		=> 'ttc.CallDate ',
			'Columns'	=> array(
				'CallListName'	=> array(
					'Title'	=> 'لیست تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'TourName'	=> array(
					'Title'	=> 'نام تور',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CallTarikh' => array(
					'Title'	=> 'تاریخ تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CallTime'	=> array(
					'Title'	=> 'ساعت تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'ShopName'	=> array(
					'Title'	=> 'نام مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CID + ');\">' + row.ShopName + '</a>';
							else
								res += '<a href=\"javascript:\" onclick=\"ViewTempCustomer(' + row.TempID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Title'		=> array(
					'Title'	=> 'نتیجه تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Tozihat'	=> array(
					'Title'	=> 'توضیحات',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				),
				'TempID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				)
			)
		),
		'sales_manager_call_no_cooperation_amar' => array(
			'Title'		=> 'آمار تماس و عدم همکاری',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_no_cooperation_amar',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "ttc.CallTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "ttc.CallTarikh<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				),
				'result'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Title AS Name FROM ttour_call_results WHERE RepTagAdam=1 AND Type=1',
					'Where'		=> 'ttc.Result IN (#value#)',
					'Title'		=> 'نتیجه تماس',
					'ListTitle' => 'لیست نتایج',
					'EmptyError'=> ' نتیجه ای پیدا نشد'
				)
			),
			'Query'		=> "SELECT concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , tcr.Title AS `Title` , COUNT(Distinct CASE WHEN not cust.id is Null THEN Cust.id WHEN not tempcust.ID is Null THEN tempcust.id END) AS `TedadMoshtari`
			FROM ttour_calls ttc LEFT JOIN tCustomers cust ON (ttc.CustomerID = cust.ID)
			LEFT JOIN temp_customers tempcust ON (ttc.TempID = tempcust.ID)
			INNER JOIN tUser u ON (ttc.VisitorID = u.ID)
			INNER JOIN tTours tour ON (ttc.TourID = tour.ID)
			inner join tcall_lists cl ON (ttc.ListID = cl.ID)
			inner join ttour_call_results tcr ON (ttc.Result = tcr.ID)
			WHERE ttc.Type = 1 and tcr.RepTagAdam = 1 #where# 
			GROUP BY concat(trim(u.LName),' ',trim(u.FName)) , tcr.Title ",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_no_cooprate_amar',
			'KeyFilter' => '',
			'Sort'		=> 'ttc.CallDate , `TedadMoshtari` Desc ',
			'Columns'	=> array(
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور'
				),
				'Title'			=> array(
					'Title'	=> 'نتیجه تماس'
				),
				'TedadMoshtari'	=> array(
					'Title'	=> 'تعداد مشتری',
					'Formatter'	=> array(
						'Type'	=> 'price'
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'VisitorName Title',
						'Title'	=> 'نمودار تعداد مشتریان'
					)
				),
			)
		),
		'sales_manager_call_peigiri_report' => array(
			'Title'		=> 'گزارش تماس و پیگیری',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_peigiri_report',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "ttc.CallTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "ttc.CallTarikh<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				)
			),
			'Query'		=> "SELECT cl.Name AS `CallListName` , tour.Name AS `TourName` , concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , ttc.CallTarikh AS `CallTarikh` , CASE WHEN not cust.id is Null THEN Cust.ShopName WHEN not tempcust.ID is Null THEN tempcust.ShopName END AS `ShopName`, ttc.CallTime AS `CallTime` ,  tcr.Title AS `Title`  , ttc.tozihat AS `Tozihat`, IFNULL(cust.ID, 0) AS CID, IFNULL(tempcust.ID, 0) AS TempID
			FROM ttour_calls ttc LEFT JOIN tCustomers cust ON (ttc.CustomerID = cust.ID)
			LEFT JOIN temp_customers tempcust ON (ttc.TempID = tempcust.ID)
			INNER JOIN tUser u ON (ttc.VisitorID = u.ID)
			INNER JOIN tTours tour ON (ttc.TourID = tour.ID)
			inner join tcall_lists cl ON (ttc.ListID = cl.ID)
			inner join ttour_call_results tcr ON (ttc.Result = tcr.ID)
			WHERE ttc.Type = 1 and tcr.Operation = 2 #where#",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_peigiri_report',
			'KeyFilter' => '',
			'Sort'		=> 'ttc.CallDate ',
			'Columns'	=> array(
				'CallListName'	=> array(
					'Title'	=> 'لیست تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'TourName'	=> array(
					'Title'	=> 'نام تور',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CallTarikh' => array(
					'Title'	=> 'تاریخ تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CallTime'	=> array(
					'Title'	=> 'ساعت تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'ShopName'	=> array(
					'Title'	=> 'نام مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CID + ');\">' + row.ShopName + '</a>';
							else
								res += '<a href=\"javascript:\" onclick=\"ViewTempCustomer(' + row.TempID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Title'		=> array(
					'Title'	=> 'نتیجه تماس',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'Tozihat'	=> array(
					'Title'	=> 'توضیحات',
					'Formatter' => array(
						'Type'	=> 'tooltip'
					)
				),
				'CID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				),
				'TempID'		=> array(
					'Title'	=> 'کد مشتری',
					'Enabled' => false
				)
			)
		),
		'sales_manager_call_peigiri_amar' => array(
			'Title'		=> 'آمار تماس و پیگیری ',
			'Access'	=> 'tel_tour_admin',
			'Controller'=> 'tel_tour',
			'Action'	=> 'call_peigiri_amar',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Value' => $CI->mViewData['D1Mah'],
					'Title'	=> 'از تاریخ:',
					'Where' => "ttc.CallTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Value'	=> $CI->getShamsiDate(),
					'Where' => "ttc.CallTarikh<='#value#'"
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> hasView('tel_tour_admin')
				),
				'tour'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM ttours WHERE Deleted=0 AND Type=1',
					'Where'		=> 'tour.ID IN (#value#)',
					'Title'		=> 'تور',
					'ListTitle' => 'تورها',
					'EmptyError'=> 'توری پیدا نشد'
				),
				'call_list'	=> array(
					'Type'	=> 'select',
					'Options'	=> 'SELECT ID, Name FROM tcall_lists WHERE Deleted=0',
					'Where'		=> 'cl.ID IN (#value#)',
					'Title'		=> 'لیست تماس',
					'ListTitle' => 'لیست تماس ها',
					'EmptyError'=> 'لیست تماسی پیدا نشد'
				)
			),
			'Query'		=> "SELECT concat(trim(u.LName),' ',trim(u.FName)) AS `VisitorName` , COUNT(Distinct CASE WHEN not cust.id is Null THEN Cust.id WHEN not tempcust.ID is Null THEN tempcust.id END) AS `TedadKol`, COUNT(Distinct CASE WHEN tcr.Operation<>2 THEN NULL ELSE (CASE WHEN not cust.id is Null THEN Cust.id WHEN not tempcust.ID is Null THEN tempcust.id END) END) `TedadMoshtari`
			FROM ttour_calls ttc LEFT JOIN tCustomers cust ON (ttc.CustomerID = cust.ID)
			LEFT JOIN temp_customers tempcust ON (ttc.TempID = tempcust.ID)
			INNER JOIN tUser u ON (ttc.VisitorID = u.ID)
			INNER JOIN tTours tour ON (ttc.TourID = tour.ID)
			inner join tcall_lists cl ON (ttc.ListID = cl.ID)
			inner join ttour_call_results tcr ON (ttc.Result = tcr.ID)
			WHERE ttc.Type = 1  #where#
			GROUP BY  concat(trim(u.LName),' ',trim(u.FName))   ",
			'FileQuery' => '',
			'FileSelect'=> '',
			'FileName'  => 'call_peigiri_amar',
			'KeyFilter' => '',
			'Sort'		=> 'ttc.CallDate , `TedadMoshtari` Desc ',
			'Columns'	=> array(
				'VisitorName' => array(
					'Title'	=> 'نام ویزیتور'
				),
				'TedadKol'	=> array(
					'Title'	=> 'تعداد کل',
					'Formatter'	=> array(
						'Type'	=> 'price'
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'VisitorName ',
						'Title'	=> 'نمودار تعداد کل',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'تعداد مشتری',
								'Name'	=> 'TedadMoshtari'
							)
						)
					)
				),
				'TedadMoshtari'	=> array(
					'Title'	=> 'تعداد مشتری',
					'Formatter'	=> array(
						'Type'	=> 'price'
					),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'VisitorName ',
						'Title'	=> 'نمودار تعداد مشتریان',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'تعداد کل',
								'Name'	=> 'TedadKol'
							)
						)
					)
				),
			)
		),
		'orders_to_verify'		=> array(
			'Title'		=> 'سفارشات در دست اقدام',
			'Access'	=> $_GET['access'],
			'Controller'=> $_GET['controller'],
			'Action'	=> 'verify_orders',
			'ExtraView' => '',			
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "t.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4 || hasView('store')) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Week']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.ShamsiDate<='#value#'"
				),
				'group' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tgroups t INNER JOIN tuser_access_groups g ON (t.ID = g.GroupID) WHERE g.UID=' . $CI->User['ID'] ,
					'Where'		=> "cu.GroupID IN (#value#)",
					'Title'		=> 'گروه مشتری',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'فروش',
						'1'	=> 'خرید',
						'2' => 'برگشت فروش',
						'3' => 'برگشت خرید'
					),
					'Where'	=> array(
						'0'	=> 't.IsBuy=0 AND t.IsReturn=0',
						'1'	=> 't.IsBuy == 1 AND t.IsReturn=0',
						'2' => 't.IsBuy=0 AND t.IsReturn=1',
						'3' => 't.IsBuy=1 AND t.IsReturn=1'
					),
					'Title'	=> 'نوع سفارش',
					'ListTitle' => 'انواع سفارش',
					'EmptyError' => 'نوع سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'send_state' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM torder_send_states t',
					'Where'		=> "t.SendState IN (#value#)",
					'Title'	=> 'وضعیت ارسال سفارش',
					'ListTitle' => 'انواع وضعیت',
					'EmptyError' => ' وضعیتی پیدا نشد',
					'Enabled'	=> $_GET['type'] == 'sale_manager' || $_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour'
				),
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tgrades t',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'	=> 'گرید',
					'ListTitle' => 'لیست گریدها',
					'EmptyError' => ' گریدی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
			),
			'Query'		=> "SELECT t.ID AS `ID`, t.UID AS `UID`, IFNULL(tour.Name, '-') AS Tour, CONCAT(t.FName, ' ', t.LName) AS `Customer`, t.`ShamsiDate`, t.`CreateDate`, `AllPrice`, s.Name AS `State`, t.Status, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS Visitor, t.Naghdi, t.Etebari, cu.MandeHesab, IFNULL(g.Name, '-') AS `GroupName`, t.IsBuy AS `IsBuy`, t.IsReturn AS `IsReturn`, t.SendState AS SendStateID, t.SendStateTarikh, ss.Name AS SendState, ss.Alert, IFNULL(gr.Name, '-') AS Grade
			FROM torders t INNER JOIN torder_states s ON (t.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (t.UID = cu.ID)
			INNER JOIN torder_send_states ss ON (t.SendState = ss.ID)
			LEFT OUTER JOIN tuser u ON (t.UserID = u.ID AND t.ByApp=0)
			LEFT OUTER JOIN ttour_list_to_call ca ON (t.CallID = ca.ID)
			LEFT OUTER JOIN ttour_visitor_customers c ON (t.VisitID = c.ID)
			LEFT OUTER JOIN ttours tour ON (tour.ID = IFNULL(ca.TourID, IFNULL(c.TourID, 0)))
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tgrades gr ON (cu.Grade = gr.ID)
			WHERE t.FromCrm=1 AND t.Status IN (-1" . (
				(hasView('store') ? ', 7' : '') .
				(hasView('mali') ? ',3, 8' : '') . 
				(hasView('tel_tour_admin') || hasView('visit_tour_admin') ? ',2, 5' : '') . 
				(hasView('tel_tour') || hasView('visit_tour') ? ',0, 4' : '')
			) . ') AND (1=1 ' . (
				hasView('mali') ? '' : 
				(hasView('tel_tour_admin') || hasView('visit_tour_admin') ? 'OR IFNULL(tour.UserID, 0)=#uid# ' : 
				(hasView('tel_tour') || hasView('visit_tour') ? 'OR IFNULL(ca.VisitorID, IFNULL(c.VisitorID, 0))=#uid#' : ''))
				
			) . ') ' . 
			($_GET['type'] == 'tel_tour' ? ' AND t.IsBuy=0 AND ca.VisitorID=#uid# AND t.Status IN (0, 4) ' : '') .
			($_GET['type'] == 'visit_tour' ? ' AND t.IsBuy=0 AND c.VisitorID=#uid# AND t.Status IN (0, 4) ' : '') .
			//($_GET['type'] == 'sale_manager' ? ' AND t.IsBuy=0 AND (t.UserID IN (SELECT tm.UserID FROM tteams t inner join tteam_users tm ON (t.ID = tm.TeamID) WHERE t.UserID=#uid#) OR (t.VisitID = 0 AND t.CallID=0 AND t.SaleManager=0) OR (t.SaleManager=#uid#)) AND t.Status IN (2, 5) ' : '') .
			($_GET['type'] == 'store' ? ' AND t.Status IN (7) ' : '') .
			($_GET['type'] == 'mali' ? ' AND t.Status IN (3, 8) ' : '')  . ' #where# '
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', `CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Noe'	=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							if (row.IsBuy == 1 && row.IsReturn == 0)
								return 'خرید';
							else if (row.IsBuy == 1 && row.IsReturn == 1)
								return 'برگشت خرید';
							else if (row.IsBuy == 0 && row.IsReturn == 1)
								return 'برگشت فروش';
							else
								return 'فروش';
						"
					)
				),
				'Tour'	=> array(
					'Title'	=> 'نام تور',
					'Formatter' => array(
						'Type' => 'tooltip'
					)
				),
				'Type'		=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.IsBuy == 0) {
								if (row.IsReturn == 0)
									res += 'فروش';
								else
									res += 'برگشت فروش';
							} else {
								if (row.IsReturn == 0)
									res += 'خرید';
								else
									res += 'برگشت خرید';
							}

							return res;
						"
					),
					'Enabled' => $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.UID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.Customer + '</a>';

							return res;
						"
					)
				),
				'GroupName'	=> array(
					'Title'	=> 'گروه مشتری'
				),
				'Grade'	=> array(
					'Title'	=> 'گرید'
				),
				'AllPrice'  => array(
					'Title' => 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price',
					),
					'Footer' => true
				),
				'Credit'	=> array(
					'Title'	=> 'نوع تسویه',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';
							if (row.Naghdi > 0) {
								res += 'تسویه نقدی: <br><div class=\'form-info\'>' + numeral(row.Naghdi).format('0,0') + '</div>';
							}

							if (row.Etebari > 0) {
								res += 'تسویه اعتباری: <br><div class=\'form-info\'>' + numeral(row.Etebari).format('0,0') + '</div>';
							}

							if (res == '') 
								res = '-';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'MandeHesab' => array(
					'Title' 	=> 'مانده حساب مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '<div data-toggle=\'tooltip\' title=\'' + numeral(row.MandeHesab).format('0,0') + '\' class=\"';

							if (row.MandeHesab >= 0)
								res += 'green';
							else
								res += 'red';

							res += '\">' + numeral(row.MandeHesab).format('0,0') + ' ' + (row.MandeHesab == 0 ? '' : (row.MandeHesab < 0 ? '(بد)' : '(بس)')) + '</div>';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'State'		=> array(
					'Title'	=> 'وضعیت'
				),
				'SendState' => array(
					'Title' => 'وضعیت ارسال',
					'Enabled' => $_GET['type'] == 'sale_manager' || $_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> '
							let res = "";

							res += "<div class=\"alert alert-" + row.Alert + "\">";
							res += row.SendState + "</div>";

							return res;
						'
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width' => 150,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Action'=> "EditOrder(' + row.ID + ')",
								'If' => "row.Status == 0 || row.Status == 2 || row.Status == 4 || row.Status == 5"
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title'	=> 'حذف',
								'Action'=> "DeleteOrder(#index#)",
								'If' => "row.Status == 0 || row.Status == 2 || row.Status == 4 || row.Status == 5"
							)
						)
					)
				)
			)
		),
		'orders_verified'		=> array(
			'Title'		=> 'سفارشات تایید شده  ',
			'Access'	=> $_GET['access'],
			'Controller'=> $_GET['controller'],
			'Action'	=> 'verified_orders',
			'ExtraView' => '',			
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "t.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Week']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.ShamsiDate<='#value#'"
				),
				'group' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tgroups t INNER JOIN tuser_access_groups g ON (t.ID = g.GroupID) WHERE g.UID=' . $CI->User['ID'] ,
					'Where'		=> "cu.GroupID IN (#value#)",
					'Title'		=> 'گروه مشتری',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'فروش',
						'1'	=> 'خرید',
						'2' => 'برگشت فروش',
						'3' => 'برگشت خرید'
					),
					'Where'	=> array(
						'0'	=> 't.IsBuy=0 AND t.IsReturn=0',
						'1'	=> 't.IsBuy == 1 AND t.IsReturn=0',
						'2' => 't.IsBuy=0 AND t.IsReturn=1',
						'3' => 't.IsBuy=1 AND t.IsReturn=1'
					),
					'Title'	=> 'نوع سفارش',
					'ListTitle' => 'انواع سفارش',
					'EmptyError' => 'نوع سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				)

			),
			'Query'		=> "SELECT t.ID AS `ID`, t.UID AS `UID`, IFNULL(tour.Name, '') AS Tour, CONCAT(t.FName, ' ', t.LName) AS `Customer`, t.`ShamsiDate`, t.`CreateDate`, `AllPrice`, s.Name AS `State`, t.Status, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS Visitor, t.Naghdi, t.Etebari, cu.MandeHesab, IFNULL(g.Name, '-') AS `GroupName` , t.IsBuy, t.IsReturn
			FROM torders t INNER JOIN torder_states s ON (t.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (t.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (t.UserID = u.ID AND t.ByApp=0)
			LEFT OUTER JOIN ttour_list_to_call ca ON (t.CallID = ca.ID)
			LEFT OUTER JOIN ttour_visitor_customers c ON (t.VisitID = c.ID)
			LEFT OUTER JOIN ttours tour ON (tour.ID = IFNULL(ca.TourID, IFNULL(c.TourID, 0)))
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			WHERE t.FromCrm=1 AND t.Status IN (-1" . (
				(hasView('store') ? ',1' : '') . 
				(hasView('mali') ? ',1,7' : '') . (hasView('tel_tour_admin') || hasView('visit_tour_admin') ? ',3, 8' : '') . (hasView('tel_tour') || hasView('visit_tour') ? ',2, 5' : '')
			) . ') AND (1=1 ' . (
				hasView('mali') ? '' : 
				(hasView('tel_tour_admin') || hasView('visit_tour_admin') ? 'OR IFNULL(tour.UserID, 0)=#uid# ' : 
				(hasView('tel_tour') || hasView('visit_tour') ? 'OR IFNULL(ca.VisitorID, IFNULL(c.VisitorID, 0))=#uid#' : ''))
				
			) . ') ' . 
			($_GET['type'] == 'tel_tour' ? ' AND t.IsBuy=0 AND ca.VisitorID=#uid# AND t.Status IN (2, 5) ' : '') .
			($_GET['type'] == 'visit_tour' ? ' AND t.IsBuy=0 AND c.VisitorID=#uid# AND t.Status IN (2, 5) ' : '') .
			($_GET['type'] == 'sale_manager' ? ' AND t.IsBuy=0 AND (t.UserID IN (SELECT tm.UserID FROM tteams t inner join tteam_users tm ON (t.ID = tm.TeamID) WHERE t.UserID=#uid#) OR (t.VisitID = 0 AND t.CallID=0 AND t.SaleManager=0) OR (t.SaleManager=#uid#)) AND t.Status IN (3, 8) ' : '') .
			($_GET['type'] == 'store' ? ' AND t.Status IN (1) ' : '') .
			($_GET['type'] == 'mali' ? ' AND t.Status IN (1, 7) ' : '')  . ' #where# '
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', `CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Type'		=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.IsBuy == 0) {
								if (row.IsReturn == 0)
									res += 'فروش';
								else
									res += 'برگشت فروش';
							} else {
								if (row.IsReturn == 0)
									res += 'خرید';
								else
									res += 'برگشت خرید';
							}

							return res;
						"
					),
					//'Enabled' => $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'Tour'	=> array(
					'Title'	=> 'نام تور'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.UID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.Customer + '</a>';

							return res;
						"
					)
				),
				'GroupName'	=> array(
					'Title'	=> 'گروه مشتری'
				),
				'AllPrice'  => array(
					'Title' => 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price',
					),
					'Footer' => true
				),
				'Credit'	=> array(
					'Title'	=> 'نوع تسویه',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';
							if (row.Naghdi > 0) {
								res += 'تسویه نقدی: <br><div class=\'form-info\'>' + numeral(row.Naghdi).format('0,0') + '</div>';
							}

							if (row.Etebari > 0) {
								res += 'تسویه اعتباری: <br><div class=\'form-info\'>' + numeral(row.Etebari).format('0,0') + '</div>';
							}

							if (res == '') 
								res = '-';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'MandeHesab' => array(
					'Title' 	=> 'مانده حساب مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '<div data-toggle=\'tooltip\' title=\'' + numeral(row.MandeHesab).format('0,0') + '\' class=\"';

							if (row.MandeHesab >= 0)
								res += 'green';
							else
								res += 'red';

							res += '\">' + numeral(row.MandeHesab).format('0,0') + '</div>';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'State'		=> array(
					'Title'	=> 'وضعیت'
				),
				'Options'  => array(
					'Title'	=> '',
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Action'=> "EditOrder(' + row.ID + ')",
								'If' => "row.Status == 0 || row.Status == 2 || row.Status == 4 || row.Status == 5"
							)
						)
					)
				)
			)
		),
		'orders_returned'		=> array(
			'Title'		=> 'سفارشات برگشتی',
			'Access'	=> $_GET['access'],
			'Controller'=> $_GET['controller'],
			'Action'	=> 'returned_orders',
			'ExtraView' => '',			
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID=3',
					'Where'		=> "t.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Week']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.ShamsiDate<='#value#'"
				),
				'group' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tgroups t INNER JOIN tuser_access_groups g ON (t.ID = g.GroupID) WHERE g.UID=' . $CI->User['ID'] ,
					'Where'		=> "cu.GroupID IN (#value#)",
					'Title'		=> 'گروه مشتری',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'فروش',
						'1'	=> 'خرید',
						'2' => 'برگشت فروش',
						'3' => 'برگشت خرید'
					),
					'Where'	=> array(
						'0'	=> 't.IsBuy=0 AND t.IsReturn=0',
						'1'	=> 't.IsBuy == 1 AND t.IsReturn=0',
						'2' => 't.IsBuy=0 AND t.IsReturn=1',
						'3' => 't.IsBuy=1 AND t.IsReturn=1'
					),
					'Title'	=> 'نوع سفارش',
					'ListTitle' => 'انواع سفارش',
					'EmptyError' => 'نوع سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				)
			),
			'Query'		=> "SELECT t.ID AS `ID`, t.UID AS `UID`, IFNULL(tour.Name, '') AS Tour, CONCAT(t.FName, ' ', t.LName) AS `Customer`, t.`ShamsiDate`, t.`CreateDate`, `AllPrice`, s.Name AS `State`, t.Status, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS Visitor, t.Naghdi, t.Etebari, cu.MandeHesab, IFNULL(g.Name, '-') AS `GroupName`, t.IsBuy, t.IsReturn
			FROM torders t INNER JOIN torder_states s ON (t.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (t.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (t.UserID = u.ID AND t.ByApp=0)
			LEFT OUTER JOIN ttour_list_to_call ca ON (t.CallID = ca.ID)
			LEFT OUTER JOIN ttour_visitor_customers c ON (t.VisitID = c.ID)
			LEFT OUTER JOIN ttours tour ON (tour.ID = IFNULL(ca.TourID, IFNULL(c.TourID, 0)))
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			WHERE t.FromCrm=1 AND t.Status IN (-1" . (
				(hasView('store') ? ',8' : '') . 
				(hasView('mali') ? ',5' : '') . (hasView('tel_tour_admin') || hasView('visit_tour_admin') ? ',4' : '') 
			) . ') AND (1=1 ' . (
				hasView('mali') ? '' : 
				(hasView('tel_tour_admin') || hasView('visit_tour_admin') ? 'OR IFNULL(tour.UserID, 0)=#uid# ' : ''
				)
				
			) . ') ' . 
			($_GET['type'] == 'sale_manager' ? ' AND t.IsBuy=0 AND (t.UserID IN (SELECT tm.UserID FROM tteams t inner join tteam_users tm ON (t.ID = tm.TeamID) WHERE t.UserID=#uid#) OR (t.VisitID = 0 AND t.CallID=0 AND t.SaleManager=0) OR (t.SaleManager=#uid#)) AND t.Status IN (4) ' : '') .
			($_GET['type'] == 'store' ? ' AND t.Status IN (8) ' : '') .
			($_GET['type'] == 'mali' ? ' AND t.Status IN (5) ' : '')  . ' #where# '
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', t.`CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Type'		=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.IsBuy == 0) {
								if (row.IsReturn == 0)
									res += 'فروش';
								else
									res += 'برگشت فروش';
							} else {
								if (row.IsReturn == 0)
									res += 'خرید';
								else
									res += 'برگشت خرید';
							}

							return res;
						"
					),
					//'Enabled' => $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'Tour'	=> array(
					'Title'	=> 'نام تور'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.UID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.Customer + '</a>';

							return res;
						"
					)
				),
				'GroupName'	=> array(
					'Title'	=> 'گروه مشتری'
				),
				'AllPrice'  => array(
					'Title' => 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price',
					),
					'Footer' => true
				),
				'Credit'	=> array(
					'Title'	=> 'نوع تسویه',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';
							if (row.Naghdi > 0) {
								res += 'تسویه نقدی: <br><div class=\'form-info\'>' + numeral(row.Naghdi).format('0,0') + '</div>';
							}

							if (row.Etebari > 0) {
								res += 'تسویه اعتباری: <br><div class=\'form-info\'>' + numeral(row.Etebari).format('0,0') + '</div>';
							}

							if (res == '') 
								res = '-';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'MandeHesab' => array(
					'Title' 	=> 'مانده حساب مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '<div data-toggle=\'tooltip\' title=\'' + numeral(row.MandeHesab).format('0,0') + '\' class=\"';

							if (row.MandeHesab >= 0)
								res += 'green';
							else
								res += 'red';

							res += '\">' + numeral(row.MandeHesab).format('0,0') + '</div>';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'State'		=> array(
					'Title'	=> 'وضعیت'
				),
				'Options'  => array(
					'Title'	=> '',
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Action'=> "EditOrder(' + row.ID + ')",
								'If' => "row.Status == 0 || row.Status == 2 || row.Status == 4 || row.Status == 5"
							)
						)
					)
				)
			)
		),
		'orders_to_deliver'		=> array(
			'Title'		=> 'سفارشات آماده ارسال',
			'Access'	=> 'distribute',
			'Controller'=> 'distribute',
			'Action'	=> 'orders_to_deliver',
			'ExtraView' => 'distribute/orders_to_deliver',	
			'AddData'	=> array(
				'Shifts'	=> 'SELECT ID, Name, Az, Ta FROM tshifts WHERE Deleted=0',
				'MapFilters'=> array(
					'map_senf' => $global_filters['map_senf'],
					'map_Country'	=> $global_filters['map_Country'],
					'map_State'	=> $global_filters['map_State'],
					'map_City'	=> $global_filters['map_City'],
					'map_Area'	=> $global_filters['map_Area'],
					'map_Block'	=> $global_filters['map_Block'],
					'map_grade' => $global_filters['map_grade'],
					'map_users' => $global_filters['map_users']
	
				)
			),	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'DefaultOptions' => 'SELECT ID, Name FROM bstates WHERE CountryID IN (' . intval($CI->User['DefaultCountry']) . ')',
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید',		
					'Default'	=> intval($CI->User['DefaultState'])	
				),
				'City'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', t.Name) AS Name FROM bcities t INNER JOIN bstates s ON (t.SID = s.ID) WHERE t.SID IN (?)",
					'DefaultOptions' => "SELECT t.ID, CONCAT(s.Name, ' - ', t.Name) AS Name FROM bcities t INNER JOIN bstates s ON (t.SID = s.ID) WHERE t.SID IN (" . intval($CI->User['DefaultState']) . ')',
					'Where'		=> "o.City IN (#value#)",
					'Title'		=> 'شهر',
					'ListTitle' => 'شهر ها',
					'Relation'	=> 'State',
					'InRelation'=> array( 'Area', 'Block' ),
					'EmptyError'=> 'استان را انتخاب نمایید',
					'Default'	=> intval($CI->User['DefaultCity'])	
				),
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'user' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE Deleted=0 AND Active=1 AND Distribute=1",
					'Where'		=> "o.DistributeUser IN (#value#)",
					'Title'		=> 'موزع',
					'ListTitle' => 'موزعین',
					'EmptyError'=> 'موزعی پیدا نشد'
				),
				'user_type' => array(
					'Type'	=> 'select',
					'Options' => array('1'	=> 'نمایش همه', '2' => 'موزع اختصاص داده شده', '3' => 'موزع اختصاص داده نشده'),
					'Where'		=> array(
						'1'	=> '',
						'2'	=> 'o.DistributeUser<>0',
						'3'	=> 'o.DistributeUser=0'
					),
					'Title'		=> 'موزع اختصاص داده شده/نشده',
					'ListTitle' => 'انتخاب',
					'EmptyError'=> ''
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل:',
					'Where' => "o.DeliverTarikh>='#value#'",
					'Value'	=> ''
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل:',
					'Where' => "o.DeliverTarikh<='#value#'",
					'Value' => ''
				),
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, IFNULL(shift.Name, '') AS Shift, o.OrderState, o.DistributeTozihat
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.IsBuy=0 AND o.IsReturn=0 AND o.FromCrm=1 AND o.Status=1 AND o.OrderState<>3  #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', t.`CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (cu.FName LIKE #key# OR cu.LName LIKE #key# OR cu.ShopName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID ASC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" data-toggle=\"tooltip\" title=\"' + row.ShopName + '\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Senf'	=> array(
					'Title'	=> 'نام شرکت یا صنف',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';
							else
								res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);
							
							res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'DistributeUser'	=> array(
					'Title'	=> 'موزع',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Deliver'  => array(
					'Title' => 'تاریخ و بازه تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliverTarikh;
							if (res != '')
								res += '<br>';

							/*if (row.DeliverAzHour != 0 && row.DeliverTaHour != 0)
								res += row.DeliverAzHour + ' تا ' + row.DeliverTaHour;*/
							res += row.Shift;

							if (row.DistriibuteTozihat != '')
								res += '<br>' + row.DistributeTozihat; 

							res = GetElementByTooltip('div', replaceAll('<br>', ' ', res), res);
							
							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-user-plus green',
								'Title'	=> 'اختصاص موزع',
								'Action'=> "AddUserToOrder(' + row.ID + ')",
								'If' => "row.DUser == 0"
							),
							array(
								'Icon'	=> 'fas fa-user-times red',
								'Title'	=> 'حذف تخصیص موزع',
								'Action'=> "RemoveUserFromOrder(#index#)",
								'If' => "row.DUser != 0"
							)
						)
					)
				)
			)
		),
		'orders_to_deliver_by_user'		=> array(
			'Title'		=> 'سفارشات آماده ارسال',
			'Access'	=> $CI->User['Distribute'] == 1,
			'Controller'=> 'distribute',
			'Action'	=> 'orders_to_deliver_by_user',
			'ExtraView' => 'distribute/orders_to_deliver_by_user',	
			'AddData'	=> array(
				'Shifts'	=> 'SELECT ID, Name, Az, Ta FROM tshifts WHERE Deleted=0',
				'Results'	=> 'SELECT ID, Title, Operation FROM ttour_call_results WHERE Type=2',
				'T1Day'		=> $CI->getShamsiDate(time() + (24 * 60 * 60)),
				'T2Day'		=> $CI->getShamsiDate(time() + (2 * 24 * 60 * 60)),
				'T3Day'		=> $CI->getShamsiDate(time() + (3 * 24 * 60 * 60)),
				'T4Day'		=> $CI->getShamsiDate(time() + (4 * 24 * 60 * 60)),
				'MapFilters'=> array(
					'map_senf' => $global_filters['map_senf'],
					'map_Country'	=> $global_filters['map_Country'],
					'map_State'	=> $global_filters['map_State'],
					'map_City'	=> $global_filters['map_City'],
					'map_Area'	=> $global_filters['map_Area'],
					'map_Block'	=> $global_filters['map_Block'],
					'map_grade' => $global_filters['map_grade'],
					'map_az'	=> $global_filters['az'],
					'map_ta'	=> $global_filters['ta'],
					'map_shifts'=> $global_filters['shifts']
	
				)
			),	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل:',
					'Where' => "o.DeliverTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل:',
					'Where' => "o.DeliverTarikh<='#value#'"
				),
				'shift' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tshifts',
					'Where'		=> "o.DeliverShift IN (#value#)",
					'Title'		=> 'شیفت',
					'ListTitle' => 'شیفت ها',
					'EmptyError'=> 'شیفتی پیدا نشد'
				),	
				'peigiri'	=> array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'نمایش همه',
						'1'	=> 'فقط پیگیری ها',
						'2'	=> 'عدم نمایش پیگیری ها'
					),
					'Where'	=> array(
						'0'	=> '',
						'1'	=> ' o.Peigiri<>0 ',
						'2'	=> ' o.Peigiri=0 '
					),
					'Title'	=> 'نوع نمایش',
					'ListTitle' => 'نوع نمایش',
					'EmptyError'	=> ''
				)			
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, o.Peigiri, IFNULL(tcall.PeigiriTarikh, '') AS PeigiriTarikh, IFNULL(tcall.PeigiriTime, '') AS PeigiriTime, DistributeTozihat
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			INNER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			LEFT OUTER JOIN ttour_calls tcall ON (tcall.ID = o.Peigiri)
			WHERE o.IsBuy=0 AND o.FromCrm=1 AND o.Status=1 AND o.OrderState<>3 AND o.DistributeUser=#uid#  #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', t.`CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID ASC',
			'Columns'	=> array(
				'Peigiri'	=> array(
					'Title'	=> 'پیگیری',
					'Width' => 50,
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.Peigiri != 0) {
								res += '<div class=\"peigiri\" data-toggle=\"tooltip\" title=\"پیگیری در تاریخ ' + row.PeigiriTarikh + ' ' + row.PeigiriTime + '\">&nbsp;</div>';
							}
							else 
								res = '-';	

							return res;
						"
					)
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" data-toggle=\"tooltip\" title=\"' + row.ShopName + '\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';
							else
								res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);
							res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Deliver'  => array(
					'Title' => 'تاریخ و بازه تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliverTarikh;
							if (res != '')
								res += '<br>';

							if (row.Az != 0 && row.Ta != 0)
								res += row.Az + ' تا ' + row.Ta;

							if (row.DistributeTozihat != '')
								res += '<br>' + row.DistributeTozihat;

							res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Class'	=> 'multiline',
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-truck',
								'Title'	=> 'تحویل',
								'Action'=> "Deliver(#index#)"
							),
							array(
								'Icon'	=> 'fas fa-exchange-alt',
								'Title' => 'ثبت درخواست مرجوعی',
								'Action'=> "ReturnOrder(#index#);"
							),
							array(
								'Icon'	=> 'fas fa-headset',
								'Title'	=> 'ثبت نتیجه حضور',
								'Action'=> "SabtResult(#index#)"
							)
						)
					)
				)
			)
		),
		'orders_delivered'		=> array(
			'Title'		=> 'سفارشات تحویل داده شده',
			'Access'	=> 'distribute',
			'Controller'=> 'distribute',
			'Action'	=> 'orders_delivered',
			'ExtraView' => '',	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل:',
					'Where' => "o.DeliverTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل:',
					'Where' => "o.DeliverTarikh<='#value#'"
				),
				'shift' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tshifts',
					'Where'		=> "o.DeliverShift IN (#value#)",
					'Title'		=> 'شیفت',
					'ListTitle' => 'شیفت ها',
					'EmptyError'=> 'شیفتی پیدا نشد'
				),				
				'user' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE Distribute=1",
					'Where'		=> "o.DistributeUser IN (#value#)",
					'Title'		=> 'موزع',
					'ListTitle' => 'موزع ها',
					'EmptyError'=> 'موزعی پیدا نشد'
				),				
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			INNER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.IsBuy=0 AND o.FromCrm=1 AND o.Status=9 AND o.OrderState=3 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (o.FName LIKE #key# OR o.LName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" data-toggle=\"tooltip\" title=\"' + row.ShopName + '\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'DistributeUser' => array(
					'Title'	=> 'موزع',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';
							else
								res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);
							res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'AllPrice'	=> array(
					'Title'	=> 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'Deliver'  => array(
					'Title' => 'تاریخ و بازه تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliverTarikh;
							if (res != '')
								res += '<br>';

							if (row.Az != 0 && row.Ta != 0)
								res += row.Az + ' تا ' + row.Ta;
								res = '<span data-toggle=\"tooltip\" title=\"' + res + '\">' + res + '</span>';

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'رسید تحویل',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-exchange-alt',
								'Title' => 'برگشت به کارتابل آماده تحویل',
								'Action'=> "ReturnOrder(#index#);"
							),
						)
					)
				)
			)
		),
		'orders_monitor_deliver'		=> array(
			'Title'		=> 'مانیتورینگ موزعین',
			'Access'	=> 'distribute',
			'Controller'=> 'distribute',
			'Action'	=> 'orders_monitor_deliver',
			'ExtraView' => 'distribute/orders_monitor_deliver',	
			'AddData'	=> array(
				'Shifts'	=> 'SELECT ID, Name, Az, Ta FROM tshifts WHERE Deleted=0',
				'MapFilters'=> array(
					'map_senf' => $global_filters['map_senf'],
					'map_Country'	=> $global_filters['map_Country'],
					'map_State'	=> $global_filters['map_State'],
					'map_City'	=> $global_filters['map_City'],
					'map_Area'	=> $global_filters['map_Area'],
					'map_Block'	=> $global_filters['map_Block'],
					'map_grade' => $global_filters['map_grade'],
					'map_users' => $global_filters['map_users'],
					'map_az'	=> $global_filters['az'],
					'map_ta'	=> $global_filters['ta'],
					'map_shifts'=> $global_filters['shifts'],
					'map_azd' => array(
						'Type'	=> 'shamsi_date',
						'Title'	=> 'از تاریخ تحویل داده شده:'
					),
					'map_tad' => array(
						'Type'	=> 'shamsi_date',
						'Title'	=> 'تا تاریخ تحویل داده شده:'
					)
				)
			),	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'user' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE Deleted=0 AND Active=1 AND Distribute=1",
					'Where'		=> "o.DistributeUser IN (#value#)",
					'Title'		=> 'موزع',
					'ListTitle' => 'موزعین',
					'EmptyError'=> 'موزعی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل:',
					'Where' => "o.DeliverTarikh>='#value#'",
					'Value'	=> ''
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل :',
					'Where' => "o.DeliverTarikh<='#value#'",
					'Value' => ''
				),
				'daz'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل داده شده:',
					'Where' => "o.DeliveredTarikh>='#value#'",
					'Value'	=> ''
				),
				'dta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل داده شده:',
					'Where' => "o.DeliveredTarikh<='#value#'",
					'Value' => ''
				),
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, IFNULL(shift.Name, '') AS Shift, o.OrderState, o.DeliveredTarikh, o.DeliveredTime, CONCAT(o.DeliveredTarikh, ' ', o.DeliveredTime) AS Delivered
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.Isbuy=0 AND o.FromCrm=1 AND o.Status=1 AND o.OrderState<>3  #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(t.FName, ' ', t.LName) AS `مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', t.`CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, CONCAT(u.FName, ' ', u.LName) AS 'ویزیتور'",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID ASC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'DistributeUser'	=> array(
					'Title'	=> 'موزع'
				),
				'Deliver'  => array(
					'Title' => 'تاریخ و بازه تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliverTarikh;
							if (res != '')
								res += '<br>';

							/*if (row.DeliverAzHour != 0 && row.DeliverTaHour != 0)
								res += row.DeliverAzHour + ' تا ' + row.DeliverTaHour;*/
							res += row.Shift;

							res = GetElementByTooltip('div', res.replace('<br>', ' '), res);

							return res;
						"
					)
				),
				'Delivered'	=> array(
					'Title'	=> 'تاریخ و ساعت تحویل',
					'Formatter' => array(
						'Type'	=> 'tooltip',
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							)
						)
					)
				)
			)
		),
		'orders_delivered_by_user'		=> array(
			'Title'		=> 'درخواست های تحویل داده شده',
			'Access'	=> $CI->User['Distribute'] == 1,
			'Controller'=> 'distribute',
			'Action'	=> 'orders_delivered_by_user',
			'ExtraView' => '',	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ تحویل:',
					'Where' => "o.DeliveredTarikh>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ تحویل:',
					'Where' => "o.DeliveredTarikh<='#value#'"
				),
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, o.DeliveredTarikh, o.DeliveredTime
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			INNER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.IsBuy=0 AND o.FromCrm=1 AND o.Status=9 AND o.OrderState=3 AND o.DistributeUser=#uid# #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (o.FName LIKE #key# OR o.LName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس'
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه'
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت'
				),
				'AllPrice'	=> array(
					'Title'	=> 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'Delivered'  => array(
					'Title' => 'تاریخ تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliveredTarikh + ' ' + row.DeliveredTime;

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'رسید تحویل',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
						)
					)
				)
			)
		),
		'orders_returned_by_user'		=> array(
			'Title'		=> 'لیست سفارشات مرجوعی',
			'Access'	=> $CI->User['Distribute'] == 1,
			'Controller'=> 'distribute',
			'Action'	=> 'orders_returned_by_user',
			'ExtraView' => '',	
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "o.ShamsiDate>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "o.ShamsiDate<='#value#'"
				),
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, o.DeliveredTarikh, o.DeliveredTime
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (o.UserID = u.ID AND o.ByApp=0)
			LEFT OUTER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.IsBuy= 0 AND o.FromCrm=1 AND o.UserID=#uid# AND o.IsReturn=1 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (o.FName LIKE #key# OR o.LName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس'
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه'
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت'
				),
				'AllPrice'	=> array(
					'Title'	=> 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
						)
					)
				)
			)
		),
		'collector_cheqs'		=> array(
			'Title'		=> 'لیست چک ها',
			'Access'	=> 'collector',
			'Controller'=> 'collector',
			'Action'	=> 'cheqs',
			'ExtraView' => 'collector/cheqs',	
			'AddData'	=> array(
				'States'	=> "SELECT ID, Name FROM tcheq_states"
			),
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'state' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcheq_states',
					'Where'		=> "p.CheqState IN (#value#)",
					'Title'		=> 'وضعیت',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "p.TarikhVariz>='#value#'"
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "p.TarikhVariz<='#value#'"
				),
			),
			'Query'		=> "SELECT p.ID, p.CreateDate, p.Credit, p.TarikhVariz, p.ShCheq, p.CheqName, p.CheqState, p.Tozihat, p.CheqHesab, p.OrderID, p.CheqSerial, p.CheqBank,p.ImportID, p.ShSanad, p.TarikhSanad, IFNULL(s.Name, '') AS State, CONCAT(u.FName, ' ', u.LName) AS User, p.UID, cu.ShopName, IFNULL(grade.Name, '') AS Grade, cu.Phone, cu.Tel
			FROM tpays p INNER JOIN tcustomers cu ON (p.UID = cu.ID)
			LEFT OUTER JOIN tcheq_states s ON (p.CheqState = s.ID)
			LEFT OUTER JOIN tuser u ON (p.VisitorID = u.ID )
			LEFT OUTER JOIN torders o ON (p.OrderID = o.ID) 
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE p.Type=3 AND p.Deleted=0 #where#"
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'cheqs', 
			'KeyFilter' => 'AND (p.TarikhVariz LIKE #key# OR p.CheqName LIKE #key# OR p.CheqSerial LIKE #key# OR p.CheqHesab LIKE #key#)',
			'Sort'		=> 'p.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'TarikhVariz'=> array (
					'Title'	=> 'تاریخ چک',
				),
				'Credit'=> array (
					'Title'	=> 'مبلغ چک',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'CheqName'=> array (
					'Title'	=> 'نام حساب چک',
				),
				'CheqSerial'=> array (
					'Title'	=> 'سریال چک',
				),
				'State'=> array (
					'Title'	=> 'وضعیت چک',
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه'
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'تغییر وضعیت چک',
								'Action'=> "ChangeState(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده سفارش',
								'Action'=> "ViewOrder(' + row.OrderID + ')",
								'If'	=> 'row.OrderID != 0'
							),
						)
					)
				)
			)
		),
		'support_orders'		=> array(
			'Title'		=> 'لیست سفارشات تحویلی جاری',
			'Access'	=> 'support',
			'Controller'=> 'support',
			'Action'	=> 'orders',
			'ExtraView' => 'support/orders',
			'AddData'	=> array(
				'function' => support_order_add_data
			),
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "o.Tarikh>='#value#'",
					'Value'	=> '1399/01/01'
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "o.Tarikh<='#value#'",
				),
			),
			'Query'		=> "SELECT o.ID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(u.FName, ''), ' ', IFNULL(u.LName, '')) AS User, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, o.DeliveredTarikh, o.DeliveredTime, cu.GroupID
			FROM torders o INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tuser u ON (o.UserID = u.ID)
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			INNER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE o.IsBuy=0 AND o.FromCrm=1 AND o.Status=9 AND o.OrderState=3 AND o.ReviewID=0 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (o.FName LIKE #key# OR o.LName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'DistributeUser' => array(
					'Title'	=> 'موزع',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Address1'	=> array(
					'Title'	=> 'آدرس'
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'Noe'	=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							if (row.IsBuy == 1 && row.IsReturn == 0)
								return 'خرید';
							else if (row.IsBuy == 1 && row.IsReturn == 1)
								return 'برگشت خرید';
							else if (row.IsBuy == 0 && row.IsReturn == 1)
								return 'برگشت فروش';
							else
								return 'فروش';
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه'
				),
				'Tel'		=> array(
					'Title'	=> 'تلفن ثابت'
				),
				'AllPrice'	=> array(
					'Title'	=> 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'DeliveredTarikh'  => array(
					'Title' => 'تاریخ و بازه تحویل',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							res += row.DeliverTarikh;
							if (res != '')
								res += '<br>';

							if (row.Az != 0 && row.Ta != 0)
								res += row.Az + ' تا ' + row.Ta;

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده سفارش',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-clipboard-check',
								'Title' => 'ثبت چک لیست',
								'Action'=> "OpenOrder(#index#);"
							),
						)
					)
				)
			)
		),
		'support_reviews'		=> array(
			'Title'		=> 'نظرات ثبت شده',
			'Access'	=> 'support',
			'Controller'=> 'support',
			'Action'	=> 'reviews',
			'ExtraView' => 'support/reviews',
			'Filters'	=> array(
				'senf' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
					'Where'		=> "cu.SenfID IN (#value#)",
					'Title'		=> 'صنف',
					'ListTitle' => 'اصناف',
					'EmptyError'=> 'صنفی پیدا نشد'
				),
				'Country'	=> $config_country,
				'State'	=> array(
					'Type'		=> 'select',
					'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
					'Where'		=> "cu.State IN (#value#)",
					'Title'		=> 'استان',
					'ListTitle' => 'استان ها',
					'Relation'	=> 'Country',
					'InRelation'=> array( 'City', 'Area', 'Block' ),
					'EmptyError'=> 'کشور را انتخاب نمایید'			
				),
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'grade' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
					'Where'		=> "cu.Grade IN (#value#)",
					'Title'		=> 'گرید',
					'ListTitle' => 'گریدها',
					'EmptyError'=> 'گریدی پیدا نشد'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "r.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "r.ShamsiDate<='#value#'",
				),
			),
			'Query'		=> "SELECT r.ID, o.ID AS OrderID, cu.ShopName, cu.ID AS CustomerID, IFNULL(sf.Name, '') AS Senf, cu.Address1, IFNULL(c.Name, '') AS Country, IFNULL(st.Name, '') AS State, IFNULL(city.Name, '') AS City, IFNULL(area.Name, '') AS Area, IFNULL(block.Name, '') AS Block, IFNULL(grade.Name, '') AS Grade, o.ShamsiDate, o.CreateDate, r.ShamsiDate AS ReviewTarikh, r.ShamsiTime AS ReviewTime, cu.Phone, cu.Tel, o.DeliverTarikh, o.DeliverAzHour, o.DeliverTaHour, CONCAT(IFNULL(u.FName, ''), ' ', IFNULL(u.LName, '')) AS User, CONCAT(IFNULL(ud.FName, ''), ' ', IFNULL(ud.LName, '')) AS DistributeUser, o.DistributeUser AS DUser, shift.Name, shift.Az, shift.Ta, o.AllPrice, CONCAT(IFNULL(p.FName, ''), ' ', IFNULL(p.LName, '')) AS Malek, o.DeliveredTarikh, o.DeliveredTime, cu.GroupID, res.Name as Result, res.Color
			FROM torder_reviews r INNER JOIN torders o ON (r.OrderID = o.ID) INNER JOIN torder_states s ON (o.OrderState = s.ID) 
			INNER JOIN tuser u ON (o.UserID = u.ID)
			INNER JOIN tcustomers cu ON (o.UID = cu.ID)
			INNER JOIN tshifts shift ON (o.DeliverShift = shift.ID)
			INNER JOIN treview_results res ON (r.ResultID = res.ID)
			LEFT OUTER JOIN tpersons p ON (cu.MalekID = p.ID)
			LEFT OUTER JOIN tuser ud ON (o.DistributeUser = ud.ID)
			LEFT OUTER JOIN tsenfs sf ON (cu.SenfID = sf.ID)
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			LEFT OUTER JOIN tcountries c ON (cu.Country = c.ID)
			LEFT OUTER JOIN bstates st ON (cu.State = st.ID)
			LEFT OUTER JOIN bcities city ON (cu.City = city.ID)
			LEFT OUTER JOIN bareas area ON (cu.Area = area.ID)
			LEFT OUTER JOIN bblocks block ON (cu.Block = block.ID)
			LEFT OUTER JOIN tgrades grade ON (cu.Grade = grade.ID)
			WHERE 1=1 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (o.FName LIKE #key# OR o.LName LIKE #key# OR o.ShamsiDate LIKE #key#)',
			'Sort'		=> 'o.ID DESC',
			'Columns'	=> array(
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.CustomerID != '0')
								res += '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'DistributeUser' => array(
					'Title'	=> 'موزع',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							/*if (row.Country != '')
								res += row.Country;

							if (row.State != '') {
								if (res != '')
									res += ' - ';

								res += row.State;
							}

							if (row.City != '') {
								if (res != '')
									res += ' - ';

								res += row.City;
							}

							if (row.Area != '') {
								if (res != '')
									res += ' - ';

								res += row.Area;
							}*/

							if (row.Block != '') {
								if (res != '')
									res += ' - ';

								res += row.Block;
							}

							if (res == '')
								res = '-';

							return res;
						"
					)
				),
				'Grade'	=> array(
					'Title' => 'گرید'
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'Phone'		=> array(
					'Title'	=> 'تلفن همراه'
				),
				'AllPrice'	=> array(
					'Title'	=> 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'Result'		=> array(
					'Title'	=> 'نتیجه'
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده سفارش',
								'Action'=> "ViewOrder(' + row.OrderID + ')"
							),
							array(
								'Icon'	=> 'fas fa-clipboard-check',
								'Title' => 'مشاهده چک لیست',
								'Action'=> "ViewReview(' + row.ID + ');"
							),
						)
					)
				)
			)
		),
		'targets_admin'		=> array(
			'Title'		=> 'مدیریت تارگت ها',
			'Access'	=> 'targets',
			'Controller'=> 'targets',
			'Action'	=> 'admin',
			'ExtraView' => 'targets/admin',
			'AddData'	=> array(
				'Groups'	=> 'SELECT ID, Name FROM tgroups',
				'Senfs'		=> 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
				'Grades'	=> 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
				'Areas'		=> "SELECT a.ID, CONCAT(c.Name, ' - ', a.Name) AS Name FROM bareas a INNER JOIN bcities c ON (a.CityID = c.ID)"
			),
			'Filters'	=> array(
				/*'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "t.AzTarikh>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "t.TaTarikh<='#value#'",
				),*/
			),
			'Actions'	=> array(
				/*array(
					'Title'	=> 'تارگت جدید ویزیتور',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewTarget(1);'
				),*/
				array(
					'Title'	=> 'تارگت جدید مشتری',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewTarget(2);'
				)
			),
			'Query'		=> "SELECT t.ID, t.Type, t.Name, t.AzTarikh, t.TaTarikh, t.Target, t.Active, CONCAT(u.FName, ' ', u.LName) AS User
			FROM ttargets t INNER JOIN tuser u ON (t.UserID = u.ID)
			WHERE t.Deleted=0 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'targets', 
			'KeyFilter' => 'AND (t.Name LIKE #key# OR t.AzTarikh LIKE #key# OR t.TaTarikh LIKE #key# OR u.FName LIKE #key# OR LName LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'User'=> array(
					'Title'	=> 'کاربر',
				),
				'Name' => array(
					'Title'	=> 'نام',
				),
				'Type'	=> array(
					'Title'	=> 'نوع',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							if (row.Type == 1)
								return 'ویزیتور';
							else
								return 'مشتری';
						"
					)
				),
				'AzTarikh'		=> array(
					'Title'	=> 'از تاریخ',
				),
				'TaTarikh'	=> array(
					'Title' => 'تا تاریخ'
				),
				'Target'=> array (
					'Title'	=> 'تارگت',
					'Formatter' => array(
						'Type'	=> 'price',
					)
				),
				'Active'		=> array(
					'Title'	=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.Active == 1) 
								return '<i class=\"far fa-check-circle green state\" onclick=\"ToggleActive(' + tours.length + ');\"></i>';
							else
								return '<i class=\"far fa-times-circle red state\" onclick=\"ToggleActive(' + tours.length + ');\"></i>';

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							/*array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده ',
								'Action'=> "ViewTarget(' + row.ID + ')"
							),*/
							array(
								'Icon'	=> 'fas fa-edit',
								'Title' => 'ویرایش',
								'Action'=> "EditTarget(' + row.ID + ');"
							),
						)
					)
				)
			)
		),
		'advisor_users'		=> array(
			'Title'		=> 'مدیریت مشاور ها',
			'Access'	=> 'advisor',
			'Controller'=> 'advisor',
			'Action'	=> 'users',
			'ExtraView' => 'advisor/users',
			'AddData'	=> array(
			),
			'Actions'	=> array(
				/*array(
					'Title'	=> 'تارگت جدید ویزیتور',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewTarget(1);'
				),*/
				array(
					'Title'	=> 'مشاور جدید',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewAdvisor();'
				)
			),
			'Query'		=> "SELECT u.ID, CONCAT(u.FName, ' ', u.LName) AS Name, u.Active, u.Username, c.ID AS CustomerID, c.ShopName
			FROM tuser u INNER JOIN tcustomers c ON (u.Advisor = c.ID)
			WHERE u.Deleted=0 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'users', 
			'KeyFilter' => 'AND (u.FName LIKE #key# OR u.LName LIKE #key# OR c.ShopName LIKE #key#)',
			'Sort'		=> 'u.ID DESC',
			'Columns'	=> array(
				'Name'=> array(
					'Title'	=> 'نام',
				),
				'Username' => array(
					'Title'	=> 'نام کاربری',
				),
				'ShopName'	=> array(
					'Title'	=> 'فروشگاه',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							res = '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ')\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Active'		=> array(
					'Title'	=> 'وضعیت',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.Active == 1) 
								return '<i class=\"far fa-check-circle green state\" onclick=\"ToggleActive(#index#);\"></i>';
							else
								return '<i class=\"far fa-times-circle red state\" onclick=\"ToggleActive(#index#);\"></i>';

							return res;
						"
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 120,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							/*array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده ',
								'Action'=> "ViewTarget(' + row.ID + ')"
							),*/
							array(
								'Icon'	=> 'fas fa-edit',
								'Title' => 'ویرایش',
								'Action'=> "EditAdvisor(#index#);"
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title' => 'حذف',
								'Action'=> "DelAdvisor(#index#);"
							),
						)
					)
				)
			)
		),
		'advisor_orders'		=> array(
			'Title'		=> 'سفارشات من',
			'Access'	=> !empty($CI->User['Advisor']),
			'Controller'=> 'advisor',
			'Action'	=> 'orders',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "ShamsiDate<='#value#'"
				),
			),
			'ExtraView' => 'advisor/orders',
			'AddData'	=> array(
			),
			'Query'		=> "SELECT ID, FName, LName, Mobile, CONCAT(ShamsiDate, ' ', ShamsiTime) AS Tarikh
			FROM tadvisor_orders 
			WHERE UserID=#uid# AND Deleted=0 #where# "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (FName LIKE #key# OR LName LIKE #key# OR Mobile LIKE #key#)',
			'Sort'		=> 'ID DESC',
			'Columns'	=> array(
				'FName'=> array(
					'Title'	=> 'نام',
				),
				'LName' => array(
					'Title'	=> 'نام کاربری',
				),
				'Mobile'=> array(
					'Title'	=> 'تلفن همراه'
				),
				'Tarikh'=> array(
					'Title'	=> 'تاریخ ثبت'
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 150,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده ',
								'Action'=> "ViewAdvisorOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title' => 'ویرایش',
								'Action'=> "EditAdvisorOrder(' + row.ID + ');"
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title' => 'حذف',
								'Action'=> "DelAdvisorOrder(#index#);"
							),
						)
					)
				)
			)
		),
		'store_products_report'		=> array(
			'Title'		=> 'گردش کالا',
			'Access'	=> 'store',
			'Controller'=> 'store',
			'Action'	=> 'products',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "s.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "s.ShamsiDate<='#value#'"
				),
				'product' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tproducts t WHERE t.Deleted=0' ,
					'Where'		=> "pm.PID IN (#value#)",
					'Title'		=> 'کالا',
					'ListTitle' => 'کالاها',
					'EmptyError'=> 'کالایی پیدا نشد'
				),
				'store' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tstores t WHERE t.Deleted=0' ,
					'Where'		=> "s.StoreID IN (#value#)",
					'Title'		=> 'انبار',
					'ListTitle' => 'انبارها',
					'EmptyError'=> 'انباری پیدا نشد'
				),
				'reseller' => array(
					'Type'	=> 'select',
					'Options' => "SELECT t.ID, CONCAT(FName, ' ', LName) AS Name FROM tuser t WHERE t.Deleted=0 AND t.IsReseller=1" ,
					'Where'		=> "s.ResellerID IN (#value#)",
					'Title'		=> 'تامین کننده',
					'ListTitle' => 'تامین کننده ها',
					'EmptyError'=> 'تامین کننده ای پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'ورود',
						'2' => 'خروج',
					),
					'Where'	=> array(
						'1'	=> 's.Count>0',
						'2'	=> 's.Count<0',
					),
					'Title'	=> 'نوع گردش',
					'ListTitle' => 'انواع گردش',
					'EmptyError' => 'نوع گردش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
			),
			'Query'		=> "SELECT CONCAT(s.ShamsiDate, ' ', s.ShamsiTime) AS Tarikh, s.Count, s.State, CONCAT(st.Name, IFNULL(CONCAT(' - ', r.FName, ' ', r.LName), '')) AS Store, (CASE WHEN s.ByCustomer=0 THEN CONCAT(u.FName, ' ', u.LName) ELSE cu.ShopName END) AS User, IFNULL(c.ID, 0) AS CustomerID, IFNULL(c.ShopName, '-') AS ShopName, s.OrderID, p.Name AS Product, p.Code AS CodeProduct, IFNULL(c.Code, '-') AS ShHesab, IFNULL(oi.OldPrice, 0) AS OldPrice, IFNULL(oi.Price, 0) AS Price, IFNULL(oi.TakhfifType, 0) AS TakhfifType, IFNULL(oi.TakhfifPercent, 0) AS TakhfifPercent, IFNULL(oi.TakhfifRial, 0) AS TakhfifRial 
				FROM tproduct_mojodi_trans s INNER JOIN tstores st ON (s.StoreID = st.ID)
				INNER JOIN tproduct_mojodi pm ON (s.PMID = pm.ID)
				INNER JOIN tproducts p ON (pm.PID = p.ID)
				LEFT OUTER JOIN tuser u ON (s.ByCustomer=0 AND s.UserID = u.ID)
				LEFT OUTER JOIN tcustomers cu ON (s.ByCustomer=1 AND s.UserID=cu.ID)
				LEFT OUTER JOIN tuser r ON (s.ResellerID = r.ID)
				LEFT OUTER JOIN torders o ON (s.OrderID = o.ID)
				LEFT OUTER JOIN tcustomers c ON (o.UID = c.ID)
				LEFT OUTER JOIN torder_items oi ON (s.OrderItemID = oi.ID)
				WHERE s.State IN (0,1) #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (u.FName LIKE #key# OR u.LName LIKE #key# OR p.Name LIKE #key# OR p.Code LIKE #key#)',
			'Sort'		=> 's.ID DESC',
			'Columns'	=> array(
				'CodeProduct'=> array(
					'Title'	=> 'کد کالا',
				),
				'Product'=> array(
					'Title'	=> 'نام کالا',
					'Formatter' => array('Type' => 'tooltip')
				),
				'User'=> array(
					'Title'	=> 'اپراتور',
				),
				'Store'=> array(
					'Title'	=> 'انبار',
				),
				'Tarikh' => array(
					'Title'	=> 'تاریخ',
				),
				'Count'=> array(
					'Title'	=> 'تعداد'
				),
				'OID' => array(
					'Title'	=> 'سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.OrderID == 0)
								res = '-';
							else
								res = '<a href=\"javascript:\" onclick=\"ViewOrder(' + row.OrderID + ');\">' + row.OrderID + '</a>';

							return res;
						"
					)
				),
				'ShHesab' => array(
					'Title'	=> 'کد مشتری',
				),
				'CustomerID' => array(
					'Title'	=> 'مشتری',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.CustomerID == 0)
								res = '-';
							else
								res = '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
				'Price'	=> array(
					'Title'	=> 'قیمت',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.CustomerID == 0)
								res = '-';
							else {
								if (row.TakhfifType != 0) {
									res += '<div class=\"old-price\">' + numeral(row.OldPrice).format('0,0') + '</div>';
									res += row.TakhfifPercent + ' %<br>';
									res += '<div class=\"price\">' + numeral(row.Price).format('0,0') + '</div>';
								}
								else {
									res = numeral(row.Price).format('0,0');
								}
							}

							return res;
						"
					)
				)
			)
		),
		'all_orders'		=> array(
			'Title'		=> 'سفارشات ',
			'Access'	=> true,
			'Controller'=> 'orders',
			'Action'	=> 'index',
			'ExtraView' => 'orders/all_orders',			
			'Filters'	=> array(
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, CONCAT(FName, \' \', LName) AS Name FROM tuser WHERE RoleID IN (3, 4)',
					'Where'		=> "t.UserID IN (#value#) AND t.ByApp=0",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتورها',
					'EmptyError'=> 'ویزیتوری پیدا نشد',
					'Enabled'	=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'group' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tgroups t INNER JOIN tuser_access_groups g ON (t.ID = g.GroupID) WHERE g.UID=' . $CI->User['ID'] ,
					'Where'		=> "cu.GroupID IN (#value#)",
					'Title'		=> 'گروه مشتری',
					'ListTitle' => 'گروه ها',
					'EmptyError'=> 'گروهی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'فروش',
						'1'	=> 'خرید',
						'2' => 'برگشت فروش',
						'3' => 'برگشت خرید'
					),
					'Where'	=> array(
						'0'	=> 't.IsBuy=0 AND t.IsReturn=0',
						'1'	=> 't.IsBuy == 1 AND t.IsReturn=0',
						'2' => 't.IsBuy=0 AND t.IsReturn=1',
						'3' => 't.IsBuy=1 AND t.IsReturn=1'
					),
					'Title'	=> 'نوع سفارش',
					'ListTitle' => 'انواع سفارش',
					'EmptyError' => 'نوع سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'status' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID,  Name FROM torder_status WHERE ID<>10',
					'Where'		=> "t.Status IN (#value#)",
					'Title'		=> 'مرحله کاری',
					'ListTitle' => 'مرحله کاری',
					'EmptyError'=> 'مرحله کاری پیدا نشد',
				),
				'state' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID,  Name FROM torder_states',
					'Where'		=> "t.OrderState IN (#value#)",
					'Title'		=> 'وضعیت',
					'ListTitle' => 'وضعیت',
					'EmptyError'=> ' وضعیت پیدا نشد',
				),
				'dargah' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'حضوری',
						'1'	=> 'تلفنی',
						'2' => 'مستقیم',
						'3' => 'اپلیکیشن'
					),
					'Where'	=> array(
						'0'	=> 't.CallID<>0',
						'1'	=> 't.VisitID<>0',
						'2' => 't.CallID=0 AND t.VisitID=0',
						'3' => 't.ByApp=1'
					),
					'Title'	=> 'درگاه سفارش',
					'ListTitle' => 'انواع درگاه سفارش',
					'EmptyError' => 'نوع درگاه سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'by_app' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'0'	=> 'توسط مشتری',
						'1'	=> 'از طریق crm',
					),
					'Where'	=> array(
						'0'	=> 't.ByApp=1',
						'1'	=> 't.ByApp=0',
					),
					'Title'	=> 'نحوه سفارش',
					'ListTitle' => 'انواع نحوه سفارش',
					'EmptyError' => 'نوع نحوه سفارش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "t.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "t.ShamsiDate<='#value#'"
				),
			),
			'Query'		=> "SELECT t.ID AS `ID`, t.UID AS `UID`, IFNULL(tour.Name, '') AS Tour, CONCAT(t.FName, ' ', t.LName) AS `Customer`, t.`ShamsiDate`, t.`CreateDate`, `AllPrice`, s.Name AS `State`, t.Status, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS Visitor, t.Naghdi, t.Etebari, cu.MandeHesab, IFNULL(g.Name, '-') AS `GroupName`, t.IsBuy, t.IsReturn, os.Name AS StatusName, t.ShSanad, cu.ShHesab AS Code, t.SepidarOrderID, t.ByApp
			FROM torders t INNER JOIN torder_states s ON (t.OrderState = s.ID) 
			INNER JOIN torder_status os ON (t.Status = os.ID)
			INNER JOIN tcustomers cu ON (t.UID = cu.ID)
			LEFT OUTER JOIN tuser u ON (t.UserID = u.ID)
			LEFT OUTER JOIN ttour_list_to_call ca ON (t.CallID = ca.ID)
			LEFT OUTER JOIN ttour_visitor_customers c ON (t.VisitID = c.ID)
			LEFT OUTER JOIN ttours tour ON (tour.ID = IFNULL(ca.TourID, IFNULL(c.TourID, 0)))
			LEFT OUTER JOIN tgroups g ON (cu.GroupID = g.ID)
			WHERE t.FromCrm=1 AND t.Status <> 10 AND " . (
				(hasView('store') || hasView('mali') || hasView('tel_tour_admin') || hasView('visit_tour_admin')) ? '1' : ' t.UserID=#uid# '				
			) . ' #where#'
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT t.ID AS 'شماره سفارش', t.ShSanad AS 'شماره سندمالی', CONCAT(t.FName, ' ', t.LName) AS `مشتری`, cu.ShHesab AS `کد مشتری`, IFNULL(tour.Name, '') AS 'تور', `ShamsiDate` as 'تاریخ سفارش', t.`CreateDate` as 'زمان سفارش', `AllPrice` as 'قیمت نهایی', s.Name AS `وضعیت`, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS 'ویزیتور' ",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (t.FName LIKE #key# OR t.LName LIKE #key# OR t.ShamsiDate LIKE #key# OR t.ID LIKE #key# OR t.ShSanad LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Check'		=> array(
					'Title'	=> '',
					'Width'	=> 30,
					'Enabled'	=> $CI->Domain['Sepidar'] == true,
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> '
							let res = "";

							if (row.Status == 3 || row.Status == 8 || row.Status == 2 || row.Status == 5) {
								res += "<input type=\'checkbox\' class=\'uk-checkbox order-select\' data-id=\'" + row.ID + "\' oninput=\'OrderSelectChange(this);\'";

								if (selects.get(row.ID) !== undefined)
									res += " checked ";

								res += " />";
							}

							return res;
						',
					)
				),
				'ByApp' 	=> array(
					'Title' => 'توسط مشتری',
					'Widh'  => 30,
					'Formatter' => array(
						'Type' => 'custom',
						'Code'  => "
							let res = '';

							if (row.ByApp == 1)
								res += '<span style=\'color: maroon; font-size: large;\'>*</span>';

							return res;
						"
					)
				),
				'ID'		=> array(
					'Title'	=> 'شماره سفارش',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'ShSanad'	=> array(
					'Title'	=> 'شماره سند مالی',
					'Formatter'	=> array(
						'Type'	=> 'tooltip'
					)
				),
				'Type'		=> array(
					'Title'	=> 'نوع سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.IsBuy == 0) {
								if (row.IsReturn == 0)
									res += 'فروش';
								else
									res += 'برگشت فروش';
							} else {
								if (row.IsReturn == 0)
									res += 'خرید';
								else
									res += 'برگشت خرید';
							}

							return res;
						"
					),
					//'Enabled' => $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'Visitor'	=> array(
					'Title'	=> 'ویزیتور',
					'Enabled'=> !($CI->User['RoleID'] == 3 || $CI->User['RoleID'] == 4) && !($_GET['type'] == 'tel_tour' || $_GET['type'] == 'visit_tour')
				),
				'Tarikh'=> array (
					'Title'	=> 'تاریخ سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = row.ShamsiDate + ' ' + row.CreateDate.substring(10);

							return res;
						"
					)
				),
				'ShopName'=> array(
					'Title'	=> 'مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';

							if (row.UID != '0')
								res += '<a title=\"' + row.Customer + '\" data-toggle=\"tooltip\" href=\"javascript:\" onclick=\"ViewCustomerById(' + row.UID + ');\">' + row.Customer + '</a>';

							return res;
						"
					)
				),
				'Code'	=> array(
					'Title'	=> 'کد مالی'
				),
				'GroupName'	=> array(
					'Title'	=> 'گروه مشتری'
				),
				'AllPrice'  => array(
					'Title' => 'مبلغ سفارش',
					'Formatter' => array(
						'Type'	=> 'price',
					),
					'Footer' => true
				),
				'Credit'	=> array(
					'Title'	=> 'نوع تسویه',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '';
							if (row.Naghdi > 0) {
								res += 'تسویه نقدی: <br><div class=\'form-info\'>' + numeral(row.Naghdi).format('0,0') + '</div>';
							}

							if (row.Etebari > 0) {
								res += 'تسویه اعتباری: <br><div class=\'form-info\'>' + numeral(row.Etebari).format('0,0') + '</div>';
							}

							if (res == '') 
								res = '-';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'MandeHesab' => array(
					'Title' 	=> 'مانده حساب مشتری',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							var res = '<div data-toggle=\'tooltip\' title=\'' + numeral(row.MandeHesab).format('0,0') + '\' class=\"';

							if (row.MandeHesab >= 0)
								res += 'green';
							else
								res += 'red';

							res += '\">' + numeral(row.MandeHesab).format('0,0') + '</div>';

							return res;
							
						"
					),
					'Enabled'	=> $_GET['controller'] == 'mali'
				),
				'State'		=> array(
					'Title'	=> 'وضعیت'
				),
				'StatusName' => array(
					'Title'	=> 'مرحله کاری',
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 240,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewOrder(' + row.ID + ')"
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Action'=> "EditOrder(' + row.ID + ')",
								'If' => $CI->User['EditAllOrders'] == 1 ? true : "row.Status == 0 || row.Status == 2 || row.Status == 4 || row.Status == 5"
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title'	=> 'حذف',
								'Action'=> "DeleteOrder(#index#)",
								'If' => "row.Status != 9"
							),
							array(
								'Icon'	=> 'fas fa-user-edit',
								'Title'	=> 'تغییر مشتری',
								'Action'=> "EditUser(#index#)",
								'If' => $CI->User['EditOrderUser'] == 1
							),
							array(
								'Icon'	=> 'fas fa-exchange-alt',
								'Title' => ' درخواست مرجوعی',
								'Action'=> "ReturnOrderById(' + row.ID + ')",
								'If' => hasView('tel_tour_admin') || hasView('visit_tour_admin') || hasView('store') || hasView('mali') || hasView('distribute')
							),
							array(
								'Icon'	=> 'fas fa-file',
								'Title' => 'تهیه فایل سپیدار',
								'Action'=> "GetSepidarOrder(#index#)",
								'If' => hasView('mali')
							)

						)
					)
				)
			)
		),
		'team_analyze' => array(
			'Title'		=> 'گزارش عملکرد تیم',
			'Access'	=> 'tel_tour_admin|visit_tour_admin',
			'Controller'=> 'sales_manager',
			'Action'	=> 'team_analyze',
			'ExtraView' => '',			
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "o.ShamsiDate>='#value#'",
					'Value' => ''
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "o.ShamsiDate<='#value#'",
					'Value' => ''
				),
				'az_mali'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ مالی:',
					'Where' => "o.TarikhSanad>='#value#'",
					'Value' => ''
				),
				'ta_mali'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ مالی:',
					'Where' => "o.TarikhSanad<='#value#'",
					'Value' => ''
				),
				'team' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tteams',
					'Where'		=> "t.ID IN (#value#)",
					'Title'		=> 'تیم',
					'ListTitle' => 'تیم ها',
					'EmptyError'=> 'تیمی پیدا نشد',
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'تیم تلفنی',
						'2' => 'تیم حضوری'
					),
					'Where'	=> array(
						'1'	=> 't.Type=1',
						'2'	=> 't.Type=2'
					),
					'Title'	=> 'نوع تیم',
					'ListTitle' => 'انواع تیم',
					'EmptyError' => 'نوع تیم پیدا نشد',
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE ID IN (SELECT UserID FROM tteam_users)",
					'Where'		=> "tu.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتور',
					'EmptyError'=> 'ویزیتوری پیدا نشد.',
				),

			),
			'Query'		=> "SELECT IFNULL(COUNT(DISTINCT t.ID), 0) AS Teams, IFNULL(COUNT(DISTINCT tu.UserID), 0) AS Visitors, IFNULL(COUNT(DISTINCT o.UID), 0) AS Customers, IFNULL(COUNT(DISTINCT o.ID), 0) AS Orders, IFNULL(SUM(o.AllPrice), 0) AS AllPrice 
			FROM  tteam_users tu INNER JOIN tteams t ON (t.ID = tu.TeamID)
			LEFT OUTER JOIN torders o ON (o.UserID = tu.UserID AND o.IsReturn=0 AND o.IsBuy=0 AND o.FromCrm=1 AND o.OrderState=3) 
			WHERE 1=1 #where# ",
			'FileQuery'=> "",
			'FileSelect'=> "",
			'FileName'	=> 'team_analyze', 
			'KeyFilter' => 'AND (t.Name LIKE #key#)',
			'Sort'		=> '',
			'Columns'	=> array(
				'Teams'		=> array(
					'Footer' => true,
					'Title'	=> 'تعداد تیم ها',
				),
				'Visitors'	=> array(
					'Footer' => true,
					'Title'	=> 'تعداد ویزیتورها',
				),
				'Customers'	=> array(
					'Footer' => true,
					'Title'	=> 'تعداد مشتریان'
				),
				'Orders'	=> array(
					'Footer' => true,
					'Title'	=> 'تعداد سفارشات'
				),
				'AllPrice'		=> array(
					'Title'		=> 'مجموع فروش',
					'Footer' => true,
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
				'Options'	=> array(
					'Title'	=> '',
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'Chart'	=> $team_chart
						)
					)
				)
			)

		),
		'lost_customers'		=> array(
			'Title'		=> 'مشتریان از دست رفته',
			'Access'	=> 'sales_manager',
			'Controller'=> 'sales_manager',
			'Action'	=> 'lost_customers',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "tc.CallTarikh>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "tc.CallTarikh<='#value#'",
				),
				'customer' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, ShopName AS Name FROM tcustomers WHERE Deleted=0 AND IsShop=1',
					'Where'		=> "tc.CustomerID IN (#value#)",
					'Title'		=> 'مشتری',
					'ListTitle' => 'مشتری ها',
					'EmptyError'=> 'مشتری پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'تیم تلفنی',
						'2' => 'تیم حضوری'
					),
					'Where'	=> array(
						'1'	=> 't.Type=1',
						'2'	=> 't.Type=2'
					),
					'Title'	=> 'نوع تیم',
					'ListTitle' => 'انواع تیم',
					'EmptyError' => 'نوع تیم پیدا نشد',
				),
				'user' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE Deleted=0 AND RoleID IN (3, 4)",
					'Where'		=> "tc.VisitorID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتور ها',
					'EmptyError'=> 'ویزیتوری پیدا نشد'
				),
				'no_op' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(Title, ' (', CASE WHEN Type=1 THEN 'تلفنی' ELSE 'حضوری' END ,')' ) AS Name FROM ttour_call_results WHERE Operation=1",
					'Where'		=> "tc.Result IN (#value#)",
					'Title'		=> 'علت عدم همکاری',
					'ListTitle' => 'علت عدم همکاری ها',
					'EmptyError'=> 'علتی پیدا نشد'
				),
				'peigiri' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(Title, ' (', CASE WHEN Type=1 THEN 'تلفنی' ELSE 'حضوری' END ,')' ) AS Name FROM ttour_call_results WHERE Operation=2",
					'Where'		=> "tc.Result IN (#value#)",
					'Title'		=> 'علت پیگیری ',
					'ListTitle' => 'علت پیگیری  ',
					'EmptyError'=> 'علتی پیدا نشد'
				),
			),
			'Query'		=> "SELECT IFNULL(team.Name, '-') AS `Team`, IFNULL(CONCAT(u.FName, ' ', u.LName), '') AS `User`, (CASE WHEN t.Type=1 THEN 'تلفنی' ELSE 'حضوری' END) AS `Type`, IFNULL(area.Name, '-') AS `Area`, IFNULL(g.Name, '') AS `Grade`, c.ShopName AS `ShopName`, c.Phone AS `Phone`, c.Tel AS `Tel`, (CASE WHEN r.Operation=1 THEN 'عدم همکاری' ELSE 'پیگیری' END) AS `ResultType`, (CASE WHEN r.Operation=1 THEN r.Title ELSE '-' END) AS `NoOp`, (CASE WHEN r.Operation=2 THEN r.Title ELSE '-' END) AS `Peigiri`, c.BuyAveragePerMonth AS `Average`
			FROM ttours t INNER JOIN ttour_calls tc ON (t.ID = tc.TourID)
			INNER JOIN ttour_call_results r ON (tc.Result = r.ID) 
			INNER JOIN tcustomers c ON (tc.CustomerID = c.ID AND c.Deleted=0) 
			INNER JOIN tuser u ON (tc.VisitorID = u.ID) 
			LEFT OUTER JOIN tteam_users tu ON (tc.VisitorID = tu.UserID)
			LEFT OUTER JOIN tteams team ON (tu.TeamID = team.ID)
			LEFT OUTER JOIN bareas area ON (c.Area = area.ID)
			LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
			WHERE r.Operation IN (1,2) #where#
			"
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'targets', 
			'KeyFilter' => 'AND (t.Name LIKE #key# OR t.AzTarikh LIKE #key# OR t.TaTarikh LIKE #key# OR u.FName LIKE #key# OR LName LIKE #key#)',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Team'=> array(
					'Title'	=> 'تیم',
					'Formatter' => array('Type' => 'tooltip')
				),
				'User'=> array(
					'Title'	=> 'ویزیتور',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Type' => array(
					'Title'	=> 'نوع ویزیت',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Area'=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Grade'=> array(
					'Title'	=> 'گرید',
					'Formatter' => array('Type' => 'tooltip')
				),
				'ShopName'=> array(
					'Title'	=> 'نام شرکت یا صنف',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Phone'=> array(
					'Title'	=> 'تلفن همراه',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Tel'=> array(
					'Title'	=> 'تلفن ثابت',
					'Formatter' => array('Type' => 'tooltip')
				),
				'ResultType'=> array(
					'Title'	=> 'عدم همکاری یا پیگیری',
					'Formatter' => array('Type' => 'tooltip')
				),
				'NoOp'=> array(
					'Title'	=> 'علت عدم همکاری',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Peigiri'=> array(
					'Title'	=> 'علت پیگیری',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Average'=> array(
					'Title'	=> 'میانگین خرید ماهیانه',
					'Formatter' => array(
						'Type'	=> 'price'
					)
				),
			)
		),
		'customer_buys' => array(
			'Title'		=> 'گزارش رفتار خرید مشتریان',
			'Access'	=> 'sales_manager',
			'Controller'=> 'sales_manager',
			'Action'	=> 'customer_buys',
			'ExtraView' => '',			
			'Filters'	=> array(
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'CustomerState' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcustomer_states WHERE Deleted=0',
					'Where'		=> "c.CustomerState IN (#value#)",
					'Title'		=> 'وضعیت مشتری',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد',
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "o.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['FirstDay']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "o.ShamsiDate<='#value#'",
				),

			),
			'PreQueries'	=> array(
				"DELETE FROM RepCustomerBuys WHERE UID=#uid#",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, Area, Grade, OldAverage, OldItems)
				SELECT #uid#, c.ID, c.ShopName, IFNULL(a.Name, '-') AS Area, IFNULL(g.Name, '') AS Grade, c.BuyAveragePerMonth, c.BuyAverageItemsPerMonth
				FROM tcustomers c LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
				LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
				JOIN (SELECT '#az#' AS ShamsiDate) o
				WHERE c.Deleted=0 AND c.IsShop=1 #where#",
				/*"INSERT INTO RepCustomerBuys (UID, ID, ShopName, Area, Grade, OldAverage, OldItems)
				SELECT #uid#, o.UID, c.ShopName, IFNULL(a.Name, '-') AS Area, IFNULL(g.Name, '') AS Grade, SUM(AllPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg, CEIL(SUM(Items) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END)) AS items
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
				LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)				
				WHERE o.Status=1 AND o.CreateDate<DATE_SUB(NOW(), INTERVAL 30 DAY) #where#
				GROUP BY o.UID",*/
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, Area, Grade, BuyMonth, ItemsMonth)
				SELECT #uid#, o.UID, c.ShopName, IFNULL(a.Name, '-') AS Area, IFNULL(g.Name, '') AS Grade, SUM(AllPrice), SUM(Items)
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
				LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)				
				WHERE o.Status IN (1, 9) AND FromCrm=1 /*AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY)*/ #where#
				GROUP BY o.UID",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, Area, Grade, MinSell, MinTour, MinVisitor, MinTourType)
				SELECT #uid#, o.UID, c.ShopName, IFNULL(a.Name, '-') AS Area, IFNULL(g.Name, '') AS Grade, om.AllPrice, IFNULL(t.Name, '') AS Tour, CONCAT(u.FName, ' ', u.LName) AS User, CASE WHEN  t.Type=1 THEN 'تلفنی' WHEN t.Type=2 THEN 'حضوری' ELSE '' END AS Type
				FROM torders o INNER JOIN (
				SELECT UID, MIN(AllPrice) AS AllPrice
				FROM torders 
				WHERE Status IN (1, 9) AND FromCrm=1 AND (CallID<>0 OR VisitID<>0)
				GROUP BY UID) om ON (o.UID=om.UID AND o.AllPrice = om.AllPrice)
				INNER JOIN tcustomers c ON (c.ID = o.UID)
				LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
				LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)	
				LEFT OUTER JOIN ttour_calls tc ON (tc.ID IN (CallID, VisitID))
				LEFT OUTER JOIN ttours t ON (tc.TourID = t.ID)
				LEFT OUTER JOIN tuser u ON (tc.VisitorID = u.ID)
				WHERE 1=1 #where#
				GROUP BY o.UID",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, Area, Grade, MaxSell, MaxTour, MaxVisitor, MaxTourType)
				SELECT #uid#, o.UID, c.ShopName, IFNULL(a.Name, '-') AS Area, IFNULL(g.Name, '') AS Grade, om.AllPrice, IFNULL(t.Name, '') AS Tour, CONCAT(u.FName, ' ', u.LName) AS User, CASE WHEN  t.Type=1 THEN 'تلفنی' WHEN t.Type=2 THEN 'حضوری' ELSE '' END AS Type
				FROM torders o INNER JOIN (
				SELECT UID, MAX(AllPrice) AS AllPrice
				FROM torders 
				WHERE Status IN (1, 9) AND FromCrm=1 AND (CallID<>0 OR VisitID<>0)
				GROUP BY UID) om ON (o.UID=om.UID AND o.AllPrice = om.AllPrice)
				INNER JOIN tcustomers c ON (c.ID = o.UID)
				LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
				LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)	
				LEFT OUTER JOIN ttour_calls tc ON (tc.ID IN (CallID, VisitID))
				LEFT OUTER JOIN ttours t ON (tc.TourID = t.ID)
				LEFT OUTER JOIN tuser u ON (tc.VisitorID = u.ID)
				WHERE 1=1 #where#
				GROUP BY o.UID"
			),
			'Query'		=> "Select ID AS `ID`, ShopName AS `ShopName`, Area AS `Area`, Grade AS `Grade`, SUM(OldAverage) AS `OldAverage`, SUM(BuyMonth) AS `BuyMonth`, CONCAT(CASE WHEN SUM(OldAverage) = SUM(BuyMonth) THEN '0' WHEN SUM(OldAverage) > SUM(BuyMonth) THEN ( CASE WHEN SUM(BuyMonth) = 0 THEN '-100' ELSE -1 * CAST(((SUM(BuyMonth)) * 100) / (SUM(OldAverage)) AS decimal(10, 2)) END  ) ELSE ( CASE WHEN SUM(OldAverage) = 0 THEN '100' ELSE CAST(((SUM(OldAverage)) * 100) / (SUM(BuyMonth)) AS decimal(10, 2)) END  ) END, ' %') AS `DiffAvg`, SUm(OldItems) AS OldItems, SUM(ItemsMonth) AS `ItemsMonth`, CONCAT(CASE WHEN SUM(OldItems) = SUM(ItemsMonth) THEN '0' WHEN SUM(OldItems) > SUM(ItemsMonth) THEN ( CASE WHEN SUM(ItemsMonth) = 0 THEN '-100' ELSE -1 * CAST(((SUM(ItemsMonth)) * 100) / (SUM(OldItems)) AS decimal(10, 2)) END  ) ELSE ( CASE WHEN SUM(OldItems) = 0 THEN '100' ELSE CAST(((SUM(OldItems)) * 100) / (SUM(ItemsMonth)) AS decimal(10, 2)) END  ) END, ' %') AS `DiffItems`, MAX(MinSell) AS `MinSell`, MAX(MinVisitor) AS `MinVisitor`, MAX(MinTour) AS `MinTour`, MAX(MinTourType) AS `MinTourType`, MAX(MaxSell) AS `MaxSell`, MAX(MaxVisitor) AS `MaxVisitor`, MAX(MaxTour) AS `MaxTour`, MAX(MaxTourType) AS `MaxTourType`
			from RepCustomerBuys 
			WHERE UID=#uid#
			group by ID, ShopName ,Area , Grade
			",
			'FileQuery'=> "",
			'FileSelect'=> "",
			'FileName'	=> 'tel_tours', 
			'KeyFilter' => 'AND (t.ShopName LIKE #key#)',
			'Sort'		=> '',
			'Columns'	=> array(
				'ID'		=> array(
					'Enabled'	=> false,
					'Title'		=> 'شناسه یکتا مشتری'
				),
				'ShopName' => array(
					'Title'   => 'مشتری',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Area'		=> array(
					'Title'	=> 'منطقه',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Grade'	=> array(
					'Title'	=> 'گرید',
					'Formatter' => array('Type' => 'tooltip')
				),
				'OldAverage'	=> array(
					'Title'	=> 'میانگین خرید ماه های قبل',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'میانگین فروش ماه های قبل',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'مجموع فروش ماه جاری',
								'Name'	=> 'BuyMonth'
							)
						)
					)
				),
				'BuyMonth'		=> array(
					'Title'		=> 'مجموع خرید ماه جاری',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'مجموع فروش ماه جاری',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'میانگین ماه قبل',
								'Name'	=> 'OldAverage'
							)
						)
					)
				),
				'DiffAvg'	=> array(
					'Title'		=> 'درصد اختلاف خرید ماه جاری نسبت به میانگین ماههای قبل',
					'Formatter' => array('Type' => 'tooltip'),
				),
				'OldItems'			=> array(
					'Title'		=> 'میانگین تعداد کالاهای خریداری شده ماه های قبل',
					'Formatter' => array('Type' => 'tooltip'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'میانگین تعداد کالاهای ماه های قبل',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'تعداد کالاهای ماه جاری',
								'Name'	=> 'ItemsMonth'
							)
						)
					)
				),
				'ItemsMonth'			=> array(
					'Title'		=> 'تعداد کالاهای خریداری شده ماه جاری',
					'Formatter' => array('Type' => 'tooltip'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'تعداد کالاهای خریداری شده ماه جاری',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'میانگین ماه قبل',
								'Name'	=> 'OldItems'
							)
						)
					)
				),
				'DiffItems'		=> array(
					'Title'		=> 'درصد اختلاف تعداد کالاها',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinSell'	=> array(
					'Title'		=> 'حداقل فروش ویزیتور',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Title' => 'حداقل فروش ویزیتور',
						'Name'	=> 'ShopName ',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'MinVisitor'		=> array(
					'Title'		=> 'ویزیتور حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinTour' => array(
					'Title'	=> 'تور ویزیت حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinTourType' => array(
					'Title'	=> 'نوع تور حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxSell'	=> array(
					'Title'		=> 'حداکثر فروش ویزیتور',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Title'	=> 'حداکثر فروش ویزیتور',
						'Name'	=> 'ShopName ',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'MaxVisitor'		=> array(
					'Title'		=> 'ویزیتور حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxTour' => array(
					'Title'	=> 'تور ویزیت حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxTourType' => array(
					'Title'	=> 'نوع تور حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Options'	=> array(
					'Title'	=> '',
					'Width'	=> 150,
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'chart_by_visitor'	=> $chart_by_visitor,
							'chart_by_brand'	=> $chart_by_brand

						)
					)
				)

			)

		),
		'team_sells' => array(
			'Title'		=> 'گزارش فروش مجموعه',
			'Access'	=> 'sales_manager',
			'Controller'=> 'sales_manager',
			'Action'	=> 'team_sells',
			'ExtraView' => '',			
			'Filters'	=> array(
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'CustomerState' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcustomer_states WHERE Deleted=0',
					'Where'		=> "c.CustomerState IN (#value#)",
					'Title'		=> 'وضعیت مشتری',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد',
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "o.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['FirstDay']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "o.ShamsiDate<='#value#'",
				),

			),
			'PreQueries'	=> array(
				"DELETE FROM RepCustomerBuys WHERE UID=#uid#",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName)
				SELECT #uid#, c.ID, c.Name
				FROM tteams c 
				WHERE c.Deleted=0",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, OldAverage, OldItems)
				SELECT #uid#, t.ID, t.Name, SUM(AllPrice) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END) AS Avg, CEIL(SUM(Items) / (CASE WHEN TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) = 0 THEN 1 ELSE TIMESTAMPDIFF(MONTH, MIN(o.CreateDate), DATE_SUB(NOW(), INTERVAL 30 DAY)) END)) AS items
				FROM torders o INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
				INNER JOIN tteams t ON (tu.TeamID = t.ID)
				INNER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND o.ShamsiDate>='{$this->mViewData['AverageStart']}' AND o.ShamsiDate<'{$this->mViewData['AverageEnd']}' #where#
				GROUP BY t.ID, t.Name",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, BuyMonth, ItemsMonth)
				SELECT #uid#, t.ID, t.Name, SUM(AllPrice), SUM(Items)
				FROM torders o INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
				INNER JOIN tteams t ON (tu.TeamID = t.ID)
				INNER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 /*AND o.CreateDate>=DATE_SUB(NOW(), INTERVAL 30 DAY)*/ #where#
				GROUP BY t.ID, t.Name",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName, MinSell, MinTour, MinVisitor, MinTourType)
				SELECT #uid#, team.ID, team.Name, om.AllPrice, IFNULL(t.Name, '') AS Tour, CONCAT(u.FName, ' ', u.LName) AS User, CASE WHEN  t.Type=1 THEN 'تلفنی' WHEN t.Type=2 THEN 'حضوری' ELSE '' END AS Type
				FROM torders o INNER JOIN (
				SELECT UserID, MIN(AllPrice) AS AllPrice
				FROM torders 
				WHERE Status IN (1, 9) AND FromCrm=1 AND (CallID<>0 OR VisitID<>0)
				GROUP BY UserID) om ON (o.UserID=om.UserID AND o.AllPrice = om.AllPrice)
				INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
				INNER JOIN tteams team ON (tu.TeamID = team.ID)
				INNER JOIN tcustomers c ON (o.UID = c.ID)
				LEFT OUTER JOIN ttour_calls tc ON (tc.ID IN (CallID, VisitID))
				LEFT OUTER JOIN ttours t ON (tc.TourID = t.ID)
				LEFT OUTER JOIN tuser u ON (tc.VisitorID = u.ID)
				WHERE 1=1 #where#
				GROUP BY team.ID, team.Name",
				"INSERT INTO RepCustomerBuys (UID, ID, ShopName,MaxSell, MaxTour, MaxVisitor, MaxTourType)
				SELECT #uid#, team.ID, team.Name, om.AllPrice, IFNULL(t.Name, '') AS Tour, CONCAT(u.FName, ' ', u.LName) AS User, CASE WHEN  t.Type=1 THEN 'تلفنی' WHEN t.Type=2 THEN 'حضوری' ELSE '' END AS Type
				FROM torders o INNER JOIN (
				SELECT UserID, MAX(AllPrice) AS AllPrice
				FROM torders 
				WHERE Status IN (1, 9) AND FromCrm=1 AND (CallID<>0 OR VisitID<>0)
				GROUP BY UserID) om ON (o.UserID=om.UserID AND o.AllPrice = om.AllPrice)
				INNER JOIN tteam_users tu ON (o.UserID = tu.UserID)
				INNER JOIN tteams team ON (tu.TeamID = team.ID)
				INNER JOIN tcustomers c ON (o.UID = c.ID)
				LEFT OUTER JOIN ttour_calls tc ON (tc.ID IN (CallID, VisitID))
				LEFT OUTER JOIN ttours t ON (tc.TourID = t.ID)
				LEFT OUTER JOIN tuser u ON (tc.VisitorID = u.ID)
				WHERE 1=1 #where#
				GROUP BY team.ID, team.Name"
			),
			'Query'		=> "Select ID AS `ID`, ShopName AS `ShopName`, SUM(OldAverage) AS `OldAverage`, SUM(BuyMonth) AS `BuyMonth`, CONCAT(CASE WHEN SUM(OldAverage) = SUM(BuyMonth) THEN '0' WHEN SUM(OldAverage) > SUM(BuyMonth) THEN ( CASE WHEN SUM(BuyMonth) = 0 THEN '-100' ELSE -1 * CAST(((SUM(BuyMonth)) * 100) / (SUM(OldAverage)) AS decimal(10, 2)) END  ) ELSE ( CASE WHEN SUM(OldAverage) = 0 THEN '100' ELSE CAST(((SUM(OldAverage)) * 100) / (SUM(BuyMonth)) AS decimal(10, 2)) END  ) END, ' %') AS `DiffAvg`, SUm(OldItems) AS OldItems, SUM(ItemsMonth) AS `ItemsMonth`, CONCAT(CASE WHEN SUM(OldItems) = SUM(ItemsMonth) THEN '0' WHEN SUM(OldItems) > SUM(ItemsMonth) THEN ( CASE WHEN SUM(ItemsMonth) = 0 THEN '-100' ELSE -1 * CAST(((SUM(ItemsMonth)) * 100) / (SUM(OldItems)) AS decimal(10, 2)) END  ) ELSE ( CASE WHEN SUM(OldItems) = 0 THEN '100' ELSE CAST(((SUM(OldItems)) * 100) / (SUM(ItemsMonth)) AS decimal(10, 2)) END  ) END, ' %') AS `DiffItems`, MAX(MinSell) AS `MinSell`, MAX(MinVisitor) AS `MinVisitor`, MAX(MinTour) AS `MinTour`, MAX(MinTourType) AS `MinTourType`, MAX(MaxSell) AS `MaxSell`, MAX(MaxVisitor) AS `MaxVisitor`, MAX(MaxTour) AS `MaxTour`, MAX(MaxTourType) AS `MaxTourType`
			from RepCustomerBuys
			WHERE UID=#uid# 
			group by ID, ShopName
			",
			'FileQuery'=> "",
			'FileSelect'=> "",
			'FileName'	=> 'tel_tours', 
			'KeyFilter' => 'AND (t.ShopName LIKE #key#)',
			'Sort'		=> '',
			'Columns'	=> array(
				'ID'		=> array(
					'Enabled'	=> false,
					'Title'		=> 'نام تیم'
				),
				'ShopName' => array(
					'Title'   => 'مشتری',
					'Formatter' => array('Type' => 'tooltip')
				),
				'OldAverage'	=> array(
					'Title'	=> 'میانگین خرید ماه های قبل',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'میانگین فروش ماه های قبل',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'مجموع فروش ماه جاری',
								'Name'	=> 'BuyMonth'
							)
						)
					)
				),
				'BuyMonth'		=> array(
					'Title'		=> 'مجموع خرید ماه جاری',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'مجموع فروش ماه جاری',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'میانگین ماه قبل',
								'Name'	=> 'OldAverage'
							)
						)
					)
				),
				'DiffAvg'	=> array(
					'Title'		=> 'درصد اختلاف خرید ماه جاری نسبت به میانگین ماههای قبل',
					'Formatter' => array('Type' => 'tooltip'),
				),
				'OldItems'			=> array(
					'Title'		=> 'میانگین تعداد کالاهای خریداری شده ماه های قبل',
					'Formatter' => array('Type' => 'tooltip'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'میانگین تعداد کالاهای ماه های قبل',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'تعداد کالاهای ماه جاری',
								'Name'	=> 'ItemsMonth'
							)
						)
					)
				),
				'ItemsMonth'			=> array(
					'Title'		=> 'تعداد کالاهای خریداری شده ماه جاری',
					'Formatter' => array('Type' => 'tooltip'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'ShopName ',
						'Title'	=> 'تعداد کالاهای خریداری شده ماه جاری',
						'Colors' => "'#03A9F4', '#4CAF50'",
						'Others' => array(
							array(
								'Title'	=> 'میانگین ماه قبل',
								'Name'	=> 'OldItems'
							)
						)
					)
				),
				'DiffItems'		=> array(
					'Title'		=> 'درصد اختلاف تعداد کالاها',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinSell'	=> array(
					'Title'		=> 'حداقل فروش ویزیتور',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Title' => 'حداقل فروش ویزیتور',
						'Name'	=> 'ShopName ',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'MinVisitor'		=> array(
					'Title'		=> 'ویزیتور حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinTour' => array(
					'Title'	=> 'تور ویزیت حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MinTourType' => array(
					'Title'	=> 'نوع تور حداقل',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxSell'	=> array(
					'Title'		=> 'حداکثر فروش ویزیتور',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Title'	=> 'حداکثر فروش ویزیتور',
						'Name'	=> 'ShopName ',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'MaxVisitor'		=> array(
					'Title'		=> 'ویزیتور حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxTour' => array(
					'Title'	=> 'تور ویزیت حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'MaxTourType' => array(
					'Title'	=> 'نوع تور حداکثر',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Options'	=> array(
					'Title'	=> '',
					'Width'	=> 150,
					'Formatter'	=> array(
						'Type'	=> 'action',
						'Actions'	=> array(
							'chart_by_visitor'	=> $chart_team_by_visitor,
							'chart_by_brand'	=> $chart_team_by_brand

						)
					)
				)

			)

		),
		'waiting_orders'		=> array(
			'Title'		=> 'وضعیت سفارشات معلق',
			'Access'	=> 'sales_manager',
			'Controller'=> 'sales_manager',
			'Action'	=> 'waiting_orders',
			'Filters'	=> array(
				'Country'	=> $config_country,
				'State'	=> $config_state,
				'City'	=> $config_city,
				'Area'	=> $config_area,
				'Block'	=> $config_block,
				'CustomerState' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcustomer_states WHERE Deleted=0',
					'Where'		=> "c.CustomerState IN (#value#)",
					'Title'		=> 'وضعیت مشتری',
					'ListTitle' => 'وضعیت ها',
					'EmptyError'=> 'وضعیتی پیدا نشد',
				),
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ :',
					'Where' => "o.ShamsiDate>='#value#'",
					'Value'	=> ''
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ :',
					'Where' => "o.ShamsiDate<='#value#'",
					'Value'	=> ''
				),
				'visitor' => array(
					'Type'	=> 'select',
					'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE ID IN (SELECT UserID FROM tteam_users)",
					'Where'		=> "o.UserID IN (#value#)",
					'Title'		=> 'ویزیتور',
					'ListTitle' => 'ویزیتور',
					'EmptyError'=> 'ویزیتوری پیدا نشد.',
				),
			),
			'Query'		=> "
				SELECT (CASE WHEN s.ID IN (2,5) THEN '2,5' WHEN s.ID IN (3,8) THEN '3,8' WHEN s.ID IN (7) THEN '7' WHEN s.ID=1 THEN '1' ELSE '' END) AS `IDS`, (CASE WHEN s.ID IN (2,5) THEN 'سرپرست فروش' WHEN s.ID IN (3,8) THEN 'بخش مالی' WHEN s.ID IN (7) THEN 'بخش انبار' WHEN s.ID=1 THEN 'بخش توزیع' ELSE '' END) AS `Name`, COUNT(DISTINCT o.ID) AS `Cnt`, SUM(o.AllPrice) AS `Price`
				FROM torder_status s LEFT OUTER JOIN torders o ON (s.ID = o.Status)
				INNER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE s.ID IN (1, 2, 3, 5, 8, 7) AND o.FromCrm=1 #where#
				GROUP BY (CASE WHEN s.ID IN (2,5) THEN 'سرپرست فروش' WHEN s.ID IN (3,8) THEN 'بخش مالی' WHEN s.ID IN (7) THEN 'بخش انبار' WHEN s.ID=1 THEN 'بخش توزیع' ELSE '' END)
			"
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'orders', 
			'KeyFilter' => 'AND (s.Name LIKE #key#)',
			'Sort'		=> 's.ID DESC',
			'Columns'	=> array(
				'IDS'	=> array(
					'Enabled' => false,
				),
				'Name'=> array(
					'Title'	=> 'نام',
				),
				'Cnt'=> array(
					'Title'	=> 'تعداد سفارشات',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'Name ',
						'Title'	=> 'تعداد سفارشات',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'Price'=> array(
					'Title'	=> 'مجموع مبلغ سفارشات',
					'Formatter' => array('Type' => 'price'),
					'Footer'	=> true,
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'Name',
						'Title'	=> 'مجموع مبلغ سفارشات',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 70,
					'Formatter' => array(
						'Type'	=> 'action',
						'Width' => 50,
						'Actions' => array(
							'details' => array(
								'Icon'	=> 'fas fa-list',
								'Title'	=> 'ریز سفارشات',
								'Action'=> array(
									'Type'	=> 'details',
									'Query' => 
										"SELECT o.ID AS 'شناسه سفارش', c.ShopName AS 'مشتری', c.Tel AS 'تلفن ثابت', c.Phone as 'تلفن همراه', s.Name AS 'مرحله سفارش', os.Name AS 'وضعیت سفارش', o.ShamsiDate AS 'تاریخ ثبت', o.AllPrice AS 'مبلغ سفارش', CONCAT(u.FName, ' ', u.LName) AS 'ثبت کننده'
										FROM torders o INNER JOIN torder_states os ON (o.OrderState = os.ID)
										INNER JOIN torder_status s ON (o.Status = s.ID)
										INNER JOIN tcustomers c ON (o.UID = c.ID)
										LEFT OUTER JOIN tuser u ON (o.UserID = u.ID)
										WHERE o.FromCrm=1 AND o.Status IN (#ids#) "
									,
									'Inputs' => array( 'ids' => 'IDS' ) 
								)
							),
						)
					)
				)

			)
		),
		'customer_pays'		=> array(
			'Title'		=> 'آخرین وضعیت مطالبات مشتریان',
			'Access'	=> 'sales_manager',
			'Controller'=> 'sales_manager',
			'Action'	=> 'customer_pays',
			'PreQueries'=> array(
				"DELETE FROM rep_customer_pays WHERE UID=#uid#",
				"INSERT INTO rep_customer_pays (UID, ID, Name, Value)
				SELECT #uid#, 1, 'مجموع فروش سفارشات قطعی', SUM(o.AllPrice)
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 #where#",
				"INSERT INTO rep_customer_pays (UID, ID, Name, Value)
				SELECT #uid#, 2, 'نقدی', SUM(p.Credit)
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				INNER JOIN tpays p ON (o.ID = p.OrderID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND p.Type<>3 #where#",
				"INSERT INTO rep_customer_pays (UID, ID, Name, Value)
				SELECT #uid#, 3, 'چک دریافت شده', SUM(p.Credit)
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				INNER JOIN tpays p ON (o.ID = p.OrderID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND p.Type=3 #where#",
				"INSERT INTO rep_customer_pays (UID, ID, Name, Value)
				SELECT #uid#, 4, 'مطالبات معوق تعیین تکلیف نشده', SUM(p.Credit)
				FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
				INNER JOIN tpays p ON (o.ID = p.OrderID)
				WHERE o.Status IN (1, 9) AND o.FromCrm=1 AND p.Type=3 AND p.CheqState<>2 #where#"
			),
			'Filters'	=> array(
			),
			'Query'		=> "SELECT ID AS `ID`, Name AS `Name`, Value AS `Value`
			FROM rep_customer_pays
			WHERE UID=#uid#"
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'customer_pays', 
			'KeyFilter' => 'AND ()',
			'Sort'		=> 'ID',
			'Columns'	=> array(
				'Name'=> array(
					'Title'	=> 'عنوان',
					'Formatter' => array(
						'Type'	=> 'tooltip',
					)
				),
				'Value'=> array (
					'Title'	=> 'مجموع مبلغ',
					'Formatter' => array('Type' => 'price'),
					'Chart'		=> array(
						'Type'	=> 'line',
						'Name'	=> 'Name',
						'Title'	=> 'مجموع مبلغ',
						'Colors' => "'#03A9F4', '#4CAF50'",
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 70,
					'Formatter' => array(
						'Type'	=> 'action',
						'Width' => 50,
						'Actions' => array(
							'details' => array(
								'Icon'	=> 'fas fa-list',
								'Title'	=> 'ریز سفارشات',
								'Action'=> array(
									'Type'	=> 'details',
									'Query' => array(
										'1'		=> "SELECT c.ShopName AS `مشتری`, IFNULL(senf.Name, '-') AS `صنف`, IFNULL(a.Name, '-') AS `منطقه`, IFNULL(g.Name, '-') AS `گرید`, o.ID AS `شماره فاکتور`, MAX(o.AllPrice) AS `مبلغ فاکتور`, SUM(CASE WHEN p.Type<>3 THEN p.Credit ELSE 0 END) AS `نقدی`, SUM(CASE WHEN p.Type=3 THEN p.Credit ELSE 0 END) AS `اعتباری`
										FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
										LEFT OUTER JOIN tpays p ON (o.ID = p.OrderID)
										LEFT OUTER JOIN tsenfs senf ON (c.SenfID = senf.ID)
										LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
										LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
										WHERE o.Status IN (1,9) AND o.FromCrm=1 #where# 
										GROUP BY c.ShopName, senf.Name, a.Name, g.Name, o.ID",
										'2'		=> "SELECT c.ShopName AS `مشتری`, IFNULL(senf.Name, '-') AS `صنف`, IFNULL(a.Name, '-') AS `منطقه`, IFNULL(g.Name, '-') AS `گرید`, o.ID AS `شماره فاکتور`, MAX(o.AllPrice) AS `مبلغ فاکتور`, SUM(CASE WHEN p.Type<>3 THEN p.Credit ELSE 0 END) AS `نقدی`, SUM(CASE WHEN p.Type=3 THEN p.Credit ELSE 0 END) AS `اعتباری`
										FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
										LEFT OUTER JOIN tpays p ON (o.ID = p.OrderID)
										LEFT OUTER JOIN tsenfs senf ON (c.SenfID = senf.ID)
										LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
										LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
										WHERE o.Status IN (1,9) AND o.FromCrm=1 AND EXISTS(SELECT ID FROM tpays pay WHERE pay.OrderID = o.ID AND pay.Type<>3) #where# 
										GROUP BY c.ShopName, senf.Name, a.Name, g.Name, o.ID",
										'3'		=> "SELECT c.ShopName AS `مشتری`, IFNULL(senf.Name, '-') AS `صنف`, IFNULL(a.Name, '-') AS `منطقه`, IFNULL(g.Name, '-') AS `گرید`, o.ID AS `شماره فاکتور`, MAX(o.AllPrice) AS `مبلغ فاکتور`, SUM(CASE WHEN p.Type<>3 THEN p.Credit ELSE 0 END) AS `نقدی`, SUM(CASE WHEN p.Type=3 THEN p.Credit ELSE 0 END) AS `اعتباری`
										FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
										LEFT OUTER JOIN tpays p ON (o.ID = p.OrderID)
										LEFT OUTER JOIN tsenfs senf ON (c.SenfID = senf.ID)
										LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
										LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
										WHERE o.Status IN (1,9) AND o.FromCrm=1 AND EXISTS(SELECT ID FROM tpays pay WHERE pay.OrderID = o.ID AND pay.Type=3) #where# 
										GROUP BY c.ShopName, senf.Name, a.Name, g.Name, o.ID",
										'4'		=> "SELECT c.ShopName AS `مشتری`, IFNULL(senf.Name, '-') AS `صنف`, IFNULL(a.Name, '-') AS `منطقه`, IFNULL(g.Name, '-') AS `گرید`, o.ID AS `شماره فاکتور`, MAX(o.AllPrice) AS `مبلغ فاکتور`, SUM(CASE WHEN p.Type<>3 THEN p.Credit ELSE 0 END) AS `نقدی`, SUM(CASE WHEN p.Type=3 THEN p.Credit ELSE 0 END) AS `اعتباری`
										FROM torders o INNER JOIN tcustomers c ON (o.UID = c.ID)
										LEFT OUTER JOIN tpays p ON (o.ID = p.OrderID)
										LEFT OUTER JOIN tsenfs senf ON (c.SenfID = senf.ID)
										LEFT OUTER JOIN bareas a ON (c.Area = a.ID)
										LEFT OUTER JOIN tgrades g ON (c.Grade = g.ID)
										WHERE o.Status IN (1,9) AND o.FromCrm=1 AND EXISTS(SELECT ID FROM tpays pay WHERE pay.OrderID = o.ID AND pay.Type=3 AND pay.CheqState<>2) #where# 
										GROUP BY c.ShopName, senf.Name, a.Name, g.Name, o.ID"

									),
									'Inputs' => array( 'id' => 'ID' ) 
								)
							),
						)
					)
				)
			)
		),
		'all_products'		=> array(
			'Title'		=> 'محصولات ',
			'Access'	=> 'products',
			'Controller'=> 'products',
			'Action'	=> 'all_products',
			'ExtraView' => 'products/list_products',		
			'Actions'	=> array(
				array(
					'Title'	=> 'محصول جدید',
					'Class'	=> 'btn btn-success',
					'Action'=> 'NewProduct()'
				)
			),	
			'Filters'	=> array(
				'cat' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, Name FROM tcats WHERE ID IN (SELECT DISTINCT CID FROM tproducts) AND Deleted=0',
					'Where'		=> "p.CID IN (#value#)",
					'Title'		=> 'دسته',
					'ListTitle' => 'دسته ها',
					'EmptyError'=> 'دسته ای پیدا نشد',
				),
				'brand' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT ID, NameFa AS Name FROM tbrands WHERE ID IN (SELECT DISTINCT BID FROM tproducts) ' ,
					'Where'		=> "p.BID IN (#value#)",
					'Title'		=> 'برند',
					'ListTitle' => 'برند ها',
					'EmptyError'=> 'برندی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'فعال',
						'2' => 'غیر فعال',
					),
					'Where'	=> array(
						'1'	=> 'p.Active=1',
						'2' => 'p.Active=0',
					),
					'Title'	=> 'وضعیت',
					'ListTitle' => 'انواع وضعیت',
					'EmptyError' => 'نوع وضعیت پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'price' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'دارای موجودی',
						'2' => 'بدون موجودی',
					),
					'Where'	=> array(
						'1'	=> 'p.Mojodi>0',
						'2' => 'p.Mojodi=0',
					),
					'Title'	=> 'وضعیت موجودی' ,
					'ListTitle' => 'انواع وضعیت موجودی',
					'EmptyError' => 'نوع وضعیت پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'variety' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'دارای تنوع',
						'2' => 'تنوع محصول',
					),
					'Where'	=> array(
						'1'	=> 'p1.cnt>0',
						'2' => 'p.Parent<>0',
					),
					'Title'	=> 'وضعیت تنوع' ,
					'ListTitle' => 'انواع وضعیت تنوع',
					'EmptyError' => 'نوع وضعیت پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				'pic' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'دارای تصویر',
						'2' => 'بدون تصویر',
						'3' => 'بیش از یک تصویر',
						'4' => 'فقط تصویر اول'
					),
					'Where'	=> array(
						'1'	=> "p.Pic<>''",
						'2' => "p.Pic=''",
						'3' => 'EXISTS (SELECT ID FROM tproduct_images pi WHERE pi.PID = p.ID)',
						'4' => "NOT EXISTS (SELECT ID FROM tproduct_images pi WHERE pi.PID = p.ID) AND p.Pic<>''"
					),
					'Title'	=> 'وضعیت تصاویر' ,
					'ListTitle' => 'انواع وضعیت تصاویر',
					'EmptyError' => 'نوع تصویر پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
				
			),
			'AddData'   => array(
				'Stores' => 'SELECT ID, Name FROM tstores WHERE Deleted=0 AND Active=1'

			),
			'PreQueries'=> array(
				'DELETE FROM tproduct_temp_mojodi WHERE UID=#uid#',
				"INSERT INTO `tproduct_temp_mojodi` (ID, UID, Mojodi)
				SELECT m.PID, #uid#, IFNULL(SUM(t.Count), 0)
				FROM tproduct_mojodi_trans t INNER JOIN tproduct_mojodi m ON (t.PMID = m.ID)
				WHERE State IN (0, 1)
				GROUP BY m.PID"
			),
			'Query'		=> "SELECT p.ID, p.Name, p.Pic, p.Code, c.Name AS Cat, b.NameFa AS Brand, m.Price, /*get_mojodi_by_pid(p.ID) AS Mojodi,*/ IFNULL(pm.Mojodi, 0) AS Mojodi, p.Active, IFNULL(p1.cnt, 0) AS Items, p.Title, p.Parent, IFNULL(parent.Name, '') AS ParentName, p.VarietyType, p.Priority
			FROM tproducts p LEFT OUTER JOIN tcats c ON (p.CID = c.ID)
			INNER JOIN tproduct_mojodi m ON (p.ID = m.PID AND m.Active=1)
			INNER JOIN tbrands b ON (p.BID = b.ID)			
 			LEFT OUTER JOIN tunits u ON (p.Unit = u.ID)
			LEFT OUTER JOIN tproduct_temp_mojodi pm ON (p.ID = pm.ID AND pm.UID=#uid#)
			LEFT OUTER JOIN (SELECT Parent, COUNT(*) AS cnt FROM tproducts WHERE Deleted=0 GROUP BY Parent) p1 ON (p.ID = p1.Parent)
			LEFT OUTER JOIN tproducts parent ON (p.Parent = parent.ID)
			WHERE p.Deleted=0 #where#
			"
			,
			'FileQuery'=> get_all_products_file,
			'FileSelect'=> "SELECT p.ID AS 'شناسه محصول', p.Name AS 'نام محصول', p.Code AS 'کد محصول', c.Name AS 'دسته', b.NameFa AS 'برند', u.Name AS 'واحد', m.Price AS 'قیمت', get_mojodi_by_pid(p.ID) AS 'موجودی', p.Active AS 'وضعیت' ",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (p.Name LIKE #key# OR c.Name LIKE #key# OR b.NameFa LIKE #key# OR p.Code LIKE #key#)',
			'Sort'		=> 'p.ID',
			'Columns'	=> array(
				'Pic'	=> array(
					'Width'	=> 80,
					'Title'	=> 'تصویر',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.Pic != '')
								res = '<div uk-lightbox><a href=\"" . $CI->ShopUrl . "assets/uploads/Products/' + row.Pic + '\" data-alt=\"Image\"><img src=\"" . $CI->ShopUrl . "assets/uploads/Products/' + row.Pic + '\" style=\"max-width: 96px; max-height: 96px;\" /></a></div>';

							return res;

						"
					)
				),
				'Code' => array(
					'Title'	=> 'کد محصول',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Name'  => array(
					'Width'	=> 200,
					'Title'	=> 'نام',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Cat'	=> array(
					'Title'	=> 'دسته',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Brand'	=> array(
					'Title'	=> 'برند',
					'Formatter' => array('Type' => 'tooltip')
				),
				'Price'	=> array(
					'Title'	=> 'قیمت',
					'Formatter'	=> array(
						'Type'	=> 'price'
					)
				),
				'Mojodi'	=> array(
					'Title'	=> 'موجودی',
					'Formatter'	=> array(
						'Type'	=> 'price'
					)
				),
				'َActive'	=> array(
					'Title'	=> 'وضعیت',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "if (row.Active == 1) return 'فعال'; else return 'غیر فعال'; 
						"	
					)
				),
				'Items'	=> array(
					'Title'	=> 'تنوع',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							if (row.Items > 0)
								return row.Items;
							else if (row.Parent != 0) {
								let res = '';

								res = '<a href=\"" . site_url('products/list_products/read') . "/' + row.Parent + '\" title=\"' + row.ParentName + '\" data-toggle=\"tooltip\">' + row.ParentName + '</a>';

								return res;
							}
							else
								return '-';

						"
					)
				),
				'Priority' => array(
					'Title'	=> 'اولویت نمایش',
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 390,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Link'	=> array(
									'Address' => site_url('products/list_products/read') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									),
								)
							),
							array(
								'Icon'	=> 'fas fa-edit',
								'Title'	=> 'ویرایش',
								'Link'	=> array(
									'Address' => site_url('products/list_products/edit') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									),
								)
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title'	=> 'حذف',
								'Action'=> 'DeleteProduct(#index#);'
							),
							array(
								'Icon'	=> 'fas fa-money-bill-alt',
								'Title'	=> 'قیمت گذاری',
								'Action'=> 'ViewPrices(#index#)'
								/*'Link'	=> array(
									'Address' => site_url('products/list_products/read') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									)
								)*/
							),
							array(
								'Icon'	=> 'fas fa-images',
								'Title'	=> 'کاتالوگ ها',
								'Link'	=> array(
									'Address' => site_url('products/item_catalogs') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									),
									'Target'  => '_blank'
								)
							),
							array(
								'Icon'	=> 'fas fa-images',
								'Title'	=> 'تصاویر',
								'Link'	=> array(
									'Address' => site_url('products/product_images') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									),
									'Target'  => '_blank'
								)
							),
							array(
								'Icon'	=> 'fas fa-database',
								'Title'	=> 'ثبت موجودی',
								'Action'=> 'SabtMojodiByID(#index#);'
							),
							array(
								'Icon'	=> 'fas fa-sitemap',
								'Title'	=> 'ثبت تنوع',
								'Action'=> 'VarietyItems(#index#);',
								'If'	=> 'row.Parent==0'
							),
						)
					)
				)
			)
		),
		'reseller_products_report'		=> array(
			'Title'		=> 'گردش کالا',
			'Access'	=> $CI->User['IsReseller'] == 1,
			'Controller'=> 'products',
			'Action'	=> 'logs',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "s.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "s.ShamsiDate<='#value#'"
				),
				'product' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tproducts t WHERE t.Deleted=0 AND ID IN (SELECT ProductID FROM treseller_products WHERE UserID=' . $CI->User['ID'] . ' AND Active=1 AND Deleted=0)' ,
					'Where'		=> "pm.PID IN (#value#)",
					'Title'		=> 'کالا',
					'ListTitle' => 'کالاها',
					'EmptyError'=> 'کالایی پیدا نشد'
				),
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'دستی',
						'2' => 'فروش',
					),
					'Where'	=> array(
						'1'	=> 's.OrderID=0',
						'2'	=> 's.OrderID<>0',
					),
					'Title'	=> 'نوع گردش',
					'ListTitle' => 'انواع گردش',
					'EmptyError' => 'نوع گردش پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),

			),
			'Query'		=> "SELECT CONCAT(s.ShamsiDate, ' ', s.ShamsiTime) AS Tarikh, s.Count, s.State, CONCAT(st.Name, IFNULL(CONCAT(' - ', r.FName, ' ', r.LName), '')) AS Store, CONCAT(u.FName, ' ', u.LName) AS User, s.OrderID, p.Name AS Product, os.Name AS OrderState, s.AzTarikh, s.TaTarikh, s.BuyPrice
				FROM tproduct_mojodi_trans s INNER JOIN tstores st ON (s.StoreID = st.ID)
				INNER JOIN tuser u ON (s.UserID = u.ID)
				INNER JOIN tproduct_mojodi pm ON (s.PMID = pm.ID)
				INNER JOIN tproducts p ON (pm.PID = p.ID)
				LEFT OUTER JOIN tuser r ON (s.ResellerID = r.ID)
				LEFT OUTER JOIN torders o ON (s.OrderID = o.ID)
				LEFT OUTER JOIN torder_status os ON (o.Status = os.ID)
				LEFT OUTER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE s.State IN (0,1) AND s.ResellerID=" . $CI->User['ID'] . " #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (u.FName LIKE #key# OR u.LName LIKE #key#)',
			'Sort'		=> 's.ID DESC',
			'Columns'	=> array(
				'Product'=> array(
					'Title'	=> 'نام کالا',
				),
				'Tarikh' => array(
					'Title'	=> 'تاریخ',
				),
				'Etebar' => array(
					'Title'	=> 'تاریخ اعتبار',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.AzTarikh != '')
								res += row.AzTarikh;

							if (row.TaTarikh != '') {
								if (res != '')
									res += ' - ';

								res += row.TaTarikh;
							}

							return res;
						"
					)
				),
				'Count'=> array(
					'Title'	=> 'تعداد'
				),
				'BuyPrice'=> array(
					'Title'	=> 'قیمت',
					'Formatter' => array('Type' => 'price'),
					'Footer'=> true
				),
				'OrderID' => array(
					'Title'	=> 'شماره سفارش',
				),
				'OrderState' => array(
					'Title'	=> 'وضعیت سفارش',
				),
			)
		),
		'reseller_products_to_deliver_report'		=> array(
			'Title'		=> 'تعهد تحویل',
			'Access'	=> $CI->User['IsReseller'] == 1,
			'Controller'=> 'products',
			'Action'	=> 'to_deliver',
			'HeaderView'=> 'products/header_reseller_to_deliver',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "s.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "s.ShamsiDate<='#value#'"
				),
				'product' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tproducts t WHERE t.Deleted=0 AND ID IN (SELECT ProductID FROM treseller_products WHERE UserID=' . $CI->User['ID'] . ' AND Active=1 AND Deleted=0)' ,
					'Where'		=> "pm.PID IN (#value#)",
					'Title'		=> 'کالا',
					'ListTitle' => 'کالاها',
					'EmptyError'=> 'کالایی پیدا نشد'
				),
			),
			'CustomFilters' => "
				pars.tajmi = document.getElementById('chbTajmi').checked ? 1 : 0;
			",
			'Query'		=> (intval($_POST['tajmi']) == 0 ? "SELECT CONCAT(s.ShamsiDate, ' ', s.ShamsiTime) AS Tarikh, s.Count, s.State, CONCAT(st.Name, IFNULL(CONCAT(' - ', r.FName, ' ', r.LName), '')) AS Store, CONCAT(u.FName, ' ', u.LName) AS User, s.OrderID, p.Name AS Product, os.Name AS OrderState, s.BuyPrice, s.Collected, s.ID AS TranID " : "SELECT p.Name AS Product, SUM(s.Count) AS Count, IFNULL(MIN(s.Collected), 0) AS MinCollected, IFNULL(MAX(s.Collected), 0) AS MaxCollected, GROUP_CONCAT(s.ID) AS TranID ") . 
				"FROM tproduct_mojodi_trans s INNER JOIN tstores st ON (s.StoreID = st.ID)
				INNER JOIN tuser u ON (s.UserID = u.ID)
				INNER JOIN tproduct_mojodi pm ON (s.PMID = pm.ID)
				INNER JOIN tproducts p ON (pm.PID = p.ID)
				LEFT OUTER JOIN tuser r ON (s.ResellerID = r.ID)
				LEFT OUTER JOIN torders o ON (s.OrderID = o.ID)
				LEFT OUTER JOIN torder_status os ON (o.Status = os.ID)
				LEFT OUTER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE s.State IN (0,1) AND IFNULL(o.ID, 0) > 0 AND IFNULL(os.ID, 0) IN (3, 7, 8) AND s.ResellerID=" . $CI->User['ID'] . " #where#
				" . ( intval($_POST['tajmi']) == 0 ? '' : " GROUP BY p.ID, p.Name " )
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (u.FName LIKE #key# OR u.LName LIKE #key#)',
			'Sort'		=> 's.ID DESC',
			'Columns'	=> array(
				'Collected' => array(
					'Title'	=> 'جمع آوری',
					'Width' => 80,
					'Formatter' => array(
						'Type' => 'custom',
						'Code' => "
							let res = '';

							res += '<input type=\'checkbox\' data-index=\'' + data.length + '\' id=\'chbCollected_' + data.length + '\' class=\'uk-checkbox\' onchange=\'CollectedChange(this);\'';
							if (document.getElementById('chbTajmi').checked) {
								if (row.MinCollected == 1)
									res += 'checked';
							}
							else {
								if (row.Collected == 1)
									res += 'checked';
							}

							res += ' />';

							data[data.length] = row;

							return res;
						"
					)
				),
				'Product'=> array(
					'Title'	=> 'نام کالا',
				),
				'Tarikh' => array(
					'Title'	=> 'تاریخ',
				),
				'Count'=> array(
					'Title'	=> 'تعداد'
				),
				'BuyPrice'=> array(
					'Title'	=> 'قیمت'
				),
				'OrderID' => array(
					'Title'	=> 'شماره سفارش',
				),
				'OrderState' => array(
					'Title'	=> 'وضعیت سفارش',
				),
			)
		),
		'store_products_to_receive_report'		=> array(
			'Title'		=> 'تعهد تحویل تامین کننده',
			'Access'	=> 'store',
			'Controller'=> 'store',
			'Action'	=> 'to_receive',
			'Filters'	=> array(
				'az'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'از تاریخ:',
					'Where' => "s.ShamsiDate>='#value#'",
					'Value'	=> $CI->mViewData['D1Mah']
				),
				'ta'	=> array(
					'Type'	=> 'shamsi_date',
					'Title'	=> 'تا تاریخ:',
					'Where' => "s.ShamsiDate<='#value#'"
				),
				'product' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.Name FROM tproducts t WHERE t.Deleted=0 AND ID IN (SELECT ProductID FROM treseller_products WHERE UserID=' . $CI->User['ID'] . ' AND Active=1 AND Deleted=0)' ,
					'Where'		=> "pm.PID IN (#value#)",
					'Title'		=> 'کالا',
					'ListTitle' => 'کالاها',
					'EmptyError'=> 'کالایی پیدا نشد'
				),
				'reseller' => array(
					'Type'	=> 'select',
					'Options' => "SELECT t.ID, CONCAT(FName, ' ', LName) AS Name FROM tuser t WHERE t.Deleted=0 AND t.IsReseller=1" ,
					'Where'		=> "s.ResellerID IN (#value#)",
					'Title'		=> 'تامین کننده',
					'ListTitle' => 'تامین کننده ها',
					'EmptyError'=> 'تامین کننده ای پیدا نشد'
				),
			),
			'Query'		=> "SELECT CONCAT(s.ShamsiDate, ' ', s.ShamsiTime) AS Tarikh, s.Count, s.State, CONCAT(st.Name, IFNULL(CONCAT(' - ', r.FName, ' ', r.LName), '')) AS Store, CONCAT(u.FName, ' ', u.LName) AS User, s.OrderID, p.Name AS Product, os.Name AS OrderState, c.ID AS CustomerID, c.ShopName, s.BuyPrice
				FROM tproduct_mojodi_trans s INNER JOIN tstores st ON (s.StoreID = st.ID)
				INNER JOIN tuser u ON (s.UserID = u.ID)
				INNER JOIN tproduct_mojodi pm ON (s.PMID = pm.ID)
				INNER JOIN tproducts p ON (pm.PID = p.ID)
				LEFT OUTER JOIN tuser r ON (s.ResellerID = r.ID)
				LEFT OUTER JOIN torders o ON (s.OrderID = o.ID)
				LEFT OUTER JOIN torder_status os ON (o.Status = os.ID)
				LEFT OUTER JOIN tcustomers c ON (o.UID = c.ID)
				WHERE s.State IN (0,1) AND IFNULL(o.ID, 0) > 0 AND IFNULL(os.ID, 0) IN (0, 2, 3, 4, 5, 6, 7, 8) AND s.ResellerID<>0 #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (u.FName LIKE #key# OR u.LName LIKE #key#)',
			'Sort'		=> 's.ID DESC',
			'Columns'	=> array(
				'Product'=> array(
					'Title'	=> 'نام کالا',
				),
				'Store' => array(
					'Title'	=> 'تامین کننده'
				),
				'Tarikh' => array(
					'Title'	=> 'تاریخ',
				),
				'Count'=> array(
					'Title'	=> 'تعداد'
				),
				'BuyPrice'=> array(
					'Title'	=> 'قیمت'
				),
				'Order' => array(
					'Title'	=> 'سفارش',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.OrderID == 0)
								res = '-';
							else
								res = '<a href=\"javascript:\" onclick=\"ViewOrder(' + row.OrderID + ');\">' + row.OrderID + '</a>';

							return res;
						"
					)
				),
				'Customer' => array(
					'Title'	=> 'مشتری',
					'Formatter'	=> array(
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.CustomerID == 0)
								res = '-';
							else
								res = '<a href=\"javascript:\" onclick=\"ViewCustomerById(' + row.CustomerID + ');\">' + row.ShopName + '</a>';

							return res;
						"
					)
				),
			)
		),
		'reseller_products'		=> array(
			'Title'		=> 'محصولات من',
			'Access'	=> $CI->User['IsReseller'] == 1,
			'Controller'=> 'products',
			'Action'	=> 'reseller_products',
			'ExtraView'	=> 'products/reseller_products',
			'Actions'	=> array(
				array(
					'Title'	=> 'جستجو و افزودن محصول',
					'Class'	=> 'btn btn-success',
					'Action'=> 'OpenSearch();'
				),
				array(
					'Title'	=> 'آپلود فایل موجودی',
					'Class'	=> 'btn btn-info',
					'Action'=> 'OpenImport();'
				)
			),
			'Filters'	=> array(
				'brand' => array(
					'Type'	=> 'select',
					'Options' => 'SELECT t.ID, t.NameFa AS Name FROM tbrands t WHERE ID IN (SELECT BID FROM treseller_products p INNER JOIN tproducts pp ON (p.ProductID = pp.ID) WHERE p.UserID=' . $CI->User['ID'] . ' AND p.Active=1 AND p.Deleted=0)' ,
					'Where'		=> "p.BID IN (#value#)",
					'Title'		=> 'برند',
					'ListTitle' => 'برندها',
					'EmptyError'=> 'برندی پیدا نشد'
				),
				'reseller' => array(
					'Type'	=> 'select',
					'Options' => "SELECT t.ID, t.Name FROM tcats t WHERE t.Deleted=0 AND ID IN (SELECT CID FROM treseller_products p INNER JOIN tproducts pp ON (p.ProductID = pp.ID) WHERE p.UserID=" . $CI->User['ID'] . " AND p.Active=1 AND p.Deleted=0)" ,
					'Where'		=> "p.CID IN (#value#)",
					'Title'		=> 'دسته',
					'ListTitle' => ' دسته ها',
					'EmptyError'=> ' دسته ای پیدا نشد'
				),
			),
			'Query'		=> "SELECT p.ID, p.CID, p.Code, p.BID, c.Name AS Cat, b.NameFa AS Brand, p.Name, p.Pic, r.LastPrice, get_mojodi_by_reseller(p.ID, " . $CI->User['ResellerStoreID'] . ", r.UserID) AS Mojodi, r.Code AS ResellerCode
			FROM treseller_products r INNER JOIN tproducts p ON (r.ProductID = p.ID)
			INNER JOIN tcats c ON (p.CID = c.ID)
			INNER JOIN tbrands b ON (p.BID = b.ID)
			WHERE p.Deleted=0 AND p.Active=1 AND r.Deleted=0 AND r.UserID=#uid# #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT r.Code AS 'کد', p.Code AS 'کد CRM',  c.Name AS 'دسته', b.NameFa AS 'برند', p.Name AS 'نام محصول', r.LastPrice AS 'آخرین قیمت', get_mojodi_by_reseller(p.ID, " . $CI->User['ResellerStoreID'] . ", r.UserID) AS 'موجودی'",
			'FileName'	=> 'products', 
			'KeyFilter' => 'AND (p.Name LIKE #key# OR c.Name LIKE #key# OR r.Code LIKE #key# OR b.NameFa LIKE #key# OR p.Code LIKE #key#)',
			'Sort'		=> 'p.ID DESC',
			'Columns'	=> array(
				'Pic'	 => array(
					'Title'	=> 'تصویر',
					'Formatter' => array(
						'Type'	=> 'custom',
						'Code'	=> "let res = '';

                        if (row.Pic != '')
							res = '<div uk-lightbox><a href=\"" . $CI->ShopUrl . "assets/uploads/Products/' + row.Pic + '\" data-alt=\"Image\"><img src=\"" . $CI->ShopUrl . "assets/uploads/Products/' + row.Pic + '\" style=\"max-width: 96px; max-height: 96px;\" /></a></div>';

                        return res;"
					)
				),
				'ResellerCode'=> array(
					'Title'	=> 'کد کالا',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Code'=> array(
					'Title'	=> 'کد کالا در CRM',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Name' => array(
					'Title'	=> 'نام',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Brand' => array(
					'Title'	=> 'برند',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Cat'=> array(
					'Title'	=> 'دسته',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Mojodi'=> array(
					'Title'	=> 'موجودی فعل',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'LastPrice'=> array(
					'Title'	=> 'آخرین قیمت',
					'Formatter' => array(
						'Type'	=> 'call_action',
						'Action'=> 'SetMojodi(#index#);'
					)
				),
				'Options'  => array(
					'Title'	=> '',
					'Width'	=> 150,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-search',
								'Title'	=> 'مشاهده',
								'Action'=> "ViewProduct(' + row.ID + ');"
							),
							array(
								'Icon'	=> 'fas fa-plus',
								'Title'	=> 'موجودی',
								'Action'=> 'SetMojodi(#index#);'
								/*'Link'	=> array(
									'Address' => site_url('reseller/mojodies') . '/#id#',
									'Params'  => array(
										'#id#'	=> 'row.ID'
									)
								)*/
							),
							array(
								'Icon'	=> 'fas fa-trash',
								'Title'	=> 'مشاهده',
								'Action'=> "DeleteMyproduct(#index#);"
							),
						)
					)
				)
			)
		),
		'app_logins'		=> array(
			'Title'		=> 'کاربران نرم افزار',
			'Access'	=> 'customers',
			'Controller'=> 'customers',
			'Action'	=> 'app_logins',
			'Filters'	=> array(
				'type' => array(
					'Type'	=> 'select',
					'Options'	=> array(
						'1'	=> 'ویزیتور',
						'2' => ' مشتری',
					),
					'Where'	=> array(
						'1'	=> 't.UserID<>0',
						'2' => 't.CustomerID<>0',
					),
					'Title'	=> 'نوع کاربر',
					'ListTitle' => 'انواع کاربر',
					'EmptyError' => 'نوع کاربر پیدا نشد',
					//'Enabled'	=> $_GET['type'] == 'mali' || $_GET['type'] == 'store'
				),
			),
			'Query'		=> "SELECT t.ID, (CASE WHEN t.UserID<>0 THEN CONCAT(u.FName, ' ', u.LName) ELSE c.ShopName END) AS Name, CONCAT(shamsi_date(t.LoginDate), ' ', SUBSTR(t.LoginDate, 12)) AS Register, CONCAT(shamsi_date(t.LastRequest), ' ', SUBSTR(t.LastRequest, 12)) AS Request, t.Device, t.UserID, t.CustomerID, (CASE WHEN t.UserID=0 THEN 'مشتری' ELSE 'ویزیتور' END) AS Type
			FROM tapp_logins t LEFT OUTER JOIN tuser u ON (t.UserID = u.ID)
			LEFT OUTER JOIN tcustomers c ON (t.CustomerID = c.ID)
			WHERE t.Active=1 AND IFNULL(c.Deleted, 0)=0 AND IFNULL(u.Deleted, 0)=0 #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT (CASE WHEN t.UserID=0 THEN c.ShHesab ELSE '-' END) AS 'کد مشتری', (CASE WHEN t.UserID<>0 THEN CONCAT(u.FName, ' ', u.LName) ELSE c.ShopName END) AS 'نام', CONCAT(shamsi_date(t.LoginDate), ' ', SUBSTR(t.LoginDate, 12)) AS 'ثبت نام', CONCAT(shamsi_date(t.LastRequest), ' ', SUBSTR(t.LastRequest, 12)) AS 'آخرین درخواست', t.Device AS 'دستگاه', (CASE WHEN t.UserID=0 THEN 'مشتری' ELSE 'ویزیتور' END) AS 'نوع کاربر'",
			'FileName'	=> 'app_users', 
			'KeyFilter' => "(u.FName LIKE #key# OR u.LName LIKE #key# OR c.ShopName LIKE #key# )",
			'Sort'		=> 't.LastRequest DESC',
			'Columns'	=> array(
				'Name' => array(
					'Title'	=> 'نام'
				),
				'Register' => array(
					'Title'	=> 'تاریخ ثبت نام',
				),
				'Request'=> array(
					'Title'	=> 'اخرین درخواست'
				),
				'Device'=> array(
					'Title'	=> 'دستگاه'
				),
				'Type'=> array(
					'Title'	=> 'نوع کاربر'
				),
			)
		),
		'notifs'		=> array(
			'Title'		=> 'نوتیفیکیشن های ارسال شده',
			'Access'	=> 'users',
			'Controller'=> 'users',
			'Action'	=> 'notifs',
			'Filters'	=> array(
			),
			'Query'		=> "SELECT t.ID, CONCAT(u.FName, ' ', u.LName) AS User, t.Topic, CONCAT(t.ShamsiDate, ' ', t.ShamsiTime) AS Tarikh, t.Title, t.Tozihat, t.Image
				FROM tnotif_sends t INNER JOIN tuser u ON (t.UserID = u.ID)
				WHERE 1=1 #where#
				 "
			,
			'FileQuery'=> '',
			'FileSelect'=> "SELECT CONCAT(u.FName, ' ', u.LName) AS 'اپراتور', t.Topic AS 'گیرندگان', CONCAT(t.ShamsiDate, ' ', t.ShamsiTime) AS 'تاریخ ارسال', t.Title AS 'عنوان', t.Tozihat AS 'متن'",
			'FileName'	=> 'notifs', 
			'KeyFilter' => '(u.FName LIKE #key# OR u.LName LIKE #key# OR t.Title LIKE #key# )',
			'Sort'		=> 't.ID DESC',
			'Columns'	=> array(
				'Image' => array(
					'Title'	=> 'عکس',
					'Formatter' => array (
						'Type'	=> 'custom',
						'Code'	=> "
							let res = '';

							if (row.Image == '')
								res = '" . base_url($CI->Domain['NotifImage']) . "';
							else
								res = '" . base_url('assets/uploads/notifs') . "/' + row.Image;

							return '<img src=\"' + res + '\" style=\"max-width: 64px; max-height: 64px;\" />';
						"
					)
				),
				'User' => array(
					'Title'	=> 'اپراتور'
				),
				'Tarikh' => array(
					'Title'	=> 'تاریخ ارسال',
				),
				'Topic'=> array(
					'Title'	=> 'گیرندگان'
				),
				'Title'=> array(
					'Title'	=> 'عنوان'
				),
				'Options'	=> array(
					'Title'	=> '',
					'Width'	=> 50,
					'Formatter' => array(
						'Type'	=> 'action',
						'Actions' => array(
							array(
								'Icon'	=> 'fas fa-sync',
								'Title'	=> 'ارسال مجدد',
								'Action'=> "SendReNotif(data[#index#]);"
							),
						)
					)
				)
			)
		),

	);
