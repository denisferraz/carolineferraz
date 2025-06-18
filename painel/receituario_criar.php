<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$doc_email = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['email'] ?? NULL) : NULL;

$stmt_painel = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$stmt_painel->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
$painel = $stmt_painel->fetch(PDO::FETCH_ASSOC);
// Para descriptografar os dados
$dados_painel_users = $painel['dados_painel_users'];
$dados = base64_decode($dados_painel_users);
$dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
$dados_array = explode(';', $dados_decifrados);
$nome = $dados_array[0];
$nascimento = $dados_array[5];

$idade = date_diff(date_create($nascimento), date_create($hoje))->y;
$hoje = date('d/m/Y', strtotime($hoje));

$conteudo = "Paciente: $nome
Idade: $idade anos
Data: $hoje

Prescrição:


Instruções:


___________________________
$config_empresa  
CRM- XXXXX 
$config_telefone
";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Criar Receita</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Cadastrar Receita</h2>
            </div>
            <div class="card-group">

            <label>Nome [ <?php echo $nome ?> ]</label>
            <label>E-mail [ <?php echo $doc_email ?> ]</label>
            <br>
            <label>Titulo</label>
            <input type="text" minlength="5" maxlength="50" name="titulo" placeholder="Receita Médica" required>
            <label>Receita</label>
            <textarea class="textarea-custom" name="conteudo" rows="20" cols="40" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $conteudo)); ?></textarea><br><br>

            <input type="hidden" name="email" value="<?= $doc_email ?>">
            <input type="hidden" name="id_job" value="Receituario" />
            <div class="card-group-red btn"><button type="submit">Registrar Receita</button></div>
            </div>
        </div>
    </form>
</body>
</html>