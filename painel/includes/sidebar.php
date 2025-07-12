<?php
$empresas = explode(';', $_SESSION['empresas']);

$query = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $tipo_cadastro = $select['tipo'];
}

$tutorialAtivo2 = isset($_SESSION['configuracao']) && $_SESSION['configuracao'] == 1;

if ($tutorialAtivo2): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            iniciarTutorial();
        });
    </script>
<?php endif; ?>

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
                <a href="javascript:void(0)" onclick='window.open("agenda.php","iframe-home");' class="nav-link active" data-step="2">
                    <i class="bi bi-calendar-check nav-link-icon"></i>
                    <span class="nav-link-text">Agenda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("autorizacao.php","iframe-home");' class="nav-link">
                    <i class="bi bi-calendar2-minus nav-link-icon"></i>
                    <span class="nav-link-text">Solicitações Pendentes</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CADASTROS -->
    <div data-step="3" class="nav-section">
        <div class="nav-section-title">Cadastros</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("cadastros.php","iframe-home");' class="nav-link" data-step="3">
                    <i class="bi bi-person-badge nav-link-icon"></i>
                    <span class="nav-link-text">Clientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("cadastro_registro.php","iframe-home");' class="nav-link">
                    <i class="bi bi-person-add nav-link-icon"></i>
                    <span class="nav-link-text">Novo Cliente</span>
                </a>
            </li>
             <!-- <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("crm.php","iframe-home");' class="nav-link">
                    <i class="bi bi-person-bounding-box nav-link-icon"></i>
                    <span class="nav-link-text">CRM</span>
                </a>
            </li> -->
        </ul>
    </div>

    <!-- CONSULTAS -->
    <div data-step="4" class="nav-section">
        <div class="nav-section-title">Consultas</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reservas_editar.php","iframe-home");' class="nav-link" data-step="4">
                    <i class="bi bi-eye-fill nav-link-icon"></i>
                    <span class="nav-link-text">Consultas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Painel","iframe-home");' class="nav-link">
                    <i class="bi bi-calendar-plus nav-link-icon"></i>
                    <span class="nav-link-text">Cadastrar Consultas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="abrirLembrete();" class="nav-link">
                    <i class="bi bi-send-plus-fill nav-link-icon"></i>
                    <span class="nav-link-text">Enviar Lembretes</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- PRONTUÁRIOS -->
    <div data-step="5" class="nav-section">
        <div class="nav-section-title">Prontuários</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("anamnese_criar_modelo.php","iframe-home");' class="nav-link" data-step="5">
                    <i class="bi bi-file-earmark-medical nav-link-icon"></i>
                    <span class="nav-link-text">Criar Anamnese</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("anamnese_modelos.php","iframe-home");' class="nav-link">
                    <i class="bi bi-clipboard2-pulse nav-link-icon"></i>
                    <span class="nav-link-text">Modelos Anamnese</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("prontuario_criar_modelo.php","iframe-home");' class="nav-link">
                    <i class="bi bi-journal-medical nav-link-icon"></i>
                    <span class="nav-link-text">Criar Prontuário</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("prontuario_modelos.php","iframe-home");' class="nav-link">
                    <i class="bi bi-journals nav-link-icon"></i>
                    <span class="nav-link-text">Modelos Prontuário</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- DISPONIBILIDADE -->
    <div data-step="6" class="nav-section">
        <div class="nav-section-title">Disponibilidade</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("disponibilidade.php","iframe-home");' class="nav-link" data-step="5">
                    <i class="bi bi-eye nav-link-icon"></i>
                    <span class="nav-link-text">Disponibilidade</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("disponibilidade_fechar.php","iframe-home");' class="nav-link">
                    <i class="bi bi-door-closed-fill nav-link-icon"></i>
                    <span class="nav-link-text">Fechar Agenda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("disponibilidade_abrir.php","iframe-home");' class="nav-link">
                    <i class="bi bi-door-open-fill nav-link-icon"></i>
                    <span class="nav-link-text">Abrir Agenda</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- FINANCEIRO -->
    <div data-step="7" class="nav-section">
        <div class="nav-section-title">Financeiro</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("controle_financeiro_app/index.php","iframe-home");' class="nav-link">
                    <i class="bi bi-bank nav-link-icon"></i>
                    <span class="nav-link-text">Contabilidade</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("relatorios.php","iframe-home");' class="nav-link">
                    <i class="bi bi-graph-up nav-link-icon"></i>
                    <span class="nav-link-text">Relatórios</span>
                </a>
            </li>
            <?php if($_SESSION['site_puro'] == 'chronoclick'){ ?>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("transacoes.php","iframe-home");' class="nav-link">
                    <i class="bi bi-credit-card nav-link-icon"></i>
                    <span class="nav-link-text">Transações</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("../mercadopago/index.php","iframe-home");' class="nav-link">
                    <i class="bi bi-cash-coin nav-link-icon"></i>
                    <span class="nav-link-text">Renovar Plano</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>

    <!-- SERVIÇOS -->
    <div data-step="8" class="nav-section">
        <div class="nav-section-title">Serviços</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("ver_valores.php","iframe-home");' class="nav-link">
                    <i class="bi bi-cash-stack nav-link-icon"></i>
                    <span class="nav-link-text">Serviços</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("tratamentos.php","iframe-home");' class="nav-link">
                    <i class="bi bi-file-earmark-medical nav-link-icon"></i>
                    <span class="nav-link-text">Cadastrar Serviço</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("custos.php","iframe-home");' class="nav-link">
                    <i class="bi bi-coin nav-link-icon"></i>
                    <span class="nav-link-text">Cadastrar Custos</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- ESTOQUE -->
    <div data-step="9" class="nav-section">
        <div class="nav-section-title">Estoque</div>
        <ul class="nav-list">
        <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("estoque_ver.php","iframe-home");' class="nav-link">
                    <i class="bi bi-bag-dash nav-link-icon"></i>
                    <span class="nav-link-text">Saldo</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("estoque_produtos.php","iframe-home");' class="nav-link">
                    <i class="bi bi-box nav-link-icon"></i>
                    <span class="nav-link-text">Produtos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("estoque_entradas.php","iframe-home");' class="nav-link">
                    <i class="bi bi-box-arrow-in-down nav-link-icon"></i>
                    <span class="nav-link-text">Entradas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("estoque_saidas.php","iframe-home");' class="nav-link">
                    <i class="bi bi-box-arrow-up nav-link-icon"></i>
                    <span class="nav-link-text">Saídas</span>
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
                    <i class="bi bi-film nav-link-icon"></i>
                    <span class="nav-link-text">Ver Videos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("videos.php?id_job=Adicionar","iframe-home");' class="nav-link">
                    <i class="bi bi-youtube nav-link-icon"></i>
                    <span class="nav-link-text">Adicionar Videos</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CONFIGURAÇÕES -->
    <div data-step="11" class="nav-section">
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
            <?php if($_SESSION['site_puro'] == 'chronoclick'){ ?>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("config_landing.php","iframe-home");' class="nav-link">
                    <i class="bi bi-file-break nav-link-icon"></i>
                    <span class="nav-link-text">Landing Page</span>
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="abrirWhatsapp();" class="nav-link">
                    <i class="bi bi-whatsapp nav-link-icon"></i>
                    <span class="nav-link-text">Whatsapp</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- SUPORTE -->
    <div data-step="12" class="nav-section">
        <div class="nav-section-title">Suporte</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="iniciarTutorial()" class="nav-link">
                    <i class="bi bi-question-circle nav-link-icon"></i>
                    <span class="nav-link-text">Tutorial</span>
                </a>
            </li>
            <?php if($_SESSION['site_puro'] == 'chronoclick'){ ?>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("tickets.php","iframe-home");' class="nav-link">
                    <i class="bi bi-headset nav-link-icon"></i>
                    <span class="nav-link-text">Tickets</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("ticket_novo.php","iframe-home");' class="nav-link">
                    <i class="bi bi-patch-question nav-link-icon"></i>
                    <span class="nav-link-text">Novo Ticket</span>
                </a>
            </li>
            <?php } ?>
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

    <!-- PAINEL OWNER -->
    <?php if($tipo_cadastro == 'Owner'){ ?>
    <div class="nav-section">
        <div class="nav-section-title">Owner</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("tickets_adm.php","iframe-home");' class="nav-link">
                    <i class="bi bi-question nav-link-icon"></i>
                    <span class="nav-link-text">Tickets</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("owner_cadastros.php","iframe-home");' class="nav-link">
                    <i class="bi bi-person-bounding-box nav-link-icon"></i>
                    <span class="nav-link-text">Cadastros</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick='window.open("owner_transacoes.php","iframe-home");' class="nav-link">
                    <i class="bi bi-wallet2 nav-link-icon"></i>
                    <span class="nav-link-text">Financeiro</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="abrirBackup();" class="nav-link">
                    <i class="bi bi-database-down nav-link-icon"></i>
                    <span class="nav-link-text">Backup</span>
                </a>
            </li>
        </ul>
    </div>
    <?php } ?>

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

<script>

function exibirPopup(id_job) {

    if (id_job === 'lembrete') {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos os Lembretes!',
            timer: 10000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Salvando...',
            text: 'Aguarde enquanto salvamos o seu Backup!',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }
}


function abrirLembrete() {
    exibirPopup('lembrete');
    window.open("lembrete.php", "iframe-home");
}

function abrirBackup() {
    exibirPopup('backup');
    window.open("backup_automatico.php?id_job=Painel", "iframe-home");
}

// Adicionar indicador visual de link ativo
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remover classe active de todos os links
            navLinks.forEach(l => l.classList.remove('active'));
            // Adicionar classe active ao link clicado
            this.classList.add('active');
        });
    });
});
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
      timer: 8000,
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      willOpen: () => {
        Swal.showLoading();
      }
    });
  }
</script>
