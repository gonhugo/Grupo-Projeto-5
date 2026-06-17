<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Catálogo - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; margin: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; }
        .container { max-width: 1200px; margin: 40px auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .card { background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .preco { font-size: 24px; font-weight: bold; color: #ff1e00; margin-top: 15px; }
        .btn-nav { background: #ff1e00; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HS Hotels</h1>
        <div>
            <a href="agentemarketing.php" class="btn-nav" style="background: #2196F3;">Marketing</a>
            <a href="administrador.php" class="btn-nav">Admin</a>
        </div>
    </div>
    <div class="container">
        <?php foreach ($ofertas as $o): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($o['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($o['descricao']); ?></p>
                <div class="preco">
                    <?php if ($o['preco_antigo']): ?>
                        <span style="text-decoration:line-through; color:#999; font-size:16px;"><?php echo number_format($o['preco_antigo'], 2); ?>€</span>
                    <?php endif; ?>
                    <?php echo number_format($o['preco'], 2); ?>€
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>