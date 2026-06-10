<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - HS Hotels</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; color: #333; }
        .admin-container { max-width: 900px; margin: 0 auto; }
        .seccao-admin { background: white; padding: 25px; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h2 { border-bottom: 2px solid #ff1e00; padding-bottom: 10px; color: #222; margin-top: 0; }
        
        /* Estilos do Formulário (ADM1) */
        .campo-grupo { margin-bottom: 15px; }
        .campo-grupo label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .campo-grupo input, .campo-grupo textarea, .campo-grupo select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submeter { border: none; cursor: pointer; padding: 12px 20px; background-color: #ff1e00; color: white; border-radius: 4px; font-weight: bold; font-size: 15px; }
        
        /* Estilos da Tabela de Registos (USR1 / Ponto 8.c) */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background-color: #333; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .sem-dados { text-align: center; color: #777; padding: 20px; }
    </style>
</head>
<body>

<div class="admin-container">
    <h1 style="text-align: center; margin-bottom: 30px; color: #111;">Gestão Interna - HS Hotels</h1>

    <div class="seccao-admin">
        <h2>➕ Inserir Nova Oferta de Quarto</h2>
        <form action="novaoferta.php" method="POST">
            <div class="campo-grupo">
                <label>Título da Oferta / Nome do Quarto:</label>
                <input type="text" name="titulo_oferta" required placeholder="Ex: Suite Familiar Vista Mar">
            </div>

            <div class="campo-grupo">
                <label>Tipo de Alojamento:</label>
                <select name="tipo_quarto" required>
                    <option value="Standard">Standard</option>
                    <option value="Prestige">Prestige</option>
                    <option value="Presidential">Presidential</option>
                </select>
            </div>

            <div class="campo-grupo">
                <label>Preço por Noite (€):</label>
                <input type="number" name="preco_noite" step="0.01" required placeholder="Ex: 120.00">
            </div>

            <div class="campo-grupo">
                <label>Descrição Detalhada:</label>
                <textarea name="descricao_oferta" rows="4" required placeholder="Escreva aqui as regalias, tamanho do quarto, extras incluídos..."></textarea>
            </div>

            <button type="submit" class="btn-submeter">Publicar Oferta no Catálogo</button>
        </form>
    </div>

    <div class="seccao-admin">
        <h2>📋 Monitorização de Utilizadores e Últimos Acessos</h2>
        <p style="font-size: 13px; color: #666;">Dados carregados em tempo real a partir do servidor SQLite3 (<code>hshotels.db</code>).</p>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username / Nome</th>
                    <th>Email do Cliente</th>
                    <th>Data do Último Acesso</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Ligar à mesma base de dados SQLite3 criada no novoregistro.php (Ponto 6)
                    $db = new PDO("sqlite:hshotels.db");
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Executar a consulta para ler os dados guardados (Leitura - Ponto 7.a e 8.c)
                    $query = "SELECT id, username, email, ultimo_acesso FROM utilizadores ORDER BY id DESC";
                    $resultado = $db->query($query);

                    // Verificar se existem utilizadores na tabela
                    $linhas = $resultado->fetchAll(PDO::FETCH_ASSOC);

                    if (count($linhas) > 0) {
                        foreach ($linhas as $linha) {
                            echo "<tr>";
                            echo "<td><strong>#" . htmlspecialchars($linha['id']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($linha['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['ultimo_acesso'] ?? 'Sem registo') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='sem-dados'>Ainda não existem clientes registados na Base de Dados.</td></tr>";
                    }

                } catch (PDOException $e) {
                    echo "<tr><td colspan='4' style='color: red; font-weight: bold;'>Erro ao aceder ao SQLite3: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>