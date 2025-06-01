<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$historico_data = date('Y-m-d H:i:s');

$result_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$historico_quem = $select_check['nome'];
$historico_unico_usuario = $select_check['unico'];
}

if($id_job == 'editar_configuracoes' || $id_job == 'disponibilidade_fechar' || $id_job == 'disponibilidade_abrir'){
if(isset($_POST['dia_segunda'])){
    $dia_segunda = 1;
    } else {
    $dia_segunda = -1;
    }
    if(isset($_POST['dia_terca'])){
    $dia_terca = 2;
    } else {
    $dia_terca = -1;
        }
    if(isset($_POST['dia_quarta'])){
    $dia_quarta = 3;
    } else {
    $dia_quarta = -1;
    }
    if(isset($_POST['dia_quinta'])){
    $dia_quinta = 4;
    } else {
    $dia_quinta = -1;
    }
    if(isset($_POST['dia_sexta'])){
    $dia_sexta = 5;
    } else {
    $dia_sexta = -1;
    }
    if(isset($_POST['dia_sabado'])){
    $dia_sabado = 6;
    } else {
    $dia_sabado = -1;
    }
    if(isset($_POST['dia_domingo'])){
    $dia_domingo = 0;
    } else {
    $dia_domingo = -1;
    }
}

if($id_job == 'editar_configuracoes'){

    $config_empresa = mysqli_real_escape_string($conn_msqli, $_POST['config_empresa']);
    $config_email = mysqli_real_escape_string($conn_msqli, $_POST['config_email']);
    $config_telefone = mysqli_real_escape_string($conn_msqli, $_POST['config_telefone']);
    $config_cnpj = mysqli_real_escape_string($conn_msqli, $_POST['config_cnpj']);
    $config_endereco = mysqli_real_escape_string($conn_msqli, $_POST['config_endereco']);
    $msg_cancelamento = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_cancelamento']);
    $msg_confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_confirmacao']);
    $msg_finalizar = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_finalizar']);
    $atendimento_hora_comeco = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_comeco']);
    $atendimento_hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_fim']);
    $atendimento_hora_intervalo = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_intervalo']);
    $atendimento_dia_max = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_max']);
    $envio_whatsapp = mysqli_real_escape_string($conn_msqli, $_POST['envio_whatsapp']);
    $envio_email = mysqli_real_escape_string($conn_msqli, $_POST['envio_email']);

    if($dia_segunda == -1 && $dia_terca == -1 && $dia_quarta == -1 && $dia_quinta == -1 && $dia_sexta == -1 && $dia_sabado == -1 && $dia_domingo){
        echo "<script>
        alert('Pelo menos um dia precisa estar Selecionado')
        window.location.replace('configuracoes.php')
        </script>";
        exit(); 
    }

    $query = $conexao->prepare("UPDATE configuracoes SET config_empresa = :config_empresa, config_email = :config_email, config_telefone = :config_telefone, config_cnpj = :config_cnpj, config_endereco = :config_endereco, config_msg_cancelamento = :msg_cancelamento, config_msg_finalizar = :msg_finalizar, config_msg_confirmacao = :msg_confirmacao, atendimento_hora_comeco = :atendimento_hora_comeco, atendimento_hora_fim = :atendimento_hora_fim, atendimento_hora_intervalo = :atendimento_hora_intervalo, atendimento_dia_max = :atendimento_dia_max, config_dia_segunda = :dia_segunda, config_dia_terca = :dia_terca, config_dia_quarta = :dia_quarta, config_dia_quinta = :dia_quinta, config_dia_sexta = :dia_sexta, config_dia_sabado = :dia_sabado, config_dia_domingo = :dia_domingo, envio_whatsapp = :envio_whatsapp, envio_email = :envio_email WHERE id = '-2'");
    $query->execute(array('config_empresa' => $config_empresa, 'config_email' => $config_email, 'config_telefone' => $config_telefone, 'config_cnpj' => $config_cnpj, 'config_endereco' => $config_endereco, 'msg_cancelamento' => $msg_cancelamento, 'msg_finalizar' => $msg_finalizar, 'msg_confirmacao' => $msg_confirmacao, 'atendimento_hora_comeco' => $atendimento_hora_comeco, 'atendimento_hora_fim' => $atendimento_hora_fim, 'atendimento_hora_intervalo' => $atendimento_hora_intervalo, 'atendimento_dia_max' => $atendimento_dia_max, 'dia_segunda' => $dia_segunda, 'dia_terca' => $dia_terca, 'dia_quarta' => $dia_quarta, 'dia_quinta' => $dia_quinta, 'dia_sexta' => $dia_sexta, 'dia_sabado' => $dia_sabado, 'dia_domingo' => $dia_domingo, 'envio_whatsapp' => $envio_whatsapp, 'envio_email' => $envio_email));
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações'));   

    echo "<script>
    alert('Configurações Editadas com sucesso')
    window.location.replace('configuracoes.php')
    </script>";
    exit();

}else if($id_job == 'disponibilidade_fechar'){

    $fechar_inicio = mysqli_real_escape_string($conn_msqli, $_POST['fechar_inicio']);
    $fechar_fim = mysqli_real_escape_string($conn_msqli, $_POST['fechar_fim']);
    $hora_inicio = mysqli_real_escape_string($conn_msqli, $_POST['hora_inicio']);
    $hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['hora_fim']);

    $close = 0;
    $close_dias = $fechar_inicio;
    $fechar_inicio_str = strtotime("$fechar_inicio");
    $fechar_fim_str = strtotime("$fechar_fim");
    $reserva_close = ($fechar_fim_str - $fechar_inicio_str) / 86400;
    $close_hora = 0;
    $close_horas = $hora_inicio;
    $hora_inicio_str = strtotime("$hora_inicio");
    $hora_fim_str = strtotime("$hora_fim");
    $hora_close = ($hora_fim_str - $hora_inicio_str) / ($config_atendimento_hora_intervalo * 60);

