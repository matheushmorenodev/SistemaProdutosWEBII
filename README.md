# Aplicação de Gerenciamento de Produtos

Este projeto é uma aplicação web simples em PHP (procedural) com MySQL, que permite registrar usuários, autenticar e realizar o CRUD de produtos com upload de imagem.
Projeto desenvolvido por Matheus Henrique Moreno e Pedro Henrique Galhardi
Link do YOUTUBE: https://youtu.be/ifn3AEvkUAc

## Requisitos

- PHP 7.4+
- Servidor web (Apache, Nginx, etc.)
- MySQL 5.7+
- Extensão **mysqli** habilitada

## Estrutura de Pastas

```
├── css/
│   └── style.css            # Estilos básicos
├── produtos/
│   └── imagens/             # Imagens enviadas pelos produtos (criada automaticamente)
├── add_product.php          # Criar produto
├── config.php               # Conexão com MySQL (ajustar credenciais)
├── delete_product.php       # Excluir produto
├── edit_product.php         # Editar produto
├── footer.php               # Rodapé comum
├── header.php               # Cabeçalho + menu
├── index.php                # Redireciona para login ou lista
├── login.php                # Login de usuário
├── logout.php               # Logout
├── products.php             # Lista/Pesquisa de produtos
├── register.php             # Registro de usuário
└── README.md
```

## Criação do Banco de Dados

Execute os comandos SQL abaixo no MySQL (por exemplo, via *phpMyAdmin* ou *mysql CLI*):

```sql
CREATE DATABASE app_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_produtos;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Configuração

1. Clone ou copie os arquivos para o diretório público do seu servidor (por exemplo, `htdocs` ou `www`).
2. Altere as credenciais do banco de dados em `config.php` (`$usuario`, `$senha`).
3. Certifique-se de que o servidor possua permissão de escrita na pasta `produtos/imagens` (ou deixe que o sistema crie a pasta automaticamente com `mkdir`).
4. Acesse `http://seu_servidor/index.php` e crie um usuário.

## Funcionalidades

- Registro e login de usuários com **password_hash** e **password_verify**.
- Manutenção da sessão de usuário via `$_SESSION`.
- CRUD de produtos (apenas para usuários logados).
- Upload de imagem com restrições de extensão (`jpg`, `jpeg`, `png`) e tamanho máximo de 2 MB.
- Listagem de produtos ordenada do mais novo para o mais antigo com pesquisa em tempo real por nome.
- Confirmação em JavaScript (`confirm()`) antes de excluir.
- Proteção mínima contra SQL Injection via *prepared statements* (mysqli).

---
