<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$typeerror = mysqli_real_escape_string($conn_msqli, $_GET['typeerror']);

$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token");
$result_check->execute(array('token' => $token));

while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$id_job = $select['tipo_consulta'];
}

if($typeerror == 1){
$typeerror= 'Não é possivel cancelar uma sessão com a data inferir ao dia de hoje!';
}else if($typeerror == 2){
$typeerror= 'Esta Sessão não foi encontrada ou não existe!';
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
    <link rel="stylesheet" href="css/style_home_v1.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <main>
        <section class="home">
            <div class="home-text">
            <h4 class="text-h4">Cancelamento de Sessão - <?php echo $confirmacao ?></h4><br>
            <?php
                if($typeerror == 0){
            ?>
            <form action="reservas_php.php" method="post" onsubmit="exibirPopup()">
                <input type="hidden" name="atendimento_dia" value="<?php echo $atendimento_dia ?>">
                <input type="hidden" name="atendimento_hora" value="<?php echo $atendimento_hora ?>">
                <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                <input type="hidden" name="status_reserva" value="Cancelada">
                <input type="hidden" name="feitapor" value="Site"><br>
                <input type="hidden" name="id_job" value="<?php echo $id_job ?>"><br>
                <button class="home-btn-cancelar" type="submit">Cancelar</button>
            </form>
            <?php
             }else{
                echo "$typeerror";
             }
            ?>

</div>
        </section>
    </main>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto cancelamos a sua Consulta!',
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