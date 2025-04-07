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
    Saldo DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    Instituicao VARCHAR(100) NOT NULL,
    DataCriacao DATE NOT NULL,
    ID_Usuario INT NOT NULL,
    CONSTRAINT FK_Conta_Usuario FOREIGN KEY (ID_Usuario) 
        REFERENCES USUARIO(ID_Usuario) ON DELETE CASCADE
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
    Data DATE NOT NULL,
    DataRegistro DATETIME NOT NULL,
    Tipo ENUM('Despesa', 'Receita', 'Transferência') NOT NULL,
    Status ENUM('Pendente', 'Efetivada', 'Cancelada') NOT NULL DEFAULT 'Efetivada',
    ID_Conta INT NOT NULL,
    ID_Categoria INT,
    ID_ContaDestino INT,
    CONSTRAINT FK_Transacao_Conta FOREIGN KEY (ID_Conta) 
        REFERENCES CONTA(ID_Conta) ON DELETE CASCADE,
    CONSTRAINT FK_Transacao_Categoria FOREIGN KEY (ID_Categoria) 
        REFERENCES CATEGORIA(ID_Categoria) ON DELETE SET NULL,
    CONSTRAINT FK_Transacao_ContaDestino FOREIGN KEY (ID_ContaDestino) 
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
    Valor DECIMAL(15,2) NOT NULL,
    MesAno DATE NOT NULL,
    Descricao VARCHAR(255),
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
