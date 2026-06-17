<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Marketing - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; padding: 20px; }
        .painel { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #f6f6f6; text-align: left; }
    </style>
</head>
<body>
    <div style="max-width:1000px; margin:0 auto 20px auto;">
        <h1>Agente de Marketing</h1>
        <a href="catalogo.php">Voltar ao Catálogo</a>
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