<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao/conexao.php';
require_once '../servicos/aluno_servico.php';
require_once '../servicos/acesso_servico.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    // GET /api/api.php?action=listar_alunos
    case 'listar_alunos':
        $service = new AlunoService();
        $stmt    = $service->listar();
        $alunos  = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $alunos[] = $row;
        }
        echo json_encode(['sucesso' => true, 'dados' => $alunos]);
        break;

    // GET /api/api.php?action=consultar_acesso&matricula=2024001
    case 'consultar_acesso':
        $matricula = $_GET['matricula'] ?? '';

        if (empty($matricula)) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'mensagem' => 'Matrícula obrigatória.']);
            break;
        }

        $service   = new AcessoService();
        $resultado = $service->consultarAluno($matricula);
        echo json_encode($resultado);
        break;

    default:
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Action inválida ou não informada.']);
        break;
}