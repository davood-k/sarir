<?php

/**
 * https://github.com/jamierumbelow/codeigniter-base-model
 */
class Order_Model extends MY_Model
{
	public $_table = 'torders';
	public $return_type = 'array';
	
	public function GetOrder($id) {
		$sql = "SELECT t.ID, t.UID, t.FName, c.GroupID, c.Tel, t.LName, t.Email, t.Phone, t.Fax, t.State, t.Sync, t.City, c.Address1 AS Address, t.CodePosti, c.CodeMelli, c.CodeEgh, t.PostMethod, t.PaymentShCart, t.PaymentMablagh, t.PaymentCode, t.PaymentMethod, t.Items, t.TotalPrice, t.Taxes, t.PostPrice, t.Takhfif, t.TakhfifPost, t.TakhfifPostID, t.TakhfifID, t.TakhfifRadif, t.AllPrice, t.CreateDate, t.ShamsiDate, t.Status AS StatusID, t.SendShamsi, t.DeliverShamsi, t.Verify, t.Paid, t.CodeRahgiri, t.Sample, t.UserID, s.Name AS Status, IFNULL(CONCAT(u.FName, ' ', u.LName), 'توسط مشتری') AS User, t.StoreID, t.TakhfifPercent, t.TakhfifRial, t.TakhfifType, t.EnabledGifts, t.BasketGifts, t.OrderState, t.ShSanad, t.TarikhSanad, t.ShSanadStore, t.TarikhSanadStore, t.IsReturn, t.IsBuy, t.DeliveredPics, t.DistributeUser AS DistributeUserID, CONCAT(ud.FName, ' ', ud.LName) AS DistributeUser, t.DeliverTarikh, sh.Name AS Shift, t.DeliveredTarikh, t.DeliveredTime, t.CallID, t.VisitID, t.UserID, t.SaleManager, t.SendState, t.SendStateTarikh, t.Tozihat, t.DistributeTozihat, t.DeliverTarikh, sh.Az, sh.Ta, t.ByApp, t.PrintCount, t.Visitor2, t.Formal, t.FormalOrderID, t.HasFormalItems, t.FormalDivPrice, IFNULL(CONCAT(v2.FName, ' ', v2.LName), '') AS Visitor2Name, t.PrintTozihat, c.Code AS CustomerCode, store.Name AS Store, c.MandeHesab, IFNULL(u.Username, '-') AS Username/*, (CASE WHEN call_user.ID IS NOT NULL THEN (CONCAT(call_user.FName, ' ', call_user.LName)) WHEN visit_user.ID IS NOT NULL THEN CONCAT(visit_user.FName, ' ', visit_user.LName) ELSE '-' END) AS Visitor*/ ";
		$sql .= " FROM torders t ";
		$sql .= " INNER JOIN torder_states s ON (t.OrderState = s.ID) ";
		$sql .= " INNER JOIN tcustomers c ON (t.UID = c.ID) ";
		$sql .= " INNER JOIN tstores store ON (t.StoreID = store.ID) ";
		$sql .= " LEFT OUTER JOIN tuser u ON (t.UserID = u.ID AND t.ByApp=0) ";
		$sql .= " LEFT OUTER JOIN tuser ud ON (t.DistributeUser = ud.ID) ";
		$sql .= " LEFT OUTER JOIN tuser v2 ON (t.Visitor2 = v2.ID) ";
		$sql .= " LEFT OUTER JOIN tshifts sh ON (t.DeliverShift = sh.ID) ";
		/*$sql .= " LEFT OUTER JOIN ttour_list_to_call tcall ON (t.CallID = tcall.ID) ";
		$sql .= " LEFT OUTER JOIN ttours call_tour ON (tcall.TourID = call_tour.ID) ";
		$sql .= " LEFT OUTER JOIN tuser call_user ON (tcall.VisitorID = call_user.ID) ";
		$sql .= " LEFT OUTER JOIN ttour_visitor_customers visit ON (t.VisitID = visit.ID) ";
		$sql .= " LEFT OUTER JOIN ttours visit_tour ON (visit.TourID = visit_tour.ID) ";
		$sql .= " LEFT OUTER JOIN tuser visit_user ON (visit.VisitorID = visit_user.ID) ";*/
		$sql .= " WHERE t.ID=? AND FromCrm=1";

		$order = $this->db->query($sql, array($id))->result();

		if (empty($order))
			return null;

		$order = $order[0];

		$order->Items = $this->db->query('SELECT t.ID, t.OID, t.Radif, t.Type, t.IID AS PID, t.PMID, t.StoreID, t.Name, t.OldPrice, t.BasePrice, t.Price, t.Count, t.TotalPrice, t.TakhfifID, t.TakhfifRadif, t.GiftID, t.GiftRadif, IFNULL(s.Name, \'\') AS Store, u.Name AS Unit, pm.Name AS Variety, p.Barcode, p.Code, t.TakhfifRial, t.TakhfifPercent, t.TakhfifType, p.Pic, t.Formal, t.ManualFormal, t.TaxFree, p.Naghdi, (SELECT IFNULL(SUM(ResellerID), 0) FROM tproduct_mojodi_trans WHERE OrderID=t.OID AND OrderItemID=t.ID) AS ResellerID FROM torder_items t  INNER JOIN tproducts p ON (t.IID = p.ID) INNER JOIN tunits u ON (p.Unit = u.ID) INNER JOIN tproduct_mojodi pm ON (t.PMID = pm.ID) ' . ($order->IsReturn == '1' ? 'LEFT OUTER' : 'INNER') . ' JOIN tstores s ON (t.StoreID = s.ID) WHERE OID=? ORDER BY t.ID ASC/*ORDER BY p.BID, p.CID, p.Name ASC, t.Type*/', array($id))->result();

		$order->Takhfifs = $this->db->query('SELECT ID, OrderID, TakhfifID, TakhfifRadif, Type, Offer FROM torder_takhfifs WHERE OrderID=?', array($id))->result();

		$order->Logs = $this->db->query("SELECT t.ID, CONCAT(u.FName, ' ', u.LName) AS User, s.Name AS State, t.State as StateID, t.CreateDate, t.ShamsiDate, t.ShamsiTime, t.Tozihat, t.UserID FROM torder_workflows t INNER JOIN tuser u ON (t.UserID = u.ID) INNER JOIN torder_status s ON (t.State = s.ID) WHERE OrderID=? ORDER BY t.ID DESC", array($id))->result();

		$order->Pays = $this->db->query("SELECT p.ID, t.ShamsiDate, t.ShamsiTime, t.Credit, p.Type, t.Tozihat, p.Verify, p.SaleID, p.ShCart, p.TarikhVariz, p.TimeVariz, p.ShCheq, p.CheqName, p.CheqState, p.Tozihat, p.BankID, p.CheqHesab, p.CheqSerial, p.CheqBank, (CASE WHEN p.Type = 3 THEN IFNULL(bn.Name, '-') ELSE IFNULL(CONCAT(b.Name, ' (', b.ShCart, ')'), '-') END) AS Bank, p.ShSanad, p.TarikhSanad FROM ttrans t INNER JOIN tpays p ON (t.PayID = p.ID) LEFT OUTER JOIN tbanks b ON (p.BankID = b.ID) LEFT OUTER JOIN tbank_names bn ON (p.BankID = bn.ID) WHERE t.OrderID=? AND t.Type=1", array($id))->result();

		return $order;
	}
	
}