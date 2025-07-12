<?php
require('../config/database.php');

// === CONFIGURAÇÕES DO BANCO ===
$dbHost = 'localhost';
$dbUser = $config_user;
$dbPass = $config_password;
$dbName = $config_db;

// === CONFIGURAÇÕES DE BACKUP ===
$backupDir = '../backups';
$data = date('Y-m-d');
$backupFile = "$backupDir/backup_{$dbName}_{$data}.sql";

// === GARANTE QUE A PASTA DE BACKUP EXISTE ===
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// === GERA BACKUP ===
$comando = "mysqldump -h $dbHost -u $dbUser -p$dbPass $dbName > \"$backupFile\"";
exec($comando, $output, $retorno);

// === REMOVE BACKUPS COM MAIS DE 7 DIAS ===
$arquivos = glob($backupDir . '/backup_*.sql');
$agora = time();

foreach ($arquivos as $arquivo) {
    
    if (is_file($arquivo)) {
        $modificado = filemtime($arquivo);
        $dias = ($agora - $modificado) / (60 * 60 * 24);
        if ($dias > 7) {
            unlink($arquivo);
        }
    }
}

$id_job = isset($_GET['id_job']) ? $_GET['id_job'] : 'Cronjob';

if($id_job == 'Painel'){
header('Location: agenda.php');
exit();
}
?>
