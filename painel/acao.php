<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

require '../vendor/autoload.php';
use Dompdf\Dompdf;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$limite_dia = $config_limitedia;
$historico_data = date('Y-m-d H:i:s');

$result_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$historico_quem = $select_check['nome'];
$historico_unico_usuario = $select_check['unico'];
}

if($id_job == 'editar_configuracoes' || $id_job == 'disponibilidade_fechar' || $id_job == 'disponibilidade_abrir'){
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

if($id_job == 'editar_configuracoes'){

    $config_empresa = mysqli_real_escape_string($conn_msqli, $_POST['config_empresa']);
    $config_email = mysqli_real_escape_string($conn_msqli, $_POST['config_email']);
    $config_telefone = mysqli_real_escape_string($conn_msqli, $_POST['config_telefone']);
    $config_cnpj = mysqli_real_escape_string($conn_msqli, $_POST['config_cnpj']);
    $config_endereco = mysqli_real_escape_string($conn_msqli, $_POST['config_endereco']);
    $msg_cancelamento = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_cancelamento']);
    $msg_confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_confirmacao']);
    $msg_finalizar = mysqli_real_escape_string($conn_msqli, $_POST['config_msg_finalizar']);
    $config_limitedia = mysqli_real_escape_string($conn_msqli, $_POST['config_limitedia']);
    $atendimento_hora_comeco = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_comeco']);
    $atendimento_hora_fim = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_fim']);
    $atendimento_hora_intervalo = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_hora_intervalo']);
    $atendimento_dia_max = mysqli_real_escape_string($conn_msqli, $_POST['atendimento_dia_max']);

    if($dia_segunda == -1 && $dia_terca == -1 && $dia_quarta == -1 && $dia_quinta == -1 && $dia_sexta == -1 && $dia_sabado == -1 && $dia_domingo){
        echo "<script>
        alert('Pelo menos um dia precisa estar Selecionado')
        window.location.replace('configuracoes.php')
        </script>";
        exit(); 
    }

    $query = $conexao->prepare("UPDATE configuracoes SET config_empresa = :config_empresa, config_email = :config_email, config_telefone = :config_telefone, config_cnpj = :config_cnpj, config_endereco = :config_endereco, config_msg_cancelamento = :msg_cancelamento, config_msg_finalizar = :msg_finalizar, config_msg_confirmacao = :msg_confirmacao, config_limitedia = :config_limitedia, atendimento_hora_comeco = :atendimento_hora_comeco, atendimento_hora_fim = :atendimento_hora_fim, atendimento_hora_intervalo = :atendimento_hora_intervalo, atendimento_dia_max = :atendimento_dia_max, config_dia_segunda = :dia_segunda, config_dia_terca = :dia_terca, config_dia_quarta = :dia_quarta, config_dia_quinta = :dia_quinta, config_dia_sexta = :dia_sexta, config_dia_sabado = :dia_sabado, config_dia_domingo = :dia_domingo WHERE id = '{$tabela_configuracoes}'");
    $query->execute(array('config_empresa' => $config_empresa, 'config_email' => $config_email, 'config_telefone' => $config_telefone, 'config_cnpj' => $config_cnpj, 'config_endereco' => $config_endereco, 'msg_cancelamento' => $msg_cancelamento, 'msg_finalizar' => $msg_finalizar, 'msg_confirmacao' => $msg_confirmacao, 'config_limitedia' => $config_limitedia, 'atendimento_hora_comeco' => $atendimento_hora_comeco, 'atendimento_hora_fim' => $atendimento_hora_fim, 'atendimento_hora_intervalo' => $atendimento_hora_intervalo, 'atendimento_dia_max' => $atendimento_dia_max, 'dia_segunda' => $dia_segunda, 'dia_terca' => $dia_terca, 'dia_quarta' => $dia_quarta, 'dia_quinta' => $dia_quinta, 'dia_sexta' => $dia_sexta, 'dia_sabado' => $dia_sabado, 'dia_domingo' => $dia_domingo));
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => 'Alterou as Configurações'));   

    echo "<script>
    alert('Configurações Editadas com sucesso')
    window.location.replace('configuracoes.php')
    </script>";
    exit();

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

    $query = $conexao->query("INSERT INTO $tabela_disponibilidade (atendimento_dia, atendimento_hora, quantidade, confirmacao) VALUES ('{$close_dias}', '{$close_horas}', '1', 'Closed')");

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

    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
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

    $query = $conexao->query("DELETE FROM $tabela_disponibilidade WHERE confirmacao = 'Closed' AND atendimento_dia = '{$close_dias}' AND atendimento_hora = '{$close_horas}'");

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

$query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
$query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Abriu disponibilidade entre as datas $fechar_inicio e $fechar_fim"));

