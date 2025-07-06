<?php

session_start();
require('../config/database.php');
require('verifica_login.php'); 
require_once('tutorial.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM videos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$link_youtube = $select['link_youtube'];
$descricao = $select['descricao'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Link</title>

    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

<form class="form" action="acao.php" method="POST">
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Edite abaixo seu Link <i class="bi bi-question-square-fill"onclick="ajudaVideosEditar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
            <label>Titulo do Video</label>
            <input data-step="2" minlength="5" maxlength="150" type="text" name="video_titulo" value="<?php echo $descricao; ?>" required>
            <label>Link do Video</label>
            <input data-step="3" minlength="5" maxlength="1500" type="text" name="video_link" value="<?php echo $link_youtube; ?>" required>
                <input type="hidden" name="video_id" value="<?php echo $id; ?>" />
                <input type="hidden" name="id_job" value="editar_link" />
            <div data-step="4" class="card-group btn"><button type="submit">Editar Link</button></div>

            </div>
        </div>
    </form>

</body>
</html>
