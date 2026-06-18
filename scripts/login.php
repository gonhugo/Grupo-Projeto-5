<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Login - HS Hotels</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; text-align: center; }
        .mensagem-box { max-width: 400px; margin: 50px auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .sucesso { color: #4CAF50; }
        .erro { color: #f44336; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="mensagem-box">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['login_username'] ?? '');
    $password = trim($_POST['login_password'] ?? '');
    if (!empty($username) && !empty($password)) {
        try {
            $db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $db->prepare("SELECT * FROM utilizadores WHERE username = :username AND password = :password");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($utilizador) {
                $dataAtual = date('Y-m-d H:i:s');
                $stmtUpdate = $db->prepare("UPDATE utilizadores SET ultimo_acesso = :ultimo_acesso WHERE id = :id");
                $stmtUpdate->bindParam(':ultimo_acesso', $dataAtual);
                $stmtUpdate->bindParam(':id', $utilizador['id']);
                $stmtUpdate->execute();
                echo "<h2 class='sucesso'>✓ Login Efetuado!</h2>";
                echo "<p>Bem-vindo de volta, <strong>" . htmlspecialchars($utilizador['username']) . "</strong>.</p>";
            } else {
                echo "<h2 class='erro'>✕ Acesso Negado</h2><p>Username ou Password incorretos.</p>";
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