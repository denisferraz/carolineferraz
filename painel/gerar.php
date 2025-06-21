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
$gerador_nome = $config_empresa;
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

function calcularLancamentosGrupo($excel_pdf, $coluna, $id, $inicio, $fim, $conexao) {
    $colunasPermitidas = ['grupo_id', 'tipo_id'];
    if (!in_array($coluna, $colunasPermitidas)) {
        throw new Exception("Coluna inválida.");
    }

    $sql = "
        SELECT SUM(valor) AS total 
        FROM lancamentos 
        WHERE token_emp = :token_emp 
        AND conta_id IN (SELECT id FROM contas WHERE $coluna = :id)
        AND data_lancamento BETWEEN :inicio AND :fim
    ";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        'token_emp' => $_SESSION['token_emp'],
        'id' => $id,
        'inicio' => $inicio,
        'fim' => $fim
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $raw_total = floatval($row['total'] ?? 0);

    if ($excel_pdf === 'pdf') {
        return number_format($raw_total, 2, ",", ".");
    } else {
        return $raw_total;
    }
}

function calcularReceitaPagamento($produtoFiltro, $inicio, $fim, $conexao, $formatar = true) {
    $sql = "
        SELECT SUM(valor) AS total
        FROM lancamentos_atendimento
        WHERE token_emp = :token_emp
          AND tipo = 'Pagamento'
          " . ($produtoFiltro ? "AND produto LIKE :produto" : "") . "
          AND quando BETWEEN :inicio AND :fim
    ";

    $stmt = $conexao->prepare($sql);

    $params = [
        'token_emp' => $_SESSION['token_emp'],
        'inicio' => $inicio,
        'fim' => $fim
    ];

    if ($produtoFiltro) {
        $params['produto'] = "%{$produtoFiltro}%";
    }

    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = floatval($row['total'] ?? 0) * -1;

    return $formatar
        ? number_format($total, 2, ",", ".")
        : $total;
}


    //Relatorios Dia
    //Receitas
    $receita_lancamento_dia = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 1, $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_lancamento_servicos_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 1, $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_lancamento_financeiro_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 2, $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_lancamento_outros_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 3, $relatorio_inicio, $relatorio_fim, $conexao);

    //Despesas
    $despesa_servicos_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 4, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_pessoal_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 5, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_comerciais_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 6, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_administrativas_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 7, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_financeiras_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 8, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_impostos_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 9, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_investimento_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 10, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_retirada_socios_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 11, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_energeticos_dia = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 12, $relatorio_inicio, $relatorio_fim, $conexao);
    $despesa_total_dia = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 2, $relatorio_inicio, $relatorio_fim, $conexao);

    //Pagamentos
    $receita_cartao_dia = calcularReceitaPagamento("Cart", $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_dinheiro_dia = calcularReceitaPagamento("Dinheiro", $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_transferencia_dia = calcularReceitaPagamento("Transferencia", $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_outros_dia = calcularReceitaPagamento("Outros", $relatorio_inicio, $relatorio_fim, $conexao);
    $receita_total_dia = calcularReceitaPagamento(null, $relatorio_inicio, $relatorio_fim, $conexao);

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

    if($relatorio_tipo == 'pdf'){
    $lucro_liquido_dia = number_format((floatval(str_replace(',', '.', str_replace('.', '', $receita_lancamento_dia))) - floatval(str_replace(',', '.', str_replace('.', '', $despesa_total_dia)))) ,2,",",".");
    }else{
    $exl_lucro_liquido_dia = $receita_lancamento_dia - $despesa_total_dia;
    }

    //Relatorios Mensal
    //Receitas
    $receita_lancamento_mes = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 1, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_lancamento_servicos_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 1, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_lancamento_financeiro_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 2, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_lancamento_outros_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 3, $relatorio_inicio_mes, $relatorio_fim, $conexao);

    //Despesas
    $despesa_servicos_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 4, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_pessoal_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 5, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_comerciais_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 6, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_administrativas_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 7, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_financeiras_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 8, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_impostos_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 9, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_investimento_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 10, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_retirada_socios_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 11, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_energeticos_mes = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 12, $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $despesa_total_mes = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 2, $relatorio_inicio_mes, $relatorio_fim, $conexao);

    //Pagamentos
    $receita_cartao_mes = calcularReceitaPagamento("Cart", $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_dinheiro_mes = calcularReceitaPagamento("Dinheiro", $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_transferencia_mes = calcularReceitaPagamento("Transferencia", $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_outros_mes = calcularReceitaPagamento("Outros", $relatorio_inicio_mes, $relatorio_fim, $conexao);
    $receita_total_mes = calcularReceitaPagamento(null, $relatorio_inicio_mes, $relatorio_fim, $conexao);

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
    
    if($relatorio_tipo == 'pdf'){
    $lucro_liquido_mes = number_format((floatval(str_replace(',', '.', str_replace('.', '', $receita_lancamento_mes))) - floatval(str_replace(',', '.', str_replace('.', '', $despesa_total_mes)))) ,2,",",".");
    }else{
    $exl_lucro_liquido_mes = $receita_lancamento_mes - $despesa_total_mes;
    }

    //Relatorios Anual
    //Receitas
    $receita_lancamento_ano = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 1, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_lancamento_servicos_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 1, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_lancamento_financeiro_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 2, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_lancamento_outros_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 3, $relatorio_inicio_ano, $relatorio_fim, $conexao);

    //Despesas
    $despesa_servicos_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 4, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_pessoal_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 5, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_comerciais_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 6, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_administrativas_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 7, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_financeiras_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 8, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_impostos_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 9, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_investimento_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 10, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_retirada_socios_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 11, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_energeticos_ano = calcularLancamentosGrupo($relatorio_tipo, 'grupo_id', 12, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $despesa_total_ano = calcularLancamentosGrupo($relatorio_tipo, 'tipo_id', 2, $relatorio_inicio_ano, $relatorio_fim, $conexao);
    
    //Pagamentos
    $receita_cartao_ano = calcularReceitaPagamento("Cart", $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_dinheiro_ano = calcularReceitaPagamento("Dinheiro", $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_transferencia_ano = calcularReceitaPagamento("Transferencia", $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_outros_ano = calcularReceitaPagamento("Outros", $relatorio_inicio_ano, $relatorio_fim, $conexao);
    $receita_total_ano = calcularReceitaPagamento(null, $relatorio_inicio_ano, $relatorio_fim, $conexao);

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

    if($relatorio_tipo == 'pdf'){
    $lucro_liquido_ano = number_format((floatval(str_replace(',', '.', str_replace('.', '', $receita_lancamento_ano))) - floatval(str_replace(',', '.', str_replace('.', '', $despesa_total_ano)))) ,2,",",".");
    }else{
    $exl_lucro_liquido_ano = $receita_lancamento_ano - $despesa_total_ano;
    }

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
    <legend><b>Resumo</b><br></legend>
    <table width=100% border=2px>
    <tr style=\"background-color: #FFFF00; font-weight: bold; color: #333;\"><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Atendimentos</td><td align=center>$arrivals_dia_consultas</td><td align=center>$arrivals_mes_consultas</td><td align=center>$arrivals_ano_consultas</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Consultas</td><td align=center>$row_reservas_dia</td><td align=center>$row_reservas_mes</td><td align=center>$row_reservas_ano</td></tr>
    <tr><td><b>Finalizadas</td><td align=center>$arrivals_dia</td><td align=center>$arrivals_mes</td><td align=center>$arrivals_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Canceladas</td><td align=center>$row_cancelamentos_dia</td><td align=center>$row_cancelamentos_mes</td><td align=center>$row_cancelamentos_ano</td></tr>
    <tr><td><b>No-Shows</td><td align=center>$noshows_dia</td><td align=center>$noshows_mes</td><td align=center>$noshows_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Performance</td><td align=center>$ocupacao_dia%</td><td align=center>$ocupacao_mes%</td><td align=center>$ocupacao_ano%</td></tr>
    <tr style=\"background-color: #81c784; color: black;\"><td><b>Receita Total</td><td align=center>R$$receita_lancamento_dia</td><td align=center>R$$receita_lancamento_mes</td><td align=center>R$$receita_lancamento_ano</td></tr>
    <tr style=\"background-color: #ffcdd2; color: #333;\"><td><b>Despesas Total</td><td align=center>R$$despesa_total_dia</td><td align=center>R$$despesa_total_mes</td><td align=center>R$$despesa_total_ano</td></tr>
    <tr style=\"background-color: #2e7d32; color: white;\"><td><b>Lucro Liquido</td><td align=center>R$$lucro_liquido_dia</td><td align=center>R$$lucro_liquido_mes</td><td align=center>R$$lucro_liquido_ano</td></tr>
    </table>
    </fieldset>
    <fieldset>
    <legend><b>Receitas</b><br></legend>
    <table width=100% border=2px>
    <tr style=\"background-color: #FFFF00; font-weight: bold; color: #333;\"><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Receita de Serviços</td><td align=center>R$$receita_lancamento_servicos_dia</td><td align=center>R$$receita_lancamento_servicos_mes</td><td align=center>R$$receita_lancamento_servicos_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Receitas Financeiras</td><td align=center>R$$receita_lancamento_financeiro_dia</td><td align=center>R$$receita_lancamento_financeiro_mes</td><td align=center>R$$receita_lancamento_financeiro_ano</td></tr>
    <tr><td><b>Outras Receitas</td><td align=center>R$$receita_lancamento_outros_dia</td><td align=center>R$$receita_lancamento_outros_mes</td><td align=center>R$$receita_lancamento_outros_ano</td></tr>
    <tr style=\"background-color: #81c784; color: black;\"><td><b>Receita Total</td><td align=center>R$$receita_lancamento_dia</td><td align=center>R$$receita_lancamento_mes</td><td align=center>R$$receita_lancamento_ano</td></tr>
    </table>
    </fieldset>
    <fieldset>
    <legend><b>Despesas</b><br></legend>
    <table width=100% border=2px>
    <tr style=\"background-color: #FFFF00; font-weight: bold; color: #333;\"><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Despesas com Serviços</td><td align=center>R$$despesa_servicos_dia</td><td align=center>R$$despesa_servicos_mes</td><td align=center>R$$despesa_servicos_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Despesas com Pessoal</td><td align=center>R$$despesa_pessoal_dia</td><td align=center>R$$despesa_pessoal_mes</td><td align=center>R$$despesa_pessoal_ano</td></tr>
    <tr><td><b>Despesas Comerciais</td><td align=center>R$$despesa_comerciais_dia</td><td align=center>R$$despesa_comerciais_mes</td><td align=center>R$$despesa_comerciais_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Despesas Administrativas</td><td align=center>R$$despesa_administrativas_dia</td><td align=center>R$$despesa_administrativas_mes</td><td align=center>R$$despesa_administrativas_ano</td></tr>
    <tr><td><b>Despesas Financeiras</td><td align=center>R$$despesa_financeiras_dia</td><td align=center>R$$despesa_financeiras_mes</td><td align=center>R$$despesa_financeiras_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Impostos</td><td align=center>R$$despesa_impostos_dia</td><td align=center>R$$despesa_impostos_mes</td><td align=center>R$$despesa_impostos_ano</td></tr>
    <tr><td><b>Energéticos</td><td align=center>R$$despesa_energeticos_dia</td><td align=center>R$$despesa_energeticos_mes</td><td align=center>R$$despesa_energeticos_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Retirada Socios</td><td align=center>R$$despesa_retirada_socios_dia</td><td align=center>R$$despesa_retirada_socios_mes</td><td align=center>R$$despesa_retirada_socios_ano</td></tr>
    <tr><td><b>Investimentos</td><td align=center>R$$despesa_investimento_dia</td><td align=center>R$$despesa_investimento_mes</td><td align=center>R$$despesa_investimento_ano</td></tr>
    <tr style=\"background-color: #ffcdd2; color: #333;\"><td><b>Despesas Total</td><td align=center>R$$despesa_total_dia</td><td align=center>R$$despesa_total_mes</td><td align=center>R$$despesa_total_ano</td></tr>
    </table>
    </fieldset>
    <div style=\"page-break-before: always;\"></div>
    <fieldset>
    <legend><b>Pagamentos</b><br></legend>
    <table width=100% border=2px>
    <tr style=\"background-color: #FFFF00; font-weight: bold; color: #333;\"><td align=center width=34%><b>Topico</td><td align=center width=34%><b>Dia</td><td align=center width=34%><b>Mês</td><td align=center width=34%><b>Ano</td></tr>
    <tr><td><b>Pagamento em Cartão</td><td align=center>R$$receita_cartao_dia</td><td align=center>R$$receita_cartao_mes</td><td align=center>R$$receita_cartao_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Pagamento em Dinheiro</td><td align=center>R$$receita_dinheiro_dia</td><td align=center>R$$receita_dinheiro_mes</td><td align=center>R$$receita_dinheiro_ano</td></tr>
    <tr><td><b>Pagamento em Transferencias</td><td align=center>R$$receita_transferencia_dia</td><td align=center>R$$receita_transferencia_mes</td><td align=center>R$$receita_transferencia_ano</td></tr>
    <tr style=\"background-color: #f0f0f0; color: #333;\"><td><b>Pagamento em Outros</td><td align=center>R$$receita_outros_dia</td><td align=center>R$$receita_outros_mes</td><td align=center>R$$receita_outros_ano</td></tr>
    <tr style=\"background-color: #81c784; color: black;\"><td><b>Pagamentos Total</td><td align=center>R$$receita_total_dia</td><td align=center>R$$receita_total_mes</td><td align=center>R$$receita_total_ano</td></tr>
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

//Receitas
$activeWorksheet->setCellValue('C5', 'Resumo');
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
$activeWorksheet->setCellValue('C13', 'Receita Total');
$activeWorksheet->setCellValue('D13', $receita_lancamento_dia);
$activeWorksheet->setCellValue('E13', $receita_lancamento_mes);
$activeWorksheet->setCellValue('F13', $receita_lancamento_ano);
$activeWorksheet->setCellValue('C14', 'Despesa Total');
$activeWorksheet->setCellValue('D14', $despesa_total_dia);
$activeWorksheet->setCellValue('E14', $despesa_total_mes);
$activeWorksheet->setCellValue('F14', $despesa_total_ano);
$activeWorksheet->setCellValue('C15', 'Lucro Liquido');
$activeWorksheet->setCellValue('D15', $exl_lucro_liquido_dia);
$activeWorksheet->setCellValue('E15', $exl_lucro_liquido_mes);
$activeWorksheet->setCellValue('F15', $exl_lucro_liquido_ano);

$activeWorksheet->setCellValue('C17', 'Receitas');
$activeWorksheet->mergeCells('C17:F17');
$activeWorksheet->setCellValue('C18', 'Linha');
$activeWorksheet->setCellValue('D18', 'Dia');
$activeWorksheet->setCellValue('E18', 'Mês');
$activeWorksheet->setCellValue('F18', 'Ano');
$activeWorksheet->setCellValue('C19', 'Receita de Serviços');
$activeWorksheet->setCellValue('D19', $receita_lancamento_servicos_dia);
$activeWorksheet->setCellValue('E19', $receita_lancamento_servicos_mes);
$activeWorksheet->setCellValue('F19', $receita_lancamento_servicos_ano);
$activeWorksheet->setCellValue('C20', 'Receitas Financeiras');
$activeWorksheet->setCellValue('D20', $receita_lancamento_financeiro_dia);
$activeWorksheet->setCellValue('E20', $receita_lancamento_financeiro_mes);
$activeWorksheet->setCellValue('F20', $receita_lancamento_financeiro_ano);
$activeWorksheet->setCellValue('C21', 'Outras Receitas');
$activeWorksheet->setCellValue('D21', $receita_lancamento_outros_dia);
$activeWorksheet->setCellValue('E21', $receita_lancamento_outros_mes);
$activeWorksheet->setCellValue('F21', $receita_lancamento_outros_ano);
$activeWorksheet->setCellValue('C22', 'Receita Total ');
$activeWorksheet->setCellValue('D22', $receita_lancamento_dia);
$activeWorksheet->setCellValue('E22', $receita_lancamento_mes);
$activeWorksheet->setCellValue('F22', $receita_lancamento_ano);

$activeWorksheet->setCellValue('C24', 'Despesas');
$activeWorksheet->mergeCells('C24:F24');
$activeWorksheet->setCellValue('C25', 'Linha');
$activeWorksheet->setCellValue('D25', 'Dia');
$activeWorksheet->setCellValue('E25', 'Mês');
$activeWorksheet->setCellValue('F25', 'Ano');
$activeWorksheet->setCellValue('C26', 'Despesas com Serviços');
$activeWorksheet->setCellValue('D26', $despesa_servicos_dia);
$activeWorksheet->setCellValue('E26', $despesa_servicos_mes);
$activeWorksheet->setCellValue('F26', $despesa_servicos_ano);
$activeWorksheet->setCellValue('C27', 'Despesas com Pessoal');
$activeWorksheet->setCellValue('D27', $despesa_pessoal_dia);
$activeWorksheet->setCellValue('E27', $despesa_pessoal_mes);
$activeWorksheet->setCellValue('F27', $despesa_pessoal_ano);
$activeWorksheet->setCellValue('C28', 'Despesas Comerciais');
$activeWorksheet->setCellValue('D28', $despesa_comerciais_dia);
$activeWorksheet->setCellValue('E28', $despesa_comerciais_mes);
$activeWorksheet->setCellValue('F28', $despesa_comerciais_ano);
$activeWorksheet->setCellValue('C29', 'Despesas Administrativas');
$activeWorksheet->setCellValue('D29', $despesa_administrativas_dia);
$activeWorksheet->setCellValue('E29', $despesa_administrativas_mes);
$activeWorksheet->setCellValue('F29', $despesa_administrativas_ano);
$activeWorksheet->setCellValue('C30', 'Despesas Financeiras');
$activeWorksheet->setCellValue('D30', $despesa_financeiras_dia);
$activeWorksheet->setCellValue('E30', $despesa_financeiras_mes);
$activeWorksheet->setCellValue('F30', $despesa_financeiras_ano);
$activeWorksheet->setCellValue('C31', 'Impostos');
$activeWorksheet->setCellValue('D31', $despesa_impostos_dia);
$activeWorksheet->setCellValue('E31', $despesa_impostos_mes);
$activeWorksheet->setCellValue('F31', $despesa_impostos_ano);
$activeWorksheet->setCellValue('C32', 'Energéticos');
$activeWorksheet->setCellValue('D32', $despesa_energeticos_dia);
$activeWorksheet->setCellValue('E32', $despesa_energeticos_mes);
$activeWorksheet->setCellValue('F32', $despesa_energeticos_ano);
$activeWorksheet->setCellValue('C33', 'Retirada Socios');
$activeWorksheet->setCellValue('D33', $despesa_retirada_socios_dia);
$activeWorksheet->setCellValue('E33', $despesa_retirada_socios_mes);
$activeWorksheet->setCellValue('F33', $despesa_retirada_socios_ano);
$activeWorksheet->setCellValue('C34', 'Investimentos');
$activeWorksheet->setCellValue('D34', $despesa_investimento_dia);
$activeWorksheet->setCellValue('E34', $despesa_investimento_mes);
$activeWorksheet->setCellValue('F34', $despesa_investimento_ano);
$activeWorksheet->setCellValue('C35', 'Despesas Total');
$activeWorksheet->setCellValue('D35', $despesa_total_dia);
$activeWorksheet->setCellValue('E35', $despesa_total_mes);
$activeWorksheet->setCellValue('F35', $despesa_total_ano);


$activeWorksheet->setCellValue('C37', 'Pagamentos');
$activeWorksheet->mergeCells('C37:F37');
$activeWorksheet->setCellValue('C38', 'Linha');
$activeWorksheet->setCellValue('D38', 'Dia');
$activeWorksheet->setCellValue('E38', 'Mês');
$activeWorksheet->setCellValue('F38', 'Ano');
$activeWorksheet->setCellValue('C39', 'Pagamento em Cartão');
$activeWorksheet->setCellValue('D39', str_replace(['.', ','], ['', '.'], $receita_cartao_dia));
$activeWorksheet->setCellValue('E39', str_replace(['.', ','], ['', '.'], $receita_cartao_mes));
$activeWorksheet->setCellValue('F39', str_replace(['.', ','], ['', '.'], $receita_cartao_ano));
$activeWorksheet->setCellValue('C40', 'Pagamento em Dinheiro');
$activeWorksheet->setCellValue('D40', str_replace(['.', ','], ['', '.'], $receita_dinheiro_dia));
$activeWorksheet->setCellValue('E40', str_replace(['.', ','], ['', '.'], $receita_dinheiro_mes));
$activeWorksheet->setCellValue('F40', str_replace(['.', ','], ['', '.'], $receita_dinheiro_ano));
$activeWorksheet->setCellValue('C41', 'Pagamento em Transferencias');
$activeWorksheet->setCellValue('D41', str_replace(['.', ','], ['', '.'], $receita_transferencia_dia));
$activeWorksheet->setCellValue('E41', str_replace(['.', ','], ['', '.'], $receita_transferencia_mes));
$activeWorksheet->setCellValue('F41', str_replace(['.', ','], ['', '.'], $receita_transferencia_ano));
$activeWorksheet->setCellValue('C42', 'Pagamento em Outros');
$activeWorksheet->setCellValue('D42', str_replace(['.', ','], ['', '.'], $receita_outros_dia));
$activeWorksheet->setCellValue('E42', str_replace(['.', ','], ['', '.'], $receita_outros_mes));
$activeWorksheet->setCellValue('F42', str_replace(['.', ','], ['', '.'], $receita_outros_ano));
$activeWorksheet->setCellValue('C43', 'Pagamentos Total');
$activeWorksheet->setCellValue('D43', str_replace(['.', ','], ['', '.'], $receita_total_dia));
$activeWorksheet->setCellValue('E43', str_replace(['.', ','], ['', '.'], $receita_total_mes));
$activeWorksheet->setCellValue('F43', str_replace(['.', ','], ['', '.'], $receita_total_ano));

//Assinatura
$activeWorksheet->setCellValue('C46', $gerador_nome.' | '.$data_gerador);
$activeWorksheet->mergeCells('C46:J46');
$activeWorksheet->setCellValue('C47', 'Relatório Gerado via Sistema');
$activeWorksheet->mergeCells('C47:J47');


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
$objDrawing->setName($config_empresa);
$objDrawing->setDescription($config_empresa);
$objDrawing->setPath($imagePath);
$objDrawing->setCoordinates('H5'); // Posição onde a imagem será inserida
$objDrawing->setWidth(170); // Largura da imagem em pixels
$objDrawing->setHeight(170); // Altura da imagem em pixels
$objDrawing->setWorksheet($activeWorksheet);

$spreadsheet->getActiveSheet()->getStyle('C8:F8')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C10:F10')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C12:F12')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C14:F14')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C20:F20')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C27:F27')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C29:F29')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C31:F31')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C33:F33')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C40:F40')->applyFromArray($styleArray_separacao);
$spreadsheet->getActiveSheet()->getStyle('C42:F42')->applyFromArray($styleArray_separacao);

$spreadsheet->getActiveSheet()->getStyle('C3:I3')->applyFromArray($styleArray_cinza);
$spreadsheet->getActiveSheet()->getStyle('B45:K48')->applyFromArray($styleArray_cinza);

$spreadsheet->getActiveSheet()->getStyle('C5:F5')->applyFromArray($styleArray_laranja);
$spreadsheet->getActiveSheet()->getStyle('C17:F17')->applyFromArray($styleArray_laranja);
$spreadsheet->getActiveSheet()->getStyle('C24:F24')->applyFromArray($styleArray_laranja);
$spreadsheet->getActiveSheet()->getStyle('C37:F37')->applyFromArray($styleArray_laranja);

$spreadsheet->getActiveSheet()->getStyle('C6:F6')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C18:F18')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C25:F25')->applyFromArray($styleArray_amarelo);
$spreadsheet->getActiveSheet()->getStyle('C38:F38')->applyFromArray($styleArray_amarelo);

$spreadsheet->getActiveSheet()->getStyle('C46:J46')->applyFromArray($styleArray_branco5);
$spreadsheet->getActiveSheet()->getStyle('C47:J47')->applyFromArray($styleArray_preto);

$spreadsheet->getActiveSheet()->getStyle('C15:F15')->applyFromArray($styleArray_green);
$spreadsheet->getActiveSheet()->getStyle('C22:F22')->applyFromArray($styleArray_green);
$spreadsheet->getActiveSheet()->getStyle('C35:F35')->applyFromArray($styleArray_red);
$spreadsheet->getActiveSheet()->getStyle('C43:F43')->applyFromArray($styleArray_blue);

$activeWorksheet->getStyle('C5:F15')->applyFromArray($styleArray_inside_borders);
$activeWorksheet->getStyle('C17:F22')->applyFromArray($styleArray_inside_borders);
$activeWorksheet->getStyle('C24:F35')->applyFromArray($styleArray_inside_borders);
$activeWorksheet->getStyle('C37:F43')->applyFromArray($styleArray_inside_borders);

$activeWorksheet->getStyle('C3:I3')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C5:F15')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C17:F22')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C24:F35')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C37:F43')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B45:K48')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('C46:J47')->applyFromArray($styleArray_outside_borders);
$activeWorksheet->getStyle('B2:K48')->applyFromArray($styleArray_outside_borders);

