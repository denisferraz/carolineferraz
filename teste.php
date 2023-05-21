<?php

require('conexao.php');

$doc_telefonewhats = "5571992604877";
$msg_whatsapp = "Teste de Envio de Mensagem com botoes no Multidevice".'\n\n'."Com quebra de linha".'\n\n'."https://apibrasil.com.br";

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
    \"text\": \"$msg_whatsapp\"
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

echo $response;

echo "<br><br>$msg_whatsapp";

curl_close($curl);
?>