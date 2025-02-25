-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/01/2025 às 09:54
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
-- Banco de dados: `pap`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `dthr_compra` datetime NOT NULL,
  `preco_total_carrinho` decimal(10,2) NOT NULL,
  `id_utilizadores` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id_carrinho`, `estado`, `dthr_compra`, `preco_total_carrinho`, `id_utilizadores`) VALUES
(1, 1, '2025-01-19 17:55:01', 38.97, 7),
(2, 1, '2025-01-19 19:34:55', 12.99, 7),
(4, 1, '2025-01-20 12:16:26', 40.37, 7),
(5, 1, '2025-01-20 12:36:41', 27.38, 7),
(6, 1, '2025-01-21 09:09:14', 139.98, 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_itens`
--

CREATE TABLE `carrinho_itens` (
  `id_itens` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_total_itens` decimal(10,2) NOT NULL,
  `id_carrinho` int(11) NOT NULL,
  `idproduto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho_itens`
--

INSERT INTO `carrinho_itens` (`id_itens`, `quantidade`, `preco_total_itens`, `id_carrinho`, `idproduto`) VALUES
(1, 3, 38.97, 1, 1),
(2, 1, 12.99, 2, 1),
(9, 2, 27.38, 4, 3),
(10, 0, 0.00, 4, 2),
(11, 1, 12.99, 4, 1),
(12, 0, 0.00, 5, 2),
(13, 2, 27.38, 5, 3),
(14, 2, 139.98, 6, 4),
(15, 0, 0.00, 6, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `designacao` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `designacao`, `preco`, `imagem`) VALUES
(1, 'BS Spike 3D', 12.99, 'Imagens/spike.png'),
(2, 'BS Doug T-shirt', 11.79, 'Imagens/doug.jpg'),
(3, 'BS Kenji POST', 13.69, 'Imagens/kenji.jfif'),
(4, 'NS Joy-Con', 69.99, 'Imagens/678e47f549a05_joycon.jpg'),
(5, 'NS Hollow Knight', 29.49, 'Imagens/678e48eda12d6_hk.jpg'),
(6, '8-bit virus T-Shirt', 18.79, 'Imagens/678f647c2c0f8_8-bit.jfif');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_utilizador`
--

CREATE TABLE `tipos_utilizador` (
  `id_tipos_utilizador` int(11) NOT NULL,
  `tipo_utilizador` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipos_utilizador`
--

INSERT INTO `tipos_utilizador` (`id_tipos_utilizador`, `tipo_utilizador`) VALUES
(0, 'administrador'),
(1, 'utilizador');

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizadores` int(11) NOT NULL,
  `utilizador` varchar(20) NOT NULL,
  `password` varchar(41) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_tipos_utilizador` int(11) NOT NULL,
  `danasc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizadores`, `utilizador`, `password`, `email`, `id_tipos_utilizador`, `danasc`) VALUES
(2, 'user1', 'a9993e364706816aba3e25717850c26c9cd0d89d', 'user1@esab.pt', 1, '2005-05-05'),
(6, 'cme', 'abbc72c5fb73509dbce0a552347dc7fb71651a70', 'trgribt@oko.pp', 1, '2003-01-01'),
(7, 'admin', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'admin@gmail.com', 0, '2000-01-01'),
(8, 'Mo', '5c06583ebdccd6c684ec1b5050d100edf3df5619', '5gt5g5@gmail.com', 0, '2005-12-12'),
(9, 'ko', '$2y$10$f0G..oX2jW07sZodlDbr5ecrnq5O9PNKgQ', 'huhu@op.co', 1, '2000-12-12');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `id_utilizadores` (`id_utilizadores`);

--
-- Índices de tabela `carrinho_itens`
--
ALTER TABLE `carrinho_itens`
  ADD PRIMARY KEY (`id_itens`),
  ADD KEY `id_carrinho` (`id_carrinho`),
  ADD KEY `id_produto` (`idproduto`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices de tabela `tipos_utilizador`
--
ALTER TABLE `tipos_utilizador`
  ADD PRIMARY KEY (`id_tipos_utilizador`);

--
-- Índices de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizadores`),
  ADD UNIQUE KEY `utilizador` (`utilizador`,`email`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FK_id_utilizador` (`id_tipos_utilizador`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `carrinho_itens`
--
ALTER TABLE `carrinho_itens`
  MODIFY `id_itens` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizadores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_utilizadores`) REFERENCES `utilizadores` (`id_utilizadores`);

--
-- Restrições para tabelas `carrinho_itens`
--
ALTER TABLE `carrinho_itens`
  ADD CONSTRAINT `carrinho_itens_ibfk_1` FOREIGN KEY (`idproduto`) REFERENCES `produtos` (`id_produto`),
  ADD CONSTRAINT `carrinho_itens_ibfk_2` FOREIGN KEY (`id_carrinho`) REFERENCES `carrinho` (`id_carrinho`);

--
-- Restrições para tabelas `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `FK_id_utilizador` FOREIGN KEY (`id_tipos_utilizador`) REFERENCES `tipos_utilizador` (`id_tipos_utilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
