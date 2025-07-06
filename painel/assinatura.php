<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

require '../vendor/autoload.php'; // Certifique-se que isso está correto para seu projeto

use Dompdf\Dompdf;

if (isset($_POST['assinatura'])) {

  $email = $_SESSION['email'];
  $cpf = mysqli_real_escape_string($conn_msqli, $_GET['cpf']);
  
  $token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
  $data_assinatura = date('Y-m-d H:i:s');
  $data_assinaturas = date('YmdHis', strtotime("$data_assinatura"));
  
  $image = $_POST['assinatura'];
  $decodedImage = base64_decode(substr($image, strpos($image, ',') + 1));
  
  // Caminho para o diretório de armazenamento
  $diretorio = '../assinaturas/' . $_SESSION['token_emp'] . '/';
  
  // Verificar se o diretório existe ou criar se necessário
  if (!is_dir($diretorio)) {
      mkdir($diretorio, 0755, true);
  }
  
  //Deleta assinatura anterior
  if($cpf == '123'){
    $assinaturaAnteriorPattern = $diretorio . $token . '-*.png';
  }else{
    $assinaturaAnteriorPattern = $diretorio . $cpf . '-' . $token . '-*.png';
  }
  $assinaturasAnteriores = glob($assinaturaAnteriorPattern);
  if (!empty($assinaturasAnteriores)) {
    foreach ($assinaturasAnteriores as $assinaturaAnterior) {
      unlink($assinaturaAnterior);
    }
  }
  
  // Caminho completo para o arquivo
if($cpf == '123'){
  $caminho = $diretorio . $token . '.png';
}else{
  $caminho = $diretorio . $cpf . '-' . $token . '-'. $data_assinaturas . '.png';
}
  // Salvar a imagem
  file_put_contents($caminho, $decodedImage);
  
  // Definir as permissões
  chmod($caminho, 0755);
  
if($cpf != '123'){
  $query = $conexao->prepare("UPDATE contrato SET assinado = 'Sim', assinado_data = :assinado_data WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
  $query->execute(array('assinado_data' => $data_assinatura, 'email' => $email));
}

}else if (isset($_FILES['pdf']) && isset($_POST['token'])) {
  $pdf = $_FILES['pdf'];
  $token = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['token']); // Sanitiza o token
  $dir = '../arquivos/' . $_SESSION['token_emp'] . '/' . $token . '/Contratos/';

  if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
  }

  $caminhoFinal = $dir . basename($pdf['name']);

  if (move_uploaded_file($pdf['tmp_name'], $caminhoFinal)) {
      echo "Arquivo salvo em: " . $caminhoFinal;
  } else {
      http_response_code(500);
      echo "Erro ao mover o arquivo.";
  }
}

?>
