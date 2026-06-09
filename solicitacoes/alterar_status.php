<?php
// solicitacoes/alterar_status.php
require_once '../conexao/conexao.php';

// Buscar solicitações
$sql = "
SELECT 
    A.id,
    A.status,
    B.nome,
    B.matricula,
    C.descricao
FROM solicitacoes A
JOIN alunos B ON A.id_aluno = B.id
JOIN tipos_solicitacoes C ON A.id_tipo = C.id
ORDER BY A.id DESC";
$solicitacoes = executarQuery($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Status</title>
</head>
<body>

<a href="../index.php">← Voltar ao Menu Principal</a>

<h1>Alterar Status de Solicitações</h1>

<hr>

<?php
// Conta solicitações por status
$sql_status = "SELECT 
    SUM(CASE WHEN status = 'ABERTA' THEN 1 ELSE 0 END) as abertas,
    SUM(CASE WHEN status = 'ANALISE' THEN 1 ELSE 0 END) as analise,
    SUM(CASE WHEN status = 'FINALIZADA' THEN 1 ELSE 0 END) as finalizadas
    FROM solicitacoes";
$resumo = executarQuery($sql_status);
$dados = sqlsrv_fetch_array($resumo, SQLSRV_FETCH_ASSOC);
?>

<p>
    <strong>Resumo:</strong><br>
    Abertas: <?php echo $dados['abertas']; ?> | 
    Análise: <?php echo $dados['analise']; ?> | 
    Finalizadas: <?php echo $dados['finalizadas']; ?>
</p>

<hr>

<table border="1" cellpadding="8">
    <tr bgcolor="#cccccc">
        <th>ID</th>
        <th>Aluno</th>
        <th>Solicitação</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>

    <?php 
    $tem_solicitacoes = false;
    while($row = sqlsrv_fetch_array($solicitacoes, SQLSRV_FETCH_ASSOC)): 
        $tem_solicitacoes = true;
    ?>
        <form action="/IESB/salvar/salvar.php" method="POST">
            <input type="hidden" name="acao" value="alterar_status">
            <input type="hidden" name="id_solicitacao" value="<?php echo $row['id']; ?>">
            
            <tr>
                <td align="center"><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nome']) . ' - ' . $row['matricula']; ?></td>
                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                <td align="center">
                    <strong>
                        <?php 
                        if($row['status'] == 'ABERTA') echo '📌 ABERTA';
                        elseif($row['status'] == 'ANALISE') echo '🔍 ANÁLISE';
                        elseif($row['status'] == 'FINALIZADA') echo '✅ FINALIZADA';
                        else echo $row['status'];
                        ?>
                    </strong>
                </td>
                <td align="center">
                    <select name="status">
                        <option value="ABERTA" <?php echo ($row['status'] == 'ABERTA') ? 'selected' : ''; ?>>ABERTA</option>
                        <option value="ANALISE" <?php echo ($row['status'] == 'ANALISE') ? 'selected' : ''; ?>>ANÁLISE</option>
                        <option value="FINALIZADA" <?php echo ($row['status'] == 'FINALIZADA') ? 'selected' : ''; ?>>FINALIZADA</option>
                    </select>
                    <br><br>
                    <button type="submit">Atualizar</button>
                </td>
            </tr>
        </form>
    <?php endwhile; ?>
    
    <?php if(!$tem_solicitacoes): ?>
        <tr>
            <td colspan="5" align="center">
                Nenhuma solicitação encontrada.
            </td>
        </tr>
    <?php endif; ?>
</table>

<br>

<p>
    <a href="abrir.php">+ Nova Solicitação</a> | 
    <a href="../index.php">Menu Principal</a>
</p>

<hr>
<p><small>Sistema Acadêmico</small></p>

</body>
</html>