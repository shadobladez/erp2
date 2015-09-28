<?

/**
 *	$.ajax({ method: invoice, table: x...x, id: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
function JKY_invoice($data) {
	$table	= get_data($data, 'table'	);
	$id		= get_data($data, 'id'		);

	switch($table) {
		case 'Customer'	: JKY_invoice_customer	($id); return;
		case 'Dyer'		: JKY_invoice_dyer		($id); return;
		case 'Partner'	: JKY_invoice_partner	($id); return;
	}

	JKY_echo_error('table [' . $table . '] is undefined');
}

function JKY_echo_count($count) {
	$return = array();
	$return[ 'status' ] = 'ok';
	$return[ 'count'  ] = $count;
	echo json_encode($return);
}

function JKY_echo_error($message) {
	$return = array();
	$return['status' ] = 'error';
	$return['message'] = $message;
	echo json_encode($return);
}

/**
 *	invoice to Customer from Sales
 *
 * @param	int		sale_id
 * @return	int		count of Records generated
 */
function JKY_invoice_customer($the_id) {
	$domain		= get_config_value('NFe Customer', 'domain');
	$my_return	= json_decode(proxy($domain, 'data={"method":"check_status"}'), true);
	if (!isset($my_return['status'])) {
		JKY_echo_error('domain [' . $domain . '] is not active');
		return;
	}
	if ($my_return['status'] != 'ok') {
		JKY_echo_error($my_return['message']);
		return;
	}

	$db = Zend_Registry::get('db');

	$sales = db_get_row('Sales', 'id=' . $the_id);

	$customer = db_get_row('Contacts', 'id=' . $sales['customer_id']);
	$company['pessoa'		] = $customer	['is_company'	] == 'Yes' ? 'J' : 'F';
	$company['name'			] = $customer	['full_name'	];
	$company['fantasia'		] = $customer	['nick_name'	];
	$company['email'		] = $customer	['email'		];
	$company['phone'		] = $customer	['phone'		];
	$company['fax'			] = $customer	['fax'			];
	$company['cnpj'			] = $customer	['cnpj'			];
	$company['ie'			] = $customer	['ie'			];
	$company['district'		] = $customer	['district'		];
	$company['number'		] = $customer	['st_number'	];
	$company['cpl'			] = $customer	['st_cpl'		];
	$company['street1'		] = $customer	['street1'		];
	$company['street2'		] = $customer	['street2'		];
	$company['zip'			] = $customer	['zip'			];
	$company['city'			] = $customer	['city'			];
	$company['state'		] = $customer	['state'		];
	$company['country'		] = $customer	['country'		];
	$my_return = json_decode(proxy($domain, 'data={"method":"add_company", "row":' . json_encode($company) . '}'), true);
	$my_id = $my_return['id'];
log_sql('Dyer', 'INSERT', $my_id);

	$transport = db_get_row('Contacts', 'id=' . $ship_dyer['transport_id']);
	$company['pessoa'		] = $transport	['is_company'	] == 'Yes' ? 'J' : 'F';
	$company['name'			] = $transport	['full_name'	];
	$company['fantasia'		] = $transport	['nick_name'	];
	$company['email'		] = $transport	['email'		];
	$company['phone'		] = $transport	['phone'		];
	$company['fax'			] = $transport	['fax'			];
	$company['cnpj'			] = $transport	['cnpj'			];
	$company['ie'			] = $transport	['ie'			];
	$company['district'		] = $transport	['district'		];
	$company['number'		] = $transport	['st_number'	];
	$company['cpl'			] = $transport	['st_cpl'		];
	$company['street1'		] = $transport	['street1'		];
	$company['street2'		] = $transport	['street2'		];
	$company['zip'			] = $transport	['zip'			];
	$company['city'			] = $transport	['city'			];
	$company['state'		] = $transport	['state'		];
	$company['country'		] = $transport	['country'		];
	$my_return = json_decode(proxy($domain, 'data={"method":"add_company", "row":' . json_encode($company) . '}'), true);
	$my_id = $my_return['id'];
log_sql('Transport', 'INSERT', $my_id);

	$i = 0;
	$total_volume = 0;
	$items = array();
	$salelines = db_get_rows('SaleLines', 'parent_id=' . $the_id);
	foreach($salelines as $saleline) {
		$salecolors = db_get_rows('SaleColors', 'parent_id=' . $saleline['id']);
		foreach($salecolors as $salecolor) {
			$pieces = db_get_rows('Pieces'	, 'parent_id=' . $salecolor['id']);
			$order  = db_get_row ('Orders'	, 'id=' . $pieces[0]['order_id'	]);
			$ftp	= db_get_row ('FTPs'	, 'id=' . $order['ftp_id'		]);
			$prod	= db_get_row ('Products', 'id=' . $order['product_id'	]);

			$total_weight = 0;
			foreach($pieces as $piece) {
				$total_volume ++;
				$total_weight += $piece['checkin_weight'];
			}
			$item	['NFe_id'		] = 'null';
			$item	['cProd'		] = get_config_value('NFe Dyer', 'cProd'	);
			$item	['xProd'		] = $prod['product_name'];
			$item	['cor_code'		] = get_config_value('NFe Dyer', 'cor_code'	);
			$item	['cor_type'		] = get_config_value('NFe Dyer', 'cor_type'	);
			$item	['composicao'	] = $ftp ['composition'	];
			$item	['NCM'			] = get_config_value('NFe Dyer', 'NCM'		);
			$item	['CFOP'			] = get_config_value('NFe Dyer', 'CFOP'		);
			$item	['uCom'			] = get_config_value('NFe Dyer', 'uCom'		);
			$item	['qCom'			] = $total_weight;
			$item	['vUnCom'		] = get_config_value('NFe Dyer', 'vUnCom'	);
			$item	['orig'			] = get_config_value('NFe Dyer', 'orig'		);
			$item	['CST_ICMS'		] = get_config_value('NFe Dyer', 'CST_ICMS'	);
			$item	['CST_IPI'		] = get_config_value('NFe Dyer', 'CST_IPI'	);
			$item	['pIPI'			] = get_config_value('NFe Dyer', 'pIPI'		);
			$item	['pPIS'			] = get_config_value('NFe Dyer', 'pPIS'		);
			$item	['pCOFINS'		] = get_config_value('NFe Dyer', 'pCOFINS'	);
			$items[$i] = $item;
			$i ++;

			$product['product_code'	] = get_config_value('NFe Dyer', 'cProd'	);
			$product['product_cf'	] = get_config_value('NFe Dyer', 'NCM'		);
			$product['IPI_code'		] = get_config_value('NFe Dyer', 'CST_IPI'	);
			$product['IPI_aliquota'	] = get_config_value('NFe Dyer', 'pIPI'		);
			$product['product_title'] = $prod['product_name'];
			$product['composicao'	] = $ftp ['composition'	];
			$my_return = json_decode(proxy($domain, 'data={"method":"add_product", "row":' . json_encode($product) . '}'), true);
			$my_id = $my_return['id'];
log_sql('Product', 'INSERT', $my_id);
		}
	}

	$my_count = 0;
	$nfe	['dEmi'			] = get_date();
	$nfe	['dSaiEnt'		] = get_date();
	$nfe	['tpNF'			] = get_config_value('NFe Dyer', 'tpNF'		);
	$nfe	['natOp'		] = get_config_value('NFe Dyer', 'natOp'	);
	$nfe	['dxNome'		] = $dyer		['full_name'	];
	$nfe	['txNome'		] = $transport	['full_name'	];
	$nfe	['modFrete'		] = get_config_value('NFe Dyer', 'modFrete'	);
	$nfe	['qVol'			] = $total_volume;
	$nfe	['esp'			] = get_config_value('NFe Dyer', 'esp'		);
	$nfe	['marca'		] = get_config_value('NFe Dyer', 'marca'	);
	$nfe	['pesoL'		] = '0';
	$nfe	['infCpl'		] = get_config_value('NFe Dyer', 'infCpl'	);
	$my_return = json_decode(proxy($domain, 'data={"method":"add_nfe", "row":' . json_encode($nfe) . '}'), true);
	$my_nfe_id = $my_return['id'];
	if ($my_nfe_id)		$my_count++;
log_sql('NFe', 'INSERT', $my_nfe_id);

	foreach($items as $item) {
		$item['NFe_id'] = $my_nfe_id;
		$my_return = json_decode(proxy($domain, 'data={"method":"add_nfeitem", "row":' . json_encode($item) . '}'), true);
		$my_id = $my_return['id'];
		if ($my_id)		$my_count++;
log_sql('NFeItem', 'INSERT', $my_id);
	}

	$sql= 'UPDATE ShipDyers'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('ShipDyers', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'ShipDyers', $the_id);

	JKY_echo_count($my_count);
}

