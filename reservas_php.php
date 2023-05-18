<?php

session_start();
require('conexao.php');

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('index.html')
    </script>";
    exit();
 }

require 'vendor/autoload.php';
use Dompdf\Dompdf;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$limite_dia = $config_limitedia;
$atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
$atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);
$doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
$status_reserva = mysqli_real_escape_string($conn_msqli, $_POST['status_reserva']);
$feitapor = mysqli_real_escape_string($conn_msqli, $_POST['feitapor']);
$doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));

$hoje_hora = date('H:i:s');
$hoje = date('Y-m-d');

$historico_data = date('Y-m-d H:i:s');
if($feitapor == 'Painel'){
$email = $_SESSION['email'];
$result_historico = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
$result_historico->execute(array('email' => $email));
while($select_historico = $result_historico->fetch(PDO::FETCH_ASSOC)){
$historico_quem = $select_historico['nome'] ;
$historico_unico_usuario = $select_historico['unico'] ;
}
$feitapor = $historico_quem;
}

if($status_reserva == 'Confirmada' || $status_reserva == 'Confirmado' || $status_reserva == 'Em Andamento'){

if($status_reserva == 'Confirmada' || $status_reserva == 'Confirmado'){
$status_reserva = 'Confirmada';
}
$doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
$doc_cpf = mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']);
$token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
$horario = '';
$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);

    //Exclusao de Dias

    if(!isset($_POST['overbook_data']) && (( (date('w', strtotime("$atendimento_dia")) == 1) && $config_dia_segunda == -1) || ( (date('w', strtotime("$atendimento_dia")) == 2) && $config_dia_terca == -1) || ( (date('w', strtotime("$atendimento_dia")) == 3) && $config_dia_quarta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 4) && $config_dia_quinta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 5) && $config_dia_sexta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 6) && $config_dia_sabado == -1) || ( (date('w', strtotime("$atendimento_dia")) == 0) && $config_dia_domingo == -1))){
        echo   "<script>
        window.location.replace('agendar_no.php?id_job=$id_job&typeerror=1&atendimento_dia=$atendimento_dia&confirmacao=$confirmacao')
                </script>";
         exit();
        }

    //Verificar horarios de atendimento
    $atendimento_horas = strtotime("$atendimento_hora");
    $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
    $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
    $rodadas = 0;

    if($atendimento_dia == $hoje && ($atendimento_horas - $atendimento_hora_intervalo) <= strtotime("$hoje_hora")){
        echo   "<script>
        window.location.replace('agendar_no.php?id_job=$id_job&typeerror=2&atendimento_dia=$atendimento_dia&confirmacao=$confirmacao')
                </script>";
        exit();   
    }
    while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){

        if($atendimento_horas == $atendimento_hora_comeco){
            $horario = 'Confirma';
        }

    $rodadas++;
    $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
        }

    if($horario == '' && !isset($_POST['overbook_data'])){
        echo   "<script>
        window.location.replace('agendar_no.php')
                </script>";
        exit();
    }
    //Verificar horarios de atendimento

