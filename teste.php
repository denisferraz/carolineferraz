<?php

require('conexao.php');

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $doc_telefone = '71992604877';

    $doc_telefonewhats = "55$doc_telefone";
    $msg_wahstapp = "Olá Denis Ferraz, tudo bem? A sua Avaliação Capilar foi confirmada com sucesso para a Data: 21/04/2023 ás: 15:00h. Caso queira Cancelar/Alterar a Data e/ou Horario, acesse ao seu E-mail.";
    
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
    
    }