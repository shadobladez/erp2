<?
/**
 *	generate Sale from Quotations
 *
 * @param	int		quotation_id
 * @return	int		count of Sale
 *
 *  Lines generated
 */
function JKY_generate_sale($the_id) {
	$db = Zend_Registry::get('db');

	$sql= 'SELECT *'
		. '  FROM Quotations'
		. ' WHERE id = ' . $the_id
		;
	$my_quotation = $db->fetchRow($sql);
	$sql= 'SELECT *'
		. '  FROM Contacts'
		. ' WHERE id = ' . $my_quotation['customer_id'	]
		;
	$my_customer = $db->fetchRow($sql);

	$my_needed_at = $my_quotation['needed_at'];
	if ($my_needed_at == null) {
		$my_needed_at = get_time();
	}

	$my_sale_id = get_next_id('Sales');
	$sql= 'INSERT Sales'
		. '   SET          id ='  . $my_sale_id
		. ',       updated_by ='  . get_session('user_id')
		. ',       updated_at ="' . get_time() . '"'
		. ',      sale_number ='  . $my_sale_id
		. ',     quotation_id ='  . $the_id
		. ',        sold_date ="' . get_time() . '"'
		. ',      sold_pieces ='  . $my_quotation['quoted_pieces'	]
		. ',      sold_amount ='  . $my_quotation['quoted_amount'	]
		. ',  discount_amount ='  . $my_quotation['discount_amount'	]
		. ',  advanced_amount ='  . $my_quotation['advanced_amount'	]
		. ',       is_taxable ="' . $my_customer ['is_taxable'		] . '"'
		. ',   icms_exemption ="' . $my_customer ['icms_exemption'	] . '"'
		. ',      deduct_cone ="' . $my_customer ['deduct_cone'		] . '"'
		. ',     payment_type ="' . $my_customer ['payment_type'		] . '"'
		. ',         payments ="' . $my_customer ['payments'			] . '"'
		. ',          remarks ="' . $my_quotation['remarks'			] . '"'
		;
	if ($my_quotation['salesman_id'		])	$sql .= ',      salesman_id='  . $my_quotation['salesman_id'	];
	if ($my_quotation['customer_id'		])	$sql .= ',      customer_id='  . $my_quotation['customer_id'	];
	if ($my_quotation['contact_id'		])	$sql .=	',       contact_id='  . $my_quotation['contact_id'		];
	if ($my_quotation['needed_at'		])	$sql .=	',      needed_date="' . $my_quotation['needed_at'		] . '"';
	if ($my_customer ['interest_rate'	])	$sql .=	',    interest_rate='  . $my_customer ['interest_rate'	];
log_sql('Sales', 'INSERT', $sql);
	$db->query($sql);
	insert_changes($db, 'Sales', $my_sale_id);

	$sql= 'SELECT *'
		. '  FROM QuotLines'
		. ' WHERE parent_id = ' . $the_id
		;
	$my_lines = $db->fetchAll($sql);

	$my_count = 0;
	foreach($my_lines as $my_line) {
		$my_sale_line_id = get_next_id('SaleLines');
		$sql= 'INSERT SaleLines'
			. '   SET          id ='  . $my_sale_line_id
			. ',       updated_by ='  . get_session('user_id')
			. ',       updated_at ="' . get_time() . '"'
			. ',        parent_id ='  . $my_sale_id
			. ',             peso ='  . $my_line['peso'			]
			. ',    quoted_weight ='  . $my_line['quoted_weight'	]
			. ',     quoted_units ='  . $my_line['quoted_units'	]
			. ',            units ='  . $my_line['units'			]
			. ',    quoted_pieces ='  . $my_line['quoted_pieces'	]
			. ',         discount ="' . $my_line['discount'		] . '"'
			. ',		  remarks ="' . $my_line['remarks'		] . '"'
			;
		if ($my_line['product_id'])	$sql .= ',       product_id='  . $my_line['product_id'];
		if ($my_line['machine_id'])	$sql .= ',       machine_id='  . $my_line['machine_id'];
log_sql('SaleLines', 'INSERT', $sql);
		$db->query($sql);
		insert_changes($db, 'SaleLines', $my_sale_line_id);

		$sql= 'SELECT *'
			. '  FROM QuotColors'
			. ' WHERE parent_id = ' . $my_line['id']
			;
		$my_colors = $db->fetchAll($sql);

		foreach($my_colors as $my_color) {
			$my_sale_color_id = get_next_id('SaleColors');
			$sql= 'INSERT SaleColors'
				. '   SET          id ='  . $my_sale_color_id
				. ',       updated_by ='  . get_session('user_id')
				. ',       updated_at ="' . get_time() . '"'
				. ',        parent_id ='  . $my_sale_line_id
				. ',       color_type ="' . $my_color['color_type'	] . '"'
				. ',     quoted_units = ' . $my_color['quoted_units'	]
				. ',     quoted_price ='  . $my_color['quoted_price'	]
				. ',    product_price ='  . $my_color['product_price']
				. ',         discount ="' . $my_color['discount'		] . '"'
				;
			if ($my_color['dyer_id'	])		$sql .= ',          dyer_id='  . $my_color['dyer_id'	];
			if ($my_color['color_id'])		$sql .= ',         color_id='  . $my_color['color_id'	];
log_sql('SaleColors', 'INSERT', $sql);
			$db->query($sql);
			insert_changes($db, 'SaleColors', $my_sale_color_id);
		}

 		$my_count++;
 	}

	$sql= 'UPDATE Quotations'
		. '   SET status = "Closed"'
		. ' WHERE id = ' . $the_id
		;
log_sql('Quotations', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'Quotations', $the_id);

	return $my_count;
}
