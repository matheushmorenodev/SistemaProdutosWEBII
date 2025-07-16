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
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    // Validações
    if (!$nome || !$email || !$senha || !$confirmar) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail inválido.';
    }

    if ($senha !== $confirmar) {
        $errors[] = 'As senhas não coincidem.';
    }

    if (strlen($senha) < 6) {
        $errors[] = 'A senha deve ter ao menos 6 caracteres.';
    }

    // Verifica se e-mail já existe
    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'E-mail já cadastrado.';
        }
        $stmt->close();
    }

    // Insere usuário
    if (!$errors) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)');
        $stmt->bind_param('sss', $nome, $email, $senha_hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $nome;
            header('Location: products.php');
            exit;
        } else {
            $errors[] = 'Erro ao registrar. Tente novamente.';
        }
        $stmt->close();
    }
}

$title = 'Registrar';
include 'header.php';
?>

<h2>Registrar</h2>

<?php if ($errors): ?>
    <div class="error">
        <?php foreach ($errors as $e) echo "<p>{$e}</p>"; ?>
    </div>
<?php endif; ?>

<form method="post" action="">
    <label>Nome</label>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>

    <label>E-mail</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

    <label>Senha</label>
    <input type="password" name="senha" required>

    <label>Confirmar Senha</label>
    <input type="password" name="confirmar_senha" required>

    <button type="submit">Registrar</button>
</form>

<p>Já possui conta? <a href="login.php">Faça login</a>.</p>

<?php include 'footer.php'; ?> 