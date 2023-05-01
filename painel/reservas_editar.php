<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');
$hoje = date('Y-m-d');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_reservas'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
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
                            url: 'reservas_buscar.php',//Indica a página que está sendo solicitada.
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

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <form class="form" action="reservas_buscar.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Buscar uma Consulta</h2>
            </div>

            <div class="card-group">
                <label>Nome Ou Confirmação Ou Email</label>
                <input type="text" minlength="5" maxlength="35" name="busca" placeholder="Para total, deixe em branco">
                <label>Atendimento Dia - Inicio</label>
                <input type="date" name="busca_inicio" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <label>Atendimento Dia - Fim</label>
                <input type="date" name="busca_fim" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <br>
                <div class="card-group btn"><button type="submit">Buscar</button></div>

            </div>
        </div>
    </form>

    <br><div id="resultado"></div>

</body>
</html>

<?php } ?>