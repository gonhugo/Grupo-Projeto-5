<?php
// Buscar dados da oferta diretamente da base de dados pelo ?id=
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$oferta = null;
if ($id > 0) {
    try {
        $db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("SELECT * FROM ofertas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $oferta = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $oferta = null;
    }
}

// Se não encontrou oferta, redireciona para catálogo
if (!$oferta) {
    header("Location: catalogo.php");
    exit();
}

$titulo   = htmlspecialchars($oferta['titulo']);
$descricao= htmlspecialchars($oferta['descricao'] ?? 'Sem descrição disponível.');
$preco    = number_format((float)$oferta['preco'], 2);
$precoAnt = !empty($oferta['preco_antigo']) ? number_format((float)$oferta['preco_antigo'], 2) : null;
$isViagem = stripos($oferta['titulo'], 'viagem') !== false;
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - <?php echo $titulo; ?> - HS Hotels</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        /* Fallback styles caso styles.css não carregue */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container-pagamento {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header-pagamento {
            background: #111;
            color: #fff;
            padding: 25px 30px;
            text-align: center;
        }
        .header-pagamento img {
            max-width: 160px;
            margin-bottom: 12px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header-pagamento h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        /* Detalhes da oferta */
        .detalhes-produto {
            padding: 28px 30px 20px;
            border-bottom: 1px solid #eee;
        }
        .detalhes-produto h3 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 10px;
            color: #111;
        }
        .detalhes-produto p {
            color: #555;
            font-size: 15px;
            margin: 0 0 16px;
            line-height: 1.5;
        }
        .preco-bloco {
            display: flex;
            align-items: baseline;
            gap: 12px;
            flex-wrap: wrap;
        }
        .preco-antigo-det {
            font-size: 16px;
            color: #aaa;
            text-decoration: line-through;
        }
        .preco-final {
            font-size: 28px;
            font-weight: 800;
            color: #ff1e00;
        }
        .desconto-pill {
            background: #ff1e00;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .tipo-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .tipo-hotel  { background: #e8f5e9; color: #2e7d32; }
        .tipo-viagem { background: #e3f2fd; color: #1565c0; }

        /* Comentários */
        .seccao-comentarios {
            padding: 24px 30px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }
        .seccao-comentarios h3 {
            margin: 0 0 16px;
            font-size: 16px;
            color: #333;
        }
        .comentario-item {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
        }
        .estrelas {
            color: #f5a623;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .comentario-item p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .novo-comentario {
            margin-top: 18px;
        }
        .novo-comentario label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .novo-comentario select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 10px;
            width: 140px;
        }
        .novo-comentario textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
            min-height: 90px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .novo-comentario textarea:focus { outline: none; border-color: #111; }
        .btn-comentar {
            margin-top: 10px;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-comentar:hover { background: #555; }

        /* Pagamento */
        .seccao-pagamento {
            padding: 24px 30px 30px;
        }
        .metodos-pagamento h3 {
            font-size: 16px;
            margin: 0 0 14px;
            color: #333;
        }
        .opcao-pagamento {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: border-color 0.2s;
        }
        .opcao-pagamento:hover { border-color: #111; }
        .opcao-pagamento input[type="radio"] { accent-color: #111; width: 18px; height: 18px; }

        .botoes-container {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .btn-cancelar {
            flex: 1;
            padding: 14px;
            background: #f5f5f5;
            color: #333;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            border: 2px solid #ddd;
            transition: background 0.2s;
        }
        .btn-cancelar:hover { background: #eee; }
        .btn-pagar {
            flex: 2;
            padding: 14px;
            background: #ff1e00;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-pagar:hover { background: #cc1800; transform: scale(1.02); }

        .aviso-total {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #795548;
        }
        .aviso-total strong { color: #333; font-size: 16px; }
    </style>
</head>
<body>

<div class="container-pagamento">

    <!-- HEADER -->
    <div class="header-pagamento">
        <img src="../images/logotipo.jpg" alt="Logótipo HS Hotels">
        <h2>Resumo da Reserva / Pagamento</h2>
    </div>

    <!-- DETALHES DA OFERTA (dados vindos da BD) -->
    <div class="detalhes-produto">
        <span class="tipo-badge <?php echo $isViagem ? 'tipo-viagem' : 'tipo-hotel'; ?>">
            <?php echo $isViagem ? '✈ Viagem' : '🏨 Hotel'; ?>
        </span>
        <h3><?php echo $titulo; ?></h3>
        <p><?php echo $descricao; ?></p>
        <div class="preco-bloco">
            <?php if ($precoAnt): ?>
                <span class="preco-antigo-det"><?php echo $precoAnt; ?>€</span>
            <?php endif; ?>
            <span class="preco-final"><?php echo $preco; ?>€</span>
            <?php if ($precoAnt && (float)$precoAnt > (float)$preco): 
                $descPct = round((1 - (float)$preco / (float)$precoAnt) * 100);
            ?>
                <span class="desconto-pill">-<?php echo $descPct; ?>%</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- AVALIAÇÕES -->
    <div class="seccao-comentarios">
        <h3>Avaliações de Clientes</h3>
        <div id="lista-comentarios"></div>

        <div class="novo-comentario">
            <label>Deixe a sua avaliação:</label>
            <select id="nota-estrela">
                <option value="★★★★★">5 Estrelas</option>
                <option value="★★★★☆">4 Estrelas</option>
                <option value="★★★☆☆">3 Estrelas</option>
                <option value="★★☆☆☆">2 Estrelas</option>
                <option value="★☆☆☆☆">1 Estrela</option>
            </select>
            <textarea id="texto-comentario" placeholder="Escreva aqui o seu comentário..."></textarea>
            <button type="button" class="btn-comentar" onclick="adicionarComentario()">Enviar Comentário</button>
        </div>
    </div>

    <!-- PAGAMENTO -->
    <div class="seccao-pagamento">
        <div class="aviso-total">
            Total a pagar: <strong><?php echo $preco; ?>€</strong> — <?php echo $titulo; ?>
        </div>

        <form onsubmit="confirmarPagamento(event)">
            <div class="metodos-pagamento">
                <h3>Método de Pagamento</h3>
                <label class="opcao-pagamento">
                    <input type="radio" name="metodo" value="mbway" required> 📱 MB WAY
                </label>
                <label class="opcao-pagamento">
                    <input type="radio" name="metodo" value="cartao"> 💳 Cartão de Crédito
                </label>
            </div>
            <div class="botoes-container">
                <a href="catalogo.php" class="btn-cancelar">← Cancelar</a>
                <button type="submit" class="btn-pagar">Pagar <?php echo $preco; ?>€ →</button>
            </div>
        </form>
    </div>

</div>

<script>
    // ID da oferta vindo do PHP (já validado e seguro)
    const idOferta = <?php echo $id; ?>;
    const chaveComentarios = 'comentarios_' + idOferta;

    // Comentário padrão se não houver nenhum
    if (!localStorage.getItem(chaveComentarios)) {
        localStorage.setItem(chaveComentarios, JSON.stringify([
            { estrelas: "★★★★★", texto: "Excelente atendimento e muito cómodo." }
        ]));
    }

    function carregarComentarios() {
        const coms = JSON.parse(localStorage.getItem(chaveComentarios)) || [];
        const box = document.getElementById('lista-comentarios');
        box.innerHTML = '';
        if (coms.length === 0) {
            box.innerHTML = '<p style="color:#999; font-size:14px;">Sem avaliações ainda.</p>';
            return;
        }
        coms.forEach(c => {
            box.innerHTML += `
                <div class="comentario-item">
                    <div class="estrelas">${c.estrelas}</div>
                    <p>${c.texto}</p>
                </div>`;
        });
    }

    function adicionarComentario() {
        const texto = document.getElementById('texto-comentario').value.trim();
        const estrelas = document.getElementById('nota-estrela').value;
        if (texto !== '') {
            const coms = JSON.parse(localStorage.getItem(chaveComentarios)) || [];
            coms.push({ estrelas, texto });
            localStorage.setItem(chaveComentarios, JSON.stringify(coms));
            document.getElementById('texto-comentario').value = '';
            carregarComentarios();
        }
    }

    function confirmarPagamento(event) {
        event.preventDefault();
        const metodo = document.querySelector('input[name="metodo"]:checked');
        if (!metodo) { alert('Por favor, selecione um método de pagamento.'); return; }
        alert('✅ Pagamento de <?php echo $preco; ?>€ efetuado com sucesso via ' + (metodo.value === 'mbway' ? 'MB WAY' : 'Cartão de Crédito') + '!\n\n<?php echo addslashes($titulo); ?>');
        window.location.href = 'catalogo.php';
    }

    window.onload = carregarComentarios;
</script>

</body>
</html>