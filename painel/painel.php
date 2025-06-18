<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE token = '{$_SESSION['token']}' AND email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'colorido'; // padrão é colorido

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

$query = $conexao->query("SELECT * FROM painel_users WHERE token = '{$_SESSION['token']}' AND email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $tipo_acesso = $select['tipo'];
}

if($tipo_acesso == 'Admin' && $_SESSION['token'] == $_SESSION['token_emp']){
    $tipo_acesso = 'Admin';
}else if($tipo_acesso == 'Owner'){
    $tipo_acesso = 'Owner';
}else{
    $tipo_acesso = 'Paciente';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>
        <?php if($tipo_acesso == 'Paciente' && isset($_SESSION['emp_selecao'])){
            include 'includes/sidebar_paciente.php';
        }else if(isset($_SESSION['emp_selecao'])){
            include 'includes/sidebar.php';
        }
        
        if(!isset($_SESSION['emp_selecao'])){
            include 'includes/sidebar_selecao.php';
        }
        ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="main-content">
    <div class="header-fixo">
        <?php if($tipo_acesso == 'Paciente' && isset($_SESSION['emp_selecao'])){
            include 'includes/header_paciente.php'; 
        }else if(isset($_SESSION['emp_selecao'])){
            include 'includes/header.php';
        }
        
        if(!isset($_SESSION['emp_selecao'])){
            include 'includes/header_selecao.php';
        }
        ?>
    </div>
    
    <div class="container-conteudo">
        <?php if($tipo_acesso == 'Paciente' && isset($_SESSION['emp_selecao'])){
            ?><iframe name="iframe-home" id="iframe-home" src="agenda_paciente.php"></iframe><?php
        }else if(isset($_SESSION['emp_selecao'])){
            ?><iframe name="iframe-home" id="iframe-home" src="agenda.php"></iframe><?php
        }
        
        if(!isset($_SESSION['emp_selecao'])){
            ?><iframe name="iframe-home" id="iframe-home" src="selecao.php"></iframe><?php
        }
        ?>
    </div>
</div>

<script>
  function abrirLembrete() {
    // Exibe o popup de carregamento
    exibirPopup();

    // Abre a página lembrete.php em uma nova janela ou iframe
    window.open("lembrete.php", "iframe-home");
  }

  function exibirPopup() {
    Swal.fire({
      icon: 'warning',
      title: 'Carregando...',
      text: 'Aguarde enquanto enviamos os Lembretes!',
      timer: 10000,
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      willOpen: () => {
        Swal.showLoading();
      }
    });
  }
</script>

</body>
</html>
