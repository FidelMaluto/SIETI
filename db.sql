CREATE DATABASE IF NOT EXISTS inventario_ti_v2;
USE inventario_ti_v2;

-- TABELA USUÁRIOS
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  usuario VARCHAR(80) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  role ENUM('admin','tecnico') NOT NULL DEFAULT 'tecnico',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABELA EQUIPAMENTOS
CREATE TABLE IF NOT EXISTS equipamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  tipo VARCHAR(80) NOT NULL,
  marca VARCHAR(80),
  modelo VARCHAR(80),
  serial VARCHAR(120) UNIQUE,
  patrimonio VARCHAR(120) UNIQUE,
  status ENUM('estoque','em_uso','manutencao','baixado') DEFAULT 'estoque',
  localizacao VARCHAR(120),
  responsavel VARCHAR(120),
  contato VARCHAR(120),
  observacao TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABELA MOVIMENTAÇÕES
CREATE TABLE IF NOT EXISTS movimentacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipamento_id INT NOT NULL,
  tipo_mov ENUM('emprestimo','devolucao') NOT NULL,
  responsavel VARCHAR(120),
  observacao VARCHAR(255),
  data_mov TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id) ON DELETE CASCADE
);
