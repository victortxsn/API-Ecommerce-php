
<?php

// Avisa para quem estiver chamando a API que a resposta será no formato JSON
header("Content-Type: application/json"); 
require_once 'Produto.php';

// 1. Criando a conexão com o banco (Host, Nome do Banco, Usuário, Senha)
$db = new PDO("mysql:host=localhost;dbname=loja_api", "root", "");

// 2. Instanciando nosso objeto
$produto = new Produto($db);

// 3. Verificando qual é o tipo de requisição (GET é para leitura)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $resultado = $produto->listar();
    
    // json_encode transforma o Array do PHP em um JSON real
    echo json_encode($resultado);
}

// Se a requisição for do tipo POST (alguém querendo enviar dados)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Pega o "pacote" JSON que veio da internet e transforma em algo que o PHP entende
    $dados = json_decode(file_get_contents("php://input"));
    
    // Chama a função que criamos no Passo 1, passando os dados
    if ($produto->cadastrar($dados->nome, $dados->preco, $dados->estoque)) {
        // Devolve uma mensagem de sucesso
        echo json_encode(["mensagem" => "Produto cadastrado com sucesso!"]);
    } else {
        // Devolve uma mensagem de erro
        echo json_encode(["mensagem" => "Erro ao cadastrar produto."]);
    }
}

// Se a requisição for do tipo DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    $dados = json_decode(file_get_contents("php://input"));
    
    // Verifica se o ID foi enviado antes de tentar deletar
    if (isset($dados->id) && $produto->deletar($dados->id)) {
        echo json_encode(["mensagem" => "Produto deletado com sucesso!"]);
    } else {
        echo json_encode(["mensagem" => "Erro ao deletar produto ou ID não informado."]);
    }
}

// Se a requisição for do tipo PUT (Atualizar)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    $dados = json_decode(file_get_contents("php://input"));
    
    // Verifica se o ID foi enviado junto com os outros dados
    if (isset($dados->id) && $produto->atualizar($dados->id, $dados->nome, $dados->preco, $dados->estoque)) {
        echo json_encode(["mensagem" => "Produto atualizado com sucesso!"]);
    } else {
        echo json_encode(["mensagem" => "Erro ao atualizar produto."]);
    }
}

// Se a requisição for do tipo PATCH (Processar Venda)
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    
    $dados = json_decode(file_get_contents("php://input"));
    
    // Verifica se enviámos o ID e a quantidade a comprar
    if (isset($dados->id) && isset($dados->quantidade)) {
        $resultado = $produto->vender($dados->id, $dados->quantidade);
        
        // Se a venda falhou (ex: stock insuficiente), podemos enviar um código HTTP de erro (400 Bad Request)
        if (!$resultado['sucesso']) {
            http_response_code(400); 
        }
        
        echo json_encode($resultado);
    } else {
        http_response_code(400);
        echo json_encode(["mensagem" => "ID do produto ou quantidade não informados corretamente."]);
    }
}

/*
Implementar tratamento de exceções no index.php;
Index funcionando mais como uma espécie de controladora(arquitetura mvc);
Tratando pdos exceptions;
Devolvendo códigos de erros e sucessos p    ro cliente.

Alterações no código pra fazer
a pedido do guimarães*/

?>