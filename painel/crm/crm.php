<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

$token_emp = $_SESSION['token_emp'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM - Painel de Contatos WhatsApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilo personalizado -->
  <style>
    body {
      background-color: #1e1e2f;
      color: #fff;
    }
    .kanban-col {
      background-color: #2a2a3b;
      border-radius: 10px;
      padding: 15px;
      min-height: 500px;
      max-height: 80vh;
      overflow-y: auto;
    }
    .contato-card {
      background-color: #343a40;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      box-shadow: 0 0 5px rgba(255,255,255,0.05);
    }
    .contato-card strong {
      font-size: 16px;
    }
    .contato-card small {
      font-size: 13px;
      color: #aaa;
    }
    .etapa-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
      text-align: center;
    }
    .btn-ver {
      width: 100%;
      margin-top: 5px;
    }
    .scroll-horizontal {
      overflow-x: auto;
      white-space: nowrap;
    }
    .col-kanban {
      display: inline-block;
      vertical-align: top;
      width: 250px;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<!-- <a href="atualizar_mensagens.php">Atualizar Mensagens</a><br> -->

<div class="container-fluid p-4">
  <h2 class="mb-4">📋 CRM - Contatos WhatsApp!</h2>

  <?php if (!empty($_SESSION['notificacao_followup'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['notificacao_followup'] ?> notificações de follow-up enviadas com sucesso!
  </div>
  <?php unset($_SESSION['notificacao_followup']); ?>
<?php endif; ?>

  <form method="get" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="busca" class="form-control" placeholder="🔍 Buscar por nome ou número" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    </div>
    <div class="col-md-3">
        <select name="etapa" class="form-select">
            <option value="">Todas as etapas</option>
            <?php
            foreach ($etapas as $etapa) {
                $selected = (($_GET['etapa'] ?? '') === $etapa) ? 'selected' : '';
                echo "<option value='$etapa' $selected>$etapa</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<div class="row g-3 mb-4">

<?php
$stmt = $conexao->prepare("SELECT * FROM contatos WHERE token_emp = :token AND followup = :hoje ORDER BY nome ASC");
$stmt->execute(['token' => $token_emp, 'hoje' => $hoje]);
$followups = $stmt->fetchAll();

if ($followups):
?>
<div class="alert alert-warning">
  <strong>📆 Acompanhamentos de hoje:</strong><br>
  <?php foreach ($followups as $c): ?>
    <div class="mb-1">
      <a href="contato.php?id=<?= $c['id'] ?>" class="text-dark fw-bold"><?= htmlspecialchars($c['nome']) ?></a>
      <small class="text-muted">(<?= $c['numero'] ?>)</small>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

  <div class="scroll-horizontal">
      <?php
    $etapa_filtro = $_GET['etapa'] ?? '';
    $lista_etapas = $etapa_filtro ? [$etapa_filtro] : $etapas;

    foreach ($lista_etapas as $etapa_for):
    ?>

<?php if ($etapa_filtro): ?>
  <div class="alert alert-info">
    Mostrando apenas contatos da etapa: <strong><?= htmlspecialchars($etapa_filtro) ?></strong>
    <a href="crm.php" class="btn btn-sm btn-secondary ms-2">Limpar filtro</a>
  </div>
<?php endif; ?>

      <div class="col-kanban">
        <div class="kanban-col">
          <div class="etapa-title"><?= $etapa_for ?></div>
          <?php
          $busca = $_GET['busca'] ?? '';
          $etapa = $_GET['etapa'] ?? '';
          
          $sql = "SELECT * FROM contatos WHERE token_emp = :token AND etapa = :etapa";
          $params = [
              'token' => $token_emp,
              'etapa' => $etapa_for
          ];

          if ($busca) {
              $sql .= " AND (nome LIKE :busca OR numero LIKE :busca)";
              $params['busca'] = "%$busca%";
          }
          
          $sql .= " ORDER BY ultimo_contato DESC";
          $stmt = $conexao->prepare($sql);
          $stmt->execute($params);

          //$stmt = $conexao->prepare("SELECT * FROM contatos WHERE etapa = ? AND token_emp = ? ORDER BY ultimo_contato DESC");
          //$stmt->execute([$etapa, $token_emp]);
          while ($contato = $stmt->fetch(PDO::FETCH_ASSOC)):
          ?>
            <div class="contato-card">
              <?php if ($contato['followup'] == date('Y-m-d')): ?>
                <span class="badge bg-warning text-dark">📆 Hoje</span>
              <?php endif; ?>
              <strong><?= htmlspecialchars($contato['nome']) ?></strong><br>
              <small><?= $contato['numero'] ?></small><br>
              <a href="contato.php?id=<?= $contato['id'] ?>" class="btn btn-sm btn-primary btn-ver">Ver histórico</a>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
