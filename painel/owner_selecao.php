<?php
session_start();
require('../config/database.php');

// Se formulário foi enviado manualmente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empresa'])) {
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
            $query = $conexao->prepare("SELECT config_empresa, token_emp FROM configuracoes WHERE id >= :id ORDER BY config_empresa ASC");
            $query->execute(['id' => 1]);
            while($select = $query->fetch(PDO::FETCH_ASSOC)){
                $token_emp = $select['token_emp'];
                $config_empresa = $select['config_empresa']; ?>
                <option value="<?= htmlspecialchars($token_emp) ?>"><?= htmlspecialchars($config_empresa) ?></option>
                <?php } ?>
    </select>
        <button type="submit">Confirmar</button>
    </form>
</div>

</body>
</html>
