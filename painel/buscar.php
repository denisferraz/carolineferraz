<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/style.css'>


</head>
<body>
<?php

    $palavra = mysqli_real_escape_string($conn_msqli, $_POST['busca']);
    $historico_inicio = mysqli_real_escape_string($conn_msqli, $_POST['historico_inicio']);
    $historico_fim = mysqli_real_escape_string($conn_msqli, $_POST['historico_fim']);

    if($historico_inicio > $historico_fim){
        echo "<script>
        alert('Data Inicio Maior do que a Data Fim')
        window.location.replace('historico.php')
        </script>";
        exit();
    }

    $historico_inicio_str = strtotime("$historico_inicio") / 86400;
    $historico_fim_str = strtotime("$historico_fim") / 86400;
    if(($historico_fim_str - $historico_inicio_str) > 30){
        echo "<script>
        alert('Periodo maximo de 30 dias')
        window.location.replace('historico.php')
        </script>";
        exit();
    }
    
    $historico_fim = date('Y-m-d', strtotime("$historico_fim") + 86400);

    $query_historico = $conexao->prepare("SELECT * FROM $tabela_historico WHERE (oque LIKE :palavra OR quem LIKE :palavra) AND quando >= '{$historico_inicio}' AND quando <= '{$historico_fim}' ORDER BY id DESC");
    $query_historico->execute(array('palavra' => "%$palavra%"));
    $historico_qtd = $query_historico->rowCount();

    if($palavra == ''){
        $palavra = 'Todos';
    }
?>

<fieldset>
<?php
if($historico_qtd == 0){
    ?>
<legend>Não existe nenhum historico em nome de <?php echo $palavra ?></legend>
    <?php
}else{
    ?>
<legend>Historico de [ <?php echo $palavra ?> ]</legend>
<?php
}
while($select_historico = $query_historico->fetch(PDO::FETCH_ASSOC)){
    $quem = $select_historico['quem'];
    $quando = $select_historico['quando'];
    $oque = $select_historico['oque'];
    ?>


<?php echo $quem ?>: <?php echo $oque ?><br>
<small>no dia <b><?php echo date('d/m/Y', strtotime($quando)) ?></b> as <b><?php echo date('H:i:s\h', strtotime($quando)) ?></b></small>
<br>
<br>

<?php
}
?>
</fieldset><br>

</body>
</html>
<?php
}
?>