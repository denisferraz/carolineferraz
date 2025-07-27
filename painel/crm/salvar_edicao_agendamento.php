<?php
require('../../config/database.php');
require('../verifica_login.php');

$id = intval($_POST['id']);
$cid = intval($_POST['cid']);
$mensagem = trim($_POST['mensagem']);
$data = $_POST['agendar_para'];

//Criptografa a mensagem
$dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
$mensagem_cripto = base64_encode($dados_criptografados);

$stmt = $conexao->prepare("UPDATE agendamentos_automaticos SET mensagem = ?, agendar_para = ? WHERE id = ?");
$stmt->execute([$mensagem_cripto, $data, $id]);

header("Location: contato.php?id=$cid");
exit;
