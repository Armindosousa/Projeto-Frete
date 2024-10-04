<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamento de Frete - Frete Magia</title>
    <link rel="stylesheet" href="../acoes/agendamento.css">
</head>
<body>
    <main>
        <h2>Agendamento de Frete</h2>
        <!--<a href="../validacoes/logout.php">Saxir X</a>-->
        <form method="POST" action="agendamento_processar.php">
            <label for="origem">Origem:</label>
            <input type="text" name="origem" id="origem" placeholder="Florianopolis" required>
            <br>

            <label for="destino">Destino:</label>
            <input type="text" name="destino" id="destino" placeholder="Palhoça" required>
            <br>

            <label for="data_agendamento">Data do Agendamento:</label>
            <input type="date" name="data_agendamento" id="data_agendamento" required>
            <br>

            <label for="motorista_id">Escolha o Motorista:</label>
            <select name="motorista_id" id="motorista_id" required>
                <?php
                    require '../validacoes/conexao.php'; 

                    // Habilita a exibição de erros para depuração
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    error_reporting(E_ALL);

                    // Consulta para obter motoristas
                    $result = mysqli_query($conexao, "SELECT id, nome FROM usuarios WHERE tipo = 'motorista'");
                    
                    if (!$result) {
                        die("Erro na consulta: " . mysqli_error($conexao)); // Exibe erro se a consulta falhar
                    }
                    
                    // Preenche o select com motoristas
                    while ($motorista = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$motorista['id']}'>{$motorista['nome']}</option>";
                    }

                    mysqli_close($conexao); // Fecha a conexão com o banco de dados
                ?>
            </select>
            <br>

            <label for="preco">Preço Estimado (R$):</label>
            <input type="number" name="preco" id="preco" step="0.01" required>
            <br>

            <button type="submit">Agendar Frete</button>
        </form>
    </main>
</body>
</html>
