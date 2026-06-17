<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT NOT NULL,
    preco REAL NOT NULL
)");

$query = "SELECT titulo, descricao, preco FROM ofertas ORDER BY id DESC";
$resultado = $db->query($query);
$ofertas = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Ofertas - HS Hotels</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header {
            background-color: #111;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header-botoes {
            display: flex;
            gap: 15px;
        }
        .btn-admin {
            background-color: #ff1e00;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-admin:hover {
            background-color: #d61800;
        }
        .btn-marketing {
            background-color: #2196F3;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-marketing:hover {
            background-color: #0b7dda;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .titulo-seccao {
            font-size: 28px;
            margin-bottom: 30px;
            text-align: center;
        }
        .grelha-ofertas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        .cartao-oferta {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        .cartao-oferta h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #000;
            font-size: 20px;
        }
        .cartao-oferta p {
            color: #666;
            line-height: 1.5;
            flex-grow: 1;
        }
        .cartao-oferta .preco {
            font-size: 24px;
            font-weight: bold;
            color: #ff1e00;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .mensagem-vazia {
            text-align: center;
            color: #888;
            font-size: 18px;
            grid-column: 1 / -1;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
        }
        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>HS Hotels</h1>
        <div class="header-botoes">
            <a href="agentemarketing.html" class="btn-marketing">Agente de Marketing</a>
            <a href="administrador.php" class="btn-admin">Painel Admin</a>
        </div>
    </div>

    <div class="container">
        <h2 class="titulo-seccao">As Nossas Melhores Ofertas</h2>
        
        <div class="grelha-ofertas">
            <?php
            if (count($ofertas) > 0) {
                foreach ($ofertas as $oferta) {
                    echo "<div class='cartao-oferta'>";
                    echo "<h3>" . htmlspecialchars($oferta['titulo']) . "</h3>";
                    echo "<p>" . htmlspecialchars($oferta['descricao']) . "</p>";
                    echo "<div class='preco'>" . number_format($oferta['preco'], 2) . "€ <span style='font-size: 14px; color: #999; font-weight: normal;'>/ noite</span></div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='mensagem-vazia'>Ainda não existem ofertas disponíveis no momento. Volte mais tarde!</div>";
            }
            ?>
        </div>
    </div>

</body>
</html>