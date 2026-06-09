-- =============================================
-- SISTEMA ACADÊMICO - SCRIPT COMPLETO
-- Banco de Dados: SQL SERVER
-- =============================================

-- =============================================
-- 0. DATABASE
-- =============================================

IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'teste_IESB')
BEGIN
    CREATE DATABASE teste_IESB;
END
GO

-- =============================================
-- 1. TABLES
-- =============================================

IF OBJECT_ID(N'dbo.alunos', N'U') IS NULL
BEGIN
CREATE TABLE alunos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    curso VARCHAR(50) NOT NULL,
    campus VARCHAR(50) NOT NULL,
    status VARCHAR(10) DEFAULT 'ATIVO' CHECK (status IN ('ATIVO', 'INATIVO')),
    data_cadastro DATETIME DEFAULT GETDATE(), --preenchido automaticamente, sem depender do usuário
    email VARCHAR(100) NULL,
    telefone VARCHAR(20) NULL
);
END
GO

IF OBJECT_ID(N'dbo.tipos_solicitacoes', N'U') IS NULL
BEGIN
CREATE TABLE tipos_solicitacoes (   --tabela criada para conter variações de tipos de solicitação
    id INT IDENTITY(1,1) PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL UNIQUE
);
END
GO

IF OBJECT_ID(N'dbo.tipos_solicitacoes', N'U') IS NOT NULL
BEGIN
    -- Verifica se a tabela está vazia para evitar duplicidade
    IF NOT EXISTS (SELECT 1 FROM dbo.tipos_solicitacoes)
    BEGIN
        INSERT INTO tipos_solicitacoes (descricao) VALUES
        ('matrícula'),
        ('trancamento'),
        ('cancelamento'),
        ('segunda_chamada'),
        ('revisao_nota'),
        ('declaracao'),
        ('historico'),
        ('transferencia'),
        ('aproveitamento'),
        ('diploma');
    END
END
GO

IF OBJECT_ID(N'dbo.solicitacoes', N'U') IS NULL
BEGIN
CREATE TABLE solicitacoes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_aluno INT NOT NULL,
    id_tipo INT NOT NULL,
    descricao TEXT,
    status VARCHAR(15) DEFAULT 'ABERTA' CHECK (status IN ('ABERTA', 'ANALISE', 'FINALIZADA')),
    data_abertura DATETIME DEFAULT GETDATE(),   --preenchido automaticamente, sem depender do usuário
    data_fechamento DATETIME NULL, --preenchido somente ao finalizar uma solicitação 'sp_alterar_status_solicitacao'
    
    FOREIGN KEY (id_aluno) REFERENCES alunos(id),
    FOREIGN KEY (id_tipo) REFERENCES tipos_solicitacoes(id)
);
END
GO

IF OBJECT_ID(N'dbo.log_solicitacoes', N'U') IS NULL
BEGIN
CREATE TABLE log_solicitacoes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_solicitacao INT NOT NULL,
    status_anterior VARCHAR(15),
    novo_status VARCHAR(15),
    data_alteracao DATETIME DEFAULT GETDATE(),
    
    FOREIGN KEY (id_solicitacao) REFERENCES solicitacoes(id)
);
END
GO

-- =============================================
-- 2. FUNCTION
-- =============================================
CREATE OR ALTER FUNCTION dbo.fn_dias_em_aberto(@id_solicitacao INT)
RETURNS INT
AS
BEGIN
    DECLARE @dias_aberto INT;
    
    SELECT @dias_aberto = DATEDIFF(DAY, data_abertura, ISNULL(data_fechamento, GETDATE()))
    FROM solicitacoes
    WHERE id = @id_solicitacao;
    
    RETURN ISNULL(@dias_aberto, 0);
END
GO

