<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
    
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
$query = $conexao->query("SELECT * FROM painel_users WHERE id >= 1 AND tipo != 'Paciente' AND plano_escolhido != 'Removido'");
$query_row = $query->rowCount();

$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $email = $select['email'];
        $plano_validade = $select['plano_validade'];
        $token = $select['token'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0],
        'telefone' => $dados_array[3],
        'email' => $email,
        'plano_validade' => $plano_validade,
        'token' => $token
    ];

}

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
                    <th>Validade</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($painel_users_array as $select){ ?>
                <tr>
                    <td>
                    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?= $select['email'] ?>","iframe-home")'>
                            <button><?= $select['email'] ?></button>
                        </a>
                    </td>
                    <td><?= $select['email'] ?></td>
                    <td>
                        <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $select['telefone']) ?>" target="_blank">
                            <i class="fab fa-whatsapp"></i> <?= $select['telefone'] ?>
                        </a>
                    </td>
                    <td><?= date('d/m/Y', strtotime($select['plano_validade'])); ?></td>
                    <td><a href="javascript:void(0)" onclick='window.open("owner_cadastro_editar.php?email=<?php echo $select['email'] ?>","iframe-home")'>
                        <button>Editar</button>
                    </a></td>
                    <td><a href="javascript:void(0)" onclick='window.open("owner_cadastro_excluir.php?email=<?php echo $select['email'] ?>&token=<?php echo $select['token'] ?>","iframe-home")'>
                        <button>Excluir</button>
                    </a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>
</fieldset>

</body>
</html>