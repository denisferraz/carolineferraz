<?php
require('../../config/database.php');
require('../verifica_login.php');

$id = intval($_GET['id']);
$cid = intval($_GET['cid']);

$stmt = $conexao->prepare("SELECT * FROM agendamentos_automaticos WHERE id = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch();

if (!$agendamento) exit("Agendamento não encontrado.");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Agendamento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-4">

<h4>✏️ Editar Agendamento</h4>

<form action="salvar_edicao_agendamento.php" method="post">
  <input type="hidden" name="id" value="<?= $id ?>">
  <input type="hidden" name="cid" value="<?= $cid ?>">

  <div class="mb-3">
    <label>Mensagem:</label>
    <textarea name="mensagem" class="form-control" rows="3" required><?= htmlspecialchars($agendamento['mensagem']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Data e Hora:</label>
    <input type="datetime-local" name="agendar_para" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($agendamento['agendar_para'])) ?>" required>
  </div>

  <button type="submit" class="btn btn-success">Salvar</button>
  <a href="contato.php?id=<?= $cid ?>" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
