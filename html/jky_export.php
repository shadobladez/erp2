<?
/**
 *   JKY Export
 *   This controller will be used to interface client to mysql using Ajax
 *   @author: Pat Jan

 *   http://jky/jky_export.php?table=X...x

 *   status = 'ok'
 *   status = 'error'

 *   message = 'table name [X...x] is undefined'
 *   message = 'method name [ X...x ] is undefined'
 *   message = 'error on server'             (only for no support)
 *   message = 'error on mysql: x...x'       (only for support)
 *   message = 'duplicate id'

 */
require_once 'jky_constant.php';
define('NL', "\r\n");

class jky_class {
     var  $table    ;
     var  $order_by ;
     var  $filter   ;
     var  $cols     ;
     var  $rows     ;

     var  $response ;
     var  $content  ;

     public function __construct() {
          define( 'HOST', $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'PHP_SELF' ]);
     }

     public function close() {
          unlink( session_id() );
          session_destroy();
          header( 'Location: /' );
     }

     public function log_proxy( $message ) {
          $date = date( 'Y-m-d' );
          $time = date( 'H:i:s' );
          $logFile = fopen( '../proxy//' . $date . '.txt', 'a' ) or die( 'cannot open proxy file' );
          fwrite( $logFile, $time . ' ' . $message . "\n" );
          fclose( $logFile );
     }
/*
 *   query - proxy using curl to run AjaxController.php
 */
public function query( $domain, $postvars ) {
$this->log_proxy( '  domain: ' . $domain   );
$this->log_proxy( 'POSTVARS: ' . $postvars );

     $ch  = curl_init( $domain );

     curl_setopt( $ch, CURLOPT_POST          , 0 );
     curl_setopt( $ch, CURLOPT_VERBOSE       , 0 );
//   curl_setopt( $ch, CURLOPT_USERAGENT     , isset( $_SERVER[ 'User-Agent' ]) ? $_SERVER[ 'User-Agent' ] : '' );
     curl_setopt( $ch, CURLOPT_POSTFIELDS    , $postvars );
//   curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
     curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1 );
     curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
     curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
     curl_setopt( $ch, CURLOPT_REFERER       , $domain );
     curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 0 );
     curl_setopt( $ch, CURLOPT_AUTOREFERER   , 0 );
     curl_setopt( $ch, CURLOPT_COOKIEJAR     , 'ses_' . session_id() );
     curl_setopt( $ch, CURLOPT_COOKIEFILE    , 'ses_' . session_id() );
//   curl_setopt( $ch, CURLOPT_COOKIE        , $COOKIE );
     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
     curl_setopt( $ch, CURLOPT_FAILONERROR   , 1 );

     $content  = curl_exec   ( $ch );
     $response = curl_getinfo( $ch );

     curl_close( $ch );
	unlink('ses_' . session_id());
     return $content;
}

/*
 *   run - generate Excel file
 */
