<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$modelo_id = $_POST['modelo_id'];
$respostas = $_POST['respostas'] ?? [];
$paciente_id = $_POST['paciente_id'] ?? null;

if (!$paciente_id) {
    die("ID do paciente não informado.");
}

foreach ($respostas as $pergunta_id => $resposta) {
    // Se for array (ex: select múltiplo ou checkbox), transforma em string separada por ";"
    if (is_array($resposta)) {
        $resposta = implode(';', array_map('trim', $resposta));
    }

    // Verifica se já existe resposta
    $stmt_check = $conexao->prepare("SELECT id FROM respostas_anamnese WHERE modelo_id = ? AND paciente_id = ? AND pergunta_id = ?");
    $stmt_check->execute([$modelo_id, $paciente_id, $pergunta_id]);
    $existe = $stmt_check->fetchColumn();

    if ($existe) {
        // Atualiza resposta
        $stmt_update = $conexao->prepare("UPDATE respostas_anamnese SET resposta = ? WHERE id = ?");
        $stmt_update->execute([$resposta, $existe]);
    } else {
        // Insere nova resposta
        $stmt_insert = $conexao->prepare("INSERT INTO respostas_anamnese (modelo_id, paciente_id, pergunta_id, resposta) VALUES (?, ?, ?, ?)");
        $stmt_insert->execute([$modelo_id, $paciente_id, $pergunta_id, $resposta]);
    }
}

header("Location: anamnese_preencher.php?modelo_id=$modelo_id&paciente_id=$paciente_id&salvo=1");
exit;
