<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$query = $conexao->prepare("SELECT * FROM profissionais WHERE token_emp = :token");
$query->execute(array('token' => $_SESSION['token_emp']));
while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
    $id = $select['id'];
    $nome = $select['nome'];
    $especialidade = $select['especialidade'];
    $descricao = $select['descricao'];
    $foto = $select['foto'];
    $email = $select['email'];
    $telefone = $select['telefone'];
    $whatsapp = $select['whatsapp'];
    $instagram = $select['instagram'];
    $experiencia = $select['experiencia'];
    $facebook = $select['facebook'];
    $endereco = $select['endereco'];
    $is_landing = $select['is_landing'];
    $is_externo = $select['is_externo'];
    $is_painel = $select['is_painel'];
}
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

<form class="form" action="acao.php" method="POST" enctype="multipart/form-data">
<div data-step="1" class="card">
<div class="card-top">
                <h2>Edite abaixo as Configurações do Profissional <i class="bi bi-question-square-fill"onclick="ajudaConfigLanding()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>
<div class="card-group">
    Configure suas informações as quais irão aparecer na sua Landing page.<br><br>
    Para acessar, basta digitiar: <a data-step="2" target="_blank" href="https://chronoclick.com.br/profissionais/index.php?profissional=<?php echo $id; ?>"><b>chronoclick.com.br/profissionais/index.php?profissional=<?php echo $id; ?></b></a><br><br>

    <input id="landing" type="checkbox" name="landing" <?php if($is_landing == 1){?>checked<?php } ?>>
    <label data-step="3" for="landing">Habilitar Landing Page</label>
    <input id="agendamento_externo" type="checkbox" name="agendamento_externo" <?php if($is_externo == 1){?>checked<?php } ?>>
    <label data-step="4" for="agendamento_externo">Habilitar Agendamento Externo</label>
    <input id="painel_externo" type="checkbox" name="painel_externo" <?php if($is_painel == 1){?>checked<?php } ?>>
    <label data-step="5" for="painel_externo">Habilitar Painel Administrativo para Pacientes</label>
    <label>Nome</label>
    <input data-step="6" type="text" minlength="10" maxlength="100" name="config_nome" value="<?php echo $nome; ?>" required>
    <label>Nome pagina Externa</label>
    <input data-step="7" type="text" minlength="5" maxlength="50" name="config_landing" value="<?php echo $id; ?>" required>
    <label>Especialidade</label>
    <input data-step="8" type="text" maxlength="100" name="config_especialidade" value="<?php echo $especialidade; ?>" required>
    <label>Descriçao</label>
    <textarea data-step="9" class="textarea-custom" name="config_descricao" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $descricao)); ?></textarea><br><br>
    <label>Instagram</label>
    <input data-step="10" type="text" maxlength="100" name="config_instagram" value="<?php echo $instagram; ?>" required>
    <label>Facebook</label>
    <input data-step="11" type="text" maxlength="100" name="config_facebook" value="<?php echo $facebook; ?>" required>
    <label>Endereço</label>
    <input data-step="12" type="text" maxlength="155" name="config_endereco" value="<?php echo $endereco; ?>" required>
    <br><br>
    <label>Foto (apenas JPG)</label>
    <input data-step="13" type="file" name="config_foto" accept=".jpg,.jpeg">
    <br>
    <img src="../profissionais/fotos/<?php echo $_SESSION['token_emp']; ?>.jpg" 
     alt="<?php echo htmlspecialchars($nome); ?>" 
     width="200" height="200">
    <br>
    <input type="hidden" name="id_job" value="editar_configuracoes_landing">
    <input type="hidden" name="configuracao" value="<?php echo $config_configuracao; ?>">
    <input type="hidden" name="token_emp" value="<?php echo $_SESSION['token_emp']; ?>">
    <input type="hidden" name="config_email" value="<?php echo $config_email; ?>">
    <input type="hidden" name="config_telefone" value="<?php echo $config_telefone; ?>">
    <div data-step="14" class="card-group btn"><button type="submit">Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>
