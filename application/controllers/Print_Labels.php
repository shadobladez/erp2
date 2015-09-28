<?

/**
 *	$.ajax({ method: print_labels, table: x...x, key: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
function JKY_print_labels($data) {
	$table = get_data($data, 'table');

	$count = 0;
	switch($table) {
		case 'Boxes'	: $count = JKY_print_boxes ($data); break;
		case 'Pieces'	: $count = JKY_print_pieces($data); break;
	}

	$return = array();
	if ($count > 0) {
		$return[ 'status' ] = 'ok';
		$return[ 'message'] = 'Labels printed: ' . $count;
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = 'Labels printed: ' . $count;
	}
	return $return;
}

function JKY_print_boxes($data) {
	$sql= 'SELECT Boxes.*'
		. '     , Batches.batch AS batch_code'
		. '     , Threads.composition, Threads.name AS thread_name'
		. '     , Incomings.nfe_dl, Incomings.nfe_tm'
		. '     , Contacts.nick_name AS supplier_name'
		. '  FROM Boxes'
		. '  LEFT JOIN Batches		ON Batches.id   = Boxes.batch_id'
		. '  LEFT JOIN Threads		ON Threads.id   = Batches.thread_id'
		. '  LEFT JOIN Incomings	ON Incomings.id = Batches.incoming_id'
		. '  LEFT JOIN Contacts		ON Contacts.id  = Incomings.supplier_id'
		. ' WHERE Boxes.is_printed = "No"'
		. ' ORDER BY Boxes.barcode ASC'
		;
	$db   = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);

	$count		= 0;
	$folder		= 'boxes/';
	$ip_number	= get_config_value('System Controls', 'IP DL Printer Barcode Boxes');
	foreach($rows as $my_row) {
		$my_id				= $my_row['id'				];
		$my_average_weight	= $my_row['average_weight'	];
		$my_real_weight		= $my_row['real_weight'		];
		if ($my_real_weight == 0) {
			$my_real_weight = $my_average_weight;
		}

		$my_thread_name		= ucwords($my_row['thread_name']);
		$my_thread_name1	= ucwords($my_row['thread_name']);
		$my_thread_name2	= '';
		if (strlen($my_thread_name) > 28) {
			$i = 28;
			for(; $i>0; $i--) {
				if ($my_thread_name[$i] == ' ') {
					break;
				}
			}
			if ($i == 0) {
				$my_thread_name1 = substr($my_thread_name, 0, 28);
				$my_thread_name2 = substr($my_thread_name, 28);
			}else{
				$my_thread_name1 = substr($my_thread_name, 0, $i);
				$my_thread_name2 = substr($my_thread_name, $i+1);
			}
		}

		$labels  =		'~NORMAL';
		$labels .= NL . '~NORMAL';
		$labels .= NL . '~PIOFF';
		$labels .= NL . '~DELETE LOGO;*ALL';
		$labels .= NL . '~PAPER;INTENSITY 6;MEDIA 1;FEED SHIFT 0;CUT 0;PAUSE 0;TYPE 0;LABELS 2;SPEED IPS 6;SLEW IPS 4';
		$labels .= NL . '~CREATE;CXFIOS;226';
		$labels .= NL . 'SCALE;DOT;203;203';
		$labels .= NL . '/PARTE FIXA';
		$labels .= NL . 'ISET;0';
		$labels .= NL . 'FONT;FACE 92250';
		$labels .= NL . 'ALPHA';
		$labels .= NL . 'INV;POINT;422;788;12;12;*FORNEC:*';
		$labels .= NL . 'INV;POINT;373;788;12;12;*COMP:*';
		$labels .= NL . 'INV;POINT;327;788;12;12;*PESO:*';
		$labels .= NL . 'INV;POINT;279;788;12;12;*CONES:*';
		$labels .= NL . 'INV;POINT;231;788;12;12;*LOTE:*';
		$labels .= NL . 'STOP';
		$labels .= NL . '/PARTE VARIAVEL';
		$labels .= NL . 'ISET;0';
		$labels .= NL . 'FONT;FACE 92250';
		$labels .= NL . 'ALPHA';
		$labels .= NL . 'INV;POINT;527;788;16;16;*' . $my_thread_name1			  . '*';
		$labels .= NL . 'INV;POINT;482;788;16;16;*' . $my_thread_name2			  . '*';
		$labels .= NL . 'INV;POINT;422;600;22;22;*' . $my_row['supplier_name'	] . '*';
		$labels .= NL . 'INV;POINT;373;667;16;16;*' . $my_row['composition'		] . '*';
		$labels .= NL . 'INV;POINT;327;671;16;16;*' . $my_real_weight			  . ' KG*';
		$labels .= NL . 'INV;POINT;279;647;16;16;*' . $my_row['number_of_cones'	] . '*';
		$labels .= NL . 'INV;POINT;231;678;16;16;*' . $my_row['batch_code'		] . '*';
		$labels .= NL . 'INV;POINT;231;296;32;33;*' . $my_row['checkin_location'] . '*';
		$labels .= NL . 'STOP';
		$labels .= NL . '/CODIGO DE BARRAS';
		$labels .= NL . 'BARCODE';
		$labels .= NL . 'C128C;INV;XRD7:7:14:14:21:21:28:28;H8;36;122';
		$labels .= NL . '*' . $my_row['barcode'] . '*';
		$labels .= NL . 'PDF;B';
		$labels .= NL . 'STOP';
		$labels .= NL . '/FIM DO PROGRAMA';
		$labels .= NL . 'END';
		$labels .= NL . '~EXECUTE;CXFIOS;1';
		$labels .= NL . '~NORMAL';

		$out_name = $folder . $my_id . '.txt';
		$out_file = fopen( $out_name, 'w' ) or die( 'cannot open ' . $out_name );
		fwrite( $out_file, $labels );
		fwrite( $out_file, NL );
		fclose( $out_file );

		$command = 'tcp.exe ' . $ip_number . ' ' . $out_name;
if (ENVIRONMENT == 'production') {
//		system( '(' . $command . ' & ) > /dev/null');
//		system( '( php ' . APPLICATION . 'GenerateHtml.php & ) > /dev/null' );
		exec($command);
}
if (ENVIRONMENT == 'development') {
		log_sql('Print_Labels', 'Boxes', $command);
}
		$sql= 'UPDATE Boxes'
			. '   SET is_printed = "Yes"'
			. ' WHERE id = ' . $my_id
			;
		$db->query($sql);
		$count++;
	}
	return $count;
}

function JKY_print_pieces($data) {
	$sql= 'SELECT Pieces.*'
		. '  FROM Pieces'
		. ' WHERE Pieces.is_printed = "No"'
		. ' ORDER BY Pieces.barcode ASC'
		;
	$db   = Zend_Registry::get('db');
	$rows = $db->fetchAll($sql);

	$count		= 0;
	$folder		= 'pieces/';
	$ip_number	= get_config_value('System Controls', 'IP DL Printer Barcode Pieces');
	foreach($rows as $my_row) {
		$my_id				=		  $my_row['id'			];
		$my_produced_by		= ucwords($my_row['produced_by'	]);

		$my_product_name	= ucwords($my_row['product_name']);
		$my_product_name1	= ucwords($my_row['product_name']);
		$my_product_name2	= '';
		if (strlen($my_product_name) > 28) {
			$i = 28;
			for(; $i>0; $i--) {
				if ($my_product_name[$i] == ' ') {
					break;
				}
			}
			if ($i == 0) {
				$my_product_name1 = substr($my_product_name, 0, 28);
				$my_product_name2 = substr($my_product_name, 28);
			}else{
				$my_product_name1 = substr($my_product_name, 0, $i);
				$my_product_name2 = substr($my_product_name, $i+1);
			}
		}
		$my_date = $my_row['updated_at'];
		$my_updated = substr($my_date, 8, 2) . '/' . substr($my_date, 5, 2) . '/' . substr($my_date, 0, 4);

		$labels  =		'~NORMAL';
		$labels .= NL . '~NORMAL';
		$labels .= NL . '~PIOFF';
		$labels .= NL . '~DELETE LOGO;*ALL';
		$labels .= NL . '~PAPER;INTENSITY 6;MEDIA 1;FEED SHIFT 0;CUT 0;PAUSE 0;TYPE 0;LABELS 2;SPEED IPS 6;SLEW IPS 4';
		$labels .= NL . '~CREATE;DL;141';
		$labels .= NL . 'SCALE;DOT;203;203';
		$labels .= NL . '/PARTE FIXA';
		$labels .= NL . 'ISET;0';
		$labels .= NL . 'FONT;FACE 92250';
		$labels .= NL . 'ALPHA';
		$labels .= NL . 'INV;POINT;268;741;12;12;*CM*';
		$labels .= NL . 'INV;POINT;268;230;12;12;*Data:*';
//		$labels .= NL . 'INV;POINT;226;741;12;12;*Estocagem:*';
		$labels .= NL . 'STOP';
		$labels .= NL . '/PARTE VARIAVEL';
		$labels .= NL . 'ISET;0';
		$labels .= NL . 'FONT;FACE 92250';
		$labels .= NL . 'ALPHA';
		$labels .= NL . 'INV;POINT;354;741;12;12;*' . $my_product_name1			  . '*';
		$labels .= NL . 'INV;POINT;311;741;12;12;*' . $my_product_name2			  . '*';
		$labels .= NL . 'INV;POINT;268;678;12;12;*' . $my_produced_by			  . '*';
		$labels .= NL . 'INV;POINT;268;150;12;12;*' . $my_updated				  . '*';
//		$labels .= NL . 'INV;POINT;226;542;12;12;*' . $my_row['checkin_location'] . '*';
		$labels .= NL . 'STOP';
		$labels .= NL . '/CODIGO DE BARRAS';
		$labels .= NL . 'BARCODE';
		$labels .= NL . 'C128C;INV;XRD4:4:8:8:12:12:16:16;H7;43;267';
		$labels .= NL . '*' . $my_row['barcode'] . '*';
		$labels .= NL . 'PDF;B';
		$labels .= NL . 'STOP';
		$labels .= NL . '/FIM DO PROGRAMA';
		$labels .= NL . 'END';
		$labels .= NL . '~EXECUTE;DL;1';
		$labels .= NL . '~NORMAL';

		$out_name = $folder . $my_id . '.txt';
		$out_file = fopen( $out_name, 'w' ) or die( 'cannot open ' . $out_name );
		fwrite( $out_file, $labels );
		fwrite( $out_file, NL );
		fclose( $out_file );

		$command = 'tcp.exe ' . $ip_number . ' ' . $out_name;
if (ENVIRONMENT == 'production') {
//		system( '(' . $command . ' & ) > /dev/null');
//		system( '( php ' . APPLICATION . 'GenerateHtml.php & ) > /dev/null' );
		exec($command);
}
if (ENVIRONMENT == 'development') {
		log_sql('Print_Labels', 'Pieces', $command);
}
		$sql= 'UPDATE Pieces'
			. '   SET is_printed = "Yes"'
			. ' WHERE id = ' . $my_id
			;
		$db->query($sql);
		$count++;
	}
	return $count;
}
