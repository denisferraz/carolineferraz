<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

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

foreach ($painel_users_array as $select){
$paciente_id = $select['id'];
$doc_nome = $select['nome'];
$doc_rg = $select['rg'];
$cpf = $select['cpf'];
$data_nascimento = $select['nascimento'];
$telefone = $select['telefone'];
$token_profile = $select['token'];
$origem = $select['origem'];
$profissao = $select['profissao'];
$cep = $select['cep'];
$rua = $select['rua'];
$numero = $select['numero'];
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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
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

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-pencil-square"></i>
                Editar Cadastro
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar este cadastro
            </p>
        </div>
    </div>
<form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">

                <div class="form-row">
                <div class="health-form-group">

                <label class="health-label"><b>Email</b></label><?php echo $email ?>
                <input type="hidden" name="doc_email" minlength="10" value="<?php echo $email ?>">

                <label class="health-label"><b>CPF</b></label><?php echo $doc_cpf ?>
                <input type="hidden" name="doc_cpf" value="<?php echo $cpf ?>">

                </div></div>
                <div class="form-row">
                <div class="health-form-group">

                <label class="health-label"><b>Nome</b></label>
                <input class="health-input" type="text" name="doc_nome" minlength="5" maxlength="30" value="<?php echo $doc_nome ?>" placeholder="Nome e Sobrenome" required>

                </div><div class="health-form-group">
                <label class="health-label"><b>Telefone</b></label>
                <input class="health-input" type="text" name="doc_telefone" minlength="11" maxlength="18" value="<?php echo $telefone ?>" placeholder="00-00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>

                </div></div>
                <div class="form-row">
                <div class="health-form-group">

                <label class="health-label"><b>RG</b></label>
                <input class="health-input" type="text" name="doc_rg" maxlength="18" value="<?php echo $doc_rg ?>" placeholder="00000">

                </div><div class="health-form-group">
                <label class="health-label"><b>Nascimento</b></label>
                <input class="health-input" type="date" name="nascimento" value="<?php echo $data_nascimento ?>" required>

                </div><div class="health-form-group">
                <label class="health-label"><b>Profissão</b></label>
                <input class="health-input" type="text" name="profissao" maxlength="25" value="<?php echo $profissao ?>" placeholder="Profissão">

                </div></div>

                <label class="health-label"><b>Endereço Completo</b></label>
                <div class="form-row">
                <div class="health-form-group">

            <label class="health-label" for="endereco_cep">[<b>CEP</b>]</label>
            <input class="health-input" type="text" id="endereco_cep" name="endereco_cep" value="<?php echo $cep ?>" placeholder="CEP..."><br>

            </div><div class="health-form-group">
            <label class="health-label" for="endereco_rua">[<b>Rua</b>]</label>
            <input class="health-input" type="text" id="endereco_rua" maxlength="50" value="<?php echo $rua ?>" name="endereco_rua" placeholder="Rua..."><br>

            </div><div class="health-form-group">
            <label class="health-label" for="endereco_n">[<b>Numero</b>]</label>
            <input class="health-input" type="text" id="endereco_n" maxlength="50" value="<?php echo $numero ?>" name="endereco_n" placeholder="Numero..."><br>

            </div><div class="health-form-group">
            <label class="health-label" for="endereco_bairro">[<b>Bairro</b>]</label>
            <input class="health-input" type="text" id="endereco_bairro" maxlength="50" value="<?php echo $bairro ?>" name="endereco_bairro" placeholder="Bairro..."><br>

            </div><div class="health-form-group">
            <label class="health-label" for="endereco_cidade">[<b>Cidade</b>]</label>
            <input class="health-input" type="text" id="endereco_cidade" maxlength="50" value="<?php echo $cidade ?>" name="endereco_cidade" placeholder="Cidade..."><br>

            </div><div class="health-form-group">
            <label class="health-label" for="endereco_uf">[<b>Estado</b>]</label>
            <input class="health-input" type="text" id="endereco_uf" maxlength="50" value="<?php echo $estado ?>" name="endereco_uf" placeholder="Estado..."><br>
            </div></div>
                <input type="hidden" name="id_job" value="cadastro_editar">
                <input type="hidden" name="feitopor" value="Painel">

                <button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Atualizar Dados</button>
    </form>
    </div>
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
