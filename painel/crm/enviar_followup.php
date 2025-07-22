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
    $config_crm_followup = $select_check_config['config_crm_followup'];

// Buscar contatos com follow-up hoje
$stmt = $pdo->prepare("SELECT * FROM contatos WHERE followup = :hoje AND token_emp = :token_emp");
$stmt->execute(['hoje' => $hoje, 'token_emp' => $token_config]);
$contatos = $stmt->fetchAll();

foreach ($contatos as $contato) {
    $numero = preg_replace('/\D/', '', $contato['numero']);
    $nome = $contato['nome'];

    $msg_replace = str_replace(
        ['{NOME}'],    // o que procurar
        [$nome],  // o que colocar no lugar
        $config_crm_followup
    );
      
    $msg_string = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $msg_replace);

    $msg_html = nl2br(htmlspecialchars($msg_string)); //Email
    $mensagem = $msg_string; // Whatsapp

    if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = "55$numero";
        $msg_whatsapp = $mensagem;
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
        
    }

}

}