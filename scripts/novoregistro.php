<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Registo - Base de Dados SQLite3</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        .mensagem-box { max-width: 500px; margin: 30px auto; padding: 20px; border-radius: 6px; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .sucesso { border-left: 5px solid #4CAF50; color: #4CAF50; }
        .erro { border-left: 5px solid #f44336; color: #f44336; }
        .btn { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<div class="mensagem-box">
<?php

try {
    $db = new PDO("sqlite:hshotels.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar a tabela se ela não existir (Ponto 6.a)
    // Já inclui o campo 'ultimo_acesso' pedido no Ponto 8.b
    $queryTabela = "CREATE TABLE IF NOT EXISTS utilizadores (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL,
        password TEXT NOT NULL,
        ultimo_acesso TEXT
    )";
    $db->exec($queryTabela);

} catch (PDOException $e) {
    echo "<h3 class='erro'>Erro de ligação à Base de Dados: " . $e->getMessage() . "</h3>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolha e higienização dos dados do formulário index.html
    $username = isset($_POST['nome_completo']) ? trim($_POST['nome_completo']) : '';
    $email = isset($_POST['email_utilizador']) ? trim($_POST['email_utilizador']) : '';
    $password = isset($_POST['password_utilizador']) ? trim($_POST['password_utilizador']) : '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            // Verificar primeiro se o Username já existe (Como pede a tua tabela da USR1)
            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM utilizadores WHERE username = :username");
            $stmtCheck->bindParam(':username', $username);
            $stmtCheck->execute();
            
            if ($stmtCheck->fetchColumn() > 0) {
                echo "<h2 class='erro'>✕ Erro no Registo</h2>";
                echo "<p>O nome de utilizador <strong>$username</strong> já se encontra registado. Escolha outro.</p>";
            } else {
                // Inserir o novo utilizador na base de dados SQLite3 (Escrita - Ponto 7.a)
                $stmtInsert = $db->prepare("INSERT INTO utilizadores (username, email, password, ultimo_acesso) VALUES (:username, :email, :password, :ultimo_acesso)");
                
                // Registar a hora atual como o primeiro acesso (Ponto 8.b)
                $dataAtual = date('Y-m-d H:i:s');
                
                $stmtInsert->bindParam(':username', $username);
                $stmtInsert->bindParam(':email', $email);
                $stmtInsert->bindParam(':password', $password); // Nota: Em produção usaria-se password_hash
                $stmtInsert->bindParam(':ultimo_acesso', $dataAtual);
                
                $stmtInsert->execute();

                echo "<h2 class='sucesso'>✓ Registado com Sucesso!</h2>";
                echo "<p>Bem-vindo, <strong>$username</strong>. Os teus dados foram guardados na base de dados SQLite3.</p>";
                echo "<p><small>Data do primeiro acesso gravada: $dataAtual</small></p>";
            }
        } catch (PDOException $e) {
            echo "<h3 class='erro'>Erro ao guardar os dados: " . $e->getMessage() . "</h3>";
        }
    } else {
        echo "<h3 class='erro'>Por favor, preencha todos os campos do formulário.</h3>";
    }
} else {
    echo "<h3 class='erro'>Acesso inválido ao script.</h3>";
}
?>
<br>
<a href="index.html" class="btn">Voltar ao Início</a>
</div>

</body>
</html>