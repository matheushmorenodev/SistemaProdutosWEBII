<?php
// config.php
// Configurações de conexão ao banco de dados MySQL
$host = 'localhost';
$usuario = 'root';
$senha = 'root';
$banco = 'app_produtos';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die('Falha na conexão: ' . $conn->connect_error);
}

// Define charset como UTF-8
$conn->set_charset('utf8');
?> 