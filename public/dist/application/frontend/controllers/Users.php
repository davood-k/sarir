<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

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
	public function index() {
		if (!hasView('users'))
			redirect('main');

		$this->mViewFile = 'users/index';
		$this->mTitle = 'مدیریت کاربران';

		/*$crud = generate_crud('tuser');
		$crud->set_subject("کاربر");
		$crud->where('DistUser', 1);

		$crud->columns('RoleID', 'FName', 'LName', 'Username');

		$crud->render();

		$id = $crud->getStateInfo();

		$user = null;
		if (!empty($id->primary_key)) {
			$user = $this->db->query('SELECT DefaultCountry, DefaultState FROM tuser WHERE ID=?', array(intval($id->primary_key)))->result();

			if (!empty($user))
				$user = $user[0];
		}*/

		$id = 0;
		for ($i = 0; $i < $this->uri->total_segments() + 1; $i++) {
			$seg = $this->uri->segment($i);

			if (intval($seg) != 0) {
				$id = intval($seg);
				break;
			}

		}

		$user = null;
		if (!empty($id)) {
			$user = $this->db->query('SELECT DefaultCountry, DefaultState FROM tuser WHERE ID=?', array(intval($id)))->result();

			if (!empty($user))
				$user = $user[0];
		}


		$crud = generate_crud('tuser');
		$crud->set_subject("کاربر");
		$crud->where('DistUser', 1);
		$crud->where('`tuser`.Deleted', 0);

		$crud->columns('RoleID', 'FName', 'LName', 'Username');
		$crud->display_as('AgentCode', 'شناسه معرف');
		$crud->display_as("FName","نام ");
		$crud->display_as('LName', 'نام خانوادگی');
		$crud->display_as('RoleID', 'نقش');
		$crud->display_as('Username', 'نام کاربری');
		$crud->display_as('Password', 'رمز ورود');
		$crud->display_as('DistAccess', 'دسترسی');
		$crud->display_as('Stores', 'دسترسی انبار');
		$crud->display_as('Groups', 'دسترسی گروه مشتری');
		$crud->display_as('DefaultCountry', 'کشور پیش فرض');
		$crud->display_as('DefaultState', 'استان پیش فرض');
		$crud->display_as('DefaultCity', 'شهر پیش فرض');
		$crud->display_as('Advisor', 'مشاور مقیم');
		$crud->display_as('EditOrderPrice', 'ویرایش قیمت سفارش');
		$crud->display_as('IsReseller', 'تامین کننده');
		$crud->display_as('ResellerStoreID', 'انبار مرتبط با تامین کننده');
		$crud->display_as('EditOrderUser', 'تغییر مشتری سفارش');
		$crud->display_as('EditAllOrders', 'دسترسی ویرایش همه سفارشات');
		$crud->display_as('SendNotif', 'ارسال نوتیفیکیشن');
		$crud->display_as('SepidarVisitorID', 'ویزیتور سپیدار');
		$crud->display_as('SalePortionPercent', 'درصد ویزیتور سپیدار');

		$crud->add_fields('RoleID', 'DistUser', 'UserID', 'AgentCode', 'FName', 'LName', 'Username', 'Password', 'Groups', 'Stores', 'DefaultCountry', 'DefaultState', 'DefaultCity', 'Advisor', 'IsReseller', 'ResellerStoreID', 'EditOrderPrice', 'EditOrderUser', 'EditAllOrders', 'SendNotif', 'SepidarVisitorID', 'SalePortionPercent', 'DistAccess');
		$crud->edit_fields('RoleID', 'AgentCode', 'FName', 'LName', 'Username', 'Groups', 'Stores', 'DefaultCountry', 'DefaultState', 'DefaultCity', 'Advisor', 'IsReseller', 'ResellerStoreID', 'EditOrderPrice', 'EditOrderUser', 'EditAllOrders', 'SendNotif', 'SepidarVisitorID', 'SalePortionPercent', 'DistAccess');
		$crud->required_fields('RoleID', 'UserID', 'FName', 'LName', 'Username');

		if ($this->User['RoleID'] != 1 && false)
			$crud->set_relation('RoleID', 'troles', 'Name', 'ID IN (3, 4)');
		else
			$crud->set_relation('RoleID', 'troles', 'Name');

		//$crud->set_rules('Username', 'نام کاربری', 'is_unique[tuser.Username.ID.4]');
		//$crud->set_rules('AgentCode', 'شناسه معرف', 'is_unique[tuser.AgentCode.ID.4]');

		$crud->field_type('UserID', 'hidden', $this->User['ID']);
		$crud->field_type('DistUser', 'hidden', 1);
		$crud->field_type('EditOrderPrice', 'true_false');
		$crud->field_type('EditAllOrders', 'true_false');
		$crud->field_type('EditOrderUser', 'true_false');
		$crud->field_type('IsReseller', 'true_false');
		$crud->field_type('SendNotif', 'true_false');
		$crud->set_relation('ResellerStoreID', 'tstores', 'Name', 'Deleted=0 AND Active=1');

		$crud->set_relation('DefaultCountry', 'tcountries', 'Name');
		$crud->set_relation('DefaultState', 'bstates', 'Name', 'CountryID=' . intval($user->DefaultCountry));
		$crud->set_relation('DefaultCity', 'bcities', 'Name', 'SID=' . intval($user->DefaultState));
		$crud->set_relation('Advisor', 'tcustomers', 'ShopName', 'Active=1 AND IsShop=1 AND Deleted=0');
		$crud->set_relation('SepidarVisitorID', 'tsepidar_visitors', "TitleShop");

        $results = $this->db->query("SELECT ID,  Name FROM tstores WHERE Deleted=0 AND Active=1")->result();
        $stores = array();

        foreach ($results as $result) {
            $stores[$result->ID] = $result->Name;
        }

        $crud->field_type('Stores', 'multiselect', $stores);

        $results = $this->db->query("SELECT ID,  Name FROM tgroups ")->result();
        $stores = array();

        foreach ($results as $result) {
            $stores[$result->ID] = $result->Name;
        }

        $crud->field_type('Groups', 'multiselect', $stores);
		
		$this->config->load('access');
		$config = $this->config->item('access');

		$access = array();
		for ($i = 0; $i < count($config); $i++) {
			$item = $config[$i];
			$item['CodeView'] = '#' . getCode($item['Name']) . '#';
			$item['CodeEdit'] = '#e' . getCode($item['Name']) . '#';
			$item['CodeAdd'] = '#a' . getCode($item['Name']) . '#';
			$item['CodeDelete'] = '#d' . getCode($item['Name']) . '#';
			$config[$i] = $item;

			//if (strpos($item['Access'], $this->User['RoleID']) !== FALSE)
				array_push($access, $item);
		}

		$Items['Access'] = $access;
		$Items['Name'] = 'DistAccess';

		
		$crud->field_type('DistAccess', 'access', $this->load->view('users/access', $Items, true));

		$crud->callback_before_insert(array($this,'callback_before_insert_user'));        
		$crud->callback_before_update(array($this,'callback_before_update_user'));    

		$crud->callback_after_insert(array($this,'callback_after_update_user'));        
		$crud->callback_after_update(array($this,'callback_after_update_user'));    

		if (hasEdit('users'))
			$crud->add_action('ویرایش رمز عبور', base_url('assets/img/lock1.png'), '', '', array($this,'user_change_password_action'), '');        

		if (!hasEdit('users')) {
			$crud->unset_edit();
		}

		if (!hasAdd('roles')) {
			$crud->unset_add();
		}

		if (!hasDel('roles')) {
			$crud->unset_delete();
		}

		$crud->callback_delete(array($this,'delete_user'));

		$this->mViewData['crud_data'] = $crud->render();   
		
	}

	function delete_user($primary_key) {
        return $this->db->update('tuser',array('Deleted' => '1'),array('ID' => $primary_key));
	}

    function user_change_password_action($primary_key , $row) {
        return "javascript: ChangeUserPassword($primary_key, '" . $row->FName . ' ' . $row->LName . "');";
    }

	public function change_password() {
		if (!hasEdit('users'))
			result(false, 'عدم دسترسی');
			
		$id = intval($_POST['id']);
		$password = $_POST['password'];

		if (!empty($id) && !empty($password)) {
			$this->db->update('tuser', array('Password' => hash_pw($password)), array('ID' => $id));

			result(true, 'رمز عبور با موفقیت تغییر کرد.');
		}

		result(false, 'اطلاعات ناقص');
	}

    function callback_before_insert_user($post_array) {
		$username = trim($post_array['Username']);

		if ($this->db->query('SELECT ID FROM tcustomers WHERE Username=? AND Deleted=0', array($username))->num_rows() > 0) 
			return false;

		$code = trim($post_array['AgentCode']);

		if (!empty($code)) {
			if ($this->db->query('SELECT ID FROM tuser WHERE AgentCode=? AND Deleted=0', array($code))->num_rows() > 0) 
				return false;
		}

		$post_array['DistAccess'] = encrypt($post_array['DistAccess']);

		if (!empty($post_array['Password']))
			$post_array['Password'] = hash_pw($post_array['Password']);

        return $post_array;
    }

    function callback_before_update_user($post_array, $primary_key) {
		$username = trim($post_array['Username']);

		if ($this->db->query('SELECT ID FROM tcustomers WHERE Username=? AND Deleted=0', array($username))->num_rows() > 0) 
			return false;

		$code = trim($post_array['AgentCode']);

		if (!empty($code)) {
			if ($this->db->query('SELECT ID FROM tuser WHERE AgentCode=? AND Deleted=0 AND ID<>?', array($code, $primary_key))->num_rows() > 0) 
				return false;
		}

		$post_array['DistAccess'] = encrypt($post_array['DistAccess']);

		if (!empty($post_array['Password']))
			$post_array['Password'] = hash_pw($post_array['Password']);

        return $post_array;
    }

	function callback_after_update_user($post_array, $primary_key) {
		$this->db->query('DELETE FROM tuser_stores WHERE UserID=?', array($primary_key));

		foreach ($post_array['Stores'] as $store) {
			$this->db->insert('tuser_stores', array(
				'UserID'	=> $primary_key,
				'StoreID'	=> $store
			));
		}

		$this->db->query('DELETE FROM tuser_access_groups WHERE UID=?', array($primary_key));

		foreach ($post_array['Groups'] as $store) {
			$this->db->insert('tuser_access_groups', array(
				'UID'	=> $primary_key,
				'GroupID'	=> $store
			));
		}
	}

	public function get_access_roles() {
		if (!hasView('users') && false)
			die();

        if (isset($_POST['ID'])) {
            $id = intval($_POST['ID']);

            $group = $this->db->query('SELECT Access FROM troles WHERE ID=' . $id)->result();
            $group = $group[0];

            echo decrypt($group->Access);
        }

        die();
    }

	public function roles() {
		if (!hasView('roles'))
			redirect('users');

		$this->mTitle = 'مدیریت نقش ها';
		$this->mViewFile = '_partial/crud';

		$crud = generate_crud('troles');
		$crud->set_subject("نقش");

		$crud->columns('Name');
		$crud->display_as("Name","نام ");
		$crud->display_as('Access', 'دسترسی');

		$crud->fields('Name', 'Access');
		$crud->required_fields('Name', 'Access');
		
		$this->config->load('access');
		$config = $this->config->item('access');

		for ($i = 0; $i < count($config); $i++) {
			$item = $config[$i];
			$item['CodeView'] = '#' . getCode($item['Name']) . '#';
			$item['CodeEdit'] = '#e' . getCode($item['Name']) . '#';
			$item['CodeAdd'] = '#a' . getCode($item['Name']) . '#';
			$item['CodeDelete'] = '#d' . getCode($item['Name']) . '#';
			$config[$i] = $item;
		}

		$Items['Access'] = $config;
		$Items['Name'] = 'Access';

		$crud->field_type('Access', 'access', $this->load->view('users/access', $Items, true));

		$crud->callback_before_insert(array($this,'callback_before_update_role'));        
		$crud->callback_before_update(array($this,'callback_before_update_role'));    


		/*if (!hasEdit('roles') || true) {
			$crud->unset_edit();
		}

		if (!hasAdd('roles') || true) {
			$crud->unset_add();
		}

		if (!hasDel('roles') || true) {
			$crud->unset_delete();
		}*/

		$this->mViewData['crud_data'] = $crud->render();                
	
	}

    function callback_before_update_role($post_array) {
        $post_array['Access'] = encrypt($post_array['Access']);

        return $post_array;
    }

}
