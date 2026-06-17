<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT NOT NULL,
    preco REAL NOT NULL
)");

$queryOfertas = "SELECT titulo, descricao, preco FROM ofertas ORDER BY id DESC";
$resultadoOfertas = $db->query($queryOfertas);
$ofertas = $resultadoOfertas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Marketing - HS Hotels</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .cabecalho-marketing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1300px;
            margin: 0 auto 30px auto;
            padding: 0 10px;
        }
        .cabecalho-marketing h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #000;
        }
        .btn-voltar {
            background-color: #333;
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            font-weight: bold;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-voltar:hover {
            background-color: #111;
        }
        .painel-container {
            max-width: 1300px;
            margin: 0 auto;
        }
        .cartao-marketing {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }
        .cartao-marketing h2 {
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 20px;
            color: #2196F3;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #666;
            font-size: 14px;
            padding: 12px 10px;
            font-weight: 600;
            border-bottom: 1px solid #eeeeee;
        }
        td {
            padding: 14px 10px;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #f6f6f6;
            vertical-align: top;
        }
        td p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #777;
        }
        .badge-ativo {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        @media (max-width: 900px) {
            .cabecalho-marketing {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="cabecalho-marketing">
        <h1>Painel do Agente de Marketing</h1>
        <a href="catalogo.php" class="btn-voltar">Voltar para o Catálogo</a>
    </div>

    <div class="painel-container">
        <div class="cartao-marketing">
            <h2>Campanhas e Ofertas Ativas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Oferta / Descrição</th>
                        <th>Preço Base</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($ofertas) > 0) {
                        foreach ($ofertas as $oferta) {
                            echo "<tr>";
                            echo "<td><strong>" . htmlspecialchars($oferta['titulo']) . "</strong><p>" . htmlspecialchars($oferta['descricao']) . "</p></td>";
                            echo "<td>" . number_format($oferta['preco'], 2) . "€</td>";
                            echo "<td><span class='badge-ativo'>Ativa</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' style='text-align: center; color: #999; padding: 20px;'>Nenhuma oferta registada. As ofertas adicionadas pelo Administrador aparecerão aqui.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>