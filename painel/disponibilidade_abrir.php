<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');

$result_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_disponibilidade'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Abrir Disponibilidade</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Selecione as datas abaixo</h2>
            </div>

            <div class="card-group">
                <label>Data Inicio</label>
                <input type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_inicio" required>
                <label>Data Fim</label>
                <input type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_fim" required>
                <br>
            <label>Hora Inicio</label>
            <select class="form-control" name="hora_inicio">
            <?php
                        $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
                        $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){
                        ?>
                                <option value="<?php echo date('H:i:s', $atendimento_hora_comeco) ?>"><?php echo date('H:i', $atendimento_hora_comeco) ?></option>
    
                        <?php
                        $rodadas++;
                        $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
                            }

                        ?>
                            </select><br>
                            <br>
            <label>Hora Fim</label>
            <select class="form-control" name="hora_fim">
            <?php
                        $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
                        $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){
                        ?>
                                <option value="<?php echo date('H:i:s', $atendimento_hora_comeco) ?>"><?php echo date('H:i', $atendimento_hora_comeco) ?></option>
    
                        <?php
                        $rodadas++;
                        $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
                            }

                        ?>
                            </select><br>
                <br><label>Dias da Semana</label><br>
            <input id="dia_segunda" type="checkbox" name=dia_segunda checked>
            <label for="dia_segunda">Segunda-Feira</label>
            <br>
            <input id="dia_terca" type="checkbox" name=dia_terca checked>
            <label for="dia_terca">Terça-Feira</label>
            <br>
            <input id="dia_quarta" type="checkbox" name=dia_quarta checked>
            <label for="dia_quarta">Quarta-Feira</label>
            <br>
            <input id="dia_quinta" type="checkbox" name=dia_quinta checked>
            <label for="dia_quinta">Quinta-Feira</label>
            <br>
            <input id="dia_sexta" type="checkbox" name=dia_sexta checked>
            <label for="dia_sexta">Sexta-Feira</label>
            <br>
            <input id="dia_sabado" type="checkbox" name=dia_sabado checked>
            <label for="dia_sabado">Sabado</label>
            <br>
            <input id="dia_domingo" type="checkbox" name=dia_domingo checked>
            <label for="dia_domingo">Domingo</label>
            <br>
            <input type="hidden" name="id_job" value="disponibilidade_abrir" />
                <div class="card-group btn"><button type="submit">Abrir Disponibilidade</button></div>

            </div>
        </div>
    </form>

</body>
</html>


<?php
}
?>