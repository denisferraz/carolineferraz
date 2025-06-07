-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 11:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_carolineferraz`
--

-- --------------------------------------------------------

--
-- Table structure for table `alteracoes`
--

CREATE TABLE `alteracoes` (
  `id` int(8) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `token` varchar(35) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time(6) NOT NULL,
  `atendimento_dia_anterior` date NOT NULL,
  `atendimento_hora_anterior` time NOT NULL,
  `alt_status` varchar(15) NOT NULL,
  `id_job` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `config_empresa` varchar(30) DEFAULT NULL,
  `config_email` varchar(35) DEFAULT NULL,
  `config_telefone` varchar(18) DEFAULT NULL,
  `config_cnpj` varchar(18) DEFAULT NULL,
  `config_limitedia` int(5) DEFAULT NULL,
  `config_limitepax` int(5) DEFAULT NULL,
  `config_endereco` mediumtext DEFAULT NULL,
  `config_msg_confirmacao` mediumtext DEFAULT NULL,
  `config_msg_cancelamento` mediumtext DEFAULT NULL,
  `config_msg_finalizar` mediumtext DEFAULT NULL,
  `config_msg_lembrete` mediumtext DEFAULT NULL,
  `config_msg_aniversario` mediumtext DEFAULT NULL,
  `atendimento_hora_comeco` time(6) DEFAULT NULL,
  `atendimento_hora_fim` time(6) DEFAULT NULL,
  `atendimento_hora_intervalo` int(3) DEFAULT NULL,
  `atendimento_dia_max` date DEFAULT NULL,
  `config_dia_segunda` int(1) DEFAULT NULL,
  `config_dia_terca` int(1) DEFAULT NULL,
  `config_dia_quarta` int(1) DEFAULT NULL,
  `config_dia_quinta` int(1) DEFAULT NULL,
  `config_dia_sexta` int(1) DEFAULT NULL,
  `config_dia_sabado` int(1) DEFAULT NULL,
  `config_dia_domingo` int(1) DEFAULT NULL,
  `envio_whatsapp` varchar(15) DEFAULT NULL,
  `envio_email` varchar(15) DEFAULT NULL,
  `is_segunda` int(1) NOT NULL DEFAULT 0,
  `is_terca` int(1) NOT NULL DEFAULT 0,
  `is_quarta` int(1) NOT NULL DEFAULT 0,
  `is_quinta` int(1) NOT NULL DEFAULT 0,
  `is_sexta` int(1) NOT NULL DEFAULT 0,
  `is_sabado` int(1) NOT NULL DEFAULT 0,
  `is_domingo` int(1) NOT NULL DEFAULT 0,
  `lembrete_auto_time` time NOT NULL DEFAULT '09:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `token_emp`, `config_empresa`, `config_email`, `config_telefone`, `config_cnpj`, `config_limitedia`, `config_limitepax`, `config_endereco`, `config_msg_confirmacao`, `config_msg_cancelamento`, `config_msg_finalizar`, `config_msg_lembrete`, `config_msg_aniversario`, `atendimento_hora_comeco`, `atendimento_hora_fim`, `atendimento_hora_intervalo`, `atendimento_dia_max`, `config_dia_segunda`, `config_dia_terca`, `config_dia_quarta`, `config_dia_quinta`, `config_dia_sexta`, `config_dia_sabado`, `config_dia_domingo`, `envio_whatsapp`, `envio_email`, `is_segunda`, `is_terca`, `is_quarta`, `is_quinta`, `is_sexta`, `is_sabado`, `is_domingo`, `lembrete_auto_time`) VALUES
(-2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Caroline Ferraz', 'contato@carolineferraz.com.br', '71991293370', 'N/A', 0, 1, 'Rua Lafaiete F. dos Santos, 153-Centro, Lauro de Freitas. Edf. Dual Medical, 5ª andar,  sala 506<br><br>Rua  Ewerton Visco, nº 290-Caminho das Árvores ,Salvador.Edf. Boulevard Side  Empresarial, 10• andar', '{NOME}, obrigado por confirmar a sua consulta conosco. ✅\\r\\n\\r\\nSegue abaixo, as informações sobre o nosso atendimento:\\r\\n\\r\\n📅Data: {DATA} ás {HORA}\\r\\n✅Consulta: {TIPO}', 'Lembre-se que o cancelamento é irreversível e com isso você ira precisar realizar um novo horário no futuro', 'Foi muito bom ter você conosco!\\r\\n\\r\\nEsperamos ver você em breve!!\\r\\n\\r\\nNão esqueça de nos avaliar, é muito importante e nos ajuda a crescer cada vez mais', 'Oi {NOME}, tudo bem? 😊\\r\\n\\r\\nPassando para confirmar seu atendimento dia {DATA} às {HORA} e para garantir que tudo esteja pronto para te receber com todo o cuidado preciso que me dê um retorno confirmando até as 17h, combinado?\\r\\n\\r\\nCaso não haja confirmação até esse horário, precisaremos liberar o horário para outro paciente. Qualquer dúvida, estou à disposição! 🤍🤍', '🎉Olá, {NOME}!\\r\\nHoje é um dia especial, e não poderíamos deixar de te enviar uma mensagem cheia de carinho.\\r\\n\\r\\nDesejamos a você um novo ciclo repleto de saúde, paz, alegria e conquistas. Que a sua jornada continue iluminada e cheia de boas surpresas!\\r\\n\\r\\nConte sempre conosco para cuidar de você com dedicação e respeito.\\r\\nFeliz aniversário! 🥳\\r\\n\\r\\nCom carinho, Carol!', '08:00:00.000000', '18:00:00.000000', 60, '2025-12-31', 1, 2, 3, 4, 5, 6, -1, 'desativado', 'desativado', 1, 1, 1, 1, 1, 0, 0, '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time(6) NOT NULL,
  `tipo_consulta` varchar(30) NOT NULL,
  `feitapor` varchar(30) NOT NULL,
  `doc_email` varchar(35) NOT NULL,
  `doc_nome` varchar(30) NOT NULL,
  `doc_telefone` varchar(18) NOT NULL,
  `doc_cpf` varchar(15) NOT NULL,
  `status_consulta` varchar(15) NOT NULL,
  `data_cancelamento` datetime(6) NOT NULL,
  `confirmacao_cancelamento` varchar(10) NOT NULL,
  `token` varchar(35) NOT NULL,
  `local_consulta` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contrato`
