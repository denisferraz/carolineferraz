<?php
session_start();
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tema'])) {
    $tema_painel = $_POST['tema'];
    $query = $conexao->prepare("UPDATE painel_users SET tema_painel = :tema_painel WHERE token = '{$_SESSION['token']}' AND email = :email");
    $query->execute(array('tema_painel' => $tema_painel, 'email' => $email));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="bg-light p-3 shadow-sm d-flex justify-content-between align-items-center">
    <h5 class="m-0">Painel do Paciente</h5>

    <form method="POST" style="display: flex; gap: 5px;">
    <button type="submit" name="tema" value="escuro" title="Tema Escuro 🌙" style="font-size: 12px;">🌙</button>
    <button type="submit" name="tema" value="claro" title="Tema Claro ☀️" style="font-size: 12px;">☀️</button>
    <button type="submit" name="tema" value="colorido" title="Tema Colorido 🎨" style="font-size: 12px;">🎨</button>
    </form>

    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary"><?= $usuario ?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
    </div>
</div>

</body>
</html>
