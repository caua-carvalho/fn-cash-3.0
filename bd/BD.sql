DROP DATABASE IF EXISTS fncash;
CREATE DATABASE fncash CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE fncash;

CREATE TABLE USUARIO (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Senha VARCHAR(255) NOT NULL,
    DataCadastro DATE NOT NULL,
    UltimoAcesso DATETIME
);

CREATE TABLE CONTA (
    ID_Conta INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Tipo ENUM('Corrente', 'Poupança', 'Cartão de Crédito', 'Investimento', 'Dinheiro', 'Outros') NOT NULL,
    Saldo DECIMAL(15 , 2 ) NOT NULL DEFAULT 0.00,
    Instituicao VARCHAR(100) NOT NULL,
    DataCriacao DATE NOT NULL,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Conta_Usuario FOREIGN KEY (ID_Usuario)
        REFERENCES USUARIO (ID_Usuario)
        ON DELETE CASCADE
);

CREATE TABLE CATEGORIA (
    ID_Categoria INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Tipo ENUM('Despesa', 'Receita') NOT NULL,
    Descricao TEXT,
    Ativa BOOLEAN NOT NULL DEFAULT TRUE,
    ID_CategoriaPai INT,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Categoria_CategoriaPai FOREIGN KEY (ID_CategoriaPai) 
        REFERENCES CATEGORIA(ID_Categoria) ON DELETE SET NULL,
    CONSTRAINT FK_Categoria_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
);

CREATE TABLE TRANSACAO (
    ID_Transacao INT AUTO_INCREMENT PRIMARY KEY,
    Titulo varchar(150) not null,
    Descricao VARCHAR(255) NOT NULL,
    Valor DECIMAL(15,2) NOT NULL,
    FormaPagamento ENUM('debito', 'credito', 'dinheiro', 'pix', 'boleto', 'transferencia') DEFAULT NULL,
    Data DATE NOT NULL,
    DataRegistro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Tipo ENUM('Despesa', 'Receita', 'Transferência') NOT NULL,
    Status ENUM('Pendente', 'Efetivada', 'Cancelada') NOT NULL DEFAULT 'Efetivada',
    ID_ContaRemetente INT NOT NULL,
    ID_Categoria INT,
    ID_ContaDestinataria INT,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Transacao_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE,
    CONSTRAINT FK_Transacao_Conta FOREIGN KEY (ID_ContaRemetente) 
        REFERENCES CONTA(ID_Conta) ON DELETE CASCADE,
    CONSTRAINT FK_Transacao_Categoria FOREIGN KEY (ID_Categoria) 
        REFERENCES CATEGORIA(ID_Categoria) ON DELETE SET NULL,
    CONSTRAINT FK_Transacao_ContaDestino FOREIGN KEY (ID_ContaDestinataria) 
        REFERENCES CONTA(ID_Conta) ON DELETE SET NULL
);

CREATE TABLE CONTA_RECORRENTE (
    ID_ContaRecorrente INT AUTO_INCREMENT PRIMARY KEY,
    Descricao VARCHAR(255) NOT NULL,
    Valor DECIMAL(15,2) NOT NULL,
    DataInicio DATE NOT NULL,
    Periodicidade ENUM('Diário', 'Semanal', 'Mensal', 'Anual') NOT NULL,
    DataFim DATE,
    Ativo BOOLEAN NOT NULL DEFAULT TRUE,
    ID_Categoria INT,
    ID_Conta INT NOT NULL,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_ContaRecorrente_Categoria FOREIGN KEY (ID_Categoria) 
        REFERENCES CATEGORIA(ID_Categoria) ON DELETE SET NULL,
    CONSTRAINT FK_ContaRecorrente_Conta FOREIGN KEY (ID_Conta) 
        REFERENCES CONTA(ID_Conta) ON DELETE CASCADE,
    CONSTRAINT FK_ContaRecorrente_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
);

