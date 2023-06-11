<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$email = recuperarEmailToken();
$nome = recuperarNomeToken();

$id = explode('*', base64_decode(mysqli_real_escape_string($conn_msqli, $_GET['id'])));

$error_msg = '';

if (array_key_exists(1, $id)) {
    $typeerror = $id[1];

    if($typeerror == '1'){
        $error_msg = 'CPF Invalido e/ou ja Existe';
    }else if($typeerror == '2'){
        $error_msg = 'Veja seu Whatsapp para confirmar a alteração<br>Caso não tenha recebido, peça para alterar novamente!';
    }else if($typeerror == '3'){
        $error_msg = 'Dados atualizados com Sucesso! Caso divergente, tente Deslogar e Logar novamente';
    }
}


$query = $conexao->query("SELECT * FROM painel_users WHERE email = '{$email}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $token = $select['token'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $telefone = $select['telefone'];
    $cpf = $select['unico'];
}

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";

$min_nasc = date('Y-m-d', strtotime("-110 years",strtotime($hoje))); 
$max_nasc = date('Y-m-d', strtotime("-18 years",strtotime($hoje))); 
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style_home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <main>
<!-- VER !-->
    <section class="home-center"><br>
        <center><p><b>Profile</b> [<?php echo $nome ?>]</p></center><br><br>
        <fieldset class="home-table">
        <legend><b><a href="javascript:void(0)" onclick='window.open("profile.php?id=<?php echo base64_encode("editar") ?>", "iframe-container")'><button class="home-btn">Editar</button></a></b></legend><br>
        <center><font size="+1" color="red"><b><?php echo $error_msg ?></b></font></center><br>
        <form action="login.php" method="post" onsubmit="exibirPopup()"> 
            <label><b>Nome Completo:</b> <?php echo $nome ?></label>
            <?php
            if($id[0] == 'editar'){
            ?>
            <input type="text" class="input" minlength="8" maxlength="45" name="nome" value="<?php echo $nome ?>" required>
            <?php } ?>
            <br>
            <?php
            if($id[0] == 'ver'){
            ?>
            <label><b>E-mail:</b> <?php echo $email ?></label>
            <br>
            <?php } ?>
            <label><b>Telefone: </b><?php echo $telefone ?></label>
            <?php
            if($id[0] == 'editar'){
            ?>
            <input type="text" class="input" minlength="13" maxlength="14" name="telefone" value="<?php echo $telefone ?>" OnKeyPress="formatar('##-#####-####', this)" required>
            <?php } ?>
            <br>
            <label><b>Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label>
            <?php
            if($id[0] == 'editar'){
            ?>
            <input type="date" class="input" min="<?php echo $min_nasc ?>" max="<?php echo $max_nasc ?>" name="nascimento" value="<?php echo $nascimento ?>" required>
            <?php } ?>
            <br>
            <label><b>RG: </b><?php echo $rg ?></label>
            <?php
            if($id[0] == 'editar'){
            ?>
            <input type="text" class="input" minlength="5" maxlength="30" name="rg" value="<?php echo $rg ?>" required>
            <?php } ?>
            <br>
            <label><b>CPF: </b><?php echo $cpf ?></label>
            <?php
            if($id[0] == 'editar'){
            ?>
            <input type="text" class="input" minlength="14" maxlength="14" name="cpf" value="<?php echo $cpf ?>" OnKeyPress="formatar('###.###.###-##', this)" required><br>
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="id_job" value="profile_editar">
            <button class="home-btn" type="submit">Confirmar</button>
            <?php } ?>
        </form>
        </fieldset>
        </section>
        <br><br>
<!-- EDITAR !-->
<?php
if($id[0] == 'Codigo'){

    $query = $conexao->prepare("UPDATE painel_users SET nome = :nome, telefone = :telefone, nascimento = :nascimento, rg = :rg, unico = :cpf WHERE token = :token");
    $query->execute(array('nome' => $id[1], 'telefone' => $id[2], 'nascimento' => $id[3], 'rg' => $id[4], 'cpf' => $id[5], 'token' => $id[6]));
    
    $id = base64_encode('ver*3');
        echo "<script>
        window.location.replace('profile.php?id=$id')
        </script>";
        exit();

} 
?>
    </main>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos suas alterações!',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>
<script>
        function formatar(mascara, documento){
        var i = documento.value.length;
        var saida = mascara.substring(0,1);
        var texto = mascara.substring(i)
  
        if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
        }
  
        }

        function removerZero(input) {
        var valor = input.value;
        if (valor.startsWith("0")) {
            input.value = valor.substring(1);
        }
    }

    </script>
</body>
</html>