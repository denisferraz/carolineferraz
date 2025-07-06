<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$historico_data = date('Y-m-d H:i:s');

$result_check = $conexao->query("SELECT * FROM painel_users WHERE token = '{$_SESSION['token']}' AND email = '{$_SESSION['email']}'");
$painel_users_array = [];
    while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2]
    ];

}

foreach ($painel_users_array as $select_check){
$historico_quem = $select_check['nome'];
$historico_unico_usuario = $select_check['cpf'];
}

if($id_job == 'editar_configuracoes_agenda' || $id_job == 'disponibilidade_fechar' || $id_job == 'disponibilidade_abrir'){
if(isset($_POST['dia_segunda'])){
    $dia_segunda = 1;
    } else {
    $dia_segunda = -1;
    }
    if(isset($_POST['dia_terca'])){
    $dia_terca = 2;
    } else {
    $dia_terca = -1;
        }
    if(isset($_POST['dia_quarta'])){
    $dia_quarta = 3;
    } else {
    $dia_quarta = -1;
    }
    if(isset($_POST['dia_quinta'])){
    $dia_quinta = 4;
    } else {
    $dia_quinta = -1;
    }
    if(isset($_POST['dia_sexta'])){
    $dia_sexta = 5;
    } else {
    $dia_sexta = -1;
    }
    if(isset($_POST['dia_sabado'])){
    $dia_sabado = 6;
    } else {
    $dia_sabado = -1;
    }
    if(isset($_POST['dia_domingo'])){
    $dia_domingo = 0;
    } else {
    $dia_domingo = -1;
    }
}

