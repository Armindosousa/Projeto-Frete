<?php
session_start();
require 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../paginas/login.html");
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

// Escapando entradas para evitar SQL Injection
$usuario_id = mysqli_real_escape_string($conexao, $usuario_id);

// Exclui o usuário
$sql = "DELETE FROM usuarios WHERE id = '$usuario_id'";
if (mysqli_query($conexao, $sql)) {
    session_destroy();
    header("Location: ../paginas/login.html?success=delete");
    exit;
} else {
    echo "Erro ao excluir o usuário: " . mysqli_error($conexao);
}

mysqli_close($conexao); // Fecha a conexão com o banco de dados
?>
