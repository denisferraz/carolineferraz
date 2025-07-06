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

$stmt = $conexao->prepare("SELECT titulo FROM modelos_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND id = ?");
$stmt->execute([$modelo_id]);
$modelo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT * FROM perguntas_modelo_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ? ORDER BY ordem ASC");
$stmt->execute([$modelo_id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT pergunta_id, resposta FROM respostas_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = ? AND paciente_id = ?");
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
  <title>Ficha de Anamnese <?php echo htmlspecialchars($modelo['titulo']); ?> - <?php echo htmlspecialchars($nome); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link rel="stylesheet" href="<?php echo $css_path ?>">
  <style>
    .card {
        width: 100%;
        max-width: 500px;
    }

    .card-group {
        margin-bottom: 1rem;
    }

    .card-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .card-group input[type="text"],
    .card-group input[type="number"],
    .card-group select {
        width: 100%;
        padding: 0.5rem;
        border: none;
        border-radius: 5px;
    }

    .opcoes-horizontal {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.opcoes-horizontal label {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    cursor: pointer;
    white-space: nowrap;
    font-size: 0.95rem;
    width: auto; /* IMPORTANTE: não deixar ocupar 100% */
    transition: background-color 0.3s, color 0.3s;
}

.opcoes-horizontal input[type="radio"] {
    margin: 0;
}

    .btn {
        text-align: center;
        margin-top: 20px;
    }

    .btn button {
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
    }

  </style>
</head>
<body>

<form class="form" action="prontuario_salvar.php" method="POST">
  <div data-step="1" class="card">
    <div class="card-top">
      <h2><?php echo htmlspecialchars($modelo['titulo']); ?> - <?php echo htmlspecialchars($nome); ?> <i class="bi bi-question-square-fill"onclick="ajudaProntuarioPreencher()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
    </div>

    <input type="hidden" name="modelo_id" value="<?php echo $modelo_id; ?>">
    <input type="hidden" name="paciente_id" value="<?php echo $paciente_id; ?>">

    <?php foreach ($perguntas as $p): 
      $resposta_salva = $respostas_salvas[$p['id']] ?? '';
    ?>
      <div class="card-group">
        <label data-step="2"><?php echo htmlspecialchars($p['pergunta']); ?></label>

        <?php if ($p['tipo'] === 'text' || $p['tipo'] === 'number'): ?>
          <input data-step="3" type="<?php echo $p['tipo']; ?>" name="respostas[<?php echo $p['id']; ?>]" value="<?php echo htmlspecialchars($resposta_salva); ?>">

          <?php elseif ($p['tipo'] === 'radio'): ?>
          <div data-step="3" class="opcoes-horizontal">
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op);
              $imgPath = "../imagens/{$_SESSION['token_emp']}/{$p['id']}_".strtolower(preg_replace('/\s+/', '_', $opTrim)).".png";
            ?>
              <label>
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
          <select data-step="3" name="respostas[<?php echo $p['id']; ?>][]" multiple>
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
              <label>
                <input type="checkbox" name="respostas[<?php echo $p['id']; ?>][]" value="<?php echo htmlspecialchars($opTrim); ?>" <?php if (in_array($opTrim, $respostas_array)) echo 'checked'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
                <?php if (file_exists($imgPath)): ?>
                  <br>
                  <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlspecialchars($opTrim); ?>" style="max-width:100px; height:auto; margin-top:5px;">
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

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

    <div class="card-group btn">
      <button data-step="4" type="submit">Salvar Respostas</button>
    </div>
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
