<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    $quantidade = trim($_POST['quantidade'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    // Validações básicas
    if (!$nome || !$preco || !$quantidade) {
        $errors[] = 'Preencha todos os campos obrigatórios.';
    }

    // Validação de imagem
    $imagem_nome_final = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imagem = $_FILES['imagem'];
        $permitidas = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $permitidas)) {
            $errors[] = 'Formato de imagem inválido. Apenas JPG, JPEG ou PNG.';
        }
        if ($imagem['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Imagem acima de 2MB.';
        }
        if (!$errors) {
            if (!is_dir('produtos/imagens')) {
                mkdir('produtos/imagens', 0755, true);
            }
            $imagem_nome_final = uniqid() . '.' . $ext;
            move_uploaded_file($imagem['tmp_name'], 'produtos/imagens/' . $imagem_nome_final);
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare('INSERT INTO produtos (nome, preco, quantidade, descricao, imagem) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sdiss', $nome, $preco, $quantidade, $descricao, $imagem_nome_final);
        if ($stmt->execute()) {
            $sucesso = true;
        } else {
            $errors[] = 'Erro ao inserir produto.';
            // Se houver erro, remove imagem enviada
            if ($imagem_nome_final) {
                @unlink('produtos/imagens/' . $imagem_nome_final);
            }
        }
        $stmt->close();
    }
}

$title = 'Novo Produto';
include 'header.php';
?>

<h2>Novo Produto</h2>

<?php if ($sucesso): ?>
    <div class="success">Produto cadastrado com sucesso! <a href="products.php">Ver lista</a></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="error">
        <?php foreach ($errors as $e) echo "<p>{$e}</p>"; ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Nome*</label>
    <input type="text" name="nome" required>

    <label>Preço (ex: 19.90)*</label>
    <input type="number" name="preco" step="0.01" min="0" required>

    <label>Quantidade*</label>
    <input type="number" name="quantidade" min="0" required>

    <label>Descrição</label>
    <textarea name="descricao" rows="4"></textarea>

    <label>Imagem (JPG/PNG até 2MB)</label>
    <input type="file" name="imagem" accept="image/png, image/jpeg">

    <button type="submit">Cadastrar</button>
</form>

<?php include 'footer.php'; ?> 