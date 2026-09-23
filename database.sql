
CREATE DATABASE IF NOT EXISTS loja_api;

USE loja_api;

 CREATE TABLE IF NOT EXISTS produtos (            
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,			-- -> BASE DO PROJETO
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL
); 

INSERT INTO produtos (nome, preco, estoque)
VALUES ("Vassoura", 50, 10);


