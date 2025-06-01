<?php

require('../config/database.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

  $diasemana_numero = date('w', time());

  if($diasemana_numero == 6){
  $amanha = date('Y-m-d', strtotime('+2 days'));
  }else if($diasemana_numero == 5){
  $amanha = date('Y-m-d', strtotime('+3 days')); 
  }else{
  $amanha = date('Y-m-d', strtotime('+1 days'));
  }
  
  
  $atendimentos_dia = '';
  
  //Envia E-mail
  $result_check = $conexao->query("SELECT * FROM consultas WHERE atendimento_dia = '{$amanha}' AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento') ");
  if ($result_check->rowCount() > 0) {

  while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
  $atendimento_dia= $select_check['atendimento_dia'];
  $atendimento_hora = $select_check['atendimento_hora'];
  $token = $select_check['token'];
  $doc_nome = $select_check['doc_nome'];
  $doc_email = $select_check['doc_email'];
  $doc_telefone = $select_check['doc_telefone'];
  
  $data_email = date('d/m/Y \-\ H:i:s');
  $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
  $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

  //Envio de Email	
  if($envio_email == 'ativado'){
  
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
      $mail->SMTPSecure = "$mail_SMTPSecure";
      $mail->Port = "$mail_Port";
  
      $mail->setFrom("$config_email", "$config_empresa");
      $mail->addAddress("$doc_email", "$doc_nome");
      $mail->addBCC("$config_email");
      
      $mail->isHTML(true);                                 
      $mail->Subject = "$pdf_corpo_01";
    // INICIO MENSAGEM  
      $mail->Body = "
  
      <fieldset>
      <legend>$pdf_corpo_01 $confirmacao</legend>
      <br>
      $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 $pdf_corpo_03.<br>
      <p>Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b></p>
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
  
    }
  //Fim Envio de Email
  
  
  //Incio Envio Whatsapp
  if($envio_whatsapp == 'ativado'){
  
      $doc_telefonewhats = "55$doc_telefone";
      $msg_wahstapp = "Oi $doc_nome, tudo bem? 😊 \n\n" .
                "Passando para confirmar seu atendimento dia $atendimento_dia_str às $atendimento_hora_str e para garantir que tudo esteja pronto para te receber com todo o cuidado preciso que me dê um retorno confirmando até as 17h, combinado?" .
                "\n\n Caso não haja confirmação até esse horário, precisaremos liberar o horário para outro paciente. Qualquer dúvida, estou à disposição! 🤍🤍";
      
      $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
  
    }
      //Fim Envio Whatsapp
  
      //Faz a String pra envio de CAROL
      $atendimentos_dia .= "\n\n" . "$doc_nome em $atendimento_dia_str às $atendimento_hora_str";
  
  }
  
  $msg_wahstapp = "Bom dia Carol. Seguem seus proximos atendimento:$atendimentos_dia";
  }else{
  $msg_wahstapp = "Bom dia Carol. Você não tem nenhum atendimento para amanhã"; 
  }

  //Incio Envio Whatsapp
  if($envio_whatsapp == 'ativado'){
  
  $doc_telefonewhats = "5571997417190";
  
  $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
  
}
  //Fim Envio Whatsapp

  file_put_contents('cron_log.txt', date('Y-m-d H:i:s') . " - Rodou\n", FILE_APPEND);

?>