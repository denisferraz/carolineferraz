<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$id = $_GET['id'];
$_SESSION['configuracao'] = $id;
http_response_code(200);

$query = $conexao->prepare("UPDATE painel_users SET configuracao = :configuracao WHERE token = :token");
$query->execute(array('configuracao' => $id, 'token' => $_SESSION['token']));