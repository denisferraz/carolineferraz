<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['unico'];
}

$image = $_POST['assinatura'];
$decodedImage = base64_decode(substr($image, strpos($image, ',') + 1));
file_put_contents("assinaturas/$nome.png", $decodedImage);

$query = $conexao->prepare("UPDATE contrato SET assinado = 'Sim', assinado_data = :assinado_data WHERE email = :email");
$query->execute(array('assinado_data' => date('Y-m-d H:i:s'), 'email' => $_SESSION['email']));

?>