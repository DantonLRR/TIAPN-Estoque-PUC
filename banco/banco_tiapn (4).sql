-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/12/2025 às 19:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco_tiapn`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tipo_cliente` varchar(10) NOT NULL,
  `cpf` int(30) NOT NULL,
  `cnpj` int(30) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `tipo_endereco` varchar(255) NOT NULL,
  `data_cadastro` date NOT NULL,
  `data_atualizacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `tipo_cliente`, `cpf`, `cnpj`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `cep`, `tipo_endereco`, `data_cadastro`, `data_atualizacao`) VALUES
(1, 'Marcus', 'stmarcusandrade44@gmail.com', 'fisica', 111111111, 0, 'Rua Maria Cândida de Almeida', '144', '', 'Diamante (Barreiro)', 'Belo Horizonte', 'MG', '30660-308', 'residencial', '2025-11-29', '2025-11-29'),
(2, 'Marcus', 'stmarcusandrade44@gmail.com', 'fisica', 1111111, 0, 'Rua Maria Cândida de Almeida', '144', '', 'Diamante (Barreiro)', 'Belo Horizonte', 'MG', '30660-308', 'residencial', '2025-11-29', '2025-11-29'),
(3, 'Marcus', 'stmarcusandrade44@gmail.com', 'juridica', 0, 2147483647, 'Rua Maria Cândida de Almeida', '144', '', 'Diamante (Barreiro)', 'Belo Horizonte', 'MG', '30660-308', 'comercial', '2025-11-29', '2025-11-29'),
(4, 'Guilherme', 'ggr@gmail.com', 'fisica', 1345679089, 0, 'Rua Maria Cândida de Almeida', '144', '', 'Diamante (Barreiro)', 'Belo Horizonte', 'MG', '30660-308', 'residencial', '2025-11-29', '2025-11-29'),
(5, 'Guilherme', 'guilherme44@gmail.com', 'juridica', 0, 2147483647, 'Rua Vereador Orlando Pacheco', '222', '', 'Bairro das Indústrias I (Barreiro)', 'Belo Horizonte', 'MG', '30610-020', 'comercial', '2025-11-30', '2025-11-30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `nome_item` varchar(100) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `preco_custo` decimal(18,2) DEFAULT 0.00,
  `preco_venda` decimal(18,2) DEFAULT 0.00,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `nome_item`, `descricao`, `quantidade`, `preco_custo`, `preco_venda`, `data_criacao`, `data_atualizacao`) VALUES
(1, 'calça jeans preta', 'calça preta', 335, 35.00, 100.00, '2025-11-02 18:57:34', '2025-12-04 18:01:54'),
(2, 'calça jeans preta', 'calça preta', 10, 35.00, 100.00, '2025-11-02 18:57:47', '2025-11-02 18:57:47'),
(3, 'calça azul', 'calça azul', 665, 33.00, 66.00, '2025-11-02 18:58:11', '2025-12-04 18:03:51'),
(4, 'calça bege', 'calça bege', 555, 66.00, 120.00, '2025-11-02 18:58:36', '2025-12-04 17:50:00'),
(5, 'Boné', 'Boné aba reta', 0, 100.00, 140.00, '2025-11-14 19:52:22', '2025-12-04 18:04:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_estoque`
--