$styleArray = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$activeWorksheet->getStyle('D5:F43')->applyFromArray($styleArray);

$activeWorksheet->getStyle('D13:F15')->getNumberFormat()->setFormatCode('R$ #,##0.00');
$activeWorksheet->getStyle('D19:F22')->getNumberFormat()->setFormatCode('R$ #,##0.00');
$activeWorksheet->getStyle('D26:F35')->getNumberFormat()->setFormatCode('R$ #,##0.00');
$activeWorksheet->getStyle('D39:F43')->getNumberFormat()->setFormatCode('R$ #,##0.00');

$worksheet = $spreadsheet->getActiveSheet();
$worksheet->setTitle('Relatorio - '.$relatorio);
$worksheet->setSelectedCell('A1');

    }
}else if($relatorio == 'Estornos Dia' || $relatorio == 'Estornos Mes' || $relatorio == 'Estornos Ano'){
    $tipo = 'landscape';

$resultado_estorno = '';
$qtd_tr = 0;
if($relatorio == 'Estornos Dia'){
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Estornos Mes'){
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_estorno = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim}' AND produto LIKE '%Estornado%' AND tipo = 'Produto' ORDER BY quando DESC");
}
$estorno_total = $query_estorno->rowCount();
if($estorno_total > 0){
while($select_estorno = $query_estorno->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_estorno['doc_email'];
$produto = $select_estorno['produto'];
$feitopor = $select_estorno['feitopor'];
$quando = $select_estorno['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$qtd_tr++;

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $doc_email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
    $hospede = $select_check2['nome'];
}

    if($relatorio_tipo == 'pdf'){

If($qtd_tr % 2 == 0){
$resultado_estorno .= "<tr><td align=center>$hospede</td><td>$produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
}else{
$resultado_estorno .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$hospede</td><td>$produto</td><td>$feitopor</td><td align=center>$quando</td></tr>";
}
    
}else{

    $dados_relatorio[] = [
        'hospede' => $hospede,
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
<tr style=\"background-color: #FFA500; color: #333;\">
<td align=center width=15%><b>Nome</b></td>
<td align=center width=55%><b>Descrição Produto</b></td>
<td align=center width=20%><b>Responsavel</b></td>
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
    $hospede = $select['hospede'];
    $produto = $select['produto'];
    $feitopor = $select['feitopor'];
    $quando = $select['quando'];

    $linha_excel++;
    $qtd++;

    if($linha_excel % 2 != 0){
    $spreadsheet->getActiveSheet()->getStyle('C'.$linha_excel.':G'.$linha_excel)->applyFromArray($styleArray_separacao); 
    }

$activeWorksheet->setCellValue('C'.$linha_excel, $qtd);
$activeWorksheet->setCellValue('D'.$linha_excel, $hospede);
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
$objDrawing->setName($config_empresa);
$objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
$resultado_lanc = '';
if($relatorio == 'Lançamentos Dia'){
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio}' AND quando <= '{$relatorio_fim}' AND tipo = 'Produto' ORDER BY quando DESC");
}else if($relatorio == 'Lançamentos Mes'){
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_mes}' AND quando <= '{$relatorio_fim}' AND tipo = 'Produto' ORDER BY quando DESC");
}else{
$query_lanc = $conexao->query("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND quando >= '{$relatorio_inicio_ano}' AND quando <= '{$relatorio_fim}' AND tipo = 'Produto' ORDER BY quando DESC");
}
$lanc_total = $query_lanc->rowCount();
if($lanc_total > 0){
while($select_lanc = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_lanc['doc_email'];
$produto = $select_lanc['produto'];
$feitopor = $select_lanc['feitopor'];
$quando = $select_lanc['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_lanc['valor'];
$valor = number_format($valor ,2,",",".");
$qtd_tr++;


$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $doc_email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
    $hospede = $select_check2['nome'];
}

if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
    $resultado_lanc .= "<tr><td align=center>$hospede</td><td>$produto</td><td>R$$valor</td><td>$feitopor</td><td align=center>$quando</td></tr>";
    }else{
    $resultado_lanc .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$hospede</td><td>$produto</td><td>R$$valor</td><td>$feitopor</td><td align=center>$quando</td></tr>";
    }

    }else{
    
        $dados_relatorio[] = [
            'hospede' => $hospede,
            'produto' => $select_lanc['produto'],
            'feitopor' => $select_lanc['feitopor'],
            'quando' => $select_lanc['quando'],
            'valor' => $select_lanc['valor']
        ];
    
        }

}
}else{
$resultado_lanc = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$lanc_total = 0;
}

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Lançamentos</b>: $lanc_total<br>
<table width=100% border=1px>
<tr style=\"background-color: #FFA500; color: #333;\">
<td align=center><b>Nome</b></td>
<td align=center><b>Descrição Produto</b></td>
<td align=center><b>Valor</b></td>
<td align=center><b>Responsavel</b></td>
<td align=center><b>Data</b></td>
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
    $hospede = $select['hospede'];
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
$activeWorksheet->setCellValue('D'.$linha_excel, $hospede);
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
$objDrawing->setName($config_empresa);
$objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
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
$doc_email = $select_pgto['doc_email'];
$produto = $select_pgto['produto'];
$feitopor = $select_pgto['feitopor'];
$quando = $select_pgto['quando'];
$quando = date('d/m/Y - H:i\h', strtotime("$quando"));
$valor = $select_pgto['valor'];
$valor = number_format(($valor * (-1)) ,2,",",".");
$qtd_tr++;

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $doc_email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
    $hospede = $select_check2['nome'];
}

