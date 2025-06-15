<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$client_id = 'carolineferraz';

try {
    $api_url = 'https://evolution-evolution.0rvbug.easypanel.host';
    $apikey = 'a7f3d9e1c4b6a2f8e9d4b7c1f2a3e6d0';
    $instance_name = $client_id;

    $webhook_url = $site_atual . '/painel/webhook_crm.php?token_emp=' . $_SESSION['token_emp'];

    $data = [
        "instanceName" => $instance_name,
        "qrcode" => true,
        "integration" => "WHATSAPP-BAILEYS",
        "webhook" => [
            "url" => $webhook_url,
            "byEvents" => false,
            "base64" => false,
            "events" => [
                "MESSAGES_UPSERT"
            ]
            ]
    ];

    $headers = [
        "Content-Type: application/json",
        "apikey: $apikey"
    ];

    $ch = curl_init("$api_url/instance/create");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 || $http_code === 201) {
        header('Location: conector-v2.php');
        exit;
    } else {
        header('Location: conector-v2.php');
        exit;
    }
} catch (Exception $e) {
    echo "Erro na requisição: " . $e->getMessage();
}
?>
