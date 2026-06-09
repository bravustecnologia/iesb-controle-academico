<?php
require_once '../conexao/conexao.php';

$mensagem = '';
$tipo_mensagem = '';

// Verifica qual ação foi enviada pelo formulário
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

try {
    
    switch ($acao) {
        
        case 'cadastrar':
            $resultado = executarProcedure(
                'sp_criar_aluno',
                [
                    $_POST['matricula'],
                    $_POST['nome'],
                    $_POST['cpf'],
                    $_POST['curso'],
                    $_POST['campus'],
                    $_POST['email'],
                    $_POST['telefone'],
                    $_POST['status']
                ]
            );
            
            if ($resultado && isset($resultado['mensagem'])) {
                if (strpos($resultado['mensagem'], 'sucesso') !== false) {
                    $mensagem = '✅ ' . $resultado['mensagem'];
                    $tipo_mensagem = 'success';
                } else {
                    $mensagem = '❌ ' . $resultado['mensagem'];
                    $tipo_mensagem = 'error';
                }
            } else {
                $mensagem = 'Aluno cadastrado com sucesso!';
                $tipo_mensagem = 'success';
            }
            break;

        case 'abrir':
            $resultado = executarProcedure(
                'sp_criar_solicitacao',
                [
                    $_POST['id_aluno'],
                    $_POST['id_tipo'],
                    $_POST['status'],
                    $_POST['descricao']
                ]
            );

            
            if ($resultado && isset($resultado['mensagem'])) {
                if (strpos($resultado['mensagem'], 'sucesso') !== false) {
                    $mensagem = '✅ ' . $resultado['mensagem'];
                    $tipo_mensagem = 'success';
                } else {
                    $mensagem = '❌ ' . $resultado['mensagem'];
                    $tipo_mensagem = 'error';
                }
            } else {
                $mensagem = 'Solicitação cadastrada com sucesso!';
                $tipo_mensagem = 'success';
            }
            break;
        
        case 'alterar_status':
            $resultado = executarProcedure(
                'sp_alterar_status_solicitacao',
                [
                    $_POST['id_solicitacao'],
                    $_POST['status']
                ]
            );
            
            if ($resultado && isset($resultado['mensagem'])) {
                if (strpos($resultado['mensagem'], 'sucesso') !== false) {
                    $mensagem = '✅ ' . $resultado['mensagem'];
                    $tipo_mensagem = 'success';
                } else {
                    $mensagem =  $resultado['mensagem'];
                    $tipo_mensagem = 'error';
                }
            } else {
                $mensagem = 'Alteração realizada com sucesso!';
                $tipo_mensagem = 'success';
            }
            break;    

        default:
            throw new Exception("Ação inválida ou não informada.");
    }
    
} catch (Exception $e) {
    $mensagem = '❌ Erro: ' . $e->getMessage();
    $tipo_mensagem = 'error';
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processando Dados</title>
</head>
<body>

    <hr>
    <h1>Sistema Acadêmico</h1>
    <hr>

    <br><br>

    <!-- Mensagem centralizada -->
    <table width="80%" border="1" align="center">
        <tr>
            <td align="center">
                <br>
                <h2><?php echo ($tipo_mensagem == 'success') ? '✅' : '❌'; ?></h2>
                <h3><?php echo $mensagem; ?></h3>
                <br>
                <a href="../index.php">← Voltar ao Menu Principal</a>
                <br><br>
            </td>
        </tr>
    </table>

    <br><br>
    <hr>

</body>
</html>