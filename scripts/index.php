<?php
try {
    $db = new PDO("sqlite:" . __DIR__ . "/hshotels.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $db->query("SELECT username, email, password, ultimo_acesso FROM utilizadores LIMIT 1");
    } catch (Exception $e) {
        $db->exec("DROP TABLE IF EXISTS utilizadores");
    }
    $db->exec("CREATE TABLE IF NOT EXISTS utilizadores (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL, password TEXT NOT NULL, ultimo_acesso TEXT)");
} catch (PDOException $e) {
    die("Erro de ligação à base de dados: " . $e->getMessage());
}

$erroLogin = "";
$erroRegisto = "";
$mostrarRegisto = false;

//LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_login'])) {
    $identificador = trim($_POST['login_email'] ?? '');
    $password = trim($_POST['login_pass'] ?? '');

    if ($identificador !== '' && $password !== '') {
        if (($identificador === 'admin' || strtolower($identificador) === 'admin@email.com') && $password === 'admin') {
            header("Location: administrador.php");
            exit();
        }

        try {
            $stmt = $db->prepare("SELECT * FROM utilizadores WHERE (username = :id OR email = :id) AND password = :pass");
            $stmt->execute([':id' => $identificador, ':pass' => $password]);
            $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utilizador) {
                $dataAtual = date('Y-m-d H:i:s');
                $stmtUpdate = $db->prepare("UPDATE utilizadores SET ultimo_acesso = :ultimo_acesso WHERE id = :id");
                $stmtUpdate->execute([':ultimo_acesso' => $dataAtual, ':id' => $utilizador['id']]);
                header("Location: catalogo.php");
                exit();
            } else {
                $erroLogin = "Email/Utilizador ou Password incorretos.";
            }
        } catch (PDOException $e) {
            $erroLogin = "Erro ao iniciar sessão.";
        }
    } else {
        $erroLogin = "Por favor, preencha todos os campos.";
    }
}

