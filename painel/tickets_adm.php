<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

// Verifica se é administrador
if ($tipo_cadastro != 'Owner') {
    header('Location: tickets.php');
    exit();
}

$status_filtro = $_GET['status'] ?? '';

// Atualização de status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['new_status'])) {
    $new_status = $_POST['new_status'];
    $ticket_id = $_POST['ticket_id'];

    if (in_array($new_status, ['Aberto', 'Em andamento', 'Resolvido', 'Fechado'])) {
        $stmt = $conexao->prepare("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$new_status, $ticket_id]);
        header("Location: tickets_adm.php?status=$status_filtro");
        exit();
    }
}

// Busca tickets
if ($status_filtro && in_array($status_filtro, ['Aberto', 'Em andamento', 'Resolvido', 'Fechado'])) {
    $stmt = $conexao->prepare("SELECT t.*, u.dados_painel_users AS user_name FROM tickets t JOIN painel_users u ON t.user_id = u.id WHERE t.status = ? ORDER BY t.updated_at DESC");
    $stmt->execute([$status_filtro]);
} else {
    $stmt = $conexao->prepare("SELECT t.*, u.dados_painel_users AS user_name FROM tickets t JOIN painel_users u ON t.user_id = u.id ORDER BY t.updated_at DESC");
    $stmt->execute();
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processa os nomes descriptografados para cada ticket
foreach ($tickets as &$ticket) {
    $dados_painel_users = $ticket['user_name']; // já vem com o alias do SQL
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    $dados_array = explode(';', $dados_decifrados);
    $ticket['nome_usuario'] = $dados_array[0] ?? 'Desconhecido';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin - Todos os Tickets</title>
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
    <h2>Painel do Administrador - Tickets</h2>

    <div class="filtros">
        <strong>Filtrar:</strong>
        <a href="tickets_adm.php">Todos</a>
        <a href="tickets_adm.php?status=Aberto">Abertos</a>
        <a href="tickets_adm.php?status=Em andamento">Em andamento</a>
        <a href="tickets_adm.php?status=Resolvido">Resolvidos</a>
        <a href="tickets_adm.php?status=Fechado">Fechados</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
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
                        <td><?= htmlspecialchars($t['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($t['subject']) ?></td>
                        <td>
                            <form method="POST" style="margin: 0;">
                                <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                                <select name="new_status" onchange="this.form.submit()">
                                    <?php
                                    $statuses = ['Aberto', 'Em andamento', 'Resolvido', 'Fechado'];
                                    foreach ($statuses as $status) {
                                        $selected = $t['status'] === $status ? 'selected' : '';
                                        echo "<option value=\"$status\" $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </td>
                        <td><?= $t['priority'] ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($t['updated_at'])) ?></td>
                        <td><a class="ver" href="ticket_view.php?id=<?= $t['id'] ?>">Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align: center;">Nenhum ticket encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
