<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Painel de Marketing</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .btn-nav { background: #ff1e00; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .painel { max-width: 1000px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #f6f6f6; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Painel de Marketing</h1>
        <a href="catalogo.php" class="btn-nav">Voltar ao Catálogo</a>
    </div>
    <div class="painel">
        <table>
            <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Preço Anterior</th></tr></thead>
            <tbody>
                <?php foreach ($ofertas as $o): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($o['titulo']); ?></strong></td>
                        <td><?php echo number_format($o['preco'], 2); ?>€</td>
                        <td><?php echo $o['preco_antigo'] ? number_format($o['preco_antigo'], 2) . "€" : "-"; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>