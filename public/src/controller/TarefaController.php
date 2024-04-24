<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class TarefaController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(Request $request, Response $response)
    {
        $stmt = $this->pdo->query('SELECT * FROM tarefa');
        $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response->withJson($tarefas);
    }

    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('INSERT INTO tarefa (titulo, descricao, inicio, fim, prioridade, usuario_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$data['titulo'], $data['descricao'], $data['inicio'], $data['fim'], $data['prioridade'], $data['usuario_id']]);
        return $response->withJson(['message' => 'Tarefa criada com sucesso']);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('UPDATE tarefa SET titulo = ?, descricao = ?, inicio = ?, fim = ?, prioridade = ?, usuario_id = ? WHERE id = ?');
        $stmt->execute([$data['titulo'], $data['descricao'], $data['inicio'], $data['fim'], $data['prioridade'], $data['usuario_id'], $args['id']]);
        return $response->withJson(['message' => 'Tarefa atualizada com sucesso']);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tarefa WHERE id = ?');
        $stmt->execute([$args['id']]);
        return $response->withJson(['message' => 'Tarefa deletada com sucesso']);
    }
}
