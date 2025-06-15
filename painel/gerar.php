<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

use Dompdf\Dompdf;
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Color;

$worksheet_password = 'h8185@Accor';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

//Criar Planilha Excel
$spreadsheet = new Spreadsheet();

$conditionRed = new Conditional();
$conditionRed->setConditionType(Conditional::CONDITION_CELLIS)
    ->setOperatorType(Conditional::OPERATOR_EQUAL)
    ->addCondition('X')
    ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(Color::COLOR_DARKRED);

$conditionGrey = new Conditional();
$conditionGrey->setConditionType(Conditional::CONDITION_CELLIS)
    ->setOperatorType(Conditional::OPERATOR_NOTEQUAL)
    ->addCondition('0')
    ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(Color::COLOR_MAGENTA);

$spreadsheet->getProperties()
->setCreator("Denis Ferraz")
->setLastModifiedBy("Denis Ferraz")
->setTitle("Sistema de Agendamento - Caroline Ferraz");

$security = $spreadsheet->getSecurity();
$security->setLockWindows(true);
$security->setLockStructure(true);
$security->setWorkbookPassword("$worksheet_password");

//Linhas de Configurações e Cores das Linhas/COlunas
$styleArray_separacao = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFC0C0C0', // Cinza
        ],
    ]
];

$styleArray_cinza = [
    'font' => [
        'bold' => true,
        'size' => 11,
        'name' => 'Calibri',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFC0C0C0', // Cinza
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_laranja = [
    'font' => [
        'bold' => true,
        'size' => 11,
        'name' => 'Calibri',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFA500', //  Laranja
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_amarelo = [
    'font' => [
        'bold' => true,
        'size' => 10,
        'name' => 'Calibri',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF00', //  Amarelo
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_branco5 = [
    'font' => [
        'bold' => false,
        'size' => 10,
        'name' => 'Calibri',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'F0F0F0', // Branco 5%
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_branco = [
    'font' => [
        'bold' => true,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF', // Branco
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_branco_10 = [
    'font' => [
        'bold' => true,
        'size' => 10,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF', // Branco
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_preto = [
    'font' => [
        'bold' => false,
        'size' => 10,
        'name' => 'Calibri',
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'black', // Preto
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_verde = [
    'font' => [
        'bold' => false,
        'size' => 10,
        'name' => 'Calibri',
        'color' => ['rgb' => '00FF00']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF00', // Verde
        ],
    ]
];

$styleArray_green = [
    'font' => [
        'bold' => true,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '0ddb9c', // Green
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_red = [
    'font' => [
        'bold' => true,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'ff0000', // Red
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_blue = [
    'font' => [
        'bold' => true,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'ADD8E6', // Blue
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_verdana_left = [
    'font' => [
        'bold' => false,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleArray_verdana_left_bold = [
    'font' => [
        'bold' => true,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Inside Borders
$styleArray_inside_borders = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => 'thin',
            'color' => ['rgb' => '000000'],
        ],
        'outline' => [
            'borderStyle' => 'thick',
            'color' => ['rgb' => '000000'],
        ],
    ],
];

$styleArray_outside_borders = [
    'borders' => [
        'outline' => [
            'borderStyle' => 'thick',
            'color' => ['rgb' => '000000'],
        ],
    ],
];

$styleArray_outside_borders_fina = [
    'borders' => [
        'outline' => [
            'borderStyle' => 'thin',
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'font' => [
        'bold' => true,
        'size' => 11,
        'name' => 'Calibri'
    ]
];

$styleArray_bold = [
    'font' => [
        'bold' => true,
        'size' => 11,
        'name' => 'Calibri'
    ]
];

$styleArray_creditlimit = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FF0000', // Vermelho
        ],
    ]
];

$styleArray_difiss = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FF0000', // Vermelho
        ],
    ]
];

$styleArray_difissok = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF00', // Verde
        ],
    ]
];

$styleArray_padrao = [
    'font' => [
        'bold' => false,
        'size' => 9,
        'name' => 'Verdana',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF', // Branco
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$dados_relatorio = [];

//Criando a Instancia
$dompdf = new DOMPDF();

$relatorio_inicio = mysqli_real_escape_string($conn_msqli, $_POST['relatorio_inicio']);
$relatorio_fim = date('y-m-d', strtotime("$relatorio_inicio") + 86400);
$relatorio_fim_outros = date('y-m-d', strtotime("$relatorio_inicio"));
$relatorio_inicio_str = date('d/m/Y', strtotime("$relatorio_inicio"));
$data_gerador = date('d/m/Y \- H:i:s\h');
$relatorio = mysqli_real_escape_string($conn_msqli, $_POST['relatorio']);
$relatorio_tipo = mysqli_real_escape_string($conn_msqli, $_POST['relatorio_tipo']);

$relatorio_inicio_mes = date('Y-m-1', strtotime("$relatorio_inicio"));
$relatorio_inicio_ano = date('Y-1-1', strtotime("$relatorio_inicio"));
$inicio_fim_mes = (strtotime("$relatorio_inicio") - strtotime("$relatorio_inicio_mes") + 86400) / 86400;
$inicio_fim_ano = (strtotime("$relatorio_inicio") - strtotime("$relatorio_inicio_ano") + 86400) / 86400;


$gera_footer = "<center><b><u><br>
$config_empresa<br><br>
$config_endereco
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
    $check_lanc = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND tipo = 'Produto' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_lanc->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc = $check_lanc->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_dia = $total_lanc['sum(valor)'];
    }
    $check_dinheiro = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_dinheiro->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro = $check_dinheiro->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_dia = number_format(($total_dinheiro['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_cartao = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_cartao->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao = $check_cartao->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_dia = number_format(($total_cartao['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_transferencia = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_transferencia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia = $check_transferencia->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_dia = number_format(($total_transferencia['sum(valor)'] * (-1)) ,2,",",".");
    }
    $check_outros = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio AND quando <= :relatorio_fim"); 
    $check_outros->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_outros = $check_outros->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_dia = number_format(($total_outros['sum(valor)'] * (-1)) ,2,",",".");
    }

    $row_dispnibilidade_dia = $conexao->prepare("SELECT * FROM disponibilidade WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio");
    $row_dispnibilidade_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_dia = $row_dispnibilidade_dia->rowCount();
    $row_cancelamentos_dia = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio AND status_consulta = 'Cancelada'");
    $row_cancelamentos_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_dia = $row_cancelamentos_dia->rowCount();
    $row_reservas_dia = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio AND tipo_consulta != 'Nova Sessão'");
    $row_reservas_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $row_reservas_dia = $row_reservas_dia->rowCount();
    $arrivals_dia = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio AND status_consulta = 'Finalizada'");
    $arrivals_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $arrivals_dia = $arrivals_dia->rowCount();
    $arrivals_dia_consultas = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio AND tipo_consulta = 'Nova Sessão' AND (status_consulta != 'Cancelada' OR status_consulta != 'NoShow')");
    $arrivals_dia_consultas->execute(array('relatorio_inicio' => $relatorio_inicio));
    $arrivals_dia_consultas = $arrivals_dia_consultas->rowCount();
    $noshows_dia = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = :relatorio_inicio AND status_consulta = 'NoShow'");
    $noshows_dia->execute(array('relatorio_inicio' => $relatorio_inicio));
    $noshows_dia = $noshows_dia->rowCount();

    //Despesas
    $check_despesa_aluguel_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_dia = $check_despesa_aluguel_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_dia = number_format(($total_despesa_aluguel_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_luz_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_dia = $check_despesa_luz_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_dia = number_format(($total_despesa_luz_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_internet_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_dia = $check_despesa_internet_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_dia = number_format(($total_despesa_internet_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_insumos_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_dia = $check_despesa_insumos_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_dia = number_format(($total_despesa_insumos_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_mobiliario_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_dia = $check_despesa_mobiliario_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_dia = number_format(($total_despesa_mobiliario_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_dia = $check_despesa_equipamentos_aluguel_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_dia = number_format(($total_despesa_equipamentos_aluguel_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_dia = $check_despesa_equipamentos_compra_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_dia = number_format(($total_despesa_equipamentos_compra_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_outros_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_dia->execute(array('relatorio_inicio' => $relatorio_inicio, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_dia = $check_despesa_outros_dia->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_dia = number_format(($total_despesa_outros_dia['sum(despesa_valor)'] ?? 0) ,2,",",".");
    }
    $check_despesa_total_dia = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= :relatorio_inicio AND despesa_dia <= :relatorio_fim"); 
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
    $check_lanc_mes = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND tipo = 'Produto' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_lanc_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc_mes = $check_lanc_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_mes = $total_lanc_mes['sum(valor)'];
    }
    $check_dinheiro_mes = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_dinheiro_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro_mes = $check_dinheiro_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_mes = number_format(($total_dinheiro_mes['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_cartao_mes = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_cartao_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao_mes = $check_cartao_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_mes = number_format(($total_cartao_mes['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_transferencia_mes = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_transferencia_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia_mes = $check_transferencia_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_mes = number_format(($total_transferencia_mes['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_outros_mes = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_mes AND quando <= :relatorio_fim"); 
    $check_outros_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_outros_mes = $check_outros_mes->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_mes = number_format(($total_outros_mes['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }

    $row_dispnibilidade_mes = $conexao->prepare("SELECT * FROM disponibilidade WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio");
    $row_dispnibilidade_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_mes = $row_dispnibilidade_mes->rowCount();
    $row_cancelamentos_mes = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'Cancelada'");
    $row_cancelamentos_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_mes = $row_cancelamentos_mes->rowCount();
    $row_reservas_mes = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND tipo_consulta != 'Nova Sessão'");
    $row_reservas_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $row_reservas_mes = $row_reservas_mes->rowCount();
    $arrivals_mes = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'Finalizada'");
    $arrivals_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_mes = $arrivals_mes->rowCount();
    $arrivals_mes_consultas = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND tipo_consulta = 'Nova Sessão' AND (status_consulta != 'Cancelada' OR status_consulta != 'NoShow')");
    $arrivals_mes_consultas->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_mes_consultas = $arrivals_mes_consultas->rowCount();
    $noshows_mes = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_mes AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'NoShow'");
    $noshows_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_inicio' => $relatorio_inicio));
    $noshows_mes = $noshows_mes->rowCount();

    //Despesas
    $check_despesa_aluguel_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_mes = $check_despesa_aluguel_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_mes = number_format(($total_despesa_aluguel_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_luz_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_mes = $check_despesa_luz_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_mes = number_format(($total_despesa_luz_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_internet_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_mes = $check_despesa_internet_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_mes = number_format(($total_despesa_internet_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_insumos_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_mes = $check_despesa_insumos_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_mes = number_format(($total_despesa_insumos_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_mobiliario_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_mes = $check_despesa_mobiliario_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_mes = number_format(($total_despesa_mobiliario_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_mes = $check_despesa_equipamentos_aluguel_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_mes = number_format(($total_despesa_equipamentos_aluguel_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_mes = $check_despesa_equipamentos_compra_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_mes = number_format(($total_despesa_equipamentos_compra_mes['sum(despesa_valor)'] ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_outros_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_mes->execute(array('relatorio_inicio_mes' => $relatorio_inicio_mes, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_mes = $check_despesa_outros_mes->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_mes = number_format(($total_despesa_outros_mes['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_total_mes = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= :relatorio_inicio_mes AND despesa_dia <= :relatorio_fim"); 
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
    $check_lanc_ano = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND tipo = 'Produto' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_lanc_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_lanc_ano = $check_lanc_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_lancamento_ano = $total_lanc_ano['sum(valor)'];
    }
    $check_dinheiro_ano = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Cart%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_dinheiro_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_dinheiro_ano = $check_dinheiro_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_dinheiro_ano = number_format(($total_dinheiro_ano['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_cartao_ano = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Dinheiro%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_cartao_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_cartao_ano = $check_cartao_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_cartao_ano = number_format(($total_cartao_ano['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_transferencia_ano = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Transferencia%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_transferencia_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_transferencia_ano = $check_transferencia_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_transferencia_ano = number_format(($total_transferencia_ano['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }
    $check_outros_ano = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND produto LIKE '%Outros%' AND tipo = 'Pagamento' AND quando >= :relatorio_inicio_ano AND quando <= :relatorio_fim"); 
    $check_outros_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_outros_ano = $check_outros_ano->fetch(PDO::FETCH_ASSOC)){
    $receita_outros_ano = number_format(($total_outros_ano['sum(valor)']  ?? 0) * (-1) ,2,",",".");
    }

    $row_dispnibilidade_ano = $conexao->prepare("SELECT * FROM disponibilidade WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio");
    $row_dispnibilidade_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_dispnibilidade_ano = $row_dispnibilidade_ano->rowCount();
    $row_cancelamentos_ano = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'Cancelada'");
    $row_cancelamentos_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_cancelamentos_ano = $row_cancelamentos_ano->rowCount();
    $row_reservas_ano = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND tipo_consulta != 'Nova Sessão'");
    $row_reservas_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $row_reservas_ano = $row_reservas_ano->rowCount();
    $arrivals_ano = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'Finalizada'");
    $arrivals_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_ano = $arrivals_ano->rowCount();
    $arrivals_ano_consultas = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND tipo_consulta = 'Nova Sessão' AND (status_consulta != 'Cancelada' OR status_consulta != 'NoShow')");
    $arrivals_ano_consultas->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $arrivals_ano_consultas = $arrivals_ano_consultas->rowCount();
    $noshows_ano = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= :relatorio_inicio_ano AND atendimento_dia <= :relatorio_inicio AND status_consulta = 'NoShow'");
    $noshows_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_inicio' => $relatorio_inicio));
    $noshows_ano = $noshows_ano->rowCount();

    //Despesas
    $check_despesa_aluguel_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Aluguel' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_aluguel_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_aluguel_ano = $check_despesa_aluguel_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_aluguel_ano = number_format(($total_despesa_aluguel_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_luz_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Luz' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_luz_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_luz_ano = $check_despesa_luz_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_luz_ano = number_format(($total_despesa_luz_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_internet_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Internet' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_internet_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_internet_ano = $check_despesa_internet_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_internet_ano = number_format(($total_despesa_internet_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_insumos_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Insumos' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_insumos_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_insumos_ano = $check_despesa_insumos_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_insumos_ano = number_format(($total_despesa_insumos_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_mobiliario_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Mobiliario' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_mobiliario_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_mobiliario_ano = $check_despesa_mobiliario_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_mobiliario_ano = number_format(($total_despesa_mobiliario_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_equipamentos_aluguel_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Aluguel]' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_aluguel_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_aluguel_ano = $check_despesa_equipamentos_aluguel_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_aluguel_ano = number_format(($total_despesa_equipamentos_aluguel_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_equipamentos_compra_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Equipamentos [Compra]' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_equipamentos_compra_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_equipamentos_compra_ano = $check_despesa_equipamentos_compra_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_equipamentos_compra_ano = number_format(($total_despesa_equipamentos_compra_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_outros_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_tipo = 'Outros' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
    $check_despesa_outros_ano->execute(array('relatorio_inicio_ano' => $relatorio_inicio_ano, 'relatorio_fim' => $relatorio_fim));
    while($total_despesa_outros_ano = $check_despesa_outros_ano->fetch(PDO::FETCH_ASSOC)){
    $despesa_outros_ano = number_format(($total_despesa_outros_ano['sum(despesa_valor)']  ?? 0) * (1) ,2,",",".");
    }
    $check_despesa_total_ano = $conexao->prepare("SELECT sum(despesa_valor) FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= :relatorio_inicio_ano AND despesa_dia <= :relatorio_fim"); 
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

    $ocupacao_dia = number_format( floatval(( ($arrivals_dia_consultas + $row_reservas_dia - $row_cancelamentos_dia - $noshows_dia ) / $inventario_dia) * 100) ,2,",",".");
    $ocupacao_mes = number_format( floatval(( ($arrivals_mes_consultas + $row_reservas_mes - $row_cancelamentos_mes - $noshows_mes ) / $inventario_mes) * 100) ,2,",",".");
    $ocupacao_ano = number_format( floatval(( ($arrivals_ano_consultas + $row_reservas_ano - $row_cancelamentos_ano - $noshows_ano ) / $inventario_ano) * 100) ,2,",",".");

    if($relatorio_tipo == 'pdf'){
    //Corpo do PDF
    $gera_body = "
    <fieldset>
    <center><b>Receitas</b><br></center>
    <table width=100% border=2px>
    <tr><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Atendimentos</td><td align=center>$arrivals_dia_consultas</td><td align=center>$arrivals_mes_consultas</td><td align=center>$arrivals_ano_consultas</td></tr>
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
    </fieldset>";

    }else{ //Relatorio em Excel

        //Planilha Excel
$spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
$protection = $spreadsheet->getActiveSheet()->getProtection();
$protection->setPassword("$worksheet_password");
$protection->setSheet(true);
$protection->setSort(false);
$protection->setInsertRows(false);
$protection->setFormatCells(false);

$security = $spreadsheet->getSecurity();
$security->setLockWindows(true);
$security->setLockStructure(true);
$security->setWorkbookPassword("$worksheet_password");

//Cabeçario
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
$activeWorksheet->mergeCells('C3:I3');

//Receitas
$activeWorksheet->setCellValue('C5', 'Receitas');
$activeWorksheet->mergeCells('C5:F5');
$activeWorksheet->setCellValue('C6', 'Linha');
$activeWorksheet->setCellValue('D6', 'Dia');
$activeWorksheet->setCellValue('E6', 'Mês');
$activeWorksheet->setCellValue('F6', 'Ano');
$activeWorksheet->setCellValue('C7', 'Atendimentos');
$activeWorksheet->setCellValue('D7', $arrivals_dia_consultas);
$activeWorksheet->setCellValue('E7', $arrivals_mes_consultas);
$activeWorksheet->setCellValue('F7', $arrivals_ano_consultas);
$activeWorksheet->setCellValue('C8', 'Consultas');
$activeWorksheet->setCellValue('D8', $row_reservas_dia);
$activeWorksheet->setCellValue('E8', $row_reservas_mes);
$activeWorksheet->setCellValue('F8', $row_reservas_ano);
$activeWorksheet->setCellValue('C9', 'Finalizadas');
$activeWorksheet->setCellValue('D9', $row_reservas_dia);
$activeWorksheet->setCellValue('E9', $row_reservas_mes);
$activeWorksheet->setCellValue('F9', $row_reservas_ano);
$activeWorksheet->setCellValue('C9', 'Finalizadas');
$activeWorksheet->setCellValue('D9', $arrivals_dia);
$activeWorksheet->setCellValue('E9', $arrivals_mes);
$activeWorksheet->setCellValue('F9', $arrivals_ano);
$activeWorksheet->setCellValue('C10', 'Canceladas');
$activeWorksheet->setCellValue('D10', $row_cancelamentos_dia);
$activeWorksheet->setCellValue('E10', $row_cancelamentos_mes);
$activeWorksheet->setCellValue('F10', $row_cancelamentos_ano);
$activeWorksheet->setCellValue('C11', 'No-Shows');
$activeWorksheet->setCellValue('D11', $noshows_dia);
$activeWorksheet->setCellValue('E11', $noshows_mes);
$activeWorksheet->setCellValue('F11', $noshows_ano);
$activeWorksheet->setCellValue('C12', 'Performance');
$activeWorksheet->setCellValue('D12', $ocupacao_dia.'%');
$activeWorksheet->setCellValue('E12', $ocupacao_mes.'%');
$activeWorksheet->setCellValue('F12', $ocupacao_ano.'%');
$activeWorksheet->setCellValue('C13', 'Outros Pagamentos');
$activeWorksheet->setCellValue('D13', str_replace(['.', ','], ['', '.'], $receita_outros_dia));
$activeWorksheet->setCellValue('E13', str_replace(['.', ','], ['', '.'], $receita_outros_mes));
$activeWorksheet->setCellValue('F13', str_replace(['.', ','], ['', '.'], $receita_outros_ano));
$activeWorksheet->setCellValue('C14', 'Pagamento em Cartão');
$activeWorksheet->setCellValue('D14', str_replace(['.', ','], ['', '.'], $receita_cartao_dia));
$activeWorksheet->setCellValue('E14', str_replace(['.', ','], ['', '.'], $receita_cartao_mes));
$activeWorksheet->setCellValue('F14', str_replace(['.', ','], ['', '.'], $receita_cartao_ano));
$activeWorksheet->setCellValue('C15', 'Pagamento em Dinheiro');
$activeWorksheet->setCellValue('D15', str_replace(['.', ','], ['', '.'], $receita_dinheiro_dia));
$activeWorksheet->setCellValue('E15', str_replace(['.', ','], ['', '.'], $receita_dinheiro_mes));
$activeWorksheet->setCellValue('F15', str_replace(['.', ','], ['', '.'], $receita_dinheiro_ano));
$activeWorksheet->setCellValue('C16', 'Pagamento em Transferencias');
$activeWorksheet->setCellValue('D16', str_replace(['.', ','], ['', '.'], $receita_transferencia_dia));
$activeWorksheet->setCellValue('E16', str_replace(['.', ','], ['', '.'], $receita_transferencia_mes));
$activeWorksheet->setCellValue('F16', str_replace(['.', ','], ['', '.'], $receita_transferencia_ano));
$activeWorksheet->setCellValue('C17', 'Receita Total');
$activeWorksheet->setCellValue('D17', str_replace(['.', ','], ['', '.'], $receita_lancamentos_dia));
$activeWorksheet->setCellValue('E17', str_replace(['.', ','], ['', '.'], $receita_lancamentos_mes));
$activeWorksheet->setCellValue('F17', str_replace(['.', ','], ['', '.'], $receita_lancamentos_ano));

//Despesas
$activeWorksheet->setCellValue('C19', 'Despesas');
$activeWorksheet->mergeCells('C19:F19');
$activeWorksheet->setCellValue('C20', 'Linha');
$activeWorksheet->setCellValue('D20', 'Dia');
$activeWorksheet->setCellValue('E20', 'Mês');
$activeWorksheet->setCellValue('F20', 'Ano');
$activeWorksheet->setCellValue('C21', 'Aluguel');
$activeWorksheet->setCellValue('D21', str_replace(['.', ','], ['', '.'], $despesa_aluguel_dia));
$activeWorksheet->setCellValue('E21', str_replace(['.', ','], ['', '.'], $despesa_aluguel_mes));
$activeWorksheet->setCellValue('F21', str_replace(['.', ','], ['', '.'], $despesa_aluguel_ano));
$activeWorksheet->setCellValue('C22', 'Energia');
$activeWorksheet->setCellValue('D22', str_replace(['.', ','], ['', '.'], $despesa_luz_dia));
$activeWorksheet->setCellValue('E22', str_replace(['.', ','], ['', '.'], $despesa_luz_mes));
$activeWorksheet->setCellValue('F22', str_replace(['.', ','], ['', '.'], $despesa_luz_ano));
$activeWorksheet->setCellValue('C23', 'Internet');
$activeWorksheet->setCellValue('D23', str_replace(['.', ','], ['', '.'], $despesa_internet_dia));
$activeWorksheet->setCellValue('E23', str_replace(['.', ','], ['', '.'], $despesa_internet_mes));
$activeWorksheet->setCellValue('F23', str_replace(['.', ','], ['', '.'], $despesa_internet_ano));
$activeWorksheet->setCellValue('C24', 'Insumos');
$activeWorksheet->setCellValue('D24', str_replace(['.', ','], ['', '.'], $despesa_insumos_dia));
$activeWorksheet->setCellValue('E24', str_replace(['.', ','], ['', '.'], $despesa_insumos_mes));
$activeWorksheet->setCellValue('F24', str_replace(['.', ','], ['', '.'], $despesa_insumos_ano));
$activeWorksheet->setCellValue('C25', 'Mobiliario');
$activeWorksheet->setCellValue('D25', str_replace(['.', ','], ['', '.'], $despesa_mobiliario_dia));
$activeWorksheet->setCellValue('E25', str_replace(['.', ','], ['', '.'], $despesa_mobiliario_mes));
$activeWorksheet->setCellValue('F25', str_replace(['.', ','], ['', '.'], $despesa_mobiliario_ano));
$activeWorksheet->setCellValue('C26', 'Equipamentos [Aluguel]');
$activeWorksheet->setCellValue('D26', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_aluguel_dia));
$activeWorksheet->setCellValue('E26', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_aluguel_mes));
$activeWorksheet->setCellValue('F26', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_aluguel_ano));
$activeWorksheet->setCellValue('C27', 'Equipamentos [Compra]');
$activeWorksheet->setCellValue('D27', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_compra_dia));
$activeWorksheet->setCellValue('E27', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_compra_mes));
$activeWorksheet->setCellValue('F27', str_replace(['.', ','], ['', '.'], $despesa_equipamentos_compra_ano));
$activeWorksheet->setCellValue('C28', 'Outros');
$activeWorksheet->setCellValue('D28', str_replace(['.', ','], ['', '.'], $despesa_outros_dia));
$activeWorksheet->setCellValue('E28', str_replace(['.', ','], ['', '.'], $despesa_outros_mes));
$activeWorksheet->setCellValue('F28', str_replace(['.', ','], ['', '.'], $despesa_outros_ano));
$activeWorksheet->setCellValue('C29', 'Despesas Total');
$activeWorksheet->setCellValue('D29', str_replace(['.', ','], ['', '.'], $despesa_total_dia));
$activeWorksheet->setCellValue('E29', str_replace(['.', ','], ['', '.'], $despesa_total_mes));
$activeWorksheet->setCellValue('F29', str_replace(['.', ','], ['', '.'], $despesa_total_ano));

//Lucro
$activeWorksheet->setCellValue('C31', 'Lucro Bruto');
$activeWorksheet->setCellValue('D31', str_replace(['.', ','], ['', '.'], $lucro_liquido_dia));
$activeWorksheet->setCellValue('E31', str_replace(['.', ','], ['', '.'], $lucro_liquido_mes));
$activeWorksheet->setCellValue('F31', str_replace(['.', ','], ['', '.'], $lucro_liquido_ano));

//Assinatura
$activeWorksheet->setCellValue('C34', $gerador_nome.' | '.$data_gerador);
$activeWorksheet->mergeCells('C34:J34');
$activeWorksheet->setCellValue('C35', 'Relatório Gerado via Sistema');
$activeWorksheet->mergeCells('C35:J35');


$activeWorksheet->setShowGridlines(false);
$activeWorksheet->setShowRowColHeaders(false);
$activeWorksheet->getRowDimension(1)->setRowHeight(10);
$activeWorksheet->getColumnDimension('A')->setWidth(2);
$activeWorksheet->getColumnDimension('B')->setWidth(2);
$activeWorksheet->getColumnDimension('C')->setWidth(27);
$activeWorksheet->getColumnDimension('D')->setWidth(15);
$activeWorksheet->getColumnDimension('E')->setWidth(15);
$activeWorksheet->getColumnDimension('F')->setWidth(15);
$activeWorksheet->getColumnDimension('G')->setWidth(2);
$activeWorksheet->getColumnDimension('H')->setWidth(15);
$activeWorksheet->getColumnDimension('I')->setWidth(15);
$activeWorksheet->getColumnDimension('J')->setWidth(1);
$activeWorksheet->getColumnDimension('K')->setWidth(1);
$activeWorksheet->getColumnDimension('L')->setWidth(2);

// Inserir uma imagem
$imagePath = '../images/logo_03.jpg';
$objDrawing = new Drawing();
$objDrawing->setName('Caroline Ferraz');
$objDrawing->setDescription('Caroline Ferraz');
$objDrawing->setPath($imagePath);
$objDrawing->setCoordinates('H5'); // Posição onde a imagem será inserida
$objDrawing->setWidth(170); // Largura da imagem em pixels
$objDrawing->setHeight(170); // Altura da imagem em pixels
$objDrawing->setWorksheet($activeWorksheet);

$spreadsheet->getActiveSheet()->getStyle('C8:F8')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C10:F10')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C12:F12')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C14:F14')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C16:F16')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C20:F20')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C22:F22')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C24:F24')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C26:F26')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C28:F28')->applyFromArray($styleArray_separacao);

$spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
$spreadsheet->getActiveSheet()->getStyle('B33:K36')->applyFromArray($styleArray_cinza);

$spreadsheet->getActiveSheet()->getStyle('C5:F5')->applyFromArray($styleArray_laranja);
$spreadsheet->getActiveSheet()->getStyle('C19:F19')->applyFromArray($styleArray_laranja);

$spreadsheet->getActiveSheet()->getStyle('C6:F6')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C20:F20')->applyFromArray($styleArray_amarelo);

$spreadsheet->getActiveSheet()->getStyle('C34:J34')->applyFromArray($styleArray_branco5);
$spreadsheet->getActiveSheet()->getStyle('C35:J35')->applyFromArray($styleArray_preto);

$spreadsheet->getActiveSheet()->getStyle('C17:F17')->applyFromArray($styleArray_green);
$spreadsheet->getActiveSheet()->getStyle('C29:F29')->applyFromArray($styleArray_red);
$spreadsheet->getActiveSheet()->getStyle('C31:F31')->applyFromArray($styleArray_blue);

$activeWorksheet->getStyle('C5:F17')->applyFromArray($styleArray_inside_borders);
$activeWorksheet->getStyle('C19:F29')->applyFromArray($styleArray_inside_borders);
$activeWorksheet->getStyle('C31:F31')->applyFromArray($styleArray_inside_borders);

$activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C5:F17')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C19:F29')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C31:F31')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B33:K36')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C34:J35')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B2:K36')->applyFromArray($styleArray_outside_borders);

$styleArray = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$activeWorksheet->getStyle('D5:F29')->applyFromArray($styleArray);

$activeWorksheet->getStyle('D13:F17')->getNumberFormat()->setFormatCode('R$ #,##0.00');
$activeWorksheet->getStyle('D20:F29')->getNumberFormat()->setFormatCode('R$ #,##0.00');
$activeWorksheet->getStyle('D31:F31')->getNumberFormat()->setFormatCode('R$ #,##0.00');

$worksheet = $spreadsheet->getActiveSheet();
$worksheet->setTitle('Relatorio - '.$relatorio);
$worksheet->setSelectedCell('A1');

    }
}else if($relatorio == 'Estornos Dia' || $relatorio == 'Estornos Mes' || $relatorio == 'Estornos Ano'){
    $tipo = 'landscape';

$resultado_estorno = '';
if($relatorio == 'Estornos Dia'){
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Estornos Mes'){
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}
$estorno_total = $query_estorno->rowCount();
if($estorno_total > 0){
while($select_estorno = $query_estorno->fetch(PDO::FETCH_ASSOC)){
$nome = $select_estorno['doc_nome'];
$produto = $select_estorno['produto'];
$feitopor = $select_estorno['feitopor'];
$quando = $select_estorno['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));

    if($relatorio_tipo == 'pdf'){

$resultado_estorno = "$resultado_estorno<tr><td align=center>$nome</td><td>$produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
    
}else{

    $dados_relatorio[] = [
        'doc_nome' => $select_estorno['doc_nome'],
        'produto' => $select_estorno['produto'],
        'feitopor' => $select_estorno['feitopor'],
        'quando' => $select_estorno['quando']
    ];

    }
}

}else{
$resultado_estorno = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>';
$estorno_total = 0;
}


if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Estornos</b>: $estorno_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>Nome</b></td>
<td align=center width=55%><b>Descrição Produto</b></td>
<td align=center width=20%><b>Responsavel/<b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_estorno
</table>
</fieldset>
            ";

}else{ //Relatorio em Excel

    //Planilha Excel
$spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
$protection = $spreadsheet->getActiveSheet()->getProtection();
$protection->setPassword("$worksheet_password");
$protection->setSheet(true);
$protection->setSort(false);
$protection->setInsertRows(false);
$protection->setFormatCells(false);

$security = $spreadsheet->getSecurity();
$security->setLockWindows(true);
$security->setLockStructure(true);
$security->setWorkbookPassword("$worksheet_password");

//Cabeçario
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
$activeWorksheet->mergeCells('C3:I3');
$activeWorksheet->setCellValue('C5', $relatorio);
$activeWorksheet->mergeCells('C5:G5');
$activeWorksheet->mergeCells('C7:G7');
$activeWorksheet->setCellValue('C9', 'Qtd.');
$activeWorksheet->setCellValue('D9', 'Nome');
$activeWorksheet->setCellValue('E9', 'Descrição Produto');
$activeWorksheet->setCellValue('F9', 'Responsavel');
$activeWorksheet->setCellValue('G9', 'Data');

//Relatorio
$quantidade_dados = count($dados_relatorio);

$linha_excel = 9;
$qtd = 0;

foreach ($dados_relatorio as $select) {
    $nome = $select['doc_nome'];
    $produto = $select['produto'];
    $feitopor = $select['feitopor'];
    $quando = $select['quando'];

    $linha_excel++;
    $qtd++;

    if($linha_excel % 2 != 0){
    $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
    }

$activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
$activeWorksheet->setCellValue('D'.$linha_excel, $nome);
$activeWorksheet->setCellValue('E'.$linha_excel, $produto);
$activeWorksheet->setCellValue('F'.$linha_excel, $feitopor);
$activeWorksheet->setCellValue('G'.$linha_excel, date('d/m/Y', strtotime("$quando")));

}

//Manter no Minimo 3 linhas
if($linha_excel < 13){
$linhas = $linha_excel;
for($i = $linhas ; $i < 13 ; $i++){
$linha_excel++;
$activeWorksheet->setCellValue('C'.$linha_excel, '');
$activeWorksheet->setCellValue('D'.$linha_excel, '');
$activeWorksheet->setCellValue('E'.$linha_excel, '');
$activeWorksheet->setCellValue('F'.$linha_excel, '');
$activeWorksheet->setCellValue('G'.$linha_excel, '');
}
}

$activeWorksheet->setCellValue('C7', 'Quantidade: '.$quantidade_dados);


//Assinatura Digital
$activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
$activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
$activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
$activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));

//Definir Tamanhos
$activeWorksheet->setShowGridlines(false);
$activeWorksheet->setShowRowColHeaders(false);
$activeWorksheet->getRowDimension(1)->setRowHeight(10);
$activeWorksheet->getColumnDimension('A')->setWidth(2);
$activeWorksheet->getColumnDimension('B')->setWidth(2);
$activeWorksheet->getColumnDimension('D')->setWidth(25);
$activeWorksheet->getColumnDimension('E')->setWidth(50);
$activeWorksheet->getColumnDimension('F')->setWidth(25);
$activeWorksheet->getColumnDimension('G')->setWidth(15);
$activeWorksheet->getColumnDimension('H')->setWidth(2);
$activeWorksheet->getColumnDimension('I')->setWidth(24);
$activeWorksheet->getColumnDimension('J')->setWidth(2);

// Inserir uma imagem
$imagePath = '../images/logo_03.jpg';
$objDrawing = new Drawing();
$objDrawing->setName('Caroline Ferraz');
$objDrawing->setDescription('Caroline Ferraz');
$objDrawing->setPath($imagePath);
$objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
$objDrawing->setWidth(150); // Largura da imagem em pixels
$objDrawing->setHeight(150); // Altura da imagem em pixels
$objDrawing->setWorksheet($activeWorksheet);

//Colocar as bordas
$activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);

$activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);

$spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
$spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);

$spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);

$spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);

$spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
$spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);

$spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
$spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
$spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);

$spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);

$styleArray = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$activeWorksheet->getStyle('D10:F'.$linha_excel)->applyFromArray($styleArray);

$worksheet = $spreadsheet->getActiveSheet();
$worksheet->setTitle('Relatorio - '.$relatorio);
$worksheet->setSelectedCell('A1');

}

}else if($relatorio == 'Lançamentos Dia' || $relatorio == 'Lançamentos Mes' || $relatorio == 'Lançamentos Ano'){
    $tipo = 'landscape';

$resultado_lanc = '';
if($relatorio == 'Lançamentos Dia'){
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Lançamentos Mes'){
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Produto' ORDER BY quando DESC");
}
$lanc_total = $query_lanc->rowCount();
if($lanc_total > 0){
while($select_lanc = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$nome = $select_lanc['doc_nome'];
$produto = $select_lanc['produto'];
$feitopor = $select_lanc['feitopor'];
$quando = $select_lanc['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_lanc['valor'];
$valor = number_format($valor ,2,",",".");

if($relatorio_tipo == 'pdf'){

    $resultado_lanc = "$resultado_lanc<tr><td align=center>$nome</td><td>[ R$$valor ] $produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
        
    }else{
    
        $dados_relatorio[] = [
            'doc_nome' => $select_lanc['doc_nome'],
            'produto' => $select_lanc['produto'],
            'feitopor' => $select_lanc['feitopor'],
            'quando' => $select_lanc['quando'],
            'valor' => $select_lanc['valor']
        ];
    
        }

}
}else{
$resultado_lanc = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$lanc_total = 0;
}

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Lançamentos</b>: $lanc_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>Nome</b></td>
<td align=center width=55%><b>[ Valor ] Descrição Produto</b></td>
<td align=center width=20%><b>Responsavel</b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_lanc
</table>
</fieldset>
            ";

    }else{ //Relatorio em Excel

        //Planilha Excel
$spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
$protection = $spreadsheet->getActiveSheet()->getProtection();
$protection->setPassword("$worksheet_password");
$protection->setSheet(true);
$protection->setSort(false);
$protection->setInsertRows(false);
$protection->setFormatCells(false);

$security = $spreadsheet->getSecurity();
$security->setLockWindows(true);
$security->setLockStructure(true);
$security->setWorkbookPassword("$worksheet_password");

//Cabeçario
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
$activeWorksheet->mergeCells('C3:I3');
$activeWorksheet->setCellValue('C5', $relatorio);
$activeWorksheet->mergeCells('C5:G5');
$activeWorksheet->mergeCells('C7:G7');
$activeWorksheet->setCellValue('C9', 'Qtd.');
$activeWorksheet->setCellValue('D9', 'Nome');
$activeWorksheet->setCellValue('E9', '[Valor] Descrição Produto');
$activeWorksheet->setCellValue('F9', 'Responsavel');
$activeWorksheet->setCellValue('G9', 'Data');

//Relatorio
$quantidade_dados = count($dados_relatorio);

$linha_excel = 9;
$qtd = 0;
$valor_total = 0.00;

foreach ($dados_relatorio as $select) {
    $nome = $select['doc_nome'];
    $produto = $select['produto'];
    $feitopor = $select['feitopor'];
    $quando = $select['quando'];
    $valor = $select['valor'];

    $valor_total += floatval($valor);

    $linha_excel++;
    $qtd++;

    if($linha_excel % 2 != 0){
    $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
    }

$activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
$activeWorksheet->setCellValue('D'.$linha_excel, $nome);
$activeWorksheet->setCellValue('E'.$linha_excel, '[ R$'.number_format($valor ,2,",",".").' ]'.$produto);
$activeWorksheet->setCellValue('F'.$linha_excel, $feitopor);
$activeWorksheet->setCellValue('G'.$linha_excel, date('d/m/Y', strtotime("$quando")));

}

//Manter no Minimo 3 linhas
if($linha_excel < 13){
$linhas = $linha_excel;
for($i = $linhas ; $i < 13 ; $i++){
$linha_excel++;
$activeWorksheet->setCellValue('C'.$linha_excel, '');
$activeWorksheet->setCellValue('D'.$linha_excel, '');
$activeWorksheet->setCellValue('E'.$linha_excel, '');
$activeWorksheet->setCellValue('F'.$linha_excel, '');
$activeWorksheet->setCellValue('G'.$linha_excel, '');
}
}

$activeWorksheet->setCellValue('C7', 'Total: R$'.number_format($valor_total ,2,",","."));


//Assinatura Digital
$activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
$activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
$activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
$activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));

//Definir Tamanhos
$activeWorksheet->setShowGridlines(false);
$activeWorksheet->setShowRowColHeaders(false);
$activeWorksheet->getRowDimension(1)->setRowHeight(10);
$activeWorksheet->getColumnDimension('A')->setWidth(2);
$activeWorksheet->getColumnDimension('B')->setWidth(2);
$activeWorksheet->getColumnDimension('D')->setWidth(40);
$activeWorksheet->getColumnDimension('E')->setWidth(50);
$activeWorksheet->getColumnDimension('F')->setWidth(25);
$activeWorksheet->getColumnDimension('G')->setWidth(14);
$activeWorksheet->getColumnDimension('H')->setWidth(2);
$activeWorksheet->getColumnDimension('I')->setWidth(24);
$activeWorksheet->getColumnDimension('J')->setWidth(2);

// Inserir uma imagem
$imagePath = '../images/logo_03.jpg';
$objDrawing = new Drawing();
$objDrawing->setName('Caroline Ferraz');
$objDrawing->setDescription('Caroline Ferraz');
$objDrawing->setPath($imagePath);
$objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
$objDrawing->setWidth(150); // Largura da imagem em pixels
$objDrawing->setHeight(150); // Altura da imagem em pixels
$objDrawing->setWorksheet($activeWorksheet);

//Colocar as bordas
$activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);

$activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);

$spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
$spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);

$spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);

$spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);

$spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
$spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);

$spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
$spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
$spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);

$spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);

$styleArray = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$activeWorksheet->getStyle('G10:G'.$linha_excel)->applyFromArray($styleArray);

$worksheet = $spreadsheet->getActiveSheet();
$worksheet->setTitle('Relatorio - '.$relatorio);
$worksheet->setSelectedCell('A1');

    }

}else if($relatorio == 'Pagamentos Dia' || $relatorio == 'Pagamentos Mes' || $relatorio == 'Pagamentos Ano'){
    $tipo = 'landscape';

$resultado_pgto = '';
if($relatorio == 'Pagamentos Dia'){
$query_pgto = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}else if($relatorio == 'Pagamentos Mes'){
$query_pgto = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}else{
$query_pgto = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim_outros}' AND tipo = 'Pagamento' ORDER BY quando DESC");
}
$pgto_total = $query_pgto->rowCount();
if($pgto_total > 0){
while($select_pgto = $query_pgto->fetch(PDO::FETCH_ASSOC)){
$nome = $select_pgto['doc_nome'];
$produto = $select_pgto['produto'];
$feitopor = $select_pgto['feitopor'];
$quando = $select_pgto['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_pgto['valor'];
$valor = number_format(($valor * (-1)) ,2,",",".");

if($relatorio_tipo == 'pdf'){

    $resultado_pgto = "$resultado_pgto<tr><td align=center>$nome</td><td>[ R$$valor ] $produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
        
    }else{
    
        $dados_relatorio[] = [
            'doc_nome' => $select_pgto['doc_nome'],
            'produto' => $select_pgto['produto'],
            'feitopor' => $select_pgto['feitopor'],
            'quando' => $select_pgto['quando'],
            'valor' => $select_pgto['valor']
        ];
    
        }

}
}else{
$resultado_pgto = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$pgto_total = 0;
    }

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Pagamentos</b>: $pgto_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=15%><b>Nome</b></td>
<td align=center width=55%><b>Descrição do Pagamento</b></td>
<td align=center width=20%><b>Responsavel</b></td>
<td align=center width=20%><b>Data</b></td>
</tr>
$resultado_pgto
</table>
</fieldset>
            ";

        }else{ //Relatorio em Excel

            //Planilha Excel
    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    $protection = $spreadsheet->getActiveSheet()->getProtection();
    $protection->setPassword("$worksheet_password");
    $protection->setSheet(true);
    $protection->setSort(false);
    $protection->setInsertRows(false);
    $protection->setFormatCells(false);
    
    $security = $spreadsheet->getSecurity();
    $security->setLockWindows(true);
    $security->setLockStructure(true);
    $security->setWorkbookPassword("$worksheet_password");
    
    //Cabeçario
    $activeWorksheet = $spreadsheet->getActiveSheet();
    $activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
    $activeWorksheet->mergeCells('C3:I3');
    $activeWorksheet->setCellValue('C5', $relatorio);
    $activeWorksheet->mergeCells('C5:G5');
    $activeWorksheet->mergeCells('C7:G7');
    $activeWorksheet->setCellValue('C9', 'Qtd.');
    $activeWorksheet->setCellValue('D9', 'Nome');
    $activeWorksheet->setCellValue('E9', '[Valor] Tipo Pagamento');
    $activeWorksheet->setCellValue('F9', 'Responsavel');
    $activeWorksheet->setCellValue('G9', 'Data');
    
    //Relatorio
    $quantidade_dados = count($dados_relatorio);
    
    $linha_excel = 9;
    $qtd = 0;
    $valor_total = 0.00;
    
    foreach ($dados_relatorio as $select) {
        $nome = $select['doc_nome'];
        $produto = $select['produto'];
        $feitopor = $select['feitopor'];
        $quando = $select['quando'];
        $valor = (-1) * $select['valor'];
    
        $valor_total += floatval($valor);
    
        $linha_excel++;
        $qtd++;
    
        if($linha_excel % 2 != 0){
        $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
        }
    
    $activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
    $activeWorksheet->setCellValue('D'.$linha_excel, $nome);
    $activeWorksheet->setCellValue('E'.$linha_excel, '[ R$'.number_format($valor ,2,",",".").' ]'.$produto);
    $activeWorksheet->setCellValue('F'.$linha_excel, $feitopor);
    $activeWorksheet->setCellValue('G'.$linha_excel, date('d/m/Y', strtotime("$quando")));
    
    }
    
    //Manter no Minimo 3 linhas
    if($linha_excel < 13){
    $linhas = $linha_excel;
    for($i = $linhas ; $i < 13 ; $i++){
    $linha_excel++;
    $activeWorksheet->setCellValue('C'.$linha_excel, '');
    $activeWorksheet->setCellValue('D'.$linha_excel, '');
    $activeWorksheet->setCellValue('E'.$linha_excel, '');
    $activeWorksheet->setCellValue('F'.$linha_excel, '');
    $activeWorksheet->setCellValue('G'.$linha_excel, '');
    }
    }
    
    $activeWorksheet->setCellValue('C7', 'Total: R$'.number_format($valor_total ,2,",","."));
    
    
    //Assinatura Digital
    $activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
    $activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
    $activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
    $activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));
    
    //Definir Tamanhos
    $activeWorksheet->setShowGridlines(false);
    $activeWorksheet->setShowRowColHeaders(false);
    $activeWorksheet->getRowDimension(1)->setRowHeight(10);
    $activeWorksheet->getColumnDimension('A')->setWidth(2);
    $activeWorksheet->getColumnDimension('B')->setWidth(2);
    $activeWorksheet->getColumnDimension('D')->setWidth(40);
    $activeWorksheet->getColumnDimension('E')->setWidth(50);
    $activeWorksheet->getColumnDimension('F')->setWidth(25);
    $activeWorksheet->getColumnDimension('G')->setWidth(14);
    $activeWorksheet->getColumnDimension('H')->setWidth(2);
    $activeWorksheet->getColumnDimension('I')->setWidth(24);
    $activeWorksheet->getColumnDimension('J')->setWidth(2);
    
    // Inserir uma imagem
    $imagePath = '../images/logo_03.jpg';
    $objDrawing = new Drawing();
    $objDrawing->setName('Caroline Ferraz');
    $objDrawing->setDescription('Caroline Ferraz');
    $objDrawing->setPath($imagePath);
    $objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
    $objDrawing->setWidth(150); // Largura da imagem em pixels
    $objDrawing->setHeight(150); // Altura da imagem em pixels
    $objDrawing->setWorksheet($activeWorksheet);
    
    //Colocar as bordas
    $activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);
    
    $activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);
    
    $spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);
    
    $spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);
    
    $spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
    $spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);
    
    $spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);
    
    $styleArray = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    $activeWorksheet->getStyle('G10:G'.$linha_excel)->applyFromArray($styleArray);
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Relatorio - '.$relatorio);
    $worksheet->setSelectedCell('A1');
    
    }

}else if($relatorio == 'Despesas Dia' || $relatorio == 'Despesas Mes' || $relatorio == 'Despesas Ano'){
    $tipo = 'landscape';

$resultado_despesa = '';
if($relatorio == 'Despesas Dia'){
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= '{$relatorio_inicio}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}else if($relatorio == 'Despesas Mes'){
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= '{$relatorio_inicio_mes}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}else{
$query_despesas = $conexao->query("SELECT * FROM despesas WHERE token_emp = '{$_SESSION['token_emp']}' AND despesa_dia >= '{$relatorio_inicio_ano}' AND despesa_dia <= '{$relatorio_fim_outros}' ORDER BY despesa_tipo, despesa_dia DESC");
}
$despesas_total = $query_despesas->rowCount();
if($despesas_total > 0){
while($select_despesas = $query_despesas->fetch(PDO::FETCH_ASSOC)){
$despesa_tipo = $select_despesas['despesa_tipo'];
$despesa_descricao = $select_despesas['despesa_descricao'];
$despesa_quem = $select_despesas['despesa_quem'];
$despesa_dia = $select_despesas['despesa_dia'];
$despesa_dia = date('d/m/Y', strtotime("$despesa_dia"));
$despesa_valor = $select_despesas['despesa_valor'];
$despesa_valor = number_format($despesa_valor ,2,",",".");

if($relatorio_tipo == 'pdf'){

$resultado_despesa = "$resultado_despesa<tr><td align=center>$despesa_tipo</td><td>[ R$$despesa_valor ] $despesa_descricao</td><td>$despesa_quem</td><td align=center>$despesa_dia</td></tr>";
        
}else{
    
    $dados_relatorio[] = [
        'despesa_tipo' => $select_despesas['despesa_tipo'],
        'feitopor' => $select_despesas['despesa_quem'],
        'produto' => $select_despesas['despesa_descricao'],
        'quando' => $select_despesas['despesa_dia'],
        'valor' => $select_despesas['despesa_valor']
    ];
    
}

}
}else{
$resultado_despesa = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$despesa_total = 0;
}

if($relatorio_tipo == 'pdf'){
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

}else{ //Relatorio em Excel

            //Planilha Excel
    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    $protection = $spreadsheet->getActiveSheet()->getProtection();
    $protection->setPassword("$worksheet_password");
    $protection->setSheet(true);
    $protection->setSort(false);
    $protection->setInsertRows(false);
    $protection->setFormatCells(false);
    
    $security = $spreadsheet->getSecurity();
    $security->setLockWindows(true);
    $security->setLockStructure(true);
    $security->setWorkbookPassword("$worksheet_password");
    
    //Cabeçario
    $activeWorksheet = $spreadsheet->getActiveSheet();
    $activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
    $activeWorksheet->mergeCells('C3:I3');
    $activeWorksheet->setCellValue('C5', $relatorio);
    $activeWorksheet->mergeCells('C5:G5');
    $activeWorksheet->mergeCells('C7:G7');
    $activeWorksheet->setCellValue('C9', 'Qtd.');
    $activeWorksheet->setCellValue('D9', 'Valor');
    $activeWorksheet->setCellValue('E9', '[Tipo] Descrição');
    $activeWorksheet->setCellValue('F9', 'Responsavel');
    $activeWorksheet->setCellValue('G9', 'Data');
    
    //Relatorio
    $quantidade_dados = count($dados_relatorio);
    
    $linha_excel = 9;
    $qtd = 0;
    $valor_total = 0.00;
    
    foreach ($dados_relatorio as $select) {
        $despesa_tipo = $select['despesa_tipo'];
        $produto = $select['produto'];
        $feitopor = $select['feitopor'];
        $quando = $select['quando'];
        $valor = $select['valor'];
    
        $valor_total += floatval($valor);
    
        $linha_excel++;
        $qtd++;
    
        if($linha_excel % 2 != 0){
        $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
        }
    
    $activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
    $activeWorksheet->setCellValue('D'.$linha_excel, $valor);
    $activeWorksheet->setCellValue('E'.$linha_excel, '['.$despesa_tipo.']'.' - '.$produto);
    $activeWorksheet->setCellValue('F'.$linha_excel, $feitopor);
    $activeWorksheet->setCellValue('G'.$linha_excel, date('d/m/Y', strtotime("$quando")));
    
    }
    
    //Manter no Minimo 3 linhas
    if($linha_excel < 13){
    $linhas = $linha_excel;
    for($i = $linhas ; $i < 13 ; $i++){
    $linha_excel++;
    $activeWorksheet->setCellValue('C'.$linha_excel, '');
    $activeWorksheet->setCellValue('D'.$linha_excel, '');
    $activeWorksheet->setCellValue('E'.$linha_excel, '');
    $activeWorksheet->setCellValue('F'.$linha_excel, '');
    $activeWorksheet->setCellValue('G'.$linha_excel, '');
    }
    }
    
    $activeWorksheet->setCellValue('C7', 'Total: R$'.number_format($valor_total ,2,",","."));
    $activeWorksheet->getStyle('D10:D'.$linha_excel)->getNumberFormat()->setFormatCode('R$ #,##0.00');
    
    
    //Assinatura Digital
    $activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
    $activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
    $activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
    $activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));
    
    //Definir Tamanhos
    $activeWorksheet->setShowGridlines(false);
    $activeWorksheet->setShowRowColHeaders(false);
    $activeWorksheet->getRowDimension(1)->setRowHeight(10);
    $activeWorksheet->getColumnDimension('A')->setWidth(2);
    $activeWorksheet->getColumnDimension('B')->setWidth(2);
    $activeWorksheet->getColumnDimension('D')->setWidth(20);
    $activeWorksheet->getColumnDimension('E')->setWidth(50);
    $activeWorksheet->getColumnDimension('F')->setWidth(25);
    $activeWorksheet->getColumnDimension('G')->setWidth(14);
    $activeWorksheet->getColumnDimension('H')->setWidth(2);
    $activeWorksheet->getColumnDimension('I')->setWidth(24);
    $activeWorksheet->getColumnDimension('J')->setWidth(2);
    
    // Inserir uma imagem
    $imagePath = '../images/logo_03.jpg';
    $objDrawing = new Drawing();
    $objDrawing->setName('Caroline Ferraz');
    $objDrawing->setDescription('Caroline Ferraz');
    $objDrawing->setPath($imagePath);
    $objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
    $objDrawing->setWidth(150); // Largura da imagem em pixels
    $objDrawing->setHeight(150); // Altura da imagem em pixels
    $objDrawing->setWorksheet($activeWorksheet);
    
    //Colocar as bordas
    $activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);
    
    $activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);
    
    $spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);
    
    $spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);
    
    $spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
    $spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);
    
    $spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);
    
    $styleArray = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    $activeWorksheet->getStyle('G10:G'.$linha_excel)->applyFromArray($styleArray);
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Relatorio - '.$relatorio);
    $worksheet->setSelectedCell('A1');
    
    }

}else if($relatorio == 'Consultas Dia' || $relatorio == 'Consultas Mes' || $relatorio == 'Consultas Ano'){
    $tipo = 'landscape';

$resultado_reservas = '';
if($relatorio == 'Consultas Dia'){
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}else if($relatorio == 'Consultas Mes'){
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia, atendimento_hora");
}

$reservas_total = $query_reservas->rowCount();
if($reservas_total > 0){
while($select_reservas = $query_reservas->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_reservas['doc_nome'];
$status_consulta = $select_reservas['status_consulta'];
$atendimento_dia = $select_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));

if($relatorio_tipo == 'pdf'){

    $resultado_reservas = "$resultado_reservas<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
            
}else{
        
    $dados_relatorio[] = [
        'hospede' => $select_reservas['doc_nome'],
        'status_consulta' => $select_reservas['status_consulta'],
        'atendimento_dia' => $select_reservas['atendimento_dia'],
        'atendimento_hora' => $select_reservas['atendimento_hora']
    ];
        
}

}
}else{
$resultado_reservas = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$reservas_total = 0;
    }

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Consultas</b>: $reservas_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Status</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_reservas
</table>
</fieldset>
            ";

}else{ //Relatorio em Excel

            //Planilha Excel
    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    $protection = $spreadsheet->getActiveSheet()->getProtection();
    $protection->setPassword("$worksheet_password");
    $protection->setSheet(true);
    $protection->setSort(false);
    $protection->setInsertRows(false);
    $protection->setFormatCells(false);
    
    $security = $spreadsheet->getSecurity();
    $security->setLockWindows(true);
    $security->setLockStructure(true);
    $security->setWorkbookPassword("$worksheet_password");
    
    //Cabeçario
    $activeWorksheet = $spreadsheet->getActiveSheet();
    $activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
    $activeWorksheet->mergeCells('C3:I3');
    $activeWorksheet->setCellValue('C5', $relatorio);
    $activeWorksheet->mergeCells('C5:G5');
    $activeWorksheet->mergeCells('C7:G7');
    $activeWorksheet->setCellValue('C9', 'Qtd.');
    $activeWorksheet->setCellValue('D9', 'Status');
    $activeWorksheet->setCellValue('E9', 'Nome');
    $activeWorksheet->setCellValue('F9', 'Dia Atendimento');
    $activeWorksheet->setCellValue('G9', 'Hora Atendimento');
    
    //Relatorio
    $quantidade_dados = count($dados_relatorio);
    
    $linha_excel = 9;
    $qtd = 0;
    
    foreach ($dados_relatorio as $select) {
        $hospede = $select['hospede'];
        $status_consulta = $select['status_consulta'];
        $atendimento_dia = $select['atendimento_dia'];
        $atendimento_hora = $select['atendimento_hora'];
    
        $linha_excel++;
        $qtd++;
    
        if($linha_excel % 2 != 0){
        $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
        }
    
    $activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
    $activeWorksheet->setCellValue('D'.$linha_excel, $status_consulta);
    $activeWorksheet->setCellValue('E'.$linha_excel, $hospede);
    $activeWorksheet->setCellValue('F'.$linha_excel, date('d/m/Y', strtotime("$atendimento_dia")));
    $activeWorksheet->setCellValue('G'.$linha_excel, date('H:i\h', strtotime("$atendimento_hora")));
    
    }
    
    //Manter no Minimo 3 linhas
    if($linha_excel < 13){
    $linhas = $linha_excel;
    for($i = $linhas ; $i < 13 ; $i++){
    $linha_excel++;
    $activeWorksheet->setCellValue('C'.$linha_excel, '');
    $activeWorksheet->setCellValue('D'.$linha_excel, '');
    $activeWorksheet->setCellValue('E'.$linha_excel, '');
    $activeWorksheet->setCellValue('F'.$linha_excel, '');
    $activeWorksheet->setCellValue('G'.$linha_excel, '');
    }
    }
    
    $activeWorksheet->setCellValue('C7', 'Consultas: '.$quantidade_dados);
    $activeWorksheet->getStyle('D10:D'.$linha_excel)->getNumberFormat()->setFormatCode('R$ #,##0.00');
    
    
    //Assinatura Digital
    $activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
    $activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
    $activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
    $activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));
    
    //Definir Tamanhos
    $activeWorksheet->setShowGridlines(false);
    $activeWorksheet->setShowRowColHeaders(false);
    $activeWorksheet->getRowDimension(1)->setRowHeight(10);
    $activeWorksheet->getColumnDimension('A')->setWidth(2);
    $activeWorksheet->getColumnDimension('B')->setWidth(2);
    $activeWorksheet->getColumnDimension('D')->setWidth(30);
    $activeWorksheet->getColumnDimension('E')->setWidth(50);
    $activeWorksheet->getColumnDimension('F')->setWidth(16);
    $activeWorksheet->getColumnDimension('G')->setWidth(16);
    $activeWorksheet->getColumnDimension('H')->setWidth(2);
    $activeWorksheet->getColumnDimension('I')->setWidth(24);
    $activeWorksheet->getColumnDimension('J')->setWidth(2);
    
    // Inserir uma imagem
    $imagePath = '../images/logo_03.jpg';
    $objDrawing = new Drawing();
    $objDrawing->setName('Caroline Ferraz');
    $objDrawing->setDescription('Caroline Ferraz');
    $objDrawing->setPath($imagePath);
    $objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
    $objDrawing->setWidth(150); // Largura da imagem em pixels
    $objDrawing->setHeight(150); // Altura da imagem em pixels
    $objDrawing->setWorksheet($activeWorksheet);
    
    //Colocar as bordas
    $activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);
    
    $activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);
    
    $spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);
    
    $spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);
    
    $spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
    $spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);
    
    $spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);
    
    $styleArray = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    $activeWorksheet->getStyle('F10:G'.$linha_excel)->applyFromArray($styleArray);
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Relatorio - '.$relatorio);
    $worksheet->setSelectedCell('A1');
    
}

}else if($relatorio == 'Cancelamentos Dia' || $relatorio == 'Cancelamentos Mes' || $relatorio == 'Cancelamentos Ano'){
    $tipo = 'landscape';

$resultado_canc_reservas = '';
if($relatorio == 'Cancelamentos Dia'){
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' AND status_consulta = 'Cancelada' ORDER BY atendimento_hora");
}else if($relatorio == 'Cancelamentos Mes'){
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'Cancelada' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'Cancelada' ORDER BY atendimento_dia, atendimento_hora");
}

$canc_reservas_total = $query_canc_reservas->rowCount();
if($canc_reservas_total > 0){
while($select_canc_reservas = $query_canc_reservas->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_canc_reservas['doc_nome'];
$status_consulta = $select_canc_reservas['status_consulta'];
$atendimento_dia = $select_canc_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_canc_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));

if($relatorio_tipo == 'pdf'){

    $resultado_canc_reservas = "$resultado_canc_reservas<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
            
}else{
        
    $dados_relatorio[] = [
        'hospede' => $select_canc_reservas['doc_nome'],
        'status_consulta' => $select_canc_reservas['status_consulta'],
        'atendimento_dia' => $select_canc_reservas['atendimento_dia'],
        'atendimento_hora' => $select_canc_reservas['atendimento_hora']
    ];
        
}

}
}else{
$resultado_canc_reservas = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$canc_reservas_total = 0;
    }

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Cancelamentos</b>: $canc_reservas_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Status</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_canc_reservas
</table>
</fieldset>
            ";

}else{ //Relatorio em Excel

            //Planilha Excel
    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    $protection = $spreadsheet->getActiveSheet()->getProtection();
    $protection->setPassword("$worksheet_password");
    $protection->setSheet(true);
    $protection->setSort(false);
    $protection->setInsertRows(false);
    $protection->setFormatCells(false);
    
    $security = $spreadsheet->getSecurity();
    $security->setLockWindows(true);
    $security->setLockStructure(true);
    $security->setWorkbookPassword("$worksheet_password");
    
    //Cabeçario
    $activeWorksheet = $spreadsheet->getActiveSheet();
    $activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
    $activeWorksheet->mergeCells('C3:I3');
    $activeWorksheet->setCellValue('C5', $relatorio);
    $activeWorksheet->mergeCells('C5:G5');
    $activeWorksheet->mergeCells('C7:G7');
    $activeWorksheet->setCellValue('C9', 'Qtd.');
    $activeWorksheet->setCellValue('D9', 'Status');
    $activeWorksheet->setCellValue('E9', 'Nome');
    $activeWorksheet->setCellValue('F9', 'Dia Atendimento');
    $activeWorksheet->setCellValue('G9', 'Hora Atendimento');
    
    //Relatorio
    $quantidade_dados = count($dados_relatorio);
    
    $linha_excel = 9;
    $qtd = 0;
    
    foreach ($dados_relatorio as $select) {
        $hospede = $select['hospede'];
        $status_consulta = $select['status_consulta'];
        $atendimento_dia = $select['atendimento_dia'];
        $atendimento_hora = $select['atendimento_hora'];
    
        $linha_excel++;
        $qtd++;
    
        if($linha_excel % 2 != 0){
        $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
        }
    
    $activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
    $activeWorksheet->setCellValue('D'.$linha_excel, $status_consulta);
    $activeWorksheet->setCellValue('E'.$linha_excel, $hospede);
    $activeWorksheet->setCellValue('F'.$linha_excel, date('d/m/Y', strtotime("$atendimento_dia")));
    $activeWorksheet->setCellValue('G'.$linha_excel, date('H:i\h', strtotime("$atendimento_hora")));
    
    }
    
    //Manter no Minimo 3 linhas
    if($linha_excel < 13){
    $linhas = $linha_excel;
    for($i = $linhas ; $i < 13 ; $i++){
    $linha_excel++;
    $activeWorksheet->setCellValue('C'.$linha_excel, '');
    $activeWorksheet->setCellValue('D'.$linha_excel, '');
    $activeWorksheet->setCellValue('E'.$linha_excel, '');
    $activeWorksheet->setCellValue('F'.$linha_excel, '');
    $activeWorksheet->setCellValue('G'.$linha_excel, '');
    }
    }
    
    $activeWorksheet->setCellValue('C7', 'Consultas: '.$quantidade_dados);
    $activeWorksheet->getStyle('D10:D'.$linha_excel)->getNumberFormat()->setFormatCode('R$ #,##0.00');
    
    
    //Assinatura Digital
    $activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
    $activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
    $activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
    $activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));
    
    //Definir Tamanhos
    $activeWorksheet->setShowGridlines(false);
    $activeWorksheet->setShowRowColHeaders(false);
    $activeWorksheet->getRowDimension(1)->setRowHeight(10);
    $activeWorksheet->getColumnDimension('A')->setWidth(2);
    $activeWorksheet->getColumnDimension('B')->setWidth(2);
    $activeWorksheet->getColumnDimension('D')->setWidth(30);
    $activeWorksheet->getColumnDimension('E')->setWidth(50);
    $activeWorksheet->getColumnDimension('F')->setWidth(16);
    $activeWorksheet->getColumnDimension('G')->setWidth(16);
    $activeWorksheet->getColumnDimension('H')->setWidth(2);
    $activeWorksheet->getColumnDimension('I')->setWidth(24);
    $activeWorksheet->getColumnDimension('J')->setWidth(2);
    
    // Inserir uma imagem
    $imagePath = '../images/logo_03.jpg';
    $objDrawing = new Drawing();
    $objDrawing->setName('Caroline Ferraz');
    $objDrawing->setDescription('Caroline Ferraz');
    $objDrawing->setPath($imagePath);
    $objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
    $objDrawing->setWidth(150); // Largura da imagem em pixels
    $objDrawing->setHeight(150); // Altura da imagem em pixels
    $objDrawing->setWorksheet($activeWorksheet);
    
    //Colocar as bordas
    $activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);
    
    $activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);
    
    $spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);
    
    $spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);
    
    $spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
    $spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);
    
    $spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);
    
    $styleArray = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    $activeWorksheet->getStyle('F10:G'.$linha_excel)->applyFromArray($styleArray);
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Relatorio - '.$relatorio);
    $worksheet->setSelectedCell('A1');
    
}

}else if($relatorio == 'No-Shows Dia' || $relatorio == 'No-Shows Mes' || $relatorio == 'No-Shows Ano'){
    $tipo = 'landscape';

$resultado_noshows = '';
if($relatorio == 'No-Shows Dia'){
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_hora");
}else if($relatorio == 'No-Shows Mes'){
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_dia, atendimento_hora");
}else{
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_dia, atendimento_hora");
}
$noshows_total = $query_noshows->rowCount();
if($noshows_total > 0){
while($select_noshows = $query_noshows->fetch(PDO::FETCH_ASSOC)){
$hospede = $select_noshows['doc_nome'];
$status_consulta = $select_noshows['status_consulta'];
$atendimento_dia = $select_noshows['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$select_noshows->atendimento_dia"));
$atendimento_hora = $select_noshows['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$select_noshows->atendimento_hora"));

if($relatorio_tipo == 'pdf'){

    $resultado_noshows = "$resultado_noshows<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
            
}else{
        
    $dados_relatorio[] = [
        'hospede' => $select_noshows['doc_nome'],
        'status_consulta' => $select_noshows['status_consulta'],
        'atendimento_dia' => $select_noshows['atendimento_dia'],
        'atendimento_hora' => $select_noshows['atendimento_hora']
    ];
        
}

}
}else{
$resultado_noshows = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$canc_noshows = 0;
    }

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de No-Shows</b>: $noshows_total<br>
<table width=100% border=1px>
<tr>
<td align=center width=30%><b>Status</b></td>
<td align=center width=55%><b>Nome</b></td>
<td align=center width=15%><b>Dia Atendimento</b></td>
<td align=center width=15%><b>Hora Atendimento</b></td>
</tr>
$resultado_noshows
</table>
</fieldset>
            ";

}else{ //Relatorio em Excel

            //Planilha Excel
    $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    $protection = $spreadsheet->getActiveSheet()->getProtection();
    $protection->setPassword("$worksheet_password");
    $protection->setSheet(true);
    $protection->setSort(false);
    $protection->setInsertRows(false);
    $protection->setFormatCells(false);
    
    $security = $spreadsheet->getSecurity();
    $security->setLockWindows(true);
    $security->setLockStructure(true);
    $security->setWorkbookPassword("$worksheet_password");
    
    //Cabeçario
    $activeWorksheet = $spreadsheet->getActiveSheet();
    $activeWorksheet->setCellValue('C3', 'Relatorio Gerencial - '.$relatorio);
    $activeWorksheet->mergeCells('C3:I3');
    $activeWorksheet->setCellValue('C5', $relatorio);
    $activeWorksheet->mergeCells('C5:G5');
    $activeWorksheet->mergeCells('C7:G7');
    $activeWorksheet->setCellValue('C9', 'Qtd.');
    $activeWorksheet->setCellValue('D9', 'Status');
    $activeWorksheet->setCellValue('E9', 'Nome');
    $activeWorksheet->setCellValue('F9', 'Dia Atendimento');
    $activeWorksheet->setCellValue('G9', 'Hora Atendimento');
    
    //Relatorio
    $quantidade_dados = count($dados_relatorio);
    
    $linha_excel = 9;
    $qtd = 0;
    
    foreach ($dados_relatorio as $select) {
        $hospede = $select['hospede'];
        $status_consulta = $select['status_consulta'];
        $atendimento_dia = $select['atendimento_dia'];
        $atendimento_hora = $select['atendimento_hora'];
    
        $linha_excel++;
        $qtd++;
    
        if($linha_excel % 2 != 0){
        $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
        }
    
    $activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
    $activeWorksheet->setCellValue('D'.$linha_excel, $status_consulta);
    $activeWorksheet->setCellValue('E'.$linha_excel, $hospede);
    $activeWorksheet->setCellValue('F'.$linha_excel, date('d/m/Y', strtotime("$atendimento_dia")));
    $activeWorksheet->setCellValue('G'.$linha_excel, date('H:i\h', strtotime("$atendimento_hora")));
    
    }
    
    //Manter no Minimo 3 linhas
    if($linha_excel < 13){
    $linhas = $linha_excel;
    for($i = $linhas ; $i < 13 ; $i++){
    $linha_excel++;
    $activeWorksheet->setCellValue('C'.$linha_excel, '');
    $activeWorksheet->setCellValue('D'.$linha_excel, '');
    $activeWorksheet->setCellValue('E'.$linha_excel, '');
    $activeWorksheet->setCellValue('F'.$linha_excel, '');
    $activeWorksheet->setCellValue('G'.$linha_excel, '');
    }
    }
    
    $activeWorksheet->setCellValue('C7', 'Consultas: '.$quantidade_dados);
    $activeWorksheet->getStyle('D10:D'.$linha_excel)->getNumberFormat()->setFormatCode('R$ #,##0.00');
    
    
    //Assinatura Digital
    $activeWorksheet->setCellValue('D'.($linha_excel + 4), $gerador_nome.' | '.$data_gerador);
    $activeWorksheet->mergeCells('D'.($linha_excel + 4).':F'.($linha_excel + 4));
    $activeWorksheet->setCellValue('D'.($linha_excel + 5), 'Relatório Gerado via Sistema');
    $activeWorksheet->mergeCells('D'.($linha_excel + 5).':F'.($linha_excel + 5));
    
    //Definir Tamanhos
    $activeWorksheet->setShowGridlines(false);
    $activeWorksheet->setShowRowColHeaders(false);
    $activeWorksheet->getRowDimension(1)->setRowHeight(10);
    $activeWorksheet->getColumnDimension('A')->setWidth(2);
    $activeWorksheet->getColumnDimension('B')->setWidth(2);
    $activeWorksheet->getColumnDimension('D')->setWidth(30);
    $activeWorksheet->getColumnDimension('E')->setWidth(50);
    $activeWorksheet->getColumnDimension('F')->setWidth(16);
    $activeWorksheet->getColumnDimension('G')->setWidth(16);
    $activeWorksheet->getColumnDimension('H')->setWidth(2);
    $activeWorksheet->getColumnDimension('I')->setWidth(24);
    $activeWorksheet->getColumnDimension('J')->setWidth(2);
    
    // Inserir uma imagem
    $imagePath = '../images/logo_03.jpg';
    $objDrawing = new Drawing();
    $objDrawing->setName('Caroline Ferraz');
    $objDrawing->setDescription('Caroline Ferraz');
    $objDrawing->setPath($imagePath);
    $objDrawing->setCoordinates('I5'); // Posição onde a imagem será inserida
    $objDrawing->setWidth(150); // Largura da imagem em pixels
    $objDrawing->setHeight(150); // Altura da imagem em pixels
    $objDrawing->setWorksheet($activeWorksheet);
    
    //Colocar as bordas
    $activeWorksheet->getStyle('C9:G'.$linha_excel)->applyFromArray($styleArray_inside_borders);
    
    $activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('C5:G'.$linha_excel)->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 5))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_outside_borders);
    $activeWorksheet->getStyle('B2:J'.($linha_excel + 1))->applyFromArray($styleArray_outside_borders);
    
    $spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linha_excel + 3).':J'.($linha_excel + 6))->applyFromArray($styleArray_cinza);
    
    $spreadsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray_laranja);
    
    $spreadsheet->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleArray_amarelo);
    $spreadsheet->getActiveSheet()->getStyle('C10:C'.$linha_excel)->applyFromArray($styleArray_amarelo);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_branco5);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 4).':F'.($linha_excel + 4))->applyFromArray($styleArray_branco5);
    
    $spreadsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray_preto);
    $spreadsheet->getActiveSheet()->getStyle('D'.($linha_excel + 5).':F'.($linha_excel + 5))->applyFromArray($styleArray_preto);
    
    $spreadsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray_bold);
    
    $styleArray = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    $activeWorksheet->getStyle('F10:G'.$linha_excel)->applyFromArray($styleArray);
    
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Relatorio - '.$relatorio);
    $worksheet->setSelectedCell('A1');
    
}

} //Fim relatorios


if($relatorio_tipo == 'pdf'){
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

}else{ //Inicio Relatorio Excel

// Create a temporary file for download
$filename = 'Relatorio - '.$relatorio.'.xls';
$tempFile = tempnam(sys_get_temp_dir(), $filename);
$writer = new Xls($spreadsheet);
$writer->save($tempFile);

// Send headers to force download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Output the file to the browser
readfile($tempFile);

// Delete the temporary file
unlink($tempFile);

$conn_mysqli->close();

header('Location: index.php');
    exit();

}

?>
