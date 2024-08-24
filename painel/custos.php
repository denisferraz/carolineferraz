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
    <title>Cadastrar Custos</title>

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
                <h2 class="title-cadastro">Cadastre abaixo seus Custos</h2>
            </div>

            <div class="card-group">
            <br>
            <label>Valor</label>
            <input minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="custo_valor" placeholder="000.00" required>
            <label><b>Tipo do Custo: 
                <select name="custo_tipo">
                <option value="Aluguel">Aluguel</option>
                <option value="Luz">Luz</option>
                <option value="Internet">Internet</option>
                <option value="Insumos">Insumos</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Aluguel Equipamentos">Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos">Equipamentos [Compra]</option>
                <option value="Hora">Valor Hora</option>
                <option value="Outros">Outros</option>
                </select></b></label><br>
                <label>Descrição Custo</label>
                <textarea name="custo_descricao" rows="5" cols="43" required></textarea><br><br>
                <input type="hidden" name="id_job" value="lancar_custos" />
            <div class="card-group btn"><button type="submit">Cadastrar Custo</button></div>

            </div>
        </div>
    </form>

    <br>
    <div class="card">
    <div class="card-group">

    <FONT COLOR="black">
                <fieldset>
                    <legend align="center"><b>Custos</b></legend>
                    <br>
                    <table border="1px" align="center">
                        <tr>
                            <td align="center"><b>Tipo</b></td>
                            <td align="center"><b>Valor</b></td>
                            <td align="center"><b>Descrição</b></td>
                            <td align="center"><b>Editar</b></td>
                            <td align="center"><b>Excluir</b></td>
                        </tr>
<?php
$query = $conexao->prepare("SELECT * FROM custos WHERE id >= :id ORDER BY custo_tipo DESC");
$query->execute(array('id' => '1'));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$id = $select['id'];
$custo_valor = $select['custo_valor'];
$custo_tipo = $select['custo_tipo'];
$custo_descricao = $select['custo_descricao'];
?>
                        <tr>
                            <td align="left"><?php echo $custo_tipo ?></td>
                            <td align="left">R$<?php echo number_format($custo_valor ,2,",",".") ?></td>
                            <td align="center"><?php echo $custo_descricao ?></td>
                            <td align="center"><a href="javascript:void(0)" onclick='window.open("custo_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                            <td align="center"><a href="javascript:void(0)" onclick='window.open("custo_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
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