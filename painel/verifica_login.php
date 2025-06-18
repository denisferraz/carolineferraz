<?php

ini_set('display_errors', 0 );
error_reporting(0);

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

session_start();

if(!$_SESSION['email']){
    header('Location: ../index.php');
    exit();
}

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE token = '{$_SESSION['token']}' AND email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'colorido'; // padrão é colorido

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

foreach ($conexao->query("SELECT * FROM configuracoes WHERE token_emp = '{$_SESSION['token_emp']}'") as $row) {
    $config_empresa = $row['config_empresa'];
    $config_email = $row['config_email'];
    $config_telefone = $row['config_telefone'];
    $config_cnpj = $row['config_cnpj'];
    $config_limitedia = $row['config_limitedia'];
    $config_limitepax = $row['config_limitepax'];
    $config_endereco = $row['config_endereco'];
    $config_msg_confirmacao = $row['config_msg_confirmacao'];
    $config_msg_cancelamento = $row['config_msg_cancelamento'];
    $config_msg_finalizar = $row['config_msg_finalizar'];
    $config_msg_lembrete = $row['config_msg_lembrete'];
    $config_msg_aniversario = $row['config_msg_aniversario'];
    $config_atendimento_hora_comeco = $row['atendimento_hora_comeco'];
    $config_atendimento_hora_fim = $row['atendimento_hora_fim'];
    $config_atendimento_hora_intervalo = $row['atendimento_hora_intervalo'];
    $config_atendimento_dia_max = $row['atendimento_dia_max'];
    $config_dia_segunda = $row['config_dia_segunda'];
    $config_dia_terca = $row['config_dia_terca'];
    $config_dia_quarta = $row['config_dia_quarta'];
    $config_dia_quinta = $row['config_dia_quinta'];
    $config_dia_sexta = $row['config_dia_sexta'];
    $config_dia_sabado = $row['config_dia_sabado'];
    $config_dia_domingo = $row['config_dia_domingo'];
    $envio_whatsapp = $row['envio_whatsapp'];
    $envio_email = $row['envio_email'];
    $is_segunda = $row['is_segunda'];
    $is_terca = $row['is_terca'];
    $is_quarta = $row['is_quarta'];
    $is_quinta = $row['is_quinta'];
    $is_sexta = $row['is_sexta'];
    $is_sabado = $row['is_sabado'];
    $is_domingo = $row['is_domingo'];
    $lembrete_auto_time = $row['lembrete_auto_time'];
}