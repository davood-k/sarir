<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public function test() {
        $res = $this->db->query("SELECT id, tozih, sh_letter, date_letter, moarefi FROM tuser_logs WHERE tozih<>'' AND (sh_letter='' OR date_letter='' OR moarefi='')")->result();

        foreach ($res as $item) {
            $str = $item->tozih;

            preg_match("/نامه[ ]*(?<name>[0-9]+)/i", $str, $matches);

            $sh = '';
            if (!empty($matches['name']))
                $sh = $matches['name'];
    
            $matches = null;
            preg_match("/(?<name>[0-9]+\/[0-9]+\/[0-9]+)/i", $str, $matches);
    
            $date = '';
            if (!empty($matches['name']))
                $date = $matches['name'];

            if (empty($date)) {
                $matches = null;
                preg_match("/(?<name>[0-9]+\-[0-9]+\-[0-9]+)/i", $str, $matches);
        
                $date = '';
                if (!empty($matches['name']))
                    $date = str_replace('-', '/', $matches['name']);    
            }
    
            $matches = null;
            preg_match("/(?<name>معرفی از .*)/i", $str, $matches);
    
            $moarefi = '';
            if (!empty($matches['name']))
                $moarefi = $matches['name'];

            if (empty($moarefi)) {
                $matches = null;
                preg_match("/(?<name>معرفی .*)/i", $str, $matches);
        
                $moarefi = '';
                if (!empty($matches['name']))
                    $moarefi = $matches['name'];
    
            }

            if (empty($sh) || empty($date)|| empty($moarefi))
                echo $str . ' ==== ' . $sh . ' ---- ' . $date . ' ---- ' . $moarefi . "<br>\r\n";

            $data = [];

            if (empty($item->sh_letter) && !empty($sh))
                $data['sh_letter'] = $sh;

            if (empty($item->date_letter) && !empty($date))
                $data['date_letter'] = $date;

            if (empty($item->moarefi) && !empty($moarefi))
                $data['moarefi'] = $moarefi;

            if (!empty($data))
                $this->db->update('tuser_logs', $data, [ 'id' => $item->id ]);
        }


        die();
    }

    public function index() {
        $this->mViewFile = 'main/index';
        $this->mTitle = 'سامانه سریر - نامه های معرفی';

        $this->mViewData['Admin'] = ($_GET['admin'] ?? '') == '27';
    }

    public function add_person() {
        $code = trim(escapeString($_POST['code']));
        $fname = trim(escapeString($_POST['fname']));
        $lname = trim(escapeString($_POST['lname']));
        $gender = intval($_POST['gender']);
        $code = escapeString($_POST['code']);
        $sh = escapeString($_POST['sh']);
        $date = escapeString($_POST['date']);
        $moarefi = escapeString($_POST['moarefi']);
        $tozih = escapeString($_POST['tozih']);
        $molahezat = escapeString($_POST['molahezat']);
        $moavenat = escapeString($_POST['moavenat']);



        if (!empty($code) && !empty($fname) && !empty($lname) && !empty($gender)) {

            while (strlen($code) < 10)
                $code = '0' . $code;

            $res = $this->db->query('SELECT id, code_melli, fname, lname, gender FROM tusers WHERE code_melli=?', [ trim($code) ])->result();

            $this->db->trans_start();
            if (empty($res)) {
                $this->db->insert('tusers', [
                    'create_date' => date('Y-m-d H:i:s'),
                    'shamsi_date' => $this->getShamsiDate(),
                    'shamsi_time' => $this->getShamsiTime(),
                    'code_melli'  => $code,
                    'fname'       => $fname,
                    'lname'       => $lname,
                    'gender'      => $gender
                ]);

                $id = $this->db->insert_id();
            }
            else {
                $res = $res[0];

                $id = $res->id;

                $this->db->update('tusers', [
                    'code_melli'  => $code,
                    'fname'       => $fname,
                    'lname'       => $lname,
                    'gender'      => $gender
                ], [ 'id' => $id ]);

            }

            $str = $tozih;

            preg_match("/نامه[ ]*(?<name>[0-9]+)/i", $str, $matches);

            //$sh = '';
            if (!empty($matches['name']) && empty($sh))
                $sh = $matches['name'];
    
            $matches = null;
            preg_match("/(?<name>[0-9]+\/[0-9]+\/[0-9]+)/i", $str, $matches);
    
            $date_ = '';
            if (!empty($matches['name']))
                $date_ = $matches['name'];

            if (empty($date_)) {
                $matches = null;
                preg_match("/(?<name>[0-9]+\-[0-9]+\-[0-9]+)/i", $str, $matches);
        
                $date_ = '';
                if (!empty($matches['name']))
                    $date_ = str_replace('-', '/', $matches['name']);    
            }

            if (empty($date))
                $date = $date_;
    
            $matches = null;
            preg_match("/(?<name>معرفی از .*)/i", $str, $matches);
    
            $moarefi_ = '';
            if (!empty($matches['name']))
                $moarefi_ = $matches['name'];

            if (empty($moarefi_)) {
                $matches = null;
                preg_match("/(?<name>معرفی .*)/i", $str, $matches);
        
                if (!empty($matches['name']))
                    $moarefi_ = $matches['name'];
    
            }

            if (empty($moarefi) && !empty($moarefi_))
                $moarefi = $moarefi_;

            $this->db->insert('tuser_logs', [
                'user_id'   => $id,
                'create_date' => date('Y-m-d H:i:s'),
                'shamsi_date' => $this->getShamsiDate(),
                'shamsi_time' => $this->getShamsiTime(),
                'tozih'       => $tozih,
                'sh_letter'   => $sh,
                'date_letter' => $date,
                'moarefi'     => $moarefi,
                'molahezat'   => $molahezat,
                'moavenat'    => $moavenat
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() !== FALSE)
                return result(1, 'اطلاعات با موفقیت ثبت شد.');
            else
                return result(0, 'عملیات با خطا متوقف شد.');
        }

        result(0, 'اطلاعات ناقص می باشد');
    }

    public function get_info() {
        $id = intval($_POST['id']);

        if (!empty($id)) {
            $info = $this->db->query('SELECT id, fname, lname, code_melli, shamsi_date, shamsi_time, gender FROM tusers WHERE id=?', [ $id ])->result();

            if (empty($info))
                return result(0, 'موردی پیدا نشد');

            $info = $info[0];

            $info->letters = $this->db->query('SELECT id, shamsi_date, shamsi_time, sh_letter, date_letter, moarefi, tozih, molahezat, moavenat 
            FROM tuser_logs WHERE user_id=? AND deleted=0
            ORDER BY id DESC', [ $id ])->result();

            return result(1, '', $info);
        }

        result(0, 'اطلاعات ناقص می باشد');
    }

    public function check_code_melli() {
        $code = escapeString($_POST['code']);

        $res = $this->db->query('SELECT id, code_melli, fname, lname, gender FROM tusers WHERE code_melli=?', [ trim($code) ])->result();

        if (empty($res))
            return result(1, '', null);
        else {
            $res = $res[0];

            return result(1, '', $res);
        }
    }

    public function edit_letter() {
        $id = intval($_POST['id']);
        $sh = escapeString($_POST['sh']);
        $date = escapeString($_POST['date']);
        $moarefi = escapeString($_POST['moarefi']);
        $tozih = escapeString($_POST['tozih']);
        $molahezat = escapeString($_POST['molahezat']);
        $moavenat = escapeString($_POST['moavenat']);

        if (!empty($id)) {
            $item = $this->db->query('SELECT t.id FROM tusers t INNER JOIN tuser_logs l ON (t.id = l.user_id) WHERE l.id=?', [ $id ])->result();

            if (empty($item))
                result(0, 'موردی پیدا نشد.');

            $item = $item[0];

            $this->db->update('tuser_logs', [
                'sh_letter'   => $sh,
                'date_letter' => $date,
                'moarefi'     => $moarefi,
                'tozih'       => $tozih,
                'molahezat'   => $molahezat,
                'moavenat'    => $moavenat
            ], [ 'id' => $id ]);

            $_POST['id'] = $item->id;

            $this->get_info();
        }

        result(0, 'اطلاعات ناقص می باشد');
    }

    public function get_list() {
        $page = intval($_POST['current']);
        $count = intval($_POST['rowCount']);
        $key = escapeString($_POST['searchPhrase']);

        $sql = "SELECT MIN(t.id) AS id, t.code_melli, t.fname, t.lname, t.gender, GROUP_CONCAT(l.sh_letter) AS sh_letter, GROUP_CONCAT(l.date_letter) AS date_letter, GROUP_CONCAT(l.moarefi) AS moarefi, GROUP_CONCAT(l.tozih) AS tozih, GROUP_CONCAT(l.molahezat) AS molahezat
        FROM tusers t INNER JOIN tuser_logs l ON (t.id = l.user_id AND l.deleted=0) 
        WHERE 1=1
         ";

        if (!empty($key)) {
            $key = "'%$key%'";
            $sql .= " AND (t.code_melli LIKE $key OR CONCAT(t.fname, ' ', t.lname) LIKE $key OR t.fname LIKE $key OR t.lname LIKE $key OR l.tozih LIKE $key OR l.molahezat LIKE $key OR l.sh_letter LIKE $key OR l.date_letter LIKE $key) ";
        }

        $sql .= " GROUP BY t.code_melli, t.fname, t.lname, t.gender ";

        $cnt = $this->db->query($sql)->num_rows();

        if (isset($_POST['sort'])) {
            $sort = $_POST['sort'];

            $sql .= " ORDER BY ";
            $first = true;
            foreach ($sort as $key => $value) {
                if ($first)
                    $first = false;
                else 
                    $sql .= ", ";

                $sql .= $key . ' ' . $value;
            }
        }
        else
            $sql .= " ORDER BY t.id DESC ";

        if ($count > 0)
            $sql .= " LIMIT " . (($page - 1) * $count) . ',' . $count;

        echo json_encode([
            'current' => $page,
            'rowCount'=> $count,
            'rows'    => $this->db->query($sql)->result(),
            'total'   => $cnt
        ]);
        die();
    }

    public function delete_letter() {
        $id = intval($_POST['id']);

        if (!empty($id)) {
            $this->db->update('tuser_logs', [ 'deleted' => 1 ], [ 'id' => $id ]);

            return result(1, 'انجام شد');
        }

        return result(0, 'اطلاعات ناقص');
    }

	public function get_file() {
		$codes = $_POST['codes'];
        $this->mViewData['Admin'] = ($_GET['admin'] ?? '') == '27';
		$this->mViewFile = 'main/get_file';
		$this->mTitle = 'سامانه سریر (خروجی)';

        if (isset($_POST['all_records'])) {
            if ($this->mViewData['Admin']) 
                $codes = 'all';
        }

		if (!empty($codes)) {
            if ($codes == 'all' && $this->mViewData['Admin'] == false)
                return;

            if ($codes != 'all') {
                $codes = explode("\n", $codes);

                $in = '';
            

                foreach ($codes as $code) {
                    if (!empty($code)) {
                        if (!empty($in))
                            $in .= ",";

                        $code = trim($code);

                        $in .= "'$code'";
                    }
                }

                if (empty($in))
                    $in = "''";
            }
        
            

			$sql = "SELECT t.code_melli, t.fname, t.lname, GROUP_CONCAT(l.sh_letter) AS sh_letter, GROUP_CONCAT(l.date_letter, '-') AS date_letter, GROUP_CONCAT(l.moarefi) AS moarefi, GROUP_CONCAT(l.tozih) AS tozih, GROUP_CONCAT(l.molahezat) AS molahezat, GROUP_CONCAT(l.moavenat) AS moavenat
			FROM tusers t INNER JOIN tuser_logs l ON (t.id = l.user_id AND l.deleted=0) ";

            if ($codes != 'all')
			    $sql .= " WHERE code_melli IN ($in) ";
            
			$sql .= " GROUP BY t.code_melli, t.fname, t.lname ";


			$res = $this->db->query($sql)->result();

            $this->load->helper('phpexcel');

            $excel = new PHPExcel();
    
            $sheet = $excel->getSheet(0);
    
            $sheet->setTitle('لیست افراد');
    
            $sheet->setCellValueByColumnAndRow(0, 1, 'کد ملی');
            $sheet->setCellValueByColumnAndRow(1, 1, 'نام');
            $sheet->setCellValueByColumnAndRow(2, 1, 'نام خانوادگی');
            $sheet->setCellValueByColumnAndRow(3, 1, 'شماره نامه');
            $sheet->setCellValueByColumnAndRow(4, 1, 'تاریخ نامه');
            $sheet->setCellValueByColumnAndRow(5, 1, 'معرفی از');
            $sheet->setCellValueByColumnAndRow(6, 1, 'توضیحات');
            $sheet->setCellValueByColumnAndRow(7, 1, 'ملاحظات');
            $sheet->setCellValueByColumnAndRow(8, 1, 'معاونت');
    
            $row = 2;
            foreach ($res as $item) {
                $sheet->setCellValueByColumnAndRow(0, $row, $item->code_melli);
                $sheet->setCellValueByColumnAndRow(1, $row, $item->fname);
                $sheet->setCellValueByColumnAndRow(2, $row, $item->lname);
                $sheet->setCellValueByColumnAndRow(3, $row, $item->sh_letter);
                $sheet->setCellValueByColumnAndRow(4, $row, $item->date_letter);
                $sheet->setCellValueByColumnAndRow(5, $row, $item->moarefi);
                $sheet->setCellValueByColumnAndRow(6, $row, $item->tozih);
                $sheet->setCellValueByColumnAndRow(7, $row, $item->molahezat);
                $sheet->setCellValueByColumnAndRow(8, $row, $item->moavenat);

                $row++;
            }
    
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    
            $folder = FCPATH . 'files';
    
            if (!is_dir($folder))
                mkdir($folder);
    
            
            $name = 'names_' . date('YmdHis') . '_' . random_int(10000, 99999) . '.xlsx';

            $filename = $folder . '/' . $name;

            $writer->save($filename);
    
			$this->load->helper('file');
			$this->load->helper('download');

            force_download($name, file_get_contents($filename));
            //$writer = PHPExcel_IOFactory::

			/*$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
			$delimiter = ",";
			$newline = "\r\n";
			$enclosure = '"';
			$filename = 'report.csv';

			$data = $this->dbutil->csv_from_result($res, $delimiter, $newline, $enclosure);
			$data = chr(239) . chr(187) . chr(191) . $data;
			force_download($filename, $data);*/
			
		}



	}

	public function add() {
        $data = $_POST['data'];

        $data = json_decode($data);

        $tarikh = $this->getShamsiDate();
        $time = $this->getShamsiTime();

        $errors = [];
        $success = 0;

        foreach ($data as $key => $sheet) {
            //$sheet = $data->Worksheet;

            
            $index = 0;
            foreach ($sheet as $item) {
                $index++;

                if ($index == 1)
                    continue;

                $code  = $this->fixString(trim($item[0] ?? ''));
                $fname = $this->fixString(trim($item[1] ?? ''));
                $lname = $this->fixString(trim($item[2] ?? ''));
                $tozih = $this->fixString(trim($item[3] ?? ''));
                $sh    = $this->fixString(trim($item[4] ?? ''));
                $tarikh_name = $this->fixString(trim($item[5] ?? ''));
                $moarefi = $this->fixString(trim($item[6] ?? ''));
                $molahezat = $this->fixString(trim($item[7] ?? ''));
                $gender = $this->fixString(trim($item[8] ?? ''));
                $moavenat = $this->fixString(trim($item[9] ?? ''));

                

                if ($gender == 'خواهر' || $gender == 'زن')
                    $gender = 2;
                else
                    $gender = 1;

                if (empty($code)) {
                    array_push($errors, $code);
                    continue;
                }

                while (strlen($code) < 10)
                    $code = '0' . $code;

                $str = $tozih;

                preg_match("/نامه[ ]*(?<name>[0-9]+)/i", $str, $matches);

                //$sh = '';
                if (!empty($matches['name']) && empty($sh))
                    $sh = $matches['name'];
        
                $matches = null;
                preg_match("/(?<name>[0-9]+\/[0-9]+\/[0-9]+)/i", $str, $matches);
        
                $date = '';
                if (!empty($matches['name']))
                    $date = $matches['name'];

                if (empty($date)) {
                    $matches = null;
                    preg_match("/(?<name>[0-9]+\-[0-9]+\-[0-9]+)/i", $str, $matches);
            
                    $date = '';
                    if (!empty($matches['name']))
                        $date = str_replace('-', '/', $matches['name']);    
                }

                if (empty($tarikh_name))
                    $tarikh_name = $date;
        
                $tarikh_name = trim(str_replace('-', '', $tarikh_name));

                $matches = null;
                preg_match("/(?<name>معرفی از .*)/i", $str, $matches);
        
                $moarefi_ = '';
                if (!empty($matches['name']))
                    $moarefi_ = $matches['name'];

                if (empty($moarefi_)) {
                    $matches = null;
                    preg_match("/(?<name>معرفی .*)/i", $str, $matches);
            
                    if (!empty($matches['name']))
                        $moarefi_ = $matches['name'];
        
                }

                if (empty($moarefi) && !empty($moarefi_))
                    $moarefi = $moarefi_;



                $res = $this->db->query('SELECT id FROM tusers WHERE code_melli=?', [ $code ])->result();

                $id = 0;
                if (empty($res)) {
                    $sql = "INSERT INTO tusers (create_date, shamsi_date, shamsi_time, code_melli, fname, lname, gender)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $res = $this->db->query($sql, [ date('Y-m-d H:i:s'), $tarikh, $time, $code, $fname, $lname, $gender ]);

                    if ($res) {
                        $id = $this->db->insert_id();
                    }
                    else {
                        array_push($errors, $code);
                        continue;
                    }
                }
                else {
                    $res = $res[0];

                    $id = $res->id;
                }

                if (!empty($id)) {
                    $last = $this->db->query('SELECT id, moarefi FROM tuser_logs WHERE user_id=? ORDER BY id DESC LIMIT 1', [ $id ])->result();

                    $repeat = false;

                    if (!empty($last)) {
                        $last = $last[0];

                        if (trim($last->moarefi) == trim($moarefi)) {
                            $repeat = true;
                        }
                    }

                    if ($repeat == false) {
                        $sql = "INSERT INTO tuser_logs (user_id, create_date, shamsi_date, shamsi_time, tozih, sh_letter, date_letter, moarefi, molahezat, moavenat)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                        $res = $this->db->query($sql, [ $id, date('Y-m-d H:i:s'), $tarikh, $time, $tozih, $sh, $tarikh_name, $moarefi, $molahezat, $moavenat ]);

                        $success++;
                    }
                }


            }
        }

        echo json_encode([
            'result'    => 1,
            'success'   => $success,
            'errors'    => $errors
        ]);

		die();

	}

    public function add_from_list() {
        $this->mTitle = 'سامانه سریر (افزودن)';
        $this->mViewFile = 'main/add_from_list';
    }

    public function calc_string() {
        $this->mTitle = 'سامانه سریر';
        $this->mViewFile = 'main/calc_string';
    }
}
