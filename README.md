# 🛒 API REST - Gestão de E-commerce & Controle de Estoque

API desenvolvida em **PHP** utilizando **Programação Orientada a Objetos (POO)** e **PDO** para integração com banco de dados **MySQL**. O projeto simula operações reais de um e-commerce, incluindo gestão de produtos (CRUD), controle de status HTTP e validação de regras de negócio para baixa automatizada de estoque.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.x** (Paradigma Orientado a Objetos + PDO)
- **MySQL** (Banco de Dados Relacional)
- **JSON** (Formato de dados para comunicação via API)
- **Thunder Client** (Testes e Validação de Requisições HTTP)



## 🛠️ Arquitetura, Regras de Negócio e Refatoração

- **Padrão Controller & Roteamento:** O arquivo `index.php` atua como Controller/Roteador centralizado via `switch-case`, garantindo separação clara entre requisições HTTP e a camada de negócio (`Produto.php`).
- **Respostas e Status Codes HTTP Semânticos:** Uso de códigos nativos do protocolo HTTP (`200 OK`, `201 Created`, `400 Bad Request`, `404 Not Found`, `405 Method Not Allowed`, `500 Internal Error`).
- **Tratamento Global de Exceções (`try-catch`):** Captura de erros de banco com `PDOException` para evitar exibição de erros brutos no cliente, retornando um JSON estruturado de falha.
- **CRUD Completo & Validação de Estoque (PATCH):** Processamento de vendas com verificação em tempo real do estoque disponível. Retorna erro HTTP `400` em caso de tentativa de compra acima do limite existente.
- **Segurança:** Uso de *Prepared Statements* com PDO para prevenção contra *SQL Injection*.

---

## 📌 Rotas da API e Exemplos de JSON (Thunder Client)

### 1️⃣ **GET `/`** - Listar Produtos
- **Método:** `GET`
- **URL:** `http://localhost/loja_api/index.php`
- **Body:** Vazio
- **Status de Sucesso:** `200 OK`

---

### 2️⃣ **POST `/`** - Cadastrar Produto
- **Método:** `POST`
- **URL:** `http://localhost/loja_api/index.php`
- **Header:** `Content-Type: application/json`
- **Status de Sucesso:** `201 Created` | **Status de Erro:** `400 Bad Request`
- **Body (JSON):**
{
  "nome": "Teclado Mecânico",
  "preco": 250.00,
  "estoque": 15
}

---

### 3️⃣ **PUT `/`** - Atualizar Produto Completo
- **Método:** `PUT`
- **URL:** `http://localhost/loja_api/index.php`
- **Header:** `Content-Type: application/json`
- **Status de Sucesso:** `200 OK` | **Status de Erro:** `400 Bad Request`
- **Body (JSON):**
{
  "id": 1,
  "nome": "Teclado Mecânico RGB",
  "preco": 280.00,
  "estoque": 20
}

---

### 3️⃣ **DELETE `/`** - Deletar Produto
- **Método:** `DELETE`
- **URL:** `http://localhost/loja_api/index.php`
- **Header:** `Content-Type: application/json`
- **Status de Sucesso:** `200 OK` | **Status de Erro:** `400 Bad Request`
- **Body (JSON):**
{
  "id": 1
}

### 3️⃣ **PATCH `/`** - Processar Venda (Baixa no Estoque)
- **Método:** `PATCH`
- **URL:** `http://localhost/loja_api/index.php`
- **Header:** `Content-Type: application/json`
- **Status de Sucesso:** `200 OK` | **Status de Erro:** `404 Not Found`
- **Body (JSON):**
{
  "id": 1,
  "quantidade": 2
}