CREATE TABLE ORCAMENTO (
    ID_Orcamento INT AUTO_INCREMENT PRIMARY KEY,
    Titulo varchar(255) not null,
    Valor DECIMAL(15,2) NOT NULL,
    Inicio date not null,
    Fim date not null,
    Descricao VARCHAR(255) default '-',
    Ativo boolean default true,
    ID_Categoria INT NOT NULL,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Orcamento_Categoria FOREIGN KEY (ID_Categoria) 
        REFERENCES CATEGORIA(ID_Categoria) ON DELETE CASCADE,
    CONSTRAINT FK_Orcamento_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
);

CREATE TABLE META_FINANCEIRA (
    ID_Meta INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Descricao TEXT,
    ValorAlvo DECIMAL(15,2) NOT NULL,
    ValorAtual DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    DataInicio DATE NOT NULL,
    DataLimite DATE,
    Status ENUM('Em andamento', 'Concluída', 'Cancelada') NOT NULL DEFAULT 'Em andamento',
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Meta_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
);

CREATE TABLE INVESTIMENTO (
    ID_Investimento INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Tipo ENUM('Ações', 'Fundos', 'Renda Fixa', 'Tesouro', 'Criptomoedas', 'Imóveis', 'Outros') NOT NULL,
    ValorInicial DECIMAL(15,2) NOT NULL,
    ValorAtual DECIMAL(15,2) NOT NULL,
    DataInicio DATE NOT NULL,
    Rendimento DECIMAL(10,4),
    ID_Conta INT NOT NULL,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Investimento_Conta FOREIGN KEY (ID_Conta) 
        REFERENCES CONTA(ID_Conta) ON DELETE CASCADE,
    CONSTRAINT FK_Investimento_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
);

-- Inserções na tabela USUARIO
INSERT INTO USUARIO (Nome, Email, Senha, DataCadastro) 
VALUES ('adm', 'adm@gmail.com', SHA2('123', 256), CURDATE());

-- Inserções na tabela CONTA
INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) 
VALUES ('Conta Corrente João', 'Corrente', 1000.00, 'Banco A', '2025-01-01', 1);

INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) 
VALUES ('Poupança Maria', 'Poupança', 5000.00, 'Banco B', '2025-01-01', 1);

-- Inserções na tabela CATEGORIA
INSERT INTO CATEGORIA (Nome, Tipo, Descricao, ID_Usuario) 
VALUES ('Alimentação', 'Despesa', 'Gastos com alimentação', 1);

INSERT INTO CATEGORIA (Nome, Tipo, Descricao, ID_Usuario) 
VALUES ('Salário', 'Receita', 'Recebimento de salário', 1);

-- Inserções na tabela TRANSACAO
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario) 
VALUES ('Compra Supermercado', 'Compra de alimentos', 200.00, 'debito', '2025-01-10', 'Despesa', 'Efetivada', 1, 1, 1);

INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario) 
VALUES ('Recebimento Salário', 'Salário mensal', 3000.00, 'credito', '2025-01-05', 'Receita', 'Efetivada', 1, 2, 1);

-- Inserções na tabela CONTA_RECORRENTE
INSERT INTO CONTA_RECORRENTE (Descricao, Valor, DataInicio, Periodicidade, ID_Categoria, ID_Conta, ID_Usuario) 
VALUES ('Assinatura Streaming', 50.00, '2025-01-01', 'Mensal', 1, 1, 1);

INSERT INTO CONTA_RECORRENTE (Descricao, Valor, DataInicio, Periodicidade, ID_Categoria, ID_Conta, ID_Usuario) 
VALUES ('Plano de Saúde', 300.00, '2025-01-01', 'Mensal', 1, 1, 1);

-- Inserções na tabela ORCAMENTO
INSERT INTO ORCAMENTO (Titulo, Valor, Inicio, Fim, ID_Categoria, ID_Usuario) 
VALUES ('Orçamento Alimentação', 1000.00, '2025-01-01', '2025-02-01', 1, 1);

INSERT INTO ORCAMENTO (Titulo, Valor, Inicio, Fim, ID_Categoria, ID_Usuario) 
VALUES ('Orçamento Transporte', 500.00, '2025-01-01', '2025-02-01', 1, 1);

