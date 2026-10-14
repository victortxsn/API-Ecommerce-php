<?php

// Avisa para quem estiver chamando a API que a resposta será no formato JSON
header("Content-Type: application/json");
require_once 'Produto.php';

try {

    // 1. Criando a conexão com o banco (Host, Nome do Banco, Usuário, Senha)
    // + Tratamento de erro ativo
    $db = new PDO("mysql:host=localhost;dbname=loja_api", "root", "");

    // Configura o PDO para lançar exceções caso 
    // ocorra algum erro no banco de dados (PDOException)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Instanciando nosso objeto e passando a conexão do banco de
    //    dados vinculada a ele
    $produto = new Produto($db);

    // 3.Identifica qual é o metodo de requisição HTTP utilizado
    $metodo = $_SERVER['REQUEST_METHOD'];

    // Pega o corpo do JSON da requisição(se tiver), e converte num
    // objeto que o PHP entende
    $dados = json_decode(file_get_contents("php://input"));

    // 4. Switch-case (Estrutura Controladora): 
    //    que funciona como um painel de roteamento de requisições
    switch ($metodo) {
        
        // --- REQUISIÇÃO GET: Utilizada para buscar/listar dados ---
        case 'GET':
            // Chama o método listar do nosso objeto Produto
            $resultado = $produto->listar();
            
            // Define o status HTTP 200 (OK - Requisição realizada com sucesso)
            http_response_code(200); 
            
            // json_encode transforma o Array do PHP em um JSON real
            // para devolver ao cliente
            echo json_encode($resultado);
            break;

        // --- REQUISIÇÃO POST: Utilizada para criar/cadastrar novos registros
        case 'POST':
            // Valida se os campos obrigatórios vieram no corpo 
            // do JSON antes de tentar cadastrar
            if (!empty($dados->nome) && !empty($dados->preco)
                                     && isset($dados->estoque)) {
                // Chama a função cadastrar passando os dados extraídos do JSON
                $produto->cadastrar($dados->nome, $dados->preco, $dados->estoque);
                
                // Define o status HTTP 201 (Created - Recurso criado com sucesso)
                http_response_code(201); 
                echo json_encode(["mensagem" => "Produto cadastrado com sucesso!"]);
            } else {
                // Se faltar algum campo, devolve status HTTP 400 
                // (Bad Request - Erro do cliente)
                http_response_code(400); 
                echo json_encode(["mensagem" => "Dados incompletos para 
                                   cadastro. Envie nome, preco e estoque."]);
            }
            break;

        // --- REQUISIÇÃO DELETE: Utilizada para remover um registro pelo ID
        case 'DELETE':
            // Verifica se o ID foi informado no JSON da requisição
            if (!empty($dados->id)) {
                // Executa a exclusão no banco de dados
                $produto->deletar($dados->id);
                
                // Status HTTP 200 (OK)
                http_response_code(200); 
                echo json_encode(["mensagem" => "Produto deletado com sucesso!"]);
            } else {
                // Retorna 400 se o ID não tiver sido enviado no pacote JSON
                http_response_code(400); 
                echo json_encode(["mensagem" => "ID não informado para exclusão."]);
            }
            break;

        // --- REQUISIÇÃO PUT: Utilizada para atualizar todos os dados de um registro
        case 'PUT':
            // Valida se o ID e todos os novos dados do produto foram passados
            if (!empty($dados->id) && !empty($dados->nome) && !empty($dados->preco) 
                                   && isset($dados->estoque)) {
                // Chama a atualização no banco de dados
                $produto->atualizar($dados->id, $dados->nome, $dados->preco, $dados->estoque);
                
                // Status HTTP 200 (OK)
                http_response_code(200); 
                echo json_encode(["mensagem" => "Produto atualizado com sucesso!"]);
            } else {
                // Retorna erro 400 se faltar algum parâmetro obrigatório
                http_response_code(400); 
                echo json_encode(["mensagem" => "Dados incompletos para atualização."]);
            }
            break;

        // REQUISIÇÃO PATCH: Utilizada para atualizações parciais 
        // (Ex: Processar uma Venda e dar baixa no estoque)
        case 'PATCH':
            // Verifica se enviamos o ID do produto e a quantidade desejada
            if (!empty($dados->id) && !empty($dados->quantidade)) {
                // Processa a regra de negócio da venda na classe Produto
                $resultado = $produto->vender($dados->id, $dados->quantidade);
                
                // O método vender() devolve o código HTTP correto 
                // (Ex: 200 para Sucesso, 400 para Estoque Insuficiente ou 404 para Não Encontrado)
                http_response_code($resultado['codigo']);
                
                // Retorna a mensagem correspondente em formato JSON
                echo json_encode(["mensagem" => $resultado['mensagem']]);
            } else {
                // Retorna 400 se faltar o ID ou a Quantidade no envio
                http_response_code(400); 
                echo json_encode(["mensagem" => "ID ou quantidade não informados para a venda."]);
            }
            break;

        // MÉTODO HTTP NÃO SUPORTADO 
        default:
            // Caso enviem uma requisição usando métodos não implementados (como OPTIONS ou HEAD)
            http_response_code(405); // 405 Method Not Allowed
            echo json_encode(["mensagem" => "Método HTTP não suportado pela API."]);
            break;
    }

} catch (Exception $e) {
    // 5. TRATAMENTO GLOBAL DE EXCEÇÕES: 
    // Se houver falha de conexão com o banco ou erro crítico de código, 
    // captura aqui e evita estourar erros brutos na tela
    http_response_code(500); // 500 Internal Server Error
    echo json_encode([
        "mensagem" => "Erro interno no servidor.",
        "detalhe" => $e->getMessage() // Exibe o motivo técnico do erro
    ]);
}

?>