<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

// CONFIGURAÇÕES
$mesSelecionado = $_GET['mes'] ?? date('m');
$anoSelecionado = $_GET['ano'] ?? date('Y');

$atendimento_hora_comeco = $config_atendimento_hora_comeco;
$atendimento_hora_fim = $config_atendimento_hora_fim;
$atendimento_hora_intervalo = $config_atendimento_hora_intervalo; // em minutos

$dia_segunda = $config_dia_segunda; //1
$dia_terca = $config_dia_terca; //2
$dia_quarta = $config_dia_quarta; //3
$dia_quinta = $config_dia_quinta; //4
$dia_sexta = $config_dia_sexta; //5
$dia_sabado = $config_dia_sabado; //6
$dia_domingo = $config_dia_domingo; //0

if ($config_dia_segunda == 1) {
    $dia_segunda = true;
} else {
    $dia_segunda = false;
}

if ($config_dia_terca == 2) {
    $dia_terca = true;
} else {
    $dia_terca = false;
}

if ($config_dia_quarta == 3) {
    $dia_quarta = true;
} else {
    $dia_quarta = false;
}

if ($config_dia_quinta == 4) {
    $dia_quinta = true;
} else {
    $dia_quinta = false;
}

if ($config_dia_sexta == 5) {
    $dia_sexta = true;
} else {
    $dia_sexta = false;
}

if ($config_dia_sabado == 6) {
    $dia_sabado = true;
} else {
    $dia_sabado = false;
}

if ($config_dia_domingo == 0) {
    $dia_domingo = true;
} else {
    $dia_domingo = false;
}

$diasPermitidos = [
    0 => $dia_domingo,  // Domingo
    1 => $dia_segunda,  // Segunda
    2 => $dia_terca,    // Terça
    3 => $dia_quarta,   // Quarta
    4 => $dia_quinta,   // Quinta
    5 => $dia_sexta,    // Sexta
    6 => $dia_sabado,   // Sábado
];

// GERAÇÃO DAS DISPONIBILIDADES
$disponibilidades = [];
$numeroDias = cal_days_in_month(CAL_GREGORIAN, $mesSelecionado, $anoSelecionado);

for ($dia = 1; $dia <= $numeroDias; $dia++) {
    $dataAtual = date('Y-m-d', mktime(0, 0, 0, $mesSelecionado, $dia, $anoSelecionado));
    $diaSemana = date('w', strtotime($dataAtual));

    if (empty($diasPermitidos[$diaSemana])) continue;

    $horario = strtotime($atendimento_hora_comeco);
    $fim = strtotime($atendimento_hora_fim);
    $intervalo = $atendimento_hora_intervalo * 60;

    while ($horario < $fim) {
        // Formato H:i:s ou H:i, dependendo de como está no banco
        $atendimento_horas = date('H:i:s', $horario);

        // Query de verificação
        $check_disponibilidade = $conexao->query(
            "SELECT * FROM $tabela_disponibilidade 
             WHERE atendimento_dia = '{$dataAtual}' 
             AND atendimento_hora = '{$atendimento_horas}'"
        );

        if ($check_disponibilidade->rowCount() == 0) {
            $disponibilidades[$dataAtual][] = date('H:i', $horario); // ou H:i:s
        }

        $horario += $intervalo;
    }
}

?>

<style>
  body {
    padding: 10px;
    margin-top: 20px;
    background: #121212;
    color: #ccc;
  }
  .calendario {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    padding: 1rem;
  }
  .dia, .cabecalho {
    padding: 10px;
    text-align: center;
    border-radius: 8px;
  }
  .cabecalho {
    font-weight: bold;
    background: #1f1f1f;
  }
  .dia {
    background: #1e1e1e;
    cursor: pointer;
  }
  .dia:hover {
    background: #333;
  }
  .com-horarios {
    border: 2px solid #4caf50;
  }
  .sem-horarios {
    opacity: 0.4;
  }
  .selecionado {
    background: #4caf50 !important;
    color: #000;
  }
  .seletor-mes {
    margin-bottom: 1rem;
  }
  .horarios-do-dia {
    margin-top: 1rem;
    background: #1a1a1a;
    padding: 1rem;
    border-radius: 8px;
  }
</style>

<form method="GET" class="seletor-mes">
  <select name="mes">
    <?php for ($m = 1; $m <= 12; $m++): ?>
      <option value="<?= $m ?>" <?= $m == $mesSelecionado ? 'selected' : '' ?>>
        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
      </option>
    <?php endfor; ?>
  </select>

  <select name="ano">
    <?php for ($a = 2023; $a <= 2026; $a++): ?>
      <option value="<?= $a ?>" <?= $a == $anoSelecionado ? 'selected' : '' ?>><?= $a ?></option>
    <?php endfor; ?>
  </select>

  <button type="submit">Atualizar</button>
</form>

<div class="calendario">
  <?php
  $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
  foreach ($diasSemana as $dia) {
    echo "<div class='cabecalho'>{$dia}</div>";
  }

  $primeiroDiaSemana = date('w', mktime(0, 0, 0, $mesSelecionado, 1, $anoSelecionado));
  for ($i = 0; $i < $primeiroDiaSemana; $i++) {
    echo "<div></div>";
  }

  for ($dia = 1; $dia <= $numeroDias; $dia++) {
    $data = date('Y-m-d', mktime(0, 0, 0, $mesSelecionado, $dia, $anoSelecionado));
    $temHorarios = !empty($disponibilidades[$data]);
    $classe = $temHorarios ? 'com-horarios' : 'sem-horarios';
    echo "<div class='dia $classe' onclick='mostrarHorarios(\"$data\", this)'>{$dia}</div>";
  }
  ?>
</div>

<div id="horarios" class="horarios-do-dia" style="display: none;">
  <h3 id="titulo-data"></h3>
  <ul id="lista-horarios"></ul>
</div>

<script>
  const disponibilidades = <?= json_encode($disponibilidades) ?>;

  function mostrarHorarios(data, elemento) {
    const titulo = document.getElementById('titulo-data');
    const lista = document.getElementById('lista-horarios');
    const container = document.getElementById('horarios');

    const dataObj = new Date(data);
    dataObj.setDate(dataObj.getDate() + 1); // Ajuste visual para fuso
    titulo.textContent = 'Horários de ' + dataObj.toLocaleDateString('pt-BR');

    const horarios = disponibilidades[data] || [];
    lista.innerHTML = horarios.length
      ? horarios.map(h => `<li>${h}h</li>`).join('')
      : '<li>Nenhum horário disponível</li>';

    container.style.display = 'block';

    document.querySelectorAll('.dia').forEach(el => el.classList.remove('selecionado'));
    if (elemento) {
      elemento.classList.add('selecionado');
    }
  }
</script>
