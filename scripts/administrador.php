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
    <title>Painel do Administrador - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f0f2f5; padding: 20px; }
        .cabecalho-admin { display: flex; justify-content: space-between; max-width: 1300px; margin: 0 auto 30px auto; }
        .painel-container { max-width: 1300px; margin: 0 auto; }
        .cartao-admin { background-color: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-adicionar { background-color: #ff1e00; color: white; padding: 10px 18px; border-radius: 6px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #eee; text-align: left; }
        .campo-form { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="cabecalho-admin">
        <h1>Painel do Administrador</h1>
        <a href="catalogo.php">Ir para o Catálogo</a>
    </div>
    <div class="painel-container">
        <div class="cartao-admin">
            <h2>Gestão de Ofertas</h2>
            <form method="POST">
                <input type="hidden" name="adicionar_oferta" value="1">
                <input type="text" name="titulo_oferta" class="campo-form" placeholder="Título" required>
                <input type="text" name="descricao_oferta" class="campo-form" placeholder="Descrição" required>
                <input type="number" name="preco_oferta" step="0.01" class="campo-form" placeholder="Preço" required>
                <button type="submit" class="btn-adicionar">Adicionar</button>
            </form>
            <table>
                <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php
                    $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ofertas as $o) {
                        echo "<tr><td><strong>{$o['titulo']}</strong><br><small>{$o['descricao']}</small></td><td>{$o['preco']}€</td><td>
                              <form method='POST'>
                              <input type='hidden' name='atualizar_preco' value='1'>
                              <input type='hidden' name='id_oferta' value='{$o['id']}'>
                              <input type='number' name='novo_preco' step='0.01' class='campo-form' style='width:80px;' required>
                              <button type='submit'>Alterar</button></form></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>