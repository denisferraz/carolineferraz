<?php
require('../config/database.php');
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

$feriados = array_merge(
    $feriadosFixos,
    feriadosMoveis($ano),
    $feriadosBahia,
    $feriadosSalvador
);
 
// Calcular mês anterior e próximo com ajuste de ano
$mesAnterior = $mes - 1;
$anoAnterior = $ano;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anoAnterior--;
}

$mesProximo = $mes + 1;
$anoProximo = $ano;
if ($mesProximo > 12) {
    $mesProximo = 1;
    $anoProximo++;
}

if($css_path == 'css/style_escuro.css'){
$font_color = '#fff';
}else{
$font_color = '#222';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda Mensal</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
body {
  margin-top: 30px;
  margin-bottom: 200px;
}

.dia{
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
    <a href="?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>" title="Mês Anterior" style="font-size: 1.5rem;">
    <i class="bi bi-arrow-left-square-fill"></i></a>
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
        <a href="?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>" title="Próximo Mês" style="font-size: 1.5rem;">
        <i class="bi bi-arrow-right-square-fill"></i></a>
    </div>
    <div style="display: flex; justify-content: center;">
  <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin: 1rem 0; border-radius: 4px; padding: 5px; justify-content: center; max-width: 800px;">
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='passado' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Dias Passados</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='hoje' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Hoje</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='futuro' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Dias Futuros</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='sabado' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Sábado</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='domingo' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Domingo</span>
    </div>
    <div style="display: flex; align-items: center; gap: 0.5rem;">
      <div class='feriado' style="width: 20px; height: 20px; border-radius: 4px;"></div>
      <span style="color: <?php echo $font_color; ?>;">Feriado</span>
    </div>
  </div>
</div>

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
        
        $query = $conexao->query("SELECT local_consulta, atendimento_hora FROM consultas WHERE doc_email = '{$_SESSION['email']}' AND atendimento_dia = '{$data_atual}' ORDER BY atendimento_hora ASC");
        if($query->rowCount() > 0){
            //Veriica se é passado, presente, futuro
            $hoje = date('Y-m-d');
            if ($data_atual < $hoje) {
                $classe_extra = 'passado';
            } else if ($data_atual > $hoje){
                $classe_extra = 'futuro';
            }
        }
        echo "<div class='dia {$classe_extra}' style='cursor: pointer;'>";
        echo "<div class='numero'>$dia_atual</div>";  // Número do dia
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $hora = substr($row['atendimento_hora'], 0, 5);
            echo "<span class='evento'>{$hora}h - {$row['local_consulta']}</span>";
        }


        echo "</div>";
        $dia_atual++;
    }
}
            ?>
        </div>
</div><script>
    let startX = 0;
    let endX = 0;

    document.addEventListener("touchstart", function(e) {
        startX = e.changedTouches[0].screenX;
    });

    document.addEventListener("touchend", function(e) {
        endX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const diffX = endX - startX;
        const threshold = 50;

        if (Math.abs(diffX) > threshold) {
            const params = new URLSearchParams(window.location.search);
            let mes = parseInt(params.get('mes')) || (new Date().getMonth() + 1);
            let ano = parseInt(params.get('ano')) || (new Date().getFullYear());

            if (diffX > 0) {
                mes--;
                if (mes < 1) {
                    mes = 12;
                    ano--;
                }
            } else {
                mes++;
                if (mes > 12) {
                    mes = 1;
                    ano++;
                }
            }

            window.location.href = `?mes=${mes}&ano=${ano}`;
        }
    }
</script>


</body>
</html>
