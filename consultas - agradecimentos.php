<?php
 // name of your file 

include_once('request.php');
//date_default_timezone_set("GMT-3");
date_default_timezone_set("Etc/GMT+3");

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


$hoje = new DateTime();
$dataHoje = $hoje->format('Y-m-d');

print_r($dataHoje);

printf("\n");
$fullpath = $baseUrl . '/appoints/search?data_start=' . $dataHoje . '&data_end=' . $dataHoje . '';

$result = request($fullpath);
foreach ($result["content"] as $consultas) {
	$paciente = buscaPaciente($consultas["paciente_id"]);
	$nomePaciente = $paciente["content"]["nome"];
	$agendamento = $consultas["agendamento_id"];
	$data = $consultas["data"];
	$horario = $consultas["horario"];
	$status = $consultas["status_id"];
	
	if(isset($meds[$consultas["profissional_id"]])){
		$profissional = $meds[$consultas["profissional_id"]];
	} else {
		$profissional = "sem informação";
	}



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
		case 3:
			$horAge = new DateTime($horario);
			$agora = new DateTime();
			$tresHoras = new DateTime('now - 3 hour');
			$duasHoras = new DateTime('now - 2 hour');
			print_r($agora);
			print_r($tresHoras);
			print_r($duasHoras);
			if($tresHoras < $horAge && $horAge < $duasHoras){
				enviaMensagemAgradecimento($dados);
			}
			echo "$data - $agora - $horario - $celular - $nomePaciente - $profissional \n";
			break;
		
			



			//pegar tudo: agora - 180 minutos <  horário agendamente and horario agendamento < agora - 120 minutos 
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

function enviaMensagemAgradecimento($dados){
		
	$url = ''; //url da webhook
	print_r("agradecimentos");
	//request($url, $dados, $_POST);

		

}



	//print_r($result);
exit();

$client = new Request();
$client->setBaseUrl(''); // url de base para as requisições
$client->setHeaders($headers); // passar por parametro um array com os headers da requisição. DEVE SER UM ARRAY

$endpoint = "/appoints/status"; // exemplo de endpoint. DEVE SER UMA STRING

$client->request($endpoint);

	//print_r($client);
print_r("format");


?>