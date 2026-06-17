<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT titulo, descricao, preco, preco_antigo FROM ofertas ORDER BY id DESC";
$resultado = $db->query($query);
$ofertas = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Ofertas</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; }
        .header { background-color: #111; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .cartao-oferta { background-color: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .preco { font-size: 24px; font-weight: bold; color: #ff1e00; margin-top: 20px; }
        .btn-nav { background-color: #ff1e00; color: #fff; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HS Hotels</h1>
        <div>
            <a href="agentemarketing.php" class="btn-nav" style="background: #2196F3;">Marketing</a>
            <a href="administrador.php" class="btn-nav">Administrador</a>
        </div>
    </div>
    <div class="container">
        <?php foreach ($ofertas as $oferta): ?>
            <div class='cartao-oferta'>
                <h3><?php echo htmlspecialchars($oferta['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($oferta['descricao']); ?></p>
                <div class='preco'>
                    <?php if (!empty($oferta['preco_antigo'])): ?>
                        <span style='text-decoration: line-through; color: #999; font-size: 16px;'><?php echo number_format($oferta['preco_antigo'], 2); ?>€</span>
                    <?php endif; ?>
                    <?php echo number_format($oferta['preco'], 2); ?>€
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>\