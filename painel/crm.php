<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href="<?php echo $css_path ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="appointment-list">
        <?php
        // Busca os atendimentos para o dia selecionado
        $query = $conexao->prepare("SELECT * FROM mensagens WHERE  token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY data_recebida DESC");
        $query->execute(array('id' => 0));
        $msg_qtd = $query->rowcount();
        ?>

<fieldset>
    <?php
    if ($msg_qtd == 0) {
        echo "<legend>Sem Mensagens Recebidas</legend>";
    } else {
        echo "<legend>Mensagens Recebidas [ {$msg_qtd} ]</legend>";
    ?>
    
    <table style="width: 100%; border-collapse: collapse; color: #eee;">
        <thead>
            <tr>
                <th style="padding: 10px; border: 1px solid #333;">Nome</th>
                <th style="padding: 10px; border: 1px solid #333;">Mensagem</th>
                <th style="padding: 10px; border: 1px solid #333;">Data</th>
                <th style="padding: 10px; border: 1px solid #333;">Telefone</th>
                <th style="padding: 10px; border: 1px solid #333;">Enviar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $nome = $row['nome'];
                $numero = $row['numero'];
                $data_recebida = date('d/m/Y', strtotime($row['data_recebida']));
                $mensagem = substr($row['mensagem'], 0, 40) . '...';
                $id = $row['id'];
                ?>
                <tr>
                <td style="padding: 10px; border: 1px solid #333;"><?php echo $nome; ?></td>
                <td style="padding: 10px; border: 1px solid #333;"><?php echo $mensagem; ?></td>
                <td style="padding: 10px; border: 1px solid #333;"><?php echo $data_recebida; ?></td>
                <td style="padding: 10px; border: 1px solid #333;">
                    <a class="whatsapp-link" href="https://wa.me/55<?php echo $numero; ?>" target="_blank">
                        <i class="fab fa-whatsapp"></i><?php echo $numero; ?>
                    </a>
                </td>
                <td style="padding: 10px; border: 1px solid #333; text-align: center;">
                    <input type="hidden" class="id_job" value="enviar_crm">
                    <input type="checkbox" class="check-enviar" data-id="<?php echo $id; ?>">
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php } ?>
</fieldset>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.check-enviar').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                const id = checkbox.dataset.id;

                // Pega o input hidden que está no mesmo <td> pai
                const id_job = checkbox.previousElementSibling.value;

                fetch('acao.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(id) + '&id_job=' + encodeURIComponent(id_job)
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Resposta:', data);
                    // Aqui você pode exibir uma notificação se quiser
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            }
        });
    });
});
</script>
</body>
</html>
