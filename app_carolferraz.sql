-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/04/2023 às 01:39
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
-- Banco de dados: `app_carolferraz`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alteracoes`
--

CREATE TABLE `alteracoes` (
  `id` int(8) NOT NULL,
  `token` varchar(35) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time(6) NOT NULL,
  `alt_status` varchar(15) NOT NULL,
  `id_job` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `calendario_pousada`
--

CREATE TABLE `calendario_pousada` (
  `id` int(6) NOT NULL,
  `tarifas` float NOT NULL,
  `qtd_pax` int(6) NOT NULL,
  `data_tarifa` date NOT NULL,
  `status_data` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(1) NOT NULL,
  `configuracao` varchar(4) DEFAULT NULL,
  `config_empresa` varchar(30) NOT NULL,
  `config_email` varchar(35) NOT NULL,
  `config_telefone` varchar(18) NOT NULL,
  `config_cnpj` varchar(18) NOT NULL,
  `config_limitedia` int(5) NOT NULL,
  `config_limitepax` int(5) NOT NULL,
  `config_endereco` varchar(100) NOT NULL,
  `config_msg_confirmacao` mediumtext NOT NULL,
  `config_msg_cancelamento` mediumtext NOT NULL,
  `config_msg_finalizar` mediumtext NOT NULL,
  `atendimento_hora_comeco` time(6) DEFAULT NULL,
  `atendimento_hora_fim` time(6) DEFAULT NULL,
  `atendimento_hora_intervalo` int(3) DEFAULT NULL,
  `atendimento_dia_max` date NOT NULL,
  `config_dia_segunda` int(1) NOT NULL,
  `config_dia_terca` int(1) NOT NULL,
  `config_dia_quarta` int(1) NOT NULL,
  `config_dia_quinta` int(1) NOT NULL,
  `config_dia_sexta` int(1) NOT NULL,
  `config_dia_sabado` int(1) NOT NULL,
  `config_dia_domingo` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `configuracao`, `config_empresa`, `config_email`, `config_telefone`, `config_cnpj`, `config_limitedia`, `config_limitepax`, `config_endereco`, `config_msg_confirmacao`, `config_msg_cancelamento`, `config_msg_finalizar`, `atendimento_hora_comeco`, `atendimento_hora_fim`, `atendimento_hora_intervalo`, `atendimento_dia_max`, `config_dia_segunda`, `config_dia_terca`, `config_dia_quarta`, `config_dia_quinta`, `config_dia_sexta`, `config_dia_sabado`, `config_dia_domingo`) VALUES
(-2, NULL, 'Caroline Ferraz', 'contato@carolineferraz.com.br', '71991293370', 'N/A', 1, 1, 'Edificio Infinity - R. Leonardo Rodrigues da Silva, 248 - Fazenda Pitangueira, Lauro de Freitas - BA', 'É indispensável a utilização da mascara durante todo o nosso procedimento<br>Qualquer duvida, sinta-se a vontade para entrar em contato conosco.', 'Lembre-se que o cancelamento é irreversível e com isso você ira precisar realizar um novo horário no futuro', 'Foi muito bom ter você conosco<br>Esperamos ver você em breve!!<br>Não esqueça de nos avaliar, é muito importante e nos ajuda a crescer cada vez mais', '08:00:00.000000', '18:00:00.000000', 60, '2023-12-31', 1, 2, 3, 4, 5, 6, -1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `assinado` varchar(5) NOT NULL,
  `assinado_data` datetime NOT NULL DEFAULT current_timestamp(),
  `assinado_empresa` varchar(5) NOT NULL,
  `assinado_empresa_data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `contrato`
--

