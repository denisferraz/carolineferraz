<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));

$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);
$tipo = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['tipo'] ?? 'Painel') : 'Painel';

$email = $nome = $telefone = $cpf = '';

if($id_job == 'Cadastro' || $tipo != 'Painel'){

    $email = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['email'] ?? $_SESSION['email']) : $_SESSION['email'];

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
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
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}
    
foreach ($painel_users_array as $select_check2){
    $nome = $select_check2['nome'];
    $telefone = $select_check2['telefone'];
    $cpf = $select_check2['cpf'];
}
}
$error_reserva = isset($_SESSION['error_reserva']) ? $_SESSION['error_reserva'] : null;
unset($_SESSION['error_reserva']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Consulta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                text: 'Confirmando a consulta...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    </script>
    <script>
        function limparCPF(cpf) {
            return cpf.replace(/[^\d]+/g, '');
        }

        function buscarCadastroCPF() {
            const input = document.getElementById('doc_cpf');
            const cpfLimpo = limparCPF(input.value);

            fetch('buscar_cadastro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cpf=' + cpfLimpo
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        title: 'CPF encontrado!',
                        html: `Cliente: <strong>${data.nome}</strong><br>Email: <strong>${data.email}</strong><br><br>Deseja importar os dados?`,
                        icon: 'success',
                        allowOutsideClick: false,
                        confirmButtonText: 'Importar dados'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aqui você pode preencher os campos automaticamente
                            preencherCamposCliente(data);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'CPF não encontrado!',
                        text: 'Deseja cadastrar um novo cliente ou continuar sem cadastro?',
                        icon: 'warning',
                        allowOutsideClick: false,
                        confirmButtonText: 'Cadastrar cliente'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona ou abre modal para cadastro
                            window.location.href = 'cadastro_registro.php?cpf=' + cpfLimpo;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro na busca do CPF', '', 'error');
            });
        }

        function buscarCadastroEmail() {
            const input = document.getElementById('doc_email');
            const emailLimpo = input.value;

            fetch('buscar_cadastro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + emailLimpo
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    Swal.fire({
                        title: 'Email encontrado!',
                        html: `Cliente: <strong>${data.nome}</strong><br>CPF: <strong>${data.doc_cpf}</strong><br><br>Deseja importar os dados?`,
                        icon: 'success',
                        allowOutsideClick: false,
                        confirmButtonText: 'Importar dados'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aqui você pode preencher os campos automaticamente
                            preencherCamposCliente(data);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Email não encontrado!',
                        text: 'Deseja cadastrar um novo cliente ou continuar sem cadastro?',
                        icon: 'warning',
                        allowOutsideClick: false,
                        confirmButtonText: 'Cadastrar cliente'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona ou abre modal para cadastro
                            window.location.href = 'cadastro_registro.php?email=' + emailLimpo;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro na busca do Email', '', 'error');
            });
        }

        function preencherCamposCliente(data) {
            // Aqui você pode preencher campos no formulário com os dados do cliente
            // Exemplo:
            document.querySelector('[name="doc_nome"]').value = data.nome;
            document.querySelector('[name="doc_email"]').value = data.email;
            document.querySelector('[name="doc_telefone"]').value = data.telefone;
            document.querySelector('[name="doc_cpf"]').value = data.doc_cpf;
            // adicione mais conforme seus campos
        }
        </script>

</head>
<body>
    <form class="form" action="../reservas_php.php" method="POST" onsubmit="exibirPopup()">
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Cadastrar Nova Consulta <i class="bi bi-question-square-fill"onclick="ajudaConsultaCadastrar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <?php if ($error_reserva): ?>
            <div class="card-top">
                <h3><?php echo $error_reserva; ?></h3>
            </div>
            <?php endif; ?>

            <div class="card-group">
                <label>Dia do Atendimento</label>
                <input data-step="2" type="date" name="atendimento_dia" min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" required>
            </div>

            <div class="card-group">
                <label>Horário</label>
                <select data-step="3" name="atendimento_hora">
                        <?php
                        $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
                        $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){
                        ?>
                                <option value="<?php echo date('H:i:s', $atendimento_hora_comeco) ?>"><?php echo date('H:i', $atendimento_hora_comeco) ?></option>
    
                        <?php
                        $rodadas++;
                        $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
                            }

                        ?>
                            </select>
            </div>

            <?php if($tipo == 'Painel'){ ?>

            <div class="card-group">
                <label>Email</label>
                <input data-step="4" type="email" id="doc_email" name="doc_email" minlength="10" maxlength="35" value="<?php echo $email ?>" placeholder="exemplo@exemplo.com" onchange="buscarCadastroEmail()" required>
            </div>

            <div class="card-group">
                <label>CPF</label>
                <input data-step="5" type="text" id="doc_cpf" name="doc_cpf" value="<?php echo $cpf ?>" class="form-control" minlength="11" maxlength="14" placeholder="000.000.000-00" onkeypress="formatar('###.###.###-##', this)" onchange="buscarCadastroCPF()" required>
            </div>

            <div class="card-group">
                <label>Nome</label>
                <input data-step="6" type="text" name="doc_nome" minlength="5" maxlength="30" value="<?php echo $nome ?>" placeholder="Nome e Sobrenome" required>
            </div>

            <div class="card-group">
                <label>Telefone</label>
                <input data-step="7" type="text" name="doc_telefone" minlength="9" maxlength="18" value="<?php echo $telefone ?>" placeholder="(00)00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>
            </div>

            <?php }else{ ?>
                <input type="hidden" name="doc_cpf" value="<?php echo $cpf ?>">
                <input type="hidden" name="doc_nome" value="<?php echo $nome ?>">
                <input type="hidden" name="doc_email" value="<?php echo $email ?>">
                <input type="hidden" name="doc_telefone" value="<?php echo $telefone ?>">
            <?php } ?>

            <div class="card-group">
                <label>Tipo de Consulta</label>
                <select data-step="8" name="id_job">
                    <option value="Nova Consulta">Nova Consulta</option>
                    <option value="Consulta Retorno">Consulta Retorno</option>
                    <option value="Consulta x2">Consulta x2</option>
                    <option value="Consulta Online">Consulta Online</option>
                </select>
            </div>

            <div class="card-group">
                <label>Local da Consulta</label>
                <input data-step="9" type="text" name="atendimento_local" maxlength="50" placeholder="Local Atendimento">
            </div>

            <div class="card-group">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input type="hidden" name="status_consulta" value="Confirmado">
                <input type="hidden" name="feitapor" value="<?php echo $tipo; ?>">
            </div>

            <?php if($tipo == 'Painel'){ ?>
            <div class="card-group">
                <input id="overbook" type="checkbox" name="overbook">
                <label data-step="10" for="overbook">Forçar Overbook</label>
            </div>

            <div class="card-group">
                <input id="overbook_data" type="checkbox" name="overbook_data">
                <label data-step="11" for="overbook_data">Forçar Data/Horário</label>
            </div>
            <?php } ?>

            <div class="card-group btn">
                <button data-step="12" type="submit">Confirmar Consulta</button>
            </div>
        </div>
    </form>
</body>
</html>
