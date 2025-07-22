<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$id_job = isset($_GET['id_job']) ? $_GET['id_job'] : 'Painel';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Painel';

$email = $nome = $telefone = '';

if($id_job == 'Cadastro' || $tipo != 'Painel'){
    $email = isset($_GET['email']) ? $_GET['email'] : $_SESSION['email'];

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
    
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

        // Para descriptografar os dados (assumindo que as variáveis existem)
        if(isset($metodo) && isset($chave) && isset($iv)) {
            $dados = base64_decode($dados_painel_users);
            $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
            $dados_array = explode(';', $dados_decifrados);
        } else {
            $dados_array = explode(';', $dados_painel_users);
        }

        $nome = $dados_array[0] ?? '';
        $telefone = $dados_array[3] ?? '';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="../css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        
        .patient-selector {
            background: var(--health-bg-accent);
            border: 2px dashed var(--health-primary);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .patient-selector:hover {
            background: rgba(37, 99, 235, 0.1);
        }
        
        .patient-selected {
            background: white;
            border: 2px solid var(--health-success);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--health-success), var(--health-secondary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 8px;
            margin-top: 12px;
        }
        
        .time-slot {
            padding: 8px 12px;
            border: 2px solid var(--health-gray-300);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .time-slot:hover {
            border-color: var(--health-primary);
            background: var(--health-bg-accent);
        }
        
        .time-slot.selected {
            background: var(--health-primary);
            color: white;
            border-color: var(--health-primary);
        }
        
        .time-slot.unavailable {
            background: var(--health-gray-100);
            color: var(--health-gray-400);
            cursor: not-allowed;
            border-color: var(--health-gray-200);
        }
        
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        
        .calendar-nav {
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--health-primary);
            font-size: 1.2rem;
        }
        
        .calendar-nav:hover {
            background: var(--health-bg-accent);
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-card-list health-icon-lg"></i>
                Criar Orçamento
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para criar um orçamento
            </p>
        </div>
    </div>
    <form method="POST" action="salvar_orcamento.php">
        
        <!-- Seleção de Paciente -->
        <div class="form-section health-fade-in">
            <div class="form-section-title">
                <i class="bi bi-person-check"></i>
                Dados do Paciente
            </div>
            
            <?php if(empty($nome)): ?>
            <div class="patient-selector" onclick="selecionarPaciente()">
                <i class="bi bi-person-plus" style="font-size: 2rem; color: var(--health-primary); margin-bottom: 8px;"></i>
                <h3 style="margin: 0; color: var(--health-primary);">Selecionar Paciente</h3>
                <p style="margin: 8px 0 0; color: var(--health-gray-600);">Clique para escolher um paciente cadastrado</p>
            </div>
            <?php else: ?>
            <div class="patient-selected">
                <div class="patient-avatar">
                    <?php 
                    $iniciais = '';
                    $nome_parts = explode(' ', $nome);
                    foreach($nome_parts as $part) {
                        if(!empty($part)) {
                            $iniciais .= strtoupper(substr($part, 0, 1));
                            if(strlen($iniciais) >= 2) break;
                        }
                    }
                    echo $iniciais;
                    ?>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--health-gray-800);">
                        <?php echo htmlspecialchars($nome); ?>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--health-gray-600);">
                        <?php echo htmlspecialchars($email); ?>
                        <?php if($telefone): ?> • <?php echo htmlspecialchars($telefone); ?><?php endif; ?>
                    </div>
                </div>
                <button type="button" onclick="selecionarPaciente()" class="health-btn health-btn-outline">
                    <i class="bi bi-arrow-repeat"></i>
                    Alterar
                </button>
            </div>
            <?php endif; ?>
            
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
        </div>

        <!-- Dados da Consulta -->
        <div class="form-section health-fade-in">
            <div class="form-section-title">
                <i class="bi bi-card-text"></i>
                Dados do Orçamento
            </div>

            <div class="form-row">
            <div class="health-form-group">
            <label class="health-label">Formas de Pagamento</label>
            <textarea class="health-input" class="textarea-custom" name="formas_pgto" rows="5" cols="43" required>Cartão de Crédito em ate 10x sem juros | Cartão de Débito | Boleto | PIX</textarea>
            
            <label class="health-label">Observações</label>
            <textarea class="health-input" class="textarea-custom" name="observacoes" rows="5" cols="43" required>Proposta de manutenção facilitada no Boleto Bancário em intervalos de 30, 60 ou 90 dias (conforme modalidade de pagamento escolhida).

Para sessões à cada 90 dias: o parcelamento poderá ser realizado via boleto bancário, totalizando 09 x de R$ 116, 66. 
Nesta proposta não estão inclusos os tônicos e vitaminas para uso domiciliar e o agendamento da primeira sessão, ocorrerá mediante pagamento de pelo menos 03 parcelas.

Para sessões à cada 60 dias: o parcelamento poderá ser realizado via boleto bancário, totalizando 06x de R$ 175.
Nesta proposta não estão inclusos os tônicos e vitaminas para uso domiciliar e o agendamento da primeira sessão, ocorrerá mediante pagamento de pelo menos 02 parcelas;

Para sessões à cada 30 dias, o parcelamento poderá ser realizado via boleto bancário, totalizando 03x de R$ 350.
Nesta proposta não estão inclusos os tônicos e vitaminas para uso domiciliar e o agendamento da primeira sessão, ocorrerá mediante pagamento de pelo menos 01 parcela.

Importante: Por se tratar de uma modalidade facilitada, sem consulta aos órgãos de proteção de crédito, a tolerância para atrasos são de até 10 dias corridos, após a data de vencimento escolhida pelo cliente. Passado este período em atraso, o cliente poderá ficar impedido de usufruir de novos agendamentos e/ou vir a ser excluído do programa de recorrência.

Proposta válida até <?php echo date('d/m/Y', strtotime('+3 days', strtotime($hoje))); ?>

Sessões avulsas (fora do programa de recorrência): R$ 480 cada</textarea>
            
            </div></div>
            
                <div id="servicos">
                <button type="button" onclick="adicionarServico()" class="health-btn health-btn-success">Adicionar serviço</button>
                <button type="button" onclick="adicionarProduto()" class="health-btn health-btn-primary">Adicionar Produto</button>
                <br><br>
                <div></div>

                </div>
                <br><br>
            <button type="submit" class="health-btn health-btn-warning">
                    <i class="bi bi-check-lg"></i>
                    Emitir Orçamento
                </button>
            
        </div></div>
    </form>
</div>

<script>
function adicionarServico() {
    const div = document.createElement('div');
    div.innerHTML = ` <div class="form-row">
            <div class="health-form-group">
            <label class="health-label">Serviço</label>
            <select class="health-select lanc-servico" data-step="1.3" name="servico[]" id="lanc_servico" required onchange="atualizaValorServico(this)">
            <option value="" disabled selected>-- Escolha um Serviço --</option>
            <?php
            $query_tratamentos = $conexao->prepare("SELECT * FROM tratamentos WHERE token_emp = :token_emp AND id >= :id ORDER BY tratamento DESC");
            $query_tratamentos->execute(['token_emp' => $_SESSION['token_emp'], 'id' => 1]);
            
            while ($select_tratamentos = $query_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                $tratamento_id = $select_tratamentos['id'];
                $tratamentos = $select_tratamentos['tratamento'];
                $custo_sugerido = 0.00;
                $custo_total = 0.00;
                $taxas = 0.00;
                $impostos = 0.00;
                $margem = 0.00;
            
                $query_custos_tratamentos = $conexao->prepare("SELECT * FROM custos_tratamentos WHERE tratamento_id = :tratamento_id");
                $query_custos_tratamentos->execute(['tratamento_id' => $tratamento_id]);
            
                while ($select_custos_tratamentos = $query_custos_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                    $custo_id = $select_custos_tratamentos['custo_id'];
                    $quantidade = $select_custos_tratamentos['quantidade'];
            
                    $query_custos = $conexao->prepare("SELECT * FROM custos WHERE id = :custo_id");
                    $query_custos->execute(['custo_id' => $custo_id]);
            
                    while ($select_custos = $query_custos->fetch(PDO::FETCH_ASSOC)) {
                        $custo_valor = $select_custos['custo_valor'];
                        $custo_tipo = $select_custos['custo_tipo'];
            
                        if ($custo_tipo == 'Taxas') {
                                $taxas += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Impostos'){
                                $impostos += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Margem'){
                                $margem += $custo_valor * $quantidade;
                            }else{
                                $custos .= "($quantidade)$custo_descricao | ";
                                $custo_total += ($custo_valor * $quantidade);
                                $custo_sugerido += ($custo_valor * $quantidade);
                            }
                            }
                        }
                        
                        //$custo_extra = $custo_sugerido * ($taxas + $impostos + $margem)/ 100;
                        $margem = $custo_sugerido * $margem / 100;
                        $taxas = ($custo_sugerido + $margem) * $taxas / 100;
                        $impostos = ($custo_sugerido + $margem + $taxas) * $impostos / 100;
                        $custo_extra = $margem + $taxas + $impostos;
                        $custo_total_sugerido = ceil($custo_sugerido + $custo_extra);
                
                // FORMATAR O VALOR EM FORMATO AMERICANO PARA USAR NO value DO INPUT
                $custo_sugerido_formatado = number_format($custo_total_sugerido, 2, '.', '');
            
                ?>
                <option value="<?php echo $tratamentos; ?>" data-valor="<?php echo $custo_sugerido_formatado; ?>">
                    <?php echo $tratamentos; ?> [Sugestão - R$ <?php echo number_format($custo_total_sugerido, 2, ',', '.'); ?>]
                </option>
            <?php } ?>
            </select>
            <input type="hidden" name="quantidade[]" value="1" required>
            </div><div class="health-form-group">
            <label class="health-label">Valor</label>
            <input class="health-input lanc-valor" data-step="1.4" minlength="1" maxlength="9999" type="text" id="lanc_valor" name="valor[]" placeholder="000.00" required>
            </div></div>`;
    document.getElementById('servicos').appendChild(div);
}

function adicionarProduto() {
    const div = document.createElement('div');
    div.innerHTML = `<div class="form-row">
            <div class="health-form-group">
            <label class="health-label">Produto</label>
            <select class="health-select" data-step="3" name="servico[]" id="lanc_produto" required>
            <option value="" disabled selected>-- Escolha um Produto --</option>
            <?php
            // Consulta o saldo atual do produto
            $query = $conexao->prepare("
                SELECT ei.id, ei.produto, COALESCE(SUM(e.quantidade), 0) as saldo
                FROM estoque_item ei
                LEFT JOIN estoque e ON ei.id = e.produto AND e.token_emp = :token_emp
                WHERE ei.token_emp = :token_emp2 AND ei.id >= :id
                GROUP BY ei.id
                ORDER BY ei.produto ASC
            ");
            $query->execute([
                'token_emp' => $_SESSION['token_emp'],
                'token_emp2' => $_SESSION['token_emp'],
                'id' => 1
            ]);
            
            while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                $produto = $select['produto'];
                $produto_id = $select['id'];
                $saldo = (int) $select['saldo'];
            ?>
                <option value="<?php echo $produto_id; ?>" data-estoque="<?php echo $saldo; ?>">
                    <?php echo $produto; ?>
                </option>
            <?php } ?>
            </select>
            </div><div class="health-form-group">
            <label class="health-label">Quantidade</label>
            <input class="health-input" data-step="4" min="1" max="999" type="number" name="quantidade[]" id="lanc_quantidade" placeholder="000" required>
            </div><div class="health-form-group">
            <label class="health-label">Valor Unitário</label>
            <input class="health-input" data-step="5" minlength="1" maxlength="9999" type="text" id="lanc_valor" name="valor[]" placeholder="000.00" required>
            </div></div>`;
    document.getElementById('servicos').appendChild(div);
}
</script>

<script>
// Função para selecionar paciente
function selecionarPaciente() {
    window.open('../cadastros.php?id_job=Select', 'selecionarPaciente', 'width=800,height=600');
}

function preencherPacienteSelecionado(email, nome, telefone) {
    // Preencher campos ocultos
    document.querySelector('input[name="nome"]').value = nome;
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="telefone"]').value = telefone;

    // Oculta o seletor antigo
    document.querySelector('.patient-selector').style.display = 'none';

    // Monta HTML da div patient-selected
    const iniciais = nome.split(' ').map(p => p.charAt(0).toUpperCase()).slice(0, 2).join('');

    const html = `
        <div class="patient-selected">
            <div class="patient-avatar">${iniciais}</div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: var(--health-gray-800);">${nome}</div>
                <div style="font-size: 0.9rem; color: var(--health-gray-600);">${email}${telefone ? ' • ' + telefone : ''}</div>
            </div>
            <button type="button" onclick="selecionarPaciente()" class="health-btn health-btn-outline">
                <i class="bi bi-arrow-repeat"></i> Alterar
            </button>
        </div>
    `;

    document.querySelector('.form-section.health-fade-in').insertAdjacentHTML('afterbegin', html);
}

