<?

/**
 *	$.ajax({ method: checkin, table: x...x, id: x...x });
 *
 *	return: [ status, message ]
 */
function JKY_checkin($data) {
	$table = get_data($data, 'table');

	$message = '';
	switch($table) {
		case 'Boxes'		: $message = JKY_checkin_box	($data); break;
		case 'Pieces'		: $message = JKY_checkin_piece	($data); break;
	}

	$return = array();
	if ($message == '') {
		$return['status'  ] = 'ok';
		$return['message' ] = 'record checked in';
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = $message;
	}
	return $return;
}

/**
 *	checkin Box from Boxes Check In
 *
 *	$.ajax({ method:'checkin', table:'Boxes', barcode:9...9};
 *
 * @return	string	''
 */
function JKY_checkin_box($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode = get_data($the_data, 'barcode');

	$sql= 'UPDATE Boxes'
		. '   SET ' . get_updated()
		. ',     status="Check In"'
		. ', checkin_by='  . get_session('user_id')
		. ', checkin_at="' . get_time() . '"'
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
		. '   SET checkin_boxes  = checkin_boxes  + 1'
		. '     , checkin_weight = checkin_weight + ' . $my_weight
		. ' WHERE id = ' . $my_box['batch_id']
		;
	log_sql('Batches', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Batches', $my_box['batch_id']);

	return '';
}

/**
 *	checkin Piece from Pieces Check In
 *
 *	$.ajax({ method:'checkin', table:'Pieces', barcode:9...9, ...};
 *
 * @return	string	''
 */
function JKY_checkin_piece($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode			= get_data($the_data, 'barcode'			);
	$my_checkin_weight	= get_data($the_data, 'checkin_weight'	);
/*
	$my_inspected_by	= get_data($the_data, 'inspected_by'	);
	$my_weighed_by		= get_data($the_data, 'weighed_by'		);
	$my_qualities		= get_data($the_data, 'qualities'		);
	$my_remarks			= get_data($the_data, 'remarks'			);
	$my_checkin_weight	= get_data($the_data, 'checkin_weight'	);
	$my_checkin_location= get_data($the_data, 'checkin_location');
*/
	$my_set  = '';
//	$my_set .= isset($the_data['revised_by'			]) ?       ', revised_by = '  . trim($the_data['revised_by'			])			: '';
	$my_set .= ', revised_by = '  . get_session('user_id');
//	$my_set .= isset($the_data['weighed_by'			]) ?       ', weighed_by = '  . trim($the_data['weighed_by'			])			: '';
	$my_set .= ', weighed_by = '  . get_session('user_id');
	$my_set .= isset($the_data['qualities'			]) ?        ', qualities =\'' . trim($the_data['qualities'			]) . '\''	: '';
	$my_set .= isset($the_data['remarks'			]) ?          ', remarks =\'' . trim($the_data['remarks'			]) . '\''	: '';
	$my_set .= isset($the_data['checkin_weight'		]) ?   ', checkin_weight = '  . trim($the_data['checkin_weight'		])			: '';
	$my_set .= isset($the_data['checkin_location'	]) ? ', checkin_location =\'' . trim($the_data['checkin_location'	]) . '\''	: '';
	
	$sql= 'UPDATE Pieces'
		. '   SET ' . get_updated()
		. ',       checkin_at=\'' . get_time()			. '\''
/*		
//		. ',           status="Check In"'
//		. ',          barcode=\'' . $my_barcode			. '\''
//		. ',       revised_by=  ' . $my_revised_by
//		. ',       weighed_by=  ' . $my_weighed_by
//		. ',   checkin_weight=  ' . $my_checkin_weight
//		. ', checkin_location=\'' . $my_checkin_location. '\''
//		. ',        qualities=\'' . $my_qualities		. '\''
//		. ',          remarks=\'' . $my_remarks			. '\''
*/
		. $my_set
		. ' WHERE id =' . $my_barcode
		;
	log_sql('Pieces', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Pieces', $my_barcode);

	$my_piece = db_get_row('Pieces', 'barcode =\'' . $my_barcode . '\'');
	if (!is_empty($my_piece['revised_by'])
	&&  !is_empty($my_piece['weighed_by'])) {
		$sql= 'UPDATE Pieces'
			. '   SET status="Check In"'
			. ' WHERE id = ' . $my_barcode
			;
		log_sql('Pieces', 'update', $sql);
		$db->query($sql);
	}
	
//	$my_order_id = get_table_value('Pieces', 'order_id', $my_barcode);
	$my_order_id = $my_piece['order_id'];
	if (strtolower($my_piece['qualities']) == 'boa') {
		$my_set = ', produced_at=\'' . get_time() . '\''
				. ', produced_pieces = produced_pieces + 1'
				. ', produced_weight = produced_weight + ' . $my_checkin_weight
				;
	}else{
		$my_set = ', rejected_pieces = rejected_pieces + 1';
	}
	$sql= 'UPDATE Orders'
		. '   SET ' . get_updated() . $my_set
		. ' WHERE id = ' . $my_order_id
		;
	log_sql('Orders', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Orders', $my_order_id);

	return '';
}

