<?php

require('../config/database.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$diasemana_numero = date('w', time());
$hora_ = time(); // agora
$hora_atual = strtotime('+3 hours', $hora_);

$hoje = date('Y-m-d');

if (!$lembrete_auto_time || strtotime($lembrete_auto_time) === false) {
  exit;
}


// Pega timestamps
$hora_config = strtotime($lembrete_auto_time); // timestamp do lembrete

// Define intervalo de tolerância (15 minutos = 900 segundos)
$intervalo = 15 * 60;

// Verifica se a hora atual está dentro do intervalo
if (abs($hora_atual - $hora_config) > $intervalo) {
  exit; // pula esse registro, está fora do intervalo
}

$diaSemana = date('w', strtotime($hoje));

if (
  ($diaSemana == 0 && $is_domingo == 0) ||
  ($diaSemana == 1 && $is_segunda == 0) ||
  ($diaSemana == 2 && $is_terca == 0) ||
  ($diaSemana == 3 && $is_quarta == 0) ||
  ($diaSemana == 4 && $is_quinta == 0) ||
  ($diaSemana == 5 && $is_sexta == 0) ||
  ($diaSemana == 6 && $is_sabado == 0)
) {
  exit;
}

$datas_envio = [];

if ($diasemana_numero == 5) { // sexta-feira
    $datas_envio[] = date('Y-m-d', strtotime('+1 day')); // sábado
    $datas_envio[] = date('Y-m-d', strtotime('+2 day')); // domingo
    $datas_envio[] = date('Y-m-d', strtotime('+3 day')); // segunda
} elseif ($diasemana_numero == 6) { // sábado
    $datas_envio[] = date('Y-m-d', strtotime('+1 day')); // domingo
    $datas_envio[] = date('Y-m-d', strtotime('+2 day')); // segunda
} else {
    $datas_envio[] = date('Y-m-d', strtotime('+1 day')); // dia seguinte
}
  
  $atendimentos_dia = '';
  
  //Envia Consultas
  $placeholders = implode(',', array_fill(0, count($datas_envio), '?'));
  $sql = "SELECT * FROM consultas WHERE atendimento_dia IN ($placeholders) AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')";
  $stmt = $conexao->prepare($sql);
  $stmt->execute($datas_envio);
  
  if ($stmt->rowCount() > 0) {

  while($select_check = $stmt->fetch(PDO::FETCH_ASSOC)){
  $atendimento_dia= $select_check['atendimento_dia'];
  $atendimento_hora = $select_check['atendimento_hora'];
  $token = $select_check['token'];
  $doc_nome = $select_check['doc_nome'];
  $doc_email = $select_check['doc_email'];
  $doc_telefone = $select_check['doc_telefone'];
  $tipo_consulta = $select_check['tipo_consulta'];
  
  $data_email = date('d/m/Y \-\ H:i:s');
  $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
  $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

  $msg_lembrete = str_replace(
      ['{NOME}', '{TELEFONE}', '{EMAIL}', '{DATA}', '{HORA}', '{TIPO}'],    // o que procurar
      [$doc_nome, $doc_telefone, $doc_email, $atendimento_dia_str, $atendimento_hora_str, $tipo_consulta],  // o que colocar no lugar
      $config_msg_lembrete
  );

  $msg_lembrete = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $msg_lembrete);
  $msg_lembrete_html = nl2br(htmlspecialchars($msg_lembrete)); //Email
  $msg_lembrete_texto = $msg_lembrete; // Whatsapp

  //Envio de Email	
  if($envio_email == 'ativado'){
  
    $link_paneil = "<a href=\"$site_atual\"'>Clique Aqui</a>";
  
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
      $mail->Subject = "$config_email - Lembrete de Consulta";
    // INICIO MENSAGEM  
      $mail->Body = "
  
      <fieldset>
      <legend><b><u>Lembrete de Consulta</u></legend>
      <p>$msg_lembrete_html</p>
      </fieldset><br><fieldset>
      <legend><b><u>Gerencia sua Consulta</u></legend>
      <p>Acesse o nosso portal, $link_paneil</p>
      </fieldset><br><fieldset>
      <legend><b><u>$config_empresa</u></legend>
      <p>CNPJ: $config_cnpj</p>
      <p>$config_telefone - $config_email</p>
      <p>$config_endereco</p></b>
      </fieldset><br><fieldset>
      <legend><b><u>Atenção</u></legend>
      <p>Este e-mail é automatico. Favor não responder!</p>
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
      $msg_whatsapp = $msg_lembrete_texto;
      
      $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
  
    }
      //Fim Envio Whatsapp
  
      //Faz a String pra envio de CAROL
      $atendimentos_dia .= "\n\n" . "$doc_nome em $atendimento_dia_str às $atendimento_hora_str";
  
  }
  
  $msg_whatsapp = "Bom dia Carol. Seguem seus proximos atendimento:$atendimentos_dia";
  }else{
  $msg_whatsapp = "Bom dia Carol. Você não tem nenhum atendimento para amanhã"; 
  }

  //Incio Envio Whatsapp
  if($envio_whatsapp == 'ativado'){
  
  $doc_telefonewhats = "5571997417190";
  
  $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
  
}
  //Fim Envio Whatsapp

  file_put_contents('cron_log.txt', date('Y-m-d H:i:s') . " - Rodou\n", FILE_APPEND);

?>