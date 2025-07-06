<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Fechar Agenda</title>
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
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Fechar Agenda <i class="bi bi-question-square-fill"onclick="ajudaDisponibilidadeFechar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>
            <div class="card-group">
                <label>Data Início</label>
                <input data-step="2" type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_inicio" required>

                <label>Data Fim</label>
                <input data-step="3" type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_fim" required>

                <label>Hora Início</label>
                <select data-step="4" class="form-control" name="hora_inicio">
                    <?php
                    $atendimento_hora_comeco = strtotime("$config_atendimento_hora_comeco");
                    $atendimento_hora_fim = strtotime("$config_atendimento_hora_fim");
                    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                    $rodadas = 0;
                    while($rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)) {
                        echo '<option value="'.date('H:i:s', $atendimento_hora_comeco).'">'.date('H:i', $atendimento_hora_comeco).'</option>';
                        $rodadas++;
                        $atendimento_hora_comeco += $atendimento_hora_intervalo;
                    }
                    ?>
                </select>

                <label>Hora Fim</label>
                <select data-step="5" class="form-control" name="hora_fim">
                    <?php
                    $atendimento_hora_comeco = strtotime("$config_atendimento_hora_comeco");
                    $atendimento_hora_fim = strtotime("$config_atendimento_hora_fim");
                    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                    $rodadas = 0;
                    while($rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)) {
                        echo '<option value="'.date('H:i:s', $atendimento_hora_comeco).'">'.date('H:i', $atendimento_hora_comeco).'</option>';
                        $rodadas++;
                        $atendimento_hora_comeco += $atendimento_hora_intervalo;
                    }
                    ?>
                </select>

                <div data-step="6">
                <label>Dias da Semana</label>
                <input id="dia_segunda" type="checkbox" name="dia_segunda" checked>
                <label for="dia_segunda">Segunda-Feira</label>
                <input id="dia_terca" type="checkbox" name="dia_terca" checked>
                <label for="dia_terca">Terça-Feira</label>
                <input id="dia_quarta" type="checkbox" name="dia_quarta" checked>
                <label for="dia_quarta">Quarta-Feira</label>
                <input id="dia_quinta" type="checkbox" name="dia_quinta" checked>
                <label for="dia_quinta">Quinta-Feira</label>
                <input id="dia_sexta" type="checkbox" name="dia_sexta" checked>
                <label for="dia_sexta">Sexta-Feira</label>
                <input id="dia_sabado" type="checkbox" name="dia_sabado" checked>
                <label for="dia_sabado">Sábado</label>
                <input id="dia_domingo" type="checkbox" name="dia_domingo" checked>
                <label for="dia_domingo">Domingo</label>
                </div>

                <input type="hidden" name="id_job" value="disponibilidade_fechar" />
                <input type="hidden" name="atendimento_local" value="N/A" />
                <div class="card-group btn">
                    <button data-step="7" type="submit">Fechar Agenda</button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
