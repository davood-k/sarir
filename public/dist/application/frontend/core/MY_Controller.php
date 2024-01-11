<?php

/**
 * Base Controller with functions for CRUD operations
 */
class MY_Controller extends CI_Controller {

    /**
     * Constructor with common logic for pages that required login139
     */
    public function __construct() {
        parent::__construct();
        //default load 
        
        $this->load->helper('jdf');
        date_default_timezone_set('Asia/Tehran');
        // get user data from session
        //print_r($this->mUser);
        // basic URL params
        $this->mCtrler = $this->router->fetch_class();
        $this->mAction = $this->router->fetch_method();

        $this->mParam = $this->uri->segment(3);
        $this->mViewData['Class'] = $this->mCtrler;
        $this->mViewData['Action'] = $this->mAction;
        $this->mViewData['AssetVersion'] = '79';
        $this->Colors = array(
            'b71c1c', '880E4F', '4A148C', '311B92', '1A237E', '0D47A1', '01579B', '006064', '004D40', '1B5E20', '33691E', '827717', 'F57F17', 'FF6F00', 'E65100', 'BF360C', '3E2723', '212121', '263238', 'f44336', 'E91E63', '9C27B0', '673AB7', '3F51B5', '2196F3', '03A9F4', '00BCD4', '009688', '4CAF50', '8BC34A', 'CDDC39', 'FFEB3B', 'FFC107', 'FF9800', 'FF5722', '795548', '9E9E9E', '607D8B'
        );

        $this->Version = 17;
        

        $this->mLayout = 'default';

        $this->User = 1;
        $this->load->model('user_model', 'user');
        $this->User = [
            'ID'  => 1,
            'NameProfile' => 'Admin'
        ];

        $this->mViewData['User'] = $this->User;
        $this->mViewData['Unit'] = "ریال";
        $this->mViewData['Today'] = $this->getShamsiDate();
        $this->mViewData['D1Week'] = $this->getShamsiDate(time() - (60 * 60 * 24) * 7);
        $this->mViewData['D1Mah'] = $this->getShamsiDate(time() - (60 * 60 * 24) * 31);
        $this->mViewData['D2Mah'] = $this->getShamsiDate(time() - (60 * 60 * 24) * 62);
        $this->mViewData['D3Mah'] = $this->getShamsiDate(time() - (60 * 60 * 24) * 93);
        $this->mViewData['FirstDay'] = jdate('Y/m/01', time(), '', 'Asia/Tehran', 'en');


        $this->mViewData['Theme'] = 'custom.css';

        $this->load->helper('url');
        $this->load->library('user_agent');   

        $config = [];
        $config = $config[0];

        $this->Config = $config;
        $this->mViewData['Config'] = $config;

        $uid = $this->User['ID'];

    }

    function fixString($text) {
        $text = str_replace('۰', '0', $text);
        $text = str_replace('۱', '1', $text);
        $text = str_replace('۲', '2', $text);
        $text = str_replace('۳', '3', $text);
        $text = str_replace('۴', '4', $text);
        $text = str_replace('۵', '5', $text);
        $text = str_replace('۶', '6', $text);
        $text = str_replace('۷', '7', $text);
        $text = str_replace('۸', '8', $text);
        $text = str_replace('۹', '9', $text);
        return $text;
    }
    
    function hasAccess() {
        return true;
    }

    /**
     * Protected function to be called by children
     */
    // add breadcrumb entry
    protected function push_breadcrumb($name, $url, $icon = '') {
        $icon = empty($icon) ? '' : 'fa fa-' . $icon;
        $this->mBreadcrumb[] = array(
            'name' => $name,
            'url' => site_url($url),
            'icon' => $icon,
        );
    }

    protected function persionToEnglish($text) {
        $text = str_replace("۰", "0", $text);
        $text = str_replace("۱", "1", $text);
        $text = str_replace("۲", "2", $text);
        $text = str_replace("۳", "3", $text);
        $text = str_replace("۴", "4", $text);
        $text = str_replace("۵", "5", $text);
        $text = str_replace("۶", "6", $text);
        $text = str_replace("۷", "7", $text);
        $text = str_replace("۸", "8", $text);
        $text = str_replace("۹", "9", $text);

        return $text;
    }

    public function englishToPersian($text) {
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

    public function getShamsiDate($time = 0) {
        if (empty($time))
            return jdate('Y/m/d', time(), '', 'Asia/Tehran', 'en');
        else
            return jdate('Y/m/d', $time, '', 'Asia/Tehran', 'en');
    }

    public function getShamsiTime() {
        return jdate('H:i:s', time(), '', 'Asia/Tehran', 'en');
    }

    public function ShamsiToMiladi($tarikh, $array = false, $time = '') {
        $res = jalali_to_gregorian(substr($tarikh, 0, 4), substr($tarikh, 5, 2), substr($tarikh, 8, 2));

        $year = intval($res[0]);
        $month = intval($res[1]);
        $day = intval($res[2]);

        if ($month < 10)
            $month = '0' . $month;

        if ($day < 10)
            $day = '0' . $day;

        if ($array) {
            return array(
                'Year'  => $year,
                'Month' => $month,
                'Day'   => $day
            );
        }
        else 
            return $year . '-' . $month . '-' . $day . $time;
    }

    public function DiffTarikh($tarikh1, $tarikh2) {
        $res1 = jalali_to_gregorian(substr($tarikh1, 0, 4), substr($tarikh1, 5, 2), substr($tarikh1, 8, 2));
        $res2 = jalali_to_gregorian(substr($tarikh2, 0, 4), substr($tarikh2, 5, 2), substr($tarikh2, 8, 2));

        $d1 = new DateTime($res1[0] . '-' . $res1[1] . '-' . $res1[2]);
        $d2 = new DateTime($res2[0] . '-' . $res2[1] . '-' . $res2[2]);

        $diff = $d1->diff($d2, true);

        return intval($diff->format('%a'));
    }

    public function ShamsiToTime($tarikh) {
        $res = jalali_to_gregorian(substr($tarikh, 0, 4), substr($tarikh, 5, 2), substr($tarikh, 8, 2));

        $year = intval($res[0]);
        $month = intval($res[1]);
        $day = intval($res[2]);

        $date = new DateTime();
        $date->setDate($year, $month, $day);
        
        return $date->getTimestamp ();
    }

    public function getFirstDay() {
        $date = new DateTime();

        while (true) {
            $day = intval($date->format('w'));
            $day++;

            if ($day == 7)
                $day = 0;

            if ($day == 0)
                return $date;
            
            date_sub($date, date_interval_create_from_date_string('1 days'));
        }

        return $date;
    }

    function get_day($date) {
        $day = intval($date->format('w'));

        $day++;

        if ($day == 7)
            $day = 0;

        return $day;
    }

    protected function generate_zip($files = array(), $path)
    {
        if (empty($files)) {
            throw new Exception('Archive should\'t be empty');
        }
        $this->load->library('zip');
        foreach ($files as $file) {
            $this->zip->read_file($file);
        }
        $this->zip->archive($path);
    }    

}
