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

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Tratamentos</title>

    <link rel="stylesheet" href="css/style.css">
    <script>
    function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i)
    if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
    }
    }   
    </script>
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Cadastre abaixo seus Tratamentos</h2>
            </div>

            <div class="card-group">
            <br>
            <label>Descrição Tratamento</label>
                <textarea name="tratamento_descricao" rows="5" cols="43" required></textarea><br><br>
                <input type="hidden" name="id_job" value="lancar_tratamento" />
            <div class="card-group btn"><button type="submit">Cadastrar Tratamento</button></div>

            </div>
        </div>
    </form>

    <br>
    <div class="card">
    <div class="card-group">

    <FONT COLOR="black">
                <fieldset>
                    <legend align="center"><b>Tratamentos</b></legend>
                    <br>
                    <table border="1px" align="center">
                        <tr>
                            <td align="center"><b>Tratamento</b></td>
                            <td align="center"><b>Editar</b></td>
                            <td align="center"><b>Excluir</b></td>
                        </tr>
<?php
$query = $conexao->prepare("SELECT * FROM tratamentos WHERE id >= :id ORDER BY tratamento DESC");
$query->execute(array('id' => '1'));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$id = $select['id'];
$tratamentos = $select['tratamento'];
?>
                        <tr>
                            <td align="left"><?php echo $tratamentos ?></td>
                            <td align="center"><a href="javascript:void(0)" onclick='window.open("tratamentos_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                            <td align="center"><a href="javascript:void(0)" onclick='window.open("tratamentos_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                        </tr>
                        <?php
}
?>
                    </table>
                </fieldset>
            </FONT>

            </div>
            </div>

</body>
</html>

<?php
}
?>