if($id_job == 'editar_configuracoes_agenda'){

    $config_limitedia = '1';
    $atendimento_hora_comeco = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_comeco']);
    $atendimento_hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_fim']);
    $atendimento_hora_intervalo = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_intervalo']);
    $atendimento_dia_max = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_max']);

    if($dia_segunda == -1 && $dia_terca == -1 && $dia_quarta == -1 && $dia_quinta == -1 && $dia_sexta == -1 && $dia_sabado == -1 && $dia_domingo){
        echo "<script>
        alert('Pelo menos um dia precisa estar Selecionado')
        window.location.replace('config_agenda.php')
        </script>";
        exit(); 
    }

    $query = $conexao->prepare("UPDATE configuracoes SET config_limitedia = :config_limitedia, atendimento_hora_comeco = :atendimento_hora_comeco, atendimento_hora_fim = :atendimento_hora_fim, atendimento_hora_intervalo = :atendimento_hora_intervalo, atendimento_dia_max = :atendimento_dia_max, config_dia_segunda = :dia_segunda, config_dia_terca = :dia_terca, config_dia_quarta = :dia_quarta, config_dia_quinta = :dia_quinta, config_dia_sexta = :dia_sexta, config_dia_sabado = :dia_sabado, config_dia_domingo = :dia_domingo WHERE token_emp = '{$_SESSION['token_emp']}'");
    $query->execute(array('config_limitedia' => $config_limitedia, 'atendimento_hora_comeco' => $atendimento_hora_comeco, 'atendimento_hora_fim' => $atendimento_hora_fim, 'atendimento_hora_intervalo' => $atendimento_hora_intervalo, 'atendimento_dia_max' => $atendimento_dia_max, 'dia_segunda' => $dia_segunda, 'dia_terca' => $dia_terca, 'dia_quarta' => $dia_quarta, 'dia_quinta' => $dia_quinta, 'dia_sexta' => $dia_sexta, 'dia_sabado' => $dia_sabado, 'dia_domingo' => $dia_domingo));
    
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações', 'token_emp' => $_SESSION['token_emp']));   

    if($_SESSION['configuracao'] == 0){
        echo "<script>
        alert('Configurações Editadas com sucesso. Continue com sua configuração!')
        window.location.replace('config_landing.php')
        </script>";
        exit();
    }else{
        echo "<script>
        alert('Configurações Editadas com sucesso')
        window.location.replace('config_agenda.php')
        </script>";
        exit();  
    }

}else if($id_job == 'editar_configuracoes_empresa'){

    $config_empresa = mysqli_real_escape_string($conn_msqli, $_POST['config_empresa']);
    $config_email = mysqli_real_escape_string($conn_msqli, $_POST['config_email']);
    $config_telefone = mysqli_real_escape_string($conn_msqli, $_POST['config_telefone']);
    $config_cnpj = mysqli_real_escape_string($conn_msqli, $_POST['config_cnpj']);
    $config_endereco = mysqli_real_escape_string($conn_msqli, $_POST['config_endereco']);

    $query = $conexao->prepare("UPDATE configuracoes SET config_empresa = :config_empresa, config_email = :config_email, config_telefone = :config_telefone, config_cnpj = :config_cnpj, config_endereco = :config_endereco WHERE token_emp = '{$_SESSION['token_emp']}'");
    $query->execute(array('config_empresa' => $config_empresa, 'config_email' => $config_email, 'config_telefone' => $config_telefone, 'config_cnpj' => $config_cnpj, 'config_endereco' => $config_endereco));

    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações', 'token_emp' => $_SESSION['token_emp']));   

    if($_SESSION['configuracao'] == 0){
    echo "<script>
    alert('Configurações Editadas com sucesso. Continue com sua configuração!')
    window.location.replace('config_msg.php')
    </script>";
    exit();
    }else{
    echo "<script>
    alert('Configurações Editadas com sucesso')
    window.location.replace('config_empresa.php')
    </script>";
    exit();  
    }

}else if($id_job == 'editar_configuracoes_msg'){

    $msg_cancelamento = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_cancelamento']);
    $msg_confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_confirmacao']);
    $msg_finalizar = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_finalizar']);
    $msg_lembrete = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_lembrete']);
    $msg_aniversario = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_aniversario']);
    $envio_whatsapp = mysqli_real_escape_string($conn_msqli, $_POST['envio_whatsapp']);
    $envio_email = mysqli_real_escape_string($conn_msqli, $_POST['envio_email']);
    $lembrete_auto_time = mysqli_real_escape_string($conn_msqli, $_POST['lembrete_hora']);

    if(isset($_POST['is_segunda'])){
    $is_segunda = 1;
    } else {
    $is_segunda = 0;
    }
    if(isset($_POST['is_terca'])){
    $is_terca = 1;
    } else {
    $is_terca = 0;
    }
    if(isset($_POST['is_quarta'])){
    $is_quarta = 1;
    } else {
    $is_quarta = 0;
    }
    if(isset($_POST['is_quinta'])){
    $is_quinta = 1;
    } else {
    $is_quinta = 0;
    }
    if(isset($_POST['is_sexta'])){
    $is_sexta = 1;
    } else {
    $is_sexta = 0;
    }
    if(isset($_POST['is_sabado'])){
    $is_sabado = 1;
    } else {
    $is_sabado = 0;
    }
    if(isset($_POST['is_domingo'])){
    $is_domingo = 1;
    } else {
    $is_domingo = 0;
    }

    $query = $conexao->prepare("UPDATE configuracoes SET config_msg_confirmacao = :config_msg_confirmacao, config_msg_cancelamento = :config_msg_cancelamento, config_msg_finalizar = :config_msg_finalizar, config_msg_lembrete = :config_msg_lembrete, config_msg_aniversario = :config_msg_aniversario, envio_whatsapp = :envio_whatsapp, envio_email = :envio_email, is_segunda = :is_segunda, is_terca = :is_terca, is_quarta = :is_quarta, is_quinta = :is_quinta, is_sexta = :is_sexta, is_sabado = :is_sabado, is_domingo = :is_domingo, lembrete_auto_time = :lembrete_auto_time WHERE token_emp = '{$_SESSION['token_emp']}'");
    $query->execute(array('config_msg_confirmacao' => $msg_confirmacao, 'config_msg_cancelamento' => $msg_cancelamento, 'config_msg_finalizar' => $msg_finalizar, 'config_msg_lembrete' => $msg_lembrete, 'config_msg_aniversario' => $msg_aniversario, 'envio_whatsapp' => $envio_whatsapp, 'envio_email' => $envio_email, 'is_segunda' => $is_segunda, 'is_terca' => $is_terca, 'is_quarta' => $is_quarta, 'is_quinta' => $is_quinta, 'is_sexta' => $is_sexta, 'is_sabado' => $is_sabado, 'is_domingo' => $is_domingo, 'lembrete_auto_time' => $lembrete_auto_time));
        
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações', 'token_emp' => $_SESSION['token_emp']));   

    if($_SESSION['configuracao'] == 0){
        echo "<script>
        alert('Configurações Editadas com sucesso. Continue com sua configuração!')
        window.location.replace('config_agenda.php')
        </script>";
        exit();
        }else{
        echo "<script>
        alert('Configurações Editadas com sucesso')
        window.location.replace('config_msg.php')
        </script>";
        exit();  
        }

}else if($id_job == 'editar_configuracoes_landing'){

    $config_nome = mysqli_real_escape_string($conn_msqli, $_POST['config_nome']);
    $config_landing = mysqli_real_escape_string($conn_msqli, $_POST['config_landing']);
    $config_especialidade = mysqli_real_escape_string($conn_msqli, $_POST['config_especialidade']);
    $config_descricao = mysqli_real_escape_string($conn_msqli, $_POST['config_descricao']);
    $config_instagram = mysqli_real_escape_string($conn_msqli, $_POST['config_instagram']);
    $config_facebook = mysqli_real_escape_string($conn_msqli, $_POST['config_facebook']);
    $config_email = mysqli_real_escape_string($conn_msqli, $_POST['config_email']);
    $config_telefone = mysqli_real_escape_string($conn_msqli, $_POST['config_telefone']);
    $config_endereco = mysqli_real_escape_string($conn_msqli, $_POST['config_endereco']);
    $token_emp = mysqli_real_escape_string($conn_msqli, $_POST['token_emp']);

    $verifica = $conexao->prepare("SELECT COUNT(*) FROM profissionais WHERE id = :id AND token_emp != :token_emp");
    $verifica->execute(array('id' => $config_landing, 'token_emp' => $token_emp));
    $existe = $verifica->fetchColumn();

    if ($existe > 0) {
        echo "<script>
            alert('Já existe um profissional com esse nome de página externa!');
            window.location.replace('config_landing.php');
        </script>";
        exit;
    }

    if(isset($_POST['landing'])){
    $is_landing = 1;
    }else{
    $is_landing = 0;
    }

    if(isset($_POST['agendamento_externo'])){
    $is_externo = 1;
    }else{
    $is_externo = 0;
    }

    if(isset($_POST['painel_externo'])){
    $is_painel = 1;
    }else{
    $is_painel = 0;
    }

    $query = $conexao->prepare("UPDATE profissionais SET id = :id, nome = :nome, especialidade = :especialidade, descricao = :descricao, email = :email, whatsapp = :whatsapp, instagram = :instagram, facebook = :facebook, endereco = :endereco, is_landing = :is_landing, is_externo = :is_externo, is_painel = :is_painel WHERE token_emp = :token_emp");
    $query->execute(array('id' => $config_landing, 'nome' => $config_nome, 'especialidade' => $config_especialidade, 'descricao' => $config_descricao, 'email' => $config_email, 'whatsapp' => $config_telefone, 'instagram' => $config_instagram, 'facebook' => $config_facebook, 'endereco' => $config_endereco, 'is_landing' => $is_landing, 'is_externo' => $is_externo, 'is_painel' => $is_painel, 'token_emp' => $token_emp));
        
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, '{$_SESSION['token']}')");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações'));   

    $query = $conexao->prepare("UPDATE painel_users SET configuracao = :configuracao WHERE token = :token_emp AND tipo != 'Paciente'");
    $query->execute(array('configuracao' => '3', 'token_emp' => $token_emp));

    $nomeArquivo = $_FILES['config_foto']['name'];
    $caminhoTemporario = $_FILES['config_foto']['tmp_name'];
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

    // Verifica se é JPG
    if (isset($_FILES['config_foto']) && $_FILES['config_foto']['error'] !== UPLOAD_ERR_NO_FILE) {

        $nomeArquivo = $_FILES['config_foto']['name'];
        $caminhoTemporario = $_FILES['config_foto']['tmp_name'];
        $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
    
        if ($extensao === 'jpg' || $extensao === 'jpeg') {
            $novoNome = $token_emp . '.jpg';
            $destino = '../profissionais/fotos/' . $novoNome;
    
            if (move_uploaded_file($caminhoTemporario, $destino)) {
                // Arquivo movido com sucesso
            } else {
                echo "Erro ao mover o arquivo.";
                exit;
            }
        } else {
            echo "Apenas arquivos JPG são permitidos.";
            exit;
        }
    }
    
    // Depois de tudo, continue normalmente:
    if($_SESSION['configuracao'] == 0){
        $_SESSION['configuracao'] = 1;
        echo "<script>
        alert('Configurações Editadas com sucesso!')
        window.top.location.replace('painel.php')
        </script>";
        exit();
    }else{
        echo "<script>
        alert('Configurações Editadas com sucesso')
        window.location.replace('config_landing.php')
        </script>";
        exit();  
    }   

}else if($id_job == 'disponibilidade_fechar'){

    $fechar_inicio = mysqli_real_escape_string($conn_msqli, $_POST['fechar_inicio']);
    $fechar_fim = mysqli_real_escape_string($conn_msqli, $_POST['fechar_fim']);
    $hora_inicio = mysqli_real_escape_string($conn_msqli, $_POST['hora_inicio']);
    $hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['hora_fim']);

    $close = 0;
    $close_dias = $fechar_inicio;
    $fechar_inicio_str = strtotime("$fechar_inicio");
    $fechar_fim_str = strtotime("$fechar_fim");
    $reserva_close = ($fechar_fim_str - $fechar_inicio_str) / 86400;
    $close_hora = 0;
    $close_horas = $hora_inicio;
    $hora_inicio_str = strtotime("$hora_inicio");
    $hora_fim_str = strtotime("$hora_fim");
    $hora_close = ($hora_fim_str - $hora_inicio_str) / ($config_atendimento_hora_intervalo * 60);