-- Inserções na tabela META_FINANCEIRA
INSERT INTO META_FINANCEIRA (Nome, Descricao, ValorAlvo, DataInicio, ID_Usuario) 
VALUES ('Viagem Internacional', 'Economizar para viagem', 10000.00, '2025-01-01', 1);

INSERT INTO META_FINANCEIRA (Nome, Descricao, ValorAlvo, DataInicio, ID_Usuario) 
VALUES ('Comprar Carro', 'Economizar para um carro novo', 20000.00, '2025-01-01', 1);

-- Inserções na tabela INVESTIMENTO
INSERT INTO INVESTIMENTO (Nome, Tipo, ValorInicial, ValorAtual, DataInicio, ID_Conta, ID_Usuario) 
VALUES ('Ações Empresa X', 'Ações', 5000.00, 5500.00, '2025-01-10', 1, 1);

INSERT INTO INVESTIMENTO (Nome, Tipo, ValorInicial, ValorAtual, DataInicio, ID_Conta, ID_Usuario) 
VALUES ('Fundo Imobiliário Y', 'Fundos', 10000.00, 10500.00, '2025-01-10', 1, 1);

DROP TRIGGER IF EXISTS atualiza_saldo_apos_insert;
DELIMITER $$

CREATE TRIGGER atualiza_saldo_apos_insert
AFTER INSERT ON TRANSACAO
FOR EACH ROW
BEGIN
    DECLARE dataAtual DATE;

    SET dataAtual = CURDATE();

    -- Só entra se for Efetivada ou Pendente com Data <= hoje
    IF NEW.Status = 'Efetivada' OR (NEW.Status = 'Pendente' AND NEW.Data <= dataAtual) THEN

        -- Se for Receita (entrada de dinheiro)
        IF NEW.Tipo = 'Receita' THEN
            UPDATE CONTA
            SET Saldo = Saldo + NEW.Valor
            WHERE ID_Conta = NEW.ID_ContaRemetente;

        -- Se for Despesa (saída de dinheiro)
        ELSEIF NEW.Tipo = 'Despesa' THEN
            UPDATE CONTA
            SET Saldo = Saldo - NEW.Valor
            WHERE ID_Conta = NEW.ID_ContaRemetente;

        -- Se for Transferência
        ELSEIF NEW.Tipo = 'Transferência' THEN
            -- Sai valor da conta remetente
            UPDATE CONTA
            SET Saldo = Saldo - NEW.Valor
            WHERE ID_Conta = NEW.ID_ContaRemetente;

            -- Entra valor na conta destinatária
            UPDATE CONTA
            SET Saldo = Saldo + NEW.Valor
            WHERE ID_Conta = NEW.ID_ContaDestinataria;
        END IF;

    END IF;

END $$

DELIMITER ;

CREATE VIEW GastoPorCategoria AS
SELECT 
    ID_Categoria,
    ID_Usuario,
    SUM(CASE 
        WHEN Tipo = 'Despesa' AND Status = 'Efetivada' THEN Valor 
        ELSE 0 
    END) AS TotalGasto
FROM TRANSACAO
GROUP BY ID_Categoria, ID_Usuario;

SELECT 
    O.Titulo,
    O.Valor AS ValorOrcamento,
    COALESCE(G.TotalGasto, 0) AS GastoAtual,
    (O.Valor - COALESCE(G.TotalGasto, 0)) AS SaldoDisponivel
FROM ORCAMENTO O
LEFT JOIN GastoPorCategoria G ON O.ID_Categoria = G.ID_Categoria AND O.ID_Usuario = G.ID_Usuario
WHERE O.Ativo = TRUE;

DROP TRIGGER IF EXISTS atualiza_saldo_apos_update;
DELIMITER $$

