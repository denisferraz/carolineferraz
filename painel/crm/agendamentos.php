<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];

$stmt = $conexao->prepare("
  SELECT a.*, c.nome FROM agendamentos_automaticos a
  LEFT JOIN contatos c ON a.contato_id = c.id
  WHERE a.token_emp = ? AND a.enviado = 0
  ORDER BY agendar_para ASC
");
$stmt->execute([$token_emp]);
$agendamentos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>📆 Agendamentos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-4">
  <h3>📆 Agendamentos Pendentes</h3>
  <table class="table table-dark table-hover mt-3">
    <thead>
      <tr>
        <th>Contato</th>
        <th>Mensagem</th>
        <th>Envia em</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($agendamentos as $ag): ?>
      <tr>
        <td><?= htmlspecialchars($ag['nome'] ?? 'Desconhecido') ?> <br><small><?= $ag['numero'] ?></small></td>
        <td><?= nl2br(htmlspecialchars($ag['mensagem'])) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($ag['agendar_para'])) ?></td>
        <td>
          <a href="editar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $ag['contato_id'] ?>" class="btn btn-warning btn-sm">✏️</a>
          <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $ag['contato_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmar exclusão?')">🗑️</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
