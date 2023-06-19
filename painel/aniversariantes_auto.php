<?php

require('../conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$aniversariante = 'Hoje é aniversario de:';

$result_check = $conexao->query("SELECT * FROM painel_users WHERE nascimento = '{$hoje}' AND tipo != 'Paciente'");
if ($result_check->rowCount() > 0) {
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select_check['nome'];
$doc_email = $select_check['email'];
$doc_telefone = $select_check['telefone'];
$telefone = $select_check['telefone'];

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";

    //Faz a String pra envio de CAROL
    $aniversariante .= '\n\n'."Nome: $doc_nome".'\n'."E-mail: $doc_email".'\n'."Telefone: $doc_telefone";

}

//Incio Envio Whatsapp


$doc_telefonewhats = "5571997417190";
$msg_wahstapp = "$aniversariante".'\n\n'.'Que tal mandar uma mensagem?';

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

//Fim Envio Whatsapp

}

?>