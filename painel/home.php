<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$dataSelecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Pega a data passada via GET

    
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
    <a href="agenda.php"><button class="voltar">Voltar</button></a><br>
    
        <?php
        // Busca os atendimentos para o dia selecionado
        $query_checkin = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$dataSelecionada}' ORDER BY atendimento_dia, atendimento_hora ASC");
        $checkin_qtd = $query_checkin->rowCount();
        ?>

        <fieldset>
        <?php
        
        $dataSelecionada_formatada = date('d/m/Y', strtotime($dataSelecionada));
        
        if ($checkin_qtd == 0) {
            echo "<legend>Sem Atendimentos para o dia $dataSelecionada_formatada</legend>";
        } else {
                echo "<legend>Atendimentos do dia $dataSelecionada_formatada [ {$checkin_qtd} ]</legend>";

        }
        $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp");
        $query->execute(array('%;'.$_SESSION['token_emp'].';%'));
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

        while ($select_checkins = $query_checkin->fetch(PDO::FETCH_ASSOC)) {
            $id_consulta = $select_checkins['id'];
            $doc_email = $select_checkins['doc_email'];
            $atendimento_dia = $select_checkins['atendimento_dia'];
            $atendimento_dia = strtotime("$atendimento_dia");
            $atendimento_hora = $select_checkins['atendimento_hora'];
            $atendimento_hora = strtotime("$atendimento_hora");
            $local_consulta = $select_checkins['local_consulta'];
            $id = $select_checkins['id'];
            $status_consulta = $select_checkins['status_consulta'];

            foreach ($painel_users_array as $item) {
                if ($item['email'] === $doc_email) {
                    $doc_nome = $item['nome'];
                }
            }
        ?>
            <div class="appointment">
                <?php echo date('d/m/Y', $atendimento_dia) ?> às <?php echo date('H:i\h', $atendimento_hora) ?>
                [ <?php echo $local_consulta; ?> ]
                <a href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")'>
                    <button>Acessar Detalhes</button>
                </a>
                <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")'>
                    <button><?php echo $doc_nome ?></button>
                </a>
                <?php
                if($status_consulta == 'Confirmada' || $status_consulta == 'Em Andamento'){ ?>
                <div class="actions">
                    <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?id_consulta=<?php echo $id_consulta ?>&id_job=EmAndamento","iframe-home")'>
                        <button>Finalizar</button>
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")'>
                        <button>Alterar</button>
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")'>
                        <button>Cancelar</button>
                    </a>
                    <?php if ($atendimento_dia < strtotime("$hoje")) { ?>
                    <a href="javascript:void(0)" onclick='window.open("reservas_noshow.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")'>
                        <button>NoShow</button>
                    </a>
                    <?php } ?>
                </div>
                <?php }else{
                    echo "<button>$status_consulta</button>";
                } ?>
            </div>
        <?php } ?>
        </fieldset>
    </div>
</div>

</body>
</html>
