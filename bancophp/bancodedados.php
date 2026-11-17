 para deixar salvo <!-- CREATE DATABASE IF NOT EXISTS gwmd_solucoes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gwmd_solucoes;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario  INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    cpf         CHAR(11)     NOT NULL UNIQUE,
    telefone    VARCHAR(15)  NOT NULL,
    senha_hash  VARCHAR(255) NOT NULL,
    criado_em   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS clientes (
    id_cliente       INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario       INT NULL,
    nome             VARCHAR(100) NOT NULL,
    data_nascimento  DATE         NOT NULL,
    cpf              CHAR(11)     NOT NULL UNIQUE,
    endereco         VARCHAR(100) NOT NULL,
    telefone         VARCHAR(15)  NOT NULL,
    email            VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE IF NOT EXISTS colaboradores (
    id_colaborador   INT AUTO_INCREMENT PRIMARY KEY,
    nome             VARCHAR(100) NOT NULL,
    data_nascimento  DATE         NOT NULL,
    cpf              CHAR(11)     NOT NULL UNIQUE,
    endereco         VARCHAR(100) NOT NULL,
    telefone         VARCHAR(15)  NOT NULL,
    email            VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS produtos (
    id_produto    INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto  VARCHAR(100)  NOT NULL,
    valor         DECIMAL(10,2) NOT NULL,
    tem_desconto  BOOLEAN       NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS descontos (
    codigo_desconto  VARCHAR(20)   PRIMARY KEY,
    valor_desconto   DECIMAL(10,2) NOT NULL,
    id_produto       INT           NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

CREATE TABLE IF NOT EXISTS assinaturas (
    id_assinatura  INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente     INT         NOT NULL,
    plataforma     VARCHAR(50) NOT NULL,
    status         ENUM('paga','em_uso','expirada','inativa') NOT NULL DEFAULT 'inativa',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

CREATE TABLE IF NOT EXISTS status_consultoria (
    id_consultoria  INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente      INT NOT NULL,
    id_produto      INT NOT NULL,
    status          ENUM('concluida','em_analise','pendente','urgente','cancelada') NOT NULL DEFAULT 'pendente',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

CREATE TABLE IF NOT EXISTS status_projeto (
    id_projeto      INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente      INT NOT NULL,
    id_produto      INT NOT NULL,
    id_consultoria  INT NOT NULL,
    status          ENUM('concluida','em_analise','pendente','urgente','cancelada') NOT NULL DEFAULT 'pendente',
    FOREIGN KEY (id_cliente)     REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_produto)     REFERENCES produtos(id_produto),
    FOREIGN KEY (id_consultoria) REFERENCES status_consultoria(id_consultoria)
);

CREATE TABLE IF NOT EXISTS mensagens_contato (
    id_mensagem  INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    telefone     VARCHAR(15)  NOT NULL,
    assunto      VARCHAR(30)  NOT NULL,
    mensagem     TEXT         NOT NULL,
    enviado_em   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
); -->