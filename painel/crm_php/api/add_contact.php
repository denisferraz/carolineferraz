<?php
require_once("../config/database.php");
require_once("../config/auth.php");

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$current_user = getCurrentUser();
$token_emp = $current_user['token_emp'];

$name = trim($_POST['name'] ?? '');
$phone_number = trim($_POST['phone_number'] ?? '');
$stage = $_POST['stage'] ?? 'Novo';

// Validações
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Nome é obrigatório']);
    exit;
}

if (empty($phone_number)) {
    echo json_encode(['success' => false, 'message' => 'Telefone é obrigatório']);
    exit;
}

// Limpar telefone (remover formatação)
$phone_clean = preg_replace('/\D/', '', $phone_number);

// Verificar se o contato já existe
try {
    $stmt = $pdo->prepare("SELECT id FROM contacts WHERE phone_number = ? AND token_emp = ?");
    $stmt->execute([$phone_clean, $token_emp]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Contato já existe com este telefone']);
        exit;
    }
    
    // Inserir novo contato
    $stmt = $pdo->prepare("
        INSERT INTO contacts (token_emp, name, phone_number, stage, last_contact, created_at) 
        VALUES (?, ?, ?, ?, NOW(), NOW())
    ");
    
    $stmt->execute([$token_emp, $name, $phone_clean, $stage]);
    
    $contact_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Contato adicionado com sucesso',
        'contact_id' => $contact_id
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao adicionar contato: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?>

