<?php
require('../config/database.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Sao_Paulo');

$aniversario_hoje = date('m-d', strtotime("$hoje"));
$aniversariante = 'Bom dia!! Veja a lista dos aniversariantes de hoje abaixo:';

$result_check_config = $conexao->query("SELECT * FROM configuracoes WHERE id > -3");
while($select_check_config = $result_check_config->fetch(PDO::FETCH_ASSOC)){
    $token_config = $select_check_config['token_emp'];
    $config_empresa = $row['config_empresa'];
    $config_email = $row['config_email'];
    $config_telefone = $row['config_telefone'];
    $config_msg_aniversario = $row['config_msg_aniversario'];
    $envio_whatsapp = $row['envio_whatsapp'];
    $envio_email = $row['envio_email'];

  //Envia Aniversariantes
  $result_check = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp");
  $result_check->execute(array('token_emp' => '%;'.$token_config.';%'));
  $painel_users_array = [];
    while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'email' => $select['email'],
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3],
        'nascimento' => $dados_array[5]
    ];

}
  $aniversariantes = 0;
    foreach ($painel_users_array as $select_check){
    $data_nascimento = $item['nascimento'];

    if (!empty($data_nascimento)) {
      $mes_dia = date('m-d', strtotime($data_nascimento));
      
      if ($mes_dia === $aniversario_hoje) {

    $aniversariantes++;
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
      $mail->Subject = "$config_empresa - Happy Birthday $doc_nome!!";
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

    }}}

    if($envio_whatsapp == 'ativado' && $aniversariantes > 0){
      $doc_telefonewhats = "55$config_telefone";
      $msg_whatsapp = "$aniversariante\n\n".
      'Verifique se as mensagens automaticas foram enviadas!';
      $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
    }

}
?>