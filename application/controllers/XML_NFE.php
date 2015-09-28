<?

/**
 *	$.ajax({ method: buscar_cep, cep: 12345-678, state: '', city: '', street2: '', street1: '', district: '', country: '' })
 *
 *	return: [ x...x, ..., x...x ]
 *
 * 	http://erp/index.php/ajax?data={"method":"get_xml","file_name":"C:/htdocs/erp/nfe/received/42140176840701000109550010000512901007934549-nfe.xml"}
 *
 */

function JKY_get_xml($data) {
	$my_xml_data = file_get_contents($data['file_name']);
	$my_xml_nfe  = new SimpleXMLElement($my_xml_data);

	$data = array();
	$data['status'	] = 'ok';
	$data['xml_nfe'	] = $my_xml_nfe;
	return $data;
}

/*
{"status":"ok"
,"xml_nfe":{"@attributes":{"versao":"2.00"}
	,"NFe":
		{"infNFe":{"@attributes":{"versao":"2.00","Id":"NFe42140882983404000107550020000795061000605127"}
			,"ide":{"cUF":"42","cNF":"00060512","natOp":"Industrializacao","indPag":"1","mod":"55","serie":"2","nNF":"79506","dEmi":"2014-08-19","dSaiEnt":"2014-08-19","tpNF":"1","cMunFG":"4202909","tpImp":"1","tpEmis":"1","cDV":"7","tpAmb":"1","finNFe":"1","procEmi":"0","verProc":"2.00"}
			,"emit":{"CNPJ":"82983404000107","xNome":"Favo Malhas Ltda","xFant":"Favo"
				,"enderEmit":{"xLgr":"Rua Joaquim Zucco","nro":"1800","xCpl":"CXP 1536","xBairro":"Nova Brasilia","cMun":"4202909","xMun":"Brusque","UF":"SC","CEP":"88352195","cPais":"1058","xPais":"Brasil","fone":"4733501622"}
				,"IE":"250208156","CRT":"3"}
			,"dest":{"CNPJ":"04744013000126","xNome":"Tecno Malhas Ltda."
				,"enderDest":{"xLgr":"Rua Baceunas","nro":"51","xBairro":"Vila Prudente","cMun":"3550308","xMun":"Sao Paulo","UF":"SP","CEP":"03127060","cPais":"1058","xPais":"Brasil","fone":"1122743833"}
				,"IE":"116257257110"}
			,"det":
				[{"@attributes":{"nItem":"1"}
					,"prod":{"cProd":"4332-8703","cEAN":{},"xProd":"PIQUET 1,2 PAC CONV TDE COC Branco Ft Tecno Pa 5 Cor: 001FT","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"123.7400","vUnCom":"1.2000","vProd":"148.49","cEANTrib":{},"uTrib":"KG","qTrib":"123.7400","vUnTrib":"1.2000","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"148.49","pICMS":"12.00","vICMS":"17.82"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"148.49","pPIS":"1.65","vPIS":"2.45"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"148.49","pCOFINS":"7.60","vCOFINS":"11.29"}}}}
				,{"@attributes":{"nItem":"2"}
					,"prod":{"cProd":"4332-70","cEAN":{},"xProd":"GOLAS Marinho Escuro Cor: 51709","NCM":"55151900","CFOP":"6124","uCom":"KG","qCom":"26.8000","vUnCom":"2.6552","vProd":"71.16","cEANTrib":{},"uTrib":"KG","qTrib":"26.8000","vUnTrib":"2.6552","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"71.16","pICMS":"12.00","vICMS":"8.54"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"71.16","pPIS":"1.65","vPIS":"1.17"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"71.16","pCOFINS":"7.60","vCOFINS":"5.41"}}}}
				,{"@attributes":{"nItem":"3"}
					,"prod":{"cProd":"4332-8717","cEAN":{},"xProd":"PIQUET 0,90 PAC CONV TDE\/AP Marinho Escuro Cor: 51709","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"246.4600","vUnCom":"2.6550","vProd":"654.35","cEANTrib":{},"uTrib":"KG","qTrib":"246.4600","vUnTrib":"2.6550","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"654.35","pICMS":"12.00","vICMS":"78.52"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"654.35","pPIS":"1.65","vPIS":"10.80"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"654.35","pCOFINS":"7.60","vCOFINS":"49.73"}}}}
				,{"@attributes":{"nItem":"4"}
					,"prod":{"cProd":"4332-8712","cEAN":{},"xProd":"PUNHOS POLO DRY S Tulipa (Inverno Cor: 51708","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"22.7000","vUnCom":"2.5251","vProd":"57.32","cEANTrib":{},"uTrib":"KG","qTrib":"22.7000","vUnTrib":"2.5251","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"57.32","pICMS":"12.00","vICMS":"6.88"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"57.32","pPIS":"1.65","vPIS":"0.95"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"57.32","pCOFINS":"7.60","vCOFINS":"4.36"}}}}
				,{"@attributes":{"nItem":"5"}
					,"prod":{"cProd":"4332-8711","cEAN":{},"xProd":"GOLAS DRY S Tulipa (Inverno Cor: 51708","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"33.8200","vUnCom":"2.5251","vProd":"85.40","cEANTrib":{},"uTrib":"KG","qTrib":"33.8200","vUnTrib":"2.5251","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"85.40","pICMS":"12.00","vICMS":"10.25"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"85.40","pPIS":"1.65","vPIS":"1.41"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"85.40","pCOFINS":"7.60","vCOFINS":"6.49"}}}}
				,{"@attributes":{"nItem":"6"}
					,"prod":{"cProd":"4332-8628","cEAN":{},"xProd":"PIQUET 1,2 PAC DRY BM Tulipa (Inverno Cor: 51708","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"308.7600","vUnCom":"2.5250","vProd":"779.62","cEANTrib":{},"uTrib":"KG","qTrib":"308.7600","vUnTrib":"2.5250","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"779.62","pICMS":"12.00","vICMS":"93.55"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"779.62","pPIS":"1.65","vPIS":"12.86"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"779.62","pCOFINS":"7.60","vCOFINS":"59.25"}}}}
				,{"@attributes":{"nItem":"7"}
					,"prod":{"cProd":"4332-2090","cEAN":{},"xProd":"PUNHOS POLO Tulipa (Inverno Cor: 51708","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"13.5400","vUnCom":"2.5251","vProd":"34.19","cEANTrib":{},"uTrib":"KG","qTrib":"13.5400","vUnTrib":"2.5251","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"34.19","pICMS":"12.00","vICMS":"4.10"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"34.19","pPIS":"1.65","vPIS":"0.56"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"34.19","pCOFINS":"7.60","vCOFINS":"2.60"}}}}
				,{"@attributes":{"nItem":"8"}
					,"prod":{"cProd":"4332-70","cEAN":{},"xProd":"GOLAS Tulipa (Inverno Cor: 51708","NCM":"55151900","CFOP":"6124","uCom":"KG","qCom":"23.4800","vUnCom":"2.5247","vProd":"59.28","cEANTrib":{},"uTrib":"KG","qTrib":"23.4800","vUnTrib":"2.5247","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"59.28","pICMS":"12.00","vICMS":"7.11"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"59.28","pPIS":"1.65","vPIS":"0.98"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"59.28","pCOFINS":"7.60","vCOFINS":"4.51"}}}}
				,{"@attributes":{"nItem":"9"}
					,"prod":{"cProd":"4332-8609","cEAN":{},"xProd":"PIQUET 1,2 PAC CONV TDE\/AP Tulipa (Inverno Cor: 51708","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"190.9200","vUnCom":"2.5250","vProd":"482.07","cEANTrib":{},"uTrib":"KG","qTrib":"190.9200","vUnTrib":"2.5250","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"482.07","pICMS":"12.00","vICMS":"57.85"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"482.07","pPIS":"1.65","vPIS":"7.95"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"482.07","pCOFINS":"7.60","vCOFINS":"36.64"}}}}
				,{"@attributes":{"nItem":"10"}
					,"prod":{"cProd":"4332-3660","cEAN":{},"xProd":"PUNHO C\/ LISTRA LAVACAO Cor: 062","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"8.1900","vUnCom":"0.9499","vProd":"7.78","cEANTrib":{},"uTrib":"KG","qTrib":"8.1900","vUnTrib":"0.9499","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"7.78","pICMS":"12.00","vICMS":"0.93"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"7.78","pPIS":"1.65","vPIS":"0.13"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"7.78","pCOFINS":"7.60","vCOFINS":"0.59"}}}}
				,{"@attributes":{"nItem":"11"}
					,"prod":{"cProd":"4332-3844","cEAN":{},"xProd":"GOLA C\/ LISTRA LAVACAO Cor: 062","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"16.6900","vUnCom":"0.9503","vProd":"15.86","cEANTrib":{},"uTrib":"KG","qTrib":"16.6900","vUnTrib":"0.9503","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"15.86","pICMS":"12.00","vICMS":"1.90"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"15.86","pPIS":"1.65","vPIS":"0.26"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"15.86","pCOFINS":"7.60","vCOFINS":"1.21"}}}}
				,{"@attributes":{"nItem":"12"}
					,"prod":{"cProd":"4332-8697","cEAN":{},"xProd":"PUNHOS POLO DRY H Preto Negao Pa5 Cor: 8570TE","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"35.9000","vUnCom":"2.2150","vProd":"79.52","cEANTrib":{},"uTrib":"KG","qTrib":"35.9000","vUnTrib":"2.2150","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"79.52","pICMS":"12.00","vICMS":"9.54"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"79.52","pPIS":"1.65","vPIS":"1.31"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"79.52","pCOFINS":"7.60","vCOFINS":"6.04"}}}}
				,{"@attributes":{"nItem":"13"}
					,"prod":{"cProd":"4332-8696","cEAN":{},"xProd":"GOLAS DRY H Preto Negao Pa5 Cor: 8570TE","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"48.8000","vUnCom":"2.2150","vProd":"108.09","cEANTrib":{},"uTrib":"KG","qTrib":"48.8000","vUnTrib":"2.2150","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"108.09","pICMS":"12.00","vICMS":"12.97"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"108.09","pPIS":"1.65","vPIS":"1.78"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"108.09","pCOFINS":"7.60","vCOFINS":"8.21"}}}}
				,{"@attributes":{"nItem":"14"}
					,"prod":{"cProd":"4332-8642","cEAN":{},"xProd":"PIQUET 1,2 PAC DRY BM F Preto Negao Pa5 Cor: 8570TE","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"297.9200","vUnCom":"2.2150","vProd":"659.89","cEANTrib":{},"uTrib":"KG","qTrib":"297.9200","vUnTrib":"2.2150","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"659.89","pICMS":"12.00","vICMS":"79.19"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"659.89","pPIS":"1.65","vPIS":"10.89"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"659.89","pCOFINS":"7.60","vCOFINS":"50.15"}}}}
				,{"@attributes":{"nItem":"15"}
					,"prod":{"cProd":"4332-4000","cEAN":{},"xProd":"NEW PLM ERIKA T Metal Blue 100% Co Cor: 22243","NCM":"55151100","CFOP":"6124","uCom":"KG","qCom":"6.5400","vUnCom":"1.2752","vProd":"8.34","cEANTrib":{},"uTrib":"KG","qTrib":"6.5400","vUnTrib":"1.2752","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"8.34","pICMS":"12.00","vICMS":"1.00"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"8.34","pPIS":"1.65","vPIS":"0.14"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"8.34","pCOFINS":"7.60","vCOFINS":"0.63"}}}}
				,{"@attributes":{"nItem":"16"}
					,"prod":{"cProd":"4332-8688","cEAN":{},"xProd":"MALHA MULT DEVORE (RAMADO) Metal Blue 100% Co Ramagem Prod.Ti Cor: 22243","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"123.5600","vUnCom":"1.7200","vProd":"212.52","cEANTrib":{},"uTrib":"KG","qTrib":"123.5600","vUnTrib":"1.7200","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"212.52","pICMS":"12.00","vICMS":"25.50"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"212.52","pPIS":"1.65","vPIS":"3.51"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"212.52","pCOFINS":"7.60","vCOFINS":"16.15"}}}}
				,{"@attributes":{"nItem":"17"}
					,"prod":{"cProd":"4332-4000","cEAN":{},"xProd":"NEW PLM ERIKA T Rosa B.B 100%Co Cor: 15692","NCM":"55151100","CFOP":"6124","uCom":"KG","qCom":"6.9000","vUnCom":"1.4551","vProd":"10.04","cEANTrib":{},"uTrib":"KG","qTrib":"6.9000","vUnTrib":"1.4551","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"10.04","pICMS":"12.00","vICMS":"1.20"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"10.04","pPIS":"1.65","vPIS":"0.17"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"10.04","pCOFINS":"7.60","vCOFINS":"0.76"}}}}
				,{"@attributes":{"nItem":"18"}
					,"prod":{"cProd":"4332-8688","cEAN":{},"xProd":"MALHA MULT DEVORE (RAMADO) Rosa B.B 100%Co Ramagem Prod.Ti Cor: 15692","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"123.4400","vUnCom":"1.9000","vProd":"234.54","cEANTrib":{},"uTrib":"KG","qTrib":"123.4400","vUnTrib":"1.9000","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"234.54","pICMS":"12.00","vICMS":"28.14"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"234.54","pPIS":"1.65","vPIS":"3.87"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"234.54","pCOFINS":"7.60","vCOFINS":"17.83"}}}}
				,{"@attributes":{"nItem":"19"}
					,"prod":{"cProd":"4332-8292","cEAN":{},"xProd":"PUNHO 2X1 CO STRETCH PREMIUM Azul Marinho No Cor: 15907","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"10.7600","vUnCom":"2.0651","vProd":"22.22","cEANTrib":{},"uTrib":"KG","qTrib":"10.7600","vUnTrib":"2.0651","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"22.22","pICMS":"12.00","vICMS":"2.67"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"22.22","pPIS":"1.65","vPIS":"0.37"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"22.22","pCOFINS":"7.60","vCOFINS":"1.69"}}}}
				,{"@attributes":{"nItem":"20"}
					,"prod":{"cProd":"4332-4166","cEAN":{},"xProd":"MEIA MALHA PENTEADA PREMIUM Azul Marinho No Cor: 15907","NCM":"52081900","CFOP":"6124","uCom":"KG","qCom":"150.5200","vUnCom":"2.0650","vProd":"310.82","cEANTrib":{},"uTrib":"KG","qTrib":"150.5200","vUnTrib":"2.0650","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"310.82","pICMS":"12.00","vICMS":"37.30"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"310.82","pPIS":"1.65","vPIS":"5.13"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"310.82","pCOFINS":"7.60","vCOFINS":"23.62"}}}}
				,{"@attributes":{"nItem":"21"}
					,"prod":{"cProd":"4332-70","cEAN":{},"xProd":"GOLAS Preto Negao Pa5 Cor: 8570TE","NCM":"55151900","CFOP":"6124","uCom":"KG","qCom":"15.9600","vUnCom":"2.2149","vProd":"35.35","cEANTrib":{},"uTrib":"KG","qTrib":"15.9600","vUnTrib":"2.2149","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"35.35","pICMS":"12.00","vICMS":"4.24"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"35.35","pPIS":"1.65","vPIS":"0.58"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"35.35","pCOFINS":"7.60","vCOFINS":"2.69"}}}}
				,{"@attributes":{"nItem":"22"}
					,"prod":{"cProd":"4332-8717","cEAN":{},"xProd":"PIQUET 0,90 PAC CONV TDE\/AP Preto Negao Pa5 Cor: 8570TE","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"99.9000","vUnCom":"2.2150","vProd":"221.28","cEANTrib":{},"uTrib":"KG","qTrib":"99.9000","vUnTrib":"2.2150","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"221.28","pICMS":"12.00","vICMS":"26.55"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"221.28","pPIS":"1.65","vPIS":"3.65"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"221.28","pCOFINS":"7.60","vCOFINS":"16.82"}}}}
				,{"@attributes":{"nItem":"23"}
					,"prod":{"cProd":"4332-8710","cEAN":{},"xProd":"PIQUET 1,2 FLAME MD Azul Genuino Tinto P Cor: 71163","NCM":"99999999","CFOP":"6124","uCom":"KG","qCom":"12.6000","vUnCom":"1.9302","vProd":"24.32","cEANTrib":{},"uTrib":"KG","qTrib":"12.6000","vUnTrib":"1.9302","indTot":"1"}
					,"imposto":{"ICMS":{"ICMS00":{"orig":"0","CST":"00","modBC":"3","vBC":"24.32","pICMS":"12.00","vICMS":"2.92"}},"IPI":{"cEnq":"999","IPINT":{"CST":"51"}},"PIS":{"PISAliq":{"CST":"01","vBC":"24.32","pPIS":"1.65","vPIS":"0.40"}},"COFINS":{"COFINSAliq":{"CST":"01","vBC":"24.32","pCOFINS":"7.60","vCOFINS":"1.85"}}}}
			]
			,"total":{"ICMSTot":{"vBC":"4322.45","vICMS":"518.67","vBCST":"0.00","vST":"0.00","vProd":"4322.45","vFrete":"0.00","vSeg":"0.00","vDesc":"0.00","vII":"0.00","vIPI":"0.00","vPIS":"71.32","vCOFINS":"328.52","vOutro":"0.00","vNF":"4322.45"}}
			,"transp":{"modFrete":"0","transporta":{"CNPJ":"07127650000141","xNome":"TRANSPORTADORA TRANSMELLO LTDA ME","IE":"254903142","xEnder":"RUA ARNOLDO RISTOW","xMun":"BRUQUE","UF":"SC"},"vol":{"qVol":"158","esp":"Rolos","pesoL":"1947.900","pesoB":"1947.900"}}
			,"cobr":{"fat":{"nFat":"79506","vOrig":"4322.45","vLiq":"4322.45"},"dup":{"nDup":"79506\/1","dVenc":"2014-10-03","vDup":"4322.45"}}
			,"infAdic":{"infAdFisco":"ICMS Diferido Cfe. Art. 8., Inciso X, Anexo 3 decreto 2870\/01 RICMS\/SC NAO ACEITAMOS DEVOLUCAO DE PECAS CORTADAS","infCpl":"Referencias(s): 2109143, 2109794, 2109864, 2109886, 2109887, 2109913, 2110015, 2110027, 2110028, 2110051, 2110061"}
		}
		,"Signature":{"SignedInfo":
			{"CanonicalizationMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/TR\/2001\/REC-xml-c14n-20010315"}},"SignatureMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2000\/09\/xmldsig#rsa-sha1"}}
				,"Reference":{"@attributes":{"URI":"#NFe42140882983404000107550020000795061000605127"}
					,"Transforms":{"Transform":[{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2000\/09\/xmldsig#enveloped-signature"}},{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/TR\/2001\/REC-xml-c14n-20010315"}}]}
					,"DigestMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2000\/09\/xmldsig#sha1"}}
					,"DigestValue":"rGoECdMQf19pUyF5Kk3kcqQj+8A="
				}
			}
		,"SignatureValue":"V6c\/MUePazDTRsc\/OSt29\/aBpYo0L\/c8rd1DmO7LsbrkEGwWBBg2xBJ\/pypYt+H8GDU0R0P6vgp2AK6swC0m6\/X0Wn8TiklWYdTPj7IJ0F0cbZAWuhXrNp0e+e3cayaEGj0wgBBv5MMB475EGH6q8YDZV9Ll+JbU7lyw\/zRPDqAtcwKyHdTmMABzlxQTeL76Jud2LPjRaadH2jNvHUIlR11SI7hA0z6Uvo+fKC4WhxE+5qxpozUUPqGU6vO6D9lxPDdF5QGiAUtSRSqYOkfvf4EF+iONTibKV0NX3upLq0iSx8Iy4worOfe9MHsdYJffpkTuwUOvY7Odf+udwBfumQ==","KeyInfo":{"X509Data":{"X509Certificate":"MIIIXDCCBkSgAwIBAgIQOwreDQVIQQsO75l2YAwJYDANBgkqhkiG9w0BAQsFADCBgDELMAkGA1UEBhMCQlIxEzARBgNVBAoTCklDUC1CcmFzaWwxNjA0BgNVBAsTLVNlY3JldGFyaWEgZGEgUmVjZWl0YSBGZWRlcmFsIGRvIEJyYXNpbCAtIFJGQjEkMCIGA1UEAxMbQUMgSW5zdGl0dXRvIEZlbmFjb24gUkZCIEcyMB4XDTE0MDgxMjAwMDAwMFoXDTE1MDgxMTIzNTk1OVowgeIxCzAJBgNVBAYTAkJSMRMwEQYDVQQKFApJQ1AtQnJhc2lsMQswCQYDVQQIEwJTQzEQMA4GA1UEBxQHYnJ1c3F1ZTE2MDQGA1UECxQtU2VjcmV0YXJpYSBkYSBSZWNlaXRhIEZlZGVyYWwgZG8gQnJhc2lsIC0gUkZCMRYwFAYDVQQLFA1SRkIgZS1DTlBKIEExMSUwIwYDVQQLFBxBdXRlbnRpY2FkbyBwb3IgQVIgU2VzY29uIFNDMSgwJgYDVQQDEx9GQVZPIE1BTEhBUyBMVERBOjgyOTgzNDA0MDAwMTA3MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnBL1WzkJrBnbpA382LhrsmKFFJue3SoXsdEWEKasGdPM6EsFeq8KXUAIhGS3vGL4BHA3uXAcvUpq4CdwbVJagqzTEuAscRqnreK08HX9Uv6K+026bE0RBm8dCSUwcYCwiBfGe9U2g\/wQpqL4nLFv8QP\/d1y9nIzO5lkrTWRdy0J6a3yE60EqWSrvxdateozBzcNDrShesxMMsfFp3TxmZ\/LrC0dcuJu+rzC1cE9QBz1wzQFCqEdkt9g4OF54nwGkKaWE3\/caJXlZNt+iEED6E04sc3Yh\/nMbTDcXJibQflBGFYBpMqybREo8TrbumX8iiUfibmonEulYg3wwigfyFwIDAQABo4IDbDCCA2gwga8GA1UdEQSBpzCBpKA4BgVgTAEDBKAvBC0wNjA5MTk1NzI5MDc0ODM1OTUzMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDCgHwYFYEwBAwKgFgQUSk9BTyBST0JFUlRPIEJFVVRJTkegGQYFYEwBAwOgEAQOODI5ODM0MDQwMDAxMDegFwYFYEwBAwegDgQMMDAwMDAwMDAwMDAwgRNuZmVmYXZvQGhvdG1haWwuY29tMAkGA1UdEwQCMAAwHwYDVR0jBBgwFoAU7Hpbz4ZIg7cDFbXJTUbW3Fp1Ft0wDgYDVR0PAQH\/BAQDAgXgMIIBKwYDVR0fBIIBIjCCAR4wXqBcoFqGWGh0dHA6Ly9pY3AtYnJhc2lsLmFjZmVuYWNvbi5jb20uYnIvcmVwb3NpdG9yaW8vbGNyL0FDSW5zdGl0dXRvRmVuYWNvblJGQkcyL0xhdGVzdENSTC5jcmwwXaBboFmGV2h0dHA6Ly9pY3AtYnJhc2lsLm91dHJhbGNyLmNvbS5ici9yZXBvc2l0b3Jpby9sY3IvQUNJbnN0aXR1dG9GZW5hY29uUkZCRzIvTGF0ZXN0Q1JMLmNybDBdoFugWYZXaHR0cDovL3JlcG9zaXRvcmlvLmljcGJyYXNpbC5nb3YuYnIvbGNyL0NlcnRpc2lnbi9BQ0luc3RpdHV0b0ZlbmFjb25SRkJHMi9MYXRlc3RDUkwuY3JsMIGGBgNVHSAEfzB9MHsGBmBMAQIBIjBxMG8GCCsGAQUFBwIBFmNodHRwOi8vaWNwLWJyYXNpbC5hY2ZlbmFjb24uY29tLmJyL3JlcG9zaXRvcmlvL2RwYy9BQy1JbnN0aXR1dG8tRmVuYWNvbi1SRkIvRFBDX0FDX0lGZW5hY29uX1JGQi5wZGYwHQYDVR0lBBYwFAYIKwYBBQUHAwIGCCsGAQUFBwMEMIGgBggrBgEFBQcBAQSBkzCBkDBkBggrBgEFBQcwAoZYaHR0cDovL2ljcC1icmFzaWwuYWNmZW5hY29uLmNvbS5ici9yZXBvc2l0b3Jpby9jZXJ0aWZpY2Fkb3MvQUNfSW5zdGl0dXRvX0ZlbmFjb25fUkZCLnA3YzAoBggrBgEFBQcwAYYcaHR0cDovL29jc3AuY2VydGlzaWduLmNvbS5icjANBgkqhkiG9w0BAQsFAAOCAgEAtfJN7+qXlDoK5TWsBUuFN6wTgWyxz0eO5iGFlMLUel0b4L4oXB3PpCj+kCwrHWPjLZaYQw4LU\/XJsy76jucU75YfY0pC0qutE3Gy4MH3T1xBDctvjVsV01KRWTBS7hjUn6ZAVUPqf1xLfNU6bdo\/Qf7LehN4N\/pKu3u2OXYybvKmWqhwJQkAcHA4a+\/2JxxwULiVRoDh1R0D18L9HkHFM2lNBNgOs\/axCQr+kICCuPl0U15WdUWd8TvCEV0ElTqBfZ0Tb6AAaiv\/p8+zgNkpQ2m1x3U2j2QqfgjdcbXDim33LN7koxyoeP7tqmOQayKuMCqE4sc8uFnLd2QUjUMmyMHk1FJFb7ywtDNeV1tdpyKZDmBdbTjhensVHeDB2FsJc1+GyCDhhd5lAJ0zVx7Xr5x3iWV\/jcSqjWJMXCC50tR7nuHg3BDyXiIZuZ5Dk6q+hnc7OJoWffHmPGRxbwZ1+emhNEAWe6YPkXejKGprkoUakmeuI1cmxXpjFNewOys9deP4ZRbFnN12U\/RQt7JF9z34HMBNCWx99ZjPkIpTnouHNj39gbPm17Y1dFWuqqgKPHQ+BSdUoSPT6lk2H2NPVD+xrBzDVtSQoQ+sEEE82t4YNHU38FfZ5e0RS6ijU\/FuEyJ3iJcNSenOU5cj\/vizD8lTGGW3LcQFn974s37nuRk="
				}
			}
		}
	}
	,"protNFe":{"@attributes":{"versao":"2.00"}
	,"infProt":{"@attributes":{"Id":"ID342140092976643"},"tpAmb":"1","verAplic":"SVRS20140804105558","chNFe":"42140882983404000107550020000795061000605127","dhRecbto":"2014-08-19T17:17:48","nProt":"342140092976643","digVal":"rGoECdMQf19pUyF5Kk3kcqQj+8A=","cStat":"100","xMotivo":"Autorizado o uso da NF-e"}}
	}
}
*/