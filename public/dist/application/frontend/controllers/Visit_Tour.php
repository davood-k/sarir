<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visit_Tour extends MY_Controller {

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
		if (!hasView('visit_tour'))
			redirect('main');


		$this->mTitle = 'تور ویزیت حضوری';
		$this->mViewFile = 'visit_tour/index';

		$this->mViewData['Results'] = $this->db->query('SELECT ID, Title, Operation FROM ttour_call_results WHERE Type=2 AND Active=1 AND Deleted=0')->result();
		$this->mViewData['T1Day'] = $this->getShamsiDate(time() + (24 * 60 * 60));
		$this->mViewData['T2Day'] = $this->getShamsiDate(time() + (2 * 24 * 60 * 60));
		$this->mViewData['T3Day'] = $this->getShamsiDate(time() + (3 * 24 * 60 * 60));
		$this->mViewData['T4Day'] = $this->getShamsiDate(time() + (4 * 24 * 60 * 60));
	}

	public function get_list() {
		if (!hasView('visit_tour_admin'))
			die();

		$page = intval($_POST['current']);
        $count = intval($_POST['rowCount']);
        $key = escapeString($_POST['searchPhrase']);

		$sql = "SELECT t.ID, t.Name, t.StartTarikh, EndTarikh, c.Name AS Cycle, t.Cycle AS CycleID, t.Active, t.ListCount, t.VisitorCount, CONCAT(u.FName, ' ', u.LName) AS User ";
		$sql .= " FROM ttours t INNER JOIN ttour_cycles c ON(t.Cycle = c.ID) ";
		$sql .= " INNER JOIN tuser u ON (t.UserID = u.ID) ";
		$sql .= " WHERE t.Deleted=0 AND t.Type=2 ";

		if ($this->User['Admin'] == 0)
			$sql .= " AND t.UserID=" . $this->User['ID'] . ' ';

		if (!empty($key)) {
			$key = "'%$key%'";

			$sql .= " AND (t.Name LIKE $key OR c.Name LIKE $key) ";
		}

		$cnt = $this->db->query($sql)->num_rows();

        if (isset($_POST['sort'])) {
            $sort = ($_POST['sort']);

            $sql .= "ORDER BY ";

            $first = true;
            foreach ($sort as $key => $value) {
                if ($first)
                    $first = false;
                else
                    $sql .= ",";

                $sql .= $key . ' ' . $value . ' ';
            }
        }
        else {
            $sql .= " ORDER BY t.ID DESC ";
        }

        if ($count > 0)
            $sql .= "LIMIT " . (($page - 1) * $count) . ',' . $count;


        $data = array(
            'current' => $page,
            'rowCount' => $count,
            'rows' => $this->db->query($sql)->result(),
            'total' => $cnt
            );

        echo json_encode($data);
        die();

	}

	public function save() {
		$id = intval($_POST['id']);

		if (($id == 0 && !hasAdd('visit_tour_admin')) || ($id != 0 && !hasEdit('visit_tour_admin')))
			result(false, 'دسترسی به این گزینه را ندارید.');

		if (!empty($_POST['name']) && !empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['cycle'])) {
			$name = escapeString($_POST['name']);
			$start = escapeString($_POST['start']);
			$end = escapeString($_POST['end']);
			$cycle = intval($_POST['cycle']);

			if ($start >= $end)
				result(false, 'تاریخ شروع باید قبل از تاریخ پایان باشد.');

			if ($start < $this->getShamsiDate() || $end < $this->getShamsiDate())
				result(false, 'تاریخ شروع و پایان باید از تاریخ امروز به بعد باشد.');

			if ($this->db->query('SELECT ID FROM ttour_cycles WHERE ID=?', array($cycle))->num_rows() == 0)
				result(false, 'بازه زمانی تعریف نشده است.');


			$data = array(
				'Type'	    => 2,
				'Name'	    => $name,
				'StartDate' => $this->ShamsiToMiladi($start),
				'EndDate'	=> $this->ShamsiToMiladi($end),
				'StartTarikh'=> $start,
				'EndTarikh'	=> $end,
				'Cycle'		=> $cycle,
				'StartCycle'=> $start
			);

			if ($id == 0) {
				$data['Active'] = 0;
				$data['UserID'] = $this->User['ID'];
				$this->db->insert('ttours', $data);

				result(true, 'تور ویزیت ایجاد شد. ');
			}
			else {
				$this->db->update('ttours', $data, array('ID' => $id));

				result(true, 'تور ویزیت ویرایش شد.');
			}

		}

		result(false, 'اطلاعات ورودی اشتباه می باشد.');

	}

	public function toggle_active() {
		if (!hasEdit('visit_tour_admin')) 
			result(false, 'دسترسی به این گزینه ندارید.');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$res = $this->db->query('SELECT ID, Active, ListCount, VisitorCount FROM ttours WHERE ID=? AND Deleted=0 AND Type=2', array($id))->result();

			if (empty($res))
				result(false, 'موردی پیدا نشد.');

			$res = $res[0];

			if ($res->VisitorCount == 0)
				result(false, 'اطلاعات تور تکمیل نشده است.');

			$this->db->update('ttours', array('Active' => !$res->Active), array('ID' => $id));

			result(true, 'عملیات انجام شد.');
		}

		result(false, 'عدم دسترسی');
	}

	public function delete_tour() {
		if (!hasEdit('tel_tour_admin')) 
			result(false, 'دسترسی به این گزینه ندارید.');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$res = $this->db->query('SELECT ID, Active, ListCount, VisitorCount FROM ttours WHERE ID=? AND Type=2 AND Deleted=0', array($id))->result();

			if (empty($res))
				result(false, 'موردی پیدا نشد.');

			unset($res);

			$this->db->update('ttours', array('Deleted' => 1), array('ID' => $id));

			result(true, '');
		}


		result(false, 'اطلاعات ورودی اشتباه می باشد.');
	}

	public function get_tour_users() {
		if (!hasEdit('visit_tour_admin')) 
			result(false, 'دسترسی به این گزینه ندارید.');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$res = $this->db->query('SELECT ID, Active, ListCount, VisitorCount FROM ttours WHERE ID=? AND Deleted=0 AND Type=2', array($id))->result();

			if (empty($res))
				result(false, 'موردی پیدا نشد.');

			unset($res);

			$users = $this->db->query("SELECT u.ID, CONCAT(u.FName, ' ', u.LName) AS Name, t.Name AS Team, IFNULL(v.TourID, 0) AS Tour, IFNULL(v.ID, 0) AS TVID, IFNULL(v.Customers, 0) AS Customers FROM tuser u INNER JOIN tteam_users tm ON (u.ID = tm.UserID) INNER JOIN tteams t ON (t.ID = tm.TeamID) LEFT OUTER JOIN ttour_visitors v ON (v.VisitorID=u.ID AND v.State=1 AND v.TourID=?) WHERE u.Active=1 AND u.Deleted=0 AND u.RoleID IN (4, 9)", array($id))->result();

			foreach ($users as $item) {
				$res = $this->db->query('SELECT CustomerID FROM ttour_visitor_customers WHERE TourID=? AND VisitorID=? AND State=1', array($id, $item->ID))->result();

				$t = '';

				foreach ($res as $i) {
					if (!empty($t))
						$t .= ',';

					$t .= $i->CustomerID;
				}

				$item->List = $t;

				$item->Selected = $this->db->query('SELECT t.ID, t.GroupID, t.FName, t.LName, t.ShopName, t.Tel, t.Phone, t.Address1, t.Lat, t.Lng, t.OrdersCount, IFNULL(tt.ID, 0) AS TourID, tvc.Radif FROM tcustomers t INNER JOIN ttour_visitor_customers tvc ON (t.ID = tvc.CustomerID AND tvc.State=1 AND tvc.VisitorID=' . $item->ID . ' AND tvc.TourID=' . $id . ') INNER JOIN ttour_visitors tv ON (tv.ID = tvc.TourVisitorID AND tv.State=1) INNER JOIN ttours tt ON (tv.TourID = tt.ID) WHERE t.Lat<>0 AND t.Lng<>0 AND t.Deleted=0 AND t.Active=1 AND t.IsShop=1 ')->result();
			}

			$this->mViewData['Users'] = $users;

			result(true, '', array (
				'Users' => $users,
				'Html' => $this->load->view('visit_tour/tour_users', $this->mViewData, true)));
		}

		result(false, 'اطلاعات ورودی اشتباه می باشد.');
	}

	public function get_customers() {
		if (!hasView('visit_tour') && !hasView('visit_tour_admin'))
			redirect('main');

		$ids = escapeString($_POST['ids']);

		if (empty($ids))
			$ids = '-1';
		
		$areas = escapeString($_POST['areas']);
		$vid = intval($_POST['vid']);
		$app = intval($_POST['app']);

		$res = $this->db->query('SELECT t.ID, t.GroupID, t.FName, t.LName, t.ShopName, t.Tel, t.Phone, t.Address1, t.Lat, t.Lng, t.OrdersCount, IFNULL(tt.ID, 0) AS TourID, IFNULL(tvc.Radif, 0) AS Radif FROM tcustomers t LEFT OUTER JOIN ttour_visitor_customers tvc ON (t.ID = tvc.CustomerID AND tvc.State=1 AND tvc.VisitorID=' . $vid . ') LEFT OUTER JOIN ttour_visitors tv ON (tv.ID = tvc.TourVisitorID AND tv.State=1) LEFT OUTER JOIN ttours tt ON (tv.TourID = tt.ID AND tt.Deleted=0 AND tt.StartDate<NOW() AND tt.EndDate>NOW()) LEFT OUTER JOIN tapp_logins l ON (t.ID = l.CustomerID) WHERE t.Lat<>0 AND t.Lng<>0 AND t.Deleted=0 AND t.Active=1 AND t.IsShop=1 ' . (!empty($ids) ? ' AND t.GroupID IN (' . $ids . ')' : '') . (empty($areas) ? '' : ' AND t.Area IN (' . $areas . ')') . ($app == 1 ? ' AND IFNULL(l.ID, 0)<>0' : ($app == 2 ? ' AND IFNULL(l.ID, 0)=0 ' : '')))->result();

		$data = array();
		$data['Customers'] = $res;

		result(true, '', $data);

	}

	public function get_list_customers() {
		if (!hasView('visit_tour') && !hasView('visit_tour_admin'))
			redirect('main');

		$page = intval($_POST['current']);
		$count = intval($_POST['rowCount']);
		$key = escapeString($_POST['searchPhrase']);
		$cid = intval($_POST['cid']);
		$oid = intval($_POST['oid']);	
		$ids = escapeString($_POST['ids']);

		if (empty($ids))
			$ids = '-1';
		
		$areas = escapeString($_POST['areas']);
		$vid = intval($_POST['vid']);

		$sql = 'SELECT t.ID, t.GroupID, g.Name AS `Group`, IFNULL(a.Name, \'\') AS Area, t.FName, t.LName, t.ShopName, t.Tel, t.Phone, t.Address1, t.Lat, t.Lng, t.OrdersCount, IFNULL(tt.ID, 0) AS TourID, IFNULL(tvc.Radif, 0) AS Radif, IFNULL(CONCAT(p.FName, \' \', p.LName), \'\') AS Malek FROM tcustomers t LEFT OUTER JOIN tgroups g ON (t.GroupID = g.ID) LEFT OUTER JOIN bareas a ON (t.Area = a.ID) LEFT OUTER JOIN tcustomer_persons p ON (t.MalekID = p.ID) LEFT OUTER JOIN ttour_visitor_customers tvc ON (t.ID = tvc.CustomerID AND tvc.State=1 AND tvc.VisitorID=' . $vid . ') LEFT OUTER JOIN ttour_visitors tv ON (tv.ID = tvc.TourVisitorID AND tv.State=1) LEFT OUTER JOIN ttours tt ON (tv.TourID = tt.ID AND tt.Deleted=0 AND tt.StartDate<NOW() AND tt.EndDate>NOW()) WHERE t.Lat<>0 AND t.Lng<>0 AND t.Deleted=0 AND t.Active=1 AND t.IsShop=1 ' . (!empty($ids) ? ' AND t.GroupID IN (' . $ids . ')' : '') . (empty($areas) ? '' : ' AND t.Area IN (' . $areas . ')');

		if (!empty($key)) {
			$key = "'%$key%'";

			$sql .= " AND (t.ShopName LIKE $key) ";
		}

		$cnt = $this->db->query($sql)->num_rows();

        if (isset($_POST['sort'])) {
            $sort = ($_POST['sort']);

            $sql .= "ORDER BY ";

            $first = true;
            foreach ($sort as $key => $value) {
                if ($first)
                    $first = false;
                else
                    $sql .= ",";

                $sql .= $key . ' ' . $value . ' ';
            }
        }
        else {
            $sql .= " ORDER BY t.ID DESC ";
        }

        if ($count > 0)
            $sql .= "LIMIT " . (($page - 1) * $count) . ',' . $count;


        $data = array(
            'current' => $page,
            'rowCount' => $count,
            'rows' => $this->db->query($sql)->result(),
            'total' => $cnt
            );

        echo json_encode($data);
        die();
	}

	public function get_tours() {
		if (!hasView('visit_tour'))
			result(false, 'دسترسی به این قسمت را ندارید.');

		$tours = get_visit_tours($this->User['ID']);
		

		result(true, '', $tours);
	}

	public function get_tour() {
		if (!hasView('visit_tour'))
			result(false, 'دسترسی به این قسمت را ندارید.');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$sql = "SELECT t.ID, t.Name, t.ListCount, t.StartTarikh, t.EndTarikh, c.Name AS Cycle ";
			$sql .= " FROM ttours t INNER JOIN ttour_cycles c ON (t.Cycle = c.ID) ";
			$sql .= " INNER JOIN ttour_visitors v ON (v.TourID=t.ID AND v.VisitorID=?) ";
			$sql .= " WHERE t.StartTarikh<=? AND t.EndTarikh>=? AND t.Type=2 AND t.Active=1 AND t.Deleted=0 AND t.ID=?";
	
			$tours = $this->db->query($sql, array($this->User['ID'], $this->getShamsiDate(), $this->getShamsiDate(), $id))->result();

			if (empty($tours))
				result(false, 'تور ویزیت پیدا نشد.');

			$tour = $tours[0];

			$this->mViewData['Tour'] = $tour;

			$res = $this->db->query('SELECT c.ID AS CustomerID, c.OrdersCount, c.ShopName, c.Lat, c.Lng, tvc.Visited, tvc.Peigiri, tvc.ID, tvc.Sell, tvc.SabtVisit, c.OrdersCount, c.Tel, c.Phone, IFNULL(CONCAT(p.FName, \' \', p.LName), \'\') AS Malek, PeigiriTarikh, PeigiriTime, 2 AS Type  FROM ttour_visitor_customers tvc INNER JOIN tcustomers c ON (tvc.CustomerID = c.ID AND c.Active=1 AND c.Deleted=0) LEFT OUTER JOIN tcustomer_persons p ON (c.MalekID = p.ID) WHERE tvc.TourID=? AND tvc.VisitorID=? AND tvc.State IN (1, 3) ORDER BY tvc.Radif ASC', array($id, $this->User['ID']))->result();

			foreach ($res as $item) {
				$item->LastMonthOrders = $this->db->query('SELECT ID FROM torders WHERE UID=? AND Paid=1 AND Verify=1 AND ShamsiDate>=?', array($item->CustomerID, $this->mViewData['D1Mah']))->num_rows(); 
			}

			$this->mViewData['Customers'] = $res;

			result(true, '', array(
				'Tour'	=> $tour,
				'Customers' => $res
			));
			
		}

		result(false, 'اطلاعات کافی نمی باشد.');
	}

	public function set_location() {
		if (!hasView('visit_tour'))
			result(false, '');

		if (!empty($_POST['lat']) && !empty($_POST['lng'])) {
			$id = intval($_POST['id']);
			$lat = floatval($_POST['lat']);
			$lng = floatval($_POST['lng']);

			$this->db->insert('tvisitor_locations', array(
				'VisitorID'	=> $this->User['ID'],
				'TourID'	=> $id,
				'CreateDate' => date('Y-m-d H:i:s'),
				'ShamsiDate' => $this->getShamsiDate(),
				'Lat'		=> $lat,
				'Lng'		=> $lng
			));

			result(true, '');
		}

		result(false, '');
	}

	public function orders() {
		if (!hasView('tel_tour'))
			redirect('main');

		$this->mTitle = 'پیش فاکتورها';
		$this->mViewFile = 'tel_tour/orders';
	}

	public function get_history_of_visits() {
		if (!hasView('visit_tour') && $this->User['Distribute'] == 0)
			die();

		$page = intval($_POST['current']);
        $count = intval($_POST['rowCount']);
		$key = escapeString($_POST['searchPhrase']);
		$cid = intval($_POST['cid']);
		$oid = intval($_POST['oid']);

		$sql = "SELECT c.ID, IFNULL(t.Name, '') AS Tour, CONCAT(u.FName, ' ', u.LName) AS Visitor, CONCAT(c.CallTarikh, ' ', c.CallTime) AS Tarikh, r.Title AS Result, c.Tozihat ";
		$sql .= " FROM ttour_calls c ";
		$sql .= " INNER JOIN tuser u ON (c.VisitorID = u.ID) ";
		$sql .= " INNER JOIN ttour_call_results r ON (c.Result = r.ID) ";
		$sql .= " LEFT OUTER JOIN ttours t ON (c.TourID = t.ID) ";
		$sql .= " WHERE c.CustomerID=? AND c.Type=2 ";

		if (!empty($oid))
			$sql .= " AND c.OrderID=" . $oid . ' ';

		if (!empty($key)) {
			$key = "'%$key%'";

			$sql .= " AND (t.Name LIKE $key OR u.FName LIKE $key OR u.LName LIKE $key OR r.Title LIKE $key) ";
		}

		$cnt = $this->db->query($sql, array($cid))->num_rows();

        if (isset($_POST['sort'])) {
            $sort = ($_POST['sort']);

            $sql .= "ORDER BY ";

            $first = true;
            foreach ($sort as $key => $value) {
                if ($first)
                    $first = false;
                else
                    $sql .= ",";

                $sql .= $key . ' ' . $value . ' ';
            }
        }
        else {
            $sql .= " ORDER BY c.ID DESC ";
        }

        if ($count > 0)
            $sql .= "LIMIT " . (($page - 1) * $count) . ',' . $count;


        $data = array(
            'current' => $page,
            'rowCount' => $count,
            'rows' => $this->db->query($sql, array($cid))->result(),
            'total' => $cnt
            );

        echo json_encode($data);
        die();

	}
	
	public function admin() {
		if (!hasView('visit_tour_admin'))
			redirect('main');

		$this->mViewFile = 'visit_tour/admin';
		$this->mTitle = 'مدیریت تورهای ویزیت حضوری';

		$this->mViewData['Cycles'] = $this->db->query('SELECT ID, Name FROM ttour_cycles ORDER BY Days')->result();
		$this->mViewData['Groups'] = $this->db->query('SELECT ID, Name FROM tgroups')->result();

		$global_filters = $this->config->item('global_filters');
		$this->mViewData['Filters'] = array(
			'visit_tour_area'	=> $global_filters['visit_tour_area']
		);
		/*$this->mViewData['Teams'] = $this->db->query('SELECT ID, Name, Users FROM tteams t WHERE Type=1 AND Active=1 AND Deleted=0 AND NOT EXISTS(SELECT ID FROM ttours WHERE TeamID=t.ID) ')->result();*/
	}

	public function get_path() {
		if (!hasEdit('visit_tour_admin'))
			result(false, 'دسترسی به این گزینه را ندارید.');

		$id = intval($_POST['id']);
		$tarikh = escapeString($_POST['tarikh']);

		if (!empty($id)) {
			if (empty($tarikh))
				$tarikh = $this->getShamsiDate();

			$res = $this->db->query('SELECT Lat, Lng, CreateDate, ShamsiDate FROM tvisitor_locations WHERE VisitorID=? AND ShamsiDate=? ORDER BY CreateDate DESC', array($id, $tarikh))->result();

			result(true, '', $res);
		}

		result(false, '');
	}

	public function change_color() {
		if (!hasEdit('visit_tour_admin'))
			result(false, 'دسترسی به این گزینه را ندارید.');

		$id = intval($_POST['id']);
		$color = escapeString($_POST['color']);

		if (!empty($id) && !empty($color)) {
			$color = str_replace('#', '', $color);

			$this->db->update('tuser', array('LineColor' => $color), array('ID' => $id));

			result(true, '');
		}

		result(false, '');
	}

	public function save_users() {
		if (!hasEdit('visit_tour_admin')) 
			result(false, 'دسترسی به این گزینه ندارید.');

		$id = intval($_POST['id']);
		$users = json_decode($_POST['users']);

		if (!empty($id) && is_array($users)) {
			$res = $this->db->query('SELECT ID, Active, ListCount, VisitorCount FROM ttours WHERE ID=? AND Deleted=0', array($id))->result();

			if (empty($res))
				result(false, 'موردی پیدا نشد.');

			unset($res);

			$this->db->trans_start();
			$ids = '';
			$lids = '';

			foreach ($users as $user) {
				$u = $this->db->query("SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE ID=? AND Active=1 AND Deleted=0 AND RoleID=4", array($user->id))->result();

				if (empty($u)) {
					unset($u);
					result(false, 'ویزیتور پیدا نشد.');
				}

				$u = $u[0];


				$team = $this->db->query('SELECT TeamID FROM tteam_users WHERE UserID=?', array($user->id))->result();

				if (empty($team)) {
					$name = $u->Name;
					unset($u);
					unset($team);
					result(false, 'تیم عملیاتی ویزیتور "' . $name . '"  مشخص نشده است.');
				}

				$team = $team[0];

				$res = $this->db->query('SELECT ID, State FROM ttour_visitors WHERE TourID=? AND VisitorID=?', array($id, $user->id))->result();

				$toinsert = false;
				$iid = 0;
				if (empty($res)) {
					$toinsert = true;
					
					$this->db->insert('ttour_visitors', array(
						'UserID'		=> $this->User['ID'],
						'TourID'		=> $id,
						'TeamID'		=> $team->TeamID,
						'VisitorID'		=> $user->id,
						'JoinDate'		=> date('Y-m-d H:i:s'),
						'LeftDate'		=> '2012-01-01',
						'JoinTarikh'	=> $this->getShamsiDate(),
						'State'			=> 1
					));

					$iid = $this->db->insert_id();
				}
				else {
					$r = $res[0];
					$iid = $r->ID;

					if ($r->State != 1) {
						$toinsert = true;
						$this->db->update('ttour_visitors', array('State' => 1, 'JoinDate' => date('Y-m-d H:i:s'), 'JoinTarikh' => $this->getShamsiDate()), array('ID' => $r->ID));

					}
				}

				if ($ids != '')
					$ids .= ',';

				$ids .= $iid;


				if ($toinsert) {
					$this->db->insert('ttour_visitor_logs', array(
						'UserID'	=> $this->User['ID'],
						'TourID'	=> $id,
						'TeamID'    => $team->TeamID,
						'VisitorID'	=> $user->id,
						'CreateDate' => date('Y-m-d H:i:s'),
						'State'		=> 1,
						'RecordID'	=> $iid
					));
				}

				$lists = explode(',', $user->lists);
				$radif = 1;
				foreach ($lists as $list) {
					$list = intval($list);

					if (empty($list))
						continue;

					$res = $this->db->query('SELECT t.ID, t.FName, t.LName, t.ShopName, t.Tel, t.Phone, t.Address1, t.Lat, t.Lng, t.OrdersCount, IFNULL(tt.ID, 0) AS TourID, IFNULL(tvc.VisitorID, 0) AS Visitor FROM tcustomers t LEFT OUTER JOIN ttour_visitor_customers tvc ON (t.ID = tvc.CustomerID AND tvc.State=1) LEFT OUTER JOIN ttour_visitors tv ON (tv.ID = tvc.TourVisitorID AND tv.State=1) LEFT OUTER JOIN ttours tt ON (tv.TourID = tt.ID AND tt.Active=1 AND tt.Deleted=0 AND tt.StartDate<NOW() AND tt.EndDate>NOW()) WHERE t.Deleted=0 AND t.Active=1 AND t.IsShop=1 AND t.ID=?', array($list))->result();

					if (empty($res))
						result(false, 'مشتری مورد نظر پیدا نشد.');

					$res = $res[0];

					if ($res->TourID > 0 && $res->TourID != $id)
						result(false, 'مشتری "' . $res->ShopName . '" مربوط به تور ویزیت دیگری می باشد.');

					if ($res->TourID > 0 && $res->TourID == $id && $res->Visitor != $user->id)
						result(false, 'مشتری "' . $res->ShopName . '" مربوط به ویزیتور دیگری می باشد.');
					

					$item = $this->db->query('SELECT ID, TourID, VisitorID, Radif, State, TourvisitorID, CustomerID FROM ttour_visitor_customers WHERE TourID=? AND TourVisitorID=? AND CustomerID=?', array($id, $iid, $list))->result();

					$toinsert = false;
					$liid = 0;
					if (empty($item)) {
						$toinsert = true;

						$this->db->insert('ttour_visitor_customers', array(
							'UserID'		=> $this->User['ID'],
							'TourID'		=> $id,
							'TourVisitorID'	=> $iid,
							'CustomerID'	=> $list,
							'VisitorID'		=> $user->id,
							'Radif'			=> $radif++,
							'JoinDate'		=> date('Y-m-d H:i:s'),
							'LeftDate'		=> '2012-01-01',
							'JoinTarikh'	=> $this->getShamsiDate(),
							'State' 		=> 1
						));

						$liid = $this->db->insert_id();
					}
					else {
						$item = $item[0];
						$liid = $item->ID;

						if ($item->State != 1) {
							$toinsert = true;

							$this->db->update('ttour_visitor_customers', array('JoinDate' => date('Y-m-d H:i:s'), 'JoinTarikh' => $this->getShamsiDate(), 'State' => 1, 'Radif' => $radif++), array('ID' => $item->ID));
						}
						else {
							$this->db->update('ttour_visitor_customers', array('Radif' => $radif++), array('ID' => $item->ID));
						}
					}

					if ($lids != '')
						$lids .= ',';

					$lids .= $liid;

					if ($toinsert) {
						$this->db->insert('ttour_visitor_customer_logs', array(
							'UserID'	=> $this->User['ID'],
							'TourID'	=> $id,
							'VisitorID' => $user->id,
							'CustomerID'	=> $list,
							'CreateDate'=> date('Y-m-d H:i:s'),
							'RecordID'	=> $liid,
							'State'		=> 1
						));
					}

					unset($item);
				}


				unset($res);
				unset($u);
				unset($team);
			}

			if (!empty($ids)) {
				$res = $this->db->query('SELECT ID, TeamID, VisitorID FROM ttour_visitors WHERE TourID=? AND State=1 AND ID NOT IN (' . $ids . ')', array($id))->result();

				foreach ($res as $item) {
					$this->db->update('ttour_visitors', array('LeftDate' => date('Y-m-d H:i:s'), 'LeftTarikh' => $this->getShamsiDate(), 'State' => 2), array('ID' => $item->ID));

					$this->db->insert('ttour_visitor_logs', array(
						'UserID'	=> $this->User['ID'],
						'TourID'	=> $id,
						'TeamID'    => $item->TeamID,
						'VisitorID'	=> $item->VisitorID,
						'CreateDate' => date('Y-m-d H:i:s'),
						'State'		=> 2,
						'RecordID'	=> $item->ID
					));

					$lists = $this->db->query('SELECT ID, TourID, TourVisitorID, VisitorID, CustomerID FROM ttour_visitor_customers WHERE TourID=? AND State=1 AND TourVisitorID=?', array($id, $item->ID))->result();

					foreach ($lists as $list) {
						$this->db->update('ttour_visitor_customers', array('LeftDate' => date('Y-m-d H:i:s'), 'LeftTarikh' => $this->getShamsiDate(), 'State' => 2), array('ID' => $list->ID));
						
						$this->db->insert('ttour_visitor_customer_logs', array(
							'UserID'	=> $this->User['ID'],
							'TourID'	=> $id,
							'VisitorID' => $list->VisitorID,
							'CustomerID'	=> $list->CustomerID,
							'CreateDate'=> date('Y-m-d H:i:s'),
							'RecordID'	=> $list->ID,
							'State'		=> 2
						));

					}

					unset($lists);
				}

				unset($res);
			}

			if (!empty($lids)) {
				$res = $this->db->query('SELECT ID, VisitorID, TourVisitorID, CustomerID FROM ttour_visitor_customers WHERE TourID=? AND State=1 AND ID NOT IN (' . $lids . ')', array($id))->result();

				foreach ($res as $item) {
					$this->db->update('ttour_visitor_customers', array('LeftDate' => date('Y-m-d H:i:s'), 'LeftTarikh' => $this->getShamsiDate(), 'State' => 2), array('ID' => $item->ID));

					$this->db->insert('ttour_visitor_customer_logs', array(
						'UserID'	=> $this->User['ID'],
						'TourID'	=> $id,
						'VisitorID'	=> $item->VisitorID,
						'CustomerID'=> $item->CustomerID,
						'CreateDate' => date('Y-m-d H:i:s'),
						'State'		=> 2,
						'RecordID'	=> $item->ID
					));

				}

				unset($res);
			}

			foreach ($this->db->query('SELECT ID, TourID, TeamID, VisitorID FROM ttour_visitors WHERE TourID=? AND State=1', array($id))->result() as $item) {
				$cnt = $this->db->query('SELECT ID FROM ttour_visitor_customers WHERE TourVisitorID=? AND State=1', array($item->ID))->num_rows();

				$this->db->update('ttour_visitors', array('Customers' => $cnt), array('ID' => $item->ID));
			}

			$this->db->query('UPDATE ttours t SET VisitorCount=(SELECT IFNULL(COUNT(*), 0) FROM ttour_visitors t1 WHERE t1.TourID=? AND State=1) WHERE t.ID=?', array($id, $id));

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				result(false, 'عملیات با خطا متوقف شد.');
			else 
				result(true, 'اطلاعات با موفقیت ذخیره شد.');

		}

		result(false, 'اطلاعات ورودی اشتباه می باشد.');
	}

	public function get_visit_details() {
		if (!hasView('visit_tour_admin'))
			result(false, 'عدم دسترسی');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$res = $this->db->query('SELECT t.ID, t.TourID, t.VisitorID, t.UserID, t.CustomerID, t.Visited, t.Peigiri, t.Sell, t.SabtVisit, t.VisitTarikh, t.VisitTime, c.ShopName FROM ttour_visitor_customers t INNER JOIN tcustomers c ON (t.CustomerID = c.ID) WHERE t.ID=?', array($id))->result();

			if (empty($res)) 
				result(false, 'اطلاعات پیدا نشد.');

			$res = $res[0];

			$order = $this->db->query('SELECT ID, CreateDate, ShamsiDate, AllPrice FROM torders WHERE UserID=? AND VisitID=? ORDER BY ID DESC LIMIT 1 ', array($res->VisitorID, $id))->result();

			if (!empty($order))
				$order = $order[0];

			$visit = $this->db->query('SELECT t.ID, t.CallTarikh, t.CallTime, r.Title FROM ttour_calls t INNER JOIN ttour_call_results r ON (t.Result = r.ID) WHERE CallListID=? ORDER BY ID DESC LIMIT 1', array($id))->result();

			if (!empty($visit))
				$visit = $visit[0];

			$this->mViewData['Order'] = $order;
			$this->mViewData['Visit'] = $visit;
			$this->mViewData['Data']  = $res;

			result(true, '', $this->load->view('visit_tour/visit_details', $this->mViewData, true));
		}

		result(false, 'اطلاعات ناقص');
	}

	public function get_tour_map_data() {
		if (!hasView('visit_tour_admin'))
			result(false, 'عدم دسترسی');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$tour = $this->db->query('SELECT t.ID, t.Name, t.StartCycle, t.CycleCount, t.VisitorCount, c.Name AS Cycle, t.StartTarikh, t.EndTarikh, c.Days AS TotalDays FROM ttours t INNER JOIN ttour_cycles c ON (t.Cycle = c.ID) WHERE t.ID=? AND t.Type=2 AND t.UserID=?', array($id, $this->User['ID']))->result();

			if (empty($tour))
				result(false, 'دسترسی به این تور ویزیت را ندارید.');

			$tour = $tour[0];

			$tour->Days = $this->DiffTarikh($tour->StartCycle, $this->getShamsiDate());
			$tour->TotalDays = intval($tour->TotalDays);

			$visitors = $this->db->query('SELECT t.ID, t.TeamID, t.VisitorID, t.Customers, t.Visited, t.Peigiri, t.Sell, CONCAT(u.FName, \' \', u.LName) AS Visitor, m.Name, u.LineColor FROM ttour_visitors t INNER JOIN tuser u ON (t.VisitorID = u.ID) INNER JOIN tteams m ON (t.TeamID = m.ID) WHERE t.TourID=? AND t.State=1', array($id))->result();

			foreach ($visitors as $item) {
				$sell = $this->db->query('SELECT IFNULL(SUM(AllPrice), 0) AS Sell FROM torders WHERE UserID=? AND ShamsiDate>=?', array($item->VisitorID, $tour->StartCycle))->result();

				$sell = $sell[0];
				$item->TotalSell = intval($sell->Sell);

				if (empty($item->LineColor)) {
					$color = $this->Colors[rand(0, count($this->Colors))];

					$this->db->update('tuser', array('LineColor' => $color), array('ID' => $item->VisitorID));
					$item->LineColor = $color;
				}

				$location = $this->db->query('SELECT Lat, Lng, ShamsiDate, CreateDate FROM tvisitor_locations WHERE VisitorID=? AND ShamsiDate=? ORDER BY CreateDate DESC LIMIT 1', array($item->VisitorID, $this->getShamsiDate()))->result();

				$item->Location = null;
				if (!empty($location)) {
					$location = $location[0];

					$item->Location = array(
						'Lat'	=> $location->Lat,
						'Lng'	=> $location->Lng
					);
				}
			}



			$customers = $this->db->query('SELECT t.ID, t.VisitorID, t.Radif, t.CustomerID, t.Visited, t.Peigiri, t.Sell, t.SabtVisit, t.Result, t.VisitTarikh, t.VisitTime, t.Tozihat, c.ShopName, CONCAT(u.FName, \' \', u.LName) AS Visitor, tv.TeamID, m.Name AS Team, c.Lat, c.Lng, c.OrdersCount FROM ttour_visitor_customers t INNER JOIN tcustomers c ON (t.CustomerID = c.ID) INNER JOIN tuser u ON (t.VisitorID = u.ID) INNER JOIN ttour_visitors tv ON (t.TourVisitorID = tv.ID) INNER JOIN tteams m ON (tv.TeamID = m.ID) WHERE t.TourID=? AND t.State IN (1, 3) AND tv.State=1 ORDER BY Radif', array($id))->result();

			foreach ($customers as $item) {
				$item->LastMonthOrders = $this->db->query('SELECT ID FROM torders WHERE UID=? AND Paid=1 AND Verify=1 AND ShamsiDate>=?', array($item->CustomerID, $this->mViewData['D1Mah']))->num_rows(); 
			}


			result(true, '', array(
				'Tour'	=> $tour,
				'Visitors' => $visitors,
				'Customers'=> $customers
			));
		}

		result(false, 'اطلاعات ناقص می باشد');
	}

	public function get_locations() {
		if (!hasView('visit_tour_admin')) 
			result(false, 'عدم دسترسی');

		$id = escapeString($_POST['ids']);

		if (!empty($id)) {
			$ids = '';

			$d = explode(',', $id);

			foreach ($d as $item) {
				if (!empty($ids))
					$ids .= ',';
				
				$ids .= $item;
			}

			$users = $this->db->query("SELECT ID, CONCAT(FName, ' ', LName) AS Name FROM tuser WHERE ID IN ($ids)")->result();

			foreach ($users as $user) {
				$res = $this->db->query('SELECT t.Lat, t.Lng, CONCAT(t.ShamsiDate, \' \', SUBSTR(t.CreateDate, 12)) AS Tarikh FROM tvisitor_locations t  WHERE t.VisitorID=? ORDER BY CreateDate DESC LIMIT 1', array($user->ID))->result();

				if (!empty($res)) {
					$res = $res[0];

					$user->Location = $res;
				}
			}

			result(true, '', $users);
		}

		result(false, '');
	}

	public function get_call_lists() {
		if (!hasView('tel_tour_admin'))
			result(false, 'به این قسمت دسترسی ندارید.');

		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);

			$sql = "SELECT t.ID, t.Name, t.Customers, IFNULL(l.ID, 0) AS Joined ";
			$sql .= " FROM tcall_lists t LEFT OUTER JOIN ttour_lists l ON (t.ID = l.ListID AND l.TourID=? AND l.State=1) ";
			$sql .= " LEFT OUTER JOIN ttour_lists l1 ON (t.ID = l1.ListID AND l1.TourID<>? AND l1.State=1) ";
			$sql .= " WHERE t.Active=1 AND t.Deleted=0 /*AND IFNULL(l1.ID, 0) = 0*/ ";

			$lists = $this->db->query($sql, array($id, $id))->result();

			result(true, '', $lists);
		}

		result(false, 'اطلاعات ناقص');

	}

	public function edit_lists() {
		if (!hasEdit('tel_tour_admin')) 
			result(false, 'دسترسی به این گزینه ندارید.');

		$id = intval($_POST['id']);

		if (!empty($id) && !empty($_POST['lists'])) {
			$lists = escapeString($_POST['lists']);

			$lists = explode(',', $lists);

			$res = $this->db->query('SELECT ID, Active, ListCount, VisitorCount FROM ttours WHERE ID=? AND Deleted=0', array($id))->result();

			if (empty($res))
				result(false, 'موردی پیدا نشد.');

			unset($res);

			foreach ($lists as $item) {
				$lid = intval($item);

				if ($this->db->query('SELECT ID FROM tcall_lists WHERE ID=? AND Active=1 AND Deleted=0', array($lid))->num_rows() == 0)
					result(false, 'لیست تماس پیدا نشد.');
			}
			
			$this->db->trans_start();

			$ids = '';
			foreach ($lists as $item) {
				$lid = intval($item);

				if ($ids != '')
					$ids .= ',';

				$ids .= $lid;

				if ($this->db->query('SELECT ID FROM ttour_lists WHERE TourID=? AND ListID=? AND State=1', array($id, $lid))->num_rows() == 0) {
					$res = $this->db->query('SELECT ID FROM ttour_lists WHERE TourID=? AND ListID=?', array($id, $lid))->result();

					$iid = 0;
					if (!empty($res)) {
						$r = $res[0];

						$iid = $r->ID;

						$this->db->update('ttour_lists', array(
							'State'		=> 1
						), array('TourID' => $id, 'ListID' => $lid));
					}
					else {
						$this->db->insert('ttour_lists', array(
							'UserID'		=> $this->User['ID'],
							'TourID'		=> $id,
							'ListID'		=> $lid,
							'JoinDate'		=> date('Y-m-d H:i:s'),
							'LeftDate'		=> '2012-01-01',
							'JoinTarikh'	=> $this->getShamsiDate(),
							'State'			=> 1
						));

						$iid = $this->db->insert_id();
					}

					unset($res);

					$this->db->insert('ttour_list_logs', array(
						'UserID'		=> $this->User['ID'],
						'TourID'		=> $id,
						'ListID'		=> $lid,
						'CreateDate'	=> date('Y-m-d H:i:s'),
						'State'			=> 1,
						'RecordID'		=> $iid
					));
				}

			}

			$res = $this->db->query('SELECT ID, TourID, ListID FROM ttour_lists WHERE State=1 AND TourID=? AND ListID NOT IN (' . $ids . ')', array($id))->result();

			foreach ($res as $item) {
				$this->db->update('ttour_lists', array('State' => 2, 'LeftDate' => date('Y-m-d H:i:s'), 'LeftTarikh' => $this->getShamsiDate()), array('ID' => $item->ID));

				$this->db->insert('ttour_list_logs', array(
					'UserID'		=> $this->User['ID'],
					'TourID'		=> $id,
					'ListID'		=> $item->ListID,
					'CreateDate'	=> date('Y-m-d H:i:s'),
					'State'			=> 2,
					'RecordID'		=> $item->ID
				));

			}

			unset($res);

			$this->db->query('UPDATE ttours t SET ListCount=(SELECT IFNULL(COUNT(*), 0) FROM ttour_lists t1 WHERE t1.TourID=? AND State=1) WHERE t.ID=?', array($id, $id));

			$this->db->trans_complete();

			if ($this->db->trans_status() === false)
				result(false, 'عملیات با خطا متوقف شد.');
			else	
				result(true, 'اطلاعات با موفقیت ثبت شد.');
		}

		result(false, 'اطلاعات اشتباه می باشد.');
	}

	public function save_call() {
		if (!hasView('visit_tour') && $this->User['Distribute'] == 0)
			result(false, 'عدم دسترسی');

		$id = intval($_POST['id']);

		if (!empty($id)) {
			$tarikh = escapeString($_POST['tarikh']);
			$tozih = escapeString($_POST['tozih']);
			$hour = intval($_POST['hour']);
			$rid = intval($_POST['rid']);
			$oid = intval($_POST['oid']);

			visit_save_call($id, $rid, $tozih, $tarikh, $hour, $oid, false);

		}

		result(false, 'اطلاعات ناقص');
	}

	public function get_log_visitor() {
		if (!hasView('visit_tour') && $this->User['Distribute'] == 0)
			die();

		$page = intval($_POST['current']);
        $count = intval($_POST['rowCount']);
		$key = escapeString($_POST['searchPhrase']);
		$az = escapeString($_POST['az']);
		$ta = escapeString($_POST['ta']);
		$tid = intval($_POST['tid']);
		$vid = intval($_POST['vid']);

		$sql = "SELECT * FROM ((SELECT 'شروع مسیر' AS Title, CONCAT(StartTarikh, ' ', StartTime) AS Tarikh, StartDate AS Date, '-' AS Customer, '-' AS Result, 0 AS CustomerID, 0 AS OrderID FROM ttour_visitor_map_logs WHERE UserID=#uid# AND TourID=#tid# AND StartTarikh>=#az# AND StartTarikh<=#ta#) UNION ";
		$sql .= "(SELECT 'پایان مسیر' AS Title, CONCAT(EndTarikh, ' ', EndTime) AS Tarikh, EndDate AS Date, '-' AS Customer, '-' AS Result, 0 AS CustomerID, 0 AS OrderID FROM ttour_visitor_map_logs WHERE UserID=#uid# AND TourID=#tid# AND Ended=1 AND EndTarikh>=#az# AND EndTarikh<=#ta#) UNION ";
		$sql .= "(SELECT 'ثبت نتیجه' AS Title, CONCAT(t.CallTarikh, ' ', CallTime) AS Tarikh, CallDate AS Date, c.ShopName AS Customer, r.Title AS Result, t.CustomerID, 0 AS OrderID FROM ttour_calls t INNER JOIN tcustomers c ON (t.CustomerID = c.ID) INNER JOIN ttour_call_results r ON (t.Result = r.ID) WHERE t.VisitorID=#uid# AND t.TourID=#tid# AND t.CallTarikh>=#az# AND t.CallTarikh<=#ta# AND t.Type=2) UNION ";
		$sql .= "(SELECT 'فروش' AS Title, CONCAT(t.ShamsiDate, ' ', DATE_FORMAT(t.CreateDate, '%H %i %S')) AS Tarikh, t.CreateDate AS Date, c.ShopName AS Customer, '-' AS Result, t.UID AS CustomerID, t.ID AS OrderID FROM torders t INNER JOIN tcustomers c ON (t.UID = c.ID) INNER JOIN ttour_visitor_customers v ON (t.VisitID = v.ID) WHERE t.UserID=#uid# AND v.TourID=#tid# AND t.ShamsiDate>=#az# AND t.ShamsiDate<=#ta#)) t 
		";

		$sql = str_replace('#uid#', $vid, $sql);
		$sql = str_replace('#tid#', $tid, $sql);
		$sql = str_replace('#az#', "'" . $az . "'", $sql);
		$sql = str_replace('#ta#', "'" . $ta . "'", $sql);

		if (!empty($key)) {
			$key = "'%$key%'";

			$sql .= " WHERE (t.Title LIKE $key OR t.Customer LIKE $key OR t.Result LIKE $key OR t.Tarikh LIKE $key) ";
		}

		$cnt = $this->db->query($sql)->num_rows();

        if (isset($_POST['sort'])) {
            $sort = ($_POST['sort']);

            $sql .= "ORDER BY ";

            $first = true;
            foreach ($sort as $key => $value) {
                if ($first)
                    $first = false;
                else
                    $sql .= ",";

                $sql .= $key . ' ' . $value . ' ';
            }
        }
        else {
            $sql .= " ORDER BY t.Date DESC ";
        }

        if ($count > 0)
            $sql .= "LIMIT " . (($page - 1) * $count) . ',' . $count;


        $data = array(
            'current' => $page,
            'rowCount' => $count,
            'rows' => $this->db->query($sql)->result(),
            'total' => $cnt
            );

        echo json_encode($data);
        die();

	}

}