--

CREATE TABLE `contrato` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `assinado` varchar(5) NOT NULL,
  `assinado_data` datetime NOT NULL DEFAULT current_timestamp(),
  `assinado_empresa` varchar(5) NOT NULL,
  `assinado_empresa_data` datetime NOT NULL DEFAULT current_timestamp(),
  `procedimento` text NOT NULL,
  `procedimento_dias` int(11) NOT NULL,
  `procedimento_valor` varchar(150) NOT NULL,
  `aditivo_valor` varchar(150) NOT NULL,
  `aditivo_procedimento` text NOT NULL,
  `aditivo_status` varchar(30) NOT NULL,
  `token` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custos`
--

CREATE TABLE `custos` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `custo_valor` float NOT NULL,
  `custo_tipo` varchar(50) NOT NULL,
  `custo_descricao` mediumtext NOT NULL,
  `custo_quem` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `despesa_dia` date NOT NULL,
  `despesa_valor` double NOT NULL,
  `despesa_tipo` varchar(30) NOT NULL,
  `despesa_descricao` text NOT NULL,
  `despesa_quem` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disponibilidade`
--

CREATE TABLE `disponibilidade` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `atendimento_dia` date NOT NULL,
  `atendimento_hora` time NOT NULL,
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `produto` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_entrada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estoque_item`
--

CREATE TABLE `estoque_item` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `produto` varchar(100) NOT NULL,
  `minimo` int(11) NOT NULL,
  `unidade` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evolucoes`
--

CREATE TABLE `evolucoes` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `doc_email` varchar(115) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `profissional` varchar(115) DEFAULT NULL,
  `anotacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historico_atendimento`
--

