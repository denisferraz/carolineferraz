<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
    $token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
    $alt_status = mysqli_real_escape_string($conn_msqli, $_GET['alt_status']);
    
    $result_check = $conexao->prepare("SELECT * FROM alteracoes WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
    $result_check->execute(array('token' => $token));
    
    while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
    $atendimento_dia = $select['atendimento_dia'];
    $atendimento_hora = $select['atendimento_hora'];
    $atendimento_dia_anterior = $select['atendimento_dia_anterior'];
    $atendimento_hora_anterior = $select['atendimento_hora_anterior'];
    $id_job = $select['id_job'];
    }
    
    $result_check2 = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
    $result_check2->execute(array('token' => $token));
    
    while($select2 = $result_check2->fetch(PDO::FETCH_ASSOC)){
    $id_consulta = $select2['id'];
    $doc_email = $select2['doc_email'];
    }

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
  $doc_nome = $select_check2['nome'];
  $doc_telefone = $select_check2['telefone'];
}
$doc_telefone = preg_replace('/[^\d]/', '', $doc_telefone);
    ?>
    
    <?php
    
        $query = $conexao->prepare("DELETE FROM alteracoes WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
        $query->execute(array('token' => $token));

        if($alt_status == 'Aceita'){

        $query = $conexao->prepare("UPDATE consultas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, status_consulta = 'Confirmada' WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
        $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'token' => $token));
        
        if($id_job == 'Consulta Capilar'){
            $atendimento_hora_anterior_mais = date('H:i:s', strtotime("$atendimento_hora_anterior") + 3600);
            $query_4 = $conexao->prepare("DELETE FROM disponibilidade WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");  
            $query_4->execute(array('atendimento_dia' => $atendimento_dia_anterior, 'atendimento_hora' => $atendimento_hora_anterior_mais));
        }

        }else{
    
        $query = $conexao->prepare("UPDATE consultas SET status_consulta = 'Confirmada' WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
        $query->execute(array('token' => $token));
        
        }

        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
    
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
            $mail->Subject = "$config_empresa - Alteração de Consulta $alt_status";
          // INICIO MENSAGEM  
            $mail->Body = "
        
            <fieldset>
            <legend><b><u>Alteração Consulta</u></legend>
            <p>Sua solicitação foi $alt_status</p>
            <p>Data: $atendimento_dia_str<br>
            Hora: $atendimento_hora_str</p>
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
    $msg_whatsapp = "Olá $doc_nome. A sua solicitação de alteração foi $alt_status para a Data: $atendimento_dia_str ás $atendimento_hora_str | Solicitação $alt_status em $data_email";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
    
    }
    //Fim Envio Whatsapp

    echo   "<script>
            alert('Alteração $alt_status com Sucesso!')
            window.location.replace('cadastro.php?email=$doc_email&id_job=Consultas')
            </script>";

    ?>