//REGISTRO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_registo'])) {
    $mostrarRegisto = true;
    $nomeCompleto = trim($_POST['nome_completo'] ?? '');
    $email = trim($_POST['email_utilizador'] ?? '');
    $password = trim($_POST['password_utilizador'] ?? '');

    if ($nomeCompleto !== '' && $email !== '' && $password !== '') {
        try {
            $stmtCheck = $db->prepare("SELECT id FROM utilizadores WHERE username = :username OR email = :email");
            $stmtCheck->execute([':username' => $nomeCompleto, ':email' => $email]);

            if ($stmtCheck->fetch()) {
                $erroRegisto = "Já existe uma conta com esse nome ou email.";
            } else {
                $dataAtual = date('Y-m-d H:i:s');
                $stmtInsert = $db->prepare("INSERT INTO utilizadores (username, email, password, ultimo_acesso) VALUES (:username, :email, :password, :ultimo_acesso)");
                $stmtInsert->execute([
                    ':username' => $nomeCompleto,
                    ':email' => $email,
                    ':password' => $password,
                    ':ultimo_acesso' => $dataAtual
                ]);
                header("Location: catalogo.php");
                exit();
            }
        } catch (PDOException $e) {
            $erroRegisto = "Erro ao criar conta. Tente novamente.";
        }
    } else {
        $erroRegisto = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registo - HS Hotels</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #111 0%, #2b2b2b 45%, #ff1e00 220%);
            padding: 20px;
        }

        .painel {
            display: flex;
            width: 100%;
            max-width: 920px;
            min-height: 560px;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
        }

        .lado-hero {
            flex: 1;
            background: linear-gradient(160deg, #111 0%, #1d1d1d 60%, #ff1e00 230%);
            color: #fff;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .lado-hero::after {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255,30,0,0.35) 0%, transparent 70%);
            bottom: -120px;
            right: -100px;
        }
        .logo-hero {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1px;
            z-index: 1;
        }
        .logo-hero span { color: #ff1e00; }
        .hero-texto {
            z-index: 1;
        }
        .hero-texto h2 {
            font-size: 28px;
            line-height: 1.3;
            margin-bottom: 12px;
        }
        .hero-texto p {
            font-size: 14px;
            color: #ccc;
            line-height: 1.6;
        }

        .lado-forms {
            flex: 1;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .tabs {
            display: flex;
            background: #f0f2f5;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 32px;
        }
        .tab-btn {
            flex: 1;
            padding: 11px;
            border: none;
            background: transparent;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 700;
            color: #777;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn.ativo {
            background: #111;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        h2.titulo-form {
            font-size: 22px;
            margin-bottom: 6px;
            color: #111;
        }
        .subtitulo-form {
            font-size: 13px;
            color: #888;
            margin-bottom: 24px;
        }

        .campo-grupo {
            margin-bottom: 16px;
            text-align: left;
        }
        .campo-grupo label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 13px;
            color: #333;
        }
        .campo-grupo input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .campo-grupo input:focus {
            outline: none;
            border-color: #ff1e00;
        }

        .btn-submeter {
            border: none;
            cursor: pointer;
            padding: 13px;
            width: 100%;
            background-color: #111;
            color: white;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            margin-top: 6px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-submeter:hover {
            background-color: #ff1e00;
            transform: translateY(-1px);
        }

        .msg-erro {
            background: #ffebee;
            color: #c62828;
            padding: 11px 14px;
            border-radius: 7px;
            font-size: 13px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .seccao { display: none; }
        .seccao.ativa { display: block; }

        .dica-admin {
            margin-top: 18px;
            font-size: 11px;
            color: #bbb;
            text-align: center;
        }

        @media (max-width: 760px) {
            .painel { flex-direction: column; }
            .lado-hero { padding: 35px 30px; }
            .lado-forms { padding: 35px 30px; }
        }
    </style>
</head>
<body>

<div class="painel">

    <div class="lado-hero">
        <div class="logo-hero">HS <span>Hotels</span></div>
        <div class="hero-texto">
            <h2>A tua próxima estadia começa aqui.</h2>
            <p>Hotéis e viagens selecionados a pensar em ti. Cria a tua conta ou inicia sessão para reservares em segundos.</p>
        </div>
        <div></div>
    </div>

    <div class="lado-forms">
        <div class="tabs">
            <button type="button" class="tab-btn <?php echo !$mostrarRegisto ? 'ativo' : ''; ?>" id="tab-login" onclick="mostrarLogin()">Iniciar Sessão</button>
            <button type="button" class="tab-btn <?php echo $mostrarRegisto ? 'ativo' : ''; ?>" id="tab-registo" onclick="mostrarRegisto()">Criar Conta</button>
        </div>

        <div id="seccao-login" class="seccao <?php echo !$mostrarRegisto ? 'ativa' : ''; ?>">
            <h2 class="titulo-form">Bem-vindo!</h2>
            <p class="subtitulo-form">Inicia sessão para ver o nosso catálogo.</p>

            <?php if ($erroLogin): ?>
                <div class="msg-erro"><?php echo htmlspecialchars($erroLogin); ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="acao_login" value="1">
                <div class="campo-grupo">
                    <label>Email ou Nome de Utilizador</label>
                    <input type="text" name="login_email" required placeholder="Insira o seu email ou utilizador">
                </div>
                <div class="campo-grupo">
                    <label>Palavra-passe</label>
                    <input type="password" name="login_pass" required placeholder="Insira a sua password">
                </div>
                <button type="submit" class="btn-submeter">Entrar</button>
            </form>
            <p class="dica-admin">Administrador? Inicia sessão com as tuas credenciais de admin.</p>
        </div>

        <div id="seccao-registo" class="seccao <?php echo $mostrarRegisto ? 'ativa' : ''; ?>">
            <h2 class="titulo-form">Cria a tua conta</h2>
            <p class="subtitulo-form">Demora menos de um minuto.</p>

            <?php if ($erroRegisto): ?>
                <div class="msg-erro"><?php echo htmlspecialchars($erroRegisto); ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="acao_registo" value="1">
                <div class="campo-grupo">
                    <label>Nome Completo</label>
                    <input type="text" name="nome_completo" required placeholder="Insira o seu nome" value="<?php echo isset($nomeCompleto) ? htmlspecialchars($nomeCompleto) : ''; ?>">
                </div>
                <div class="campo-grupo">
                    <label>Email</label>
                    <input type="email" name="email_utilizador" required placeholder="Insira o seu email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                <div class="campo-grupo">
                    <label>Palavra-passe</label>
                    <input type="password" name="password_utilizador" required placeholder="Crie uma password">
                </div>
                <button type="submit" class="btn-submeter">Registar e Entrar</button>
            </form>
        </div>
    </div>
</div>

<script>
    function mostrarRegisto() {
        document.getElementById('seccao-login').classList.remove('ativa');
        document.getElementById('seccao-registo').classList.add('ativa');
        document.getElementById('tab-login').classList.remove('ativo');
        document.getElementById('tab-registo').classList.add('ativo');
    }
    function mostrarLogin() {
        document.getElementById('seccao-registo').classList.remove('ativa');
        document.getElementById('seccao-login').classList.add('ativa');
        document.getElementById('tab-registo').classList.remove('ativo');
        document.getElementById('tab-login').classList.add('ativo');
    }
</script>

</body>
</html>