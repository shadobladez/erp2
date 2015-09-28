<?
require_once   'Buscar_CEP.php';
require_once      'CheckIn.php';
require_once     'CheckOut.php';
require_once      'Combine.php';
require_once     'Generate.php';
require_once      'Invoice.php';
require_once 'Print_Labels.php';
require_once       'Return.php';
require_once         'Glob.php';
require_once	  'XML_NFE.php';
/**
 *	Process all [Ajax] functions
 *	This controller will be used to interface client to mysql using Ajax
 *	@author: Pat Jan
 *
 *	status = 'ok'
 *	status = 'error'
 *
 *	message = 'table name	[X...x] is undefined'
 *	message = 'method name	[X...x] is undefined'
 *	message = 'error on server'				(only for no support)
 *	message = 'error on mysql: x...x'		(only for support)
 *	message = 'duplicate id'
 *
 */
class	AjaxController	extends	JKY_Controller {

public function init() {
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender();

//	set_session('user_level', MINIMUM_TO_BROWSE  );
//	set_session('user_level', MINIMUM_TO_SUPPORT );
//	set_session('user_role' , 'Support'  );
//	set_session('full_name' , 'Pat Jan'  );
//	set_session('user_id'	, 4 );

//	*************************************** Export required Support
	$data = json_decode(get_request('data'), true);
	$method = $data['method'];
	if ($method == 'get_columns'
	||  $method == 'export') {
		set_permissions('Support');
	}

	if (!is_session('version'			))		set_session('version'			, VERSION				);
	if (!is_session('environment'		))		set_session('environment'		, ENVIRONMENT			);
	if (!is_session('control_company'	))		set_session('control_company'	, COMPANY_ID			);
	if (!is_session('user_time'			))		set_session('user_time'			, date( 'Y-m-d H:i:s')	);
	if (!is_session('user_role'			))		set_session('user_role'			, 'visitor'				);
	if (!is_session('company_name'		))		set_session('company_name'		, COMPANY_NAME			);
	if (!is_session('company_logo'		))		set_session('company_logo'		, COMPANY_LOGO			);
	if (!is_session('locale'			))		set_session('locale'			, LOCALE				);
//	if (!is_session('event_id'			)) {
//		set_session('event_id'	, $this->get_last_id('Events', 'status="Active"'));
//		set_session('event_name', get_table_value('Events', 'event_name', get_session('event_id')));
//	}
	if (!is_session('permissions'		))		set_permissions(get_session('user_role'));
}

public function indexAction() {
	try {
		$request	= Zend_Controller_Front::getInstance()->getRequest();
		$controller	= $request->getControllerName();
//		$action		= $request->getActionName();
		logger($controller);

		$data = json_decode(get_request('data'), true);
//		$method = get_request('method');
		$method = $data['method'];
		switch ($method) {
			case 'set_language'		: $this->set_language	(); return;
			case 'get_language'		: $this->get_language	(); return;
			case 'set_session'		: $this->set_session	($data); return;
			case 'get_session'		: $this->get_session	(); return;
			case 'get_options'		: $this->get_options	($data); return;
			case 'get_users'		: $this->get_users		(); return;
			case 'get_controls' 	: $this->get_controls	($data); return;
			case 'get_configs'		: $this->get_configs	($data); return;
			case 'get_companies'	: $this->get_companies	($data); return;
			case 'get_categories'	: $this->get_categories	(); return;
			case 'get_loadout_by_color_id'	: $this->get_loadout_by_color_id($data); return;
			case 'get_profile'		: $this->get_profile	(); return;
			case 'get_contact'		: $this->get_contact	(); return;
			case 'get_contact_id'	: $this->get_contact_id	(); return;
			case 'get_user_id'		: $this->get_user_id	($data); return;
			case 'get_ftp_id'		: $this->get_ftp_id		($data); return;
			case 'get_order_id'		: $this->get_order_id	($data); return;
			case 'get_product_id'	: $this->get_product_id	($data); return;
			case 'set_company_id'	: $this->set_company_id	(); return;
			case 'get_company_id'	: $this->get_company_id	(); return;
			case 'set_user_id'		: $this->set_user_id	(); return;
			case 'set_event_id'		: $this->set_event_id	(); return;
			case 'set_group_id'		: $this->set_group_id	(); return;

//			case 'get_user_screen'	: $this->get_user_screen(); return;
//			case 'get_header'		: $this->get_header		(); return;
//			case 'get_menus'		: $this->get_menus		(); return;

			case 'check_session'	: $this->check_session	(); return;
			case 'log_in'			: $this->log_in			($data); return;
			case 'log_out'			: $this->log_out		($data); return;
			case 'log_help'			: $this->log_help		($data); return;
			case 'profile'			: $this->profile		($data); return;
			case 'sign_up'			: $this->sign_up		(); return;
			case 'confirm'			: $this->confirm		(); return;
			case 'reset'			: $this->reset			($data); return;

			case 'send_email'		: $this->send_email		(); return;
			case 'send_receipt'		: $this->send_receipt	(); return;
			case 'print_labels'		: echo json_encode(JKY_print_labels	($data)); return;
			case 'refresh'			: $this->refresh		($data); return;
			case 'buscar_cep'		: echo json_encode(JKY_buscar_cep	($data)); return;
			case 'glob'				: echo json_encode(JKY_glob			($data)); return;
			case 'get_xml'			: echo json_encode(JKY_get_xml		($data)); return;
		}

//		$table = get_request('table');
		$table = $data['table'];
		$user_action = get_user_action($table);
		if ($user_action == '') {
			$user_action = get_user_action('All');
		}

		set_session('user_action', $user_action);

		//	for undefined resource or denied user_action
		if ($user_action == '' or $user_action == 'Denied') {
			$this->echo_error('resource [' . $table . '] is denied with action: ' . $user_action);
			return;
		}

		if ($user_action != 'All') {
			switch ($method) {
/*
				case 'get_names'	: $required = 'View Insert Update Delete Export'; break;
				case 'get_id'		: $required = 'View Insert Update Delete Export'; break;
				case 'get_count'	: $required = 'View Insert Update Delete Export'; break;
				case 'get_value'	: $required = 'View Insert Update Delete Export'; break;
				case 'get_row'		: $required = 'View Insert Update Delete Export'; break;
				case 'get_rows'		: $required = 'View Insert Update Delete Export'; break;

				case 'get_index'	: $required = 'View Insert Update Delete Export'; break;
				case 'get_comments'	: $required = 'View Insert Update Delete Export'; break;
				case 'add_comment'	: $required = 'View Insert Update Delete Export'; break;
*/
				case 'get_names'	: $required = 'View'	; break;
				case 'get_id'		: $required = 'View'	; break;
				case 'get_ids'		: $required = 'View'	; break;
				case 'get_count'	: $required = 'View'	; break;
				case 'get_sum'		: $required = 'View'	; break;
				case 'get_value'	: $required = 'View'	; break;
				case 'get_row'		: $required = 'View'	; break;
				case 'get_rows'		: $required = 'View'	; break;

				case 'get_index'	: $required = 'View'	; break;
				case 'get_comments'	: $required = 'View'	; break;
				case 'add_comment'	: $required = 'View'	; break;		//	???? Update

				case 'get_columns'	: $required = 'Export'	; break;

				case 'insert'		: $required = 'Insert'	; break;
				case 'update'		: $required = 'Update'	; break;
				case 'replace'		: $required = 'Update'	; break;
				case 'copy'			: $required = 'Update'	; break;
				case 'delete'		: $required = 'Delete'	; break;
				case 'delete_many'	: $required = 'Delete'	; break;
				case 'combine'		: $required = 'Combine'	; break;
				case 'publish'		: $required = 'Publish'	; break;
				case 'export'		: $required = 'Export'	; break;

				case 'checkin'		: $required = 'Update'	; break;
				case 'checkout'		: $required = 'Update'	; break;
				case 'generate'		: $required = 'Update'	; break;
				case 'invoice'		: $required = 'Update'	; break;
				case 'return'		: $required = 'Update'	; break;

				default				: $this->echo_error('method name [' . $method . '] is undefined'); return;
			}

//			for undefined user_action
//			if (strpos($required, $user_action) === false) {
			if ($required != 'View' and strpos($user_action, $required) === false) {
				$this->echo_error('method name [' . $method . '] is denied, action: ' . $user_action . ', required: ' . $required);
				return;
			}
		}

		switch ($method) {
			case 'get_names'	: $this->get_names		($data); break;
			case 'get_id'		: $this->get_id			($data); break;
			case 'get_ids'		: $this->get_ids		($data); break;
			case 'get_count'	: $this->get_count		($data); break;
			case 'get_sum'		: $this->get_sum		($data); break;
			case 'get_value'	: $this->get_value		($data); break;
			case 'get_row'		: $this->get_row		($data); break;
			case 'get_rows'		: $this->get_rows		($data); break;
			case 'get_index'	: $this->get_index		($data); break;
			case 'get_comments'	: $this->get_comments	(); break;
			case 'add_comment'	: $this->add_comment	(); break;
			case 'get_columns'	: $this->get_columns	($data); break;
			case 'insert'		: $this->insert			($data); break;
			case 'update'		: $this->update			($data); break;
			case 'replace'		: $this->replace		($data); break;
			case 'copy'			: $this->copy			($data); break;
			case 'move'			: $this->move			($data); break;

			case 'delete'		: $this->delete			($data); break;
			case 'delete_many'	: $this->delete_many	($data); break;
			case 'combine'		: echo json_encode (JKY_combine($data)); return;
			case 'publish'		: $this->publish		($data); break;
			case 'export'		: $this->get_index		($data); break;
			case 'Xrefresh'		: $this->Xrefresh		(); break;

			case 'checkin'		: echo json_encode (JKY_checkin	($data)); return;
			case 'checkout'		: echo json_encode (JKY_checkout($data)); return;
			case 'generate'		: echo json_encode (JKY_generate($data)); return;
			case 'invoice'		:					JKY_invoice	($data) ; return;
			case 'return'		: echo json_encode (JKY_return	($data)); return;

			case 'set_amount'	: $this->set_amount		(); break;
			case 'reset_amount'	: $this->reset_amount	(); break;

			default				: $this->echo_error('method name [' . $method . '] is undefined'); return;
		}

//		process insert duplicate
//		process limit number of rows

		return;

	} catch(Exception $exp){
//		if (get_session('user_level') == MINIMUM_TO_SUPPORT) {
			$this->echo_error('' . $exp);
//		}else{
//			$this->echo_error('error on server');
//		}
	}
}

/**
 *	get security
 *	if
 *
 *	return where
 */
private function get_security($table, $where) {
//	if (get_session('user_action') == 'All') {
//		return $where;
//	}

	if ($table == 'Contacts') {
		if (get_session('user_role') != 'Support') {
			$my_where = '(JKY_Users.id IS NULL OR JKY_Users.user_role != "Support")';
			if ($where != '') {
				return $my_where . ' AND ' . $where;
			}else{
				return $my_where;
			}
		}else{
			return $where;
		}
	}

	switch($table) {
//		case 'Contacts'		: return      'Contacts.id=' . get_session('user_id');
//		case 'Services'		: return 'Services.user_id=' . get_session('user_id') . ' AND Services.event_id=' . get_session('event_id');
		default				: return $where;
	}
}

private function get_left_join($table) {
	$my_left_join = '';
	if ($table == 'Contacts') {
		if (get_session('user_role') != 'Support') {
			$my_left_join = '  LEFT JOIN   JKY_Users AS JKY_Users	ON  Contacts.id =  JKY_Users.contact_id';
		}
	}
	return $my_left_join;
}

/**
 *	$.ajax({ method: get_names, table: x...x, field: x...x, key: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
private function get_names($data) {
	$table = get_data($data, 'table');
	$field = get_data($data, 'field');
	$key   = get_data($data, 'key'	);
	$data  = '';

	if ($key != '') {
		$sql= 'SELECT ' . $field . ' AS value'
			. '  FROM ' . $table
			. ' WHERE ' . $field . ' LIKE "%' . $key . '%"'
			. ' ORDER BY ' . $field
			. ' LIMIT 0, 10'
			;
		$db   = Zend_Registry::get('db');
		$rows = $db->fetchAll($sql);

		$prepen = '';
		foreach($rows as $row) {
			$data  .= $prepen . '"' . $row['value'] . '"';
			$prepen = ', ';
		}
	}
	echo '[' . $data . ']';
}

/**
 *   $.ajax({ method: get_user_screen, name: x...x });
 *
 *   return: [ x...x, ..., x...x ]
 */
private function get_user_screen() {
	$name = get_request('name');

	$sql= 'SELECT value'
		. '  FROM Controls'
		. ' WHERE group_set  = "User Screens"'
		. '   AND control_name = "' . $name . '"'
		;
//$this->log_sql( null, 'get_user_screen', $sql );
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['page'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_id, table: x...x, where: x...x });
 *
 *	status: ok
 *	id: 9...9 (false)
 */
private function get_id($data) {
	$table = get_data($data, 'table');
	$where = get_data($data, 'where');

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	if ($table == 'PiecesFTP') {
		$sql= 'SELECT Orders.ftp_id'
			. '  FROM Pieces'
			. '  LEFT JOIN Orders ON Orders.id = Pieces.order_id'
			. ' WHERE ' . $where
			. ' LIMIT 1'
			;
/*
	if ($table == 'QuotColorFTPs') {
		$sql= 'SELECT DISTINCT FTPs.id'
			. '  FROM QuotColors'
			. '  LEFT JOIN QuotLines ON QuotLines.id = QuotColors.parent_id'
			. '  LEFT JOIN FTPs   ON FTPs.product_id = QuotLines.product_id'
			. ' WHERE ' . $where
			. ' ORDER BY FTPs.ftp_number'
			. ' LIMIT 1'
			;
*/
	}else{
		$where = $this->get_security($table, $where);
		$names = explode('=', $where);
		if (trim($names[0]) == 'user_name') {
			$sql= 'SELECT contact_id as id'
				. '  FROM JKY_Users'
				. ' WHERE ' . $where
				;
		}else{
			$sql= 'SELECT ' . $table . '.id'
				. '  FROM ' . $table . $this->get_left_join($table)
				. ' WHERE ' . $where
				;
			;
		}
	}

$this->log_sql( $table, 'get_id', $sql );
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_ids, table: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
private function get_ids($data) {
	$table = get_data($data, 'table');
	$sql= 'SELECT id, name'
		. '  FROM ' . $table
		. ' WHERE status = "Active"'
		. ' ORDER BY name'
		;
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $db->fetchAll($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_count, table: x...x [, where: x...x] });
 *
 *	status: ok
 *	count: 9...9
 */
private function get_count($data) {
	$table = get_data($data, 'table');
	$where = get_data($data, 'where');

	$where = $this->get_security($table, $where);
	if ($where != '') {
		$where = ' WHERE ' . $where;
	}

	$sql= 'SELECT COUNT(*) AS count'
		. '  FROM ' . $table
		. $where
		;

	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['count'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_sum, table: x...x, field: x...x, where: x...x });
 *
 *	status: ok
 *	count: 9...9
 */
private function get_sum($data) {
	$table = get_data($data, 'table');
	$field = get_data($data, 'field');
	$where = get_data($data, 'where');

	$where = $this->get_security($table, $where);
	if ($where != '') {
		$where = ' WHERE ' . $where;
	}

	$sql= 'SELECT SUM(' . $field . ') AS sum'
		. '  FROM ' . $table
		. $where
		;

	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['sum'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_value, table: x...x, field:x...x, where: x...x });
 *
 *	status: ok
 *	value: x...x (false)
 */
private function get_value($data) {
	$table = get_data($data, 'table');
	$field = get_data($data, 'field');
	$where = get_data($data, 'where');

	if ($field == '') {
		$this->echo_error('missing [field] statement');
		return;
	}

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$where = $this->get_security($table, $where);
	$sql= 'SELECT ' . $field
		. '  FROM ' . $table
		. ' WHERE ' . $where
		;
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['value'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_row, table: x...x, where: x...x });
 *
 *	status: ok
 *	row: { x...x: y...y, ... } (false)
 */
private function get_row($data) {
	$table = get_data($data, 'table');
	$where = get_data($data, 'where');

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$where = $this->get_security($table, $where);
	$sql= 'SELECT ' . $table . '.*' . $this->set_new_fields($table)
		. '  FROM ' . $table		. $this->set_left_joins($table)
		. ' WHERE ' . $where
		;
$this->log_sql( $table, 'get_row', $sql );
	$db  = Zend_Registry::get('db');
	$row = $db->fetchRow($sql);

	if ($table == 'Categories') {
		$sql = 'SELECT COUNT(*) FROM Categories WHERE parent_id = ' . $row['id'];
		$row['children'] = $db->fetchOne($sql);
	}

	$return = array();
	$return['status'] = 'ok';
	$return['row'	] = $row;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_rows, table: x...x [, where: x...x] [, order_by: x...x] });
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]
 */
private function get_rows($data) {
	$table		= get_data($data, 'table');
	$where		= get_data($data, 'where');
	$order_by	= get_data($data, 'order_by');

	$where = $this->get_security($table, $where);
	if ($where		!= '')		$where		= ' WHERE '		. $where	;
	if ($order_by	!= '')		$order_by	= ' ORDER BY '	. $order_by	;

	$sql= 'SELECT ' . $table . '.*' . $this->set_new_fields($table)
		. '  FROM ' . $table		. $this->set_left_joins($table)
		. $where
		. $order_by
		;
//$this->log_sql($table, 'get_rows', $sql);
	$db   = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);

	if ($table == 'Categories') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT COUNT(*) FROM Categories WHERE parent_id = ' . $row['id'];
			$rows[$n]['children'] = $db->fetchOne($sql);
			$n++;
		}
	}

	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_index, table: x...x, filter: x...x, select: x...x, display: x...x, order_by: x...x, specific: x...x });
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]
 */
private function get_index($data) {
//file_put_contents('debug.txt', "\n" . get_session('user_action'), FILE_APPEND);
	if (get_session('user_action') != 'All'
	and get_session('user_action') != 'View') {
		return;
	}

	$table		= get_data($data, 'table'		);
	$specific	= get_data($data, 'specific'	);
	$specific_id= get_data($data, 'specific_id'	);
	$select		= get_data($data, 'select'		);
	$filter		= get_data($data, 'filter'		);
	$display	= get_data($data, 'display'		);
	$order_by	= get_data($data, 'order_by'	);
	$group_by	= get_data($data, 'group_by'	);
	$where		= get_data($data, 'where'		);

	if ($where == '') {
		$where .= $this->set_specific($table, $specific, $specific_id);
		$where .= $this->set_select  ($table, $specific, $select);
		if ($filter != '') {
			$filters = explode(' and ', $filter);
			foreach($filters as $filter)
				$where .= $this->set_where($table, $filter);
		}
	}

//$limit_number = get_config_value('System Controls', 'Limit number of records to select');
//poop($limit_number, '$limit_number');

	if (is_numeric( $display)) {
		$limit = ' LIMIT ' . $display;
	}else{
//		$limit = '';
		$limit = ' LIMIT 250';
	}

	if ($where != '') {
		$where  = substr($where, 4);
	}
	$where = $this->get_security($table, $where);

	if ($table == 'BatchesBalance') {
		$sql= 'SELECT Batches.id'
			. '		, Batches.batch'
			. '		, Batches.remarks'
			. '		, Incomings.invoice_date'
			. '     , Boxes.checkin_location'
			. '     , SUM(IF(Boxes.status  = "Check In"	OR Boxes.status  = "Return", 1, 0)) AS balance_boxes'
			. '     , SUM(IF(Boxes.status  = "Check In"	OR Boxes.status  = "Return", IF(Boxes.real_weight = 0, Boxes.average_weight, Boxes.real_weight), 0)) AS balance_weight'
			. '     , SUM(IF(Boxes.status  = "Active"	, 0,	Boxes.average_weight	   )) AS  checkin_weight'
//			. '     , SUM(IF(Boxes.status  = "Return"	,		Boxes.real_weight		, 0)) AS   return_weight'
//			. '     , SUM(IF(Boxes.status  = "Check Out",		Boxes.average_weight	, 0)) AS checkout_weight'
			. '     ,   Supplier.id				AS supplier_id'
			. '     ,   Supplier.nick_name		AS supplier_name'
			. '  FROM Boxes'
			. '  LEFT JOIN Batches				ON Batches.id = Boxes.batch_id'
			. '  LEFT JOIN Incomings  			ON Incomings.id	= Batches.incoming_id'
			. '  LEFT JOIN Contacts AS Supplier	ON  Supplier.id	= Incomings.supplier_id'
			. ' WHERE Batches.thread_id = ' . $specific_id . $this->set_where($table, $filter)
			. ' GROUP BY invoice_date, batch, checkin_location'
			. ' HAVING balance_boxes > 0'
			. ' ORDER BY ' . $order_by
			;
	}else
	if ($table == 'CheckinLocations') {
		$sql= 'SELECT Boxes.checkin_location	AS location'
			. '	 , MIN(Boxes.checkin_at)		AS checkin_at'
			. '	 , COUNT(*)						AS total_boxes'
			. '	 , SUM(IF(Boxes.real_weight = 0, Boxes.average_weight, Boxes.real_weight))	AS total_weight'
			. '  FROM Boxes'
			. '  LEFT JOIN Batches	ON Batches.id = Boxes.batch_id'
			. ' WHERE Batches.thread_id = ' . $specific_id
			. '   AND Batches.batch = "' . $select . '"'
			. '   AND (Boxes.status = "Check In" OR Boxes.status = "Return")'
			. ' GROUP BY Boxes.checkin_location'
			. ' ORDER BY Boxes.checkin_location'
			;
	}else
	if ($table == 'Compositions') {
		$sql= 'SELECT FTPs.composition AS name, COUNT(*) AS count'
			. '  FROM FTPs'
			. ' WHERE FTPs.status = "Active"'
			. '   AND FTPs.composition != ""'
			. ' GROUP BY FTPs.composition'
			. ' ORDER BY ' . $order_by
			;
	}else
	if ($table == 'PieceLocations') {
		$sql= 'SELECT parent_id'
			. '  FROM Products'
			. ' WHERE Products.id = ' . $select
			;
		$db   = Zend_Registry::get('db');
		$parent_id = $db->fetchOne($sql);
		if ($parent_id) {
			$select .= ', ' . $parent_id;
		}

		$sql= 'SELECT Pieces.checkin_location		AS location'
			. '		, Pieces.order_id				AS order_id'
			. '		, Orders.machine_id				AS machine_id'
			. '		, Orders.ftp_id					AS ftp_id'
			. '		, Orders.osa_line_id			AS osa_line_id'
			. '		, Orders.order_number			AS order_number'
			. '		, MIN(Pieces.checkin_at)		AS checkin_at'
			. '		, COUNT(*)						AS total_pieces'
			. '		, SUM(Pieces.checkin_weight)	AS total_weight'
			. '  FROM Pieces'
			. '  LEFT JOIN Orders ON Orders.id = Pieces.order_id'
			. ' WHERE Pieces.status = "Check In"'
			. '   AND Orders.product_id IN (' . $select . ')'
			. ' GROUP BY Pieces.checkin_location, Pieces.order_id'
			. ' ORDER BY Pieces.checkin_location, Pieces.order_id'
			;
	}else
	if ($table == 'ColorUnloadeds') {
		$where = ($filter == '') ? '' : ' AND Color.color_name LIKE "%' . $filter . '%"';
		$sql= 'SELECT QuotColors.id				AS			 id'
			. ',      QuotColors.quoted_units	AS    quoted_units'
			. ',           Color.id				AS	   color_id'
			. ',           Color.color_name		AS     color_name'
			. ',           Color.color_type		AS     color_type'
			. ',        QuotLine.peso			AS			 peso'
			. ',        QuotLine.units			AS			 units'
			. '  FROM QuotColors'
			. '  LEFT JOIN      Colors AS Color 	ON     Color.id	=	QuotColors.color_id'
			. '  LEFT JOIN   QuotLines AS QuotLine	ON  QuotLine.id	=	QuotColors.parent_id'
			. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=	  QuotLine.parent_id'
			. ' WHERE Quotation.status IN ("Draft", "Active")'
			. '   AND Color.id IS NOT NULL'
			. $where
			. '  ORDER BY ' . $order_by
			;
	}else
	if ($table == 'QuotUnloadeds') {
		$sql= 'SELECT QuotColors.*'
			. ',      Color.color_name			AS     color_name'
			. ',      Color.color_type			AS     color_type'
			. ',       Dyer.nick_name			AS      dyer_name'
			. ',   QuotLine.product_id			AS	 product_id'
			. ',   QuotLine.machine_id			AS	 machine_id'
			. ',   QuotLine.peso				AS			 peso'
			. ',   QuotLine.units				AS			 units'
			. ',  Quotation.quotation_number	AS quotation_number'
			. ',  Quotation.quoted_at			AS    quoted_at'
			. ',    Product.product_name		AS   product_name'
			. ',   Customer.nick_name			AS  customer_name'
			. '  FROM QuotColors'
			. '  LEFT JOIN      Colors AS Color 	ON     Color.id	=	QuotColors.color_id'
			. '  LEFT JOIN    Contacts AS Dyer		ON		Dyer.id	=	QuotColors.dyer_id'
			. '  LEFT JOIN   QuotLines AS QuotLine	ON  QuotLine.id	=	QuotColors.parent_id'
			. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=	  QuotLine.parent_id'
			. '  LEFT JOIN    Products AS Product	ON   Product.id	=	  QuotLine.product_id'
			. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=	 Quotation.customer_id'
			. ' WHERE Quotation.status IN ("Draft", "Active")'
			. '    AND ' . $where
			. '  ORDER BY ' . $order_by
			;
	}else
	if ($table == 'QuotProducts') {
		if ($where    != '')	{$where		= ' WHERE '    . $where   ;}
		$sql= 'SELECT QuotColors.*'
			. ',    Product.product_name		AS   product_name'
			. ',      Color.color_name			AS     color_name'
			. ',  Quotation.quotation_number	AS quotation_number'
			. ',   Customer.nick_name			AS  customer_name'
			. ',    Machine.name				AS   machine_name'
			. ',  Quotation.id					AS quotation_id'
			. ',  Quotation.status				AS           status'
			. ',  Quotation.quoted_at			AS    quoted_at'
			. ',   QuotLine.peso				AS			 peso'
			. ',   QuotLine.units				AS			 units'
			. ', QuotColors.quoted_units		AS	  quoted_units'
			. '  FROM QuotColors'
			. '  LEFT JOIN         Colors AS Color		ON     Color.id	=	QuotColors.color_id'
			. '  LEFT JOIN      QuotLines AS QuotLine	ON  QuotLine.id	=	QuotColors.parent_id'
			. '  LEFT JOIN     Quotations AS Quotation	ON Quotation.id	=	  QuotLine.parent_id'
			. '  LEFT JOIN       Products AS Product	ON   Product.id	=	  QuotLine.product_id'
			. '  LEFT JOIN       Machines AS Machine	ON   Machine.id	=	  QuotLine.machine_id'
			. '  LEFT JOIN       Contacts AS Customer	ON  Customer.id	=	 Quotation.customer_id'
			. $where
			. '  ORDER BY ' . $order_by
			. $limit
			;
	}else
	if ($table == 'FTP_Ord_Threads') {
		$ftp_id		= get_data($data, 'ftp_id'	);
		$order_id	= get_data($data, 'order_id');
		if ($order_id) {
			$sql= 'SELECT FTP_Threads.percent'
				. '     , FTP_Threads.thread_id'
				. '     , Threads.name AS thread_name'
				. '     , Batches.batch AS batch'
				. '     , Contacts.nick_name AS supplier_name'
				. '  FROM FTP_Threads'
				. '  LEFT JOIN Threads		ON     Threads.id = FTP_Threads.thread_id'
				. '  LEFT JOIN OrdThreads	ON  OrdThreads.parent_id = ' . $order_id . ' AND OrdThreads.thread_id = FTP_Threads.thread_id'
				. '  LEFT JOIN Batches		ON     Batches.id =  OrdThreads.batchin_id'
				. '  LEFT JOIN Incomings	ON   Incomings.id =     Batches.incoming_id'
				. '  LEFT JOIN Contacts		ON    Contacts.id =   Incomings.supplier_id'
				. ' WHERE FTP_Threads.parent_id = ' . $ftp_id
				. ' ORDER BY FTP_Threads.id'
				;
		}else{		
			$sql= 'SELECT FTP_Threads.percent'
				. '     , FTP_Threads.thread_id'
				. '     , Threads.name AS thread_name'
				. '     , "" AS batch'
				. '     , Contacts.nick_name AS supplier_name'
				. '  FROM FTP_Threads'
				. '  LEFT JOIN Threads		ON     Threads.id = FTP_Threads.thread_id'
				. '  LEFT JOIN Contacts		ON    Contacts.id = FTP_Threads.supplier_id'
				. ' WHERE FTP_Threads.parent_id = ' . $ftp_id
				. ' ORDER BY FTP_Threads.id'
				;
		}
	}else
	if ($table == 'FTP_Sets') {
		$sql= 'SELECT Configs.id as setting, Configs.name, FTP_Sets.id, FTP_Sets.value'
			. '  FROM Configs'
			. '  LEFT JOIN FTP_Sets'
			. '    ON FTP_Sets.setting_id = Configs.id AND ' . $where
			. ' WHERE Configs.group_set = "Settings"'
			. ' ORDER BY Configs.sequence'
			;
	}else
	if ($table == 'Purchases' and $group_by != '') {
		if ($where    != '')	{$where		= ' WHERE '    . $where   ;}
		if ($order_by != '')	{$order_by	= ' ORDER BY ' . $order_by;}
		if ($group_by != '')	{$group_by	= ' GROUP BY ' . $group_by;}

		$sql= 'SELECT '		. $table . '.expected_date'
			. '		, SUM(' . $table . '.expected_weight' . ') AS expected_weight'
			. '		, SUM(' . $table . '.received_weight' . ') AS received_weight'
			. '  FROM ' . $table		. $this->set_left_joins($table)
			. $where
			. $group_by
			. $order_by
			. $limit
			;
	}else
	if ($table == 'Incomings' and $group_by != '') {
		if ($where    != '')	{$where		= ' WHERE '    . $where   ;}
		if ($order_by != '')	{$order_by	= ' ORDER BY ' . $order_by;}
		if ($group_by != '')	{$group_by	= ' GROUP BY ' . $group_by;}

		$sql= 'SELECT '		. $table . '.invoice_date'
			. '		, SUM(' . $table . '.invoice_weight'  . ') AS invoice_weight'
			. '		, SUM(' . $table . '.received_weight' . ') AS received_weight'
			. '  FROM ' . $table		. $this->set_left_joins($table)
			. $where
			. $group_by
			. $order_by
			. $limit
			;
	}else
	if ($table == 'LoadsByDyer') {
		$sql= '	SELECT Dyer.nick_name						AS    dyer_name'
			. '      , SUM(LoadQuotations.quoted_pieces)	AS  quoted_pieces'
			. '      , MIN(loadout_number)					AS loadout_number'
			. '   FROM LoadQuotations'
			. '   LEFT JOIN LoadOuts AS LoadOut	ON LoadOut.id =	LoadQuotations.loadout_id'
			. '   LEFT JOIN Contacts AS Dyer	ON    Dyer.id =		   LoadOut.dyer_id'
			. '  WHERE  LoadQuotations.quot_color_id = ' . $specific_id
			. '  GROUP BY LoadOut.dyer_id'
			;
	}else{
		if ($where    != '')	{$where		= ' WHERE '    . $where   ;}
		if ($order_by != '')	{$order_by	= ' ORDER BY ' . $order_by;}
		if ($group_by != '')	{$group_by	= ' GROUP BY ' . $group_by;}

		$sql= 'SELECT ' . $table . '.*' . $this->set_new_fields($table)
			. '  FROM ' . $table		. $this->set_left_joins($table)
			. $where
			. $group_by
			. $order_by
			. $limit
			;
	}

	$this->log_sql($table, 'get_index', $sql);
    $db   = Zend_Registry::get('db');
    $rows = $db->fetchAll($sql);

	if ($table == 'Categories') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT COUNT(*)'
				 . '  FROM Categories'
				 . ' WHERE parent_id = ' . $row['id']
				 ;
			$rows[$n]['children'] = $db->fetchOne($sql);
			$n++;
		}
	}else
	if ($table == 'ColorUnloadeds') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT SUM(quoted_pieces) AS pieces, SUM(quoted_weight) AS weight'
				 . '  FROM LoadQuotations'
				 . ' WHERE quot_color_id = ' . $row['id']
				 ;
			$my_assigned = $db->fetchRow($sql);
			$rows[$n]['assigned_pieces'] = $my_assigned['pieces'];
			$rows[$n]['assigned_weight'] = $my_assigned['weight'];
			$n++;
		}
	}else
