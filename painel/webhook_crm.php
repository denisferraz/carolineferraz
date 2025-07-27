<?php
require('../config/database.php');
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['event']) || $data['event'] !== 'messages.upsert') {
    http_response_code(200);
    exit('Evento ignorado');
}

date_default_timezone_set('America/Sao_Paulo');
$data_envio = date('Y-m-d H:i:s');

$dados = $data['data'] ?? [];

$token_emp = isset($_GET['token_emp']) ? mysqli_real_escape_string($conn_msqli, $_GET['token_emp']) : 'token_emp';

if($token_emp != 'token_emp'){
$mensagem = $dados['message']['conversation'] ?? null;
$numeroCompleto = $dados['key']['remoteJid'] ?? null;
$numeroLimpo = preg_replace('/\D/', '', substr($numeroCompleto, 2, 10));

// Ignorar mensagens de grupos
if (str_ends_with($numeroCompleto, '@g.us')) {
    http_response_code(200);
    exit('Mensagem de grupo - ignorada');
}

if (strlen($numeroLimpo) === 10) {
    $numero = substr($numeroLimpo, 0, 2) . '9' . substr($numeroLimpo, 2);
} else {
    $numero = $numeroLimpo;
}

$nome = $dados['pushName'] ?? 'Desconhecido';

if (!$mensagem || !$numero) {
    http_response_code(400);
    exit('Dados incompletos');
}

try {
    $pdo = $conexao;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar regras da empresa
    $stmt = $pdo->prepare("SELECT * FROM regras_etapa WHERE token_emp = ?");
    $stmt->execute([$token_emp]);
    $regras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($regras as $regra) {
        $palavra = strtolower($regra['palavra_chave']);
        if (str_contains(strtolower($mensagem), $palavra)) {
            // Atualizar etapa do contato
            $stmtUpdate = $pdo->prepare("UPDATE contatos SET etapa = ? WHERE numero = ? AND token_emp = ?");
            $stmtUpdate->execute([$regra['etapa_destino'], $numero, $token_emp]);
            break;
        }
    }

        // Verifica se o contato já existe
        $stmtContato = $pdo->prepare("SELECT * FROM contatos WHERE numero = ? AND token_emp = ?");
        $stmtContato->execute([$numero, $token_emp]);
        $contato = $stmtContato->fetch();
        $contatoId = null; // Define como null por padrão

        // Somente cria novo contato se a mensagem veio do cliente (não foi enviada por você)
        if (!isset($dados['key']['fromMe']) || $dados['key']['fromMe'] === false) {
            if (!$contato) {
                // Criar novo contato
                $stmtInsert = $pdo->prepare("INSERT INTO contatos (numero, nome, token_emp) VALUES (?, ?, ?)");
                $stmtInsert->execute([$numero, $nome, $token_emp]);
                $contatoId = $pdo->lastInsertId();
            } else {
                $contatoId = $contato['id'];
                // Atualizar nome e último contato
                $stmtUpdate = $pdo->prepare("UPDATE contatos SET nome = ?, ultimo_contato = NOW() WHERE id = ? AND token_emp = ?");
                $stmtUpdate->execute([$nome, $contatoId, $token_emp]);
            }
        } elseif ($contato) {
            // Se for mensagem enviada por você, usa contato existente (não cria novo)
            $contatoId = $contato['id'];
        }

        //Criptografa a mensagem
        $dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
        $mensagem_cripto = base64_encode($dados_criptografados);

        // Inserir interação, apenas se tiver $contatoId definido
        if ($contatoId) {
            if (!isset($dados['key']['fromMe']) || $dados['key']['fromMe'] === true) {
                $stmtInteracao = $pdo->prepare("INSERT INTO interacoes (contato_id, mensagem, origem, token_emp, data) VALUES (?, ?, 'empresa', ?, ?)");
            } else {
                $stmtInteracao = $pdo->prepare("INSERT INTO interacoes (contato_id, mensagem, origem, token_emp, data) VALUES (?, ?, 'cliente', ?, ?)");
                
                // Atualizar último contato do cliente
                $stmt = $pdo->prepare("UPDATE contatos SET ultimo_contato_cliente = NOW() WHERE numero = ? AND token_emp = ?");
                $stmt->execute([$numero, $token_emp]);
            }

            // Executar inserção da interação
            $stmtInteracao->execute([$contatoId, $mensagem_cripto, $token_emp, $data_envio]);
        }


        // Marcar mensagem como processada
        $pdo->prepare("UPDATE mensagens SET processado = 1 WHERE id = ? AND token_emp = ?")
            ->execute([$row['id'], $token_emp]);

    http_response_code(200);
    echo json_encode(["status" => "registro atualizado"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao salvar no banco"]);
}
}