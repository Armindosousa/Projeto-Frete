<?php
session_start();
require 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../paginas/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $usuario_id = $_SESSION['usuario']['id'];

    // Escapando entradas para evitar SQL Injection
    $nome = mysqli_real_escape_string($conn, $nome);
    $email = mysqli_real_escape_string($conn, $email);

    // Atualiza os dados do usuário
    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id = '$usuario_id'";
    if (mysqli_query($conn, $sql)) {
        // Atualiza os dados na sessão
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        header("Location: ../paginas/gerenciar_usuario.html?success=update");
        exit;
    } else {
        header("Location: ../paginas/gerenciar_usuario.html?error=update");
        exit;
    }
}
mysqli_close($conn); // Fecha a conexão com o banco de dados
?>
