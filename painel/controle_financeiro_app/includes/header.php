<?php
if(basename($_SERVER['PHP_SELF']) == 'index.php'){
$espacador = '' ;
}else{
$espacador = '../' ;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo $espacador; ?>css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body><br>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-chart-line"></i>
                <span><?php echo APP_NAME; ?></span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $espacador; ?>index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $espacador; ?>pages/lancamentos.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lancamentos.php' ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i> Lançamentos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $espacador; ?>pages/recorrentes.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'recorrentes.php' ? 'active' : ''; ?>">
                        <i class="fas fa-sync-alt"></i> Recorrentes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $espacador; ?>pages/relatorios.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'relatorios.php' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar"></i> Relatórios
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="<?php echo $espacador; ?>pages/contas.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contas.php' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Contas
                    </a>
                </li> -->
            </ul>
            <div class="nav-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div data-step="1" class="container"><?php echo isset($pageTitle) ? '<h1 class="page-title">' . $pageTitle . ' <i class="bi bi-question-square-fill"onclick="ajudaContabilidade' . $pageTitle . '()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h1>' : ''; ?>

