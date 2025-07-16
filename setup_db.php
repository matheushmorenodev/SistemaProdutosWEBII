<?php
// setup_db.php - cria banco e tabelas
$host = 'localhost';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Falha na conexão com MySQL: " . $conn->connect_error . PHP_EOL);
}

$sql = "CREATE DATABASE IF NOT EXISTS app_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Erro criando banco: " . $conn->error . PHP_EOL);
}

$conn->select_db('app_produtos');

$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
)";

$sqlProdutos = "CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sqlUsuarios)) {
    die("Erro criando tabela usuarios: " . $conn->error . PHP_EOL);
}
if (!$conn->query($sqlProdutos)) {
    die("Erro criando tabela produtos: " . $conn->error . PHP_EOL);
}

echo "Banco e tabelas criados com sucesso!" . PHP_EOL;
?> 