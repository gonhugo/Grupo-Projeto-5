<?php
try {
    $db = new PDO("sqlite:hshotels.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $db->query("SELECT username FROM utilizadores LIMIT 1");
    } catch (Exception $e) {
        $db->exec("DROP TABLE IF EXISTS utilizadores");
    }

    $db->exec("CREATE TABLE IF NOT EXISTS ofertas (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, preco REAL, preco_antigo REAL)");
    $db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL, password TEXT NOT NULL, ultimo_acesso TEXT)");

} catch (PDOException $e) {
    die("Erro ao ligar ou estruturar a base de dados: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_oferta'])) {
        try {
            $stmt = $db->prepare("INSERT INTO ofertas (titulo, descricao, preco, preco_antigo) VALUES (:t, :d, :p, NULL)");
            $stmt->execute([
                ':t' => $_POST['titulo_oferta'], 
                ':d' => $_POST['descricao_oferta'], 
                ':p' => $_POST['preco_oferta']
            ]);
            header("Location: administrador.php"); 
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao adicionar oferta: " . $e->getMessage();
        }
    }
    
    if (isset($_POST['adicionar_utilizador'])) {
        try {
            $username = trim($_POST['nome_utilizador']);
            $email = trim($_POST['email_utilizador']);
            $password = "12345"; 
            $dataAtual = date('Y-m-d H:i:s');

            $stmt = $db->prepare("INSERT INTO utilizadores (username, email, password, ultimo_acesso) VALUES (:username, :email, :password, :ultimo_acesso)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $password,
                ':ultimo_acesso' => $dataAtual
            ]);
            header("Location: administrador.php"); 
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao adicionar utilizador (O username já pode existir): " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Administrador - HS Hotels</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; color: #333; }
        .header { background-color: #111; color: #fff; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header h1 { margin: 0; font-size: 24px; }
        .container { max-width: 1100px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h2 { margin-top: 40px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; color: #000; }
        h2:first-of-type { margin-top: 0; }
        form { background: #fafafa; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .campo-form { padding: 10px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 180px; font-size: 14px; }
        .btn-adicionar { background-color: #ff1e00; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; transition: background 0.2s; }
        .btn-adicionar:hover { background-color: #cc1800; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        th { background-color: #f8f9fa; font-weight: bold; color: #555; border-bottom: 2px solid #eee; text-align: left; padding: 12px; font-size: 14px; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: top; }
        tr:hover { background-color: #fdfdfd; }
        .erro-msg { background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Painel Administrativo</h1>
        <a href="catalogo.php" style="color: #ff1e00; text-decoration: none; font-weight: bold;">Ir para o Catálogo →</a>
    </div>

    <div class="container">
        
        <?php if (isset($erro)): ?>
            <div class="erro-msg"><?php echo $erro; ?></div>
        <?php endif; ?>

        <h2>Gestão de Ofertas</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_oferta" value="1">
            <input type="text" name="titulo_oferta" class="campo-form" placeholder="Título da Oferta" required>
            <input type="text" name="descricao_oferta" class="campo-form" placeholder="Descrição/Detalhes" required>
            <input type="number" name="preco_oferta" step="0.01" class="campo-form" placeholder="Preço (€)" required>
            <button type="submit" class="btn-adicionar">Adicionar Oferta</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Oferta</th>
                    <th>Preço Atual</th>
                    <th>Preço Antigo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($ofertas)) {
                        echo "<tr><td colspan='3' style='text-align:center; color:#999;'>Nenhuma oferta registada.</td></tr>";
                    } else {
                        foreach ($ofertas as $o) {
                            $titulo = htmlspecialchars($o['titulo'] ?? '');
                            $desc = htmlspecialchars($o['descricao'] ?? '');
                            $preco = number_format((float)($o['preco'] ?? 0), 2) . "€";
                            $preco_antigo = !empty($o['preco_antigo']) ? number_format((float)$o['preco_antigo'], 2) . "€" : "-";
                            
                            echo "<tr>";
                            echo "<td><strong>{$titulo}</strong><br><span style='color:#666; font-size:12px;'>{$desc}</span></td>";
                            echo "<td>{$preco}</td>";
                            echo "<td>{$preco_antigo}</td>";
                            echo "</tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='3' style='color:red;'>Erro ao carregar ofertas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Gestão de Utilizadores</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_utilizador" value="1">
            <input type="text" name="nome_utilizador" class="campo-form" placeholder="Username (Nome de Utilizador)" required>
            <input type="email" name="email_utilizador" class="campo-form" placeholder="Email do Utilizador" required>
            <button type="submit" class="btn-adicionar" style="background-color: #2196F3;">Adicionar Utilizador</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome de Utilizador (Username)</th>
                    <th>Email</th>
                    <th>Último Acesso</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $utilizadores = $db->query("SELECT * FROM utilizadores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($utilizadores)) {
                        echo "<tr><td colspan='3' style='text-align:center; color:#999;'>Nenhum utilizador registado.</td></tr>";
                    } else {
                        foreach ($utilizadores as $u) {
                            $username = htmlspecialchars($u['username'] ?? 'Sem Nome');
                            $email = htmlspecialchars($u['email'] ?? 'Sem Email');
                            $ultimo = htmlspecialchars($u['ultimo_acesso'] ?? 'Nunca acedeu');
                            
                            echo "<tr>";
                            echo "<td>{$username}</td>";
                            echo "<td>{$email}</td>";
                            echo "<td><small style='color:#666;'>{$ultimo}</small></td>";
                            echo "</tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='3' style='color:red;'>Erro ao carregar utilizadores.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>