<?php

session_start();
require('config/database.php');

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

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$limite_dia = 1;
$atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
$atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
$doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
$status_consulta = mysqli_real_escape_string($conn_msqli, $_POST['status_consulta']);
$feitapor = mysqli_real_escape_string($conn_msqli, $_POST['feitapor']);
$doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));

$hoje_hora = date('H:i:s');
$hoje = date('Y-m-d');

$historico_data = date('Y-m-d H:i:s');
if($feitapor == 'Painel'){
$email = $_SESSION['email'];
$result_historico = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$result_historico->execute(array('email' => $email));
while($select_historico = $result_historico->fetch(PDO::FETCH_ASSOC)){
$historico_quem = $select_historico['nome'] ;
$historico_unico_usuario = $select_historico['unico'] ;
}
$feitapor = $historico_quem;
}

if($status_consulta == 'Confirmada' || $status_consulta == 'Confirmado' || $status_consulta == 'Em Andamento'){

if($status_consulta == 'Confirmada' || $status_consulta == 'Confirmado'){
$status_consulta = 'Confirmada';
}
$doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
$doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']));
$token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
$horario = '';
$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$local_consulta = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_local']);

    //Exclusao de Dias

    if(!isset($_POST['overbook_data']) && (( (date('w', strtotime("$atendimento_dia")) == 1) && $config_dia_segunda == -1) || ( (date('w', strtotime("$atendimento_dia")) == 2) && $config_dia_terca == -1) || ( (date('w', strtotime("$atendimento_dia")) == 3) && $config_dia_quarta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 4) && $config_dia_quinta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 5) && $config_dia_sexta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 6) && $config_dia_sabado == -1) || ( (date('w', strtotime("$atendimento_dia")) == 0) && $config_dia_domingo == -1))){
        if($feitapor == 'Paciente'){
            $_SESSION['error_reserva'] = 'Não funcionamos nesta data!';
            header("Location: painel/reservas_cadastrar.php?id_job=Site&tipo=Paciente");
            exit();
        }else{
            $_SESSION['error_reserva'] = 'Não funcionamos nesta data!';
            header("Location: painel/reservas_cadastrar.php?id_job=Painel");
            exit();
        }
        }

    //Verificar horarios de atendimento
    $atendimento_horas = strtotime("$atendimento_hora");
    $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
    $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
    $rodadas = 0;

    if(!isset($_POST['overbook_data']) && $atendimento_dia == $hoje && ($atendimento_horas - $atendimento_hora_intervalo) <= strtotime("$hoje_hora")){
        if($feitapor == 'Paciente'){

        $_SESSION['error_reserva'] = 'Não funcionamos neste horário!';
        header("Location: painel/reservas_cadastrar.php?id_job=Site&tipo=Paciente");
        exit();

        }else{
            $_SESSION['error_reserva'] = 'Não funcionamos neste horário!';
            header("Location: painel/reservas_cadastrar.php?id_job=Painel");
            exit();
    } 
    }
    while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){

        if($atendimento_horas == $atendimento_hora_comeco){
            $horario = 'Confirma';
        }

    $rodadas++;
    $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
        }

    if($horario == '' && !isset($_POST['overbook_data'])){
        if($feitapor == 'Paciente'){
            $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data!';
            header("Location: painel/reservas_cadastrar.php?id_job=Site&tipo=Paciente");
            exit();
        }else{
            $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data!';
            header("Location: painel/reservas_cadastrar.php?id_job=Painel");
            exit();
    }
    }
    //Verificar horarios de atendimento

