<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Cadastrar Finalização</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="../reservas_php.php" method="POST" id="form-finalizacao">
        <div class="card">
            <div class="card-top">
                <h2>Confirmar a Finalização</h2>
            </div>
<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);
$id_job = 'Finalizada';

$query = $conexao->prepare("SELECT * FROM consultas WHERE id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
?>
            <div class="card-group">
            <label>Nome</label>
            <input type="text" minlength="8" maxlength="30" name="doc_nome" value="<?php echo $doc_nome ?>" required>
            <label>Data Atendimento</label>
            <input value="<?php echo $atendimento_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
            <label>Atendimento Hora</label>
            <input value="<?php echo date('H:i', $atendimento_hora) ?>" type="time" name="atendimento_hora" required>
            <label>E-mail</label>
            <input minlength="10" maxlength="35" type="email" name="doc_email" value="<?php echo $doc_email ?>" required>
            <input type="hidden" name="status_consulta" value="<?php echo $id_job ?>">
            <input type="hidden" name="doc_telefone" value="<?php echo $doc_telefone?>">
            <input type="hidden" name="id_consulta" value="<?php echo $id_consulta?>">
            <input type="hidden" name="feitapor" value="Painel">
            <input type="hidden" name="enviar_mensagem" id="enviar_mensagem" value="nao">
            <div class="card-group-green btn"><button type="submit">Finalizar</button></div>

            </div>
<?php
}
?>
        </div>
    </form>
    <script>
document.getElementById("form-finalizacao").addEventListener("submit", function(event) {
    event.preventDefault(); // Impede envio automático

    Swal.fire({
        title: "Deseja enviar mensagem de finalização?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Sim, enviar",
        cancelButtonText: "Não enviar"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("enviar_mensagem").value = "sim";
        } else {
            document.getElementById("enviar_mensagem").value = "nao";
        }

        Swal.fire({
            icon: 'info',
            title: 'Finalizando...',
            text: 'Aguarde enquanto finalizamos a consulta.',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Envia o formulário manualmente após decisão
        event.target.submit();
    });
});
</script>
</body>
</html>
<?php } ?>