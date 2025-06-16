<?php
session_start();
require('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];

    $query = $conexao->prepare("DELETE FROM receituarios WHERE id = :id AND doc_email = :email AND token_emp = :token_emp");
    $query->execute([
        'id' => $id,
        'email' => $email,
        'token_emp' => $_SESSION['token_emp']
    ]);

    echo "<script>
        alert('Receita Excluida com Sucesso');
        window.location.replace('cadastro.php?email=$email&id_job=Receituario');
    </script>";
}
