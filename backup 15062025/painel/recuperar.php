<?php
session_start();
require('../config/database.php');

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('..index.php')
    </script>";
    exit();
 }

if(empty($_POST['email']) || empty($_POST['cpf'])){
    header('Location: ../index.php');
    exit();
}


require '../vendor/autoload.php';
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['cpf']));

function validaCPF($doc_cpf) {
     
    // Extrai somente os números
    $doc_cpf = preg_replace( '/[^0-9]/is', '', $doc_cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($doc_cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $doc_cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $doc_cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($doc_cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}

if(validaCPF($doc_cpf) == false){
    echo json_encode([
        'success' => false,
        'message' => 'CPF Invalido!'
    ]);
    exit();
}

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email AND unico = :cpf");
$query->execute(array('email' => $email, 'cpf' => $doc_cpf));
$row = $query->rowCount();

if($row == 1){

    $query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email AND unico = :cpf");
    $query->execute(array('email' => $email, 'cpf' => $doc_cpf));
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $telefone = $select['telefone'];
        $nome = $select['nome'];
        $token = $select['token'];
    }

    $id = substr($doc_cpf, 4, 6).substr($doc_cpf, -3);

    //Incio Envio Whatsapp
    if($envio_whatsapp == 'ativado'){

        $doc_telefonewhats = "55$telefone";
        $msg_whastapp = "Olá $nome\n\n".
        "Você solicitou a recuperação de senha.\n\n".
        "Caso tenha sido você, clique abaixo:\n\n".
        "$site_atual/recuperar.php?id=$id&token=$token\n\n".
        "Caso contrario, certifique-se de proteger sua conta!";

        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whastapp);

    }//Fim Whatsapp

    if($envio_email == 'ativado'){

    $link_alterar = "<a href=\"$site_atual/recuperar.php?id=$id&token=$token\"'>Recuperar</a>";
    
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
        $mail->addAddress("$email", "$nome");
        
        $mail->isHTML(true);                                 
        $mail->Subject = "$config_empresa - Recuperar Senha";
      // INICIO MENSAGEM  
        $mail->Body = "
    
        <fieldset>
        <legend>Solicitação para Recuperar a Senha</legend>
        Ola <b>$nome</b>, Você solicitou a recuperação de senha.<br>
        <p>Caso tenha sido você, clique abaixo:</p>
        $link_alterar<br><br>
        Caso contrario, certifique-se de proteger sua conta!
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
    
    }//Fim Envio de Email

    $query = $conexao->prepare("UPDATE painel_users SET codigo = '1' WHERE email = :email AND unico = :cpf");
    $query->execute(array('email' => $email, 'cpf' => $doc_cpf));

    echo json_encode([
        'success' => true,
        'message' => 'Você ira receber em seu e-mail/Whatsapp o link para recuperar!'
    ]);
    exit();
}else{
    echo json_encode([
        'success' => true,
        'message' => 'Você ira receber em seu e-mail/Whatsapp o link para recuperar!'
    ]);
    exit();
}
