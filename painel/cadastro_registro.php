<?php
session_start();
require("../config/database.php");
require("verifica_login.php");

$cpf = isset($_GET["cpf"]) ? mysqli_real_escape_string($conn_msqli, $_GET["cpf"]) : "";
// Ajustar CPF para exibição se já vier formatado ou precisar de ajuste
if (!empty($cpf)) {
    $cpf = preg_replace("/\\D/", "", $cpf); // Remove caracteres não numéricos
    if (strlen($cpf) == 11) {
        $parte1 = substr($cpf, 0, 3);
        $parte2 = substr($cpf, 3, 3);
        $parte3 = substr($cpf, 6, 3);
        $parte4 = substr($cpf, 9);
        $cpf = "$parte1.$parte2.$parte3-$parte4";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente - <?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
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
                text: 'Cadastrando...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }

        // Removida a função buscarCadastroCPF() e preencherCamposCliente() pois o arquivo buscar_cadastro.php não foi fornecido.
        // Opcional: ao mudar o valor, remove a marcação de erro
        document.getElementById('doc_cpf').addEventListener('input', function () {
            this.classList.remove('erro-campo');
            this.setCustomValidity('');
        });

        // Adiciona validação de formulário com SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro de Validação',
                        text: 'Por favor, preencha todos os campos obrigatórios corretamente.',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    exibirPopup(); // Exibe o popup de carregamento se a validação passar
                }
            });
        });
    </script>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-person-plus health-icon-lg"></i>
                Cadastrar Novo Cliente
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para registrar um novo cliente no sistema
            </p>
        </div>
    </div>

    <form class="form" action="acao.php" method="POST">
        <div class="form-section health-fade-in">
            <div class="form-section-title">
                <i class="bi bi-person-vcard"></i>
                Dados Pessoais
            </div>

            <div class="form-row">
                <div class="health-form-group">
                    
                    <div class="health-form-group">
                    <label class="health-label" for="doc_email">
                        <i class="bi bi-envelope"></i>
                        Email *
                    </label>
                    <input type="email" id="doc_email" name="doc_email" class="health-input" minlength="10" maxlength="100" placeholder="exemplo@exemplo.com" onchange="buscarCadastroEmail()" required>
                </div>
                
                    <label class="health-label" for="doc_cpf">
                        <i class="bi bi-credit-card"></i>
                        CPF *
                    </label>
                    <input type="text" id="doc_cpf" name="doc_cpf" class="health-input" minlength="11" maxlength="14"
                        placeholder="000.000.000-00" value="<?php echo htmlspecialchars($cpf); ?>"
                        onkeypress="formatar('###.###.###-##', this)" 
                        onchange="buscarCadastroCPF()"
                        required>
                </div>
                
            </div>

            <div class="form-row">
                <div class="health-form-group">
                    <label class="health-label" for="doc_nome">
                        <i class="bi bi-person"></i>
                        Nome *
                    </label>
                    <input type="text" id="doc_nome" name="doc_nome" class="health-input" minlength="5" maxlength="100" placeholder="Nome e Sobrenome" required>
                </div>

                <div class="health-form-group">
                    <label class="health-label" for="doc_telefone">
                        <i class="bi bi-phone"></i>
                        Telefone *
                    </label>
                    <input type="text" id="doc_telefone" name="doc_telefone" class="health-input" minlength="11" maxlength="18" placeholder="(00)00000-0000" onkeypress="formatar('##-#####-####', this)" required>
                </div>
            </div>

            <div class="form-row">
                <div class="health-form-group">
                    <label class="health-label" for="doc_nascimento">
                        <i class="bi bi-calendar"></i>
                        Nascimento *
                    </label>
                    <input type="date" id="doc_nascimento" name="doc_nascimento" class="health-input" max="<?php echo $hoje ?>" required>
                </div>

                <div class="health-form-group">
                    <label class="health-label" for="origem">
                        <i class="bi bi-globe"></i>
                        Onde nos Conheceu?
                    </label>
                    <select name="origem" id="origem" class="health-select">
                        <option value="Instagram">Instagram</option>
                        <option value="Google">Google</option>
                        <option value="Indicação">Indicação</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="id_job" value="cadastro_novo">
            
            <div class="form-actions">
                <button type="button" class="health-btn health-btn-outline" onclick="window.history.back();">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </button>
                <button type="submit" class="health-btn health-btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Cadastrar Cliente
                </button>
            </div>
        </div>
    </form>
    
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

<script>

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
                input.classList.add('erro-campo');
                input.setCustomValidity('Email já registrado.');
                
                Swal.fire({
                    title: 'Email já registrado!',
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
            Swal.fire('Erro ao buscar Email', '', 'error');
        });
    }

    // Opcional: ao mudar o valor, remove a marcação de erro
    document.getElementById('doc_email').addEventListener('input', function () {
        this.classList.remove('erro-campo');
        this.setCustomValidity('');
    });
</script>
</body>
</html>