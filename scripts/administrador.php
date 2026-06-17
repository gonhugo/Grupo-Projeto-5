<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Garante que as tabelas existem
$db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT)");
$db->exec("CREATE TABLE IF NOT EXISTS ofertas (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, preco REAL, preco_antigo REAL)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_oferta'])) {
        $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco, preco_antigo) VALUES (:t, :d, :p, NULL)");
        $stmt->execute([':t' => $_POST['titulo_oferta'], ':d' => $_POST['descricao_oferta'], ':p' => $_POST['preco_oferta']]);
        header("Location: administrador.php"); exit();
    }
    if (isset($_POST['atualizar_preco'])) {
        $stmt = $db->prepare("UPDATE ofertas SET preco_antigo = preco, preco = :np WHERE id = :id");
        $stmt->execute([':np' => $_POST['novo_preco'], ':id' => $_POST['id_oferta']]);
        header("Location: administrador.php"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Administrador</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; }
        .container { max-width: 1200px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    <div class="header"><h1>Administrador</h1></div>
    <div class="container">
        <a href="catalogo.php">Ir para o Catálogo</a>
        <h2>Gestão de Ofertas</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_oferta" value="1">
            <input type="text" name="titulo_oferta" placeholder="Título" required>
            <input type="text" name="descricao_oferta" placeholder="Descrição" required>
            <input type="number" name="preco_oferta" step="0.01" placeholder="Preço" required>
            <button type="submit" style="background:red; color:white;">Adicionar</button>
        </form>
        <table>
            <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Preço Antigo</th></tr></thead>
            <tbody>
                <?php
                $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($ofertas as $o) {
                    echo "<tr><td>{$o['titulo']}</td><td>{$o['preco']}€</td><td>" . ($o['preco_antigo'] ?? '-') . "€</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>