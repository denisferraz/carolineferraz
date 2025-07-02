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
                <a href="javascript:void(0)" onclick='window.open("agenda_paciente.php","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-calendar-check me-2"></i> Agenda
                </a>
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Tratamento","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-card-checklist me-2"></i> Sessões
                </a>
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Arquivos","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-archive me-2"></i> Arquivos
                </a>
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Receitas","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-file-earmark-medical me-2"></i> Receitas
                </a>
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Atestados","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-file-earmark-person me-2"></i> Atestados
                </a>
                <a href="javascript:void(0)" onclick='window.open("reserva_paciente.php?id_job=Contratos","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-file-earmark-diff me-2"></i> Contrato
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
                    <i class="bi bi-youtube me-2"></i> Videos
                </a>
            </div>
        </li>

        <!-- PROFILE -->
        <li class="nav-item mt-2">
            <a class="nav-link text-white d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#menuProfile" role="button" aria-expanded="false" aria-controls="menuProfile">
                <span><i class="bi bi-person-square me-2"></i> Profile</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="menuProfile">
                <a href="javascript:void(0)" onclick='window.open("profile.php?id_job=Profile","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-person-check me-2"></i> Profile
                </a>
                <a href="javascript:void(0)" onclick='window.open("profile.php?id_job=Senha","iframe-home");' class="nav-link text-white d-flex align-items-center">
                    <i class="bi bi-lock me-2"></i> Senha
                </a>
            </div>
        </li>

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

</body>
</html>