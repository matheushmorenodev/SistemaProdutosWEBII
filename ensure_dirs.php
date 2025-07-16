<?php
// ensure_dirs.php - cria pasta produtos/imagens caso não exista
$dir = 'produtos/imagens';
if (!is_dir($dir)) {
    if (mkdir($dir, 0755, true)) {
        echo "Diretório {$dir} criado." . PHP_EOL;
    } else {
        echo "Falha ao criar diretório {$dir}." . PHP_EOL;
    }
} else {
    echo "Diretório {$dir} já existe." . PHP_EOL;
}
?> 