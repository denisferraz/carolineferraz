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
                <i class="bi bi-youtube"></i>
                Adicionar Videos <i class="bi bi-question-square-fill"onclick="ajudaVideosAdd()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para adicionar um novo video para os seus pacientes
            </p>
        </div>
    </div>
    <form data-step="1" class="form" action="acao.php" method="POST">

            <label class="health-label">Titulo do Video</label>
            <input class="health-input" data-step="2" minlength="5" maxlength="150" type="text" name="video_titulo" placeholder="Nome do Video" required>
            <label class="health-label">Link do Video</label>
            <input class="health-input" data-step="3" minlength="5" maxlength="1500" type="text" name="video_link" placeholder="https://youtube.com" required>
            <br><br>
            <input type="hidden" name="id_job" value="adicionar_link" />
            <div data-step="4"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Adicionar Link</button></div>

            </div>
    </form>
    
<div class="table-responsive">
    <table data-step="6" class="data-table">
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
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("videos_editar.php?id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-primary btn-mini"><i class="bi bi-pencil"></i> Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("videos_excluir.php?id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i> Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>

<?php } ?>