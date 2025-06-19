<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);
$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
  $doc_nome = $select_check2['nome'];
  $doc_telefone = $select_check2['telefone'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Lançamentos de Consumos</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
<form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2>Confirmar o Lançamento</h2>
            </div>

            <div class="card-group">
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <label>Nome  [ <b><u><?php echo $doc_nome ?></u></b> ]</label>
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <br>
            <?php if($id_job == 'Produto'){ ?>
            <label>Produto</label>
            <select name="lanc_produto" id="lanc_produto" required onchange="atualizaMaxEstoque()">
            <?php
            // Consulta o saldo atual do produto
            $query = $conexao->prepare("
                SELECT ei.id, ei.produto, COALESCE(SUM(e.quantidade), 0) as saldo
                FROM estoque_item ei
                LEFT JOIN estoque e ON ei.id = e.produto AND e.token_emp = :token_emp
                WHERE ei.token_emp = :token_emp2 AND ei.id >= :id
                GROUP BY ei.id
                ORDER BY ei.produto ASC
            ");
            $query->execute([
                'token_emp' => $_SESSION['token_emp'],
                'token_emp2' => $_SESSION['token_emp'],
                'id' => 1
            ]);
            
            while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                $produto = $select['produto'];
                $produto_id = $select['id'];
                $saldo = (int) $select['saldo'];
            ?>
                <option value="<?php echo $produto_id; ?>" data-estoque="<?php echo $saldo; ?>">
                    <?php echo $produto; ?> [Saldo: <?php echo $saldo; ?>]
                </option>
            <?php } ?>
            </select>
            <label>Quantidade</label>
            <input min="1" max="999" type="number" name="lanc_quantidade" id="lanc_quantidade" placeholder="000" required>
            <label>Valor</label>
            <input minlength="1" maxlength="9999" type="text" id="lanc_valor" name="lanc_valor" placeholder="000.00" required>
            <input type="hidden" name="tipo_lanc" value="produto">
            <?php }else{ ?>
            <label>Serviço</label>
            <select name="lanc_produto" id="lanc_servico" required onchange="atualizaValorServico()">
            <?php
            $query_tratamentos = $conexao->prepare("SELECT * FROM tratamentos WHERE token_emp = :token_emp AND id >= :id ORDER BY tratamento DESC");
            $query_tratamentos->execute(['token_emp' => $_SESSION['token_emp'], 'id' => 1]);
            
            while ($select_tratamentos = $query_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                $tratamento_id = $select_tratamentos['id'];
                $tratamentos = $select_tratamentos['tratamento'];
                $custo_sugerido = 0.00;
                $custo_total = 0.00;
                $taxas = 0.00;
                $impostos = 0.00;
                $margem = 0.00;
            
                $query_custos_tratamentos = $conexao->prepare("SELECT * FROM custos_tratamentos WHERE tratamento_id = :tratamento_id");
                $query_custos_tratamentos->execute(['tratamento_id' => $tratamento_id]);
            
                while ($select_custos_tratamentos = $query_custos_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                    $custo_id = $select_custos_tratamentos['custo_id'];
                    $quantidade = $select_custos_tratamentos['quantidade'];
            
                    $query_custos = $conexao->prepare("SELECT * FROM custos WHERE id = :custo_id");
                    $query_custos->execute(['custo_id' => $custo_id]);
            
                    while ($select_custos = $query_custos->fetch(PDO::FETCH_ASSOC)) {
                        $custo_valor = $select_custos['custo_valor'];
                        $custo_tipo = $select_custos['custo_tipo'];
            
                        if ($custo_tipo == 'Taxas') {
                                $taxas += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Impostos'){
                                $impostos += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Margem'){
                                $margem += $custo_valor * $quantidade;
                            }else{
                                $custos .= "($quantidade)$custo_descricao | ";
                                $custo_total += ($custo_valor * $quantidade);
                                $custo_sugerido += ($custo_valor * $quantidade);
                            }
                            }
                        }
                        
                        //$custo_extra = $custo_sugerido * ($taxas + $impostos + $margem)/ 100;
                        $margem = $custo_sugerido * $margem / 100;
                        $taxas = ($custo_sugerido + $margem) * $taxas / 100;
                        $impostos = ($custo_sugerido + $margem + $taxas) * $impostos / 100;
                        $custo_extra = $margem + $taxas + $impostos;
                        $custo_total_sugerido = ceil($custo_sugerido + $custo_extra);
                
                // FORMATAR O VALOR EM FORMATO AMERICANO PARA USAR NO value DO INPUT
                $custo_sugerido_formatado = number_format($custo_total_sugerido, 2, '.', '');
            
                ?>
                <option value="<?php echo $tratamentos; ?>" data-valor="<?php echo $custo_sugerido_formatado; ?>">
                    <?php echo $tratamentos; ?> [Sugestão - R$ <?php echo number_format($custo_total_sugerido, 2, ',', '.'); ?>]
                </option>
            <?php } ?>
            </select>
            <input type="hidden" name="lanc_quantidade" value="1" required>
            <label>Valor</label>
            <input minlength="1" maxlength="9999" type="text" id="lanc_valor" name="lanc_valor" placeholder="000.00" required>
            <input type="hidden" name="tipo_lanc" value="servico">
            <?php } ?>
            <br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div class="card-group btn"><button type="submit">Lançar</button></div>

            </div>
        </div>
    </form>
<script>
document.getElementById("lanc_valor").addEventListener("input", function() {
    // Remove espaços em branco e formata o valor para ter apenas números e até duas casas decimais
    this.value = this.value.replace(/\s/g, "").replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");
    
    // Verifica se o valor possui mais de duas casas decimais e, se sim, limita-o a duas casas decimais
    if (this.value.split(".")[1] && this.value.split(".")[1].length > 2) {
        this.value = parseFloat(this.value).toFixed(2);
    }
});
</script>
<script>
function atualizaMaxEstoque() {
    const select = document.getElementById('lanc_produto');
    const selectedOption = select.options[select.selectedIndex];
    const estoque = selectedOption.getAttribute('data-estoque');
    
    const inputQtd = document.getElementById('lanc_quantidade');
    inputQtd.max = estoque;
    
    // Se a quantidade digitada for maior que o estoque, zera:
    if (parseInt(inputQtd.value) > parseInt(estoque)) {
        inputQtd.value = estoque;
    }
}
window.onload = atualizaMaxEstoque; // atualiza no carregamento
</script>
<script>
function atualizaValorServico() {
    const select = document.getElementById('lanc_servico');
    const selectedOption = select.options[select.selectedIndex];
    const valor = selectedOption.getAttribute('data-valor');

    const inputValor = document.getElementById('lanc_valor');
    inputValor.value = valor;
}
window.onload = atualizaValorServico;
</script>

</body>
</html>

