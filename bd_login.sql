-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/10/2025 às 19:49
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema`
--
CREATE DATABASE IF NOT EXISTS `sistema` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `nivel` enum('user','admin') NOT NULL DEFAULT 'user',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `endereco`, `nivel`, `data_criacao`) VALUES
(1, 'Administrador', 'adm@gmail.com', 'adm0102', '(11) 9999-9999', 'Rua da Administração, 123', 'admin', '2025-10-08 17:49:00'),
(2, 'Usuário Teste', 'usuario@gmail.com', 'usuario0102', '(11) 8888-8888', 'Rua do Usuário, 456', 'user', '2025-10-08 17:49:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id` int(11) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `ano` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) DEFAULT 'img05.jpg',
  `destaque` tinyint(1) DEFAULT 0,
  `quilometragem` int(11) DEFAULT NULL,
  `combustivel` varchar(50) DEFAULT 'Gasolina',
  `cambio` varchar(50) DEFAULT 'Manual',
  `cor` varchar(50) DEFAULT NULL,
  `final_placa` int(11) DEFAULT NULL,
  `detalhes` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id`, `modelo`, `marca`, `ano`, `preco`, `descricao`, `imagem`, `destaque`, `quilometragem`, `combustivel`, `cambio`, `cor`, `final_placa`, `detalhes`, `data_cadastro`, `ativo`) VALUES
(1, 'Fusca 1300 L', 'Volkswagen', 1975, 45000.00, 'Fusca 1300 L em estado de concours! Carro totalmente original, com motor 1.300cc revisado, documentação em dia e histórico de apenas 2 proprietários. Pintura azul metálica original preservada, interior em ótimo estado e mecânica impecável. Perfect para colecionadores e entusiastas que buscam autenticidade.', 'img05.jpg', 1, 85000, 'Gasolina', 'Manual', 'Azul', 5, 'Carro totalmente original, com documentação em dia e histórico de manutenção completo. Motor revisado recentemente. Único dono desde novo. Inclui manual original e chave reserva.', '2025-10-08 16:49:00', 1),
(2, 'Opala Comodoro', 'Chevrolet', 1982, 65000.00, 'Opala Comodoro 4.1 SL/E - O clássico brasileiro em seu auge! Este exemplar possui pintura preta original, interior em couro genuíno conservado, ar-condicionado funcionando perfeitamente e todos os opcionais de fábrica. Motor 4.1 com carburador duplo, escapamento esportivo e suspensão recentemente revisada.', 'img05.jpg', 1, 120000, 'Gasolina', 'Manual', 'Preto', 3, 'Opala Comodoro em excelente estado de conservação. Interior em couro original bem preservado. Sistema de som original funcionando perfeitamente. Documentação completa desde a primeira licença.', '2025-10-08 16:49:00', 1),
(3, 'Maverick GT', 'Ford', 1974, 75000.00, 'Maverick GT V8 - O muscle car brasileiro! Equipado com motor V8 302 de fabricação nacional, câmbio automático e diferencial autoblocante. Pintura vermelha recente, interior reformado com materiais originais e sistema de som premium. Documentação de importação regularizada e laudo de autenticidade.', 'img05.jpg', 1, 95000, 'Gasolina', 'Automático', 'Vermelho', 7, 'Maverick GT V8 com motor original. Pintura recente e interior reformado. Documentação importada regularizada. Inclui pneus novos e kit de ferramentas original.', '2025-10-08 16:49:00', 1),
(4, 'Brasília LS', 'Volkswagen', 1979, 35000.00, 'Brasília LS - O compacto elegante da Volkswagen! Último ano de fabricação, com apenas 78.000km rodados. Carro muito econômico, perfeito para uso urbano e viagens. Motor 1.6 boxer refrigerado a ar, extremamente confiável e de baixa manutenção. Ideal para primeiro carro clássico.', 'img05.jpg', 0, 78000, 'Gasolina', 'Manual', 'Amarelo', 1, 'Brasília LS conservada, perfeita para colecionadores. Baixa quilometragem. Último ano de fabricação. Documentação organizada e revisão completa recente.', '2025-10-08 16:49:00', 1),
(5, 'Chevette Hatch', 'Chevrolet', 1985, 28000.00, 'Chevette Hatch - O esportivo compacto! Versão hatch com motor 1.6 álcool, câmbio manual de 5 marchas e suspensão esportiva. Carro ágil e divertido de dirigir, com apenas 2 proprietários e toda documentação organizada. Pintura cinza grafite recente e interior em ótimo estado de conservação.', 'img05.jpg', 0, 110000, 'Álcool', 'Manual', 'Cinza', 9, 'Chevette hatch com apenas 2 donos. Documentação organizada e mecânica em dia. Recentemente pintado. Inclui manual do proprietário e nota fiscal de compra.', '2025-10-08 16:49:00', 1),
(6, 'Corcel II', 'Ford', 1978, 32000.00, 'Corcel II - O elegante da Ford! Linha 1978 com motor 1.6, câmbio manual e acabamento GL. Carro muito confortável, com direção hidráulica, bancos de veludo originais e painel completo. Mecânica simples e de fácil manutenção, perfeito para quem está começando no mundo dos clássicos.', 'img05.jpg', 0, 135000, 'Gasolina', 'Manual', 'Marrom', 2, 'Corcel II bem conservado. Ideal para primeiro carro clássico, de fácil manutenção. Interior em ótimo estado. Revisão completa do motor e suspensão realizada há 3 meses.', '2025-10-08 16:49:00', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_carros`
--

CREATE TABLE `fotos_carros` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `foto_nome` varchar(255) NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fotos_carros`
--

INSERT INTO `fotos_carros` (`id`, `carro_id`, `foto_nome`, `data_upload`) VALUES
(1, 1, 'fusca_interior.jpg', '2025-10-08 17:49:00'),
(2, 1, 'fusca_motor.jpg', '2025-10-08 17:49:00'),
(3, 2, 'opala_interior.jpg', '2025-10-08 17:49:00'),
(4, 2, 'opala_traseira.jpg', '2025-10-08 17:49:00'),
(5, 3, 'maverick_motor.jpg', '2025-10-08 17:49:00'),
(6, 3, 'maverick_lateral.jpg', '2025-10-08 17:49:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `carro_interesse` varchar(255) DEFAULT NULL,
  `data_contato` timestamp NOT NULL DEFAULT current_timestamp(),
  `lido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `data_venda` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `data_log` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destaque` (`destaque`),
  ADD KEY `ativo` (`ativo`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fotos_carros`
--
ALTER TABLE `fotos_carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`);

--
-- Índices de tabela `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `nivel` (`nivel`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fotos_carros`
--
ALTER TABLE `fotos_carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `fotos_carros`
--
ALTER TABLE `fotos_carros`
  ADD CONSTRAINT `fotos_carros_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `vendas_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `carros` (`id`),
  ADD CONSTRAINT `vendas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;