//Exclusao de dias
    $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");   
    $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));

    while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){

    if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
        echo   "<script>
        window.location.replace('agendar_no.php?id_job=$id_job&typeerror=3&atendimento_dia=$atendimento_dia&confirmacao=$confirmacao')
                </script>";
         exit();
    }
    }

    //Caso seja procedimento de 2h (Consulta Capilar)
    if($id_job == 'Consulta Capilar'){
    
        $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

        $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");   
        $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
        
        while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){
        
        if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
            echo   "<script>
            window.location.replace('agendar_no.php?id_job=$id_job&typeerror=3&atendimento_dia=$atendimento_dia&confirmacao=$confirmacao')
                    </script>";
                exit();
        }
        }
        }

    if($id_job == 'Consulta Capilar'){
    $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, 1)");
    $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais, 'confirmacao' => $confirmacao));
    }
    $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, 1)");
    $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao));
    
    if($status_reserva == 'Confirmada'){
    $query_2 = $conexao->prepare("INSERT INTO $tabela_reservas (atendimento_inicio, atendimento_dia, atendimento_hora, confirmacao, tipo_consulta, status_sessao, doc_email, doc_nome, doc_cpf, doc_telefone, status_reserva, data_cancelamento, confirmacao_cancelamento, feitapor, token) VALUES (:atendimento_inicio, :atendimento_dia, :atendimento_hora, :confirmacao, :tipo_consulta, 'Confirmada', :doc_email, :doc_nome, :doc_cpf, :doc_telefone, :status_reserva, :data_cancelamento, 'Ativa', :feitapor, :token)");
    $query_2->execute(array('atendimento_inicio' => $atendimento_dia, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'tipo_consulta' => $id_job, 'doc_email' => $doc_email, 'doc_nome' => $doc_nome, 'doc_cpf' => $doc_cpf, 'doc_telefone' => $doc_telefone, 'status_reserva' => $status_reserva, 'data_cancelamento' => $historico_data, 'feitapor' => $feitapor, 'token' => $token));
    }else{
    $query_2 = $conexao->prepare("UPDATE $tabela_reservas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, status_reserva = :status_reserva, token = :token, tipo_consulta = 'Nova Sessão', status_sessao = 'Confirmada' WHERE doc_email = :doc_email AND confirmacao = :confirmacao");
    $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'doc_email' => $doc_email, 'status_reserva' => $status_reserva, 'token' => $token));
    }
    if($feitapor != 'Site'){
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Criou a consulta $confirmacao"));    
    }
//Envio de Email	

$data_email = date('d/m/Y \-\ H:i:s');
$atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
$atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));

    $pdf_corpo_00 = 'Olá';
    $pdf_corpo_01 = 'Confirmação Atendimento';
    $pdf_corpo_03 = 'foi confirmado com sucesso';
    $pdf_corpo_07 = 'Atendimento confirmado em'; 
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
    $mail->Subject = "Confirmação $id_job - $config_empresa";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend>$pdf_corpo_01 $confirmacao</legend>
    <br>
    $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 <b><u>$confirmacao</u></b> $pdf_corpo_03.<br>
    <p>Tipo Consulta: <b>$id_job</b><br>
    Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b>h</p>
    <p>Preencha o formulario, $link_formulario</p>
    <b>$pdf_corpo_07 $data_email</b>
    </fieldset><br><fieldset>
    <legend><b><u>$pdf_corpo_04</u></legend>
    <p>$config_msg_confirmacao</p>
    </fieldset><br><fieldset>
    <legend><b><u>Gerenciar seus Atendimentos</u></legend>
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

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado' && $status_reserva == 'Confirmada'){

    $doc_telefonewhats = "5571997417190";
    $msg_wahstapp = "Olá $config_empresa, $doc_nome acabou de marcar uma $id_job para a Data: $atendimento_dia_str ás: $atendimento_hora_str. Entre em contato com ele por E-mail: $doc_email e/ou Whatsapp: $doc_telefone";
    
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

 echo   "<script>
window.location.replace('agendar_ok.php?token=$token')
        </script>";

}else if($status_reserva == 'Cancelada' || $status_reserva == 'Cancelado'){

    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
    
    if($atendimento_dia < $hoje){
        echo    "<script>
                window.location.replace('cancelar.php?typeerror=1&token=$token')
                </script>";
         exit();
    }

    $status_reserva = 'Cancelada';
    $confirmacao_cancelamento = strtoupper(substr(md5(date("YmdHismm")), 0, 10));

        $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
        $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'doc_email' => $doc_email, 'confirmacao' => $confirmacao));
        $row_check = $result_check->rowCount();

        while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
            $doc_nome = $select['doc_nome'];
            }

        if($row_check >= 1){
        $query = $conexao->prepare("UPDATE $tabela_reservas SET status_sessao = :status_reserva, data_cancelamento = :data_cancelamento, confirmacao_cancelamento = :confirmacao_cancelamento WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email");
        $query->execute(array('status_reserva' => $status_reserva, 'data_cancelamento' => $historico_data, 'confirmacao_cancelamento' => $confirmacao_cancelamento ,'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'doc_email' => $doc_email, 'confirmacao' => $confirmacao));
        
        if($id_job == 'Consulta Capilar'){
        $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);
        $query_2 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE id >= 1 AND confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
        $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'confirmacao' => $confirmacao, 'atendimento_hora' => $atendimento_hora_mais));
        }
        $query_2 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE id >= 1 AND confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
        $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'confirmacao' => $confirmacao, 'atendimento_hora' => $atendimento_hora));

        if($feitapor != 'Site'){
        $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
        $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cancelou a consulta $confirmacao"));
        }

            //Envio de Email	

        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
        $pdf_corpo_00 = 'Olá';
        $pdf_corpo_01 = 'Atendimento Cancelado';
        $pdf_corpo_02 = 'o seu atendimento';
        $pdf_corpo_03 = 'foi cancelado com sucesso';
        $pdf_corpo_04 = 'Numero de Cancelamento';
        $pdf_corpo_05 = 'Atenção';
        $pdf_corpo_07 = 'Atendimento Cancelado em';
        $pdf_corpo_09 = 'Atenção';

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
        <p>Data: <b>$atendimento_dia_str</b> ás: <b>$atendimento_hora_str</b>h</p>
        <p>$pdf_corpo_04: <b>$confirmacao_cancelamento</b></p>
        <br><b>$pdf_corpo_07 $data_email</b>
        </fieldset>
        <br>
        <fieldset>
        <legend><b><u>$pdf_corpo_05</u></b></legend>
        <br>
        <p><b>$config_msg_cancelamento</b></p>
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
            echo    "<script>
                window.location.replace('cancelar.php?typeerror=2&token=$token')
                </script>";
         exit();
        }

