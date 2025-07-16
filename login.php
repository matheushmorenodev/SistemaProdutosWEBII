<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: products.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $errors[] = 'Preencha todos os campos.';
    }

    if (!$errors) {
        $stmt = $conn->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nome, $senha_hash);
            $stmt->fetch();
            if (password_verify($senha, $senha_hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $nome;
                header('Location: products.php');
                exit;
            } else {
                $errors[] = 'Senha incorreta.';
            }
        } else {
            $errors[] = 'Usuário não encontrado.';
        }
        $stmt->close();
    }
}

$title = 'Login';
include 'header.php';
?>

<h2>Login</h2>

<?php if ($errors): ?>
    <div class="error">
        <?php foreach ($errors as $e) echo "<p>{$e}</p>"; ?>
    </div>
<?php endif; ?>

<form method="post" action="">
    <label>E-mail</label>
    <input type="email" name="email" required>

    <label>Senha</label>
    <input type="password" name="senha" required>

    <button type="submit">Entrar</button>
</form>

<p>Não possui conta? <a href="register.php">Registre-se</a>.</p>

<?php include 'footer.php'; ?> 