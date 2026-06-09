<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
</head>
<body>

    <!-- Cabeçalho com navegação -->
    <header>
        <nav>
            <a href="../index.php">← Voltar ao Menu Principal</a>
        </nav>
        <h1>📝 Cadastro de Alunos</h1>
        <hr>
    </header>

    <!-- Formulário principal -->
    <main>
        <form action="/IESB/salvar/salvar.php" method="POST">

            <!-- Campo oculto -->
            <input type="hidden" name="acao" value="cadastrar">

            <!-- Campo: Nome Completo -->
            <fieldset>
                <legend><strong>📌 Dados Pessoais</strong></legend>
                
                <p>
                    <label for="nome"><strong>Nome Completo:*</strong></label><br>
                    <input type="text" id="nome" name="nome" required size="40">
                </p>

                <p>
                    <label for="matricula"><strong>Matrícula:*</strong></label><br>
                    <input type="text" id="matricula" name="matricula" required size="20">
                </p>

                <p>
                    <label for="cpf"><strong>CPF:*</strong></label><br>
                    <input type="text" id="cpf" name="cpf" required size="15" title="O CPF deve conter exatamente 11 números, sem pontos ou traço.">
                    <br><small>ex: 00000000000 (apenas números)</small>
                </p>

                <p>
                    <label for="email"><strong>E-mail:</strong></label><br>
                    <input type="email" id="email" name="email" size="40">
                </p>

                <p>
                    <label for="telefone"><strong>Telefone:</strong></label><br>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" size="20">
                </p>
            </fieldset>

            <!-- Valores hardcoded: cursos, campus e status fixos no HTML.
            Futuramente podem ser carregados dinamicamente do banco de dados. -->
            <fieldset>
                <legend><strong>🎓 Informações Acadêmicas</strong></legend>
                
                <p>
                    <label for="curso"><strong>Curso:*</strong></label><br>
                    <select name="curso" required size="1">
                        <option value="">Selecione</option>
                        <option>Engenharia de Software</option>
                        <option>Ciência da Computação</option>
                        <option>Administração</option>
                        <option>Direito</option>
                    </select>
                </p>

                <p>
                    <label for="campus"><strong>Campus:*</strong></label><br>
                    <select name="campus" required size="1">
                        <option value="">Selecione</option>
                        <option>Sede Principal</option>
                        <option>Sul</option>
                        <option>Norte</option>
                    </select>
                </p>

                <p>
                    <label for="status"><strong>Status do Aluno:*</strong></label><br>
                    <select name="status" required size="1">
                        <option value="">Selecione</option>
                        <option>ATIVO</option>
                        <option>INATIVO</option>
                    </select>
                </p>
            </fieldset>

            <!-- Botões de ação -->
            <p>
                <button type="submit">✓ Salvar Cadastro</button>
                &nbsp;&nbsp;&nbsp;
                <button type="button" onclick="window.location.href='../index.php'">✗ Cancelar</button>
            </p>

        </form>
    </main>

    <!-- Rodapé -->
    <footer>
        <hr>
        <p><small>Campos marcados com * são obrigatórios</small></p>
        <p><small>Sistema Acadêmico - Cadastro de Alunos</small></p>
    </footer>

</body>
</html>