</script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="email"]').value.trim();
    const nome = document.querySelector('input[name="nome"]').value.trim();

    if (email === '' || nome === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Selecione um paciente',
            text: 'Você precisa selecionar um paciente antes de continuar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });

        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    // Verificar se pelo menos um serviço/produto está preenchido
    const servicos = document.querySelectorAll('select[name="servico[]"]');
    const valores = document.querySelectorAll('input[name="valor[]"]');

    let temPreenchido = false;
    for (let i = 0; i < servicos.length; i++) {
        const servico = servicos[i].value.trim();
        const valor = valores[i]?.value.trim();
        if (servico !== '' && valor !== '') {
            temPreenchido = true;
            break;
        }
    }

    if (!temPreenchido) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Adicione ao menos um Serviço/Produto',
            text: 'Você precisa adicionar ao menos um produto/serviço antes de continuar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });

        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    // Exibe carregando mas permite o envio normal do form
    Swal.fire({
        title: 'Gerando orçamento...',
        html: 'Por favor, aguarde!',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    // ⚠️ NÃO usar e.preventDefault() aqui — o form será enviado normalmente
});
</script>

<script>
document.getElementById("lanc_valor").addEventListener("input", function() {
    // Remove espaços em branco e formata o valor para ter apenas números e até duas casas decimais
    this.value = this.value.replace(/\s/g, "").replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");
    
    // Verifica se o valor possui mais de duas casas decimais e, se sim, limita-o a duas casas decimais
    if (this.value.split(".")[1] && this.value.split(".")[1].length > 2) {
        this.value = parseFloat(this.value).toFixed(2);
    }
});

function atualizaValorServico(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const valor = selectedOption.getAttribute('data-valor');

    const parentRow = selectElement.closest('.form-row');
    const inputValor = parentRow.querySelector('.lanc-valor');

    if (inputValor && valor) {
        inputValor.value = valor;
    }
}
</script>
</body>
</html>