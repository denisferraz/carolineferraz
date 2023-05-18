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

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Consultas no Sistema</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<fieldset>
<?php
    $palavra = mysqli_real_escape_string($conn_msqli, $_POST['busca']);
    $busca_inicio = mysqli_real_escape_string($conn_msqli, $_POST['busca_inicio']);
    $busca_fim = mysqli_real_escape_string($conn_msqli, $_POST['busca_fim']);

    if($busca_inicio > $busca_fim){
        echo "<script>
        alert('Data Inicio Maior do que a Data Fim')
        window.location.replace('reservas_editar.php')
        </script>";
        exit();
    }

    $busca_inicio_str = strtotime("$busca_inicio") / 86400;
    $busca_fim_str = strtotime("$busca_fim") / 86400;
    if(($busca_fim_str - $busca_inicio_str) > 30){
        echo "<script>
        alert('Periodo maximo de 30 dias')
        window.location.replace('historico.php')
        </script>";
        exit();
    }

    $busca_fim = date('Y-m-d', strtotime("$busca_fim") + 86400);

    if($palavra == ''){
    $palavra = 'Todos';
    $query_select = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim ORDER BY atendimento_dia ASC");
    $query_select->execute(array('busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }else{
    $query_select = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim AND (doc_nome LIKE :palavra OR doc_email LIKE :palavra OR confirmacao LIKE :palavra) ORDER BY atendimento_dia ASC");
    $query_select->execute(array('palavra' => "%$palavra%", 'busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }
    $select_qtd = $query_select->rowCount();

    if($select_qtd == 0){
?> <legend>Nenhuma consulta encontrada com o filtro [ <?php echo $palavra ?> ]</legend> <?php
    }else{
?>  
<legend><h2 class="title-cadastro">Veja abaixo todas as consultas com o filtro [ <?php echo $palavra ?> ]</h2></legend>
<table widht="1200" border="1px">
<tr>
    <td width="17%" align="center">Confirmação</td>
    <td width="35%" align="center">Nome [ E-mail ]</td>
    <td width="15%" align="center">Entrada</td>
    <td width="15%" align="center">Horário</td>
    <td width="15%" align="center">Status</td>
    <td width="20%" align="center">Editar</td>
    <td width="20%" align="center">Cancelar</td>
</tr>
<?php
    while($select = $query_select->fetch(PDO::FETCH_ASSOC)){
    $status_reserva = $select['status_reserva'];
    $confirmacao = $select['confirmacao'];
    $doc_nome = $select['doc_nome'];
    $doc_email = $select['doc_email'];
    $atendimento_dia = $select['atendimento_dia'];
    $atendimento_dia = strtotime("$atendimento_dia");
    $atendimento_hora = $select['atendimento_hora'];
    $atendimento_hora = strtotime("$atendimento_hora");
    $id = $select['id'];
    ?>    
    <tr>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $confirmacao ?></button></a></td>
    <td><?php echo $doc_nome ?><br><?php echo $doc_email ?></td>
    <td align="center"><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td align="center"><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><?php echo $status_reserva ?></td>
    <?php if($status_reserva == 'Cancelada' || $status_reserva == 'Finalizada'){  ?>
    <td align="center">-</td>
    <td align="center">-</td>
    <?php }else{  ?>
    <td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></td>
    <?php if($status_reserva == 'Checkedin' || $status_reserva == 'NoShow'){  ?>
    <td align="center">-</td>
    <?php }else{ ?>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button>Cancelar</button></a></td>
    <?php }}  ?>
    </tr>
    <?php
    }}
    ?>
</table>
</fieldset>
</body>
</html>

<?php
}
?>