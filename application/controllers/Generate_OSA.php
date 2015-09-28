<?
/**
 *	generate OSA from Quotations
 *
 * @param	int		quotation_id
 * @return	int		count of OSA Lines generated
 */
function JKY_generate_osa($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM Quotations'
		. ' WHERE id = ' . $the_id
		;
	$my_quotation = $db->fetchRow($sql);

	$my_needed_at = $my_quotation['needed_at'];
	if ($my_needed_at == null) {
		$my_needed_at = get_time();
	}

	$my_osa_id = get_next_id('OSAs');
	$sql= 'INSERT OSAs'
		. '   SET          id ='  . $my_osa_id
		. ',       updated_by ='  . get_session('user_id')
		. ',       updated_at ="' . get_time() . '"'
		. ',       osa_number ='  . $my_osa_id
		. ',     quotation_id ='  . $the_id
		. ',       ordered_at ="' . get_time() . '"'
		. ',        needed_at ="' . $my_needed_at . '"'
		. ',    quoted_pieces ='  . $my_quotation['quoted_pieces']
		. ',   ordered_pieces ='  . $my_quotation['quoted_pieces']
		. ',          remarks ="' . $my_quotation['remarks'] . '"'
		;
	if ($my_quotation['customer_id'])	$sql .= ',      customer_id='  . $my_quotation['customer_id'];
	if ($my_quotation['salesman_id'])	$sql .=	',      salesman_id='  . $my_quotation['salesman_id'];
log_sql('OSAs', 'INSERT', $sql);
	$db->query($sql);
	insert_changes($db, 'OSAs', $my_osa_id);

	$sql= 'SELECT *'
		. '  FROM QuotLines'
		. ' WHERE parent_id = ' . $the_id
		;
	$my_rows = $db->fetchAll($sql);

	$my_count = 0;
	foreach($my_rows as $my_row) {
		$my_osa_line_id = get_next_id('OSA_Lines');
		$sql= 'INSERT OSA_Lines'
			. '   SET          id ='  . $my_osa_line_id
			. ',       updated_by ='  . get_session('user_id')
			. ',       updated_at ="' . get_time() . '"'
			. ',        parent_id ='  . $my_osa_id
			. ',             peso ='  . $my_row['peso'			]
			. ',     quoted_units ='  . $my_row['quoted_units'	]
			. ',            units ='  . $my_row['units'			]
			. ',    quoted_pieces ='  . $my_row['quoted_pieces'	]
			. ',   ordered_pieces ='  . $my_row['quoted_pieces'	]
			. ',    quoted_weight ='  . $my_row['quoted_weight'	]
			. ',   ordered_weight ='  . $my_row['quoted_weight'	]
			. ',		  remarks ="' . $my_row['remarks'		] . '"'
			;
		if ($my_row['product_id'])	$sql .= ',       product_id='  . $my_row['product_id'];
log_sql('OSA_Lines', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'OSA_Lines', $my_osa_line_id);

		$sql= 'SELECT *'
			. '  FROM QuotColors'
			. ' WHERE parent_id = ' . $my_row['id']
			;
		$my_colors = $db->fetchAll($sql);

		foreach($my_colors as $my_color) {
			$my_osa_color_id = get_next_id('OSA_Colors');
			if ($my_row['units'] == 0) {
				$my_quoted_pieces = ceil($my_color['quoted_units'] / $my_row['peso']);
				$my_quoted_weight = $my_color['quoted_units'];
			}else{
				$my_quoted_pieces = ceil($my_color['quoted_units'] / $my_row['units']);
				$my_quoted_weight = $my_color['quoted_units'] * $my_row['peso'];
			}

			$sql= 'INSERT OSA_Colors'
				. '   SET          id ='  . $my_osa_color_id
				. ',       updated_by ='  . get_session('user_id')
				. ',       updated_at ="' . get_time() . '"'
				. ',        parent_id ='  . $my_osa_line_id
				. ',    quoted_pieces ='  . $my_quoted_pieces
				. ',    quoted_weight ='  . $my_quoted_weight
				. ',   ordered_pieces ='  . $my_quoted_pieces
				. ',   ordered_weight ='  . $my_quoted_weight
				;
//			if ($my_quotation['customer_id'])	$sql .= ',      customer_id='  . $my_quotation['customer_id'];
			if ($my_row['machine_id'])			$sql .= ',       machine_id='  . $my_row['machine_id'];
//			if ($my_row['product_id'])			$sql .= ',       product_id='  . $my_row['product_id'];
			if ($my_color['color_id'])			$sql .= ',         color_id='  . $my_color['color_id'];
log_sql('OSA_Colors', 'INSERT', $sql);
			$db->query($sql);
			insert_changes($db, 'OSA_Colors', $my_osa_color_id);
		}

 		$my_count++;
 	}

	$sql= 'UPDATE Quotations'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('Quotations', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'Quotations', $the_id);

	return $my_count;
}