public function run( $table, $cols, $rows ) {
     function is_internal( $field ) {
          if(  $field == 'id'
          or   $field == 'updated_by'
          or   $field == 'updated_at'
          or   $field == 'counter'
          or   $field == 'parent_id'
          or   $field == 'company_id'
          or   $field == 'member_id'
          or   $field == 'owner_id'
          or   $field == 'contact_id'
          or   $field == 'support_id'
          or   $field == 'opened_by'
          or   $field == 'closed_by'
          or   $field == 'assigned_to' )
               return true ;
          else return false;
     }

     $count_cols = count( $cols );
     $dropped    = 0;
     $count_rows = count( $rows );

$this->log_proxy('count_cols: ' . $count_cols);
$this->log_proxy('count_rows: ' . $count_rows);

     $widths = '';
     foreach( $cols as $col ) {
          $field = $col[ 'Field' ];
          if(  is_internal( $field )) {
               $dropped++;
               continue;
          }

          switch( $field ) {
               case 'country'      : $width =  40; break;
               case 'description'  : $width = 200; break;
               case 'resolution'   : $width = 200; break;
               case 'sequence'     : $width =  60; break;
               case 'state'        : $width =  40; break;
               case 'status'       : $width =  40; break;
               case 'value'        : $width = 200; break;
               case 'zip'          : $width =  40; break;
               default : {
                    $types = explode( '(', $col[ 'Type' ]);
                    $type  = $types[ 0 ];
                    switch( $type ) {
                         case 'bigint'  : $width =  60; break;
                         case 'char'    : $width =  60; break;
                         case 'date'    : $width =  60; break;
                         case 'datetime': $width = 100; break;
                         case 'decimal' : $width =  60; break;
                         case 'int'     : $width =  40; break;
                         default        : $width = 100; break;
                    }
               }
          }
//          $width = $col['Width'];
          $widths .= "\n" . '  <Column ss:AutoFitWidth="0" ss:Width="' . $width . '"/>';
     }

     $tops = "\n" . '<Row ss:StyleID="s22">'
           . "\n" . '<Cell><Data ss:Type="String"> Table:  ' . $table . '</Data></Cell>'
           . "\n" . '</Row>'
           . "\n" . '<Row ss:StyleID="s22">'
           . "\n" . '<Cell><Data ss:Type="String">  Time:  ' . date( 'Y-m-d H:m:s' ) . '</Data></Cell>'
           . "\n" . '</Row>'
           . "\n" . '<Row>'
           . "\n" . '</Row>'
           ;
     $head1 = '';
     $head2 = '';
     foreach( $cols as $col ) {
          $field = $col[ 'Field' ];
          if(  is_internal( $field  )) {
               continue;
          } else {
               $names = explode( '_', $field );
               if(  count( $names ) == 1 ) {
                    $name1 = ' '        ; $name2 = $names[ 0 ];
               } else {
                    $name1 = $names[ 0 ]; $name2 = $names[ 1 ];
               }
               $head1 .= "\n" . '    <Cell ss:StyleID="s65"><Data ss:Type="String">' . $name1 . '</Data></Cell>';
               $head2 .= "\n" . '    <Cell ss:StyleID="s65"><Data ss:Type="String">' . $name2 . '</Data></Cell>';
          }
     }

     $body = '';
     foreach( $rows as $row ) {
          $body .= "\n" . '<Row>';
          for( $c=0; $c<$count_cols; $c++ ) {
               $col = $cols[ $c ];
               $field = $col[ 'Field' ];
               if(  is_internal( $field  )) {
                    continue;
               } else {
                    $types = explode( '(', $col[ 'Type' ]);
                    $type  = $types[ 0 ];
                    switch( $type ) {
                         case 'bigint'  : $ssStyle = ''          ; $ssType = 'Number'  ; break;
                         case 'date'    : $ssStyle = 'shortDate' ; $ssType = 'DateTime'; break;
                         case 'datetime': $ssStyle = 'dateTime'  ; $ssType = 'DateTime'; break;
                         case 'decimal' : $ssStyle = ''          ; $ssType = 'Number'  ; break;
                         case 'int'     : $ssStyle = ''          ; $ssType = 'Number'  ; break;
                         case 'char'    : $ssStyle = 'char'      ; $ssType = 'String'  ; break;
                         default        : $ssStyle = ''          ; $ssType = 'String'  ; break;
                    }
                    $value = $row[ $col[ 'Field' ]];
					if ($ssType == 'DateTime') {
						$value = str_replace( ' ', 'T', $value );
						if ($value == '0000-00-00T00:00:00') {
							$value = '';
						}
					}
					if ($value  == '')		$ssType = 'String';

                    if(  $field == 'country'
                    or   $field == 'fax'
                    or   $field == 'mobile'
                    or   $field == 'parent_name'
                    or   $field == 'phone'
                    or   $field == 'state'
                    or   $field == 'zip' )
                         $ssStyle  = 'center';

                    if(  $ssStyle != '' )              $ssStyle  = ' ss:StyleID="' . $ssStyle . '"';

                    $value = htmlspecialchars( $value );
                    $body .= "\n" . '<Cell' . $ssStyle . '><Data ss:Type="' . $ssType . '">' . $value . '</Data></Cell>';
               }
          }
          $body .= "\n" . '</Row>';
     }
     $body .= "\n" . ' </Table>';

     $names = ''
            . "\n" . ' <Worksheet ss:Name="Names">'
            . "\n" . '  <Table ss:ExpandedColumnCount="' . ( $count_cols-$dropped ) . '" ss:ExpandedRowCount="' . ( $count_rows+6 ) . '" x:FullColumns="1" x:FullRows="1">'
            . $widths
            . $tops
            . "\n" . '   <Row ss:StyleID="s22">' . $head1 . "\n" . '   </Row>'
            . "\n" . '   <Row ss:StyleID="s22">' . $head2 . "\n" . '   </Row>'
            . "\n" . '   <Row>'
            . "\n" . '   </Row>'
            ;

     $fileName = 'excel/Header.txt';
     $inpFile  = fopen( $fileName, 'r' );
     $header   = fread( $inpFile, filesize( $fileName ));
     fclose( $inpFile );

     $fileName = 'excel/Footer.txt';
     $inpFile  = fopen( $fileName, 'r' );
     $footer   = fread( $inpFile, filesize( $fileName ));
     fclose( $inpFile );

     header( 'Pragma: ' );
     header( 'Cache-Control: ' );
     header( 'Content-Type: application/x-msexcel' );
//     header( 'Content-Disposition: attachment; filename="' . $table . '.xlm"' );          //   generate *.xlm.xls
//     header( 'Content-Disposition: inline; filename="' . $table . '.xlm"' );              //   generate *.xls
     header( 'Content-Disposition: inline; filename="' . $table . '.xls"' );              //   generate *.xls
     echo( $header );
     echo( $names  );
     echo( $body   );
     echo( $footer );
}

