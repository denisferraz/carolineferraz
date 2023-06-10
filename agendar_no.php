<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$doc_nome = recuperarNomeToken();
$email = recuperarEmailToken();

$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$email}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $doc_cpf = $select['unico'];
    $doc_email= $select['email'];
    $doc_telefone = $select['telefone'];
}

$token = md5(date("YmdHismm"));

$id = explode('.', base64_decode(mysqli_real_escape_string($conn_msqli, $_GET['id'])));

$id_job = $id[0];
$typeerror = $id[1];
$atendimento_dia =  $id[2];
$confirmacao = $id[3];

if($id_job == 'Nova Sessão'){
$status_reserva = 'Em Andamento';
}else{
$status_reserva = 'Confirmada';
}

if($typeerror == '1'){
    $typeerror = 'não funcionamos nesta data.';   
}else if($typeerror == '2'){
    $typeerror = 'não é possivel agendar para este dia/horario.';   
}else if($typeerror == '3'){
    $typeerror = 'não temos disponibilidade para este horario.';   
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style_home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <main>
        <section class="home">
            <div class="home-text">
                <h4 class="text-h4">Desculpa <?php echo $doc_nome ?>, mas <?php echo $typeerror ?> Veja abaixo datas/horarios proximos</h4>
                
                <style type="text/css">
                                table {margin-right: 10px; margin-left: 10px; border: 0px; border-spacing: 3px; border-collapse: separate;}
                                table td{border: 2px solid orangered; text-align: center; padding: 5px; border-radius: 10px;}
                                </style>
                                <table border="1px"><tr>
                                <?php

$limite_dia = $config_limitedia;
$atendimento_hora_comeco =  $config_atendimento_hora_comeco;
$atendimento_hora_fim =  $config_atendimento_hora_fim;
$atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
$reserva_horas_qtd = (strtotime("$atendimento_hora_fim") - strtotime("$atendimento_hora_comeco")) / 3600;
$dias = 0;

$dia_segunda = $config_dia_segunda; //1
$dia_terca = $config_dia_terca; //2
$dia_quarta = $config_dia_quarta; //3
$dia_quinta = $config_dia_quinta; //4
$dia_sexta = $config_dia_sexta; //5
$dia_sabado = $config_dia_sabado; //6
$dia_domingo = $config_dia_domingo; //0

$reserva_dias = 5;
$atendimento_dias = date('Y-m-d', strtotime("$atendimento_dia") - (86400 * 3));

if($atendimento_dias <= $hoje){
$atendimento_dias = date('Y-m-d', strtotime("$hoje") + 86400);
}

while($dias < $reserva_dias){

    if( (date('w', strtotime("$atendimento_dias")) == 1) && $dia_segunda == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 2) && $dia_terca == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 3) && $dia_quarta == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 4) && $dia_quinta == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 5) && $dia_sexta == -1){
        $atendimento_dias= date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 6) && $dia_sabado == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 0) && $dia_domingo == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
    ?>
    <td bgcolor="#000000"><font color="white">
    <?php echo date("d/m", strtotime("$atendimento_dias")); ?><br>
    <?php echo date("D", strtotime("$atendimento_dias")); ?>
    </font></td>
    <?php

    $dias++;
    $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400);
}
?>
    </tr><tr>
<?php
$dias = 0;
$reserva_horas = $reserva_dias * $reserva_horas_qtd * (60 / $config_atendimento_hora_intervalo) + ($reserva_dias * (60 / $config_atendimento_hora_intervalo));
$atendimento_horas = date('H:i:s', strtotime("$atendimento_hora_comeco") - $atendimento_hora_intervalo);

$atendimento_dias = date('Y-m-d', strtotime("$atendimento_dia") - (86400 * 3));
if($atendimento_dias <= $hoje){
$atendimento_dias = date('Y-m-d', strtotime("$hoje") + 86400);
}
while($dias < $reserva_horas){


    if( $dias % $reserva_dias == 0 ){
        $atendimento_dia = date("Y-m-d", strtotime("$atendimento_dias"));
        if( (date('w', strtotime("$atendimento_dia")) == 1) && $dia_segunda == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 2) && $dia_terca == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 3) && $dia_quarta == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 4) && $dia_quinta == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 5) && $dia_sexta == -1){
            $atendimento_dia= date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 6) && $dia_sabado == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 0) && $dia_domingo == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
        $atendimento_horas = date('H:i:s', strtotime("$atendimento_horas") + $atendimento_hora_intervalo);
            ?> <tr> <?php 
        }

    $check_disponibilidade = $conexao->query("SELECT * FROM $tabela_disponibilidade WHERE atendimento_dia = '{$atendimento_dia}' AND atendimento_hora = '{$atendimento_horas}'");
    while($select = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){
    }
    $total_reservas = $check_disponibilidade->rowCount();

    if( (strtotime("$atendimento_horas") ) <= strtotime("$atendimento_hora_fim") ){

    if($total_reservas >= $limite_dia){
        $total = 'Closed';
        ?> <td bgcolor="#000000"><font color="grey"> <?php
    }else if( ($limite_dia - $total_reservas) <= ( $limite_dia / 4 ) ){
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#A0522D"> <?php
    }else if( ($limite_dia - $total_reservas) <= ( $limite_dia / 2 ) ){
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#DAA520"> <?php
    }else{
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#32CD32"> <?php
    }

?>
<b>
<?php echo date("H:i", strtotime("$atendimento_horas")); ?>h</font>
<br>
<?php 
if(is_numeric($total) || $total == 'Closed'){
}else{
    ?>-<?php
}
?>
</b></td>

<?php
}
?>

<?php
    $dias++;
    $atendimento_horas = date("H:i:s", strtotime("$atendimento_horas"));
    $atendimento_dia = date("Y-m-d", strtotime("$atendimento_dia") + 86400);

    if( (date('w', strtotime("$atendimento_dia")) == 1) && $dia_segunda == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 2) && $dia_terca == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 3) && $dia_quarta == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 4) && $dia_quinta == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 5) && $dia_sexta == -1){
        $atendimento_dia= date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 6) && $dia_sabado == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 0) && $dia_domingo == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
}
?>
</tr></table>
                

            </div>
            <div class="home-text">
                <h4 class="text-h4">Escolha uma nova data</h4>
                <p><b>Nossos Horários</b><br>
                Segunda a Sexta: <b>14h as 18h</b><br>
                Sabado: <b>08h as 18h</b>
                </p>
                <form action="reservas_php.php" method="post" onsubmit="exibirPopup()">
                            <label>Dia do Atendimento</label>
                            <input min="<?php echo $min_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
                            <br><br>
                            <label>Hora do Atendimento</label>
                            <select name="atendimento_hora">
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
                            </select>
                            <br><br>
                            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
                            <input type="hidden" name="doc_cpf" value="<?php echo $doc_cpf ?>">
                            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
                            <input type="hidden" name="doc_telefone" value="<?php echo $doc_telefone ?>">
                            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                            <input type="hidden" name="token" value="<?php echo $token ?>">
                            <input type="hidden" name="status_reserva" value="<?php echo $status_reserva ?>">
                            <input type="hidden" name="feitapor" value="Site">
                            <input type="hidden" name="id_job" value="<?php echo $id_job ?>">
                            <button class="home-btn" type="submit">Confirmar</button>
                            </form>

            </div>
        </section>
    </main>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto confirmamos a sua Consulta!',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>
</body>
</html>