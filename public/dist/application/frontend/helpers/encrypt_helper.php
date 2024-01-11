<?php

    function decrypt($data, $key = 'lkirwf897+22#bbtrm8814ikmq=498j5') {
      $CI =& get_instance();

      $CI->load->helper('aes');

      $fernet = new Fernet($key);

      $data = $fernet->decode($data);

      return $data;
    }

    function encrypt($data, $key = 'lkirwf897+22#bbtrm8814ikmq=498j5') {
      $CI =& get_instance();

      $CI->load->helper('aes');

      $fernet = new Fernet($key);

      $data = $fernet->encode($data);

      return $data;
    }    

    function encrypt_android($str, $key = 'lkirwf897+22#bbtrm8814ikmq=498j5',$iv = '741952hhasyy66#c') 
    {
      /*$td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

      mcrypt_generic_init($td, $key, $iv);
      $encrypted = mcrypt_generic($td, $str);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return bin2hex($encrypted);*/
      return $str;
    }

    function decrypt_android($code, $key = 'lkirwf897+22#bbtrm8814ikmq=498j5',$iv = '741952hhasyy66#c') 
    {
      /*$code = hex2bin($code);

      $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

      mcrypt_generic_init($td, $key, $iv);
      $decrypted = mdecrypt_generic($td, $code);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return utf8_encode(trim($decrypted));*/
      return $code;
    }



?>