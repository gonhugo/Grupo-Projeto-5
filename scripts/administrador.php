<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, preco REAL, preco_antigo REAL)");
$db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT NOT NULL, email TEXT NOT NULL)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_oferta'])) {
        $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco, preco_antigo) VALUES (:t, :d, :p, NULL)");
        $stmt->execute([':t' => $_POST['titulo_oferta'], ':d' => $_POST['descricao_oferta'], ':p' => $_POST['preco_oferta']]);
        header("Location: administrador.php"); exit();
    }
    if (isset($_POST['adicionar_utilizador'])) {
        $stmt = $db->prepare("INSERT INTO utilizadores (nome, email) VALUES (:n, :e)");
        $stmt->execute([':n' => $_POST['nome_utilizador'], ':e' => $_POST['email_utilizador']]);
        header("Location: administrador.php"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Administrador - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h2 { margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 40px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #eee; text-align: left; }
        .campo-form { padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-right: 5px; }
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
            <button type="submit" class="btn-adicionar">Adicionar Oferta</button>
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

        <h2>Gestão de Utilizadores</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_utilizador" value="1">
            <input type="text" name="nome_utilizador" class="campo-form" placeholder="Nome Completo" required>
            <input type="email" name="email_utilizador" class="campo-form" placeholder="Email" required>
            <button type="submit" class="btn-adicionar" style="background-color: #2196F3;">Adicionar Utilizador</button>
        </form>

        <table>
            <thead><tr><th>Nome</th><th>Email</th></tr></thead>
            <tbody>
                <?php
                $utilizadores = $db->query("SELECT * FROM utilizadores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($utilizadores as $u) {
                    echo "<tr><td>{$u['nome']}</td><td>{$u['email']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>