CREATE TRIGGER atualiza_saldo_apos_update
AFTER UPDATE ON TRANSACAO
FOR EACH ROW
BEGIN
    DECLARE dataAtual DATE;
    SET dataAtual = CURDATE();

    -- Só processa se algum campo relevante mudou
    IF (OLD.Valor <> NEW.Valor OR OLD.Tipo <> NEW.Tipo OR OLD.Status <> NEW.Status OR OLD.Data <> NEW.Data OR OLD.ID_ContaRemetente <> NEW.ID_ContaRemetente OR OLD.ID_ContaDestinataria <> NEW.ID_ContaDestinataria) THEN

        -- PRIMEIRO: desfaz efeito da transação antiga (se era efetivada ou pendente e data <= hoje)
        IF OLD.Status = 'Efetivada' OR (OLD.Status = 'Pendente' AND OLD.Data <= dataAtual) THEN

            -- Receita
            IF OLD.Tipo = 'Receita' THEN
                UPDATE CONTA
                SET Saldo = Saldo - OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

            -- Despesa
            ELSEIF OLD.Tipo = 'Despesa' THEN
                UPDATE CONTA
                SET Saldo = Saldo + OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

            -- Transferência
            ELSEIF OLD.Tipo = 'Transferência' THEN
                -- volta o valor pra conta remetente
                UPDATE CONTA
                SET Saldo = Saldo + OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

                -- tira o valor da conta destinatária
                UPDATE CONTA
                SET Saldo = Saldo - OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaDestinataria;
            END IF;

        END IF;

        -- DEPOIS: aplica o efeito da nova transação (se for efetivada ou pendente com data <= hoje)
        IF NEW.Status = 'Efetivada' OR (NEW.Status = 'Pendente' AND NEW.Data <= dataAtual) THEN

            -- Receita
            IF NEW.Tipo = 'Receita' THEN
                UPDATE CONTA
                SET Saldo = Saldo + NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

            -- Despesa
            ELSEIF NEW.Tipo = 'Despesa' THEN
                UPDATE CONTA
                SET Saldo = Saldo - NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

            -- Transferência
            ELSEIF NEW.Tipo = 'Transferência' THEN
                -- tira valor da conta remetente
                UPDATE CONTA
                SET Saldo = Saldo - NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

                -- adiciona valor na conta destinatária
                UPDATE CONTA
                SET Saldo = Saldo + NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaDestinataria;
            END IF;

        END IF;

    END IF;

END $$

DELIMITER ;

SELECT SUM(Saldo) AS saldo_total FROM CONTA;

-- Gerado pelo Copilot

-- Inserções extras para testes de volume e visualização (dados realistas para 1 ano, usuário adm)

-- Contas adicionais
INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) VALUES
('Cartão Nubank João', 'Cartão de Crédito', 0.00, 'Nubank', '2025-01-01', 1),
('Dinheiro Carteira João', 'Dinheiro', 150.00, 'Físico', '2025-01-01', 1),
('Investimento XP João', 'Investimento', 20000.00, 'XP Investimentos', '2025-01-01', 1);

-- Categorias adicionais
INSERT INTO CATEGORIA (Nome, Tipo, Descricao, ID_Usuario) VALUES
('Transporte', 'Despesa', 'Gastos com transporte', 1),
('Lazer', 'Despesa', 'Cinema, bares, festas', 1),
('Educação', 'Despesa', 'Cursos, livros, faculdade', 1),
('Freelance', 'Receita', 'Trabalhos extras', 1),
('Dividendos', 'Receita', 'Rendimentos de investimentos', 1);

-- Orçamentos variados
INSERT INTO ORCAMENTO (Titulo, Valor, Inicio, Fim, ID_Categoria, ID_Usuario) VALUES
('Orçamento Lazer', 600.00, '2025-01-01', '2025-12-31', 5, 1),
('Orçamento Transporte', 1200.00, '2025-01-01', '2025-12-31', 4, 1),
('Orçamento Educação', 2000.00, '2025-01-01', '2025-12-31', 6, 1);

-- Metas financeiras
INSERT INTO META_FINANCEIRA (Nome, Descricao, ValorAlvo, DataInicio, DataLimite, ID_Usuario) VALUES
('Reserva de Emergência', 'Juntar 6 meses de despesas', 12000.00, '2025-01-01', '2025-12-31', 1),
('Comprar Notebook', 'Trocar notebook antigo', 6000.00, '2025-03-01', '2025-09-01', 1);

