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
$doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']));
$token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
$horario = '';
$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$local_reserva = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_local']);

    //Exclusao de Dias

    if(!isset($_POST['overbook_data']) && (( (date('w', strtotime("$atendimento_dia")) == 1) && $config_dia_segunda == -1) || ( (date('w', strtotime("$atendimento_dia")) == 2) && $config_dia_terca == -1) || ( (date('w', strtotime("$atendimento_dia")) == 3) && $config_dia_quarta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 4) && $config_dia_quinta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 5) && $config_dia_sexta == -1) || ( (date('w', strtotime("$atendimento_dia")) == 6) && $config_dia_sabado == -1) || ( (date('w', strtotime("$atendimento_dia")) == 0) && $config_dia_domingo == -1))){
        if($feitapor == 'Site'){
        
        $id = base64_encode("$id_job.1.$atendimento_dia.$confirmacao.$local_reserva");
        echo   "<script>
        window.location.replace('agendar_no.php?id=$id')
                </script>";
         exit();
        }else{
            echo   "<script>
            alert('Não funcionamos nesta data')
            window.location.replace('painel/reservas_cadastrar.php?id_job=Painel')
                </script>";
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
        if($feitapor == 'Site'){

        $id = base64_encode("$id_job.2.$atendimento_dia.$confirmacao.$local_reserva");
        echo   "<script>
        window.location.replace('agendar_no.php?id=$id')
                </script>";
        exit();
        }else{
        echo   "<script>
        alert('Não é possivel agendar para este dia/horario')
        window.location.replace('painel/reservas_cadastrar.php?id_job=Painel')
            </script>";
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
        if($feitapor == 'Site'){
        echo   "<script>
        window.location.replace('agendar_no.php')
                </script>";
        exit();
        }else{
        echo   "<script>
        alert('Sem Disponibilidade para esta data!')
        window.location.replace('painel/reservas_cadastrar.php?id_job=Painel')
            </script>";
            exit();
    }
    }
    //Verificar horarios de atendimento

//Exclusao de dias
if($local_reserva == 'Salvador'){
    $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora"); 
}else{
    $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND local_reserva != 'Salvador'");   
}

    $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora));
    while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){

    if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
        if($feitapor == 'Site'){

        $id = base64_encode("$id_job.3.$atendimento_dia.$confirmacao.$local_reserva");
        echo   "<script>
        window.location.replace('agendar_no.php?id=$id')
                </script>";
         exit();
        }else{
            echo   "<script>
            alert('Sem Disponibilidade para esta data/horario!')
            window.location.replace('painel/reservas_cadastrar.php?id_job=Painel')
                </script>";
                exit();
        }
    }
    }

    //Caso seja procedimento de 2h (Consulta Capilar)
    if($id_job == 'Consulta Capilar'){
    
        $atendimento_hora_mais = date('H:i:s', strtotime("$atendimento_hora") + 3600);

        if($local_reserva == 'Salvador'){
            $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora");
        }else{
            $check_disponibilidade = $conexao->prepare("SELECT sum(quantidade) FROM $tabela_disponibilidade WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND local_reserva != 'Salvador'"); 
        }   
        $check_disponibilidade->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais));
        
        while($total_reservas = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){
        
        if(!isset($_POST['overbook']) && ($total_reservas['sum(quantidade)'] + 1) > $limite_dia){
            if($feitopor == 'Site'){

            $id = base64_encode("$id_job.3.$atendimento_dia.$confirmacao.$local_reserva");
            echo   "<script>
            window.location.replace('agendar_no.php?id=$id')
                    </script>";
                exit();
            }else{
                echo   "<script>
                alert('Sem Disponibilidade para esta data/horario!')
                window.location.replace('painel/reservas_cadastrar.php?id_job=Painel')
                    </script>";
                    exit();
            }
        }
        }
        }

        //Verificar se existe Email cadastro, caso contrario, cria um cadastro novo
        $query_cadastro = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
        $query_cadastro->execute(array('email' => $doc_email));
        $row_cadastro = $query_cadastro->rowCount();
    
        if($row_cadastro != 1){

        $token = md5(date("YmdHismm"));
        $doc_senha = md5(substr($doc_cpf, 0, 6));

        $query_cadastro2 = $conexao->prepare("INSERT INTO $tabela_painel_users (email, tipo, senha, nome, telefone, unico, token, rg, nascimento, codigo, tentativas, aut_painel, origem) VALUES (:email, 'Paciente', :senha, :nome, :telefone, :cpf, :token, :rg, :nascimento, '0', '0', '1', :origem)");
        $query_cadastro2->execute(array('email' => $doc_email, 'nome' => $doc_nome, 'cpf' => $doc_cpf, 'token' => $token, 'rg' => '000', 'nascimento' => '2000-01-01', 'telefone' => $doc_telefone, 'senha' => $doc_senha, 'origem' => 'Indicação'));

        }

    $query_consulta = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE doc_email = :doc_email AND status_sessao = :status_sessao");
    $query_consulta->execute(array('doc_email' => $doc_email, 'status_sessao' => 'Em Andamento'));
    $row_query_consulta = $query_consulta->rowCount();
    while($consulta_query = $query_consulta->fetch(PDO::FETCH_ASSOC)){
        $confirmacao = $consulta_query['confirmacao'];
    }

    if($id_job == 'Consulta Capilar'){
    $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, 1)");
    $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais, 'confirmacao' => $confirmacao, 'local_reserva' => 'ALL'));
    }
    $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, 1)");
    $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'local_reserva' => 'ALL'));
    
    if($status_reserva == 'Confirmada' && ($row_query_consulta == 0 || $row_query_consulta == '')){
    $query_2 = $conexao->prepare("INSERT INTO $tabela_reservas (atendimento_inicio, atendimento_dia, atendimento_hora, confirmacao, tipo_consulta, status_sessao, doc_email, doc_nome, doc_cpf, doc_telefone, status_reserva, data_cancelamento, confirmacao_cancelamento, feitapor, token, local_reserva) VALUES (:atendimento_inicio, :atendimento_dia, :atendimento_hora, :confirmacao, :tipo_consulta, 'Confirmada', :doc_email, :doc_nome, :doc_cpf, :doc_telefone, :status_reserva, :data_cancelamento, 'Ativa', :feitapor, :token, :local_reserva)");
    $query_2->execute(array('atendimento_inicio' => $atendimento_dia, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'tipo_consulta' => $id_job, 'doc_email' => $doc_email, 'doc_nome' => $doc_nome, 'doc_cpf' => $doc_cpf, 'doc_telefone' => $doc_telefone, 'status_reserva' => $status_reserva, 'data_cancelamento' => $historico_data, 'feitapor' => $feitapor, 'token' => $token, 'local_reserva' => $local_reserva));
    }else{
    $query_2 = $conexao->prepare("UPDATE $tabela_reservas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, status_reserva = :status_reserva, token = :token, tipo_consulta = 'Nova Sessão', status_sessao = 'Confirmada', local_reserva = :local_reserva WHERE doc_email = :doc_email AND confirmacao = :confirmacao");
    $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'doc_email' => $doc_email, 'status_reserva' => $status_reserva, 'token' => $token, 'local_reserva' => $local_reserva));
    }
    if($feitapor != 'Site'){
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Criou a consulta $confirmacao"));    
    }

    //Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
    
    $doc_telefonewhats = "5571997417190";
    $msg_wahstapp = "Olá $config_empresa".'\n\n'."$doc_nome agendou uma $id_job para $local_reserva - Data: $atendimento_dia_str ás: $atendimento_hora_str".'\n\n'."Telefone: $doc_telefone".'\n'."E-mail: $doc_email";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
    
    }
    //Fim Envio Whatsapp

    if($feitapor == 'Site'){
    echo   "<script>
    window.location.replace('agendar_ok.php?id=$token')
            </script>";
    exit();
    }else{
    echo   "<script>
    alert('Consulta Confirmada com Sucesso!')
    window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
        </script>";
        exit();
}

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

    }
