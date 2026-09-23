
# 🛒 API REST - Gestão de E-commerce & Controle de Estoque

API desenvolvida em **PHP** utilizando **Programação Orientada a Objetos (POO)** e **PDO** para integração com banco de dados **MySQL**. O projeto simula operações reais de um e-commerce, incluindo gestão de produtos (CRUD) e validação de regras de negócio para baixa automatizada de estoque.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.x** (Paradigma Orientado a Objetos + PDO)
- **MySQL** (Banco de Dados Relacional)
- **JSON** (Formato de dados para comunicação via API)
- **Thunder Client** (Testes de Requisições HTTP)

---

## 🛠️ Regras de Negócio e Funcionalidades

- **CRUD Completo de Produtos:** Cadastro, Listagem, Atualização e Remoção.
- **Validação de Estoque (PATCH):** Processamento de vendas com verificação em tempo real do estoque disponível. Retorna erro HTTP 400 em caso de tentativa de compra acima do limite.
- **Segurança:** Uso de *Prepared Statements* com PDO para prevenção contra *SQL Injection*.

---

## 📌 Rotas da API

| Método | Rota | Descrição |
|---|---|---|
| **GET** | `/` | Lista todos os produtos cadastrados |
| **POST** | `/` | Cadastra um novo produto (Requer `nome`, `preco`, `estoque`) |
| **PUT** | `/` | Atualiza dados de um produto existente (Requer `id`, `nome`, `preco`, `estoque`) |
| **DELETE** | `/` | Remove um produto pelo ID (Requer `id`) |
| **PATCH** | `/` | Simula uma venda e decrementa o estoque (Requer `id` e `quantidade`) |

---

## ⚙️ Como Executar o Projeto Localmente

1. Clone o repositório ou baixe os arquivos para a pasta do seu servidor local (ex: `C:\XAMPP\htdocs\`).
2. Inicie os serviços do **Apache** e **MySQL** no XAMPP.
3. Importe o arquivo `database.sql` no seu gerenciador MySQL (phpMyAdmin) para criar o banco de dados `loja_api` e a tabela `produtos`.
4. Acesse a API via `http://localhost/api-loja/` usando seu navegador ou cliente HTTP no VSCode (Thunder Client).