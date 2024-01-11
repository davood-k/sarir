<?php
function mablagh($n){
	$n = intval($n);
	
	function to_int($s=''){
		
		$s = preg_replace('/[^\d]+/iu','',$s);
		return (int)$s;
		
		
	}
	
		function num_pos($n){
		switch($n){
			
		case 0 : 
	return '';    
		case 1 : 
	return 'هزار';    
		case 2 : 
	return 'میلیون';            
			case 3 : 
	return 'میلیارد';        
		}
		
	}
	function num_to_string($n){
		
	switch($n){
				case '*' : 
	return '';    
		case 0 :
		return 'صفر';
		break;
		case 1 :
		return 'یک';
		break;
		case 2 :
		return 'دو';
		break;
		case 3 :
		return 'سه';
		break;
		case 4 :
		return 'چهار';
		break;
		case 5 :
		return 'پنج';
		break;
		case 6 :
		return 'شش';
		break;
		case 7 :
		return 'هفت';
		break;
		case 8 :
		return 'هشت';
		break;
		case 9 :
		return 'نه';
		break;
		case 10 :
		return 'ده';
		break;
		case 11 :
		return 'یازده';
		break;
		case 12 :
		return 'دوازده';
		break;
		case 13 :
		return 'سیزده';
		break;
		case 14 :
		return 'چهارده';
		break;
		case 15 :
		return 'پانزده';
		break;
		case 16 :
		return 'شانزده';
		break;
		case 17 :
		return 'هفده';
		break;
		case 18 :
		return 'هجده';
		break;
		case 19 :
		return 'نوزده';
		break;
		
		case ($n >=20 && $n<=29):
		return 'بسیت';
		break;
		case ($n >=30 && $n<=39):
		return 'سی';
		break;
		case ($n >=40 && $n<=49):
		return 'چهل';
		break;
		case ($n >=50 && $n<=59):
		return 'پنجاه';
		break;
		case ($n >=60 && $n<=69):
		return 'شصت';
		break;
		case ($n >=70 && $n<=79):
		return 'هفتاد';
		break;
		case ($n >=80 && $n<=89):
		return 'هشتاد';
		break;
		case ($n >=90 && $n<=99):
		return 'نود';
		break;
		
		
		
		case ($n >=100 && $n<=199):
		return 'یکصد';
		break;
		case ($n >=200 && $n<=299):
		return 'دویست';
		break;
		case ($n >=300 && $n<=399):
		return 'سیصد';
		break;
		case ($n >=400 && $n<=499):
		return 'چهارصد';
		break;
		case ($n >=500 && $n<=599):
		return 'پانصد';
		break;
		case ($n >=600 && $n<=699):
		return 'ششصد';
		break;
		case ($n >=700 && $n<=799):
		return 'هفتصد';
		break;
		case ($n >=800 && $n<=899):
		return 'هشتصد';
		break;
		case ($n >=900 && $n<=999):
		return 'نهصد';
		break;
		
		
		case ($n >=1000 && $n<=999999):
		return 'هزار';
		break;
		case ($n >=1000000 && $n<=999999999):
		return 'میلیون';
		break;
		
	}
		
		
		
		
	}
		
		
		$n = to_int($n);
		if($n<0){
			$n * -1;
			
		}
		if(strpos($n,',')===false){
			$n = number_format($n);
			
		}
		
		$ex = explode(',',$n);
		
		$c= count($ex);
		$last_pos = $c-1;
		$l = '';
	
		foreach($ex as $n){
		$o = strlen($n) < 3 ? str_repeat('*',3-strlen($n)).$n : $n;    
	$is = false;
		
	$sadgan = $o[0]=='*' ? '*' : (int)$o[0] * 100;
	 $dahgan = (int)$o[1] * 10;
	$yekan  = (int)$o[2] * 1;
	$pp = num_pos($last_pos);
		
		
	 if($sadgan>0 && ($dahgan+$yekan) > 0){
	 $l .=  num_to_string($sadgan ).' - ';
	$is = true;
	
	 } elseif($sadgan>0 && ($dahgan+$yekan) == 0){
		$l .=  num_to_string($sadgan );
		$is = true; 
	 }
	
	 
	 
	 
	 
	
	 if($dahgan==10 && ($yekan>0 && $yekan<=9)){
	 $l .=  num_to_string($dahgan+$yekan );
	$is = true;
	
	 } elseif($dahgan==10 && $yekan==0){
	 $l .=  num_to_string($dahgan ).' - ';
	$is = true;
	
	 }elseif($dahgan>10  && ($yekan>0 && $yekan<=9)){
		 $l .=  num_to_string($dahgan ).' - '.num_to_string($yekan );
	$is = true;
		 
	 }elseif($dahgan>10 &&  $yekan==0 ){
		 $l .=  num_to_string($dahgan );
	$is = true;
		 
	 }
	if($dahgan==0 && ($yekan>0 )){
	 $l .=  num_to_string($yekan );
	$is = true;
	
	 } 
	
	$l .= ' '.$pp;
	$l .= $is ? ' - ' : '';
	 $last_pos--;
	
		
			
		}
		$l =trim($l);
		$l =  trim($l,'-');
		$l =  trim($l,' - ');
		$l = str_replace('-','و',$l);
		return $l;
		
		
	}