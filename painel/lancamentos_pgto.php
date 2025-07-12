<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);

$check = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email");
$check->execute(array('doc_email' => $doc_email));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
  $doc_nome = $select_check2['nome'];
}
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
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
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
    </style>
</head>
<body>
    
    <div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-cash-coin"></i>
                Lançar Pagamento
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para lançar um pagamento
            </p>
        </div>
    </div>
<form class="form" action="acao.php" method="POST">

<div class="form-section-title">
                <div data-step="2">
                <i class="bi bi-person-vcard"></i> <?php echo $doc_nome ?><br>
                <i class="bi bi-envelope"></i> <?php echo $doc_email ?><br>
                <i class="bi bi-cash"></i> Valor em Aberto  [ <u>R$<?php echo number_format($valor ,2,",",".") ?></u> ]<br>
                </div>
            </div>

<div class="form-row">
                <div class="health-form-group">
                    
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            
            <label class="health-label">Forma de Pagamento</label>
            <select class="health-select" name="lanc_produto" required>
            <option value="Cartão">Cartão</option>
            <option value="Dinheiro">Dinheiro</option>
            <option value="Transferencia">Transferencia</option>
            <option value="Outros">Outros</option>
            </select><br><br>
            <label class="health-label">Valor</label>
            <input class="health-input" minlength="1" maxlength="30" type="text" name="lanc_valor" value="<?php echo number_format($valor ,2,".",".") ?>" required>
            <br><br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="lanc_quantidade" value="1">
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Confirmar</button></div>

            </div>
        </div>
    </form>

</body>
</html>
