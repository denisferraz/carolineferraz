<?php
require('../../config/database.php');
date_default_timezone_set('America/Sao_Paulo');

$result_check_config = $conexao->query("SELECT * FROM configuracoes WHERE id > 0");
while($select_check_config = $result_check_config->fetch(PDO::FETCH_ASSOC)){
    $token_config = $select_check_config['token_emp'];
    $envio_whatsapp = $select_check_config['envio_whatsapp'];
    $envio_email = $select_check_config['envio_email'];
    $client_id = $select_check_config['id'];

$pdo = $conexao;
$diasLimite = 3; // Exemplo: se passaram 3 dias sem retorno

// Etapas consideradas para monitorar
$etapas_monitoradas = ['Em Contato', 'Negociação'];

// Monta placeholders dinâmicos (?, ?, ...)
$placeholders = implode(',', array_fill(0, count($etapas_monitoradas), '?'));

// Busca contatos inativos
$sql = "
    SELECT * FROM contatos 
    WHERE etapa IN ($placeholders)
    AND DATEDIFF(NOW(), ultimo_contato_cliente) >= ?
    AND token_emp = ?
";

$params = [...$etapas_monitoradas, $diasLimite, $token_config];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inativos = $stmt->fetchAll();

foreach ($inativos as $contato) {
    // Atualiza etapa para "Perdido"
    $stmtUp = $pdo->prepare("UPDATE contatos SET etapa = 'Perdido' WHERE id = ?");
    $stmtUp->execute([$contato['id']]);
}

echo count($inativos) . " contatos marcados como 'Perdido'.";

}