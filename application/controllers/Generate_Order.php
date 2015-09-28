<?
/**
 *	generate Order from OSAs
 *
 * @param	int		quotation_id
 * @return	int		count of Orders generated
 */
function JKY_generate_order($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM OSAs'
		. ' WHERE id = ' . $the_id
		;
	$my_osa = $db->fetchRow($sql);

	$sql= 'SELECT *'
		. '  FROM OSA_Lines'
		. ' WHERE parent_id = ' . $the_id
		;
	$my_rows = $db->fetchAll($sql);

	$my_count = 0;
	foreach($my_rows as $my_row) {
		$my_osa_line_id	= $my_row['id'];

		$sql= 'SELECT *'
			. '  FROM OSA_Colors'
			. ' WHERE parent_id = ' . $my_osa_line_id
			;
		$my_colors = $db->fetchAll($sql);

		foreach($my_colors as $my_color) {
			$my_order_id = get_next_id('Orders');
			$sql= 'INSERT Orders'
				. '   SET          id ='  . $my_order_id
				. ',       updated_by ='  . get_session('user_id')
				. ',       updated_at ="' . get_time() . '"'
				. ',     order_number ='  . $my_order_id
				. ',      osa_line_id ='  . $my_osa_line_id
				. ',       osa_number ='  . $my_osa['osa_number']
				. ',       ordered_at ="' . $my_osa['ordered_at'] . '"'
				. ',        needed_at ="' . $my_osa['needed_at' ] . '"'
				. ',     quoted_units ='  . $my_row['units' ]
				. ',    quoted_pieces ='  . $my_color['quoted_pieces']
				. ',    quoted_weight ='  . $my_color['quoted_weight']
//				. ',       color_type ="' . $my_color['color_type'] . '"'
				. ',   ordered_pieces ='  . $my_color['ordered_pieces']
				. ',   ordered_weight ='  . $my_color['ordered_weight']
				;
			if ($my_osa['customer_id'	])	$sql .= ', customer_id=' . $my_osa['customer_id'];
			if ($my_row['product_id'	])	$sql .= ',  product_id=' . $my_row['product_id'	];
			if ($my_color['color_id'	])	$sql .= ',    color_id=' . $my_color['color_id'		];
			if ($my_color['ftp_id'		])	$sql .= ',      ftp_id=' . $my_color['ftp_id'		];
			if ($my_color['machine_id'	])	$sql .= ',  machine_id=' . $my_color['machine_id'	];
			if ($my_color['partner_id'	])	$sql .= ',  partner_id=' . $my_color['partner_id'	];
	log_sql('Orders', 'INSERT', $sql);
			$db->query($sql);
			insert_changes($db, 'Orders', $my_order_id);
/*
			$sql= 'UPDATE OSA_lines'
				. '   SET status = "Active"'
				. ' WHERE id = ' . $my_row['id']
				;
	log_sql('OSA_Lines', 'UPDATE', $sql);
			$db->query($sql);
			insert_changes($db, 'OSA_Lines', $my_row['id']);
*/
			$my_count++;
		}
	}

	$sql= 'UPDATE OSAs'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('OSAs', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'OSAs', $the_id);

	return $my_count;
}
