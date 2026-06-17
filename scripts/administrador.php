<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT NOT NULL,
    preco REAL NOT NULL,
    preco_antigo REAL
)");

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
    <title>Painel Admin</title>
    <style>body{font-family:sans-serif; background:#f0f2f5; padding:20px;} .cartao{background:#fff; padding:20px; border-radius:8px; margin-bottom:20px;} table{width:100%; border-collapse:collapse;} th,td{padding:10px; border-bottom:1px solid #ddd; text-align:left;}</style>
</head>
<body>
    <h1>Administrador</h1>
    <a href="catalogo.php">Ir para o Catálogo</a>
    <div class="cartao">
        <h2>Adicionar Oferta</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_oferta" value="1">
            <input type="text" name="titulo_oferta" placeholder="Título" required>
            <input type="text" name="descricao_oferta" placeholder="Descrição" required>
            <input type="number" name="preco_oferta" step="0.01" placeholder="Preço" required>
            <button type="submit">Adicionar</button>
        </form>
    </div>
    <table>
        <thead><tr><th>Oferta</th><th>Preço</th><th>Ação</th></tr></thead>
        <tbody>
            <?php
            $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($ofertas as $o) {
                echo "<tr><td>{$o['titulo']}</td><td>{$o['preco']}€</td><td>
                      <form method='POST' style='display:inline;'>
                      <input type='hidden' name='atualizar_preco' value='1'>
                      <input type='hidden' name='id_oferta' value='{$o['id']}'>
                      <input type='number' name='novo_preco' step='0.01' required>
                      <button type='submit'>Alterar</button></form></td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>