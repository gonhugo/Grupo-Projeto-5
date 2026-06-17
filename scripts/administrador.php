<?php
$db = new PDO("sqlite:hshotels.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS utilizadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    ultimo_acesso TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS ofertas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT NOT NULL,
    preco REAL NOT NULL
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_utilizador'])) {
        $username = trim($_POST['nome_completo'] ?? '');
        $email = trim($_POST['email_utilizador'] ?? '');
        $password = trim($_POST['password_utilizador'] ?? '');
        
        if (!empty($username) && !empty($email) && !empty($password)) {
            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM utilizadores WHERE username = :username");
            $stmtCheck->execute([':username' => $username]);
            
            if ($stmtCheck->fetchColumn() == 0) {
                $stmt = $db->prepare("INSERT INTO utilizadores (username, email, password, ultimo_acesso) VALUES (:username, :email, :password, :ultimo_acesso)");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $password,
                    ':ultimo_acesso' => date('Y-m-d H:i:s')
                ]);
            }
        }
        header("Location: administrador.php");
        exit();
    }

    if (isset($_POST['adicionar_oferta'])) {
        $titulo = trim($_POST['titulo_oferta'] ?? '');
        $descricao = trim($_POST['descricao_oferta'] ?? '');
        $preco = floatval($_POST['preco_oferta'] ?? 0);
        
        if (!empty($titulo) && !empty($descricao) && $preco > 0) {
            $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco) VALUES (:titulo, :descricao, :preco)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':preco' => $preco
            ]);
        }
        header("Location: administrador.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - HS Hotels</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1.titulo-admin {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #000;
        }
        .painel-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            gap: 25px;
            max-width: 1300px;
            margin: 0 auto;
        }
        .cartao-admin {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            width: 45%;
            min-width: 450px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }
        .cartao-admin h2 {
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 20px;
            color: #000;
            font-weight: bold;
        }
        .btn-adicionar {
            display: inline-block;
            background-color: #ff1e00;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 18px;
            font-weight: bold;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-adicionar:hover {
            background-color: #d61800;
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
        .link-remover {
            color: #ff1e00;
            text-decoration: none;
            font-weight: 500;
        }
        .link-remover:hover {
            text-decoration: underline;
        }
        .link-editar {
            color: #000000;
            text-decoration: none;
            font-weight: 500;
            margin-left: 10px;
        }
        .link-editar:hover {
            text-decoration: underline;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
        }
        .fechar {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .fechar:hover {
            color: #000;
        }
        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }
        .campo-form {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .btn-submit {
            width: 100%;
            background-color: #ff1e00;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }
        .btn-submit:hover {
            background-color: #d61800;
        }

        @media (max-width: 900px) {
            .painel-container {
                flex-direction: column;
                align-items: center;
            }
            .cartao-admin {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
</head>
<body>

    <h1 class="titulo-admin">Olá Administrador</h1>

    <div class="painel-container">

        <div class="cartao-admin">
            <h2>Lista de Utilizadores</h2>
            <button onclick="abrirModal('modalUtilizador')" class="btn-adicionar">+ Adicionar Utilizador</button>

            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT username, email, ultimo_acesso FROM utilizadores ORDER BY id DESC";
                    $resultado = $db->query($query);
                    $linhas = $resultado->fetchAll(PDO::FETCH_ASSOC);

                    if (count($linhas) > 0) {
                        foreach ($linhas as $linha) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($linha['username']) . "<p><small>Acesso: " . htmlspecialchars($linha['ultimo_acesso'] ?? 'N/A') . "</small></p></td>";
                            echo "<td>" . htmlspecialchars($linha['email']) . "</td>";
                            echo "<td><a href='#' class='link-remover'>Remover</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' style='text-align: center; color: #999; padding: 20px;'>Nenhum utilizador registado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="cartao-admin">
            <h2>Lista de Ofertas (Catálogo)</h2>
            <button onclick="abrirModal('modalOferta')" class="btn-adicionar">+ Adicionar Nova Oferta</button>

            <table>
                <thead>
                    <tr>
                        <th>Oferta / Descrição</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $queryOfertas = "SELECT titulo, descricao, preco FROM ofertas ORDER BY id DESC";
                    $resultadoOfertas = $db->query($queryOfertas);
                    $linhasOfertas = $resultadoOfertas->fetchAll(PDO::FETCH_ASSOC);

                    if (count($linhasOfertas) > 0) {
                        foreach ($linhasOfertas as $oferta) {
                            echo "<tr>";
                            echo "<td><strong>" . htmlspecialchars($oferta['titulo']) . "</strong><p>" . htmlspecialchars($oferta['descricao']) . "</p></td>";
                            echo "<td>" . number_format($oferta['preco'], 2) . "€</td>";
                            echo "<td><a href='#' class='link-remover'>Remover</a><a href='#' class='link-editar'>Editar</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' style='text-align: center; color: #999; padding: 20px;'>Nenhuma oferta registada no catálogo.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <div id="modalUtilizador" class="modal">
        <div class="modal-content">
            <span class="fechar" onclick="fecharModal('modalUtilizador')">&times;</span>
            <h3>Adicionar Novo Utilizador</h3>
            <form action="administrador.php" method="POST">
                <input type="hidden" name="adicionar_utilizador" value="1">
                <input type="text" name="nome_completo" class="campo-form" placeholder="Nome Completo" required>
                <input type="email" name="email_utilizador" class="campo-form" placeholder="Email" required>
                <input type="password" name="password_utilizador" class="campo-form" placeholder="Password" required>
                <button type="submit" class="btn-submit">Registar Utilizador</button>
            </form>
        </div>
    </div>

    <div id="modalOferta" class="modal">
        <div class="modal-content">
            <span class="fechar" onclick="fecharModal('modalOferta')">&times;</span>
            <h3>Adicionar Nova Oferta</h3>
            <form action="administrador.php" method="POST">
                <input type="hidden" name="adicionar_oferta" value="1">
                <input type="text" name="titulo_oferta" class="campo-form" placeholder="Nome do Hotel / Quarto" required>
                <textarea name="descricao_oferta" class="campo-form" rows="3" placeholder="Descrição da oferta..." required></textarea>
                <input type="number" name="preco_oferta" class="campo-form" step="0.01" placeholder="Preço por noite (€)" required>
                <button type="submit" class="btn-submit">Publicar Oferta</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function fecharModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>