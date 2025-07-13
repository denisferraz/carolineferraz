<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

// Define o caminho do CSS melhorado
$css_path = "css/style_2.css";
$css_path_2 = "css/style.css";

if($tipo_cadastro == 'Admin' && $_SESSION['token'] == $_SESSION['token_emp']){
    $tipo_cadastro = 'Admin';
}else if($tipo_cadastro == 'Owner'){
    $tipo_cadastro = 'Owner';
}else{
    $tipo_cadastro = 'Paciente';
}

if($site_puro == 'chronoclick'){
$hoje = date('Y-m-d');
$data_validade = $plano_validade;
$dias_restantes = (strtotime($data_validade) - strtotime($hoje)) / 86400;
}else{
    $dias_restantes = 365;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?> - Painel Administrativo</title>
    
    <!-- CSS Melhorado -->
    <link rel="stylesheet" href="<?php echo $css_path_2 ?>">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <!-- Favicon -->
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    
    <!-- Tutorial -->
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
    <script src="js/tutorial.js"></script>
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-H7LG32M41D"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-H7LG32M41D');
    </script>
</head>
<body>
    <!-- Overlay para mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Container principal -->
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <?php if($tipo_cadastro == 'Paciente' && isset($_SESSION['emp_selecao']) || $_SESSION['vencido']){
                include 'includes/sidebar_paciente.php';
            }else if(!isset($_SESSION['emp_selecao']) && $site_puro == 'chronoclick'){
                include 'includes/sidebar_selecao.php';
            }else if($tipo_cadastro == 'Owner' || ($configuracao >= 1 && $dias_restantes > 0) || $site_puro != 'chronoclick'){
                include 'includes/sidebar.php';
            } else if($configuracao == 0 && $dias_restantes > 0) {
                include 'includes/sidebar_config.php';
            } else if ($dias_restantes <= 0){
                include 'includes/sidebar_renovar.php';
            }
            ?>
        </aside>

        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <!-- Botão menu mobile -->
                <button class="mobile-menu-button" id="mobileMenuButton">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs" id="breadcrumbs">
                    <div class="breadcrumb-item">
                        <a href="#" class="breadcrumb-link">Início</a>
                        <span class="breadcrumb-separator">/</span>
                    </div>
                    <div class="breadcrumb-item">
                        <span class="breadcrumb-current">Dashboard</span>
                    </div>
                </nav>
            </div>
            
            <div class="header-right">
                <?php if($tipo_cadastro == 'Paciente' && isset($_SESSION['emp_selecao']) && ($_SESSION['vencido'] || $site_puro != 'chronoclick')){
                    include 'includes/header_paciente.php'; 
                }else if($site_puro != 'chronoclick' || isset($_SESSION['emp_selecao'])){
                    include 'includes/header.php';
                }
                
                if(!isset($_SESSION['emp_selecao']) && $site_puro == 'chronoclick'){
                    include 'includes/header_selecao.php';
                }
                ?>
            </div>
        </header>

        <!-- Conteúdo principal -->
        <main class="main-content" id="mainContent">
            <div class="iframe-container">
                <?php
                if (!isset($_SESSION['emp_selecao']) && $site_puro == 'chronoclick') {
                    // Redireciona para seleção de empresa
                    ?><iframe name="iframe-home" id="iframe-home" src="selecao.php"></iframe><?php
                } else if ($tipo_cadastro == 'Paciente') {
                    ?><iframe name="iframe-home" id="iframe-home" src="agenda_paciente.php"></iframe><?php
                } else if ($tipo_cadastro == 'Owner' || ($tipo_cadastro == 'Admin' && $configuracao >= 1 && $dias_restantes > 0) || $site_puro != 'chronoclick') {
                    ?><iframe name="iframe-home" id="iframe-home" src="agenda.php"></iframe><?php
                } else if ($configuracao == 0 && $dias_restantes > 0 && $site_puro == 'chronoclick') {
                    ?><iframe name="iframe-home" id="iframe-home" src="config_empresa.php"></iframe><?php
                } else if ($dias_restantes <= 0 && $site_puro == 'chronoclick') {
                    ?><iframe name="iframe-home" id="iframe-home" src="renovar.php"></iframe><?php
                }

                if ($_SESSION['vencido'] && $site_puro == 'chronoclick') {
                    ?><iframe name="iframe-home" id="iframe-home" src="renovar.php"></iframe><?php
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Controle do menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            }
            
            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            mobileMenuButton?.addEventListener('click', toggleSidebar);
            sidebarOverlay?.addEventListener('click', closeSidebar);
            
            // Fechar sidebar ao clicar em um link (mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Fechar sidebar ao redimensionar para desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
        });

        // Função para abrir lembrete (mantida para compatibilidade)
        function abrirLembrete() {
            // Exibe o popup de carregamento
            exibirPopup();
            // Abre a página lembrete.php no iframe
            window.open("lembrete.php", "iframe-home");
        }

        function exibirPopup() {
            Swal.fire({
                icon: 'warning',
                title: 'Carregando...',
                text: 'Aguarde enquanto enviamos os Lembretes!',
                timer: 10000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        // Melhorar acessibilidade
        document.addEventListener('keydown', function(e) {
            // Esc para fechar sidebar mobile
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    document.getElementById('sidebarOverlay').classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (<?php echo $tutorialAtivo ? 'true' : 'false'; ?>) {
        introJs().setOptions({
            exitOnOverlayClick: false,
            steps: [
                {
                    element: document.querySelector('[data-step="1"]'),
                    intro: "Ola, seja muito Bem Vindo(a)!"
                },
                {
                    element: document.querySelector('[data-step="1"]'),
                    intro: "Vamos começar a sua Primeira Configuração?"
                },
                {
                    element: document.querySelector('[data-step="2"]'),
                    intro: "Preencha as 04 proximas paginas que irão aparecer neste cantinho aqui. Conforme você for Salvando, ele ira avançando automaticamente."
                },
                {
                    element: document.querySelector('[data-step="2"]'),
                    intro: "Quando finalizar a sua configuração, ele ira lhe levar para a pagina principal!"
                }
            ],
            doneLabel: "Finalizar",
            showButtons: true,
            showBullets: false,
            nextLabel: "Próximo",
            prevLabel: "Anterior",
            skipLabel: "X"
        }).start();
    }
});
</script>
</body>
</html>