//Exclusao de dias
    $check_consultas = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora"); 
    $check_consultas->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));
    $check_disponibilidade = $conexao->prepare("SELECT * FROM disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora"); 
    $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));

    $total_consultas = $check_consultas->rowcount() + $check_disponibilidade->rowcount();

    if(!isset($_POST['overbook']) && ($total_consultas + 1) > $limite_dia){
        if($feitapor == 'Paciente'){
            $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data/horario!';
            header("Location: painel/reservas_cadastrar.php?id_job=Site&tipo=Paciente");
            exit();
        }else{
            $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data/horario!';
            header("Location: painel/reservas_cadastrar.php?id_job=Painel");
            exit();
        }
    }

    //Caso seja procedimento de 2h (Consulta Capilar)
    if($id_job == 'Consulta Capilar'){
    
        $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

        $check_consultas = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora"); 
        $check_consultas->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
        $check_disponibilidade = $conexao->prepare("SELECT * FROM disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora"); 
        $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));

        $total_consultas = $check_consultas->rowcount() + $check_disponibilidade->rowcount();
        
        if(!isset($_POST['overbook']) && ($total_consultas + 1) > $limite_dia){
            if($feitopor == 'Site'){
                $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data/horario!';
                header("Location: painel/reservas_cadastrar.php?id_job=Site&tipo=Paciente");
                exit();
            }else{
            $_SESSION['error_reserva'] = 'Sem Disponibilidade para esta data/horario!';
            header("Location: painel/reservas_cadastrar.php?id_job=Painel");
            exit();
            }
        }
        }

    if($id_job == 'Consulta Capilar'){
    $query = $conexao->prepare("INSERT INTO disponibilidade (atendimento_dia, atendimento_hora) VALUES (:atendimento_dia, :atendimento_hora)");
    $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
    }

    if($status_consulta == 'Confirmada'){
    $query_2 = $conexao->prepare("INSERT INTO consultas (atendimento_dia, atendimento_hora, tipo_consulta, status_consulta, doc_email, doc_nome, doc_cpf, doc_telefone, data_cancelamento, confirmacao_cancelamento, feitapor, token, local_consulta) VALUES (:atendimento_dia, :atendimento_hora, :tipo_consulta, :status_consulta, :doc_email, :doc_nome, :doc_cpf, :doc_telefone, :data_cancelamento, 'Ativa', :feitapor, :token, :local_consulta)");
    $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'tipo_consulta' => $id_job, 'doc_email' => $doc_email, 'doc_nome' => $doc_nome, 'doc_cpf' => $doc_cpf, 'doc_telefone' => $doc_telefone, 'status_consulta' => $status_consulta, 'data_cancelamento' => $historico_data, 'feitapor' => $feitapor, 'token' => $token, 'local_consulta' => $local_consulta));
    }else{
    $query_2 = $conexao->prepare("UPDATE consultas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, status_consulta = :status_consulta, token = :token, tipo_consulta = 'Nova Sessão', local_consulta = :local_consulta WHERE doc_email = :doc_email AND id = :id_consulta");
    $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta, 'doc_email' => $doc_email, 'status_consulta' => $status_consulta, 'token' => $token, 'local_consulta' => $local_consulta));
    }
    if($feitapor != 'Paciente'){
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Criou a consulta $id_consulta"));    
    }

    //Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
    
    $doc_telefonewhats = "5571997417190";
    $msg_whastapp = "Olá $config_empresa\n\n".
    "$doc_nome agendou uma $id_job para $local_consulta - Data: $atendimento_dia_str ás: $atendimento_hora_str\n\n".
    "Telefone: $doc_telefone\n".
    "E-mail: $doc_email";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whastapp);
    
    }
    //Fim Envio Whatsapp

    if($feitapor == 'Paciente'){
    echo   "<script>
    alert('Consulta Confirmada com Sucesso!')
    window.location.replace('painel/reservas_paciente.php')
            </script>";
    exit();
    }else{
    echo   "<script>
    alert('Consulta Confirmada com Sucesso!')
    window.location.replace('painel/agenda.php')
        </script>";
        exit();
}

}else if($status_consulta == 'Cancelada' || $status_consulta == 'Cancelado'){

    $id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    
    if($atendimento_dia < $hoje){
        $_SESSION['error_reserva'] = 'Não é possivel Cancelar esta Consulta!';
        header("Location: painel/reservas_cancelar.php?id_consulta=$id_consulta&tipo=$feitapor");
        exit();
    }

    $status_consulta = 'Cancelada';
    $confirmacao_cancelamento = strtoupper(substr(md5(date("YmdHismm")), 0, 10));

        $result_check = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')");
        $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta));
        $row_check = $result_check->rowCount();

        while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
            $doc_nome = $select['doc_nome'];
            }

        if($row_check >= 1){
        $query = $conexao->prepare("UPDATE consultas SET status_consulta = :status_consulta, data_cancelamento = :data_cancelamento, confirmacao_cancelamento = :confirmacao_cancelamento WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta");
        $query->execute(array('status_consulta' => $status_consulta, 'data_cancelamento' => $historico_data, 'confirmacao_cancelamento' => $confirmacao_cancelamento ,'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta));
        
        if($id_job == 'Consulta Capilar'){
        $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);
        $query_2 = $conexao->prepare("DELETE FROM disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
        $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
        }

        if($feitapor != 'Paciente'){
        $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
        $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cancelou a consulta $id_consulta"));
        }

            //Envio de Email	

        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
        if($envio_email == 'ativado'){

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
        <legend>$pdf_corpo_01</legend>
        <br>
        $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 $pdf_corpo_03.<br>
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

    }
//Fim Envio de Email


        }else{
            $_SESSION['error_reserva'] = 'Não é possivel Cancelar esta Consulta!';
            header("Location: painel/reservas_cancelar.php?id_consulta=$id_consulta&tipo=$feitapor");
            exit();
        }

        if($feitapor == 'Paciente'){
            echo   "<script>
            alert('Consulta Cancelada com Sucesso!')
            window.location.replace('painel/reservas_paciente.php')
            </script>";
            exit();
            }else{
            echo   "<script>
            alert('Consulta Cancelada com Sucesso!')
            window.location.replace('painel/reserva.php?id_consulta=$id_consulta')
            </script>";
            exit();
        }

}else if($status_consulta == 'Alterada' || $status_consulta == 'Alterado'){

        $horario = '';
        $token = mysqli_real_escape_string($conn_msqli, $_POST['new_token']);
        $id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
        $atendimento_dia_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_anterior']);
        $atendimento_hora_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_anterior']);
        $local_consulta = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_local']);
        $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);

        if($atendimento_dia < $hoje){
            $_SESSION['error_reserva'] = 'Não é possivel Alterar esta Consulta!';
            header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
            exit();
        }

            //Exclusao de Dias

    if(!isset($_POST['overbook_data']) && (( (date('w', strtotime("$atendimento_dia")) == 1) && $config_dia_segunda == -1) || ( (date('w', strtotime("$atendimento_dia")) == 2) && $config_dia_terca == -1) || ( (date('w', strtotime("$atendimento_dia")) == 3) && $config_dia_quarta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 4) && $config_dia_quinta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 5) && $config_dia_sexta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 6) && $config_dia_sabado == -1) || ( (date('w', strtotime("$atendimento_dia")) == 0) && $config_dia_domingo == -1))){
        $_SESSION['error_reserva'] = 'Não funcionamos nesta data!';
        header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
        exit();
    }

    //Verificar horarios de atendimento
    $atendimento_horas = strtotime("$atendimento_hora");
    $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
    $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
    $rodadas = 0;

    if(!isset($_POST['overbook_data']) && $atendimento_dia == $hoje && ($atendimento_horas - $atendimento_hora_intervalo) <= strtotime("$hoje_hora")){
        $_SESSION['error_reserva'] = 'Não funcionamos neste horário!';
        header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
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
        $_SESSION['error_reserva'] = 'Não foi possivel alterar a Consulta!';
        header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
        exit();
    }
    //Verificar horarios de atendimento

