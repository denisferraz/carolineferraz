<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$tratamento_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $tratamento_id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$tratamento = $select['tratamento'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Serviços</title>

    <link rel="stylesheet" href="<?php echo $css_path ?>">
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
                <h2>Adicione Custos ao <?php echo $tratamento; ?></h2>
            </div>

            <div class="card-group">
            <br>
            <label><b>Custo:  
                <select name="custo_id">
                <?php
            $query2 = $conexao->prepare("SELECT * FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY custo_descricao ASC");
            $query2->execute(array('id' =>0));
            while($select2 = $query2->fetch(PDO::FETCH_ASSOC)){
            $id_custo = $select2['id'];
            $custo_valor = $select2['custo_valor'];
            $custo_tipo = $select2['custo_tipo'];
            $custo_descricao = $select2['custo_descricao'];

            if($custo_tipo == 'Taxas' || $custo_tipo == 'Impostos' || $custo_tipo == 'Margem'){
                $custo_valor = number_format($custo_valor ,2,",",".") . '%';
            }else{
                $custo_valor = 'R$' . number_format($custo_valor ,2,",",".");
            }
                ?>
                <option value="<?php echo $id_custo?>"><?php echo $custo_descricao; ?> | <?php echo $custo_valor; ?></option>
                <?php } ?>
                </select></b></label><br>
            <label>Quantidade</label>
            <input min="1" max="999" type="number" name="quantidade" value="1" required>
            <input type="hidden" name="tratamento_id" value="<?php echo $tratamento_id; ?>" />
            <input type="hidden" name="id_job" value="lancar_custo_tratamento" />
            <div class="card-group btn"><button type="submit">Incluir Custo</button></div>

            </div>
        </div>
    </form>

    <br>
    <div class="card">
    <div class="card-group">

    <FONT COLOR="white">
                <fieldset>
                    <legend align="center"><b>Custos do Serviço: <?php echo $tratamento; ?></b></legend>
                    <br>
                    <table border="1px" align="center">
                        <tr>
                            <td align="center"><b>Custo</b></td>
                            <td align="center"><b>Valor</b></td>
                            <td align="center"><b>Quantidade</b></td>
                            <td align="center"><b>Excluir</b></td>
                        </tr>
<?php
$query = $conexao->prepare("SELECT * FROM custos_tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND tratamento_id = :tratamento_id ORDER BY id DESC");
$query->execute(array('tratamento_id' => $tratamento_id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$id = $select['id'];
$custo_id = $select['custo_id'];
$quantidade = $select['quantidade'];

$query_custos = $conexao->prepare("SELECT * FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :custo_id");
$query_custos->execute(array('custo_id' => $custo_id));
while($select_custos = $query_custos->fetch(PDO::FETCH_ASSOC)){
$custo_descricao = $select_custos['custo_descricao'];
$custo_valor = $select_custos['custo_valor'];
$custo_tipo = $select_custos['custo_tipo'];
}

if($custo_tipo == 'Taxas' || $custo_tipo == 'Impostos' || $custo_tipo == 'Margem'){
    $custo_valor = number_format($custo_valor ,2,",",".") . '%';
}else{
    $custo_valor = 'R$' . number_format($custo_valor ,2,",",".");
}
?>
                        <tr>
                            <td align="left"><?php echo $custo_descricao; ?></td>
                            <td align="left"><?php echo $custo_valor; ?></td>
                            <td align="left"><?php echo $quantidade; ?></td>
                            <td align="center"><a href="javascript:void(0)" onclick='window.open("tratamentos_custo_excluir.php?id=<?php echo $id ?>&tratamento_id=<?php echo $tratamento_id ?>","iframe-home")'><button>Excluir</button></a></td>
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
