<?php
$empresas = explode(';', $_SESSION['empresas']);
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
<nav data-step="1" class="sidebar-nav">
    <!-- INÍCIO -->
    <div data-step="2" class="nav-section">
        <div class="nav-section-title">Dashboard</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("agenda_paciente.php","iframe-home");' class="nav-link active" data-step="2">
                    <i class="bi bi-calendar-check nav-link-icon"></i>
                    <span class="nav-link-text">Agenda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Tratamento","iframe-home");' class="nav-link">
                    <i class="bi bi-card-checklist nav-link-icon"></i>
                    <span class="nav-link-text">Sessões</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Arquivos","iframe-home");' class="nav-link">
                    <i class="bi bi-archive nav-link-icon"></i>
                    <span class="nav-link-text">Arquivos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Receitas","iframe-home");' class="nav-link">
                    <i class="bi bi-file-earmark-medical nav-link-icon"></i>
                    <span class="nav-link-text">Receitas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Atestados","iframe-home");' class="nav-link">
                    <i class="bi bi-file-earmark-person nav-link-icon"></i>
                    <span class="nav-link-text">Atestados</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Contratos","iframe-home");' class="nav-link">
                    <i class="bi bi-file-earmark-diff nav-link-icon"></i>
                    <span class="nav-link-text">Contrato</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- VIDEOS -->
    <div data-step="10" class="nav-section">
        <div class="nav-section-title">Videos</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("videos.php?id_job=Ver","iframe-home");' class="nav-link">
                    <i class="bi bi-youtube nav-link-icon"></i>
                    <span class="nav-link-text">Ver Videos</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- PROFILE -->
    <div data-step="13" class="nav-section">
        <div class="nav-section-title">Profile</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("profile.php?id_job=Profile","iframe-home");' class="nav-link">
                    <i class="bi bi-person-check nav-link-icon"></i>
                    <span class="nav-link-text">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("profile.php?id_job=Senha","iframe-home");' class="nav-link">
                    <i class="bi bi-lock nav-link-icon"></i>
                    <span class="nav-link-text">Senha</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- SELEÇÃO -->
    <?php if (count($empresas) > 1){ ?>
    <div class="nav-section">
        <div class="nav-section-title">Empresas</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("selecao.php","iframe-home");' class="nav-link">
                    <i class="bi bi-check2-square nav-link-icon"></i>
                    <span class="nav-link-text">Selecionar</span>
                </a>
            </li>
        </ul>
    </div>
    <?php } ?>
</nav>
