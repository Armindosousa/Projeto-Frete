<?php
session_start();

// Verifica se o usuário está logado e é motorista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'motorista') {
    header("Location: ../paginas/login.php");
    exit;
}

require 'conexao.php';

// Buscar informações do motorista para o relatório
$motorista_id = $_SESSION['usuario']['id'];

// Contar a quantidade de viagens feitas, recusadas e pendentes
$query_fretes = "
    SELECT 
        SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) AS viagens_feitas,
        SUM(CASE WHEN status = 'recusado' THEN 1 ELSE 0 END) AS viagens_recusadas
    FROM fretes
    WHERE motorista_id = '$motorista_id'
";
$result_fretes = mysqli_query($conexao, $query_fretes);
$relatorio = mysqli_fetch_assoc($result_fretes);

// Calcular a pontuação baseada na quantidade de viagens concluídas
$viagens_feitas = $relatorio['viagens_feitas'];
$viagens_recusadas = $relatorio['viagens_recusadas'];
$pontuacao = $viagens_feitas * 10 - $viagens_recusadas * 5;

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório do Motorista - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/relatorio-motorista.css">
</head>
<body>
    <main>
        <header>
            <h1>Relatório do Motorista</h1>
            <nav>
                <ul>
                    <li><a href="painel-motorista.php">Voltar</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <h2>Resumo de Viagens</h2>
            <table>
                <thead>
                    <tr>
                        <th>Quantidade de Viagens Feitas</th>
                        <th>Quantidade de Viagens Recusadas</th>
                        <th>Pontuação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($viagens_feitas); ?></td>
                        <td><?php echo htmlspecialchars($viagens_recusadas); ?></td>
                        <td><?php echo htmlspecialchars($pontuacao); ?></td>
                    </tr>
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