-- =============================================
-- 3. PROCEDURES
-- =============================================
CREATE OR ALTER PROCEDURE dbo.sp_criar_aluno
    @matricula VARCHAR(20),
    @nome VARCHAR(100),
    @cpf VARCHAR(14),
    @curso VARCHAR(50),
    @campus VARCHAR(50),
    @email VARCHAR(100),
    @telefone VARCHAR(20),
    @status VARCHAR(20)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION
        IF EXISTS (SELECT 1 FROM alunos WHERE matricula = @matricula)
        BEGIN
            RAISERROR('Matrícula já cadastrada!', 16, 1);
            RETURN;
        END
        
        IF EXISTS (SELECT 1 FROM alunos WHERE cpf = @cpf)
        BEGIN
            RAISERROR('CPF já cadastrado!', 16, 1);
            RETURN;
        END
        
        INSERT INTO alunos (matricula, nome, cpf, curso, campus, status, data_cadastro, email, telefone)
        VALUES (@matricula, @nome, @cpf, @curso, @campus, @status, GETDATE(), @email, @telefone);
        
        SELECT 'Aluno cadastrado com sucesso!' AS mensagem, 
               SCOPE_IDENTITY() AS id_aluno;

        COMMIT TRANSACTION
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION
        SELECT ERROR_MESSAGE() AS mensagem;
    END CATCH
END;
GO



CREATE OR ALTER PROCEDURE dbo.sp_criar_solicitacao  
    @id_aluno INT,  
    @id_tipo INT,  
    @status VARCHAR(15),
    @descricao TEXT  
AS  
BEGIN  
    BEGIN TRY  
        BEGIN TRANSACTION
        DECLARE @status_aluno VARCHAR(10);  
          
        SELECT @status_aluno = status FROM alunos WHERE id = @id_aluno;  
          
        IF @status_aluno IS NULL  
        BEGIN  
            RAISERROR('Aluno não encontrado!', 16, 1);  
            RETURN;  
        END  
          
        IF @status_aluno = 'INATIVO'  
        BEGIN  
            RAISERROR('Aluno INATIVO não pode abrir solicitações!', 16, 1);  
            RETURN;  
        END  
          
        INSERT INTO solicitacoes (id_aluno, id_tipo, descricao, status, data_abertura)  
        VALUES (@id_aluno, @id_tipo, @descricao, @status, GETDATE());  
          
        SELECT 'Solicitação aberta com sucesso!' AS mensagem,  
               SCOPE_IDENTITY() AS id_solicitacao;  
        COMMIT TRANSACTION
    END TRY  
    BEGIN CATCH  
        ROLLBACK TRANSACTION
        SELECT ERROR_MESSAGE() AS mensagem;  
    END CATCH  
END;
GO


CREATE OR ALTER PROCEDURE dbo.sp_alterar_status_solicitacao
    @id_solicitacao INT,
    @novo_status VARCHAR(15)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION
        DECLARE @status_atual VARCHAR(15);
        
        SELECT @status_atual = status
        FROM solicitacoes
        WHERE id = @id_solicitacao;
        
        IF @status_atual IS NULL
        BEGIN
            RAISERROR('Solicitação não encontrada!', 16, 1);
            RETURN;
        END
        
        IF @status_atual = 'FINALIZADA'
        BEGIN
            RAISERROR('Solicitação FINALIZADA não pode ser alterada!', 16, 1);
            RETURN;
        END
        
        IF @novo_status NOT IN ('ABERTA', 'ANALISE', 'FINALIZADA')
        BEGIN
            RAISERROR('Status inválido! Use: ABERTA, ANALISE ou FINALIZADA', 16, 1);
            RETURN;
        END
        
        UPDATE solicitacoes 
        SET status = @novo_status,
            data_fechamento = CASE WHEN @novo_status = 'FINALIZADA' THEN GETDATE() ELSE NULL END
        WHERE id = @id_solicitacao;

        COMMIT TRANSACTION
        
        SELECT 'Status atualizado com sucesso!' AS mensagem;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION
        SELECT ERROR_MESSAGE() AS mensagem;
    END CATCH
END;
GO


CREATE OR ALTER PROCEDURE dbo.sp_consulta_acesso_aluno
    @matricula VARCHAR(20)
