-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/04/2026 às 04:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `conecta_solidaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_doacao`
--

CREATE TABLE `itens_doacao` (
  `id` int(11) NOT NULL,
  `doacao_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_doacao`
--

INSERT INTO `itens_doacao` (`id`, `doacao_id`, `categoria_id`, `quantidade`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 4),
(3, 1, 5, 1),
(4, 2, 1, 10),
(5, 2, 6, 10),
(6, 2, 7, 5),
(7, 3, 1, 10),
(8, 3, 6, 10),
(9, 3, 7, 5),
(10, 5, 2, 20);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `itens_doacao`
--
ALTER TABLE `itens_doacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_itens_doacao_doacao` (`doacao_id`),
  ADD KEY `fk_itens_doacao_categoria` (`categoria_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `itens_doacao`
--
ALTER TABLE `itens_doacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_doacao`
--
ALTER TABLE `itens_doacao`
  ADD CONSTRAINT `fk_itens_doacao_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_itens` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_itens_doacao_doacao` FOREIGN KEY (`doacao_id`) REFERENCES `doacoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
