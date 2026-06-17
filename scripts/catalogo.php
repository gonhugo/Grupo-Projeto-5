<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Catálogo</title>
    <style>body{font-family:sans-serif; padding:20px;} .grid{display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;} .card{border:1px solid #ccc; padding:15px;}</style>
</head>
<body>
    <h1>Catálogo de Ofertas</h1>
    <a href="administrador.php">Admin</a> | <a href="agentemarketing.php">Marketing</a>
    <div class="grid">
        <?php foreach ($ofertas as $o): ?>
            <div class="card">
                <h3><?php echo $o['titulo']; ?></h3>
                <p><?php echo $o['descricao']; ?></p>
                <p>
                    <?php if ($o['preco_antigo']): ?>
                        <span style="text-decoration:line-through; color:red;"><?php echo $o['preco_antigo']; ?>€</span>
                    <?php endif; ?>
                    <strong><?php echo $o['preco']; ?>€</strong>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>