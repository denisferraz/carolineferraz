<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$token_emp = mysqli_real_escape_string($conn_msqli, $_GET['token']);


    function excluirPorToken($conexao, $tabela, $token_emp) {
        // Lista de tabelas permitidas (evita SQL injection)
        $tabelasPermitidas = ['alteracoes', 'atestados', 'configuracoes', 'consultas', 'contrato', 'custos', 'custos_tratamentos',
        'disponibilidade', 'estoque', 'estoque_item', 'evolucoes', 'historico_atendimento', 'lancamentos', 'lancamentos_atendimento',
        'lancamentos_recorrentes', 'mensagens', 'modelos_anamnese', 'modelos_prontuario', 'perguntas_modelo', 'perguntas_modelo_prontuario',
        'profissionais', 'receituarios', 'respostas_anamnese', 'respostas_prontuario', 'tratamento', 'tratamentos', 'videos', 'salas', 'interacoes', 'orcamentos'];

        // Valida se a tabela informada é permitida
        if (!in_array($tabela, $tabelasPermitidas)) {
            throw new Exception("Tabela não permitida: $tabela");
        }

        // Monta e executa o SQL de forma segura
        $query = $conexao->prepare("DELETE FROM `$tabela` WHERE token_emp = :token_emp");
        $query->execute(['token_emp' => $token_emp]);
    }

    $nomesDasTabelas = ['alteracoes', 'atestados', 'configuracoes', 'consultas', 'contrato', 'custos', 'custos_tratamentos',
                        'disponibilidade', 'estoque', 'estoque_item', 'evolucoes', 'historico_atendimento', 'lancamentos', 'lancamentos_atendimento',
                        'lancamentos_recorrentes', 'mensagens', 'modelos_anamnese', 'modelos_prontuario', 'perguntas_modelo', 'perguntas_modelo_prontuario',
                        'profissionais', 'receituarios', 'respostas_anamnese', 'respostas_prontuario', 'tratamento', 'tratamentos', 'videos', 'salas', 'interacoes', 'orcamentos'];

    foreach ($nomesDasTabelas as $tabela) {
        excluirPorToken($conexao, $tabela, $token_emp);
    }

    $query = $conexao->prepare("DELETE FROM painel_users WHERE token = :token AND email = :email");
    $query->execute(array('token' => $token_emp, 'email' => $email));

    $query2 = $conexao->prepare("
        SELECT token_emp, email 
        FROM painel_users 
        WHERE CONCAT(';', token_emp, ';') LIKE :token_emp 
        AND tipo = 'Paciente'
    ");
    $query2->execute([
        'token_emp' => '%;' . $token_emp . ';%'
    ]);
    $registro = $query2->fetch(PDO::FETCH_ASSOC);
    $doc_email = $registro['email'];

    if ($registro) {
        $tokens = explode(';', $registro['token_emp']);

        // Remove espaços em branco e tokens vazios
        $tokens = array_filter(array_map('trim', $tokens));

        if (count($tokens) === 1 && $tokens[0] === $token_emp) {
            // Caso tenha apenas 1 token, e é igual ao que queremos remover: deletar o registro
            $delete = $conexao->prepare("DELETE FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email AND tipo = 'Paciente'");
            $delete->execute(['token_emp' => '%;' . $token_emp . ';%', 'email' => $doc_email]);
        } else {
            // Mais de um token, removemos o desejado e atualizamos
            $tokens = array_filter($tokens, function($t) use ($token_emp) {
                return $t !== $token_emp;
            });

            $novo_token_emp = implode(';', $tokens);

            $update = $conexao->prepare("UPDATE painel_users SET token_emp = :novo WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email AND tipo = 'Paciente'");
            $update->execute([
                'novo' => $novo_token_emp,
                'token_emp' => '%;' . $token_emp . ';%',
                'email' => $doc_email
            ]);
        }
    }

    $pastaAssinatura = '../profissionais/fotos/';
    $arquivos = glob($pastaAssinatura . '*' . $token_emp . '*');

    foreach ($arquivos as $arquivo) {
        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }

    function excluirPasta($pasta) {
        // Normaliza o caminho e verifica se é diretório
        $pasta = rtrim($pasta, '/') . '/';
        if (!is_dir($pasta)) return;
    
        // Usa DirectoryIterator (mais robusto que scandir)
        $itens = new DirectoryIterator($pasta);
        foreach ($itens as $item) {
            if ($item->isDot()) continue;
    
            $caminho = $item->getPathname();
    
            if ($item->isDir()) {
                excluirPasta($caminho); // Recursivamente
            } elseif ($item->isFile()) {
                @unlink($caminho); // O @ evita warning se o arquivo já não existir
            }
        }
    
        @rmdir($pasta); // O @ evita warning se já tiver sido removido
    }
    
    // Caminhos das pastas (usa __DIR__ para robustez)
    $baseDir = __DIR__ . '/../'; // Caminho base seguro
    $token_emp = basename($token_emp); // Protege contra path traversal
    
    $pastaArquivos = $baseDir . 'arquivos/' . $token_emp;
    $pastaImages   = $baseDir . 'imagens/' . $token_emp;
    $pastaAss      = $baseDir . 'assinaturas/' . $token_emp;
    
    // Exclui pastas
    excluirPasta($pastaArquivos);
    excluirPasta($pastaImages);
    excluirPasta($pastaAss);

    echo "<script>
    alert('Cadastro Deletado com Sucesso')
    window.location.replace('owner_cadastros.php')
    </script>";
    exit();

?>