<?php

require('../config/database.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$aniversario_hoje = date('d/m', strtotime("$hoje"));
$aniversariante = 'Bom dia!! Veja a lista dos aniversariantes de hoje abaixo:';

$result_check = $conexao->query("SELECT * FROM painel_users WHERE DATE_FORMAT(nascimento, '%d/%m') = '{$aniversario_hoje}' AND tipo = 'Paciente'");
if ($result_check->rowCount() > 0) {
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select_check['nome'];
$doc_email = $select_check['email'];
$doc_telefone = $select_check['telefone'];
$telefone = $select_check['telefone'];

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";

    //Faz a String pra envio de CAROL
    $aniversariante .= "\n\nNome: $doc_nome\n".
    "E-mail: $doc_email\n".
    "Telefone: $doc_telefone";

}
//Incio Envio Whatsapp

if($envio_whatsapp == 'ativado'){
$doc_telefonewhats = "5571997417190";
$msg_wahstapp = "$aniversariante\n\n".
'Que tal mandar uma mensagem?';

$whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);

echo $whatsapp;

}
//Fim Envio Whatsapp

}

?>