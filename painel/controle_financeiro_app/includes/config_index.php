<?php

session_start();
require('../../config/database.php');
require('../verifica_login.php');

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', $config_db);
define('DB_USER', $config_user);
define('DB_PASS', $config_password);

// Configurações da aplicação
define('APP_NAME', 'Sistema de Controle Financeiro');
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Função para conectar ao banco de dados
function getConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

// Função para formatar valores monetários
function formatMoney($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

// Função para formatar datas
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

// Função para validar dados
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Headers para CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
?>

