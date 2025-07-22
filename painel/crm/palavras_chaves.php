<?php

session_start();
require('../../config/database.php');
require('../verifica_login.php');

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
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
            text-align: center;
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
</head>
<body>
<div class="section-content health-fade-in">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-gear"></i>
                Configurações
            </h1>
            <p class="health-card-subtitle">
                Cadastre as palavras chaves e mensagens de Followup
            </p>
        </div>
    </div>
    <form data-step="1" class="form" action="../acao.php" method="POST">
    <div class="form-row">
                <div class="health-form-group">


<div data-step="2" style="margin-bottom: 10px;">
    <b>Utilize as Variaveis abaixo conforme necessidade:</b><br><br>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{NOME}')">{NOME}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('😊')">😊</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🤍')">🤍</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🎉')">🎉</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('✅')">✅</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🔔')">🔔</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('📅')">📅</button>
</div>
<br>

    <label class="health-label">Mensagem Follow-up</label>
    <textarea class="health-input" data-step="3" class="textarea-custom" name="config_crm_followup" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_crm_followup)); ?></textarea><br>
    <input type="hidden" name="id_job" value="editar_configuracoes_crm">
    <div data-step="11"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Atualizar Mensagem Follow Up</button></div>
</div>
</div>
</form>
    <form data-step="1" class="form" action="../acao.php" method="POST">

    <div class="form-row">
                <div class="health-form-group">

                <label class="health-label">Palavra Chave *</label>
                <input class="health-input" data-step="2" type="text" name="palavra_chave" required>
                </div>
                <div class="health-form-group">
                <label class="health-label" for="etapa">Etapa *</label>
                <select class="health-select" name="etapa" required>
                <?php 
                foreach ($etapas as $etapa) {
                ?>
                <option value="<?php echo $etapa; ?>"><?php echo $etapa; ?></option>
                <?php } ?>
                </select>
                </div></div>

                <input type="hidden" name="id_job" value="palavras_chaves" />
                <div data-step="3"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Cadastrar Palavra Chave</button></div>
    </form>

<div class="table-responsive">
    <table data-step="4" class="data-table">
            <thead>
                <tr>
                    <th>Palavra Chave</th>
                    <th>Etapa Destino</th>
                    <th data-step="5">Editar</th>
                    <th data-step="6">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM regras_etapa WHERE token_emp = :token_emp AND id >= :id ORDER BY etapa_destino, palavra_chave ASC");
                $query->execute(['token_emp' => $_SESSION['token_emp'], 'id' => 1]);            

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $palavra_chave = $select['palavra_chave'];
                    $etapa_destino = $select['etapa_destino'];
                ?>
                <tr>
                    <td data-label="Palavra Chave"><?php echo $palavra_chave; ?></td>
                    <td data-label="Etapa Destino"><?php echo $etapa_destino; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("palavra_chave_acao.php?id_job=Editar&id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-primary btn-mini"><i class="bi bi-pencil"></i> Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("palavra_chave_acao.php?id_job=Excluir&id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i> Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table></div>
    </div>

    <script>
let textareaSelecionado = null;

// Quando o usuário focar em qualquer textarea, guardamos qual é
document.addEventListener('focusin', function(e) {
    if (e.target.tagName === 'TEXTAREA') {
        textareaSelecionado = e.target;
    }
});

function inserirVariavel(texto) {
    if (textareaSelecionado) {
        let start = textareaSelecionado.selectionStart;
        let end = textareaSelecionado.selectionEnd;
        let textoAtual = textareaSelecionado.value;

        // Insere o texto na posição atual do cursor
        textareaSelecionado.value = textoAtual.substring(0, start) + texto + textoAtual.substring(end);

        // Move o cursor para depois do texto inserido
        textareaSelecionado.selectionStart = textareaSelecionado.selectionEnd = start + texto.length;

        // Dá foco novamente
        textareaSelecionado.focus();
    } else {
        alert('Clique primeiro no campo de texto onde deseja inserir a variável.');
    }
}
</script>
</body>
</html>
