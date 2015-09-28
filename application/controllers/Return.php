<?

/**
 *	$.ajax({ method: return, table: x...x, id: x...x });
 *
 *	return: [ status, message ]
 */
function JKY_return($data) {
	$table = get_data($data, 'table');

	$message = '';
	switch($table) {
		case 'Boxes'		: $message = JKY_return_box		($data); break;
		case 'Pieces'		: $message = JKY_return_piece	($data); break;
	}

	$return = array();
	if ($message == '') {
		$return['status'  ] = 'ok';
		$return['message' ] = 'record returned';
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = $message;
	}
	return $return;
}

/**
 *	return Box from Boxes Return
 * 
 *	$.ajax({ method:'return', table:'Boxes', barcode:9...9};
 *
 * @return	string	''
 */
function JKY_return_box($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode			= get_data($the_data, 'barcode'			);
	$my_number_of_cones	= get_data($the_data, 'number_of_cones'	);
	$my_real_weight		= get_data($the_data, 'real_weight'		);

	$sql= 'UPDATE Boxes'
		. '   SET ' . get_updated()
		. ',           status="Return"'
		. ',      returned_by='  . get_session('user_id')
		. ',      returned_at="' . get_time() . '"'
		. ', number_of_cones ='  . $my_number_of_cones
		. ',     real_weight ='  . $my_real_weight
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
		. '   SET returned_boxes  = returned_boxes  + 1'
		. '     , returned_weight = returned_weight + ' . $my_weight
		. ' WHERE id = ' . $my_box['batch_id']
		;
	log_sql('Batches', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Batches', $my_box['batch_id']);

	return '';
}

/**
 *	return Piece from Pieces Return
 * 
 *	$.ajax({ method:'return', table:'Pieces', barcode:9...9, ...};
 *
 * @return	string	''
 */
function JKY_return_piece($the_data) {
	$db = Zend_Registry::get('db');
	$my_barcode			= get_data($the_data, 'barcode'			);
	$my_inspected_by	= get_data($the_data, 'inspected_by'	);
	$my_weighed_by		= get_data($the_data, 'weighed_by'		);
	$my_remarks			= get_data($the_data, 'remarks'			);
	$my_checkin_weight	= get_data($the_data, 'checkin_weight'	);
	$my_checkin_location= get_data($the_data, 'checkin_location');

	$sql= 'UPDATE Pieces'
		. '   SET ' . get_updated()
		. ',           status="Check In"'
//		. ',          barcode=\'' . $my_barcode			. '\''
		. ',     inspected_by=  ' . $my_inspected_by
		. ',       weighed_by=  ' . $my_weighed_by
		. ',          remarks=\'' . $my_remarks			. '\''
		. ',   checkin_weight=  ' . $my_checkin_weight
		. ', checkin_location=\'' . $my_checkin_location. '\''
		. ',       checkin_at=\'' . get_time()			. '\''
		. ' WHERE id =' . $my_barcode
		;
	log_sql('Pieces', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Pieces', $my_barcode);

	$my_order_id = get_table_value('Pieces', 'order_id', $my_barcode);
	$my_set = ', produced_at=\'' . get_time() . '\''
			. ', produced_pieces = produced_pieces + 1'
			;
	if ($my_remarks != 'boa') {
		$my_set = ', rejected_pieces = rejected_pieces + 1';
	}
	$my_field_name	= $my_remarks == 'boa' ? 'produced_pieces' : 'rejected_pieces';
	$sql= 'UPDATE Orders'
		. '   SET ' . get_updated() . $my_set
		. ' WHERE id = ' . $my_order_id
	     ;
	log_sql('Orders', 'update', $sql);
	$db->query($sql);
	insert_changes($db, 'Orders', $my_order_id);

	return '';
}

