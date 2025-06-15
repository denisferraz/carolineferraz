<?php


if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('index.php')
    </script>";
    exit();    
 }

date_default_timezone_set('America/Sao_Paulo');

//Local de Configuração
$local_configuracao = 'Casa';

//Configuração DB
$config_host = 'localhost';

if($local_configuracao == 'Casa'){
$config_db = 'app_carolineferraz';
$config_user = 'root';
$config_password = '';
}else{
$config_db = 'u826189016_tricologia';
$config_user = 'u826189016_tricologia';
$config_password = 'Much@ch0';
}

//Configuração E-mail
if($local_configuracao == 'Casa'){
$mail_Host = 'sandbox.smtp.mailtrap.io';
$mail_Username = '9521804de716a8';
$mail_Password = 'fc24a55905f17e';
$mail_SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
$mail_Port = 2525;
}else{
$mail_Host = 'smtp.hostinger.com';
$mail_Username = 'contato@carolineferraz.com.br';
$mail_Password = 'Much@ch0';
$mail_SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS';
$mail_Port = 465;
}

$site_atual = 'https://carolineferraz.com.br';

// Chave de criptografia
$chave = 'Accor@123';
$metodo = 'AES-256-CBC';
$iv = '8246508246508246';

$hoje = date('Y-m-d');
$min_dia = date('Y-m-d', strtotime("+1 days",strtotime($hoje))); 

function gerarConfirmacao($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $confirmacao_rdn = 'C';
    for ($i = 0; $i < $length; $i++) {
        $confirmacao_rdn .= $characters[rand(0, $charactersLength - 1)];
    }
    return "$confirmacao_rdn".'T';
}

$config_dsn = "mysql:host=$config_host;dbname=$config_db";
try {
    $conexao = new PDO($config_dsn, $config_user, $config_password);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}

define('Host', "$config_host");
define('Usuario', "$config_user");
define('Senha', "$config_password");
define('DB', "$config_db");
$conn_msqli = mysqli_connect(Host, Usuario, Senha, DB) or die ('Não foi possivel conectar');

foreach ($conexao->query("SELECT * FROM configuracoes WHERE id = '-2'") as $row) {
    $config_empresa = $row['config_empresa'];
    $config_email = $row['config_email'];
    $config_telefone = $row['config_telefone'];
    $config_cnpj = $row['config_cnpj'];
    $config_limitedia = $row['config_limitedia'];
    $config_limitepax = $row['config_limitepax'];
    $config_endereco = $row['config_endereco'];
    $config_msg_confirmacao = $row['config_msg_confirmacao'];
    $config_msg_cancelamento = $row['config_msg_cancelamento'];
    $config_msg_finalizar = $row['config_msg_finalizar'];
    $config_msg_lembrete = $row['config_msg_lembrete'];
    $config_msg_aniversario = $row['config_msg_aniversario'];
    $config_atendimento_hora_comeco = $row['atendimento_hora_comeco'];
    $config_atendimento_hora_fim = $row['atendimento_hora_fim'];
    $config_atendimento_hora_intervalo = $row['atendimento_hora_intervalo'];
    $config_atendimento_dia_max = $row['atendimento_dia_max'];
    $config_dia_segunda = $row['config_dia_segunda'];
    $config_dia_terca = $row['config_dia_terca'];
    $config_dia_quarta = $row['config_dia_quarta'];
    $config_dia_quinta = $row['config_dia_quinta'];
    $config_dia_sexta = $row['config_dia_sexta'];
    $config_dia_sabado = $row['config_dia_sabado'];
    $config_dia_domingo = $row['config_dia_domingo'];
    $envio_whatsapp = $row['envio_whatsapp'];
    $envio_email = $row['envio_email'];
    $is_segunda = $row['is_segunda'];
    $is_terca = $row['is_terca'];
    $is_quarta = $row['is_quarta'];
    $is_quinta = $row['is_quinta'];
    $is_sexta = $row['is_sexta'];
    $is_sabado = $row['is_sabado'];
    $is_domingo = $row['is_domingo'];
    $lembrete_auto_time = $row['lembrete_auto_time'];
}

// Função para Enviar Mensagem no WhatsApp via API do Facebook
function enviarWhatsapp($telefone, $mensagem) {

    // Normaliza o telefone: remove espaços, traços, parênteses
    $telefone = preg_replace('/[^0-9]/', '', $telefone);

    // Dados da instância e da API
$token = 'a7f3d9e1c4b6a2f8e9d4b7c1f2a3e6d0';   // Adicione o token se necessário
$url = 'https://evolution-evolution.0rvbug.easypanel.host/message/sendText/carolineferraz';

// Dados da mensagem
$data = [
    "number" => $telefone, // Número do destinatário com DDI
    "text"   => $mensagem
];

// Headers (adicione Authorization se sua API exigir)
$headers = [
    "Content-Type: application/json; charset=UTF-8",
    "apikey: a7f3d9e1c4b6a2f8e9d4b7c1f2a3e6d0", // Descomente se a API exigir autenticação
];

// Enviando com cURL
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue redirecionamentos se houver

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Resultado
if ($http_code === 200) {
    //echo "Mensagem enviada com sucesso: $response";
} else {
   //echo "Erro ao enviar mensagem (HTTP $http_code): $response";
}
}


?>
