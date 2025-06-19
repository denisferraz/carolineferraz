<?php
$empresas = explode(';', $_SESSION['empresas']);
?>

<div class="sidebar bg-dark text-white p-3">
    <h4 class="text-center">Painel</h4>
    <ul class="nav flex-column small">

        <!-- INÍCIO -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuInicio" role="button" aria-expanded="false" aria-controls="menuInicio">
                <span><i class="bi bi-grid me-2"></i> Início</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuInicio">
                <a href="javascript:void(0)" onclick='window.open("agenda.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-calendar-check me-2"></i> Agenda
                </a>
                <a href="javascript:void(0)" onclick='window.open("autorizacao.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-calendar2-minus me-2"></i> Solicitações Pendentes
                </a>
            </div>
        </li>

        <!-- CADASTROS -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuCadastros" role="button" aria-expanded="false" aria-controls="menuCadastros">
                <span><i class="bi bi-people-fill me-2"></i> Cadastros</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuCadastros">
                <a href="javascript:void(0)" onclick='window.open("cadastros.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-person-badge me-2"></i> Clientes
                </a>
                <a href="javascript:void(0)" onclick='window.open("cadastro_registro.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-person-add me-2"></i> Novo Cliente
                </a>
                <a href="javascript:void(0)" onclick='window.open("crm.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-person-bounding-box me-2"></i> CRM
                </a>
            </div>
        </li>

        <!-- CONSULTAS -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuConsultas" role="button" aria-expanded="false" aria-controls="menuConsultas">
                <span><i class="bi bi-calendar3 me-2"></i> Consultas</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuConsultas">
                <a href="javascript:void(0)" onclick='window.open("reservas_editar.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-eye-fill me-2"></i> Consultas
                </a>
                <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Painel","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-calendar-plus me-2"></i> Cadastrar Consultas
                </a>
                <a href="javascript:void(0)" onclick="abrirLembrete();" class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-send-plus-fill me-2"></i> Enviar Lembretes
                </a>
            </div>
        </li>

        <!-- ANAMNESE -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuAnamnese" role="button" aria-expanded="false" aria-controls="menuAnamnese">
                <span><i class="bi bi-journal-medical me-2"></i> Anamnese</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-2" id="menuAnamnese">
                <a href="javascript:void(0)" onclick='window.open("anamnese_criar_modelo.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-file-earmark-medical me-2"></i> Criar Modelo
                </a>
                <a href="javascript:void(0)" onclick='window.open("anamnese_modelos.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-clipboard2-pulse me-2"></i> Modelos
                </a>
            </div>
        </li>

        <!-- VIDEOS -->
        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuVideo" role="button" aria-expanded="false" aria-controls="menuVideo">
                <span><i class="bi bi-film me-2"></i> Videos</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuVideo">
                <a href="javascript:void(0)" onclick='window.open("videos.php?id_job=Ver","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-youtube me-2"></i> Ver Videos
                </a>
                <a href="javascript:void(0)" onclick='window.open("videos.php?id_job=Adicionar","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-youtube me-2"></i> Adicionar Videos
                </a>
            </div>
        </li>

        <!-- DISPONIBILIDADE -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuDisponibilidade" role="button" aria-expanded="false" aria-controls="menuDisponibilidade">
                <span><i class="bi bi-calendar-event-fill me-2"></i> Disponibilidade</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuDisponibilidade">
                <a href="javascript:void(0)" onclick='window.open("disponibilidade.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-eye me-2"></i> Disponibilidade
                </a>
                <a href="javascript:void(0)" onclick='window.open("disponibilidade_fechar.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-door-closed-fill me-2"></i> Fechar Agenda
                </a>
                <a href="javascript:void(0)" onclick='window.open("disponibilidade_abrir.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-door-open-fill me-2"></i> Abrir Agenda
                </a>
            </div>
        </li>

        <!-- HISTÓRICO -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuHistorico" role="button" aria-expanded="false" aria-controls="menuHistorico">
                <span><i class="bi bi-clock-history me-2"></i> Histórico</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuHistorico">
                <a href="javascript:void(0)" onclick='window.open("historico.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-clock-history me-2"></i> Histórico
                </a>
            </div>
        </li>

        <!-- FINANCEIRO -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuFinanceiro" role="button" aria-expanded="false" aria-controls="menuFinanceiro">
                <span><i class="bi bi-cash-stack me-2"></i> Financeiro</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuFinanceiro">
                <a href="javascript:void(0)" onclick='window.open("controle_financeiro_app/index.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-bank me-2"></i> Painel Adm
                </a>
                <a href="javascript:void(0)" onclick='window.open("custos.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-coin me-2"></i> Custos Fixos
                </a>
                <a href="javascript:void(0)" onclick='window.open("tratamentos.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-file-earmark-medical me-2"></i> Serviços
                </a>
                <a href="javascript:void(0)" onclick='window.open("ver_valores.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-currency-dollar me-2"></i> Valores
                </a>
            </div>
        </li>

        <!-- ESTOQUE -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuEstoque" role="button" aria-expanded="false" aria-controls="menuEstoque">
                <span><i class="bi bi-archive me-2"></i> Estoque</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuEstoque">
                <a href="javascript:void(0)" onclick='window.open("estoque_ver.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-bag-dash me-2"></i> Ver Estoque
                </a>
                <a href="javascript:void(0)" onclick='window.open("estoque_produtos.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-bag-plus me-2"></i> Produtos
                </a>
                <a href="javascript:void(0)" onclick='window.open("estoque_entradas.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-arrow-bar-right me-2"></i> Entradas
                </a>
                <a href="javascript:void(0)" onclick='window.open("estoque_saidas.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-arrow-bar-left me-2"></i> Saidas
                </a>
            </div>
        </li>

        <!-- RELATÓRIOS -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuRelatorios" role="button" aria-expanded="false" aria-controls="menuRelatorios">
                <span><i class="bi bi-bar-chart-fill me-2"></i> Relatórios</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuRelatorios">
                <a href="javascript:void(0)" onclick='window.open("relatorios.php", "iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-bar-chart-fill me-2"></i> Relatórios
                </a>
            </div>
        </li>

        <!-- CONFIGURAÇÕES -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuConfiguracoes" role="button" aria-expanded="false" aria-controls="menuConfiguracoes">
                <span><i class="bi bi-gear-fill me-2"></i> Configurações</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuConfiguracoes">
                <a href="javascript:void(0)" onclick='window.open("config_empresa.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-building-gear me-2"></i> Empresa
                </a>
                <a href="javascript:void(0)" onclick='window.open("config_msg.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-database-gear me-2"></i> Mensagens
                </a>
                <a href="javascript:void(0)" onclick='window.open("config_agenda.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-journal-bookmark me-2"></i> Agenda
                </a>
                <a href="javascript:void(0)" onclick="abrirWhatsapp();" class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-whatsapp me-2"></i> Whatsapp
                </a>
            </div>
        </li>
        
        <?php if (count($empresas) > 1){ ?>
        <!-- Seleção -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuSelecao" role="button" aria-expanded="false" aria-controls="menuSelecao">
                <span><i class="bi bi-check2-circle me-2"></i> Empresas</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuSelecao">
                <a href="javascript:void(0)" onclick='window.open("selecao.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-check2-square me-2"></i> Selecionar
                </a>
            </div>
        </li>
        <?php } ?>

    </ul>
</div>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

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
  function abrirWhatsapp() {
    // Exibe o popup de carregamento
    exibirPopupWhatsapp();

    // Abre a página lembrete.php em uma nova janela ou iframe
    window.open("conectar_whatsapp.php", "iframe-home");
  }

  function exibirPopupWhatsapp() {
    Swal.fire({
      icon: 'warning',
      title: 'Carregando...',
      text: 'Aguarde enquanto configuramos a interface!',
      timer: 6000,
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