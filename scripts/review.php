<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Debug - Reviews</title>
    <style>body { font-family: Arial; padding: 20px; }</style>
</head>
<body>
    <h2 style="color: #2196F3;">[DEBUG] Script reviews.php executado!</h2>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h3>Dados da Review recebidos:</h3>";
        echo "<ul>";
        echo "<li><strong>Autor:</strong> " . htmlspecialchars($_POST['autor_nome'] ?? 'N/A') . "</li>";
        echo "<li><strong>Quarto Avaliado:</strong> " . htmlspecialchars($_POST['hotel_quarto'] ?? 'N/A') . "</li>";
        echo "<li><strong>Comentário:</strong> " . htmlspecialchars($_POST['comentario_texto'] ?? 'N/A') . "</li>";
        echo "</ul>";
        echo "<p><em>* A preparar ligação futura com a tabela 'reviews' na Base de Dados.</em></p>";
    } else {
        echo "<p style='color: red;'>Aviso: Acedeu diretamente ao script sem enviar dados.</p>";
    }
    ?>
    <br><a href="catalogo.php">Ir para o Catálogo</a>
</body>
</html>