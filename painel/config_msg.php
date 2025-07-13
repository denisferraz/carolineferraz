<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
?>

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

        .btn-sm {
            padding: var(--space-2) var(--space-3);
            font-size: var(--font-size-xs);
        }
    </style>

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

</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-database-gear"></i>
                Editar Configurações de Mensagens <i class="bi bi-question-square-fill"onclick="ajudaConfigMsg()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar esta configuração
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">
    <div class="form-row">
                <div class="health-form-group">


<div data-step="2" style="margin-bottom: 10px;">
    <b>Utilize as Variaveis abaixo conforme necessidade:</b><br><br>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{NOME}')">{NOME}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{TELEFONE}')">{TELEFONE}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{EMAIL}')">{EMAIL}</button><br><br>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{DATA}')">{DATA}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{HORA}')">{HORA}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{SALA}')">{SALA}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{LOCAL}')">{LOCAL}</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('{TIPO}')">{TIPO}</button>
<br><br>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('😊')">😊</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🤍')">🤍</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🎉')">🎉</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('✅')">✅</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🔔')">🔔</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('📅')">📅</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('📍')">📍</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🗺️')">🗺️</button>
    <button class="health-btn health-btn-outline btn-sm" type="button" onclick="inserirVariavel('🚩')">🚩</button>
</div>
<br>

    <label class="health-label">Mensagem Confirmação</label>
    <textarea class="health-input" data-step="3" class="textarea-custom" name="config_msg_confirmacao" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_msg_confirmacao)); ?></textarea><br><br>
    <label class="health-label">Mensagem Cancelamento</label>
    <textarea class="health-input" data-step="4" class="textarea-custom" name="config_msg_cancelamento" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_msg_cancelamento)); ?></textarea><br><br>
    <label class="health-label">Mensagem Finalização</label>
    <textarea class="health-input" data-step="5" class="textarea-custom" name="config_msg_finalizar" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_msg_finalizar)); ?></textarea><br><br>
    <label class="health-label">Mensagem Lembrete</label>
    <textarea class="health-input" data-step="6" class="textarea-custom" name="config_msg_lembrete" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_msg_lembrete)); ?></textarea><br><br>
    <label class="health-label">Mensagem Aniversario</label>
    <textarea class="health-input" data-step="7" class="textarea-custom" name="config_msg_aniversario" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $config_msg_aniversario)); ?></textarea><br><br>
    <br>
    <div data-step="8">
    <label class="health-label"><b>Formas de Comunicação:</b></label>
    <label class="health-label">Whatsapp <b>(<?php echo $envio_whatsapp ?>)</b>
        <select class="health-select" name="envio_whatsapp">
    <?php foreach (['ativado', 'desativado'] as $option) {
        $selected = ($option == $envio_whatsapp) ? 'selected' : '';
        echo "<option value='$option' $selected>$option</option>";
    } ?>
        </select></label>
    <label class="health-label">E-mail <b>(<?php echo $envio_email ?>)</b>
        <select class="health-select" name="envio_email">
    <?php foreach (['ativado', 'desativado'] as $option) {
        $selected = ($option == $envio_email) ? 'selected' : '';
        echo "<option value='$option' $selected>$option</option>";
    } ?>
        </select></label>
    </div><br><br>
    <label class="health-label"><b>Configuração Envio Lembretes Automatico</b></label>
    <br><label class="health-label">Hora do Envio</label>
    <select class="health-select" data-step="9" name="lembrete_hora" required>
    <?php
    for ($h = 0; $h < 24; $h++) {
        $hora_formatada = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        $selecionado = ($hora_formatada == date('H:i', strtotime($lembrete_auto_time))) ? 'selected' : '';
        echo "<option value='$hora_formatada' $selecionado>$hora_formatada</option>";
    }
    ?>
    </select>
    <br><br>
    <div data-step="10">
    <label class="health-label">Dias da Semana</label><br>
    <input id="is_segunda" type="checkbox" name="is_segunda" <?php if($is_segunda == 1){?>checked<?php } ?>>
    <label for="is_segunda">Segunda-Feira</label>
    <br>
    <input id="is_terca" type="checkbox" name="is_terca" <?php if($is_terca == 1){?>checked<?php } ?>>
    <label for="is_terca">Terça-Feira</label>
    <br>
    <input id="is_quarta" type="checkbox" name="is_quarta" <?php if($is_quarta == 1){?>checked<?php } ?>>
    <label for="is_quarta">Quarta-Feira</label>
    <br>
    <input id="is_quinta" type="checkbox" name="is_quinta" <?php if($is_quinta == 1){?>checked<?php } ?>>
    <label for="is_quinta">Quinta-Feira</label>
    <br>
    <input id="is_sexta" type="checkbox" name="is_sexta" <?php if($is_sexta == 1){?>checked<?php } ?>>
    <label for="is_sexta">Sexta-Feira</label>
    <br>
    <input id="is_sabado" type="checkbox" name="is_sabado" <?php if($is_sabado == 1){?>checked<?php } ?>>
    <label for="is_sabado">Sabado</label>
    <br>
    <input id="is_domingo" type="checkbox" name="is_domingo" <?php if($is_domingo == 1){?>checked<?php } ?>>
    <label for="is_domingo">Domingo</label>
    </div>
    <br><br>
    <input type="hidden" name="id_job" value="editar_configuracoes_msg">
    <div data-step="11"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Atualizar Dados</button></div>
</div>
</div>
</form>
</body>
</html>