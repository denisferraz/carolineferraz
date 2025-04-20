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
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo '<div style="color: red; font-weight: bold; text-align: center; margin-top: 50px;">Você não tem permissão para acessar esta página.</div>';
    exit;
}

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));
$confirmacao = gerarConfirmacao();

$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

$email = $nome = $telefone = $cpf = '';

if($id_job == 'Cadastro'){
    $email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

    $query_check2 = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$email}'");
    while($select_check2 = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $nome = $select_check2['nome'];
        $telefone = $select_check2['telefone'];
        $cpf = $select_check2['unico'];
    }
}
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
                        showCancelButton: true,
                        confirmButtonText: 'Importar dados',
                        cancelButtonText: 'Não importar'
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
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Cadastrar cliente',
                        denyButtonText: 'Continuar sem cadastrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona ou abre modal para cadastro
                            window.location.href = 'cadastro_registro.php?cpf=' + cpfLimpo;
                        } else if (result.isDenied) {
                            // Continuar sem cadastro
                            Swal.fire('Continuando sem cadastro', '', 'info');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro na busca do CPF', '', 'error');
            });
        }

        function preencherCamposCliente(data) {
            // Aqui você pode preencher campos no formulário com os dados do cliente
            // Exemplo:
            document.querySelector('[name="doc_nome"]').value = data.nome;
            document.querySelector('[name="doc_email"]').value = data.email;
            document.querySelector('[name="doc_telefone"]').value = data.telefone;
            // adicione mais conforme seus campos
        }
        </script>

</head>
<body>
    <form class="form" action="../reservas_php.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Cadastrar Nova Consulta</h2>
            </div>

            <div class="card-group">
                <label>Dia do Atendimento</label>
                <input type="date" name="atendimento_dia" min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" required>
            </div>

            <div class="card-group">
                <label>Horário</label>
                <input type="time" name="atendimento_hora" min="01:00" max="23:00" required>
            </div>

            <div class="card-group">
                <label>CPF</label>
                <input type="text" id="doc_cpf" name="doc_cpf" class="form-control" minlength="11" maxlength="14"
                    placeholder="000.000.000-00"
                    onkeypress="formatar('###.###.###-##', this)"
                    onchange="buscarCadastroCPF()" required>
            </div>

            <div class="card-group">
                <label>Nome</label>
                <input type="text" name="doc_nome" minlength="5" maxlength="30" value="<?php echo $nome ?>" placeholder="Nome e Sobrenome" required>
            </div>

            <div class="card-group">
                <label>Email</label>
                <input type="email" name="doc_email" minlength="10" maxlength="35" value="<?php echo $email ?>" placeholder="exemplo@exemplo.com" required>
            </div>

            <div class="card-group">
                <label>Telefone</label>
                <input type="text" name="doc_telefone" minlength="11" maxlength="18" value="<?php echo $telefone ?>" placeholder="(00)00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>
            </div>

            <div class="card-group">
                <label>Tipo de Consulta</label>
                <select name="id_job">
                    <option value="Avaliação Capilar">Avaliação Capilar</option>
                    <option value="Consulta Capilar">Consulta Capilar</option>
                    <option value="Consulta Online">Consulta Online</option>
                    <option value="Nova Sessão">Nova Sessão</option>
                </select>
            </div>

            <div class="card-group">
                <label>Local da Consulta</label>
                <select name="atendimento_local">
                    <option value="Lauro de Freitas">Lauro de Freitas</option>
                    <option value="Salvador">Salvador</option>
                </select>
            </div>

            <div class="card-group">
                <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input type="hidden" name="status_reserva" value="Confirmado">
                <input type="hidden" name="feitapor" value="Painel">
            </div>

            <div class="card-group">
                <input id="overbook" type="checkbox" name="overbook">
                <label for="overbook">Forçar Overbook</label>
            </div>

            <div class="card-group">
                <input id="overbook_data" type="checkbox" name="overbook_data">
                <label for="overbook_data">Forçar Data/Horário</label>
            </div>

            <div class="card-group btn">
                <button type="submit">Confirmar Consulta</button>
            </div>
        </div>
    </form>
</body>
</html>
