<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Configurações</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<form class="form" action="acao.php" method="POST">
<div class="card">
<div class="card-top">
                <h2>Edite abaixo as Configurações do Profissional</h2>
            </div>
<div class="card-group">
    <label>Profissional</label>
    <input type="text" minlength="5" maxlength="30" name="config_empresa" value="<?php echo $config_empresa; ?>" required>
    <label>Email</label>
    <input type="text" minlength="10" maxlength="35" name="config_email" value="<?php echo $config_email; ?>" required>
    <label>Telefone</label>
    <input type="text" minlength="8" maxlength="18" name="config_telefone" value="<?php echo $config_telefone; ?>" required>
    <label>CNPJ/CPF</label>
    <input type="text" minlength="2" maxlength="18" name="config_cnpj" value="<?php echo $config_cnpj; ?>" required>
    <label>Endereco</label>
    <textarea class="textarea-custom" name="config_endereco" rows="5" cols="43" required><?php echo $config_endereco ?></textarea><br><br>
    <br>
    <input type="hidden" name="id_job" value="editar_configuracoes_empresa">
    <div class="card-group btn"><button type="submit">Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>
