<?php
// Inicia a sessão
session_start();
require '../php/conexao.php'; // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.html");
    exit;
}

// Pega o ID do usuário logado (pode ser motorista ou passageiro)
$usuario_id = $_SESSION['usuario']['id'];

// Busca os fretes realizados no banco de dados
$sql = "SELECT * FROM fretes WHERE usuario_id = '$usuario_id' OR motorista_id = '$usuario_id'";
$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Viagens - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/style.css">
</head>
<body>
    <main>
        <h2>Relatório de Viagens</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Destino</th>
                    <th>Distância (km)</th>
                    <th>Valor (R$)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    // Exibe os dados do relatório
                    while ($frete = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $frete['data_viagem'] . "</td>";
                        echo "<td>" . $frete['destino'] . "</td>";
                        echo "<td>" . $frete['distancia'] . "</td>";
                        echo "<td>R$ " . $frete['valor'] . "</td>";
                        echo "<td>" . $frete['status'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum frete encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
