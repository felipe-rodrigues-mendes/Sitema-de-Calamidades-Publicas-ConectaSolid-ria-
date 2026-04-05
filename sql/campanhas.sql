-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/04/2026 às 02:29
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
-- Banco de dados: `conecta_solidaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `campanhas`
--

CREATE TABLE `campanhas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text NOT NULL,
  `localizacao` varchar(150) NOT NULL,
  `status` enum('ativa','encerrada') NOT NULL DEFAULT 'ativa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `campanhas`
--

INSERT INTO `campanhas` (`id`, `titulo`, `descricao`, `localizacao`, `status`, `created_at`) VALUES
(4, 'Rio Grande do Sul', 'Campanha de apoio às famílias afetadas por enchentes.', 'Rio Grande do Sul', 'ativa', '2026-04-05 00:27:45'),
(5, 'Bahia', 'Campanha de arrecadação para regiões afetadas por fortes chuvas.', 'Bahia', 'ativa', '2026-04-05 00:27:45'),
(6, 'Minas Gerais', 'Campanha solidária para apoio às vítimas de desastres.', 'Minas Gerais', 'ativa', '2026-04-05 00:27:45'),
(7, 'Paraná', 'Campanha de doações para famílias em situação de calamidade.', 'Paraná', 'ativa', '2026-04-05 00:27:45'),
(8, 'Pernambuco', 'Campanha de ajuda humanitária para áreas afetadas.', 'Pernambuco', 'ativa', '2026-04-05 00:27:45'),
(9, 'São Paulo', 'Campanha de arrecadação de itens essenciais.', 'São Paulo', 'ativa', '2026-04-05 00:27:45');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `campanhas`
--
ALTER TABLE `campanhas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `campanhas`
--
ALTER TABLE `campanhas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
