<?php

require('conexao.php');

$aniversariante = 'Hoje é aniversario de:<br><br>';

$result_check = $conexao->query("SELECT * FROM painel_users WHERE nascimento = '{$hoje}' AND tipo != 'Paciente'");
if ($result_check->rowCount() > 0) {
while($select_check = $result_check->fetch(PDO::FETCH_ASSOC)){
$nome = $select_check['nome'];
$email = $select_check['email'];
$telefone = $select_check['telefone'];

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";

$aniversariante .= "Nome: $nome<br>E-mail: $email<br>Telefone: $telefone<br><br>Que tal mandar uma mensagem?";

}

echo $aniversariante;

}else{

    echo 'Sem aniversariantes hoje!<br>';

}



?>