<?php
require('../../config/database.php');
require('../verifica_login.php');

$id = intval($_POST['id']);
$cid = intval($_POST['cid']);
$mensagem = trim($_POST['mensagem']);
$data = $_POST['agendar_para'];

$stmt = $conexao->prepare("UPDATE agendamentos_automaticos SET mensagem = ?, agendar_para = ? WHERE id = ?");
$stmt->execute([$mensagem, $data, $id]);

header("Location: contato.php?id=$cid");
exit;
