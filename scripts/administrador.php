<?php
// Certifica-te de que este caminho aponta para o ficheiro correto na raiz (ou pasta comum)
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lógica de remoção
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remover_utilizador'])) {
    $stmt = $db->prepare("DELETE FROM utilizadores WHERE username = :username AND email = :email");
    $stmt->execute([':username' => $_POST['username_remover'], ':email' => $_POST['email_remover']]);
    header("Location: administrador.php"); exit();
}
?>

<h2>Remover Utilizador</h2>
<form method="POST">
    <input type="hidden" name="remover_utilizador" value="1">
    <input type="text" name="username_remover" class="campo-form" placeholder="Username" required>
    <input type="email" name="email_remover" class="campo-form" placeholder="Email" required>
    <button type="submit" class="btn-adicionar" style="background-color: #f44336;">Remover Utilizador</button>
</form>

<table>
    <thead><tr><th>Username</th><th>Email</th></tr></thead>
    <tbody>
        <?php
        // Lemos da tabela 'utilizadores' onde o teu novoregistro.php também escreve
        $utilizadores = $db->query("SELECT username, email FROM utilizadores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($utilizadores as $u) {
            echo "<tr><td>{$u['username']}</td><td>{$u['email']}</td></tr>";
        }
        ?>
    </tbody>
</table>