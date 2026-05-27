<?php
// novoregisto.php - Recebe a informação do novo utilizador (USR1)

// Forçar o browser a não guardar esta página em cache para evitar erros de submissão antigos
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados enviados pelo formulário HTML através do atributo 'name'
    $nome  = isset($_POST['nome_completo']) ? $_POST['nome_completo'] : 'Não definido';
    $email = isset($_POST['email_utilizador']) ? $_POST['email_utilizador'] : 'Não definido';
    $pass  = isset($_POST['password_utilizador']) ? $_POST['password_utilizador'] : '';

    // Output visual para confirmar que o PHP processou tudo com sucesso
    echo "<!DOCTYPE html>";
    echo "<html lang='pt-PT'>";
    echo "<head><meta charset='UTF-8'><title>Sucesso - HS Hotels</title></head>";
    echo "<body style='font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f4;'>";
    echo "<div style='background: white; padding: 30px; border-radius: 8px; max-width: 500px; margin: 0 auto; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);'>";
    
    echo "<h2 style='color: #4CAF50;'>✔ Servidor PHP: Dados Recebidos!</h2>";
    echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";
    echo "<p><strong>Nome recebido:</strong> " . htmlspecialchars($nome) . "</p>";
    echo "<p><strong>Email recebido:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p style='color: #777; font-size: 13px;'>A sua User Story (USR1) está funcional. O próximo passo será validar na Base de Dados.</p>";
    echo "<br><a href='catalogo.html' style='display: inline-block; padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 4px;'>Ir para o Catálogo</a>";
    
    echo "</div>";
    echo "</body>";
    echo "</html>";

} else {
    header("Location: index.html");
    exit();
}
?>