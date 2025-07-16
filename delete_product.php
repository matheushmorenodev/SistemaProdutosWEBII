<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    // Pega imagem para remover depois
    $stmt = $conn->prepare('SELECT imagem FROM produtos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($imagem);
    $stmt->fetch();
    $stmt->close();

    // Deleta produto
    $stmt = $conn->prepare('DELETE FROM produtos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // Remove arquivo de imagem
    if ($imagem) {
        @unlink('produtos/imagens/' . $imagem);
    }
}

header('Location: products.php');
exit; 