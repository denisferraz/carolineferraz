<?php
$email = $_SESSION['email'];

//Checar Pendentes
$query_alteracao = $conexao->query("SELECT * FROM alteracoes WHERE alt_status = 'Pendente'");
$alteracao_qtd = $query_alteracao->rowCount();

//Checar Estoque minimo
$itens_abaixo_minimo = 0;
$query = $conexao->prepare("
    SELECT produto, SUM(quantidade) AS total_quantidade, MAX(data_entrada) as ultima_entrada
    FROM estoque 
    WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id 
    GROUP BY produto 
    ORDER BY produto DESC
    ");
$query->execute([
    'id' => 1
    ]);
            
while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
    $produto_id = $select['produto'];
    $quantidade = $select['total_quantidade'];
    $data_entrada = $select['ultima_entrada'];

$query2 = $conexao->prepare("
        SELECT produto, unidade, minimo 
        FROM estoque_item 
        WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id
    ");
$query2->execute([
        'id' => $produto_id
        ]);
        $select2 = $query2->fetch(PDO::FETCH_ASSOC);
        $produto = $select2['produto'];
        $unidade = $select2['unidade'];
        $minimo = $select2['minimo'];
        
    if ($quantidade < $minimo) {
        $itens_abaixo_minimo++;
    }
}

if($_SESSION['site_puro'] == 'chronoclick'){
$hoje = date('Y-m-d');
$dias_restantes = (strtotime($plano_validade) - strtotime($hoje)) / 86400;
}else{
    $dias_restantes = 365;
}

if ($dias_restantes > 30) {
    $bg_color = '#198754'; // verde
    $text_color = 'white';
} elseif ($dias_restantes > 15) {
    $bg_color = '#ffc107'; // amarelo
    $text_color = '#212529'; // preto
} else {
    $bg_color = '#dc3545'; // vermelho
    $text_color = 'white';
}

// Verificar tickets abertos ou em andamento (para administradores)
$notificacao_tickets = 0;
if($_SESSION['site_puro'] == 'chronoclick'){
if ($tipo_cadastro == 'Owner') {
    $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM tickets WHERE status IN ('Aberto', 'Em andamento')");
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado && $resultado['total'] > 0) {
        $notificacao_tickets = 1;
    }
}
}

//Criar Alertas
$alerta_final = '';
$class_alerta = 0;
if ($tipo_cadastro != 'Paciente') {
    if ($itens_abaixo_minimo > 0) {
        $class_alerta = 1;
        $alerta_final .= "🔴 Você possui $itens_abaixo_minimo produto(s) com estoque abaixo do mínimo.<br>\n";
    }
    if ($alteracao_qtd > 0) {
        $class_alerta = 1;
        $alerta_final .= "🔴 Você possui $alteracao_qtd solicitação(ões) pendentes.<br>\n";
    }
    if ($notificacao_tickets > 0) {
        $class_alerta = 1;
        $alerta_final .= "🔴 Você tem {$resultado['total']} ticket(s) pendente(s) para responder.<br>\n";
    }
}
?>

<?php if ($class_alerta == 1): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'warning',
        title: 'Atenção!',
        html: `<pre style="text-align:left; font-size: 14px; white-space: pre-wrap;"><?= nl2br(addslashes($alerta_final)) ?></pre>`,
        confirmButtonText: 'Ok',
        customClass: {
            popup: 'swal2-rounded',
        }
    });
});
</script>
<?php endif; ?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
        <?php if($class_alerta == 1){ ?>
        <span style="font-size: 0.8rem; padding: 2px; background-color: #ffc107; color: black; border-radius: 5px; margin-left: 10px; text-align: left; line-height: 1.5;">
            <?= $alerta_final ?>
        </span>
        <?php } ?>
        <?php if ($tipo_cadastro != 'Paciente' && $_SESSION['site_puro'] == 'chronoclick') { ?>
            <span style="font-size: 1rem; margin-left: 10px; background-color: <?= $bg_color ?>; color: <?= $text_color ?>; padding: 4px 8px; border-radius: 5px;">
                Plano válido até <?= date('d/m/Y', strtotime($plano_validade)) ?>
            </span><?php } ?><a href="logout.php" style="font-size: 1rem; margin-left: 10px; background-color: darkred; color: white; padding: 4px 8px; border-radius: 5px;">Sair</a>
</body>
</html>
