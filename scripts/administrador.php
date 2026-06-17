<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// NOTA: Certifica-te que o teu 'novoregistro.php' usa o nome de tabela 'utilizadores'
$db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT NOT NULL, email TEXT NOT NULL)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lógica para remover utilizador
    if (isset($_POST['remover_utilizador'])) {
        $stmt = $db->prepare("DELETE FROM utilizadores WHERE nome = :nome AND email = :email");
        $stmt->execute([':nome' => $_POST['nome_remover'], ':email' => $_POST['email_remover']]);
        header("Location: administrador.php"); exit();
    }
}
?>
<h2>Remover Utilizador</h2>
        <form method="POST">
            <input type="hidden" name="remover_utilizador" value="1">
            <input type="text" name="nome_remover" class="campo-form" placeholder="Nome Exato" required>
            <input type="email" name="email_remover" class="campo-form" placeholder="Email Exato" required>
            <button type="submit" class="btn-adicionar" style="background-color: #f44336;">Remover Utilizador</button>
        </form>

        <table>
            <thead><tr><th>Nome</th><th>Email</th></tr></thead>
            <tbody>
                <?php
                // Esta consulta lê da tabela 'utilizadores'
                $utilizadores = $db->query("SELECT * FROM utilizadores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($utilizadores as $u) {
                    echo "<tr><td>{$u['nome']}</td><td>{$u['email']}</td></tr>";
                }
                ?>
            </tbody>
        </table>