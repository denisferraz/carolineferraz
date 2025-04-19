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
    <title>Relatorios Gerenciais</title>
    <link rel="stylesheet" href="css/style_v2.css">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="gerar.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Selecione o Relatorio Abaixo</h2>
            </div>

            <div class="card-group">
                <label><b>Selecione: 
                <select name="relatorio">
                <option value="Gerencial">Gerencial</option>
                <option value="Estornos Dia">Estornos Dia</option>
                <option value="Estornos Mes">Estornos Mês</option>
                <option value="Estornos Ano">Estornos Ano</option>
                <option value="Lançamentos Dia">Lançamentos Dia</option>
                <option value="Lançamentos Mes">Lançamentos Mês</option>
                <option value="Lançamentos Ano">Lançamentos Ano</option>
                <option value="Pagamentos Dia">Pagamentos Dia</option>
                <option value="Pagamentos Mes">Pagamentos Mês</option>
                <option value="Pagamentos Ano">Pagamentos Ano</option>
                <option value="Despesas Dia">Despesas Dia</option>
                <option value="Despesas Mes">Despesas Mês</option>
                <option value="Despesas Ano">Despesas Ano</option>
                <option value="Consultas Dia">Consultas Dia</option>
                <option value="Consultas Mes">Consultas Mês</option>
                <option value="Consultas Ano">Consultas Ano</option>
                <option value="Cancelamentos Dia">Cancelamentos Dia</option>
                <option value="Cancelamentos Mes">Cancelamentos Mês</option>
                <option value="Cancelamentos Ano">Cancelamentos Ano</option>
                <option value="No-Shows Dia">No-Shows Dia</option>
                <option value="No-Shows Mes">No-Shows Mês</option>
                <option value="No-Shows Ano">No-Shows Ano</option>
                </select></b></label><br>
                <label><b>Tipo Relatório: 
                <select name="relatorio_tipo">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
                </select></b></label><br>
                <label>Data Relatorio</label>
                <input type="date" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" name="relatorio_inicio" required>
                <br>
                <div class="card-group btn"><button type="submit">Gerar Relatorio</button></div>

            </div>
        </div>
    </form>

</body>
</html>

<?php
}
?>