<?php

require('../conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$diasemana_numero = date('w', time());

if($diasemana_numero = 0){
$hoje = date('Y-m-d', strtotime('+1 days'));
$amanha = date('Y-m-d', strtotime('+1 days'));
}
if($diasemana_numero = 6){
$amanha = date('Y-m-d', strtotime('+3 days'));
}
if($diasemana_numero = 7){
$amanha = date('Y-m-d', strtotime('+2 days'));
}


//Envia E-mail
$result_check = $conexao->query("SELECT * FROM $tabela_reservas WHERE (atendimento_dia >= '{$hoje}' AND atendimento_dia <= '{$amanha}') AND (status_sessao = 'Confirmada' OR status_sessao = 'Em Andamento') ");
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$atendimento_dia= $select_check['atendimento_dia'];
$atendimento_hora = $select_check['atendimento_hora'];
$token = $select_check['token'];
$doc_nome = $select_check['doc_nome'];
$confirmacao = $select_check['confirmacao'];
$doc_email = $select_check['doc_email'];
$doc_telefone = $select_check['doc_telefone'];


//Envio de Email	

$data_email = date('d/m/Y \-\ H:i:s');
$atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
$atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));

    $pdf_corpo_00 = 'Olá';
    $pdf_corpo_01 = 'Lembrete de Consulta';
    $pdf_corpo_03 = 'esta confirmada e chegando!';
    $pdf_corpo_07 = 'Lembrete Enviado em'; 
    $pdf_corpo_02 = 'passando para lembrar que a sua Consulta';
    $pdf_corpo_04 = 'Atenção';

    $link_cancelar = "<a href=\"$site_atual/cancelar.php?token=$token\"'>Clique Aqui</a>";
    $link_alterar = "<a href=\"$site_atual/alterar.php?token=$token\"'>Clique Aqui</a>";

    $mail = new PHPMailer(true);

try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "$mail_Host";
    $mail->SMTPAuth = true;
    $mail->Username = "$mail_Username";
    $mail->Password = "$mail_Password";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = "$mail_Port";

    $mail->setFrom("$config_email", "$config_empresa");
    $mail->addAddress("$doc_email", "$doc_nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "$pdf_corpo_01 - $confirmacao";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend>$pdf_corpo_01 $confirmacao</legend>
    <br>
    $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 <b><u>$confirmacao</u></b> $pdf_corpo_03.<br>
    <p>Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b>h</p>
    <b>$pdf_corpo_07 $data_email</b>
    </fieldset><br><fieldset>
    <legend><b><u>$pdf_corpo_04</u></legend>
    <p>$config_msg_confirmacao</p>
    </fieldset><br><fieldset>
    <legend><b><u>Gerencia sua Consulta</u></legend>
    <p>Para Alterar sua consulta, $link_alterar</p>
    <p>Para Cancelar sua consulta, $link_cancelar</p>
    </fieldset><br><fieldset>
    <legend><b><u>$config_empresa</u></legend>
    <p>CNPJ: $config_cnpj</p>
    <p>$config_telefone - $config_email</p>
    <p>$config_endereco</p></b>
    </fieldset>
    
    "; // FIM MENSAGEM

        $mail->send();

    } catch (Exception $e) {

    }

//Fim Envio de Email

//Incio Envio Whatsapp


    $doc_telefonewhats = "55$doc_telefone";
    $msg_wahstapp = "Olá $doc_nome, passando para lhe lembrar do seu Atendimento para a Data: $atendimento_dia_str ás: $atendimento_hora_str. Posso confirmar seu Atendimento?";
    
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

//Incio Envio Whatsapp


$doc_telefonewhats = "5571997417190";
$msg_wahstapp = "Bom dia Carol. Caso tenha recebido esta mensagem, quer dizer que o sistema diario esta funcionando!";

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

?>