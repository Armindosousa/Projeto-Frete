<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frete_id = $_POST['frete_id'];
    $motivo = $_POST['motivo'];

    // Inserir o cancelamento
    $sql = "INSERT INTO cancelamentos (frete_id, motivo) VALUES ('$frete_id', '$motivo')";
    if (mysqli_query($conexao, $sql)) {
        // Atualizar o status do frete para 'cancelado'
        $update_frete = "UPDATE fretes SET status = 'cancelado' WHERE id = '$frete_id'";
        mysqli_query($conexao, $update_frete);
        header("Location: ../paginas/login.html");
        exit;
    } else {
        echo "Erro ao cancelar o frete: " . mysqli_error($conexao);
    }
}
?>
