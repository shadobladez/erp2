<?
require_once     'Combine_BatchOuts.php';
/**
 *	$.ajax({ method: generate, table: x...x, id: x...x });
 *
 *	return: [ x...x, ..., x...x ]
 */
function JKY_combine($data) {
	$table	= get_data($data, 'table'	);
	$ids	= get_data($data, 'ids'		);

	$count = 0;
	switch($table) {
		case 'BatchOuts'	: $count = JKY_combine_batchouts($ids); break;
	}

	$return = array();
	if ($count > 0) {
		$return[ 'status' ] = 'ok';
		$return[ 'count'  ] = $count;
	}else{
		$return[ 'status' ] = 'error';
		$return[ 'message'] = 'no record combined';
	}
	return $return;
}
