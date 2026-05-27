<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servidor PHP - Dados Recebidos</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .sucesso-container {
            max-width: 450px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .titulo-sucesso {
            color: #4CAF50;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dado-item {
            margin-bottom: 15px;
            font-size: 16px;
            color: #333;
        }
        .dado-item strong {
            color: #000;
        }
        .btn-catalogo {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-catalogo:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="sucesso-container">
    <div class="titulo-sucesso">
        ✓ Servidor PHP: Dados Recebidos!
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar os dados enviados pelo formulário HTML
        $nome = isset($_POST['nome_completo']) ? htmlspecialchars($_POST['nome_completo']) : 'Não definido';
        $email = isset($_POST['email_utilizador']) ? htmlspecialchars($_POST['email_utilizador']) : 'Não definido';

        
        echo "<div class='dado-item'><strong>Nome recebido:</strong> " . $nome . "</div>";
        echo "<div class='dado-item'><strong>Email recebido:</strong> " . $email . "</div>";
    } else {
        echo "<div class='dado-item' style='color: red;'>Nenhum dado foi enviado via formulário.</div>";
    }
    ?>

    <a href="catalogo.html" class="btn-catalogo">Ir para o Catálogo</a>
</div>

</body>
</html>