AS
BEGIN
    DECLARE @id_aluno INT;
    DECLARE @status_aluno VARCHAR(10);
    DECLARE @solicitacao_pendente INT;
    
    SELECT @id_aluno = id, @status_aluno = status
    FROM alunos
    WHERE matricula = @matricula;
    
    IF @id_aluno IS NULL
    BEGIN
        SELECT 'Aluno não encontrado!' AS resultado;
        RETURN;
    END
    
    SELECT @solicitacao_pendente = COUNT(*)
    FROM solicitacoes
    WHERE id_aluno = @id_aluno AND status IN ('ABERTA', 'ANALISE');
    
    SELECT 
        a.matricula,
        a.nome,
        a.curso,
        a.campus,
        a.status AS status_aluno,
        CASE 
            WHEN a.status = 'ATIVO' AND (@solicitacao_pendente IS NULL OR @solicitacao_pendente = 0) 
            THEN 'LIBERADO' 
            ELSE 'BLOQUEADO' 
        END AS acesso_permitido,
        CASE 
            WHEN a.status = 'INATIVO' THEN 'Aluno está INATIVO'
            WHEN @solicitacao_pendente > 0 THEN 'Possui solicitação(ões) pendente(s)'
            ELSE 'Acesso liberado para o sistema'
        END AS motivo,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM solicitacoes s 
                WHERE s.id_aluno = a.id 
                AND s.status IN ('ABERTA', 'ANALISE')
            ) THEN 'SIM'
            ELSE 'NÃO'
        END AS possui_pendencia
    FROM alunos a
    WHERE a.id = @id_aluno;
END;
GO

-- =============================================
-- 4. VIEW
-- =============================================
CREATE OR ALTER VIEW dbo.vw_status_academico AS
SELECT 
    a.matricula,
    a.nome,
    a.curso,
    a.campus,
    a.status AS status_aluno,
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM solicitacoes s 
            WHERE s.id_aluno = a.id 
            AND s.status IN ('ABERTA', 'ANALISE')
        ) THEN 'SIM'
        ELSE 'NÃO'
    END AS possui_pendencia,
    CASE 
        WHEN a.status = 'ATIVO' AND NOT EXISTS (
            SELECT 1 
            FROM solicitacoes s 
            WHERE s.id_aluno = a.id 
            AND s.status IN ('ABERTA', 'ANALISE')
        ) THEN 'LIBERADO'
        ELSE 'BLOQUEADO'
    END AS acesso_liberado,
    (
        SELECT COUNT(*) 
        FROM solicitacoes s 
        WHERE s.id_aluno = a.id 
        AND s.status IN ('ABERTA', 'ANALISE')
    ) AS total_pendencias,
    (
        SELECT TOP 1 id 
        FROM solicitacoes s 
        WHERE s.id_aluno = a.id 
        ORDER BY s.data_abertura DESC
    ) AS ultima_solicitacao
FROM alunos a;
GO

-- =============================================
-- 5. TRIGGER
-- =============================================
CREATE OR ALTER TRIGGER trg_solicitacoes_insert_log
ON solicitacoes
AFTER INSERT, UPDATE
AS
BEGIN
    -- Para INSERT (quando não há registro em deleted)
    INSERT INTO log_solicitacoes (id_solicitacao, status_anterior, novo_status, data_alteracao)
    SELECT 
        i.id,
        NULL,  -- INSERT não tem status anterior
        i.status,
        GETDATE()
    FROM inserted i
    LEFT JOIN deleted d ON i.id = d.id
    WHERE d.id IS NULL;  -- Só INSERT
    
    -- Para UPDATE (quando há registro em deleted e mudou o status)
    INSERT INTO log_solicitacoes (id_solicitacao, status_anterior, novo_status, data_alteracao)
    SELECT 
        i.id,
        d.status,
        i.status,
        GETDATE()
    FROM inserted i
    INNER JOIN deleted d ON i.id = d.id
    WHERE i.status != d.status;  -- Só quando realmente mudou
END;
GO

-- =============================================
-- 6. INDEX
-- =============================================
CREATE INDEX IX_ALUNOS_MATRICULA
ON alunos(matricula);

CREATE INDEX IX_SOLICITACOES_ALUNO
ON solicitacoes(id_aluno);


-- =============================================
-- LIMPEZA
-- =============================================
-- DROP VIEW IF EXISTS vw_status_academico;
-- DROP PROCEDURE IF EXISTS sp_consulta_acesso_aluno;
-- DROP PROCEDURE IF EXISTS sp_alterar_status_solicitacao;
-- DROP PROCEDURE IF EXISTS sp_criar_solicitacao;
-- DROP PROCEDURE IF EXISTS sp_criar_aluno;
-- DROP FUNCTION IF EXISTS fn_dias_em_aberto;
-- DROP TABLE IF EXISTS log_solicitacoes;
-- DROP TABLE IF EXISTS solicitacoes;
-- DROP TABLE IF EXISTS tipos_solicitacoes;
-- DROP TABLE IF EXISTS alunos;
