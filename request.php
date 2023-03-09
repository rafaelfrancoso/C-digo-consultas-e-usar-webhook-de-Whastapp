<?php

class Request {

  private $token;
  private $baseUrl;
  private $headers = array();
  
  public function setBaseUrl(String $baseUrl){
    $this->baseUrl = $baseUrl;
  }
  
  public function setHeaders(Array $headers){
    $this->headers = $headers;
  }

  public function request(String $endpoint, $dados = null, $method = "GET", $filtros = '') {
    
    $fullpath = $this->baseUrl . $endpoint;

    $fullpath = $filtros ? $fullpath . '?' . $filtros : $fullpath;

    $ch = curl_init($fullpath);
    
    if($dados) {
      # Setup request to send json via POST.
      $payload = json_encode( $dados );
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    }
  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  
    if($method == "POST") {
      curl_setopt($ch, CURLOPT_POST, 1);
    } else if($method == "GET"){
      curl_setopt($ch, CURLOPT_POST, 0);
    } else if($method == "PUT") {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    }

    if(empty($this->headers)){
      $this->headers = array(
        'Content-Type:application/json'
      );
    }

    curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->headers);
  
    # Return response instead of printing.
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  
    # Send request.
    $result = curl_exec($ch);
    $error = curl_error($ch);

    if($error){
      return $error;
    }

    curl_close($ch);
    # Print response.
    // echo "<pre>$result</pre>";
  
    return json_decode($result); 
  }

}