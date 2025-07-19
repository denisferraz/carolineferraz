<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$id_sala = isset($_GET['id_sala']) ? mysqli_real_escape_string($conn_msqli, $_GET['id_sala']) : 0;

if($id_sala >= 1){

$query = $conexao->prepare("SELECT sala, descricao, quantidade FROM salas WHERE token_emp = :token_emp AND id = :id_sala");
$query->execute(array('token_emp' => $_SESSION['token_emp'], 'id_sala' => $id_sala));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $sala = $select['sala'];
    $descricao = $select['descricao'];
    $quantidade = $select['quantidade'];
}
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
                <?php if($id_sala == 0){ ?>
                <i class="bi bi-house-add"></i>
                Cadastrar Salas <i class="bi bi-question-square-fill"onclick="ajudaSalas()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
                <?php }else{ ?>
                <i class="bi bi-pencil"></i>
                    Editar Salas
                <?php } ?>
            </h1>
            <p class="health-card-subtitle">
            <?php if($id_sala == 0){ ?>
                Preencha os dados para cadastrar uma sala
            <?php }else{ ?>
                Preencha os dados para editar a sala
            <?php } ?>
            </p>
        </div>
    </div>
<?php if($id_sala == 0){ ?>
    <form data-step="1" class="form" action="acao.php" method="POST" enctype="multipart/form-data">

    <div class="form-row">
                <div class="health-form-group">

            <label class="health-label">Sala *</label>
            <input class="health-input" data-step="2" minlength="5" maxlength="100" type="text" name="sala" placeholder="Nome da Sala" required>
            <label class="health-label">Descrição *</label>
            <textarea class="health-input" data-step="3" class="textarea-custom" name="descricao" rows="5" cols="43" required> </textarea><br>
            <label class="health-label">Foto da Sala (JPG/JPEG)</label>
            <input class="health-input" type="file" name="foto" accept="image/jpeg,image/jpg">
            <br><br>
            <input type="hidden" name="id_job" value="config_salas_Cadastrar" />
            <div data-step="4"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Cadastrar</button></div>

            </div>
            </div>
    </form>
<div class="table-responsive">
<table data-step="5" class="data-table">
    <thead>
        <tr>
            <th>Sala</th>
            <th>Descrição</th>
            <th>Editar</th>
            <th data-step="6">Habilitar/Desabilitar</th>
            <th data-step="7">Excluir</th>
        </tr>
    </thead>
    <tbody>
        <?php

        // Conta o total de salas da empresa (exceto removidas)
        $contaTotal = $conexao->prepare("SELECT COUNT(*) FROM salas WHERE token_emp = :token AND status_sala != 'Removida'");
        $contaTotal->execute(['token' => $_SESSION['token_emp']]);
        $totalSalas = $contaTotal->fetchColumn();

        // Conta as salas Habilitadas
        $contaHabilitadas = $conexao->prepare("SELECT COUNT(*) FROM salas WHERE token_emp = :token AND status_sala = 'Habilitar'");
        $contaHabilitadas->execute(['token' => $_SESSION['token_emp']]);
        $totalHabilitadas = $contaHabilitadas->fetchColumn();

        $query = $conexao->prepare("SELECT * FROM salas WHERE token_emp = '{$_SESSION['token_emp']}' AND status_sala != 'Removida' AND id >= :id ORDER BY sala DESC");
        $query->execute(['id' => 1]);

        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $select['id'];
            $sala = $select['sala'];
            $descricao = $select['descricao'];
            $status_sala = $select['status_sala'];

            if ($status_sala == 'Habilitar') {
                $id_job = 'Desabilitar';
                $class_status = 'warning';
            } else {
                $id_job = 'Habilitar';
                $class_status = 'success';
            }

            $basePath = "../imagens/{$_SESSION['token_emp']}/salas/";
            $imgJpg = $basePath . $id . '.jpg';
            $imgJpeg = $basePath . $id . '.jpeg';

            if (file_exists($imgJpg)) {
                $imgSrc = $imgJpg;
            } elseif (file_exists($imgJpeg)) {
                $imgSrc = $imgJpeg;
            } else {
                $imgSrc = '../images/logo.png'; // opcional
            }

            // Impede desabilitar se for a única habilitada
            $bloquearDesabilitar = ($status_sala == 'Habilitar' && $totalHabilitadas <= 1);

            // Impede excluir se for a única sala
            $bloquearExcluir = ($totalSalas <= 1);
        ?>
        <tr>
            <td data-label="Sala">
            <img src="<?php echo $imgSrc; ?>" alt="Foto da Sala" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
            <br><?php echo $sala; ?></td>
            <td data-label="Descrição"><?php echo $descricao; ?></td>
            <td data-label="Editar">
            <a data-step="3" href="javascript:void(0)" onclick='window.open("config_salas.php?id_sala=<?php echo $id ?>","iframe-home")' class="health-btn health-btn-primary">
                            <i class="bi bi-pencil"></i>
                            Editar
                        </a>
            </td>
            <td data-label="Habilitar/Desabilitar">
            <?php if ($bloquearDesabilitar): ?>
                <button type="button" class="health-btn health-btn-<?php echo $class_status; ?> btn-mini" disabled title="Não é possível desabilitar a única sala habilitada">
                <i class="bi bi-pencil-square"></i> Não é possível Desabilitar a única sala
                </button>
            <?php else: ?>
                <form method="post" action="acao.php" target="iframe-home" style="margin: 0;">
                    <input type="hidden" name="id_job" value="config_salas_<?php echo $id_job; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="health-btn health-btn-<?php echo $class_status; ?> btn-mini">
                        <i class="bi bi-pencil-square"></i> <?php echo $id_job; ?>
                    </button>
                </form>
            <?php endif; ?>
            </td>
            <td data-label="Excluir">
            <?php if ($bloquearExcluir): ?>
                <button type="button" class="health-btn health-btn-danger btn-mini" disabled title="Não é possível excluir a única sala">
                    <i class="bi bi-trash"></i> Não é possível excluir a única sala
                </button>
            <?php else: ?>
                <form method="post" action="acao.php" target="iframe-home" style="margin: 0;">
                    <input type="hidden" name="id_job" value="config_salas_Excluir">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" class="health-btn health-btn-danger btn-mini">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </form>
            <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php }else{ ?>
    <form data-step="1" class="form" action="acao.php" method="POST" enctype="multipart/form-data">

<div class="form-row">
            <div class="health-form-group">

        <label class="health-label">Sala *</label>
        <input class="health-input" minlength="5" maxlength="100" type="text" name="sala" value="<?php echo $sala; ?>" placeholder="Nome da Sala" required>
        <label class="health-label">Descrição *</label>
        <textarea class="health-input" class="textarea-custom" name="descricao" rows="5" cols="43" required><?php echo $descricao; ?></textarea><br>
        <label class="health-label">Foto da Sala (JPG/JPEG)</label>
        <input class="health-input" type="file" name="foto" accept="image/jpeg,image/jpg">
        <br><br>
        <input type="hidden" name="id_job" value="config_salas_Editar" />
        <input type="hidden" name="id_sala" value="<?php echo $id_sala; ?>" />
        <div><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Salvar</button></div>

        </div>
        </div>
</form>

<?php } ?>
</body>
</html>