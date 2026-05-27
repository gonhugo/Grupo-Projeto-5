<?php
// Verificar se os dados foram enviados via método POST (quando o formulário é submetido)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados enviados pelo formulário HTML através do atributo 'name'
    $nome  = $_POST['nome_completo'];
    $email = $_POST['email_utilizador'];
    $pass  = $_POST['password_utilizador'];
 
    // O script recebe os dados e, no futuro, irá ligar-se à BD para verificar se o Username/Email já existe.
    echo "<h2>Servidor PHP: Dados Recebidos com Sucesso!</h2>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<br><a href='catalogo.html'>Ir para o Catálogo de Hotéis</a>";
} else {
    header("Location: index.html");
    exit();
}
?>