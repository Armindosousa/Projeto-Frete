<?php
session_start();

// Verifica se o usuário está logado e se é um passageiro
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'passageiro') {
    header("Location: ../paginas/login.html");
    exit;
}

require '../validacoes/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Agendamentos - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/historico_agendamentos.css">
</head>
<body>
    <main>
        <h2>Histórico de Agendamentos</h2>

        <?php
        // Mensagem de sucesso
        if (isset($_GET['status']) && $_GET['status'] === 'cancelado') {
            echo "<p class='success-message'>Agendamento cancelado com sucesso!</p>";
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Data do Agendamento</th>
                    <th>Motorista</th>
                    <th>Preço</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $usuario_id = $_SESSION['usuario']['id'];  // ID do passageiro
                    $query = "SELECT f.id, f.origem, f.destino, f.data_agendamento, f.preco, u.nome AS motorista 
                              FROM fretes f 
                              JOIN usuarios u ON f.motorista_id = u.id 
                              WHERE f.usuario_id = '$usuario_id'";
                    $result = mysqli_query($conexao, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($agendamento = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$agendamento['origem']}</td>
                                    <td>{$agendamento['destino']}</td>
                                    <td>{$agendamento['data_agendamento']}</td>
                                    <td>{$agendamento['motorista']}</td>
                                    <td>R$ {$agendamento['preco']}</td>
                                    <td>
                                        <form method='POST' action='cancelar_agendamento.php'>
                                            <input type='hidden' name='agendamento_id' value='{$agendamento['id']}' />
                                            <button type='submit' class='cancel-button'>Cancelar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nenhum agendamento encontrado.</td></tr>";
                    }

                    mysqli_close($conexao);
                ?>
            </tbody>
        </table>
        <a href="../paginas/painel_passageiro.html">Voltar ao Painel do Passageiro</a>
    </main>
</body>
</html>
