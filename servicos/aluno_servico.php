<?php

class AlunoService
{
    public function listar()
    {
        $sql = "SELECT * FROM alunos ORDER BY data_cadastro DESC";

        return executarQuery($sql);
    }

    public function listarParaSelect()
    {
        $sql = "
            SELECT
                id,
                nome,
                matricula
            FROM alunos
            ORDER BY nome
        ";

        return executarQuery($sql);
    }
}