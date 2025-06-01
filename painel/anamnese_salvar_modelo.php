<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$titulo = $_POST['titulo_modelo'];
$perguntas = $_POST['perguntas'] ?? [];
$modelo_id = $_POST['modelo_id'] ?? null;

if ($modelo_id) {
  // Atualiza título
  $stmt = $conexao->prepare("UPDATE modelos_anamnese SET titulo = ? WHERE id = ?");
  $stmt->execute([$titulo, $modelo_id]);

  // Busca perguntas atuais do banco
  $stmt = $conexao->prepare("SELECT id FROM perguntas_modelo WHERE modelo_id = ?");
  $stmt->execute([$modelo_id]);
  $ids_atuais = $stmt->fetchAll(PDO::FETCH_COLUMN);

  $ids_recebidos = [];
  foreach ($perguntas as $ordem => $pergunta) {
    $pergunta_id = $pergunta['id'] ?? null;

    if ($pergunta_id && in_array($pergunta_id, $ids_atuais)) {
      // Atualiza pergunta existente
      $stmt = $conexao->prepare("UPDATE perguntas_modelo SET ordem = ?, tipo = ?, pergunta = ?, opcoes = ? WHERE id = ? AND modelo_id = ?");
      $stmt->execute([
        $ordem,
        $pergunta['tipo'],
        $pergunta['texto'],
        $pergunta['opcoes'] ?? null,
        $pergunta_id,
        $modelo_id
      ]);
      $ids_recebidos[] = $pergunta_id;
    } else {
      // Insere nova pergunta
      $stmt = $conexao->prepare("INSERT INTO perguntas_modelo (modelo_id, ordem, tipo, pergunta, opcoes) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([
        $modelo_id,
        $ordem,
        $pergunta['tipo'],
        $pergunta['texto'],
        $pergunta['opcoes'] ?? null
      ]);
      $ids_recebidos[] = $conexao->lastInsertId();
    }
  }

  // Deleta perguntas que não foram enviadas (removidas no formulário)
  $ids_para_deletar = array_diff($ids_atuais, $ids_recebidos);
  if ($ids_para_deletar) {
    $in  = str_repeat('?,', count($ids_para_deletar) - 1) . '?';
    $stmt = $conexao->prepare("DELETE FROM perguntas_modelo WHERE id IN ($in) AND modelo_id = ?");
    $params = array_merge($ids_para_deletar, [$modelo_id]);
    $stmt->execute($params);
  }

} else {
  // Insere novo modelo
  $stmt = $conexao->prepare("INSERT INTO modelos_anamnese (titulo) VALUES (?)");
  $stmt->execute([$titulo]);
  $modelo_id = $conexao->lastInsertId();

  // Insere perguntas novas
  foreach ($perguntas as $ordem => $pergunta) {
    $stmt = $conexao->prepare("INSERT INTO perguntas_modelo (modelo_id, ordem, tipo, pergunta, opcoes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $modelo_id,
      $ordem,
      $pergunta['tipo'],
      $pergunta['texto'],
      $pergunta['opcoes'] ?? null
    ]);
  }
}

echo json_encode(['sucesso' => true, 'modelo_id' => $modelo_id]);