if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
        $resultado_pgto .= "<tr><td align=center>$hospede</td><td>$produto</td><td>R$$valor</td><td>$feitopor</td><td align=center>$quando</td></tr>";
    }else{
        $resultado_pgto .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$hospede</td><td>$produto</td><td>R$$valor</td><td>$feitopor</td><td align=center>$quando</td></tr>";
    }

    }else{
    
        $dados_relatorio[] = [
            'hospede' => $hospede,
            'produto' => $select_pgto['produto'],
            'feitopor' => $select_pgto['feitopor'],
            'quando' => $select_pgto['quando'],
            'valor' => $select_pgto['valor']
        ];
    
        }

}
}else{
$resultado_pgto = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$pgto_total = 0;
    }

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Pagamentos</b>: $pgto_total<br>
<table width=100% border=1px>
<tr style=\"background-color: #FFA500; color: #333;\">
<td align=center><b>Nome</b></td>
<td align=center><b>Descrição do Pagamento</b></td>
<td align=center><b>Valor</b></td>
<td align=center><b>Responsavel</b></td>
<td align=center><b>Data</b></td>
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
        $hospede = $select['hospede'];
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
    $activeWorksheet->setCellValue('D'.$linha_excel, $hospede);
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
    $objDrawing->setName($config_empresa);
    $objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
