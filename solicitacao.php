<?php

session_start();
require('conexao.php');
require('verifica_login.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$alt_status = mysqli_real_escape_string($conn_msqli, $_GET['alt_status']);

$result_check = $conexao->prepare("SELECT * FROM alteracoes WHERE token = :token");
$result_check->execute(array('token' => $token));

while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$atendimento_dia_anterior = $select['atendimento_dia_anterior'];
$atendimento_hora_anterior = $select['atendimento_hora_anterior'];
$id_job = $select['id_job'];
}

$result_check2 = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token");
$result_check2->execute(array('token' => $token));

while($select2 = $result_check2->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select2['confirmacao'];
$doc_nome = $select2['doc_nome'];
$doc_email = $select2['doc_email'];
$doc_telefone = $select2['doc_telefone'];
$doc_telefone = preg_replace('/[^\d]/', '', $doc_telefone);
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <header>
    <?php echo $menu_site_logado ?>
    </header>
    <main>
        <section class="home">
            <div class="home-text">
            <h4 class="text-h4">Atenção!</h4><br>
            <p>A Troca de Data foi <b><?php echo $alt_status ?></b>!</p>

</div>
        </section>
    </main>
    <script src="js/script.js"></script>
    <script>
        $(function(){ 
           $('i.fas').click(function(){
               var listaMenu = $('nav.mobile ul');
               if(listaMenu.is(':hidden') == true){
                   var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('fas fa-bars');
                   icone.addClass('far fa-times-circle');
                   listaMenu.slideToggle();
               }else{
                var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('far fa-times-circle');
                   icone.addClass('fas fa-bars');
                   listaMenu.slideToggle(); 
               }
           }) 
        })
    </script> 
</body>
</html>

<?php

    $query = $conexao->prepare("UPDATE alteracoes SET alt_status = :alt_status, id_job = :id_job WHERE token = :token");
    $query->execute(array('alt_status' => $alt_status,'id_job' => $id_job, 'token' => $token));

    $data_email = date('d/m/Y \-\ H:i:s');
    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

    if($alt_status == 'Aceita'){
        $query = $conexao->prepare("UPDATE $tabela_reservas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, status_sessao = 'Confirmada' WHERE token = :token");
        $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'token' => $token));

        $query_3 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");  
        $query_3->execute(array('confirmacao' => $confirmacao, 'atendimento_dia' => $atendimento_dia_anterior, 'atendimento_hora' => $atendimento_hora_anterior));
    
    if($id_job == 'Consulta Capilar'){
        $atendimento_hora_anterior_mais = date('H:i:s', strtotime("$atendimento_hora_anterior") + 3600);
        $query_4 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");  
        $query_4->execute(array('confirmacao' => $confirmacao, 'atendimento_dia' => $atendimento_dia_anterior, 'atendimento_hora' => $atendimento_hora_anterior_mais));
    }

    //Envio de Email	
    
        $pdf_corpo_00 = 'Olá';
        $pdf_corpo_01 = 'Alteração Atendimento';
        $pdf_corpo_03 = 'foi alterado com sucesso';
        $pdf_corpo_07 = 'Atendimento alterado em';
        $pdf_corpo_02 = 'o seu atendimento';
        $pdf_corpo_04 = 'Atenção';

        $link_cancelar = "<a href=\"$site_atual/cancelar.php?token=$token\"'>Clique Aqui</a>";
        $link_alterar = "<a href=\"$site_atual/alterar.php?token=$token\"'>Clique Aqui</a>";
        $link_formulario = "<a href=\"$site_atual/formulario.php?token=$token\"'>Clique Aqui</a>";
    
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
        <p>Preencha o formulario, $link_formulario</p>
        <b>$pdf_corpo_07 $data_email</b>
        </fieldset><br><fieldset>
        <legend><b><u>$pdf_corpo_04</u></legend>
        <p>$config_msg_confirmacao</p>
        </fieldset><br><fieldset>
        <legend><b><u>Gerenciar seu Atendimento</u></legend>
        <p>Para Alterar seu Atendimento, $link_alterar</p>
        <p>Para Cancelar seu Atendimento, $link_cancelar</p>
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
    }else{

    $query = $conexao->prepare("UPDATE $tabela_reservas SET status_sessao = 'Recusada' WHERE token = :token");
    $query->execute(array('token' => $token));

    $query_3 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");  
    $query_3->execute(array('confirmacao' => $confirmacao, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));

    }

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

$doc_telefonewhats = "55$doc_telefone";
$msg_wahstapp = "Olá $doc_nome. A sua solicitação de alteração foi $alt_status para a Data: $atendimento_dia_str ás $atendimento_hora_str | Solicitação $alt_status em $data_email";

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

}
//Fim Envio Whatsapp

?>