while($close <= $reserva_close){

    if(( (date('w', strtotime("$close_dias")) == 1) && $dia_segunda == -1) || ( (date('w', strtotime("$close_dias")) == 2) && $dia_terca == -1) || ( (date('w', strtotime("$close_dias")) == 3) && $dia_quarta == -1) || ( (date('w', strtotime("$close_dias")) == 4) && $dia_quinta == -1) || ( (date('w', strtotime("$close_dias")) == 5) && $dia_sexta == -1) || ( (date('w', strtotime("$close_dias")) == 6) && $dia_sabado == -1) || ( (date('w', strtotime("$close_dias")) == 0) && $dia_domingo == -1)){
    }else{

    //Hora Inicio
    while($close_hora <= $hora_close){

    $query = $conexao->query("INSERT INTO disponibilidade (atendimento_dia, atendimento_hora) VALUES ('{$close_dias}', '{$close_horas}')");

    $close_hora++;
    $close_horas = date('H:i:s', strtotime("$close_horas") + ($config_atendimento_hora_intervalo * 60));
    }}
    //Hora Fim

    $close_hora = 0;
    $close_horas = $hora_inicio;
    $close++;
    $close_dias = date('Y-m-d', strtotime("$close_dias") + 86400);

}

$fechar_inicio = date('d/m/Y', strtotime($fechar_inicio));
$fechar_fim = date('d/m/Y', strtotime($fechar_fim));

    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Fechou disponibilidade entre as datas $fechar_inicio e $fechar_fim"));

echo "<script>
    alert('Disponibilidade entre os dias $fechar_inicio e $fechar_fim fechadas')
    window.location.replace('disponibilidade_fechar.php')
    </script>";

}else if($id_job == 'disponibilidade_abrir'){

    $fechar_inicio = mysqli_real_escape_string($conn_msqli, $_POST['fechar_inicio']);
    $fechar_fim = mysqli_real_escape_string($conn_msqli, $_POST['fechar_fim']);
    $hora_inicio = mysqli_real_escape_string($conn_msqli, $_POST['hora_inicio']);
    $hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['hora_fim']);

    $close = 0;
    $close_dias = $fechar_inicio;
    $fechar_inicio_str = strtotime("$fechar_inicio");
    $fechar_fim_str = strtotime("$fechar_fim");
    $reserva_close = ($fechar_fim_str - $fechar_inicio_str) / 86400;
    $close_hora = 0;
    $close_horas = $hora_inicio;
    $hora_inicio_str = strtotime("$hora_inicio");
    $hora_fim_str = strtotime("$hora_fim");
    $hora_close = ($hora_fim_str - $hora_inicio_str) / ($config_atendimento_hora_intervalo * 60);