while($close <= $reserva_close){

    if(( (date('w', strtotime("$close_dias")) == 1) && $dia_segunda == -1) || ( (date('w', strtotime("$close_dias")) == 2) && $dia_terca == -1) || ( (date('w', strtotime("$close_dias")) == 3) && $dia_quarta == -1) || ( (date('w', strtotime("$close_dias")) == 4) && $dia_quinta == -1) || ( (date('w', strtotime("$close_dias")) == 5) && $dia_sexta == -1) || ( (date('w', strtotime("$close_dias")) == 6) && $dia_sabado == -1) || ( (date('w', strtotime("$close_dias")) == 0) && $dia_domingo == -1)){
    }else{

    //Hora Inicio
    while($close_hora <= $hora_close){

    $query = $conexao->query("INSERT INTO disponibilidade (atendimento_dia, atendimento_hora, token_emp) VALUES ('{$close_dias}', '{$close_horas}', '{$_SESSION['token_emp']}')");

    $close_hora++;
    $close_horas = date('H:i:s', strtotime("$close_horas") + ($config_atendimento_hora_intervalo * 60));
    }}
    //Hora Fim

    $close_hora = 0;
    $close_horas = $hora_inicio;
    $close++;
    $close_dias = date('Y-m-d', strtotime("$close_dias") + 86400);

}

$fechar_inicio = date('d/m/Y', strtotime($fechar_inicio));
$fechar_fim = date('d/m/Y', strtotime($fechar_fim));

    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Fechou disponibilidade entre as datas $fechar_inicio e $fechar_fim"));

echo "<script>
    alert('Disponibilidade entre os dias $fechar_inicio e $fechar_fim fechadas')
    window.location.replace('disponibilidade_fechar.php')
    </script>";

}else if($id_job == 'disponibilidade_abrir'){

    $fechar_inicio = mysqli_real_escape_string($conn_msqli, $_POST['fechar_inicio']);
    $fechar_fim = mysqli_real_escape_string($conn_msqli, $_POST['fechar_fim']);
    $hora_inicio = mysqli_real_escape_string($conn_msqli, $_POST['hora_inicio']);
    $hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['hora_fim']);

    $close = 0;
    $close_dias = $fechar_inicio;
    $fechar_inicio_str = strtotime("$fechar_inicio");
    $fechar_fim_str = strtotime("$fechar_fim");
    $reserva_close = ($fechar_fim_str - $fechar_inicio_str) / 86400;
    $close_hora = 0;
    $close_horas = $hora_inicio;
    $hora_inicio_str = strtotime("$hora_inicio");
    $hora_fim_str = strtotime("$hora_fim");
    $hora_close = ($hora_fim_str - $hora_inicio_str) / ($config_atendimento_hora_intervalo * 60);

while($close <= $reserva_close){

    if(( (date('w', strtotime("$close_dias")) == 1) && $dia_segunda == -1) || ( (date('w', strtotime("$close_dias")) == 2) && $dia_terca == -1) || ( (date('w', strtotime("$close_dias")) == 3) && $dia_quarta == -1) || ( (date('w', strtotime("$close_dias")) == 4) && $dia_quinta == -1) || ( (date('w', strtotime("$close_dias")) == 5) && $dia_sexta == -1) || ( (date('w', strtotime("$close_dias")) == 6) && $dia_sabado == -1) || ( (date('w', strtotime("$close_dias")) == 0) && $dia_domingo == -1)){
    }else{

    //Hora Inicio
    while($close_hora <= $hora_close){

    $query = $conexao->query("DELETE FROM disponibilidade WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$close_dias}' AND atendimento_hora = '{$close_horas}'");

    $close_hora++;
    $close_horas = date('H:i:s', strtotime("$close_horas") + ($config_atendimento_hora_intervalo * 60));
    }}
    //Hora Fim

    $close_hora = 0;
    $close_horas = $hora_inicio;
    $close++;
    $close_dias = date('Y-m-d', strtotime("$close_dias") + 86400);

}

$fechar_inicio = date('d/m/Y', strtotime($fechar_inicio));
$fechar_fim = date('d/m/Y', strtotime($fechar_fim));

$query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
$query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Abriu disponibilidade entre as datas $fechar_inicio e $fechar_fim", 'token_emp' => $_SESSION['token_emp']));

