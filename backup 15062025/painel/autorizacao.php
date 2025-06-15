<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$hoje = date('Y-m-d');
$proximos_dias_amanha = date('Y-m-d', strtotime("$hoje") + (86400 * 1 ));
$proximos_dias = date('Y-m-d', strtotime("$hoje") + (86400 * 7 ));

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


</head>
<body>

<div class="container">

<div class="appointment-list">
    
    <!-- Botão Voltar -->
    <a href="agenda.php"><button class="voltar">Voltar</button></a><br>

<!-- Solicitações Pendentes -->
<fieldset>
<?php
    $query_alteracao = $conexao->query("SELECT * FROM alteracoes WHERE token_emp = '{$_SESSION['token_emp']}' AND alt_status = 'Pendente'");
    $alteracao_qtd = $query_alteracao->rowCount();

if($alteracao_qtd > 0){
  echo "<legend>Solicitações pendentes [ {$alteracao_qtd} ]</legend>";
?>
<?php
while($select_alteracao = $query_alteracao->fetch(PDO::FETCH_ASSOC)){
    $token = $select_alteracao['token'];
    $atendimento_dia = $select_alteracao['atendimento_dia'];
    $atendimento_hora = $select_alteracao['atendimento_hora'];
    $atendimento_dia_anterior = $select_alteracao['atendimento_dia_anterior'];
    $atendimento_hora_anterior = $select_alteracao['atendimento_hora_anterior'];

    $query_alteracao_reserva = $conexao->query("SELECT * FROM consultas WHERE token = '{$token}'");
    while($select_alteracao_reserva = $query_alteracao_reserva->fetch(PDO::FETCH_ASSOC)){
    $confirmacao = $select_alteracao_reserva['confirmacao'];
    $doc_nome = $select_alteracao_reserva['doc_nome'];
    $doc_email = $select_alteracao_reserva['doc_email'];
    }
    ?>
<div class="appointment">
    <a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $doc_nome ?></button></a>
    <span class="name">deseja alterar de:</span> <span class="time"><?php echo date('d/m/Y', strtotime("$atendimento_dia_anterior")) ?> [ <?php echo date('H:i\h', strtotime("$atendimento_hora_anterior")) ?> ]</span>
    <span class="name">para:</span> <span class="time"><?php echo date('d/m/Y', strtotime("$atendimento_dia")) ?> [ <?php echo date('H:i\h', strtotime("$atendimento_hora")) ?> ]</span>
    <a href="javascript:void(0)" onclick="AlteracaoAceitar()"><button>Aceitar</button></a>
    <a href="javascript:void(0)" onclick="AlteracaoRecusar()"><button>Recusar</button></a>
</div>
<?php } ?>

<?php }else{
      echo "<legend>Sem Solicitações pendentes</legend>";
} ?>
</fieldset>
</div>

</div>

<script>
  function AlteracaoRecusar() {
    // Exibe o popup de carregamento
    exibirPopup();

    // Abre a página lembrete.php em uma nova janela ou iframe
    window.open("reservas_solicitacao.php?alt_status=Recusada&token=<?php echo $token ?>", "iframe-home");
  }

  function AlteracaoAceitar() {
    // Exibe o popup de carregamento
    exibirPopup();

    // Abre a página lembrete.php em uma nova janela ou iframe
    window.open("reservas_solicitacao.php?alt_status=Aceita&token=<?php echo $token ?>", "iframe-home");
  }

  function exibirPopup() {
    Swal.fire({
      icon: 'warning',
      title: 'Carregando...',
      text: 'Aguarde enquanto enviamos sua resposta!',
      timer: 10000,
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

<?php
}
?>