<?php
require('conexao.php');

$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);
$typeerror = mysqli_real_escape_string($conn_msqli, $_GET['typeerror']);

if($typeerror == '1'){
    $typeerror = 'E-mail e/ou Senha Invalidos e/ou Celular não Verificado! Caso ja tenha verificado seu Celular, tente novamente abaixo ou Recupere sua conta.';   
}else if($typeerror == '2'){
    $typeerror = 'Sua Conta esta Temporariamente Bloqueada. Se o erro persistir, entre em contato conoscoo!';   
}else if($typeerror == '3'){
    $typeerror = 'Senhas não Conferem! Favor repita a mesma senha nos dois campos.';   
}else if($typeerror == '4'){
    $typeerror = 'Email ja existe. Tente recuperar a sua senha!';   
}else if($typeerror == '5'){
    $typeerror = 'CPF ja existe. Tente recuperar a sua senha!';   
}else if($typeerror == '6'){
    $typeerror = 'CPF invalido. Verifique seu CPF digitado';   
}else if($typeerror == '7'){
    $typeerror = 'Um e-mail foi enviado para voce com um codigo. Digite o mesmo abaixo';   
}else if($typeerror == '8'){
    $typeerror = 'Seu e-mail foi Bloqueado por questões de Segurança. Recupere a sua senha com o botão abaixo';   
}else if($typeerror == '9'){
    $typeerror = 'Sua nova senha foi alterada. Favor tentar acessar com seus novos dados';   
}else if($typeerror == '10'){
    $typeerror = 'Codigo Invalido!';   
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script>
        function formatar(mascara, documento){
        var i = documento.value.length;
        var saida = mascara.substring(0,1);
        var texto = mascara.substring(i)
  
        if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
        }
  
        }
    </script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Accesar <?php echo $config_empresa ?></title>
</head>
<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                    <img src="images/white.png" alt="">
                    <div class="text">
                        
                    </div>
                </div>
                <div class="col-md-6 right">
                     <div class="input-box">

                    <?php
                        if($id_job == 'login'){

                            $tentativas = mysqli_real_escape_string($conn_msqli, $_GET['amount']);
                            if($tentativas >= 1){
                            $tentativas = '! Ja foram 0';
                            $tentativas .= mysqli_real_escape_string($conn_msqli, $_GET['amount']);
                            $tentativas .= ' tentativas';
                            }
                    ?>

                    <header><font color="red"><?php echo $typeerror ?><?php echo $tentativas ?></font></header>
                        <form action="login.php" method="post">
                        <div class="input-field">
                            <input type="email" class="input" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" name="password" required>
                            <label for="password">Senha</label>
                        </div>
                        <div class="input-field">
                        <input type="hidden" name="id_job" value="login">
                            <input type="submit" class="submit" value="Acessar">
                            
                        </div>
                        </form>
                        <div class="signin">
                            <span>Esqueceu sua Senha? <a href="recuperar.php">Recupere Aqui</a></span>
                        </div>

                    <?php
                        }else if($id_job == 'registro'){
                    ?>

                    <header><font color="red"><?php echo $typeerror ?></font></header>
                        <form action="login.php" method="post">
                        <div class="input-field">
                            <input type="txt" class="input" minlength="8" maxlength="45" name="nome" required>
                            <label for="nome">Nome Completo</label>
                        </div>
                        <div class="input-field">
                            <input type="email" class="input" minlength="8" maxlength="30" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="14" maxlength="14" name="cpf" OnKeyPress="formatar('###.###.###-##', this)" required>
                            <label for="cpf">CPF</label>
                        </div>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="13" maxlength="13" name="telefone" OnKeyPress="formatar('##-#####-####', this)" required>
                            <label for="telefone">Telefone</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" minlength="8" maxlength="20" name="password" required>
                            <label for="password">Senha</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" minlength="8" maxlength="20" name="conf_password" required>
                            <label for="conf_password">Confirmar Senha</label>
                        </div>
                        <div class="input-field">
                        <input type="hidden" name="id_job" value="registro">
                            <input type="submit" class="submit" value="Registrar">
                            
                        </div>
                        </form>
                        <div class="signin">
                            <span>Ja tem Conta? <a href="painel.php">Acesse Aqui</a></span>
                        </div>

                    <?php
                        }else if($id_job == 'recuperar'){

                        $email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
                    ?>

                    <header><font color="red"><?php echo $typeerror ?></font></header>
                        <form action="login.php" method="post">
                        <div class="input-field">
                            <input type="text" class="input" minlength="8" maxlength="8" name="codigo" required>
                            <label for="text">Codigo</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" minlength="8" maxlength="20" name="password" required>
                            <label for="password">Senha</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" minlength="8" maxlength="20" name="conf_password" required>
                            <label for="conf_password">Confirmar Senha</label>
                        </div>
                        <div class="input-field">
                        <input type="hidden" name="email" value="<?php echo $email ?>">
                        <input type="hidden" name="id_job" value="recuperar_codigo">
                            <input type="submit" class="submit" value="Alterar Senha">
                        </div>
                        </form>
                        <div class="signin">
                            <span>Ja tem Conta? <a href="painel.php">Acesse Aqui</a></span>
                        </div>

                    <?php
                        }
                    ?>

                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>