<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{
    
$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

<?php
$query = $conexao->query("SELECT * FROM painel_users WHERE id >= 1 AND tipo = 'Paciente' ORDER BY nome ASC");
$query_row = $query->rowCount();
?>

<fieldset>
    <legend><h2>
        <?= $query_row == 0 ? 'Sem Cadastros' : "Cadastros [$query_row]" ?>
    </h2></legend>
    <?php if ($query_row > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
            <?php while($select = $query->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>
                    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?= $select['email'] ?>","iframe-home")'>
                            <button><?= $select['nome'] ?></button>
                        </a>
                    </td>
                    <td><?= $select['email'] ?></td>
                    <td>
                        <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $select['telefone']) ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i> <?= $select['telefone'] ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</fieldset>

</body>
</html>


<?php
}
?>