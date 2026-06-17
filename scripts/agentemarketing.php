<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$queryOfertas = "SELECT titulo, descricao, preco, preco_antigo FROM ofertas ORDER BY id DESC";
$resultadoOfertas = $db->query($queryOfertas);
$ofertas = $resultadoOfertas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Marketing</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; padding: 20px; }
        .cabecalho { display: flex; justify-content: space-between; max-width: 1300px; margin: 0 auto 30px auto; }
        .painel { max-width: 1300px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #f6f6f6; text-align: left; }
        .btn-voltar { background-color: #333; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="cabecalho">
        <h1>Painel de Marketing</h1>
        <a href="catalogo.php" class="btn-voltar">Ir para o Catálogo</a>
    </div>
    <div class="painel">
        <table>
            <thead>
                <tr>
                    <th>Oferta</th>
                    <th>Preço Atual</th>
                    <th>Preço Anterior</th>
                </tr>
            </thead>
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