$resultado_despesa = '';
if($relatorio == 'Despesas Dia'){
$query_despesas = $conexao->prepare("SELECT * FROM lancamentos WHERE token_emp = :token_emp AND conta_id IN (SELECT id FROM contas WHERE tipo_id = :tipo_id) AND data_lancamento BETWEEN :inicio AND :fim ORDER BY data_lancamento DESC");
$query_despesas->execute(['token_emp' => $_SESSION['token_emp'], 'tipo_id' => 2, 'inicio' => $relatorio_inicio, 'fim' => $relatorio_fim]);
}else if($relatorio == 'Despesas Mes'){
$query_despesas = $conexao->prepare("SELECT * FROM lancamentos WHERE token_emp = :token_emp AND conta_id IN (SELECT id FROM contas WHERE tipo_id = :tipo_id) AND data_lancamento BETWEEN :inicio AND :fim ORDER BY data_lancamento DESC");
$query_despesas->execute(['token_emp' => $_SESSION['token_emp'], 'tipo_id' => 2, 'inicio' => $relatorio_inicio_mes, 'fim' => $relatorio_fim]);
}else{
$query_despesas = $conexao->prepare("SELECT * FROM lancamentos WHERE token_emp = :token_emp AND conta_id IN (SELECT id FROM contas WHERE tipo_id = :tipo_id) AND data_lancamento BETWEEN :inicio AND :fim ORDER BY data_lancamento DESC");
$query_despesas->execute(['token_emp' => $_SESSION['token_emp'], 'tipo_id' => 2, 'inicio' => $relatorio_inicio_ano, 'fim' => $relatorio_fim]);
}
$despesas_total = $query_despesas->rowCount();
if($despesas_total > 0){
while($select_despesas = $query_despesas->fetch(PDO::FETCH_ASSOC)){
$conta_id = $select_despesas['conta_id'];
$despesa_descricao = $select_despesas['descricao'];
$despesa_quem = $select_despesas['feitopor'];
$despesa_dia = $select_despesas['data_lancamento'];
$despesa_dia = date('d/m/Y', strtotime("$despesa_dia"));
$valor = $select_despesas['valor'];
$qtd_tr++;

$query_conta_id = $conexao->prepare("SELECT descricao FROM contas WHERE id = :id");
$query_conta_id->execute(['id' => $conta_id]);
while($select_conta_id = $query_conta_id->fetch(PDO::FETCH_ASSOC)){
    $despesa_tipo = $select_conta_id['descricao'];
}


if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
        $resultado_despesa .= "<tr><td>$despesa_tipo</td><td>$despesa_descricao</td><td>R$$valor</td><td>$despesa_quem</td><td align=center>$despesa_dia</td></tr>";
    }else{
        $resultado_despesa .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$despesa_tipo</td><td>$despesa_descricao</td><td>R$$valor</td><td>$despesa_quem</td><td align=center>$despesa_dia</td></tr>";
    }
}else{
    
    $dados_relatorio[] = [
        'despesa_tipo' => $despesa_tipo,
        'feitopor' => $select_despesas['feitopor'],
        'produto' => $select_despesas['descricao'],
        'quando' => $select_despesas['data_lancamento'],
        'valor' => $select_despesas['valor']
    ];
    
}

}
}else{
$resultado_despesa = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>'; 
$despesas_total = 0;
}