CREATE TABLE `log_estoque` (
  `id_log` int(11) NOT NULL,
  `id_item` int(11) DEFAULT NULL,
  `quantidade_anterior` int(11) DEFAULT NULL,
  `quantidade_nova` int(11) DEFAULT NULL,
  `diferenca` varchar(10) DEFAULT NULL,
  `tipo_movimentacao` varchar(50) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `data_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `log_estoque`
--

INSERT INTO `log_estoque` (`id_log`, `id_item`, `quantidade_anterior`, `quantidade_nova`, `diferenca`, `tipo_movimentacao`, `usuario`, `data_hora`) VALUES
(4, 3, 60, 65, '5', 'Entrada', 'desconhecido', '2025-12-04 18:01:49'),
(5, 1, 30, 335, '305', 'Entrada', 'desconhecido', '2025-12-04 18:01:54'),
(6, 3, 65, 665, '+600', 'Entrada', 'desconhecido', '2025-12-04 18:03:51'),
(7, 5, 100, 0, '-100', 'Saída', 'desconhecido', '2025-12-04 18:04:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento_estoque`
--

CREATE TABLE `orcamento_estoque` (
  `id` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `doc_cliente` varchar(20) DEFAULT NULL,
  `dta_hora_orcamento` datetime NOT NULL,
  `vendedor` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor_orcado` decimal(18,2) NOT NULL,
  `id_item` int(11) NOT NULL,
  `nome_item` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `orcamento_estoque`
--

INSERT INTO `orcamento_estoque` (`id`, `idcliente`, `cliente`, `doc_cliente`, `dta_hora_orcamento`, `vendedor`, `descricao`, `valor_orcado`, `id_item`, `nome_item`, `quantidade`, `status`) VALUES
(8, 0, 'teste', NULL, '2025-11-02 19:46:52', 'maria', '543543', 1233.00, 0, '', 0, 'Cancelado'),
(9, 0, 'danton', NULL, '2025-11-02 19:50:18', 'maria', 'sadas', 12312.00, 0, '', 0, 'Cancelado'),
(10, 0, 'danton', NULL, '2025-11-14 17:09:10', 'dantinho', 'teste', 1232131.00, 1, '', 10, 'Cancelado'),
(11, 0, 'danton', NULL, '2025-11-14 17:14:41', 'dantinho2', 'testttt', 231231.00, 4, 'calça bege', 24, 'Aprovado'),
(12, 0, 'Marcus', NULL, '2025-11-14 19:49:11', 'Fabio', 'Calça azul', 200.00, 3, 'calça azul', 2, 'Aprovado'),
(13, 0, 'Marcus', NULL, '2025-11-14 19:50:41', 'Fabio', 'vdgeeb', 200.00, 1, 'calça jeans preta', 7, 'Aprovado'),
(14, 0, 'leo', NULL, '2025-11-25 08:14:37', 'roney', 'venda de dois bones', 160.00, 5, 'Boné', 2, 'Aprovado'),
(15, 0, 'leo', NULL, '2025-11-28 21:37:22', 'roney', '.', 160.00, 5, 'Boné', 1, 'Pendente'),
(16, 0, '4', '1345679089', '2025-12-05 12:36:45', 'Marcus', '18x calças', 12111.00, 3, 'calça azul', 18, 'Pendente'),
(17, 0, '1', '111111111', '2025-12-05 12:48:56', 'Marcus', 'test', 232.00, 3, 'calça azul', 15, 'Pendente'),
(18, 4, 'Guilherme', '1345679089', '2025-12-05 12:53:30', 'Marcus', 'teste', 33.00, 4, 'calça bege', 9, 'Pendente'),
(19, 1, 'Marcus', '111111111', '2025-12-05 12:54:06', 'Marcus', 'teste', 333.00, 2, 'calça jeans preta', 8, 'Pendente'),
(20, 4, 'Guilherme', '1345679089', '2025-12-05 14:22:02', 'Marcus', 'teste', 240.00, 4, 'calça bege', 2, 'Pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('pix','boleto','card') NOT NULL,
  `payer_name` varchar(120) NOT NULL,
  `payer_email` varchar(160) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `external_ref` varchar(80) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `payments`
--

INSERT INTO `payments` (`id`, `plan_id`, `plan_name`, `billing_cycle`, `amount`, `method`, `payer_name`, `payer_email`, `status`, `external_ref`, `created_at`) VALUES
(1, 1, 'Básico', 'yearly', 499.00, 'pix', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'pending', 'BFDF229F081E', '2025-11-08 02:07:28'),
(2, 8, 'Trimestral', 'monthly', 249.00, 'pix', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'pending', '70395D508F3B', '2025-11-13 04:33:30'),
(3, 8, 'Trimestral', 'monthly', 249.00, 'boleto', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'pending', '925692B43F33', '2025-11-13 04:33:34'),
(4, 8, 'Trimestral', 'monthly', 249.00, 'pix', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'pending', 'D4EAE1E3F4B6', '2025-11-13 04:35:15'),
(5, 8, 'Trimestral', 'monthly', 249.00, 'boleto', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'pending', '212E2EC21A79', '2025-11-13 04:35:18'),
(6, 8, 'Trimestral', 'monthly', 249.00, 'card', 'Lucas Augusto Medeiros Ramos', 'diegotrakinao@gmail.com', 'approved', 'cs_test_a1qHAAQmH4KaMdm2AFUwVxMNFMq5bJvN3DsrIezQEmXh9UbFdCAH5YHaOs', '2025-11-28 18:13:24'),
(7, 7, 'Mensal', 'monthly', 89.00, 'card', 'Marcus', 'stmarcusandrade44@gmail.com', 'rejected', 'FD0764195543', '2025-11-30 20:44:03'),
(8, 8, 'Trimestral', 'monthly', 249.00, 'card', 'Marcus', 'stmarcusandrade44@gmail.com', 'rejected', '4B1FB6863A1B', '2025-11-30 21:24:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `yearly_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_users` int(11) DEFAULT NULL,
  `max_products` int(11) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `plans`
--

INSERT INTO `plans` (`id`, `name`, `description`, `monthly_price`, `yearly_price`, `max_users`, `max_products`, `features`, `is_active`, `created_at`) VALUES
(7, 'Mensal', 'Assinatura válida por 30 dias.', 89.00, 0.00, NULL, NULL, NULL, 1, '2025-11-10 00:27:48'),
(8, 'Trimestral', 'Assinatura válida por 3 meses.', 249.00, 0.00, NULL, NULL, NULL, 1, '2025-11-10 00:27:48'),
(9, 'Semestral', 'Assinatura válida por 6 meses.', 449.00, 0.00, NULL, NULL, NULL, 1, '2025-11-10 00:27:48'),
(10, 'Anual', 'Assinatura válida por 12 meses.', 799.00, 0.00, NULL, NULL, NULL, 1, '2025-11-10 00:27:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `tipo_usuario` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` datetime NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `tipo_usuario`, `senha`, `email`, `data_cadastro`, `data_atualizacao`, `status`) VALUES
(1, 'Marcus', 'MarcusV', 'gerente', 'M@rcusone55', 'marcus44@ac.com', '2025-11-02 19:55:32', '2025-11-27 01:56:08', 1),
(5, 'Vinicius', 'Vini', 'gerente', 'gucelo08', 'vnmz@gmail.com', '2025-11-21 18:30:40', '2025-11-21 18:30:40', 1),
(6, 'Andrade', 'addd', 'gerente', 'gucelo08', 'stmarcusandrade44@gmail.com', '2025-11-21 18:33:19', '2025-11-21 18:33:19', 1),
(7, 'Ryan', 'Terril', 'gerente_estoque', 'gucelo08', 'plenoR@gmail.com', '2025-11-21 18:47:15', '2025-11-21 18:47:15', 1),
(8, 'AAA', 'senai', 'vendedor', 'gucelo08', 'frg@hh.com', '2025-11-21 20:02:43', '2025-11-21 22:02:04', 1),
(9, 'Gleison', 'pucprof@gmail.com', 'vendedor', 'hugo', 'vnmz@gmail.com', '2025-11-21 21:56:30', '2025-11-21 21:56:30', 1),
(10, 'Roney', 'Roneyvon', 'vendedor', 'gucelo08', 'roney@gmail.com', '2025-11-25 12:12:38', '2025-11-27 02:26:48', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log_estoque`
--
ALTER TABLE `log_estoque`
  ADD PRIMARY KEY (`id_log`);

--
-- Índices de tabela `orcamento_estoque`
--
ALTER TABLE `orcamento_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `log_estoque`
--
ALTER TABLE `log_estoque`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `orcamento_estoque`
--
ALTER TABLE `orcamento_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
