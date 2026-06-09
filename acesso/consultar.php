<?php
require_once '../conexao/conexao.php';
require_once '../servicos/acesso_servico.php';

$resultado = null;
$erro = false;
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consultar'])) {

    $service = new AcessoService();

    $retorno = $service->consultarAluno(
        trim($_POST['matricula'])
    );

    if ($retorno['sucesso']) {
        $resultado = $retorno['dados'];
    } else {
        $erro = true;
        $mensagem_erro = $retorno['mensagem'];
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Acesso do Aluno</title>
</head>
<body>

<a href="../index.php">← Voltar ao Menu Principal</a>

<h1>Consultar Acesso do Aluno</h1>

<hr>

<form method="POST">
    <p>
        <strong>Matrícula:</strong><br>
        <input type="text" name="matricula" required placeholder="Digite a matrícula" size="30">
    </p>
    <p>
        <button type="submit" name="consultar">Consultar</button>
    </p>
</form>

<hr>

<?php if($resultado && !$erro): ?>
    
    <h3>Resultado da Consulta</h3>
    
    <table border="1" cellpadding="8">
        <tr bgcolor="#cccccc">
            <th>Campo</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td><strong>Nome</strong></td>
            <td><?php echo htmlspecialchars($resultado['nome'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Matrícula</strong></td>
            <td><?php echo htmlspecialchars($resultado['matricula'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Campus</strong></td>
            <td><?php echo htmlspecialchars($resultado['campus'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Curso</strong></td>
            <td><?php echo htmlspecialchars($resultado['curso'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Status Acadêmico</strong></td>
            <td><?php echo htmlspecialchars($resultado['status_aluno'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Possui Pendência</strong></td>
            <td><?php echo htmlspecialchars($resultado['possui_pendencia'] ?? ''); ?></td>
        </tr>
        <tr>
            <td><strong>Acesso</strong></td>
            <td>
                <?php if(isset($resultado['acesso_permitido']) && $resultado['acesso_permitido'] == 'LIBERADO'): ?>
                    ✅ LIBERADO
                <?php elseif(isset($resultado['acesso_permitido'])): ?>
                    ❌ BLOQUEADO
                <?php else: ?>
                    --
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><strong>Motivo</strong></td>
            <td><?php echo htmlspecialchars($resultado['motivo'] ?? ''); ?></td>
        </tr>
    </table>
    
    <br>
    <hr>

<?php elseif ($erro): ?>
    
    <p style="color: red; font-weight: bold;">
        ❌ <?php echo htmlspecialchars($mensagem_erro); ?>
    </p>
    
<?php endif; ?>

<hr>
<p><small>Sistema Acadêmico</small></p>

</body>
</html>