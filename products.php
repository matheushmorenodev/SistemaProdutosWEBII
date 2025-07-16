<?php
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$result = $conn->query('SELECT * FROM produtos ORDER BY data_criacao DESC');

$title = 'Lista de Produtos';
include 'header.php';
?>

<h2>Produtos</h2>

<input type="text" id="search" placeholder="Pesquisar produto..." style="margin-bottom:15px; padding:8px; width:100%; max-width:400px;">

<table id="productTable">
    <thead>
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Preço (R$)</th>
            <th>Quantidade</th>
            <th>Descrição</th>
            <th>Data Criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php if ($row['imagem']): ?><img src="produtos/imagens/<?php echo htmlspecialchars($row['imagem']); ?>" class="product-img" alt="Imagem Produto"><?php endif; ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                <td><?php echo (int)$row['quantidade']; ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['data_criacao'])); ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Editar</a> |
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
// Filtro de pesquisa em tempo real
const searchInput = document.getElementById('search');
searchInput.addEventListener('keyup', function() {
    const filter = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('#productTable tbody tr');
    rows.forEach(r => {
        const nameCell = r.cells[1].textContent.toLowerCase();
        r.style.display = nameCell.includes(filter) ? '' : 'none';
    });
});
</script>

<?php include 'footer.php'; ?> 