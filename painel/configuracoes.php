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

$dia_segunda = $config_dia_segunda; //1
$dia_terca = $config_dia_terca; //2
$dia_quarta = $config_dia_quarta; //3
$dia_quinta = $config_dia_quinta; //4
$dia_sexta = $config_dia_sexta; //5
$dia_sabado = $config_dia_sabado; //6
$dia_domingo = $config_dia_domingo; //0

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Configurações</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php

$query = $conexao->query("SELECT * FROM configuracoes WHERE id = '{$tabela_configuracoes}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$atendimento_hora_comeco = $select['atendimento_hora_comeco'];
$atendimento_hora_fim = $select['atendimento_hora_fim'];
$atendimento_hora_intervalo = $select['atendimento_hora_intervalo'];
?>

<form class="form" action="acao.php" method="POST">
<div class="card">
<div class="card-top">
                <h2 class="title-cadastro">Edite abaixo as Configurações</h2>
            </div>
<div class="card-group">
    <label>Nome Empresa</label>
    <input type="text" minlength="5" maxlength="30" name="config_empresa" value="<?php echo $select['config_empresa'] ?>" required>
    <label>Email Empresa</label>
    <input type="text" minlength="10" maxlength="35" name="config_email" value="<?php echo $select['config_email'] ?>" required>
    <label>Telefone Empresa</label>
    <input type="text" minlength="8" maxlength="18" name="config_telefone" value="<?php echo $select['config_telefone'] ?>" required>
    <label>CNPJ Empresa</label>
    <input type="text" minlength="13" maxlength="18" name="config_cnpj" value="<?php echo $select['config_cnpj'] ?>" required>
    <label>Endereco Empresa</label>
    <input type="text" minlength="10" maxlength="100" name="config_endereco" value="<?php echo $select['config_endereco'] ?>" required>
    <label>Limite Diario</label>
    <input type="number" min="1" max="99999" name="config_limitedia" value="<?php echo $select['config_limitedia'] ?>" required>
    <label>Mensagem Confirmação</label>
    <textarea name="config_msg_confirmacao" rows="5" cols="43" required><?php echo $select['config_msg_confirmacao'] ?></textarea><br><br>
    <label>Mensagem Cancelamento</label>
    <textarea name="config_msg_cancelamento" rows="5" cols="43" required><?php echo $select['config_msg_cancelamento'] ?></textarea><br><br>
    <label>Mensagem Finalização</label>
    <textarea name="config_msg_finalizar" rows="5" cols="43" required><?php echo $select['config_msg_finalizar'] ?></textarea><br><br>
    <label>Hora Inicial de Atendimento</label>
    <input type="time" name="atendimento_hora_comeco" value="<?php echo date('H:i', strtotime("$atendimento_hora_comeco")) ?>" required>
    <label>Hora Final de Atendimento</label>
    <input type="time" name="atendimento_hora_fim" value="<?php echo date('H:i', strtotime("$atendimento_hora_fim")) ?>" required>
    <label>Intervalo entre Atendimentos (em minutos)</label>
    <input type="number" min="1" max="999" name="atendimento_hora_intervalo" value="<?php echo $atendimento_hora_intervalo ?>" required>
    <label>Data Maxima de Agendamento</label>
    <input type="date" name="atendimento_dia_max" value="<?php echo $select['atendimento_dia_max'] ?>" required>
    <br><label>Dias da Semana</label><br>
    <input id="dia_segunda" type="checkbox" name=dia_segunda <?php if($dia_segunda == 1){?>checked<?php } ?>>
    <label for="dia_segunda">Segunda-Feira</label>
    <br>
    <input id="dia_terca" type="checkbox" name=dia_terca <?php if($dia_terca == 2){?>checked<?php } ?>>
    <label for="dia_terca">Terça-Feira</label>
    <br>
    <input id="dia_quarta" type="checkbox" name=dia_quarta <?php if($dia_quarta == 3){?>checked<?php } ?>>
    <label for="dia_quarta">Quarta-Feira</label>
    <br>
    <input id="dia_quinta" type="checkbox" name=dia_quinta <?php if($dia_quinta == 4){?>checked<?php } ?>>
    <label for="dia_quinta">Quinta-Feira</label>
    <br>
    <input id="dia_sexta" type="checkbox" name=dia_sexta <?php if($dia_sexta == 5){?>checked<?php } ?>>
    <label for="dia_sexta">Sexta-Feira</label>
    <br>
    <input id="dia_sabado" type="checkbox" name=dia_sabado <?php if($dia_sabado == 6){?>checked<?php } ?>>
    <label for="dia_sabado">Sabado</label>
    <br>
    <input id="dia_domingo" type="checkbox" name=dia_domingo <?php if($dia_domingo == 0){?>checked<?php } ?>>
    <label for="dia_domingo">Domingo</label>
    <br>
    <input type="hidden" name="id_job" value="editar_configuracoes">
    <div class="card-group btn"><button type="submit">Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>

<?php
}
}
?>