//Exclusao de dias
            if($feitapor == 'Painel'){
        $feitapor = $historico_quem;
            }

        $result_check = $conexao->prepare("SELECT * FROM consultas WHERE id = :id_consulta AND doc_email = :doc_email AND (status_consulta = 'Confirmada' OR status_consulta = 'NoShow' OR status_consulta = 'Em Andamento')");
        $result_check->execute(array('id_consulta' => $id_consulta,'doc_email' => $doc_email));
        $row_check = $result_check->rowCount();

        while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
            $doc_nome = $select['doc_nome'];
            $atendimento_dia_original = $select['atendimento_dia'];
            }

            if($row_check < 1){
                $_SESSION['error_reserva'] = 'Não foi possivel alterar a Consulta!';
                header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
                exit();
            }
            $check_consulta = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id != :id_consulta");   
            $check_consulta->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'id_consulta' => $id_consulta));
            
            $check_disponibilidade = $conexao->prepare("SELECT * FROM disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");   
            $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));

            $check_total = $check_consulta->rowcount() + $check_disponibilidade->rowcount();

             if(!isset($_POST['overbook']) && ($check_total + 1) > $limite_dia){
                $_SESSION['error_reserva'] = 'Não temos disponibilidade para este horario e data!';
                header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
                exit();
            }

        //Caso seja procedimento de 2h (Consulta Capilar)
        if($id_job == 'Consulta Capilar'){

            $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

            $check_consulta = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id != :id_consulta");   
            $check_consulta->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'id_consulta' => $id_consulta));
            
            $check_disponibilidade = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id != :id_consulta");   
            $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'id_consulta' => $id_consulta));

            $check_total = $check_consulta->rowcount() + $check_disponibilidade->rowcount();

            if(!isset($_POST['overbook']) && ($check_total + 1) > $limite_dia){
                $_SESSION['error_reserva'] = 'Não temos disponibilidade para este horario e data!';
                header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
                exit();
            }
        }


        //Inicio do Aguarda retorno em casos de alterações antes das 24h
        if($atendimento_dia_original == date('Y-m-d', strtotime("$hoje"))){

            $query = $conexao->prepare("UPDATE consultas SET token = :token, status_consulta = 'A Confirmar' WHERE id = :id_consulta AND doc_email = :doc_email");
            $query->execute(array('token' => $token,'id_consulta' => $id_consulta, 'doc_email' => $doc_email));

            if($id_job == 'Consulta Capilar'){

            $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

            $query = $conexao->prepare("INSERT INTO disponibilidade (atendimento_dia, atendimento_hora) VALUES (:atendimento_dia, :atendimento_hora)");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));

            }

            $query = $conexao->prepare("INSERT INTO alteracoes (token, atendimento_dia, atendimento_hora, atendimento_dia_anterior, atendimento_hora_anterior, alt_status, id_job) VALUES (:token, :atendimento_dia, :atendimento_hora, :atendimento_dia_anterior, :atendimento_hora_anterior, 'Pendente', :id_job)");
            $query->execute(array('token' => $token, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'atendimento_dia_anterior' => $atendimento_dia_anterior, 'atendimento_hora_anterior' => $atendimento_hora_anterior, 'id_job' => $id_job));


        //Incio Envio Whatsapp
        if($envio_whatsapp == 'ativado'){

        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

        $doc_telefonewhats = "5571997417190";
        $msg_whastapp = "Olá $config_empresa\n\n".
        "$doc_nome solicitou uma alteração para $local_consulta - Data: $atendimento_dia_str ás: $atendimento_hora_str\n\n\n".
        "Para Aceitar clique abaixo:\n".
        "$site_atual/painel/reservas_solicitacao.php?alt_status=Aceita&token=$token\n\n".
        "Para Recusar clique abaixo:\n".
        "$site_atual/painel/reservas_solicitacao.php?alt_status=Recusada&token=$token";

        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whastapp);

        }
