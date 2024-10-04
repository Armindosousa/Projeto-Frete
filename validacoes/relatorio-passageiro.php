<?php
session_start();

// Verifica se o usuário está logado e se é um passageiro
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'passageiro') {
    header("Location: ../paginas/login.html");
    exit;
}

require 'conexao.php'; // Verifique se o caminho do arquivo de conexão está correto

// Busca a quantidade de viagens feitas e canceladas
$query_viagens = "SELECT 
                    SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) AS feitas,
                    SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) AS canceladas
                  FROM fretes
                  WHERE usuario_id = '{$_SESSION['usuario']['id']}'";

$result_viagens = mysqli_query($conexao, $query_viagens);

// Verifica se a consulta foi executada corretamente
if ($result_viagens) {
    $relatorio_viagens = mysqli_fetch_assoc($result_viagens);
} else {
    // Exibe o erro de SQL para fins de depuração
    echo "Erro ao executar a consulta: " . mysqli_error($conexao);
    exit;
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório do Passageiro - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/relatorio-passageiro.css">
</head>
<body>
    <main>
        <h2>Relatório do Passageiro</h2>

        <section>
            <h3>Resumo das Viagens</h3>
            <p><strong>Viagens Feitas:</strong> <?php echo isset($relatorio_viagens['feitas']) ? $relatorio_viagens['feitas'] : 0; ?></p>
            <p><strong>Viagens Canceladas:</strong> <?php echo isset($relatorio_viagens['canceladas']) ? $relatorio_viagens['canceladas'] : 0; ?></p>
        </section>

        <a href="../paginas/painel_passageiro.html">Voltar ao Painel do Passageiro</a>
    </main>
</body>
</html>
