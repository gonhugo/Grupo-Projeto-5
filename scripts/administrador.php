<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, preco REAL, preco_antigo REAL)");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_oferta'])) {
    $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco, preco_antigo) VALUES (:t, :d, :p, NULL)");
    $stmt->execute([':t' => $_POST['titulo_oferta'], ':d' => $_POST['descricao_oferta'], ':p' => $_POST['preco_oferta']]);
    header("Location: administrador.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Administrador - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #eee; text-align: left; }
        .campo-form { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-adicionar { background-color: #ff1e00; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Administrador</h1>
        <a href="catalogo.php" style="color: white; text-decoration: none; font-weight: bold;">Ir para o Catálogo</a>
    </div>
    <div class="container">
        <h2>Gestão de Ofertas</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_oferta" value="1">
            <input type="text" name="titulo_oferta" class="campo-form" placeholder="Título" required>
            <input type="text" name="descricao_oferta" class="campo-form" placeholder="Descrição" required>
            <input type="number" name="preco_oferta" step="0.01" class="campo-form" placeholder="Preço" required>
            <button type="submit" class="btn-adicionar">Adicionar</button>
        </form>
        <table>
            <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Preço Antigo</th></tr></thead>
            <tbody>
                <?php
                $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($ofertas as $o) {
                    echo "<tr><td><strong>{$o['titulo']}</strong><br><small>{$o['descricao']}</small></td><td>{$o['preco']}€</td><td>" . ($o['preco_antigo'] ? $o['preco_antigo']."€" : "-") . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>