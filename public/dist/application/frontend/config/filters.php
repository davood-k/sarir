<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$config['filters'] = array(
		'brand'	=> array(
			'Options'	=> 'SELECT ID, NameFa as Name FROM tbrands',
			'Where'		=> "BID=#value#",
			'Title'		=> 'برند',
			'ListTitle' => 'برندها',
			'Type'		=> 'int',
			'InRelation'=> array('option1', 'option2', 'option3', 'option4')
		),
		'option1'	=> array(
			'Options'	=> 'SELECT DISTINCT StringValue AS ID, StringValue as Name FROM tproduct_infoes t inner join tproducts p ON (t.ProductID = p.ID) WHERE InfoID=83 AND p.Active=1 AND p.Deleted=0 AND p.Mojodi>0',
			'Where'		=> "EXISTS (SELECT ID FROM tproduct_infoes pi WHERE pi.ProductID=t.ID AND pi.InfoID=83 AND StringValue='#value#') ",
			'Title'		=> 'ویژگی یک',
			'ListTitle' => 'ویژگی یک',
			'Type'		=> 'text',
			'InRelation'=> array('option2', 'option3', 'option4'),
			'Relations'	=> array(
				'brand'	=> ' p.BID IN (#value#) '
			)
		),
		'option2'	=> array(
			'Options'	=> 'SELECT DISTINCT StringValue AS ID, StringValue as Name FROM tproduct_infoes t inner join tproducts p ON (t.ProductID = p.ID) WHERE InfoID=84 AND p.Active=1 AND p.Deleted=0 AND p.Mojodi>0',
			'Where'		=> "EXISTS (SELECT ID FROM tproduct_infoes pi WHERE pi.ProductID=t.ID AND pi.InfoID=84 AND StringValue='#value#') ",
			'Title'		=> 'ویژگی دو',
			'ListTitle' => 'ویژگی دو',
			'Type'		=> 'text',
			'InRelation'=> array('option3', 'option4'),
			'Relations'	=> array(
				'brand'	=> ' p.BID =#value# ',
				'option1' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=83 AND i.StringValue IN (\'#value#\')) '
			)
		),
		'option3'	=> array(
			'Options'	=> 'SELECT DISTINCT StringValue AS ID, StringValue as Name FROM tproduct_infoes t inner join tproducts p ON (t.ProductID = p.ID) WHERE InfoID=85 AND p.Active=1 AND p.Deleted=0 AND p.Mojodi>0',
			'Where'		=> "EXISTS (SELECT ID FROM tproduct_infoes pi WHERE pi.ProductID=t.ID AND pi.InfoID=85 AND StringValue='#value#') ",
			'Title'		=> 'ویژگی سه',
			'ListTitle' => 'ویژگی سه',
			'Type'		=> 'text',
			'InRelation'=> array('option4'),
			'Relations'	=> array(
				'brand'	=> ' p.BID =#value# ',
				'option1' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=83 AND i.StringValue IN (\'#value#\')) ',
				'option2' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=84 AND i.StringValue IN (\'#value#\')) '
			)
		),
		'option4'	=> array(
			'Options'	=> 'SELECT DISTINCT StringValue AS ID, StringValue as Name FROM tproduct_infoes t inner join tproducts p ON (t.ProductID = p.ID) WHERE InfoID=86 AND p.Active=1 AND p.Deleted=0 AND p.Mojodi>0',
			'Where'		=> "EXISTS (SELECT ID FROM tproduct_infoes pi WHERE pi.ProductID=t.ID AND pi.InfoID=86 AND StringValue='#value#') ",
			'Title'		=> 'ویژگی چهار',
			'ListTitle' => 'ویژگی چهار',
			'Type'		=> 'text',
			'Relations'	=> array(
				'brand'	=> ' p.BID =#value# ',
				'option1' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=83 AND i.StringValue IN (\'#value#\')) ',
				'option2' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=84 AND i.StringValue IN (\'#value#\')) ',
				'option3' => ' EXISTS (SELECT ID FROM tproduct_infoes i WHERE i.ProductID=p.ID AND i.InfoID=85 AND i.StringValue IN (\'#value#\')) '
			)
		),

	);