//Fim Envio Whatsapp

        $_SESSION['error_reserva'] = 'Como faltam <b>menos do que 24h</b> para o seu <b>Horário Original</b>, uma <b>solicitação</b> foi enviada para Caroline Ferraz. Em breve entraremos em contato.';
        header("Location: painel/editar_reservas.php?id_consulta=$id_consulta&tipo=$feitapor");
        exit();
        }
        //Fim do Aguarda retorno em casos de alterações antes das 24h

            if($id_job == 'Consulta Capilar'){
            $atendimento_hora_anterior_mais = date('H:i:s', strtotime("$atendimento_hora_anterior") + 3600);
            $query = $conexao->prepare("INSERT INTO disponibilidade (atendimento_dia, atendimento_hora) VALUES (:atendimento_dia, :atendimento_hora)");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
            }
            $query_2 = $conexao->prepare("UPDATE consultas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, feitapor = :feitapor, token = :token, status_consulta = 'Confirmada', local_consulta = :local_consulta WHERE id = :id_consulta");
            $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta, 'feitapor' => $feitapor, 'token' => $token, 'local_consulta' => $local_consulta));
            if($feitapor != 'Paciente'){
            $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
            $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Alterou a consulta $id_consulta"));  
            }

        
        //Envio de Email	
        
        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
        if($envio_email == 'ativado'){

            $pdf_corpo_00 = 'Olá';
            $pdf_corpo_01 = 'Alteração Atendimento';
            $pdf_corpo_03 = 'foi alterado com sucesso';
            $pdf_corpo_07 = 'Atendimento alterado em';
            $pdf_corpo_02 = 'o seu atendimento';
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
            $mail->Subject = "Alteração da sua Consulta";
          // INICIO MENSAGEM  
            $mail->Body = "
        
            <fieldset>
            <legend>Alteração da sua Consulta</legend>
            <br>
            $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 $pdf_corpo_03.<br>
            <p>Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b>h</p>
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
        
        }
        //Fim Envio de Email

        if($feitapor == 'Paciente'){
            echo   "<script>
            alert('Consulta Alterada com Sucesso!')
            window.location.replace('painel/reservas_paciente.php')
            </script>";
            exit();
            }else{
            echo   "<script>
            alert('Consulta Alterada com Sucesso!')
            window.location.replace('painel/reserva.php?id_consulta=$id_consulta')
            </script>";
            exit();
        }

}else if($status_consulta == 'NoShow'){

    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);

    $result_check = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta AND doc_email = :doc_email");
    $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'id_consulta' => $id_consulta, 'doc_email' => $doc_email));
    $row_check = $result_check->rowCount();

    if($row_check >= 1){
    $query = $conexao->prepare("UPDATE consultas SET status_consulta = :status_consulta WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta AND doc_email = :doc_email");
    $query->execute(array('status_consulta' => $status_consulta,'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'id_consulta' => $id_consulta, 'doc_email' => $doc_email));
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cadastrou No-Show na consulta $id_consulta"));
    
    echo "<script>
    alert('No-Show cadastrado com Sucesso')
    window.location.replace('painel/reserva.php?id_consulta=$id_consulta')
    </script>";

    }else{
        echo $msg_not_found;
        exit();
    }

}else if($status_consulta == 'Finalizada'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $msg_finalizacao = $config_msg_finalizar;

    $result_check = $conexao->prepare("SELECT * FROM consultas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta AND doc_email = :doc_email AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')");
    $result_check->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta, 'doc_email' => $doc_email));
    $row_check = $result_check->rowCount();

    if($row_check >= 1){
    $query = $conexao->prepare("UPDATE consultas SET status_consulta = :status_consulta WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND id = :id_consulta AND doc_email = :doc_email");
    $query->execute(array('status_consulta' => $status_consulta, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'id_consulta' => $id_consulta, 'doc_email' => $doc_email));
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Finalizou a consulta $id_consulta"));  

            //Envio de Email	

        $data_email = date('d/m/Y \-\ H:i:s');
        $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
        $atendimento_hora_str = date('H:i',  strtotime($atendimento_hora));
        
        if($envio_email == 'ativado'){

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
        <legend>$pdf_corpo_01</legend>
        <br>
        $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 $pdf_corpo_03.<br>
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

        } catch (Exception $e) {

        }

    }