/**
 *	invoice to Dyer from ShipDyer
 *
 * @param	int		shipdyer_id
 * @return	int		count of Records generated
 */
function JKY_invoice_dyer($the_id) {
	$domain		= get_config_value('NFe Dyer', 'domain');
	$my_return	= json_decode(proxy($domain, 'data={"method":"check_status"}'), true);
	if (!isset($my_return['status'])) {
		JKY_echo_error('domain [' . $domain . '] is not active');
		return;
	}
	if ($my_return['status'] != 'ok') {
		JKY_echo_error($my_return['message']);
		return;
	}

	$db	= Zend_Registry::get('db');
	$ship_dyer = db_get_row('ShipDyers', 'id=' . $the_id);

	$dyer = db_get_row('Contacts', 'id=' . $ship_dyer['dyer_id']);
	$company['pessoa'		] = $dyer		['is_company'	] == 'Yes' ? 'J' : 'F';
	$company['name'			] = $dyer		['full_name'	];
	$company['fantasia'		] = $dyer		['nick_name'	];
	$company['email'		] = $dyer		['email'		];
	$company['phone'		] = $dyer		['phone'		];
	$company['fax'			] = $dyer		['fax'			];
	$company['cnpj'			] = $dyer		['cnpj'			];
	$company['ie'			] = $dyer		['ie'			];
	$company['district'		] = $dyer		['district'		];
	$company['number'		] = $dyer		['st_number'	];
	$company['cpl'			] = $dyer		['st_cpl'		];
	$company['street1'		] = $dyer		['street1'		];
	$company['street2'		] = $dyer		['street2'		];
	$company['zip'			] = $dyer		['zip'			];
	$company['city'			] = $dyer		['city'			];
	$company['state'		] = $dyer		['state'		];
	$company['country'		] = $dyer		['country'		];
	$my_return = json_decode(proxy($domain, 'data={"method":"add_company", "row":' . json_encode($company) . '}'), true);
	$my_id = $my_return['id'];
log_sql('Dyer', 'INSERT', $my_id);

	$transport = db_get_row('Contacts', 'id=' . $ship_dyer['transport_id']);
	$company['pessoa'		] = $transport	['is_company'	] == 'Yes' ? 'J' : 'F';
	$company['name'			] = $transport	['full_name'	];
	$company['fantasia'		] = $transport	['nick_name'	];
	$company['email'		] = $transport	['email'		];
	$company['phone'		] = $transport	['phone'		];
	$company['fax'			] = $transport	['fax'			];
	$company['cnpj'			] = $transport	['cnpj'			];
	$company['ie'			] = $transport	['ie'			];
	$company['district'		] = $transport	['district'		];
	$company['number'		] = $transport	['st_number'	];
	$company['cpl'			] = $transport	['st_cpl'		];
	$company['street1'		] = $transport	['street1'		];
	$company['street2'		] = $transport	['street2'		];
	$company['zip'			] = $transport	['zip'			];
	$company['city'			] = $transport	['city'			];
	$company['state'		] = $transport	['state'		];
	$company['country'		] = $transport	['country'		];
	$my_return = json_decode(proxy($domain, 'data={"method":"add_company", "row":' . json_encode($company) . '}'), true);
	$my_id = $my_return['id'];
log_sql('Transport', 'INSERT', $my_id);

	$i = 0;
	$total_volume = 0;
	$items = array();
	$loadouts = db_get_rows('LoadOuts', 'shipdyer_id=' . $the_id);
	foreach($loadouts as $loadout) {
		$loadquots = db_get_rows('LoadQuotations', 'loadout_id=' . $loadout['id']);
		foreach($loadquots as $loadquot) {
			$pieces = db_get_rows('Pieces'	, 'load_quot_id=' .$loadquot['id']);
			$order  = db_get_row ('Orders'	, 'id=' . $pieces[0]['order_id'	]);
			$ftp	= db_get_row ('FTPs'	, 'id=' . $order['ftp_id'		]);
			$prod	= db_get_row ('Products', 'id=' . $order['product_id'	]);

			$total_weight = 0;
			foreach($pieces as $piece) {
				$total_volume ++;
				$total_weight += $piece['checkin_weight'];
			}
			$item	['NFe_id'		] = 'null';
			$item	['cProd'		] = get_config_value('NFe Dyer', 'cProd'	);
			$item	['xProd'		] = $prod['product_name'];
			$item	['cor_code'		] = get_config_value('NFe Dyer', 'cor_code'	);
			$item	['cor_type'		] = get_config_value('NFe Dyer', 'cor_type'	);
			$item	['composicao'	] = $ftp ['composition'	];
			$item	['NCM'			] = get_config_value('NFe Dyer', 'NCM'		);
			$item	['CFOP'			] = get_config_value('NFe Dyer', 'CFOP'		);
			$item	['uCom'			] = get_config_value('NFe Dyer', 'uCom'		);
			$item	['qCom'			] = $total_weight;
			$item	['vUnCom'		] = get_config_value('NFe Dyer', 'vUnCom'	);
			$item	['orig'			] = get_config_value('NFe Dyer', 'orig'		);
			$item	['CST_ICMS'		] = get_config_value('NFe Dyer', 'CST_ICMS'	);
			$item	['CST_IPI'		] = get_config_value('NFe Dyer', 'CST_IPI'	);
			$item	['pIPI'			] = get_config_value('NFe Dyer', 'pIPI'		);
			$item	['pPIS'			] = get_config_value('NFe Dyer', 'pPIS'		);
			$item	['pCOFINS'		] = get_config_value('NFe Dyer', 'pCOFINS'	);
			$items[$i] = $item;
			$i ++;

			$product['product_code'	] = get_config_value('NFe Dyer', 'cProd'	);
			$product['product_cf'	] = get_config_value('NFe Dyer', 'NCM'		);
			$product['IPI_code'		] = get_config_value('NFe Dyer', 'CST_IPI'	);
			$product['IPI_aliquota'	] = get_config_value('NFe Dyer', 'pIPI'		);
			$product['product_title'] = $prod['product_name'];
			$product['composicao'	] = $ftp ['composition'	];
			$my_return = json_decode(proxy($domain, 'data={"method":"add_product", "row":' . json_encode($product) . '}'), true);
			$my_id = $my_return['id'];
log_sql('Product', 'INSERT', $my_id);
		}
	}

	$my_count = 0;
	$nfe	['dEmi'			] = get_date();
	$nfe	['dSaiEnt'		] = get_date();
	$nfe	['tpNF'			] = get_config_value('NFe Dyer', 'tpNF'		);
	$nfe	['natOp'		] = get_config_value('NFe Dyer', 'natOp'	);
	$nfe	['dxNome'		] = $dyer		['full_name'	];
	$nfe	['txNome'		] = $transport	['full_name'	];
	$nfe	['modFrete'		] = get_config_value('NFe Dyer', 'modFrete'	);
	$nfe	['qVol'			] = $total_volume;
	$nfe	['esp'			] = get_config_value('NFe Dyer', 'esp'		);
	$nfe	['marca'		] = get_config_value('NFe Dyer', 'marca'	);
	$nfe	['pesoL'		] = '0';
	$nfe	['infCpl'		] = get_config_value('NFe Dyer', 'infCpl'	);
log_sql('NFe', 'BEFORE INSERT', json_encode($nfe));
	$my_return = json_decode(proxy($domain, 'data={"method":"add_nfe", "row":' . json_encode($nfe) . '}'), true);
	$my_nfe_id = $my_return['id'];
	if ($my_nfe_id)		$my_count++;
log_sql('NFe', 'INSERT', $my_nfe_id);

	foreach($items as $item) {
		$item['NFe_id'] = $my_nfe_id;
		$my_return = json_decode(proxy($domain, 'data={"method":"add_nfeitem", "row":' . json_encode($item) . '}'), true);
		$my_id = $my_return['id'];
		if ($my_id)		$my_count++;
log_sql('NFeItem', 'INSERT', $my_id);
	}

	$sql= 'UPDATE ShipDyers'
		. '   SET status = "Active"'
		. ' WHERE id = ' . $the_id
		;
log_sql('ShipDyers', 'UPDATE', $sql);
	$db->query($sql);
	insert_changes($db, 'ShipDyers', $the_id);

	JKY_echo_count($my_count);
}

/**
 *	invoice to Partner from ShipDyer
 *
 * @param	int		shipdyer_id
 * @return	int		count of Records generated
 */
function JKY_invoice_partner($the_id) {
	$db		= Zend_Registry::get('db');
	$domain	= get_config_value('NFe Dyer', 'domain');

	$my_count = 0;
	JKY_echo_count($my_count);
}
