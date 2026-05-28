<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Debug - Promoções</title>
    <style>body { font-family: Arial; padding: 20px; }</style>
</head>
<body>
    <h2 style="color: #9C27B0;">[DEBUG] Script promocoes.php executado!</h2>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h3>Dados da Campanha de Marketing:</h3>";
        echo "<ul>";
        echo "<li><strong>Nome da Campanha:</strong> " . htmlspecialchars($_POST['nome_campanha'] ?? 'N/A') . "</li>";
        echo "<li><strong>Código do Cupão:</strong> " . htmlspecialchars($_POST['codigo_cupao'] ?? 'N/A') . "</li>";
        echo "<li><strong>Desconto (%):</strong> " . htmlspecialchars($_POST['percentagem_desconto'] ?? 'N/A') . " %</li>";
        echo "<li><strong>Validade:</strong> " . htmlspecialchars($_POST['data_validade'] ?? 'N/A') . "</li>";
        echo "</ul>";
        echo "<p><em>* Dados validados e prontos para inserção na tabela 'promocoes'.</em></p>";
    } else {
        echo "<p style='color: red;'>Aviso: O formulário de promoções não foi submetido.</p>";
    }
    ?>
</body>
</html>