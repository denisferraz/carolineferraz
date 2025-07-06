<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

if($id_job == 'Ver'){

$videos = [];

$result_check = $conexao->query("SELECT * FROM videos WHERE token_emp = '{$_SESSION['token_emp']}' AND id > 0");
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)) {
    $link_youtube = $select_check['link_youtube'];
    $descricao = $select_check['descricao'];

    // Adiciona ao array corretamente
    $videos[] = [
        'titulo' => $descricao,
        'url' => $link_youtube
    ];
}

function extrairVideoId($url) {
    if (preg_match('/v=([a-zA-Z0-9_-]+)/', $url, $match)) {
        return $match[1];
    }
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $match)) {
        return $match[1];
    }
    return null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Galeria de Vídeos</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .video-container {
            max-width: 640px;
            width: 100%;
            margin-bottom: 3rem;
            text-align: center;
        }
        .video-title {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        iframe {
            width: 100%;
            height: 360px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 20px darkred;
        }
    </style>
</head>
<body>

<h3>📺 Galeria de Vídeos</h3><br>

<?php foreach ($videos as $video): 
    $id = extrairVideoId($video['url']);
    if ($id):
?>
    <div class="video-container">
        <div class="video-title"><?= htmlspecialchars($video['titulo']) ?></div>
        <iframe src="https://www.youtube.com/embed/<?= $id ?>" allowfullscreen></iframe>
    </div>
<?php 
    endif;
endforeach; 
?>

</body>
</html>

<?php }else{ ?>

    <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Adicionar Videos</title>
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
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Adicionar Video <i class="bi bi-question-square-fill"onclick="ajudaVideosAdd()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
            <label>Titulo do Video</label>
            <input data-step="2" minlength="5" maxlength="150" type="text" name="video_titulo" placeholder="Nome do Video" required>
            <label>Link do Video</label>
            <input data-step="3" minlength="5" maxlength="1500" type="text" name="video_link" placeholder="https://youtube.com" required>
            <input type="hidden" name="id_job" value="adicionar_link" />
            <div data-step="4" class="card-group btn"><button type="submit">Adicionar Link</button></div>

            </div>
    </form>
    <br><br>
    <table data-step="5">
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Link</th>
                    <th data-step="6">Editar</th>
                    <th data-step="7">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM videos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY id DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $link_youtube = $select['link_youtube'];
                    $descricao = $select['descricao'];
                ?>
                <tr>
                    <td data-label="Titulo"><?php echo $descricao; ?></td>
                    <td data-label="Link"><?php echo $link_youtube; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("videos_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("videos_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>

<?php } ?>