<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);

$ticket_id = intval($_GET['id']);
$user_id = $client_id;

// Verifica se o ticket pertence ao usuário logado (ou pula isso se for admin)
$stmt = $conexao->prepare("SELECT * FROM tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticket_id, $user_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket não encontrado ou acesso negado.";
    exit();
}

$stmt = $conexao->prepare("SELECT r.*, u.dados_painel_users FROM ticket_responses r JOIN painel_users u ON r.user_id = u.id WHERE r.ticket_id = ? ORDER BY r.created_at ASC");
$stmt->execute([$ticket_id]);
$respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($respostas as &$resposta) {
    $dados_painel_users = $resposta['dados_painel_users'];
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    $dados_array = explode(';', $dados_decifrados);
    $resposta['nome'] = $dados_array[0]; // adiciona o nome descriptografado
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
<form class="form" action="ticket_responder.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2>Ticket #<?= $ticket['id'] ?></h2>
            </div>

            <div class="card-group">
                <label>
                <p><strong>Assunto:</strong> <?= htmlspecialchars($ticket['subject']) ?></p>
                <p><strong>Prioridade:</strong> <?= $ticket['priority'] ?></p>
                <p><strong>Status:</strong> <?= $ticket['status'] ?></p>
                <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
                </label>
            </div>
            <br>
            <h3>Respostas</h3>
    <?php if ($respostas): ?>
        <?php foreach ($respostas as $r): ?>
            <div class="resposta">
                <div class="autor"><?= htmlspecialchars($resposta['nome']) ?> - <?= date("d/m/Y H:i", strtotime($r['created_at'])) ?></div>
                <div class="texto"><?= nl2br(htmlspecialchars($r['message'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #ffcc00;">⚠️ Nenhuma resposta ainda.</p>
    <?php endif; ?>
    <br>
    <form action="ticket_responder.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <label><b>Responder:</b></label>
        <textarea class="textarea-custom" name="message" rows="5" cols="43" required></textarea><br>
        <button type="submit">Enviar Resposta</button>
    </form>

    </div>
</body>
</html>
