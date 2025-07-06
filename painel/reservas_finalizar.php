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
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Confirmar a Finalização <i class="bi bi-question-square-fill"onclick="ajudaConsultaFinalizar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>
<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);
$id_job = 'Finalizada';

$query = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select['doc_email'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
}
    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
  $doc_nome = $select_check2['nome'];
  $doc_telefone = $select_check2['telefone'];
}
?>
            <div class="card-group">
            <div data-step="2">
            <label>Nome: <?php echo $doc_nome ?></label>
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <label>E-mail: <?php echo $doc_email ?></label>
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            </div>
            <br>
            <div data-step="3">
            <label>Data Atendimento: <?php echo date('d/m/Y', strtotime($atendimento_dia)); ?></label>
            <input value="<?php echo $atendimento_dia ?>" type="hidden" name="atendimento_dia">
            <label>Atendimento Hora: <?php echo date('H:i\h', $atendimento_hora); ?></label>
            <input value="<?php echo date('H:i', $atendimento_hora) ?>" type="hidden" name="atendimento_hora">
            </div>
            <br>
            <label>Telefone</label>
            <input data-step="4" minlength="11" maxlength="18" type="text" name="doc_telefone" value="<?php echo $doc_telefone ?>" required>
            <br><br>
            <input type="hidden" name="status_consulta" value="<?php echo $id_job ?>">
            <input type="hidden" name="id_consulta" value="<?php echo $id_consulta?>">
            <input type="hidden" name="feitapor" value="Painel">
            <input type="hidden" name="enviar_mensagem" id="enviar_mensagem" value="nao">
            <div data-step="5" class="card-group-green btn"><button type="submit">Finalizar</button></div>

            </div>
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
