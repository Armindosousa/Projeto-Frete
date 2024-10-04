<?php
require 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha
    $tipo = $_POST['tipo']; // Tipo do usuário (motorista ou passageiro)

    // Verifica se o email já existe no banco de dados
    $verifica_email = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($verifica_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "O email já está cadastrado!";
    } else {
        // Preparando a query para inserir no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

        // Executando a inserção
        if ($stmt->execute()) {
            // Redireciona para a página de login após o cadastro
            header("Location: ../paginas/login.html");
            exit;
        } else {
            echo "Erro ao cadastrar!";
        }
    }

    $stmt->close(); // Fecha o statement
}

$conexao->close(); // Fecha a conexão com o banco de dados
?>
