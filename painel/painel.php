<?php

session_start();
require('../conexao.php');
require('verifica_login.php');


$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
    $nome = $select['nome'];
}
?>



<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Painel de Controle</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<table width="100%">
<tr>
<td width="50%" align="left">Ola, <?php echo $nome ?> Tudo bem?</td>
<td width="50%" align="right"><button><a href="logout.php">Sair</a></button></td>
</tr></table>


<nav class="dp-menu"><ul>

    <li><a href="javascript:void(0)" onclick='window.open("home.php","iframe-home")'>Inicio</a>
    <ul><li><a href="javascript:void(0)" onclick='window.open("cadastros.php","iframe-home")'>Cadastros</a></li>
    </ul></li>

    <li><a href="javascript:void(0)" onclick='window.open("reservas_editar.php","iframe-home")'>Consultas</a>
    <ul><li><a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Painel","iframe-home")'>Cadastrar</a></li>
    <li><a href="javascript:void(0)" onclick='window.open("lembrete.php","iframe-home")'>Enviar Lembretes</a></li>

    </ul></li>
    
    <li><a href="javascript:void(0)" onclick='window.open("disponibilidade.php","iframe-home")'>Disponibilidade</a>
    <ul><li><a href="javascript:void(0)" onclick='window.open("disponibilidade_fechar.php","iframe-home")'>Fechar Datas</a></li>
    <li><a href="javascript:void(0)" onclick='window.open("disponibilidade_abrir.php","iframe-home")'>Abrir Datas</a></li>

    </ul></li>

    <li><a href="javascript:void(0)" onclick='window.open("historico.php","iframe-home")'>Historico Alterações</a></li>

    <li><a href="javascript:void(0)" onclick='window.open("configuracoes.php","iframe-home")'>Configurações</a></li>

    <li><a href="javascript:void(0)" onclick='window.open("despesas.php","iframe-home")'>Despesas</a>
    <ul><li><a href="javascript:void(0)" onclick='window.open("despesas_lancar.php","iframe-home")'>Lançar</a></li>

    </ul></li>

    <li><a href="javascript:void(0)" onclick='window.open("relatorios.php", "iframe-home")'>Relatorios Gerenciais</a></li>

    <li><a href="javascript:void(0)" onclick='window.open("../index.php")'>Site Principal</a></li>


</ul></nav>

<center><iframe name="iframe-home" id="iframe-home" src="home.php"></iframe></center>

</body>
</html>
