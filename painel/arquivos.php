<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
    
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query_check2 = $conexao->query("SELECT * FROM painel_users WHERE email IN (SELECT doc_email FROM consultas WHERE id = '{$id_consulta}')");
while($select_check2 = $query_check2->fetch(PDO::FETCH_ASSOC)){
    $token_profile = $select_check2['token'];
}
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
                <h2>Salve um Novo Arquivo</h2>
            </div>

            <div class="card-group">
                <label>Selecionar um Arquivo PDF</label>
                <FONT COLOR="white"><input type="file" name="arquivos" id="arquivo" onchange="updateFileName()" required></font><br><br>
                <label>Nome do Arquivo</label>
                <input type="text" name="arquivo" minlength="5" maxlength="20" required><br>
                <label>Tipo do Arquivo</label>
                <select name="arquivo_tipo">
                    <option value="Tratamento">Plano de Tratamento</option>
                    <option value="Evolucao">Evolução</option>
                    <option value="Orientacao">Orientações</option>
                    <option value="Laudos">Laudos e Exames</option>
                    <option value="Contratos">Contratos</option>
                    <option value="Outros">Outros</option>
                </select>
                <input type="hidden" name="id_job" value="arquivos" />
                <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>" />
                <input type="hidden" name="token_profile" value="<?php echo $token_profile ?>" />
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