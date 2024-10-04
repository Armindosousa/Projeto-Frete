<?php
session_start();

// Verifica se o usuário está logado e é motorista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'motorista') {
    header("Location: ../paginas/login.html");
    exit;
}

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motorista_id = $_SESSION['usuario']['id']; // ID do motorista
    $agendamento_id = mysqli_real_escape_string($conexao, $_POST['agendamento_id']);
    $acao = mysqli_real_escape_string($conexao, $_POST['acao']);

    if ($acao === 'aceitar') {
        $sql = "UPDATE fretes SET status = 'concluido' WHERE id = '$agendamento_id' AND motorista_id = '$motorista_id'";
    } elseif ($acao === 'recusar') {
        $sql = "UPDATE fretes SET status = 'cancelado' WHERE id = '$agendamento_id' AND motorista_id = '$motorista_id'";
    } else {
        die("Ação inválida.");
    }

    if (mysqli_query($conexao, $sql)) {
        header("Location: painel-motorista.php?status=sucesso");
        exit;
    } else {
        echo "Erro ao atualizar o agendamento: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
}
?>
