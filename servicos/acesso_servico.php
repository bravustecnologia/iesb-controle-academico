<?php

class AcessoService
{
    public function consultarAluno($matricula)
    {
        $sql = "EXEC sp_consulta_acesso_aluno ?";
        $stmt = executarQuery($sql, array($matricula));

        if (!$stmt) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao executar a consulta.'
            ];
        }

        $resultado = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if (!$resultado) {
            return [
                'sucesso' => false,
                'mensagem' => 'Nenhum dado encontrado para esta matrícula.'
            ];
        }

        if (
            isset($resultado['resultado']) &&
            $resultado['resultado'] == 'Aluno não encontrado!'
        ) {
            return [
                'sucesso' => false,
                'mensagem' => 'Aluno não encontrado!'
            ];
        }

        return [
            'sucesso' => true,
            'dados' => $resultado
        ];
    }
}