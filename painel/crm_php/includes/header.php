<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/health_theme.css">
    
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos específicos da página */
        <?php if (isset($custom_css)) echo $custom_css; ?>
    </style>
</head>
<body>
    <?php
    $current_user = getCurrentUser();
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    ?>
    
    <!-- Sidebar -->
    <nav class="health-sidebar">
        <div class="mb-6">
            <h2 class="health-card-title">
                <i class="fas fa-heartbeat" style="color: var(--health-primary);"></i>
                CRM Saúde
            </h2>
            <p class="health-card-subtitle">Sistema Profissional</p>
        </div>
        
        <?php if ($current_user): ?>
            <div class="mb-6">
                <div class="health-card" style="padding: 1rem;">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: var(--health-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            <?php echo strtoupper(substr($current_user['name'], 0, 1)); ?>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--health-text-primary);">
                                <?php echo htmlspecialchars($current_user['name']); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--health-text-secondary);">
                                <?php echo htmlspecialchars($current_user['username']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <ul class="health-nav">
            <li class="health-nav-item">
                <a href="dashboard.php" class="health-nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a>
            </li>
            <li class="health-nav-item">
                <a href="crm.php" class="health-nav-link <?php echo $current_page === 'crm' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    CRM - Contatos
                </a>
            </li>
            <li class="health-nav-item">
                <a href="mensagens.php" class="health-nav-link <?php echo $current_page === 'mensagens' ? 'active' : ''; ?>">
                    <i class="fab fa-whatsapp"></i>
                    Mensagens
                </a>
            </li>
            <li class="health-nav-item">
                <a href="relatorios.php" class="health-nav-link <?php echo $current_page === 'relatorios' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    Relatórios
                </a>
            </li>
            <li class="health-nav-item">
                <a href="configuracoes.php" class="health-nav-link <?php echo $current_page === 'configuracoes' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    Configurações
                </a>
            </li>
        </ul>
        
        <?php if ($current_user): ?>
            <div style="margin-top: auto; padding-top: 2rem;">
                <a href="logout.php" class="health-btn health-btn-outline health-btn-full">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </a>
            </div>
        <?php endif; ?>
    </nav>
    
    <!-- Conteúdo Principal -->
    <main class="health-main">
        <?php if (isset($show_header) && $show_header): ?>
            <div class="health-card-header mb-6">
                <h1 class="health-card-title">
                    <?php if (isset($page_icon)): ?>
                        <i class="<?php echo $page_icon; ?>"></i>
                    <?php endif; ?>
                    <?php echo isset($page_title) ? $page_title : 'CRM Profissional'; ?>
                </h1>
                <?php if (isset($page_description)): ?>
                    <p class="health-card-subtitle"><?php echo $page_description; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

