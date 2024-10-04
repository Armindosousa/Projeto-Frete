<?php
session_start();

// Verifica se o usuário está logado e é motorista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'motorista') {
    header("Location: ../paginas/login.php");
    exit;
}

require 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Motorista - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/painel-motorista.css">
</head>
<body>
    <main>
        <header>
            <h1>Painel do Motorista</h1>
            <nav>
                <ul>
                    <li><a href="relatorio-motorista.php">Ver Relatório</a></li>
                    <li><a href="gerenciar-motorista.php">Gerenciar Conta</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>!</h2>

            <?php
            if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
                echo "<p class='success-message'>Agendamento atualizado com sucesso!</p>";
            }
            ?>

            <h3>Agendamentos Pendentes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data do Agendamento</th>
                        <th>Passageiro</th>
                        <th>Preço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Buscar todos os fretes com status 'agendado'
                        $motorista_id = $_SESSION['usuario']['id'];  // ID do motorista
                        $query = "SELECT f.id, f.origem, f.destino, f.data_agendamento, f.preco, u.nome AS passageiro 
                                  FROM fretes f 
                                  JOIN usuarios u ON f.usuario_id = u.id 
                                  WHERE f.motorista_id = '$motorista_id' AND f.status = 'agendado'";
                        $result = mysqli_query($conexao, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($agendamento = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>{$agendamento['origem']}</td>
                                        <td>{$agendamento['destino']}</td>
                                        <td>{$agendamento['data_agendamento']}</td>
                                        <td>{$agendamento['passageiro']}</td>
                                        <td>R$ {$agendamento['preco']}</td>
                                        <td>
                                            <form method='POST' action='../validacoes/atualizar-agendamento.php' style='display: inline;'>
                                                <input type='hidden' name='agendamento_id' value='{$agendamento['id']}'>
                                                <button type='submit' name='acao' value='aceitar' class='action-button accept-button'>Aceitar</button>
                                                <button type='submit' name='acao' value='recusar' class='action-button reject-button'>Recusar</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nenhum agendamento pendente.</td></tr>";
                        }

                        mysqli_close($conexao);
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Frete Magia. Todos os direitos reservados.</p>
        <p><a href="#">Contato</a> | <a href="ajuda.html">Ajuda</a> | <a href="politica_privacidade.html">Política de Privacidade</a></p>
    </footer>

</body>
</html>
