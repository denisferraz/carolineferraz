<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

use Dompdf\Dompdf;
require '../vendor/autoload.php';

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

 $query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

//Criando a Instancia
$dompdf = new DOMPDF();

$relatorio_inicio = mysqli_real_escape_string($conn_msqli, $_POST['relatorio_inicio']);
$relatorio_fim = date('y-m-d', strtotime("$relatorio_inicio") + 86400);
$relatorio_fim_outros = date('y-m-d', strtotime("$relatorio_inicio"));
$relatorio_inicio_str = date('d/m/Y', strtotime("$relatorio_inicio"));
$data_gerador = date('d/m/Y \- H:i:s\h');
$relatorio = mysqli_real_escape_string($conn_msqli, $_POST['relatorio']);

$relatorio_inicio_mes = date('Y-m-1', strtotime("$relatorio_inicio"));
$relatorio_inicio_ano = date('Y-1-1', strtotime("$relatorio_inicio"));
$inicio_fim_mes = (strtotime("$relatorio_inicio") - strtotime("$relatorio_inicio_mes") + 86400) / 86400;
$inicio_fim_ano = (strtotime("$relatorio_inicio") - strtotime("$relatorio_inicio_ano") + 86400) / 86400;


$gera_footer = "<center><b><u>
$config_empresa - CNPJ: $config_cnpj<br>
$config_telefone - $config_email<br>
$config_endereco<br>
</u></b></center>";

