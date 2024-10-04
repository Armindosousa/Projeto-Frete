<?php
session_start();
require 'conexao.php'; // Corrija o caminho se necessário

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../paginas/login.html");
        exit;
    }

    $usuario_id = $_SESSION['usuario']['id'];  // ID do passageiro
    $motorista_id = mysqli_real_escape_string($conexao, $_POST['motorista_id']);
    $origem = mysqli_real_escape_string($conexao, $_POST['origem']);
    $destino = mysqli_real_escape_string($conexao, $_POST['destino']);
    $data_agendamento = mysqli_real_escape_string($conexao, $_POST['data_agendamento']);
    $preco = mysqli_real_escape_string($conexao, $_POST['preco']);

    // Query de inserção
    $sql = "INSERT INTO fretes (usuario_id, motorista_id, origem, destino, data_agendamento, preco)
            VALUES ('$usuario_id', '$motorista_id', '$origem', '$destino', '$data_agendamento', '$preco')";

    if (mysqli_query($conexao, $sql)) {
        header("Location: ../paginas/confirmacao.html"); // Redireciona para a página de confirmação
        exit;
    } else {
        echo "Erro ao agendar o frete: " . mysqli_error($conexao);
    }

    mysqli_close($conexao); // Fecha a conexão com o banco de dados
}
?>
