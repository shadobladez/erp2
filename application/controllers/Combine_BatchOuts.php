<?
/**
 *	combine BatchOuts from Threads
 *
 * @param	int		order_id
 * @return	int		count of CheckOuts combined
 */
function JKY_combine_batchouts($the_ids) {
	$db = Zend_Registry::get('db');

	$my_id = get_next_id('BatchOuts');
	$sql= 'INSERT INTO BatchOuts '
	    . 'SELECT ' . $my_id
		. '    	, ' . get_session('user_id')
		. '     , NOW()'
		. '     , status'
		. '     , MIN(checkout_id)'
		. '     , thread_id'
		. '     , batchin_id'
		. '     , req_line_id'
		. '     , tdyer_thread_id'
		. '     , order_thread_id'
		. '     , code'
		. '     , batch'
		. '     , AVG(unit_price)'
		. '     , SUM(requested_weight)'
		. '     , AVG(average_weight)'
		. '     , SUM(requested_boxes)'
		. '     , SUM(reserved_boxes)'
		. '     , SUM(checkout_boxes)'
		. '     , SUM(checkout_weight)'
		. '  FROM BatchOuts'
		. ' WHERE id IN (' . $the_ids . ')'
		;
	log_sql('BatchOuts', $my_id, $sql);
	$db->query($sql);
	insert_changes($db, 'BatchOuts', $my_id);

	$sql= 'SELECT *'
		. '  FROM BatchOuts'
		. ' WHERE id IN (' . $the_ids . ')'
		;
	log_sql('BatchOuts', $my_id, $sql);
/*
	$sql= 'UPDATE BatchOuts '
	    . '   SET status = "History"'
		. '  FROM BatchOuts'
		. ' WHERE id IN (' . $the_ids . ')'
		;
 */
	$my_rows = $db->fetchAll($sql);

	foreach($my_rows as $my_row) {
		$sql= 'UPDATE BatchOuts'
			. '   SET status = "History"'
			. ' WHERE id = ' . $my_row['id']
			;
		log_sql('BatchOuts', $my_row['id'], $sql);
		$db->query($sql);
		insert_changes($db, 'BatchOuts', $my_row['id']);
	}
	return $my_id;
}
