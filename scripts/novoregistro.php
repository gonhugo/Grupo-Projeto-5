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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username_utilizador'] ?? '');
    $email = trim($_POST['email_utilizador'] ?? '');
    $password = trim($_POST['password_utilizador'] ?? '');
    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            $db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL, password TEXT NOT NULL, ultimo_acesso TEXT)");
            $stmtCheck = $db->prepare("SELECT * FROM utilizadores WHERE username = :username");
            $stmtCheck->bindParam(':username', $username);
            $stmtCheck->execute();
            if ($stmtCheck->fetch()) {
                echo "<h2 class='erro'>✕ Erro no Registo</h2><p>O nome de utilizador <strong>$username</strong> já se encontra registado.</p>";
            } else {
                $stmtInsert = $db->prepare("INSERT INTO utilizadores (username, email, password, ultimo_acesso) VALUES (:username, :email, :password, :ultimo_acesso)");
                $dataAtual = date('Y-m-d H:i:s');
                $stmtInsert->bindParam(':username', $username);
                $stmtInsert->bindParam(':email', $email);
                $stmtInsert->bindParam(':password', $password);
                $stmtInsert->bindParam(':ultimo_acesso', $dataAtual);
                $stmtInsert->execute();
                echo "<h2 class='sucesso'>Registado com Sucesso!</h2><p>Bem-vindo, <strong>$username</strong>.</p>";
            }
        } catch (PDOException $e) {
            echo "<h3 class='erro'>Erro: " . $e->getMessage() . "</h3>";
        }
    } else {
        echo "<h3 class='erro'>Por favor, preencha todos os campos.</h3>";
    }
}
?>
<br><a href="catalogo.php" class="btn">Ir para o Catálogo</a>
</div>
</body>
</html>