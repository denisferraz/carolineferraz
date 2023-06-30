<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}



$token = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
$result_check->execute(array('token' => $token));


while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_telefone2 = $select['doc_telefone'];
$doc_nome = $select['doc_nome'];
$tipo_consulta = $select['tipo_consulta'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$id_job = $select['tipo_consulta'];
}

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";
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
            <h1 class="text-h1">Atendimento Confirmado!</h1><br>
            <h4 class="text-h4">Veja abaixo os detalhes</h4><br>
            <p>
            <b>Confirmação:</b> <?php echo $confirmacao ?><br><br>
            <b>Tipo Consulta:</b> <?php echo $tipo_consulta ?><br>
            <b>Data:</b> <?php echo date('d/m/Y', strtotime("$atendimento_dia")) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', strtotime("$atendimento_hora")) ?>
            </p> 
            </div>
            <div class="home-text">
            <h4 class="text-h4">Deseja enviar este Confirmação para seu Whatsapp e/ou E-mail?</h4><br>
            <form action="reservas_php.php" method="post" onsubmit="exibirPopup()">
            <input id="whatsapp" type="checkbox" name="whatsapp" checked>
            <label for="whatsapp"><b>Whatsapp</b> <?php echo $doc_telefone ?></label><br>
            <input id="email" type="checkbox" name="email" checked>
            <label for="email"><b>E-mail</b> <?php echo $doc_email ?></label><br>
            <input type="hidden" name="atendimento_dia" value="<?php echo $atendimento_dia ?>">
            <input type="hidden" name="atendimento_hora" value="<?php echo $atendimento_hora ?>">
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="doc_telefone" value="<?php echo $doc_telefone2 ?>">
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="status_reserva" value="EnvioMensagem">
            <input type="hidden" name="feitapor" value="Site">
            <input type="hidden" name="id_job" value="<?php echo $id_job ?>">
            <button class="home-btn" type="submit">Enviar</button>
            </form>
            <br>
            </div>
        </section>
    </main>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos a sua confirmação!',
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