INSERT INTO `contrato` (`id`, `email`, `assinado`, `assinado_data`, `assinado_empresa`, `assinado_empresa_data`) VALUES
(1, 'denis_ferraz359@hotmail.com', 'Sim', '2023-04-23 20:11:47', 'Não', '2023-04-23 20:11:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `despesa_dia` date NOT NULL,
  `despesa_valor` double NOT NULL,
  `despesa_tipo` varchar(30) NOT NULL,
  `despesa_descricao` text NOT NULL,
  `despesa_quem` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `despesas`
--

INSERT INTO `despesas` (`id`, `despesa_dia`, `despesa_valor`, `despesa_tipo`, `despesa_descricao`, `despesa_quem`) VALUES
(1, '2023-04-22', 1200, 'Aluguel', 'teste despesa', 'Denis Ferraz'),
(2, '2023-04-05', 1200, 'Aluguel', 'teste despesa', 'Denis Ferraz'),
(3, '2023-03-27', 1200, 'Aluguel', 'teste despesa', 'Denis Ferraz'),
(4, '2023-03-27', 200, 'Luz', 'teste despesa luz', 'Denis Ferraz'),
(5, '2023-04-01', 200, 'Luz', 'teste despesa luz', 'Denis Ferraz'),
(6, '2023-04-22', 200, 'Luz', 'teste despesa luz', 'Denis Ferraz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `disponibilidade_atendimento`
--

CREATE TABLE `disponibilidade_atendimento` (
  `id` int(11) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time(6) NOT NULL,
  `confirmacao` varchar(10) NOT NULL,
  `quantidade` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formulario_atendimento`
--

CREATE TABLE `formulario_atendimento` (
  `id` int(11) NOT NULL,
  `confirmacao` varchar(10) NOT NULL,
  `feitopor` varchar(30) DEFAULT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `endereco` varchar(50) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `bairro` varchar(30) DEFAULT NULL,
  `municipio` varchar(30) DEFAULT NULL,
  `uf` varchar(30) DEFAULT NULL,
  `celular` varchar(13) DEFAULT NULL,
  `email` varchar(35) DEFAULT NULL,
  `profissao` varchar(30) DEFAULT NULL,
  `estado_civil` varchar(15) DEFAULT NULL,
  `queixa_principal` varchar(155) DEFAULT NULL,
  `doenca_outras_areas` varchar(155) DEFAULT NULL,
  `doenca_outras_areas_tempo` varchar(30) DEFAULT NULL,
  `doenca_outras_areas_status` varchar(15) DEFAULT NULL,
  `doenca_outras_areas_cabelo` varchar(15) DEFAULT NULL,
  `doenca_outras_areas_alteracoes` varchar(15) DEFAULT NULL,
  `doenca_outras_areas_crises` varchar(155) DEFAULT NULL,
  `doencas_ultimas` varchar(155) DEFAULT NULL,
  `doencas_atual` varchar(155) DEFAULT NULL,
  `endocrino` varchar(155) DEFAULT NULL,
  `cardiaco` varchar(4) DEFAULT NULL,
  `marca_passo` varchar(4) DEFAULT NULL,
  `medicacao` varchar(155) DEFAULT NULL,
  `precederam_problema` varchar(30) DEFAULT NULL,
  `alergias` varchar(155) DEFAULT NULL,
  `filhos` varchar(10) DEFAULT NULL,
  `gravidez` varchar(15) DEFAULT NULL,
  `carne` varchar(4) DEFAULT NULL,
  `alteracao_menstrual` varchar(155) DEFAULT NULL,
  `familiares` varchar(30) DEFAULT NULL,
  `quimica_cabelos_atual` varchar(155) DEFAULT NULL,
  `cuidado_cabelo_usa` varchar(30) DEFAULT NULL,
  `cuidado_cabelo_lavagem` varchar(155) DEFAULT NULL,
  `cuidado_cabelo_produtos` varchar(155) DEFAULT NULL,
  `exm_fisico_volume_cabelo` varchar(4) DEFAULT NULL,
  `exm_fisico_comprimento_cabelo` varchar(4) DEFAULT NULL,
  `exm_fisico_quimica` varchar(155) DEFAULT NULL,
  `quimica_cabelos_atual_frequencia` varchar(30) DEFAULT NULL,
  `exm_fisico_cabelo` varchar(15) DEFAULT NULL,
  `exm_fisico_pontas` varchar(155) DEFAULT NULL,
  `exm_fisico_couro_cabeludo` varchar(15) DEFAULT NULL,
  `exm_fisico_presenca` varchar(155) DEFAULT NULL,
  `alopecia` int(2) DEFAULT NULL,
  `alopecia_localizacao` varchar(1000) DEFAULT NULL,
  `alopecia_lesoes` varchar(100) DEFAULT NULL,
  `alopecia_formato` varchar(100) DEFAULT NULL,
  `alopecia_formato_2` varchar(100) DEFAULT NULL,
  `alopecia_tamanho` varchar(100) DEFAULT NULL,
  `alopecia_reposicao` varchar(4) DEFAULT NULL,
  `alopecia_couro` varchar(100) DEFAULT NULL,
  `alopecia_obs` varchar(100) DEFAULT NULL,
  `alteracao_encontrada` mediumtext DEFAULT NULL,
  `protocolo_sugerio` mediumtext DEFAULT NULL,
  `protocolo_realizado_01` mediumtext DEFAULT NULL,
  `protocolo_realizado_01_data` date DEFAULT NULL,
  `protocolo_realizado_02` mediumtext DEFAULT NULL,
  `protocolo_realizado_02_data` date DEFAULT NULL,
  `protocolo_realizado_03` mediumtext DEFAULT NULL,
  `protocolo_realizado_03_data` date DEFAULT NULL,
  `protocolo_realizado_04` mediumtext DEFAULT NULL,
  `protocolo_realizado_04_data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_atendimento`
--

CREATE TABLE `historico_atendimento` (
  `id` int(11) NOT NULL,
  `quando` datetime(6) NOT NULL,
  `quem` varchar(35) NOT NULL,
  `unico` varchar(16) NOT NULL,
  `oque` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lancamentos_atendimento`
--

CREATE TABLE `lancamentos_atendimento` (
  `id` int(11) NOT NULL,
  `confirmacao` varchar(10) NOT NULL,
  `produto` varchar(45) NOT NULL,
  `quantidade` int(5) NOT NULL,
  `valor` float NOT NULL,
  `quando` datetime(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `feitopor` varchar(30) DEFAULT NULL,
  `doc_nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `painel_users`
--

CREATE TABLE `painel_users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tipo` varchar(15) DEFAULT NULL,
  `senha` varchar(50) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `telefone` varchar(18) NOT NULL,
  `unico` varchar(15) NOT NULL,
  `codigo` int(8) NOT NULL,
  `tentativas` int(2) NOT NULL,
  `aut_reservas` int(1) NOT NULL,
  `aut_configuracoes` int(1) NOT NULL,
  `aut_disponibilidade` int(1) NOT NULL,
  `aut_acessos` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `painel_users`
--

INSERT INTO `painel_users` (`id`, `email`, `tipo`, `senha`, `nome`, `telefone`, `unico`, `codigo`, `tentativas`, `aut_reservas`, `aut_configuracoes`, `aut_disponibilidade`, `aut_acessos`) VALUES
(4, 'denis_ferraz359@hotmail.com', 'Admin', 'd753f0b2743ac9a5a0e356a4cc08d072', 'Denis Ferraz', '71-99260-4877', '05336888508', 57869679, 0, 0, 0, 0, 0),
(5, 'carolineferraz.tricologia@gmail.com', 'Admin', 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Caroline Ferraz', '71-99129-3370', '03326635583', 0, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas_atendimento`
--

CREATE TABLE `reservas_atendimento` (
  `id` int(11) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time(6) NOT NULL,
  `confirmacao` varchar(10) NOT NULL,
  `tipo_consulta` varchar(30) NOT NULL,
  `feitapor` varchar(30) NOT NULL,
  `doc_email` varchar(35) NOT NULL,
  `doc_nome` varchar(30) NOT NULL,
  `doc_telefone` varchar(18) NOT NULL,
  `doc_cpf` varchar(15) NOT NULL,
  `status_reserva` varchar(11) NOT NULL,
  `data_cancelamento` datetime(6) NOT NULL,
  `confirmacao_cancelamento` varchar(10) NOT NULL,
  `token` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tratamento`
--

CREATE TABLE `tratamento` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `plano_descricao` mediumtext NOT NULL,
  `plano_data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alteracoes`
--
ALTER TABLE `alteracoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `calendario_pousada`
--
ALTER TABLE `calendario_pousada`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`config_empresa`);

--
-- Índices de tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `disponibilidade_atendimento`
--
ALTER TABLE `disponibilidade_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `formulario_atendimento`
--
ALTER TABLE `formulario_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `painel_users`
--
ALTER TABLE `painel_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reservas_atendimento`
--
ALTER TABLE `reservas_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tratamento`
--
ALTER TABLE `tratamento`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alteracoes`
--
ALTER TABLE `alteracoes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `calendario_pousada`
--
ALTER TABLE `calendario_pousada`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `disponibilidade_atendimento`
--
ALTER TABLE `disponibilidade_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `formulario_atendimento`
--
ALTER TABLE `formulario_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `painel_users`
--
ALTER TABLE `painel_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reservas_atendimento`
--
ALTER TABLE `reservas_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tratamento`
--
ALTER TABLE `tratamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
