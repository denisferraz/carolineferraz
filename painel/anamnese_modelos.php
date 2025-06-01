<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Despesas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="container">
        <h2>Minhas Anamneses</h2>

        <table>
            <thead>
                <tr>
                    <th>Data Criado</th>
                    <th>Nome</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM modelos_anamnese WHERE id >= :id ORDER BY id DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $criado_em = date('d/m/Y', strtotime($select['criado_em']));
                    $titulo = $select['titulo'];
                    $id_modelo = $select['id'];
                ?>
                <tr>
                    <td data-label="Data Criado"><?php echo $criado_em; ?></td>
                    <td data-label="Nome"><?php echo $titulo; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("anamnese_criar_modelo.php?id_modelo=<?php echo $id_modelo ?>","iframe-home")' class="btn-black">Editar</td>
                    <td data-label="Excluir">
                    <a href="javascript:void(0)" onclick="confirmarExclusao(<?php echo $id_modelo ?>)" class="btn-red">Excluir</a>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<script>
  function confirmarExclusao(id) {
    Swal.fire({
      title: 'Tem certeza?',
      text: "Esta ação tambem ira apagar as anameses preenchidas com este modelo de todos os paciente! Você não poderá desfazer isso depois!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sim, excluir!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        window.open("anamnese_excluir.php?id=" + id, "iframe-home");
      }
    });
  }
</script>


</body>
</html>
