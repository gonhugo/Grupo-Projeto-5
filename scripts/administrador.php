<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT NOT NULL,
    preco REAL NOT NULL,
    preco_antigo REAL
)");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_oferta'])) {
    $titulo = trim($_POST['titulo_oferta'] ?? '');
    $descricao = trim($_POST['descricao_oferta'] ?? '');
    $preco = floatval($_POST['preco_oferta'] ?? 0);
    
    $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco, preco_antigo) VALUES (:titulo, :descricao, :preco, NULL)");
    $stmt->execute([':titulo' => $titulo, ':descricao' => $descricao, ':preco' => $preco]);
    
    header("Location: administrador.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_preco'])) {
    $id = intval($_POST['id_oferta']);
    $novo_preco = floatval($_POST['novo_preco']);
    
    $stmt = $db->prepare("UPDATE ofertas SET preco_antigo = preco, preco = :novo_preco WHERE id = :id");
    $stmt->execute([':novo_preco' => $novo_preco, ':id' => $id]);
    
    header("Location: administrador.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; padding: 20px; }
        .cabecalho-admin { display: flex; justify-content: space-between; max-width: 1300px; margin: 0 auto 30px auto; }
        .painel-container { display: flex; justify-content: center; gap: 25px; max-width: 1300px; margin: 0 auto; }
        .cartao-admin { background-color: #fff; border-radius: 12px; padding: 30px; width: 45%; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-adicionar { background-color: #ff1e00; color: white; padding: 10px 18px; border-radius: 6px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 300px; border-radius: 8px; }
        .campo-form { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; box-sizing: border-box; }
        .btn-submit { width: 100%; background: #ff1e00; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="cabecalho-admin">
        <h1>Administrador</h1>
        <a href="catalogo.php">Ir para o Catálogo</a>
    </div>

    <div class="painel-container">
        <div class="cartao-admin">
            <h2>Ofertas</h2>
            <button onclick="abrirModal('modalOferta')" class="btn-adicionar">+ Adicionar Oferta</button>
            <table>
                <thead><tr><th>Oferta</th><th>Preço</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php
                    $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ofertas as $o) {
                        echo "<tr><td>" . htmlspecialchars($o['titulo']) . "</td><td>" . number_format($o['preco'], 2) . "€</td>";
                        echo "<td><button onclick='abrirEditar(".$o['id'].")'>Alterar Preço</button></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <h3>Alterar Preço</h3>
            <form action="administrador.php" method="POST">
                <input type="hidden" name="atualizar_preco" value="1">
                <input type="hidden" name="id_oferta" id="id_oferta">
                <input type="number" name="novo_preco" step="0.01" class="campo-form" placeholder="Novo Preço" required>
                <button type="submit" class="btn-submit">Confirmar Alteração</button>
            </form>
        </div>
    </div>

    <script>
        function abrirEditar(id) { document.getElementById('id_oferta').value = id; document.getElementById('modalEditar').style.display = 'block'; }
        function abrirModal(id) { document.getElementById(id).style.display = 'block'; }
    </script>
</body>
</html>