/*
	if ($table == 'LoadSets') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT produced_by'
				 . '  FROM Pieces'
				 . ' WHERE load_quot_id =  ' . $row['load_quot_id']
				 . '   AND product_name = "' . $row['product_name'] . '"'
//				 . '   AND status = "Check In"'
				 . ' LIMIT 1'
				 ;
			$rows[$n]['produced_by'] = $db->fetchOne($sql);
			$n++;
		}
	}else
*/
	if ($table == 'Orders') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT COUNT(*) AS revised_pieces'
				 . '  FROM Pieces'
				 . ' WHERE order_id = ' . $row['id']
				 . '   AND status != "Active"'
				 ;
			$rows[$n]['revised_pieces'] = $db->fetchOne($sql);
			$n++;
		}
	}else
	if ($table == 'PieceLocations') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT thread_id'
				 . '  FROM FTP_Threads'
				 . ' WHERE FTP_Threads.parent_id = ' . $row['ftp_id']
				 . ' ORDER BY FTP_Threads.percent DESC, FTP_Threads.thread_id'
				 . ' LIMIT 1'
				 ;
			$my_thread_id = $db->fetchOne($sql);

			$sql = 'SELECT Threads.name AS thread_name'
				 . '  FROM Threads'
				 . ' WHERE Threads.id = ' . $my_thread_id
				 ;
			$my_thread_name = $db->fetchOne($sql);

			$sql = 'SELECT OrdThreads.batchin_id'
				 . '  FROM OrdThreads'
				 . ' WHERE OrdThreads.parent_id = ' . $row['order_id']
				 . '   AND OrdThreads.thread_id = ' . $my_thread_id
				 ;
			$my_ord_thread = $db->fetchRow($sql);

			$my_supplier_name	= '';
			$my_batch_code		= '';
			if ($my_ord_thread['batchin_id']) {
				$sql = 'SELECT Contacts.nick_name	AS supplier_name'
					 . '     , Batches.batch		AS batch_code' 
					 . '  FROM Batches'
					 . '  LEFT JOIN Incomings	ON Incomings.id =   Batches.incoming_id'
					 . '  LEFT JOIN Contacts	ON  Contacts.id = Incomings.supplier_id'
					 . ' WHERE Batches.id = ' . $my_ord_thread['batchin_id']
					 ;
				$my_batchin = $db->fetchRow($sql);
				$my_supplier_name	= $my_batchin['supplier_name'	];
				$my_batch_code		= $my_batchin['batch_code'		];
			}

			$my_machine_name = '';
			if ($row['machine_id']) {
				$sql = 'SELECT Machines.name AS machine_name'
					 . '  FROM Machines'
					 . ' WHERE Machines.id = ' . $row['machine_id']
					 ;
				$my_machine_name = $db->fetchOne($sql);
			}

			$my_quotation_number = '';
			if ($row['osa_line_id']) {
				$sql = 'SELECT Quotations.quotation_number'
					 . '  FROM OSA_Lines, OSAs, Quotations'
					 . ' WHERE OSA_Lines.id = ' . $row['osa_line_id']
					 . '   AND OSAs.id = OSA_Lines.parent_id'
					 . '   AND Quotations.id = OSAs.quotation_id'
					 ;
				$my_quotation_number = $db->fetchOne($sql);
			}

			$rows[$n]['thread_name'		] = $my_thread_name		;
			$rows[$n]['supplier_name'	] = $my_supplier_name	;
			$rows[$n]['batch_code'		] = $my_batch_code		;
			$rows[$n]['machine_name'	] = $my_machine_name	;
			$rows[$n]['quotation_number'] = $my_quotation_number;
			$n++;
		}
	}else
	if ($table == 'QuotProducts') {
		$n = 0;
		foreach($rows as $row) {
			$sql = 'SELECT LoadOuts.loadout_number'
				 . '  FROM LoadOuts, LoadQuotations'
				 . ' WHERE LoadQuotations.quot_color_id = ' . $row['id']
				 . '   AND LoadQuotations.loadout_id = LoadOuts.id'
				 ;
			$my_loadout_number = $db->fetchOne($sql);
			if(!$my_loadout_number)			$my_loadout_number = null;
			$rows[$n]['loadout_number']	=	$my_loadout_number;

			$sql = 'SELECT OSAs.osa_number'
				 . '  FROM OSAs'
				 . ' WHERE OSAs.quotation_id = ' . $row['quotation_id']
				 ;
			$my_osa_number = $db->fetchOne($sql);
			if(!$my_osa_number)				$my_osa_number = null;
			$rows[$n]['osa_number']		=	$my_osa_number;
			$n++;
		}
	}else
	if ($table == 'QuotUnloadeds') {
		$n = 0;
		foreach($rows as $row) {
			$rows[$n]['composition'] = get_product_composition($row['product_id']);

			$sql = 'SELECT SUM(quoted_pieces) AS pieces, SUM(quoted_weight) AS weight'
				 . ' FROM LoadQuotations'
				 . ' WHERE quot_color_id = ' . $row['id']
				 ;
			$my_assigned = $db->fetchRow($sql);
			$rows[$n]['assigned_pieces'] = $my_assigned['pieces'];
			$rows[$n]['assigned_weight'] = $my_assigned['weight'];
			$n++;
		}
	}

	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

private function set_specific($table, $specific, $specific_id) {
	if ($specific == '')	return '';

	if ($table == 'Addresses'		&& $specific == 'customer'		)	return ' AND      Addresses.parent_name		= "Contact"'
																			.  ' AND      Addresses.parent_id		= ' . $specific_id;
	if ($table == 'Contacts'		&& $specific == 'is_customer'	)	return ' AND       Contacts.is_customer		= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_supplier'	)	return ' AND       Contacts.is_supplier		= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_dyer'		)	return ' AND       Contacts.is_dyer			= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_partner'	)	return ' AND       Contacts.is_partner		= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_transport'	)	return ' AND       Contacts.is_transport	= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_company'	)	return ' AND       Contacts.is_company		= "Yes"';
	if ($table == 'Contacts'		&& $specific == 'is_contact'	)	return ' AND       Contacts.is_company		= "No" ';
	if ($table == 'Contacts'		&& $specific == 'is_salesman'	)	return ' AND       Contacts.is_company		= "No" ';
	if ($table == 'Contacts'		&& $specific == 'company'		)	return ' AND       Contacts.company_id		= ' . $specific_id;
	if ($table == 'Batches'			&& $specific == 'incoming'		)	return ' AND        Batches.incoming_id		= ' . $specific_id;
	if ($table == 'Batches'			&& $specific == 'thread'		)	return ' AND        Batches.thread_id		= ' . $specific_id;
	if ($table == 'BatchOuts'		&& $specific == 'checkout'		)	return ' AND      BatchOuts.checkout_id		= ' . $specific_id;
	if ($table == 'Boxes'			&& $specific == 'batch'			)	return ' AND          Boxes.batch_id		= ' . $specific_id;
	if ($table == 'FTPs'			&& $specific == 'product'		)	return ' AND           FTPs.product_id		= ' . $specific_id;
	if ($table == 'History'			&& $specific == 'parent_id'		)	return ' AND        History.parent_id		= ' . $specific_id;
	if ($table == 'LoadIns'			&& $specific == 'receive'		)	return ' AND        LoadIns.receivedyer_id	= ' . $specific_id;
	if ($table == 'LoadOuts'		&& $specific == 'dyer'			)	return ' AND       LoadOuts.dyer_id			= ' . $specific_id
																			.  ' AND       LoadOuts.shipdyer_id		IS NULL';
	if ($table == 'LoadOuts'		&& $specific == 'shipdyer'		)	return ' AND       LoadOuts.shipdyer_id		= ' . $specific_id;
	if ($table == 'LoadQuotations'	&& $specific == 'loadout'		)	return ' AND LoadQuotations.loadout_id		= ' . $specific_id;
	if ($table == 'Pieces'			&& $specific == 'order'			)	return ' AND         Pieces.order_id		= ' . $specific_id;
	if ($table == 'Pieces'			&& $specific == 'rejected'		)	return ' AND         Pieces.qualities	   != "Boa"';
	if ($table == 'Pieces'			&& $specific == 'loadout'		)	return ' AND       LoadQuot.loadout_id		= ' . $specific_id;
	if ($table == 'ProdPrices'		&& $specific == 'product'		)	return ' AND     ProdPrices.product_id		= ' . $specific_id;
	if ($table == 'PurchaseLines'	&& $specific == 'parent'		)	return ' AND  PurchaseLines.parent_id		= ' . $specific_id;
	if ($table == 'PurchaseLines'	&& $specific == 'supplier'		)	return ' AND      Purchases.supplier_id		= ' . $specific_id;
	if ($table == 'Recipes'			&& $specific == 'color'			)	return ' AND        Recipes.color_id		= ' . $specific_id;
	if ($table == 'Restrictions'	&& $specific == 'customer'		)	return ' AND   Restrictions.customer_id		= ' . $specific_id;
	if ($table == 'QuotColors'		&& $specific == 'color'			)	return ' AND     QuotColors.color_id		= ' . $specific_id;
	if ($table == 'Translations'	&& $specific == 'locale'		)	return ' AND   Translations.locale			= "en_US"';

	if ($table == 'Quotations'		&& $specific == 'same_server'	) {
//		for no Support, each server can only access their own quotations
		if (get_session('user_role') != 'Support') {
			return ' AND SUBSTR(Quotations.id, 1, 1) = ' . SERVER_NUMBER;
		}else{
			return '';
		}
	}

	if ($table == 'QuotProducts') {
		if ($specific == 'color' && $specific_id) {
			return ' AND QuotColors.color_id = ' . $specific_id;
		}else{
			return ' AND Color.recipes = 0';
		}
	}

	if ($table == 'QuotUnloadeds') {
		if ($specific == 'color' && $specific_id) {
			return ' AND QuotColors.color_id = ' . $specific_id;
		}else{
			return ' AND Color.recipes = 0';
		}
	}

	return '';
}

private function set_select($table, $specific, $select) {
	if ($select == 'All')	return '';

	if ($select == 'Draft + Active'			//	from ajax
	||  $select == 'Draft   Active') {		//	from export
		switch($table) {
			case 'BatchOuts'		: return ' AND  BatchOuts		.status IN   ("Draft","Active")';
			case 'CheckOuts'		: return ' AND  CheckOuts		.status IN   ("Draft","Active")';
			case 'LoadIns'			: return ' AND  LoadIns			.status IN   ("Draft","Active")';
//			case 'LoadOuts'			: return ' AND  LoadOuts		.status IN   ("Draft","Active")';
			case 'LoadQuotations'	: return ' AND  LoadQuotations	.status IN   ("Draft","Active")';
			case 'Orders'			: return ' AND  Orders			.status IN   ("Draft","Active")';
			case 'OSAs'				: return ' AND  OSAs			.status IN   ("Draft","Active")';
			case 'Purchases'		: return ' AND  Purchases		.status IN   ("Draft","Active")';
			case 'PurchaseLines'	: return ' AND  Purchases		.status IN   ("Draft","Active")';
			case 'Quotations'		: return ' AND  Quotations		.status IN   ("Draft","Active")';
			case 'QuotProducts'		: return ' AND  Quotation		.status IN   ("Draft","Active")';
			case 'ReceiveDyers'		: return ' AND  ReceiveDyers	.status IN   ("Draft","Active")';
			case 'Sales'			: return ' AND  Sales			.status IN   ("Draft","Active")';
			case 'ShipDyers'		: return ' AND  ShipDyers		.status IN   ("Draft","Active")';
			case 'TDyers'			: return ' AND  TDyers			.status IN   ("Draft","Active")';
		}
	}

	if ($table == 'Contacts' && $specific == 'is_contact') {
		return ' AND JKY_Users.user_role = "' . $select . '"';
	}

	switch($table) {
		case 'Batches'			: return ' AND      Incomings.status		= "' . $select . '"';
		case 'BatchOuts'		: return ' AND      BatchOuts.status		= "' . $select . '"';
		case 'BatchSets'		: return ' AND      BatchOuts.status		= "' . $select . '"';
		case 'Boxes'			: return ' AND          Boxes.status		= "' . $select . '"';
//		case 'Categories'		: return ' AND         Parent.category		= "' . $select . '"';
		case 'CheckOuts'		: return ' AND      CheckOuts.status		= "' . $select . '"';
		case 'Colors'			: return ' AND         Colors.color_type	= "' . $select . '"';
		case 'Configs'			: return ' AND        Configs.group_set		= "' . $select . '"';
		case 'Contacts'			: return ' AND       Contacts.status		= "' . $select . '"';
		case 'Controls'			: return ' AND       Controls.group_set		= "' . $select . '"';
		case 'Cylinders'		: return ' AND      Cylinders.machine_id	=  ' . $select;
		case 'FTPs'				: return ' AND           FTPs.collection	= "' . $select . '"';
		case 'FTP_Loads'		: return ' AND      FTP_Loads.parent_id		=  ' . $select;
		case 'FTP_Threads'		: return ' AND    FTP_Threads.parent_id		=  ' . $select;
		case 'FTP_Sets'			: return ' AND       FTP_Sets.parent_id		=  ' . $select;
		case 'History'			: return ' AND        History.parent_name	= "' . $select . '"';
		case 'Incomings'		: return ' AND      Incomings.status		= "' . $select . '"';
		case 'LoadIns'			: return ' AND        LoadIns.status		= "' . $select . '"';
		case 'LoadOuts'			: return ' AND       LoadOuts.status		= "' . $select . '"';
		case 'LoadQuotations'	: return ' AND LoadQuotations.status		= "' . $select . '"';
		case 'LoadSets'			: return ' AND       LoadSets.status		= "' . $select . '"';
		case 'Machines'			: return ' AND       Machines.machine_brand	= "' . $select . '"';
		case 'Orders'			: return ' AND         Orders.status		= "' . $select . '"';
		case 'OrdThreads'		: return ' AND     OrdThreads.parent_id		=  ' . $select;
		case 'OSAs'				: return ' AND           OSAs.status		= "' . $select . '"';
		case 'OSA_Lines'		: return ' AND      OSA_Lines.parent_id		=  ' . $select;
		case 'OSA_Colors'		: return ' AND     OSA_Colors.parent_id		=  ' . $select;
		case 'Permissions'		: return ' AND    Permissions.user_role		= "' . $select . '"';
		case 'Pieces'			: return ' AND         Pieces.status		= "' . $select . '"';
		case 'ProdPrices'		: return ' AND     ProdPrices.status		= "' . $select . '"';
		case 'Products'			: return ' AND       Products.product_type	= "' . $select . '"';
		case 'Purchases'		: return ' AND      Purchases.status		= "' . $select . '"';
		case 'PurchaseLines'	: return ' AND      Purchases.status		= "' . $select . '"';
		case 'Quotations'		: return ' AND     Quotations.status		= "' . $select . '"';
		case 'QuotLines'		: return ' AND      QuotLines.parent_id		=  ' . $select;
		case 'QuotColors'		: return ' AND     QuotColors.parent_id		=  ' . $select;
		case 'QuotProducts'		: return ' AND      Quotation.status		= "' . $select . '"';
		case 'Sales'			: return ' AND          Sales.status		= "' . $select . '"';
		case 'SaleLines'		: return ' AND      SaleLines.parent_id		=  ' . $select;
		case 'SaleColors'		: return ' AND     SaleColors.parent_id		=  ' . $select;
		case 'ReceiveDyers'		: return ' AND   ReceiveDyers.status		= "' . $select . '"';
		case 'ReqLines'			: return ' AND       ReqLines.request_id	=  ' . $select;
		case 'ShipDyers'		: return ' AND      ShipDyers.status		= "' . $select . '"';
		case 'TDyers'			: return ' AND         TDyers.status		= "' . $select . '"';
		case 'TDyerColors'		: return ' AND    TDyerColors.parent_id		=  ' . $select;
		case 'TDyerThreads'		: return ' AND   TDyerThreads.parent_id		=  ' . $select;
		case 'Templates'		: return ' AND      Templates.status		= "' . $select . '"';
		case 'ThreadForecast'	: return ' AND        Threads.thread_group	= "' . $select . '"';
		case 'Threads'			: return ' AND        Threads.thread_group	= "' . $select . '"';
		case 'Tickets'			: return ' AND        Tickets.status		= "' . $select . '"';
		case 'Translations'		: return ' AND   Translations.status		= "' . $select . '"';
	}
	return '';
}

private function set_new_fields($table) {
	$return = '';
	if ($table == 'Categories'		)	$return = ',    Parent.category			AS    parent_name';
	if ($table == 'Companies'		)	$return = ',   Contact.full_name		AS   contact_name';
	if ($table == 'Templates'		)	$return = ',   Updated.full_name		AS   updated_name';
	if ($table == 'Tickets'			)	$return = ',    Opened.full_name		AS    opened_name'
												. ',    Closed.full_name		AS    closed_name'
												. ',  Assigned.full_name		AS  assigned_name';

	if ($table == 'Contacts'		)	$return = ', JKY_Users.id				AS      user_id'
												. ', JKY_Users.user_name		AS      user_name'
												. ', JKY_Users.user_role		AS      user_role'
												. ', Companies.full_name		AS   company_name';
	if ($table == 'FTPs'			)	$return = ',  Products.product_name		AS   product_name'
												. ',  Machines.name				AS   machine_name';
	if ($table == 'FTP_Loads'		)	$return = ',   Thread1.name				AS    thread_name_1'
												. ',   Thread2.name				AS    thread_name_2'
												. ',   Thread3.name				AS    thread_name_3'
												. ',   Thread4.name				AS    thread_name_4';
	if ($table == 'FTP_Threads'		)	$return = ',   Threads.name				AS           name'
												. ',  Supplier.nick_name		AS           supplier';
	if ($table == 'FTP_Sets'		)	$return = ',   Configs.sequence			AS           sequence'
												. ',   Configs.name				AS           name';
	if ($table == 'History'			)	$return = ',  Contacts.full_name		AS   updated_name';
	if ($table == 'LoadIns'			)	$return = ',   Product.product_name		AS   product_name';
	if ($table == 'LoadOuts'		)	$return = ',      Dyer.nick_name		AS      dyer_name'
												. ',     Color.color_name		AS     color_name';
	if ($table == 'LoadQuotations'	)	$return = ',   LoadOut.loadout_number	AS   loadout_number'
												. ',   LoadOut.requested_at		AS requested_at'
												. ', Quotation.quotation_number	AS quotation_number'
												. ',      Dyer.nick_name		AS      dyer_name'
												. ',     Color.id				AS     color_id'
												. ',     Color.color_name		AS     color_name'
												. ',  Customer.nick_name		AS  customer_name'
												. ',   Product.id				AS   product_id'
												. ',   Product.product_name		AS   product_name';
	if ($table == 'LoadSets'		)	$return = ',   LoadOut.loadout_number	AS   loadout_number'
												. ',   LoadOut.requested_at		AS requested_at'
												. ',   LoadOut.checkout_at		AS  checkout_at'
												. ',      Dyer.nick_name		AS      dyer_name'
												. ',     Color.color_name		AS     color_name'
												. ', Quotation.quotation_number	AS quotation_number'
												. ',  Customer.nick_name		AS  customer_name'
												. ',   Product.id				AS   product_id'
												. ',   Product.product_name		AS   product_name'
//												. ', QuotColor.quoted_pieces	AS      sold_pieces';
							. ', CEIL(QuotColor.quoted_units / QuotLine.units)	AS      sold_pieces';
	if ($table == 'Orders'			)	$return = ',  Customer.nick_name		AS  customer_name'
												. ',   Machine.name				AS   machine_name'
												. ',   Partner.nick_name		AS   partner_name'
												. ',     Color.color_name		AS     color_name'
												. ',       FTP.ftp_number		AS       ftp_number'
												. ',   Product.product_name		AS   product_name'
												. ', Quotation.quotation_number	AS quotation_number';
	if ($table == 'OrdThreads'		)	$return = ',    Orderx.order_number		AS 	   order_number'
												. ',    Thread.name				AS    thread_name'
												. ',   BatchIn.batch			AS     batch_code';
	if ($table == 'OSAs'			)	$return = ',  Salesman.full_name		AS  salesman_name'
												. ',  Customer.nick_name		AS  customer_name'
												. ', Quotation.quotation_number	AS quotation_number'
												. ', Quotation.produce_from_date	AS produce_from_date'
												. ', Quotation.produce_to_date		AS produce_to_date';
	if ($table == 'OSA_Lines'		)	$return = ',   Product.product_name		AS   product_name'
												. ',   Product.weight_dyer		AS    weight_dyer'
												. ',   Product.width_dyer		AS     width_dyer';
	if ($table == 'OSA_Colors'		)	$return = ',   Machine.name				AS   machine_name'
												. ',   Partner.nick_name		AS   partner_name'
												. ',     Color.color_name		AS     color_name'
												. ',     Color.color_type		AS     color_type'
												. ',       FTP.ftp_number		AS       ftp_number';
	if ($table == 'Pieces'			)	$return = ',    Orderx.order_number		AS     order_number'
												. ',   Revised.nick_name		AS   revised_name'
												. ',   Weighed.nick_name		AS   weighed_name'
												. ',   LoadOut.loadout_number   AS   loadout_number';
	if ($table == 'Products'		)	$return = ',    Parent.product_name		AS    parent_name';
	if ($table == 'Purchases'		)	$return = ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'PurchaseLines'	)	$return = ', Purchases.purchase_number	AS  purchase_number'
												. ', Purchases.ordered_at		AS   ordered_at'
												. ', Purchases.supplier_id		AS  supplier_id'
												. ',   Threads.name				AS    thread_name'
