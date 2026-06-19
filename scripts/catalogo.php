<?php
$db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
$ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);


$imagensOfertas = [
    1  => '../images/hotel_maia.webp',   // Hotel Maia 
    2  => '../images/TheVannah.webp',     // Hotel The Vannah
    3  => '../images/Tartarus.webp',       // Hotel Tartarus
    4  => '../images/Citadel.webp',        // Hotel Citadel
    5  => '../images/DelColorado.webp',    // Hotel Del Colorado
    6  => '../images/Zanarkand.webp',      // Zanarkand Underwater City
    7  => '../images/Anorlondo.webp',      // Anor Londo
    8  => '../images/Ryanair.webp',        // Ryanair Portugal - UK
    9  => '../images/easyjet.webp',        // Easyjet Portugal - EUA
    10 => '../images/portocoimbra.webp',   // Viagem Porto - Coimbra
    11 => '../images/Zanarkand.webp',      // Viagem para Zanarkand
    12 => '../images/easyjet.webp',        // TAP Portugal Continental
    13 => '../images/Ryanair.webp',        // Ryanair Portugal - New Vegas
    14 => '../images/vault21.webp',        // Hotel Vault 21
];
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - HS Hotels</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
        }

        /* ── HEADER ── */
        .header {
            background-color: #111;
            color: #fff;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .header h1 { font-size: 22px; letter-spacing: 1px; }
        .btn-nav {
            background: #ff1e00;
            color: #fff;
            padding: 9px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
            font-weight: bold;
            margin-left: 8px;
            transition: background 0.2s;
        }
        .btn-nav:hover { background: #cc1800; }
        .btn-nav.blue { background: #2196F3; }
        .btn-nav.blue:hover { background: #1565C0; }

        /* ── GRID ── */
        .container {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 25px 60px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
        }

        /* ── CARD ── */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.13);
        }

        /* ── CARD IMAGE ── */
        .card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .card-img-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }

        /* ── CARD BODY ── */
        .card-body {
            padding: 20px 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Badge: Hotel / Viagem */
        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 9px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .badge-hotel  { background: #e8f5e9; color: #2e7d32; }
        .badge-viagem { background: #e3f2fd; color: #1565c0; }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .card-desc {
            font-size: 13px;
            color: #666;
            flex: 1;
            line-height: 1.5;
        }

        /* ── PRICE BLOCK ── */
        .preco-bloco {
            margin-top: 16px;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }
        .preco-atual {
            font-size: 26px;
            font-weight: 800;
            color: #ff1e00;
        }
        .preco-antigo {
            font-size: 14px;
            color: #aaa;
            text-decoration: line-through;
        }
        .desconto-badge {
            background: #ff1e00;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            margin-left: auto;
        }

        /* ── BUTTON ── */
        .btn-reservar {
            display: block;
            margin-top: 18px;
            padding: 12px;
            background: #111;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-reservar:hover {
            background: #ff1e00;
            transform: scale(1.02);
        }

        /* ── EMPTY STATE ── */
        .empty {
            grid-column: 1 / -1;
            text-align: center;
            color: #999;
            padding: 60px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>🏨 HS Hotels</h1>
    <div>
        <a href="../index.php" class="btn-nav" style="background:#555;">← Início</a>
        <a href="agentemarketing.php" class="btn-nav blue">Marketing</a>
        <a href="administrador.php" class="btn-nav">Admin</a>
    </div>
</div>

<div class="container">
    <?php if (empty($ofertas)): ?>
        <div class="empty">Nenhuma oferta disponível de momento.</div>
    <?php else: ?>
        <?php foreach ($ofertas as $o):
            $id       = (int)$o['id'];
            $titulo   = htmlspecialchars($o['titulo'] ?? '');
            $descricao= htmlspecialchars($o['descricao'] ?? '');
            $preco    = (float)($o['preco'] ?? 0);
            $precoAnt = !empty($o['preco_antigo']) ? (float)$o['preco_antigo'] : null;
            $isViagem = stripos($titulo, 'viagem') !== false;
            $imgPath  = $imagensOfertas[$id] ?? null;

            // Calculate discount %
            $desconto = null;
            if ($precoAnt && $precoAnt > $preco) {
                $desconto = round((1 - $preco / $precoAnt) * 100);
            }
        ?>
        <div class="card">
            <?php if ($imgPath): ?>
                <img class="card-img" src="<?php echo $imgPath; ?>" alt="<?php echo $titulo; ?>">
            <?php else: ?>
                <div class="card-img-placeholder">
                    <?php echo $isViagem ? '✈️' : '🏨'; ?>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <span class="badge <?php echo $isViagem ? 'badge-viagem' : 'badge-hotel'; ?>">
                    <?php echo $isViagem ? '✈ Viagem' : '🏨 Hotel'; ?>
                </span>

                <div class="card-title"><?php echo $titulo; ?></div>
                <div class="card-desc"><?php echo $descricao; ?></div>

                <div class="preco-bloco">
                    <?php if ($precoAnt): ?>
                        <span class="preco-antigo"><?php echo number_format($precoAnt, 2); ?>€</span>
                    <?php endif; ?>
                    <span class="preco-atual"><?php echo number_format($preco, 2); ?>€</span>
                    <?php if ($desconto): ?>
                        <span class="desconto-badge">-<?php echo $desconto; ?>%</span>
                    <?php endif; ?>
                </div>

                <a href="pagamento.php?id=<?php echo $id; ?>" class="btn-reservar">
                    <?php echo $isViagem ? '✈ Reservar Viagem' : '🛏 Reservar Hotel'; ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>