echo "<script>
    alert('Disponibilidade entre os dias $fechar_inicio e $fechar_fim abertas')
    window.location.replace('disponibilidade_fechar.php')
    </script>";

}else if($id_job == 'reservas_lancamentos'){

    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $lanc_produto = mysqli_real_escape_string($conn_msqli, $_POST['lanc_produto']);
    $lanc_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['lanc_quantidade']);
    $lanc_valor = mysqli_real_escape_string($conn_msqli, $_POST['lanc_valor']);
    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $tipo_lanc = mysqli_real_escape_string($conn_msqli, $_POST['tipo_lanc']);
    $lanc_data = date('Y-m-d H:i');

    if($lanc_produto == 'Cartão' || $lanc_produto == 'Dinheiro' || $lanc_produto == 'Transferencia' || $lanc_produto == 'Outros'){
        if($lanc_valor > 0){
        $lanc_produto = "Pagamento em $lanc_produto";
        $tipo = 'Pagamento';
        }else{
        $lanc_produto = "Pagamento em $lanc_produto [ Estornado ]";
        $tipo = 'Estorno';
        }
    $valor = (-1) * $lanc_valor;
    }else{
    $valor = $lanc_quantidade * $lanc_valor;
    $tipo = 'Produto';
    }
    
    if($tipo == 'Produto'){

    $query = $conexao->prepare("SELECT produto FROM estoque_item WHERE token_emp = :token_emp AND id = :produto");
    $query->execute(array('produto' => $lanc_produto, 'token_emp' => $_SESSION['token_emp']));
    $estoque_item = $query->fetch(PDO::FETCH_ASSOC);
    
    if($tipo_lanc != 'servico'){
        $lanc_produto = $estoque_item['produto'];
    }

    if($tipo_lanc != 'estoque'){
    $stmt = $conexao->prepare("INSERT INTO lancamentos (token_emp, data_lancamento, conta_id, descricao, recorrente, valor, observacoes, feitopor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['token_emp'], $lanc_data, 1, $lanc_produto, 'nao', $valor, '', $historico_quem]);
    }else{
    $tipo = 'Estoque';  
    }
    
    if($tipo_lanc != 'servico'){
    $produto = mysqli_real_escape_string($conn_msqli, $_POST['lanc_produto']);
    $produto_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['lanc_quantidade']);
    $produto_lote = 'Painel';
    $produto_validade = $hoje;

    $produto_quantidade = $produto_quantidade * -1;

    $query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade, lote, validade, token_emp) VALUES (:produto, :tipo, :quantidade, :lote, :validade, :token_emp)");
    $query->execute(array('produto' => $produto, 'tipo' => 'Saida', 'quantidade' => $produto_quantidade, 'lote' => $produto_lote, 'validade' => $produto_validade, 'token_emp' => $_SESSION['token_emp']));
    }}

    $query = $conexao->prepare("INSERT INTO lancamentos_atendimento (doc_email, produto, quantidade, valor, quando, feitopor, tipo, doc_nome, token_emp) VALUES ('{$doc_email}', :lanc_produto, :lanc_quantidade, :valor, '{$lanc_data}', '{$historico_quem}', '{$tipo}', '{$doc_nome}', :token_emp)");
    $query->execute(array('lanc_produto' => $lanc_produto, 'lanc_quantidade' => $lanc_quantidade, 'valor' => $valor, 'token_emp' => $_SESSION['token_emp']));
    $lanc_valor = number_format($lanc_valor ,2,",",".");
    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Lançou $lanc_quantidade $lanc_produto no valor de R$$lanc_valor no Cadastro $doc_email", 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Produto Lançado com Sucesso!')
    window.location.replace('cadastro.php?email=$doc_email&id_job=Lancamentos')
    </script>";

}else if($id_job == 'lancar_despesas'){

    $despesa_dia = mysqli_real_escape_string($conn_msqli, $_POST['despesa_dia']);
    $despesa_valor = mysqli_real_escape_string($conn_msqli, $_POST['despesa_valor']);
    $despesa_tipo = mysqli_real_escape_string($conn_msqli, $_POST['despesa_tipo']);
    $despesa_descricao = mysqli_real_escape_string($conn_msqli, $_POST['despesa_descricao']);



    $query = $conexao->prepare("INSERT INTO despesas (despesa_dia, despesa_valor, despesa_tipo, despesa_descricao, despesa_quem, token_emp) VALUES (:despesa_dia, :despesa_valor, :despesa_tipo, :despesa_descricao, :despesa_quem, :token_emp)");
    $query->execute(array('despesa_dia' => $despesa_dia, 'despesa_valor' => $despesa_valor, 'despesa_tipo' => $despesa_tipo, 'despesa_descricao' => $despesa_descricao, 'despesa_quem' => $historico_quem, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Despesa Cadastrada com Sucesso')
    window.location.replace('despesas.php')
    </script>";
    exit();


}else if($id_job == 'lancar_custos'){

    $custo_valor = mysqli_real_escape_string($conn_msqli, $_POST['custo_valor']);
    $custo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['custo_tipo']);
    $custo_descricao = mysqli_real_escape_string($conn_msqli, $_POST['custo_descricao']);

    $query = $conexao->prepare("INSERT INTO custos (custo_valor, custo_tipo, custo_descricao, custo_quem, token_emp) VALUES (:custo_valor, :custo_tipo, :custo_descricao, :custo_quem, :token_emp)");
    $query->execute(array('custo_valor' => $custo_valor, 'custo_tipo' => $custo_tipo, 'custo_descricao' => $custo_descricao, 'custo_quem' => $historico_quem, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Custo Cadastrado com Sucesso')
    window.location.replace('custos.php')
    </script>";
    exit();


}else if($id_job == 'editar_custos'){

    $custo_id = mysqli_real_escape_string($conn_msqli, $_POST['custo_id']);
    $custo_valor = mysqli_real_escape_string($conn_msqli, $_POST['custo_valor']);
    $custo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['custo_tipo']);
    $custo_descricao = mysqli_real_escape_string($conn_msqli, $_POST['custo_descricao']);

    $query = $conexao->prepare("UPDATE custos SET custo_valor = :custo_valor, custo_tipo = :custo_tipo, custo_descricao = :custo_descricao WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :custo_id");
    $query->execute(array('custo_valor' => $custo_valor, 'custo_tipo' => $custo_tipo, 'custo_descricao' => $custo_descricao, 'custo_id' => $custo_id));

    echo "<script>
    alert('Custo Editado com Sucesso')
    window.location.replace('custos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_tratamento'){

    $tratamento_descricao = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_descricao']);

    $query = $conexao->prepare("INSERT INTO tratamentos (tratamento, tratamento_quem, token_emp) VALUES (:tratamento_descricao, :tratamento_quem, :token_emp)");
    $query->execute(array('tratamento_descricao' => $tratamento_descricao, 'tratamento_quem' => $historico_quem, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Serviço Cadastrado com Sucesso')
    window.location.replace('tratamentos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_custo_tratamento'){

    $custo_id = mysqli_real_escape_string($conn_msqli, $_POST['custo_id']);
    $quantidade = mysqli_real_escape_string($conn_msqli, $_POST['quantidade']);
    $tratamento_id = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_id']);

    $query = $conexao->prepare("INSERT INTO custos_tratamentos (tratamento_id, custo_id, quantidade, token_emp) VALUES (:tratamento_id, :custo_id, :quantidade, :token_emp)");
    $query->execute(array('tratamento_id' => $tratamento_id, 'custo_id' => $custo_id, 'quantidade' => $quantidade, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Custo Cadastrado com Sucesso')
    window.location.replace('tratamentos_editar.php?id=$tratamento_id')
    </script>";
    exit();


}else if($id_job == 'cadastro_contrato'){

    $procedimento_valor = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_valor']);
    $procedimento_dias = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_dias']);
    $procedimentos = trim(mysqli_real_escape_string($conn_msqli, $_POST['procedimentos']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
    $token_contrato = mysqli_real_escape_string($conn_msqli, $_POST['token']);

    $query = $conexao->prepare("INSERT INTO contrato (email, assinado, assinado_data, assinado_empresa, assinado_empresa_data, procedimento, procedimento_dias, procedimento_valor, aditivo_valor, aditivo_procedimento, aditivo_status, token, token_emp) VALUES (:email, 'Não', :ass_data, 'Sim', :ass_data, :procedimento, :procedimento_dias, :procedimento_valor, '-', '-', 'Não', :token, :token_emp)");
    $query->execute(array('procedimento_valor' => $procedimento_valor, 'procedimento' => $procedimentos, 'procedimento_dias' => $procedimento_dias, 'email' => $email, 'ass_data' => date('Y-m-d H:i:s'), 'token' => $token_contrato, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Contrato Enviado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Contratos')
    </script>";
    exit();

}else if($id_job == 'cadastro_aditivo'){

    $procedimento_valor = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_valor']);
    $procedimentos = trim(mysqli_real_escape_string($conn_msqli, $_POST['procedimentos']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
    $token_contrato = mysqli_real_escape_string($conn_msqli, $_POST['token']);

    $query = $conexao->prepare("INSERT INTO contrato (email, assinado, assinado_data, assinado_empresa, assinado_empresa_data, procedimento, procedimento_valor, aditivo_valor, aditivo_procedimento, aditivo_status, token, token_emp) VALUES (:email, 'Não', :ass_data, 'Sim', :ass_data, '-', '-', :procedimento, :procedimento_valor, 'Sim', :token, :token_emp)");
    $query->execute(array('procedimento_valor' => $procedimento_valor, 'procedimento' => $procedimentos, 'email' => $email, 'ass_data' => date('Y-m-d H:i:s'), 'token' => $token_contrato, 'token_emp' => $_SESSION['token_emp']));

    $query2 = $conexao->prepare("UPDATE contrato SET assinado = 'Não' WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
    $query2->execute(array('token' => $token_contrato));

    echo "<script>
    alert('Aditivo Contrato Enviado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Contratos')
    </script>";
    exit();

}else if($id_job == 'cadastro_tratamento_enviar'){

    $tratamento = mysqli_real_escape_string($conn_msqli, $_POST['tratamento']);
    $tratamento_sessao = trim(mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']));
    $tratamento_data = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_data']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $comentario = mysqli_real_escape_string($conn_msqli, $_POST['comentario']);

    $query = $conexao->prepare("INSERT INTO tratamento (email, plano_descricao, plano_data, sessao_atual, sessao_total, sessao_status, token, comentario, token_emp) VALUES (:email, :plano_descricao, :plano_data, 0, :sessao_total, 'Em Andamento', :token, :comentario, :token_emp)");
    $query->execute(array('email' => $email, 'plano_descricao' => $tratamento, 'plano_data' => $tratamento_data, 'sessao_total' => $tratamento_sessao, 'token' => $token, 'comentario' => $comentario, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Tratamento Enviado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Tratamento')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_cadastrar'){

    $tratamento_sessao = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']);
    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $comentario = mysqli_real_escape_string($conn_msqli, $_POST['comentario']);
    $tratamento_data = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_data']);
    $tratamento = mysqli_real_escape_string($conn_msqli, $_POST['tratamento']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_atual = :tratamento_sessao WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id AND email = :email AND token = :token");
    $query->execute(array('email' => $email, 'id' => $id, 'tratamento_sessao' => $tratamento_sessao, 'token' => $token));

    $query = $conexao->prepare("INSERT INTO tratamento (email, plano_descricao, plano_data, sessao_atual, sessao_total, sessao_status, token, comentario, token_emp) VALUES (:email, :plano_descricao, :plano_data, :sessao_total, :sessao_total, 'Em Andamento', :token, :comentario, :token_emp)");
    $query->execute(array('email' => $email, 'plano_descricao' => $tratamento, 'plano_data' => $tratamento_data, 'sessao_total' => 0, 'token' => $token, 'comentario' => $comentario, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Sessao $tratamento_sessao Cadastrada com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Tratamento')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_finalizar'){

    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_status = 'Finalizada' WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id AND email = :email");
    $query->execute(array('email' => $email, 'id' => $id));

    echo "<script>
    alert('Sessao Finalizada com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Tratamento')
    </script>";
    exit();


}else if($id_job == 'arquivos'){

    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $arquivo_tipo = mysqli_real_escape_string($conn_msqli, $_POST['arquivo_tipo']);
    $token_profile = mysqli_real_escape_string($conn_msqli, $_POST['token_profile']);
    $arquivo = mysqli_real_escape_string($conn_msqli, $_POST['arquivo']).'.pdf';
    
    $arquivos = $_FILES['arquivos'];
    $dirAtual = '../arquivos/'.$_SESSION['token_emp'].'/'.$token_profile.'/'.$arquivo_tipo.'/';

    if($arquivos['type'] != 'application/pdf'){
        echo "<script>
        alert('Selecione apenas arquivos tipo PDF');
        window.location.replace('cadastro.php?email=$email&id_job=Arquivos');
        </script>";
        exit();
    }

    // Criar diretórios intermediários, se não existirem
    if (!is_dir($dirAtual)) {
        mkdir($dirAtual, 0777, true); // o 'true' aqui é essencial!
    }

    $caminhoFinal = $dirAtual . $arquivo;

    if (move_uploaded_file($arquivos['tmp_name'], $caminhoFinal)) {
        // Sucesso
    } else {
        echo "Erro ao mover o arquivo para $caminhoFinal";
    }


    $query_historico = $conexao->prepare("INSERT INTO historico_atendimento (quando, quem, unico, oque, token_emp) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque, :token_emp)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cadastrou um novo Arquivo $arquivo na Consulta $id_consulta", 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Arquivo Cadastrado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Arquivos')
    </script>";
    exit();

}else if($id_job == 'EnvioConfirmacao'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
    $atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $tipo_consulta = mysqli_real_escape_string($conn_msqli, $_POST['tipo_consulta']);
    
    $data_email = date('d/m/Y \-\ H:i:s');
    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
      
    $msg_replace = str_replace(
        ['{NOME}', '{TELEFONE}', '{EMAIL}', '{DATA}', '{HORA}', '{TIPO}'],    // o que procurar
        [$doc_nome, $doc_telefone, $doc_email, $atendimento_dia_str, $atendimento_hora_str, $tipo_consulta],  // o que colocar no lugar
        $config_msg_confirmacao
    );
      
    $msg_string = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $msg_replace);

    $msg_html = nl2br(htmlspecialchars($msg_string)); //Email
    $msg_texto = $msg_string; // Whatsapp
    
        //Envio de Email
    if(isset($_POST['email'])){
        if($envio_email == 'ativado'){
    
        $link_paneil = "<a href=\"$site_atual\"'>Clique Aqui</a>";
    
        $mail = new PHPMailer(true);
    
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = "$mail_Host";
        $mail->SMTPAuth = true;
        $mail->Username = "$mail_Username";
        $mail->Password = "$mail_Password";
        $mail->SMTPSecure = "$mail_SMTPSecure";
        $mail->Port = "$mail_Port";
    
        $mail->setFrom("$config_email", "$config_empresa");
        $mail->addAddress("$doc_email", "$doc_nome");
        
        $mail->isHTML(true);                                 
        $mail->Subject = "$config_empresa - Sua Consulta";
      // INICIO MENSAGEM  
        $mail->Body = "
    
        <fieldset>
      <legend><b><u>Sua Consulta</u></legend>
      <p>$msg_html</p>
      </fieldset><br><fieldset>
      <legend><b><u>Gerencia sua Consulta</u></legend>
      <p>Acesse o nosso portal, $link_paneil</p>
      </fieldset><br><fieldset>
      <legend><b><u>$config_empresa</u></legend>
      <p>CNPJ: $config_cnpj</p>
      <p>$config_telefone - $config_email</p>
      <p>$config_endereco</p></b>
      </fieldset><br><fieldset>
      <legend><b><u>Atenção</u></legend>
      <p>Este e-mail é automatico. Favor não responder!</p>
      </fieldset>
        
        "; // FIM MENSAGEM
    
            $mail->send();
    
        } catch (Exception $e) {
    
        }
    
    }
}
    //Fim Envio de Email
    
    if(isset($_POST['whatsapp'])){

    //Incio Envio Whatsapp
    if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = "55$doc_telefone";
        $msg_whatsapp = $msg_texto;
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
        
        }
    
    }
    //Fim Envio Whatsapp
    
    $id = base64_encode("$confirmacao.$token");
        echo   "<script>
                alert('Confirmação Enviada com Sucesso')
                window.location.replace('reserva.php?id_consulta=$id_consulta')
                </script>";
    
}else if($id_job == 'EnvioLembrete'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']);
    $id_consulta = mysqli_real_escape_string($conn_msqli, $_POST['id_consulta']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $atendimento_dia = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia']);
    $atendimento_hora = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora']);
    
    //Envio de Email	
    
    $data_email = date('d/m/Y \-\ H:i:s');
    $atendimento_dia_str = date('d/m/Y',  strtotime($atendimento_dia));
    $atendimento_hora_str = date('H:i\h',  strtotime($atendimento_hora));
      
    $msg_replace = str_replace(
        ['{NOME}', '{TELEFONE}', '{EMAIL}', '{DATA}', '{HORA}', '{TIPO}'],    // o que procurar
        [$doc_nome, $doc_telefone, $doc_email, $atendimento_dia_str, $atendimento_hora_str, $tipo_consulta],  // o que colocar no lugar
        $config_msg_lembrete
    );
      
    $msg_string = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $msg_replace);

    $msg_html = nl2br(htmlspecialchars($msg_string)); //Email
    $msg_texto = $msg_string; // Whatsapp
    
    
    if(isset($_POST['email'])){
        if($envio_email == 'ativado'){

    $link_paneil = "<a href=\"$site_atual\"'>Clique Aqui</a>";
  
    $mail = new PHPMailer(true);
  
  try {
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
      $mail->CharSet = 'UTF-8';
      $mail->isSMTP();
      $mail->Host = "$mail_Host";
      $mail->SMTPAuth = true;
      $mail->Username = "$mail_Username";
      $mail->Password = "$mail_Password";
      $mail->SMTPSecure = "$mail_SMTPSecure";
      $mail->Port = "$mail_Port";
  
      $mail->setFrom("$config_email", "$config_empresa");
      $mail->addAddress("$doc_email", "$doc_nome");
      
      $mail->isHTML(true);                                 
      $mail->Subject = "$config_empresa - Lembrete de Consulta";
    // INICIO MENSAGEM  
      $mail->Body = "
  
      <fieldset>
      <legend><b><u>Lembrete de Consulta</u></legend>
      <p>$msg_html</p>
      </fieldset><br><fieldset>
      <legend><b><u>Gerencia sua Consulta</u></legend>
      <p>Acesse o nosso portal, $link_paneil</p>
      </fieldset><br><fieldset>
      <legend><b><u>$config_empresa</u></legend>
      <p>CNPJ: $config_cnpj</p>
      <p>$config_telefone - $config_email</p>
      <p>$config_endereco</p></b>
      </fieldset><br><fieldset>
      <legend><b><u>Atenção</u></legend>
      <p>Este e-mail é automatico. Favor não responder!</p>
      </fieldset>
      
      "; // FIM MENSAGEM
  
          $mail->send();
  
      } catch (Exception $e) {
  
      }
    
    }
    }
    //Fim Envio de Email
    
    if(isset($_POST['whatsapp'])){
    //Incio Envio Whatsapp
    if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = "55$doc_telefone";
        $msg_whatsapp = $msg_texto;
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
        
        }
    
    }
    //Fim Envio Whatsapp
        echo   "<script>
                alert('Lembrete Enviado com Sucesso')
                window.location.replace('reserva.php?id_consulta=$id_consulta')
                </script>";
    
}else if($id_job == 'cadastro_novo'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']));
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));
    $origem = mysqli_real_escape_string($conn_msqli, $_POST['origem']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['doc_nascimento']);

    $senha = '123456';
    $crip_senha = md5($senha);
    $token = md5(date("YmdHismm"));

    function validaCPF($doc_cpf) {
     
        // Extrai somente os números
        $doc_cpf = preg_replace( '/[^0-9]/is', '', $doc_cpf );
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($doc_cpf) != 11) {
            return false;
        }
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $doc_cpf)) {
            return false;
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $doc_cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($doc_cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    
    }

    if(validaCPF($doc_cpf) == false){
        echo "<script>
        alert('CPF Invalido')
        window.location.replace('cadastro_registro.php')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $row_check = $query->rowCount();
    
    if($row_check == 1){
        echo "<script>
        alert('Email Cadastrado!')
        window.location.replace('cadastro_registro.php')
        </script>";
        exit();
    }
    
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
    $query->execute(array('email' => $doc_email));
    $row_check = $query->rowCount();
    

    //$dados_painel_users = $nome.';'.$rg.';'.$cpf.';'.$telefone.';'.$profissao.';'.$nascimento.';'.$cep.';'.$rua.';'.$numero.';'.$cidade.';'.$bairro.';'.$estado;
    $dados_painel_users = $doc_nome.';'.''.';'.$doc_cpf.';'.$doc_telefone.';'.''.';'.$nascimento.';'.''.';'.''.';'.''.';'.''.';'.''.';'.'';
    $dados_criptografados = openssl_encrypt($dados_painel_users, $metodo, $chave, 0, $iv);
    $dados_final = base64_encode($dados_criptografados);

    if($row_check == 1){
        
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $token_emp = $select['token_emp'] . ';' . $_SESSION['token_emp'];
    }
        
    $query = $conexao->prepare("UPDATE painel_users SET token_emp = :token_emp WHERE email = :email");
    $query->execute(array('email' => $doc_email, 'token_emp' => $token_emp));
    
    }else{
    $query = $conexao->prepare("INSERT INTO painel_users (email, dados_painel_users, tipo, senha, token, codigo, tentativas, origem, token_emp) VALUES (:email, :dados_painel_users, 'Paciente', :senha, :token, '0', '1', :origem, :token_emp)");
    $query->execute(array('email' => $doc_email, 'dados_painel_users' => $dados_final, 'token' => $token, 'senha' => $crip_senha, 'origem' => $origem, 'token_emp' => $_SESSION['token_emp']));  
    }

    echo "<script>
    alert('Cliente Cadastrado Sucesso!')
    window.location.replace('cadastro.php?email=$doc_email')
    </script>";

}else if($id_job == 'cadastro_editar'){

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $doc_cpf = mysqli_real_escape_string($conn_msqli, $_POST['doc_cpf']);
    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['doc_telefone']));
    $doc_rg = mysqli_real_escape_string($conn_msqli, $_POST['doc_rg']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['nascimento']);
    $profissao = mysqli_real_escape_string($conn_msqli, $_POST['profissao']);
    $endereco_cep = mysqli_real_escape_string($conn_msqli, $_POST['endereco_cep']);
    $endereco_rua = mysqli_real_escape_string($conn_msqli, $_POST['endereco_rua']);
    $endereco_n = mysqli_real_escape_string($conn_msqli, $_POST['endereco_n']);
    $endereco_bairro = mysqli_real_escape_string($conn_msqli, $_POST['endereco_bairro']);
    $endereco_cidade = mysqli_real_escape_string($conn_msqli, $_POST['endereco_cidade']);
    $endereco_uf = mysqli_real_escape_string($conn_msqli, $_POST['endereco_uf']);
    $feitopor = mysqli_real_escape_string($conn_msqli, $_POST['feitopor']);

    $dados_painel_users = $doc_nome.';'.$doc_rg.';'.$doc_cpf.';'.$doc_telefone.';'.$profissao.';'.$nascimento.';'.$endereco_cep.';'.$endereco_rua.';'.$endereco_n.';'.$endereco_cidade.';'.$endereco_bairro.';'.$endereco_uf;
    $dados_criptografados = openssl_encrypt($dados_painel_users, $metodo, $chave, 0, $iv);
    $dados_final = base64_encode($dados_criptografados);

    $query = $conexao->prepare("UPDATE painel_users SET dados_painel_users = :dados_painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email, 'dados_painel_users' => $dados_final));

    if($feitopor == 'Paciente'){
        echo "<script>
        alert('Cadastro Alterado com Sucesso')
        window.location.replace('profile.php')
        </script>";
    }else{
        echo "<script>
        alert('Cadastro Alterado com Sucesso')
        window.location.replace('cadastro.php?email=$doc_email')
        </script>";
    }
    

}else if($id_job == 'cadastro_editar_senha'){

    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $senha_antes = mysqli_real_escape_string($conn_msqli, $_POST['senha_antes']);
    $senha_nova = mysqli_real_escape_string($conn_msqli, $_POST['senha_nova']);
    $senha_nova_conf = mysqli_real_escape_string($conn_msqli, $_POST['senha_nova_conf']);

    if($senha_nova != $senha_nova_conf){
        $_SESSION['error_reserva'] = 'Senhas diferentes!';
        header("Location: profile.php?id_job=Senha");
        exit();
    }

    $crip_senha = md5($senha_antes);
    $crip_senha_nova = md5($senha_nova);
    
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email AND senha = :senha");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email, 'senha' => $crip_senha));
    $row = $query->rowCount();
    
    if($row == 1){

    $query = $conexao->prepare("UPDATE painel_users SET senha = :senha WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email, 'senha' => $crip_senha_nova));

    echo "<script>
    alert('Senha Alterada com Sucesso')
    window.location.replace('profile.php?id_job=Senha')
    </script>";

    }else{
        $_SESSION['error_reserva'] = 'Senha antiga errada!';
        header("Location: profile.php?id_job=Senha");
        exit();
    }    

}else if($id_job == 'lancar_produto'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_minimo = mysqli_real_escape_string($conn_msqli, $_POST['produto_minimo']);
    $produto_unidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_unidade']);

    $query = $conexao->prepare("INSERT INTO estoque_item (produto, minimo, unidade, token_emp) VALUES (:produto, :minimo, :unidade, :token_emp)");
    $query->execute(array('produto' => $produto, 'minimo' => $produto_minimo, 'unidade' => $produto_unidade, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Produto Cadastrado com Sucesso')
    window.location.replace('estoque_produtos.php')
    </script>";
    exit();


}else if($id_job == 'editar_produto'){

    $produto_id = mysqli_real_escape_string($conn_msqli, $_POST['produto_id']);
    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_minimo = mysqli_real_escape_string($conn_msqli, $_POST['produto_minimo']);
    $produto_unidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_unidade']);

    $query = $conexao->prepare("UPDATE estoque_item SET produto = :produto, minimo = :minimo, unidade = :unidade WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :produto_id");
    $query->execute(array('produto' => $produto, 'minimo' => $produto_minimo, 'unidade' => $produto_unidade, 'produto_id' => $produto_id));

    echo "<script>
    alert('Produto Editado com Sucesso')
    window.location.replace('estoque_produtos.php')
    </script>";
    exit();


}else if($id_job == 'lancar_entrada'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_quantidade']);
    $produto_valor = mysqli_real_escape_string($conn_msqli, $_POST['produto_valor']);
    $produto_lote = mysqli_real_escape_string($conn_msqli, $_POST['produto_lote']);
    $produto_validade = mysqli_real_escape_string($conn_msqli, $_POST['produto_validade']);
    $data_lancamento = mysqli_real_escape_string($conn_msqli, $_POST['data_lancamento']);
    $lancar_despesa = mysqli_real_escape_string($conn_msqli, $_POST['lancar_despesa']);

    $query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade, lote, validade, token_emp) VALUES (:produto, :tipo, :quantidade, :lote, :validade, :token_emp)");
    $query->execute(array('produto' => $produto, 'tipo' => 'Entrada', 'quantidade' => $produto_quantidade, 'lote' => $produto_lote, 'validade' => $produto_validade, 'token_emp' => $_SESSION['token_emp']));

    if($lancar_despesa == 'Sim'){
    $query = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :produto");
    $query->execute(['produto' => $produto]);
    while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $produto = $select['produto'];
        }

    $stmt = $conexao->prepare("
                        INSERT INTO lancamentos (token_emp, data_lancamento, conta_id, descricao, valor, observacoes, feitopor)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $_SESSION['token_emp'],
                        $_POST['data_lancamento'],
                        16,
                        $produto,
                        $_POST['produto_valor'],
                        '',
                        $historico_quem
                    ]);
    }

    echo "<script>
    alert('Entrada Cadastrada com Sucesso')
    window.location.replace('estoque_entradas.php')
    </script>";
    exit();


}else if($id_job == 'lancar_saida'){

    $produto = mysqli_real_escape_string($conn_msqli, $_POST['produto']);
    $produto_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['produto_quantidade']);
    $produto_lote = mysqli_real_escape_string($conn_msqli, $_POST['produto_lote']);
    $produto_validade = mysqli_real_escape_string($conn_msqli, $_POST['produto_validade']);

    $produto_quantidade = $produto_quantidade * -1;

    $query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade, lote, validade, token_emp) VALUES (:produto, :tipo, :quantidade, :lote, :validade, :token_emp)");
    $query->execute(array('produto' => $produto, 'tipo' => 'Saida', 'quantidade' => $produto_quantidade, 'lote' => $produto_lote, 'validade' => $produto_validade, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
    alert('Saida Cadastrada com Sucesso')
    window.location.replace('estoque_saidas.php')
    </script>";
    exit();


}else if($id_job == 'adicionar_link'){

$descricao = mysqli_real_escape_string($conn_msqli, $_POST['video_titulo']);
$link_youtube = mysqli_real_escape_string($conn_msqli, $_POST['video_link']);

// Verifica se é um link do YouTube
if (preg_match('/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/', $link_youtube)) {

    $query = $conexao->prepare("INSERT INTO videos (link_youtube, descricao, token_emp) VALUES (:link_youtube, :descricao, :token_emp)");
    $query->execute(array('link_youtube' => $link_youtube, 'descricao' => $descricao, 'token_emp' => $_SESSION['token_emp']));

    echo "<script>
        alert('Vídeo cadastrado com sucesso');
        window.location.replace('videos.php?id_job=Adicionar');
    </script>";
    exit();

} else {
    echo "<script>
        alert('Link inválido. Por favor insira um link do YouTube válido.');
        window.history.back();
    </script>";
    exit();
}


}else if($id_job == 'editar_link'){

    $video_id = mysqli_real_escape_string($conn_msqli, $_POST['video_id']);
    $descricao = mysqli_real_escape_string($conn_msqli, $_POST['video_titulo']);
    $link_youtube = mysqli_real_escape_string($conn_msqli, $_POST['video_link']);

    // Verifica se é um link do YouTube
    if (preg_match('/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/', $link_youtube)) {

    $query = $conexao->prepare("UPDATE videos SET link_youtube = :link_youtube, descricao = :descricao WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :video_id");
    $query->execute(array('link_youtube' => $link_youtube, 'descricao' => $descricao, 'video_id' => $video_id));

    echo "<script>
        alert('Vídeo editado com sucesso');
        window.location.replace('videos.php?id_job=Adicionar');
    </script>";
    exit();

    } else {
    echo "<script>
        alert('Link inválido. Por favor insira um link do YouTube válido.');
        window.history.back();
    </script>";
    exit();
    }


}else if($id_job == 'Prontuario_Add'){

    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $profissional = mysqli_real_escape_string($conn_msqli, $_POST['profissional']);
    $anotacao = mysqli_real_escape_string($conn_msqli, $_POST['anotacao']);

    $stmt = $conexao->prepare("INSERT INTO evolucoes (doc_email, profissional, anotacao, token_emp) VALUES (?, ?, ?, ?)");
    $stmt->execute([$doc_email, $profissional, $anotacao, $_SESSION['token_emp']]);

    echo "<script>
        alert('Prontuario Registrado com Sucesso');
        window.location.replace('cadastro.php?email=$doc_email&id_job=Evolucao');
    </script>";
    exit();



}else if($id_job == 'enviar_crm'){

    $id = mysqli_real_escape_string($conn_msqli, $_POST['id']);

    $stmt = $conexao->prepare("INSERT INTO evolucoes (doc_email, profissional, anotacao, token_emp) VALUES (?, ?, ?, ?)");
    $stmt->execute([$doc_email, $profissional, $anotacao, $_SESSION['token_emp']]);

    echo "<script>
        alert('Prontuario Registrado com Sucesso');
        window.location.replace('cadastro.php?email=$doc_email&id_job=Prontuario');
    </script>";
    exit();

}else if($id_job == 'Receituario'){

    $conteudo = mysqli_real_escape_string($conn_msqli, $_POST['conteudo']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $titulo = mysqli_real_escape_string($conn_msqli, $_POST['titulo']);
    $token_emp = $_SESSION['token_emp'];

    $query = $conexao->prepare("INSERT INTO receituarios (doc_email, token_emp, titulo, conteudo, criado_em) VALUES (:email, :token_emp, :titulo, :conteudo, NOW())");
    $query->execute([
        'email' => $email,
        'token_emp' => $token_emp,
        'titulo' => $titulo,
        'conteudo' => $conteudo
    ]);

    echo "<script>
        alert('Receituario Registrado com Sucesso');
        window.location.replace('cadastro.php?email=$email&id_job=Receituario');
    </script>";
    exit();
    
}else if($id_job == 'Atestado'){

    $conteudo = mysqli_real_escape_string($conn_msqli, $_POST['conteudo']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $titulo = mysqli_real_escape_string($conn_msqli, $_POST['titulo']);
    $token_emp = $_SESSION['token_emp'];

    $query = $conexao->prepare("INSERT INTO atestados (doc_email, token_emp, titulo, conteudo, criado_em) VALUES (:email, :token_emp, :titulo, :conteudo, NOW())");
    $query->execute([
        'email' => $email,
        'token_emp' => $token_emp,
        'titulo' => $titulo,
        'conteudo' => $conteudo
    ]);

    echo "<script>
        alert('Atestado Registrado com Sucesso');
        window.location.replace('cadastro.php?email=$email&id_job=Atestado');
    </script>";
    exit();
    
}else if($id_job == 'cadastro_editar_owner'){

    $doc_email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $token_profile = mysqli_real_escape_string($conn_msqli, $_POST['token_profile']);
    $plano_validade = mysqli_real_escape_string($conn_msqli, $_POST['plano_validade']);

    $query = $conexao->prepare("UPDATE painel_users SET plano_validade = :plano_validade WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('token_emp' => '%;'.$token_profile.';%', 'email' => $doc_email, 'plano_validade' => $plano_validade));

        echo "<script>
        alert('Cadastro Alterado com Sucesso')
        window.location.replace('owner_cadastro_editar.php?email=$doc_email')
        </script>";

}