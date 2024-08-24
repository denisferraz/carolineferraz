<?php

session_start();
require('../conexao.php');
require('verifica_login.php');


$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
    $nome = $select['nome'];
}
?>



<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Painel de Controle</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<table width="100%">
<tr>
<td width="50%" align="left">Ola, <?php echo $nome ?> Tudo bem?</td>
<td width="50%" align="right"><a href="logout.php"><button>Sair</button></a></td>
</tr></table>

<br>
<center>
<div id="menuIcon">Ver Menu &#9776;</div>
</center>

<div class="container">
<div id="menu">
            
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">[ &times; ]</a>

    <a>Inicio<span class="submenu-parent"></span></a>
<div class="submenu">
    <a href="javascript:void(0)" onclick='window.open("home.php","iframe-home"); closeNav();'>Inicio</a>
    <a href="javascript:void(0)" onclick='window.open("cadastros.php","iframe-home"); closeNav();'>Cadastros</a>
</div>
    

    <a>Consultas<span class="submenu-parent"></span></a>
<div class="submenu">
    <a href="javascript:void(0)" onclick='window.open("reservas_editar.php","iframe-home"); closeNav();'>Consultas</a>
    <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Painel","iframe-home"); closeNav();'>Cadastrar</a>
    <a href="javascript:void(0)" onclick="abrirLembrete(); closeNav();">Enviar Lembretes</a>
</div>

    
    
    <a>Disponibilidade<span class="submenu-parent"></span></a>
<div class="submenu">
    <a href="javascript:void(0)" onclick='window.open("disponibilidade.php","iframe-home"); closeNav();'>Disponibilidade</a>
    <a href="javascript:void(0)" onclick='window.open("disponibilidade_fechar.php","iframe-home"); closeNav();'>Fechar Datas</a>
    <a href="javascript:void(0)" onclick='window.open("disponibilidade_abrir.php","iframe-home"); closeNav();'>Abrir Datas</a>
</div>

    

    <a href="javascript:void(0)" onclick='window.open("historico.php","iframe-home"); closeNav();'>Historico Alterações</a>

    <a href="javascript:void(0)" onclick='window.open("configuracoes.php","iframe-home"); closeNav();'>Configurações</a>

    <a>Financeiro<span class="submenu-parent"></span></a>
<div class="submenu">
    <a href="javascript:void(0)" onclick='window.open("despesas.php","iframe-home"); closeNav();'>Despesas</a>
    <a href="javascript:void(0)" onclick='window.open("despesas_lancar.php","iframe-home"); closeNav();'>Lançar Despesas</a>
    <a href="javascript:void(0)" onclick='window.open("custos.php","iframe-home"); closeNav();'>Cadastrar Custos</a>
    <a href="javascript:void(0)" onclick='window.open("tratamentos.php","iframe-home"); closeNav();'>Cadastrar Tratamentos</a>
    <a href="javascript:void(0)" onclick='window.open("ver_valores.php","iframe-home"); closeNav();'>Ver Valores</a>
</div>

    

    <a href="javascript:void(0)" onclick='window.open("relatorios.php", "iframe-home"); closeNav();'>Relatorios Gerenciais</a>

    <a href="javascript:void(0)" onclick='window.open("../index.php"); closeNav();'>Site Principal</a>
    

</div>

<center><iframe name="iframe-home" id="iframe-home" src="home.php"></iframe></center>

</div>
<script>
        $(function(){ 
        $('nav.mobile ul li a').click(function(){
            var listaMenu = $('nav.mobile ul');
            var icone = $('.botao-menu-mobile').find('i');
            icone.removeClass('far fa-times-circle');
            icone.addClass('fas fa-bars');
            listaMenu.slideToggle(); 
        });
    
        $('i.fas').click(function(){
        var listaMenu = $('nav.mobile ul');
            if(listaMenu.is(':hidden') == true){
                var icone = $('.botao-menu-mobile').find('i');
                icone.removeClass('fas fa-bars');
                icone.addClass('far fa-times-circle');
                listaMenu.slideToggle();
            }else{
                var icone = $('.botao-menu-mobile').find('i');
                icone.removeClass('far fa-times-circle');
                icone.addClass('fas fa-bars');
                listaMenu.slideToggle(); 
            }
        });
        });
    </script> 

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

<script>
  // Função para abrir o menu
  function openNav() {
    document.getElementById("menu").style.width = "200px";
  }

  // Função para fechar o menu
  function closeNav() {
    document.getElementById("menu").style.width = "0";
  }

  // Evento de clique no ícone de menu
  document.getElementById("menuIcon").addEventListener("click", function() {
    if (document.getElementById("menu").style.width === "0px") {
      openNav();
    } else {
      closeNav();
    }
  });

  // Evento de clique em toda a frase (elemento pai)
  var submenuParents = document.getElementsByClassName("submenu-parent");
  for (var i = 0; i < submenuParents.length; i++) {
    submenuParents[i].parentNode.addEventListener("click", function() {
      var submenu = this.nextElementSibling;
      var icon = this.querySelector(".submenu-parent");
      icon.classList.toggle("open");

      // Fechar submenus que estão abertos
      var openSubmenus = document.getElementsByClassName("submenu");
      for (var j = 0; j < openSubmenus.length; j++) {
        if (openSubmenus[j].style.display === "block") {
          openSubmenus[j].style.display = "none";
          openSubmenus[j].style.color = ""; // Resetar a cor do texto do submenu fechado
        }
      }

      if (submenu.style.display === "block") {
        submenu.style.display = "none";
      } else {
        submenu.style.display = "block";
      }
    });
  }
</script>



</body>
</html>
