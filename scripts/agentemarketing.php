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
    <title>Marketing</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; }
        .container { max-width: 1200px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header"><h1>Agente de Marketing</h1></div>
    <div class="container">
        <a href="catalogo.php">Voltar ao Catálogo</a>
        <table>
            <thead><tr><th>Oferta</th><th>Preço</th><th>Alterar Preço</th></tr></thead>
            <tbody>
                <?php foreach ($ofertas as $o): ?>
                    <tr>
                        <td><?php echo $o['titulo']; ?></td>
                        <td><?php echo $o['preco']; ?>€</td>
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