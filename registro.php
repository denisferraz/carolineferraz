<?php
require('conexao.php');


$id_registro = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

$min_nasc = date('Y-m-d', strtotime("-110 years",strtotime($hoje))); 
$max_nasc = date('Y-m-d', strtotime("-18 years",strtotime($hoje))); 
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

        function removerZero(input) {
        var valor = input.value;
        if (valor.startsWith("0")) {
            input.value = valor.substring(1);
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
                </div>
                <div class="col-md-6 right">
                     <div class="input-box">
                        <header>Registre-se</header>
                        <form action="login.php" method="post">
                        <?php
                            if($id_registro == 'Registrar'){
                        ?>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="8" maxlength="45" name="nome" required>
                            <label for="nome">Nome Completo</label>
                        </div>
                        <div class="input-field">
                            <input type="email" class="input" minlength="8" maxlength="50" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="14" maxlength="14" name="cpf" OnKeyPress="formatar('###.###.###-##', this)" required>
                            <label for="cpf">CPF</label>
                        </div>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="5" maxlength="30" name="rg" required>
                            <label for="rg">RG</label>
                        </div>
                        <div>
                        <label for="nascimento">Nascimento</label>
                            <input type="date" min="<?php echo $min_nasc ?>" max="<?php echo $max_nasc ?>" class="input" name="nascimento" required>
                        </div>
                        <div class="input-field">
                            <input type="txt" class="input" minlength="13" maxlength="14" name="telefone" OnKeyPress="formatar('##-#####-####', this)" onblur="removerZero(this)" required>
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
                        <?php
                            }else if($id_registro == 'Codigo'){

                            $token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
                            $email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

                        ?>
                        <center><p>Digite o codigo enviado para o seu Celular!</p></center><br>
                        <div class="input-field">
                            <input type="email" class="input" minlength="8" maxlength="50" name="email" value="<?php echo $email; ?>" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="text" class="input" minlength="8" maxlength="8" name="codigo" required>
                            <label for="codigo">Codigo</label>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <?php
                            }
                        ?>
                        <div class="input-field">
                        <input type="hidden" name="id_registro" value="<?php echo $id_registro; ?>">
                        <input type="hidden" name="id_job" value="registro">
                            <input type="submit" class="submit" value="Confirmar"> 
                        </div>
                        </form>
                        <div class="signin">
                            <span><b>[X]</b> Aceito os <a href="lgpd.php" target="_blank">Termos e Condições</a></span>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>