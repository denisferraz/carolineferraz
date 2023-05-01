<?php
session_start();
require('conexao.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('painel.php')
    </script>";
    exit();
 }

$id_job = mysqli_real_escape_string($conn_msqli, $_POST['id_job']);
$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$senha = mysqli_real_escape_string($conn_msqli, $_POST['password']);
$crip_senha = md5($senha);

$tentativas = 0;

 if($id_job == 'login'){

$query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email AND senha = :senha");
$query->execute(array('email' => $email, 'senha' => $crip_senha));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $tentativas = $select['tentativas'];
}

if($tentativas >= 5){
    echo "<script>
        window.location.replace('login_error.php?id_job=login&typeerror=8&amount=$tentativas')
        </script>";
        exit();
    }

$row = $query->rowCount();

if($row == 1){

    $query = $conexao->prepare("UPDATE $tabela_painel_users SET tentativas = '0' WHERE email = :email");
    $query->execute(array('email' => $email));

    $_SESSION['email'] = $email;
    header('Location: home.php');
    exit();

}else{

$query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $tentativas = $select['tentativas'];
}

    $tentativas = $tentativas + 1;

    $query = $conexao->prepare("UPDATE $tabela_painel_users SET tentativas = :tentativas WHERE email = :email");
    $query->execute(array('tentativas' => $tentativas, 'email' => $email));

    if($tentativas >= 5){
    echo "<script>
        window.location.replace('login_error.php?id_job=login&typeerror=8&amount=$tentativas')
        </script>";
        exit();
    }else{
    echo "<script>
        window.location.replace('login_error.php?id_job=login&typeerror=1&amount=$tentativas')
        </script>";
        exit();  
    }

}

}else if($id_job == 'registro'){
    if(empty($_POST['email']) || empty($_POST['password'])){
        header('Location: registro.php');
        exit();
    }
    
    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
    $telefone = mysqli_real_escape_string($conn_msqli, $_POST['telefone']);
    $rg = mysqli_real_escape_string($conn_msqli, $_POST['rg']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['nascimento']);
    $doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['cpf']));
    $conf_senha = mysqli_real_escape_string($conn_msqli, $_POST['conf_password']);
    $crip_senha = md5($senha);
    $token = md5(date("YmdHismm"));

    if($senha != $conf_senha){
        echo "<script>
        window.location.replace('login_error.php?id_job=registro&typeerror=3')
        </script>";
        exit();
    }
    
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
        window.location.replace('login_error.php?id_job=registro&typeerror=6')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
    $query->execute(array('email' => $email));
    $row = $query->rowCount();
    
    if($row == 1){
        echo "<script>
        window.location.replace('login_error.php?id_job=registro&typeerror=4')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE unico = :cpf");
    $query->execute(array('cpf' => $doc_cpf));
    $row = $query->rowCount();
    
    if($row == 1){
        echo "<script>
        window.location.replace('login_error.php?id_job=registro&typeerror=5')
        </script>";
        exit();
    }

    
        $query2 = $conexao->prepare("INSERT INTO $tabela_painel_users (email, tipo, senha, nome, telefone, unico, token, rg, nascimento, codigo, tentativas, aut_reservas, aut_disponibilidade, aut_configuracoes, aut_acessos) VALUES (:email, 'Paciente', :senha, :nome, :telefone, :cpf, :token, :rg, :nascimento, '0', '0', '1', '1', '1', '1')");
        $query2->execute(array('email' => $email, 'nome' => $nome, 'cpf' => $doc_cpf, 'token' => $token, 'rg' => $rg, 'nascimento' => $nascimento, 'telefone' => $telefone, 'senha' => $crip_senha));
        
        $_SESSION['email'] = $email;
        header('Location: home.php');
        exit();

}else if($id_job == 'recuperar'){

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
    $query->execute(array('email' => $email));
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $doc_nome = $select['nome'];
    }
    $row = $query->rowCount();
    
    if($row == 1){

    $codigo = rand(10000000, 99999999);

    $query = $conexao->prepare("UPDATE $tabela_painel_users SET codigo = :codigo WHERE email = :email");
    $query->execute(array('codigo' => $codigo, 'email' => $email));

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
    $mail->addAddress("$email", "$doc_nome");
    $mail->addBCC("$config_email");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "Recuperação de Senha";
  // INICIO MENSAGEM  
    $mail->Body = "

    <fieldset>
    <legend><b>Recuperação de Senha</b></legend>
    <br>
    <b>$doc_nome</b>, voce pediu para recuperar a senha do seu cadastro.<br>
    Digite o Seguinte Codigo: <b>$codigo</b>
    </fieldset><br><fieldset>
    <legend><b>$config_empresa</b></legend>
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
        window.location.replace('login_error.php?id_job=recuperar&typeerror=7&email=$email')
        </script>";
        exit();
    }else{
        echo "<script>
        window.location.replace('login_error.php?id_job=recuperar&typeerror=7&email=$email')
        </script>";
        exit();
    }
    
}else if($id_job == 'recuperar_codigo'){

    $conf_senha = mysqli_real_escape_string($conn_msqli, $_POST['conf_password']);

    if($senha != $conf_senha){
        echo "<script>
        window.location.replace('login_error.php?id_job=recuperar&typeerror=3&email=$email')
        </script>";
        exit();
    }

    $codigo = mysqli_real_escape_string($conn_msqli, $_POST['codigo']);

        $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email AND codigo = :codigo");
        $query->execute(array('email' => $email, 'codigo' => $codigo));
        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $doc_nome = $select['nome'];
        }
        $row = $query->rowCount();
        
        if($row == 1){
    
        $codigo = rand(10000000, 99999999);
    
        $query = $conexao->prepare("UPDATE $tabela_painel_users SET senha = :senha, tentativas = '0' WHERE email = :email");
        $query->execute(array('senha' => $crip_senha, 'email' => $email));
    
            echo "<script>
            window.location.replace('login_error.php?id_job=login&typeerror=9')
            </script>";
            exit();
        }else{
            echo "<script>
            window.location.replace('login_error.php?id_job=recuperar&typeerror=10&email=$email')
            </script>";
            exit();
        }
        
}