-- Investimentos
INSERT INTO INVESTIMENTO (Nome, Tipo, ValorInicial, ValorAtual, DataInicio, Rendimento, ID_Conta, ID_Usuario) VALUES
('Tesouro Selic', 'Tesouro', 5000.00, 5200.00, '2025-01-10', 0.0400, 3, 1),
('Criptomoedas', 'Criptomoedas', 2000.00, 2500.00, '2025-02-15', 0.2500, 3, 1);

-- Contas recorrentes (assinaturas, planos, etc)
INSERT INTO CONTA_RECORRENTE (Descricao, Valor, DataInicio, Periodicidade, DataFim, ID_Categoria, ID_Conta, ID_Usuario) VALUES
('Spotify', 19.90, '2025-01-01', 'Mensal', NULL, 5, 1, 1),
('Internet Fibra', 99.90, '2025-01-01', 'Mensal', NULL, 5, 1, 1),
('Curso Inglês', 250.00, '2025-01-01', 'Mensal', '2025-12-01', 6, 1, 1);

-- Transações de receitas (salário, freelance, dividendos)
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Salário Janeiro', 'Salário mensal', 3000.00, 'credito', '2025-01-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Fevereiro', 'Salário mensal', 3000.00, 'credito', '2025-02-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Março', 'Salário mensal', 3000.00, 'credito', '2025-03-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Abril', 'Salário mensal', 3000.00, 'credito', '2025-04-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Maio', 'Salário mensal', 3000.00, 'credito', '2025-05-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Junho', 'Salário mensal', 3000.00, 'credito', '2025-06-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Julho', 'Salário mensal', 3000.00, 'credito', '2025-07-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Agosto', 'Salário mensal', 3000.00, 'credito', '2025-08-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Setembro', 'Salário mensal', 3000.00, 'credito', '2025-09-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Outubro', 'Salário mensal', 3000.00, 'credito', '2025-10-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Novembro', 'Salário mensal', 3000.00, 'credito', '2025-11-05', 'Receita', 'Efetivada', 1, 2, 1),
('Salário Dezembro', 'Salário mensal', 3000.00, 'credito', '2025-12-05', 'Receita', 'Efetivada', 1, 2, 1),
('Freelance Site', 'Desenvolvimento de site', 1200.00, 'credito', '2025-03-20', 'Receita', 'Efetivada', 1, 6, 1),
('Freelance App', 'Desenvolvimento de app', 2000.00, 'credito', '2025-07-15', 'Receita', 'Efetivada', 1, 6, 1),
('Dividendos Abril', 'Dividendos ações', 150.00, 'credito', '2025-04-25', 'Receita', 'Efetivada', 3, 7, 1),
('Dividendos Outubro', 'Dividendos ações', 180.00, 'credito', '2025-10-25', 'Receita', 'Efetivada', 3, 7, 1);

-- Transações de despesas (alimentação, transporte, lazer, educação, recorrentes)
-- Alimentação (todo mês) - ID_Categoria = 1
-- Transporte (todo mês) - ID_Categoria = 3
-- Lazer - ID_Categoria = 4
-- Educação - ID_Categoria = 5

INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Supermercado Janeiro', 'Compras do mês', 600.00, 'debito', '2025-01-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Janeiro', 'Almoço fora', 120.00, 'debito', '2025-01-15', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Fevereiro', 'Compras do mês', 650.00, 'debito', '2025-02-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Fevereiro', 'Almoço fora', 110.00, 'debito', '2025-02-18', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Março', 'Compras do mês', 700.00, 'debito', '2025-03-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Março', 'Almoço fora', 130.00, 'debito', '2025-03-18', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Abril', 'Compras do mês', 680.00, 'debito', '2025-04-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Abril', 'Almoço fora', 140.00, 'debito', '2025-04-15', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Maio', 'Compras do mês', 720.00, 'debito', '2025-05-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Maio', 'Almoço fora', 125.00, 'debito', '2025-05-20', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Junho', 'Compras do mês', 690.00, 'debito', '2025-06-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Junho', 'Almoço fora', 135.00, 'debito', '2025-06-18', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Julho', 'Compras do mês', 710.00, 'debito', '2025-07-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Julho', 'Almoço fora', 120.00, 'debito', '2025-07-15', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Agosto', 'Compras do mês', 730.00, 'debito', '2025-08-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Agosto', 'Almoço fora', 140.00, 'debito', '2025-08-18', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Setembro', 'Compras do mês', 750.00, 'debito', '2025-09-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Setembro', 'Almoço fora', 130.00, 'debito', '2025-09-15', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Outubro', 'Compras do mês', 770.00, 'debito', '2025-10-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Outubro', 'Almoço fora', 125.00, 'debito', '2025-10-20', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Novembro', 'Compras do mês', 800.00, 'debito', '2025-11-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Novembro', 'Almoço fora', 135.00, 'debito', '2025-11-18', 'Despesa', 'Efetivada', 1, 1, 1),
('Supermercado Dezembro', 'Compras do mês', 820.00, 'debito', '2025-12-10', 'Despesa', 'Efetivada', 1, 1, 1),
('Restaurante Dezembro', 'Almoço fora', 140.00, 'debito', '2025-12-15', 'Despesa', 'Efetivada', 1, 1, 1);

-- Transporte (todo mês)
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Uber Janeiro', 'Corridas Uber', 120.00, 'debito', '2025-01-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Janeiro', 'Passagens', 80.00, 'dinheiro', '2025-01-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Fevereiro', 'Corridas Uber', 110.00, 'debito', '2025-02-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Fevereiro', 'Passagens', 85.00, 'dinheiro', '2025-02-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Março', 'Corridas Uber', 130.00, 'debito', '2025-03-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Março', 'Passagens', 90.00, 'dinheiro', '2025-03-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Abril', 'Corridas Uber', 125.00, 'debito', '2025-04-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Abril', 'Passagens', 95.00, 'dinheiro', '2025-04-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Maio', 'Corridas Uber', 140.00, 'debito', '2025-05-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Maio', 'Passagens', 100.00, 'dinheiro', '2025-05-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Junho', 'Corridas Uber', 135.00, 'debito', '2025-06-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Junho', 'Passagens', 105.00, 'dinheiro', '2025-06-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Julho', 'Corridas Uber', 120.00, 'debito', '2025-07-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Julho', 'Passagens', 110.00, 'dinheiro', '2025-07-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Agosto', 'Corridas Uber', 145.00, 'debito', '2025-08-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Agosto', 'Passagens', 115.00, 'dinheiro', '2025-08-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Setembro', 'Corridas Uber', 150.00, 'debito', '2025-09-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Setembro', 'Passagens', 120.00, 'dinheiro', '2025-09-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Outubro', 'Corridas Uber', 130.00, 'debito', '2025-10-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Outubro', 'Passagens', 125.00, 'dinheiro', '2025-10-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Novembro', 'Corridas Uber', 140.00, 'debito', '2025-11-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Novembro', 'Passagens', 130.00, 'dinheiro', '2025-11-25', 'Despesa', 'Efetivada', 2, 3, 1),
('Uber Dezembro', 'Corridas Uber', 155.00, 'debito', '2025-12-20', 'Despesa', 'Efetivada', 1, 3, 1),
('Ônibus Dezembro', 'Passagens', 135.00, 'dinheiro', '2025-12-25', 'Despesa', 'Efetivada', 2, 3, 1);

-- Lazer (eventos esporádicos)
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Cinema Janeiro', 'Cinema com amigos', 50.00, 'debito', '2025-01-12', 'Despesa', 'Efetivada', 1, 4, 1),
('Bar Fevereiro', 'Barzinho', 80.00, 'debito', '2025-02-17', 'Despesa', 'Efetivada', 1, 4, 1),
('Show Março', 'Show de banda', 120.00, 'debito', '2025-03-22', 'Despesa', 'Efetivada', 1, 4, 1),
('Viagem Julho', 'Viagem curta', 600.00, 'debito', '2025-07-10', 'Despesa', 'Efetivada', 1, 4, 1),
('Cinema Outubro', 'Cinema', 60.00, 'debito', '2025-10-14', 'Despesa', 'Efetivada', 1, 4, 1);

