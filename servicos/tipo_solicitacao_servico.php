<?php

class TipoSolicitacaoService
{
    public function listar()
    {
        $sql = "
            SELECT
                id,
                descricao
            FROM tipos_solicitacoes
            ORDER BY descricao
        ";

        return executarQuery($sql);
    }
}