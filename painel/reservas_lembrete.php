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
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar2-minus"></i>
                Lembrete Consulta <i class="bi bi-question-square-fill"onclick="ajudaConsultaLembrete()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para enviar o lembrete desta consulta
            </p>
        </div>
    </div>
    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

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
<div data-step="1" class="form-section health-fade-in">
            <div data-step="2" class="form-section-title">
                <div data-step="3">
                <i class="bi bi-person-vcard"></i> <?php echo $doc_nome ?><br>
                <i class="bi bi-envelope"></i> <?php echo $doc_email ?><br>
                <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?> as <?php echo date('H:i\h', $atendimento_hora) ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="health-form-group">
                    
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <input value="<?php echo $atendimento_dia ?>" type="hidden" name="atendimento_dia">
            <input value="<?php echo date('H:i', $atendimento_hora) ?>" type="hidden" name="atendimento_hora">


            <label class="health-label">Telefone</label>
            <input class="health-input" data-step="4" minlength="11" maxlength="18" type="text" name="doc_telefone" value="<?php echo $doc_telefone ?>" required>
            <br><br>
            <input id="whatsapp" type="checkbox" name="whatsapp" checked>
            <label data-step="5" for="whatsapp">Enviar para Whatsapp</label>
            <br>
            <input id="email" type="checkbox" name="email" checked>
            <label data-step="6" for="email">Enviar para E-mail</label>
            <br><br>
            <input type="hidden" name="id_job" value="EnvioLembrete">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>">
            <div data-step="7"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Enviar Lembrete</button></div>

            </div>
        </div>
    </form>
    </div>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos o Lembrete!',
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
