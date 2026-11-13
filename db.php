<?php
/**
 * Arquivo de conexão e inicialização do banco de dados.
 * Autor: Júlio Pinheiro
 * 
 * Optei por usar SQLite para simplificar o deploy da prova, 
 * assim não dependemos de subir um container MySQL ou XAMPP.
 */

// Define o caminho do arquivo do banco na mesma pasta do projeto
$db_file = __DIR__ . '/database.sqlite';

try {
    // Inicia a conexão PDO
    $pdo = new PDO('sqlite:' . $db_file);
    
    // Configura o PDO para lançar exceções em caso de erros (facilita o debug)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Cria a tabela base se for a primeira execução do projeto
    $sql_tabela = "CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE
    )";
    
    $pdo->exec($sql_tabela);

} catch (PDOException $e) {
    // Se der erro na conexão, devolve um JSON para o cliente em vez de quebrar a tela
    http_response_code(500);
    echo json_encode(['erro' => 'Falha interna ao conectar no banco de dados.']);
    exit;
}
?>