-- Educação (mensalidade, livros, cursos)
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Curso Inglês Janeiro', 'Mensalidade', 250.00, 'debito', '2025-01-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Livro Fevereiro', 'Livro técnico', 90.00, 'debito', '2025-02-12', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Fevereiro', 'Mensalidade', 250.00, 'debito', '2025-02-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Março', 'Mensalidade', 250.00, 'debito', '2025-03-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Abril', 'Mensalidade', 250.00, 'debito', '2025-04-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Maio', 'Mensalidade', 250.00, 'debito', '2025-05-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Junho', 'Mensalidade', 250.00, 'debito', '2025-06-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Julho', 'Mensalidade', 250.00, 'debito', '2025-07-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Agosto', 'Mensalidade', 250.00, 'debito', '2025-08-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Setembro', 'Mensalidade', 250.00, 'debito', '2025-09-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Outubro', 'Mensalidade', 250.00, 'debito', '2025-10-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Novembro', 'Mensalidade', 250.00, 'debito', '2025-11-08', 'Despesa', 'Efetivada', 1, 5, 1),
('Curso Inglês Dezembro', 'Mensalidade', 250.00, 'debito', '2025-12-08', 'Despesa', 'Efetivada', 1, 5, 1);

-- Transferências entre contas (exemplo: sacar dinheiro)
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario)
VALUES
('Saque Janeiro', 'Saque para carteira', 200.00, 'debito', '2025-01-15', 'Transferência', 'Efetivada', 1, NULL, 2, 1),
('Saque Julho', 'Saque para carteira', 300.00, 'debito', '2025-07-15', 'Transferência', 'Efetivada', 1, NULL, 2, 1);

-- Pagamento de cartão de crédito
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_ContaDestinataria, ID_Usuario)
VALUES
('Fatura Nubank Março', 'Pagamento fatura', 1200.00, 'debito', '2025-03-28', 'Transferência', 'Efetivada', 1, NULL, 3, 1),
('Fatura Nubank Setembro', 'Pagamento fatura', 1500.00, 'debito', '2025-09-28', 'Transferência', 'Efetivada', 1, NULL, 3, 1);

-- Gerado pelo Copilot
-- Transações extras para o mês 5 (maio) do usuário adm

INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario)
VALUES
('Padaria Maio', 'Café da manhã', 35.00, 'debito', '2025-05-03', 'Despesa', 'Efetivada', 1, 1, 1),
('Farmácia Maio', 'Remédio para dor de cabeça', 60.00, 'debito', '2025-05-06', 'Despesa', 'Efetivada', 1, 1, 1),
('Uber Extra Maio', 'Corrida para reunião', 45.00, 'debito', '2025-05-09', 'Despesa', 'Efetivada', 1, 3, 1),
('Cinema Maio', 'Cinema com família', 70.00, 'debito', '2025-05-13', 'Despesa', 'Efetivada', 1, 4, 1),
('Bar Maio', 'Happy hour', 90.00, 'debito', '2025-05-19', 'Despesa', 'Efetivada', 1, 4, 1),
('Livro Maio', 'Livro de finanças', 80.00, 'debito', '2025-05-21', 'Despesa', 'Efetivada', 1, 5, 1),
('Freelance Maio', 'Projeto rápido', 500.00, 'credito', '2025-05-22', 'Receita', 'Efetivada', 1, 6, 1),
('Mercado Extra Maio', 'Compras emergenciais', 120.00, 'debito', '2025-05-25', 'Despesa', 'Efetivada', 1, 1, 1),
('Ônibus Extra Maio', 'Viagem intermunicipal', 60.00, 'dinheiro', '2025-05-27', 'Despesa', 'Efetivada', 2, 3, 1),
('Restaurante Especial Maio', 'Jantar comemorativo', 180.00, 'debito', '2025-05-30', 'Despesa', 'Efetivada', 1, 1, 1);