if($relatorio_tipo == 'pdf'){
//Corpo do PDF
$gera_body = "
<fieldset>
<b>Data Relatorio</b> - $relatorio_inicio_str<br><br>
<b>Quantidade de Lançamentos</b>: $despesas_total<br>
<table width=100% border=1px>
<tr style=\"background-color: #FFA500; color: #333;\">
<td align=center><b>Tipo</b></td>
<td align=center><b>Descrição Despesa</b></td>
<td align=center><b>Valor</b></td>
<td align=center><b>Responsavel</b></td>
<td align=center><b>Data</b></td>
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
    $objDrawing->setName($config_empresa);
    $objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
$resultado_reservas = '';
if($relatorio == 'Consultas Dia'){
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}else if($relatorio == 'Consultas Mes'){
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}else{
$query_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}

$reservas_total = $query_reservas->rowCount();
if($reservas_total > 0){
while($select_reservas = $query_reservas->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_reservas['doc_email'];
$status_consulta = $select_reservas['status_consulta'];
$atendimento_dia = $select_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));
$qtd_tr++;

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $doc_email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
    $hospede = $select_check2['nome'];
}

if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
    $resultado_reservas .= "<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }else{
    $resultado_reservas .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }

}else{
        
    $dados_relatorio[] = [
        'hospede' => $hospede,
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
<tr style=\"background-color: #FFA500; color: #333;\">
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
    $objDrawing->setName($config_empresa);
    $objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
$resultado_canc_reservas = '';
if($relatorio == 'Cancelamentos Dia'){
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' AND status_consulta = 'Cancelada' ORDER BY atendimento_hora DESC");
}else if($relatorio == 'Cancelamentos Mes'){
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_fim}' AND status_consulta = 'Cancelada' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}else{
$query_canc_reservas = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_fim}' AND status_consulta = 'Cancelada' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}

$canc_reservas_total = $query_canc_reservas->rowCount();
if($canc_reservas_total > 0){
while($select_canc_reservas = $query_canc_reservas->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_canc_reservas['doc_email'];
$status_consulta = $select_canc_reservas['status_consulta'];
$atendimento_dia = $select_canc_reservas['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_canc_reservas['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));
$qtd_tr++;

$result_check = $conexao->query("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = '{$doc_email}'");
$hospede = '';
if ($select = $result_check->fetch(PDO::FETCH_ASSOC)) {
    $dados = base64_decode($select['dados_painel_users']);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    $dados_array = explode(';', $dados_decifrados);
    $hospede = $dados_array[0] ?? '';
}

if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
    $resultado_canc_reservas .= "<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }else{
    $resultado_canc_reservas .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }

}else{
        
    $dados_relatorio[] = [
        'hospede' => $hospede,
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
<tr style=\"background-color: #FFA500; color: #333;\">
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
    $objDrawing->setName($config_empresa);
    $objDrawing->setDescription($config_empresa);
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

$qtd_tr = 0;
$resultado_noshows = '';
if($relatorio == 'No-Shows Dia'){
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_hora DESC");
}else if($relatorio == 'No-Shows Mes'){
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_mes}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}else{
$query_noshows = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia >= '{$relatorio_inicio_ano}' AND atendimento_dia <= '{$relatorio_inicio}' AND status_consulta = 'NoShow' ORDER BY atendimento_dia DESC, atendimento_hora DESC");
}
$noshows_total = $query_noshows->rowCount();
if($noshows_total > 0){
while($select_noshows = $query_noshows->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_noshows['doc_email'];
$status_consulta = $select_noshows['status_consulta'];
$atendimento_dia = $select_noshows['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$select_noshows->atendimento_dia"));
$atendimento_hora = $select_noshows['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$select_noshows->atendimento_hora"));
$qtd_tr++;

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $doc_email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
    $hospede = $select_check2['nome'];
}

if($relatorio_tipo == 'pdf'){

    If($qtd_tr % 2 == 0){
    $resultado_noshows .= "<tr><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }else{
    $resultado_noshows .= "<tr style=\"background-color: #f0f0f0; color: #333;\"><td align=center>$status_consulta</td><td>$hospede</td><td align=center>$atendimento_dia</td><td align=center>$atendimento_hora</td></tr>";
    }

}else{
        
    $dados_relatorio[] = [
        'hospede' => $hospede,
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
<tr style=\"background-color: #FFA500; color: #333;\">
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
    $objDrawing->setName($config_empresa);
    $objDrawing->setDescription($config_empresa);
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
					<title>Relatorio Gerencial - '.$relatorio.'</title>
            <style>
            html {
                height:100%;
            }
            
            .geral {
                min-height:100%;
                position:center;
                width:95%;
            }
            .data_gerador {
                position:absolute;
                top:5px;
                right:5px;
            }
            .page {
                position: relative;
                min-height: 80%;
            }
            .footer {
                position: absolute;
                bottom: 10px;
                width: 100%;
                text-align: center;
              }
                                         
            legend {
                font-size: 1rem;
                font-weight: bold;
                padding: 0 0.5rem;
              }
              fieldset {
                border: 1px solid black;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1.5rem;
              }
              table {
                border-radius: 8px;
              }
              td {
                border-radius: 4px;
              }
            </style>
				</head>
                <body>
                <div class="geral">
                <div class="data_gerador">
                <small>'.$data_gerador.'</small>
                </div>
                <center><h1>Relatorio Gerencial - '.$relatorio.' - '.$relatorio_inicio_str.'</h1></center>
                <br>
                <div class="page">
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

$conexao->close();

header('Location: index.php');
    exit();

}

?>
