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

// Caminho para o diretório de armazenamento
$diretorio = 'assinaturas/';

// Verificar se o diretório existe ou criar se necessário
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

// Caminho completo para o arquivo
$caminho = $diretorio . $nome . '.png';

// Salvar a imagem
file_put_contents($caminho, $decodedImage);

// Definir as permissões
chmod($caminho, 0755);

$query = $conexao->prepare("UPDATE contrato SET assinado = 'Sim', assinado_data = :assinado_data WHERE email = :email");
$query->execute(array('assinado_data' => date('Y-m-d H:i:s'), 'email' => $_SESSION['email']));

?>