echo   "<script>
window.location.replace('reserva.php?confirmacao=$confirmacao&token=$token')
        </script>";

}else if($status_reserva == 'Alterada' || $status_reserva == 'Alterado'){

        $horario = '';
        $token = mysqli_real_escape_string($conn_msqli, $_POST['new_token']);
        $id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
        $atendimento_dia_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_anterior']);
        $atendimento_hora_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_anterior']);

        if($atendimento_dia < $hoje){
            echo "<script>
            window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                    </script>";
            exit();
        }

            //Exclusao de Dias

    if(!isset($_POST['overbook_data']) && (( (date('w', strtotime("$atendimento_dia")) == 1) && $config_dia_segunda == -1) || ( (date('w', strtotime("$atendimento_dia")) == 2) && $config_dia_terca == -1) || ( (date('w', strtotime("$atendimento_dia")) == 3) && $config_dia_quarta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 4) && $config_dia_quinta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 5) && $config_dia_sexta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 6) && $config_dia_sabado == -1) || ( (date('w', strtotime("$atendimento_dia")) == 0) && $config_dia_domingo == -1))){
        echo "<script>
        window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                </script>";
         exit();
    }

    //Verificar horarios de atendimento
    $atendimento_horas = strtotime("$atendimento_hora");
    $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
    $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
    $rodadas = 0;

    if($atendimento_dia == $hoje && ($atendimento_horas - $atendimento_hora_intervalo) <= strtotime("$hoje_hora")){
        echo "<script>
        window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                </script>";
        exit();   
    }
    while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){

        if($atendimento_horas == $atendimento_hora_comeco){
            $horario = 'Confirma';
        }

    $rodadas++;
    $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
        }

    if($horario == '' && !isset($_POST['overbook_data'])){
        echo "<script>
        window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                </script>";
        exit();
    }
    //Verificar horarios de atendimento

