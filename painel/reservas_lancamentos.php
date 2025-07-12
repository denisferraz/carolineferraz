<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

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

if($id_job == 'Produto'){
    $tutorial = 'ajudaLancamentoProduto()';
}else if($id_job == 'Serviço'){
    $tutorial = 'ajudaLancamentoServico()';
}else if($id_job == 'BaixaEstoque'){
    $tutorial = 'ajudaLancamentoEstoque()';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
    </style>
</head>
<body>
    
<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-cash-coin"></i>
                <?php if($id_job == 'Produto'){ ?>
                Lançar Produto
                <?php } ?>
                <?php if($id_job == 'Serviço'){ ?>
                Lançar Serviço
                <?php } ?>
                <?php if($id_job == 'BaixaEstoque'){ ?>
                Cadastrar Baixa de Estoque
                <?php } ?> <i class="bi bi-question-square-fill"onclick="<?php echo $tutorial; ?>"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                <?php if($id_job == 'Produto'){ ?>
                Confirme os dados para lançar um produto
                <?php } ?>
                <?php if($id_job == 'Serviço'){ ?>
                Confirme os dados para lançar um serviço
                <?php } ?>
                <?php if($id_job == 'BaixaEstoque'){ ?>
                Confirme os dados para cadastrar uma baixa de estoque
                <?php } ?>
            </p>
        </div>
    </div>
    
<form class="form" action="acao.php" method="POST">
    
    <div class="form-section-title">
                <div data-step="2">
                <i class="bi bi-person-vcard"></i> <?php echo $doc_nome ?><br>
                <i class="bi bi-envelope"></i> <?php echo $doc_email ?><br>
                </div>
            </div>

<div class="form-row">
                <div class="health-form-group">
                    
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            
            <?php if($id_job == 'Produto'){ ?>
            <label class="health-label">Produto</label>
            <select class="health-select" data-step="3" name="lanc_produto" id="lanc_produto" required onchange="atualizaMaxEstoque()">
            <option value="" disabled selected>-- Escolha um Produto --</option>
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
            <label class="health-label">Quantidade</label>
            <input class="health-input" data-step="4" min="1" max="999" type="number" name="lanc_quantidade" id="lanc_quantidade" placeholder="000" required>
            <label class="health-label">Valor</label>
            <input class="health-input" data-step="5" minlength="1" maxlength="9999" type="text" id="lanc_valor" name="lanc_valor" placeholder="000.00" required>
            <input type="hidden" name="tipo_lanc" value="produto">
            <?php }else if($id_job == 'Serviço'){ ?>
            <label class="health-label">Serviço</label>
            <select class="health-select" data-step="1.3" name="lanc_produto" id="lanc_servico" required onchange="atualizaValorServico()">
            <option value="" disabled selected>-- Escolha um Serviço --</option>
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
            <label class="health-label">Valor</label>
            <input class="health-input" data-step="1.4" minlength="1" maxlength="9999" type="text" id="lanc_valor" name="lanc_valor" placeholder="000.00" required>
            <input type="hidden" name="tipo_lanc" value="servico">
            <?php }else if($id_job == 'BaixaEstoque'){ ?>
            <label>Produto (Baixa Direta de Estoque)</label>
            <select class="health-select" data-step="2.3" name="lanc_produto" id="lanc_produto" required onchange="atualizaMaxEstoque()">
            <option value="" disabled selected>-- Escolha um Produto --</option>
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
            <label class="health-label">Quantidade</label>
            <input class="health-select" data-step="2.4" min="1" max="999" type="number" name="lanc_quantidade" id="lanc_quantidade" placeholder="000" required>
            <input type="hidden" id="lanc_valor" name="lanc_valor" value="0">
            <input type="hidden" name="tipo_lanc" value="estoque">
            <?php } ?>
            
            <br><br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div data-step="6"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Confirmar</button></div>

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

