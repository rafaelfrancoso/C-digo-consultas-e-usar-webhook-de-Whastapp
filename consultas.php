<?php
 // name of your file 

include_once('request.php');
//include_once('agradecimentos.php');

$baseUrl = '';

//$fullpath = $baseUrl . '/appoints/search?data_start=10-02-2022&data_end=10-02-2022';

$endpoint = '/professional/list';

$fullpath = $baseUrl . $endpoint;

$medicos = request($fullpath);

//print_r($medicos);

$meds = array();

foreach($medicos['content'] as $medico){
	$meds[$medico['profissional_id']] = $medico['nome'];
}

$endpoint = "/appoints/search"; // exemplo de endpoint. DEVE SER UMA STRING


//$amanha = new DateTime('tomorrow');
//$dataAmanha = $amanha->format('Y-m-d');
$depoisamanha = new DateTime('tomorrow + 1 day');
$dataDepoisAmanha = $depoisamanha->format('Y-m-d');
//print_r($dataAmanha);
print_r($dataDepoisAmanha);
printf('\n');
$fullpath = $baseUrl . '/appoints/search?data_start=' . $dataDepoisAmanha . '&data_end=' . $dataDepoisAmanha . '';

$result = request($fullpath);
foreach ($result["content"] as $consultas) {
	$paciente = buscaPaciente($consultas["paciente_id"]);
	$nomePaciente = $paciente["content"]["nome"];
	$agendamento = $consultas["agendamento_id"];
	$data = $consultas["data"];
	$horario = $consultas["horario"];
	$status = $consultas["status_id"];
	//print_r($status);
	$profissional = $meds[$consultas["profissional_id"]];
	foreach($paciente["content"]["celulares"] as $cel){
		if(strlen($cel) == 11) {
			$celular = $cel;
			break;
		}

	}
	
		
	$dados = array("destinatario" => $celular, "nomePaciente" => $nomePaciente, "nomeProfissional" => $profissional, "horarioConsulta" => $horario, "dataAgendamento" => $data);
	//json_encode($dados);
	//print_r($dados);
	switch ($status) {
		case 1:
			enviaMensagemConsulta($dados);
			echo "$data -$horario - $celular - $nomePaciente - $profissional \n";
			break;
	
		/*
		case 3:
			enviaMensagemAgradecimento($dados);
			break;
		*/
			
	}
}

function buscaPaciente($id){
	global $baseUrl;

	$fullpath = $baseUrl . "/patient/search?paciente_id=$id";

	return $paciente = request($fullpath);

}

function buscaStatus($id){
	global $baseUrl;

	$fullpath = $baseUrl . "/appoints/status";

	$status = request($fullpath);

	$stats = array();

	foreach($status['content'] as $con){
		$stats[$con['id']] = $con['status'];	
	}
		
	return $stats;

}

function request($endpoint){
	$headers = array(
		'Content-Type: application/json',
		'x-access-token: ', //token aqui
	);

	$ch = curl_init($endpoint);
	      
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	  
	curl_setopt($ch, CURLOPT_POST, 0);

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	  
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	  
	# Send request.
	$result = curl_exec($ch);
	$error = curl_error($ch);

		//print_r($result);
	return json_decode($result, true);

}

function enviaMensagemConsulta($dados){

	$url = ''; //url da webhook
	

	// timestamp Y-m-d H:i-s, data agendamento, hora agendamento, nome paciente, nome profissional \n 
	//request($url, $dados, $_POST);
}





	//print_r($result);
exit();

$client = new Request();
$client->setBaseUrl('https://api.feegow.com/v1/api'); // url de base para as requisições
$client->setHeaders($headers); // passar por parametro um array com os headers da requisição. DEVE SER UM ARRAY

$endpoint = "/appoints/status"; // exemplo de endpoint. DEVE SER UMA STRING

$client->request($endpoint);

	//print_r($client);
print_r("format");

?>