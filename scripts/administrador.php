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
            <a href="index.html" class="btn-adicionar">+ Adicionar Utilizador</a>

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
                    try {
                        $db = new PDO("sqlite:hshotels.db");
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $queryTabela = "CREATE TABLE IF NOT EXISTS utilizadores (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            username TEXT NOT NULL UNIQUE,
                            email TEXT NOT NULL,
                            password TEXT NOT NULL,
                            ultimo_acesso TEXT
                        )";
                        $db->exec($queryTabela);

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
                            echo "<tr><td colspan='3' style='text-align: center; color: #999; padding: 20px;'>Nenhum utilizador registado na Base de Dados.</td></tr>";
                        }

                    } catch (PDOException $e) {
                        echo "<tr><td colspan='3' style='color: red; font-weight: bold;'>Erro SQLite3: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="cartao-admin">
            <h2>Lista de Ofertas (Catálogo)</h2>
            <a href="inseriroferta.html" class="btn-adicionar">+ Adicionar Nova Oferta</a>

            <table>
                <thead>
                    <tr>
                        <th>Oferta / Descrição</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Hotel Praia Azul</strong>
                            <p>Localização fantástica em frente ao mar, inclui p...</p>
                        </td>
                        <td>85€</td>
                        <td>
                            <a href="#" class="link-remover">Remover</a>
                            <a href="#" class="link-editar">Editar</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Resort Serra Verde</strong>
                            <p>Desconexão total no meio da natureza com trilh...</p>
                        </td>
                        <td>130€</td>
                        <td>
                            <a href="#" class="link-remover">Remover</a>
                            <a href="#" class="link-editar">Editar</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>The Vannah</strong>
                            <p>65</p>
                        </td>
                        <td>58€</td>
                        <td>
                            <a href="#" class="link-remover">Remover</a>
                            <a href="#" class="link-editar">Editar</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>