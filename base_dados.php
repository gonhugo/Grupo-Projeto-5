<?php
$databaseFile = 'hotel.db';

try {
    $pdo = new PDO("sqlite:" . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>A iniciar a criação da Base de Dados SQLite3...</h2>";

    $sqlUtilizadores = "
        CREATE TABLE IF NOT EXISTS Utilizadores (
            ID_Utilizador INTEGER PRIMARY KEY AUTOINCREMENT,
            Username TEXT NOT NULL UNIQUE,
            Nome_Completo TEXT NOT NULL,
            Email TEXT NOT NULL,
            Password_Hash TEXT NOT NULL,
            Data_Registo DATETIME DEFAULT CURRENT_TIMESTAMP,
            Ultimo_Acesso DATETIME
        );
    ";
    $pdo->exec($sqlUtilizadores);
    echo "<p style='color: green;'>✅ Tabela 'Utilizadores' criada ou já existente.</p>";

    
    $sqlReviews = "
        CREATE TABLE IF NOT EXISTS Reviews (
            ID_Review INTEGER PRIMARY KEY AUTOINCREMENT,
            ID_Utilizador INTEGER,
            ID_Hotel INTEGER,
            Comentario TEXT,
            Avaliacao INTEGER CHECK(Avaliacao BETWEEN 1 AND 5),
            Data_Publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (ID_Utilizador) REFERENCES Utilizadores(ID_Utilizador)
        );
    ";
    $pdo->exec($sqlReviews);
    echo "<p style='color: green;'>✅ Tabela 'Reviews' criada ou já existente.</p>";

    
    $sqlOfertas = "
        CREATE TABLE IF NOT EXISTS Ofertas (
            ID_Oferta INTEGER PRIMARY KEY AUTOINCREMENT,
            Titulo_Oferta TEXT NOT NULL,
            Descricao TEXT,
            Preco REAL NOT NULL,
            Estado_Disponibilidade INTEGER DEFAULT 1 -- 1 para Ativa, 0 para Inativa (SQLite não tem Booleano nativo)
        );
    ";
    $pdo->exec($sqlOfertas);
    echo "<p style='color: green;'>✅ Tabela 'Ofertas' criada ou já existente.</p>";

    $sqlPromocoes = "
        CREATE TABLE IF NOT EXISTS Promocoes (
            ID_Promocao INTEGER PRIMARY KEY AUTOINCREMENT,
            Nome_Campanha TEXT NOT NULL,
            Codigo_Cupao TEXT NOT NULL UNIQUE,
            Valor_Desconto INTEGER NOT NULL,
            Data_Inicio TEXT,
            Data_Fim TEXT
        );
    ";
    $pdo->exec($sqlPromocoes);
    echo "<p style='color: green;'>✅ Tabela 'Promocoes' criada ou já existente.</p>";

    echo "<h3>A base de dados foi configurada com sucesso no ficheiro: <strong>$databaseFile</strong></h3>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro ao criar a base de dados: " . $e->getMessage() . "</p>";
}
?>