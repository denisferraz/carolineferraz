<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));
$confirmacao = gerarConfirmacao();


$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

if($id_job == 'Cadastro'){

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query_check2 = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$email}'");
while($select_check2 = $query_check2->fetch(PDO::FETCH_ASSOC)){
    $nome = $select_check2['nome'];
    $telefone = $select_check2['telefone'];
    $cpf = $select_check2['unico'];
}

}else{
    $email = '';
    $nome = '';
    $telefone = '';
    $cpf = '';
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Consulta</title>

    <link rel="stylesheet" href="css/style.css">
    <script>
    function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i)
    if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
    }
    }   
    </script>
</head>
<body>

    <form class="form" action="../reservas_php.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Cadastre abaixo uma nova Consulta</h2>
            </div>

            <div class="card-group">
            <label>Dia do Atendimento</label>
            <input min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
            <br>
            <label>Atendimento Hora</label>
            <input min="01:00" max="23:00" type="time" name="atendimento_hora" required>
            <label>Seu Nome</label>
            <input minlength="5" maxlength="30" type="text" name="doc_nome" value="<?php echo $nome ?>" placeholder="Nome e Sobrenome" required>
            <label>Seu CPF</label>
            <input class="form-control" minlength="11" maxlength="14" type="text" name="doc_cpf" value="<?php echo $cpf ?>" placeholder="000.000.000-00" OnKeyPress="formatar('###.###.###-##', this)" required>
            <label>Seu E-mail</label>
            <input minlength="10" maxlength="35" type="email" name="doc_email" value="<?php echo $email ?>" placeholder="exemplo@exemplo.com" required>
            <label>Telefone</label>
            <input minlength="11" maxlength="18" type="text" name="doc_telefone" value="<?php echo $telefone ?>" placeholder="(00)00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>
            <label><b>Tipo Consulta: 
                <select name="id_job">
                <option value="Avaliação Capilar">Avaliação Capilar</option>
                <option value="Consulta Capilar">Consulta Capilar</option>
                <option value="Consulta Online">Consulta Online</option>
                <option value="Nova Sessão">Nova Sessão</option>
                </select></b></label><br>
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="status_reserva" value="Confirmado">
            <input type="hidden" name="feitapor" value="Painel">
            <br>
            <input id="overbook" type="checkbox" name="overbook">
            <label for="overbook">Forçar Overbook</label>
            <br>
            <input id="overbook_data" type="checkbox" name="overbook_data">
            <label for="overbook_data">Forçar Data/Horario</label>
            <br>
            <div class="card-group btn"><button type="submit">Confirmar Consulta</button></div>

            </div>
        </div>
    </form>

</body>
</html>

<?php } ?>