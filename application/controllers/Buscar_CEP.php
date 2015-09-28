<?

/**
 *	$.ajax({ method: buscar_cep, cep: 12345-678, state: '', city: '', street2: '', street1: '', district: '', country: '' })
 *
 *	return: [ x...x, ..., x...x ]
 */
function JKY_buscar_cep($data) {
    $my_cep = fix_digits($data['zip']);

	if (strlen($my_cep) == 8) {
		if ($data['state'	] == ''
		or  $data['city'	] == ''
		or  $data['street2'	] == ''
		or  $data['street1'	] == ''
		or  $data['district'] == '') {
			$my_curl = curl_init();
			curl_setopt($my_curl, CURLOPT_URL, 'http://www.buscarcep.com.br/?&chave=146RwGh.Q3UM1x2871JTmxKemqLfYX/&formato=xml&cep=' . $my_cep );
			curl_setopt($my_curl, CURLOPT_RETURNTRANSFER, 1);
			$my_return = curl_exec($my_curl);
			curl_close($my_curl);

			if	(!is_empty($my_return)) {
				$my_cep = new SimpleXMLElement($my_return);
				if	($my_cep->retorno->resultado == '1') {
					$data['state'		] = remover_acentos($my_cep->retorno->uf);
					$data['city'		] = remover_acentos($my_cep->retorno->cidade);
					$data['street2'		] = remover_acentos($my_cep->retorno->bairro);
					$data['street1'		] = remover_acentos($my_cep->retorno->tipo_logradouro . ' ' . $my_cep->retorno->logradouro);
					$data['district'	] =	remover_acentos($my_cep->retorno->ibge_municipio_verificador);
				}
			}
		}
		$data['country'] = get_control_value('System Defaults', 'Company Country');
	}

	$data['status'] = 'ok';
	return $data;
}
