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
$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND tipo = :tipo");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'tipo' => 'Paciente'));
$query_row = $query->rowCount();
?>

<fieldset>
    <legend><h2>
        <?= $query_row == 0 ? 'Sem Cadastros' : "Cadastros [$query_row]" ?>
    </h2></legend>
    <?php if ($query_row > 0): 
        
        $painel_users_array = [];
        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $dados_painel_users = $select['dados_painel_users'];
            $id = $select['id'];
            $email = $select['email'];
    
        // Para descriptografar os dados
        $dados = base64_decode($dados_painel_users);
        $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
        $dados_array = explode(';', $dados_decifrados);
    
        $painel_users_array[] = [
            'id' => $id,
            'email' => $email,
            'nome' => $dados_array[0],
            'rg' => $dados_array[1],
            'cpf' => $dados_array[2],
            'telefone' => $dados_array[3],
            'profissao' => $dados_array[4],
            'nascimento' => $dados_array[5],
            'cep' => $dados_array[6],
            'rua' => $dados_array[7],
            'numero' => $dados_array[8],
            'cidade' => $dados_array[9],
            'bairro' => $dados_array[10],
            'estado' => $dados_array[11]
        ];
    
        }
        
        usort($painel_users_array, function ($a, $b) {
            return $a['nome'] <=> $b['nome'];
        });
        
        ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($painel_users_array as $select): ?>
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
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</fieldset>

</body>
</html>
