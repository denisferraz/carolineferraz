<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d'); // hoje no formato padrão
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultas no Sistema</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>
<fieldset>
<?php

    $query_select = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY atendimento_dia DESC");
    $query_select->execute(array('doc_email' => $_SESSION['email']));    

    $select_qtd = $query_select->rowCount();

    if($select_qtd == 0){
?>
    <legend>Nenhuma consulta encontrada!</legend>
<?php
    }else{
?>
<legend><h2>Veja abaixo todas as consultas encontradas!</h2></legend>
<table>
<tr>
    <th>Local</th>
    <th>Data</th>
    <th>Horário</th>
    <th>Status</th>
    <th>Alterar</th>
    <th>Cancelar</th>
</tr>
<?php
    while($select = $query_select->fetch(PDO::FETCH_ASSOC)){
        $token = $select['token'];
        $status_consulta = $select['status_consulta'];
        $local_atendimento = $select['local_consulta'];
        $atendimento_dia = $select['atendimento_dia'];
        $atendimento_hora = strtotime($select['atendimento_hora']);
        $id = $select['id'];
?>
<tr>
    <td><?php echo $local_atendimento ?></td>
    <td><?php echo date('d/m/Y', strtotime($atendimento_dia)) ?></td>
    <td><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><?php echo $status_consulta ?></td>
    <?php if (
                $status_consulta == 'Cancelada' ||
                $status_consulta == 'Finalizada' ||
                strtotime($atendimento_dia) < strtotime($hoje)
            )
            {  ?>
    <td>-</td>
    <td>-</td>
    <?php }else{  ?>
    <td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>&tipo=Paciente","iframe-home")'><button>Alterar</button></td>
    <?php if($status_consulta == 'Checkedin' || $status_consulta == 'NoShow'){  ?>
    <td>-</td>
    <?php }else{ ?>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?token=<?php echo $token ?>&tipo=Paciente","iframe-home")'><button>Cancelar</button></a></td>
    <?php }}  ?>
</tr>
<?php
    }}
?>
</table>
</fieldset>
</body>
</html>