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

$input = json_decode(file_get_contents('php://input'), true);
$contact_id = $input['contact_id'] ?? null;
$stage = $input['stage'] ?? null;

// Validações
if (!$contact_id || !$stage) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$valid_stages = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
if (!in_array($stage, $valid_stages)) {
    echo json_encode(['success' => false, 'message' => 'Etapa inválida']);
    exit;
}

try {
    // Verificar se o contato pertence ao usuário
    $stmt = $pdo->prepare("SELECT id FROM contacts WHERE id = ? AND token_emp = ?");
    $stmt->execute([$contact_id, $token_emp]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Contato não encontrado']);
        exit;
    }
    
    // Atualizar etapa
    $stmt = $pdo->prepare("
        UPDATE contacts 
        SET stage = ?, updated_at = NOW() 
        WHERE id = ? AND token_emp = ?
    ");
    
    $stmt->execute([$stage, $contact_id, $token_emp]);
    
    // Registrar interação
    $stmt = $pdo->prepare("
        INSERT INTO interactions (token_emp, contact_id, message_content, origin, interaction_date) 
        VALUES (?, ?, ?, 'company', NOW())
    ");
    
    $message = "Etapa alterada para: " . $stage;
    $stmt->execute([$token_emp, $contact_id, $message]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Etapa atualizada com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao atualizar etapa: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?>

