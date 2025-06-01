<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$paciente_id = $_GET['paciente_id'] ?? 0;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Despesas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

    <div class="container">
        <h2>Selecione a Anamese</h2>

        <table>
            <thead>
                <tr>
                    <th>Data Criado</th>
                    <th>Nome</th>
                    <th>Preencher</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM modelos_anamnese WHERE id >= :id ORDER BY id DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $criado_em = date('d/m/Y', strtotime($select['criado_em']));
                    $titulo = $select['titulo'];
                    $id = $select['id'];
                ?>
                <tr>
                    <td data-label="Data Criado"><?php echo $criado_em; ?></td>
                    <td data-label="Nome"><?php echo $titulo; ?></td>
                    <td data-label="Preencher"><a href="javascript:void(0)" onclick='window.open("anamnese_preencher.php?paciente_id=<?php echo $paciente_id ?>&modelo_id=<?php echo $id ?>","iframe-home")' class="btn-black">Preencher</td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
