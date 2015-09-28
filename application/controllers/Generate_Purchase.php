<?
/**
 *	generate Purchase
 *
 * @param	int		purchase_id
 * @return	int		count of Incomings generated
 */
function JKY_generate_purchase($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM Purchases'
		. ' WHERE id = ' . $the_id
		;
	$my_purchase = $db->fetchRow($sql);

	$sql= 'SELECT *'
		. '  FROM PurchaseLines'
		. ' WHERE parent_id = ' . $the_id
		;
	$my_rows = $db->fetchAll($sql);

	$my_count = 0;
	foreach($my_rows as $my_row) {
		$my_incoming_id = get_next_id('Incomings');
		$sql= 'INSERT Incomings'
			. '   SET         id = ' . $my_incoming_id
			. ',      updated_by = ' . get_session('user_id')
			. ',      updated_at ="' . get_time() . '"'
			. ', incoming_number = ' . $my_incoming_id
			. ',     supplier_id = ' . $my_purchase['supplier_id']
			. ',    invoice_date ="' . $my_row['expected_date'] . '"'
			. ',  invoice_weight = ' . $my_row['expected_weight']
			;
log_sql('Incomings', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'Incomings', $my_incoming_id);

		$my_batchin_id = get_next_id('Batches');
		$sql= 'INSERT Batches'
			. '   SET          id = ' . $my_batchin_id
			. ',       updated_by = ' . get_session('user_id')
			. ',       updated_at ="' . get_time() . '"'
			. ',      incoming_id = ' . $my_incoming_id
			. ',        thread_id = ' . $my_row['thread_id']
			. ', purchase_line_id = ' . $my_row['id']
			;
log_sql('Batches', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'Batches', $my_batchin_id);

		$sql= 'UPDATE PurchaseLines'
			. '   SET batch_id =  ' . $my_batchin_id
			. ' WHERE id = ' . $my_row['id']
			;
log_sql('PurchaseLines', 'UPDATE', $sql);
		$db->query($sql);
		insert_changes($db, 'PurchaseLines', $my_row['id']);

		$my_count++;
	}

	$sql= 'UPDATE Purchases'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('Purchases', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'Purchases', $the_id);

	return $my_count;
}
