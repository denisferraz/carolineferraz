<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$result_check = $conexao->prepare("SELECT * FROM painel_users WHERE token = :token");
$result_check->execute(array('token' => $token));
$row_check = $result_check->rowCount();
while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$email = $select['email'];
$nome = $select['nome'];
$telefone = $select['telefone'];
$nascimento = $select['nascimento'];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- /.website title -->
        <title><?php echo $config_empresa ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
		    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
            <link rel="stylesheet" href="painel/css/style.css">
    <script>

        $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>


        <link rel="shortcut icon" href="images/favicon.ico">
        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
    </head>

    <body>

        <!-- /.preloader -->
        <div id="preloader"></div>

        <div id="top"></div>
<!-- Cancelamento da Reserva -->
<div id="cancelamento">
    <div class="container">

        <!-- /.mail icon -->
        <div class="col-md-4 col-md-offset-4 text-center">
        <center><h1><?php echo $config_empresa ?></h1></center>
        </div>

        <!-- /.subscribe form -->
        <div class="col-md-8 col-md-offset-2">
            <div class="subscribe-form wow fadeInUp">
            <center><h4>Preencha o Formulario Abaixo</h4></center>
                <form class="news-letter" action="painel/acao.php" method="post">
                <fieldset><legend><u><b>Dados do Cliente</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Nome</label><br>
            <input type="text" minlength="5" maxlength="30" name="nome" value="<?php echo $nome ?>" required></td>
            <td><label>E-mail</label><br>
            <input minlength="10" maxlength="35" type="email" name="email" value="<?php echo $email ?>" required></td>
            </tr><tr>
            <td><label>CEP</label><br>
            <input type="text" minlength="8" maxlength="10" id="cep" name="cep" required></td>
            <td><label>Bairro</label><br>
            <input type="text" minlength="5" maxlength="30" id="bairro" name="bairro" required></td>
            <td><label>Endereço</label><br>
            <input minlength="5" maxlength="50" type="text" id="rua" name="rua" required></td>
            </tr><tr>
            <td><label>Municio</label><br>
            <input type="text" minlength="5" maxlength="30" id="cidade" name="cidade" required></td>
            <td><label>Estado</label><br>
            <input type="text" minlength="5" maxlength="30" id="uf" name="uf" required></td>
            <td><label>Celular</label><br>
            <input minlength="8" maxlength="13" type="text" name="celular" value="<?php echo $telefone ?>" required></td>
            </tr><tr>
            <td><label>Profissão</label><br>
            <input type="text" minlength="5" maxlength="30" name="profissao" required></td>
            <td><label>Estado Civil</label><br>
            <input type="text" minlength="5" maxlength="15" name="estado_civil" required></td>
            <td><label>Nascimento</label><br>
            <input type="date" name="nascimento" value="<?php echo $nascimento ?>" required></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Avaliação do Problema</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Qual a Queixa Principal</label><br>
            <textarea name="queixa_principal" rows="5" cols="35" required></textarea></td>
            <td><label>A doença acomete outras areas do corpo? </label><br><br>
            <input id="doenca_outras_areas" type="radio" name="doenca_outras_areas" value="0"><label for="doenca_outras_areas">[ Sim ]</label>
            <input id="doenca_outras_areas" type="radio" name="doenca_outras_areas" value="1"><label for="doenca_outras_areas">[ Não ]</label>
            </td>
            <td><label>Quais </label>
            <input minlength="5" maxlength="90" type="text" name="doenca_outras_areas_quais"></td>
            </tr><tr>
            <td><label>Faz quanto tempo </label>
            <input minlength="5" maxlength="30" type="text" name="doenca_outras_areas_tempo"></td>
            <td><label>O problema esta </label><br><br>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="0"><label for="outras_areas_prob">Estável</label>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="1"><label for="outras_areas_prob">Aumentando</label><br><br>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="2"><label for="outras_areas_prob">Diminuindo</label>
            </td><td><label>O cabelo ficou </label><br><br>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="0"><label for="outras_areas_cabelo">Estável</label>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="1"><label for="outras_areas_cabelo">Aumentando</label><br><br>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="2"><label for="outras_areas_cabelo">Diminuindo</label>
            </td>
            </tr><tr>
            <td><label>Apresentou alterações no Couro cabeludo como Fixo Comercial </label>
            <br><br>
            <input id="outras_areas_alteracoes0" type="checkbox" name="outras_areas_alteracoes0" value="X"><label for="outras_areas_alteracoes0">Dor</label>
            <input id="outras_areas_alteracoes1" type="checkbox" name="outras_areas_alteracoes1" value="X"><label for="outras_areas_alteracoes1">Coceira</label>
            <input id="outras_areas_alteracoes2" type="checkbox" name="outras_areas_alteracoes2" value="X"><label for="outras_areas_alteracoes2">Ardor</label>
            <input id="outras_areas_alteracoes3" type="checkbox" name="outras_areas_alteracoes3" value="X"><label for="outras_areas_alteracoes3">Inflamação</label>
            <br><br>
            <input id="outras_areas_alteracoes4" type="checkbox" name="outras_areas_alteracoes4" value="X"><label for="outras_areas_alteracoes4">Crostas</label>
            <input id="outras_areas_alteracoes5" type="checkbox" name="outras_areas_alteracoes5" value="X"><label for="outras_areas_alteracoes5">Feridas</label>
            <input id="outras_areas_alteracoes6" type="checkbox" name="outras_areas_alteracoes6" value="X"><label for="outras_areas_alteracoes6">Caspa</label>
            <input id="outras_areas_alteracoes7" type="checkbox" name="outras_areas_alteracoes7" value="X"><label for="outras_areas_alteracoes7">Oleosidade</label>
            <br><br>
            <input id="outras_areas_alteracoes8" type="checkbox" name="outras_areas_alteracoes8" value="X"><label for="outras_areas_alteracoes8">Odor</label>
            <input id="outras_areas_alteracoes9" type="checkbox" name="outras_areas_alteracoes9" value="X"><label for="outras_areas_alteracoes9">Descamação</label>
            </td><td><label>Ja teve outras crises? </label><br><br>
            <input id="doenca_outras_areas_crises" type="radio" name="doenca_outras_areas_crises" value="0"><label for="doenca_outras_areas_crises">[ Sim ]</label>
            <input id="doenca_outras_areas_crises" type="radio" name="doenca_outras_areas_crises" value="1"><label for="doenca_outras_areas_crises">[ Não ]</label>
            </td>
            <td><label>Quando </label>
            <input minlength="8" maxlength="50" type="text" name="doenca_outras_areas_crises_quando" required></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Histórico Pessoal</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Descrever últimas doenças (6 meses), operações ou internações (2 anos)</label><br>
            <textarea name="doencas_ultimas" rows="5" cols="35" required></textarea></td>
            <td><label>Você tem algum doença atual? </label><br><br>
            <input id="doenca_atual" type="radio" name="doenca_atual" value="0"><label for="doenca_atual">[ Sim ]</label>
            <input id="doenca_atual" type="radio" name="doenca_atual" value="1"><label for="doenca_atual">[ Não ]</label><br><br>
            <input minlength="5" maxlength="140" type="text" name="doenca_atual_quais"></td> 
            <td><label>Você tem algum problema Endócrino? </label><br><br>
            <input id="endocrino" type="radio" name="endocrino" value="0"><label for="endocrino">[ Sim ]</label>
            <input id="endocrino" type="radio" name="endocrino" value="1"><label for="endocrino">[ Não ]</label><br><br>
            <input minlength="5" maxlength="150" type="text" name="endocrino_quais"></td>     
            </tr><tr>
            <td><label>É Cardiaco? </label><br><br>
            <input id="cardiaco" type="radio" name="cardiaco" value="0"><label for="cardiaco">[ Sim ]</label>
            <input id="cardiaco" type="radio" name="cardiaco" value="1"><label for="cardiaco">[ Não ]</label></td>
            <td><label>Usa Marcapasso? </label><br><br>
            <input id="marcapasso" type="radio" name="marcapasso" value="0"><label for="marcapasso">[ Sim ]</label>
            <input id="marcapasso" type="radio" name="marcapasso" value="1"><label for="marcapasso">[ Não ]</label></td>
            <td><label>Toma alguma medicação? </label><br><br>
            <input id="medicacao" type="radio" name="medicacao" value="0"><label for="medicacao">[ Sim ]</label>
            <input id="medicacao" type="radio" name="medicacao" value="1"><label for="medicacao">[ Não ]</label><br><br>
            <input minlength="5" maxlength="140" type="text" name="medicacao_quais" value="<?php echo substr("$medicacao", 2) ?>" required></td>
            </tr><tr>
            <td><label>Nos meses que vocês procederam o problema você? </label><br><br>
            <input id="proc_prob0" type="checkbox" name="proc_prob0" value="X"><label for="proc_prob0">Fez Dietas</label>
            <input id="proc_prob1" type="checkbox" name="proc_prob1" value="X"><label for="proc_prob1">Emagreceu</label>
            <input id="proc_prob2" type="checkbox" name="proc_prob2" value="X"><label for="proc_prob2">Engordou</label><br><br>
            <input id="proc_prob3" type="checkbox" name="proc_prob3" value="X"><label for="proc_prob3">Teve Crise Emocional</label></td>
            <td><label>Tem alergia a algum medicamento ou cosmético? </label><br><br>
            <input id="alergia" type="radio" name="alergia" value="0"><label for="alergia">[ Sim ]</label>
            <input id="alergia" type="radio" name="alergia" value="1"><label for="alergia">[ Não ]</label><br><br>
            <input minlength="5" maxlength="30" type="text" name="alergia_quais" value="<?php echo substr("$alergias", 2) ?>" required></td>
            <td><label>Come carne? </label><br><br>
            <input id="carne" type="radio" name="carne" value="0"><label for="carne">[ Sim ]</label>
            <input id="carne" type="radio" name="carne" value="1"><label for="carne">[ Não ]</label></td>
            </tr><tr>
            <td><label>Tem filhos? </label><br><br>
            <input id="filhos" type="radio" name="filhos" value="0"><label for="filhos">[ Sim ]</label>
            <input id="filhos" type="radio" name="filhos" value="1"><label for="filhos">[ Não ]</label><br><br>
            <input min="1" max="9" type="number" name="filhos_qtd"></td>
            <td><label>Data ultima gravidez </label>
            <input minlength="8" maxlength="15" type="text" name="gravidez_data" required></td>
            <td><label>A gravidez piorou o problema atual? </label><br><br>
            <input id="gravidez" type="radio" name="gravidez" value="0"><label for="gravidez">[ Sim ]</label>
            <input id="gravidez" type="radio" name="gravidez" value="1"><label for="gravidez">[ Não ]</label></td>
            </tr><tr>
            <td><label>Tem alguma alteração menstrual? </label><br><br>
            <input id="alteracao_menstrual" type="radio" name="alteracao_menstrual" value="0"><label for="alteracao_menstrual">[ Sim ]</label>
            <input id="alteracao_menstrual" type="radio" name="alteracao_menstrual" value="1"><label for="alteracao_menstrual">[ Não ]</label><br><br>
            <input minlength="5" maxlength="140" type="text" name="alteracao_menstrual_quais"></td>
            <td><label>Alguem da familia tem ou teve o mesmo problema? </label><br><br>
            <input id="familiares" type="radio" name="familiares" value="0"><label for="familiares">[ Sim ]</label>
            <input id="familiares" type="radio" name="familiares" value="1"><label for="familiares">[ Não ]</label></td>
            <td><label>Alguem da familia tem algum dos tipos de calvice demonstrados no quadro abaixo? </label>
            <input minlength="1" maxlength="5" type="text" name="familiares_qual" required></td>
            </tr>
            </table></fieldset><br>

            FOTOS AQUI

            <fieldset><legend><u><b>Cuidados com os Cabelos</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Faz química no cabelo? </label><br><br>
            <input id="quimica_cabelo" type="radio" name="quimica_cabelo" value="0"><label for="quimica_cabelo">[ Sim ]</label>
            <input id="quimica_cabelo" type="radio" name="quimica_cabelo" value="1"><label for="quimica_cabelo">[ Não ]</label><br><br>
            <input minlength="5" maxlength="30" type="text" name="quimica_cabelo_quais"></td> 
            <td><label>Frequencia? </label>
            <input minlength="5" maxlength="30" type="text" name="quimica_cabelos_atual_frequencia"></td>
            <td><label>Usa? </label><br><br>
            <input id="usa0" type="checkbox" name="usa0" value="X"><label for="usa0">Gel</label>
            <input id="usa1" type="checkbox" name="usa1" value="X"><label for="usa1">Bonés</label>
            <input id="usa2" type="checkbox" name="usa2" value="X"><label for="usa2">Chapas</label><br><br>
            <input id="usa3" type="checkbox" name="usa3" value="X"><label for="usa3">Chapéu</label>
            <input id="usa4" type="checkbox" name="usa4" value="X"><label for="usa4">Penteados Presos</label><br><br>
            <input id="usa5" type="checkbox" name="usa5" value="X"><label for="usa5">Escovas</label>
            <input id="usa6" type="checkbox" name="usa6" value="X"><label for="usa6">Capacetes</label></td>
            </tr><tr>
            <td><label>De quanto em quanto tempo lava os cabelos? </label>
            <input minlength="5" maxlength="30" type="text" name="cuidado_cabelo_lavagem" required></td>
            <td><label>Quais produtos utiliza? </label>
            <input minlength="5" maxlength="30" type="text" name="cuidado_cabelo_produtos" required></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Exame Físico</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Volume dos cabelos é o mesmo em todo couro cabeludo? </label><br><br>
            <input id="volume_cabelo" type="radio" name="volume_cabelo" value="0"><label for="volume_cabelo">[ Sim ]</label>
            <input id="volume_cabelo" type="radio" name="volume_cabelo" value="1"><label for="volume_cabelo">[ Não ]</label></td> 
            <td><label>Comprimento dos cabelos é o mesmo em todo couro cabeludo? </label><br><br>
            <input id="comprimento_cabelo" type="radio" name="comprimento_cabelo" value="0"><label for="comprimento_cabelo">[ Sim ]</label>
            <input id="comprimento_cabelo" type="radio" name="comprimento_cabelo" value="1"><label for="comprimento_cabelo">[ Não ]</label></td>
            <td><label>Tem algum tipo de quimica? </label><br><br>
            <input id="exm_fisico_quimica" type="radio" name="exm_fisico_quimica" value="0"><label for="exm_fisico_quimica">[ Sim ]</label>
            <input id="exm_fisico_quimica" type="radio" name="exm_fisico_quimica" value="1"><label for="exm_fisico_quimica">[ Não ]</label><br><br>
            <input minlength="10" maxlength="35" type="text" name="exm_fisico_quimica_quais" required></td> 
            </tr><tr>
            <td><label>Os cabelos são </label><br><br>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="0"><label for="tipo_cabelo">Macios</label>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="1"><label for="tipo_cabelo">Asperos</label><br><br>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="2"><label for="tipo_cabelo">Brilhantes</label>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="3"><label for="tipo_cabelo">Opacos Presos</label></td>
            <td><label>As pontas dos cabelos são </label><br><br>
            <input id="pontas_cabelo" type="radio" name="pontas_cabelo" value="0"><label for="pontas_cabelo">Integras</label>
            <input id="pontas_cabelo" type="radio" name="pontas_cabelo" value="1"><label for="pontas_cabelo">Quebradiças</label><br><br>
            <input minlength="5" maxlength="30" type="text" name="pontas_cabelo_regiao" required></td> 
            <td><label>O couro cabeludo apresenta? </label><br><br>
            <input id="cc_apresenta0" type="checkbox" name="cc_apresenta0" value="X"><label for="cc_apresenta0">Oleosidade</label>
            <input id="cc_apresenta1" type="checkbox" name="cc_apresenta1" value="X"><label for="cc_apresenta1">Descamação</label><br><br>
            <input id="cc_apresenta2" type="checkbox" name="cc_apresenta2" value="X"><label for="cc_apresenta2">Vermelhidão</label>
            <input id="cc_apresenta3" type="checkbox" name="cc_apresenta3" value="X"><label for="cc_apresenta3">Manchas</label><br><br>
            <input id="cc_apresenta4" type="checkbox" name="cc_apresenta4" value="X"><label for="cc_apresenta4">Caspa</label>
            <input id="cc_apresenta5" type="checkbox" name="cc_apresenta5" value="X"><label for="cc_apresenta5">Odor</label>
            <input id="cc_apresenta6" type="checkbox" name="cc_apresenta6" value="X"><label for="cc_apresenta6">Outro</label></td>
            </tr><tr>
            <td><label>Presença de? </label><br><br>
            <input id="exm_fisico_presenca0" type="checkbox" name="exm_fisico_presenca0" value="X"><label for="exm_fisico_presenca0">Falhas</label>
            <input id="exm_fisico_presenca1" type="checkbox" name="exm_fisico_presenca1" value="X"><label for="exm_fisico_presenca1">Entradas</label></td>
            <td><label>Retrações em que região </label>
            <input minlength="5" maxlength="90" type="text" name="exm_fisico_regiao"></td> 
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Casos de Alopecia</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Alopecia Areata e/ou Cicatricial? </label><br><br>
            <input id="alopecia" type="radio" name="alopecia" value="0"><label for="alopecia">[ Sim ]</label>
            <input id="alopecia" type="radio" name="alopecia" value="1"><label for="alopecia">[ Não ]</label></td>
            <td><label>Localização </label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_localizacao" required></td> 
            <td><label>Formato </label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_formato" required></td> 
            </tr><tr>
            <td><label>Nº de Lesões </label>
            <input min="1" max="10" type="number" name="alopecia_lesoes" required></td> 
            <td><label>Formato</label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_formato_2" required></td> 
            <td><label>Tamanho </label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_tamanho" required></td> 
            </tr><tr>
            <td><label>Superficie do Couro </label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_couro" required></td> 
            <td><label>Existe reposição dos fios</label><br><br>
            <input id="alopecia_fios" type="radio" name="alopecia_fios" value="0"><label for="alopecia_fios">[ Sim ]</label>
            <input id="alopecia_fios" type="radio" name="alopecia_fios" value="1"><label for="alopecia_fios">[ Não ]</label></td>
            <td><label>Alguma observação complementar</label>
            <input minlength="5" maxlength="30" type="text" name="alopecia_obs"></td>  
            </tr>
            </table></fieldset>
            <input type="hidden" name="id_job" value="Formulario_2">
            <input type="hidden" name="alteracao_encontrada" value="A Preencher">
            <input type="hidden" name="protocolo_sugerido" value="A Preencher">
            <input type="hidden" name="protocolo_realizado_01" value="A Preencher">
            <input type="hidden" name="protocolo_realizado_02" value="A Preencher">
            <input type="hidden" name="protocolo_realizado_03" value="A Preencher">
            <input type="hidden" name="protocolo_realizado_04" value="A Preencher">
            <input type="hidden" name="protocolo_realizado_01_data" value="<?php echo $hoje ?>">
            <input type="hidden" name="protocolo_realizado_02_data" value="<?php echo $hoje ?>">
            <input type="hidden" name="protocolo_realizado_03_data" value="<?php echo $hoje ?>">
            <input type="hidden" name="protocolo_realizado_04_data" value="<?php echo $hoje ?>">
            <br>
            <div class="card-group-black btn"><button type="submit">Enviar Formulario</button></div>
                </form>
            </div>
        </div>
    </div>
    <br><br>
    <center>Site Oficial | <a href="index.php" target="_blank"><?php echo $config_empresa ?></a></center>
    <br><br>
</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.footer -->
        <footer id="footer">
            <div class="container">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <center><b><u>
					<p><?php echo $config_empresa ?><br>CNPJ: <?php echo $config_cnpj ?></p>
                    <p><?php echo $config_telefone ?> - <?php echo $config_email ?></p>
                    <p><?php echo $config_endereco ?></p>
                </u></b></center>
                </div>
            </div>
        </footer>

        <!-- /.javascript files -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.downCount.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqJ08ftKoeJo_IuXwTlRozICJNtNczM-M"></script>
        <script src="js/wow.min.js"></script>

        <script src="js/custom.js"></script>
        <script type="text/javascript">

        </script>

    </body>
</html>
