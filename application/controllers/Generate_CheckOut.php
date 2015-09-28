<?
/**
 *	generate CheckOut from Planning Orders
 *
 * @param	int		order_id
 * @return	int		count of CheckOuts generated
 */
function JKY_generate_checkout($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM Orders'
		. ' WHERE id = ' . $the_id
		;
	$my_order = $db->fetchRow($sql);

	$sql= 'SELECT *'
		. '  FROM OrdThreads'
		. ' WHERE parent_id = ' . $the_id
		;
	$my_rows = $db->fetchAll($sql);
/*
	$my_needed_at = $my_order['needed_at'];
	if ($my_needed_at == null) {
		$my_needed_at = get_time();
	}
*/
	$my_checkout_id = get_next_id('CheckOuts');
	$sql= 'INSERT CheckOuts'
		. '   SET          id ='  . $my_checkout_id
		. ',       updated_by ='  . get_session('user_id')
		. ',       updated_at ="' . get_time() . '"'
		. ',           number ='  . $my_checkout_id
//		. ',     requested_at ="' . $my_needed_at . '"'
		. ',     requested_at ="' . get_time() . '"'
		. ', requested_weight ='  . $my_order['ordered_weight']
		;
	if( $my_order['machine_id']) {
		$sql .= ', machine_id='  . $my_order['machine_id'];
	}
	if( $my_order['partner_id']) {
		$sql .= ', partner_id='  . $my_order['partner_id'];
	}

log_sql('CheckOuts', 'INSERT', $sql);
	$db->query($sql);
	insert_changes($db, 'CheckOuts', $my_checkout_id);

	$my_count = 0;
	foreach($my_rows as $my_row) {
		$my_ord_thread_id	= $my_row['id'];
		$my_batch = db_get_row('Batches', 'id=' . $my_row['batchin_id']);
		$my_ordered_weight = $my_row['ordered_weight'];
		$my_ordered_boxes  = ceil((float)$my_ordered_weight / (float)$my_batch['average_weight']);
		$my_batchout_id = get_next_id('BatchOuts');
		$sql= 'INSERT BatchOuts'
			. '   SET          id ='  . $my_batchout_id
			. ',       updated_by ='  . get_session('user_id')
			. ',       updated_at ="' . get_time() . '"'
			. ',      checkout_id ='  . $my_checkout_id
			. ',        thread_id ='  . $my_row['thread_id']
			. ',       batchin_id ='  . $my_row['batchin_id']
			. ',  order_thread_id ='  . $my_ord_thread_id
			. ',            batch ="' . $my_batch['batch'] . '"'
			. ',       unit_price ='  . $my_batch['unit_price']
			. ',   average_weight ='  . $my_batch['average_weight']
			. ', requested_weight ='  . $my_ordered_weight
			. ',  requested_boxes ='  . $my_ordered_boxes
			;
log_sql('BatchOuts', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'BatchOuts', $my_batchout_id);

		$sql= 'UPDATE OrdThreads'
			. '   SET batchout_id = ' . $my_batchout_id
			. ' WHERE id = ' . $my_ord_thread_id
			;
log_sql('OrdThreads', 'UPDATE', $sql);
		$db->query($sql);
		insert_changes($db, 'OrdThreads', $my_ord_thread_id);

		$my_count++;
	}

	$sql= 'UPDATE Orders'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('Orders', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'Orders', $the_id);

	return $my_count;
}
