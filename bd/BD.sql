DROP DATABASE IF EXISTS fncash;
CREATE DATABASE fncash;
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
    FormaPagamento ENUM('debito', 'credito', 'dinheiro') NOT NULL,
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
VALUES ('Conta Corrente João', 'Corrente', 1000.00, 'Banco A', CURDATE(), 1);

INSERT INTO CONTA (Nome, Tipo, Saldo, Instituicao, DataCriacao, ID_Usuario) 
VALUES ('Poupança Maria', 'Poupança', 5000.00, 'Banco B', CURDATE(), 1);

-- Inserções na tabela CATEGORIA
INSERT INTO CATEGORIA (Nome, Tipo, Descricao, ID_Usuario) 
VALUES ('Alimentação', 'Despesa', 'Gastos com alimentação', 1);

INSERT INTO CATEGORIA (Nome, Tipo, Descricao, ID_Usuario) 
VALUES ('Salário', 'Receita', 'Recebimento de salário', 1);

-- Inserções na tabela TRANSACAO
INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario) 
VALUES ('Compra Supermercado', 'Compra de alimentos', 200.00, 'debito', CURDATE(), 'Despesa', 'Efetivada', 1, 1, 1);

INSERT INTO TRANSACAO (Titulo, Descricao, Valor, FormaPagamento, Data, Tipo, Status, ID_ContaRemetente, ID_Categoria, ID_Usuario) 
VALUES ('Recebimento Salário', 'Salário mensal', 3000.00, 'credito', CURDATE(), 'Receita', 'Efetivada', 1, 2, 1);

-- Inserções na tabela CONTA_RECORRENTE
INSERT INTO CONTA_RECORRENTE (Descricao, Valor, DataInicio, Periodicidade, ID_Categoria, ID_Conta, ID_Usuario) 
VALUES ('Assinatura Streaming', 50.00, CURDATE(), 'Mensal', 1, 1, 1);

INSERT INTO CONTA_RECORRENTE (Descricao, Valor, DataInicio, Periodicidade, ID_Categoria, ID_Conta, ID_Usuario) 
VALUES ('Plano de Saúde', 300.00, CURDATE(), 'Mensal', 1, 1, 1);

-- Inserções na tabela ORCAMENTO
INSERT INTO ORCAMENTO (Titulo, Valor, Inicio, Fim, ID_Categoria, ID_Usuario) 
VALUES ('Orçamento Alimentação', 1000.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 1, 1);

INSERT INTO ORCAMENTO (Titulo, Valor, Inicio, Fim, ID_Categoria, ID_Usuario) 
VALUES ('Orçamento Transporte', 500.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 1, 1);

-- Inserções na tabela META_FINANCEIRA
INSERT INTO META_FINANCEIRA (Nome, Descricao, ValorAlvo, DataInicio, ID_Usuario) 
VALUES ('Viagem Internacional', 'Economizar para viagem', 10000.00, CURDATE(), 1);

INSERT INTO META_FINANCEIRA (Nome, Descricao, ValorAlvo, DataInicio, ID_Usuario) 
VALUES ('Comprar Carro', 'Economizar para um carro novo', 20000.00, CURDATE(), 1);

-- Inserções na tabela INVESTIMENTO
INSERT INTO INVESTIMENTO (Nome, Tipo, ValorInicial, ValorAtual, DataInicio, ID_Conta, ID_Usuario) 
VALUES ('Ações Empresa X', 'Ações', 5000.00, 5500.00, CURDATE(), 1, 1);

INSERT INTO INVESTIMENTO (Nome, Tipo, ValorInicial, ValorAtual, DataInicio, ID_Conta, ID_Usuario) 
VALUES ('Fundo Imobiliário Y', 'Fundos', 10000.00, 10500.00, CURDATE(), 1, 1);
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


CREATE TRIGGER atualiza_saldo_apos_update
AFTER UPDATE ON TRANSACAO
FOR EACH ROW
BEGIN
    DECLARE dataAtual DATE;
    SET dataAtual = CURDATE();

    IF (OLD.Valor <> NEW.Valor 
        OR OLD.Tipo <> NEW.Tipo 
        OR OLD.Status <> NEW.Status 
        OR OLD.Data <> NEW.Data 
        OR OLD.ID_ContaRemetente <> NEW.ID_ContaRemetente 
        OR OLD.ID_ContaDestinataria <> NEW.ID_ContaDestinataria) THEN

        -- Desfaz efeito da transação antiga
        IF OLD.Status = 'Efetivada' OR (OLD.Status = 'Pendente' AND OLD.Data <= dataAtual) THEN

            IF OLD.Tipo = 'Receita' THEN
                UPDATE CONTA
                SET Saldo = Saldo - OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

            ELSEIF OLD.Tipo = 'Despesa' THEN
                UPDATE CONTA
                SET Saldo = Saldo + OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

            ELSEIF OLD.Tipo = 'Transferência' THEN
                UPDATE CONTA
                SET Saldo = Saldo + OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaRemetente;

                UPDATE CONTA
                SET Saldo = Saldo - OLD.Valor
                WHERE ID_Conta = OLD.ID_ContaDestinataria;

            END IF;
        END IF;

        -- Aplica efeito da nova transação
        IF NEW.Status = 'Efetivada' OR (NEW.Status = 'Pendente' AND NEW.Data <= dataAtual) THEN

            IF NEW.Tipo = 'Receita' THEN
                UPDATE CONTA
                SET Saldo = Saldo + NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

            ELSEIF NEW.Tipo = 'Despesa' THEN
                UPDATE CONTA
                SET Saldo = Saldo - NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

            ELSEIF NEW.Tipo = 'Transferência' THEN
                UPDATE CONTA
                SET Saldo = Saldo - NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaRemetente;

                UPDATE CONTA
                SET Saldo = Saldo + NEW.Valor
                WHERE ID_Conta = NEW.ID_ContaDestinataria;

            END IF;
        END IF;

    END IF;
END $$

DELIMITER ;
