-- Banco de Dados para o Sistema Listsy
-- Sistema de Listas Colaborativas

CREATE DATABASE IF NOT EXISTS listsy;
USE listsy;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Grupos
CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(40) NOT NULL,
    admin_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Membros dos Grupos
CREATE TABLE membros_grupo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grupo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_membro (grupo_id, usuario_id)
);

-- Tabela de Convites
CREATE TABLE convites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grupo_id INT NOT NULL,
    usuario_convidado_id INT NOT NULL,
    usuario_convidador_id INT NOT NULL,
    status ENUM('pendente', 'aceito', 'recusado') DEFAULT 'pendente',
    data_convite TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_convidado_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_convidador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_convite (grupo_id, usuario_convidado_id)
);

-- Tabela de Listas
CREATE TABLE listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    grupo_id INT NOT NULL,
    criador_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Itens das Listas
CREATE TABLE itens_lista (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lista_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    marcado BOOLEAN DEFAULT FALSE,
    criador_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_modificacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lista_id) REFERENCES listas(id) ON DELETE CASCADE,
    FOREIGN KEY (criador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Índices para melhor performance
CREATE INDEX idx_grupos_admin ON grupos(admin_id);
CREATE INDEX idx_membros_grupo ON membros_grupo(grupo_id);
CREATE INDEX idx_membros_usuario ON membros_grupo(usuario_id);
CREATE INDEX idx_convites_usuario ON convites(usuario_convidado_id);
CREATE INDEX idx_listas_grupo ON listas(grupo_id);
CREATE INDEX idx_itens_lista ON itens_lista(lista_id);

-- Inserção de dados de exemplo
INSERT INTO usuarios (username, senha) VALUES 
('JPaulo_Moura', '$2y$10$example_hash_1'),
('Eich_Rafael', '$2y$10$example_hash_2'),
('Gabriel', '$2y$10$example_hash_3'),
('GabiEich', '$2y$10$example_hash_4');

INSERT INTO grupos (nome, admin_id) VALUES 
('Família', 1),
('Pessoal da Faculdade', 2),
('Processo Seletivo Focus Consultoria', 1);

INSERT INTO membros_grupo (grupo_id, usuario_id) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4),
(2, 2), (2, 3),
(3, 1), (3, 2);

INSERT INTO listas (nome, grupo_id, criador_id) VALUES 
('Compras de Mercado', 1, 1),
('Afazeres da Casa', 1, 2),
('Móveis da Casa', 1, 1);

INSERT INTO itens_lista (lista_id, nome, marcado, criador_id) VALUES 
(1, 'Pão', FALSE, 1),
(1, 'Tomate', TRUE, 1),
(1, 'Presunto', TRUE, 2),
(1, 'Queijo', FALSE, 2),
(1, 'Arroz', TRUE, 1);