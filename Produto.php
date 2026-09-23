<?php

class Produto {
    private $conexao;

    // O construtor recebe a conexão do banco na hora de instanciar
    public function __construct($db) {
        $this->conexao = $db;
    }

    // Método para buscar os dados
    public function listar(){
        $query = "SELECT * FROM produtos";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        // Retorna os dados do banco como um Array associativo do PHP
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para cadastrar um novo produto
    public function cadastrar($nome, $preco, $estoque) {
        // A query de inserção. Note os dois pontos (:nome). Isso é pra segurança (evitar SQL Injection)
        $query = "INSERT INTO produtos (nome, preco, estoque) 
                  VALUES (:nome, :preco, :estoque)";
        
        $stmt = $this->conexao->prepare($query);
        
        // Aqui a gente substitui os ":nome" pelos valores reais que chegaram na função
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':estoque', $estoque);
        
        // Executa e retorna true (deu certo) ou false (deu erro)
        return $stmt->execute();
    }

    // Método para deletar um produto pelo ID
    public function deletar($id) {
        $query = "DELETE FROM produtos WHERE id = :id";
        
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Método para atualizar os dados de um produto existente
    public function atualizar($id, $nome, $preco, $estoque) {
        $query = "UPDATE produtos SET nome = :nome, preco = :preco, estoque = :estoque WHERE id = :id";
        
        $stmt = $this->conexao->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':estoque', $estoque);
        
        return $stmt->execute();
    }

    // Método para simular uma venda e baixar o stock
    public function vender($id, $quantidadeComprada) {
        // 1. Verificar o stock atual do produto
        $queryBusca = "SELECT estoque FROM produtos WHERE id = :id";
        $stmtBusca = $this->conexao->prepare($queryBusca);
        $stmtBusca->bindParam(':id', $id);
        $stmtBusca->execute();
        
        $produto = $stmtBusca->fetch(PDO::FETCH_ASSOC);
        
        if (!$produto) {
            return ["sucesso" => false, "mensagem" => "Produto não encontrado."];
        }
        
        // 2. Impedir a venda se não houver stock suficiente
        if ($produto['estoque'] < $quantidadeComprada) {
            return ["sucesso" => false, "mensagem" => "Estoque insuficiente. Estoque atual: " . $produto['estoque']];
        }
        
        // 3. Subtrair o stock e atualizar na base de dados
        $novoEstoque = $produto['estoque'] - $quantidadeComprada;
        $queryAtualiza = "UPDATE produtos SET estoque = :novoEstoque WHERE id = :id";
        $stmtAtualiza = $this->conexao->prepare($queryAtualiza);
        $stmtAtualiza->bindParam(':novoEstoque', $novoEstoque);
        $stmtAtualiza->bindParam(':id', $id);
        
        if ($stmtAtualiza->execute()) {
            return ["sucesso" => true, "mensagem" => "Venda processada com sucesso! Novo stock: " . $novoEstoque];
        } else {
            return ["sucesso" => false, "mensagem" => "Erro ao processar a venda na base de dados."];
        }
    }
}

?>