<?php
require('../../config/database.php');

    $pdo = $conexao;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM mensagens WHERE processado IS NULL");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $numero = $row['numero'];
        $nome = $row['nome'];
        $mensagem = $row['mensagem'];
        $token_emp = $row['token_emp'];

        // Verifica se o contato já existe
        $stmtContato = $pdo->prepare("SELECT * FROM contatos WHERE numero = ? AND token_emp = ?");
        $stmtContato->execute([$numero, $token_emp]);
        $contato = $stmtContato->fetch();

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

        // Inserir interação
        $stmtInteracao = $pdo->prepare("INSERT INTO interacoes (contato_id, mensagem, origem, token_emp) VALUES (?, ?, 'cliente', ?)");
        $stmtInteracao->execute([$contatoId, $mensagem, $token_emp]);

        // Marcar mensagem como processada
        $pdo->prepare("UPDATE mensagens SET processado = 1 WHERE id = ? AND token_emp = ?")
            ->execute([$row['id'], $token_emp]);
    }

    echo "<script>
        alert('Mensagens Atualizadas')
        window.location.replace('crm.php')
        </script>";
        exit();  