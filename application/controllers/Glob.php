<?

/**
 *	$.ajax({ method: glob, dir: 'C:\htdocs\erp\nfe\received\', filter: '*.xml' })
 *
 *	return: files
				[ name: x...x
				, size: x...x
				,  ext: xxx
				, time: yyyy-mm-dd hh:mm:ss
				]

	http://erp/index.php/ajax?data={"method":"glob","select":"C:/htdocs/erp/nfe/received/","filter":"*.xml"}

 	{"status":"ok"
	,"files":
		[{"name":"42140176840701000109550010000512901007934549-nfe.xml"		,"ext":"xml","size": "7 KB","time":"2014-02-05 02:31:00"}
		,{"name":"42140176840701000109550010000512911007906488-nfe.xml"		,"ext":"xml","size": "9 KB","time":"2014-02-05 02:31:00"}
		,{"name":"42140182983404000107550020000733191000543240-procNFe.xml"	,"ext":"xml","size":"28 KB","time":"2014-02-05 02:29:00"}
		,{"name":"42140182983404000107550020000733201000543259-procNFe.xml"	,"ext":"xml","size": "8 KB","time":"2014-02-05 02:30:00"}
		,{"name":"42140182983404000107550020000733211000543264-procNFe.xml"	,"ext":"xml","size": "7 KB","time":"2014-02-05 02:30:00"}
		,{"name":"42140182983404000107550020000733221000543270-procNFe.xml"	,"ext":"xml","size": "9 KB","time":"2014-02-05 02:31:00"}
		,{"name":"42140876840701000109550010000582511008109044-nfe.xml"		,"ext":"xml","size": "8 KB","time":"2014-09-13 11:46:50"}
		,{"name":"42140876840701000109550010000582521007990763-nfe.xml"		,"ext":"xml","size":"12 KB","time":"2014-09-13 11:45:23"}
		]}
 */
function JKY_glob($data) {
	$my_files = array();
	foreach(glob($data['select'] . $data['filter']) as $file_name) {
		$my_file = array();
		$my_file['id'	] = $file_name;

		$my_names = explode('/', $file_name);
		$my_file['name'	] = $my_names[count($my_names)-1];

		$my_file['ext'	] = get_file_ext($file_name);

		$my_file['size'	] = put_size(filesize($file_name));
		$my_file['time'	] = date('Y-m-d h:i:s', filemtime($file_name));
		array_push($my_files, $my_file);
	}

	$data = array();
	$data['status'] = 'ok';
	$data['rows'  ] = $my_files;
	return $data;
}
?>