/*
 *	run thread forecast - generate Excel file
 */
public function run_thread_forecast($table, $cols, $rows) {
	function GetMes($mes) {
		if ($mes > 12)		$mes = $mes - 12;
		$meses = array('Jan', 'Feb', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
		return $meses[$mes - 1];
	}

	function Set_Total( $Total_Peso, $Total_Compra_0, $Total_Compra_1, $Total_Compra_2, $Total_Compra_3 ) {
		$total = NL . '   <Row ss:AutoFitHeight="0" ss:Height="15" ss:StyleID="s29">'
			   . NL . '    <Cell ss:Index="2" ss:StyleID="s25"/>'
			   . NL . '    <Cell ss:StyleID="s25"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s47"><Data ss:Type="Number">' . $Total_Peso     . '</Data></Cell>'
			   . NL . '    <Cell ss:StyleID="s47"><Data ss:Type="Number">' . $Total_Compra_0 . '</Data></Cell>'
			   . NL . '    <Cell ss:StyleID="s47"><Data ss:Type="Number">' . $Total_Compra_1 . '</Data></Cell>'
			   . NL . '    <Cell ss:StyleID="s47"><Data ss:Type="Number">' . $Total_Compra_2 . '</Data></Cell>'
			   . NL . '    <Cell ss:StyleID="s47"><Data ss:Type="Number">' . $Total_Compra_3 . '</Data></Cell>'
			   . NL . '   </Row>'
			   . NL . '   <Row ss:AutoFitHeight="0" ss:Height="14.25" ss:StyleID="s29">'
			   . NL . '    <Cell ss:Index="2" ss:StyleID="s25"/>'
			   . NL . '    <Cell ss:StyleID="s25"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:Index="7" ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s42"/>'
			   . NL . '    <Cell ss:StyleID="s30"/>'
			   . NL . '   </Row>'
			   ;
		 return $total;
	}

	$new_rows = array();
	for ($i=0; $i<count($rows); $i++) {
		$row = $rows[$i];
		$Grupo		= $row['thread_group'		];
		$Fio		= $row['thread_name'		];
		$Composicao	= $row['composition'		];
		$Fornecedor	= $row['supplier_name'		];
		$Fatura		= $row['invoice_date'		];
		$Peso		= $row['current_balance'	];
		$Compra_0	= $row['forecast_month_0'	];
		$Compra_1	= $row['forecast_month_1'	];
		$Compra_2	= $row['forecast_month_2'	];
		$Compra_3	= $row['forecast_month_3'	];
		if ($Grupo == '')		continue;
		if ($Peso == 0 and $Compra_0 == 0 and $Compra_1 == 0 and $Compra_2 == 0 and $Compra_3 == 0)	continue;
		$new_rows[] = $row;
	}

	$Mes = date('n');
	$NumberRows = count($new_rows);
$this->log_proxy('NumberRows: ' . $NumberRows);

	$count			= 2;
	$body			= '';

	$next_grupo		= '';
	$next_fio		= '';
	$first_grupo	= true;
	$first_fio		= true;
	$last_grupo		= true;
	$last_fio		= true;

	$Soma_Peso		= 0;
	$Total_Peso		= 0;
	$Total_Compra_0	= 0;
	$Total_Compra_1	= 0;
	$Total_Compra_2	= 0;
	$Total_Compra_3	= 0;

	for ($i=0; $i<$NumberRows; $i++) {
		$row = $new_rows[$i];
		$Grupo		= $row['thread_group'		];
		$Fio		= $row['thread_name'		];
		$Composicao	= $row['composition'		];
		$Fornecedor	= $row['supplier_name'		];
		$Fatura		= $row['invoice_date'		];
		$Peso		= $row['current_balance'	];
		$Compra_0	= $row['forecast_month_0'	];
		$Compra_1	= $row['forecast_month_1'	];
		$Compra_2	= $row['forecast_month_2'	];
		$Compra_3	= $row['forecast_month_3'	];

		if ($Fatura == null) {
			$Fatura = 'N/A';
		}else{
			$dates = explode('-', $Fatura);
			$Fatura = $dates[2] . '-' . $dates[1] . '-' . $dates[0];
		}
//var_dump($Fatura);

		if ($i < $NumberRows) {
			$next_row = $new_rows[$i+1];
			$next_grupo = $next_row['thread_group'	];
			$next_fio	= $next_row['thread_name'	];
		}else{
			$next_grupo	= '';
			$next_fio	= '';
		}

		$first_grupo	= $last_grupo	;
		$first_fio		= $last_fio		;
		$last_grupo	= ($Grupo != $next_grupo) ? true : false;
		$last_fio	= ($Fio   != $next_fio	) ? true : false;

		if ($first_grupo) {
			$count += 3;
			$body  .= ''
				. NL . '   <Row ss:AutoFitHeight="0" ss:Height="14.25">'
				. NL . '    <Cell ss:Index="7" ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s25"/>'
				. NL . '   </Row>'
				. NL . '   <Row ss:AutoFitHeight="0" ss:Height="14.25">'
				. NL . '    <Cell ss:Index="7" ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s26"/>'
				. NL . '    <Cell ss:StyleID="s56"><Data ss:Type="String">Fios   a   chegar</Data></Cell>'
				. NL . '    <Cell ss:StyleID="s81"/>'
				. NL . '    <Cell ss:StyleID="s28"/>'
				. NL . '   </Row>'
				. NL . '   <Row ss:AutoFitHeight="0" ss:Height="14.25">'
				. NL . '    <Cell ss:StyleID="s31"><Data ss:Type="String">Grupo:</Data></Cell>'
				. NL . '    <Cell ss:StyleID="s22"><Data ss:Type="String">' . $Grupo . '</Data></Cell>'
				. NL . '    <Cell ss:Index="7" ss:StyleID="s25"/>'
				. NL . '    <Cell ss:StyleID="s44"><Data ss:Type="String">' . GetMes(0+$Mes) . '</Data></Cell>'
				. NL . '    <Cell ss:StyleID="s44"><Data ss:Type="String">' . GetMes(1+$Mes) . '</Data></Cell>'
				. NL . '    <Cell ss:StyleID="s44"><Data ss:Type="String">' . GetMes(2+$Mes) . '</Data></Cell>'
				. NL . '    <Cell ss:StyleID="s44"><Data ss:Type="String">' . GetMes(3+$Mes) . '</Data></Cell>'
				. NL . '   </Row>'
				;
			$Total_Peso		= 0;
			$Total_Compra_0	= 0;
			$Total_Compra_1	= 0;
			$Total_Compra_2	= 0;
			$Total_Compra_3	= 0;
		}

		if ($first_fio) {
			$Soma_Peso = 0;
		}

		$Soma_Peso += $Peso;
		if ($last_fio) {
			$Print_Peso = '<Data ss:Type="Number">' . $Soma_Peso . '</Data>';
		}else{
			$Print_Peso = '';
		}

		$count += 1;
		$body  .= ''
			. NL . '   <Row ss:AutoFitHeight="0" ss:Height="14.25" ss:StyleID="s29">'
			. NL . '    <Cell ss:Index="2" ss:StyleID="s35"><Data ss:Type="String">' . $Fio . '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s35"><Data ss:Type="String">' . $Composicao .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s35"><Data ss:Type="String">' . $Fatura	  .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s35"><Data ss:Type="String">' . $Fornecedor .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s46"><Data ss:Type="Number">' . $Peso       .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s46">'						. $Print_Peso .			'</Cell>'
			. NL . '    <Cell ss:StyleID="s46"><Data ss:Type="Number">' . $Compra_0   .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s46"><Data ss:Type="Number">' . $Compra_1   .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s46"><Data ss:Type="Number">' . $Compra_2   .  '</Data></Cell>'
			. NL . '    <Cell ss:StyleID="s46"><Data ss:Type="Number">' . $Compra_3   .  '</Data></Cell>'
			. NL . '   </Row>'
			;

		$Total_Peso     += $Peso;
		$Total_Compra_0 += $Compra_0;
		$Total_Compra_1 += $Compra_1;
		$Total_Compra_2 += $Compra_2;
		$Total_Compra_3 += $Compra_3;

		if ($last_grupo) {
			$count += 2;
			$body  .= Set_Total($Total_Peso, $Total_Compra_0, $Total_Compra_1, $Total_Compra_2, $Total_Compra_3);
		}
	}

$this->log_proxy('Body: ' . $body);

     $tableX = NL . ' <Worksheet ss:Name="Sheet1">'
            . NL . '  <Table ss:ExpandedColumnCount="12" ss:ExpandedRowCount="' . $count . '" x:FullColumns="1"'
            . NL . '   x:FullRows="1">'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="34.5"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="109.5"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="113.25"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="113.25"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="113.25"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="55.5"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="55.5"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="49.5"/>'
            . NL . '   <Column ss:Index="11" ss:StyleID="s21" ss:AutoFitWidth="0" ss:Width="49.5"/>'
            . NL . '   <Column ss:AutoFitWidth="0" ss:Width="49.5"/>'
            . NL . '   <Row>'
            . NL . '    <Cell ss:StyleID="s22"><Data ss:Type="String">Lista de Promocao (disponivel no mercado)</Data></Cell>'
            . NL . '    <Cell ss:Index="11" ss:StyleID="s23"><Data ss:Type="String">' . date('d-m-Y') . '</Data></Cell>'
            . NL . '   </Row>'
            . NL . '   <Row>'
            . NL . '    <Cell ss:StyleID="s22"><Data ss:Type="String">Previsao do mercado /ton</Data></Cell>'
            . NL . '    <Cell ss:Index="11" ss:StyleID="s23"/>'
            . NL . '    <Cell ss:StyleID="s24"/>'
            . NL . '   </Row>'
            ;

     $fileName = 'excel/ThreadForecastHeader.txt';
     $inpFile  = fopen( $fileName, 'r' );
     $header   = fread( $inpFile, filesize( $fileName ));
     fclose( $inpFile );

     $fileName = 'excel/ThreadForecastFooter.txt';
     $inpFile  = fopen( $fileName, 'r' );
     $footer   = fread( $inpFile, filesize( $fileName ));
     fclose( $inpFile );

     header( 'Pragma: ' );
     header( 'Cache-Control: ' );
     header( 'Content-Type: application/x-msexcel' );
     header( 'Content-Disposition: inline; filename="' . $table . '.xls"' );              //   generate *.xls
     echo( $header );
     echo( $tableX );
     echo( $body   );
     echo( $footer );
}
}