while($close <= $reserva_close){

    if(( (date('w', strtotime("$close_dias")) == 1) && $dia_segunda == -1) || ( (date('w', strtotime("$close_dias")) == 2) && $dia_terca == -1) || ( (date('w', strtotime("$close_dias")) == 3) && $dia_quarta == -1) || ( (date('w', strtotime("$close_dias")) == 4) && $dia_quinta == -1) || ( (date('w', strtotime("$close_dias")) == 5) && $dia_sexta == -1) || ( (date('w', strtotime("$close_dias")) == 6) && $dia_sabado == -1) || ( (date('w', strtotime("$close_dias")) == 0) && $dia_domingo == -1)){
    }else{

    //Hora Inicio
    while($close_hora <= $hora_close){

    $query = $conexao->query("DELETE FROM disponibilidade WHERE atendimento_dia = '{$close_dias}' AND atendimento_hora = '{$close_horas}'");

    $close_hora++;
    $close_horas = date('H:i:s', strtotime("$close_horas") + ($config_atendimento_hora_intervalo * 60));
    }}
    //Hora Fim

    $close_hora = 0;
    $close_horas = $hora_inicio;
    $close++;
    $close_dias = date('Y-m-d', strtotime("$close_dias") + 86400);

}

$fechar_inicio = date('d/m/Y', strtotime($fechar_inicio));
$fechar_fim = date('d/m/Y', strtotime($fechar_fim));

$query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
$query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Abriu disponibilidade entre as datas $fechar_inicio e $fechar_fim"));

