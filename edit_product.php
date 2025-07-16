<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: products.php');
    exit;
}

// Busca produto existente
$stmt = $conn->prepare('SELECT * FROM produtos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produto) {
    header('Location: products.php');
    exit;
}

$errors = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    $quantidade = trim($_POST['quantidade'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem_nome_final = $produto['imagem'];

    if (!$nome || !$preco || !$quantidade) {
        $errors[] = 'Preencha todos os campos obrigatórios.';
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imagem = $_FILES['imagem'];
        $permitidas = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $permitidas)) {
            $errors[] = 'Formato de imagem inválido.';
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
            // Remove antiga
            if ($produto['imagem']) {
                @unlink('produtos/imagens/' . $produto['imagem']);
            }
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare('UPDATE produtos SET nome=?, preco=?, quantidade=?, descricao=?, imagem=? WHERE id=?');
        $stmt->bind_param('sdissi', $nome, $preco, $quantidade, $descricao, $imagem_nome_final, $id);
        if ($stmt->execute()) {
            $sucesso = true;
            // Atualiza dados exibidos
            $produto['nome'] = $nome;
            $produto['preco'] = $preco;
            $produto['quantidade'] = $quantidade;
            $produto['descricao'] = $descricao;
            $produto['imagem'] = $imagem_nome_final;
        } else {
            $errors[] = 'Erro ao atualizar produto.';
        }
        $stmt->close();
    }
}

$title = 'Editar Produto';
include 'header.php';
?>

<h2>Editar Produto</h2>

<?php if ($sucesso): ?>
    <div class="success">Produto atualizado com sucesso! <a href="products.php">Voltar à lista</a></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="error">
        <?php foreach ($errors as $e) echo "<p>{$e}</p>"; ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Nome*</label>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

    <label>Preço*</label>
    <input type="number" name="preco" step="0.01" min="0" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>

    <label>Quantidade*</label>
    <input type="number" name="quantidade" min="0" value="<?php echo (int)$produto['quantidade']; ?>" required>

    <label>Descrição</label>
    <textarea name="descricao" rows="4"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>

    <?php if ($produto['imagem']): ?>
        <p>Imagem atual:</p>
        <img src="produtos/imagens/<?php echo htmlspecialchars($produto['imagem']); ?>" class="product-img" alt="Imagem Produto">
    <?php endif; ?>

    <label>Nova Imagem (opcional)</label>
    <input type="file" name="imagem" accept="image/png, image/jpeg">

    <button type="submit">Salvar Alterações</button>
</form>

<?php include 'footer.php'; ?> 