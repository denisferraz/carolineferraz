<?php
session_start();
require('../config/database.php');

$query = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $configuracao = $select['configuracao'];
    $plano_validade = $select['plano_validade'];
    $tipo_cadastro = $select['tipo'];
}

// Define o caminho do CSS
$css_path = "css/style_2.css";
$css_path_2 = "css/style.css";

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
            <?php include 'includes/sidebar_renovar.php'; ?>
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
                <?php include 'includes/header.php'; ?>
            </div>
        </header>

        <!-- Conteúdo principal -->
        <main class="main-content" id="mainContent">
            <div class="iframe-container"><iframe name="iframe-home" id="iframe-home" src="../mercadopago/index.php"></iframe></div>
        </main>
    </div>
</body>
</html>
