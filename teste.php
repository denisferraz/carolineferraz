<?php
$whatsapp_secretkey = 'e3b0e4b8-7670-47b6-8543-47f869ccc90e';
$whatsapp_publictoken = 'e101ed3e-f52b-4214-9fd0-a755cbc1f733';
$whatsapp_devicetoken = 'b39b7b16-f681-4ca4-9088-588f6f3b3125';
$whatsapp_authorization = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3BsYXRhZm9ybWEuYXBpYnJhc2lsLmNvbS5ici9hdXRoL2xvZ2luIiwiaWF0IjoxNjgwOTA1MjkwLCJleHAiOjE3MTI0NDEyOTAsIm5iZiI6MTY4MDkwNTI5MCwianRpIjoib2ZmQ056ZXdyMkY1YjJEaSIsInN1YiI6IjIwNzIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.o_l9j3-38eT6unoq0-DQCp5OnSePw8_ZWb2RzSWGfsA';

$doc_telefonewhats = "5571992604877";
$msg_wahstapp = "Ola Denis Ferraz, tudo bem?";

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

?>