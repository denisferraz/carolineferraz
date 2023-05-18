<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$email = recuperarEmailToken();

$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$email}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['unico'];
}

$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
$data_assinatura = date('Y-m-d H:i:s');
$data_assinaturas = date('YmdHis', strtotime("$data_assinatura"));

$image = $_POST['assinatura'];
$decodedImage = base64_decode(substr($image, strpos($image, ',') + 1));

// Caminho para o diretório de armazenamento
$diretorio = 'assinaturas/';

// Verificar se o diretório existe ou criar se necessário
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

//Deleta assinatura anterior
$assinaturaAnteriorPattern = $diretorio . $nome . '-' . $confirmacao . '-*.png';
$assinaturasAnteriores = glob($assinaturaAnteriorPattern);
if (!empty($assinaturasAnteriores)) {
  foreach ($assinaturasAnteriores as $assinaturaAnterior) {
    unlink($assinaturaAnterior);
  }
}

// Caminho completo para o arquivo
$caminho = $diretorio . $nome . '-' . $confirmacao . '-'. $data_assinaturas . '.png';

// Salvar a imagem
file_put_contents($caminho, $decodedImage);

// Definir as permissões
chmod($caminho, 0755);

$query = $conexao->prepare("UPDATE contrato SET assinado = 'Sim', assinado_data = :assinado_data WHERE email = :email");
$query->execute(array('assinado_data' => $data_assinatura, 'email' => $email));

?>