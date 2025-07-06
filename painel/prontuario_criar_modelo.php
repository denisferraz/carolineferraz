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
  <meta charset="UTF-8" />
  <title><?= $modelo_id ? "Editar Modelo" : "Criar Prontuario" ?></title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <link rel="stylesheet" href="<?= $css_path ?>" />
  <style>
    .card {
      padding: 2rem;
      border-radius: 12px;
      max-width: 700px;
      margin: 2rem auto;
      font-family: Arial, sans-serif;
    }
    .card h2 {
      margin-bottom: 1.5rem;
    }
    .card-group {
      margin-bottom: 1.2rem;
    }
    .card-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    .card-group input[type="text"],
    .card-group select {
      width: 100%;
      padding: 0.5rem;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
    }
    .card-group select {
      appearance: none;
    }
    .opcoes-horizontal {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.5rem;
    }
    .opcoes-horizontal label {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      padding: 0.4rem 0.8rem;
      border-radius: 6px;
      cursor: pointer;
      white-space: nowrap;
      font-size: 0.95rem;
    }
    .btn_2 button {
      margin-right: 1rem;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s;
    }
  </style>
</head>
<body>
  <form data-step="1" id="form-modelo-prontuario" class="card" enctype="multipart/form-data">
    <h2><?= $modelo_id ? "Editar Modelo de Prontuario" : "Criar Prontuario" ?> <i class="bi bi-question-square-fill"onclick="ajudaProntuario()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>

    <div class="card-group">
      <label for="titulo_modelo">Título do Modelo:</label>
      <input data-step="2" type="text" name="titulo_modelo" id="titulo_modelo" required value="<?= htmlspecialchars($titulo) ?>" />
    </div>

    <div data-step="3" id="perguntasContainer"></div>

    <?php if ($modelo_id): ?>
      <input type="hidden" name="modelo_id" value="<?= $modelo_id ?>" />
    <?php endif; ?>

    <div class="card-group btn_2">
      <button data-step="6" type="button" onclick="adicionarCampo()">Adicionar Pergunta</button>
      <button data-step="7" type="button" onclick="salvarModelo()">Salvar Modelo</button>
    </div>
  </form>

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
    <div class="card-group" draggable="true">
      <input type="hidden" name="perguntas[${index}][id]" value="${pergunta_id}">
      <label>Pergunta:</label>
      <input data-step="4" type="text" name="perguntas[${index}][texto]" required value="${texto.replace(/"/g, '&quot;')}">
      <label>Tipo:</label>
      <select data-step="5" name="perguntas[${index}][tipo]" onchange="mostrarOpcoes(this, ${index})">
        <option value="text" ${tipo === 'text' ? 'selected' : ''}>Texto</option>
        <option value="number" ${tipo === 'number' ? 'selected' : ''}>Número</option>
        <option value="radio" ${tipo === 'radio' ? 'selected' : ''}>Escolha Única</option>
        <option value="checkbox" ${tipo === 'checkbox' ? 'selected' : ''}>Múltipla Seleção</option>
        <option value="select" ${tipo === 'select' ? 'selected' : ''}>Lista</option>
      </select>
      <div id="opcoes-${index}" style="display: ${['radio','checkbox','select'].includes(tipo) ? 'block' : 'none'}">
      <label>Opções (separadas por ;)</label>
      <input type="text" name="perguntas[${index}][opcoes]" value="${opcoes.replace(/"/g, '&quot;')}">
  `;

  // Mostrar campo upload para imagens em radio e checkbox
  if (tipo === 'radio' || tipo === 'checkbox') {
    html += `
      <label>Imagens para as opções (ordem correspondente)</label>
      <input type="file" name="perguntas[${index}][imagens][]" multiple accept="image/png, image/jpeg">
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
