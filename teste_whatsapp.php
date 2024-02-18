<?php

require('conexao.php');

 //Incio Envio Whatsapp
$doc_telefonewhats = "557197417190";
$msg_wahstapp = "Testando Sistema de Whatsapp!";

$resposta = enviarWhatsapp($doc_telefonewhats, $msg_wahstapp);

echo $resposta;
  //Fim Envio Whatsapp

?>