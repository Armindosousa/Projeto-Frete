<?php
session_start();

// Verifica se o usuário está logado e é motorista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'motorista') {
    header("Location: login.html");
    exit;
}

require 'conexao.php';

// Buscar informações do motorista para exibição
$motorista_id = $_SESSION['usuario']['id'];
$query = "SELECT nome, email, senha FROM usuarios WHERE id = '$motorista_id'";
$result = mysqli_query($conexao, $query);
$motorista = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizar'])) {
        // Atualizar dados do motorista
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
        $email = mysqli_real_escape_string($conexao, $_POST['email']);
        $senha_atualizada = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : $motorista['senha'];

        $update_query = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha_atualizada' WHERE id = '$motorista_id'";

        if (mysqli_query($conexao, $update_query)) {
            echo "Conta atualizada com sucesso!";
            $_SESSION['usuario']['nome'] = $nome;  // Atualiza o nome na sessão também
        } else {
            echo "Erro ao atualizar a conta: " . mysqli_error($conexao);
        }
    } elseif (isset($_POST['excluir'])) {
        // Excluir conta do motorista
        $delete_query = "DELETE FROM usuarios WHERE id = '$motorista_id'";

        if (mysqli_query($conexao, $delete_query)) {
            session_destroy(); // Destroi a sessão do usuário
            header("Location: ../paginas/login.html");
            exit;
        } else {
            echo "Erro ao excluir a conta: " . mysqli_error($conexao);
        }
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Conta - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/gerenciar-motorista.css">
</head>
<body>
    <main>
        <header>
            <h1>Gerenciar Conta do Motorista</h1>
        </header>

        <section>
            <h2>Atualizar Conta</h2>
            <form method="POST" action="">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($motorista['nome']); ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($motorista['email']); ?>" required>
                
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua nova senha">
                
                <button type="submit" name="atualizar" class="action-button">Atualizar Conta</button>
            </form>
        </section>

        <section>
            <h2>Excluir Conta</h2>
            <p>Se deseja excluir sua conta, clique no botão abaixo. Esta ação é irreversível.</p>
            <form method="POST" action="">
                <button type="submit" name="excluir" class="action-button delete-button">Excluir Conta</button>
            </form>
        </section>
        
        <!-- Link para voltar ao painel do motorista -->
        <section>
            <a href="painel-motorista.php" class="back-link">Voltar ao Painel do Motorista</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Frete Magia. Todos os direitos reservados.</p>
        <p>&copy; 2024 Dinay - System. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