//Fim Envio de Email


        }else{
            echo    "<script>
                window.location.replace('cancelar.php?typeerror=2&token=$token')
                </script>";
         exit();
        }

        $id = base64_encode("$confirmacao.$token");
        echo   "<script>
                window.location.replace('reserva.php?id=$id')
                </script>";

}else if($status_reserva == 'Alterada' || $status_reserva == 'Alterado'){

        $horario = '';
        $token = mysqli_real_escape_string($conn_msqli, $_POST['new_token']);
        $id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
        $atendimento_dia_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_anterior']);
        $atendimento_hora_anterior = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_anterior']);
        $local_reserva = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_local']);

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

    if(!isset($_POST['overbook_data']) && $atendimento_dia == $hoje && ($atendimento_horas - $atendimento_hora_intervalo) <= strtotime("$hoje_hora")){
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

            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'confirmacao' => $confirmacao, 'local_reserva' => 'ALL'));

            }

            $query = $conexao->prepare("INSERT INTO alteracoes (token, atendimento_dia, atendimento_hora, atendimento_dia_anterior, atendimento_hora_anterior, alt_status, id_job) VALUES (:token, :atendimento_dia, :atendimento_hora, :atendimento_dia_anterior, :atendimento_hora_anterior, 'Pendente', :id_job)");
            $query->execute(array('token' => $token, 'atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'atendimento_dia_anterior' => $atendimento_dia_anterior, 'atendimento_hora_anterior' => $atendimento_hora_anterior, 'id_job' => $id_job));

            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'local_reserva' => 'ALL'));