session_start();
$domain = SERVER_NAME . 'index.php/ajax?';
$table  = $_REQUEST[ 'table' ];
$program  = new jky_class();
/*
$args = '&table='        . $_REQUEST[ 'table'     ]
      . '&filter='       . $_REQUEST[ 'filter'    ]
      . '&select='       . $_REQUEST[ 'select'    ]
      . '&display='      . $_REQUEST[ 'display'   ]
      . '&order_by='     . $_REQUEST[ 'order_by'  ]
      . '&specific='     . $_REQUEST[ 'specific'  ]
      ;
 */
$args = ',"table":"'	. $_REQUEST[ 'table'     ] . '"'
      . ',"filter":"'	. $_REQUEST[ 'filter'    ] . '"'
//      . ',"select":"'	. $_REQUEST[ 'select'    ] . '"'
//      . ',"select":"'	. 'All' . '"'
//      . ',"display":"'	. $_REQUEST[ 'display'   ] . '"'
      . ',"display":"'	. '99999' . '"'
      . ',"order_by":"'	. $_REQUEST[ 'order_by'  ] . '"'
      . ',"specific":"'	. $_REQUEST[ 'specific'  ] . '"'
      ;

$select = $_REQUEST['select'];
$args .= ',"select":"' . ($select == '' ? 'All' : $select) . '"';


$program->log_proxy( 'args: ' . $args );

$arrays   = json_decode( $program->query( $domain, 'data={"method":"get_columns"' . $args . '}' ), true );
$cols     = $arrays[ 'columns' ];
//foreach( $cols as $col ) { echo '<br>'; var_dump( $col ); }

$arrays   = json_decode( $program->query( $domain, 'data={"method":"export"'      . $args . '}' ), true );
$rows     = $arrays[ 'rows' ];
//foreach( $rows as $row ) { echo '<br>'; var_dump( $row ); }

if ($table == 'ThreadForecast') {
	$program->run_thread_forecast($table, $cols, $rows);
}else{
	$program->run($table, $cols, $rows);
}

?>