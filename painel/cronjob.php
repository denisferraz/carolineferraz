<?php
date_default_timezone_set('America/Sao_Paulo');

echo "=== Iniciando CRON geral em " . date('Y-m-d H:i:s') . " ===\n";

$start = microtime(true);

// Incluir scripts sequenciais
$arquivos = [
    'crm/enviar_agendados.php',
    'crm/detectar_noshow.php',
    'backup_automatico.php',
    'aniversariantes_auto.php',
    // adicione mais conforme necessário
];

foreach ($arquivos as $arquivo) {
    echo "\n>> Executando: $arquivo\n";
    try {
        include_once(__DIR__ . '/' . $arquivo);
    } catch (Exception $e) {
        echo "Erro em $arquivo: " . $e->getMessage() . "\n";
    }
}

$end = microtime(true);
echo "\n=== CRON finalizado em " . date('Y-m-d H:i:s') . " (" . round($end - $start, 2) . "s) ===\n";