CREATE TABLE `historico_atendimento` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `quando` datetime(6) NOT NULL,
  `quem` varchar(35) NOT NULL,
  `unico` varchar(16) NOT NULL,
  `oque` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lancamentos_atendimento`
--

CREATE TABLE `lancamentos_atendimento` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `doc_email` varchar(100) NOT NULL,
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
-- Table structure for table `modelos_anamnese`
--

CREATE TABLE `modelos_anamnese` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `painel_users`
--

CREATE TABLE `painel_users` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tipo` varchar(15) DEFAULT NULL,
  `senha` varchar(50) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `rg` varchar(30) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `telefone` varchar(18) NOT NULL,
  `profissao` varchar(50) DEFAULT NULL,
  `unico` varchar(15) NOT NULL,
  `cep` int(11) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `token` varchar(35) NOT NULL,
  `codigo` int(8) NOT NULL,
  `tentativas` int(2) NOT NULL,
  `aut_painel` int(1) NOT NULL,
  `origem` varchar(50) DEFAULT NULL,
  `tema_painel` varchar(10) NOT NULL DEFAULT 'colorido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `painel_users`
--

INSERT INTO `painel_users` (`id`, `token_emp`, `email`, `tipo`, `senha`, `nome`, `rg`, `nascimento`, `telefone`, `profissao`, `unico`, `cep`, `rua`, `numero`, `cidade`, `bairro`, `estado`, `token`, `codigo`, `tentativas`, `aut_painel`, `origem`, `tema_painel`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'denis_ferraz359@hotmail.com', 'Admin', 'd753f0b2743ac9a5a0e356a4cc08d072', 'Denis Ferraz', '1368107133', '1989-12-17', '71992604877', 'Hoteleiro', '05336888508', 41500300, 'Avenida Luís Viana Filho', '10', 'Salvador', 'São Cristóvão', 'BA', '24774953ab53456d38dfdd421a995b51', 0, 0, 0, NULL, 'colorido');

-- --------------------------------------------------------

--
-- Table structure for table `perguntas_modelo`
--

CREATE TABLE `perguntas_modelo` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `modelo_id` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `pergunta` text DEFAULT NULL,
  `opcoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `respostas_anamnese`
--

CREATE TABLE `respostas_anamnese` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `modelo_id` int(11) DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `pergunta_id` int(11) DEFAULT NULL,
  `resposta` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tratamento`
--

CREATE TABLE `tratamento` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `plano_descricao` mediumtext NOT NULL,
  `comentario` text DEFAULT NULL,
  `plano_data` datetime NOT NULL,
  `sessao_atual` int(11) NOT NULL,
  `sessao_total` int(11) NOT NULL,
  `sessao_status` varchar(30) NOT NULL,
  `token` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `link_youtube` mediumtext NOT NULL,
  `descricao` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alteracoes`
--
ALTER TABLE `alteracoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custos`
--
ALTER TABLE `custos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disponibilidade`
--
ALTER TABLE `disponibilidade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estoque_item`
--
ALTER TABLE `estoque_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evolucoes`
--
ALTER TABLE `evolucoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modelos_anamnese`
--
ALTER TABLE `modelos_anamnese`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `painel_users`
--
ALTER TABLE `painel_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perguntas_modelo`
--
ALTER TABLE `perguntas_modelo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modelo_id` (`modelo_id`);

--
-- Indexes for table `respostas_anamnese`
--
ALTER TABLE `respostas_anamnese`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tratamento`
--
ALTER TABLE `tratamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alteracoes`
--
ALTER TABLE `alteracoes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custos`
--
ALTER TABLE `custos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disponibilidade`
--
ALTER TABLE `disponibilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estoque_item`
--
ALTER TABLE `estoque_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evolucoes`
--
ALTER TABLE `evolucoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modelos_anamnese`
--
ALTER TABLE `modelos_anamnese`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `painel_users`
--
ALTER TABLE `painel_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `perguntas_modelo`
--
ALTER TABLE `perguntas_modelo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `respostas_anamnese`
--
ALTER TABLE `respostas_anamnese`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tratamento`
--
ALTER TABLE `tratamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `perguntas_modelo`
--
ALTER TABLE `perguntas_modelo`
  ADD CONSTRAINT `perguntas_modelo_ibfk_1` FOREIGN KEY (`modelo_id`) REFERENCES `modelos_anamnese` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
