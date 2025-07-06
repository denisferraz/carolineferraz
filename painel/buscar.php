<?php

ini_set('display_errors', 0 );
error_reporting(0);

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
body {
  margin-top: 30px;
  margin-bottom: 200px;
}
    </style>
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

    $query_historico = $conexao->prepare("SELECT * FROM historico_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND (oque LIKE :palavra OR quem LIKE :palavra) AND quando >= '{$historico_inicio}' AND quando <= '{$historico_fim}' ORDER BY id DESC");
    $query_historico->execute(array('palavra' => "%$palavra%"));
    $historico_qtd = $query_historico->rowCount();

    if($palavra == ''){
        $palavra = 'Todos';
    }
?>

<fieldset data-step="1">
<?php
if($historico_qtd == 0){
    ?>
<legend><h2>Não existe nenhum historico em nome de <?php echo $palavra ?></h2></legend>
    <?php
}else{
    ?>
<legend><h2>Historico de [ <?php echo $palavra ?> ] <i class="bi bi-question-square-fill"onclick="ajudaHistoricoVer()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<?php
}
while($select_historico = $query_historico->fetch(PDO::FETCH_ASSOC)){
    $quem = $select_historico['quem'];
    $quando = $select_historico['quando'];
    $oque = $select_historico['oque'];
    ?>

<div data-step="2">
<?php echo $quem ?>: <?php echo $oque ?><br>
<small>no dia <b><?php echo date('d/m/Y', strtotime($quando)) ?></b> as <b><?php echo date('H:i:s\h', strtotime($quando)) ?></b></small>
</div>
<br>
<br>

<?php
}
?>
</fieldset><br>

</body>
</html>