echo "<script>
    alert('Disponibilidade entre os dias $fechar_inicio e $fechar_fim abertas')
    window.location.replace('disponibilidade_fechar.php')
    </script>";

}else if($id_job == 'reservas_lancamentos'){

    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $lanc_produto = mysqli_real_escape_string($conn_msqli, $_POST['lanc_produto']);
    $lanc_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['lanc_quantidade']);
    $lanc_valor = mysqli_real_escape_string($conn_msqli, $_POST['lanc_valor']);
    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $lanc_data = date('Y-m-d H:i');

    if($lanc_produto == 'Cartão' || $lanc_produto == 'Dinheiro' || $lanc_produto == 'Transferencia' || $lanc_produto == 'Outros'){
        if($lanc_valor > 0){
        $lanc_produto = "Pagamento em $lanc_produto";
        $tipo = 'Pagamento';
        }else{
        $lanc_produto = "Pagamento em $lanc_produto [ Estornado ]";
        $tipo = 'Estorno';
        }
    $valor = (-1) * $lanc_valor;
    }else{
    $valor = $lanc_quantidade * $lanc_valor;
    $tipo = 'Produto';
    }

    $query = $conexao->prepare("INSERT INTO lancamentos_atendimento (doc_email, produto, quantidade, valor, quando, feitopor, tipo, doc_nome) VALUES ('{$doc_email}', :lanc_produto, :lanc_quantidade, :valor, '{$lanc_data}', '{$historico_quem}', '{$tipo}', '{$doc_nome}')");
    $query->execute(array('lanc_produto' => $lanc_produto, 'lanc_quantidade' => $lanc_quantidade, 'valor' => $valor));
    $lanc_valor = number_format($lanc_valor ,2,",",".");
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Lançou $lanc_quantiade $lanc_produto no valor de R$$lanc_valor na Confirmação $id_consulta"));

    echo "<script>
    alert('Produto Lançado com Sucesso na Consulta $id_consulta')
    window.location.replace('reserva.php?id_consulta=$id_consulta')
    </script>";

}else if($id_job == 'lancar_despesas'){

    $despesa_dia = mysqli_real_escape_string($conn_msqli, $_POST['despesa_dia']);
    $despesa_valor = mysqli_real_escape_string($conn_msqli, $_POST['despesa_valor']);
    $despesa_tipo = mysqli_real_escape_string($conn_msqli, $_POST['despesa_tipo']);
    $despesa_descricao = mysqli_real_escape_string($conn_msqli, $_POST['despesa_descricao']);



    $query = $conexao->prepare("INSERT INTO despesas (despesa_dia, despesa_valor, despesa_tipo, despesa_descricao, despesa_quem) VALUES (:despesa_dia, :despesa_valor, :despesa_tipo, :despesa_descricao, :despesa_quem)");
    $query->execute(array('despesa_dia' => $despesa_dia, 'despesa_valor' => $despesa_valor, 'despesa_tipo' => $despesa_tipo, 'despesa_descricao' => $despesa_descricao, 'despesa_quem' => $historico_quem));

    echo "<script>
    alert('Despesa Cadastrada com Sucesso')
    window.location.replace('despesas.php')
    </script>";
    exit();


}else if($id_job == 'lancar_custos'){

    $custo_valor = mysqli_real_escape_string($conn_msqli, $_POST['custo_valor']);
    $custo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['custo_tipo']);
    $custo_descricao = mysqli_real_escape_string($conn_msqli, $_POST['custo_descricao']);

    $query = $conexao->prepare("INSERT INTO custos (custo_valor, custo_tipo, custo_descricao, custo_quem) VALUES (:custo_valor, :custo_tipo, :custo_descricao, :custo_quem)");
    $query->execute(array('custo_valor' => $custo_valor, 'custo_tipo' => $custo_tipo, 'custo_descricao' => $custo_descricao, 'custo_quem' => $historico_quem));

    echo "<script>
    alert('Custo Cadastrado com Sucesso')
    window.location.replace('custos.php')
    </script>";
    exit();


}else if($id_job == 'editar_custos'){

    $custo_id = mysqli_real_escape_string($conn_msqli, $_POST['custo_id']);
    $custo_valor = mysqli_real_escape_string($conn_msqli, $_POST['custo_valor']);
    $custo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['custo_tipo']);
    $custo_descricao = mysqli_real_escape_string($conn_msqli, $_POST['custo_descricao']);

    $query = $conexao->prepare("UPDATE custos SET custo_valor = :custo_valor, custo_tipo = :custo_tipo, custo_descricao = :custo_descricao WHERE id = :custo_id");
    $query->execute(array('custo_valor' => $custo_valor, 'custo_tipo' => $custo_tipo, 'custo_descricao' => $custo_descricao, 'custo_id' => $custo_id));

    echo "<script>
    alert('Custo Editado com Sucesso')
    window.location.replace('custos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_tratamento'){

    $tratamento_descricao = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_descricao']);

    $query = $conexao->prepare("INSERT INTO tratamentos (tratamento, tratamento_quem) VALUES (:tratamento_descricao, :tratamento_quem)");
    $query->execute(array('tratamento_descricao' => $tratamento_descricao, 'tratamento_quem' => $historico_quem));

    echo "<script>
    alert('Tratamento Cadastrado com Sucesso')
    window.location.replace('tratamentos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_custo_tratamento'){

    $custo_id = mysqli_real_escape_string($conn_msqli, $_POST['custo_id']);
    $quantidade = mysqli_real_escape_string($conn_msqli, $_POST['quantidade']);
    $tratamento_id = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_id']);

    $query = $conexao->prepare("INSERT INTO custos_tratamentos (tratamento_id, custo_id, quantidade) VALUES (:tratamento_id, :custo_id, :quantidade)");
    $query->execute(array('tratamento_id' => $tratamento_id, 'custo_id' => $custo_id, 'quantidade' => $quantidade));

    echo "<script>
    alert('Custo Cadastrado com Sucesso')
    window.location.replace('tratamentos_editar.php?id=$tratamento_id')
    </script>";
    exit();


}else if($id_job == 'cadastro_contrato'){

    $procedimento_valor = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_valor']);
    $procedimento_dias = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_dias']);
    $procedimentos = trim(mysqli_real_escape_string($conn_msqli, $_POST['procedimentos']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);

    $query = $conexao->prepare("INSERT INTO contrato (email, assinado, assinado_data, assinado_empresa, assinado_empresa_data, procedimento, procedimento_dias, procedimento_valor, aditivo_valor, aditivo_procedimento, aditivo_status) VALUES (:email, 'Não', :ass_data, 'Sim', :ass_data, :procedimento, :procedimento_dias, :procedimento_valor, '-', '-', 'Não')");
    $query->execute(array('procedimento_valor' => $procedimento_valor, 'procedimento' => $procedimentos, 'procedimento_dias' => $procedimento_dias, 'email' => $email, 'ass_data' => date('Y-m-d H:i:s')));

    //Envio de Email	

    $data_email = date('d/m/Y \-\ H:i:s');

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
    $mail->addAddress("$email", "$nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "$config_empresa - Contrato";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Novo Contrato</b></legend>
    <br>
    Ola <b>$nome</b>, tudo bem?<br>
    <br>$config_empresa lhe enviou um novo <b><u>Contrato</u></b>. Va no seu Perfil/Acompanhamentos e acesse o seu Contrato para Assinar.<br>
    <br><b>Contrato enviado em $data_email</b>
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

    echo "<script>
    alert('Contrato Enviado com Sucesso')
    window.location.replace('reserva.php?email=$email')
    </script>";
    exit();


}else if($id_job == 'cadastro_aditivo'){

    $procedimento_valor = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_valor']);
    $procedimentos = trim(mysqli_real_escape_string($conn_msqli, $_POST['procedimentos']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);

    $query = $conexao->prepare("INSERT INTO contrato (email, assinado, assinado_data, assinado_empresa, assinado_empresa_data, procedimento, procedimento_valor, aditivo_valor, aditivo_procedimento, aditivo_status) VALUES (:email, 'Não', :ass_data, 'Sim', :ass_data, '-', '-', :procedimento, :procedimento_valor, 'Sim')");
    $query->execute(array('procedimento_valor' => $procedimento_valor, 'procedimento' => $procedimentos, 'email' => $email, 'ass_data' => date('Y-m-d H:i:s')));

    $query2 = $conexao->prepare("UPDATE contrato SET assinado = 'Não' WHERE email = :email");
    $query2->execute(array('email' => $email, 'confirmacao' => $confirmacao));

    //Envio de Email	

    $data_email = date('d/m/Y \-\ H:i:s');

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
    $mail->addAddress("$email", "$nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "$config_empresa - Aditivo Contratual";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Aditivo Contratual</b></legend>
    <br>
    Ola <b>$nome</b>, tudo bem?<br>
    <br>$config_empresa lhe enviou o <b><u>Aditivo Contratual</u></b>. Va no seu Perfil/Acompanhamentos e acesse o seu Contrato para Assinar.<br>
    <br><b>Aditivo do Contrato enviado em $data_email</b>
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

    echo "<script>
    alert('Aditivo Contrato Enviado com Sucesso')
    window.location.replace('reserva.php?email=$email')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_enviar'){

    $tratamento = mysqli_real_escape_string($conn_msqli, $_POST['tratamento']);
    $tratamento_sessao = trim(mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']));
    $tratamento_data = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_data']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $comentario = mysqli_real_escape_string($conn_msqli, $_POST['comentario']);

    $query = $conexao->prepare("INSERT INTO tratamento (email, plano_descricao, plano_data, sessao_atual, sessao_total, sessao_status, token, comentario) VALUES (:email, :plano_descricao, :plano_data, 0, :sessao_total, 'Em Andamento', :token, :comentario)");
    $query->execute(array('email' => $email, 'plano_descricao' => $tratamento, 'plano_data' => $tratamento_data, 'sessao_total' => $tratamento_sessao, 'token' => $token, 'comentario' => $comentario));

    echo "<script>
    alert('Tratamento Enviado com Sucesso')
    window.location.replace('reserva.php?id_consulta=$id_consulta')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_cadastrar'){

    $tratamento_sessao = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']);
    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $comentario = mysqli_real_escape_string($conn_msqli, $_POST['comentario']);
    $tratamento_data = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_data']);
    $tratamento = mysqli_real_escape_string($conn_msqli, $_POST['tratamento']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_atual = :tratamento_sessao WHERE id = :id AND email = :email AND token = :token");
    $query->execute(array('email' => $email, 'id' => $id, 'tratamento_sessao' => $tratamento_sessao, 'token' => $token));

    $query = $conexao->prepare("INSERT INTO tratamento (email, plano_descricao, plano_data, sessao_atual, sessao_total, sessao_status, token, comentario) VALUES (:email, :plano_descricao, :plano_data, :sessao_total, :sessao_total, 'Em Andamento', :token, :comentario)");
    $query->execute(array('email' => $email, 'plano_descricao' => $tratamento, 'plano_data' => $tratamento_data, 'sessao_total' => 0, 'token' => $token, 'comentario' => $comentario));

    echo "<script>
    alert('Sessao $tratamento_sessao Cadastrada com Sucesso')
    window.location.replace('reserva.php?id_consulta=$id_consulta')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_finalizar'){

    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_status = 'Finalizada' WHERE id = :id AND email = :email");
    $query->execute(array('email' => $email, 'id' => $id));

    echo "<script>
    alert('Sessao Finalizada com Sucesso')
    window.location.replace('reserva.php?id_consulta=$id_consulta')
    </script>";
    exit();


}else if($id_job == 'formulario_enviar'){

    $email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);

    //Envio de Email	

    $data_email = date('d/m/Y \-\ H:i:s');

    if($envio_email == 'ativado'){

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
    $mail->SMTPSecure = "$mail_SMTPSecure";
    $mail->Port = "$mail_Port";

    $mail->setFrom("$config_email", "$config_empresa");
    $mail->addAddress("$email", "$nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "Formulario Anamnese - $config_empresa";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Formulario Anamnese</b></legend>
    <br>
    Ola <b>$nome</b>, tudo bem?<br>
    $config_empresa lhe enviou um <b><u>Formulario de Anamnese</u></b>, preencha-o assim que possivel.<br>
    <p>Preencha o formulario, $link_formulario</p>
    <b>Formulario enviado em $data_email</b>
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

    echo "<script>
    alert('Formulario Enviado com Sucesso')
    window.location.replace('cadastro.php?email=$email')
    </script>";
    exit();


}else if($id_job == 'arquivos'){

    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $arquivo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['arquivo_tipo']);
    $token_profile = mysqli_real_escape_string($conn_msqli, $_POST['token_profile']);
    $arquivo = mysqli_real_escape_string($conn_msqli, $_POST['arquivo']).'.pdf';
    
    $arquivos = $_FILES['arquivos'];
    $dirAtual = '../arquivos/'.$token_profile.'/'.$arquivo_tipo.'/';

    if($arquivos['type'] != 'application/pdf'){
        echo "<script>
        alert('Selecione apenas arquivos tipo PDF');
        window.location.replace('reserva.php?id_consulta=$id_consulta');
        </script>";
        exit();
    }

    // Criar diretórios intermediários, se não existirem
    if (!is_dir($dirAtual)) {
        mkdir($dirAtual, 0777, true); // o 'true' aqui é essencial!
    }

    $caminhoFinal = $dirAtual . $arquivo;

    if (move_uploaded_file($arquivos['tmp_name'], $caminhoFinal)) {
        // Sucesso
    } else {
        echo "Erro ao mover o arquivo para $caminhoFinal";
    }


    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cadastrou um novo Arquivo $arquivo na Consulta $id_consulta"));

    echo "<script>
    alert('Arquivo Cadastrado com Sucesso $id_consulta')
    window.location.replace('reserva.php?id_consulta=$id_consulta')
    </script>";
    exit();

}else if($id_job == 'EnvioConfirmacao'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
    $atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    
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
        <legend>$pdf_corpo_01</legend>
        <br>
        $pdf_corpo_00 <b>$doc_nome</b>, $pdf_corpo_02 $pdf_corpo_03.<br>
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
        $msg_whatsapp = "Olá $doc_nome, tudo bem?\n\n".
                "Aqui vai a confirmação da sua Consulta para a Data: $atendimento_dia_str às $atendimento_hora_str.\n\n\n".
                "Para Alterar seu Atendimento acesse: $site_atual/alterar.php?token=$token\n\n\n".
                "Para Cancelar seu Atendimento acesse: $site_atual/cancelar.php?token=$token&typeerror=0";
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
        
        }
    
    }
    //Fim Envio Whatsapp
    
    $id = base64_encode("$confirmacao.$token");
        echo   "<script>
                alert('Confirmação Enviada com Sucesso')
                window.location.replace('reserva.php?id_consulta=$id_consulta')
                </script>";
    
}else if($id_job == 'EnvioLembrete'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
    $atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
    
    //Envio de Email	
    
    $data_email = date('d/m/Y \-\ H:i:s');
    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
    
    
    if(isset($_POST['email'])){
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
      <p>Data: <b>$atendimento_dia_str</b> ás <b>$atendimento_hora_str</b>h</p>
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
    }
    //Fim Envio de Email
    
    if(isset($_POST['whatsapp'])){
    //Incio Envio Whatsapp
    if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = "55$doc_telefone";
        $msg_wahstapp = "Oi $doc_nome, tudo bem? 😊 \n\n" .
                "Passando para confirmar seu atendimento dia $atendimento_dia_str às $atendimento_hora_str e para garantir que tudo esteja pronto para te receber com todo o cuidado preciso que me dê um retorno confirmando até as 17h, combinado?" .
                "\n\n Caso não haja confirmação até esse horário, precisaremos liberar o horário para outro paciente. Qualquer dúvida, estou à disposição! 🤍🤍";
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);
        
        }
    
    }
    //Fim Envio Whatsapp
    
    $id = base64_encode("$confirmacao.$token");
        echo   "<script>
                alert('Lembrete Enviado com Sucesso')
                window.location.replace('reserva.php?id_consulta=$id_consulta')
                </script>";
    
}else if($id_job == 'cadastro_novo'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']));
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));
    $origem = mysqli_real_escape_string($conn_msqli, $_POST['origem']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['doc_nascimento']);

    $senha = '123456';
    $crip_senha = md5($senha);
    $token = md5(date("YmdHismm"));

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
        echo "<script>
        alert('CPF Invalido')
        window.location.replace('cadastro_registro.php')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email OR unico = :cpf");
    $query->execute(array('email' => $doc_email, 'cpf' => $doc_cpf));
    $row_check = $query->rowCount();
    
    if($row_check == 1){
        echo "<script>
        alert('Email e/ou CPF ja Cadastrado!')
        window.location.replace('cadastro_registro.php')
        </script>";
        exit();
    }

    $query = $conexao->prepare("INSERT INTO painel_users (email, tipo, senha, nome, telefone, nascimento, unico, token, codigo, tentativas, aut_painel, origem) VALUES (:email, 'Paciente', :senha, :nome, :telefone, :nascimento, :cpf, :token, '0', '0', '1', :origem)");
    $query->execute(array('email' => $doc_email, 'nome' => $doc_nome, 'cpf' => $doc_cpf, 'token' => $token, 'telefone' => $doc_telefone, 'nascimento' => $nascimento, 'senha' => $crip_senha, 'origem' => $origem));

    echo "<script>
    alert('Cliente Cadastrado Sucesso!')
    window.location.replace('cadastro.php?email=$doc_email')
    </script>";

}else if($id_job == 'cadastro_editar'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));
    $doc_rg = mysqli_real_escape_string($conn_msqli, $_POST['doc_rg']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['nascimento']);
    $profissao = mysqli_real_escape_string($conn_msqli, $_POST['profissao']);
    $endereco_cep = mysqli_real_escape_string($conn_msqli, $_POST['endereco_cep']);
    $endereco_rua = mysqli_real_escape_string($conn_msqli, $_POST['endereco_rua']);
    $endereco_n = mysqli_real_escape_string($conn_msqli, $_POST['endereco_n']);
    $endereco_bairro = mysqli_real_escape_string($conn_msqli, $_POST['endereco_bairro']);
    $endereco_cidade = mysqli_real_escape_string($conn_msqli, $_POST['endereco_cidade']);
    $endereco_uf = mysqli_real_escape_string($conn_msqli, $_POST['endereco_uf']);
    $feitopor = mysqli_real_escape_string($conn_msqli, $_POST['feitopor']);

    $query = $conexao->prepare("UPDATE painel_users SET nome = :doc_nome, telefone = :doc_telefone, rg = :doc_rg, nascimento = :nascimento, profissao = :profissao, cep = :cep, rua = :rua, numero = :numero, cidade = :cidade, bairro = :bairro, estado = :estado WHERE email = :email");
    $query->execute(array('email' => $doc_email, 'doc_nome' => $doc_nome, 'doc_telefone' => $doc_telefone, 'doc_rg' => $doc_rg, 'nascimento' => $nascimento, 'profissao' => $profissao, 'cep' => $endereco_cep, 'rua' => $endereco_rua, 'numero' => $endereco_n, 'cidade' => $endereco_cidade, 'bairro' => $endereco_bairro, 'estado' => $endereco_uf));

    $query = $conexao->prepare("UPDATE consultas SET doc_nome = :doc_nome, doc_telefone = :doc_telefone WHERE doc_email = :email");
    $query->execute(array('email' => $doc_email, 'doc_nome' => $doc_nome, 'doc_telefone' => $doc_telefone));

    if($feitopor == 'Paciente'){
        echo "<script>
        alert('Cadastro Alterado com Sucesso')
        window.location.replace('profile.php')
        </script>";
    }else{
        echo "<script>
        alert('Cadastro Alterado com Sucesso')
        window.location.replace('cadastro.php?email=$doc_email')
        </script>";
    }
    

}else if($id_job == 'cadastro_editar_senha'){

    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $senha_antes = mysqli_real_escape_string($conn_msqli, $_POST['senha_antes']);
    $senha_nova = mysqli_real_escape_string($conn_msqli, $_POST['senha_nova']);
    $senha_nova_conf = mysqli_real_escape_string($conn_msqli, $_POST['senha_nova_conf']);

    if($senha_nova != $senha_nova_conf){
        $_SESSION['error_reserva'] = 'Senhas diferentes!';
        header("Location: profile.php?id_job=Senha");
        exit();
    }

    $crip_senha = md5($senha_antes);
    $crip_senha_nova = md5($senha_nova);
    
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email AND senha = :senha");
    $query->execute(array('email' => $doc_email, 'senha' => $crip_senha));
    $row = $query->rowCount();
    
    if($row == 1){

    $query = $conexao->prepare("UPDATE painel_users SET senha = :senha WHERE email = :email");
    $query->execute(array('email' => $doc_email, 'senha' => $crip_senha_nova));

    echo "<script>
    alert('Senha Alterada com Sucesso')
    window.location.replace('profile.php?id_job=Senha')
    </script>";

    }else{
        $_SESSION['error_reserva'] = 'Senha antiga errada!';
        header("Location: profile.php?id_job=Senha");
        exit();
    }    

}else if($id_job == 'lancar_produto'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_minimo = mysqli_real_escape_string($conn_msqli, $_POST['produto_minimo']);
    $produto_unidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_unidade']);

    $query = $conexao->prepare("INSERT INTO estoque_item (produto, minimo, unidade) VALUES (:produto, :minimo, :unidade)");
    $query->execute(array('produto' => $produto, 'minimo' => $produto_minimo, 'unidade' => $produto_unidade));

    echo "<script>
    alert('Produto Cadastrado com Sucesso')
    window.location.replace('estoque_produtos.php')
    </script>";
    exit();


}else if($id_job == 'editar_produto'){

    $produto_id = mysqli_real_escape_string($conn_msqli, $_POST['produto_id']);
    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_minimo = mysqli_real_escape_string($conn_msqli, $_POST['produto_minimo']);
    $produto_unidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_unidade']);

    $query = $conexao->prepare("UPDATE estoque_item SET produto = :produto, minimo = :minimo, unidade = :unidade WHERE id = :produto_id");
    $query->execute(array('produto' => $produto, 'minimo' => $produto_minimo, 'unidade' => $produto_unidade, 'produto_id' => $produto_id));

    echo "<script>
    alert('Produto Editado com Sucesso')
    window.location.replace('estoque_produtos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_entrada'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_quantidade']);

    $query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade) VALUES (:produto, :tipo, :quantidade)");
    $query->execute(array('produto' => $produto, 'tipo' => 'Entrada', 'quantidade' => $produto_quantidade));

    echo "<script>
    alert('Entrada Cadastrada com Sucesso')
    window.location.replace('estoque_entradas.php')
    </script>";
    exit();


}else if($id_job == 'lancar_saida'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_quantidade']);

    $produto_quantidade = $produto_quantidade * -1;

    $query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade) VALUES (:produto, :tipo, :quantidade)");
    $query->execute(array('produto' => $produto, 'tipo' => 'Saida', 'quantidade' => $produto_quantidade));

    echo "<script>
    alert('Saida Cadastrada com Sucesso')
    window.location.replace('estoque_saidas.php')
    </script>";
    exit();


}else if($id_job == 'adicionar_link'){

$descricao = mysqli_real_escape_string($conn_msqli, $_POST['video_titulo']);
$link_youtube = mysqli_real_escape_string($conn_msqli, $_POST['video_link']);

// Verifica se é um link do YouTube
if (preg_match('/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/', $link_youtube)) {

    $query = $conexao->prepare("INSERT INTO videos (link_youtube, descricao) VALUES (:link_youtube, :descricao)");
    $query->execute(array('link_youtube' => $link_youtube, 'descricao' => $descricao));

    echo "<script>
        alert('Vídeo cadastrado com sucesso');
        window.location.replace('videos.php?id_job=Adicionar');
    </script>";
    exit();

} else {
    echo "<script>
        alert('Link inválido. Por favor insira um link do YouTube válido.');
        window.history.back();
    </script>";
    exit();
}


}else if($id_job == 'editar_link'){

    $video_id = mysqli_real_escape_string($conn_msqli, $_POST['video_id']);
    $descricao = mysqli_real_escape_string($conn_msqli, $_POST['video_titulo']);
    $link_youtube = mysqli_real_escape_string($conn_msqli, $_POST['video_link']);

    // Verifica se é um link do YouTube
    if (preg_match('/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/', $link_youtube)) {

    $query = $conexao->prepare("UPDATE videos SET link_youtube = :link_youtube, descricao = :descricao WHERE id = :video_id");
    $query->execute(array('link_youtube' => $link_youtube, 'descricao' => $descricao, 'video_id' => $video_id));

    echo "<script>
        alert('Vídeo editado com sucesso');
        window.location.replace('videos.php?id_job=Adicionar');
    </script>";
    exit();

    } else {
    echo "<script>
        alert('Link inválido. Por favor insira um link do YouTube válido.');
        window.history.back();
    </script>";
    exit();
    }


}