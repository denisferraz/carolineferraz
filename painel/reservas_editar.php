<?php
session_start();
require('../conexao.php');
require('verifica_login.php');

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'escuro'; // padrão é escuro

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
$aut_acesso = $query_check->fetch(PDO::FETCH_ASSOC)['aut_painel'];

if ($aut_acesso == 1) {
    echo 'Você não tem permissão para acessar esta página';
    exit;
}

$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="ajax/jquery.2.1.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
    <script>
        function buscar(palavra) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'reservas_buscar.php',
                beforeSend: function () {
                    $("#resultado").html("Carregando...");
                },
                data: { palavra: palavra },
                success: function (msg) {
                    $("#resultado").html(msg);
                }
            });
        }

        $(document).ready(function () {
            $('#buscar').on('click', function () {
                buscar($("#palavra").val());
            });
        });
    </script>
</head>
<body>
<form class="form" action="reservas_buscar.php" method="POST">
<div class="card">
<div class="card-top">
        <h2>Buscar uma Consulta</h2>
        </div>
        <div class="card-group">
                <label>Nome, Confirmação ou E-mail</label>
                <input type="text" minlength="5" maxlength="35" name="busca" placeholder="Para total, deixe em branco">
                <label>Atendimento Dia - Início</label>
                <input type="date" name="busca_inicio" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <label>Atendimento Dia - Fim</label>
                <input type="date" name="busca_fim" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <div class="card-group btn"><button type="submit">Buscar</button></div>
        </div>
    </div>
    </form>

</body>
</html>
