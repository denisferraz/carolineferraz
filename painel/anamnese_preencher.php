<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

$modelo_id = $_GET['modelo_id'] ?? 0;
$paciente_id = $_GET['paciente_id'] ?? 0;

$stmt = $conexao->prepare("SELECT titulo FROM modelos_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND id = ?");
$stmt->execute([$modelo_id]);
$modelo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT * FROM perguntas_modelo WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ? ORDER BY ordem ASC");
$stmt->execute([$modelo_id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT pergunta_id, resposta FROM respostas_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ? AND paciente_id = ?");
$stmt->execute([$modelo_id, $paciente_id]);
$respostas_salvas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt_painel = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND id = :id");
$stmt_painel->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'id' => $paciente_id));
$painel = $stmt_painel->fetch(PDO::FETCH_ASSOC);

// Para descriptografar os dados
$dados_painel_users = $painel['dados_painel_users'];
$dados = base64_decode($dados_painel_users);
$dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
$dados_array = explode(';', $dados_decifrados);
$nome = $dados_array[0];
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
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-clipboard2-heart"></i>
                <?php echo htmlspecialchars($modelo['titulo']); ?> - <?php echo htmlspecialchars($nome); ?> <i class="bi bi-question-square-fill"onclick="ajudaAnamnesePreencher()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para salvar estas respostas deste modelo
        </div>
    </div>
    
<form class="form" action="anamnese_salvar.php" method="POST">

<div class="form-row">
    <div class="health-form-group">

    <input type="hidden" name="modelo_id" value="<?php echo $modelo_id; ?>">
    <input type="hidden" name="paciente_id" value="<?php echo $paciente_id; ?>">

    <?php foreach ($perguntas as $p): 
      $resposta_salva = $respostas_salvas[$p['id']] ?? '';
    ?>
        <label class="health-label" data-step="2"><b><?php echo htmlspecialchars($p['pergunta']); ?></b></label>

        <?php if ($p['tipo'] === 'text' || $p['tipo'] === 'number'): ?>
          <input class="health-input" data-step="3" type="<?php echo $p['tipo']; ?>" name="respostas[<?php echo $p['id']; ?>]" value="<?php echo htmlspecialchars($resposta_salva); ?>">

          <?php elseif ($p['tipo'] === 'radio'): ?>
          <div data-step="3" class="opcoes-horizontal">
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op);
              $imgPath = "../imagens/{$_SESSION['token_emp']}/{$p['id']}_".strtolower(preg_replace('/\s+/', '_', $opTrim)).".png";
            ?>
              <label class="health-label">
                <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="<?php echo htmlspecialchars($opTrim); ?>" <?php if ($resposta_salva === $opTrim) echo 'checked'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
                <?php if (file_exists($imgPath)): ?>
                  <br>
                  <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($opTrim); ?>" style="max-width:100px; height:auto; margin-top:5px;">
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
          </div>

        <?php elseif ($p['tipo'] === 'select'): ?>
          <select class="health-select" data-step="3" name="respostas[<?php echo $p['id']; ?>][]" multiple>
            <?php $respostas_array = explode(';', $resposta_salva); ?>
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op); ?>
              <option value="<?php echo htmlspecialchars($opTrim); ?>" <?php if (in_array($opTrim, $respostas_array)) echo 'selected'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
              </option>
            <?php endforeach; ?>
          </select>

        <?php elseif ($p['tipo'] === 'checkbox'): ?>
          <div data-step="3" class="opcoes-horizontal">
            <?php $respostas_array = explode(';', $resposta_salva); ?>
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op); ?>
              <?php
                $imgPath = "../imagens/{$_SESSION['token_emp']}/{$p['id']}_".strtolower(preg_replace('/\s+/', '_', $opTrim)).".png";
              ?>
              <label class="health-label">
                <input type="checkbox" name="respostas[<?php echo $p['id']; ?>][]" value="<?php echo htmlspecialchars($opTrim); ?>" <?php if (in_array($opTrim, $respostas_array)) echo 'checked'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
                <?php if (file_exists($imgPath)): ?>
                  <br>
                  <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($opTrim); ?>" style="max-width:100px; height:auto; margin-top:5px;">
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
          </div>

          <?php elseif ($p['tipo'] === 'textarea'): ?>
            <textarea class="health-input" data-step="3" rows="4" name="respostas[<?php echo $p['id']; ?>]"><?php echo htmlspecialchars($resposta_salva); ?></textarea>
            
        <?php endif; ?><br><br>
    <?php endforeach; ?>
    </div>
    </div>

    <?php if (isset($_GET['salvo']) && $_GET['salvo'] == 1): ?>
    <script>
        Swal.fire({
        icon: 'success',
        title: 'Ficha salva!',
        text: 'As respostas foram registradas com sucesso.',
        confirmButtonColor: '#4caf50'
        });
    </script>
    <?php endif; ?>

      <button data-step="4" class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Salvar Respostas</button>
  </div>
</form>

<script>
document.querySelectorAll('.opcoes-horizontal input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const label = this.closest('label');
        if (this.checked) {
            label.classList.add('checked-black');
        } else {
            label.classList.remove('checked-black');
        }
    });

    // Aplica a classe já marcada no carregamento
    if (checkbox.checked) {
        checkbox.closest('label').classList.add('checked-black');
    }
});

document.querySelectorAll('.opcoes-horizontal input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const name = this.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
            r.closest('label').classList.remove('checked-black');
        });
        if (this.checked) {
            this.closest('label').classList.add('checked-black');
        }
    });

    if (radio.checked) {
        radio.closest('label').classList.add('checked-black');
    }
});
</script>

</body>
</html>
