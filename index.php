<?php
/**
 * API REST - Gerenciamento de Usuários
 * Autor: Júlio Pinheiro
 * 
 * Endpoint base: /usuarios
 * Obs: Estou usando o roteamento simplificado via path_info do servidor embutido.
 */

// Define o retorno padrão como JSON e codifica os acentos corretamente
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// Pega o método HTTP da requisição
$metodo = $_SERVER['REQUEST_METHOD'];
$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Valida se a requisição está tentando acessar o recurso correto
if (strpos($url_path, 'usuarios') === false) {
    http_response_code(404);
    echo json_encode(['erro' => 'Rota inválida. Utilize /usuarios']);
    exit;
}

// Captura o corpo da requisição (para POST e PUT)
$input_json = file_get_contents('php://input');
$dados_req = json_decode($input_json, true);

// Roteamento baseado no verbo HTTP
switch ($metodo) {
    case 'GET':
        // Lista todos os usuários cadastrados
        $stmt = $pdo->query("SELECT id, nome, email FROM usuarios ORDER BY id DESC");
        $lista_usuarios = $stmt->fetchAll();
        
        http_response_code(200);
        echo json_encode($lista_usuarios, JSON_UNESCAPED_UNICODE);
        break;

    case 'POST':
        // Valida os campos obrigatórios antes de tentar inserir
        if (empty($dados_req['nome']) || empty($dados_req['email'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Os campos nome e email são de preenchimento obrigatório.']);
            break;
        }

        try {
            // Insert com prepared statements
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
            $stmt->execute([$dados_req['nome'], $dados_req['email']]);
            
            $novo_id = $pdo->lastInsertId();

            // Retorna 201 Created e o Location, conforme as boas práticas do REST
            http_response_code(201);
            header("Location: /usuarios/" . $novo_id);
            
            echo json_encode([
                'id' => $novo_id, 
                'mensagem' => 'Usuário cadastrado com sucesso!'
            ]);
        } catch (PDOException $e) {
            // Trata erro de e-mail duplicado (UNIQUE constraint)
            http_response_code(409);
            echo json_encode(['erro' => 'Este e-mail já está cadastrado no sistema.']);
        }
        break;

    case 'PUT':
        // Para atualizar, precisamos do ID e dos novos dados
        $id_user = $dados_req['id'] ?? null;

        if (!$id_user || empty($dados_req['nome']) || empty($dados_req['email'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID, nome e email são necessários para a atualização.']);
            break;
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$dados_req['nome'], $dados_req['email'], $id_user]);

        // Se a query rodou, retorna 200
        http_response_code(200);
        echo json_encode(['mensagem' => 'Dados do usuário atualizados.']);
        break;

    case 'DELETE':
        $id_user = $dados_req['id'] ?? null;

        if (!$id_user) {
            http_response_code(400);
            echo json_encode(['erro' => 'Informe o ID do usuário a ser deletado.']);
            break;
        }

        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id_user]);

        // 204 No Content é o padrão REST para exclusões bem sucedidas
        http_response_code(204);
        break;

    default:
        // Se mandarem um método tipo PATCH ou OPTIONS sem tratamento
        http_response_code(405);
        echo json_encode(['erro' => 'Método HTTP não suportado por esta API.']);
        break;
}
?>