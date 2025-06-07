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

$hoje = date('Y-m-d');

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <title>Informações Consulta</title>
</head>
<body>
<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$confirmacao_cancelamento = $select['confirmacao_cancelamento'];
$status_consulta = $select['status_consulta'];
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_cpf = $select['doc_cpf'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$data_cancelamento = $select['data_cancelamento'];
$data_cancelamento = strtotime("$data_cancelamento");
$tipo_consulta = $select['tipo_consulta'];
$local_consulta = $select['local_consulta'];
}

//Ajustar CPF
$parte1 = substr($doc_cpf, 0, 3);
$parte2 = substr($doc_cpf, 3, 3);
$parte3 = substr($doc_cpf, 6, 3);
$parte4 = substr($doc_cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $doc_email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$nome = $select['nome'];
$token_profile = $select['token'];
$origem = $select['origem'];
}

?>
<div class="card">

<!-- Dados da Consulta -->
  <fieldset>
    <legend><h2>Consulta [ <?php echo $status_consulta ?> ]</h2></legend>

    <div class="info-bloco">
      <p><strong>Origem:</strong> <?php echo $origem ?></p>
      <p><strong>Nome:</strong> <?php echo $doc_nome ?></p>
      <p><strong>CPF:</strong> <?php echo $doc_cpf ?></p>
      <p><strong>Consulta:</strong> <?php echo $tipo_consulta ?></p>
      <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?></p>
      <p><strong>Hora:</strong> <?php echo date('H:i\h', strtotime($atendimento_hora)) ?></p>
      <p><strong>Local:</strong> <?php echo $local_consulta ?></p>
      <strong>Telefone:</strong> <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $doc_telefone) ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i><?= $doc_telefone ?></a>
      <p>
        <strong>E-mail:</strong>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")' class="btn-small">
          <?php echo $doc_email ?>
        </a>
      </p>

      <?php if ($status_consulta == 'Cancelada') { ?>
        <p><strong>Data Cancelamento:</strong> <?php echo date('d/m/Y - H:i:s\h', $data_cancelamento) ?></p>
        <p><strong>Confirmação Cancelamento:</strong> <?php echo $confirmacao_cancelamento ?></p>
      <?php } ?>
    </div>
        <center>
        <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="btn-black">Alterar Sessão</a>
          <?php if ($status_consulta == 'Finalizada' || $status_consulta == 'Cancelada' || $status_consulta == 'Em Andamento') { ?>
            <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?php echo $doc_email ?>","iframe-home")' class="btn-black">Nova Sessão</a>
          <?php } else { ?>
            <a href="javascript:void(0)" onclick='window.open("reservas_confirmacao.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="btn-black">Enviar Confirmação</a>
          <?php } ?>
          <a href="javascript:void(0)" onclick='window.open("reservas_lembrete.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="btn-black">Enviar Lembrete</a>
            <br><br>
            <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="btn-red">Cancelar Sessão</a>
          <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?id_consulta=<?php echo $id_consulta ?>&id_job=Finalizada","iframe-home")' class="btn-red">Finalizar Consulta</a>
            </center>
  </fieldset>
</div>
</body>
</html>

<?php
}
?>