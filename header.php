<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Produtos'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Sistema de Produtos</h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="products.php">Produtos</a></li>
                <li><a href="add_product.php">Novo Produto</a></li>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Registrar</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main> 