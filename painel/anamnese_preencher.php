<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$modelo_id = $_GET['modelo_id'] ?? 0;
$paciente_id = $_GET['paciente_id'] ?? 0;

$stmt = $conexao->prepare("SELECT titulo FROM modelos_anamnese WHERE id = ?");
$stmt->execute([$modelo_id]);
$modelo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT * FROM perguntas_modelo WHERE modelo_id = ? ORDER BY ordem ASC");
$stmt->execute([$modelo_id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conexao->prepare("SELECT pergunta_id, resposta FROM respostas_anamnese WHERE modelo_id = ? AND paciente_id = ?");
$stmt->execute([$modelo_id, $paciente_id]);
$respostas_salvas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt = $conexao->prepare("SELECT nome, email FROM painel_users WHERE id = ?");
$stmt->execute([$paciente_id]);
$painel = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Ficha de Anamnese <?php echo htmlspecialchars($modelo['titulo']); ?> - <?php echo htmlspecialchars($painel['nome']); ?></title>
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

<form class="form" action="anamnese_salvar.php" method="POST">
  <div class="card">
    <div class="card-top">
      <h2><?php echo htmlspecialchars($modelo['titulo']); ?> - <?php echo htmlspecialchars($painel['nome']); ?></h2>
    </div>

    <input type="hidden" name="modelo_id" value="<?php echo $modelo_id; ?>">
    <input type="hidden" name="paciente_id" value="<?php echo $paciente_id; ?>">

    <?php foreach ($perguntas as $p): 
      $resposta_salva = $respostas_salvas[$p['id']] ?? '';
    ?>
      <div class="card-group">
        <label><?php echo htmlspecialchars($p['pergunta']); ?></label>

        <?php if ($p['tipo'] === 'text' || $p['tipo'] === 'number'): ?>
          <input type="<?php echo $p['tipo']; ?>" name="respostas[<?php echo $p['id']; ?>]" value="<?php echo htmlspecialchars($resposta_salva); ?>" required>

        <?php elseif ($p['tipo'] === 'radio'): ?>
          <div class="opcoes-horizontal">
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op); ?>
              <label>
                <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="<?php echo htmlspecialchars($opTrim); ?>" <?php if ($resposta_salva === $opTrim) echo 'checked'; ?> required>
                <?php echo htmlspecialchars($opTrim); ?>
              </label>
            <?php endforeach; ?>
          </div>

        <?php elseif ($p['tipo'] === 'select'): ?>
          <select name="respostas[<?php echo $p['id']; ?>][]" multiple required>
            <?php $respostas_array = explode(';', $resposta_salva); ?>
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op); ?>
              <option value="<?php echo htmlspecialchars($opTrim); ?>" <?php if (in_array($opTrim, $respostas_array)) echo 'selected'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
              </option>
            <?php endforeach; ?>
          </select>

        <?php elseif ($p['tipo'] === 'checkbox'): ?>
          <div class="opcoes-horizontal">
            <?php $respostas_array = explode(';', $resposta_salva); ?>
            <?php foreach (explode(';', $p['opcoes']) as $op): 
              $opTrim = trim($op); ?>
              <label>
                <input type="checkbox" name="respostas[<?php echo $p['id']; ?>][]" value="<?php echo htmlspecialchars($opTrim); ?>" <?php if (in_array($opTrim, $respostas_array)) echo 'checked'; ?>>
                <?php echo htmlspecialchars($opTrim); ?>
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
      <button type="submit">Salvar Respostas</button>
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
</script>

</body>
</html>
