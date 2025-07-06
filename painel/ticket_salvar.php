<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

$user_id = $client_id;
$subject = $_POST['subject'];
$priority = $_POST['priority'];
$description = $_POST['description'];

$stmt = $conexao->prepare("INSERT INTO tickets (user_id, subject, description, priority, status) VALUES (?, ?, ?, ?, 'Aberto')");
$stmt->execute([$user_id, $subject, $description, $priority]);

//Enviar Whatsapp
if($envio_whatsapp_chronoclick == 'ativado'){
    
$telefone = '5571992604877';
$mensagem = 'Novo Ticket de Suporte no Chronoclick!';

// Dados da instância e da API
$client_id = 'client_id_1';
$token = 'a7f3d9e1c4b6a2f8e9d4b7c1f2a3e6d0';   // Adicione o token se necessário
$url_api = 'https://evolution-evolution.0rvbug.easypanel.host';
$url = $url_api .'/message/sendText/'. $client_id;

// Dados da mensagem
$data = [
"number" => $telefone, // Número do destinatário com DDI
"text"   => $mensagem
];

// Headers (adicione Authorization se sua API exigir)
$headers = [
"Content-Type: application/json; charset=UTF-8",
"apikey: $token", // Descomente se a API exigir autenticação
];

// Enviando com cURL
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue redirecionamentos se houver

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Resultado
if ($http_code === 200) {
//echo "Mensagem enviada com sucesso: $response";
} else {
//echo "Erro ao enviar mensagem (HTTP $http_code): $response";
}

}
//Fim Whatsapp

header("Location: tickets.php?msg=ticket_criado");
exit();
