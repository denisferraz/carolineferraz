<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$user_id = $client_id;
$status_filtro = $_GET['status'] ?? '';

// Monta query com ou sem filtro de status
if ($status_filtro && in_array($status_filtro, ['Aberto', 'Em andamento', 'Resolvido', 'Fechado'])) {
    $stmt = $conexao->prepare("SELECT * FROM tickets WHERE user_id = ? AND status = ? ORDER BY updated_at DESC");
    $stmt->execute([$user_id, $status_filtro]);
} else {
    $stmt = $conexao->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY updated_at DESC");
    $stmt->execute([$user_id]);
}

$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Tickets</title>
    <style>
    body {
        background-color: #121212;
        color: #ffffff;
        font-family: Arial, sans-serif;
        padding: 20px;
    }

    .container {
        max-width: 1100px;
        margin: auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 10px #00000080;
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    .filtros {
        text-align: center;
        margin-bottom: 20px;
    }

    .filtros a {
        color: #60efff;
        margin: 0 10px;
        text-decoration: none;
        font-weight: bold;
        background: #1c1c1c;
        padding: 8px 12px;
        border-radius: 6px;
        transition: 0.2s;
    }

    .filtros a:hover {
        background: #2a2a2a;
        text-decoration: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #333;
    }

    th {
        background-color: #2a2a2a;
        color: #ccc;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    tr:nth-child(even) {
        background-color: #1c1c1c;
    }

    tr:hover {
        background-color: #2a2a2a;
    }

    .status {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.85em;
        display: inline-block;
    }

    .Aberto {
        background-color: #2196F3;
        color: white;
    }

    .Em\ andamento {
        background-color: #FFC107;
        color: black;
    }

    .Resolvido {
        background-color: #4CAF50;
        color: white;
    }

    .Fechado {
        background-color: #f44336;
        color: white;
    }

    .ver {
        color: #60efff;
        text-decoration: none;
        font-weight: bold;
        background-color: #1f1f1f;
        padding: 6px 10px;
        border-radius: 5px;
        transition: 0.2s;
    }

    .ver:hover {
        background-color: #2c2c2c;
        text-decoration: none;
    }

    select {
        background-color: #2a2a2a;
        color: #fff;
        border: 1px solid #444;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.9em;
        font-weight: bold;
    }

    select option {
        background-color: #2a2a2a;
        color: #fff;
    }
</style>

</head>
<body>

<div class="container">
    <h2>Meus Tickets</h2>

    <div class="filtros">
        <strong>Filtrar:</strong>
        <a href="tickets.php">Todos</a>
        <a href="tickets.php?status=Aberto">Abertos</a>
        <a href="tickets.php?status=Em andamento">Em andamento</a>
        <a href="tickets.php?status=Resolvido">Resolvidos</a>
        <a href="tickets.php?status=Fechado">Fechados</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Assunto</th>
                <th>Status</th>
                <th>Prioridade</th>
                <th>Atualizado</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($tickets): ?>
                <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td>#<?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['subject']) ?></td>
                        <td><span class="status <?= str_replace(' ', '\\ ', $t['status']) ?>"><?= $t['status'] ?></span></td>
                        <td><?= $t['priority'] ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($t['updated_at'])) ?></td>
                        <td><a class="ver" href="ticket_view.php?id=<?= $t['id'] ?>">Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center;">Nenhum ticket encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
