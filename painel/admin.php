<?php
session_start();
require("../config/database.php");
require("verifica_login.php");

$id_job = isset($_GET["id_job"]) ? mysqli_real_escape_string($conn_msqli, $_GET["id_job"]) : '';

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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid var(--health-gray-900);
        }
        
        .data-table th {
            background: var(--health-gray-300);
            font-weight: 600;
            color: var(--health-gray-800);
        }
        
        .data-table tr:hover {
            background: var(--health-gray-200);
        }

        .valor-sugestao {
            background: var(--health-success-light);
            color: var(--health-success);
        }
        
        .valor-margem {
            background: var(--health-warning-light);
            color: var(--health-warning);
        }
        
        .valor-taxas {
            background: var(--health-danger-light);
            color: var(--health-danger);
        }
        
        .valor-total {
            background: var(--health-info-light);
            color: var(--health-info);
        }

    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .data-table {
            min-width: 600px; /* ou o mínimo necessário para sua tabela não quebrar */
        }

        .data-table th, .data-table td {
            padding: 8px;
            font-size: 0.8rem;
        }
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
                <?php if($id_job == 'Novo'){ ?>
                <i class="bi bi-person-plus health-icon-lg"></i>
                Cadastrar Novo Acesso
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para registrar um novo acesso no sistema
            </p>
            <?php }else{ ?>
                <i class="bi bi-person-gear health-icon-lg"></i>
                Gerenciar Acessos
            </h1>
            <p class="health-card-subtitle">
                Gerencie todos os cadastros com acesso ao seu sistema
            </p>
            <?php } ?>
        </div>
    </div>
<?php if($id_job == 'Novo'){ ?>
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
                    <input type="email" id="doc_email" name="doc_email" class="health-input" minlength="10" maxlength="100" placeholder="exemplo@exemplo.com" required>
                </div>
                
                    <label class="health-label" for="doc_cpf">
                        <i class="bi bi-credit-card"></i>
                        CPF *
                    </label>
                    <input type="text" id="doc_cpf" name="doc_cpf" class="health-input" minlength="11" maxlength="14"
                        placeholder="000.000.000-00" value="<?php echo htmlspecialchars($cpf); ?>"
                        onkeypress="formatar('###.###.###-##', this)"
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
                    <label class="health-label" for="senha">
                        <i class="bi bi-shield-lock"></i>
                        Senha *
                    </label>
                    <input type="password" id="senha" name="senha" class="health-input" minlength="5" maxlength="100" required>
                </div>

                <div class="health-form-group">
                    <label class="health-label" for="conf_senha">
                        <i class="bi bi-shield-lock-fill"></i>
                        Confirmar Senha *
                    </label>
                    <input type="password" id="conf_senha" name="conf_senha" class="health-input" minlength="5" maxlength="100" required>
                </div>
            </div>

            <input type="hidden" name="id_job" value="cadastro_novo_admin">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token_emp']; ?>">
            <input type="hidden" name="doc_nascimento" value="<?php echo $hoje; ?>">
            
            <button type="submit" class="health-btn health-btn-primary"><i class="bi bi-check-lg"></i> Cadastrar Cliente</button>

        </div>
    </form>
<?php }else{ ?>
<div class="table-responsive">
    <table class="data-table">
    <thead>
                <tr>
                    <th>Email</th>
                    <th>Nome</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM painel_users WHERE token = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY email DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $email = $select['email'];
                ?>
                <tr>
                    <td data-label="Email"><i class="bi bi-envelope"></i> <?php echo $email; ?></td>
                    <td data-label="Nome"><i class="bi bi-person"></i> <?php echo $email; ?></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("admin_excluir.php?id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i> Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table></div>

<?php } ?>
</body>
</html>