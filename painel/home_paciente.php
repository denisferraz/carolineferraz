<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$dataSelecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Pega a data passada via GET

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $_SESSION['email']));
$painel_users_array = [];
        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $dados_painel_users = $select['dados_painel_users'];
            $id = $select['id'];
    
        // Para descriptografar os dados
        $dados = base64_decode($dados_painel_users);
        $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
        $dados_array = explode(';', $dados_decifrados);
    
        $painel_users_array[] = [
            'id' => $id,
            'email' => $select['email'],
            'nome' => $dados_array[0],
            'rg' => $dados_array[1],
            'cpf' => $dados_array[2],
            'telefone' => $dados_array[3],
            'profissao' => $dados_array[4],
            'nascimento' => $dados_array[5],
            'cep' => $dados_array[6],
            'rua' => $dados_array[7],
            'numero' => $dados_array[8],
            'cidade' => $dados_array[9],
            'bairro' => $dados_array[10],
            'estado' => $dados_array[11]
        ];
    
        }  
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href="<?php echo $css_path ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<div class="container">
    <div class="appointment-list">
        
        <!-- Botão Voltar -->
    <a href="agenda_paciente.php"><button class="voltar">Voltar</button></a><br>
    
        <?php
        // Busca os atendimentos para o dia selecionado
        $query_checkin = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = '{$_SESSION['email']}' AND atendimento_dia = '{$dataSelecionada}' ORDER BY atendimento_dia, atendimento_hora ASC");
        $checkin_qtd = $query_checkin->rowCount();
        ?>

        <fieldset>
        <?php
        
        $dataSelecionada_formatada = date('d/m/Y', strtotime($dataSelecionada));
        
        if ($checkin_qtd == 0) {
            echo "<legend>Sem Atendimentos para o dia $dataSelecionada_formatada</legend>";
        } else {
            echo "<legend>Seus Atendimentos do dia $dataSelecionada_formatada</legend>";

        }

        while ($select_checkins = $query_checkin->fetch(PDO::FETCH_ASSOC)) {
            $doc_email = $select_checkins['doc_email'];
            $atendimento_dia = $select_checkins['atendimento_dia'];
            $atendimento_dia = strtotime("$atendimento_dia");
            $atendimento_hora = $select_checkins['atendimento_hora'];
            $atendimento_hora = strtotime("$atendimento_hora");
            $local_reserva = $select_checkins['local_reserva'];
            $id = $select_checkins['id'];

            foreach ($painel_users_array as $item) {
                if ($item['email'] === $doc_email) {
                    $doc_nome = $item['nome'];
                }
            }
        ?>
            <div class="appointment">
                <button><?php echo $doc_nome ?> | <?php echo date('d/m/Y', $atendimento_dia) ?> às <?php echo date('H:i\h', $atendimento_hora) ?></button>
                <button><?php echo $local_reserva; ?></button>
                <div class="actions">
                <?php echo "<button>$status_consulta</button>"; ?>
                </div>
            </div>
        <?php } ?>
        </fieldset>
    </div>
</div>

</body>
</html>
