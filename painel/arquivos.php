<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

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
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
    $confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Arquivos</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">

    <style>
        .card {
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="acao.php" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-top">
                <h2>Salve um Novo Arquivo <?php echo $confirmacao ?></h2>
            </div>

            <div class="card-group">
                <label>Selecionar um Arquivo PDF</label>
                <FONT COLOR="white"><input type="file" name="arquivos" id="arquivo" onchange="updateFileName()" required></font><br><br>
                <label>Nome do Arquivo</label>
                <input type="text" name="arquivo" minlength="5" maxlength="20" required><br>
                <input type="hidden" name="id_job" value="arquivos" />
                <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>" />
                <br>
                <div class="card-group btn"><button type="submit">Salvar PDF</button></div>

            </div>
        </div>
    </form><br><br>

    <script>
    function updateFileName() {
        var fileInput = document.getElementById('arquivos');
        var fileNameDisplay = document.getElementById('arquivo');
        
        if (fileInput.files.length > 0) {
            fileNameDisplay.value = fileInput.files[0].name;
        } else {
            fileNameDisplay.value = '';
        }
    }
</script>

</body>
</html>

<?php
}
?>