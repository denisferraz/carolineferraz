<?php
require('../../config/database.php');

$host = 'localhost';
$dbname = $config_db;
$username = $config_user;
$password = $config_password;

try {
    // Conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Conexão MySQLi (para compatibilidade)
    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_error) {
        die("Erro de conexão: " . $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8mb4");
    
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Configurações globais
define('SITE_URL', 'http://localhost');
define('SITE_NAME', 'CRM Profissional');
define('TIMEZONE', 'America/Sao_Paulo');

// Configurar timezone
date_default_timezone_set(TIMEZONE);
?>

