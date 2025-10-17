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
  `endereco` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `endereco`) VALUES
(1, 'leo', 'leo@gmail.com', '22', '(11) 99999-9999', 'Rua Exemplo, 123');

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
  `imagem` varchar(255) NOT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `quilometragem` int(11) DEFAULT NULL,
  `combustivel` varchar(50) DEFAULT NULL,
  `cambio` varchar(50) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `final_placa` int(11) DEFAULT NULL,
  `detalhes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carros`
--

INSERT INTO `carros` (`id`, `modelo`, `marca`, `ano`, `preco`, `descricao`, `imagem`, `destaque`, `quilometragem`, `combustivel`, `cambio`, `cor`, `final_placa`, `detalhes`) VALUES
(1, 'Fusca', 'Volkswagen', 1975, 45000.00, 'Fusca 1300 L em ótimo estado de conservação. Carro todo original, com motor revisado e documentação em dia.', 'img01.jpg', 1, 85000, 'Gasolina', 'Manual', 'Azul', 5, 'Carro totalmente original, com documentação em dia e histórico de manutenção completo. Motor revisado recentemente.'),
(2, 'Opala', 'Chevrolet', 1982, 65000.00, 'Opala Comodoro 4.1. Carro com pintura original, interior em couro e mecânica impecável.', 'img02.jpg', 1, 120000, 'Gasolina', 'Manual', 'Preto', 3, 'Opala Comodoro em excelente estado de conservação. Interior em couro original bem preservado.'),
(3, 'Maverick', 'Ford', 1974, 75000.00, 'Maverick GT V8. Um clássico americano com motor 302 e som característico inconfundível.', 'img03.jpg', 1, 95000, 'Gasolina', 'Automático', 'Vermelho', 7, 'Maverick GT V8 com motor original. Pintura recente e interior reformado.'),
(4, 'Brasília', 'Volkswagen', 1979, 35000.00, 'Brasília LS. Carro econômico e divertido, perfeito para colecionadores.', 'img04.jpg', 0, 78000, 'Gasolina', 'Manual', 'Amarelo', 1, 'Brasília LS conservada, perfeita para colecionadores. Baixa quilometragem.'),
(5, 'Chevette', 'Chevrolet', 1985, 28000.00, 'Chevette Hatch. Conservado, com apenas 2 donos e toda a documentação organizada.', 'img05.jpg', 0, 110000, 'Álcool', 'Manual', 'Cinza', 9, 'Chevette hatch com apenas 2 donos. Documentação organizada e mecânica em dia.'),
(6, 'Corcel', 'Ford', 1978, 32000.00, 'Corcel II. Carro com mecânica simples e de fácil manutenção, ideal para primeiro clássico.', 'img06.jpg', 0, 135000, 'Gasolina', 'Manual', 'Marrom', 2, 'Corcel II bem conservado. Ideal para primeiro carro clássico, de fácil manutenção.');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;