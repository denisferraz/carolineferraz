<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$titulo = '';
$perguntas = [];
$modelo_id = null;

if (isset($_GET['id_modelo'])) {
  $modelo_id = (int)$_GET['id_modelo'];

  // Carregar título do modelo
  $stmt = $conexao->prepare("SELECT titulo FROM modelos_prontuario WHERE id = ?");
  $stmt->execute([$modelo_id]);
  $titulo = $stmt->fetchColumn();

  if (!$titulo) {
    die('Modelo não encontrado ou sem permissão.');
  }

  // Carregar perguntas do modelo
  $stmt = $conexao->prepare("SELECT id, ordem, tipo, pergunta, opcoes FROM perguntas_modelo_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ? ORDER BY ordem ASC");
  $stmt->execute([$modelo_id]);
  $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
    </style>
</head>
<body>
    
<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-clipboard2-heart"></i>
                <?= $modelo_id ? "Editar Modelo de prontuario" : "Criar Ficha de prontuario" ?> <i class="bi bi-question-square-fill"onclick="ajudaProntuario()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para salvar este modelo de prontuario
        </div>
    </div>
    
  <form data-step="1" id="form-modelo-prontuario" class="card" enctype="multipart/form-data">
      
<div class="form-row">
                <div class="health-form-group">

      <label class="health-label" for="titulo_modelo">Título do Modelo:</label>
      <input class="health-input" data-step="2" type="text" name="titulo_modelo" id="titulo_modelo" required value="<?= htmlspecialchars($titulo) ?>" />
    </div>
</div>
    <div data-step="3" id="perguntasContainer"></div>

    <?php if ($modelo_id): ?>
      <input type="hidden" name="modelo_id" value="<?= $modelo_id ?>" />
    <?php endif; ?>

    <div class="card-group btn_2">
      <button data-step="6" class="health-btn health-btn-primary" type="button" onclick="adicionarCampo()"><i class="bi bi-plus-lg"></i> Adicionar Pergunta</button>
      <button data-step="7" class="health-btn health-btn-success" type="button" onclick="salvarModelo()"><i class="bi bi-check-lg"></i> Salvar Modelo</button>
    </div>
  </form>
</div>
<script>
  const container = document.getElementById('perguntasContainer');
  const tokenEmp = <?= json_encode($_SESSION['token_emp']) ?>;

  let index = 0;

  // Inicializa Sortable para arrastar perguntas e mudar ordem
  new Sortable(container, {
    animation: 150,
  });

  // Adiciona pergunta, opcionalmente recebendo dados para edição
  function adicionarCampo(pergunta = null) {
  const texto = pergunta ? pergunta.pergunta : '';
  const tipo = pergunta ? pergunta.tipo : 'text';
  const opcoes = pergunta && pergunta.opcoes ? pergunta.opcoes : '';
  const pergunta_id = pergunta && pergunta.id ? pergunta.id : '';
  
  let html = `
 <div class="form-row">
    <div class="health-form-group" draggable="true">
      <input type="hidden" name="perguntas[${index}][id]" value="${pergunta_id}">
      <label class="health-label">Pergunta:</label>
      <input class="health-input" data-step="4" type="text" name="perguntas[${index}][texto]" required value="${texto.replace(/"/g, '&quot;')}">
      <label class="health-label">Tipo:</label>
      <select required class="health-select" data-step="5" name="perguntas[${index}][tipo]" onchange="mostrarOpcoes(this, ${index})">
        <option value="text" ${tipo === 'text' ? 'selected' : ''}>Texto Pequeno</option>
        <option value="textarea" ${tipo === 'textarea' ? 'selected' : ''}>Texto Grande</option>
        <option value="number" ${tipo === 'number' ? 'selected' : ''}>Número</option>
        <option value="radio" ${tipo === 'radio' ? 'selected' : ''}>Escolha Única</option>
        <option value="checkbox" ${tipo === 'checkbox' ? 'selected' : ''}>Múltipla Seleção</option>
        <option value="select" ${tipo === 'select' ? 'selected' : ''}>Lista</option>
      </select>
      <div id="opcoes-${index}" style="display: ${['radio','checkbox','select'].includes(tipo) ? 'block' : 'none'}">
      <label class="health-label">Opções (separadas por ;)</label>
      <input required class="health-input" type="text" name="perguntas[${index}][opcoes]" value="${opcoes.replace(/"/g, '&quot;')}">
  `;

  // Mostrar campo upload para imagens em radio e checkbox
  if (tipo === 'radio' || tipo === 'checkbox') {
    html += `
      <label class="health-label">Imagens para as opções (ordem correspondente)</label>
      <input class="health-input" type="file" name="perguntas[${index}][imagens][]" multiple accept="image/png, image/jpeg"><br>
      <small>As imagens devem estar na mesma ordem das opções (separadas por ponto e vírgula)</small>
    `;
  }

  // Aqui você pode colocar o preview das imagens já existentes (se estiver editando)
  if (pergunta_id && (tipo === 'radio' || tipo === 'checkbox') && opcoes) {
    const tokenEmp = <?= json_encode($_SESSION['token_emp']) ?>;
    const opArray = opcoes.split(';');
    opArray.forEach(op => {
      const opTrim = op.trim();
      const nomeImg = opTrim.toLowerCase().replace(/\s+/g, '_');
      const path = `../imagens/${tokenEmp}/${pergunta_id}_${nomeImg}.png`;

      html += `
        <div style="margin: 0.5rem 0;" id="preview-${pergunta_id}-${nomeImg}">
          <strong>${opTrim}</strong><br>
          <img src="${path}" alt="${opTrim}" style="max-width: 150px; border: 1px solid #ccc; border-radius: 6px; display:none;" 
               onload="this.style.display='block';" 
               onerror="this.parentNode.style.display='none';">
        </div>
      `;
    });
  }

  html += `</div></div>`;
  container.insertAdjacentHTML('beforeend', html);
  index++;
}

  // Mostra ou esconde campo de opções baseado no tipo da pergunta
  function mostrarOpcoes(select, idx) {
  const tipo = select.value;
  const opcoesDiv = document.getElementById(`opcoes-${idx}`);
  const uploadDiv = document.getElementById(`upload-${idx}`);
  opcoesDiv.style.display = (tipo === 'radio' || tipo === 'checkbox' || tipo === 'select') ? 'block' : 'none';
  if (uploadDiv) {
    uploadDiv.style.display = tipo === 'radio' ? 'block' : 'none';
  }
}

  // Salvar modelo via fetch para PHP
  function salvarModelo() {
    const form = document.getElementById('form-modelo-prontuario');
    const formData = new FormData(form);

    // Corrige a ordem das perguntas antes de enviar (pega a ordem do Sortable)
    const grupos = container.querySelectorAll('.card-group');
    grupos.forEach((grupo, i) => {
      // Atualiza os names das perguntas com nova ordem
      const inputTexto = grupo.querySelector('input[type="text"][name^="perguntas"]');
      const selectTipo = grupo.querySelector('select[name^="perguntas"]');
      const inputOpcoes = grupo.querySelector('input[type="text"][name^="perguntas"][name$="[opcoes]"]');

      if (inputTexto) inputTexto.name = `perguntas[${i}][texto]`;
      if (selectTipo) selectTipo.name = `perguntas[${i}][tipo]`;
      if (inputOpcoes) inputOpcoes.name = `perguntas[${i}][opcoes]`;
    });

    // Atualiza o formData para refletir os novos names
    const novoFormData = new FormData(form);
    
    fetch('prontuario_salvar_modelo.php', {
      method: 'POST',
      body: novoFormData
    })
    .then(res => res.json())
    .then(data => {
      if (data.sucesso) {
        Swal.fire('Sucesso!', 'Modelo salvo com sucesso.', 'success');
        if (!<?= json_encode((bool)$modelo_id) ?>) {
          // Se foi criação, redireciona para edição do novo modelo
          window.location.href = 'prontuario_criar_modelo.php?id=' + data.modelo_id;
        }
      } else {
        Swal.fire('Erro', 'Falha ao salvar modelo.', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire('Erro', 'Erro na requisição.', 'error');
    });
  }

  // Carregar perguntas já salvas (PHP injetado)
  <?php if ($perguntas): ?>
    const perguntasSalvas = <?= json_encode($perguntas) ?>;
    perguntasSalvas.forEach(p => adicionarCampo(p));
  <?php else: ?>
    // Começa com uma pergunta vazia para novos modelos
    adicionarCampo();
  <?php endif; ?>
</script>
</body>
</html>
