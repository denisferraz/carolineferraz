<?php
require('../../config/database.php');
date_default_timezone_set('America/Sao_Paulo');

$pdo = $conexao;

$result_check_config = $conexao->query("SELECT * FROM configuracoes WHERE id > 0");
while($select_check_config = $result_check_config->fetch(PDO::FETCH_ASSOC)){
    $token_config = $select_check_config['token_emp'];
    $envio_whatsapp = $select_check_config['envio_whatsapp'];
    $envio_email = $select_check_config['envio_email'];
    $client_id = $select_check_config['id'];
    
// Buscar agendamentos prontos para envio
$stmt = $pdo->prepare("
  SELECT * FROM agendamentos_automaticos 
  WHERE enviado = 0 AND agendar_para <= NOW() AND token_emp = ?
");
$stmt->execute([$token_config]);
$pendentes = $stmt->fetchAll();

foreach ($pendentes as $ag) {
    // Verifica se ainda está na etapa "Novo"
    $stmtEtapa = $pdo->prepare("SELECT etapa FROM contatos WHERE id = ? AND token_emp = ?");
    $stmtEtapa->execute([$ag['contato_id'], $token_config]);
    $etapa = $stmtEtapa->fetchColumn();

    if ($etapa === 'Novo') {
        
        //Enviar Mensagem
        if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = '55'.$ag['numero'];
        $msg_whatsapp = $ag['mensagem'];
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
        
    }

        // Marcar como enviado
        $pdo->prepare("UPDATE agendamentos_automaticos SET enviado = 1 WHERE id = ?")
            ->execute([$ag['id']]);

        sleep(1); // evitar spam
    }
}

}
