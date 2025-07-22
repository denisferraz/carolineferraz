<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];

$pdo = $conexao;

if (!isset($_GET['id'])) {
    echo "Contato não encontrado.";
    exit;
}

$contato_id = intval($_GET['id']);

// Buscar dados do contato
$stmt = $pdo->prepare("SELECT * FROM contatos WHERE id = ? AND token_emp = ?");
$stmt->execute([$contato_id, $token_emp]);
$contato = $stmt->fetch();

if (!$contato) {
    echo "Contato inválido.";
    exit;
}

// Buscar interações
$stmt = $pdo->prepare("SELECT * FROM interacoes WHERE contato_id = ? AND token_emp = ? ORDER BY data DESC");
$stmt->execute([$contato_id, $token_emp]);
$interacoes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Contato - <?= htmlspecialchars($contato['nome']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #1e1e2f; color: #fff; }
    .mensagem { padding: 10px; border-radius: 8px; margin-bottom: 8px; max-width: 70%; }
    .cliente { background-color: #343a40; text-align: left; }
    .empresa { background-color: #0d6efd; text-align: right; margin-left: auto; }
    .mensagem small { display: block; font-size: 12px; color: #bbb; }
    .header-contato { margin-bottom: 20px; }
  </style>
</head>
<body>

<div class="container p-4">
  <div class="header-contato">
    <h4><form action="atualizar_etapa.php" method="post" class="d-inline">
      <input type="hidden" name="id" value="<?= $contato['id'] ?>">
      <select name="etapa" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
        <?php
        $etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
        foreach ($etapas as $etapa) {
            $selected = ($etapa === $contato['etapa']) ? 'selected' : '';
            echo "<option value='$etapa' $selected>$etapa</option>";
        }
        ?>
      </select>
    </form>📞 <?= htmlspecialchars($contato['nome']) ?> <small>(<?= $contato['numero'] ?>)</small> <a href="crm.php" class="btn btn-secondary btn-sm ms-2">⬅ Voltar</a></h4>
    <br>Follow-up 
    <form action="atualizar_followup.php" method="post" class="d-inline ms-2">
      <input type="hidden" name="id" value="<?= $contato['id'] ?>">
      <input type="date" name="followup" value="<?= $contato['followup'] ?>" class="form-control form-control-sm d-inline w-auto" onchange="this.form.submit()">
  </form>
  </div>
<hr class="my-4">
<h6>📅 Agendar mensagem futura</h6>
<form action="agendar_mensagem.php" method="post">
  <input type="hidden" name="contato_id" value="<?= $contato_id ?>">
  <input type="hidden" name="numero" value="<?= $contato['numero'] ?>">
  
  <div class="mb-2">
    <label class="form-label">Mensagem:</label>
    <textarea name="mensagem" class="form-control" required rows="3"></textarea>
  </div>
  <div class="mb-2">
    <label class="form-label">Data e Hora de Envio:</label>
    <input type="datetime-local" name="agendar_para" class="form-control" required>
  </div>
  
  <button type="submit" class="btn btn-outline-warning">⏰ Agendar Envio</button>
</form>
<h6 class="mt-4">📆 Mensagens Agendadas</h6>
<ul class="list-group list-group-flush">
<?php
$stmt = $conexao->prepare("
    SELECT * FROM agendamentos_automaticos 
    WHERE contato_id = ? AND enviado = 0
    ORDER BY agendar_para ASC
");
$stmt->execute([$contato_id]);
$agendados = $stmt->fetchAll();

if ($agendados):
    foreach ($agendados as $ag): ?>
    <li class="list-group-item bg-dark text-white">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                📨 <?= nl2br(htmlspecialchars($ag['mensagem'])) ?><br>
                ⏰ <small><?= date('d/m/Y H:i', strtotime($ag['agendar_para'])) ?></small>
            </div>
            <div class="ms-2 text-end">
                <a href="editar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $contato_id ?>" class="btn btn-sm btn-warning">✏️</a>
                <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $contato_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancelar este agendamento?')">🗑️</a>
            </div>
        </div>
    </li>
<?php endforeach;
else:
    echo "<li class='list-group-item bg-dark text-muted'>Nenhum agendamento</li>";
endif;
?>
</ul>

<br>
  <h5>📨 Histórico de Conversas</h5>
  <div class="mt-3">
    <?php foreach ($interacoes as $msg): ?>
      <div class="mensagem <?= $msg['origem'] === 'cliente' ? 'cliente' : 'empresa' ?>">
      <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
      <small>
        <?= date('d/m/Y H:i', strtotime($msg['data'])) ?> -
        <?= $msg['origem'] === 'cliente' ? htmlspecialchars($contato['nome']) : ($config_empresa ?? 'Empresa') ?>
      </small>
    </div>
    <?php endforeach; ?>
  </div>

  <hr class="mt-4 mb-3">
  <h6>✏️ Enviar resposta via Whatsapp</h6>
  <form action="enviar_evolution.php" method="post">
      <input type="hidden" name="contato_id" value="<?= $contato_id ?>">
      <input type="hidden" name="numero" value="<?= $contato['numero'] ?>">
      <textarea name="mensagem" class="form-control mb-2" placeholder="Digite a resposta..." rows="3" required></textarea>
      <button type="submit" class="btn btn-success">Enviar</button>
  </form>
</div>

</body>
</html>
