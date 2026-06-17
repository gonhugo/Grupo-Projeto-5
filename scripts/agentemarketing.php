<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_preco'])) {
    $stmt = $db->prepare("UPDATE ofertas SET preco_antigo = preco, preco = :np WHERE id = :id");
    $stmt->execute([':np' => $_POST['novo_preco'], ':id' => $_POST['id_oferta']]);
    header("Location: agentemarketing.php"); exit();
}
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Agente de Marketing - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #eee; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Agente de Marketing</h1>
        <a href="catalogo.php" style="color: white; text-decoration: none; font-weight: bold;">Voltar ao Catálogo</a>
    </div>
    <div class="container">
        <table>
            <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Alterar Preço</th></tr></thead>
            <tbody>
                <?php foreach ($ofertas as $o): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($o['titulo']); ?></strong></td>
                        <td><?php echo number_format($o['preco'], 2); ?>€</td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="atualizar_preco" value="1">
                                <input type="hidden" name="id_oferta" value="<?php echo $o['id']; ?>">
                                <input type="number" name="novo_preco" step="0.01" required>
                                <button type="submit">Alterar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>