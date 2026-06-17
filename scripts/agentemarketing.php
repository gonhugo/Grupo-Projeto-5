<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Painel Marketing</title>
</head>
<body>
    <h1>Agente de Marketing</h1>
    <a href="catalogo.php">Voltar ao Catálogo</a>
    <table border="1">
        <thead><tr><th>Oferta</th><th>Preço Atual</th><th>Preço Anterior</th></tr></thead>
        <tbody>
            <?php foreach ($ofertas as $o): ?>
                <tr>
                    <td><?php echo $o['titulo']; ?></td>
                    <td><?php echo $o['preco']; ?>€</td>
                    <td><?php echo $o['preco_antigo'] ? $o['preco_antigo'] . "€" : "-"; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>