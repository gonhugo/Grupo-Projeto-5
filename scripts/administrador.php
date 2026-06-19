<?php
try {
    $db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $db->query("SELECT username FROM utilizadores LIMIT 1");
    } catch (Exception $e) {
        $db->exec("DROP TABLE IF EXISTS utilizadores");
    }
    $db->exec("CREATE TABLE IF NOT EXISTS ofertas (id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, preco REAL, preco_antigo REAL)");
    $db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL, password TEXT NOT NULL, ultimo_acesso TEXT)");
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

$erro = "";
$sucesso = "";

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
            $erro = "Erro: " . $e->getMessage();
        }
    }

    if (isset($_POST['remover_oferta_id'])) {
        try {
            $stmt = $db->prepare("DELETE FROM ofertas WHERE id = :id");
            $stmt->execute([':id' => $_POST['remover_oferta_id']]);
            header("Location: administrador.php?oferta_removida=1");
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao remover oferta: " . $e->getMessage();
        }
    }

    if (isset($_POST['remover_utilizador_email'])) {
        try {
            $emailRemover = trim($_POST['email_utilizador'] ?? '');
            if ($emailRemover === '') {
                $erro = "Indique o email do utilizador a remover.";
            } else {
                $stmt = $db->prepare("DELETE FROM utilizadores WHERE email = :email");
                $stmt->execute([':email' => $emailRemover]);
                if ($stmt->rowCount() > 0) {
                    header("Location: administrador.php?removido=1");
                    exit();
                } else {
                    $erro = "Não foi encontrado nenhum utilizador com o email \"" . htmlspecialchars($emailRemover) . "\".";
                }
            }
        } catch (PDOException $e) {
            $erro = "Erro ao remover utilizador: " . $e->getMessage();
        }
    }

    if (isset($_POST['remover_utilizador_id'])) {
        try {
            $stmt = $db->prepare("DELETE FROM utilizadores WHERE id = :id");
            $stmt->execute([':id' => $_POST['remover_utilizador_id']]);
            header("Location: administrador.php?removido=1");
            exit();
        } catch (PDOException $e) {
            $erro = "Erro ao remover utilizador: " . $e->getMessage();
        }
    }
}

if (isset($_GET['removido'])) {
    $sucesso = "Utilizador removido com sucesso.";
}
if (isset($_GET['oferta_removida'])) {
    $sucesso = "Oferta removida com sucesso.";
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
        .btn-remover-topo { background-color: #e53935; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; transition: background 0.2s; }
        .btn-remover-topo:hover { background-color: #b71c1c; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        th { background-color: #f8f9fa; font-weight: bold; color: #555; border-bottom: 2px solid #eee; text-align: left; padding: 12px; font-size: 14px; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: top; }
        tr:hover { background-color: #fdfdfd; }
        .erro-msg { background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .sucesso-msg { background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .btn-remover-linha {
            background-color: #fff;
            color: #e53935;
            border: 1.5px solid #e53935;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-remover-linha:hover { background-color: #e53935; color: #fff; }
        form.form-linha { background: none; padding: 0; border: none; margin: 0; display: inline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Painel Administrativo</h1>
        <a href="catalogo.php" style="color: #ff1e00; text-decoration: none; font-weight: bold;">Ir para o Catálogo →</a>
    </div>
    <div class="container">
        <?php if ($erro): ?>
            <div class="erro-msg"><?php echo $erro; ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="sucesso-msg"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <h2>Gestão de Ofertas</h2>
        <form method="POST">
            <input type="hidden" name="adicionar_oferta" value="1">
            <input type="text" name="titulo_oferta" class="campo-form" placeholder="Título da Oferta" required>
            <input type="text" name="descricao_oferta" class="campo-form" placeholder="Descrição/Detalhes" required>
            <input type="number" name="preco_oferta" step="0.01" class="campo-form" placeholder="Preço (€)" required>
            <button type="submit" class="btn-adicionar">Adicionar Oferta</button>
        </form>
        <form method="POST">
            <input type="number" name="remover_oferta_id" class="campo-form" placeholder="ID da Oferta a remover" required>
            <button type="submit" class="btn-remover-topo" onclick="return confirm('Tem a certeza que quer remover esta oferta?');">Remover Oferta</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Oferta</th>
                    <th>Preço Atual</th>
                    <th>Preço Antigo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $ofertas = $db->query("SELECT * FROM ofertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($ofertas)) {
                        echo "<tr><td colspan='5' style='text-align:center; color:#999;'>Nenhuma oferta registada.</td></tr>";
                    } else {
                        foreach ($ofertas as $o) {
                            $idOferta = (int)$o['id'];
                            $titulo = htmlspecialchars($o['titulo'] ?? '');
                            $desc = htmlspecialchars($o['descricao'] ?? '');
                            $preco = number_format((float)($o['preco'] ?? 0), 2) . "€";
                            $preco_antigo = !empty($o['preco_antigo']) ? number_format((float)$o['preco_antigo'], 2) . "€" : "-";
                            echo "<tr>";
                            echo "<td>{$idOferta}</td>";
                            echo "<td><strong>{$titulo}</strong><br><span style='color:#666; font-size:12px;'>{$desc}</span></td>";
                            echo "<td>{$preco}</td>";
                            echo "<td>{$preco_antigo}</td>";
                            echo "<td>
                                    <form class='form-linha' method='POST' onsubmit='return confirm(\"Remover {$titulo}?\");'>
                                        <input type='hidden' name='remover_oferta_id' value='{$idOferta}'>
                                        <button type='submit' class='btn-remover-linha'>🗑 Remover</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5' style='color:red;'>Erro ao carregar ofertas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Gestão de Utilizadores</h2>
        <form method="POST">
            <input type="hidden" name="remover_utilizador_email" value="1">
            <input type="email" name="email_utilizador" class="campo-form" placeholder="Email do Utilizador a remover" required>
            <button type="submit" class="btn-remover-topo" onclick="return confirm('Tem a certeza que quer remover este utilizador?');">Remover Utilizador</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nome de Utilizador (Username)</th>
                    <th>Email</th>
                    <th>Último Acesso</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $utilizadores = $db->query("SELECT * FROM utilizadores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($utilizadores)) {
                        echo "<tr><td colspan='4' style='text-align:center; color:#999;'>Nenhum utilizador registado.</td></tr>";
                    } else {
                        foreach ($utilizadores as $u) {
                            $username = htmlspecialchars($u['username'] ?? 'Sem Nome');
                            $email = htmlspecialchars($u['email'] ?? 'Sem Email');
                            $ultimo = htmlspecialchars($u['ultimo_acesso'] ?? 'Nunca acedeu');
                            $id = (int)$u['id'];
                            echo "<tr>";
                            echo "<td>{$username}</td>";
                            echo "<td>{$email}</td>";
                            echo "<td><small style='color:#666;'>{$ultimo}</small></td>";
                            echo "<td>
                                    <form class='form-linha' method='POST' onsubmit='return confirm(\"Remover {$username}?\");'>
                                        <input type='hidden' name='remover_utilizador_id' value='{$id}'>
                                        <button type='submit' class='btn-remover-linha'>🗑 Remover</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='4' style='color:red;'>Erro ao carregar utilizadores.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>