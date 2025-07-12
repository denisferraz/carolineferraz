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
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid var(--health-gray-900);
        }

        .data-table th {
            text-align: center;
        }
        
        .data-table th {
            background: var(--health-gray-300);
            font-weight: 600;
            color: var(--health-gray-800);
        }
        
        .data-table tr:hover {
            background: var(--health-gray-200);
        }

        .valor-sugestao {
            background: var(--health-success-light);
            color: var(--health-success);
        }
        
        .valor-margem {
            background: var(--health-warning-light);
            color: var(--health-warning);
        }
        
        .valor-taxas {
            background: var(--health-danger-light);
            color: var(--health-danger);
        }
        
        .valor-total {
            background: var(--health-info-light);
            color: var(--health-info);
        }

    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .data-table {
            min-width: 600px; /* ou o mínimo necessário para sua tabela não quebrar */
        }

        .data-table th, .data-table td {
            padding: 8px;
            font-size: 0.8rem;
        }
    }
    </style>
</head>
<body>
<div class="section-content health-fade-in">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-eye"></i>
                Histórico de Consultas
            </h1>
            <p class="health-card-subtitle">
                Veja abaixo todas as consultas com o filtro [ <?php echo $palavra; ?> ]
            </p>
        </div>
    </div>  
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

    //$busca_fim = date('Y-m-d', strtotime("$busca_fim") + 86400);

    if($palavra == ''){
        $palavra = 'Todos';
        $query_select = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim ORDER BY atendimento_dia ASC");
        $query_select->execute(array('busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }else{
        $query_select = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :busca_inicio AND atendimento_dia <= :busca_fim AND (doc_email LIKE :palavra) ORDER BY atendimento_dia ASC");
        $query_select->execute(array('palavra' => "%$palavra%", 'busca_inicio' => $busca_inicio, 'busca_fim' => $busca_fim));    
    }
    $select_qtd = $query_select->rowCount();

    if($select_qtd > 0){
?>
<div class="table-responsive">
    <table class="data-table">
<thead>
    <th>Ver</th>
    <th>Nome [ E-mail ]</th>
    <th>Data</th>
    <th>Horário</th>
    <th>Status</th>
</thead>
<?php
    while($select = $query_select->fetch(PDO::FETCH_ASSOC)){
        $status_consulta = $select['status_consulta'];
        $doc_email = $select['doc_email'];
        $atendimento_dia = strtotime($select['atendimento_dia']);
        $atendimento_hora = strtotime($select['atendimento_hora']);
        $id = $select['id'];

        $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
        $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
        $painel_users_array = [];
        while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
            $dados_painel_users = $select['dados_painel_users'];

        // Para descriptografar os dados
        $dados = base64_decode($dados_painel_users);
        $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

        $dados_array = explode(';', $dados_decifrados);

        $painel_users_array[] = [
            'nome' => $dados_array[0],
            'telefone' => $dados_array[3]
        ];

    }

    foreach ($painel_users_array as $select_check2){
    $doc_nome = $select_check2['nome'];
    $doc_telefone = $select_check2['telefone'];
    }
?>
<tr>
    <td><a href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-primary btn-mini"><i class="bi bi-eye"></i> Ver</button></a></td>
    <td><i class="bi bi-person"></i> <?php echo $doc_nome ?><br><i class="bi bi-envelope"></i> <?php echo $doc_email ?><br> <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $doc_telefone) ?>" target="_blank"><i class="bi bi-whatsapp"></i> <?php echo $doc_telefone ?></a></td>
    <td><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><?php echo $status_consulta ?></td>
</tr>
<?php
    }}
?>
</table>
</div>
</body>
</html>
