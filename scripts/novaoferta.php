<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Debug - Nova Oferta</title>
    <style>body { font-family: Arial; padding: 20px; }</style>
</head>
<body>
    <h2 style="color: #FF9800;">[DEBUG] Script novaoferta.php executado!</h2>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h3>Dados da Oferta (Administração):</h3>";
        echo "<ul>";
        echo "<li><strong>Título da Oferta:</strong> " . htmlspecialchars($_POST['titulo_oferta'] ?? 'N/A') . "</li>";
        echo "<li><strong>Tipo de Quarto:</strong> " . htmlspecialchars($_POST['tipo_quarto'] ?? 'N/A') . "</li>";
        echo "<li><strong>Preço por Noite:</strong> " . htmlspecialchars($_POST['preco_noite'] ?? 'N/A') . " €</li>";
        echo "<li><strong>Descrição:</strong> " . htmlspecialchars($_POST['descricao_oferta'] ?? 'N/A') . "</li>";
        echo "</ul>";
        echo "<p><em>* Teste de debug concluído para a tabela 'ofertas'.</em></p>";
    } else {
        echo "<p style='color: red;'>Aviso: Nenhuma oferta submetida. Teste via linha de comandos detetado.</p>";
    }
    ?>
</body>
</html>
