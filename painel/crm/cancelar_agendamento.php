<?php
require('../../config/database.php');
require('../verifica_login.php');

$id = intval($_GET['id']);
$cid = intval($_GET['cid']);

$stmt = $conexao->prepare("DELETE FROM agendamentos_automaticos WHERE id = ?");
$stmt->execute([$id]);

header("Location: contato.php?id=$cid");
exit;
