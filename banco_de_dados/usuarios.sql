-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Abr-2026 às 17:46
-- Versão do servidor: 10.4.6-MariaDB
-- versão do PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `usuarios`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cad_usuarios`
--

CREATE TABLE `cad_usuarios` (
  `cad_usuario_id` int(11) NOT NULL,
  `cad_usuario_nome` varchar(150) DEFAULT NULL,
  `cad_usuario_ativo_sn` varchar(1) DEFAULT NULL,
  `cad_usuario_dt_cad` datetime DEFAULT NULL,
  `cad_usuario_usu_cad` varchar(150) DEFAULT NULL,
  `cad_usuario_dt_alt` datetime DEFAULT NULL,
  `cad_usuario_usu_alt` varchar(150) DEFAULT NULL,
  `cad_usuario_tipo_login` varchar(40) DEFAULT NULL,
  `cad_usuario_senha` varchar(100) DEFAULT NULL COMMENT 'Senha do usuário',
  `cad_usuario_email` varchar(100) DEFAULT NULL COMMENT 'e-mail do usuário'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cad_usuarios`
--

INSERT INTO `cad_usuarios` (`cad_usuario_id`, `cad_usuario_nome`, `cad_usuario_ativo_sn`, `cad_usuario_dt_cad`, `cad_usuario_usu_cad`, `cad_usuario_dt_alt`, `cad_usuario_usu_alt`, `cad_usuario_tipo_login`, `cad_usuario_senha`, `cad_usuario_email`) VALUES
(1, 'Vitor Hugo', 'S', '2026-04-25 02:51:20', 'Sistema', NULL, NULL, 'CEO', 'teste', 'vitor@teste.com.br'),
(2, 'Wallace Alexandre', 'S', '2026-04-28 16:32:59', '', NULL, NULL, 'CEO', 'teste123', 'wallace@teste.com'),
(3, 'Osvaldo Alves', 'S', '2026-04-28 16:33:37', '', NULL, NULL, 'Cliente', 'teste123', 'osvaldo@teste.com');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cad_usuarios`
--
ALTER TABLE `cad_usuarios`
  ADD PRIMARY KEY (`cad_usuario_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cad_usuarios`
--
ALTER TABLE `cad_usuarios`
  MODIFY `cad_usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
