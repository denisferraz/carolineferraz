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

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancelar Reserva</title>

    <link rel="stylesheet" href="css/style_v2.css">
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
</head>
<body>
<?php
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

$query_2 = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query_2->execute(array('email' => $email));
while($select_2 = $query_2->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select_2['nome'];
$doc_telefone = $select_2['telefone'];
$token = $select_2['token'];
$nascimento = $select_2['nascimento'];
}

if($id_job == 'Ver'){
$query = $conexao->prepare("SELECT * FROM formulario_atendimento WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$feitopor = $select['feitopor'];
$endereco = $select['endereco'];
$cep = $select['cep'];
$bairro = $select['bairro'];
$municipio = $select['municipio'];
$uf = $select['uf'];
$profissao = $select['profissao'];
$estado_civil = $select['estado_civil'];
$queixa_principal = $select['queixa_principal'];
$doenca_outras_areas = $select['doenca_outras_areas'];
$doenca_outras_areas_tempo = $select['doenca_outras_areas_tempo'];
$doenca_outras_areas_status = $select['doenca_outras_areas_status'];
$doenca_outras_areas_cabelo = $select['doenca_outras_areas_cabelo'];
$doenca_outras_areas_alteracoes = $select['doenca_outras_areas_alteracoes'];
$doenca_outras_areas_crises = $select['doenca_outras_areas_crises'];
$doencas_ultimas = $select['doencas_ultimas'];
$doencas_atual = $select['doencas_atual'];
$endocrino = $select['endocrino'];
$cardiaco = $select['cardiaco'];
$marca_passo = $select['marca_passo'];
$medicacao = $select['medicacao'];
$precederam_problema = $select['precederam_problema'];
$alergias = $select['alergias'];
$filhos = $select['filhos'];
$gravidez = $select['gravidez'];
$carne = $select['carne'];
$alteracao_menstrual = $select['alteracao_menstrual'];
$familiares = $select['familiares'];
$quimica_cabelos_atual = $select['quimica_cabelos_atual'];
$quimica_cabelos_atual_frequencia = $select['quimica_cabelos_atual_frequencia'];
$cuidado_cabelo_usa = $select['cuidado_cabelo_usa'];
$cuidado_cabelo_lavagem = $select['cuidado_cabelo_lavagem'];
$cuidado_cabelo_produtos = $select['cuidado_cabelo_produtos'];
$exm_fisico_volume_cabelo = $select['exm_fisico_volume_cabelo'];
$exm_fisico_comprimento_cabelo = $select['exm_fisico_comprimento_cabelo'];
$exm_fisico_quimica = $select['exm_fisico_quimica'];
$exm_fisico_cabelo = $select['exm_fisico_cabelo'];
$exm_fisico_pontas = $select['exm_fisico_pontas'];
$exm_fisico_couro_cabeludo = $select['exm_fisico_couro_cabeludo'];
$exm_fisico_presenca = $select['exm_fisico_presenca'];
$alopecia = $select['alopecia'];
$alopecia_localizacao = $select['alopecia_localizacao'];
$alopecia_lesoes = $select['alopecia_lesoes'];
$alopecia_formato = $select['alopecia_formato'];
$alopecia_formato_2 = $select['alopecia_formato_2'];
$alopecia_tamanho = $select['alopecia_tamanho'];
$alopecia_reposicao = $select['alopecia_reposicao'];
$alopecia_couro = $select['alopecia_couro'];
$alopecia_obs = $select['alopecia_obs'];
$alteracao_encontrada = $select['alteracao_encontrada'];
$protocolo_sugerido = $select['protocolo_sugerido'];
$protocolo_realizado_01 = $select['protocolo_realizado_01'];
$protocolo_realizado_01_data = $select['protocolo_realizado_01_data'];
$protocolo_realizado_02 = $select['protocolo_realizado_02'];
$protocolo_realizado_02_data = $select['protocolo_realizado_02_data'];
$protocolo_realizado_03 = $select['protocolo_realizado_03'];
$protocolo_realizado_03_data = $select['protocolo_realizado_03_data'];
$protocolo_realizado_04 = $select['protocolo_realizado_04'];
$protocolo_realizado_04_data = $select['protocolo_realizado_04_data'];
}
?>
<form action="acao.php" method="POST">
        <div>
            <div class="card-top">
                <h2>Formulario</h2>
            </div>
            <div class="card-group">
            <fieldset><legend><u><b>Dados do Cliente</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Nº Confirmação </label>
            <input width="100%" type="text" minlength="10" maxlength="10" name="confirmacao" value="<?php echo $confirmacao ?>" required></td>
            <td><label>Nome </label>
            <input type="text" minlength="5" maxlength="30" name="nome" value="<?php echo $doc_nome ?>" required></td>
            <td><label>E-mail </label>
            <input minlength="10" maxlength="35" type="email" name="email" value="<?php echo $email ?>" required></td>
            </tr><tr>
            <td><label>CEP </label>
            <input type="text" minlength="8" maxlength="10" id="cep" name="cep" value="<?php echo $cep ?>"></td>
            <td><label>Bairro </label>
            <input type="text" minlength="5" maxlength="30" id="bairro" name="bairro" value="<?php echo $bairro ?>"></td>
            <td><label>Endereço </label>
            <input minlength="5" maxlength="50" type="text" id="rua" name="rua" value="<?php echo $endereco ?>"></td>
            </tr><tr>
            <td><label>Municio </label>
            <input type="text" minlength="5" maxlength="30" id="cidade" name="cidade" value="<?php echo $municipio ?>"></td>
            <td><label>Estado </label>
            <input type="text" minlength="5" maxlength="30" id="uf" name="uf" value="<?php echo $uf ?>"></td>
            <td><label>Celular </label>
            <input minlength="8" maxlength="13" type="text" name="celular" value="<?php echo $doc_telefone ?>"></td>
            </tr><tr>
            <td><label>Profissão </label>
            <input type="text" minlength="5" maxlength="30" name="profissao" value="<?php echo $profissao ?>"></td>
            <td><label>Estado Civil </label>
            <input type="text" minlength="5" maxlength="15" name="estado_civil" value="<?php echo $estado_civil ?>"></td>
            <td><label>Nascimento </label>
            <input type="date" name="nascimento" value="<?php echo $nascimento ?>"></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Terapeuta Capilar</b></u></legend>
            <table width="100%" border="2px">
            <tr><td>
            <label>Terapeuta </label>
            <input minlength="5" maxlength="30" type="text" name="feitopor" value="<?php echo $feitopor ?>">
            </td></tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Avaliação do Problema do Cliente</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Qual a Queixa Principal</label><br>
            <textarea name="queixa_principal" maxlength="155" rows="5" cols="35"><?php echo $queixa_principal ?></textarea></td>
            <td><label>A doença acomete outras areas do corpo? </label><br><br>
            <input id="doenca_outras_areas" type="radio" name="doenca_outras_areas" value="0" <?php if($doenca_outras_areas[0] == 0){?>checked<?php } ?>><label for="doenca_outras_areas">[ Sim ]</label>
            <input id="doenca_outras_areas" type="radio" name="doenca_outras_areas" value="1" <?php if($doenca_outras_areas[0] == 1){?>checked<?php } ?>><label for="doenca_outras_areas">[ Não ]</label>
            </td>
            <td><label>Quais </label>
            <input minlength="1" maxlength="90" type="text" name="doenca_outras_areas_quais" value="<?php echo substr("$doenca_outras_areas", 2) ?>"></td>
            </tr><tr>
            <td><label>Faz quanto tempo </label>
            <input minlength="1" maxlength="30" type="text" name="doenca_outras_areas_tempo" value="<?php echo $doenca_outras_areas_tempo ?>"></td>
            <td><label>O problema esta </label><br><br>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="0" <?php if($doenca_outras_areas_status == 0){?>checked<?php } ?>><label for="outras_areas_prob">Estável</label>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="1" <?php if($doenca_outras_areas_status == 1){?>checked<?php } ?>><label for="outras_areas_prob">Aumentando</label><br><br>
            <input id="outras_areas_prob" type="radio" name="outras_areas_prob" value="2" <?php if($doenca_outras_areas_status == 2){?>checked<?php } ?>><label for="outras_areas_prob">Diminuindo</label>
            </td><td><label>O cabelo ficou </label><br><br>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="0" <?php if($doenca_outras_areas_cabelo == 0){?>checked<?php } ?>><label for="outras_areas_cabelo">Estável</label>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="1" <?php if($doenca_outras_areas_cabelo == 1){?>checked<?php } ?>><label for="outras_areas_cabelo">Aumentando</label><br><br>
            <input id="outras_areas_cabelo" type="radio" name="outras_areas_cabelo" value="2" <?php if($doenca_outras_areas_cabelo == 2){?>checked<?php } ?>><label for="outras_areas_cabelo">Diminuindo</label>
            </td>
            </tr><tr>
            <td><label>Apresentou alterações no Couro cabeludo como Fixo Comercial </label>
            <br><br>
            <input id="outras_areas_alteracoes0" type="checkbox" name="outras_areas_alteracoes0" value="X" <?php if($doenca_outras_areas_alteracoes[0] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes0">Dor</label>
            <input id="outras_areas_alteracoes1" type="checkbox" name="outras_areas_alteracoes1" value="X" <?php if($doenca_outras_areas_alteracoes[1] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes1">Coceira</label>
            <input id="outras_areas_alteracoes2" type="checkbox" name="outras_areas_alteracoes2" value="X" <?php if($doenca_outras_areas_alteracoes[2] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes2">Ardor</label>
            <input id="outras_areas_alteracoes3" type="checkbox" name="outras_areas_alteracoes3" value="X" <?php if($doenca_outras_areas_alteracoes[3] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes3">Inflamação</label>
            <br><br>
            <input id="outras_areas_alteracoes4" type="checkbox" name="outras_areas_alteracoes4" value="X" <?php if($doenca_outras_areas_alteracoes[4] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes4">Crostas</label>
            <input id="outras_areas_alteracoes5" type="checkbox" name="outras_areas_alteracoes5" value="X" <?php if($doenca_outras_areas_alteracoes[5] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes5">Feridas</label>
            <input id="outras_areas_alteracoes6" type="checkbox" name="outras_areas_alteracoes6" value="X" <?php if($doenca_outras_areas_alteracoes[6] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes6">Caspa</label>
            <input id="outras_areas_alteracoes7" type="checkbox" name="outras_areas_alteracoes7" value="X" <?php if($doenca_outras_areas_alteracoes[7] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes7">Oleosidade</label>
            <br><br>
            <input id="outras_areas_alteracoes8" type="checkbox" name="outras_areas_alteracoes8" value="X" <?php if($doenca_outras_areas_alteracoes[8] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes8">Odor</label>
            <input id="outras_areas_alteracoes9" type="checkbox" name="outras_areas_alteracoes9" value="X" <?php if($doenca_outras_areas_alteracoes[9] == 0){?>checked<?php } ?>><label for="outras_areas_alteracoes9">Descamação</label>
            </td><td><label>Ja teve outras crises? </label><br><br>
            <input id="doenca_outras_areas_crises" type="radio" name="doenca_outras_areas_crises" value="0" <?php if($doenca_outras_areas_crises[0] == 0){?>checked<?php } ?>><label for="doenca_outras_areas_crises">[ Sim ]</label>
            <input id="doenca_outras_areas_crises" type="radio" name="doenca_outras_areas_crises" value="1" <?php if($doenca_outras_areas_crises[0] == 1){?>checked<?php } ?>><label for="doenca_outras_areas_crises">[ Não ]</label>
            </td>
            <td><label>Quando </label>
            <input minlength="1" maxlength="50" type="text" name="doenca_outras_areas_crises_quando" value="<?php echo substr("$doenca_outras_areas_crises", 2) ?>"></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Histórico Pessoal</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Descrever últimas doenças (6 meses), operações ou internações (2 anos)</label><br>
            <textarea name="doencas_ultimas" maxlength="155" rows="5" cols="35"><?php echo $doencas_ultimas ?></textarea></td>
            <td><label>Você tem algum doença atual? </label><br><br>
            <input id="doenca_atual" type="radio" name="doenca_atual" value="0" <?php if($doencas_atual[0] == 0){?>checked<?php } ?>><label for="doenca_atual">[ Sim ]</label>
            <input id="doenca_atual" type="radio" name="doenca_atual" value="1" <?php if($doencas_atual[0] == 1){?>checked<?php } ?>><label for="doenca_atual">[ Não ]</label><br><br>
            <input minlength="1" maxlength="140" type="text" name="doenca_atual_quais" value="<?php echo substr("$doencas_atual", 2) ?>"></td> 
            <td><label>Você tem algum problema Endócrino? </label><br><br>
            <input id="endocrino" type="radio" name="endocrino" value="0" <?php if($endocrino[0] == 0){?>checked<?php } ?>><label for="endocrino">[ Sim ]</label>
            <input id="endocrino" type="radio" name="endocrino" value="1" <?php if($endocrino[0] == 1){?>checked<?php } ?>><label for="endocrino">[ Não ]</label><br><br>
            <input minlength="1" maxlength="150" type="text" name="endocrino_quais" value="<?php echo substr("$endocrino", 2) ?>"></td>     
            </tr><tr>
            <td><label>É Cardiaco? </label><br><br>
            <input id="cardiaco" type="radio" name="cardiaco" value="0" <?php if($cardiaco == 0){?>checked<?php } ?>><label for="cardiaco">[ Sim ]</label>
            <input id="cardiaco" type="radio" name="cardiaco" value="1" <?php if($cardiaco == 1){?>checked<?php } ?>><label for="cardiaco">[ Não ]</label></td>
            <td><label>Usa Marcapasso? </label><br><br>
            <input id="marcapasso" type="radio" name="marcapasso" value="0" <?php if($marca_passo == 0){?>checked<?php } ?>><label for="marcapasso">[ Sim ]</label>
            <input id="marcapasso" type="radio" name="marcapasso" value="1" <?php if($marca_passo == 1){?>checked<?php } ?>><label for="marcapasso">[ Não ]</label></td>
            <td><label>Toma alguma medicação? </label><br><br>
            <input id="medicacao" type="radio" name="medicacao" value="0" <?php if($medicacao[0] == 0){?>checked<?php } ?>><label for="medicacao">[ Sim ]</label>
            <input id="medicacao" type="radio" name="medicacao" value="1" <?php if($medicacao[0] == 1){?>checked<?php } ?>><label for="medicacao">[ Não ]</label><br><br>
            <input minlength="1" maxlength="140" type="text" name="medicacao_quais" value="<?php echo substr("$medicacao", 2) ?>"></td>
            </tr><tr>
            <td><label>Nos meses que vocês procederam o problema você? </label><br><br>
            <input id="proc_prob0" type="checkbox" name="proc_prob0" value="X" <?php if($precederam_problema[0] == 0){?>checked<?php } ?>><label for="proc_prob0">Fez Dietas</label>
            <input id="proc_prob1" type="checkbox" name="proc_prob1" value="X" <?php if($precederam_problema[1] == 0){?>checked<?php } ?>><label for="proc_prob1">Emagreceu</label>
            <input id="proc_prob2" type="checkbox" name="proc_prob2" value="X" <?php if($precederam_problema[2] == 0){?>checked<?php } ?>><label for="proc_prob2">Engordou</label><br><br>
            <input id="proc_prob3" type="checkbox" name="proc_prob3" value="X" <?php if($precederam_problema[3] == 0){?>checked<?php } ?>><label for="proc_prob3">Teve Crise Emocional</label></td>
            <td><label>Tem alergia a algum medicamento ou cosmético? </label><br><br>
            <input id="alergia" type="radio" name="alergia" value="0" <?php if($alergias[0] == 0){?>checked<?php } ?>><label for="alergia">[ Sim ]</label>
            <input id="alergia" type="radio" name="alergia" value="1" <?php if($alergias[0] == 1){?>checked<?php } ?>><label for="alergia">[ Não ]</label><br><br>
            <input minlength="1" maxlength="100" type="text" name="alergia_quais" value="<?php echo substr("$alergias", 2) ?>"></td>
            <td><label>Come carne? </label><br><br>
            <input id="carne" type="radio" name="carne" value="0" <?php if($carne == 0){?>checked<?php } ?>><label for="carne">[ Sim ]</label>
            <input id="carne" type="radio" name="carne" value="1" <?php if($carne == 1){?>checked<?php } ?>><label for="carne">[ Não ]</label></td>
            </tr><tr>
            <td><label>Tem filhos? </label><br><br>
            <input id="filhos" type="radio" name="filhos" value="0" <?php if($filhos[0] == 0){?>checked<?php } ?>><label for="filhos">[ Sim ]</label>
            <input id="filhos" type="radio" name="filhos" value="1" <?php if($filhos[0] == 1){?>checked<?php } ?>><label for="filhos">[ Não ]</label><br><br>
            <input min="0" max="9" type="number" name="filhos_qtd" value="<?php echo substr("$filhos", 2) ?>"></td>
            <td><label>Data ultima gravidez </label>
            <input minlength="1" maxlength="13" type="text" name="gravidez_data" value="<?php echo substr("$gravidez", 2) ?>"></td>
            <td><label>A gravidez piorou o problema atual? </label><br><br>
            <input id="gravidez" type="radio" name="gravidez" value="0" <?php if($gravidez[0] == 0){?>checked<?php } ?>><label for="gravidez">[ Sim ]</label>
            <input id="gravidez" type="radio" name="gravidez" value="1" <?php if($gravidez[0] == 1){?>checked<?php } ?>><label for="gravidez">[ Não ]</label></td>
            </tr><tr>
            <td><label>Tem alguma alteração menstrual? </label><br><br>
            <input id="alteracao_menstrual" type="radio" name="alteracao_menstrual" value="0" <?php if($alteracao_menstrual[0] == 0){?>checked<?php } ?>><label for="alteracao_menstrual">[ Sim ]</label>
            <input id="alteracao_menstrual" type="radio" name="alteracao_menstrual" value="1" <?php if($alteracao_menstrual[0] == 1){?>checked<?php } ?>><label for="alteracao_menstrual">[ Não ]</label><br><br>
            <input minlength="1" maxlength="140" type="text" name="alteracao_menstrual_quais" value="<?php echo substr("$alteracao_menstrual", 2) ?>"></td>
            <td><label>Alguem da familia tem ou teve o mesmo problema? </label><br><br>
            <input id="familiares" type="radio" name="familiares" value="0" <?php if($familiares[0] == 0){?>checked<?php } ?>><label for="familiares">[ Sim ]</label>
            <input id="familiares" type="radio" name="familiares" value="1" <?php if($familiares[0] == 1){?>checked<?php } ?>><label for="familiares">[ Não ]</label></td>
            <td><label>Alguem da familia tem algum dos tipos de calvice demonstrados no quadro abaixo? </label>
            <input minlength="1" maxlength="25" type="text" name="familiares_qual" value="<?php echo substr("$familiares", 2) ?>"></td>
            </tr>
            </table></fieldset><br>

            FOTOS AQUI

            <fieldset><legend><u><b>Cuidados com os Cabelos</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Faz química no cabelo? </label><br><br>
            <input id="quimica_cabelo" type="radio" name="quimica_cabelo" value="0" <?php if($quimica_cabelos_atual[0] == 0){?>checked<?php } ?>><label for="quimica_cabelo">[ Sim ]</label>
            <input id="quimica_cabelo" type="radio" name="quimica_cabelo" value="1" <?php if($quimica_cabelos_atual[0] == 1){?>checked<?php } ?>><label for="quimica_cabelo">[ Não ]</label><br><br>
            <input minlength="1" maxlength="95" type="text" name="quimica_cabelo_quais" value="<?php echo substr("$quimica_cabelos_atual", 2) ?>"></td> 
            <td><label>Frequencia? </label>
            <input minlength="1" maxlength="30" type="text" name="quimica_cabelos_atual_frequencia" value="<?php echo $quimica_cabelos_atual_frequencia ?>"></td>
            <td><label>Usa? </label><br><br>
            <input id="usa0" type="checkbox" name="usa0" value="X" <?php if($cuidado_cabelo_usa[0] == 0){?>checked<?php } ?>><label for="usa0">Gel</label>
            <input id="usa1" type="checkbox" name="usa1" value="X" <?php if($cuidado_cabelo_usa[1] == 0){?>checked<?php } ?>><label for="usa1">Bonés</label>
            <input id="usa2" type="checkbox" name="usa2" value="X" <?php if($cuidado_cabelo_usa[2] == 0){?>checked<?php } ?>><label for="usa2">Chapas</label><br><br>
            <input id="usa3" type="checkbox" name="usa3" value="X" <?php if($cuidado_cabelo_usa[3] == 0){?>checked<?php } ?>><label for="usa3">Chapéu</label>
            <input id="usa4" type="checkbox" name="usa4" value="X" <?php if($cuidado_cabelo_usa[4] == 0){?>checked<?php } ?>><label for="usa4">Penteados Presos</label><br><br>
            <input id="usa5" type="checkbox" name="usa5" value="X" <?php if($cuidado_cabelo_usa[5] == 0){?>checked<?php } ?>><label for="usa5">Escovas</label>
            <input id="usa6" type="checkbox" name="usa6" value="X" <?php if($cuidado_cabelo_usa[6] == 0){?>checked<?php } ?>><label for="usa6">Capacetes</label></td>
            </tr><tr>
            <td><label>De quanto em quanto tempo lava os cabelos? </label>
            <input minlength="1" maxlength="30" type="text" name="cuidado_cabelo_lavagem" value="<?php echo $cuidado_cabelo_lavagem ?>"></td>
            <td><label>Quais produtos utiliza? </label>
            <input minlength="1" maxlength="50" type="text" name="cuidado_cabelo_produtos" value="<?php echo $cuidado_cabelo_produtos ?>"></td>
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Exame Físico</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Volume dos cabelos é o mesmo em todo couro cabeludo? </label><br><br>
            <input id="volume_cabelo" type="radio" name="volume_cabelo" value="0" <?php if($exm_fisico_volume_cabelo == 0){?>checked<?php } ?>><label for="volume_cabelo">[ Sim ]</label>
            <input id="volume_cabelo" type="radio" name="volume_cabelo" value="1" <?php if($exm_fisico_volume_cabelo == 1){?>checked<?php } ?>><label for="volume_cabelo">[ Não ]</label></td> 
            <td><label>Comprimento dos cabelos é o mesmo em todo couro cabeludo? </label><br><br>
            <input id="comprimento_cabelo" type="radio" name="comprimento_cabelo" value="0" <?php if($exm_fisico_comprimento_cabelo == 0){?>checked<?php } ?>><label for="comprimento_cabelo">[ Sim ]</label>
            <input id="comprimento_cabelo" type="radio" name="comprimento_cabelo" value="1" <?php if($exm_fisico_comprimento_cabelo == 1){?>checked<?php } ?>><label for="comprimento_cabelo">[ Não ]</label></td>
            <td><label>Tem algum tipo de quimica? </label><br><br>
            <input id="exm_fisico_quimica" type="radio" name="exm_fisico_quimica" value="0" <?php if($exm_fisico_quimica[0] == 0){?>checked<?php } ?>><label for="exm_fisico_quimica">[ Sim ]</label>
            <input id="exm_fisico_quimica" type="radio" name="exm_fisico_quimica" value="1" <?php if($exm_fisico_quimica[0] == 1){?>checked<?php } ?>><label for="exm_fisico_quimica">[ Não ]</label><br><br>
            <input minlength="1" maxlength="50" type="text" name="exm_fisico_quimica_quais" value="<?php echo substr("$exm_fisico_quimica", 2) ?>"></td> 
            </tr><tr>
            <td><label>Os cabelos são </label><br><br>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="0" <?php if($exm_fisico_cabelo == 0){?>checked<?php } ?>><label for="tipo_cabelo">Macios</label>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="1" <?php if($exm_fisico_cabelo == 1){?>checked<?php } ?>><label for="tipo_cabelo">Asperos</label><br><br>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="2" <?php if($exm_fisico_cabelo == 2){?>checked<?php } ?>><label for="tipo_cabelo">Brilhantes</label>
            <input id="tipo_cabelo" type="radio" name="tipo_cabelo" value="3" <?php if($exm_fisico_cabelo == 3){?>checked<?php } ?>><label for="tipo_cabelo">Opacos Presos</label></td>
            <td><label>As pontas dos cabelos são </label><br><br>
            <input id="pontas_cabelo" type="radio" name="pontas_cabelo" value="0" <?php if($exm_fisico_pontas[0] == 0){?>checked<?php } ?>><label for="pontas_cabelo">Integras</label>
            <input id="pontas_cabelo" type="radio" name="pontas_cabelo" value="1" <?php if($exm_fisico_pontas[0] == 1){?>checked<?php } ?>><label for="pontas_cabelo">Quebradiças</label><br><br>
            <input minlength="1" maxlength="30" type="text" name="pontas_cabelo_regiao" value="<?php echo substr("$exm_fisico_pontas", 2) ?>"></td> 
            <td><label>O couro cabeludo apresenta? </label><br><br>
            <input id="cc_apresenta0" type="checkbox" name="cc_apresenta0" value="X" <?php if($exm_fisico_couro_cabeludo[0] == 0){?>checked<?php } ?>><label for="cc_apresenta0">Oleosidade</label>
            <input id="cc_apresenta1" type="checkbox" name="cc_apresenta1" value="X" <?php if($exm_fisico_couro_cabeludo[1] == 0){?>checked<?php } ?>><label for="cc_apresenta1">Descamação</label><br><br>
            <input id="cc_apresenta2" type="checkbox" name="cc_apresenta2" value="X" <?php if($exm_fisico_couro_cabeludo[2] == 0){?>checked<?php } ?>><label for="cc_apresenta2">Vermelhidão</label>
            <input id="cc_apresenta3" type="checkbox" name="cc_apresenta3" value="X" <?php if($exm_fisico_couro_cabeludo[3] == 0){?>checked<?php } ?>><label for="cc_apresenta3">Manchas</label><br><br>
            <input id="cc_apresenta4" type="checkbox" name="cc_apresenta4" value="X" <?php if($exm_fisico_couro_cabeludo[4] == 0){?>checked<?php } ?>><label for="cc_apresenta4">Caspa</label>
            <input id="cc_apresenta5" type="checkbox" name="cc_apresenta5" value="X" <?php if($exm_fisico_couro_cabeludo[5] == 0){?>checked<?php } ?>><label for="cc_apresenta5">Odor</label>
            <input id="cc_apresenta6" type="checkbox" name="cc_apresenta6" value="X" <?php if($exm_fisico_couro_cabeludo[6] == 0){?>checked<?php } ?>><label for="cc_apresenta6">Outro</label></td>
            </tr><tr>
            <td><label>Presença de? </label><br><br>
            <input id="exm_fisico_presenca0" type="checkbox" name="exm_fisico_presenca0" value="X" <?php if($exm_fisico_presenca[0] == 0){?>checked<?php } ?>><label for="exm_fisico_presenca0">Falhas</label>
            <input id="exm_fisico_presenca1" type="checkbox" name="exm_fisico_presenca1" value="X" <?php if($exm_fisico_presenca[1] == 0){?>checked<?php } ?>><label for="exm_fisico_presenca1">Entradas</label></td>
            <td><label>Retrações em que região </label>
            <input minlength="1" maxlength="90" type="text" name="exm_fisico_regiao" value="<?php echo substr("$exm_fisico_presenca", 3) ?>"></td> 
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Casos de Alopecia</b></u></legend>
            <table width="100%" border="2px">
            <tr>
            <td><label>Alopecia Areata e/ou Cicatricial? </label><br><br>
            <input id="alopecia" type="radio" name="alopecia" value="0" <?php if($alopecia == 0){?>checked<?php } ?>><label for="alopecia">[ Sim ]</label>
            <input id="alopecia" type="radio" name="alopecia" value="1" <?php if($alopecia == 1){?>checked<?php } ?>><label for="alopecia">[ Não ]</label></td>
            <td><label>Localização </label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_localizacao" value="<?php echo $alopecia_localizacao ?>"></td> 
            <td><label>Formato </label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_formato" value="<?php echo $alopecia_formato ?>"></td> 
            </tr><tr>
            <td><label>Nº de Lesões </label>
            <input min="0" max="10" type="number" name="alopecia_lesoes" value="<?php echo $alopecia_lesoes ?>"></td> 
            <td><label>Formato</label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_formato_2" value="<?php echo $alopecia_formato_2 ?>"></td> 
            <td><label>Tamanho </label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_tamanho" value="<?php echo $alopecia_tamanho ?>"></td> 
            </tr><tr>
            <td><label>Superficie do Couro </label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_couro" value="<?php echo $alopecia_couro ?>"></td> 
            <td><label>Existe reposição dos fios</label><br><br>
            <input id="alopecia_fios" type="radio" name="alopecia_fios" value="0" <?php if($alopecia_reposicao == 0){?>checked<?php } ?>><label for="alopecia_fios">[ Sim ]</label>
            <input id="alopecia_fios" type="radio" name="alopecia_fios" value="1" <?php if($alopecia_reposicao == 1){?>checked<?php } ?>><label for="alopecia_fios">[ Não ]</label></td>
            <td><label>Alguma observação complementar</label>
            <input minlength="1" maxlength="90" type="text" name="alopecia_obs" value="<?php echo $alopecia_obs ?>"></td>  
            </tr>
            </table></fieldset><br>
            <fieldset><legend><u><b>Alteração encontrada</b></u></legend>
            <table width="100%" border="2px">
            <textarea name="alteracao_encontrada" maxlength="300" rows="10" cols="50"><?php echo $alteracao_encontrada ?></textarea><br>
            </table></fieldset><br>
            <fieldset><legend><u><b>Protocolo Sugerido</b></u></legend>
            <table width="100%" border="2px">
            <textarea name="protocolo_sugerido" maxlength="300" rows="10" cols="50"><?php echo $protocolo_sugerido ?></textarea><br>
            </table></fieldset><br>
            <fieldset><legend><u><b>Protocolo Realizado</b></u></legend>
            <table width="100%" border="2px">
            <label>Data</label>
            <input type="date" name="protocolo_realizado_01_data" value="<?php echo $protocolo_realizado_01_data ?>"><br><br>
            <textarea name="protocolo_realizado_01" maxlength="300" rows="10" cols="50"><?php echo $protocolo_realizado_01 ?></textarea><br>
            </table></fieldset><br>
            <fieldset><legend><u><b>Protocolo Realizado</b></u></legend>
            <table width="100%" border="2px">
            <label>Data</label>
            <input type="date" name="protocolo_realizado_02_data" value="<?php echo $protocolo_realizado_02_data ?>"><br><br>
            <textarea name="protocolo_realizado_02" maxlength="300" rows="10" cols="50"><?php echo $protocolo_realizado_02 ?></textarea><br>
            </table></fieldset><br>
            <fieldset><legend><u><b>Protocolo Realizado</b></u></legend>
            <table width="100%" border="2px">
            <label>Data</label>
            <input type="date" name="protocolo_realizado_03_data" value="<?php echo $protocolo_realizado_03_data ?>"><br><br>
            <textarea name="protocolo_realizado_03" maxlength="300" rows="10" cols="50"><?php echo $protocolo_realizado_03 ?></textarea><br>
            </table></fieldset><br>
            <fieldset><legend><u><b>Protocolo Realizado</b></u></legend>
            <table width="100%" border="2px">
            <label>Data</label>
            <input type="date" name="protocolo_realizado_04_data" value="<?php echo $protocolo_realizado_04_data ?>"><br><br>
            <textarea name="protocolo_realizado_04" maxlength="300" rows="10" cols="50"><?php echo $protocolo_realizado_04 ?></textarea><br>
            </table></fieldset><br>
            <input type="hidden" name="id_job" value="Formulario">
            <br>
            <div class="card-group-black btn"><button type="submit">Gerar PDF</button></div>

            </div>
        </div>
    </form>
<?php }else{ ?>

    <form class="form" action="acao.php" method="POST">
    <div class="card">
            <div class="card-top">
                <h2>Enviar Fomulario</h2>
            </div>
    <div class="card-group">
            <label>Nome</label>
            <input type="text" minlength="8" maxlength="30" name="doc_nome" value="<?php echo $doc_nome ?>" required>
            <label>E-mail</label>
            <input minlength="10" maxlength="35" type="email" name="doc_email" value="<?php echo $email ?>" required>
            <input type="hidden" name="id_job" value="formulario_enviar">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <div class="card-group-green btn"><button type="submit">Enviar Formulario</button></div>

        </div>
    </div>

<?php } ?>
</body>
</html>
<?php } ?>