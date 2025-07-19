<?php
//$tutorialAtivo = isset($_SESSION['configuracao']) && $_SESSION['configuracao'] == 0;
?>

<!-- Header da Sidebar -->
<div class="sidebar-header">
    <div class="sidebar-logo">
        <i class="bi bi-building-exclamation"></i>
        <?php echo $config_empresa; ?>
    </div>
    <div class="sidebar-subtitle">
        Painel Administrativo
    </div>
</div>

<!-- Navegação da Sidebar -->
<nav class="sidebar-nav">
    <!-- CONFIGURAÇÕES -->
    <div data-step="2" class="nav-section">
        <div class="nav-section-title">Configurações</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_empresa.php","iframe-home");' class="nav-link">
                    <i class="bi bi-building-gear nav-link-icon"></i>
                    <span class="nav-link-text">Empresa</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_msg.php","iframe-home");' class="nav-link">
                    <i class="bi bi-database-gear nav-link-icon"></i>
                    <span class="nav-link-text">Mensagens</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_agenda.php","iframe-home");' class="nav-link">
                    <i class="bi bi-journal-bookmark nav-link-icon"></i>
                    <span class="nav-link-text">Agenda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_salas.php","iframe-home");' class="nav-link">
                    <i class="bi bi-house-add nav-link-icon"></i>
                    <span class="nav-link-text">Salas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_landing.php","iframe-home");' class="nav-link">
                    <i class="bi bi-file-break nav-link-icon"></i>
                    <span class="nav-link-text">Landing Page</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
