<?php
require('../conexao.php');
require('verifica_login.php');

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
$diaSelecionado = isset($_GET['dia']) ? (int)$_GET['dia'] : null;

$primeiro_dia = mktime(0, 0, 0, $mes, 1, $ano);
$ultimo_dia = date("t", $primeiro_dia);
$nome_mes = strftime('%B', $primeiro_dia);
$dia_semana_inicio = date('w', $primeiro_dia);

$feriadosFixos = [
    '01-01', // Confraternização Universal
    '21-04', // Tiradentes
    '01-05', // Dia do Trabalhador
    '07-09', // Independência do Brasil
    '12-10', // Nossa Senhora Aparecida
    '02-11', // Finados
    '15-11', // Proclamação da República
    '25-12', // Natal
];

$feriadosSalvador = [
    '24-06', // São João
    '08-12', // Nossa Senhora da Conceição da Praia (padroeira de Salvador)
];

$feriadosBahia = [
    '02-07', // Independência da Bahia
    '08-12', // Nossa Senhora da Conceição (padroeira da Bahia)
];


function feriadosMoveis($ano) {
    $pascoa = date('Y-m-d', easter_date($ano));
    $data = new DateTime($pascoa);

    $carnaval = clone $data;
    $carnaval->modify('-47 days');

    $sextaSanta = clone $data;
    $sextaSanta->modify('-2 days');

    $corpusChristi = clone $data;
    $corpusChristi->modify('+60 days');

    return [
        $carnaval->format('m-d'),     // Carnaval
        $sextaSanta->format('m-d'),   // Sexta-feira Santa
        $pascoa = $data->format('m-d'),     // Páscoa
        $corpusChristi->format('m-d') // Corpus Christi
    ];
}

$ano = date('Y');
$feriados = array_merge(
    $feriadosFixos,
    feriadosMoveis($ano),
    $feriadosBahia,
    $feriadosSalvador
);

$query_alteracao = $conexao->query("SELECT * FROM alteracoes WHERE alt_status = 'Pendente'");
$alteracao_qtd = $query_alteracao->rowCount();
$temSolicitacaoPendente = $alteracao_qtd > 0;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda Mensal</title>
    <link rel="stylesheet" href="css/style_v2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
body {
  margin-top: 30px;
  margin-bottom: 200px;
}

.dia, .cabecalho {
  padding: 10px;
  min-height: 90px;
  border-radius: 12px;
  font-size: 0.85rem;
}
    </style>
</head>
<body>

<div class="calendario-container">
    <div class="calendario-header">
        <form method="get">
            <select name="mes" onchange="this.form.submit()">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m == $mes ? 'selected' : '' ?>>
                        <?= strftime('%B', mktime(0, 0, 0, $m, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
            <select name="ano" onchange="this.form.submit()">
                <?php for ($a = date('Y') - 1; $a <= date('Y') + 1; $a++): ?>
                    <option value="<?= $a ?>" <?= $a == $ano ? 'selected' : '' ?>><?= $a ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>
    
    
    <?php if ($temSolicitacaoPendente): ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: 'Solicitação Pendente!',
                text: 'Há uma ou mais solicitações pendentes aguardando autorização.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ver',
                cancelButtonText: 'Sair',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#444'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'autorizacao.php';
                }
            });
        });
        </script>
        <?php endif; ?>


    <?php if ($diaSelecionado): ?>

    <?php else: ?>
        <div class="calendario">
            <?php
            $dias_semana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
            foreach ($dias_semana as $index => $dia) {
                $classe = 'diautil';
                if ($index == 0) $classe = 'domingo';
                elseif ($index == 6) $classe = 'sabado';
            
                echo "<div class='cabecalho $classe'>$dia</div>";
            }

            $dia_atual = 1;
            $total_celulas = $ultimo_dia + $dia_semana_inicio;

            for ($i = 0; $i < $total_celulas; $i++) {
    if ($i < $dia_semana_inicio) {
        echo "<div class='dia_zero'></div>";
    } else {
        $data_atual = "$ano-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia_atual, 2, '0', STR_PAD_LEFT);
        
        $timestamp = strtotime($data_atual);
        $dia_semana = date('w', $timestamp); // 0=domingo ... 6=sábado
        $classe_extra = '';
        
        // Verifica se é sábado, domingo ou feriado
        if ($dia_semana == 0) {
            $classe_extra = 'domingo';
        } elseif ($dia_semana == 6) {
            $classe_extra = 'sabado';
        }
        
        $data_formatada = date('d-m', $timestamp);
        if (in_array($data_formatada, $feriados)) {
            $classe_extra = 'feriado';
        }else if($data_formatada == date('d-m', strtotime($hoje))){
            $classe_extra = 'hoje';
        }
        
        echo "<div class='dia {$classe_extra}' style='cursor: pointer;'>";


        echo "<a href='home.php?data=$data_atual' style='display: block; color: inherit; text-decoration: none; height: 100%; width: 100%;'>"; // Tornando o link da data inteira clicável
        echo "<div class='numero'>$dia_atual</div>";  // Número do dia
        $query = $conexao->query("SELECT doc_nome, atendimento_hora FROM reservas_atendimento WHERE atendimento_dia = '{$data_atual}' AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento') AND status_sessao = 'Confirmada' ORDER BY atendimento_hora ASC");

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $hora = substr($row['atendimento_hora'], 0, 5);
            echo "<span class='evento'>{$hora}h - {$row['doc_nome']}</span>";
        }

        echo "</a>";  // Fechando o link para tornar toda a área clicável
        echo "</div>";
        $dia_atual++;
    }
}
            ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
