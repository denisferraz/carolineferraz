<?php
session_start();
require('../config/database.php');

if($_SESSION['vencido']){
require('verifica_login.php');
}

if (!isset($_SESSION['token_emp'])) {
    die("Erro: Sessão 'token_emp' não definida.");
}

// Quebra a string em array de empresas
$empresas = explode(';', $_SESSION['empresas']);

// Se só tem uma empresa, seleciona automaticamente
if (count($empresas) === 1) {
    $_SESSION['emp_selecao'] = trim($empresas[0]);

    echo "<script>window.top.location.replace('painel.php');</script>";
    exit;
}

// Se formulário foi enviado manualmente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empresa'])) {
    $_SESSION['emp_selecao'] = $_POST['empresa'];
    $_SESSION['token_emp']  = $_POST['empresa'];
    echo "<script>window.top.location.replace('painel.php');</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Selecionar Empresa</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        body {
            font-family: sans-serif;
            padding: 40px;
        }
        .form-box {
            max-width: 400px;
            margin: auto;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Selecione a empresa</h2>
    <form method="POST">
    <select name="empresa" required>
        <option value="" disabled selected>-- Escolha uma empresa --</option>
        <?php
        foreach ($empresas as $empresa_token) {
            $empresa_token = trim($empresa_token);

            $query = $conexao->prepare("SELECT config_empresa FROM configuracoes WHERE token_emp = :token");
            $query->execute(['token' => $empresa_token]);
            $empresa = $query->fetch(PDO::FETCH_ASSOC);

            // Só mostra se encontrou a empresa
            if ($empresa) {
                $nome_empresa = $empresa['config_empresa'];
                ?>
                <option value="<?= htmlspecialchars($empresa_token) ?>">
                    <?= htmlspecialchars($nome_empresa) ?>
                </option>
                <?php
            }
        }
        ?>
    </select>
        <button type="submit">Confirmar</button>
    </form>
</div>

</body>
</html>
