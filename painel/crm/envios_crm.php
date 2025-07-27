<?php
require('../config/database.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

date_default_timezone_set('America/Sao_Paulo');

$diasemana_numero = date('w', time());
$hora_ = time(); // agora
$hora_atual = strtotime('+0 hours', $hora_);

$hoje = date('Y-m-d');

$pdo = $conexao;

$result_check_config = $conexao->query("SELECT * FROM configuracoes WHERE id > 0");
while($select_check_config = $result_check_config->fetch(PDO::FETCH_ASSOC)){
    $token_config = $select_check_config['token_emp'];
    $envio_whatsapp = $select_check_config['envio_whatsapp'];
    $lembrete_auto_time = $select_check_config['lembrete_auto_time'];
    $is_segunda = $select_check_config['is_segunda'];
    $is_terca = $select_check_config['is_terca'];
    $is_quarta = $select_check_config['is_quarta'];
    $is_quinta = $select_check_config['is_quinta'];
    $is_sexta = $select_check_config['is_sexta'];
    $is_sabado = $select_check_config['is_sabado'];
    $is_domingo = $select_check_config['is_domingo'];
    $client_id = $select_check_config['id'];

if (!$lembrete_auto_time || strtotime($lembrete_auto_time) === false) {
  continue;
}

if($site_puro != 'chronoclick'){
  $client_id = 'carolineferraz';
}

// Pega timestamps
$hora_config = strtotime($lembrete_auto_time); // timestamp do lembrete

// Define intervalo de tolerância (15 minutos = 900 segundos)
$intervalo = 15 * 60;

// Verifica se a hora atual está dentro do intervalo
if (abs($hora_atual - $hora_config) > $intervalo) {
  continue; // pula esse registro, está fora do intervalo
}

$diaSemana = date('w', strtotime($hoje));

if (
  ($diaSemana == 0 && $is_domingo == 0) ||
  ($diaSemana == 1 && $is_segunda == 0) ||
  ($diaSemana == 2 && $is_terca == 0) ||
  ($diaSemana == 3 && $is_quarta == 0) ||
  ($diaSemana == 4 && $is_quinta == 0) ||
  ($diaSemana == 5 && $is_sexta == 0) ||
  ($diaSemana == 6 && $is_sabado == 0)
) {
  continue;
}

//Mensagens Agendadas
$stmt = $pdo->prepare("
  SELECT * FROM agendamentos_automaticos 
  WHERE enviado = 0 
    AND DATE(agendar_para) <= CURDATE() 
    AND token_emp = ?
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

        // Para descriptografar os dados
        $dados = base64_decode($ag['mensagem']);
        $ag_mensagem = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
        $doc_telefonewhats = '55'.$ag['numero'];
        $msg_whatsapp = $ag_mensagem;
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
        
    }

        // Marcar como enviado
        $pdo->prepare("UPDATE agendamentos_automaticos SET enviado = 1 WHERE id = ?")
            ->execute([$ag['id']]);

        sleep(1); // evitar spam
    }
}

//Followups agendados
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

//Detectar No Show
$diasLimite = 5; // Exemplo: se passaram 3 dias sem retorno

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


}

  file_put_contents('cron_log.txt', date('Y-m-d H:i:s') . " - Rodou (Envios CRM)\n", FILE_APPEND);

?>