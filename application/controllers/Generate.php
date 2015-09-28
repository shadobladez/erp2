<?
require_once     'Generate_CheckOut.php';
require_once     'Generate_Order.php';
require_once     'Generate_OSA.php';
require_once     'Generate_Purchase.php';
require_once     'Generate_Sale.php';
require_once     'Generate_TDyer.php';
/**
 *	$.ajax({ method: generate, table: x...x, id: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
function JKY_generate($data) {
	$table	= get_data($data, 'table'	);
	$id		= get_data($data, 'id'		);

	$count = 0;
	switch($table) {
		case 'CheckOut'	: $count = JKY_generate_checkout($id); break;
		case 'Order'	: $count = JKY_generate_order	($id); break;
		case 'OSA'		: $count = JKY_generate_osa		($id); break;
		case 'Purchase'	: $count = JKY_generate_purchase($id); break;
		case 'Sale'		: $count = JKY_generate_sale	($id); break;
		case 'TDyer'	: $count = JKY_generate_tdyer	($id); break;
	}

	$return = array();
	if ($count > 0) {
		$return[ 'status' ] = 'ok';
		$return[ 'count'  ] = $count;
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = 'no record generated';
	}
	return $return;
}
