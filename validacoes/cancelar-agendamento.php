<?php
session_start();

// Verifica se o usuário está logado e se é um passageiro
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'passageiro') {
    header("Location: ../paginas/login.html");
    exit;
}

require 'conexao.php';

// Verifica se o ID do agendamento foi passado
if (isset($_POST['agendamento_id'])) {
    $agendamento_id = mysqli_real_escape_string($conexao, $_POST['agendamento_id']);

    // Atualiza o status do agendamento para 'cancelado'
    $update_query = "UPDATE fretes SET status = 'cancelado' WHERE id = '$agendamento_id' AND usuario_id = '{$_SESSION['usuario']['id']}'";

    if (mysqli_query($conexao, $update_query)) {
        // Redireciona para o histórico de agendamentos com uma mensagem de sucesso
        header("Location: historico_agendamento.php?status=cancelado");
        exit;
    } else {
        // Se houver um erro, exibe uma mensagem de erro
        echo "Erro ao cancelar o agendamento: " . mysqli_error($conexao);
    }
} else {
    // Se o ID do agendamento não for passado, redireciona para o histórico de agendamentos
    header("Location: historico_agendamento.php");
    exit;
}

mysqli_close($conexao);
?>
