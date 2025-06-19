<?php
session_start();
$email = $_SESSION['email'];

//Criar Alertas
$alerta = 'Selecione a Empresa antes de continuar!';
$class_alerta = 1;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="bg-light p-3 shadow-sm d-flex justify-content-between align-items-center">
    
    <h5 class="m-0">
        Painel Administrativo
        <?php if($class_alerta == 1){ ?>
        <span class="badge bg-danger" style="font-size: 0.8rem; margin-left: 10px; text-align: left; line-height: 1.5;">
            <?= $alerta ?>
        </span>
        <?php } ?>
    </h5>

    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary"><?= $usuario ?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
    </div>
</div>

</body>
</html>
