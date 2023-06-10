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

$tentativas = 0;

if($id_job == 'registro'){

    $id_registro = mysqli_real_escape_string($conn_msqli, $_POST['id_registro']);

    if($id_registro == 'Registrar'){

    $senha = mysqli_real_escape_string($conn_msqli, $_POST['password']);
    $crip_senha = md5($senha);

    if(empty($_POST['email']) || empty($_POST['password'])){
        header('Location: registro.php?id_job=Registrar');
        exit();
    }

    $nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
    $telefone = preg_replace('/[^\d]/', '', mysqli_real_escape_string($conn_msqli, $_POST['telefone']));
    $rg = mysqli_real_escape_string($conn_msqli, $_POST['rg']);
    $nascimento = mysqli_real_escape_string($conn_msqli, $_POST['nascimento']);
    $doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['cpf']));
    $conf_senha = mysqli_real_escape_string($conn_msqli, $_POST['conf_password']);
    $crip_senha = md5($senha);
    $token = md5(date("YmdHismm"));

    if($senha != $conf_senha){
        $id = base64_encode('registro*3');
        echo "<script>
        window.location.replace('login_error.php?id=$id')
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
        $id = base64_encode('registro*6');
        echo "<script>
        window.location.replace('login_error.php?id=$id')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email");
    $query->execute(array('email' => $email));
    $row = $query->rowCount();
    
    if($row == 1){
        $id = base64_encode('registro*4');
        echo "<script>
        window.location.replace('login_error.php?id=$id')
        </script>";
        exit();
    }

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE unico = :cpf");
    $query->execute(array('cpf' => $doc_cpf));
    $row = $query->rowCount();
    
    if($row == 1){
        $id = base64_encode('registro*5');
        echo "<script>
        window.location.replace('login_error.php?id=$id')
        </script>";
        exit();
    }

        $codigo = rand(10000000, 99999999);

        $query2 = $conexao->prepare("INSERT INTO $tabela_painel_users (email, tipo, senha, nome, telefone, unico, token, rg, nascimento, codigo, tentativas, aut_painel) VALUES (:email, 'Paciente', :senha, :nome, :telefone, :cpf, :token, :rg, :nascimento, :codigo, '0', '1')");
        $query2->execute(array('email' => $email, 'nome' => $nome, 'cpf' => $doc_cpf, 'token' => $token, 'rg' => $rg, 'nascimento' => $nascimento, 'codigo' => $codigo, 'telefone' => $telefone, 'senha' => $crip_senha));
        
//Incio Envio Whatsapp

$id = base64_encode("Codigo*$email*$codigo*$token");
$doc_telefonewhats = "55$telefone";
$msg_wahstapp = "Ola $nome, tudo bem?".'\n\n'."Para completar o seu cadastro, clique no Link abaixo:".'\n\n'."https://carolineferraz.com.br/registro.php?id=$id";

$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://cluster.apigratis.com/api/v1/whatsapp/sendText',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => "{
    \"number\": \"$doc_telefonewhats\",
    \"text\": \"$msg_wahstapp\"
}",
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    "SecretKey: $whatsapp_secretkey",
    "PublicToken: $whatsapp_publictoken",
    "DeviceToken: $whatsapp_devicetoken",
    "Authorization: $whatsapp_authorization"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

//Fim Envio Whatsapp

        $id = base64_encode("EnvCodigo*$email*$token*$telefone*$nome");
        echo "<script>
        alert('Um Codigo foi Enviado para o seu Celular!')
        window.location.replace('registro.php?id=$id')
        </script>";
        exit();
}else if($id_registro == 'Codigo'){

    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $codigo = mysqli_real_escape_string($conn_msqli, $_POST['codigo']);

    $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email AND token = :token AND codigo = :codigo");
    $query->execute(array('email' => $email, 'token' => $token, 'codigo' => $codigo));
    $row = $query->rowCount();
    
    if($row == 1){

        $query2 = $conexao->prepare("UPDATE $tabela_painel_users SET codigo = 0 WHERE email = :email AND token = :token");
        $query2->execute(array('email' => $email, 'token' => $token));

        echo "<script>
        alert('E-mail cadastrado com sucesso! Acesse abaixo')
        window.location.replace('painel.php')
        </script>";
        exit();

    }else{

        $id = base64_encode("RecCodigo*$email*$token");
        echo "<script>
        alert('Codigo Invalido!')
        window.location.replace('registro.php?id=$id')
        </script>";
        exit(); 

    }

}else if($id_registro == 'RecCodigo'){

        $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
        $telefone = preg_replace('/[^\d]/', '', mysqli_real_escape_string($conn_msqli, $_POST['telefone']));
    
        $query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email AND token = :token");
        $query->execute(array('email' => $email, 'token' => $token));
        $row = $query->rowCount();
        
        if($row == 1){

        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $nome = $select['nome'];
            $codigo = $select['codigo'];
        }
    
            $query2 = $conexao->prepare("UPDATE $tabela_painel_users SET telefone = :telefone WHERE email = :email AND token = :token");
            $query2->execute(array('telefone' => $telefone, 'email' => $email, 'token' => $token));

 //Incio Envio Whatsapp

$id = base64_encode("Codigo*$email*$codigo*$token");
$doc_telefonewhats = "55$telefone";
$msg_wahstapp = "Ola $nome, tudo bem?".'\n\n'."Para completar o seu cadastro, clique no Link abaixo:".'\n\n'."https://carolineferraz.com.br/registro.php?id=$id";

$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://cluster.apigratis.com/api/v1/whatsapp/sendText',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => "{
    \"number\": \"$doc_telefonewhats\",
    \"text\": \"$msg_wahstapp\"
}",
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    "SecretKey: $whatsapp_secretkey",
    "PublicToken: $whatsapp_publictoken",
    "DeviceToken: $whatsapp_devicetoken",
    "Authorization: $whatsapp_authorization"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

//Fim Envio Whatsapp

            $id = base64_encode("EnvCodigo*$email*$token*$telefone*$nome");
            echo "<script>
            window.location.replace('registro.php?id=$id')
            </script>";
            exit();
    
        }else{
    
            $id = base64_encode("Registrar");
            echo "<script>
            alert('E-mail não existe. Cadastre!')
            window.location.replace('registro.php?id=$id')
            </script>";
            exit(); 

    
        }}


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

        $id = base64_encode("recuperar*7*$email");
        echo "<script>
        window.location.replace('login_error.php?id=$id')
        </script>";
        exit();
    }else{
        $id = base64_encode("recuperar*7*$email");
        echo "<script>
        window.location.replace('login_error.php?id$id')
        </script>";
        exit();
    }
    
}else if($id_job == 'recuperar_codigo'){

    $conf_senha = mysqli_real_escape_string($conn_msqli, $_POST['conf_password']);
    $senha = mysqli_real_escape_string($conn_msqli, $_POST['password']);
    $crip_senha = md5($senha);

    if($senha != $conf_senha){
        $id = base64_encode("recuperar*3*$email");
        echo "<script>
        window.location.replace('login_error.php?id=$id')
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
    
        $query = $conexao->prepare("UPDATE $tabela_painel_users SET senha = :senha, tentativas = '0', codigo = '0' WHERE email = :email");
        $query->execute(array('senha' => $crip_senha, 'email' => $email));
    
            $id = base64_encode("login*9");
            echo "<script>
            window.location.replace('login_error.php?id=$id')
            </script>";
            exit();
        }else{
            $id = base64_encode("recuperar*10*$email");
            echo "<script>
            window.location.replace('login_error.php?id=$id')
            </script>";
            exit();
        }
        
}