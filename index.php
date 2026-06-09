<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Acadêmico</title>
</head>
<body>

    <!-- Cabeçalho principal -->
    <header>
        <h1>📚 Sistema de Gestão Acadêmica</h1>
        <p><strong>Bem-vindo</strong> ao sistema integrado de alunos e solicitações</p>
        <hr>
    </header>

    <!-- Navegação principal - os cards viram itens de menu semântico -->
    <nav>
        <ul>
            <li>
                <strong>👨‍🎓 Cadastro de Alunos</strong><br>
                <small>Cadastrar novos alunos no sistema</small>
                <br><a href="alunos/cadastrar.php">Acessar →</a>
                <hr>
            </li>

            <li>
                <strong>📋 Listagem de Alunos</strong><br>
                <small>Consultar e gerenciar alunos cadastrados</small>
                <br><a href="alunos/listar.php">Acessar →</a>
                <hr>
            </li>

            <li>
                <strong>📝 Abertura de Solicitação</strong><br>
                <small>Abrir nova solicitação acadêmica</small>
                <br><a href="solicitacoes/abrir.php">Acessar →</a>
                <hr>
            </li>

            <li>
                <strong>🔄 Alteração de Status</strong><br>
                <small>Atualizar status das solicitações</small>
                <br><a href="solicitacoes/alterar_status.php">Acessar →</a>
                <hr>
            </li>

            <li>
                <strong>🔍 Consulta de Acesso do Aluno</strong><br>
                <small>Verificar status de acessos</small>
                <br><a href="acesso/consultar.php">Acessar →</a>
                <hr>
            </li>
        </ul>
    </nav>

    <!-- Rodapé informativo -->
    <footer>
        <hr>
        <p><small>Sistema Acadêmico</small></p>
    </footer>

</body>
</html>