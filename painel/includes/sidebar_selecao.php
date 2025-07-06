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
    <!-- SELEÇÃO -->
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
</nav>