//Exclusao de dias
            if($feitapor == 'Painel'){
        $feitapor = $historico_quem;
            }

        $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao AND doc_email = :doc_email AND (status_reserva = 'Confirmada' OR status_reserva = 'NoShow' OR status_reserva = 'Em Andamento')");
        $result_check->execute(array('confirmacao' => $confirmacao,'doc_email' => $doc_email));
        $row_check = $result_check->rowCount();

        while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
            $doc_nome = $select['doc_nome'];
            $atendimento_dia_original = $select['atendimento_dia'];
            }

            if($row_check < 1){
                echo "<script>
                window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                        </script>";
                exit();
            }
            
            $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao != :confirmacao");   
            $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao));
            
            while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){

             if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
                echo   "<script>
                window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                        </script>";
                 exit();
            }

        }

        //Caso seja procedimento de 2h (Consulta Capilar)
        if($id_job == 'Consulta Capilar'){

            $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

            $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao != :confirmacao");   
            $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'confirmacao' => $confirmacao));
            
            while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){

             if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
                echo   "<script>
                window.location.replace('alterar_erro.php?confirmacao=$confirmacao')
                        </script>";
                 exit();
            }
            
        }
        }


        //Inicio do Aguarda retorno em casos de alterações antes das 24h
        if($atendimento_dia_original == date('Y-m-d', strtotime("$hoje"))){

            $query = $conexao->prepare("UPDATE $tabela_reservas SET token = :token, status_reserva = 'A Confirmar' WHERE confirmacao = :confirmacao AND doc_email = :doc_email");
            $query->execute(array('token' => $token,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));

            if($id_job == 'Consulta Capilar'){

            $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'confirmacao' => $confirmacao));

            }

            $query = $conexao->prepare("INSERT INTO alteracoes (token, atendimento_dia, atendimento_hora, atendimento_dia_anterior, atendimento_hora_anterior, alt_status, id_job) VALUES (:token, :atendimento_dia, :atendimento_hora, :atendimento_dia_anterior, :atendimento_hora_anterior, :id_job, 'Pendente')");
            $query->execute(array('token' => $token, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'atendimento_dia_anterior' => $atendimento_dia_anterior, 'atendimento_hora_anterior' => $atendimento_hora_anterior, 'id_job' => $id_job));

            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao));

            //Envio de Email	
        
        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));

            $link_aceitar = "<a href=\"$site_atual/solicitacao.php?alt_status=Aceita&token=$token\"'>Clique Aqui</a>";
            $link_recusar = "<a href=\"$site_atual/solicitacao.php?alt_status=Recusada&token=$token\"'>Clique Aqui</a>";
        
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
            $mail->addAddress("$config_email", "$config_empresa");
            
            $mail->isHTML(true);                                 
            $mail->Subject = "Solicitação de Alteração - $confirmacao";
          // INICIO MENSAGEM  
            $mail->Body = "
        
            <fieldset>
            <legend>Alteração $confirmacao</legend>
            <br>
            Ola <b>$config_empresa</b>, tudo bem?<br>
            O atendimento em nome de <b>$doc_nome</b>, de confirmação <b><u>$confirmacao</u></b> teve uma solicitação de Alteração.<br>
            <p>Tipo de Consulta: <b>$id_job</b><br>
            Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b>h</p>
            <b>Solicitação enviada em $data_email</b>
            </fieldset><br><fieldset>
            <legend><b><u>Gerencia a Solicitação</u></legend>
            <p>Para Aceitar a Alteração, $link_aceitar</p>
            <p>Para Recusar a Alteração, $link_recusar</p>
            </fieldset>
            
            "; // FIM MENSAGEM
        
                $mail->send();
        
            } catch (Exception $e) {
        
            }
        
        //Fim Envio de Email

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

$doc_telefonewhats = "5571997417190";
$msg_wahstapp = "Olá $config_empresa, $doc_nome solicitou uma alteração para Data: $atendimento_dia_str ás: $atendimento_hora_str. Caso queira Aceitar e/ou Recusar, acesso ao seu E-mail. | Atendimento Alterado em $data_email";

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

            echo   "<script>
            window.location.replace('alterar_limitedia.php?token=$token')
                    </script>";
            exit();
        }
        //Fim do Aguarda retorno em casos de alterações antes das 24h

            $query_3 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
            $query_3->execute(array('confirmacao' => $confirmacao, 'atendimento_dia' => $atendimento_dia_anterior, 'atendimento_hora' => $atendimento_hora_anterior));

            if($id_job == 'Consulta Capilar'){
            $atendimento_hora_anterior_mais = date('H:i:s', strtotime("$atendimento_hora_anterior") + 3600);
            $query_3 = $conexao->prepare("DELETE FROM $tabela_disponibilidade WHERE confirmacao = :confirmacao AND atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
            $query_3->execute(array('confirmacao' => $confirmacao, 'atendimento_dia' => $atendimento_dia_anterior, 'atendimento_hora' => $atendimento_hora_anterior_mais));
            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'confirmacao' => $confirmacao));
            }
            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao));
            $query_2 = $conexao->prepare("UPDATE $tabela_reservas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, feitapor = :feitapor, token = :token, status_reserva = 'Confirmada' WHERE confirmacao = :confirmacao AND doc_email = :doc_email");
            $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'feitapor' => $feitapor, 'token' => $token, 'doc_email' => $doc_email));
            if($feitapor != 'Site'){
            $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
            $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Alterou a consulta $confirmacao"));  
            }

        
        //Envio de Email	
        
        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
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

