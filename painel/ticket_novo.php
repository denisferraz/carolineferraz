<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Ticket</title>
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
<form class="form" action="ticket_salvar.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2>Abrir Novo Ticket</h2>
            </div>

            <div class="card-group">
                <label>Assunto</label>
                <input type="text" name="subject" id="subject" required>
            </div>

            <div class="card-group">
                <label>Prioridade</label>
                <select name="priority">
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <label><b>Descreva o Problema</b></label>
            <textarea class="textarea-custom" name="description" rows="5" cols="43" required></textarea><br><br>

            <div class="card-group btn">
                <button type="submit">Enviar Ticket</button>
            </div>
        </div>
    </form>
</body>
</html>
