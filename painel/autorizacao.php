<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');
$proximos_dias_amanha = date('Y-m-d', strtotime("$hoje") + (86400 * 1 ));
$proximos_dias = date('Y-m-d', strtotime("$hoje") + (86400 * 7 ));

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Agenda do Dia - ChronoClick</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <!-- CSS Tema Saúde -->
    <link rel='stylesheet' type='text/css' href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        .appointment-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
            transition: all 0.3s ease;
        }
        
        .appointment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .appointment-time {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-primary);
            margin-bottom: 8px;
        }
        
        .appointment-patient {
            font-size: 1rem;
            font-weight: 500;
            color: var(--health-gray-800);
            margin-bottom: 4px;
        }
        
        .appointment-treatment {
            font-size: 0.9rem;
            color: var(--health-gray-600);
            margin-bottom: 12px;
        }
        
        .appointment-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .status-agendado {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }
        
        .status-confirmado {
            background: rgba(5, 150, 105, 0.1);
            color: #047857;
        }
        
        .status-cancelado {
            background: rgba(220, 38, 38, 0.1);
            color: #b91c1c;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--health-gray-500);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 16px;
            color: var(--health-gray-400);
        }
    </style>
</head>
<body>
<!-- Lista de Consultas -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar-minus health-icon-lg"></i>
                Solicitações Pendentes <i class="bi bi-question-square-fill"onclick="ajudaAutorizacao()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 30px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Veja abaixo as solicitações pendentes de autorização ou recusa
            </p>
        </div>
        
        <div class="health-card-body">
            <a href="agenda.php" class="health-btn health-btn-outline">
                <i class="bi bi-arrow-left"></i>
                Voltar para Agenda
            </a>
        </div>
<?php
    $query_alteracao = $conexao->query("SELECT * FROM alteracoes WHERE token_emp = '{$_SESSION['token_emp']}' AND alt_status = 'Pendente'");
    $alteracao_qtd = $query_alteracao->rowCount();

if($alteracao_qtd > 0){

$stmt_painel = $conexao->prepare("SELECT dados_painel_users, id FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE ?");
$stmt_painel->execute(['%;'.$_SESSION['token_emp'].';%']);
$painel_users_array = [];
    while($select = $stmt_painel->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'email' => $select['email'],
        'nome' => $dados_array[0]
    ];
}

while($select_alteracao = $query_alteracao->fetch(PDO::FETCH_ASSOC)){
    $token = $select_alteracao['token'];
    $atendimento_dia = $select_alteracao['atendimento_dia'];
    $atendimento_hora = $select_alteracao['atendimento_hora'];
    $atendimento_dia_anterior = $select_alteracao['atendimento_dia_anterior'];
    $atendimento_hora_anterior = $select_alteracao['atendimento_hora_anterior'];
    $id_consulta = $select_alteracao['id'];
    $doc_email = $select_alteracao['doc_email'];

    foreach ($painel_users_array as $item) {
      if ($item['email'] === $doc_email) {
          $doc_nome = $item['nome'];
      }
  }

    $query_alteracao_reserva = $conexao->query("SELECT * FROM consultas WHERE token = '{$token}'");
    ?>
<div data-step="1" class="health-card-body">
    <span class="appointment-item">
    <a href="javascript:void(0)" onclick='window.open("id_consulta=<?php echo $id_consulta ?>","iframe-home")'><button class="health-btn health-btn-primary btn-mini"><?php echo $doc_nome ?></button></a>
    deseja alterar de: <b><?php echo date('d/m/Y', strtotime($atendimento_dia_anterior)) ?> as <?php echo date('H:i\h', strtotime("$atendimento_hora_anterior")) ?></b>
    para: <b><?php echo date('d/m/Y', strtotime("$atendimento_dia")) ?> as <?php echo date('H:i\h', strtotime("$atendimento_hora")) ?></b> 
    <a href="javascript:void(0)" onclick="AlteracaoAceitar()"><button class="health-btn health-btn-success btn-mini"><i class="bi bi-check-lg"></i> Aceitar</button></a>
    <a href="javascript:void(0)" onclick="AlteracaoRecusar()"><button class="health-btn health-btn-danger btn-mini"><i class="bi bi-x-lg"></i> Recusar</button></a>
    </span>
</div>
<?php   }
        }else{ ?>
      <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>Nenhuma solicitação pendente</h3>
                </div>
<?php } ?>
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
