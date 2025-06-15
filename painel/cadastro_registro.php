<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$cpf = mysqli_real_escape_string($conn_msqli, $_GET['cpf']);
//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";
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
                text: 'Cadastrando...',
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
                input.classList.add('erro-campo');
                input.setCustomValidity('CPF já registrado.');
                
                Swal.fire({
                    title: 'CPF já registrado!',
                    html: `Cliente: <strong>${data.nome}</strong><br>Email: <strong>${data.email}</strong>`,
                    icon: 'warning',
                }).then((result) => {
                        preencherCamposCliente(data);
                        input.setCustomValidity(''); // Limpa erro, pois aceitou importar
                        input.classList.remove('erro-campo');
                });
            }else{
                input.setCustomValidity(''); // Limpa erro, pois aceitou importar
                input.classList.remove('erro-campo');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire('Erro ao buscar CPF', '', 'error');
        });
    }

    // Opcional: ao mudar o valor, remove a marcação de erro
    document.getElementById('doc_cpf').addEventListener('input', function () {
        this.classList.remove('erro-campo');
        this.setCustomValidity('');
    });
</script>

</head>
<body>
    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Cadastrar Novo Cliente</h2>
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
                <input type="text" name="doc_nome" minlength="5" maxlength="30" placeholder="Nome e Sobrenome" required>
            </div>

            <div class="card-group">
                <label>Email</label>
                <input type="email" name="doc_email" minlength="10" maxlength="35" placeholder="exemplo@exemplo.com" required>
            </div>

            <div class="card-group">
                <label>Telefone</label>
                <input type="text" name="doc_telefone" minlength="11" maxlength="18" placeholder="(00)00000-0000" OnKeyPress="formatar('##-#####-####', this)" required>
            </div>

            <div class="card-group">
                <label>Nascimento</label>
                <input type="date" name="doc_nascimento" minlength="11" max="<?php echo $hoje ?>" required>
            </div>

            <div class="card-group">
                <label>Onde nos Conheceu?</label>
                <select name="origem">
                        <option value="Instagram">Instagram</option>
                        <option value="Google">Google</option>
                        <option value="Indicação">Indicação</option>
                        </select>
            </div>
<br>
            <div class="card-group">
                <input type="hidden" name="id_job" value="cadastro_novo">
            </div>

            <div class="card-group btn">
                <button type="submit">Cadastrar</button>
            </div>
        </div>
    </form>
</body>
</html>
