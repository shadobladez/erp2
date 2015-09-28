<?
/**
 *	generate TDyers
 *
 * @param	int		tdyer_id
 * @return	int		count of CheckOuts generated
 */
function JKY_generate_tdyer($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM TDyers'
		. ' WHERE id = ' . $the_id
		;
	$my_tdyer = $db->fetchRow($sql);

	$my_needed_date = $my_tdyer['needed_at'];
	if ($my_needed_date == null) {
		$my_needed_date = get_time();
	}

	$my_checkout_id = get_next_id('CheckOuts');
	$sql= 'INSERT CheckOuts'
		. '   SET          id ='  . $my_checkout_id
		. ',       updated_by ='  . get_session('user_id')
		. ',       updated_at ="' . get_time() . '"'
		. ',           number ='  . $my_checkout_id
		. ',          dyer_id ='  . $my_tdyer['dyer_id']
		. ',     requested_at ="' . $my_needed_date . '"'
		. ', requested_weight ='  . $my_tdyer['ordered_weight']
		;
log_sql('CheckOuts', 'INSERT', $sql);
	$db->query($sql);
	insert_changes($db, 'CheckOuts', $my_checkout_id);

	$sql= 'SELECT *'
		. '  FROM TDyerThreads'
		. ' WHERE parent_id=' . $the_id
		;
	$my_rows = $db->fetchAll($sql);

	$my_count = 0;
	foreach($my_rows as $my_row) {
		$my_batch = db_get_row('Batches', 'id=' . $my_row['batchin_id']);
		$my_ordered_weight = db_get_sum('TDyerColors', 'ordered_weight', 'parent_id=' . $my_row['id']);
		$my_ordered_boxes  = ceil((float)$my_ordered_weight / (float)$my_batch['average_weight']);
		$my_batchout_id = get_next_id('BatchOuts');
		$sql= 'INSERT BatchOuts'
			. '   SET          id ='  . $my_batchout_id
			. ',       updated_by ='  . get_session('user_id')
			. ',       updated_at ="' . get_time() . '"'
			. ',      checkout_id ='  . $my_checkout_id
			. ',        thread_id ='  . $my_row['thread_id']
			. ',       batchin_id ='  . $my_row['batchin_id']
//			. ',      req_line_id ='  . $my_row['id']
			. ',  tdyer_thread_id ='  . $my_row['id']
//			. ',             code ="' . '' . '"'
			. ',            batch ="' . $my_batch['batch'] . '"'
			. ',       unit_price ='  . $my_batch['unit_price']
			. ',   average_weight ='  . $my_batch['average_weight']
			. ', requested_weight ='  . $my_ordered_weight
			. ',  requested_boxes ='  . $my_ordered_boxes
			;
log_sql('BatchOuts', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'BatchOuts', $my_batchout_id);

		$sql= 'UPDATE TDyerThreads'
			. '   SET batchout_id = ' . $my_batchout_id
			. ' WHERE id = ' . $my_row['id']
			;
log_sql('TDyerThreads', 'UPDATE', $sql);
		$db->query($sql);
		insert_changes($db, 'TDyerThreads', $my_row['id']);

		$my_count++;
	}

	$sql= 'UPDATE TDyers'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('TDyers', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'TDyers', $the_id);

	return $my_count;
}
