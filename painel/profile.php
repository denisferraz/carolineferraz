<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$email = $_SESSION['email'];

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));

$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $select['email'],
        'token' => $select['token'],
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

foreach ($painel_users_array as $select){
        $doc_nome = $select['nome'];
        $cpf = $select['cpf'];
        $telefone = $select['telefone'];
        $data_nascimento = $select['nascimento'];
        $doc_rg = $select['rg'];
        $profissao = $select['profissao'];
        $cep = $select['cep'];
        $rua = $select['rua'];
        $numero = $select['numero'];
        $complemento = $select['complemento'];
        $cidade = $select['cidade'];
        $bairro = $select['bairro'];
        $estado = $select['estado'];
    }

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "$ddd-$prefixo-$sufixo";

$min_nasc = date('Y-m-d', strtotime("-110 years",strtotime($hoje))); 
$max_nasc = date('Y-m-d', strtotime("-18 years",strtotime($hoje)));

if($data_nascimento == ''){
    $data_nascimento = $hoje;
}

$error_reserva = isset($_SESSION['error_reserva']) ? $_SESSION['error_reserva'] : null;
unset($_SESSION['error_reserva']);

$id_job = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['id_job'] ?? 'Profile') : 'Profile';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
    <script>
        function formatar(mascara, documento) {
            var i = documento.value.length;
            var saida = mascara.substring(0,1);
            var texto = mascara.substring(i)
            if (texto.substring(0,1) != saida) {
                documento.value += texto.substring(0,1);
            }
        }

        function exibirPopup() {
            Swal.fire({
                icon: 'info',
                title: 'Aguarde...',
                text: 'Editando...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    </script>
</head>
<body>

    <?php if($id_job == 'Senha'){ ?>
    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Alterar Senha</h2>
            </div>

            <?php if ($error_reserva): ?>
            <div class="card-top">
                <h3><?php echo $error_reserva; ?></h3>
            </div>
            <?php endif; ?>

            <div class="card-group">
                <label>CPF</label><?php echo $doc_cpf ?>
                <input type="hidden" name="doc_cpf" value="<?php echo $cpf ?>">
            </div>

            <div class="card-group">
                <label>Email</label><?php echo $email ?>
                <input type="hidden" name="doc_email" minlength="10" value="<?php echo $email ?>">
            </div>

            <div class="card-group">
                <label>Senha Antiga</label>
                <input type="password" name="senha_antes" minlength="5" maxlength="30" required>
            </div>

            <div class="card-group">
                <label>Senha Nova</label>
                <input type="password" name="senha_nova" minlength="5" maxlength="30" required>
            </div>

            <div class="card-group">
                <label>Confirmar Senha Nova</label>
                <input type="password" name="senha_nova_conf" minlength="5" maxlength="30" required>
            </div>

            <div class="card-group">
                <input type="hidden" name="id_job" value="cadastro_editar_senha">
                <input type="hidden" name="feitopor" value="Paciente">
            </div>

            <div class="card-group btn">
                <button type="submit">Cadastrar</button>
            </div>
        </div>
    </form>

    <?php }else if($id_job == 'Profile'){ ?>

    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
    <div class="card">
        <div class="card-top">
            <h2>Editar Cadastro</h2>
        </div>

        <div class="card-group">
            <label>CPF</label><?php echo $doc_cpf ?>
            <input type="hidden" name="doc_cpf" value="<?php echo $cpf ?>">
        </div>

        <div class="card-group">
            <label>Email</label><?php echo $email ?>
            <input type="hidden" name="doc_email" minlength="10" value="<?php echo $email ?>">
        </div>

        <div class="card-group">
            <label>Nome</label>
            <input type="text" name="doc_nome" minlength="5" maxlength="30" value="<?php echo $doc_nome ?>" placeholder="Nome e Sobrenome" required>
        </div>

        <div class="card-group">
            <label>Telefone</label>
            <input type="text" name="doc_telefone" minlength="11" maxlength="18" value="<?php echo $telefone ?>" placeholder="00-00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>
        </div>

        <div class="card-group">
            <label>RG</label>
            <input type="text" name="doc_rg" maxlength="18" value="<?php echo $doc_rg ?>" placeholder="00000" required>
        </div>

        <div class="card-group">
            <label>Nascimento</label>
            <input type="date" name="nascimento" min="<?php echo $min_nasc ?>" max="<?php echo $max_nasc ?>" value="<?php echo $data_nascimento ?>" required>
        </div>

        <div class="card-group">
            <label>Profissão</label>
            <input type="text" name="profissao" maxlength="25" value="<?php echo $profissao ?>" placeholder="Profissão" required>
        </div>

        <br>
        <div class="card-group">
        <label><b>Endereço Completo</b></label>
        </div>
        <br>
        <div class="card-group">
        <label for="endereco_cep">[<b>CEP</b>]</label>
        <input type="text" id="endereco_cep" name="endereco_cep" value="<?php echo $cep ?>" placeholder="CEP..." required><br>
        </div><div class="card-group">
        <label for="endereco_rua">[<b>Rua</b>]</label>
        <input type="text" id="endereco_rua" maxlength="50" value="<?php echo $rua ?>" name="endereco_rua" placeholder="Rua..." required><br>
        </div><div class="card-group">
        <label for="endereco_n">[<b>Numero</b>]</label>
        <input type="text" id="endereco_n" maxlength="50" value="<?php echo $numero ?>" name="endereco_n" placeholder="Numero..." required><br>
        </div><div class="card-group">
        </div><div class="card-group">
        <label for="endereco_bairro">[<b>Bairro</b>]</label>
        <input type="text" id="endereco_bairro" maxlength="50" value="<?php echo $bairro ?>" name="endereco_bairro" placeholder="Bairro..." required><br>
        </div><div class="card-group">
        <label for="endereco_cidade">[<b>Cidade</b>]</label>
        <input type="text" id="endereco_cidade" maxlength="50" value="<?php echo $cidade ?>" name="endereco_cidade" placeholder="Cidade..." required><br>
        </div><div class="card-group">
        <label for="endereco_uf">[<b>Estado</b>]</label>
        <input type="text" id="endereco_uf" maxlength="50" value="<?php echo $estado ?>" name="endereco_uf" placeholder="Estado..." required><br>
        </div>

        <div class="card-group">
            <input type="hidden" name="id_job" value="cadastro_editar">
            <input type="hidden" name="feitopor" value="Paciente">
        </div>

        <div class="card-group btn">
            <button type="submit">Cadastrar</button>
        </div>
    </div>
    </form>

    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#endereco_cep').on('keyup', function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('#endereco_rua').val(data.logradouro);
                        $('#endereco_bairro').val(data.bairro);
                        $('#endereco_cidade').val(data.localidade);
                        $('#endereco_uf').val(data.uf);
                    }
                });
            }
        });
    });
</script>

</body>
</html>
