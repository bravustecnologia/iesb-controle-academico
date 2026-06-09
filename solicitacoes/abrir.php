<?php
// solicitacoes/abrir.php
require_once '../conexao/conexao.php';
require_once '../servicos/aluno_servico.php';
require_once '../servicos/tipo_solicitacao_servico.php';

$alunoService = new AlunoService();

$stmt_aluno = $alunoService->listarParaSelect();

$tipoSolicitacaoService = new TipoSolicitacaoService();

$stmt_tipo_solicitacao = $tipoSolicitacaoService->listar();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Solicitação</title>
</head>
<body>

    <!-- Cabeçalho com navegação -->
    <header>
        <nav>
            <a href="../index.php">← Voltar ao Menu Principal</a>
        </nav>
        <h1>📝 Abertura de Solicitação Acadêmica</h1>
        <hr>
    </header>

    <main>
        <form action="/IESB/salvar/salvar.php" method="POST">

            <!-- Campo oculto -->
            <input type="hidden" name="acao" value="abrir">

            <!-- Seção 1: Dados da Solicitação -->
            <fieldset>
                <legend><strong>📌 Dados da Solicitação</strong></legend>
                
                <p>
                    <label for="id_aluno"><strong>👨‍🎓 Aluno:*</strong></label><br>
                    <select id="id_aluno" name="id_aluno" required size="1" style="min-width: 300px;" require>
                        <option value="">-- Selecione um aluno --</option>
                        <?php
                        
                        if ($stmt_aluno) {
                            while ($row_aluno = sqlsrv_fetch_array($stmt_aluno, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value='{$row_aluno['id']}'>📘 {$row_aluno['nome']} (Matrícula: {$row_aluno['matricula']})</option>";
                            }
                            sqlsrv_free_stmt($stmt_aluno);
                        } else {
                            echo "<option value='' disabled>❌ Erro ao carregar alunos</option>";
                        }
                        ?>
                    </select>
                    <br>
                </p>

                <p>
                    <label for="id_tipo"><strong>📋 Tipo de Solicitação:*</strong></label><br>
                    <select id="id_tipo" name="id_tipo" required size="1" style="min-width: 300px;" require>
                        <option value="">-- Selecione um tipo de solicitação --</option>
                        <?php
                        
                        if ($stmt_tipo_solicitacao) {
                            while ($row_tipo_solicitacao = sqlsrv_fetch_array($stmt_tipo_solicitacao, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value='{$row_tipo_solicitacao['id']}'>📄 {$row_tipo_solicitacao['descricao']}</option>";
                            }
                            sqlsrv_free_stmt($stmt_tipo_solicitacao);
                        } else {
                            echo "<option value='' disabled>❌ Erro ao carregar solicitações</option>";
                        }
                        ?>
                    </select>
                    <br>
                </p>

                
            <!-- Valores hardcoded: cursos, campus e status fixos no HTML.
            Futuramente podem ser carregados dinamicamente do banco de dados. -->
                <p>
                    <label for="status"><strong>⚙️ Status Inicial:*</strong></label><br>
                    <select name="status" required size="1" require>
                        <option value="">-- Selecione o status --</option>
                        <option value="ABERTA">📌 ABERTA</option>
                        <option value="ANALISE">🔍 ANALISE</option>
                        <option value="FINALIZADA">✅ FINALIZADA</option>
                    </select>
                    <br>
                </p>
            </fieldset>

            <!-- Seção 2: Descrição Detalhada -->
            <fieldset>
                <legend><strong>✍️ Detalhamento da Solicitação</strong></legend>
                
                <p>
                    <label for="descricao"><strong>Descrição Completa:</strong></label><br>
                    <textarea id="descricao" name="descricao" rows="6" cols="60" required placeholder="Descreva detalhadamente a solicitação..."></textarea>
                    <br>
                </p>
            </fieldset>

            <!-- Botões de ação -->
            <p>
                <button type="submit">✓ Abrir Solicitação</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" onclick="window.location.href='../index.php'">✗ Cancelar</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="reset">⟳ Limpar Formulário</button>
            </p>

            <!-- Aviso importante -->
            <hr>

        </form>
    </main>

    <!-- Rodapé -->
    <footer>
        <hr>
        <p><small>Sistema Acadêmico</small></p>
        <p><small>Campos com * são obrigatórios</small></p>
    </footer>

</body>
</html>