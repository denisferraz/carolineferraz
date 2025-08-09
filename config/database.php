<?php


if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('index.php')
    </script>";
    exit();    
 }

date_default_timezone_set('America/Sao_Paulo');


//Configuração DB
$config_host = 'localhost';
$config_db = 'SUA_DB';
$config_user = 'SEU_USUARIO';
$config_password = 'SUA_SENHA';


//Configuração E-mail

$mail_Host = 'smtp.hostinger.com';
$mail_Username = 'SEU_EMAIL';
$mail_Password = 'SUA_SENHA';
$mail_SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS';
$mail_Port = 465;


$site_puro = 'SEU_SITE';
$site_atual = 'https://' . $site_puro . '.com.br';

// Chave de criptografia
$chave = 'SUA_CHAVE';
$metodo = 'AES-256-CBC';
$iv = 'SUA_CHAVE_2';

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

// Função para Enviar Mensagem no WhatsApp via API do Facebook
function enviarWhatsapp($telefone, $mensagem, $client_id) {

    global $site_puro;

    // Normaliza o telefone: remove espaços, traços, parênteses
    $telefone = preg_replace('/[^0-9]/', '', $telefone);

    // Dados da instância e da API
$client_id = 'client_id_' . $client_id;


$token = 'SEU_TOKEN';   // Adicione o token se necessário
$url_api = 'https://evolution-evolution.0rvbug.easypanel.host';
$url = $url_api .'/message/sendText/'. $client_id;

// Dados da mensagem
$data = [
    "number" => $telefone, // Número do destinatário com DDI
    "text"   => $mensagem
];

// Headers (adicione Authorization se sua API exigir)
$headers = [
    "Content-Type: application/json; charset=UTF-8",
    "apikey: $token", // Descomente se a API exigir autenticação
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

foreach ($conexao->query("SELECT * FROM configuracoes WHERE id = '1'") as $row) {
    $config_empresa_chronoclick = $row['config_empresa'];
    $config_email_chronoclick = $row['config_email'];
    $config_telefone_chronoclick = $row['config_telefone'];
    $config_cnpj_chronoclick = $row['config_cnpj'];
    $config_endereco_chronoclick = $row['config_endereco'];
    $envio_whatsapp_chronoclick = $row['envio_whatsapp'];
    $envio_email_chronoclick = $row['envio_email'];
}

?>