echo "<script>
    alert('Disponibilidade entre os dias $fechar_inicio e $fechar_fim abertas')
    window.location.replace('disponibilidade_fechar.php')
    </script>";

}else if($id_job == 'reservas_lancamentos'){

    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);
    $lanc_produto = mysqli_real_escape_string($conn_msqli, $_POST['lanc_produto']);
    $lanc_quantidade = mysqli_real_escape_string($conn_msqli, $_POST['lanc_quantidade']);
    $lanc_valor = mysqli_real_escape_string($conn_msqli, $_POST['lanc_valor']);
    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
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

    $query = $conexao->prepare("INSERT INTO $tabela_lancamentos (confirmacao, produto, quantidade, valor, quando, feitopor, tipo, doc_nome) VALUES ('{$confirmacao}', :lanc_produto, :lanc_quantidade, :valor, '{$lanc_data}', '{$historico_quem}', '{$tipo}', '{$doc_nome}')");
    $query->execute(array('lanc_produto' => $lanc_produto, 'lanc_quantidade' => $lanc_quantidade, 'valor' => $valor));
    $lanc_valor = number_format($lanc_valor ,2,",",".");
    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Lançou $lanc_quantiade $lanc_produto no valor de R$$lanc_valor na Confirmação $confirmacao"));

    echo "<script>
    alert('Produto Lançado com Sucesso na Reserva $confirmacao')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";

}else if($id_job == 'Formulario' || $id_job == 'Formulario_2'){

    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $cep = mysqli_real_escape_string($conn_msqli, $_POST['cep']);
    $bairro = mysqli_real_escape_string($conn_msqli, $_POST['bairro']);
    $endereco = mysqli_real_escape_string($conn_msqli, $_POST['rua']);
    $municipio = mysqli_real_escape_string($conn_msqli, $_POST['cidade']);
    $uf = mysqli_real_escape_string($conn_msqli, $_POST['uf']);
    $celular = mysqli_real_escape_string($conn_msqli, $_POST['celular']);
    $profissao = mysqli_real_escape_string($conn_msqli, $_POST['profissao']);
    $estado_civil = mysqli_real_escape_string($conn_msqli, $_POST['estado_civil']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['nascimento']);
    $feitopor = mysqli_real_escape_string($conn_msqli, $_POST['feitopor']);
    $queixa_principal = mysqli_real_escape_string($conn_msqli, $_POST['queixa_principal']);
    $doenca_outras_areas = mysqli_real_escape_string($conn_msqli, $_POST['doenca_outras_areas']);
    $doenca_outras_areas_quais = mysqli_real_escape_string($conn_msqli, $_POST['doenca_outras_areas_quais']);
    $doenca_outras_areas_tempo = mysqli_real_escape_string($conn_msqli, $_POST['doenca_outras_areas_tempo']);
    $doenca_outras_areas_status = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_prob']);
    $doenca_outras_areas_cabelo = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_cabelo']);
    $outras_areas_alteracoes0 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes0']);
    $outras_areas_alteracoes1 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes1']);
    $outras_areas_alteracoes2 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes2']);
    $outras_areas_alteracoes3 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes3']);
    $outras_areas_alteracoes4 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes4']);
    $outras_areas_alteracoes5 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes5']);
    $outras_areas_alteracoes6 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes6']);
    $outras_areas_alteracoes7 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes7']);
    $outras_areas_alteracoes8 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes8']);
    $outras_areas_alteracoes9 = mysqli_real_escape_string($conn_msqli, $_POST['outras_areas_alteracoes9']);
    $doenca_outras_areas_crises = mysqli_real_escape_string($conn_msqli, $_POST['doenca_outras_areas_crises']);
    $doenca_outras_areas_crises_quando = mysqli_real_escape_string($conn_msqli, $_POST['doenca_outras_areas_crises_quando']);
    $doencas_ultimas = mysqli_real_escape_string($conn_msqli, $_POST['doencas_ultimas']);
    $doenca_atual = mysqli_real_escape_string($conn_msqli, $_POST['doenca_atual']);
    $doenca_atual_quais = mysqli_real_escape_string($conn_msqli, $_POST['doenca_atual_quais']);
    $endocrino = mysqli_real_escape_string($conn_msqli, $_POST['endocrino']);
    $endocrino_quais = mysqli_real_escape_string($conn_msqli, $_POST['endocrino_quais']);
    $cardiaco = mysqli_real_escape_string($conn_msqli, $_POST['cardiaco']);
    $marca_passo = mysqli_real_escape_string($conn_msqli, $_POST['marca_passo']);
    $medicacao = mysqli_real_escape_string($conn_msqli, $_POST['medicacao']);
    $medicacao_quais = mysqli_real_escape_string($conn_msqli, $_POST['medicacao_quais']);
    $proc_prob0 = mysqli_real_escape_string($conn_msqli, $_POST['proc_prob0']);
    $proc_prob1 = mysqli_real_escape_string($conn_msqli, $_POST['proc_prob1']);
    $proc_prob2 = mysqli_real_escape_string($conn_msqli, $_POST['proc_prob2']);
    $proc_prob3 = mysqli_real_escape_string($conn_msqli, $_POST['proc_prob3']);
    $alergias = mysqli_real_escape_string($conn_msqli, $_POST['alergia']);
    $alergias_quais = mysqli_real_escape_string($conn_msqli, $_POST['alergia_quais']);
    $carne = mysqli_real_escape_string($conn_msqli, $_POST['carne']);
    $filhos = mysqli_real_escape_string($conn_msqli, $_POST['filhos']);
    $filhos_qtd = mysqli_real_escape_string($conn_msqli, $_POST['filhos_qtd']);
    $gravidez = mysqli_real_escape_string($conn_msqli, $_POST['gravidez']);
    $gravidez_data = mysqli_real_escape_string($conn_msqli, $_POST['gravidez_data']);
    $alteracao_menstrual = mysqli_real_escape_string($conn_msqli, $_POST['alteracao_menstrual']);
    $alteracao_menstrual_quais = mysqli_real_escape_string($conn_msqli, $_POST['alteracao_menstrual_quais']);
    $familiares = mysqli_real_escape_string($conn_msqli, $_POST['familiares']);
    $familiares_qual = mysqli_real_escape_string($conn_msqli, $_POST['familiares_qual']);
    $quimica_cabelos_atual = mysqli_real_escape_string($conn_msqli, $_POST['quimica_cabelo']);
    $quimica_cabelos_atual_quais = mysqli_real_escape_string($conn_msqli, $_POST['quimica_cabelo_quais']);
    $quimica_cabelos_atual_frequencia = mysqli_real_escape_string($conn_msqli, $_POST['quimica_cabelos_atual_frequencia']);
    $usa0 = mysqli_real_escape_string($conn_msqli, $_POST['usa0']);
    $usa1 = mysqli_real_escape_string($conn_msqli, $_POST['usa1']);
    $usa2 = mysqli_real_escape_string($conn_msqli, $_POST['usa2']);
    $usa3 = mysqli_real_escape_string($conn_msqli, $_POST['usa3']);
    $usa4 = mysqli_real_escape_string($conn_msqli, $_POST['usa4']);
    $usa5 = mysqli_real_escape_string($conn_msqli, $_POST['usa5']);
    $usa6 = mysqli_real_escape_string($conn_msqli, $_POST['usa6']);
    $cuidado_cabelo_lavagem = mysqli_real_escape_string($conn_msqli, $_POST['cuidado_cabelo_lavagem']);
    $cuidado_cabelo_produtos = mysqli_real_escape_string($conn_msqli, $_POST['cuidado_cabelo_produtos']);
    $exm_fisico_volume_cabelo = mysqli_real_escape_string($conn_msqli, $_POST['volume_cabelo']);
    $exm_fisico_comprimento_cabelo = mysqli_real_escape_string($conn_msqli, $_POST['comprimento_cabelo']);
    $exm_fisico_quimica = mysqli_real_escape_string($conn_msqli, $_POST['exm_fisico_quimica']);
    $exm_fisico_quimica_quais = mysqli_real_escape_string($conn_msqli, $_POST['exm_fisico_quimica_quais']);
    $exm_fisico_presenca0 = mysqli_real_escape_string($conn_msqli, $_POST['exm_fisico_presenca0']);
    $exm_fisico_presenca1 = mysqli_real_escape_string($conn_msqli, $_POST['exm_fisico_presenca1']);
    $exm_fisico_cabelo = mysqli_real_escape_string($conn_msqli, $_POST['tipo_cabelo']);
    $exm_fisico_pontas = mysqli_real_escape_string($conn_msqli, $_POST['pontas_cabelo']);
    $pontas_cabelo_regiao = mysqli_real_escape_string($conn_msqli, $_POST['pontas_cabelo_regiao']);
    $cc_apresenta0 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta0']);
    $cc_apresenta1 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta1']);
    $cc_apresenta2 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta2']);
    $cc_apresenta3 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta3']);
    $cc_apresenta4 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta4']);
    $cc_apresenta5 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta5']);
    $cc_apresenta6 = mysqli_real_escape_string($conn_msqli, $_POST['cc_apresenta6']);
    $alopecia = mysqli_real_escape_string($conn_msqli, $_POST['alopecia']);
    $alopecia_localizacao = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_localizacao']);
    $alopecia_formato = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_formato']);
    $alopecia_lesoes = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_lesoes']);
    $alopecia_formato_2 = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_formato_2']);
    $alopecia_tamanho = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_tamanho']);
    $alopecia_couro = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_couro']);
    $alopecia_obs = mysqli_real_escape_string($conn_msqli, $_POST['alopecia_obs']);
    $alopecia_fios = mysqli_real_escape_string($conn_msqli, $_POST['alopecia']);
    $alteracao_encontrada = mysqli_real_escape_string($conn_msqli, $_POST['alteracao_encontrada']);
    $protocolo_sugerido = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_sugerido']);
    $protocolo_realizado_01_data = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_01_data']);
    $protocolo_realizado_01 = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_01']);
    $protocolo_realizado_02_data = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_02_data']);
    $protocolo_realizado_02 = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_02']);
    $protocolo_realizado_03_data = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_03_data']);
    $protocolo_realizado_03 = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_03']);
    $protocolo_realizado_04_data = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_04_data']);
    $protocolo_realizado_04 = mysqli_real_escape_string($conn_msqli, $_POST['protocolo_realizado_04']);
    $exm_fisico_regiao = mysqli_real_escape_string($conn_msqli, $_POST['exm_fisico_regiao']);


    $result_check = $conexao->prepare("SELECT * FROM $tabela_formulario WHERE confirmacao = :confirmacao AND email = :email");
    $result_check->execute(array('confirmacao' => $confirmacao, 'email' => $email));
    $row_check = $result_check->rowCount();

    if(isset($_POST['outras_areas_alteracoes0'])){
    $doenca_outras_areas_alteracoes = '0';
    }else{
    $doenca_outras_areas_alteracoes = '1';
    }
    if(isset($_POST['outras_areas_alteracoes1'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes2'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes3'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes4'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes5'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes6'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes7'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes8'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['outras_areas_alteracoes9'])){
    $doenca_outras_areas_alteracoes .= '0';
    }else{
    $doenca_outras_areas_alteracoes .= '1';
    }
    if(isset($_POST['proc_prob0'])){
    $precederam_problema = '0';
    }else{
    $precederam_problema = '1';
    }
    if(isset($_POST['proc_prob1'])){
    $precederam_problema .= '0';
    }else{
    $precederam_problema .= '1';
    }
    if(isset($_POST['proc_prob2'])){
    $precederam_problema .= '0';
    }else{
    $precederam_problema .= '1';
    }
    if(isset($_POST['proc_prob3'])){
    $precederam_problema .= '0';
    }else{
    $precederam_problema .= '1';
    }
    if(isset($_POST['usa0'])){
    $cuidado_cabelo_usa = '0';
    }else{
    $cuidado_cabelo_usa = '1';
    }
    if(isset($_POST['usa1'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['usa2'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['usa3'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['usa4'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['usa5'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['usa6'])){
    $cuidado_cabelo_usa .= '0';
    }else{
    $cuidado_cabelo_usa .= '1';
    }
    if(isset($_POST['cc_apresenta0'])){
    $exm_fisico_couro_cabeludo = '0';
    }else{
    $exm_fisico_couro_cabeludo = '1';
    }
    if(isset($_POST['cc_apresenta1'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['cc_apresenta2'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['cc_apresenta3'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['cc_apresenta4'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['cc_apresenta5'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['cc_apresenta6'])){
    $exm_fisico_couro_cabeludo .= '0';
    }else{
    $exm_fisico_couro_cabeludo .= '1';
    }
    if(isset($_POST['exm_fisico_presenca0'])){
    $exm_fisico_presenca = '0';
    }else{
    $exm_fisico_presenca = '1';
    }
    if(isset($_POST['exm_fisico_presenca1'])){
    $exm_fisico_presenca .= '0';
    }else{
    $exm_fisico_presenca .= '1';
    }


    $doenca_outras_areass = $doenca_outras_areas .'_' .$doenca_outras_areas_quais;
    $doenca_outras_areas_crisess = $doenca_outras_areas_crises .'_' .$doenca_outras_areas_crises_quando;
    $doenca_atuals = $doenca_atual .'_' .$doenca_atual_quais;
    $endocrinos = $endocrino .'_' .$endocrino_quais;
    $medicacaos = $medicacao .'_' .$medicacao_quais;
    $alergiass = $alergias .'_' .$alergias_quais;
    $filhoss = $filhos .'_' .$filhos_qtd;
    $gravidezs = $gravidez .'_' .$gravidez_data;
    $alteracao_menstruals = $alteracao_menstrual .'_' .$alteracao_menstrual_quais;
    $familiaress = $familiares .'_' .$familiares_qual;
    $quimica_cabelos_atuals = $quimica_cabelos_atual .'_' .$quimica_cabelos_atual_quais;
    $exm_fisico_quimicas = $exm_fisico_quimica .'_' .$exm_fisico_quimica_quais;
    $exm_fisico_pontass = $exm_fisico_pontas .'_' .$pontas_cabelo_regiao;
    $exm_fisico_presencas = $exm_fisico_presenca .'_' .$exm_fisico_regiao;


    if($row_check > 0){
    $query = $conexao->prepare("UPDATE $tabela_formulario SET feitopor = :feitopor, nome = :nome, nascimento = :nascimento, endereco = :endereco, cep = :cep, bairro = :bairro, municipio = :municipio, uf = :uf, celular = :celular, profissao = :profissao, estado_civil = :estado_civil, queixa_principal = :queixa_principal, doenca_outras_areas = :doenca_outras_areas, doenca_outras_areas_tempo = :doenca_outras_areas_tempo, doenca_outras_areas_status = :doenca_outras_areas_status, doenca_outras_areas_cabelo = :doenca_outras_areas_cabelo, doenca_outras_areas_alteracoes = :doenca_outras_areas_alteracoes, doenca_outras_areas_crises = :doenca_outras_areas_crises, doencas_ultimas = :doencas_ultimas, doencas_atual = :doenca_atuals, endocrino = :endocrino, cardiaco = :cardiaco, marca_passo = :marca_passo, medicacao = :medicacao, precederam_problema = :precederam_problema, alergias = :alergias, filhos = :filhos, gravidez = :gravidez, carne = :carne, alteracao_menstrual = :alteracao_menstrual, familiares = :familiares, quimica_cabelos_atual = :quimica_cabelos_atual, cuidado_cabelo_usa = :cuidado_cabelo_usa, cuidado_cabelo_lavagem = :cuidado_cabelo_lavagem, cuidado_cabelo_produtos = :cuidado_cabelo_produtos, exm_fisico_volume_cabelo = :exm_fisico_volume_cabelo, exm_fisico_comprimento_cabelo = :exm_fisico_comprimento_cabelo, exm_fisico_quimica = :exm_fisico_quimica, quimica_cabelos_atual_frequencia = :quimica_cabelos_atual_frequencia, exm_fisico_cabelo = :exm_fisico_cabelo, exm_fisico_pontas = :exm_fisico_pontas, exm_fisico_couro_cabeludo = :exm_fisico_couro_cabeludo, exm_fisico_presenca = :exm_fisico_presenca, alopecia = :alopecia, alopecia_localizacao = :alopecia_localizacao, alopecia_lesoes = :alopecia_lesoes, alopecia_formato = :alopecia_formato, alopecia_formato_2 = :alopecia_formato_2, alopecia_tamanho = :alopecia_tamanho, alopecia_reposicao = :alopecia_reposicao, alopecia_couro = :alopecia_couro, alopecia_obs = :alopecia_obs, alteracao_encontrada = :alteracao_encontrada, protocolo_sugerio = :protocolo_sugerido, protocolo_realizado_01 = :protocolo_realizado_01, protocolo_realizado_02 = :protocolo_realizado_02, protocolo_realizado_03 = :protocolo_realizado_03, protocolo_realizado_04 = :protocolo_realizado_04, protocolo_realizado_01_data = :protocolo_realizado_01_data, protocolo_realizado_02_data = :protocolo_realizado_02_data, protocolo_realizado_03_data = :protocolo_realizado_03_data, protocolo_realizado_04_data = :protocolo_realizado_04_data WHERE confirmacao = :confirmacao AND email = :email");
    $query->execute(array('feitopor' => $feitopor, 'nome' => $nome, 'nascimento' => $nascimento, 'endereco' => $endereco, 'cep' => $cep, 'bairro' => $bairro, 'municipio' => $municipio, 'uf' => $uf, 'celular' => $celular, 'profissao' => $profissao, 'estado_civil' => $estado_civil, 'queixa_principal' => $queixa_principal, 'doenca_outras_areas' => $doenca_outras_areass, 'doenca_outras_areas_tempo' => $doenca_outras_areas_tempo, 'doenca_outras_areas_status' => $doenca_outras_areas_status, 'doenca_outras_areas_cabelo' => $doenca_outras_areas_cabelo, 'doenca_outras_areas_alteracoes' => $doenca_outras_areas_alteracoes, 'doenca_outras_areas_crises' => $doenca_outras_areas_crisess, 'doencas_ultimas' => $doencas_ultimas, 'doenca_atuals' => $doenca_atuals, 'endocrino' => $endocrinos, 'cardiaco' => $cardiaco, 'marca_passo' => $marca_passo, 'medicacao' => $medicacaos, 'precederam_problema' => $precederam_problema, 'alergias' => $alergiass, 'filhos' => $filhoss, 'gravidez' => $gravidezs, 'carne' => $carne, 'alteracao_menstrual' => $alteracao_menstruals, 'familiares' => $familiaress, 'quimica_cabelos_atual' => $quimica_cabelos_atuals, 'cuidado_cabelo_usa' => $cuidado_cabelo_usa, 'cuidado_cabelo_lavagem' => $cuidado_cabelo_lavagem, 'cuidado_cabelo_produtos' => $cuidado_cabelo_produtos, 'exm_fisico_volume_cabelo' => $exm_fisico_volume_cabelo, 'exm_fisico_comprimento_cabelo' => $exm_fisico_comprimento_cabelo, 'exm_fisico_quimica' => $exm_fisico_quimicas, 'quimica_cabelos_atual_frequencia' => $quimica_cabelos_atual_frequencia, 'exm_fisico_cabelo' => $exm_fisico_cabelo, 'exm_fisico_pontas' => $exm_fisico_pontass, 'exm_fisico_couro_cabeludo' => $exm_fisico_couro_cabeludo, 'exm_fisico_presenca' => $exm_fisico_presencas, 'alopecia' => $alopecia, 'alopecia_localizacao' => $alopecia_localizacao, 'alopecia_lesoes' => $alopecia_lesoes, 'alopecia_formato' => $alopecia_formato, 'alopecia_formato_2' => $alopecia_formato_2, 'alopecia_tamanho' => $alopecia_tamanho, 'alopecia_reposicao' => $alopecia_fios, 'alopecia_couro' => $alopecia_couro, 'alopecia_obs' => $alopecia_obs, 'alteracao_encontrada' => $alteracao_encontrada, 'protocolo_sugerido' => $protocolo_sugerido, 'protocolo_realizado_01' => $protocolo_realizado_01, 'protocolo_realizado_02' => $protocolo_realizado_02, 'protocolo_realizado_03' => $protocolo_realizado_03, 'protocolo_realizado_04' => $protocolo_realizado_04, 'protocolo_realizado_01_data' => $protocolo_realizado_01_data, 'protocolo_realizado_02_data' => $protocolo_realizado_02_data, 'protocolo_realizado_03_data' => $protocolo_realizado_03_data, 'protocolo_realizado_04_data' => $protocolo_realizado_04_data , 'confirmacao' => $confirmacao, 'email' => $email));
    }else{
    $query = $conexao->prepare("INSERT INTO $tabela_formulario (feitopor, nome, nascimento, endereco, cep, bairro, municipio, uf, celular, profissao, estado_civil, queixa_principal, doenca_outras_areas, doenca_outras_areas_tempo, doenca_outras_areas_status, doenca_outras_areas_cabelo, doenca_outras_areas_alteracoes, doenca_outras_areas_crises, doencas_ultimas, doencas_atual, endocrino, cardiaco, marca_passo, medicacao, precederam_problema, alergias, filhos, gravidez, carne, alteracao_menstrual, familiares, quimica_cabelos_atual, cuidado_cabelo_usa, cuidado_cabelo_lavagem, cuidado_cabelo_produtos, exm_fisico_volume_cabelo, exm_fisico_comprimento_cabelo, exm_fisico_quimica, quimica_cabelos_atual_frequencia, exm_fisico_cabelo, exm_fisico_pontas, exm_fisico_couro_cabeludo, exm_fisico_presenca, alopecia, alopecia_localizacao, alopecia_lesoes, alopecia_formato, alopecia_formato_2, alopecia_tamanho, alopecia_reposicao, alopecia_couro, alopecia_obs, alteracao_encontrada, protocolo_sugerio, protocolo_realizado_01, protocolo_realizado_02, protocolo_realizado_03, protocolo_realizado_04, protocolo_realizado_01_data, protocolo_realizado_02_data, protocolo_realizado_03_data, protocolo_realizado_04_data, confirmacao, email) VALUES (:feitopor, :nome, :nascimento, :endereco, :cep, :bairro, :municipio, :uf, :celular, :profissao, :estado_civil, :queixa_principal, :doenca_outras_areas, :doenca_outras_areas_tempo, :doenca_outras_areas_status, :doenca_outras_areas_cabelo, :doenca_outras_areas_alteracoes, :doenca_outras_areas_crises, :doencas_ultimas, :doenca_atuals, :endocrino, :cardiaco, :marca_passo, :medicacao, :precederam_problema, :alergias, :filhos, :gravidez, :carne, :alteracao_menstrual, :familiares, :quimica_cabelos_atual, :cuidado_cabelo_usa, :cuidado_cabelo_lavagem, :cuidado_cabelo_produtos, :exm_fisico_volume_cabelo, :exm_fisico_comprimento_cabelo, :exm_fisico_quimica, :quimica_cabelos_atual_frequencia, :exm_fisico_cabelo, :exm_fisico_pontas, :exm_fisico_couro_cabeludo, :exm_fisico_presenca, :alopecia, :alopecia_localizacao, :alopecia_lesoes, :alopecia_formato, :alopecia_formato_2, :alopecia_tamanho, :alopecia_reposicao, :alopecia_couro, :alopecia_obs, :alteracao_encontrada, :protocolo_sugerido, :protocolo_realizado_01, :protocolo_realizado_02, :protocolo_realizado_03, :protocolo_realizado_04, :protocolo_realizado_01_data, :protocolo_realizado_02_data, :protocolo_realizado_03_data, :protocolo_realizado_04_data, :email)");
    $query->execute(array('feitopor' => $feitopor, 'nome' => $nome, 'nascimento' => $nascimento, 'endereco' => $endereco, 'cep' => $cep, 'bairro' => $bairro, 'municipio' => $municipio, 'uf' => $uf, 'celular' => $celular, 'profissao' => $profissao, 'estado_civil' => $estado_civil, 'queixa_principal' => $queixa_principal, 'doenca_outras_areas' => $doenca_outras_areass, 'doenca_outras_areas_tempo' => $doenca_outras_areas_tempo, 'doenca_outras_areas_status' => $doenca_outras_areas_status, 'doenca_outras_areas_cabelo' => $doenca_outras_areas_cabelo, 'doenca_outras_areas_alteracoes' => $doenca_outras_areas_alteracoes, 'doenca_outras_areas_crises' => $doenca_outras_areas_crisess, 'doencas_ultimas' => $doencas_ultimas, 'doenca_atuals' => $doenca_atuals, 'endocrino' => $endocrinos, 'cardiaco' => $cardiaco, 'marca_passo' => $marca_passo, 'medicacao' => $medicacaos, 'precederam_problema' => $precederam_problema, 'alergias' => $alergiass, 'filhos' => $filhoss, 'gravidez' => $gravidezs, 'carne' => $carne, 'alteracao_menstrual' => $alteracao_menstruals, 'familiares' => $familiaress, 'quimica_cabelos_atual' => $quimica_cabelos_atuals, 'cuidado_cabelo_usa' => $cuidado_cabelo_usa, 'cuidado_cabelo_lavagem' => $cuidado_cabelo_lavagem, 'cuidado_cabelo_produtos' => $cuidado_cabelo_produtos, 'exm_fisico_volume_cabelo' => $exm_fisico_volume_cabelo, 'exm_fisico_comprimento_cabelo' => $exm_fisico_comprimento_cabelo, 'exm_fisico_quimica' => $exm_fisico_quimicas, 'quimica_cabelos_atual_frequencia' => $quimica_cabelos_atual_frequencia, 'exm_fisico_cabelo' => $exm_fisico_cabelo, 'exm_fisico_pontas' => $exm_fisico_pontass, 'exm_fisico_couro_cabeludo' => $exm_fisico_couro_cabeludo, 'exm_fisico_presenca' => $exm_fisico_presencas, 'alopecia' => $alopecia, 'alopecia_localizacao' => $alopecia_localizacao, 'alopecia_lesoes' => $alopecia_lesoes, 'alopecia_formato' => $alopecia_formato, 'alopecia_formato_2' => $alopecia_formato_2, 'alopecia_tamanho' => $alopecia_tamanho, 'alopecia_reposicao' => $alopecia_fios, 'alopecia_couro' => $alopecia_couro, 'alopecia_obs' => $alopecia_obs, 'alteracao_encontrada' => $alteracao_encontrada, 'protocolo_sugerido' => $protocolo_sugerido, 'protocolo_realizado_01' => $protocolo_realizado_01, 'protocolo_realizado_02' => $protocolo_realizado_02, 'protocolo_realizado_03' => $protocolo_realizado_03, 'protocolo_realizado_04' => $protocolo_realizado_04, 'protocolo_realizado_01_data' => $protocolo_realizado_01_data, 'protocolo_realizado_02_data' => $protocolo_realizado_02_data, 'protocolo_realizado_03_data' => $protocolo_realizado_03_data, 'protocolo_realizado_04_data' => $protocolo_realizado_04_data , 'email' => $email));
    }

    $nascimento = date('d/m/Y', strtotime("$nascimento"));
    if($id_job == 'Formulario'){
    $protocolo_realizado_01_data = date('d/m/Y', strtotime("$protocolo_realizado_01_data"));
    $protocolo_realizado_02_data = date('d/m/Y', strtotime("$protocolo_realizado_02_data"));
    $protocolo_realizado_03_data = date('d/m/Y', strtotime("$protocolo_realizado_03_data"));
    $protocolo_realizado_04_data = date('d/m/Y', strtotime("$protocolo_realizado_04_data"));

    if($alopecia_fios == 0){
    $alopecia_fios = '[X] Sim <tab> [ ] Não';
    }else{
    $alopecia_fios = '[ ] Sim <tab> [X] Não';
    }
    if($alopecia == 0){
    $alopecia = '[X] Sim <tab> [ ] Não';
    }else{
    $alopecia = '[ ] Sim <tab> [X] Não';
    }
    if($exm_fisico_pontas == 0){
    $exm_fisico_pontas = '[X] Integras<br><b>[ ] Quebradiças';
    }else{
    $exm_fisico_pontas = '[ ] Integras<br><b>[X] Quebradiças'; 
    }
    if($exm_fisico_cabelo == 0){
    $exm_fisico_cabelo = '[X] Macios <tab> [ ] Asperos <tab> [ ] Brilhantes<br><b> [ ] Opacos Presos';
    }else if($exm_fisico_cabelo == 1){
    $exm_fisico_cabelo = '[ ] Macios <tab> [X] Asperos <tab> [ ] Brilhantes<br><b> [ ] Opacos Presos';
    }else if($exm_fisico_cabelo == 2){
    $exm_fisico_cabelo = '[ ] Macios <tab> [ ] Asperos <tab> [X] Brilhantes<br><b> [ ] Opacos Presos';
    }else{
    $exm_fisico_cabelo = '[ ] Macios <tab> [ ] Asperos <tab> [ ] Brilhantes<br><b> [X] Opacos Presos';
    }
    if($exm_fisico_quimica == 0){
    $exm_fisico_quimica = '[X] Sim <tab> [ ] Não';
    }else{
    $exm_fisico_quimica = '[ ] Sim <tab> [X] Não';
    }
    if($exm_fisico_comprimento_cabelo == 0){
    $exm_fisico_comprimento_cabelo = '[X] Sim <tab> [ ] Não';
    }else{
    $exm_fisico_comprimento_cabelo = '[ ] Sim <tab> [X] Não';
    }
    if($exm_fisico_volume_cabelo == 0){
    $exm_fisico_volume_cabelo = '[X] Sim <tab> [ ] Não';
    }else{
    $exm_fisico_volume_cabelo = '[ ] Sim <tab> [X] Não';
    }
    if($quimica_cabelos_atual == 0){
    $quimica_cabelos_atual = '[X] Sim <tab> [ ] Não';
    }else{
    $quimica_cabelos_atual = '[ ] Sim <tab> [X] Não';
    }
    if($familiares == 0){
    $familiares = '[X] Sim <tab> [ ] Não';
    }else{
    $familiares = '[ ] Sim <tab> [X] Não';
    }
    if($alteracao_menstrual == 0){
    $alteracao_menstrual = '[X] Sim <tab> [ ] Não';
    }else{
    $alteracao_menstrual = '[ ] Sim <tab> [X] Não';
    }
    if($gravidez == 0){
    $gravidez = '[X] Sim <tab> [ ] Não';
    }else{
    $gravidez = '[ ] Sim <tab> [X] Não';
    }
    if($filhos == 0){
    $filhos = '[X] Sim <tab> [ ] Não';
    }else{
    $filhos = '[ ] Sim <tab> [X] Não';
    }
    if($alergias == 0){
    $alergias = '[X] Sim <tab> [ ] Não';
    }else{
    $alergias = '[ ] Sim <tab> [X] Não';
    }
    if($carne == 0){
    $carne = '[X] Sim <tab> [ ] Não';
    }else{
    $carne = '[ ] Sim <tab> [X] Não';
    }
    if($doenca_outras_areas == 0){
    $doenca_outras_areas = '[X] Sim <tab> [ ] Não';
    }else{
    $doenca_outras_areas = '[ ] Sim <tab> [X] Não';
    }
    if($doenca_atual == 0){
    $doenca_atual = '[X] Sim <tab> [ ] Não';
    }else{
    $doenca_atual = '[ ] Sim <tab> [X] Não';
    }
    if($endocrino == 0){
    $endocrino = '[X] Sim <tab> [ ] Não';
    }else{
    $endocrino = '[ ] Sim <tab> [X] Não';
    }
    if($cardiaco == 0){
    $cardiaco = '[X] Sim <tab> [ ] Não';
    }else{
    $cardiaco = '[ ] Sim <tab> [X] Não';
    }
    if($marca_passo == 0){
    $marca_passo = '[X] Sim <tab> [ ] Não';
    }else{
    $marca_passo = '[ ] Sim <tab> [X] Não';
    }
    if($medicacao == 0){
    $medicacao = '[X] Sim <tab> [ ] Não';
    }else{
    $medicacao = '[ ] Sim <tab> [X] Não';
    }
    if($doenca_outras_areas_status == 0){
    $doenca_outras_areas_status = '<b>[X] Estável<br>
    <b>[ ] Aumentando <tab>[ ] Diminuindo';
    }else if($doenca_outras_areas_status == 1){
    $doenca_outras_areas_status = '<b>[ ] Estável<br>
    <b>[X] Aumentando <tab>[ ] Diminuindo';
    }else{
    $doenca_outras_areas_status = '<b>[ ] Estável<br>
    <b>[ ] Aumentando <tab>[X] Diminuindo';
    }

    if($doenca_outras_areas_cabelo == 0){
    $doenca_outras_areas_cabelo = '[X] Estável<br>
    <b>[ ] Aumentando <tab> [ ] Diminuindo';
    }else if($doenca_outras_areas_cabelo == 1){
    $doenca_outras_areas_cabelo = '[ ] Estável<br>
    <b>[X] Aumentando <tab> [ ] Diminuindo';
    }else{
    $doenca_outras_areas_cabelo = '[ ] Estável<br>
    <b>[ ] Aumentando <tab> [X] Diminuindo';
    }

    if($doenca_outras_areas_crises == 0){
    $doenca_outras_areas_crises = '[X] Sim <tab> [ ] Não';
    }else{
    $doenca_outras_areas_crises = '[ ] Sim <tab> [X] Não';  
    }

    $gera_body = "
            <fieldset><small><u><b>Dados do Cliente</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label><small>Nome</small><b>$nome</b></label></td>
            <td><label><small>E-mail</small><b>$email</b></label></td>
            </tr><tr>
            <td><label><small>CEP</small><b>$cep</b></label></td>
            <td><label><small>Bairro</small><b>$bairro</b></label></td>
            <td><label><small>Endereço</small><b>$endereco</b></label></td>
            </tr><tr>
            <td><label><small>Municio</small><b>$municipio</b></label></td>
            <td><label><small>Estado</small><b>$uf</b></label></td>
            <td><label><small>Celular</small><b>$celular</b></label></td>
            </tr><tr>
            <td><label><small>Profissão</small><b>$profissao</b></label></td>
            <td><label><small>Estado Civil</small><b>$estado_civil</b></label></td>
            <td><label><small>Nascimento</small><b>$nascimento</b></label></td>
            </tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Terapeuta Capilar</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr><td><label><small>Terapeuta</small><b>$feitopor</b></label></td></tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Avaliação do Problema do Cliente</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label><small>Qual a Queixa Principal</small></label><br>
            <b>$queixa_principal</b></td>
            <td><label><small>A doença acomete outras areas do corpo?</small></label><br>
            <b>$doenca_outras_areas</b></td>
            <td><label><small>Quais</small></label><br>
            <b>$doenca_outras_areas_quais</b></td>
            </tr><tr>
            <td><label><small>Faz quanto tempo</small><b>$doenca_outras_areas_tempo</b></label></td>
            <td><label><small>O problema esta</small></label><br>
            <b>$doenca_outras_areas_status</b></td>
            <td><label><small>O cabelo ficou</small></label><br>
            <b>$doenca_outras_areas_cabelo</b></td>
            </tr><tr>
            <td><label><small>Apresentou alterações no Couro cabeludo como Fixo Comercial</small></label><br>
            <b>[$outras_areas_alteracoes0] Dor <tab> [$outras_areas_alteracoes1] Coceira <tab> [$outras_areas_alteracoes2] Ardor<br>
            <b>[$outras_areas_alteracoes3] Inflamação <tab> [$outras_areas_alteracoes4] Crostas <tab> [$outras_areas_alteracoes5] Feridas<br>
            <b>[$outras_areas_alteracoes6] Caspa <tab> [$outras_areas_alteracoes7] Oleosidade<br>
            <b>[$outras_areas_alteracoes8] Odor <tab> [$outras_areas_alteracoes9] Descamação</b></td>
            <td><label><small>Ja teve outras crises?</small></label><br>
            <b>$doenca_outras_areas_crises</b></td>
            <td><label><small>Quando</small></label><br>
            <b>$doenca_outras_areas_crises_quando</b></td>
            </tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Histórico Pessoal</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label><small>Descrever últimas doenças (6 meses), operações ou internações (2 anos)</small></label><br>
            <b>$doencas_ultimas</b></td>
            <td><label><small>Você tem algum doença atual?</small></label><br>
            <b>$doenca_atual</b><br>
            Quais:<b>$doenca_atual_quais</b></td> 
            <td><label><small>Você tem algum problema Endócrino?</small></label><br>
            <b>$endocrino</b><br>
            Qual:<b>$endocrino_quais</b></td>     
            </tr><tr>
            <td><label><small>É Cardiaco?</small></label><br>
            <b>$cardiaco</b></td>
            <td><label><small>Usa Marcapasso?</small></label><br>
            <b>$marca_passo</b></td>
            <td><label><small>Toma alguma medicação?</small></label><br>
            <b>$medicacao</b><br>
            Quais:<b>$medicacao_quais</b></td>
            </tr><tr>
            <td><label><small>Nos meses que vocês procederam o problema você?</small></label><br>
            <b>[$proc_prob0] Fez Dietas <tab> [$proc_prob1] Emagreceu <tab> [$proc_prob2] Engordou<br>
            <b>[$proc_prob3] Teve Crise Emocional</b></td>
            <td><label><small>Tem alergia a algum medicamento ou cosmético?</small></label><br>
            <b>$alergias</b><br>
            Quais:<b>$alergias_quais</b></td>
            <td><label><small>Come carne?</small></label><br>
            <b>$carne</b></td>
            </tr><tr>
            <td><label><small>Tem filhos?</small></label><br>
            <b>$filhos</b><br>
            Quantos:<b>$filhos_qtd</b></td>
            <td><label><small>Data ultima gravidez</small></label><br>
            <b>$gravidez_data</b></td>
            <td><label><small>A gravidez piorou o problema atual?</small></label><br>
            <b>$gravidez</b></td>
            </tr><tr>
            <td><label><small>Tem alguma alteração menstrual?</small></label><br>
            <b>$alteracao_menstrual</b><br>
            Qual:<b>$alteracao_menstrual_quais</b></td>
            <td><label><small>Alguem da familia tem ou teve o mesmo problema?</small></label><br>
            <b>$familiares</b></td>
            <td><label><small>Alguem da familia tem algum dos tipos de calvice demonstrados no quadro abaixo?</small></label><br>
            Qual:<b>$familiares_qual</b></td>
            </tr>
            </table></fieldset><br>

            FOTOS AQUI

            <fieldset><small><u><b>Cuidados com os Cabelos</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label><small>Faz química no cabelo?</small></label><br>
            <b>$quimica_cabelos_atual</b></td>
            <td><label><small>Quais?</small></label><br>
            <b>$quimica_cabelos_atual_quais</b></td>
            <td><label><small>Frequencia?</small></label><br>
            <b>$quimica_cabelos_atual_frequencia</b></td>
            </tr><tr>
            <td><label><small>Usa?</small></label><br>
            <b>[$usa0] Gel <tab>  [$usa1] Bonés <tab> [$usa2] Chapas<br>
            <b>[$usa3] Chapéu <tab> [$usa4] Penteados Presos<br>
            <b>[$usa5] Escovas <tab> [$usa6] Capacetes</b></td>
            <td><label><small>De quanto em quanto tempo lava os cabelos?</small></label><br>
            <b>$cuidado_cabelo_lavagem</b></td>
            <td><label><small>Quais produtos utiliza?</small></label><br>
            <b>$cuidado_cabelo_produtos</b></td>
            </tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Exame Físico</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label>Volume dos cabelos é o mesmo em todo couro cabeludo?</small></label><br>
            <b>$exm_fisico_volume_cabelo</b></td> 
            <td><label><small>Comprimento dos cabelos é o mesmo em todo couro cabeludo?</small></label><br>
            <b>$exm_fisico_comprimento_cabelo</b></td>
            <td><label><small>Presença de?</small></label><br>
            <b>[$exm_fisico_presenca0] Falhas<br>
            <b>[$exm_fisico_presenca1] Entradas</b></td>
            </tr><tr>
            <td><label><small>Os cabelos são</small></label><br>
            <b>$exm_fisico_cabelo</b></td>
            <td><label><small>As pontas dos cabelos são</small></label><br>
            <b>$exm_fisico_pontas</b><br>
            Qual região: <b>$pontas_cabelo_regiao</b></td> 
            <td><label><small>O couro cabeludo apresenta?</small></label><br>
            <b>[$cc_apresenta0] Oleosidade <tab> [$cc_apresenta1] Outro<br>
            <b>[$cc_apresenta2] Descamação<br>
            <b>[$cc_apresenta3] Vermelhidão <tab> [$cc_apresenta4] Manchas<br>
            <b>[$cc_apresenta5] Caspa <tab> [$cc_apresenta6] Odor</td>
            </tr><tr>
            <td><label><small>Tem algum tipo de quimica?</small></label><br>
            <b>$exm_fisico_quimica</b></td>
            <td><label><small>Quais?</small></label><br>
            <b>$exm_fisico_quimica_quais</b></td>
            <td><label><small>Retrações em que região</small></label><br>
            <b>$exm_fisico_regiao</b></td> 
            </tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Casos de Alopecia</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <tr>
            <td><label><small>Alopecia Areata e/ou Cicatricial?</small></label><br>
            <b>$alopecia</b></td>
            <td><label><small>Localização</small></label><br>
            <b>$alopecia_localizacao</b></td> 
            <td><label><small>Formato</small></label><br>
            <b>$alopecia_formato</b></td> 
            </tr><tr>
            <td><label><small>Nº de Lesões</small></label><br>
            <b>$alopecia_lesoes</b></td> 
            <td><label><small>Formato</small></label><br>
            <b>$alopecia_formato_2</b></td> 
            <td><label><small>Tamanho</small></label><br>
            <b>$alopecia_tamanho</b></td> 
            </tr><tr>
            <td><label><small>Superficie do Couro</small></label><br>
            <b>$alopecia_couro</b></td> 
            <td><label><small>Existe reposição dos fios</small></label><br>
            <b>$alopecia_fios</b></td>
            <td><label><small>Alguma observação complementar</small></label><br>
            <b>$alopecia_obs</b></td>  
            </tr>
            </table></fieldset><br>
            <fieldset><small><u><b>Alteração encontrada</b></u></small><br>
            <table width=\"100%\" border=\"2px\"><br>
            <b>$alteracao_encontrada</b>
            </table></fieldset><br>
            <fieldset><small><u><b>Protocolo Sugerio</b></u></small><br>
            <table width=\"100%\" border=\"2px\"><br>
            <b>$protocolo_sugerido</b>
            </table></fieldset><br>
            <fieldset><small><u><b>Protocolo Realizado</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <label>Data <b>$protocolo_realizado_01_data</b></label><br><br>
            <b>$protocolo_realizado_01</b>
            </table></fieldset><br>
            <fieldset><small><u><b>Protocolo Realizado</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <label>Data <b>$protocolo_realizado_02_data</b></label><br><br>
            <b>$protocolo_realizado_02</b>
            </table></fieldset><br>
            <fieldset><small><u><b>Protocolo Realizado</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <label>Data <b>$protocolo_realizado_03_data</b></label><br><br>
            <b>$protocolo_realizado_03</b>
            </table></fieldset><br>
            <fieldset><small><u><b>Protocolo Realizado</b></u></small><br>
            <table width=\"100%\" border=\"2px\">
            <label>Data <b>$protocolo_realizado_04_data</b></label><br><br>
            <b>$protocolo_realizado_04</b>
            </table></fieldset>
            ";


$dompdf = new Dompdf();
$dompdf->loadHtml('
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/gerar.css">
        <title>Formulario de Anamnese - '.$nome.'</title>
    </head><body>
    <div class="geral">
    <div class="data_gerador">
    <small>'.$data_gerador.'</small>
    </div>
    <center><h1>Formulario de Anamnese - '.$nome.'</h1></center>
    <br>
    <div class="content">
    '.$gera_body.'
    </div>
    <div class="footer">
    '.$gera_footer.'
    </div>
    </div>
    </body></html>
');

$dompdf->setPaper('A4', 'portrait');


$dompdf->render();
$dompdf->stream(
    "Formulario de Anamnese - '$nome'.pdf", 
    array(
        "Attachment" => true //Para realizar o download somente alterar para true
    )
    );

}else{
    echo   "<script>
            window.location.replace('../conf_formulario.php?token=$token')
            </script>";
}


}else if($id_job == 'lancar_despesas'){

    $despesa_dia = mysqli_real_escape_string($conn_msqli, $_POST['despesa_dia']);
    $despesa_valor = mysqli_real_escape_string($conn_msqli, $_POST['despesa_valor']);
    $despesa_tipo = mysqli_real_escape_string($conn_msqli, $_POST['despesa_tipo']);
    $despesa_descricao = mysqli_real_escape_string($conn_msqli, $_POST['despesa_descricao']);



    $query = $conexao->prepare("INSERT INTO despesas (despesa_dia, despesa_valor, despesa_tipo, despesa_descricao, despesa_quem) VALUES (:despesa_dia, :despesa_valor, :despesa_tipo, :despesa_descricao, :despesa_quem)");
    $query->execute(array('despesa_dia' => $despesa_dia, 'despesa_valor' => $despesa_valor, 'despesa_tipo' => $despesa_tipo, 'despesa_descricao' => $despesa_descricao, 'despesa_quem' => $historico_quem));

    echo "<script>
    alert('Despesa Cadastrada com Sucesso')
    window.location.replace('despesas.php')
    </script>";
    exit();


}else if($id_job == 'cadastro_contrato'){

    $procedimento_valor = mysqli_real_escape_string($conn_msqli, $_POST['procedimento_valor']);
    $procedimentos = trim(mysqli_real_escape_string($conn_msqli, $_POST['procedimentos']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);

    $query = $conexao->prepare("INSERT INTO contrato (email, assinado, assinado_data, assinado_empresa, assinado_empresa_data, procedimento, procedimento_valor, confirmacao) VALUES (:email, 'Não', :ass_data, 'Sim', :ass_data, :procedimento, :procedimento_valor, :confirmacao)");
    $query->execute(array('procedimento_valor' => $procedimento_valor, 'procedimento' => $procedimentos, 'email' => $email, 'ass_data' => date('Y-m-d H:i:s'), 'confirmacao' => $confirmacao));

    //Envio de Email	

    $data_email = date('d/m/Y \-\ H:i:s');

    $mail = new PHPMailer(true);

try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "$mail_Host";
    $mail->SMTPAuth = true;
    $mail->Username = "$mail_Username";
    $mail->Password = "$mail_Password";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = "$mail_Port";

    $mail->setFrom("$config_email", "$config_empresa");
    $mail->addAddress("$email", "$nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "Contrato $confirmacao - $config_empresa";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Contrato $confirmacao</b></legend>
    <br>
    Ola <b>$nome</b>, tudo bem?<br>
    $config_empresa lhe enviou o <b><u>Contrato $confirmacao</u></b>. Va no seu Perfil/Acompanhamentos e acesse o seu Contrato para Assinar.<br>
    <b>Contrato enviado em $data_email</b>
    </fieldset><br><fieldset>
    <legend><b><u>$config_empresa</u></legend>
    <p>CNPJ: $config_cnpj</p>
    <p>$config_telefone - $config_email</p>
    <p>$config_endereco</p></b>
    </fieldset>
    
    "; // FIM MENSAGEM

        $mail->send();

    } catch (Exception $e) {

    }

//Fim Envio de Email

    echo "<script>
    alert('Contrato Enviado com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_enviar'){

    $tratamento = mysqli_real_escape_string($conn_msqli, $_POST['tratamento']);
    $tratamento_sessao = trim(mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']));
    $tratamento_data = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_data']);
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);

    $query = $conexao->prepare("INSERT INTO tratamento (email, plano_descricao, plano_data, sessao_atual, sessao_total, sessao_status, confirmacao) VALUES (:email, :plano_descricao, :plano_data, 0, :sessao_total, 'Em Andamento', :confirmacao)");
    $query->execute(array('email' => $email, 'plano_descricao' => $tratamento, 'plano_data' => $tratamento_data, 'sessao_total' => $tratamento_sessao, 'confirmacao' => $confirmacao));

    echo "<script>
    alert('Tratamento Enviado com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_cadastrar'){

    $tratamento_sessao = mysqli_real_escape_string($conn_msqli, $_POST['tratamento_sessao']);
    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_atual = :tratamento_sessao WHERE id = :id AND email = :email AND confirmacao = :confirmacao");
    $query->execute(array('email' => $email, 'id' => $id, 'tratamento_sessao' => $tratamento_sessao, 'confirmacao' => $confirmacao));

    echo "<script>
    alert('Sessao $tratamento_sessao Cadastrada com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();


}else if($id_job == 'cadastro_tratamento_finalizar'){

    $id = trim(mysqli_real_escape_string($conn_msqli, $_POST['id']));
    $email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);

    $query = $conexao->prepare("UPDATE tratamento SET sessao_status = 'Finalizada' WHERE id = :id AND email = :email AND confirmacao = :confirmacao");
    $query->execute(array('email' => $email, 'id' => $id, 'confirmacao' => $confirmacao));

    echo "<script>
    alert('Sessao Finalizada com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();


}else if($id_job == 'formulario_enviar'){

    $email = mysqli_real_escape_string($conn_msqli, $_POST['doc_email']);
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['doc_nome']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);

    //Envio de Email	

    $data_email = date('d/m/Y \-\ H:i:s');

    $link_formulario = "<a href=\"$site_atual/formulario.php?token=$token\"'>Clique Aqui</a>";

    $mail = new PHPMailer(true);

try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = "$mail_Host";
    $mail->SMTPAuth = true;
    $mail->Username = "$mail_Username";
    $mail->Password = "$mail_Password";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = "$mail_Port";

    $mail->setFrom("$config_email", "$config_empresa");
    $mail->addAddress("$email", "$nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "Formulario Anamnese - $config_empresa";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Formulario Anamnese</b></legend>
    <br>
    Ola <b>$nome</b>, tudo bem?<br>
    $config_empresa lhe enviou um <b><u>Formulario de Anamnese</u></b>, preencha-o assim que possivel.<br>
    <p>Preencha o formulario, $link_formulario</p>
    <b>Formulario enviado em $data_email</b>
    </fieldset><br><fieldset>
    <legend><b><u>$config_empresa</u></legend>
    <p>CNPJ: $config_cnpj</p>
    <p>$config_telefone - $config_email</p>
    <p>$config_endereco</p></b>
    </fieldset>
    
    "; // FIM MENSAGEM

        $mail->send();

    } catch (Exception $e) {

    }

//Fim Envio de Email

    echo "<script>
    alert('Formulario Enviado com Sucesso')
    window.location.replace('cadastro.php?email=$email')
    </script>";
    exit();


}else if($id_job == 'arquivos'){

    $confirmacao = mysqli_real_escape_string($conn_msqli, $_POST['confirmacao']);
    $arquivo = mysqli_real_escape_string($conn_msqli, $_POST['arquivo']).'.pdf';
    $arquivos = $_FILES['arquivos'];
    $dirAtual = '../arquivos/'.$confirmacao.'/';

    if($arquivos['type'] != 'application/pdf'){
        echo "<script>
        alert('Selecione apenas arquivos tipo PDF')
        window.location.replace('reserva.php?confirmacao=$confirmacao')
        </script>";
        exit();
    }

    if (!is_dir($dirAtual)) {
        mkdir($dirAtual);
    }

    move_uploaded_file($arquivos['tmp_name'], $dirAtual.$arquivo);

    $query_historico = $conexao->prepare("INSERT INTO $tabela_historico (quando, quem, unico, oque) VALUES (:historico_data, :historico_quem, :historico_unico_usuario, :oque)");
    $query_historico->execute(array('historico_data' => $historico_data, 'historico_quem' => $historico_quem, 'historico_unico_usuario' => $historico_unico_usuario, 'oque' => "Cadastrou um novo Arquivo $arquivo na Confirmação $confirmacao"));

    echo "<script>
    alert('Arquivo Cadastrado com Sucesso $confirmacao')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();

}