if($relatorio == 'Gerencial'){
    $tipo = 'portrait';

$dias_trabalho = 0;
if($config_dia_segunda != -1){
$dias_trabalho++;
}
if($config_dia_terca != -1){
$dias_trabalho++;
}
if($config_dia_quarta != -1){
$dias_trabalho++;
}
if($config_dia_quinta != -1){
$dias_trabalho++;
}
if($config_dia_sexta != -1){
$dias_trabalho++;
}
if($config_dia_sabado != -1){
$dias_trabalho++;
}
if($config_dia_domingo != -1){
$dias_trabalho++;
}

    //Relatorios Dia
    $check_lanc = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE tipo = 'Produto' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_lanc->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc = $check_lanc->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_dia = $total_lanc['sum(valor)'];
    }
    $check_dinheiro = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_dinheiro->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro = $check_dinheiro->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_dia = number_format(($total_dinheiro['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_cartao = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_cartao->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao = $check_cartao->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_dia = number_format(($total_cartao['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_transferencia = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_transferencia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia = $check_transferencia->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_dia = number_format(($total_transferencia['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_outros = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_outros->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_outros = $check_outros->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_dia = number_format(($total_outros['sum(valor)'] * (-1)) ,2,",",".");
    }

    $row_dispnibilidade_dia = $conexao->prepare("SELECT * FROM $tabela_disponibilidade WHERE atendimento_dia = :relatorio_inicio AND confirmacao = 'Closed'");
    $row_dispnibilidade_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_dia = $row_dispnibilidade_dia->rowCount();
    $row_cancelamentos_dia = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :relatorio_inicio AND status_reserva = 'Cancelada'");
    $row_cancelamentos_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_dia = $row_cancelamentos_dia->rowCount();
    $row_reservas_dia = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :relatorio_inicio");
    $row_reservas_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_reservas_dia = $row_reservas_dia->rowCount();
    $arrivals_dia = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :relatorio_inicio AND status_reserva = 'Finalizada'");
    $arrivals_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $arrivals_dia = $arrivals_dia->rowCount();
    $noshows_dia = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia = :relatorio_inicio AND status_reserva = 'NoShow'");
    $noshows_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $noshows_dia = $noshows_dia->rowCount();

    //Despesas
    $check_despesa_aluguel_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_dia = $check_despesa_aluguel_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_dia = number_format(($total_despesa_aluguel_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_luz_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_dia = $check_despesa_luz_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_dia = number_format(($total_despesa_luz_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_internet_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_dia = $check_despesa_internet_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_dia = number_format(($total_despesa_internet_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_insumos_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_dia = $check_despesa_insumos_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_dia = number_format(($total_despesa_insumos_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_mobiliario_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_dia = $check_despesa_mobiliario_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_dia = number_format(($total_despesa_mobiliario_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_dia = $check_despesa_equipamentos_aluguel_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_dia = number_format(($total_despesa_equipamentos_aluguel_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_dia = $check_despesa_equipamentos_compra_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_dia = number_format(($total_despesa_equipamentos_compra_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_outros_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_dia = $check_despesa_outros_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_dia = number_format(($total_despesa_outros_dia['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_total_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_total_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_total_dia = $check_despesa_total_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_total_dia = $total_despesa_total_dia['sum(despesa_valor)'];
    }

    if($despesa_total_dia == ''){
        $despesa_total_dia = 0;
    }

    if($receita_lancamento_dia == ''){
        $receita_lancamento_dia = 0;
    }

    $lucro_liquido_dia = number_format(($receita_lancamento_dia - $despesa_total_dia) ,2,",",".");

    $despesa_total_dia = number_format($despesa_total_dia ,2,",",".");
    $receita_lancamentos_dia = number_format($receita_lancamento_dia ,2,",",".");

    //Relatorios Mensal
    $check_lanc_mes = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE tipo = 'Produto' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_lanc_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc_mes = $check_lanc_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_mes = $total_lanc_mes['sum(valor)'];
    }
    $check_dinheiro_mes = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_dinheiro_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro_mes = $check_dinheiro_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_mes = number_format(($total_dinheiro_mes['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_cartao_mes = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_cartao_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao_mes = $check_cartao_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_mes = number_format(($total_cartao_mes['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_transferencia_mes = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_transferencia_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia_mes = $check_transferencia_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_mes = number_format(($total_transferencia_mes['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_outros_mes = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_outros_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_outros_mes = $check_outros_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_mes = number_format(($total_outros_mes['sum(valor)'] * (-1)) ,2,",",".");
    }

    $row_dispnibilidade_mes = $conexao->prepare("SELECT * FROM $tabela_disponibilidade WHERE atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND confirmacao = 'Closed'");
    $row_dispnibilidade_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_mes = $row_dispnibilidade_mes->rowCount();
    $row_cancelamentos_mes = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'Cancelada'");
    $row_cancelamentos_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_mes = $row_cancelamentos_mes->rowCount();
    $row_reservas_mes = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio");
    $row_reservas_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_reservas_mes = $row_reservas_mes->rowCount();
    $arrivals_mes = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'Finalizada'");
    $arrivals_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_mes = $arrivals_mes->rowCount();
    $noshows_mes = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'NoShow'");
    $noshows_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $noshows_mes = $noshows_mes->rowCount();

    //Despesas
    $check_despesa_aluguel_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_mes = $check_despesa_aluguel_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_mes = number_format(($total_despesa_aluguel_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_luz_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_mes = $check_despesa_luz_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_mes = number_format(($total_despesa_luz_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_internet_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_mes = $check_despesa_internet_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_mes = number_format(($total_despesa_internet_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_insumos_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_mes = $check_despesa_insumos_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_mes = number_format(($total_despesa_insumos_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_mobiliario_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_mes = $check_despesa_mobiliario_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_mes = number_format(($total_despesa_mobiliario_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_mes = $check_despesa_equipamentos_aluguel_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_mes = number_format(($total_despesa_equipamentos_aluguel_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_mes = $check_despesa_equipamentos_compra_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_mes = number_format(($total_despesa_equipamentos_compra_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_outros_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_mes = $check_despesa_outros_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_mes = number_format(($total_despesa_outros_mes['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_total_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_total_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_total_mes = $check_despesa_total_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_total_mes = $total_despesa_total_mes['sum(despesa_valor)'];
    }

    if($despesa_total_mes == ''){
        $despesa_total_mes = 0;
    }

    if($receita_lancamento_mes == ''){
        $receita_lancamento_mes = 0;
    }
    
    $lucro_liquido_mes = number_format(($receita_lancamento_mes - $despesa_total_mes) ,2,",",".");
    
    $despesa_total_mes = number_format($despesa_total_mes ,2,",",".");
    $receita_lancamentos_mes = number_format($receita_lancamento_mes ,2,",",".");

    //Relatorios Anual
    $check_lanc_ano = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE tipo = 'Produto' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_lanc_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc_ano = $check_lanc_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_ano = $total_lanc_ano['sum(valor)'];
    }
    $check_dinheiro_ano = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_dinheiro_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro_ano = $check_dinheiro_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_ano = number_format(($total_dinheiro_ano['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_cartao_ano = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_cartao_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao_ano = $check_cartao_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_ano = number_format(($total_cartao_ano['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_transferencia_ano = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_transferencia_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia_ano = $check_transferencia_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_ano = number_format(($total_transferencia_ano['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_outros_ano = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_outros_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_outros_ano = $check_outros_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_ano = number_format(($total_outros_ano['sum(valor)'] * (-1)) ,2,",",".");
    }

    $row_dispnibilidade_ano = $conexao->prepare("SELECT * FROM $tabela_disponibilidade WHERE atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND confirmacao = 'Closed'");
    $row_dispnibilidade_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_ano = $row_dispnibilidade_ano->rowCount();
    $row_cancelamentos_ano = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'Cancelada'");
    $row_cancelamentos_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_ano = $row_cancelamentos_ano->rowCount();
    $row_reservas_ano = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio");
    $row_reservas_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_reservas_ano = $row_reservas_ano->rowCount();
    $arrivals_ano = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'Finalizada'");
    $arrivals_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_ano = $arrivals_ano->rowCount();
    $noshows_ano = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_reserva = 'NoShow'");
    $noshows_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $noshows_ano = $noshows_ano->rowCount();

    //Despesas
    $check_despesa_aluguel_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_ano = $check_despesa_aluguel_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_ano = number_format(($total_despesa_aluguel_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_luz_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_ano = $check_despesa_luz_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_ano = number_format(($total_despesa_luz_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_internet_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_ano = $check_despesa_internet_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_ano = number_format(($total_despesa_internet_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_insumos_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_ano = $check_despesa_insumos_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_ano = number_format(($total_despesa_insumos_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_mobiliario_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_ano = $check_despesa_mobiliario_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_ano = number_format(($total_despesa_mobiliario_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_ano = $check_despesa_equipamentos_aluguel_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_ano = number_format(($total_despesa_equipamentos_aluguel_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_ano = $check_despesa_equipamentos_compra_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_ano = number_format(($total_despesa_equipamentos_compra_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_outros_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_ano = $check_despesa_outros_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_ano = number_format(($total_despesa_outros_ano['sum(despesa_valor)'] * (1)) ,2,",",".");
    }
    $check_despesa_total_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_total_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_total_ano = $check_despesa_total_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_total_ano = $total_despesa_total_ano['sum(despesa_valor)'];
    }

    if($despesa_total_ano == ''){
        $despesa_total_ano = 0;
    }

    if($receita_lancamento_ano == ''){
        $receita_lancamento_ano = 0;
    }
    
    $lucro_liquido_ano = number_format(($receita_lancamento_ano - $despesa_total_ano) ,2,",",".");
    
    $despesa_total_ano = number_format($despesa_total_ano ,2,",",".");
    $receita_lancamentos_ano = number_format($receita_lancamento_ano ,2,",",".");

    $inventario_dia = ( strtotime("$config_atendimento_hora_fim") - strtotime("$config_atendimento_hora_comeco") ) / ( $config_atendimento_hora_intervalo * 60 ) - $row_dispnibilidade_dia;
    $inventario_mes = number_format( ( ($inventario_dia + $row_dispnibilidade_dia) * $inicio_fim_mes / 7 * $dias_trabalho) ,0,"","") - $row_dispnibilidade_mes;
    $inventario_ano = number_format( ( ($inventario_dia + $row_dispnibilidade_dia) * $inicio_fim_ano / 7 * $dias_trabalho) ,0,"","") - $row_dispnibilidade_ano;

    $ocupacao_dia = number_format( floatval(( ($row_reservas_dia - $row_cancelamentos_dia - $noshows_dia ) / $inventario_dia) * 100) ,2,",",".");
    $ocupacao_mes = number_format( floatval(( ($row_reservas_mes - $row_cancelamentos_mes - $noshows_mes ) / $inventario_mes) * 100) ,2,",",".");
    $ocupacao_ano = number_format( floatval(( ($row_reservas_ano - $row_cancelamentos_ano - $noshows_ano ) / $inventario_ano) * 100) ,2,",",".");

    
    //Corpo do PDF
    $gera_body = "
    <fieldset>
    <center><b>Receitas</b><br></center>
    <table width=100% border=2px>
    <tr><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Capacidade</td><td align=center>$inventario_dia</td><td align=center>$inventario_mes</td><td align=center>$inventario_ano</td></tr>
    <tr><td><b>Bloqueados</td><td align=center>$row_dispnibilidade_dia</td><td align=center>$row_dispnibilidade_mes</td><td align=center>$row_dispnibilidade_ano</td></tr>
    <tr><td><b>Consultas</td><td align=center>$row_reservas_dia</td><td align=center>$row_reservas_mes</td><td align=center>$row_reservas_ano</td></tr>
    <tr><td><b>Finalizadas</td><td align=center>$arrivals_dia</td><td align=center>$arrivals_mes</td><td align=center>$arrivals_ano</td></tr>
    <tr><td><b>Canceladas</td><td align=center>$row_cancelamentos_dia</td><td align=center>$row_cancelamentos_mes</td><td align=center>$row_cancelamentos_ano</td></tr>
    <tr><td><b>No-Shows</td><td align=center>$noshows_dia</td><td align=center>$noshows_mes</td><td align=center>$noshows_ano</td></tr>
    <tr><td><b>Performance</td><td align=center>$ocupacao_dia%</td><td align=center>$ocupacao_mes%</td><td align=center>$ocupacao_ano%</td></tr>
    <tr><td><b>Receita Total</td><td align=center>R$$receita_lancamentos_dia</td><td align=center>R$$receita_lancamentos_mes</td><td align=center>R$$receita_lancamentos_ano</td></tr>
    <tr><td><b>Pagamento em Cartão</td><td align=center>R$$receita_cartao_dia</td><td align=center>R$$receita_cartao_mes</td><td align=center>R$$receita_cartao_ano</td></tr>
    <tr><td><b>Pagamento em Dinheiro</td><td align=center>R$$receita_dinheiro_dia</td><td align=center>R$$receita_dinheiro_mes</td><td align=center>R$$receita_dinheiro_ano</td></tr>
    <tr><td><b>Pagamento em Transferencias</td><td align=center>R$$receita_transferencia_dia</td><td align=center>R$$receita_transferencia_mes</td><td align=center>R$$receita_transferencia_ano</td></tr>
    <tr><td><b>Pagamento em Outros</td><td align=center>R$$receita_outros_dia</td><td align=center>R$$receita_outros_mes</td><td align=center>R$$receita_outros_ano</td></tr>
    </table>
    </fieldset>
    <fieldset>
    <center><b>Despesas</b><br></center>
    <table width=100% border=2px>
    <tr><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Aluguel</td><td align=center>R$$despesa_aluguel_dia</td><td align=center>R$$despesa_aluguel_mes</td><td align=center>R$$despesa_aluguel_ano</td></tr>
    <tr><td><b>Luz</td><td align=center>R$$despesa_luz_dia</td><td align=center>R$$despesa_luz_mes</td><td align=center>R$$despesa_luz_ano</td></tr>
    <tr><td><b>Internet</td><td align=center>R$$despesa_internet_dia</td><td align=center>R$$despesa_internet_mes</td><td align=center>R$$despesa_internet_ano</td></tr>
    <tr><td><b>Insumos</td><td align=center>R$$despesa_insumos_dia</td><td align=center>R$$despesa_insumos_mes</td><td align=center>R$$despesa_insumos_ano</td></tr>
    <tr><td><b>Mobiliario</td><td align=center>R$$despesa_mobiliario_dia</td><td align=center>R$$despesa_mobiliario_mes</td><td align=center>R$$despesa_mobiliario_ano</td></tr>
    <tr><td><b>Equipamentos [Aluguel]</td><td align=center>R$$despesa_equipamentos_aluguel_dia</td><td align=center>R$$despesa_equipamentos_aluguel_mes</td><td align=center>R$$despesa_equipamentos_aluguel_ano</td></tr>
    <tr><td><b>Equipamentos [Compra]</td><td align=center>R$$despesa_equipamentos_compra_dia</td><td align=center>R$$despesa_equipamentos_compra_mes</td><td align=center>R$$despesa_equipamentos_compra_ano</td></tr>
    <tr><td><b>Outros</td><td align=center>R$$despesa_outros_dia</td><td align=center>R$$despesa_outros_mes</td><td align=center>R$$despesa_outros_ano</td></tr>
    <tr><td><b>Despesas Total</td><td align=center>R$$despesa_total_dia</td><td align=center>R$$despesa_total_mes</td><td align=center>R$$despesa_total_ano</td></tr>
    <tr><td><b>Lucro Liquido</td><td align=center>R$$lucro_liquido_dia</td><td align=center>R$$lucro_liquido_mes</td><td align=center>R$$lucro_liquido_ano</td></tr>
    </table>
    </fieldset><br>
                ";
}else if($relatorio == 'Estornos Dia' || $relatorio == 'Estornos Mes' || $relatorio == 'Estornos Ano'){
    $tipo = 'landscape';

$resultado_estorno = '';
if($relatorio == 'Estornos Dia'){
$query_estorno = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Estornos Mes'){
$query_estorno = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_estorno = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}
$estorno_total = $query_estorno->rowCount();
if($estorno_total > 0){
while($select_estorno = $query_estorno->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select_estorno['confirmacao'];
$nome = $select_estorno['doc_nome'];
$produto = $select_estorno['produto'];
$feitopor = $select_estorno['feitopor'];
$quando = $select_estorno['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$resultado_estorno = "$resultado_estorno<tr><td align=center>[$confirmacao] $nome</td><td>$produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
}

}else{
$resultado_estorno = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$estorno_total = 0;
}


//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Estornos</b>: $estorno_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>[Confirmacao] Nome</b></td>
<td align=center width=55%><b>Descrição Produto</b></td>
<td align=center width=20%><b>Responsavel/<b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_estorno
</table>
</fieldset>
            ";
}else if($relatorio == 'Lançamentos Dia' || $relatorio == 'Lançamentos Mes' || $relatorio == 'Lançamentos Ano'){
    $tipo = 'landscape';

$resultado_lanc = '';
if($relatorio == 'Lançamentos Dia'){
$query_lanc = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Lançamentos Mes'){
$query_lanc = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_lanc = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}
$lanc_total = $query_lanc->rowCount();
if($lanc_total > 0){
while($select_lanc = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select_lanc['confirmacao'];
$nome = $select_lanc['doc_nome'];
$produto = $select_lanc['produto'];
$feitopor = $select_lanc['feitopor'];
$quando = $select_lanc['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_lanc['valor'];
$valor = number_format($valor ,2,",",".");

$resultado_lanc = "$resultado_lanc<tr><td align=center>[$confirmacao] $nome</td><td>[ R$$valor ] $produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
}
}else{
$resultado_lanc = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$lanc_total = 0;
}

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Lançamentos</b>: $lanc_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>[Confirmacao] Nome</b></td>
<td align=center width=55%><b>[ Valor ] Descrição Produto</b></td>
<td align=center width=20%><b>Responsavel</b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_lanc
</table>
</fieldset>
            ";
}else if($relatorio == 'Pagamentos Dia' || $relatorio == 'Pagamentos Mes' || $relatorio == 'Pagamentos Ano'){
    $tipo = 'landscape';

$resultado_pgto = '';
if($relatorio == 'Pagamentos Dia'){
$query_pgto = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}else if($relatorio == 'Pagamentos Mes'){
$query_pgto = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}else{
$query_pgto = $conexao->query("SELECT * FROM $tabela_lancamentos WHERE quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}
$pgto_total = $query_pgto->rowCount();
if($pgto_total > 0){
while($select_pgto = $query_pgto->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select_pgto['confirmacao'];
$nome = $select_pgto['doc_nome'];
$produto = $select_pgto['produto'];
$feitopor = $select_pgto['feitopor'];
$quando = $select_pgto['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_pgto['valor'];
$valor = number_format(($valor * (-1)) ,2,",",".");

$resultado_pgto = "$resultado_pgto<tr><td align=center>[$confirmacao] $nome</td><td>[ R$$valor ] $produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
}
}else{
$resultado_pgto = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$pgto_total = 0;
    }

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Pagamentos</b>: $pgto_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>[Confirmação] Nome</b></td>
<td align=center width=55%><b>Descrição do Pagamento</b></td>
<td align=center width=20%><b>Responsavel</b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_pgto
</table>
</fieldset>
            ";
}else if($relatorio == 'Despesas Dia' || $relatorio == 'Despesas Mes' || $relatorio == 'Despesas Ano'){
    $tipo = 'landscape';

$resultado_despesa = '';
if($relatorio == 'Despesas Dia'){
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE despesa_dia >= '{$relatorio_inicio}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}else if($relatorio == 'Despesas Mes'){
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE despesa_dia >= '{$relatorio_inicio_mes}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}else{
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE despesa_dia >= '{$relatorio_inicio_ano}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}
$despesas_total = $query_despesas->rowCount();
if($despesas_total > 0){
while($select_despesas = $query_despesas->fetch(PDO::FETCH_ASSOC)){
$despesa_dia = $select_despesas['despesa_dia'];
$despesa_tipo = $select_despesas['despesa_tipo'];
$despesa_descricao = $select_despesas['despesa_descricao'];
$despesa_quem = $select_despesas['despesa_quem'];
$despesa_dia = $select_despesas['despesa_dia'];
$despesa_dia = date('d/m/Y', strtotime("$despesa_dia"));
$despesa_valor = $select_despesas['despesa_valor'];
$despesa_valor = number_format($despesa_valor ,2,",",".");

$resultado_despesa = "$resultado_despesa<tr><td align=center>$despesa_tipo</td><td>[ R$$despesa_valor ] $despesa_descricao</td><td>$despesa_quem</td><td align=center>$despesa_dia</td></tr>";
}
}else{
$resultado_despesa = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$despesa_total = 0;
}

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Lançamentos</b>: $despesa_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>Tipo</b></td>
<td align=center width=55%><b>[ Valor ] Descrição Despesa</b></td>
<td align=center width=20%><b>Responsavel</b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_despesa
</table>
</fieldset>
            ";
}else if($relatorio == 'Consultas Dia' || $relatorio == 'Consultas Mes' || $relatorio == 'Consultas Ano'){
    $tipo = 'landscape';

$resultado_reservas = '';
if($relatorio == 'Consultas Dia'){
$query_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia = '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}else if($relatorio == 'Consultas Mes'){
$query_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}

$reservas_total = $query_reservas->rowCount();
if($reservas_total > 0){
while($select_reservas = $query_reservas->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_reservas['doc_nome'];
$confirmacao = $select_reservas['confirmacao'];
$status_reserva = $select_reservas['status_reserva'];
$atendimento_dia = $select_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));

$resultado_reservas = "$resultado_reservas<tr><td align=center>$confirmacao [$status_reserva]</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
}
}else{
$resultado_reservas = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$reservas_total = 0;
    }

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Consultas</b>: $reservas_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Confirmação [Status]</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_reservas
</table>
</fieldset>
            ";
}else if($relatorio == 'Cancelamentos Dia' || $relatorio == 'Cancelamentos Mes' || $relatorio == 'Cancelamentos Ano'){
    $tipo = 'landscape';

$resultado_canc_reservas = '';
if($relatorio == 'Cancelamentos Dia'){
$query_canc_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia = '{$relatorio_inicio}' AND status_reserva = 'Cancelada' ORDER BY atendimento_hora");
}else if($relatorio == 'Cancelamentos Mes'){
$query_canc_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_reserva = 'Cancelada' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_canc_reservas = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_reserva = 'Cancelada' ORDER BY atendimento_dia, atendimento_hora");
}

$canc_reservas_total = $query_canc_reservas->rowCount();
if($canc_reservas_total > 0){
while($select_canc_reservas = $query_canc_reservas->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_canc_reservas['doc_nome'];
$confirmacao = $select_canc_reservas['confirmacao'];
$status_reserva = $select_canc_reservas['status_reserva'];
$atendimento_dia = $select_canc_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_canc_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));

$resultado_canc_reservas = "$resultado_canc_reservas<tr><td align=center>$confirmacao [$status_reserva]</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
}
}else{
$resultado_canc_reservas = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$canc_reservas_total = 0;
    }

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Cancelamentos</b>: $canc_reservas_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Confirmação [Status]</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_canc_reservas
</table>
</fieldset>
            ";
}else if($relatorio == 'No-Shows Dia' || $relatorio == 'No-Shows Mes' || $relatorio == 'No-Shows Ano'){
    $tipo = 'landscape';

$resultado_noshows = '';
if($relatorio == 'No-Shows Dia'){
$query_noshows = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia = '{$relatorio_inicio}' AND status_reserva = 'NoShow' ORDER BY atendimento_hora");
}else if($relatorio == 'No-Shows Mes'){
$query_noshows = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_reserva = 'NoShow' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_noshows = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_reserva = 'NoShow' ORDER BY atendimento_dia, atendimento_hora");
}
$noshows_total = $query_noshows->rowCount();
if($noshows_total > 0){
while($select_noshows = $query_noshows->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_noshows['doc_nome'];
$confirmacao = $select_noshows['confirmacao'];
$status_reserva = $select_noshows['status_reserva'];
$atendimento_dia = $select_noshows['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$select_noshows->atendimento_dia"));
$atendimento_hora = $select_noshows['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$select_noshows->atendimento_hora"));

$resultado_noshows = "$resultado_noshows<tr><td align=center>$confirmacao [$status_reserva]</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
}
}else{
$resultado_noshows = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$canc_noshows = 0;
    }

//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de No-Shows</b>: $noshows_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Confirmação [Status]</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_noshows
</table>
</fieldset>
            ";
}
			// Carrega seu HTML
	$dompdf->loadHtml('
			<!DOCTYPE html>
			<html lang="pt-br">
				<head>
					<meta charset="utf-8">
                    <link rel="stylesheet" href="css/gerar.css">
					<title>Relatorio Gerencial - '.$relatorio.'</title>
				</head><body>
                <div class="geral">
                <div class="data_gerador">
                <small>'.$data_gerador.'</small>
                </div>
                <center><h1>Relatorio Gerencial - '.$relatorio.' - '.$relatorio_inicio_str.'</h1></center>
                <br>
                <div class="content">
                '.$gera_body.'
                </div>
                <div class="footer">
                '.$gera_footer.'
                </div>
                </div>
				</body></html>
		');

	$dompdf->setPaper('A4', $tipo);
	//Renderizar o html
	$dompdf->render();
	//Exibibir a página
	$dompdf->stream(
		"Relatorio Gerencial - '$relatorio'.pdf", 
		array(
			"Attachment" => true //Para realizar o download somente alterar para true
		)
	);

?>

<?php
}
?>