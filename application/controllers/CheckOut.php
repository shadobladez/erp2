<?

/**
 *	$.ajax({ method: checkout, table: x...x, id: x...x });
 *
 *	return: [ status, message ]
 */
function JKY_checkout($data) {
	$table = get_data($data, 'table');

	$message = '';
	switch($table) {
		case 'Boxes'		: $message = JKY_checkout_box	($data); break;
		case 'Pieces'		: $message = JKY_checkout_piece	($data); break;
	}

	$return = array();
	if ($message == '') {
		$return['status'  ] = 'ok';
		$return['message' ] = 'record checked out';
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = $message;
	}
	return $return;
}

/**
 *	checkout Box from Boxes Check Out
 *
 *	$.ajax({ method:'checkout', table:'Boxes', barcode:9...9};
 *
 *	@return	string	''
 */
function JKY_checkout_box($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode		= get_data($the_data, 'barcode'		);
	$my_location	= get_data($the_data, 'location'	);
	$my_batchset_id	= get_data($the_data, 'batchset_id'	);

	$sql= 'UPDATE Boxes'
		. '   SET ' . get_updated()
		. ',            status="Check Out"'
		. ',       checkout_by='  . get_session('user_id')
		. ',       checkout_at="' . get_time() . '"'
		. ', checkout_location="' . $my_location . '"'
		. ' WHERE id=' . $my_barcode
		;
	log_sql('Boxes', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Boxes', $my_barcode);

	$my_box = db_get_row('Boxes', 'id=' . $my_barcode);
	$my_average_weight	= $my_box['average_weight'	];
	$my_real_weight		= $my_box['real_weight'		];
	$my_weight			= $my_real_weight == 0 ? $my_average_weight : $my_real_weight;

	$sql= 'UPDATE Batches'
		. '   SET checkout_boxes  = checkout_boxes  + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_weight
		. ' WHERE id = ' . $my_box['batch_id']
		;
	log_sql('Batches', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Batches', $my_box['batch_id']);

	$sql= 'UPDATE BatchSets'
		. '   SET reserved_boxes = reserved_boxes - 1'
		. '     , checkout_boxes = checkout_boxes + 1'
		. ' WHERE id = ' . $my_batchset_id
		;
	log_sql('BatchSets', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'BatchSets', $my_batchset_id);

	$my_batchset = db_get_row('BatchSets', 'id=' . $my_batchset_id);

	$sql= 'UPDATE BatchOuts'
		. '   SET reserved_boxes  = reserved_boxes  - 1'
		. '     , checkout_boxes  = checkout_boxes  + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_weight
		. ' WHERE id = ' . $my_batchset['batchout_id']
		;
	log_sql('BatchOuts', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'BatchOuts', $my_batchset['batchout_id']);

	$my_batchout = db_get_row('BatchOuts', 'id=' . $my_batchset['batchout_id']);
	$my_amount	 = $my_weight * $my_batchout['unit_price'];

	$sql= 'UPDATE CheckOuts'
		. '   SET checkout_weight = checkout_weight + ' . $my_weight
		. '     , checkout_amount = checkout_amount + ' . $my_amount
		. ' WHERE id = ' . $my_batchout['checkout_id']
		;
	log_sql('CheckOuts', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'CheckOuts', $my_batchout['checkout_id']);

	if ($my_batchout['req_line_id']) {
		$sql= 'UPDATE ReqLines'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_batchout['req_line_id']
			;
		log_sql('ReqLines', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'ReqLines', $my_batchout['req_line_id']);

		$my_reqline = db_get_row('ReqLines', 'id=' . $my_batchout['req_line_id']);

		$sql= 'UPDATE Requests'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_reqline['request_id']
			;
		log_sql('Requests', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'Requests', $my_reqline['request_id']);
	}

	if ($my_batchout['tdyer_thread_id']) {
		$sql= 'UPDATE TDyerThreads'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_batchout['tdyer_thread_id']
			;
		log_sql('TDyerThreads', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'TDyerThreads', $my_batchout['tdyer_thread_id']);

		$my_tdyer_thread = db_get_row('TDyerThreads', 'id=' . $my_batchout['tdyer_thread_id']);

		$sql= 'UPDATE TDyers'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_tdyer_thread['parent_id']
			;
		log_sql('TDyers', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'TDyers', $my_tdyer_thread['parent_id']);
	}

	if ($my_batchout['order_thread_id']) {
		$sql= 'UPDATE OrdThreads'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_batchout['order_thread_id']
			;
		log_sql('OrdThreads', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'OrdThreads', $my_batchout['order_thread_id']);
/*
		$my_order_thread = db_get_row('OrdThreads', 'id=' . $my_batchout['order_thread_id']);

		$sql= 'UPDATE Orders'
			. '   SET checkout_weight = checkout_weight + ' . $my_weight
			. ' WHERE id = ' . $my_order_thread['parent_id']
			;
		log_sql('Orders', 'update', $sql);
		$db->query($sql);
		insert_changes($db, 'Orders', $my_order_thread['parent_id']);
 */
	}

	return '';
}

/**
 *	checkout Piece from Pieces Check Out
 *
 *	$.ajax({ method:'checkout', table:'Pieces', barcode:9...9, ...};
 *
 * @return	string	''
 */
function JKY_checkout_piece($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode		= get_data($the_data, 'barcode'		);
	$my_location	= get_data($the_data, 'location'	);
	$my_loadset_id	= get_data($the_data, 'loadset_id'	);

	$my_piece = db_get_row('Pieces', 'id=' . $my_barcode);

	$sql= 'UPDATE LoadSets'
		. '   SET reserved_pieces = reserved_pieces - 1'
		. '     , reserved_weight = reserved_weight - ' . $my_piece['checkin_weight']
		. '     , checkout_pieces = checkout_pieces + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_piece['checkin_weight']
		. ' WHERE id = ' . $my_loadset_id
		;
	log_sql('LoadSets', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'LoadSets', $my_loadset_id);

	$my_loadset = db_get_row('LoadSets', 'id=' . $my_loadset_id);

	$sql= 'UPDATE LoadQuotations'
		. '   SET reserved_pieces = reserved_pieces - 1'
		. '     , reserved_weight = reserved_weight - ' . $my_piece['checkin_weight']
		. '     , checkout_pieces = checkout_pieces + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_piece['checkin_weight']
		. ' WHERE id = ' . $my_loadset['load_quot_id']
		;
	log_sql('LoadQuotations', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'LoadQuotations', $my_loadset['load_quot_id']);

	$my_loadquot = db_get_row('LoadQuotations', 'id=' . $my_loadset['load_quot_id']);

	$sql= 'UPDATE LoadOuts'
		. '   SET checkout_at="' . get_time() . '"'
		. '		, checkout_pieces = checkout_pieces + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_piece['checkin_weight']
		. ' WHERE id = ' . $my_loadquot['loadout_id']
		;
	log_sql('LoadOuts', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'LoadOuts', $my_loadquot['loadout_id']);

	$sql= 'UPDATE Pieces'
		. '   SET ' . get_updated()
		. ',           status="Check Out"'
		. ',       checkout_by='  . get_session('user_id')
		. ',       checkout_at="' . get_time() . '"'
		. ', checkout_location="' . $my_location . '"'
		.      ', load_quot_id= ' . $my_loadquot['id']
		. ' WHERE id =' . $my_piece['id']
		;
	log_sql('Pieces', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Pieces', $my_barcode);

	$sql= 'UPDATE Orders'
		. '   SET checkout_pieces = checkout_pieces + 1'
		. '     , checkout_weight = checkout_weight + ' . $my_piece['checkin_weight']
		. ' WHERE id = ' . $my_piece['order_id']
		;
	log_sql('Orders', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Orders', $my_piece['order_id']);

	return '';
}

