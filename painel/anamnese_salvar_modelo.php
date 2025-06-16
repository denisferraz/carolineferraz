<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$titulo = $_POST['titulo_modelo'];
$perguntas = $_POST['perguntas'] ?? [];
$modelo_id = $_POST['modelo_id'] ?? null;

function salvarImagensDaPergunta($pergunta_id, $ordem, $opcoes_raw, $index_input, $token_emp) {
  $pasta = "../imagens/{$token_emp}/";
  if (!is_dir($pasta)) mkdir($pasta, 0777, true);

  $opcoes = explode(';', $opcoes_raw);
  $arquivos = $_FILES['perguntas']['tmp_name'][$index_input]['imagens'] ?? [];

  foreach ($opcoes as $i => $op) {
      if (!isset($arquivos[$i]) || empty($arquivos[$i])) continue;

      $nome_limpado = strtolower(preg_replace('/\s+/', '_', trim($op)));
      $destino = $pasta . "{$pergunta_id}_{$nome_limpado}.png";

      move_uploaded_file($arquivos[$i], $destino);
  }
}

if ($modelo_id) {
    // Atualiza título
    $stmt = $conexao->prepare("UPDATE modelos_anamnese SET titulo = ? WHERE token_emp = '{$_SESSION['token_emp']}' AND id = ?");
    $stmt->execute([$titulo, $modelo_id]);

    // Perguntas existentes
    $stmt = $conexao->prepare("SELECT id FROM perguntas_modelo WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ?");
    $stmt->execute([$modelo_id]);
    $ids_atuais = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $ids_recebidos = [];
    foreach ($perguntas as $ordem => $pergunta) {
        $pergunta_id = $pergunta['id'] ?? null;
        $tipo = $pergunta['tipo'];
        $texto = $pergunta['texto'];
        $opcoes = $pergunta['opcoes'] ?? null;

        if ($pergunta_id && in_array($pergunta_id, $ids_atuais)) {
            // Atualizar
            $stmt = $conexao->prepare("UPDATE perguntas_modelo SET ordem = ?, tipo = ?, pergunta = ?, opcoes = ? WHERE token_emp = '{$_SESSION['token_emp']}' AND id = ? AND modelo_id = ?");
            $stmt->execute([$ordem, $tipo, $texto, $opcoes, $pergunta_id, $modelo_id]);
        } else {
            // Inserir nova
            $stmt = $conexao->prepare("INSERT INTO perguntas_modelo (modelo_id, ordem, tipo, pergunta, opcoes, token_emp) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$modelo_id, $ordem, $tipo, $texto, $opcoes, $_SESSION['token_emp']]);
            $pergunta_id = $conexao->lastInsertId();
        }

        $ids_recebidos[] = $pergunta_id;

        // 👉 Salva imagens se for tipo radio
        if (in_array($tipo, ['radio', 'checkbox']) && isset($_FILES['perguntas']['tmp_name'][$ordem]['imagens'])) {
          salvarImagensDaPergunta($pergunta_id, $ordem, $opcoes, $ordem, $_SESSION['token_emp']);
        }
    }

    // Deleta perguntas removidas
    $ids_para_deletar = array_diff($ids_atuais, $ids_recebidos);
    if ($ids_para_deletar) {
        $in = str_repeat('?,', count($ids_para_deletar) - 1) . '?';
        $stmt = $conexao->prepare("DELETE FROM perguntas_modelo WHERE token_emp = '{$_SESSION['token_emp']}' AND id IN ($in) AND modelo_id = ?");
        $params = array_merge($ids_para_deletar, [$modelo_id]);
        $stmt->execute($params);
    }

} else {
    // Novo modelo
    $stmt = $conexao->prepare("INSERT INTO modelos_anamnese (titulo, token_emp) VALUES (?, ?)");
    $stmt->execute([$titulo, $_SESSION['token_emp']]);
    $modelo_id = $conexao->lastInsertId();

    foreach ($perguntas as $ordem => $pergunta) {
        $tipo = $pergunta['tipo'];
        $texto = $pergunta['texto'];
        $opcoes = $pergunta['opcoes'] ?? null;

        $stmt = $conexao->prepare("INSERT INTO perguntas_modelo (modelo_id, ordem, tipo, pergunta, opcoes, token_emp) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$modelo_id, $ordem, $tipo, $texto, $opcoes, $_SESSION['token_emp']]);

        $pergunta_id = $conexao->lastInsertId();

        // 👉 Salva imagens se for tipo radio
        if (in_array($tipo, ['radio', 'checkbox']) && isset($_FILES['perguntas']['tmp_name'][$ordem]['imagens'])) {
          salvarImagensDaPergunta($pergunta_id, $ordem, $opcoes, $ordem, $_SESSION['token_emp']);
        }
    }
}

echo json_encode(['sucesso' => true, 'modelo_id' => $modelo_id]);