//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

$atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
$atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));

$doc_telefonewhats = "5571997417190";
$msg_wahstapp = "Olá $config_empresa".'\n\n'."$doc_nome solicitou uma alteração para $local_reserva - Data: $atendimento_dia_str ás: $atendimento_hora_str".'\n\n\n'."Para Aceitar clique abaixo:".'\n'."$site_atual/painel/reservas_solicitacao.php?alt_status=Aceita&token=$token".'\n\n'."Para Recusar clique abaixo:".'\n'."$site_atual/painel/reservas_solicitacao.php?alt_status=Recusada&token=$token";

$whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);

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
            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora_mais,'confirmacao' => $confirmacao, 'local_reserva' => $local_reserva));
            }
            $query = $conexao->prepare("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, confirmacao, local_reserva, quantidade) VALUES (:atendimento_dia, :atendimento_hora, :confirmacao, :local_reserva, '1')");
            $query->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora,'confirmacao' => $confirmacao, 'local_reserva' => $local_reserva));
            $query_2 = $conexao->prepare("UPDATE $tabela_reservas SET atendimento_dia = :atendimento_dia, atendimento_hora = :atendimento_hora, feitapor = :feitapor, token = :token, status_reserva = 'Confirmada', local_reserva = :local_reserva WHERE confirmacao = :confirmacao AND doc_email = :doc_email");
            $query_2->execute(array('atendimento_dia' => $atendimento_dia, 'atendimento_hora' => $atendimento_hora, 'confirmacao' => $confirmacao, 'feitapor' => $feitapor, 'token' => $token, 'doc_email' => $doc_email, 'local_reserva' => $local_reserva));
            if($feitapor != 'Site'){
            $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
            $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Alterou a consulta $confirmacao"));  
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

    $id = base64_encode("$confirmacao.$token");
    echo   "<script>
            window.location.replace('reserva.php?id=$id')
            </script>";

}else if($status_reserva == 'NoShow'){

    $result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :atendimento_dia AND atendimento_hora = :atendimento_hora AND confirmacao = :confirmacao AND doc_email = :doc_email");
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
            window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
                    </script>";
             exit();

        } catch (Exception $e) {

            echo "<script>
            window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
                    </script>";

        }

    }
//Fim Envio de Email

//Incio Envio Whatsapp
if($envio_whatsapp == 'ativado'){

    $doc_telefonewhats = "55$doc_telefone";
    $msg_wahstapp = "Olá $doc_nome, o seu Atendimento foi Finalizado com sucesso em $data_email!".'\n\n'."Não esqueça de nos avaliar em https://g.page/r/CY8KhBQj3vtrEB0/review!";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
    
    }
    //Fim Envio Whatsapp

        }else{
            echo "<script>
            window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
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
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
    
    }
    //Fim Envio Whatsapp

    echo "<script>
            alert('Sessão Finalizada com Sucesso')
            window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
                    </script>";
            exit();

        }else{
            echo "<script>
            alert('Erro ao Finalizar Sessao')
            window.location.replace('painel/reserva.php?confirmacao=$confirmacao')
                    </script>";
            exit();
        }



}else if($status_reserva == 'EnvioMensagem'){

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
    $msg_wahstapp = "Olá $doc_nome, tudo bem?".'\n\n'."Aqui vai a confirmação da sua $id_job para a Data: $atendimento_dia_str ás $atendimento_hora_str.".'\n\n\n'."Para Alterar seu Atendimento acesse: $site_atual/alterar.php?token=$token".'\n\n\n'."Para Cancelar seu Atendimento acesse: $site_atual/cancelar.php?token=$token&typeerror=0";
    
    $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
    
    }

}
//Fim Envio Whatsapp

$id = base64_encode("$confirmacao.$token");
    echo   "<script>
            window.location.replace('reserva.php?id=$id')
            </script>";

}