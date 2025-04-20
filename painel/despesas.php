<?php
session_start();
require('../conexao.php');
require('verifica_login.php');

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'escuro'; // padrão é escuro

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
$aut_acesso = $query_check->fetch(PDO::FETCH_ASSOC)['aut_painel'];

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta página';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Despesas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

    <div class="container">
        <h2>Minhas Despesas</h2>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM despesas WHERE id >= :id ORDER BY despesa_dia DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $despesa_dia = date('d/m/Y', strtotime($select['despesa_dia']));
                    $despesa_tipo = $select['despesa_tipo'];
                    $despesa_valor = 'R$' . number_format($select['despesa_valor'], 2, ',', '.');
                    $despesa_descricao = $select['despesa_descricao'];
                ?>
                <tr>
                    <td data-label="Data"><?php echo $despesa_dia; ?></td>
                    <td data-label="Tipo"><?php echo $despesa_tipo; ?></td>
                    <td data-label="Valor"><?php echo $despesa_valor; ?></td>
                    <td data-label="Descrição"><?php echo $despesa_descricao; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