echo   "<script>
window.location.replace('reserva.php?confirmacao=$confirmacao&token=$token')
        </script>";

}else if($status_reserva == 'NoShow'){

    $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email AND status_reserva = 'Confirmada'");
    $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $row_check = $result_check->rowCount();

    if($row_check >= 1){
    $query = $conexao->prepare("UPDATE $tabela_reservas SET status_reserva = :status_reserva WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email");
    $query->execute(array('status_reserva' => $status_reserva,'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cadastrou No-Show na consulta $confirmacao"));
    
    echo "<script>
    alert('No-Show cadastrado com Sucesso')
    window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
    </script>";

    }else{
        echo $msg_not_found;
        exit();
    }

}else if($status_reserva == 'Finalizada'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $msg_finalizacao = mysqli_real_escape_string($conn_msqli, $_POST['msg_finalizar']);

    $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
    $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $row_check = $result_check->rowCount();

    if($row_check >= 1){
    $query = $conexao->prepare("UPDATE $tabela_reservas SET status_reserva = :status_reserva WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email");
    $query->execute(array('status_reserva' => $status_reserva, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Finalizou a consulta $confirmacao"));  

            //Envio de Email	

        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
        $pdf_corpo_00 = 'Olá';
        $pdf_corpo_01 = 'Atendimento Finalizado';
        $pdf_corpo_02 = 'o seu atendimento';
        $pdf_corpo_03 = 'foi finalizado com sucesso';
        $pdf_corpo_05 = 'Obrigado';
        $pdf_corpo_07 = 'Atendimento finalizado em';

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
        <p>Data: <b>$atendimento_dia_str</b> ás: <b>$atendimento_hora_str</b>h</p>
        <br><b>$pdf_corpo_07 $data_email</b>
        </fieldset>
        <br>
        <fieldset>
        <legend><b><u>$pdf_corpo_05</u></b></legend>
        <p><b>$msg_finalizacao</b></p>
        </fieldset><br><fieldset>
        <legend><b><u>$config_empresa</u></legend>
        <p>CNPJ: $config_cnpj</p>
        <p>$config_telefone - $config_email</p>
        <p>$config_endereco</p></b>
        </fieldset>
        "; // FIM MENSAGEM
    
            $mail->send();
            
            echo "<script>
            window.location.replace('painel/home.php')
                    </script>";
             exit();

        } catch (Exception $e) {

            echo "<script>
            window.location.replace('painel/home.php')
                    </script>";

        }

//Fim Envio de Email

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $doc_telefonewhats = "55$doc_telefone";
    $msg_wahstapp = "Olá $doc_nome, o seu Atendimento foi Finalizado com sucesso em $data_email! Não esqueça de nos avaliar em https://g.page/r/CY8KhBQj3vtrEB0/review!";
    
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

    $msg_wahstapp = 'https://g.page/r/CY8KhBQj3vtrEB0/review!';
    
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

        }else{
            echo "<script>
            window.location.replace('painel/home.php')
                    </script>";
            exit();
        }



}else if($status_reserva == 'EmAndamento'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $status_reserva = 'Em Andamento';

    $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
    $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $row_check = $result_check->rowCount();

    if($row_check >= 1){
    $query = $conexao->prepare("UPDATE $tabela_reservas SET status_sessao = :status_reserva WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email");
    $query->execute(array('status_reserva' => $status_reserva, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'doc_email' => $doc_email));
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Finalizou a consulta $confirmacao"));  

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $data_email = date('d/m/Y \-\ H:i:s');
    $doc_telefonewhats = "55$doc_telefone";
    $msg_wahstapp = "Olá $doc_nome, o sua Sessão foi Finalizada com sucesso em $data_email! Aproveite e já Marque a sua Próxima Sessão!";
    
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

    echo "<script>
            alert('Sessão Finalizada com Sucesso')
            window.location.replace('painel/home.php')
                    </script>";
            exit();

        }else{
            echo "<script>
            alert('Erro ao Finalizar Sessao')
            window.location.replace('painel/home.php')
                    </script>";
            exit();
        }



}