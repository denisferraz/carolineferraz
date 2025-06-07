<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Configurações</title>
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
                <h2>Edite abaixo as Configurações da Agenda</h2>
            </div>
<div class="card-group">
    <label>Hora Inicial de Atendimento</label>
    <input type="time" name="atendimento_hora_comeco" value="<?php echo date('H:i', strtotime("$config_atendimento_hora_comeco")) ?>" required>
    <label>Hora Final de Atendimento</label>
    <input type="time" name="atendimento_hora_fim" value="<?php echo date('H:i', strtotime("$config_atendimento_hora_fim")) ?>" required>
    <label>Intervalo entre Atendimentos (em minutos)</label>
    <input type="number" min="1" max="999" name="atendimento_hora_intervalo" value="<?php echo $config_atendimento_hora_intervalo; ?>" required>
    <label>Data Maxima de Agendamento</label>
    <input type="date" name="atendimento_dia_max" value="<?php echo $config_atendimento_dia_max; ?>" required>
    <br><br>
    <br><label>Dias da Semana</label><br>
    <input id="dia_segunda" type="checkbox" name=dia_segunda <?php if($config_dia_segunda == 1){?>checked<?php } ?>>
    <label for="dia_segunda">Segunda-Feira</label>
    <br>
    <input id="dia_terca" type="checkbox" name=dia_terca <?php if($config_dia_terca == 2){?>checked<?php } ?>>
    <label for="dia_terca">Terça-Feira</label>
    <br>
    <input id="dia_quarta" type="checkbox" name=dia_quarta <?php if($config_dia_quarta == 3){?>checked<?php } ?>>
    <label for="dia_quarta">Quarta-Feira</label>
    <br>
    <input id="dia_quinta" type="checkbox" name=dia_quinta <?php if($config_dia_quinta == 4){?>checked<?php } ?>>
    <label for="dia_quinta">Quinta-Feira</label>
    <br>
    <input id="dia_sexta" type="checkbox" name=dia_sexta <?php if($config_dia_sexta == 5){?>checked<?php } ?>>
    <label for="dia_sexta">Sexta-Feira</label>
    <br>
    <input id="dia_sabado" type="checkbox" name=dia_sabado <?php if($config_dia_sabado == 6){?>checked<?php } ?>>
    <label for="dia_sabado">Sabado</label>
    <br>
    <input id="dia_domingo" type="checkbox" name=dia_domingo <?php if($config_dia_domingo == 0){?>checked<?php } ?>>
    <label for="dia_domingo">Domingo</label>
    <br>
    <input type="hidden" name="id_job" value="editar_configuracoes_agenda">
    <div class="card-group btn"><button type="submit">Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>