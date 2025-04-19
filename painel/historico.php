<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
    
$hoje = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="ajax/jquery.2.1.3.min.js"></script>
    <script>
            function buscar(palavra)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'buscar.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                $("#resultado").html("Carregando...");
                            },
                            data: {palavra: palavra},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {
                                $("#resultado").html(msg);
                            }
                        });
            }
            
            
            $('#buscar').click(function () {
                buscar($("#palavra").val())
            });
        </script>
    <title>Historico</title>
    <link rel="stylesheet" href="css/style_v2.css">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="buscar.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Buscar Historico de Alterações</h2>
            </div>

            <div class="card-group">
                <label>Nome Ou Confirmação ou Ação</label>
                <input type="text" minlength="5" maxlength="30" name="busca" placeholder="Para total, deixe em branco">
                <label>Inicio</label>
                <input type="date" max="<?php echo $hoje ?>" value="<?php echo $hoje ?>" name="historico_inicio" required>
                <label>Fim</label>
                <input type="date" max="<?php echo $hoje ?>" value="<?php echo $hoje ?>" name="historico_fim" required>
                <br>
                <div class="card-group btn"><button type="submit">Buscar</button></div>

            </div>
        </div>
    </form>

    <br><div id="resultado"></div>

</body>
</html>

<?php
}
?>