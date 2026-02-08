-- Clientes: enderecos
CREATE TABLE IF NOT EXISTS enderecos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  titulo VARCHAR(80) NOT NULL,
  linha1 VARCHAR(180) NOT NULL,
  linha2 VARCHAR(120) DEFAULT NULL,
  cidade VARCHAR(120) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  cep VARCHAR(12) NOT NULL,
  principal TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_enderecos_user (user_id),
  CONSTRAINT fk_enderecos_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clientes: metodos de pagamento
CREATE TABLE IF NOT EXISTS metodos_pagamento (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  tipo ENUM('cartao','pix','boleto') NOT NULL,
  apelido VARCHAR(120) NOT NULL,
  masked VARCHAR(64) DEFAULT NULL,
  validade VARCHAR(7) DEFAULT NULL,
  principal TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pagto_user (user_id),
  CONSTRAINT fk_pagto_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  endereco_id INT NOT NULL,
  pagamento_id INT DEFAULT NULL,
  status ENUM('criado','pago','em_rota','entregue','cancelado') NOT NULL DEFAULT 'criado',
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
  frete DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_pedidos_user (user_id),
  INDEX idx_pedidos_status (status),
  CONSTRAINT fk_pedidos_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_pedidos_endereco FOREIGN KEY (endereco_id) REFERENCES enderecos(id),
  CONSTRAINT fk_pedidos_pagto FOREIGN KEY (pagamento_id) REFERENCES metodos_pagamento(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedido_itens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  produto_id INT DEFAULT NULL,
  nome_snapshot VARCHAR(180) NOT NULL,
  sku_snapshot VARCHAR(80) DEFAULT NULL,
  qtd INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL,
  total_linha DECIMAL(10,2) NOT NULL,
  INDEX idx_pedido_itens_pedido (pedido_id),
  CONSTRAINT fk_pedido_itens_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedido_eventos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  tipo ENUM('criado','pago','em_rota','entregue','cancelado','devolucao') NOT NULL,
  mensagem VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_eventos_pedido (pedido_id),
  CONSTRAINT fk_eventos_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Faturas / pagamentos
CREATE TABLE IF NOT EXISTS faturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  metodo_id INT DEFAULT NULL,
  valor DECIMAL(10,2) NOT NULL,
  status ENUM('pago','pendente','falhou') NOT NULL DEFAULT 'pendente',
  link_boleto VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  paid_at TIMESTAMP NULL,
  INDEX idx_faturas_pedido (pedido_id),
  CONSTRAINT fk_faturas_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
  CONSTRAINT fk_faturas_metodo FOREIGN KEY (metodo_id) REFERENCES metodos_pagamento(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notas fiscais
CREATE TABLE IF NOT EXISTS notas_fiscais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  numero VARCHAR(40) NOT NULL,
  serie VARCHAR(20) DEFAULT NULL,
  valor DECIMAL(10,2) NOT NULL,
  data_emissao DATE NOT NULL,
  link_download VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nf_pedido (pedido_id),
  CONSTRAINT fk_nf_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Suporte
CREATE TABLE IF NOT EXISTS tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  pedido_id INT DEFAULT NULL,
  assunto VARCHAR(180) NOT NULL,
  categoria VARCHAR(60) NOT NULL,
  status ENUM('aberto','em_atendimento','resolvido','fechado') NOT NULL DEFAULT 'aberto',
  prioridade ENUM('baixa','media','alta') NOT NULL DEFAULT 'media',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_tickets_user (user_id),
  INDEX idx_tickets_status (status),
  CONSTRAINT fk_tickets_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_tickets_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ticket_mensagens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_id INT NOT NULL,
  autor_id INT NOT NULL,
  mensagem TEXT NOT NULL,
  anexos_json JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_msg_ticket (ticket_id),
  CONSTRAINT fk_msg_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id),
  CONSTRAINT fk_msg_autor FOREIGN KEY (autor_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Consignado (caso não exista)
CREATE TABLE IF NOT EXISTS consignado_movimentacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parceiro_id INT NOT NULL,
  produto VARCHAR(180) NOT NULL,
  quantidade INT NOT NULL,
  tipo ENUM('transferencia','devolucao') NOT NULL,
  descricao VARCHAR(255) DEFAULT NULL,
  data_mov DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_consignado_parceiro (parceiro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS consignado_produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parceiro_id INT NOT NULL,
  produto VARCHAR(180) NOT NULL,
  sku VARCHAR(120) DEFAULT NULL,
  lote VARCHAR(80) DEFAULT NULL,
  nf VARCHAR(80) DEFAULT NULL,
  estoque INT NOT NULL DEFAULT 0,
  min INT NOT NULL DEFAULT 0,
  devolucao INT NOT NULL DEFAULT 0,
  vendido_mes INT NOT NULL DEFAULT 0,
  prazo_dev VARCHAR(80) DEFAULT NULL,
  UNIQUE KEY uq_parceiro_produto (parceiro_id, produto),
  INDEX idx_consignado_prod_parceiro (parceiro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
