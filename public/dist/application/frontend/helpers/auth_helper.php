<?php

/**
 * Helper class to handle authentication
 */

function escapeString($val) {
    $db = get_instance()->db->conn_id;
    $val = mysqli_real_escape_string($db, $val);
    return $val;
}
// Generate random password (e.g. for newly created user)
function random_pw($length = 10)
{
	$CI =& get_instance();
	$CI->load->helper('string');
	return random_string('alnum', $length);
}
function password_hash2($plain_pw=0, $def)
{
    $x = sha1($plain_pw ? $plain_pw : $def);
    return $x;
}
// Create hashed password
function hash_pw($plain_pw)
{
	// (optional) change logic here for different hash algorithm
    $defpass="asdasdasdadcsa";
	return password_hash2($plain_pw, $defpass);
}

// Verify password
function verify_pw($plain_pw, $hashed_pw)
{
	// (optional) change logic here for different hash algorithm
	return password_verify2($plain_pw, $hashed_pw);
}
/**
 * 
 * @param type $plain_pw
 * @param type $hashed_pw
 * @return type
 */
function password_verify2($plain_pw, $hashed_pw)
{
	return (sha1($plain_pw)==($hashed_pw));
}
// Activation / Forgot Password code

    function create_rate($rate) {
        $rate = intval($rate);

        $res = '';

        for ($i = 0; $i < 5; $i++) {
            $res .= '<span class="fa fa-star ' . ($i <= ($rate - 1) ? 'checked' : '') . '"></span>';
        }

        return $res;
    }

    function GetPhone($mobile) {
        $mobile = str_replace("+", "", $mobile);
    
        $mobile = trim($mobile);
    
        while (true)
        {
            if (substr($mobile, 0, 1) == '0')
                $mobile = substr($mobile, 1);
            else
                break;
        }
    
        if (substr($mobile, 0, 2) == "98")
            $mobile = substr($mobile, 2);
    
        while (true)
        {
            if (substr($mobile, 0, 1) == '0')
                $mobile = substr($mobile, 1);
            else
                break;
        }
    
        return $mobile;
    }
    
    function CheckPhone($Phone)
    {
        $Phone = GetPhone($Phone);
    
        while (substr($Phone, 0, 1) == '0' || substr($Phone, 0, 1) == '+')
            $Phone = substr($Phone, 1);
    
        if (!ctype_digit($Phone))
            return false;
    
        if (substr($Phone, 0, 2) == "98")
        {
            if (strlen($Phone) != 12)
                return false;
        }
        else
        {
            if (substr($Phone, 0 , 1) != '9')
                return false;
    
            if (strlen($Phone) != 10)
                return false;
        }
    
    
        return true;
    
    }
    
    function CheckPhones($Phones) {
        $arr = explode("\n", $Phones);
    
        foreach ($arr as $phone) {
            if (!CheckPHone($phone))
                return false;
        }
    
        return true;
    }
    
    
    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
    
    function createRate($rate, $li = true) {
        $res = '';
    
        $suli = '<li class="un-rate">';
        $sli  = '<li>';
        $seli = '</li>';
    
        if ($li == false) {
            $suli = '';
            $sli = '';
            $seli = '';
        }
    
        if ($rate == 0)
            $res .= $suli . '<i class="fa fa-star-o"></i>' . $seli;
        else if ($rate < 1)
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
        else 
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
    
        if ($rate <= 1)
            $res .= $suli . '<i class="fa fa-star-o"></i>' . $seli;
        else if ($rate < 2)
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
        else
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
    
        if ($rate <= 2)
            $res .= $suli . '<i class="fa fa-star-o"></i>' . $seli;
        else if ($rate < 3)
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
        else
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
    
        if ($rate <= 3)
            $res .= $suli . '<i class="fa fa-star-o"></i>' . $seli;
        else if ($rate < 4)
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
        else
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
    
        if ($rate <= 4)
            $res .= $suli . '<i class="fa fa-star-o"></i>' . $seli;
        else if ($rate < 5)
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
        else
            $res .= $sli . '<i class="fa fa-star"></i></li>' . $seli;
    
        return $res;
    }