//												. ',   Batches.received_weight	AS  received_weight'
												. ', Incomings.received_at		AS  received_at'
												. ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'Quotations'		)	$return = ',  Salesman.full_name		AS  salesman_name'
												. ',  Customer.nick_name		AS  customer_name'
												. ',   Contact.nick_name		AS   contact_name'
												. ',   Contact.mobile			AS   contact_mobile';
//												. ',   Machine.name				AS   machine_name'
//												. ',      Dyer.nick_name		AS      dyer_name'
//												. ',     Punho.product_name		AS     punho_name'
//												. ',      Gola.product_name		AS      gola_name'
//												. ',     Galao.product_name		AS     galao_name';
	if ($table == 'QuotLines'		)	$return = ',   Product.product_name		AS   product_name'
												. ',   Machine.name				AS   machine_name';
	if ($table == 'QuotColors'		)	$return = ',QuotColors.quoted_units		AS    quoted_units'
												. ',     Color.color_name		AS     color_name'
												. ',     Color.color_type		AS     color_type'
												. ',      Dyer.nick_name		AS      dyer_name'
												. ',  QuotLine.product_id		AS	 product_id'
												. ',  QuotLine.machine_id		AS	 machine_id'
												. ',  QuotLine.peso				AS			 peso'
												. ',  QuotLine.units			AS			 units'
												. ', Quotation.quotation_number	AS quotation_number'
												. ', Quotation.quoted_at		AS    quoted_at'
												. ',   Product.product_name		AS   product_name'
												. ',   Product.product_type		AS   product_type'
												. ',  Customer.nick_name		AS  customer_name';
	if ($table == 'Sales'			)	$return = ',  Salesman.full_name		AS   salesman_name'
												. ',  Customer.nick_name		AS  customer_name'
												. ',   Contact.nick_name		AS   contact_name'
												. ',   Contact.mobile			AS   contact_mobile'
												. ', Quotation.quotation_number	AS quotation_number';
	if ($table == 'SaleLines'		)	$return = ',   Product.product_name		AS   product_name'
												. ',   Machine.name				AS   machine_name';
	if ($table == 'SaleColors'		)	$return = ',SaleColors.quoted_units		AS    quoted_units'
												. ',     Color.color_name		AS     color_name'
												. ',     Color.color_type		AS     color_type'
												. ',      Dyer.nick_name		AS      dyer_name'
												. ',  SaleLine.product_id		AS	 product_id'
												. ',  SaleLine.machine_id		AS	 machine_id'
												. ',  SaleLine.peso				AS			 peso'
												. ',  SaleLine.units			AS			 units'
												. ',      Sale.sale_number		AS      sale_number'
												. ',      Sale.sold_date		AS      sold_date'
												. ',   Product.product_name		AS   product_name'
												. ',  Customer.nick_name		AS  customer_name';
	if ($table == 'ShipDyers'		)	$return = ',      Dyer.nick_name		AS      dyer_name'
												. ', Transport.nick_name		AS transport_name';
	if ($table == 'Incomings'		)	$return = ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'Batches'			)	$return = ',   Threads.name				AS           name'
												. ', Incomings.incoming_number	AS  incoming_number'
												. ', Incomings.nfe_dl			AS       nfe_dl'
												. ', Incomings.nfe_tm			AS       nfe_tm'
												. ', Incomings.received_at		AS  received_at'
												. ', Incomings.invoice_date		AS   invoice_date'
												. ',  Supplier.nick_name		AS  supplier_name'
												. ', Purchases.purchase_number	AS  purchase_number';
	if ($table == 'Boxes'			)	$return = ',   Batches.batch			AS     batch_code'
												. ',    Parent.barcode			AS           parent'
												. ',   CheckIn.nick_name		AS           checkin'
												. ',  CheckOut.nick_name		AS           checkout'
												. ',  Returned.nick_name		AS           returned'
												. ',   Threads.id				AS    thread_id'
												. ',   Threads.name				AS    thread_name'
												. ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'Requests'		)	$return = ',  Machines.name				AS   machine_name'
												. ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'ReqLines'		)	$return = ',  Requests.number			AS   request_number'
												. ',  Requests.ordered_at		AS   ordered_at'
												. ',  Requests.machine_id		AS   machine_id'
												. ',  Requests.supplier_id		AS  supplier_id'
												. ',   Threads.name				AS    thread_name'
												. ',   Batches.batch			AS     batch_code'
//												. ', BatchOuts.checkout_weight	AS  checkout_weight'
												. ', CheckOuts.checkout_at		AS  checkout_at'
												. ',  Machines.name				AS   machine_name'
												. ',  Supplier.nick_name		AS  supplier_name';
	if ($table == 'CheckOuts'		)	$return = ',  Machines.name				AS   machine_name'
												. ',   Partner.nick_name		AS   partner_name'
												. ',  Supplier.nick_name		AS  supplier_name'
												. ',      Dyer.nick_name		AS      dyer_name';
	if ($table == 'BatchOuts'		)	$return = ',   Threads.name				AS	  thread_name'
												. ',   Batches.batch			AS     batch_code'
												. ', CheckOuts.number			AS  checkout_number'
												. ', CheckOuts.requested_at		AS requested_at'
												. ', CheckOuts.checkout_at		AS  checkout_at'
												. ',  Machines.name				AS   machine_name'
												. ',   Partner.nick_name		AS  partner_name'
												. ',  Supplier.nick_name		AS  supplier_name'
												. ',      Dyer.nick_name		AS      dyer_name';
	if ($table == 'BatchSets'		)	$return = ', BatchOuts.average_weight	AS   average_weight'
												. ', BatchOuts.requested_weight	AS requested_weight'
												. ', BatchOuts.checkout_weight	AS  checkout_weight'
												. ', BatchOuts.scheduled_date	AS scheduled_date'
												. ',   Threads.name				AS    thread_name'
												. ',   Batches.batch			AS     batch_code'
												. ', CheckOuts.number			AS  checkout_number'
												. ', CheckOuts.requested_at		AS requested_at'
												. ', CheckOuts.checkout_at		AS  checkout_at'
												. ',  Machines.name				AS   machine_name'
												. ',   Partner.nick_name		AS   partner_name'
												. ',  Supplier.nick_name		AS  supplier_name'
												. ',      Dyer.nick_name		AS      dyer_name';
	if ($table == 'ReceiveDyers'	)	$return = ',      Dyer.nick_name		AS      dyer_name';
	if ($table == 'TDyers'			)	$return = ',    Orderx.order_number		AS	   order_number'
												. ',  Customer.nick_name		AS  customer_name'
												. ',      Dyer.nick_name		AS      dyer_name';
	if ($table == 'TDyerThreads'	)	$return = ',    Thread.name				AS    thread_name'
												. ',   BatchIn.batch			AS   batchin_code';
	if ($table == 'TDyerColors'		)	$return = ',     Color.color_name		AS     color_name';
	if ($table == 'ThreadForecast'	)	$return = ',  Contacts.nick_name		AS  supplier_name'
												. ',   Threads.thread_group		AS    thread_group'
												. ',   Threads.name				AS    thread_name'
												. ',   Threads.composition		AS           composition'
												. ',   Configs.sequence			AS    thread_sequence';
	if ($table == 'Translations'	)	$return = ', Translated.sentence		AS           translated';

//	special code to append fields from Contacts to Services table
	if (get_request('method') == 'export') {
		if ($table   == 'Services') {
			$sql  = 'SHOW COLUMNS FROM Contacts WHERE Field != "id" AND Field != "updated_by" AND Field != "updated_at" AND Field != "status" AND Field != "completed"';
			$db   = Zend_Registry::get( 'db' );
			$cols = $db->fetchAll( $sql );
			foreach($cols as $col) {
				$return .= ', Contacts.' . $col['Field'] . ' AS ' . $col['Field'];
			}
		}
	}

	return $return;
}

private function set_left_joins($table) {
	$return = '';
	if ($table == 'Categories'		)	$return = '  LEFT JOIN  Categories AS Parent	ON    Parent.id	=	   Categories.parent_id';
	if ($table == 'Companies'		)	$return = '  LEFT JOIN    Contacts AS Contact	ON   Contact.id	=		Companies.contact_id';
	if ($table == 'Templates'		)	$return = '  LEFT JOIN   JKY_Users AS User		ON      User.id =		Templates.updated_by'
												. '  LEFT JOIN    Contacts AS Updated	ON   Updated.id	=		     User.contact_id';
	if ($table == 'Tickets'			)	$return = '  LEFT JOIN   JKY_Users AS User_Op	ON   User_Op.id	=		  Tickets.opened_by'
												. '  LEFT JOIN   JKY_Users AS User_As	ON   User_As.id	=		  Tickets.assigned_to'
												. '  LEFT JOIN   JKY_Users AS User_Cl	ON   User_Cl.id	=		  Tickets.closed_by'
												. '  LEFT JOIN    Contacts AS Opened	ON    Opened.id	=		  User_Op.contact_id'
												. '  LEFT JOIN    Contacts AS Assigned	ON  Assigned.id	=		  User_As.contact_id'
												. '  LEFT JOIN    Contacts AS Closed 	ON    Closed.id	=		  User_Cl.contact_id';

	if ($table == 'Contacts'		)	$return = '  LEFT JOIN   JKY_Users AS JKY_Users	ON  Contacts.id	=		JKY_Users.contact_id'
//												. '  LEFT JOIN    Contacts AS Companies	ON Companies.id	=		 Contacts.company_id AND Companies.is_company = "Yes"';
												. '  LEFT JOIN    Contacts AS Companies	ON Companies.id	=		 Contacts.company_id';
	if ($table == 'FTPs'			)	$return = '  LEFT JOIN    Products				ON  Products.id	=			 FTPs.product_id'
												. '  LEFT JOIN    Machines				ON  Machines.id	=			 FTPs.machine_id';
	if ($table == 'FTP_Loads'		)	$return = '  LEFT JOIN     Threads AS Thread1	ON   Thread1.id	=	    FTP_Loads.thread_id_1'
												. '  LEFT JOIN     Threads AS Thread2	ON   Thread2.id	=	    FTP_Loads.thread_id_2'
												. '  LEFT JOIN     Threads AS Thread3	ON   Thread3.id	=		FTP_Loads.thread_id_3'
												. '  LEFT JOIN     Threads AS Thread4	ON   Thread4.id	=		FTP_Loads.thread_id_4';
	if ($table == 'FTP_Threads'		)	$return = '  LEFT JOIN     Threads  			ON   Threads.id	=	  FTP_Threads.thread_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=	  FTP_Threads.supplier_id';
	if ($table == 'FTP_Sets'		)	$return = '  LEFT JOIN     Configs  			ON   Configs.id	=		  FTP_Sets.setting_id';
	if ($table == 'History'			)	$return = '  LEFT JOIN   JKY_Users AS Users		ON     Users.id =		   History.updated_by'
												. '  LEFT JOIN    Contacts				ON  Contacts.id =			 Users.contact_id';
	if ($table == 'LoadIns'			)	$return = '  LEFT JOIN    Products AS Product	ON   Product.id	=		   LoadIns.product_id';
	if ($table == 'LoadOuts'		)	$return = '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		  LoadOuts.dyer_id'
												. '  LEFT JOIN      Colors AS Color		ON     Color.id	=		  LoadOuts.color_id';
	if ($table == 'LoadQuotations'	)	$return = '  LEFT JOIN    LoadOuts AS LoadOut	ON   LoadOut.id	=	LoadQuotations.loadout_id'
												. '  LEFT JOIN  QuotColors AS QuotColor	ON QuotColor.id	=	LoadQuotations.quot_color_id'
												. '  LEFT JOIN    Contacts AS Dyer      ON      Dyer.id =          LoadOut.dyer_id'
												. '  LEFT JOIN   QuotLines AS QuotLine	ON  QuotLine.id	=		 QuotColor.parent_id'
												. '  LEFT JOIN      Colors AS Color     ON     Color.id =        QuotColor.color_id'
												. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=		  QuotLine.parent_id'
												. '  LEFT JOIN    Products AS Product	ON   Product.id	=		  QuotLine.product_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		 Quotation.customer_id';
	if ($table == 'LoadSets'		)	$return = '  LEFT JOIN LoadQuotations AS LoadQuot ON LoadQuot.id =        LoadSets.load_quot_id'
												. '  LEFT JOIN    LoadOuts AS LoadOut	ON   LoadOut.id	=		  LoadQuot.loadout_id'
												. '  LEFT JOIN  QuotColors AS QuotColor	ON QuotColor.id	=		  LoadQuot.quot_color_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		   LoadOut.dyer_id'
												. '  LEFT JOIN      Colors AS Color		ON     Color.id	=		 QuotColor.color_id'
												. '  LEFT JOIN   QuotLines AS QuotLine	ON  QuotLine.id	=		 QuotColor.parent_id'
												. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=		  QuotLine.parent_id'
												. '  LEFT JOIN    Products AS Product	ON   Product.id	=		  QuotLine.product_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		 Quotation.customer_id';
	if ($table == 'Orders'			)	$return = '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		    Orders.customer_id'
												. '  LEFT JOIN    Machines AS Machine	ON   Machine.id	=		    Orders.machine_id'
												. '  LEFT JOIN    Contacts AS Partner	ON   Partner.id	=		    Orders.partner_id'
												. '  LEFT JOIN    Products AS Product	ON   Product.id	=		    Orders.product_id'
												. '  LEFT JOIN      Colors AS Color		ON     Color.id	=		    Orders.color_id'
												. '  LEFT JOIN        FTPs AS FTP		ON       FTP.id	=		    Orders.ftp_id'
												. '  LEFT JOIN        OSAs				ON      OSAs.id	=		    Orders.osa_number'
												. '  LEFT JOIN  Quotations AS Quotation ON Quotation.id	=		      OSAs.quotation_id';
	if ($table == 'OrdThreads'		)	$return = '  LEFT JOIN      Orders AS Orderx 	ON    Orderx.id	=		OrdThreads.parent_id'
												. '  LEFT JOIN     Threads AS Thread	ON    Thread.id	=		OrdThreads.thread_id'
												. '  LEFT JOIN     Batches AS BatchIn	ON   BatchIn.id	=		OrdThreads.batchin_id';
	if ($table == 'OSAs'			)	$return = '  LEFT JOIN  Quotations AS Quotation ON Quotation.id	=		      OSAs.quotation_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		      OSAs.customer_id'
												. '  LEFT JOIN    Contacts AS Salesman  ON  Salesman.id =             OSAs.salesman_id';
	if ($table == 'OSA_Lines'		)	$return = '  LEFT JOIN    Products AS Product	ON   Product.id	=	     OSA_Lines.product_id';
	if ($table == 'OSA_Colors'		)	$return = '  LEFT JOIN    Machines AS Machine	ON   Machine.id	=		OSA_Colors.machine_id'
												. '  LEFT JOIN    Contacts AS Partner	ON   Partner.id	=		OSA_Colors.partner_id'
												. '  LEFT JOIN      Colors AS Color		ON     Color.id	=		OSA_Colors.color_id'
												. '  LEFT JOIN        FTPs AS FTP		ON       FTP.id	=		OSA_Colors.ftp_id';
	if ($table == 'Pieces'			)	$return = '  LEFT JOIN      Orders AS Orderx 	ON    Orderx.id	=		    Pieces.order_id'
												. '  LEFT JOIN    Contacts AS Revised	ON   Revised.id	=		    Pieces.revised_by'
												. '  LEFT JOIN    Contacts AS Weighed	ON   Weighed.id	=		    Pieces.weighed_by'
												. '  LEFT JOIN LoadQuotations AS LoadQuot ON LoadQuot.id =          Pieces.load_quot_id'
												. '  LEFT JOIN    LoadOuts AS LoadOut   ON   LoadOut.id	=         LoadQuot.loadout_id';
	if ($table == 'Products'		)	$return = '  LEFT JOIN    Products AS Parent	ON    Parent.id	=		  Products.parent_id';
	if ($table == 'Purchases'		)	$return = '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 Purchases.supplier_id';
	if ($table == 'PurchaseLines'	)	$return = '  LEFT JOIN   Purchases  			ON Purchases.id	=	 PurchaseLines.parent_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=	 PurchaseLines.thread_id'
												. '  LEFT JOIN     Batches  			ON   Batches.id	=	 PurchaseLines.batch_id'
												. '  LEFT JOIN   Incomings				ON Incomings.id	=		   Batches.incoming_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 Purchases.supplier_id';
	if ($table == 'Quotations'		)	$return = '  LEFT JOIN    Contacts AS Salesman	ON  Salesman.id	=		Quotations.salesman_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		Quotations.customer_id'
												. '  LEFT JOIN    Contacts AS Contact	ON   Contact.id	=		Quotations.contact_id';
	if ($table == 'QuotLines'		)	$return = '  LEFT JOIN    Products AS Product	ON   Product.id	=	     QuotLines.product_id'
												. '  LEFT JOIN    Machines AS Machine	ON   Machine.id	=		 QuotLines.machine_id';
	if ($table == 'QuotColors'		)	$return = '  LEFT JOIN      Colors AS Color 	ON     Color.id	=	    QuotColors.color_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON		Dyer.id	=		QuotColors.dyer_id'
												. '  LEFT JOIN   QuotLines AS QuotLine	ON  QuotLine.id	=		QuotColors.parent_id'
												. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=		  QuotLine.parent_id'
												. '  LEFT JOIN    Products AS Product	ON   Product.id	=		  QuotLine.product_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		 Quotation.customer_id';
	if ($table == 'Sales'			)	$return = '  LEFT JOIN    Contacts AS Salesman  ON  Salesman.id =            Sales.salesman_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		     Sales.customer_id'
												. '  LEFT JOIN    Contacts AS Contact	ON   Contact.id	=		     Sales.contact_id'
												. '  LEFT JOIN  Quotations AS Quotation	ON Quotation.id	=		     Sales.quotation_id';
	if ($table == 'SaleLines'		)	$return = '  LEFT JOIN    Products AS Product	ON   Product.id	=	     SaleLines.product_id'
												. '  LEFT JOIN    Machines AS Machine	ON   Machine.id	=		 SaleLines.machine_id';
	if ($table == 'SaleColors'		)	$return = '  LEFT JOIN      Colors AS Color 	ON     Color.id	=	    SaleColors.color_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON		Dyer.id	=		SaleColors.dyer_id'
												. '  LEFT JOIN   SaleLines AS SaleLine	ON  SaleLine.id	=		SaleColors.parent_id'
												. '  LEFT JOIN       Sales AS Sale		ON      Sale.id	=		  SaleLine.parent_id'
												. '  LEFT JOIN    Products AS Product	ON   Product.id	=		  SaleLine.product_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		      Sale.customer_id';
	if ($table == 'ShipDyers'		)	$return = '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		 ShipDyers.dyer_id'
												. '  LEFT JOIN    Contacts AS Transport	ON Transport.id	=		 ShipDyers.transport_id';
	if ($table == 'Incomings'		)	$return = '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 Incomings.supplier_id';
	if ($table == 'Batches'			)	$return = '  LEFT JOIN   Incomings  			ON Incomings.id	=		   Batches.incoming_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 Incomings.supplier_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=		   Batches.thread_id'
												. '  LEFT JOIN PurchaseLines AS PLines  ON    PLines.id	=		   Batches.purchase_line_id'
												. '  LEFT JOIN   Purchases				ON Purchases.id	=		    PLines.parent_id';
	if ($table == 'Boxes'			)	$return = '  LEFT JOIN     Batches  			ON   Batches.id	=		     Boxes.batch_id'
												. '  LEFT JOIN       Boxes AS Parent	ON    Parent.id	=		     Boxes.parent_id'
												. '  LEFT JOIN    Contacts AS CheckIn	ON   CheckIn.id	=			 Boxes.checkin_by'
												. '  LEFT JOIN    Contacts AS CheckOut	ON  CheckOut.id	=			 Boxes.checkout_by'
												. '  LEFT JOIN    Contacts AS Returned	ON  Returned.id	=			 Boxes.returned_by'
												. '  LEFT JOIN   Incomings  			ON Incomings.id	=		   Batches.incoming_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=		   Batches.thread_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 Incomings.supplier_id';
	if ($table == 'Requests'		)	$return = '  LEFT JOIN    Machines				ON  Machines.id	=		  Requests.machine_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		  Requests.supplier_id';
	if ($table == 'ReqLines'		)	$return = '  LEFT JOIN    Requests  			ON  Requests.id	=		  ReqLines.request_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=		  ReqLines.thread_id'
												. '  LEFT JOIN     Batches  			ON   Batches.id	=		  ReqLines.batchin_id'
												. '  LEFT JOIN   BatchOuts  			ON BatchOuts.id	=		  ReqLines.batch_id'
												. '  LEFT JOIN   CheckOuts				ON CheckOuts.id	=		 BatchOuts.checkout_id'
												. '  LEFT JOIN    Machines				ON  Machines.id	=		  Requests.machine_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		  Requests.supplier_id';
	if ($table == 'CheckOuts'		)	$return = '  LEFT JOIN    Machines				ON  Machines.id	=		 CheckOuts.machine_id'
												. '  LEFT JOIN    Contacts AS Partner	ON   Partner.id	=		 CheckOuts.partner_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 CheckOuts.supplier_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		 CheckOuts.dyer_id';
	if ($table == 'BatchOuts'		)	$return = '  LEFT JOIN   CheckOuts  			ON CheckOuts.id	=		 BatchOuts.checkout_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=		 BatchOuts.thread_id'
												. '  LEFT JOIN     Batches  			ON   Batches.id	=		 BatchOuts.batchin_id'
												. '  LEFT JOIN    ReqLines  			ON  ReqLines.id	=		 BatchOuts.req_line_id'
												. '  LEFT JOIN    Machines				ON  Machines.id	=		 CheckOuts.machine_id'
												. '  LEFT JOIN    Contacts AS Partner	ON   Partner.id	=		 CheckOuts.partner_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 BatchOuts.supplier_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		 CheckOuts.dyer_id';
	if ($table == 'BatchSets'		)	$return = '  LEFT JOIN   BatchOuts				ON BatchOuts.id	=		 BatchSets.batchout_id'
												. '  LEFT JOIN   CheckOuts  			ON CheckOuts.id	=		 BatchOuts.checkout_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=		 BatchOuts.thread_id'
												. '  LEFT JOIN     Batches  			ON   Batches.id	=		 BatchOuts.batchin_id'
												. '  LEFT JOIN    ReqLines  			ON  ReqLines.id	=		 BatchOuts.req_line_id'
												. '  LEFT JOIN    Machines				ON  Machines.id	=		 CheckOuts.machine_id'
												. '  LEFT JOIN    Contacts AS Partner	ON   Partner.id	=		 CheckOuts.partner_id'
												. '  LEFT JOIN    Contacts AS Supplier	ON  Supplier.id	=		 CheckOuts.supplier_id'
												. '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=		 CheckOuts.dyer_id';
	if ($table == 'ReceiveDyers'	)	$return = '  LEFT JOIN    Contacts AS Dyer		ON      Dyer.id	=	  ReceiveDyers.dyer_id';
	if ($table == 'TDyers'			)	$return = '  LEFT JOIN      Orders AS Orderx	ON    Orderx.id	=		    TDyers.order_id'
												. '  LEFT JOIN    Contacts AS Customer	ON  Customer.id	=		    TDyers.customer_id'
												. '  LEFT JOIN    Contacts AS Dyer    	ON      Dyer.id	=		    TDyers.dyer_id';
	if ($table == 'TDyerThreads'	)	$return = '  LEFT JOIN     Threads AS Thread	ON    Thread.id	=	  TDyerThreads.thread_id'
												. '  LEFT JOIN     Batches AS BatchIn	ON   BatchIn.id	=	  TDyerThreads.batchin_id';
	if ($table == 'TDyerColors'		)	$return = '  LEFT JOIN      Colors AS Color 	ON     Color.id	=	   TDyerColors.color_id';
	if ($table == 'ThreadForecast'	)	$return = '  LEFT JOIN    Contacts  			ON  Contacts.id	=	ThreadForecast.supplier_id'
												. '  LEFT JOIN     Threads  			ON   Threads.id	=	ThreadForecast.thread_id'
												. '  LEFT JOIN	   Configs  			ON   Configs.name = Threads.thread_group AND Configs.group_set = "Thread Groups"';
	if ($table == 'Translations'	)	$return = '  LEFT JOIN Translations AS Translated ON Translated.parent_id = Translations.parent_id AND Translated.locale = "pt_BR"';
	return $return;
}

