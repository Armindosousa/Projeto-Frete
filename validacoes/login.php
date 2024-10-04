<?php
session_start();
require 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar a consulta para selecionar o usuário com base no email
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se encontrou o usuário
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario; // Armazena os dados do usuário na sessão
            
            // Redirecionamento conforme o tipo de usuário (motorista ou passageiro)
            if ($usuario['tipo'] === 'motorista') {
                header("Location: painel-motorista.php"); // Redireciona o motorista para a página de gerenciamento
                exit;
            } elseif ($usuario['tipo'] === 'passageiro') {
                header("Location: ../paginas/painel_passageiro.html"); // Redireciona o passageiro para a página de agendamento
                exit;
            } else {
                echo "Tipo de usuário desconhecido.";
            }
        } else {
            echo "Senha inválida!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $stmt->close(); // Fecha o statement
}

$conexao->close(); // Fecha a conexão com o banco de dados
?>
