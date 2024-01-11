<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$config['global_filters'] = array(
		'map_senf' => array(
			'Type'	=> 'select',
			'Options' => 'SELECT ID, Name FROM tsenfs WHERE Deleted=0',
			'Title'		=> 'صنف',
			'ListTitle' => 'اصناف',
			'EmptyError'=> 'صنفی پیدا نشد'
		),
		'map_Country'	=> array(
			'Type'		=> 'select',
			'Options'	=> 'SELECT ID, Name FROM tcountries',
			'Title'		=> 'کشور',
			'ListTitle' => 'کشورها',
			'InRelation'=> array( 'map_State', 'map_City', 'map_Area', 'map_Block' )		
		),
		'map_State'	=> array(
			'Type'		=> 'select',
			'Options'	=> "SELECT ID, Name FROM bstates WHERE CountryID IN (?)",
			'Title'		=> 'استان',
			'ListTitle' => 'استان ها',
			'Relation'	=> 'map_Country',
			'InRelation'=> array( 'map_City', 'map_Area', 'map_Block' ),
			'EmptyError'=> 'کشور را انتخاب نمایید'			
		),
		'map_City'	=> array(
			'Type'		=> 'select',
			'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', t.Name) AS Name FROM bcities t INNER JOIN bstates s ON (t.SID = s.ID) WHERE t.SID IN (?)",
			'Title'		=> 'شهر',
			'ListTitle' => 'شهر ها',
			'Relation'	=> 'map_State',
			'InRelation'=> array( 'map_Area', 'map_Block' ),
			'EmptyError'=> 'استان را انتخاب نمایید'
		),
		'map_Area'	=> array(
			'Type'		=> 'select',
			'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', c.Name, ' - ', t.Name) AS Name FROM bareas t INNER JOIN bcities c ON (t.CityID = c.ID) INNER JOIN bstates s ON (c.SID = s.ID) WHERE t.CityID IN (?)",
			'Title'		=> 'منطقه',
			'ListTitle' => 'منطقه ها',
			'Relation'	=> 'map_City',
			'InRelation'=> array('map_Block'),
			'EmptyError'=> 'شهر را انتخاب نمایید'
		),
		'map_Block'	=> array(
			'Type'		=> 'select',
			'Options'	=> "SELECT t.ID, CONCAT(s.Name, ' - ', c.Name, ' - ', a.Name, ' - ', t.Name) AS Name FROM bblocks t INNER JOIN bareas a ON (t.AreaID = a.ID) INNER JOIN bcities c ON (a.CityID = c.ID) INNER JOIN bstates s ON (c.SID = s.ID) WHERE t.AreaID IN (?)",
			'Title'		=> 'بلوک',
			'ListTitle' => 'بلوک ها',
			'Relation'	=> 'map_Area',
			'EmptyError'=> 'منطقه را انتخاب نمایید'
		),
		'map_grade' => array(
			'Type'	=> 'select',
			'Options' => 'SELECT ID, Name FROM tgrades WHERE Deleted=0',
			'Title'		=> 'گرید',
			'ListTitle' => 'گریدها',
			'EmptyError'=> 'گریدی پیدا نشد'
		),
		'map_groups' => array(
			'Type'	=> 'select',
			'Options' => 'SELECT ID, Name FROM tgroups',
			'Title'		=> 'گروه مشتری',
			'ListTitle' => 'گروه های مشتری',
			'EmptyError'=> 'گروه مشتری پیدا نشد'
		),
		'map_user_states' => array(
			'Type'	=> 'select',
			'Options' => 'SELECT ID, Name FROM tcustomer_states',
			'Title'		=> 'وضعیت مشتری',
			'ListTitle' => 'وضعیت های مشتری',
			'EmptyError'=> 'وضعیت مشتری پیدا نشد'
		),
		'map_users' => array(
			'Type'	=> 'select',
			'Options' => "SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE Deleted=0 AND Active=1 AND Distribute=1",
			'Title'		=> 'موزع',
			'ListTitle' => 'موزعین',
			'EmptyError'=> 'موزعی پیدا نشد',
			'Multiple' => false
		),
		'az'	=> array(
			'Type'	=> 'shamsi_date',
			'Title'	=> 'از تاریخ تحویل:',
		),
		'ta'	=> array(
			'Type'	=> 'shamsi_date',
			'Title'	=> 'تا تاریخ تحویل:',
		),
		'shifts' => array(
			'Type'	=> 'select',
			'Options' => 'SELECT ID, Name FROM tshifts',
			'Title'		=> 'شیفت',
			'ListTitle' => 'شیفت ها',
			'EmptyError'=> 'شیفتی پیدا نشد'
		),	
		'visit_tour_area' => array(
			'Type' => 'select',
			'Options' => "SELECT t.ID, Name FROM bareas t WHERE Name<>'' ORDER BY Name",
			'Title'	=> 'منطقه',
			'ListTitle'	=> 'منطقه ها',
			'EmptyError' => 'منطقه ای پیدا نشد.',
			'OnChange'	=> 'UpdateMap();'
		),
		'cats' => array(
			'Type' => 'select',
			'Options' => "SELECT t.ID, Name FROM tcats t WHERE t.Active=1 AND t.Deleted=0",
			'Title'	=> 'دسته بندی',
			'ListTitle'	=> 'دسته بندی ها',
			'EmptyError' => 'دسته بندی ای پیدا نشد.',
			'Multiple' => false
		),
		'brands' => array(
			'Type' => 'select',
			'Options' => "SELECT t.ID, NameFa AS Name FROM tbrands t WHERE t.Active=1 AND t.Deleted=0",
			'Title'	=> 'برند',
			'ListTitle'	=> ' برند ها',
			'EmptyError' => 'برندی پیدا نشد.',
			'Multiple' => false
		)			

	);


