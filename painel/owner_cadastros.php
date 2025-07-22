<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
    
$hoje = date('Y-m-d');
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
            text-align: left;
            border-bottom: 1px solid var(--health-gray-900);
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

<?php
$query = $conexao->prepare("
SELECT 
    p.dados_painel_users, 
    p.email, 
    p.token, 
    c.plano_validade 
FROM 
    painel_users p
LEFT JOIN 
    configuracoes c ON c.token_emp = p.token
WHERE 
    p.id >= 1 AND p.tipo != 'Paciente'
GROUP BY 
    p.token
");
$query->execute();
$query_row = $query->rowcount();

$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $email = $select['email'];
        $token = $select['token'];
        $plano_validade = $select['plano_validade'];


    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3],
        'email' => $email,
        'plano_validade' => $plano_validade,
        'token' => $token
    ];

}

?>
<div class="section-content health-fade-in">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-person-square"></i>
                Cadastros do Portal
            </h1>
            <p class="health-card-subtitle">
                Veja e gerencie todos os cadastros abaixo
            </p>
        </div>
    </div>
<div class="table-responsive">
    <?php if ($query_row > 0): ?>
        <table data-step="4" class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Validade</th>
                    <th>Mensagem</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($painel_users_array as $select){ ?>
                <tr>
                    <td><?= $select['nome'] ?>
                    </td>
                    <td><?= $select['email'] ?></td>
                    <td>
                        <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $select['telefone']) ?>" target="_blank">
                        <i class="bi bi-whatsapp"></i> <?= $select['telefone'] ?>
                        </a>
                    </td>
                    <td><?= date('d/m/Y', strtotime($select['plano_validade'])); ?></td>
                    <td><a href="javascript:void(0)" onclick='window.open("owner_cadastro_msg.php?email=<?php echo $select['email'] ?>","iframe-home")'>
                        <button class="health-btn health-btn-success btn-mini"><i class="bi bi-envelope"></i> Mensagem</button>
                    </a></td>
                    <td><a href="javascript:void(0)" onclick='window.open("owner_cadastro_editar.php?email=<?php echo $select['email'] ?>","iframe-home")'>
                        <button class="health-btn health-btn-primary btn-mini"><i class="bi bi-pencil"></i> Editar</button>
                    </a></td>
                    <td><a href="javascript:void(0)" onclick='window.open("owner_cadastro_excluir.php?email=<?php echo $select['email'] ?>&token=<?php echo $select['token'] ?>","iframe-home")'>
                        <button class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i> Excluir</button>
                    </a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>