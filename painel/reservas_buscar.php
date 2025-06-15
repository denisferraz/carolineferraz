<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

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
        $query_select = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim ORDER BY atendimento_dia ASC");
        $query_select->execute(array('busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }else{
        $query_select = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim AND (doc_email LIKE :palavra) ORDER BY atendimento_dia ASC");
        $query_select->execute(array('palavra' => "%$palavra%", 'busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }
    $select_qtd = $query_select->rowCount();

    if($select_qtd == 0){
?>
    <legend>Nenhuma consulta encontrada com o filtro [ <?php echo $palavra ?> ]</legend>
<?php
    }else{
?>
<legend><h2>Veja abaixo todas as consultas com o filtro [ <?php echo $palavra ?> ]</h2></legend>
<table>
<tr>
    <th>Acessar</th>
    <th>Nome [ E-mail ]</th>
    <th>Entrada</th>
    <th>Horário</th>
    <th>Status</th>
    <th>Editar</th>
    <th>Cancelar</th>
</tr>
<?php
    while($select = $query_select->fetch(PDO::FETCH_ASSOC)){
        $status_consulta = $select['status_consulta'];
        $doc_email = $select['doc_email'];
        $atendimento_dia = strtotime($select['atendimento_dia']);
        $atendimento_hora = strtotime($select['atendimento_hora']);
        $id = $select['id'];

        $query_check2 = $conexao->query("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = '{$doc_email}'");
        $painel_users_array = [];
        while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
            $dados_painel_users = $select['dados_painel_users'];

        // Para descriptografar os dados
        $dados = base64_decode($dados_painel_users);
        $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

        $dados_array = explode(';', $dados_decifrados);

        $painel_users_array[] = [
            'nome' => $dados_array[0]
        ];

    }

    foreach ($painel_users_array as $select_check2){
    $doc_nome = $select_check2['nome'];
    }
?>
<tr>
    <td><a href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id ?>","iframe-home")'><button>Acessar</button></a></td>
    <td><?php echo $doc_nome ?><br><?php echo $doc_email ?></td>
    <td><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><?php echo $status_consulta ?></td>
    <?php if($status_consulta == 'Cancelada' || $status_consulta == 'Finalizada'){  ?>
    <td>-</td>
    <td>-</td>
    <?php }else{  ?>
    <td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id_consulta=<?php echo $id ?>","iframe-home")'><button>Editar</button></td>
    <?php if($status_consulta == 'Checkedin' || $status_consulta == 'NoShow'){  ?>
    <td>-</td>
    <?php }else{ ?>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?id_consulta=<?php echo $id ?>","iframe-home")'><button>Cancelar</button></a></td>
    <?php }}  ?>
</tr>
<?php
    }}
?>
</table>
</fieldset>
</body>
</html>
