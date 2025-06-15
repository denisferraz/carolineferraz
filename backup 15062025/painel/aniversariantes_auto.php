<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

  
$aniversario_hoje = date('d/m', strtotime("$hoje"));
$aniversariante = 'Bom dia!! Veja a lista dos aniversariantes de hoje abaixo:';
  
  //Envia Aniversariantes
  $result_check = $conexao->query("SELECT * FROM painel_users WHERE DATE_FORMAT(nascimento, '%d/%m') = '{$aniversario_hoje}' AND tipo = 'Paciente'");

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
    $doc_telefone_ajustado = "($ddd)$prefixo-$sufixo";
    
    //Faz a String pra envio de CAROL
    $aniversariante .= "\n\nNome: $doc_nome\n".
    "E-mail: $doc_email\n".
    "Telefone: $doc_telefone_ajustado";

  
  $data_email = date('d/m/Y \-\ H:i:s');
  $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
  $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

  $msg_aniversario = str_replace(
      ['{NOME}', '{TELEFONE}', '{EMAIL}', '{DATA}', '{HORA}', '{TIPO}'],    // o que procurar
      [$doc_nome, $doc_telefone, $doc_email, $atendimento_dia_str, $atendimento_hora_str, $tipo_consulta],  // o que colocar no lugar
      $config_msg_aniversario
  );

  $msg_aniversario = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $msg_aniversario);
  $msg_html = nl2br(htmlspecialchars($msg_aniversario)); //Email
  $msg_texto = $msg_aniversario; // Whatsapp

  //Envio de Email	
  if($envio_email == 'ativado'){
  
    $link_paneil = "<a href=\"$site_atual\"'>CLique Aqui</a>";
  
    $mail = new PHPMailer(true);
  
  try {
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
      $mail->CharSet = 'UTF-8';
      $mail->isSMTP();
      $mail->Host = "$mail_Host";
      $mail->SMTPAuth = true;
      $mail->Username = "$mail_Username";
      $mail->Password = "$mail_Password";
      $mail->SMTPSecure = "$mail_SMTPSecure";
      $mail->Port = "$mail_Port";
  
      $mail->setFrom("$config_email", "$config_empresa");
      $mail->addAddress("$doc_email", "$doc_nome");
      
      $mail->isHTML(true);                                 
      $mail->Subject = "$config_email - Happy Birthday!!";
    // INICIO MENSAGEM  
      $mail->Body = "
  
      <fieldset>
      <legend><b><u>Parabens!!!</u></legend>
      <p>$msg_html</p>
      </fieldset>
      
      "; // FIM MENSAGEM
  
          $mail->send();
  
      } catch (Exception $e) {
  
      }
  
    }
  //Fim Envio de Email
  
  
  //Incio Envio Whatsapp
  if($envio_whatsapp == 'ativado'){
  
      $doc_telefonewhats = "55$doc_telefone";
      $msg_whatsapp = $msg_texto;
      
      $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
  
    }
      //Fim Envio Whatsapp
  
  }
  
  if($envio_whatsapp == 'ativado'){
    $doc_telefonewhats = "5571997417190";
    $msg_whatsapp = "$aniversariante\n\n".
    'Verifique se as mensagens automaticas foram enviadas!';
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
  }

  }
?>