private function set_where($table, $filter) {
	$filter = strtolower($filter);
	$filter = trim($filter);
	if ($filter == '')		return '';

	$names = explode('=', $filter, 2);
	if (count($names) == 2) {
		$name  =        trim( $names[ 0 ]);
		$value = '"%' . trim( $names[ 1 ]) . '%"';

		if ($table == 'Categories') {
			if ($name == 'sequence'
			or	$name == 'category') {
				if ($value == '"%null%"') {
					return ' AND Categories.' . $name . ' IS NULL ';
				}else{
					return ' AND Categories.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'parent_name') {
				if ($value == '"%null%"') {
					return ' AND Categories.parent_id  IS NULL';
				}else{
					return ' AND     Parent.category   LIKE ' . $value;
				}
			}
		}

		if ($table == 'BatchSets') {
			if ($name == 'checkin_location'
			or	$name == 'checkin_weight'
			or	$name == 'checkin_boxes'
			or	$name == 'reserved_boxes'
			or	$name == 'checkout_boxes') {
				if ($value == '"%null%"') {
					return ' AND BatchSets.' . $name . ' IS NULL ';
				}else{
					return ' AND   Batches.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Companies') {
			if ($name == 'company_name'
			or	$name == 'company_number'
			or	$name == 'phone'
			or	$name == 'fax'
			or	$name == 'street'
			or	$name == 'city'
			or	$name == 'state'
			or	$name == 'zip'
			or	$name == 'country') {
				if ($value == '"%null%"') {
					return ' AND Companies.' . $name . ' IS NULL ';
				}else{
					return ' AND Companies.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'contact_name') {
				if ($value == '"%null%"') {
					return ' AND Companies.contact_id  IS NULL';
				}else{
					return ' AND   Contact.full_name   LIKE ' . $value;
				}
			}
		}

		if ($table == 'Colors') {
			if ($name == 'color_code'
			or	$name == 'color_type'
			or	$name == 'color_name'
			or	$name == 'value') {
				if ($value == '"%null%"') {
					return ' AND Colors.' . $name . ' IS NULL ';
				}else{
					return ' AND Colors.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Controls') {
			if ($name == 'group_set'
			or	$name == 'sequence'
			or	$name == 'name'
			or	$name == 'value') {
				if ($value == '"%null%"') {
					return ' AND Controls.' . $name . ' IS NULL ';
				}else{
					return ' AND Controls.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Configs') {
			if ($name == 'group_set'
			or	$name == 'sequence'
			or	$name == 'name'
			or	$name == 'value') {
				if ($value == '"%null%"') {
					return ' AND Configs.' . $name . ' IS NULL ';
				}else{
					return ' AND Configs.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Permissions') {
			if ($name == 'user_role'
			or	$name == 'user_resource'
			or	$name == 'user_action'
			or	$name == 'status') {
				if ($value == '"%null%"') {
					return ' AND Permissions.' . $name . ' IS NULL ';
				}else{
					return ' AND Permissions.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Products') {
			if ($name == 'product_name'
			or	$name == 'product_type'
			or	$name == 'start_at') {
				if ($value == '"%null%"') {
					return ' AND Products.' . $name . ' IS NULL ';
				}else{
					return ' AND Products.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Receives') {
			if ($name == 'receive_on'
			or	$name == 'receive_amount'
			or	$name == 'set_amount'
			or	$name == 'document'
			or	$name == 'full_name'
			or	$name == 'email'
			or	$name == 'street'
			or	$name == 'zip'
			or	$name == 'city'
			or	$name == 'state'
			or	$name == 'country') {
				if ($value == '"%null%"') {
					return ' AND Receives.' . $name . ' IS NULL ';
				}else{
					return ' AND Receives.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'TDyers') {
			if ($name == 'tdyer_number'
			or	$name == 'ordered_at'
			or	$name == 'needed_at'
			or	$name == 'checkout_at'
			or	$name == 'returned_at'
			or	$name == 'ordered_weight'
			or	$name == 'checkout_weight'
			or	$name == 'returned_weight'
			or	$name == 'remarks') {
				if ($value == '"%null%"') {
					return ' AND TDyers.' . $name . ' IS NULL ';
				}else{
					return ' AND TDyers.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'order_number') {
				if ($value == '"%null%"') {
					return ' AND TDyers.order_id IS NULL';
				}else{
					return ' AND Orderx.order_number LIKE ' . $value;
				}
			}else
			if ($name == 'customer') {
				if ($value == '"%null%"') {
					return ' AND TDyers.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'dyer') {
				if ($value == '"%null%"') {
					return ' AND TDyers.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'Templates') {
			if ($name == 'updated_at'
			or	$name == 'template_name'
			or	$name == 'template_type'
			or	$name == 'template_subject'
			or	$name == 'template_body'
			or	$name == 'template_sql'
			or	$name == 'description'
			or	$name == 'status') {
				if ($value == '"%null%"') {
					return ' AND Templates.' . $name . ' IS NULL ';
				}else{
					return ' AND Templates.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'updated_by') {
				if ($value == '"%null%"') {
					return ' AND Templates.updated_by  IS NULL';
				}else{
					return ' AND   Updated.full_name   LIKE ' . $value;
				}
			}
		}

		if ($table == 'Tickets') {
			if ($name == 'opened_at'
			or	$name == 'priority'
			or	$name == 'category'
			or	$name == 'description'
			or	$name == 'resolution'
			or	$name == 'status') {
				if ($value == '"%null%"') {
					return ' AND Tickets.' . $name . ' IS NULL ';
				}else{
					return ' AND Tickets.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'opened_by') {
				if ($value == '"%null%"') {
					return ' AND  Tickets.opened_by    IS NULL';
				}else{
					return ' AND   Opened.full_name    LIKE ' . $value;
				}
			}
		}

		if ($table == 'Translations') {
			if( $name == 'sentence'
			or	$name == 'status') {
				if ($value == '"%null%"') {
					return ' AND Translations.' . $name . ' IS NULL ';
				}else{
					return ' AND Translations.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Contacts') {
			if ($name == 'nick_name'
			or  $name == 'first_name'
			or	$name == 'last_name'
			or	$name == 'full_name'
			or	$name == 'email'
			or	$name == 'mobile'
			or	$name == 'phone'
			or	$name == 'street'
			or	$name == 'city'
			or	$name == 'state'
			or	$name == 'zip'
			or	$name == 'country') {
				if ($value == '"%null%"') {
					return ' AND Contacts.' . $name . ' IS NULL ';
				}else{
					return ' AND Contacts.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'company_name') {
				if ($value == '"%null%"') {
					return ' AND Contacts.company_id IS NULL';
				}else{
					return ' AND Companies.full_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'FTPs') {
			if ($name == 'ftp_number'
			or	$name == 'diameter'
			or	$name == 'density'
			or	$name == 'inputs'
			or	$name == 'speed'
			or	$name == 'turns'
			or	$name == 'weight'
			or	$name == 'width'
//			or	$name == 'lanes'
//			or	$name == 'elasticity'
//			or	$name == 'needling'
			or	$name == 'peso'
			or	$name == 'has_break'
			or	$name == 'composition'
			or	$name == 'nick_name') {
				if ($value == '"%null%"') {
					return ' AND FTPs.' . $name . ' IS NULL ';
				}else{
					return ' AND FTPs.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND FTPs.product_id IS NULL';
				}else{
					return ' AND Products.product_name LIKE ' . $value;
				}
			}else
			if ($name == 'machine_name') {
				if ($value == '"%null%"') {
					return ' AND FTPs.machine_id IS NULL';
				}else{
					return ' AND Machines.name LIKE ' . $value;
				}
			}
		}

		if ($table == 'LoadOuts') {
			if ($name == 'loadout_number'
			or	$name == 'dyeing_type'
			or	$name == 'recipe'
			or	$name == 'requested_at'
			or	$name == 'quoted_pieces'
			or	$name == 'quoted_weight'
			or	$name == 'checkout_at'
			or	$name == 'checkout_pieces'
			or	$name == 'checkout_weight'
			or	$name == 'returned_at'
			or	$name == 'returned_pieces'
			or	$name == 'returned_weight') {
				if ($value == '"%null%"') {
					return ' AND LoadOuts.' . $name . ' IS NULL ';
				}else{
					return ' AND LoadOuts.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'dyer_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOuts.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'color_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOuts.color_id IS NULL';
				}else{
					return ' AND Color.color_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'LoadQuotations') {
			if ($name == 'requested_weight'
			or	$name == 'reserved_pieces'
			or	$name == 'reserved_weight'
			or	$name == 'checkout_pieces'
			or	$name == 'checkout_weight'
			or	$name == 'returned_pieces'
			or	$name == 'returned_weight') {
				if ($value == '"%null%"') {
					return ' AND LoadSets.' . $name . ' IS NULL ';
				}else{
					return ' AND LoadSets.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'loadout_number') {
				if ($value == '"%null%"') {
					return ' AND LoadQuotations.loadout_id IS NULL';
				}else{
					return ' AND LoadOut.loadout_number LIKE ' . $value;
				}
			}else
			if ($name == 'quotation_number') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.parent_id IS NULL';
				}else{
					return ' AND Quotation.quotation_number LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Quotation.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'dyer_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOut.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'color_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOut.color_id IS NULL';
				}else{
					return ' AND Color.color_name LIKE ' . $value;
				}
			}else
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.product_id IS NULL';
				}else{
					return ' AND Product.product_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'LoadSets') {
			if ($name == 'checkin_location'
			or	$name == 'checkin_date'
			or	$name == 'checkin_weight'
			or	$name == 'checkin_pieces'
			or	$name == 'reserved_pieces'
			or	$name == 'checkout_pieces') {
				if ($value == '"%null%"') {
					return ' AND LoadSets.' . $name . ' IS NULL ';
				}else{
					return ' AND LoadSets.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'loadout_number') {
				if ($value == '"%null%"') {
					return ' AND LoadSale.loadout_id IS NULL';
				}else{
					return ' AND LoadOut.loadout_number LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Sale.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'dyer_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOut.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'color_name') {
				if ($value == '"%null%"') {
					return ' AND LoadOut.color_id IS NULL';
				}else{
					return ' AND Color.color_name LIKE ' . $value;
				}
			}else
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND SaleLine.product_id IS NULL';
				}else{
					return ' AND Product.product_name LIKE ' . $value;
				}
			}
		}

		if ($table ==  'Machines') {
			if ($name == 'name'
			or	$name == 'machine_type'
			or	$name == 'machine_family'
			or	$name == 'machine_brand'
			or	$name == 'serial_number'
			or	$name == 'diameter'
			or	$name == 'width'
			or	$name == 'density'
			or	$name == 'inputs'
			or	$name == 'lanes'
			or	$name == 'purchase_date'
			or	$name == 'repair_date'
			or	$name == 'return_date') {
				if ($value == '"%null%"') {
					return ' AND Machines.' . $name . ' IS NULL ';
				}else{
					return ' AND Machines.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Threads') {
			if ($name == 'name'
			or	$name == 'thread_group'
			or	$name == 'composition') {
				if ($value == '"%null%"') {
					return ' AND Threads.' . $name . ' IS NULL ';
				}else{
					return ' AND Threads.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'History') {
			if ($name == 'updated_at'
			or	$name == 'updated_by'
			or	$name == 'parent_name'
			or	$name == 'parent_id'
			or	$name == 'method'
			or	$name == 'history') {
				if ($value == '"%null%"') {
					return ' AND History.' . $name . ' IS NULL ';
				}else{
					return ' AND History.' . $name . ' LIKE ' . $value;
				}
			}
		}

		if ($table == 'Orders') {
			if ($name == 'order_number'
			or	$name == 'ordered_at'
			or	$name == 'needed_at'
			or	$name == 'produced_at'
			or	$name == 'labels_printed'
			or	$name == 'ordered_pieces'
			or	$name == 'rejected_pieces'
			or	$name == 'produced_pieces') {
				if ($value == '"%null%"') {
					return ' AND Orders.' . $name . ' IS NULL ';
				}else{
					return ' AND Orders.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.product_id IS NULL';
				}else{
					return ' AND Product.product_name LIKE ' . $value;
				}
			}else
			if ($name == 'color_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.color_id IS NULL';
				}else{
					return ' AND Color.color_name LIKE ' . $value;
				}
			}else
			if ($name == 'ftp_number') {
				if ($value == '"%null%"') {
					return ' AND Orders.ftp_id IS NULL';
				}else{
					return ' AND FTP.ftp_number LIKE ' . $value;
				}
			}else
			if ($name == 'machine_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.machine_id IS NULL';
				}else{
					return ' AND Machine.name LIKE ' . $value;
				}
			}else
			if ($name == 'partner_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.partner_id IS NULL';
				}else{
					return ' AND Partner.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'OSAs') {
			if ($name == 'osa_number'
			or	$name == 'ordered_at'
			or	$name == 'needed_at'
			or	$name == 'produced_date'
			or	$name == 'delivered_date'
			or	$name == 'quoted_pieces'
			or	$name == 'ordered_pieces') {
				if ($value == '"%null%"') {
					return ' AND OSAs.' . $name . ' IS NULL ';
				}else{
					return ' AND OSAs.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'quotation_number') {
				if ($value == '"%null%"') {
					return ' AND OSAs.quotation_id IS NULL';
				}else{
					return ' AND Quotation.quotation_number LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Orders.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'salesman_name') {
				if ($value == '"%null%"') {
					return ' AND OSAs.salesman_id IS NULL';
				}else{
					return ' AND Salesman.full_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'Pieces') {
			if ($name == 'barcode'
			or	$name == 'product_name'
			or	$name == 'produced_by'
			or	$name == 'checkin_at'
			or	$name == 'returned_at'
			or	$name == 'checkout_at'
			or	$name == 'checkin_location'
			or	$name == 'returned_location'
			or	$name == 'checkout_location'
			or	$name == 'checkin_weight'
			or	$name == 'returned_weight'
			or	$name == 'qualities'
			or	$name == 'remarks') {
				if ($value == '"%null%"') {
					return ' AND Pieces.' . $name . ' IS NULL ';
				}else{
					return ' AND Pieces.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'revised') {
				if ($value == '"%null%"') {
					return ' AND Pieces.revised_by IS NULL';
				}else{
					return ' AND Revised.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'weighed') {
				if ($value == '"%null%"') {
					return ' AND Pieces.weighed_by IS NULL';
				}else{
					return ' AND Weighed.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'order_number') {
				if ($value == '"%null%"') {
					return ' AND Pieces.order_id IS NULL';
				}else{
					return ' AND Orderx.order_number LIKE ' . $value;
				}
			}
		}

		if ($table == 'Purchases') {
			if ($name == 'purchase_number'
			or	$name == 'source_doc'
			or	$name == 'ordered_at'
			or	$name == 'expected_date'
			or	$name == 'scheduled_at'
			or	$name == 'supplier_ref'
			or	$name == 'payment_term') {
				if ($value == '"%null%"') {
					return ' AND Purchases.' . $name . ' IS NULL ';
				}else{
					return ' AND Purchases.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'supplier_name') {
				if ($value == '"%null%"') {
					return ' AND Purchases.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'PurchaseLines') {
			if ($name == 'expected_date'
			or	$name == 'scheduled_at'
			or	$name == 'expected_weight') {
				if ($value == '"%null%"') {
					return ' AND PurchaseLines.' . $name . ' IS NULL ';
				}else{
					return ' AND PurchaseLines.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'purchase') {
				if ($value == '"%null%"') {
					return ' AND PurchaseLines.parent_id IS NULL';
				}else{
					return ' AND Purchases.number LIKE ' . $value;
				}
			}else
			if ($name == 'thread') {
				if ($value == '"%null%"') {
					return ' AND PurchaseLines.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'supplier') {
				if ($value == '"%null%"') {
					return ' AND Incomings.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'Quotations') {
			if ($name == 'quotation_number'
			or	$name == 'quoted_at'
			or	$name == 'produce_from_date'
			or	$name == 'produce_to_date'
			or	$name == 'delivered_date'
			or	$name == 'quoted_pieces'
			or	$name == 'produced_pieces'
			or	$name == 'delivered_pieces'
			or	$name == 'remarks') {
				if ($value == '"%null%"') {
					return ' AND Quotations.' . $name . ' IS NULL ';
				}else{
					return ' AND Quotations.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'salesman_name') {
				if ($value == '"%null%"') {
					return ' AND Quotations.salesman_id IS NULL';
				}else{
					return ' AND Salesman.full_name LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Quotations.customer_id IS NULL';
				}else{
					return ' AND Contacts.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'contact_name') {
				if ($value == '"%null%"') {
					return ' AND Quotations.contact_id IS NULL';
				}else{
					return ' AND Contacts.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'QuotProducts') {
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.product_id IS NULL';
				}else{
					return ' AND Product.product_name LIKE ' . $value;
				}
			}else
			if ($name == 'color_name') {
				if ($value == '"%null%"') {
					return ' AND QuotColors.color_id IS NULL';
				}else{
					return ' AND Color.color_name LIKE ' . $value;
				}
			}else
			if ($name == 'quotation_number') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.parent_id IS NULL';
				}else{
					return ' AND Quotation.quotation_number LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Quotation.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'machine_name') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.machine_id IS NULL';
				}else{
					return ' AND Machine.name LIKE ' . $value;
				}
			}else
			if ($name == 'quoted_date') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.parent_id IS NULL';
				}else{
					return ' AND Quotation.quoted_at LIKE ' . $value;
				}
			}
		}

		if ($table == 'QuotUnloadeds') {
			if ($name == 'quotation_number') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.parent_id IS NULL';
				}else{
					return ' AND Quotation.quotation_number LIKE ' . $value;
				}
			}else
			if ($name == 'product_name') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.product_id IS NULL';
				}else{
					return ' AND Product.product_name LIKE ' . $value;
				}
			}else
			if ($name == 'contact_name') {
				if ($value == '"%null%"') {
					return ' AND Quotation.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'quoted_date') {
				if ($value == '"%null%"') {
					return ' AND QuotLine.parent_id IS NULL';
				}else{
					return ' AND Quotation.quoted_at LIKE ' . $value;
				}
			}
		}

		if ($table == 'Sales') {
			if ($name == 'sale_number'
			or	$name == 'quoted_at'
			or	$name == 'produced_date'
			or	$name == 'expected_date'
			or	$name == 'delivered_date'
			or	$name == 'quoted_pieces'
			or	$name == 'produced_pieces'
			or	$name == 'delivered_pieces'
			or	$name == 'remarks') {
				if ($value == '"%null%"') {
					return ' AND Sales.' . $name . ' IS NULL ';
				}else{
					return ' AND Sales.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'sales_name') {
				if ($value == '"%null%"') {
					return ' AND Sales.salesman_id IS NULL';
				}else{
					return ' AND Salesman.full_name LIKE ' . $value;
				}
			}else
			if ($name == 'customer_name') {
				if ($value == '"%null%"') {
					return ' AND Sales.customer_id IS NULL';
				}else{
					return ' AND Customer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'contact_name') {
				if ($value == '"%null%"') {
					return ' AND Sales.contact_id IS NULL';
				}else{
					return ' AND Contact.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'quotation_number') {
				if ($value == '"%null%"') {
					return ' AND Sales.quotation_id IS NULL';
				}else{
					return ' AND Quotation.quotation_number LIKE ' . $value;
				}
			}
		}

		if ($table == 'ShipDyers') {
			if ($name == 'shipdyer_number'
			or	$name == 'invoice_number'
			or	$name == 'truck_license'
			or	$name == 'shipped_at'
			or	$name == 'delivered_at'
			or	$name == 'unit_name'
			or	$name == 'brand_name'
			or	$name == 'batch_code'
			or	$name == 'quantity'
			or	$name == 'gross_weight'
			or	$name == 'net_weight') {
				if ($value == '"%null%"') {
					return ' AND ShipDyers.' . $name . ' IS NULL ';
				}else{
					return ' AND ShipDyers.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'dyer_name') {
				if ($value == '"%null%"') {
					return ' AND ShipDyers.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'transport_name') {
				if ($value == '"%null%"') {
					return ' AND ShipDyers.transport_id IS NULL';
				}else{
					return ' AND Transport.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'Incomings') {
			if ($name == 'incoming_number'
			or	$name == 'received_at'
			or	$name == 'nfe_dl'
			or	$name == 'nfe_tm'
			or	$name == 'invoice_date'
			or	$name == 'invoice_weight'
			or	$name == 'invoice_amount'
			or	$name == 'received_weight'
			or	$name == 'received_amount') {
				if ($value == '"%null%"') {
					return ' AND Incomings.' . $name . ' IS NULL ';
				}else{
					return ' AND Incomings.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'supplier_name') {
				if ($value == '"%null%"') {
					return ' AND Incomings.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'Batches') {
			if ($name == 'code'
			or	$name == 'batch'
			or	$name == 'received_boxes'
			or	$name == 'checkin_boxes'
			or	$name == 'returned_boxes'
			or	$name == 'checkout_boxes'
			or	$name == 'unit_price'
			or	$name == 'average_weight'
			or	$name == 'received_weight'
			or	$name == 'checkin_weight'
			or	$name == 'returned_weight'
			or	$name == 'checkout_weight') {
				if ($value == '"%null%"') {
					return ' AND Batches.' . $name . ' IS NULL ';
				}else{
					return ' AND Batches.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'thread_name') {
				if ($value == '"%null%"') {
					return ' AND Batches.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'purchase_number') {
				if ($value == '"%null%"') {
					return ' AND Batches.purchase_line_id IS NULL';
				}else{
					return ' AND Incomings.number LIKE ' . $value;
				}
			}
		}

		if ($table == 'Boxes') {
			if ($name == 'barcode'
			or	$name == 'average_weight'
			or	$name == 'real_weight'
			or	$name == 'checkin_location'
			or	$name == 'checkout_location'
			or	$name == 'stoced_location') {
				if ($value == '"%null%"') {
					return ' AND Boxes.' . $name . ' IS NULL ';
				}else{
					return ' AND Boxes.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'thread') {
				if ($value == '"%null%"') {
					return ' AND Batches.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'supplier') {
				if ($value == '"%null%"') {
					return ' AND Incomings.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'batch_code') {
				if ($value == '"%null%"') {
					return ' AND Boxes.batch_id IS NULL';
				}else{
					return ' AND Batches.batch LIKE ' . $value;
				}
			}else
			if ($name == 'parent') {
				if ($value == '"%null%"') {
					return ' AND Boxes.parent_id IS NULL';
				}else{
					return ' AND Parent.barcode LIKE ' . $value;
				}
			}
		}

		if ($table == 'Requests') {
			if ($name == 'number'
			or	$name == 'source_doc'
			or	$name == 'ordered_at'
			or	$name == 'expected_date'
			or	$name == 'scheduled_at'
			or	$name == 'supplier_ref'
			or	$name == 'payment_term') {
				if ($value == '"%null%"') {
					return ' AND Requests.' . $name . ' IS NULL ';
				}else{
					return ' AND Requests.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'machine_name') {
				if ($value == '"%null%"') {
					return ' AND Requests.machine_id IS NULL';
				}else{
					return ' AND Machines.name LIKE ' . $value;
				}
			}else
			if ($name == 'supplier_name') {
				if ($value == '"%null%"') {
					return ' AND Requests.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'ReqLines') {
			if ($name == 'requested_date'
			or	$name == 'scheduled_at'
			or	$name == 'requested_weight') {
				if ($value == '"%null%"') {
					return ' AND ReqLines.' . $name . ' IS NULL ';
				}else{
					return ' AND ReqLines.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'request') {
				if ($value == '"%null%"') {
					return ' AND ReqLines.request_id IS NULL';
				}else{
					return ' AND Requests.number LIKE ' . $value;
				}
			}else
			if ($name == 'thread') {
				if ($value == '"%null%"') {
					return ' AND ReqLines.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'batch') {
				if ($value == '"%null%"') {
					return ' AND ReqLines.batch_id IS NULL';
				}else{
					return ' AND BatchOuts.checkout_weight LIKE ' . $value;
				}
			}else
			if ($name == 'checkout') {
				if ($value == '"%null%"') {
					return ' AND Batches.checkout_id IS NULL';
				}else{
					return ' AND CheckOut.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'CheckOuts') {
			if ($name == 'number'
			or	$name == 'checkout_at'
			or	$name == 'nfe_dl'
			or	$name == 'nfe_tm'
			or	$name == 'requested_at'
			or	$name == 'checkout_weight'
			or	$name == 'checkout_amount'
			or	$name == 'requested_weight'
			or	$name == 'requested_amount') {
				if ($value == '"%null%"') {
					return ' AND CheckOuts.' . $name . ' IS NULL ';
				}else{
					return ' AND CheckOuts.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'machine_name') {
				if ($value == '"%null%"') {
					return ' AND CheckOuts.machine_id IS NULL';
				}else{
					return ' AND Machines.name LIKE ' . $value;
				}
			}else
			if ($name == 'partner_name') {
				if ($value == '"%null%"') {
					return ' AND CheckOuts.partner_id IS NULL';
				}else{
					return ' AND Partner.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'supplier_name') {
				if ($value == '"%null%"') {
					return ' AND CheckOuts.supplier_id IS NULL';
				}else{
					return ' AND Supplier.nick_name LIKE ' . $value;
				}
			}else
			if ($name == 'dyer_name') {
				if ($value == '"%null%"') {
					return ' AND CheckOuts.dyer_id IS NULL';
				}else{
					return ' AND Dyer.nick_name LIKE ' . $value;
				}
			}
		}

		if ($table == 'BatchOuts') {
			if ($name == 'code'
			or	$name == 'batch'
			or	$name == 'unit_price'
			or	$name == 'requested_weight'
			or	$name == 'average_weight'
			or	$name == 'requested_boxes'
			or	$name == 'checkout_boxes'
			or	$name == 'checkout_weight') {
				if ($value == '"%null%"') {
					return ' AND BatchOuts.' . $name . ' IS NULL ';
				}else{
					return ' AND BatchOuts.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'thread') {
				if ($value == '"%null%"') {
					return ' AND BatchOuts.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'request_number') {
				if ($value == '"%null%"') {
					return ' AND BatchOuts.req_line_id IS NULL';
				}else{
					return ' AND Requests.number LIKE ' . $value;
				}
			}
		}

		if ($table == 'ThreadForecast') {
			if ($name == 'current_balance'
			or	$name == 'forecast_past'
			or	$name == 'forecast_month_1'
			or	$name == 'forecast_month_2'
			or	$name == 'forecast_month_3'
			or	$name == 'forecast_future') {
				if ($value == '"%null%"') {
					return ' AND ThreadForecast.' . $name . ' IS NULL ';
				}else{
					return ' AND ThreadForecast.' . $name . ' LIKE ' . $value;
				}
			}else
			if ($name == 'thread_name') {
				if ($value == '"%null%"') {
					return ' AND ThreadForecast.thread_id IS NULL';
				}else{
					return ' AND Threads.name LIKE ' . $value;
				}
			}else
			if ($name == 'supplier_name') {
				if ($value == '"%null%"') {
					return ' AND ThreadForecast.supplier_id IS NULL';
				}else{
					return ' AND Contacts.nick_name LIKE ' . $value;
				}
			}
		}

	}

//	$filter = '"%' . $filter . '%"';
	$filter = '"' . $filter . '%"';

	switch($table) {
		case	'BatchSets' :
		$return = '         BatchSets.checkin_location	LIKE ' . $filter
				. ' OR      BatchSets.checkin_weight	LIKE ' . $filter
				. ' OR      BatchSets.checkin_boxes		LIKE ' . $filter
				. ' OR      BatchSets.reserved_boxes	LIKE ' . $filter
				. ' OR      BatchSets.checkout_boxes	LIKE ' . $filter
				. ' OR		CheckOuts.number			LIKE ' . $filter
				. ' OR      CheckOuts.requested_at		LIKE ' . $filter
				. ' OR       Machines.name				LIKE ' . $filter
				. ' OR        Partner.nick_name			LIKE ' . $filter
				. ' OR       Supplier.nick_name			LIKE ' . $filter
				. ' OR           Dyer.nick_name			LIKE ' . $filter
				. ' OR        Threads.name				LIKE ' . $filter
				. ' OR        Batches.batch				LIKE ' . $filter
				;
		break;

		case	'BatchesBalance' :
		$return = '			Batches.batch				LIKE ' . $filter
				. ' OR		Batches.checkin_weight		LIKE ' . $filter
				. ' OR		Incomings.invoice_date		LIKE ' . $filter
				. ' OR		Supplier.nick_name			LIKE ' . $filter
				;
		break;

		case	'Categories' :
		$return = '       Categories.sequence			LIKE ' . $filter
				. ' OR    Categories.category			LIKE ' . $filter
				. ' OR        Parent.category			LIKE ' . $filter
				;
		break;

		case	'Colors' :
		$return = '		Colors.color_code				LIKE ' . $filter
				. ' OR	Colors.color_type				LIKE ' . $filter
				. ' OR	Colors.color_name				LIKE ' . $filter
				;
		break;

		case	'Controls' :
		$return = '         Controls.sequence			LIKE ' . $filter
				. ' OR      Controls.name				LIKE ' . $filter
				. ' OR      Controls.value				LIKE ' . $filter
				;
		break;

		case	'Configs' :
		$return = '          Configs.sequence			LIKE ' . $filter
				. ' OR       Configs.name				LIKE ' . $filter
				. ' OR       Configs.value				LIKE ' . $filter
				;
		break;

		case	'Permissions' :
		$return = '      Permissions.user_role			LIKE ' . $filter
				. ' OR   Permissions.user_resource		LIKE ' . $filter
				. ' OR   Permissions.user_action		LIKE ' . $filter
				;
		break;

		case	'Products' :
		$return = '    Products.product_name	LIKE ' . $filter
				. ' OR Products.product_type	LIKE ' . $filter
				. ' OR Products.start_at		LIKE ' . $filter
				;
		break;

		case	'Receives' :
		$return = '         Receives.receive_on			LIKE ' . $filter
				. ' OR      Receives.receive_amount		LIKE ' . $filter
				. ' OR      Receives.full_name			LIKE ' . $filter
				;
		break;

		case	'TDyers' :
		$return = '        TDyers.tdyer_number			LIKE ' . $filter
				. ' or     TDyers.ordered_at			LIKE ' . $filter
				. ' or     TDyers.needed_at				LIKE ' . $filter
				. ' or     TDyers.checkout_at			LIKE ' . $filter
				. ' or     TDyers.returned_at			LIKE ' . $filter
				. ' or     TDyers.ordered_weight		LIKE ' . $filter
				. ' or     TDyers.checkout_weight		LIKE ' . $filter
				. ' or     TDyers.returned_weight		LIKE ' . $filter
				. ' or     TDyers.remarks				LIKE ' . $filter
				. ' or     Orderx.order_number			LIKE ' . $filter
				. ' or   Customer.nick_name				LIKE ' . $filter
				. ' or       Dyer.nick_name				LIKE ' . $filter
				;
		break;

		case	'Templates' :
		$return = '        Templates.updated_at			LIKE ' . $filter
				. ' or     Templates.template_name		LIKE ' . $filter
				. ' or     Templates.template_type		LIKE ' . $filter
				. ' or     Templates.template_subject	LIKE ' . $filter
				. ' or     Templates.template_body		LIKE ' . $filter
				. ' or     Templates.template_sql		LIKE ' . $filter
				. ' or     Templates.description		LIKE ' . $filter
				;
		break;

		case	'Tickets' :
		$return = '           Tickets.opened_at			LIKE ' . $filter
				. ' OR        Tickets.priority			LIKE ' . $filter
				. ' OR        Tickets.category			LIKE ' . $filter
				. ' OR        Tickets.description		LIKE ' . $filter
				. ' OR        Tickets.resolution		LIKE ' . $filter
				. ' OR         Opened.full_name			LIKE ' . $filter
				;
		break;

		case	'Translations' :
		$return = '     Translations.updated_at			LIKE ' . $filter
				. ' OR  Translations.sentence			LIKE ' . $filter
				;
		break;

		case	'Contacts' :
		$return = ' Contacts.nick_name		LIKE ' . $filter
			. ' OR	Contacts.first_name		LIKE ' . $filter
			. ' OR	Contacts.last_name		LIKE ' . $filter
			. ' OR	Contacts.full_name		LIKE ' . $filter
			. ' OR	Contacts.mobile			LIKE ' . $filter
			. ' OR	Contacts.email			LIKE ' . $filter
			. ' OR	Contacts.phone			LIKE ' . $filter
			. ' OR	Contacts.street1		LIKE ' . $filter
			. ' OR	Contacts.street2		LIKE ' . $filter
			. ' OR	Contacts.city			LIKE ' . $filter
			. ' OR	Contacts.state			LIKE ' . $filter
			. ' OR	Contacts.zip			LIKE ' . $filter
			. ' OR	Contacts.country		LIKE ' . $filter
			. ' OR	Companies.full_name		LIKE ' . $filter
			;
		break;

		case	'FTPs' :
		$return = ' FTPs.ftp_number			LIKE ' . $filter
			. ' OR  FTPs.diameter			LIKE ' . $filter
			. ' OR  FTPs.density			LIKE ' . $filter
			. ' OR  FTPs.inputs				LIKE ' . $filter
			. ' OR  FTPs.speed				LIKE ' . $filter
			. ' OR  FTPs.turns				LIKE ' . $filter
			. ' OR  FTPs.weight				LIKE ' . $filter
			. ' OR  FTPs.width				LIKE ' . $filter
//			. ' OR  FTPs.lanes				LIKE ' . $filter
//			. ' OR  FTPs.elasticity			LIKE ' . $filter
//			. ' OR  FTPs.needling			LIKE ' . $filter
			. ' OR  FTPs.peso				LIKE ' . $filter
			. ' OR  FTPs.has_break			LIKE ' . $filter
			. ' OR  FTPs.composition		LIKE ' . $filter
			. ' OR  FTPs.nick_name			LIKE ' . $filter
			. ' OR  Products.product_name	LIKE ' . $filter
			. ' OR  Machines.name			LIKE ' . $filter
			;
		break;

		case	'LoadOuts' :
		$return = ' LoadOuts.loadout_number		LIKE ' . $filter
			. ' OR  LoadOuts.dyeing_type		LIKE ' . $filter
			. ' OR  LoadOuts.recipe				LIKE ' . $filter
			. ' OR  LoadOuts.requested_at		LIKE ' . $filter
			. ' OR  LoadOuts.quoted_pieces		LIKE ' . $filter
			. ' OR  LoadOuts.quoted_weight		LIKE ' . $filter
			. ' OR  LoadOuts.checkout_at		LIKE ' . $filter
			. ' OR  LoadOuts.checkout_pieces	LIKE ' . $filter
			. ' OR  LoadOuts.checkout_weight	LIKE ' . $filter
			. ' OR  LoadOuts.returned_at		LIKE ' . $filter
			. ' OR  LoadOuts.returned_pieces	LIKE ' . $filter
			. ' OR  LoadOuts.returned_weight	LIKE ' . $filter
			. ' OR      Dyer.nick_name			LIKE ' . $filter
			. ' OR     Color.color_name			LIKE ' . $filter
			;
		break;

		case	'LoadQuotations' :
		$return = ' LoadQuotations.reserved_pieces	LIKE ' . $filter
			. ' OR  LoadQuotations.checkout_pieces	LIKE ' . $filter
			. ' OR  LoadQuotations.checkout_weight	LIKE ' . $filter
			. ' OR  LoadQuotations.returned_pieces	LIKE ' . $filter
			. ' OR  LoadQuotations.returned_weight	LIKE ' . $filter
			. ' OR         LoadOut.loadout_number	LIKE ' . $filter
			. ' OR       Quotation.quotation_number	LIKE ' . $filter
			. ' OR        Customer.nick_name		LIKE ' . $filter
			. ' OR            Dyer.nick_name		LIKE ' . $filter
			. ' OR           Color.color_name		LIKE ' . $filter
			. ' OR         Product.product_name		LIKE ' . $filter
			;
		break;

		case	'LoadSets' :
		$return = ' LoadSets.checkin_location	LIKE ' . $filter
			. ' OR  LoadSets.checkin_date		LIKE ' . $filter
			. ' OR  LoadSets.checkin_weight		LIKE ' . $filter
			. ' OR  LoadSets.checkin_pieces		LIKE ' . $filter
			. ' OR  LoadSets.reserved_pieces	LIKE ' . $filter
			. ' OR  LoadSets.checkout_pieces	LIKE ' . $filter
			. ' OR   LoadOut.loadout_number		LIKE ' . $filter
			. ' OR  Customer.nick_name			LIKE ' . $filter
			. ' OR      Dyer.nick_name			LIKE ' . $filter
			. ' OR     Color.color_name			LIKE ' . $filter
			. ' OR   Product.product_name		LIKE ' . $filter
			;
		break;

		case	'Machines' :
		$return = ' Machines.name			LIKE ' . $filter
			. ' OR  Machines.machine_type	LIKE ' . $filter
			. ' OR  Machines.machine_family	LIKE ' . $filter
			. ' OR  Machines.machine_brand	LIKE ' . $filter
			. ' OR  Machines.serial_number	LIKE ' . $filter
			. ' OR  Machines.diameter		LIKE ' . $filter
			. ' OR  Machines.width			LIKE ' . $filter
			. ' OR  Machines.density		LIKE ' . $filter
			. ' OR  Machines.inputs			LIKE ' . $filter
			. ' OR  Machines.lanes			LIKE ' . $filter
			. ' OR  Machines.purchase_date	LIKE ' . $filter
			. ' OR  Machines.repair_date	LIKE ' . $filter
			. ' OR  Machines.return_date	LIKE ' . $filter
			;
		break;

		case	'Orders' :
		$return = ' Orders.order_number			LIKE ' . $filter
			. ' OR  Orders.ordered_at			LIKE ' . $filter
			. ' OR  Orders.needed_at			LIKE ' . $filter
			. ' OR  Orders.produced_at			LIKE ' . $filter
			. ' OR  Orders.labels_printed		LIKE ' . $filter
			. ' OR  Orders.ordered_pieces		LIKE ' . $filter
			. ' OR  Orders.rejected_pieces		LIKE ' . $filter
			. ' OR  Orders.produced_pieces		LIKE ' . $filter
			. ' OR    Customer.nick_name		LIKE ' . $filter
			. ' OR     Product.product_name		LIKE ' . $filter
			. ' OR       Color.color_name		LIKE ' . $filter
			. ' OR         FTP.ftp_number		LIKE ' . $filter
			. ' OR     Machine.name				LIKE ' . $filter
			. ' OR     Partner.nick_name		LIKE ' . $filter
			;
		break;

		case	'OSAs' :
		$return = ' OSAs.osa_number				LIKE ' . $filter
			. ' OR  OSAs.ordered_at				LIKE ' . $filter
			. ' OR  OSAs.needed_at				LIKE ' . $filter
			. ' OR  OSAs.produced_date			LIKE ' . $filter
			. ' OR  OSAs.delivered_date			LIKE ' . $filter
			. ' OR  OSAs.quoted_pieces			LIKE ' . $filter
			. ' OR  OSAs.ordered_pieces			LIKE ' . $filter
			. ' OR  Quotation.quotation_number	LIKE ' . $filter
			. ' OR    Customer.nick_name		LIKE ' . $filter
			. ' OR    Salesman.full_name		LIKE ' . $filter
			;
		break;

		case	'Pieces' :
		$return = ' Pieces.barcode				LIKE ' . $filter
			. ' OR  Pieces.product_name			LIKE ' . $filter
			. ' OR  Pieces.produced_by			LIKE ' . $filter
			. ' OR  Pieces.checkin_at			LIKE ' . $filter
			. ' OR  Pieces.returned_at			LIKE ' . $filter
			. ' OR  Pieces.checkout_at			LIKE ' . $filter
			. ' OR  Pieces.checkin_location		LIKE ' . $filter
			. ' OR  Pieces.returned_location	LIKE ' . $filter
			. ' OR  Pieces.checkout_location	LIKE ' . $filter
			. ' OR  Pieces.checkin_weight		LIKE ' . $filter
			. ' OR  Pieces.returned_weight		LIKE ' . $filter
			. ' OR  Pieces.qualities			LIKE ' . $filter
			. ' OR  Pieces.remarks				LIKE ' . $filter
			. ' OR  Revised.nick_name			LIKE ' . $filter
			. ' OR  Weighed.nick_name			LIKE ' . $filter
			. ' OR  Orderx.order_number			LIKE ' . $filter
			;
		break;

		case	'Purchases' :
		$return = ' Purchases.purchase_number	LIKE ' . $filter
			. ' OR  Purchases.source_doc		LIKE ' . $filter
			. ' OR  Purchases.ordered_at		LIKE ' . $filter
			. ' OR  Purchases.expected_date		LIKE ' . $filter
			. ' OR  Purchases.scheduled_at		LIKE ' . $filter
			. ' OR  Purchases.supplier_ref		LIKE ' . $filter
			. ' OR  Purchases.payment_term		LIKE ' . $filter
			. ' OR   Supplier.nick_name			LIKE ' . $filter
			;
		break;

		case	'PurchaseLines' :
		$return = ' PurchaseLines.expected_date		LIKE ' . $filter
			. ' OR  PurchaseLines.scheduled_at		LIKE ' . $filter
			. ' OR  PurchaseLines.expected_weight	LIKE ' . $filter
			. ' OR      Purchases.purchase_number	LIKE ' . $filter
			. ' OR		Purchases.ordered_at		LIKE ' . $filter
			. ' OR		  Threads.name				LIKE ' . $filter
			. ' OR		  Batches.received_weight	LIKE ' . $filter
			. ' OR		Incomings.received_at		LIKE ' . $filter
			. ' OR		 Supplier.nick_name			LIKE ' . $filter
			;
		break;

		case	'Quotations' :
		$return = ' Quotations.quotation_number		LIKE ' . $filter
			. ' OR  Quotations.quoted_at			LIKE ' . $filter
			. ' OR  Quotations.produce_from_date	LIKE ' . $filter
			. ' OR  Quotations.produce_to_date		LIKE ' . $filter
			. ' OR  Quotations.delivered_date		LIKE ' . $filter
			. ' OR  Quotations.quoted_pieces		LIKE ' . $filter
			. ' OR  Quotations.produced_pieces		LIKE ' . $filter
			. ' OR  Quotations.delivered_pieces		LIKE ' . $filter
			. ' OR    Salesman.nick_name			LIKE ' . $filter
			. ' OR    Customer.nick_name			LIKE ' . $filter
			. ' OR     Contact.nick_name			LIKE ' . $filter
			;
		break;

		case	'QuotProducts' :
		$return = '    Product.product_name			LIKE ' . $filter
			. ' OR       Color.color_name			LIKE ' . $filter
			. ' OR   Quotation.quotation_number		LIKE ' . $filter
			. ' OR    Customer.nick_name			LIKE ' . $filter
			. ' OR     Machine.name					LIKE ' . $filter
			. ' OR   Quotation.quoted_at			LIKE ' . $filter
			;
		break;

		case	'QuotUnloadeds' :
		$return = '  Quotation.quotation_number		LIKE ' . $filter
			. ' OR     Product.product_name			LIKE ' . $filter
			. ' OR    Customer.nick_name			LIKE ' . $filter
			. ' OR   Quotation.quoted_at			LIKE ' . $filter
			;
		break;

		case	'Sales' :
		$return = ' Sales.sale_number			LIKE ' . $filter
			. ' OR  Sales.quoted_at				LIKE ' . $filter
			. ' OR  Sales.produced_date			LIKE ' . $filter
			. ' OR  Sales.delivered_date		LIKE ' . $filter
			. ' OR  Sales.quoted_pieces			LIKE ' . $filter
			. ' OR  Sales.produced_pieces		LIKE ' . $filter
			. ' OR  Sales.delivered_pieces		LIKE ' . $filter
			. ' OR    Salesman.nick_name		LIKE ' . $filter
			. ' OR    Customer.nick_name		LIKE ' . $filter
			. ' OR     Contact.nick_name		LIKE ' . $filter
			. ' OR   Quotation.quotation_number	LIKE ' . $filter
			;
		break;

		case	'ShipDyers' :
		$return = ' ShipDyers.shipdyer_number	LIKE ' . $filter
			. ' OR  ShipDyers.invoice_number	LIKE ' . $filter
			. ' OR  ShipDyers.truck_license		LIKE ' . $filter
			. ' OR  ShipDyers.shipped_at		LIKE ' . $filter
			. ' OR  ShipDyers.delivered_at		LIKE ' . $filter
			. ' OR  ShipDyers.unit_name			LIKE ' . $filter
			. ' OR  ShipDyers.brand_name		LIKE ' . $filter
			. ' OR  ShipDyers.batch_code		LIKE ' . $filter
			. ' OR  ShipDyers.quantity			LIKE ' . $filter
			. ' OR  ShipDyers.gross_weight		LIKE ' . $filter
			. ' OR  ShipDyers.net_weight		LIKE ' . $filter
			. ' OR      Dyer.nick_name			LIKE ' . $filter
			. ' OR Transport.nick_name			LIKE ' . $filter
			;
		break;

		case	'Incomings' :
		$return = ' Incomings.incoming_number	LIKE ' . $filter
			. ' OR  Incomings.received_at		LIKE ' . $filter
			. ' OR  Incomings.nfe_dl			LIKE ' . $filter
			. ' OR  Incomings.nfe_tm			LIKE ' . $filter
			. ' OR  Incomings.invoice_date		LIKE ' . $filter
			. ' OR  Incomings.invoice_weight	LIKE ' . $filter
			. ' OR  Incomings.invoice_amount	LIKE ' . $filter
			. ' OR  Incomings.received_weight	LIKE ' . $filter
			. ' OR  Incomings.received_amount	LIKE ' . $filter
			. ' OR   Supplier.nick_name			LIKE ' . $filter
			;
		break;

		case	'Batches' :
		$return = '   Batches.code				LIKE ' . $filter
			. ' OR    Batches.batch				LIKE ' . $filter
			. ' OR    Batches.received_boxes	LIKE ' . $filter
			. ' OR    Batches.checkin_boxes		LIKE ' . $filter
			. ' OR    Batches.returned_boxes	LIKE ' . $filter
			. ' OR    Batches.checkout_boxes	LIKE ' . $filter
			. ' OR    Batches.unit_price		LIKE ' . $filter
			. ' OR    Batches.average_weight	LIKE ' . $filter
			. ' OR    Batches.received_weight	LIKE ' . $filter
			. ' OR    Batches.checkin_weight	LIKE ' . $filter
			. ' OR    Batches.returned_weight	LIKE ' . $filter
			. ' OR    Batches.checkout_weight	LIKE ' . $filter
			. ' OR    Threads.name				LIKE ' . $filter
			. ' OR	Incomings.incoming_number	LIKE ' . $filter
			;
		break;

		case	'Boxes' :
		$return = ' Boxes.barcode			LIKE ' . $filter
			. ' OR  Boxes.average_weight	LIKE ' . $filter
			. ' OR  Boxes.real_weight		LIKE ' . $filter
			. ' OR  Boxes.checkin_location	LIKE ' . $filter
			. ' OR  Boxes.checkout_location	LIKE ' . $filter
			. ' OR  Boxes.returned_location	LIKE ' . $filter
			. ' OR  Threads.name			LIKE ' . $filter
			. ' OR  Supplier.nick_name		LIKE ' . $filter
			. ' OR  Batches.batch			LIKE ' . $filter
			. ' OR  Parent.barcode			LIKE ' . $filter
			;
		break;

		case	'Requests' :
		$return = ' Requests.number			LIKE ' . $filter
			. ' OR  Requests.source_doc		LIKE ' . $filter
			. ' OR  Requests.ordered_at		LIKE ' . $filter
			. ' OR  Requests.requested_date	LIKE ' . $filter
			. ' OR  Requests.scheduled_at	LIKE ' . $filter
			. ' OR  Requests.supplier_ref	LIKE ' . $filter
			. ' OR  Requests.payment_term	LIKE ' . $filter
			. ' OR  Supplier.nick_name		LIKE ' . $filter
			;
		break;

		case	'ReqLines' :
		$return = ' ReqLines.requested_date		LIKE ' . $filter
			. ' OR  ReqLines.scheduled_at		LIKE ' . $filter
			. ' OR  ReqLines.requested_weight	LIKE ' . $filter
			. ' OR  Requests.number				LIKE ' . $filter
			. ' OR	Requests.ordered_at			LIKE ' . $filter
			. ' OR	 Threads.name				LIKE ' . $filter
			. ' OR BatchOuts.checkout_weight	LIKE ' . $filter
			. ' OR CheckOuts.checkout_at		LIKE ' . $filter
			. ' OR	Supplier.nick_name			LIKE ' . $filter
			;
		break;

		case	'CheckOuts' :
		$return = ' CheckOuts.number			LIKE ' . $filter
			. ' OR  CheckOuts.checkout_at		LIKE ' . $filter
			. ' OR  CheckOuts.nfe_dl			LIKE ' . $filter
			. ' OR  CheckOuts.nfe_tm			LIKE ' . $filter
			. ' OR  CheckOuts.requested_at		LIKE ' . $filter
			. ' OR  CheckOuts.checkout_weight	LIKE ' . $filter
			. ' OR  CheckOuts.checkout_amount	LIKE ' . $filter
			. ' OR  CheckOuts.requested_weight	LIKE ' . $filter
			. ' OR  CheckOuts.requested_amount	LIKE ' . $filter
			. ' OR   Machines.name				LIKE ' . $filter
			. ' OR    Partner.nick_name			LIKE ' . $filter
			. ' OR   Supplier.nick_name			LIKE ' . $filter
			. ' OR       Dyer.nick_name			LIKE ' . $filter
			;
		break;

		case	'BatchOuts' :
		$return = '   BatchOuts.code				LIKE ' . $filter
			. ' OR    BatchOuts.batch				LIKE ' . $filter
			. ' OR    BatchOuts.unit_price			LIKE ' . $filter
			. ' OR    BatchOuts.requested_weight	LIKE ' . $filter
			. ' OR    BatchOuts.average_weight		LIKE ' . $filter
			. ' OR    BatchOuts.requested_boxes		LIKE ' . $filter
			. ' OR    BatchOuts.checkout_boxes		LIKE ' . $filter
			. ' OR    BatchOuts.checkout_weight		LIKE ' . $filter
			. ' OR    Threads.name					LIKE ' . $filter
			. ' OR	CheckOuts.number				LIKE ' . $filter
			;
		break;

		case	'Threads' :
		$return = ' Threads.name			LIKE ' . $filter
			. ' OR	Threads.thread_group	LIKE ' . $filter
			. ' OR	Threads.composition		LIKE ' . $filter
			;
		break;

		case	'History' :
		$return = ' History.updated_at		LIKE ' . $filter
			. ' OR	History.updated_by		LIKE ' . $filter
			. ' OR	History.parent_name		LIKE ' . $filter
			. ' OR	History.parent_id		LIKE ' . $filter
			. ' OR	History.method			LIKE ' . $filter
			. ' OR	History.history			LIKE ' . $filter
			;
		break;

		case	'ThreadForecast' :
		$return =  'ThreadForecast.current_balance	LIKE ' . $filter
			. ' OR	ThreadForecast.forecast_past	LIKE ' . $filter
			. ' OR	ThreadForecast.forecast_month_1	LIKE ' . $filter
			. ' OR	ThreadForecast.forecast_month_2	LIKE ' . $filter
			. ' OR	ThreadForecast.forecast_month_3	LIKE ' . $filter
			. ' OR	ThreadForecast.forecast_future	LIKE ' . $filter
			. ' OR	       Threads.thread_group		LIKE ' . $filter
			. ' OR	       Threads.name				LIKE ' . $filter
			. ' OR	      Contacts.nick_name		LIKE ' . $filter
			;
		break;

		default :	$return = '';
	}

	return ' AND (' . $return . ')';
}

/**
 *	$.ajax({ method: get_comments, table: x...x, id: 9...9 });
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]
 */
private function get_comments() {
	$table		= get_request('table'	);
	$parent_id	= get_request('id'		);

	$user_id	= get_session('user_id'  );
	$user_role	= get_session('user_role');
	if (strpos($user_role, 'Staff'	)
	or	strpos($user_role, 'Admin'	)
	or	strpos($user_role, 'Support')) {
		$staff = 'true' ;
	}else{
		$staff = 'false';
	}

	$sql= 'SELECT *'
		. '  FROM Comments'
		. ' WHERE parent_name = "' . $table . '"'
		. '   AND parent_id   =  ' . $parent_id
		. '   AND ( status = "Active" )'
		. '    OR ( status = "Private" AND updated_by = ' . $user_id . ' )'
		. '    OR ( status = "Staff"   AND ' . $staff . ' ) )'
		. ' ORDER BY updated_at'
		;
//$this->log_sql($table, 'get_comments', $sql);
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $db->fetchAll($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: add_comment, table: x...x, id: 9...9, comment: x...x });
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]
 */
private function add_comment() {
	$table		= get_request('table'	);
	$parent_id	= get_request('id'		);
	$comment	= get_request('comment'	);

	$my_id = get_next_id('Comments');
	$sql= 'INSERT INTO Comments'
		. '   SET       id='  . $my_id
		. ',    updated_by='  . get_session('user_id')
		. ',    updated_at="' . get_time() . '"'
		. ',   parent_name="' . $table . '"'
		. ',     parent_id='  . $parent_id
		. ',  created_name="' . get_session('full_name' ) . '"'
		. ', created_email="' . get_session('user_email') . '"'
		. ',       comment="' . $comment . '"'
		;
//$this->log_sql($table, 'add_comment', $sql);
	$db = Zend_Registry::get('db');
	insert_changes($db, 'Comments', $my_id);

	$return = array();
	$return['status'] = 'ok';
	$return['id'	] = $db->query($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_columns, table: x...x });
 *
 */
private function get_columns($data) {
	if (get_session('user_action') != 'All') {
		return;
	}

	$table	= get_data($data, 'table');
	$extra	= array();
	$col	= array();

	if ($table == 'Categories'	)	{	$col['Field'] =   'parent_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] =      'children' ; $col['Type'] = 'int(11)'       ; $extra[] = $col; }
	if ($table == 'Companies'	)	{	$col['Field'] =  'contact_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col; }
	if ($table == 'Services'	)	{	$col['Field'] =     'full_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] =        'avatar' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] =    'group_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col; }
	if ($table == 'Templates'	)	{	$col['Field'] =  'updated_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col; }
	if ($table == 'Tickets'		)	{	$col['Field'] =   'opened_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] =   'closed_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] = 'assigned_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col; }
	if ($table == 'Contacts'	)	{	$col['Field'] =  'support_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col;
										$col['Field'] =  'company_name' ; $col['Type'] = 'varchar(255)'  ; $extra[] = $col; }

	$sql = 'SHOW COLUMNS FROM ' . $table;
//$this->log_sql($table, 'get_columns', $sql);
	$db   = Zend_Registry::get('db');
	$cols = $db->fetchAll($sql);

	$return = array();
	$return['status'] = 'ok';

//	special code to append fields from Contacts to Services table
	if ($table == 'Services') {
		$sql  = 'SHOW COLUMNS FROM Contacts WHERE Field != "id" AND Field != "updated_by" AND Field != "updated_at" AND Field != "status" AND Field != "completed"';
		$db   = Zend_Registry::get('db');
		$users= $db->fetchAll($sql);
		$return['columns'] = array_merge($extra, $cols, $users);
	}else{
		$return['columns'] = array_merge($extra, $cols);
	}

	echo json_encode($return);
}

/**
 *	$.ajax({ method: insert, table: x...x, set: x...x });
 *
 *	  status: ok
 *	inserted: 9...9
 */
private function insert($data) {
	$table	= get_data($data, 'table');
	$set	= get_data($data, 'set'  );
	$db		= Zend_Registry::get('db');

	if ($set == '') {
		$this->echo_error('missing [set] statement');
		return;
	}

	$my_id = get_next_id($table);
	$set .= ', id=' . $my_id;
	$set .= ', updated_by='  . get_session('user_id');
	$set .= ', updated_at="' . get_time() . '"';

	switch($table) {
		case('Boxes'		)	: $set .=          ', barcode = ' . $my_id; break;
		case('CheckOuts'	)	: $set .=           ', number = ' . $my_id; break;
		case('FTPs'			)	: $set .=       ', ftp_number = ' . $my_id; break;
		case('Incomings'	)	: $set .=  ', incoming_number = ' . $my_id; break;
		case('LoadOuts'		)	: $set .=   ', loadout_number = ' . $my_id; break;
		case('Orders'		)	: $set .=     ', order_number = ' . $my_id; break;
		case('OSAs'			)	: $set .=       ', osa_number = ' . $my_id; break;
		case('Purchases'	)	: $set .=  ', purchase_number = ' . $my_id; break;
		case('ReceiveDyers'	)	: $set .=   ', receive_number = ' . $my_id; break;
		case('Quotations'	)	: $set .= ', quotation_number = ' . $my_id; break;
		case('Pieces'		)	: $set .=          ', barcode = ' . $my_id; break;
		case('Requests'		)	: $set .=           ', number = ' . $my_id; break;
		case('Sales'		)	: $set .=      ', sale_number = ' . $my_id; break;
		case('ShipDyers'	)	: $set .=  ', shipdyer_number = ' . $my_id; break;
		case('TDyers'		)	: $set .=     ', tdyer_number = ' . $my_id; break;
	}

	if ($table == 'Categories') {
		$set .= ',     company_id= ' . get_session('company_id');
	}

	if ($table == 'Companies') {
		$set .= ',      parent_id= ' . get_session('control_company', COMPANY_ID);
		$set .= ', company_number= ' . $this->getUniqueNumber($table, 'company_number');
	}

	if ($table == 'Tickets') {
		$set .= ',     company_id= ' . get_session('control_company', COMPANY_ID);
		$set .= ',      opened_by= ' . get_session('user_id');
		$set .= ',      opened_at="' . get_time() . '"';
	}

	if ($table == 'JKY_Users') {
		$set .= ',     start_date="' . get_time() . '"';
		$set .= ',       user_key="' . MD5(date('Y-m-d H:i:s')) . '"';
	}

	$sql= 'INSERT ' . $table
		. '   SET ' . str_replace("*#", "&", $set)
		;
	$this->log_sql($table, 'insert', $sql);
	$return = array();
	try {
		$db->query($sql);
		insert_changes($db, $table, $my_id);
		$this->log_sql($table, $my_id, $sql);
		$new = db_get_row($table, 'id = ' . $my_id);
		$this->history_log('insert', $table, $my_id, $new, null);
		$return['status' ] = 'ok';
		$return['message'] = 'new record (' . $my_id . ') added';
		$return['id'     ] = $my_id;
	} catch(Exception $exp) {
		$this->log_sql($table, null, $exp->getMessage());
		$return['status' ] = 'error';
		$return['message'] = $exp->getMessage();
	}
	echo json_encode($return);
}

private function insert_user_jky() {
	$my_id = get_next_id('JKY_Users');
	$sql= 'INSERT JKY_Users'
		. '   SET  id='  . $my_id
		. ', password="' . MD5(date('Y-m-d H:i:s')) . '"'
		. ', user_key="' . MD5(date('Y-m-d H:i:s')) . '"'
		;
	$db  = Zend_Registry::get('db');
	$db->query($sql);
	insert_changes($db, 'JKY_Users', $my_id);
	return $my_id;
}

/**
 *	$.ajax({ method: update, table: x...x, set: x...x, where: x...x });
 *
 *	 status: ok
 *	updated: 9...9
 */
private function update($data) {
	$table	= get_data($data, 'table');
	$set	= get_data($data, 'set'  );
	$where	= get_data($data, 'where');

	if ($set == '') {
		$this->echo_error('missing [set] statement');
		return;
	}

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$my_id = $this->get_only_id($table, $where);
	if (!$my_id) {
		$this->echo_error('record not found');
		return;
	}

	$old = db_get_row($table, 'id = ' . $my_id);

	$set .= ', updated_by='  . get_session('user_id');
	$set .= ', updated_at="' . get_time() . '"';
	$sql= 'UPDATE ' . $table
		. '   SET ' . str_replace("*#", "&", $set)
		. ' WHERE id = ' . $my_id
		;
	$this->log_sql($table, $my_id, $sql);
	$return = array();
	try {
		$db = Zend_Registry::get('db');
		$db->query($sql);
		insert_changes($db, $table, $my_id);
		$new = db_get_row($table, 'id = ' . $my_id);
		$this->history_log('update', $table, $my_id, $new, $old);
		$return['status' ] = 'ok';
		$return['message'] = 'record (' . $my_id . ') updated';
		$return['id'     ] = $my_id;
	} catch(Exception $exp) {
		$this->log_sql($table, null, $exp->getMessage());
		$return['status' ] = 'error';
		$return['message'] = $exp->getMessage();
	}
	echo json_encode($return);
}

private function update_user_jky($id, $set) {
	$my_id = $id;
	$sql = 'UPDATE JKY_Users'
		. '   SET ' . str_replace("*#", "&", $set)
		. ' WHERE id = ' . $my_id
		;
	$db = Zend_Registry::get('db');
	$db->query($sql);
	insert_changes($db, 'JKY_Users', $my_id);
}

/*
 *	$.ajax({ method: replace, table: x...x, set: x...x, where: x...x });
 *
 *	 status: ok
 *	updated: 9...9
 */
private function replace($data) {
	$table	= get_data($data, 'table');
	$set	= get_data($data, 'set'  );
	$where	= get_data($data, 'where');

	if ($set == '') {
		$this->echo_error('missing [set] statement');
		return;
	}

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$my_id = $this->get_only_id($table, $where);

	if (!$my_id) {
		$old = null;
	}else{
		$old = db_get_row($table, 'id = ' . $my_id);
	}

	$set .= ', updated_by='  . get_session('user_id');
	$set .= ', updated_at="' . get_time() . '"';
	$sql= 'REPLACE ' . $table
		. '   SET ' . str_replace("*#", "&", $set)
		. ' WHERE id = ' . $my_id
		;
	$this->log_sql($table, $my_id, $sql);
	$return = array();
	try {
		$db = Zend_Registry::get('db');
		$db->query($sql);
		insert_changes($db, $table, $my_id);
		$new = db_get_row($table, 'id = ' . $my_id);
		$this->history_log('replace', $table, $my_id, $new, $old);
		$return['status' ] = 'ok';
		$return['message'] = 'record (' . $my_id . ') replaced';
		$return['id'     ] = $my_id;
	} catch(Exception $exp) {
		$this->log_sql($table, null, $exp->getMessage());
		$return['status' ] = 'error';
		$return['message'] = $exp->getMessage();
	}
	echo json_encode($return);
}

/*
 *	$.ajax({ method: copy, folder: x...x, from: x...x, to: x...x });
 *
 *	 status: ok
 *	 copied: 9...9
 */
private function copy($data) {
	$folder	= get_data($data, 'folder'	);
	$from	= get_data($data, 'from'	);
	$to		= get_data($data, 'to'		);

	if ($folder != 'ftp_draws'
	&&  $folder != 'ftp_photos') {
		$this->echo_error('folder undefined');
		return;
	}

	if ($from == '' || $to == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$directory	= DOCUMENT_ROOT . 'uploads/' . $folder . '/';
	$source		= $directory . $from . '.*';
	$copied		= 0;
	foreach(glob($source) as $filename) {
//		echo $filename . ", size: " . filesize($filename) . "\n";
		$ext	= get_file_ext($filename);
		$dest	= $directory . $to . '.' . $ext;
		if (copy($filename, $dest)) {
			$copied ++;
		}
	}

	$return = array();
	$return['status'] = 'ok';
	$return['copied'] = $copied;
	echo json_encode($return);
}

/*
 *	$.ajax({ method: move, filename: x...x, from: x...x, to: x...x });
 *
 *	 status: ok
 *	 copied: 9...9
 */
private function move($data) {
	$filename	= get_data($data, 'filename');
	$from		= get_data($data, 'from'	);
	$to			= get_data($data, 'to'		);

	rename($from . '/' . $filename, $to . '/' . $filename);

	$return = array();
	$return['status'] = 'ok';
	echo json_encode($return);
}

/*
 *	$.ajax({ method: delete, table: x...x, set: x...x });
 *
 *	 status: ok
 *	deleted: 9...9
 */
private function delete($data) {
	$table = get_data($data, 'table');
	$where = get_data($data, 'where');

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$return = array();
	$return['status'] = 'ok';

	$my_id = $this->get_only_id($table, $where);
	if ($my_id) {

		if ($table == 'Contacts') {
			$this->delete_jky_user($my_id);
		}
		if ($table == 'ShipDyers') {
			$this->unlink_loadouts($my_id);
		}

		$new = db_get_row($table, 'id = ' . $my_id);
		$this->history_log('delete', $table, $my_id, $new, null);

		$sql= 'DELETE'
			. '  FROM ' . $table
			. ' WHERE id = ' . $my_id
			;
		$this->log_sql($table, $my_id, $sql);
		$db = Zend_Registry::get('db');
		$db->query($sql);
		insert_changes($db, $table, $my_id);

		$return['message'] = 'record (' . $my_id . ') deleted';
		$return['id'     ] = $my_id;
	}else{
		$return['message'] = 'record already deleted';
	}
	echo json_encode($return);
}

private function delete_jky_user($id) {
	$where = 'contact_id = ' . $id;
	$my_id = $this->get_only_id('JKY_Users', $where);

	if ($my_id) {
		$sql= 'DELETE'
			. '  FROM JKY_Users'
			. ' WHERE id = ' . $my_id
			;
		$this->log_sql('JKY_Users', $my_id, $sql);
		$db = Zend_Registry::get('db');
		$db->query($sql);
		insert_changes($db, 'JKY_Users', $my_id);
	}
}

private function unlink_loadouts($id) {
	$my_id = $id;
	$sql= 'UPDATE LoadOuts'
		. '   SET LoadOuts.shipdyer_id = NULL'
		. ' WHERE LoadOuts.shipdyer_id = ' . $my_id
		;
	$this->log_sql('LoadOuts', $my_id, $sql);
	$db = Zend_Registry::get('db');
	$db->query($sql);
	insert_changes($db, 'JKY_Users', $my_id);
}

/*
 *	$.ajax({ method: delete_many, table: x...x, set: x...x });
 *
 *	 status: ok
 *	deleted: 9...9
 */
private function delete_many($data) {
	$table = get_data($data, 'table');
	$where = $this->get_security($table, get_data($data, 'where'));
$this->log_sql($table, 'delete_many', $where);

	if ($where == '') {
		$this->echo_error('missing [where] statement');
		return;
	}

	$db = Zend_Registry::get('db');

	$sql= 'SELECT ' . $table . '.*'
		. '  FROM ' . $table
		. ' WHERE ' . $where
		;
$this->log_sql($table, 'delete_many', $sql);
	$rows = $db->fetchAll($sql);

	$return = array();
	$return['status'] = 'ok';

	$sql= 'DELETE'
		. '  FROM ' . $table
		. ' WHERE ' . $where
		;
	$this->log_sql($table, 'delete_many', $sql);
	$result = $db->query($sql);

	foreach($rows as $row) {
		$this->history_log('delete', $table, $row['id'], $row, null);
		insert_changes($db, $table, $row['id']);
	}

	$return['message'] = 'record count (' . $result->rowCount() . ') deleted';
	echo json_encode($return);
}

/*
 *	$.ajax({ method: combine, table: x...x , source: x...x, target: x...x });
 *
 *	status: ok
 *	   row: { x...x: y...y, ... } (false)
 */
private function combine() {
	function replace( $s, &$t, $name, $empty ) {
		if(( $t[ $name ] == null  or $t[ $name ] == $empty )
		and( $s[ $name ] != null and $s[ $name ] != $empty )) {
			return ', ' . $name . '="' . $s[ $name ] . '"';
		}else{
			return '';
		}
	}

	if (get_session('user_action') != 'All') {
		return;
	}

	$table    = get_request( 'table'  );
	$source   = get_request( 'source' );
	$target   = get_request( 'target' );

	$db = Zend_Registry::get( 'db' );
	$s = $db->fetchRow( 'SELECT * FROM Contacts WHERE id = ' . $source );
	$t = $db->fetchRow( 'SELECT * FROM Contacts WHERE id = ' . $target );

	$error = '';
	if( !$s )      { $error = '<br>source record ' . $source . ' not found'; }
	if( !$t )      { $error = '<br>target record ' . $target . ' not found'; }

	if(  $error != '' ) {
	       $this->echo_error( $error );
	       return;
	}

	$set  = '';
	$set .= replace( $s, $t, 'user_title'   , '' );
	$set .= replace( $s, $t, 'official_name', '' );
	$set .= replace( $s, $t, 'special_name' , '' );
	$set .= replace( $s, $t, 'user_email'   , '' );
	$set .= replace( $s, $t, 'gender'       , '' );
	$set .= replace( $s, $t, 'birth_date'   , '' );
	$set .= replace( $s, $t, 'user_tags'    , '' );
	$set .= replace( $s, $t, 'mobile'       , '' );
	$set .= replace( $s, $t, 'phone'        , '' );
	$set .= replace( $s, $t, 'street'       , '' );
	$set .= replace( $s, $t, 'zip'          , '' );
	$set .= replace( $s, $t, 'city'         , '' );
	$set .= replace( $s, $t, 'state'        , '' );
	$set .= replace( $s, $t, 'country'      , '' );
	$set .= replace( $s, $t, 'medications'  , '' );
	$set .= replace( $s, $t, 'all_gifts'    , '' );
	$set .= replace( $s, $t, 'other_gifts'  , '' );

	if ($set != '') {
		$sql= 'UPDATE Contacts'
			. '   SET ' . substr( $set, 2 )
			. ' WHERE id = ' . $target
			;
		$this->log_sql( $table, $target, $sql );
		$db->query( $sql );
//		insert_changes($db, 'Contacts', $target);
	}

	  $db->query( 'UPDATE Categories     SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Comments       SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Comments       SET    parent_id = ' . $target . ' WHERE   parent_id = ' . $source . ' AND parent_name = "Contacts"' );

	  $db->query( 'UPDATE Companies      SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Companies      SET    member_id = ' . $target . ' WHERE   member_id = ' . $source );
	  $db->query( 'UPDATE Companies      SET     owner_id = ' . $target . ' WHERE    owner_id = ' . $source );
	  $db->query( 'UPDATE Companies      SET   contact_id = ' . $target . ' WHERE  contact_id = ' . $source );
	  $db->query( 'UPDATE Companies      SET   support_id = ' . $target . ' WHERE  support_id = ' . $source );

	  $db->query( 'UPDATE Controls       SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Configs        SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Events         SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Groups         SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Groups         SET    leader_id = ' . $target . ' WHERE   leader_id = ' . $source );

	  $db->query( 'UPDATE Permissions    SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Services       SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Services       SET      user_id = ' . $target . ' WHERE     user_id = ' . $source );      //   duplicates same event_id
	  $db->query( 'UPDATE Services       SET  assigned_by = ' . $target . ' WHERE assigned_by = ' . $source );

	  $db->query( 'UPDATE Settings       SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Templates      SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );

	  $db->query( 'UPDATE Tickets        SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Tickets        SET    opened_by = ' . $target . ' WHERE   opened_by = ' . $source );
	  $db->query( 'UPDATE Tickets        SET  assigned_to = ' . $target . ' WHERE assigned_to = ' . $source );
	  $db->query( 'UPDATE Tickets        SET    closed_by = ' . $target . ' WHERE   closed_by = ' . $source );

	  $db->query( 'UPDATE Contacts       SET   updated_by = ' . $target . ' WHERE  updated_by = ' . $source );
	  $db->query( 'UPDATE Contacts       SET   support_id = ' . $target . ' WHERE  support_id = ' . $source );

	  $db->query( 'UPDATE User_metas     SET    parent_id = ' . $target . ' WHERE   parent_id = ' . $source );

	  $db->query( 'DELETE FROM Contacts                                     WHERE          id = ' . $source );
	  $db->query( 'DELETE FROM JKY_Users                                    WHERE  contact_id = ' . $source );

	  $return = array();
	  $return[ 'status'   ] = 'ok';
	  $return[ 'message'  ] = 'record combined';
	  $return[ 'source'   ] = $source;
	  $return[ 'target'   ] = $target;
	  echo json_encode( $return );
     }

/*
 *	$.ajax({ method: publish, table: x...x [, group_set: x...x] });
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]
 */
private function publish($data) {
	function write_categories($db, $out_file, $level, $parent_id) {
		$newline = NL;
		for($i=0; $i<$level; $i++)
			$newline .= TAB;

			$sql = 'SELECT *'
		    . '  FROM Categories'
		    . ' WHERE status = "Active"'
		    . '   AND parent_id = ' . $parent_id
		    . ' ORDER BY sequence'
	       ;
	       $rows     = $db->fetchAll( $sql );
	       $counter  = count( $rows );

	       if(  $counter > 0 ) {
		    fwrite( $out_file, $newline . '<ul>' );
		    foreach( $rows as $row ) {
			 $url = 'category.php?id=';
			 fwrite( $out_file, $newline . '<li><a href="' . $url . $row[ 'id' ] . '">' . $row[ 'category' ] . '</a>' );
			 $level++;
			 $counter += write_categories( $db, $out_file, $level, $row[ 'id' ]);
			 $level--;
			 fwrite( $out_file, '</li>' );
		    }
		    fwrite( $out_file, $newline . '</ul>' );
	       }
	       fwrite( $out_file, NL );
	       return $counter;
	  }

	  function write_currencies( $db, $out_file, $default ) {
	       $sql = 'SELECT *'
		    . '  FROM Controls'
		    . ' WHERE status = "Active"'
		    . '   AND group_set = "Currencies"'
		    . '   AND name != "' .  $default . '"'
		    . ' ORDER BY sequence'
	       ;
	       $rows     = $db->fetchAll( $sql );
	       $counter  = count( $rows );

	       fwrite( $out_file, "<a href='/#nogo' class='three outer' rel='nofollow' title='Currencies'><font style='font-weight:bold; font-size:12px; color:#000'>Currencies: " . $default . "</font></a>" );
	       fwrite( $out_file, NL . "<div class='tab_right'>" );
	       foreach( $rows as $row ) {
		    $control_name  = $row[ 'control_name'  ];
		    $control_value = $row[ 'control_value' ];
		    fwrite( $out_file, NL . TAB . "<p><a class='tab_" . $control_name . "' href='/?currency=" . $control_name . "' rel='nofollow'>" . $control_value . "</a></p>" );
	       }
	       fwrite( $out_file, NL . "</div>" );
	       fwrite( $out_file, NL );
	       return $counter;
	  }

	function write_translations_js($db, $out_file, $locale) {
		$sql= 'SELECT Translations.sentence AS source'
			. '     ,      Targets.sentence AS target'
			. '  FROM Translations'
			. '  LEFT JOIN Translations AS Targets'
			. '    ON Targets.parent_id = Translations.id'
			. '   AND Targets.locale = "' . $locale . '"'
			. ' WHERE Translations.status = "Active"'
			. '   AND Translations.locale = "en_US"'
			. ' ORDER BY source'
			;
		$rows = $db->fetchAll($sql);
		$counter = count($rows);

		if ($counter > 0) {
			fwrite($out_file, NL . 'var JKY = JKY || {};');
			fwrite($out_file, NL . 'JKY.translations = ');
			$first = '{';
			foreach ($rows as $row) {
				fwrite($out_file, NL . $first . ' "' . $row['source'] . '":"' . $row['target'] . '"');
				$first = ',';
			}
			fwrite($out_file, NL . '};');
		}
		return $counter;
	}
/*
	  function write_translations_csv($db, $out_file, $locale) {
	       $sql = 'SELECT Translations.sentence AS source'
		    . '     ,      Targets.sentence AS target'
		    . '  FROM Translations'
		    . '  LEFT JOIN Translations AS Targets'
		    . '    ON Targets.parent_id = Translations.id'
		    . '   AND Targets.locale = "' . $locale . '"'
		    . ' WHERE Translations.status = "Active"'
		    . '   AND Translations.locale = "en_US"'
		    . ' ORDER BY source'
	       ;
	       $rows    = $db->fetchAll($sql);
	       $counter = count($rows);

	       if( $counter > 0 ) {
		    fwrite( $out_file, '# csv ' . $locale );
		    foreach( $rows as $row ) {
			 fwrite($out_file, NL . $row['source'] . ';' . $row['target']);
		    }
	       }
	       return $counter;
	  }
*/
	function write_translations_php($db, $out_file, $locale) {
		$sql= 'SELECT Translations.sentence AS source'
			. '     ,      Targets.sentence AS target'
			. '  FROM Translations'
			. '  LEFT JOIN Translations AS Targets'
			. '    ON Targets.parent_id = Translations.id'
			. '   AND Targets.locale = "' . $locale . '"'
			. ' WHERE Translations.status = "Active"'
			. '   AND Translations.locale = "en_US"'
			. ' ORDER BY source'
			;
		$rows = $db->fetchAll($sql);
		$counter = count($rows);

		if ($counter > 0) {
			fwrite($out_file, '<?');
			fwrite($out_file, NL . '//   language ' . $locale);
			fwrite($out_file, NL . '$translations = array');
			$first = '(';
			foreach ($rows as $row) {
				fwrite($out_file, NL . $first . ' "' . $row['source'] . '"=>"' . $row['target'] . '"');
				$first = ',';
			}
			fwrite($out_file, NL . ');');
			fwrite($out_file, NL . '?>');
		}
		return $counter;
	}

	if (get_session('user_action') != 'All') {
		return;
	}

	$table		= get_data($data, 'table');
	$group_set	= get_data($data, 'group_set');
	$db			= Zend_Registry::get('db');
	$counter	= 0;

	  if(  $table == 'Categories' ) {
	       $out_name = 'jky_all_categories.html';
	       $out_file = fopen( $out_name, 'w' ) or die( 'cannot open ' . $out_name );
	       $counter  = write_categories( $db, $out_file, 0, 1000000000 );
	       fclose( $out_file );
	  }

	  if(  $table == 'Controls' ) {
	       if(  $group_set == 'Currencies' ) {
		    $out_name = 'jky_currencies.html';
		    $out_file = fopen( $out_name, 'w' ) or die( 'cannot open ' . $out_name );
		    $counter  = write_currencies( $db, $out_file, 'USD' );
		    fclose( $out_file );
	       }
	  }

	if ($table == 'Translations') {
		$sql= 'SELECT name'
			. '  FROM Controls'
			. ' WHERE status = "Active"'
			. '   AND group_set = "Languages"'
			. ' ORDER BY sequence'
			;
		$rows = $db->fetchAll($sql);

		foreach ($rows as $row) {
			$locale		= $row['name'];
			$out_name	= '../html/js/translations/' . $locale . '.js';
			$out_file	= fopen($out_name, 'w') or die('cannot open ' . $out_name);
			$counter	= write_translations_js($db, $out_file, $locale);
			fclose($out_file);
		}
/*
	      foreach( $rows as $row ) {
		   $locale = $row['setting_name'];
		   $out_name = '../languages/' . $locale . '.csv';
		   $out_file = fopen($out_name, 'w') or die('cannot open ' . $out_name);
		   $counter  = write_translations_csv($db, $out_file, $locale);
		   fclose($out_file);
	      }
*/
		foreach ($rows as $row) {
			$locale = $row['name'];
			$out_name = '../application/' . $locale . '.php';
			$out_file = fopen($out_name, 'w') or die('cannot open ' . $out_name);
			$counter  = write_translations_php($db, $out_file, $locale);
			fclose($out_file);
		}
	 }

//          system( '( php ' . APPLICATION . 'GenerateHtml.php & ) > /dev/null' );
//          exec( 'php ' . APPLICATION . 'GenerateHtml.php' );

	$return = array();
	$return['status' ] = 'ok';
	$return['message'] = $counter . ' records published';
	echo json_encode($return);
}

//   ---------------------------------------------------------------------------
private function echo_json($return) {
//	echo get_request('callback') . '( ' . json_encode($return) . ' );';
	echo json_encode($return);
}

/*
 *	$.ajax({method:set_language, language:language});
 *
 *	http://jky/jky_proxy.php?method=set_language&language=en_US
 *
 *	status: ok
 */
private function set_language() {
    if ( is_request('language')) {
	set_session('language', get_request('language'));
    }

     $return = array();
     $return[ 'status'   ] = 'ok';
     echo json_encode( $return );
}

/*
 *   $.ajax({ method: get_language });
 *
 *       status: ok
 *     language: xx_yy
 */
private function get_language() {
	$return = array();
	$return[ 'status'        ] = 'ok';
	$return[ 'language'      ] = get_session('language');
	echo json_encode( $return );
    }

/*
 *	$.ajax({ method: set_session });
 *
 *	$.ajax({method:set_session, data:{action:confirm, user_key=6e5fa4d9c48ca921c0a2ce1e64c9ae6f});
 *
 *	http://jky/jky_proxy.php?method=set_session&action=reset  &user_key=6e5fa4d9c48ca921c0a2ce1e64c9ae6f
 *
 *	status: ok
 */
private function set_session($data) {
	if ($data['action'] == 'reset') {
		unset_session('overlay_page');
	}else{
		set_session('action', $data['action']);
	}
//	if ($data['user_key'])		set_session('user_key', $data['user_key']);

	$return = array();
	$return['status'] = 'ok';
	echo json_encode( $return );
}

/*
 *	$.ajax({ method: get_session });
 *
 *	status: ok
 *	today_date: yyyy-mm-dd
 */
private function get_session() {
	$data = array();
	$data['today_date'] = date('Y-m-d');

	if (is_session('action'			))   $data['action'			] = fetch_session( 'action'		);
	if (is_session('user_key'		))   $data['user_key'		] = fetch_session( 'user_key'	);

	if (is_session('language'		))   $data['language'		] =   get_session('language'	);
	if (is_session('version'		))   $data['version'		] =   get_session('version'		);
	if (is_session('environment'	))   $data['environment'	] =   get_session('environment'	);
	if (is_session('control_company'))   $data['control_company'] =   get_session('control_company', COMPANY_ID);
	if (is_session('company_name'	))   $data['company_name'	] =   get_session('company_name');
	if (is_session('company_logo'	))   $data['company_logo'	] =   get_session('company_logo');
	if (is_session('locale'			))   $data['locale'			] =   get_session('locale'		);
	if (is_session('user_time'		))   $data['user_time'		] =   get_session('user_time'	);

if (is_session('full_name')) {
	if (is_session('contact_id'		))   $data['contact_id'		] =   get_session('contact_id'	);
	if (is_session('full_name'		))   $data['full_name'		] =   get_session('full_name'	);
	if (is_session('user_name'		))   $data['user_name'		] =   get_session('user_name'	);
	if (is_session('user_role'		))   $data['user_role'		] =   get_session('user_role'	);
	if (is_session('user_id'		))   $data['user_id'		] =   get_session('user_id'		);
	if (is_session('permissions'	))   $data['permissions'	] =   get_session('permissions'	);
	if (is_session('start_page'		))   $data['start_page'		] =   get_session('start_page'	);
	if (is_session('overlay_page'	))   $data['overlay_page'	] =   get_session('overlay_page');
}
/*
	$data['copyright'	] = '&#64; 2013 JKY Software Corp';
	$data['contact_us'	] = 'Contact Us';
	$data['language'	] = 'Portugues';
	$data['languages'	] = array('English', 'Portugues', 'Chinese', 'Taiwanese');
*/
	$return = array();
	$return['status'] = 'ok';
	$return['data'  ] = $data;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_profile );
 *
 *	status: ok
 *	   row: Contacts
 */
private function get_profile() {
	if (!is_session('user_id')) {
		$this->echo_error('user_id is undefined');
		return;
	}

	$sql= 'SELECT *'
		. '  FROM Contacts'
		. ' WHERE id = ' . get_session('user_id')
		;
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['row'	] = $db->fetchRow($sql);
	echo json_encode($return);
}

/**
 *   $.ajax({ method: get_contact );
 *
 *   status: ok
 *      row: Contacts
 */
private function get_contact() {
	if (!is_session('control_company')) {
		$this->echo_error('control_company is undefined');
		return;
	}

	$sql= 'SELECT Companies.*'
		. '     , Contact.user_email AS business_email'
		. '     , Support.user_email AS  support_email'
		. '  FROM Companies'
		. '  LEFT JOIN Contacts AS Contact On Contact.id = Companies.contact_id'
		. '  LEFT JOIN Contacts AS Support On Support.id = Companies.support_id'
		. ' WHERE Companies.id = ' . get_session( 'control_company', COMPANY_ID )
		;
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['row'	] = $db->fetchRow($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_contact_id, contact_name: x...x );
 *
 *	status: ok
 *		id: 9...9
 */
private function get_contact_id() {
	$contact_name = get_request('contact_name');

	$sql= 'SELECT id'
		. '  FROM Contacts'
		. ' WHERE full_name = "' . $contact_name . '"'
		;
	$db = Zend_Registry::get('db');
	$my_id = $db->fetchOne($sql);

	if (!$my_id) {
		$my_id = $this->insert_user_jky();
		$sql= 'INSERT INTO Contacts'
			. '   SET     id='  . $my_id
//			. ', user_number='  . $this->getUniqueNumber( 'Contacts', 'user_number' )
			. ',   full_name="' . $contact_name . '"'
			. ',   user_name="' . $contact_name . '"'
			;
		$db = Zend_Registry::get('db');
		$db->query($sql);
		insert_changes($db, 'Contacts', $my_id);
	}

	$return = array();
	$return['status'] = 'ok';
	$return['id'	] = $my_id;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_user_id, user_name: x...x );
 *
 *	status: ok     | error
 *		id: 9...9  | null
 */
private function get_user_id($data) {
	$sql= 'SELECT id'
		. '  FROM JKY_Users'
		. ' WHERE user_name = "' . $data['user_name'] . '"'
		;
	$return = array();
	$db = Zend_Registry::get('db');
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_ftp_id, product_id: x...x );
 *	of most current ftp
 *
 *	status: ok     | error
 *		id: 9...9  | null
 */

private function get_ftp_id($data) {
	$sql= 'SELECT id'
		. '  FROM FTPs'
		. ' WHERE product_id = ' . $data['product_id']
		. ' ORDER BY is_current DESC, id DESC'
		. ' LIMIT 1'
		;
$this->log_sql(null, 'get_ftp_id', $sql);
	$return = array();
	$db = Zend_Registry::get('db');
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_order_id, load_quot_id: x...x );
 *	of most current piece
 *
 *	status: ok     | error
 *		id: 9...9  | null
 */

private function get_order_id($data) {
	$sql= 'SELECT order_id'
		. '  FROM Pieces'
		. ' WHERE load_quot_id = ' . $data['load_quot_id']
		. ' ORDER BY id DESC'
		. ' LIMIT 1'
		;
$this->log_sql(null, 'get_order_id', $sql);
	$return = array();
	$db = Zend_Registry::get('db');
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_product_id, product_name: x...x );
 *
 *	status: ok     | error
 *		id: 9...9  | null
 */
private function get_product_id($data) {
	$sql= 'SELECT id'
		. '  FROM Products'
		. ' WHERE product_name = "' . $data['product_name'] . '"'
		;
	$return = array();
	$db = Zend_Registry::get('db');
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: set_user_id, user_key: x...x });
 *
 *	status: ok / error
 * message: x...x
 */

private function set_user_id() {
	$error = '';
	$user_id = db_get_id('JKY_Users', 'user_key = "' . get_request('user_key') . '"');

	if ($user_id) {
		$this->set_user_session($user_id);
	}else{
		$error .= BR . 'User Account already expired';
	}

	$return['status'	] = $error == '' ? 'ok' : 'error';
	$return['message'	] = $error;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_company_id, company_name: x...x );
 *
 *	status: ok
 *		id: 9...9
 */
private function get_company_id() {
	$sql= 'SELECT id'
		. '  FROM Companies'
		. ' WHERE company_name = "' . get_request('company_name') . '"'
		;
//$this->log_sql(null, 'get_company_id', $sql);
	$db = Zend_Registry::get('db');
	$return = array();
	$return['status'] = 'ok';
	$return['id'	] = $db->fetchOne($sql);
	echo json_encode($return);
}

/**
 *	$.ajax({ method: set_company_id, company_id: 9...9 );
 *
 *	status: ok
 *		id: 9...9
 */
private function set_company_id() {
	$company = db_get_row('Companies', 'id = ' . get_request('company_id'));
	set_session('company_id'     , $company['id'			]);
	set_session('company_name'   , $company['company_name'	]);
//	set_session('control_company', $company['parent_id'		]);
	$return = array();
	$return['status'] = 'ok';
	$return['name'	] = $company['company_name'];
	echo json_encode($return);
}

/**
 *	$.ajax({ method: set_group_id, group_id: 9...9 );
 *
 *	status: ok
 *		id: 9...9
 */
private function set_group_id() {
	$group = db_get_row('Groups', 'id = ' . get_request('group_id'));
	set_session('group_id'		, $group['id'			]);
	set_session('group_name'	, $group['group_name'	]);
//	set_session('event_id'		, $group['event_id'		]);
	$return = array();
	$return['status'] = 'ok';
	$return['name'	] = $group['group_name'];
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_options, table:x..x, field=x...x, selected: x...x, initial:x...x });
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_options($data) {
	$table		= get_data($data, 'table'	);
	$field		= get_data($data, 'field'	);
//	$selected	= get_data($data, 'selected');
//	$initial	= get_data($data, 'initial'	);

	$sql= 'SELECT id, ' . $field
		. '  FROM ' . $table
		. ' WHERE status = "Active"'
		. ' ORDER BY ' . $field
		;
	$this->log_sql(null, 'get_options', $sql);
	$db = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);

	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_users, where:..x, select: x...x, initial: x...x });
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_users() {
	$where		= get_request('where'	);
	$select		= get_request('select'	);
	$initial	= get_request('initial'	);

	$sql= 'SELECT Contacts.id, Contacts.full_name'
//		. '  FROM Contacts, Services, Groups'
//		. ' WHERE Contacts.id = Services.user_id'
//		. '   AND Services.group_id = Groups.id'
//		. '   AND ' . $where
		. '  FROM Contacts'
		. ' WHERE ' . $where
		. ' ORDER BY Contacts.full_name'
		;
	if ($initial == '') {
//		$return = '';
		$return = '<option value="">' . $initial . '</option>';
	}else{
//		else $return = '<option value="*">' . $initial . '</option>';
		$return = '<option value="All">' . $initial . '</option>';
	}

	if ($sql != '') {
$this->log_sql(null, 'get_users', $sql);
		$db = Zend_Registry::get('db');
		$rows = $db->fetchAll($sql);

		foreach($rows as $row) {
			$selected = $row['id'] == $select ? ' selected="selected"' : '';
			$return .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['full_name'] . '</options>';
		}
	}
	echo $return;
}

/**
 *	$.ajax({ method: get_controls, group_set: x...x);
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_controls($data) {
	$group_set = get_data($data, 'group_set');

	$security = '';
	if ($group_set == 'User Roles') {
		if (get_session('user_role') != 'Support') {
			$security = ' AND Controls.name != "Support"';
		}
	}

	$sql= 'SELECT * '
		. '  FROM Controls'
		. ' WHERE group_set = "' . $group_set . '"' . $security
		. ' ORDER BY sequence, name'
		;
$this->log_sql(null, 'get_users', $sql);
	$db = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);
	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

/**
 *   $.ajax({ method: get_configs, group_set: x...x);
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]

 */
private function get_configs($data) {
	$group_set = get_data($data, 'group_set');

	$sql= 'SELECT * '
		. '  FROM Configs'
		. ' WHERE group_set = "' . $group_set . '"'
		. ' ORDER BY sequence, name'
		;
	$db = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);
	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

/**
 *   $.ajax({ method: get_companies, specific: x...x);
 *
 *	status: ok
 *	  rows: [{ x...x: y...y, ... } (false)
 *			,{ x...x: y...y, ... }
 *			,{ x...x: y...y, ... }
 *			]

 */
private function get_companies($data) {
	$specific = get_data($data, 'specific');

//	$sql= 'SELECT *'
	$sql= 'SELECT id, nick_name, full_name'
		. '  FROM Contacts'
		. ' WHERE ' . $specific . ' = "Yes"'
		. '   AND is_company = "Yes"'
		. ' ORDER BY nick_name'
		;
	$db = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);
	$return = array();
	$return['status'] = 'ok';
	$return['rows'	] = $rows;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: get_options, group_set: x...x, select: x...x, initial: x...x });
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_optionss() {
	$group_set	= get_request('group_set'	);
	$selected	= get_request('selected'	);
	$initial	= get_request('initial'		);

	$sql= 'SELECT * '
		. '  FROM Configs'
		. ' WHERE group_set = "' . $group_set . '"'
		. ' ORDER BY sequence, name'
		;
	if ($initial == '') {
		$return = '';
	}else{
//		$return = '<option value="*">' . $initial . '</option>';
		$return = '<option value="All">' . $initial . '</option>';
	}

	if ($sql != '') {
		$db = Zend_Registry::get('db');
		$rows = $db->fetchAll($sql);

		foreach ($rows as $row) {
			if ($row['value'] == ''){
				$row['value'] = $row['name'];
			}
		$selected = $row['name'] == $selected ? ' selected="selected"' : '';
		$return .= '<option value="' . $row['name'] . '"' . $selected . '>' . $row['value'] . '</options>';
		}
	}
	echo $return;
}

/**
 *   $.ajax({ method: get_categories, parent_id: 9...9, select: x...x, initial: x...x });
 *
 *   return: <options value="x...x" selected="selected">x...x</options>
 *           ...
 */
private function get_categories() {
	$parent_id	= get_request('parent_id'	);
	$select		= get_request('select'		);
	$initial	= get_request('initial'		);

	$sql= 'SELECT Categories.*'
		. '  FROM Categories'
		. '  LEFT JOIN Categories AS Parent ON Parent.id = Categories.parent_id'
		. ' WHERE Categories.parent_id = ' . $parent_id
		. ' ORDER BY sequence, category'
		;
//$this->log_sql(null, 'get_categories', $sql);
	if ($initial == '') {
		$return = '';
	}else{
		$return = '<option value="All">' . $initial . '</option>';
	}

	if ($sql != '') {
		$db = Zend_Registry::get('db');
		$rows = $db->fetchAll($sql);

		foreach($rows as $row) {
			$selected = $row['category'] == $select ? ' selected="selected"' : '';
			$return .= '<option value="' . $row['id'] . '"' . $selected . '>' . $row['category'] . '</options>';
		}
	}
	echo $return;
}

/**
 *   $.ajax({ method: get_loadout_by_color_id, color_id: 9...9 });
 *
 *   return: loadout row
 */
private function get_loadout_by_color_id($data) {
	$specific = get_data($data, 'specific');

	$sql= 'SELECT DISTINCT LoadOuts.*'
		. '  FROM LoadOuts, LoadQuotations, QuotColors, QuotLines, OSAs, OSA_Lines, OSA_Colors'
		. ' WHERE LoadOuts.color_id = OSA_Colors.color_id'
		. '   AND LoadOuts.id = LoadQuotations.loadout_id'
		. '   AND LoadQuotations.quot_color_id = QuotColors.id'
		. '   AND QuotColors.parent_id = QuotLines.id'
		. '   AND QuotLines.parent_id = OSAs.quotation_id'
		. '   AND OSAs.id = OSA_Lines.parent_id'
		. '   AND OSA_Lines.id = OSA_Colors.parent_id'
		. '   AND OSA_Colors.id = ' . $specific
		;
$this->log_sql(null, 'get_load_by_color_id', $sql);
	$db  = Zend_Registry::get('db');
	$row = $db->fetchRow($sql);
	$return = array();
	$return['status'] = 'ok';
	$return['row'	] = $row;
	echo json_encode($return);
}
	
/**
 *	$.ajax({ method: get_header });
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_header() {
	$full_name = get_session('full_name');
	if ($full_name == '') {
		$header_id = 'Welcome, please &nbsp;<a onclick="display_signup();">Sign Up</a>&nbsp; or &nbsp;<a onclick="display_login();">Log In</a>';
	}else{
		$header_id = 'Hello ' . $full_name . ', want to view &nbsp;<a onclick="display_profile();">Your Profile</a>&nbsp; or &nbsp;<a onclick="request_logout();">Log Out</a>';
	}

	$return = ''
			. '<div class="margin">'
			. '<span id="header_company">' . get_session('company_name') . '</span>'
			. '<span id="header_id">' . $header_id .'</span>'
			. '</div>'
			;
	echo $return;
}

/**
 *	$.ajax({ method: get_menus });
 *
 *	return: <options value="x...x" selected="selected">x...x</options>
 *			...
 */
private function get_menus() {
	$html = ''
	   . '   <div class="margin">'
	   . '       <a href="/bridge/return"><img id="header_logo" src="/images/logo.png" /></a>'
	   . '       <div class="clear"></div>'

	   . '       <div id="header_menu" class="tabs">'
	   . '            <ul>'
	   . '                 <li class="' . $this->get_tab_class( 'Home'       ) . '"><a href="/home"            ><span>Home         </span></a></li>'
	   . '                 <li class="' . $this->get_tab_class( 'Videos'     ) . '"><a href="/videos"          ><span>Videos       </span></a></li>'
	   . '                 <li class="' . $this->get_tab_class( 'Reports'    ) . '"><a href="/reports"         ><span>Reports      </span></a></li>'
	   . '                 <li class="' . $this->get_tab_class( 'Features'   ) . '"><a href="/features"        ><span>Features     </span></a></li>'
	   . '            </ul>'
	   . '       </div id="header_menu">'

	   . '       <div id="header_control" class="tabs">'
	   . '            <ul>'
	   . '                 <li class="' . $this->get_tab_class( 'MyAccount'  ) . '"><a href="/myaccount"       ><span>My Account   </span></a></li>'
	   . '                 <li class="' . $this->get_tab_class( 'Tickets'    ) . '"><a href="/Tickets.html"    ><span>Tickets      </span></a></li>'
	   . '                 <li class="' . $this->get_tab_class( 'Admin'      ) . '">'
	   . '                      <a href="#"    onmouseover="mopen(\'admin\')"   onmouseout="mclosetime()"><span>Admin</span></a>'
	   . '                      <ul id="admin" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">'
	   . '                           <li><a href="/companies/settings"  ><span>Settings     </span></a></li>'
	   . '                           <li><a href="/companies.html"      ><span>Companies    </span></a></li>'
	   . '                           <li><a href="/invoices"            ><span>Invoices     </span></a></li>'
	   . '                           <li><a href="/production"          ><span>Production   </span></a></li>'
	   . '                           <li><a href="/repository"          ><span>Repository   </span></a></li>'
	   . '                           <li><a href="/Contacts.html"        ><span>Contacts        </span></a></li>'
	   . '                           <li><a href="/tcounters"           ><span>Talents      </span></a></li>'
	   . '                           <li><a href="/counters"            ><span>Summary      </span></a></li>'
	   . '                           <li><a href="/templates"           ><span>Templates    </span></a></li>'
	   . '                           <li><a href="/controls.html"       ><span>Controls     </span></a></li>'
	   . '                           <li><a href="/permissions.html"    ><span>Permissions  </span></a></li>'
	   . '                      </ul>'
	   . '                 </li>'
	   . '            </ul>'
	   . '       </div id="header_control">'

	   . '       <div class="clear"></div>'
	   . '  </div>'
	   ;
     echo $html;
}

private function get_tab_class($tab) {
	$user_action = get_user_action($tab);
	if ($user_action == '') {
		$user_action = get_user_action('All');
	}

//	for undefined resource or denied user_action
	if ($user_action == '' or $user_action == 'Denied') {
		return 'tab_denied'  ;
	}else{
		return 'tab_inactive';
	}
}

private function set_user_session($user_id) {
	$user = db_get_row('JKY_Users', 'id = ' . $user_id);
	set_session('user_id'		, $user['id'			]);
	set_session('user_name'		, $user['user_name'		]);
	set_session('user_type'		, $user['user_type'		]);
	set_session('user_role'		, $user['user_role'		]);

	$contact = db_get_row('Contacts', 'id = ' . $user['contact_id']);
	set_session('contact_id'	, $contact['id'			]);
	set_session('first_name'	, $contact['first_name'	]);
	set_session('last_name'		, $contact['last_name'	]);
	set_session('full_name'		, $contact['full_name'	]);
	set_session('user_email'	, $contact['email'		]);

	set_permissions($user['user_role']);
}

private function get_user_data() {
	$control = db_get_row('Controls', 'status = "Active" AND group_set ="User Roles" AND name= "' . get_session('user_role') . '"') ;
	set_session('start_page', $control['value']);
	$data = array();
if (is_session('full_name')) {
	$data['first_name'	] = get_session('first_name');
	$data['last_name'	] = get_session('last_name'	);
	$data['full_name'	] = get_session('full_name'	);
	$data['user_role'	] = get_session('user_role' );
	$data['start_page'	] = get_session('start_page');
}
	return $data;
}

//   ---------------------------------------------------------------------------

/**
 *	$.ajax({ method: check_session });
 *
 *	status: ok
 * message: x...x
 *	  data: x...x
 */
private function check_session($data) {
	$error = '';
	if (!is_session('user_id')) {
		$error = 'Session is gone';
	}

	$return = array();
	if (is_empty($error)) {
		$return['status' ] = 'ok';
		$return['data'   ] = $this->get_user_data();
	}else{
		$return['status' ] = 'error';
		$return['message'] = $error;
	}
	echo json_encode($return);
}

/**
 *	$.ajax({ method: confirm, user_key: x...x });
 *
 *	status: ok / error
 * message: x...x
 */

private function confirm($data) {
	$error = '';
	$user_id = db_get_id('JKY_Users', 'user_key = "' . $data['user_key'] . '"');

	if (!$user_id) {
		$error .= BR . 'User Account already expired';
	}else{
		if (is_empty(meta_get_id('Contacts', $user_id, 'unconfirmed_email'))) {
			$error .= BR . 'Email Address already confirmed';
		}
	}

	$return = array();
	if (is_empty($error)) {
		meta_delete('User', $user_id, 'unconfirmed_email');
		$this->set_user_session($user_id);
		$return['status' ] = 'ok';
		$return['message'] = 'Email Address confirmed';
	}else{
		$return['status' ] = 'error';
		$return['message'] = $error;
	}
	echo json_encode($return);
}

/**
 *	$.ajax({ method: reset, encrypted: x...x });
 *
 *	status: ok
 * message: password reseted
 */
private function reset($data) {
	$error = '';
	$user_id	= get_session('user_id');
	$password	= $data['password'];

	if ($password != '') {
		$sql= 'UPDATE JKY_Users'
			. '   SET password = "' . $password . '"'
			. ' WHERE id = ' . $user_id
			;
		$db = Zend_Registry::get('db');
		$db->query($sql);
		$this->log_sql('reset', $user_id, $sql);
		insert_changes($db, 'JKY_Users', $user_id);
	}

	$return = array();
	$return['status' ] = 'ok';
	$return['message'] = 'New Password reseted';

//	$control = db_get_row('Controls', 'status = "Active" AND group_set ="User Role" AND control_name= "' . get_session( 'user_role' ) . '"');
//	$return['re_direct'] = $control['control_value'];
	echo json_encode($return);
}

/**
 *	$.ajax({ method: log_in, user_name: x...x, encrypted: x...x, remember_me: Y/N });
 *
 *	status: ok
 * message: x...x
 *	  data: first_name	: x...x
 *			last_name	: x...x
 *			user_role	: x...x
 *			start_page	: x...x
 */
private function log_in($data) {
	$user_name	= $data['user_name'];
	$encrypted	= $data['encrypted'];

	$error = '';
	$user_id = db_get_id('JKY_Users', 'status = "Active" AND user_name = "' . $user_name . '"');
	if (!$user_id) {
		$error .= set_is_invalid('User Name');
	}

	if (is_empty($error)) {
		$password = $this->get_password($user_id);
		$password = MD5(get_session('user_time') . $password);
		if ($password !== $encrypted) {
			$error .= set_is_invalid('Password');
		}
	}

	$return = array();
	if (is_empty($error)) {
		$this->set_user_session($user_id);
		$return['status' ] = 'ok';
		$return['data'   ] = $this->get_user_data();
	}else{
		$return['status' ] = 'error';
		$return['message'] = $error;
	}
	echo json_encode($return);
}

private function get_password($id) {
	$sql= 'SELECT password'
		. '  FROM JKY_Users'
		. ' WHERE id = ' . $id
		;
	$db = Zend_Registry::get('db');
//$this->log_sql(null, 'get_password', $sql);
	return $db->fetchOne($sql);
}

/**
 *	$.ajax({ method: log_out });
 *
 *	status: ok
 * message: x...x
 */
private function log_out($data) {
//	setcookie('remember_me'  , '', time() - 86400, '/');
//	setcookie('authorization', '', time() - 86400, '/');

	$error = '';
	$session = new Zend_Session_Namespace();
	foreach($session as $name => $value) {
//		if ($name != 'control_company') {
			unset_session($name);
//		}
	}
//	$this->_redirect( INDEX . 'index' );                   //   in linux, it generates = http://xxx/jky_index.php/jky_index.php/index
//	$this->_redirect( INDEX . 'jky_index.php/index' );

	$return = array();
	$return[ 'status' ] = $error == '' ? 'ok' : 'error';
	$return[ 'message'] = $error;
	echo json_encode($return);
}

/**
 *	$.ajax({ method: log_help, help_name: x...x });
 *
 *		status: ok
 *  user_email: x...x
 */
private function log_help($data) {
	$help_name = $data['help_name'];

	$db = Zend_Registry::get('db');
	$error = '';
	$sql= 'SELECT JKY_Users.id, JKY_Users.contact_id, Contacts.email'
		. '  FROM JKY_Users'
		. '  LEFT JOIN Contacts ON Contacts.id = JKY_Users.contact_id'
		. ' WHERE JKY_Users.status = "Active"'
		. '   AND  Contacts.email IS NOT NULL'
		. '   AND(JKY_Users.user_name = "' . $help_name . '" OR Contacts.email = "' . $help_name . '" )'
		;
//$this->log_sql( null, 'log_help', $sql );
	$users = $db->fetchAll($sql);
	if (count($users) == 0) {
		$error .= set_not_found('User Name or Email Address');
	}

	$data = array();
	if (is_empty($error)) {
//		email for all users
		foreach($users as $user) {
			$to_email = email_by_event($user['id'], $user['contact_id'], 'Remind Me', 'Email From System');
			$data[] = $to_email;
		}
	}
	$return = array();
	$return['status' ] = $error == '' ? 'ok' : 'error';
	$return['message'] = $error;
	$return['data'   ] = $data;
//$this->log_sql( null, 'log_help', print_r($return, true));
//$this->log_sql( null, 'log_help', json_encode($return));
	echo json_encode($return);
}

/**
 *	$.ajax({ method: profile, user_name: x...x, first_name: x...x, last_name: x...x, email: x...x, current: x...x, password: x...x });
 *
 *	status: ok
 * message: x...x
 */
private function profile($data) {
	$user_name	= $data['user_name'	];
	$first_name	= $data['first_name'];
	$last_name	= $data['last_name'	];
	$email		= $data['email'		];
	$current	= $data['current'	];
	$password	= $data['password'	];
	$full_name	= $first_name . ' ' . $last_name;

	$db = Zend_Registry::get('db');
	$error = '';
	if ($password != MD5('')) {
		$sql= 'SELECT password'
			. '  FROM JKY_Users'
			. ' WHERE id = ' . get_session('user_id')
			;
		$my_password = $db->fetchOne($sql);

		if ($my_password != $current) {
			$error = 'Current Password is invalid';
		}
		$set_password = ', password = "' . $password . '"';
	}else{
		$set_password = '';
	}

	if ($error == '') {
		$set= '  first_name		= "' . $first_name		. '"'
			. ', last_name		= "' . $last_name		. '"'
			. ', full_name		= "' . $full_name		. '"'
			. ', email			= "' . $email			. '"'
			;
		$sql= 'UPDATE Contacts'
			. '   SET ' . $set
			. ' WHERE id = ' . get_session('contact_id')
			;
		$this->log_sql('profile', null, $sql);
		$db->query($sql);
		insert_changes($db, 'Contacts', get_session('contact_id'));

//		set_session('full_name', $full_name);

		$sql= 'UPDATE JKY_Users'
			. '   SET user_name	= "' . $user_name . '"'	. $set_password
			. ' WHERE id = ' . get_session('user_id')
			;
		$this->log_sql('profile', null, $sql);
		$db->query($sql);
		insert_changes($db, 'JKY_Users', get_session('user_id'));
	}

	$return = array();
	if ($error == '') {
		$return['status' ] = 'ok';
		$return['message'] = 'profile updated';
	}else{
		$return['status' ] = 'error';
		$return['message'] = $error;
	}
	echo json_encode($return);
}

/**
 *	$.ajax({ method: sign_up, user_name: x...x, email_address: x...x[, company_name: x...x][, phone_number: x...x][, newsletter: [yes/no]] });
 *
 *	status: ok
 * message: x...x
 */
private function sign_up() {
	$db = Zend_Registry::get('db');

     $user_name     = get_request( 'user_name'         );
     $email_address = get_request( 'email_address'     );
     $company_name  = get_request( 'company_name'      );
     $phone         = get_request( 'phone_number'      );
     $newsletter    = get_request( 'newsletter'        );

     if(  is_empty( $company_name )) {
	  $company_id = COMPANY_ID;
     } else {
	  $set = '  updated_at     = "' . date( 'Y-m-d H:i:s' ) . '"'
	       . ', parent_id      =  ' . get_session( 'control_company', COMPANY_ID )
	       . ', company_name   = "' . $company_name     . '"'
	       . ', start_date     = "' . date( 'Y-m-d' )   . '"'
	       . ', company_number =  ' . $this->getUniqueNumber( 'Companies', 'company_number' )
	       ;
	  $sql = 'INSERT Companies'
	       . '   SET ' . $set
	       ;
//$this->log_sql( null, 'sign_up', $sql );
	  $db->query( $sql );
	  $company_id = $db->lastInsertId();
	  $this->log_sql( 'sign_up', $company_id, $sql );
     }

     $user_id = $this->insert_user_jky();

     $set = '       id='  . $user_id
	  . ',  updated_at="' . date( 'Y-m-d H:i:s' ) . '"'
	  . ',  company_id='  . $company_id
	  . ',  newsletter="' . $newsletter       . '"'
	  . ', user_number='  . $this->getUniqueNumber( 'Contacts', 'user_number' )
	  . ',   user_role="' . 'member'          . '"'
	  . ',   full_name="' . $user_name        . '"'
	  . ',  first_name="' . $user_name        . '"'
	  . ',  user_email="' . $email_address    . '"'
	  . ',   user_name="' . $user_name        . '"'
	  . ',       phone="' . $phone            . '"'
	  ;
     $sql = 'INSERT Contacts'
	  . '   SET ' . $set
	  ;
//$this->log_sql( null, 'sign_up', $sql );
     $db->query( $sql );
     meta_replace( 'User', $user_id, 'unconfirmed_email', $email_address );

     $this->log_sql( 'sign_up', $user_id, $sql );

     $set = '  contact_id     =  ' . $user_id;
     $sql = 'UPDATE Companies'
	  . '   SET ' . $set
	  . ' WHERE id = ' . $company_id
	  . '   AND contact_id IS NULL'
	  ;
//$this->log_sql( null, 'sign_up', $sql );
     $db->query( $sql );
     $this->log_sql( 'sign_up', $company_id, $sql );

     email_by_event( $user_id, 'Confirm Email', 'Email From System' );

     $return = array();
     $return[ 'status'   ] = 'ok';
     $return[ 'message'  ] = 'new account created';
     echo json_encode( $return );
}

/*
 *   $.ajax({ method: send_email, user_id: 9...9, template_name: x...x });
 *
 *   status: ok
 *  message: x...x
 */
private function send_email() {
     $user_id       = get_request( 'user_id'           );
     $template_name = get_request( 'template_name'     );
     $email_from    = 'Email From System';

     $user     = db_get_row( 'Contacts' , 'id = ' . $user_id );
     $user_jky = db_get_row( 'JKY_Users', 'id = ' . $user_id );
     $to_name  = $user[ 'full_name'	];
     $to_email = $user[ 'email'		];
     $cc_name  = '';
     $cc_email = '';

     $template = db_get_row( 'Templates', 'template_name = "' . $template_name . '"' );
     $subject  = revert_entities($template[ 'template_subject'   ]);
     $body     = revert_entities($template[ 'template_body'      ]);

     $names    = explode( ';', get_control_value( 'System Keys', $email_from ));
     $from_name     = $names[ 0 ];
     $from_email    = $names[ 1 ];

     $search   = array();
     $replace  = array();
     $search[] = '+'               ; $replace[] = ' ';
     $search[] = '{SERVER_NAME}'   ; $replace[] = SERVER_NAME;
     $search[] = '{SUPPORT_NAME}'  ; $replace[] = $from_name;
     $search[] = '{USER_EMAIL}'    ; $replace[] = $user     [ 'email'		];
     $search[] = '{USER_NAME}'     ; $replace[] = $user     [ 'full_name'	];
     $search[] = '{USER_KEY}'      ; $replace[] = $user_jky [ 'user_key'	];

     $subject  = str_replace( $search, $replace, $subject   );
     $body     = str_replace( $search, $replace, $body      );

     email_now( $from_email, $from_name, $to_email, $to_name, $cc_email, $cc_name, $subject, $body );

     $return = array();
     $return[ 'status'   ] = 'ok';
     $return[ 'message'  ] = 'Email sent out, the template: ' . $template_name;
     echo json_encode( $return );
}

/*
 *   $.ajax({ method: send_receipt, receive_id: 9...9, template_name: x...x });
 *
 *   status: ok
 *  message: x...x
 */
    private function send_receipt() {
	$receive_id    = get_request( 'receive_id'        );
	$template_name = get_request( 'template_name'     );
	$email_from    = 'Email From System';

	$receive  = db_get_row ('Receives', 'id = ' . $receive_id);
	$services = db_get_rows('Services', 'receive_id = ' . $receive_id);

	$helper_names = '';
	foreach ($services as $service) {
	    $user = db_get_row('Contacts', 'id = ' . $service['user_id']);
	    $helper_names .= '<br>' . $user['full_name'];
	}

	$to_name  = $receive['full_name'];
	$to_email = $receive['email'    ];
	$cc_name  = '';
	$cc_email = '';

	$template = db_get_row( 'Templates', 'template_name = "' . $template_name . '"' );
	$subject  = revert_entities($template[ 'template_subject'   ]);
	$body     = revert_entities($template[ 'template_body'      ]);

	$names    = explode( ';', get_control_value( 'System Keys', $email_from ));
	$from_name     = $names[ 0 ];
	$from_email    = $names[ 1 ];

	$search   = array();
	$replace  = array();
	$search[] = '+'                 ; $replace[] = ' ';
	$search[] = '{SERVER_NAME}'     ; $replace[] = SERVER_NAME;
	$search[] = '{FULL_NAME}'       ; $replace[] = $receive['full_name'     ];
	$search[] = '{STREET}'          ; $replace[] = $receive['street'        ];
	$search[] = '{CITY}'            ; $replace[] = $receive['city'          ];
	$search[] = '{ZIP}'             ; $replace[] = $receive['zip'           ];
	$search[] = '{STATE}'           ; $replace[] = $receive['state'         ];
	$search[] = '{RECEIVE_ON}'      ; $replace[] = format_date($receive['receive_on']);
	$search[] = '{RECEIVE_AMOUNT}'  ; $replace[] = $receive['receive_amount'];
	$search[] = '{EVENT_NAME}'      ; $replace[] = get_session('event_name');
	$search[] = '{HELPER_NAMES}'    ; $replace[] = $helper_names;

	$subject  = str_replace( $search, $replace, $subject   );
	$body     = str_replace( $search, $replace, $body      );

	email_now( $from_email, $from_name, $to_email, $to_name, $cc_email, $cc_name, $subject, $body );

	$return = array();
	$return[ 'status'   ] = 'ok';
	$return[ 'message'  ] = 'Email sent out, the template: ' . $template_name;
	echo json_encode( $return );
    }

//   ---------------------------------------------------------------------------

private function get_last_id( $table, $where='1' ) {
     $sql = 'SELECT id'
	  . '  FROM ' . $table
	  . ' WHERE ' . $where
	  . ' ORDER BY updated_at DESC'
	  . ' LIMIT 0, 1'
	  ;
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

private function get_only_id($table, $where) {
	$sql= 'SELECT id'
		. '  FROM ' . $table
		. ' WHERE ' . $where
		;
//$this->log_sql( 'get_only_id', null, $sql );
     $db  = Zend_Registry::get( 'db' );
     return $db->fetchOne( $sql );
}

private function log_sql( $table, $id, $sql ) {
     $date = date( 'Y-m-d' );
     $time = date( 'H:i:s' );

     $logFile = fopen( SERVER_BASE . 'logsql/' . $date . '.txt', 'a' ) or die( 'cannot open logsql file' );
     fwrite( $logFile, get_now() . ' table ' . $table . ' id ' . $id . ' ' . $sql . NL );
     fclose( $logFile );
}

private function history_log($method, $table, $parent_id, $new, $old) {
	$history = '';
	$first   = '';
	foreach($new as $key=>$value) {
	    if ($method == 'update') {
		if ($key != 'updated_at' and $new[$key] != $old[$key]) {
		    $history .= $first . $key . ':' . $old[$key] . '=>'. $value;
		    $first = ', ';
		}
	    } else {
		$history .= $first . $key . ':' . $value;
		$first = ', ';
	    }
	}

	if (empty($history)) {
	    return;
	}
	$my_id = get_next_id('History');
	$sql= 'INSERT History'
	    . '   SET     id='  . $my_id
	    . ',  updated_by='  . get_session('user_id')
		. ',  updated_at="' . get_time() . '"'
	    . ', parent_name="' . $table . '"'
	    . ',   parent_id='  . $parent_id
	    . ',      method="' . $method . '"'
	    . ',     history="' . $history . '"'
	;
//$this->log_sql('History', $my_id, $sql);
	$db  = Zend_Registry::get( 'db' );
	$db->query( $sql );
	insert_changes($db, 'History', $my_id);
}

private function getUniqueNumber( $table, $field ) {
     while ( true ) {
	  $number = mt_rand( 1000000000, 1999999999 );
	  $sql = 'SELECT id'
	       . '  FROM ' . $table
	       . ' WHERE ' . $field . ' = ' . $number
	       ;
	  $db  = Zend_Registry::get( 'db' );
	  $result = $db->fetchOne( $sql );
	  if( !$result )
	       return $number;
     }
}

private function echo_error( $message ) {
     $return = array();
     $return[ 'status'  ] = 'error';
     $return[ 'message' ] = $message;
     echo json_encode( $return );
}

/*
 *   $.ajax({ method:'set_amount', table:'Admin', receive_id:receive_id, service_id:service_id, fee_amount:fee_amount};
 *
 *   status: ok
 *  message: record updated
 */
private function set_amount() {
	$table      = get_request('table'     );
	$receive_id = get_request('receive_id');
	$service_id = get_request('service_id');
	$fee_amount = get_request('fee_amount');

	if(  $receive_id == ''
	or   $service_id == ''
	or   $fee_amount == '' ) {
	    $this->echo_error( 'missing input fields' );
	    return;
	}

	$updated = '  updated_by='  . get_session( 'user_id' )
		 . ', updated_at="' . get_time() . '"'
		 ;

	$sql = 'UPDATE Receives'
	     . '   SET ' . $updated
	     . '     , set_amount = set_amount + ' . $fee_amount
	     . ' WHERE id = ' . $receive_id
	     ;
	$this->log_sql( $table, 'update', $sql );
	$db  = Zend_Registry::get( 'db' );
	$db->query( $sql );

	if ($fee_amount < 0) {
	    $receive_id = 'null';
	}
	$sql = 'UPDATE Services'
	     . '   SET ' . $updated
	     . '     , receive_id = ' . $receive_id
	     . '     , receive_amount = receive_amount + ' . $fee_amount
	     . ' WHERE id = ' . $service_id
	     ;
	$this->log_sql( $table, 'update', $sql );
	$db  = Zend_Registry::get( 'db' );
	$db->query( $sql );

	$return = array();
	$return[ 'status'   ] = 'ok';
	$return[ 'message'  ] = 'record updated';
	echo json_encode( $return );
    }

/*
 *   $.ajax({ method:'reset_amount', table:'Admin', receive_id:receive_id, service_id:service_id, fee_amount:fee_amount};
 *
 *   status: ok
 *  message: record updated
 */
private function reset_amount() {
	$table      = get_request('table'     );
	$receive_id = get_request('receive_id');
	$service_id = get_request('service_id');
	$fee_amount = get_request('fee_amount');

	if(  $receive_id == ''
	or   $service_id == ''
	or   $fee_amount == '' ) {
	    $this->echo_error( 'missing input fields' );
	    return;
	}

	$updated = '  updated_by='  . get_session( 'user_id' )
		 . ', updated_at="' . get_time() . '"'
		 ;

	$sql = 'UPDATE Receives'
	     . '   SET ' . $updated
	     . '     , set_amount = set_amount - ' . $fee_amount
	     . ' WHERE id = ' . $receive_id
	     ;
	$this->log_sql( $table, 'update', $sql );
	$db  = Zend_Registry::get( 'db' );
	$db->query( $sql );

	if ($fee_amount < 0) {
	    $receive_id = 'null';
	}
	$sql = 'UPDATE Services'
	    . '   SET ' . $updated
	    . '     , receive_id = ' . $receive_id
	    . '     , receive_amount = receive_amount - ' . $fee_amount
	    . ' WHERE id = ' . $service_id
	;
	$this->log_sql( $table, 'update', $sql );
	$db  = Zend_Registry::get( 'db' );
	$db->query( $sql );

	$return = array();
	$return[ 'status'   ] = 'ok';
	$return[ 'message'  ] = 'record updated';
	echo json_encode( $return );
    }

/*
 *   $.ajax({ method: refresh, table: x...x });
 *
 *   status: ok
 */
private function Xrefresh() {
/*
	if (get_session('user_action') != 'All') {
	    return;
	}

	$table         = get_request( 'table' );
	$db            = Zend_Registry::get( 'db' );

	if( $table == 'Summary' ) {
	    $fileName = '../sql/Summary.sql';
	    $inpFile  = fopen( $fileName, 'r' );
	    $sql      = fread( $inpFile, filesize( $fileName ));
	    fclose( $inpFile );

	    $db->query($sql);
	}
*/
	$this->init_counters();

	$sql = 'SELECT Services.*'
	     . '     , Contacts.gender, Contacts.all_gifts, Contacts.church_name'
	     . '  FROM Services'
	     . '  LEFT JOIN Contacts ON Contacts.id = Services.user_id'
	     . ' WHERE Services.event_id = ' . get_session('event_id')
	     ;
	$db   = Zend_Registry::get( 'db' );
	$rows = $db->fetchAll( $sql );
	foreach( $rows as $row ) {
	    $weeks = 0;
	    if ($row['week_1']=='yes')  {$weeks += 1;}
	    if ($row['week_2']=='yes')  {$weeks += 1;}
	    if ($row['week_3']=='yes')  {$weeks += 1;}

	    $week_1 = ($weeks == 1) ? 1 : 0;
	    $week_2 = ($weeks == 2) ? 1 : 0;
	    $week_3 = ($weeks == 3) ? 1 : 0;

	    $gender = $row['gender'];
	    if ($gender == '') {
		$gender = 'unknown';
	    }
	    $this->add_counter('Tshirt Size'        , $gender       . ' + ' . $row['tshirt_size'], $week_1, $week_2, $week_3);

	    $week_1 = ($row['week_1']=='yes') ? 1 : 0;
	    $week_2 = ($row['week_2']=='yes') ? 1 : 0;
	    $week_3 = ($row['week_3']=='yes') ? 1 : 0;

	    $this->add_counter('Language English'   , 'Speaking '   . ' + ' . $row['en_speaking'], $week_1, $week_2, $week_3);
	    $this->add_counter('Language English'   , 'Reading '    . ' + ' . $row['en_reading' ], $week_1, $week_2, $week_3);
	    $this->add_counter('Language English'   , 'Writing '    . ' + ' . $row['en_writing' ], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Mandarim'  , 'Speaking '   . ' + ' . $row['ma_speaking'], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Mandarim'  , 'Reading '    . ' + ' . $row['ma_reading' ], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Mandarim'  , 'Writing '    . ' + ' . $row['ma_writing' ], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Taiwanese' , 'Speaking '   . ' + ' . $row['tw_speaking'], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Taiwanese' , 'Reading '    . ' + ' . $row['tw_reading' ], $week_1, $week_2, $week_3);
	    $this->add_counter('Language Taiwanese' , 'Writing '    . ' + ' . $row['tw_writing' ], $week_1, $week_2, $week_3);

	    $age = $row['helper_age'];
		 if( $age > 20 and $age < 31)    $age = '21 - 30';
	    else if( $age > 30 and $age < 41)    $age = '31 - 40';
	    else if( $age > 40 and $age < 51)    $age = '41 - 50';
	    else if( $age > 50 and $age < 61)    $age = '51 - 60';
	    else if( $age > 60              )    $age = '61 +'   ;
	    $this->add_counter('Count by Age'           , $age          , $week_1, $week_2, $week_3);

	    $school_year = $row['school_year'];
	    if ($school_year == '') {
		$school_year = 'unknown';
	    }
	    $this->add_counter('Count by School Year'   , $school_year  , $week_1, $week_2, $week_3);

	    $names = explode(' ', $row['all_gifts']);
	    foreach($names as $name) {
		if ($name != '') {
		    $this->add_counter('Count by Gift'  , $name         , $week_1, $week_2, $week_3);
		}
	    }

	    $church_name = $row['church_name'];
	    if ($church_name == '' or $church_name == 'null') {
		$church_name = 'unknown';
	    }
	    $this->add_counter('Count by Church'        , $church_name  , $week_1, $week_2, $week_3);

	    $completed = $row['completed'];
	    $this->add_counter('Percentage Completed'   , $completed    , $week_1, $week_2, $week_3);
	}

	$sql = NL . 'TRUNCATE TABLE Summary;'
	     . NL . 'INSERT INTO `Summary` (`id`, `group_by`, `group_key`, `week_1`, `week_2`, `week_3`) VALUES '
	     ;
	$first = ' ';
	$counters = $this->get_counters();
	foreach($counters as $group_by=>$counter_by) {
	    foreach($counter_by as $group_key=>$counter_key) {
		$sql .= NL . $first . '(NULL, "' . $group_by . '", "' . $group_key . '", ' . $counter_key[0] . ', ' . $counter_key[1] . ', ' . $counter_key[2] . ')';
		$first = ',';
	    }
	}
	$sql .= "\n;";
	$db->query($sql);

	$return = array();
	$return[ 'status'   ] = 'ok';
	echo json_encode( $return );
}

/**
 *	$.ajax({ method: refresh, table: x...x, reference_date: yyy-mm-dd });
 *
 *	return: [ x...x, ..., x...x ]
 */
private function refresh($data) {
	$table			= get_data($data, 'table');
	$reference_date = get_data($data, 'reference_date');

	$sql= 'SET @cut_off_date = ' . $reference_date . ';'
		. 'TRUNCATE PurchaseMonthly;'
		. 'TRUNCATE ThreadJoined;'
		. 'TRUNCATE ThreadForecast;'
		. 'INSERT PurchaseMonthly(thread_id, supplier_id, months, forecast_weight)'
		. 'SELECT PurchaseLines.thread_id'
		. '	    , Purchases.supplier_id'
		. '	    , 12 * (YEAR(PurchaseLines.expected_date) - YEAR(@cut_off_date)) + (MONTH(PurchaseLines.expected_date) - MONTH(@cut_off_date)) AS months'
		. '	    , SUM(PurchaseLines.expected_weight - PurchaseLines.received_weight) AS forecast_weight'
		. '  FROM PurchaseLines'
		. '  LEFT JOIN Purchases ON Purchases.id = PurchaseLines.parent_id'
		. ' WHERE PurchaseLines.status = "Draft"'
		. '   AND PurchaseLines.expected_weight > PurchaseLines.received_weight'
		. ' GROUP BY thread_id, supplier_id, months'
		. ';'
		. 'INSERT ThreadJoined(thread_id, supplier_id, invoice_date, current_balance)'
		. 'SELECT Batches.thread_id'
		. '     , Incomings.supplier_id'
		. '     , MIN(Incomings.invoice_date) AS invoice_date'
		. '     , SUM(IF(Boxes.status = "Check In" OR Boxes.status = "Return", IF(Boxes.real_weight = 0, Boxes.average_weight, Boxes.real_weight), 0)) AS current_balance'
		. '  FROM Boxes'
		. '  LEFT JOIN Batches				ON Batches.id = Boxes.batch_id'
		. '  LEFT JOIN Incomings  			ON Incomings.id	= Batches.incoming_id'
		. ' WHERE Batches.status = "Active"'
		. ' GROUP BY thread_id, supplier_id'
		. ';'
		. 'INSERT ThreadJoined(thread_id, supplier_id, months, forecast_weight)'
		. 'SELECT *'
		. '  FROM PurchaseMonthly'
		. ';'
		. 'INSERT ThreadForecast(thread_id, supplier_id, invoice_date, current_balance, forecast_past, forecast_month_0, forecast_month_1, forecast_month_2, forecast_month_3, forecast_future)'
		. 'SELECT thread_id'
		. '	    , supplier_id'
		. '	    , invoice_date'
		. '	    , SUM(current_balance) AS current_balance'
		. '	    , SUM(IF (months < 0, forecast_weight, 0)) AS forecast_past'
		. '	    , SUM(IF (months = 0, forecast_weight, 0)) AS forecast_month_0'
		. '	    , SUM(IF (months = 1, forecast_weight, 0)) AS forecast_month_1'
		. '	    , SUM(IF (months = 2, forecast_weight, 0)) AS forecast_month_2'
		. '	    , SUM(IF (months = 3, forecast_weight, 0)) AS forecast_month_3'
		. '	    , SUM(IF (months > 3, forecast_weight, 0)) AS forecast_future'
		. '  FROM ThreadJoined'
		. ' GROUP BY thread_id, supplier_id'
		. ';'
		. 'UPDATE Configs'
		. '   SET Configs.value = @cut_off_date'
		. ' WHERE Configs.group_set = "System Controls"'
		. '   AND Configs.name = "Reference Date"'
		. ';'
		;
	$this->log_sql( $table, 'refresh', $sql );
	$db   = Zend_Registry::get('db');
	$db->query($sql);

	$return = array();
	$return[ 'status' ] = 'ok';
	$return[ 'message'] = 'Refreshed';
	echo json_encode( $return );
}

}

?>