//Fim Envio de Email

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){
    
    $site_avaliacao1 = 'https://forms.gle/mc2gyyVwMCvW2HRq9';
    $site_avaliacao2 = 'https://g.co/kgs/kgkGKy';

    $doc_telefonewhats = "55$doc_telefone";
    $msg_whastapp = "Olá $doc_nome, sua consulta foi finalizada em $data_email!\n".
    "Obrigada por sua presença hoje!\n\n".
    "Se puder, responda esse breve formulário pra me contar como foi sua experiência:\n".
    "📋$site_avaliacao1\n\n".
    "E se meu atendimento te ajudou, sua avaliação no Google faz toda a diferença. Isso fortalece meu trabalho e ajuda outras pessoas a me encontrarem:\n".
    "🌟$site_avaliacao2";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whastapp);
    
    }
    //Fim Envio Whatsapp

    echo "<script>
    alert('Consulta finalizada com Sucesso')
    window.location.replace('painel/reserva.php?id_consulta=$id_consulta')
    </script>";

        }else{
            echo "<script>
            window.location.replace('painel/home.php')
                    </script>";
            exit();
        }



}else if($status_consulta == 'EnvioMensagem'){

$doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
$doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
$token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);

//Envio de Email	

$data_email = date('d/m/Y \-\ H:i:s');
$atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
$atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));


if(isset($_POST['email'])){
    if($envio_email == 'ativado'){

    $pdf_corpo_00 = 'Olá';
    $pdf_corpo_01 = 'Confirmação Atendimento';
    $pdf_corpo_03 = 'foi confirmado com sucesso';
    $pdf_corpo_07 = 'Atendimento confirmado em'; 
    $pdf_corpo_02 = 'o seu atendimento';
    $pdf_corpo_04 = 'Atenção';

    $link_cancelar = "<a href=\"$site_atual/cancelar.php?token=$token&typeerror=0\"'>Clique Aqui</a>";
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
    $mail->Subject = "Confirmação $id_job - $config_empresa";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend>$pdf_corpo_01 $confirmacao</legend>
    <br>
    $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 <b><u>$confirmacao</u></b> $pdf_corpo_03.<br>
    <p>Tipo Consulta: <b>$id_job</b><br>
    Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b></p>
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

}
}
//Fim Envio de Email

if(isset($_POST['whatsapp'])){

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $doc_telefonewhats = "55$doc_telefone";
    $msg_whatsapp = "Olá $doc_nome, tudo bem?\n\n" .
                "Aqui vai a confirmação da sua $id_job para a Data: $atendimento_dia_str às $atendimento_hora_str.\n\n\n" .
                "Para Alterar seu Atendimento acesse: $site_atual/alterar.php?token=$token\n\n\n" .
                "Para Cancelar seu Atendimento acesse: $site_atual/cancelar.php?token=$token&typeerror=0";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whastapp);
    
    }

}
//Fim Envio Whatsapp

$id = base64_encode("$confirmacao.$token");
    echo   "<script>
            window.location.replace('reserva.php?id=$id')
            </script>";

}