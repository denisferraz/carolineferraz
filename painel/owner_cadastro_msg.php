<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("
SELECT 
    p.dados_painel_users, 
    p.token, 
    c.plano_validade 
FROM 
    painel_users p
LEFT JOIN 
    configuracoes c ON c.token_emp = p.token
WHERE 
    p.email = :email
");
$query->execute(array('email' => $email));
$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
        $plano_validade = $select['plano_validade'];
        $token_profile = $select['token'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $email,
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3],
    ]; 
}

foreach ($painel_users_array as $select){
$doc_nome = $select['nome'];
$telefone = $select['telefone'];
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

        .btn-sm {
            padding: var(--space-2) var(--space-3);
            font-size: var(--font-size-xs);
        }
    </style>
</head>
<body>
<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-person"></i>
                Enviar Mensagem de Follow-up
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para enviar uma mensagem para este cliente
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">
    <div class="form-row">
                <div class="health-form-group">
                <label class="health-label">Email</label>
                <input class="health-input" type="email" name="doc_email" minlength="10" value="<?php echo $email ?>">
                </div><div class="health-form-group">
                <label class="health-label">Telefone</label>
                <input class="health-input" type="text" name="doc_telefone" minlength="10" maxlength="15" value="<?php echo $telefone ?>">
                </div></div>
                <div class="form-row">
                <div class="health-form-group">
                <label class="health-label">Mensagem</label>
                <textarea class="health-input" class="health-input" data-step="3" name="msg" rows="5" cols="43" required>
Bom dia *<?php echo $doc_nome; ?>*, tudo bem?

Aqui quem fala é o *Denis*, sou do time do *Chronoclick*.

Seja muito bem vinda ao seu *Portal de Agendamento*.

Qualquer dúvida ou dificuldade, _não deixe de falar conosco!_

Desejamos *sucesso* nos seus agendamentos!!
</textarea>
            </div></div>

                <input type="hidden" name="id_job" value="cadastro_msg_owner">
                <input type="hidden" name="feitopor" value="Painel">

                <button class="health-btn health-btn-primary" type="submit"><i class="bi bi-envelope"></i> Enviar</button>
    </form>
    </div>
</body>
</html>