if ( ! function_exists('toPersian'))
{
    function toPersian($text) {
        $text = str_replace("0", "۰", $text);
        $text = str_replace("1", "۱", $text);
        $text = str_replace("2", "۲", $text);
        $text = str_replace("3", "۳", $text);
        $text = str_replace("4", "۴", $text);
        $text = str_replace("5", "۵", $text);
        $text = str_replace("6", "۶", $text);
        $text = str_replace("7", "۷", $text);
        $text = str_replace("8", "۸", $text);
        $text = str_replace("9", "۹", $text);

        return $text;
    } 
}

if ( ! function_exists('Comma_deli'))
{
    function Comma_deli($text) {
        $text = intval($text);
        $text = number_format($text);
        //$text = toPersian($text);

        return $text;
    }
}

if ( ! function_exists('TicksToTime'))
{
    function TicksToTime($ticks) {
        if ($ticks < 2)
            return 'همین حالا';
        else if ($ticks < 60)
            return toPersian($ticks) . 'ثانیه قبل';
        else if ($ticks < 3600)
            return toPersian(intval($ticks / 60)) . ' دقیقه قبل';
        else if ($ticks < 86400)
            return toPersian(intval($ticks / 3600)) . ' ساعت قبل';
        else 
            return toPersian(intval($ticks / 86400)) . ' روز قبل';


        return $ticks;
    }
}
    
    function result($res, $mes, $data = null) {
        $CI =& get_instance();

        echo json_encode(array(
            'Result'    => $res,
            'Message'   => $mes,
            'Data'      => $data,
            'Version'   => $CI->Version
        ));
        die();
    }

    function toEnglish($text) {
        return $text;
    }

    function CheckDay($day)
    {
        $day = intval($day);
        if ($day > 31 || $day <= 0)
            return false;
    
        return true;
    }
    
    function CheckMonth($month)
    {
        $month = intval($month);
        if ($month > 12 || $month <= 0)
            return false;
    
        return true;
    }
    
    function CheckYear($year) {
        $year = intval($year);
        if ($year < 1200 || $year >= 1500)
            return false;
    
        return true;
    }
    
    function CheckHour($hour)
    {
        $hour = intval($hour);
        if ($hour >= 24 || $hour < 0)
            return false;
    
        return true;
    }
    
    function CheckMin($min) {
        $min = intval($min);
        if ($min >= 60 || $min < 0)
            return false;
    
        return true;
    }
    
    
    function CheckTime($time) {
        try {
            if (strpos($time, ':') === FALSE) {
                $time = substr($time, 0, 2) . ':' . substr($time, 2, 4);
            }
    
            $time = toEnglish($time);
    
            if (strlen($time) != 5) {
                return false;
            }
    
            if (!CheckHour(intval(substr($time, 0, 2))))
                return false;
    
            if (!CheckMin(intval(substr($time, 3, 5))))
                return false;
        }
        catch (Exception $e) {
            return false;
        }
    
        return true;
    }
    
    function CheckDate1($date, $EmptyTrue = false) {
        try {
            if (strpos($date, '/') === FALSE) {
                $date = substr($date, 0, 4) . '/' . substr($date, 4, 6) . "/" . substr($date, 6, 8);
            }
    
            $date = toEnglish($date);
    
            if (strlen($date) != 10) {
                if (strlen($date) == 0)
                    return $EmptyTrue;
                return false;
            }
    
    
            if (!CheckYear(intval(substr($date, 0, 4))))
                return false;
    
            if (!CheckMonth(intval(substr($date, 5, 7))))
                return false;
    
            if (!CheckDay(intval(substr($date, 8, 10))))
                return false;
        }
        catch (Exception $e) {
           if (trim($date) == "")
                return $EmptyTrue;
    
            return false;
        }
    
        return true;
    }
  