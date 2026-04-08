-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/04/2026 às 04:26
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
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT 'doador',
  `ativo` tinyint(1) DEFAULT 1,
  `perfil_id` int(11) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `email`, `telefone`, `senha`, `tipo`, `ativo`, `perfil_id`, `created_at`) VALUES
(1, 'Felipe Rodrigues Mendes', '073.925.411-10', 'feliperodrigues744@hotmail.com', NULL, '$2y$10$FryRi1NuMciH8Cs5ZbJJbe4vWLXfEexFMtgMKI7Di/TrTfvwxmiMm', 'doador', 1, 2, '2026-04-07 23:02:45'),
(2, 'Iago Souza Oliveira', '012.569.741-79', 'iagogay@gmail.com', NULL, '$2y$10$k8FW.hhTprI2ET0/2QMwouF1kHcw7yQdvcv3JIHQtQ5ALNk5lJCPe', 'doador', 1, 2, '2026-04-08 01:12:26'),
(3, 'Mateus Gayroba da Silva', '036.456.981-96', 'mate123@hotmail.com', NULL, '$2y$10$oJE5heBVdwjWB4agY.lVG.MuGdYRUFSpd569c/b8SjFVikPc54MYq', 'doador', 1, 2, '2026-04-08 01:13:25'),
(4, 'gabriel mendes', '456.789.123-78', 'gabi1234@gmail.com', NULL, '$2y$10$U.JJEw.3jBBKSVIkdi0Pvec4Igw3aXni9c5z4Z98qkt7vTOuaj3n2', 'doador', 1, 2, '2026-04-08 01:20:56');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_perfil` (`perfil_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
