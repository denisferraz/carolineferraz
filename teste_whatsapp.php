<?php

require('conexao.php');

 //Incio Envio Whatsapp

    
    $doc_telefonewhats = "5571997417190";
    $msg_wahstapp = "Testando Sistema de Whatsapp!";
    
    $curl = curl_init();
    
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cluster.apigratis.com/api/v1/whatsapp/sendText',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => "{
        \"number\": \"$doc_telefonewhats\",
        \"text\": \"$msg_wahstapp\"
    }",
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        "SecretKey: $whatsapp_secretkey",
        "PublicToken: $whatsapp_publictoken",
        "DeviceToken: $whatsapp_devicetoken",
        "Authorization: $whatsapp_authorization"
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    echo $response;
    //Fim Envio Whatsapp

?>