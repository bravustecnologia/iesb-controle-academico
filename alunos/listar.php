<?php
// alunos/listar.php
require_once '../conexao/conexao.php';

require_once '../servicos/aluno_servico.php';

$service = new AlunoService();

$alunos = $service->listar();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Alunos</title>
</head>
<body>

    <!-- Cabeçalho com navegação -->
    <header>
        <nav>
            <a href="../index.php">← Voltar ao Menu Principal</a>
        </nav>
        <h1>📋 Listagem de Alunos</h1>
        <hr>
    </header>

    <main>
        <!-- Tabela de alunos com formatação nativa -->
        <table border="1" cellpadding="8" cellspacing="0">
            <caption><strong>📌 Alunos Cadastrados no Sistema</strong></caption>
            
            <thead>
                <tr bgcolor="#f0f0f0">
                    <th><strong>ID</strong></th>
                    <th><strong>Matrícula</strong></th>
                    <th><strong>Nome Completo</strong></th>
                    <th><strong>Curso</strong></th>
                    <th><strong>E-mail</strong></th>
                    <th><strong>Status</strong></th>
                    <th><strong>Data Cadastro</strong></th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                $linha = 0;
                while($row = sqlsrv_fetch_array($alunos, SQLSRV_FETCH_ASSOC)): 
                    $linha++;
                    // Alterna cores de linha usando atributo nativo
                    $cor_fundo = ($linha % 2 == 0) ? '#f9f9f9' : '#ffffff';
                ?>
                    <tr bgcolor="<?php echo $cor_fundo; ?>">
                        <td align="center"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td align="center"><strong><?php echo htmlspecialchars($row['matricula']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['curso']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td align="center">
                            <?php 
                            $status = $row['status'];
                            if($status == 'ATIVO'): 
                            ?>
                                <strong>✅ ATIVO</strong>
                            <?php else: ?>
                                <strong>❌ INATIVO</strong>
                            <?php endif; ?>
                        </td>
                        <td align="center"><?php echo date('d/m/Y H:i', strtotime($row['data_cadastro'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            
            <tfoot>
                <tr bgcolor="#f0f0f0">
                    <td colspan="7" align="center">
                        <small>Total de registros: <?php echo $linha; ?></small>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Links rápidos -->
        <br>
        <p>

            ➕ <a href="cadastrar.php">Cadastrar novo aluno</a><br>
        </p>

    </main>

    <!-- Rodapé -->
    <footer>
        <hr>
        <p><small>Sistema Acadêmico</small></p>
        <p><small>Última atualização: <?php echo date('d/m/Y H:i:s'); ?></small></p>
    </footer>

</body>
</html>