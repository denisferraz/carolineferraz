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

date_default_timezone_set('America/Sao_Paulo');

  $diasemana_numero = date('w', time());

  $datas_envio = [];
  
  if ($diasemana_numero == 5) { // sexta-feira
    $datas_envio[] = date('Y-m-d', strtotime("+{$config_antecedencia} day"));         // sábado
    $datas_envio[] = date('Y-m-d', strtotime("+" . ($config_antecedencia + 1) . " day")); // domingo
    $datas_envio[] = date('Y-m-d', strtotime("+" . ($config_antecedencia + 2) . " day")); // segunda
  } elseif ($diasemana_numero == 6) { // sábado
    $datas_envio[] = date('Y-m-d', strtotime("+{$config_antecedencia} day"));         // domingo
    $datas_envio[] = date('Y-m-d', strtotime("+" . ($config_antecedencia + 1) . " day")); // segunda
  } else {
    $datas_envio[] = date('Y-m-d', strtotime("+{$config_antecedencia} day"));         // dia seguinte
  }

  $atendimentos_dia = '';
  
  //Envia Consultas
  $placeholders = implode(',', array_fill(0, count($datas_envio), '?'));
  $sql = "SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia IN ($placeholders) AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')";
  $stmt = $conexao->prepare($sql);
  $stmt->execute($datas_envio);
  
  if ($stmt->rowCount() > 0) {

if($site_puro != 'chronoclick'){
  $client_id = 'carolineferraz';
}

  while($select_check = $stmt->fetch(PDO::FETCH_ASSOC)){
  $atendimento_dia= $select_check['atendimento_dia'];
  $atendimento_hora = $select_check['atendimento_hora'];
  $token = $select_check['token'];
  $doc_email = $select_check['doc_email'];
  $tipo_consulta = $select_check['tipo_consulta'];
  $local_consulta = $select_check['local_consulta'];
  $sala = $select_check['sala'];

$query_sala = $conexao->prepare("SELECT sala FROM salas WHERE token_emp = :token_emp AND id = :id");
$query_sala->execute([
    'token_emp' => $_SESSION['token_emp'],
    'id' => $id
]);
$sala_nome = $query_sala->fetchColumn() ?: 'Sala não encontrada';

  $result_check = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
  $result_check->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
  $painel_users_array = [];
    while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check){
$doc_nome = $select_check['nome'];
$doc_telefone = $select_check['telefone'];
}

  $data_email = date('d/m/Y \-\ H:i:s');
  $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
  $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

  $msg_lembrete = str_replace(
      ['{NOME}', '{TELEFONE}', '{EMAIL}', '{DATA}', '{HORA}', '{TIPO}', '{SALA}', '{LOCAL}'],    // o que procurar
      [$doc_nome, $doc_telefone, $doc_email, $atendimento_dia_str, $atendimento_hora_str, $tipo_consulta, $sala_nome, $local_consulta],  // o que colocar no lugar
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
      
      $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
  
    }
      //Fim Envio Whatsapp
  
      //Faz a String pra envio de $config_empresa
      $atendimentos_dia .= "\n\n" . "$doc_nome em $atendimento_dia_str às $atendimento_hora_str";
  
  }

  $msg_whatsapp = "Bom dia $config_empresa. Seguem seus proximos atendimento:$atendimentos_dia";
  }else{

  $datas_formatadas = implode(', ', array_map(function($data) {
      return date('d/m/Y', strtotime($data));
  }, $datas_envio));

  $msg_whatsapp = "Bom dia $config_empresa. Você não tem nenhum atendimento para: $datas_formatadas"; 
  }

  //Incio Envio Whatsapp
  if($envio_whatsapp == 'ativado'){
  
  $doc_telefonewhats = "55$config_telefone";
  
  $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
  
}
  //Fim Envio Whatsapp

echo "<script>
alert('Lembrete de consultas enviados com Sucesso')
window.location.replace('agenda.php')
</script>";

?>