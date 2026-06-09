# Sistema Acadêmico PHP com SQL Server

## Objetivo

Desenvolver um módulo simplificado de controle de acesso acadêmico,
capaz de registrar e gerenciar as solicitações de acesso de alunos, com suporte
a regras de negócio como validação de status, controle de datas e rastreabilidade
de aprovações. O módulo será estruturado de forma a permitir futuras integrações
com sistemas de catraca, plataformas de BI e sistemas acadêmicos institucionais.

## Requisitos do Sistema

- SQL Server 2019 ou superior
- PHP 8.x
- Driver Microsoft SQLSRV
- Servidor web (Apache/Laragon/XAMPP)


## Estrutura do banco

Banco: teste_IESB

Tabelas:

1. alunos
   - Armazena os dados cadastrais dos alunos.
   - Controle de status ATIVO e INATIVO.

2. tipos_solicitacoes
   - Tabela de domínio utilizada para padronizar os tipos de solicitações acadêmicas.

3. solicitacoes
   - Registra as solicitações abertas pelos alunos.
   - Relacionada à tabela alunos e tipos_solicitacoes.

4. log_solicitacoes
   - Armazena o histórico de alterações de status das solicitações.
   - Permite auditoria das movimentações realizadas.

Relacionamentos:

alunos (1) → (N) solicitacoes

tipos_solicitacoes (1) → (N) solicitacoes

solicitacoes (1) → (N) log_solicitacoes



## Procedures criadas

sp_criar_aluno
Responsável por cadastrar novos alunos.
Realiza validação de matrícula e CPF duplicados.

sp_criar_solicitacao
Responsável pela abertura de solicitações acadêmicas.
Impede abertura para alunos com status INATIVO.

sp_alterar_status_solicitacao
Responsável pela alteração de status das solicitações.
Impede alterações em solicitações FINALIZADAS.
Registra automaticamente o histórico de alterações.

sp_consulta_acesso_aluno
Consulta a situação acadêmica do aluno.
Determina se o acesso está LIBERADO ou BLOQUEADO conforme as regras de negócio.

## Function Criada

fn_dias_em_aberto

Retorna a quantidade de dias em que uma solicitação permanece aberta,
considerando a data de abertura e a data de fechamento (quando existir).

## vw_status_academico

Consolida informações acadêmicas dos alunos:

- Matrícula
- Nome
- Curso
- Campus
- Status do aluno
- Possui pendências
- Acesso liberado

## Como executar

1. Executar o script database.sql no SQL Server.
2. Configurar os dados de conexão no arquivo conexao.php.
3. Publicar os arquivos PHP no servidor web.
4. Acessar o sistema pelo navegador.
5. Utilizar as telas disponíveis:
   - Cadastro de Alunos
   - Listagem de Alunos
   - Abertura de Solicitações
   - Alteração de Status
   - Consulta de Acesso

## Integração com (API, Catraca e BI):

Endpoints Disponíveis
Listar alunos

Retorna a relação de alunos cadastrados.

Exemplo:

GET /api/api.php?action=listar_alunos

Exemplo real:

https://bravustecnologia.com.br/IESB/api/api.php?action=listar_alunos

Resposta:

{
  "sucesso": true,
  "dados": [
    {
      "id": 1,
      "matricula": "123456",
      "nome": "fernando galvao",
      "cpf": "02135815314",
      "curso": "Engenharia de Software",
      "campus": "Sede Principal",
      "status": "ATIVO",
      "data_cadastro": "2026-06-09 15:04:35.620",
      "email": "si.fernandogalvao@gmail.com",
      "telefone": ""
    }
  ]
}

Exemplo real:

https://bravustecnologia.com.br/IESB/api/api.php?action=listar_alunos

Resposta:

{
  "sucesso": true,
  "dados": {
    "matricula": "123456",
    "nome": "fernando galvao",
    "curso": "Engenharia de Software",
    "campus": "Sede Principal",
    "status_aluno": "ATIVO",
    "acesso_permitido": "BLOQUEADO",
    "motivo": "Possui solicitação(ões) pendente(s)",
    "possui_pendencia": "SIM"
  }
}


## Melhorias futuras

As operações de inclusão e alteração de dados foram centralizadas no arquivo salvar.php,
que atua como ponto único de processamento dos formulários da aplicação.
Essa abordagem permitiu reduzir duplicação de código e manter a simplicidade da solução.

Como evolução futura, pretendo migrar gradualmente as regras de processamento atualmente
concentradas em salvar.php para classes de serviço específicas.

