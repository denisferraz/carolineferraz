-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 21, 2025 at 02:26 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u826189016_tricologia`
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
-- Table structure for table `atestados`
--

CREATE TABLE `atestados` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
  `doc_email` varchar(100) DEFAULT NULL,
  `conteudo` text DEFAULT NULL,
  `titulo` varchar(50) NOT NULL,
  `criado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
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
(-2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Caroline Ferraz', 'contato@carolineferraz.com.br', '71991293370', 'N/A', 0, 1, 'Rua Lafaiete F. dos Santos, 153-Centro, Lauro de Freitas. Edf. Dual Medical, 5ª andar,  sala 506<br><br>Rua  Ewerton Visco, nº 290-Caminho das Árvores ,Salvador.Edf. Boulevard Side  Empresarial, 10• andar', '{NOME}, obrigado por confirmar a sua consulta conosco. ✅\\r\\n\\r\\nSegue abaixo, as informações sobre o nosso atendimento:\\r\\n\\r\\n📅Data: {DATA} ás {HORA}\\r\\n✅Consulta: {TIPO}', 'Lembre-se que o cancelamento é irreversível e com isso você ira precisar realizar um novo horário no futuro', 'Foi muito bom ter você conosco!\\r\\n\\r\\nEsperamos ver você em breve!!\\r\\n\\r\\nNão esqueça de nos avaliar, é muito importante e nos ajuda a crescer cada vez mais', 'Oi {NOME}, tudo bem? 😊\\r\\n\\r\\nPassando para confirmar seu atendimento dia {DATA} às {HORA} e para garantir que tudo esteja pronto para te receber com todo o cuidado preciso que me dê um retorno confirmando até as 17h, combinado?\\r\\n\\r\\nCaso não haja confirmação até esse horário, precisaremos liberar o horário para outro paciente. Qualquer dúvida, estou à disposição! 🤍🤍', '🎉Olá, {NOME}!\\r\\nHoje é um dia especial, e não poderíamos deixar de te enviar uma mensagem cheia de carinho.\\r\\n\\r\\nDesejamos a você um novo ciclo repleto de saúde, paz, alegria e conquistas. Que a sua jornada continue iluminada e cheia de boas surpresas!\\r\\n\\r\\nConte sempre conosco para cuidar de você com dedicação e respeito.\\r\\nFeliz aniversário! 🥳\\r\\n\\r\\nCom carinho, Carol!', '08:00:00.000000', '18:00:00.000000', 60, '2025-12-31', 1, 2, 3, 4, 5, 6, -1, 'ativado', 'desativado', 1, 1, 1, 1, 1, 0, 0, '09:00:00');

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
  `status_consulta` varchar(15) NOT NULL,
  `data_cancelamento` datetime(6) NOT NULL,
  `confirmacao_cancelamento` varchar(10) NOT NULL,
  `token` varchar(35) NOT NULL,
  `local_consulta` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `consultas`
--

INSERT INTO `consultas` (`id`, `token_emp`, `atendimento_dia`, `atendimento_hora`, `tipo_consulta`, `feitapor`, `doc_email`, `status_consulta`, `data_cancelamento`, `confirmacao_cancelamento`, `token`, `local_consulta`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-03-14 20:27:15.000000', '82A3CB2F81', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-06', '09:00:00.000000', 'Avaliação Capilar', 'Denis Ferraz', 'everton.pinheiro@hotmail.com', 'Em Andamento', '2023-04-24 11:42:16.000000', 'Ativa', 'f6d6d1877a58fa4b08ab2a0c5843aebb', ''),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-17', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'NoShow', '2023-07-26 11:58:52.000000', '68CA5BF126', '31946ec0f29162557b632db0732d02d7', 'Lauro de Freitas'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-12', '18:00:00.000000', 'Avaliação Capilar', 'Site', 'alinerochas@hotmail.com', 'Finalizada', '2023-05-05 19:25:46.000000', 'Ativa', 'ba12e9e1dadf88b1133c5927aed07fbf', ''),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-03', '10:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'fernandaandradeteixeira@gmail.com', 'Finalizada', '2023-05-06 12:12:35.000000', 'Ativa', '4966c7f0318a5b1c760f192ff763c808', ''),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-27', '15:00:00.000000', 'Nova Sessão', 'Site', 'everton.pinheiro@hotmail.com', 'Finalizada', '2023-05-07 10:09:51.000000', 'Ativa', '888f06fce9e71d050306fb492a98741e', ''),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-30', '14:00:00.000000', 'Nova Sessão', 'Site', 'krinasantana@gmail.com', 'Finalizada', '2023-05-07 18:48:41.000000', 'Ativa', 'bc5aba92df6ec3ce34fa06979456bb85', ''),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-13', '09:00:00.000000', 'Consulta Capilar', 'Site', 'andreacrb03@gmail.com', 'Finalizada', '2023-05-09 09:20:11.000000', 'Ativa', '2e70f9960d416e58a56fb9ca23f045aa', ''),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18', '17:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2023-05-18 17:38:53.000000', '4C61ABDA41', 'f2f1d01a6ba85665e82feca093ff82c3', ''),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2023-05-16 18:05:14.000000', 'Ativa', 'a4aa8d1390b47347aa040a5b58dba564', ''),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2023-05-19 08:38:47.000000', 'Ativa', '0d8e811e2d60318b12b30ea60b82131e', ''),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'amandalgarcez@hotmail.com', 'Finalizada', '2023-05-24 18:59:10.000000', 'Ativa', 'dc697c2a966748a66b57af1a203f1901', ''),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17', '11:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2023-06-11 08:50:25.000000', 'Ativa', '181832b31fe7b226bf8576d12e7d2d5f', ''),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-22', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'elizamatine74@gmail.com', 'NoShow', '2023-06-20 18:24:57.000000', 'Ativa', 'e41a83b0db3e7b68474ea3f4f38f03f3', ''),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2023-06-20 20:14:47.000000', 'A9C2647F90', '0d04b631ff96ba3f646dc204279a9fa1', ''),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-23', '08:00:00.000000', 'Nova Sessão', 'Site', 'evanilsonsoliveira@gmail.com', 'Em Andamento', '2023-06-28 19:33:42.000000', 'Ativa', '281a59f024eb983fa8646d4df0b8812a', ''),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29', '15:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'exemplo@exemplo.com', 'Finalizada', '2023-06-29 11:52:51.000000', 'Ativa', 'f8280a9b16b10d6a40dcbc785595e73b', ''),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-04', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'iraesfoliano@gmail.com', 'NoShow', '2023-07-01 10:32:58.000000', 'Ativa', '69fc936eec40e2ad5510117eb5e379ca', ''),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-11', '15:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'shirleylaranjeira14@gmail.com', 'Finalizada', '2023-07-07 17:51:43.000000', 'Ativa', 'ce46db19549318224838f4523d3f23d7', ''),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-24', '16:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2023-07-22 10:05:37.000000', 'Ativa', '89a164a63a6c3eb421e804448d6fb3a9', ''),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-22', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2023-08-21 12:15:51.000000', '8ADB5BA70C', '83702a0192922ae7547d0059c5a67cc8', ''),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-04', '14:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'jr_rosant@hormail.com', 'Finalizada', '2023-08-09 15:22:36.000000', 'Ativa', '424f23601e1b1f9a7c0e8ef3cb7073d7', ''),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-11', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'teste@gmail.com', 'Finalizada', '2023-08-10 09:58:46.000000', 'Ativa', 'c08d39020cea67491777a6fbc079f2a2', ''),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-01', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2023-08-17 10:44:30.000000', 'Ativa', '56ec054dae36e9ce651976ed6b8c085b', ''),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-04', '16:30:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'andrezaveimrober@gmail.com', 'Finalizada', '2023-08-29 18:00:05.000000', 'Ativa', 'd9c8da67f6ccd26b54e9c995b3373292', ''),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11', '15:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'exemplo@exemplo.com', 'Finalizada', '2023-09-07 13:31:02.000000', 'Ativa', 'f2c46d58ff8009454649b3c3df95b332', ''),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-08', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Em Andamento', '2025-04-23 11:50:25.000000', '36F79230D5', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-17', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2024-01-16 16:21:22.000000', '796A4004AE', '05339a27091486a2ab71abfc55f753a8', 'Lauro de Freitas'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-10', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'exemplo@exemplo.com', 'Finalizada', '2023-10-09 09:44:37.000000', 'Ativa', 'ec184ec6c66b1c188f80ce2374fbaa76', ''),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-18', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2023-10-09 17:11:50.000000', '882F194377', 'fbc4efd9ecbf0f70bcbe1e5c64702867', ''),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-11', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2023-10-11 23:53:06.000000', 'Ativa', '037d0457f8cf8d40b48ba71307624e28', 'Lauro de Freitas'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-18', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Em Andamento', '2025-01-17 15:40:37.000000', '410A553324', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-16', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jr_losant@hotmail.com', 'Em Andamento', '2023-10-11 23:57:40.000000', 'Ativa', 'c2fc0c38105e12d101efeae9f96e72aa', ''),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-25', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Confirmada', '2025-01-17 15:40:19.000000', 'A746E9DF53', '6dcc8c58b1b3609b19b9da773c0837b7', 'Salvador'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-11', '09:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'fernandinhacruz2017@gmail.com', 'Finalizada', '2023-11-09 14:15:15.000000', '8D10B961B8', '1ecf6daa75c97badcb965b78af493f68', ''),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-16', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Em Andamento', '2023-11-06 15:53:19.000000', 'Ativa', '1b5d0ad0e1900d4aae02043845afb6f4', ''),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-09', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Em Andamento', '2023-11-16 15:09:46.000000', 'Ativa', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-27', '16:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'fabriciomjpop4321@gmail.com', 'Finalizada', '2023-11-24 23:35:41.000000', 'Ativa', '2908c334334fc56c38f6ded14aa723e7', 'Lauro de Freitas'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-05', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'fabriciomjpop4321@gmail.com', 'NoShow', '2023-12-05 12:09:42.000000', 'Ativa', '8be78e1d6d45695310cade4fe7a225c5', 'Lauro de Freitas'),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09', '09:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'filipeferreira99@hotmail.com', 'Finalizada', '2023-12-07 11:24:31.000000', 'Ativa', '71d6c6e7904c7422e7f8f445d4301a84', 'Lauro de Freitas'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'filipeferreira99@hotmail.com', 'Finalizada', '2023-12-09 10:10:47.000000', 'Ativa', '31f4f36f92b99e57247092ce9286210d', 'Lauro de Freitas'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-26', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'filipeferreira99@hotmail.com', 'Finalizada', '2023-12-11 17:34:00.000000', 'Ativa', '724539bf06c1ab1fe9ffc96e04b9a412', 'Lauro de Freitas'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-18', '16:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'fonseca.mayana@gmail.com', 'Finalizada', '2023-12-13 00:56:31.000000', 'Ativa', 'e1e401ee10788d056c779917b6f802c0', 'Salvador'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-07', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'filipeferreira99@hotmail.com', 'Finalizada', '2023-12-27 06:40:06.000000', 'Ativa', '00f2cdc6b92e2c23c242fdb023e71a43', 'Lauro de Freitas'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-06', '10:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'gadmessias@gmail.com', 'Finalizada', '2024-01-03 22:19:11.000000', 'Ativa', '664356bc8c06ee511f138b02425aca09', 'Salvador'),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-04', '16:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'tiago.manicoba@gmail.com', 'Finalizada', '2024-01-04 17:10:23.000000', 'Ativa', '41b20518d1e6ec22e7ce819d52521500', 'Lauro de Freitas'),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-12', '09:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'luaduques@hotmail.com', 'NoShow', '2024-01-08 12:12:34.000000', 'Ativa', '3cb380970ebbbd561a8b658e3bfaaa75', 'Salvador'),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-03', '09:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'camila-pita@hotmail.com', 'Finalizada', '2024-01-29 11:10:35.000000', 'Ativa', 'ffb3fb88d2656d10657cfd26903d4b89', 'Salvador'),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-16', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'vej2305@gmail.com', 'Finalizada', '2024-02-02 16:40:46.000000', 'Ativa', '204a606c4ec0cb009becfaad654d8075', 'Lauro de Freitas'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-26', '08:00:00.000000', 'Avaliação Capilar', 'Site', 'denis_ferraz359@hotmail.com', 'Finalizada', '2024-02-25 09:03:44.000000', '7B551483CC', 'a232c0ed5a7b1b0b46ce5e299dc4d341', 'Lauro de Freitas'),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-21', '16:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'rzyssapaz@gmail.com', 'Finalizada', '2024-03-07 17:00:56.000000', '8B1C632B63', '4107d7144d8c3e022ed3899a2b794243', 'Salvador'),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-04', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2024-03-12 09:39:19.000000', 'Ativa', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(79, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-28', '10:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'emersonsc1000@gmail.com', 'Finalizada', '2024-03-26 13:09:13.000000', 'Ativa', 'e6d2375430960c4273a152d93b891d8c', 'Salvador'),
(80, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-14', '17:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'camila-pita@hotmail.com', 'Finalizada', '2024-04-26 10:39:00.000000', 'Ativa', '554d7db4bc36e25bfcecf33da7bc58dd', 'Salvador'),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-06', '09:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'exemplo@exemplo.com.br', 'Finalizada', '2024-05-02 17:37:46.000000', 'Ativa', '39ce5eb44845dd293c0bdfb0da3cff73', 'Salvador'),
(82, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-10', '11:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'betamerces38@gmail.com', 'Finalizada', '2024-05-09 21:00:51.000000', 'Ativa', '4c06c966eaa2b690e92ecedd5ec11190', 'Lauro de Freitas'),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2024-05-20 16:52:01.000000', 'Ativa', 'f2baef5435379edb09d5a6d49305b179', 'Lauro de Freitas'),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-06', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2024-05-25 13:48:41.000000', '1787530313', 'e2bda2fc7ed357be4d725b947c16fe23', 'Lauro de Freitas'),
(85, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-17', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Em Andamento', '2024-09-17 13:40:36.000000', '5BA070D548', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(86, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-20', '16:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'vej2305@gmail.com', 'Finalizada', '2024-06-05 13:12:58.000000', 'Ativa', 'c0e8e8b635b15f8052829dd1ab3e34e0', 'Lauro de Freitas'),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-21', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Em Andamento', '2024-10-29 17:03:07.000000', '98BC371581', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(88, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-30', '13:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'andrezaveimrober@gmail.com', 'Finalizada', '2024-07-25 17:13:03.000000', 'Ativa', 'da1c2dfd899b9f858ab375724973c9c6', 'Lauro de Freitas'),
(89, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-23', '10:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'ldiasamino7@gmail.com', 'Finalizada', '2024-08-22 10:47:44.000000', 'Ativa', 'e0e681961302cc1a2cde26b672000570', 'Lauro de Freitas'),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13', '17:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'ldiasamino7@gmail.com', 'Finalizada', '2024-08-23 18:45:05.000000', 'Ativa', 'bf8baf49a053229bfcd28b2b94a827f7', 'Lauro de Freitas'),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-05', '10:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'maria.jdr@gmail.com', 'Finalizada', '2024-11-04 17:11:52.000000', 'B3D3B1DEC4', '9d1c9ddc838bb78471d040a2794f45ef', 'Salvador'),
(92, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-09', '11:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'elisamaandrade.m@gmail.com', 'Finalizada', '2024-11-09 10:16:04.000000', 'Ativa', '1cb9181ad8c6c577d429a50f4b165e6c', 'Salvador'),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-04-15 08:47:32.000000', '55443DEF96', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-13', '15:00:00.000000', 'Consulta Online', 'Caroline Ferraz', 'lambiasefisica@gmail.com', 'Finalizada', '2024-11-12 16:40:43.000000', 'Ativa', '53fb8597d6abd1538591650533ae279c', 'Lauro de Freitas'),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-26', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'danyelporto@gmail.com', 'Finalizada', '2025-04-25 00:15:55.000000', 'FE49D3EB54', 'e2a1dddb3b1dd388a48b55f236c2e623', 'Salvador'),
(96, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Cancelada', '2025-06-17 11:01:39.000000', '565405E129', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Lauro de Freitas'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15', '16:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'paterson.franco@gmail.com', 'Finalizada', '2025-01-08 19:22:11.000000', '611EF24801', '311c6352936906eb5398c5f70159997a', 'Salvador'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-25', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Confirmada', '2025-01-15 11:08:41.000000', 'Ativa', '37ba673f2703e981be31d39df6ffdd39', 'Salvador'),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-15', '10:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-02-11 14:52:20.000000', 'BF0118F4C9', 'c3b28b3297b1112928ba9be4cfca9334', 'Salvador'),
(100, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'raquel.d.santos110194@gmail.com', 'Finalizada', '2025-01-31 14:01:19.000000', 'Ativa', '0d3cf7ebe223c7ea1a921f66637aaab0', 'Lauro de Freitas'),
(101, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-07', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'adrianamagalhaes026@gmail.com', 'Em Andamento', '2025-02-10 10:43:17.000000', 'Ativa', '1de336fa35a32c8cbe6a44a2f4e99ffc', 'Lauro de Freitas'),
(102, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'lucassimas160@hotmail.com', 'Finalizada', '2025-02-11 14:53:00.000000', 'Ativa', '8acab63d1d28edde12f1804685e1682a', 'Salvador'),
(103, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-22', '10:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'emersonsc1000@gmail.com', 'Finalizada', '2025-02-11 15:12:31.000000', 'Ativa', 'a9077c00739034568d636996839b9b65', 'Salvador'),
(104, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-21', '15:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'camilacarine@outlook.com', 'Finalizada', '2025-02-20 18:33:29.000000', '166982F8AA', 'bb11e9c1e11afd5493d7a6315a02c5f1', 'Lauro de Freitas'),
(105, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-26', '15:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-02-26 09:38:24.000000', '02D4340AFB', 'f2dff5d260b3f3c97844b955273d372e', 'Salvador'),
(106, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-03', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'arianedasilvasantos2@gmail.com', 'Finalizada', '2025-03-06 14:28:52.000000', 'Ativa', 'c963f9313d148bdb5ea79c3ab9a590bb', 'Lauro de Freitas'),
(107, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15', '14:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'leideadrianonogieirasouza@gmail.com', 'Finalizada', '2025-04-15 08:47:17.000000', 'DE4A4191E8', '37b6264fbbbd936fe7409bfc1f768f6a', 'Salvador'),
(108, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erico_nascimento@hotmail.com', 'Confirmada', '2025-04-11 18:01:44.000000', 'Ativa', 'd8b70a0d3030e823d5207e023101ba24', 'Lauro de Freitas'),
(109, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16', '15:00:00.000000', 'Avaliação Capilar', 'Denis Ferraz', 'denis_ferraz359@hotmail.com', 'Finalizada', '2025-04-16 13:00:47.000000', 'F3F7DF9F21', 'ee5d74b65b7789ae850d9e6011af3fab', 'Lauro de Freitas'),
(110, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'livia.carvalho@msn.com', 'Finalizada', '2025-04-30 20:14:14.000000', 'Ativa', 'b16464e42f30538ff5cb3f02ff692de9', 'Lauro de Freitas'),
(111, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-06', '16:00:00.000000', 'Avaliação Capilar', 'Caroline Ferraz', 'iuryforte15@gmail.com', 'Finalizada', '2025-04-30 20:19:34.000000', 'Ativa', 'a1a3710d5fcdf26097f0ee4ca43dbe32', 'Salvador'),
(112, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12', '08:00:00.000000', 'Avaliação Capilar', 'Denis Ferraz', 'denis_ferraz359@hotmail.com', 'Finalizada', '2025-05-12 21:55:32.000000', 'Ativa', 'a8aa9a69e17566f1a50348bb1dd7100d', 'Lauro de Freitas'),
(113, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21', '16:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'jecsantos20@gmail.com', 'Finalizada', '2025-05-20 17:31:36.000000', 'Ativa', '610964a7401f29161f4f80dce627a9a5', 'Lauro de Freitas'),
(114, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-26', '10:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'weslenvini99@hotmail.com', 'Finalizada', '2025-05-22 17:03:36.000000', 'Ativa', '4d4c4615afb7774514a2361171a5e121', 'Salvador'),
(115, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-04-29', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(117, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-02', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3d02377849a4ad5b01bc6cbd68ed4dd3', 'Salvador'),
(118, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-10', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '888f06fce9e71d050306fb492a98741e', 'Salvador'),
(121, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-12', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(122, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-13', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'andreacrb03@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2e70f9960d416e58a56fb9ca23f045aa', 'Salvador'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-20', '13:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(125, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(126, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(127, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-19', '19:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(128, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-19', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(129, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-24', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(130, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '888f06fce9e71d050306fb492a98741e', 'Salvador'),
(131, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-25', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d8e811e2d60318b12b30ea60b82131e', 'Salvador'),
(132, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-25', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d8e811e2d60318b12b30ea60b82131e', 'Salvador'),
(133, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-31', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(134, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'amandalgarcez@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'dc697c2a966748a66b57af1a203f1901', 'Salvador'),
(136, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-27', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(137, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d8e811e2d60318b12b30ea60b82131e', 'Salvador'),
(139, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(140, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-02', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(141, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-15', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '888f06fce9e71d050306fb492a98741e', 'Salvador'),
(142, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-08', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'henrique.amon@saude.ba.gov.br', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d8e811e2d60318b12b30ea60b82131e', 'Salvador'),
(143, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-14', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(144, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-06', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(145, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-07', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(146, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(148, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17', '13:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(149, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(150, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '888f06fce9e71d050306fb492a98741e', 'Salvador'),
(152, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-22', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'elizamatine74@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e41a83b0db3e7b68474ea3f4f38f03f3', 'Salvador'),
(154, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-28', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(155, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(156, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-27', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'krinasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'bc5aba92df6ec3ce34fa06979456bb85', 'Salvador'),
(157, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-27', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(159, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-08', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'evanilsonsoliveira@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '281a59f024eb983fa8646d4df0b8812a', 'Salvador'),
(161, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-13', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '888f06fce9e71d050306fb492a98741e', 'Salvador'),
(162, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-04', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'iraesfoliano@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '69fc936eec40e2ad5510117eb5e379ca', 'Salvador'),
(164, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-05', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(165, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-12', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(167, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-18', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(168, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-15', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(169, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-19', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(170, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-12', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(171, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-22', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'evanilsonsoliveira@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '281a59f024eb983fa8646d4df0b8812a', 'Salvador'),
(172, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-25', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(174, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-05', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'evanilsonsoliveira@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '281a59f024eb983fa8646d4df0b8812a', 'Salvador'),
(176, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-27', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '83702a0192922ae7547d0059c5a67cc8', 'Salvador'),
(177, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'alinerochas@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'a4aa8d1390b47347aa040a5b58dba564', 'Salvador'),
(178, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '83702a0192922ae7547d0059c5a67cc8', 'Salvador'),
(179, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-02', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(180, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '83702a0192922ae7547d0059c5a67cc8', 'Salvador'),
(181, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-19', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'evanilsonsoliveira@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '281a59f024eb983fa8646d4df0b8812a', 'Salvador'),
(183, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-10', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(185, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-11', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'teste@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c08d39020cea67491777a6fbc079f2a2', 'Salvador'),
(187, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(188, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-09', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(189, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-16', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'michel.oliveira2701@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '83702a0192922ae7547d0059c5a67cc8', 'Salvador'),
(190, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-18', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(191, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-23', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(192, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-01', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(194, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-29', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(195, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-04', '17:30:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'andrezaveimrober@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'd9c8da67f6ccd26b54e9c995b3373292', 'Salvador'),
(197, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-09', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'evanilsonsoliveira@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '281a59f024eb983fa8646d4df0b8812a', 'Salvador'),
(198, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-14', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(199, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-08', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(202, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-03', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(203, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-20', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(204, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(205, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-27', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(206, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-27', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(207, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-10', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '05339a27091486a2ab71abfc55f753a8', 'Salvador'),
(208, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-10', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '05339a27091486a2ab71abfc55f753a8', 'Salvador'),
(209, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-06', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(210, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-20', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscila_nutri89@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '56ec054dae36e9ce651976ed6b8c085b', 'Salvador'),
(212, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-18', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(213, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-13', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '037d0457f8cf8d40b48ba71307624e28', 'Salvador'),
(214, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-14', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(215, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-14', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(216, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-16', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jr_losant@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c2fc0c38105e12d101efeae9f96e72aa', 'Salvador'),
(217, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-27', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '037d0457f8cf8d40b48ba71307624e28', 'Salvador'),
(218, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-24', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jr_losant@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c2fc0c38105e12d101efeae9f96e72aa', 'Salvador'),
(219, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(220, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-21', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(222, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-30', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jr_losant@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c2fc0c38105e12d101efeae9f96e72aa', 'Salvador'),
(223, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-01', '18:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(224, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jr_losant@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c2fc0c38105e12d101efeae9f96e72aa', 'Salvador'),
(225, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(226, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-09', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '1b5d0ad0e1900d4aae02043845afb6f4', 'Salvador'),
(227, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-09', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '1b5d0ad0e1900d4aae02043845afb6f4', 'Salvador'),
(229, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-17', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Salvador'),
(230, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-10', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '037d0457f8cf8d40b48ba71307624e28', 'Salvador'),
(232, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(233, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(234, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '05339a27091486a2ab71abfc55f753a8', 'Salvador'),
(235, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-20', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(236, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-28', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '05339a27091486a2ab71abfc55f753a8', 'Salvador'),
(237, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-12', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(238, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-28', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '037d0457f8cf8d40b48ba71307624e28', 'Lauro de Freitas'),
(239, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-27', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'fabriciomjpop4321@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2908c334334fc56c38f6ded14aa723e7', 'Salvador'),
(241, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-05', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Kaiqueeecr7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '05339a27091486a2ab71abfc55f753a8', 'Salvador'),
(242, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-14', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(243, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Lauro de Freitas'),
(245, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'priscilaeve@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '037d0457f8cf8d40b48ba71307624e28', 'Lauro de Freitas'),
(248, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-16', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(249, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(251, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-18', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'fonseca.mayana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e1e401ee10788d056c779917b6f802c0', 'Salvador'),
(253, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-11', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(255, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-04', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'tiago.manicoba@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '41b20518d1e6ec22e7ce819d52521500', 'Salvador'),
(257, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-12', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luaduques@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3cb380970ebbbd561a8b658e3bfaaa75', 'Salvador'),
(259, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-15', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(260, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'manucassia@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '31946ec0f29162557b632db0732d02d7', 'Lauro de Freitas'),
(262, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(264, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-01', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(265, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-03', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camila-pita@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'ffb3fb88d2656d10657cfd26903d4b89', 'Salvador'),
(267, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-16', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'vej2305@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '204a606c4ec0cb009becfaad654d8075', 'Salvador'),
(271, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-12', '13:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(272, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-14', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(273, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-21', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(274, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-02', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(275, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-11', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(276, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-28', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'emersonsc1000@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e6d2375430960c4273a152d93b891d8c', 'Salvador'),
(278, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas');
INSERT INTO `consultas` (`id`, `token_emp`, `atendimento_dia`, `atendimento_hora`, `tipo_consulta`, `feitapor`, `doc_email`, `status_consulta`, `data_cancelamento`, `confirmacao_cancelamento`, `token`, `local_consulta`) VALUES
(279, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-19', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(280, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-17', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(281, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-16', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(282, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-02', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Lauro de Freitas'),
(283, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-06', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(285, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-10', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'betamerces38@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4c06c966eaa2b690e92ecedd5ec11190', 'Salvador'),
(287, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25', '13:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(288, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-13', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(289, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-03', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(290, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-31', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(291, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-31', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(292, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-07', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(293, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(295, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-17', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(296, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-14', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(297, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-03', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(298, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-05', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(300, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-15', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(301, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-08', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(302, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-11', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(303, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-13', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(304, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-18', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(305, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-09', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(306, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-08', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(307, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-26', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(308, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-29', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(309, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-30', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(310, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-23', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(311, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-30', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'andrezaveimrober@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'da1c2dfd899b9f858ab375724973c9c6', 'Salvador'),
(313, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-01', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(314, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-15', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(315, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-12', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'camile.ac@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '5cffe3893e04163d642329cee5444152', 'Salvador'),
(316, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-17', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(317, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-29', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(318, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-05', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(319, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-29', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(320, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-23', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'ldiasamino7@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e0e681961302cc1a2cde26b672000570', 'Salvador'),
(322, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-30', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(323, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-12', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(324, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-29', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(325, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-10', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(326, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-30', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(327, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-26', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(328, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-28', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(329, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-27', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(330, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-01', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(331, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-19', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(332, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-24', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'neilsonrabelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '2ce2b3647d8ed37c96221f2e0fd2ba0f', 'Salvador'),
(333, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-15', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(334, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-18', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(335, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-05', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(336, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-14', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(337, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-09', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(339, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-07', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'caroline_lordelo@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4845502fbfbafb6e39255cc3a2396b61', 'Salvador'),
(340, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-22', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(341, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-22', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(343, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-13', '13:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'danyelporto@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e2a1dddb3b1dd388a48b55f236c2e623', 'Salvador'),
(344, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-22', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(345, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-06', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(346, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-06', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(347, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-12', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(348, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-12', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Salvador'),
(349, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(350, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-17', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(351, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-16', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Lauro de Freitas'),
(352, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(353, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-07', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(354, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-20', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Lauro de Freitas'),
(355, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-08', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Lauro de Freitas'),
(356, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-24', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Lauro de Freitas'),
(357, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-21', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(358, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-17', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Salvador'),
(359, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(360, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-12', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'raquel.d.santos110194@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d3cf7ebe223c7ea1a921f66637aaab0', 'Salvador'),
(361, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-12', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'raquel.d.santos110194@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '0d3cf7ebe223c7ea1a921f66637aaab0', 'Salvador'),
(362, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'email@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3abd39f316745cd440cc4e99b0f84f9f', 'Salvador'),
(363, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(364, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(365, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-26', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(366, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-12', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'adrianamagalhaes026@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '1de336fa35a32c8cbe6a44a2f4e99ffc', 'Salvador'),
(367, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-12', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'adrianamagalhaes026@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '1de336fa35a32c8cbe6a44a2f4e99ffc', 'Salvador'),
(368, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-13', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'lucassimas160@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '8acab63d1d28edde12f1804685e1682a', 'Salvador'),
(371, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-22', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'danyelporto@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'e2a1dddb3b1dd388a48b55f236c2e623', 'Salvador'),
(372, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-17', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Salvador'),
(373, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-02', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(374, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-10', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'arianedasilvasantos2@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c963f9313d148bdb5ea79c3ab9a590bb', 'Salvador'),
(375, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-10', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'arianedasilvasantos2@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c963f9313d148bdb5ea79c3ab9a590bb', 'Salvador'),
(376, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-13', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(377, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-14', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(378, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-18', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(379, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-18', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(380, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-18', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(381, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Salvador'),
(383, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-19', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'bruno.ferreira88@icloud.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '227d722f2cda1fff229ff524b3c2fec5', 'Salvador'),
(384, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-12', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '15663d271059bf6aa62640c2cce8d90b', 'Salvador'),
(385, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16', '14:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Salvador'),
(386, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'everton.pinheiro@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'aeacd4fdcab20ad5460ef4ac73caaed6', 'Salvador'),
(387, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-24', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(388, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-03', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erico_nascimento@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c1dc038d5705e33d5d7594c23261db6b', 'Lauro de Freitas'),
(389, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-03', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erico_nascimento@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c1dc038d5705e33d5d7594c23261db6b', 'Lauro de Freitas'),
(390, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-24', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(391, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-24', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(392, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'eli.trevo@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '72b135af9f44da8d3c4d4caeae256383', 'Salvador'),
(393, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-06', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'brenoalmeidasantana@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '380e27d06c5ad33b7887a7071e0793c5', 'Salvador'),
(394, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-06', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(396, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-03', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'livia.carvalho@msn.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'b16464e42f30538ff5cb3f02ff692de9', 'Salvador'),
(397, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-03', '08:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'livia.carvalho@msn.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'b16464e42f30538ff5cb3f02ff692de9', 'Salvador'),
(401, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22', '12:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'luciana_gomes@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '3e24807a8e40b1b0afaf02c3bb1bcf50', 'Salvador'),
(402, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-19', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'sand11cost@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'f90041c8d317b97a99dd9744c59e4972', 'Lauro de Freitas'),
(403, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-24', '09:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'livia.carvalho@msn.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'b16464e42f30538ff5cb3f02ff692de9', 'Salvador'),
(407, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'jecsantos20@gmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '610964a7401f29161f4f80dce627a9a5', 'Salvador'),
(410, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-29', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'Rosana_silva654@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '6c37362af2ebe7b977ec1dab5fd10233', 'Salvador'),
(413, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-26', '11:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'weslenvini99@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', '4d4c4615afb7774514a2361171a5e121', 'Salvador'),
(415, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-24', '10:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erico_nascimento@hotmail.com', 'Finalizada', '2025-05-31 00:00:00.000000', '', 'c1dc038d5705e33d5d7594c23261db6b', 'Lauro de Freitas'),
(421, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-11', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'erikadourado14@gmail.com', 'Confirmada', '2025-06-01 18:04:55.000000', 'Ativa', '74db49497a1701964020ac19ce14a084', 'Lauro de Freitas'),
(423, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '13:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'raquelsinal3@gmail.com', 'Finalizada', '2025-06-03 13:57:01.000000', 'Ativa', 'e838399ebf6f5823cf7df353477c0b1e', 'Lauro de Freitas'),
(424, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-25', '18:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'fauqueiroz@hotmail.com', 'Confirmada', '2025-06-06 16:01:56.000000', 'Ativa', 'aee67b35af36e3e5a6b56dba7749c679', 'Salvador'),
(425, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-11', '15:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'livia.carvalho@msn.com', 'Confirmada', '2025-06-07 10:19:41.000000', 'Ativa', 'cb81022737e3c625f6cc6eace4c62ad4', 'Lauro de Freitas'),
(426, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-25', '12:00:00.000000', 'Nova Sessão', 'Luciana Gomes', 'luciana_gomes@hotmail.com', 'Confirmada', '2025-06-09 12:08:24.000000', 'Ativa', '174b7e8666c1ff3f5be918bf88631c3e', 'Salvador'),
(427, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-12', '16:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'raquel.d.santos110194@gmail.com', 'Confirmada', '2025-06-13 16:55:43.000000', 'Ativa', '8d67ccf7349b1889f982b1261a20043e', 'Lauro de Freitas'),
(428, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '17:00:00.000000', 'Nova Sessão', 'Caroline Ferraz', 'ldiasamino7@gmail.com', 'Finalizada', '2025-06-13 18:06:27.000000', 'Ativa', '935876568be52b45f6a431e1ddcae0ef', 'Lauro de Freitas'),
(429, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '10:00:00.000000', 'Consulta Capilar', 'Caroline Ferraz', 'karla.amorim@gmail.com', 'Finalizada', '2025-06-16 11:17:15.000000', 'Ativa', 'b21855ee335e31b78ac798128f8fcd6a', 'Lauro de Freitas'),
(430, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-16', '14:00:00.000000', 'Nova Sessão', 'Sandra Maria de Assis Costa', 'sand11cost@gmail.com', 'Confirmada', '2025-06-18 12:50:10.000000', 'Ativa', 'f25ea0d911b19701b0fe4887527ca8fd', 'Lauro de Freitas'),
(431, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-04', '17:00:00.000000', 'Nova Sessão', 'Larissa Dias dos Santos ', 'ldiasamino7@gmail.com', 'Confirmada', '2025-06-18 18:19:38.000000', 'Ativa', '19092131c5ca814b00c1a06aefbd82a7', 'Lauro de Freitas'),
(432, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-26', '14:00:00.000000', 'Avaliação Capilar', 'Weslen Vinicius de Souza goes', 'weslenvini99@hotmail.com', 'Confirmada', '2025-06-20 13:49:57.000000', 'Ativa', 'd4da5ad40c9213bb9620d9c988d68779', 'Salvador');

-- --------------------------------------------------------

--
-- Table structure for table `contas`
--

CREATE TABLE `contas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `sub_grupo_id` int(11) DEFAULT NULL,
  `ativa` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contas`
--

INSERT INTO `contas` (`id`, `codigo`, `descricao`, `tipo_id`, `grupo_id`, `sub_grupo_id`, `ativa`, `created_at`) VALUES
(1, 'RS1', 'Todos os serviços da clinica', 1, 1, 1, 1, '2025-06-15 04:14:33'),
(12, 'RF1', 'Investimento', 1, 2, 2, 1, '2025-06-15 04:14:33'),
(14, 'RO1', 'Parcerias', 1, 3, 3, 1, '2025-06-15 04:14:33'),
(16, 'DS1', 'Matérias Primas', 2, 4, 4, 1, '2025-06-15 04:14:33'),
(18, 'DPP1', 'Pro-labore', 2, 5, 5, 1, '2025-06-15 04:14:33'),
(23, 'DC1', 'Social Media', 2, 6, 6, 1, '2025-06-15 04:14:33'),
(24, 'DC2', 'Tráfego Google Ads', 2, 6, 7, 1, '2025-06-15 04:14:33'),
(25, 'DC3', 'Video Maker', 2, 6, 8, 1, '2025-06-15 04:14:33'),
(26, 'DC4', 'Impressos e papelaria', 2, 6, 9, 1, '2025-06-15 04:14:33'),
(27, 'DC5', 'Brindes pacientes', 2, 6, 10, 1, '2025-06-15 04:14:33'),
(28, 'DC6', 'Gestor de Trafego', 2, 6, 11, 1, '2025-06-15 04:14:33'),
(32, 'DA1', 'Coworking', 2, 7, 12, 1, '2025-06-15 04:14:33'),
(33, 'DA2', 'Conta de Celular', 2, 7, 13, 1, '2025-06-15 04:14:33'),
(34, 'DA3', 'Contabilidade', 2, 7, 14, 1, '2025-06-15 04:14:33'),
(35, 'DA4', 'Seguros', 2, 7, 15, 1, '2025-06-15 04:14:33'),
(36, 'DA5', 'Gasolina', 2, 7, 16, 1, '2025-06-15 04:14:33'),
(37, 'DA6', 'Dominio site', 2, 7, 17, 1, '2025-06-15 04:14:33'),
(38, 'DA7', 'Software', 2, 7, 18, 1, '2025-06-15 04:14:33'),
(39, 'DA8', 'Estacionamento', 2, 7, 19, 1, '2025-06-15 04:14:33'),
(40, 'DA9', 'Alimentação', 2, 7, 20, 1, '2025-06-15 04:14:33'),
(48, 'DF1', 'Despesas e Tarifas Bancárias', 2, 8, 21, 1, '2025-06-15 04:14:33'),
(51, 'DI1', 'DAS', 2, 9, 22, 1, '2025-06-15 04:14:33'),
(52, 'DI2', 'Imposto Federal/Estadual/Mun', 2, 9, 23, 1, '2025-06-15 04:14:33'),
(53, 'DI3', 'INSS', 2, 9, 24, 1, '2025-06-15 04:14:33'),
(54, 'DI4', 'IOF', 2, 9, 25, 1, '2025-06-15 04:14:33'),
(58, 'IV2', 'Cursos e Materiais de Estudo', 2, 10, 26, 1, '2025-06-15 04:14:33'),
(59, 'IV3', 'Máquinas e Equipamentos', 2, 10, 27, 1, '2025-06-15 04:14:33'),
(60, 'IV4', 'Parcerias', 2, 10, 28, 1, '2025-06-15 04:14:33'),
(61, 'IV5', 'Investimento Financeiro', 2, 10, 29, 1, '2025-06-15 04:14:33'),
(69, 'RS2', 'Estornos', 1, 1, 1, 1, '2025-06-15 12:52:50'),
(70, 'EN1', 'Energia', 2, 12, NULL, 1, '2025-06-15 13:39:36'),
(71, 'EN2', 'Agua', 2, 12, NULL, 1, '2025-06-15 13:39:50'),
(72, 'EN3', 'Gás', 2, 12, NULL, 1, '2025-06-15 13:40:01');

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

--
-- Dumping data for table `contrato`
--

INSERT INTO `contrato` (`id`, `token_emp`, `email`, `assinado`, `assinado_data`, `assinado_empresa`, `assinado_empresa_data`, `procedimento`, `procedimento_dias`, `procedimento_valor`, `aditivo_valor`, `aditivo_procedimento`, `aditivo_status`, `token`) VALUES
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Não', '2023-05-12 15:42:49', 'Sim', '2023-05-02 19:17:14', '1 Sessão de Laser<br>1 Sessão de Microagulhamento', 15, 'R$ 590,00 sendo R$127,00 em pix e R$463,00 no cartão de credito dividido em 2x', '-', '-', 'Não', '053dd635bc2b4ad53c47240ff20d29f6'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Não', '2023-05-07 19:08:43', 'Sim', '2023-05-07 19:08:43', '4 Sessões de Fotobioestimulação com Laser<br>2 Sessões SPA dos Fios<br>2 Sessões de Blend de óleos no couro cabeludo', 15, 'R$ 1380,00 Parcelado em 7x sem juros', '-', '-', 'Não', '076ee3cce883300a9be2e8d8e72c40be'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Sim', '2023-05-18 16:31:54', 'Sim', '2023-05-07 19:33:35', '3 Sessões de Microagulhamento<br> 3 Sessões de Fotobioestimulação com Laser<br> 2 Sessões de Terapia ILIB<br> 1 Sessão brinde de Terapia ILIB', 15, 'R$1870,00 parcelado em 10x sem juros ', '-', '-', 'Não', 'a65e2c05eee407b2f5609fbf0ee6248e'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Não', '2023-05-24 17:05:06', 'Sim', '2023-05-07 19:38:15', '3 Sessões de Terapia com Laser<br> 1 Sessão de microagulhamento', 15, 'R$1050,00 sendo R$ 500,00 em dinheiro e R$ 550,00 em cartão de débito', '-', '-', 'Não', '35bfb3518df572b44520bb9a3d7bad7a'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Não', '2023-05-23 19:16:46', 'Sim', '2023-05-23 19:16:46', '3 Sessões de Fotobioestimulação com Laser<br>1 Sessão de Microagulhamento com drug delivery', 15, 'R$1050,00 em cartão de débito', '-', '-', 'Não', '0c6254026d4568e76ae844079152672a'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Não', '2023-06-06 19:08:35', 'Sim', '2023-06-06 19:08:35', '3 Sessões de Fotobioestimulação com laser <br>1 Sessão de Microagulhamento com drug delivery', 15, 'R$ 997,00 EM PIX', '-', '-', 'Não', '9e31b171d57d0ac76a51b1d823cba4a5'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'denis_ferraz359@hotmail.com', 'Sim', '2025-05-31 18:54:01', 'Sim', '2023-07-02 22:51:30', 'bado viado', 15, 'R$1.000,00 parcelado em 10x de R$100,00', '-', '-', 'Não', '8fb2f79127aa6eaf63369e4f7bb0c4a7'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Não', '2023-07-27 17:51:08', 'Sim', '2023-07-27 17:51:08', '3 Sessões de Fotobiomodulação com Laser <br> 1 Sessão de Microagulhamento', 15, 'R$ 976,00 em espécie', '-', '-', 'Não', 'b10ea2e38723a154dc88c4a21c2b49f2'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Não', '2023-08-05 09:17:48', 'Sim', '2023-08-05 09:17:48', '3 Sessões de Microagulhamento<br>3 Sessões de fotobiomodulação com laser', 15, 'R$1990,00 parcelado em 10x sem juros', '-', '-', 'Não', 'a9c2e48eeeda2544f3d6654a82d805dc'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Não', '2023-09-03 17:23:10', 'Sim', '2023-09-03 17:23:10', '3 Sessões de Laser<BR>1 Sessão de Microagulhamento com drug delivery<BR>2 Sessões de Intradermoterapia', 15, 'R$ 1550,00 Parcelado em 6x s/ juros', '-', '-', 'Não', '1895c16fe111a0a40d9714d52f6e361f'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Não', '2023-10-11 23:58:20', 'Sim', '2023-10-11 23:58:20', '4 Sessões de laserterapia', 15, 'R$ 960,00 parcelado em 2x sem juros ', '-', '-', 'Não', 'a0ad0b171b15e855ce078c9ec216e480'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Não', '2023-10-13 12:15:38', 'Sim', '2023-10-13 12:15:38', '2 Sessões de Laserterapia>br>2 Sessões de Microagulhamento>br>2 Sessões de Intradermoterapia', 15, 'R$ 1740,00', '-', '-', 'Não', 'cbb766534b190db0616dc9ec69f4a288'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Não', '2023-10-13 12:17:27', 'Sim', '2023-10-13 12:17:27', '2 Sessões de Laserterapia<br>2 Sessões de Microagulhamento<br> 2 Sessões de Intradermoterapia', 15, 'R$ 1740,00', '-', '-', 'Não', 'f7dcab111f422dca1104b45feaf5f396'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Não', '2023-10-13 12:19:38', 'Sim', '2023-10-13 12:19:38', 'Sessão intradermoterapia mensal', 15, 'R$ 250,000', '-', '-', 'Não', 'ca7c39fb4d36bea9e37a7f8616528204'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Não', '2023-11-07 18:49:16', 'Sim', '2023-11-07 18:49:16', '6 sessões de tratamento em consultório', 15, 'R$1620,00 pago em 6x por sessão em dinheiro', '-', '-', 'Não', '584b6f08426a243c04dcd1d7e69c4ef9'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Não', '2023-11-07 19:33:38', 'Sim', '2023-11-07 19:33:38', '4 SESSÕES DE TRATAMENTO EM CONSULTÓRIO', 15, 'R$ 980,00 EM PIX COM 5% DE DESCONTO', '-', '-', 'Não', '9204655a460242b886d19b2d7449fdc7'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Não', '2023-11-17 16:58:46', 'Sim', '2023-11-17 16:58:46', '6 Sessões com tecnologia em Consultório<br>', 15, 'R$ 1740,00 EM 3X SEM JUROS', '-', '-', 'Não', '40f9f2bff2df0378bf6f6ea8c57f5c75'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Não', '2024-06-04 14:03:46', 'Sim', '2024-06-04 14:03:46', '4 sessões de Laserterapia<br> 1 Sessão de Microagulhamento', 15, 'R$1187,50em débito automàtico', '-', '-', 'Não', '407a8b0dc63bb2c0f8cbe7de80826ac3'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Não', '2024-08-09 19:41:13', 'Sim', '2024-08-09 19:41:13', 'Sessão de intradermoterapia mensal', 15, 'R$ 107,00 em pix mensalmente  referente a mão de obra ', '-', '-', 'Não', 'a6472a8788b5c364fb826b414e9a203c'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'neilsonrabelo@hotmail.com', 'Não', '2024-08-15 09:06:29', 'Sim', '2024-08-14 17:44:45', '2 SESSÕES DE FOTOBIOMODULAÇÃO COM LASER', 15, 'R$550,00 EM 3X SEM JUROS ', '-', '-', 'Não', 'ec8f026201b59991210ff2c77546bc3f'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Não', '2024-12-14 12:33:15', 'Sim', '2024-12-14 12:33:15', 'PLANO DE TRATAMENTO HAIRCUPERE POR 6 MESES', 15, 'RS1980,00 PARCELADO EM 7X NO CARTÃO DE CRÉDITO', '-', '-', 'Não', '4e03728f5d095308118b0ef9902a52f3'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Não', '2025-04-17 11:16:05', 'Sim', '2025-04-17 11:16:05', 'Programa Haircupere Clinica<br> Sessão mensal de injetáveis associado a laserterapia', 30, 'R$ 2580,00 Parcelado em 6x sem juros ', '-', '-', 'Não', '93c4d844bb8f986bcb006f2a4d42f229'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Não', '2025-05-12 15:21:04', 'Sim', '2025-05-12 15:21:04', 'Programa Haircupere Clinica Personalizado por 3 meses<br>Programa Haircupere Home Care personalizado por 3 meses totalizando 6 meses de tratamento', 30, 'R$ 1500,00 sendoR$ 500,00 abatido referente a credito anterior e  R$1000,00 pago a vista via pix .', '-', '-', 'Não', '1f5e630453f3cab6c9d91690eafdb211'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'pacienteteste@pacienteteste.com', 'Não', '2025-06-01 18:43:35', 'Sim', '2025-06-01 18:43:35', 'TESTE TESTE TESTE', 30, 'R$ 1000 PAGO EM CARTAO', '-', '-', 'Não', 'ea5c02b6c73b249624c942100d1c3a2a'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Não', '2025-06-09 11:19:19', 'Sim', '2025-06-09 11:19:19', 'Programa HaiRcupere Clinica (6 sessões em Consultório de procedimento injetável associado a Laserterapia)', 30, 'R$ 2382,00(Dois mil e Trezentos e oitenta e dois reais) sendo R$600,00 via pix e R$1782,00 Parcelado em 6x sem juros ', '-', '-', 'Não', '');

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

--
-- Dumping data for table `custos`
--

INSERT INTO `custos` (`id`, `token_emp`, `custo_valor`, `custo_tipo`, `custo_descricao`, `custo_quem`) VALUES
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 54.17, 'Hora', 'Hora Trabalhada', 'Denis Ferraz'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 1, 'Insumos', 'Matéria Prima', 'Denis Ferraz'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 31, 'Gasolina', 'Gasolina SSA', 'Denis Ferraz'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 13, 'Gasolina', 'Gasolina Lauro', 'Denis Ferraz'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 'Estacionamento', 'Estacionamento SSA', 'Denis Ferraz'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 'Estacionamento', 'Estacionamento Lauro', 'Denis Ferraz'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 48, 'Coworking', 'Coworking SSA', 'Denis Ferraz'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 50, 'Coworking', 'Coworking Lauro', 'Denis Ferraz'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 'Impostos', 'Imposto', 'Denis Ferraz'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5.65, 'Taxas', 'Taxa Cartão', 'Denis Ferraz'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 40, 'Margem', 'Margem 40%', 'Denis Ferraz'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 70, 'Margem', 'Margem 70%', 'Denis Ferraz'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9.69, 'Taxas', 'Taxa Cartão', 'Denis Ferraz'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 1, 'Outros', 'Custo Fixo', 'Denis Ferraz');

-- --------------------------------------------------------

--
-- Table structure for table `custos_tratamentos`
--

CREATE TABLE `custos_tratamentos` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `tratamento_id` int(11) NOT NULL,
  `custo_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custos_tratamentos`
--

INSERT INTO `custos_tratamentos` (`id`, `token_emp`, `tratamento_id`, `custo_id`, `quantidade`) VALUES
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 3, 6),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 4, 720),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 5, 6),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 7, 6),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 9, 6),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 11, 1),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 15, 1),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 3, 6),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 4, 720),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 6, 6),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 8, 6),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 10, 6),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 11, 1),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 15, 1),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 6, 13, 1),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 5, 13, 1),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 3, 1),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 4, 65),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 5, 1),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 7, 1),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 9, 1),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 11, 1),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 12, 1),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 7, 13, 1),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 3, 1),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 4, 65),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 6, 1),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 8, 1),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 10, 1),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 11, 1),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 12, 1),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 13, 1),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 3, 1),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 4, 100),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 9, 1),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 5, 1),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 7, 1),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 11, 1),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 12, 1),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 13, 1),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 3, 1),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 4, 100),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 10, 1),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 8, 1),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 6, 1),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 11, 1),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 12, 1),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 10, 13, 1),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 11, 16, 140),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 12, 16, 107),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 13, 16, 160),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 18, 3, 1),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 18, 11, 1),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 18, 12, 1),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 18, 14, 1),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 3, 1),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 4, 20),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 5, 1),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 7, 1),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 9, 1),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 11, 1),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 12, 1),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 13, 1),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 3, 1),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 4, 20),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 10, 1),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 8, 1),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 6, 1),
(79, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 13, 1),
(80, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 11, 1),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 12, 1),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 4, 62),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 5, 1),
(85, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 7, 1),
(86, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 9, 1),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 11, 1),
(88, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 13, 1),
(89, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 12, 1),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 3, 2),
(92, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 6, 1),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 8, 1),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 10, 1),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 11, 1),
(96, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 13, 1),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 12, 1),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 4, 62),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 3, 2);

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

--
-- Dumping data for table `despesas`
--

INSERT INTO `despesas` (`id`, `token_emp`, `despesa_dia`, `despesa_valor`, `despesa_tipo`, `despesa_descricao`, `despesa_quem`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-05', 725, 'Aluguel', 'R$1450,00 PAGO METADE DO VALOR', 'Caroline Ferraz'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-05', 40, 'Internet', 'Internet: 79,00 pago metade do valor', 'Caroline Ferraz'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-15', 35, 'Outros', 'Plano de celular', 'Caroline Ferraz'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', 68, 'Mobiliario', 'cadeira', 'Caroline Ferraz'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', 69, 'Outros', 'software de avaliação de exames', 'Caroline Ferraz'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', 1102, 'Outros', 'Cursos', 'Caroline Ferraz'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', 1102, 'Outros', 'Cursos', 'Caroline Ferraz'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-20', 1102, 'Outros', 'Cursos', 'Caroline Ferraz'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30', 519.34, 'Insumos', 'Materias primas ', 'Caroline Ferraz');

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

--
-- Dumping data for table `disponibilidade`
--

INSERT INTO `disponibilidade` (`id`, `token_emp`, `atendimento_dia`, `atendimento_hora`, `data_alteracao`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '13:00:00', '2025-05-31 21:00:26'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '14:00:00', '2025-05-31 21:00:26'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '15:00:00', '2025-05-31 21:00:26'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '16:00:00', '2025-05-31 21:00:26'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '17:00:00', '2025-05-31 21:00:26'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', '18:00:00', '2025-05-31 21:00:26'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '13:00:00', '2025-05-31 21:00:26'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '14:00:00', '2025-05-31 21:00:26'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '15:00:00', '2025-05-31 21:00:26'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '16:00:00', '2025-05-31 21:00:26'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '17:00:00', '2025-05-31 21:00:26'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07', '18:00:00', '2025-05-31 21:00:26'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '13:00:00', '2025-05-31 21:00:26'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '14:00:00', '2025-05-31 21:00:26'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '15:00:00', '2025-05-31 21:00:26'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '16:00:00', '2025-05-31 21:00:26'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '17:00:00', '2025-05-31 21:00:26'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14', '18:00:00', '2025-05-31 21:00:26'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '13:00:00', '2025-05-31 21:00:26'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '14:00:00', '2025-05-31 21:00:26'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '15:00:00', '2025-05-31 21:00:26'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '16:00:00', '2025-05-31 21:00:26'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '17:00:00', '2025-05-31 21:00:26'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-21', '18:00:00', '2025-05-31 21:00:26'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '13:00:00', '2025-05-31 21:00:26'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '14:00:00', '2025-05-31 21:00:26'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '15:00:00', '2025-05-31 21:00:26'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '16:00:00', '2025-05-31 21:00:26'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '17:00:00', '2025-05-31 21:00:26'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '18:00:00', '2025-05-31 21:00:26'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '13:00:00', '2025-05-31 21:00:26'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '14:00:00', '2025-05-31 21:00:26'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '15:00:00', '2025-05-31 21:00:26'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '16:00:00', '2025-05-31 21:00:26'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '17:00:00', '2025-05-31 21:00:26'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-05', '18:00:00', '2025-05-31 21:00:26'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '13:00:00', '2025-05-31 21:00:26'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '14:00:00', '2025-05-31 21:00:26'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '15:00:00', '2025-05-31 21:00:26'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '16:00:00', '2025-05-31 21:00:26'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '17:00:00', '2025-05-31 21:00:26'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-12', '18:00:00', '2025-05-31 21:00:26'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '13:00:00', '2025-05-31 21:00:26'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '14:00:00', '2025-05-31 21:00:26'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '15:00:00', '2025-05-31 21:00:26'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '16:00:00', '2025-05-31 21:00:26'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '17:00:00', '2025-05-31 21:00:26'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-19', '18:00:00', '2025-05-31 21:00:26'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '13:00:00', '2025-05-31 21:00:26'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '14:00:00', '2025-05-31 21:00:26'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '15:00:00', '2025-05-31 21:00:26'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '16:00:00', '2025-05-31 21:00:26'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '17:00:00', '2025-05-31 21:00:26'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-07-26', '18:00:00', '2025-05-31 21:00:26'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '13:00:00', '2025-05-31 21:00:26'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '14:00:00', '2025-05-31 21:00:26'),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '15:00:00', '2025-05-31 21:00:26'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '16:00:00', '2025-05-31 21:00:26'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '17:00:00', '2025-05-31 21:00:26'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-02', '18:00:00', '2025-05-31 21:00:26'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '13:00:00', '2025-05-31 21:00:26'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '14:00:00', '2025-05-31 21:00:26'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '15:00:00', '2025-05-31 21:00:26'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '16:00:00', '2025-05-31 21:00:26'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '17:00:00', '2025-05-31 21:00:26'),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-09', '18:00:00', '2025-05-31 21:00:26'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '13:00:00', '2025-05-31 21:00:26'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '14:00:00', '2025-05-31 21:00:26'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '15:00:00', '2025-05-31 21:00:26'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '16:00:00', '2025-05-31 21:00:26'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '17:00:00', '2025-05-31 21:00:26'),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-16', '18:00:00', '2025-05-31 21:00:26'),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '13:00:00', '2025-05-31 21:00:26'),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '14:00:00', '2025-05-31 21:00:26'),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '15:00:00', '2025-05-31 21:00:26'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '16:00:00', '2025-05-31 21:00:26'),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '17:00:00', '2025-05-31 21:00:26'),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-23', '18:00:00', '2025-05-31 21:00:26'),
(79, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '13:00:00', '2025-05-31 21:00:26'),
(80, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '14:00:00', '2025-05-31 21:00:26'),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '15:00:00', '2025-05-31 21:00:26'),
(82, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '16:00:00', '2025-05-31 21:00:26'),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '17:00:00', '2025-05-31 21:00:26'),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-08-30', '18:00:00', '2025-05-31 21:00:26'),
(85, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '13:00:00', '2025-05-31 21:00:26'),
(86, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '14:00:00', '2025-05-31 21:00:26'),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '15:00:00', '2025-05-31 21:00:26'),
(88, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '16:00:00', '2025-05-31 21:00:26'),
(89, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '17:00:00', '2025-05-31 21:00:26'),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-06', '18:00:00', '2025-05-31 21:00:26'),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '13:00:00', '2025-05-31 21:00:26'),
(92, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '14:00:00', '2025-05-31 21:00:26'),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '15:00:00', '2025-05-31 21:00:26'),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '16:00:00', '2025-05-31 21:00:26'),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '17:00:00', '2025-05-31 21:00:26'),
(96, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-13', '18:00:00', '2025-05-31 21:00:26'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '13:00:00', '2025-05-31 21:00:26'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '14:00:00', '2025-05-31 21:00:26'),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '15:00:00', '2025-05-31 21:00:26'),
(100, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '16:00:00', '2025-05-31 21:00:26'),
(101, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '17:00:00', '2025-05-31 21:00:26'),
(102, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-20', '18:00:00', '2025-05-31 21:00:26'),
(103, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '13:00:00', '2025-05-31 21:00:26'),
(104, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '14:00:00', '2025-05-31 21:00:26'),
(105, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '15:00:00', '2025-05-31 21:00:26'),
(106, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '16:00:00', '2025-05-31 21:00:26'),
(107, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '17:00:00', '2025-05-31 21:00:26'),
(108, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-09-27', '18:00:00', '2025-05-31 21:00:26'),
(109, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '13:00:00', '2025-05-31 21:00:26'),
(110, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '14:00:00', '2025-05-31 21:00:26'),
(111, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '15:00:00', '2025-05-31 21:00:26'),
(112, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '16:00:00', '2025-05-31 21:00:26'),
(113, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '17:00:00', '2025-05-31 21:00:26'),
(114, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-04', '18:00:00', '2025-05-31 21:00:26'),
(115, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '13:00:00', '2025-05-31 21:00:26'),
(116, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '14:00:00', '2025-05-31 21:00:26'),
(117, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '15:00:00', '2025-05-31 21:00:26'),
(118, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '16:00:00', '2025-05-31 21:00:26'),
(119, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '17:00:00', '2025-05-31 21:00:26'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-11', '18:00:00', '2025-05-31 21:00:26'),
(121, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '13:00:00', '2025-05-31 21:00:26'),
(122, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '14:00:00', '2025-05-31 21:00:26'),
(123, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '15:00:00', '2025-05-31 21:00:26'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '16:00:00', '2025-05-31 21:00:26'),
(125, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '17:00:00', '2025-05-31 21:00:26'),
(126, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-18', '18:00:00', '2025-05-31 21:00:26'),
(127, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '13:00:00', '2025-05-31 21:00:26'),
(128, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '14:00:00', '2025-05-31 21:00:26'),
(129, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '15:00:00', '2025-05-31 21:00:26'),
(130, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '16:00:00', '2025-05-31 21:00:26'),
(131, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '17:00:00', '2025-05-31 21:00:26'),
(132, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-10-25', '18:00:00', '2025-05-31 21:00:26'),
(133, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '13:00:00', '2025-05-31 21:00:26'),
(134, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '14:00:00', '2025-05-31 21:00:26'),
(135, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '15:00:00', '2025-05-31 21:00:26'),
(136, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '16:00:00', '2025-05-31 21:00:26'),
(137, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '17:00:00', '2025-05-31 21:00:26'),
(138, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-01', '18:00:00', '2025-05-31 21:00:26'),
(139, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '13:00:00', '2025-05-31 21:00:26'),
(140, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '14:00:00', '2025-05-31 21:00:26'),
(141, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '15:00:00', '2025-05-31 21:00:26'),
(142, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '16:00:00', '2025-05-31 21:00:26'),
(143, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '17:00:00', '2025-05-31 21:00:26'),
(144, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-08', '18:00:00', '2025-05-31 21:00:26'),
(145, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '13:00:00', '2025-05-31 21:00:26'),
(146, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '14:00:00', '2025-05-31 21:00:26'),
(147, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '15:00:00', '2025-05-31 21:00:26'),
(148, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '16:00:00', '2025-05-31 21:00:26'),
(149, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '17:00:00', '2025-05-31 21:00:26'),
(150, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-15', '18:00:00', '2025-05-31 21:00:26'),
(151, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '13:00:00', '2025-05-31 21:00:26'),
(152, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '14:00:00', '2025-05-31 21:00:26'),
(153, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '15:00:00', '2025-05-31 21:00:26'),
(154, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '16:00:00', '2025-05-31 21:00:26'),
(155, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '17:00:00', '2025-05-31 21:00:26'),
(156, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-22', '18:00:00', '2025-05-31 21:00:26'),
(157, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '13:00:00', '2025-05-31 21:00:26'),
(158, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '14:00:00', '2025-05-31 21:00:26'),
(159, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '15:00:00', '2025-05-31 21:00:26'),
(160, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '16:00:00', '2025-05-31 21:00:26'),
(161, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '17:00:00', '2025-05-31 21:00:26'),
(162, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-11-29', '18:00:00', '2025-05-31 21:00:26'),
(163, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '13:00:00', '2025-05-31 21:00:26'),
(164, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '14:00:00', '2025-05-31 21:00:26'),
(165, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '15:00:00', '2025-05-31 21:00:26'),
(166, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '16:00:00', '2025-05-31 21:00:26'),
(167, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '17:00:00', '2025-05-31 21:00:26'),
(168, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-06', '18:00:00', '2025-05-31 21:00:26'),
(169, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '13:00:00', '2025-05-31 21:00:26'),
(170, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '14:00:00', '2025-05-31 21:00:26'),
(171, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '15:00:00', '2025-05-31 21:00:26'),
(172, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '16:00:00', '2025-05-31 21:00:26'),
(173, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '17:00:00', '2025-05-31 21:00:26'),
(174, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-13', '18:00:00', '2025-05-31 21:00:26'),
(175, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '13:00:00', '2025-05-31 21:00:26'),
(176, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '14:00:00', '2025-05-31 21:00:26'),
(177, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '15:00:00', '2025-05-31 21:00:26'),
(178, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '16:00:00', '2025-05-31 21:00:26'),
(179, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '17:00:00', '2025-05-31 21:00:26'),
(180, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-20', '18:00:00', '2025-05-31 21:00:26'),
(181, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '13:00:00', '2025-05-31 21:00:26'),
(182, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '14:00:00', '2025-05-31 21:00:26'),
(183, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '15:00:00', '2025-05-31 21:00:26'),
(184, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '16:00:00', '2025-05-31 21:00:26'),
(185, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '17:00:00', '2025-05-31 21:00:26'),
(186, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-12-27', '18:00:00', '2025-05-31 21:00:26'),
(188, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-28', '10:00:00', '2025-06-06 19:01:56'),
(189, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '14:00:00', '2025-06-13 13:44:55'),
(190, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', '11:00:00', '2025-06-16 14:17:15');

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
  `data_entrada` datetime NOT NULL DEFAULT current_timestamp(),
  `lote` varchar(50) DEFAULT NULL,
  `validade` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estoque`
--

INSERT INTO `estoque` (`id`, `token_emp`, `produto`, `tipo`, `quantidade`, `data_entrada`, `lote`, `validade`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 'Entrada', 1, '2025-06-21 00:37:11', '14012504S', '2026-01-31 00:00:00'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 8, 'Entrada', 1, '2025-06-21 00:44:35', '14012504S', '2026-01-31 00:00:00'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 9, 'Entrada', 5, '2025-06-21 00:46:02', '11122408S', '2025-12-31 00:00:00'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 12, 'Entrada', 34, '2025-06-21 01:27:20', '01/2025', '2028-01-31 00:00:00'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 17, 'Entrada', 10, '2025-06-21 01:28:33', '06052021', '2026-05-30 00:00:00'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 19, 'Entrada', 165, '2025-06-21 01:29:57', '24050399', '2027-05-30 00:00:00'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 13, 'Entrada', 101, '2025-06-21 01:31:15', 'SSLLAB039', '2029-08-30 00:00:00'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 14, 'Entrada', 127, '2025-06-21 01:32:33', 'SAGAAB006A', '2029-07-30 00:00:00'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 15, 'Entrada', 124, '2025-06-21 01:33:41', '07024091', '2029-09-14 00:00:00'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 20, 'Entrada', 81, '2025-06-21 01:34:40', 'B125', '0000-00-00 00:00:00'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 18, 'Entrada', 74, '2025-06-21 01:39:37', 'TPPHH', '2028-10-31 00:00:00'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 16, 'Entrada', 1, '2025-06-21 01:40:43', '2203962', '2025-09-30 00:00:00'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 23, 'Entrada', 50, '2025-06-21 01:42:05', 'TPPHX014', '2029-03-30 00:00:00'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 21, 'Entrada', 5, '2025-06-21 01:43:51', 'PR11296', '2027-11-30 00:00:00'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 22, 'Entrada', 10, '2025-06-21 01:44:55', 'BSA0556/24', '2029-05-30 00:00:00'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 26, 'Entrada', 26, '2025-06-21 01:46:58', 'PR267', '2025-09-30 00:00:00');

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

--
-- Dumping data for table `estoque_item`
--

INSERT INTO `estoque_item` (`id`, `token_emp`, `produto`, `minimo`, `unidade`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Lidocaina 1% 2ml cx com 10un', 10, 'UN'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Zinco 0,04% cx 10un', 2, 'UN'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Silicio P 3% cx cm 10un', 3, 'UN'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Minoxidil 0,5% cx com 10', 5, 'UN'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Finasterida 0,05% cx com 10un', 5, 'UN'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '17 alfa estradiol cx com 10un', 2, 'UN'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'compl vitaminico cx cm 10un', 3, 'UN'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Dutasterida 0,05ml cx com 5un', 2, 'UN'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Pill food cx cm 10un', 3, 'UN'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Copper peptideo + fator de cresc 1,20% cx cm 5un', 2, 'UN'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Luva G cx com 100un', 16, 'UN'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Gaze Pct com 500un', 10, 'UN'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Seringa 3ml Luer Lock caixa com 100 um', 10, 'UN'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Agulha 40x12/ 30x7 cx com 100un', 10, 'UN'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Agulha 4mm 32g cx com 100 un', 10, 'UN'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Clorexidina alcolica FR 100ml', 1, 'Lt'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Cartucho Caneta create 3523mg cx com 20 um ', 5, 'UN'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Touca descartável pacote com 100 um', 10, 'UN'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Alcool swab cx com 100un', 10, 'UN'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Pente descartável pact com 100un', 10, 'UN'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Lencol de maca TNT pact com 10 um', 3, 'UN'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Avental descartável manga longa TNT pac com 10un', 3, 'UN'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Máscara descartável cx com 50un', 5, 'UN'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Higienizante 200ml/ml', 1, 'UN'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Renovador Capilar 200ml/ml', 1, 'UN'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Luva M Latéx caixa com 100un', 2, 'UN');

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

--
-- Dumping data for table `evolucoes`
--

INSERT INTO `evolucoes` (`id`, `token_emp`, `doc_email`, `data`, `profissional`, `anotacao`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', '2025-06-09 14:13:00', 'Caroline Ferraz', '07/06: Paciente relata manutenção da queixa de queda mais intensa, evidencio couro cabeludo ainda com presença de descamação e inflamação. Realizo sessão de laser associado a intradermoterapia sem intercorrências. Oriento paciente quanto ao cuidado pós procedimento. Oriento quanto a realização dos exames laboratoriais solicitados.'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquel.d.santos110194@gmail.com', '2025-06-15 22:30:40', 'Caroline Ferraz', 'Paciente retorna para consulta de reavaliação relata melhora da queda, ainda com um pouco de fios caindo mas não apresenta intensidade como antes. Conseguiu seguir o tratamento corretamente.\\r\\nMelhorou o estresse diminuiu a rotisna de trabalho, segue fazendo terapia.\\r\\nPercebe melhora do sono.\\r\\nAlimentação em melhora devido ao acompanhamento com nutricionista.\\r\\nRealizando suplementação com whey + creatina prescrito pela nutricionista.\\r\\nRealizando musculação sente que esta com outra energia. melhora da coceira e descamação. Realizou progressiva recentemente sem queixas.\\r\\nOriento retorno em 3 meses, ajusto formulação e mantenho acompanhamento para suspeita de AAG levantada por imagem global.\\r\\n\\r\\n'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', '2025-06-15 22:37:00', 'Caroline Ferraz', 'Paciente retorna para consulta de retorno em atraso, relata que devido a dificuldade de aceitar diagnostico seguiu apenas o uso do tônico por 1 mês. Passou pro problemas familiares que levou a um processo de depressão hoje está se sentindo melhor.Mantém queixa de cansaço excessivo. Não estuda mais apenas trabalha de 8 as 17hs. Começou academia esse mes. MMantém habitos aimentares ruins.Mantém habitos capilares de lavagem 2x por semana.Senti desconforto ao comer. Não realiza acompanhamento com psicologo. Ajusto formulações de home care, oriento acompanhamento com psicologo, endocrinologista e indico tratamento em consultório'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '2025-06-18 15:53:09', 'Caroline Ferraz', 'Paciente comparece para sessão de microagulhamento associado a laserterapia. Relata percepção de queda continuada, nega outras queixas. Relata não estar fazendo uso do tônico com frequência, oriento quanto a importância do uso diário. Realizo microagulhamento com drug delivery (minoxidil + finasterida + pilfood ) associado a laser vermelho 4 joules por ponto. Procedimento concluido sem intercorrências paciente orientada quanto aos cuidados pós procedimento.'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', '2025-06-20 14:19:43', 'Caroline Ferraz', '18/06/25-Paciente compatrece para 1ª Sessão de laserterapia (brinde), nega queixas, ainda não iniciou uso da formulação. Realizo sessão de laser vermelho associado a LED azul 20s + LED ambar 10s, realizo higienização de couro cabeludo e finalizo com fatores de crescimento.Sessão realizada sem intercorrências.');

-- --------------------------------------------------------

--
-- Table structure for table `grupos_contas`
--

CREATE TABLE `grupos_contas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grupos_contas`
--

INSERT INTO `grupos_contas` (`id`, `nome`, `tipo_id`, `created_at`) VALUES
(1, 'Receita de Serviços', 1, '2025-06-15 04:14:33'),
(2, 'Receitas Financeiras', 1, '2025-06-15 04:14:33'),
(3, 'Outras Entradas', 1, '2025-06-15 04:14:33'),
(4, 'Despesas com Serviços', 2, '2025-06-15 04:14:33'),
(5, 'Despesas com Pessoal', 2, '2025-06-15 04:14:33'),
(6, 'Despesas Comerciais', 2, '2025-06-15 04:14:33'),
(7, 'Despesas Administrativas', 2, '2025-06-15 04:14:33'),
(8, 'Despesas Financeiras', 2, '2025-06-15 04:14:33'),
(9, 'Impostos', 2, '2025-06-15 04:14:33'),
(10, 'Investimento', 2, '2025-06-15 04:14:33'),
(11, 'Retirada de Sócios', 2, '2025-06-15 04:14:33'),
(12, 'Energéticos', 2, '2025-06-15 13:38:45');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `historico_atendimento`
--

INSERT INTO `historico_atendimento` (`id`, `token_emp`, `quando`, `quem`, `unico`, `oque`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-01 20:14:06.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 04/05/2023 e 04/05/2023'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-01 20:14:56.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 10/05/2023 e 10/05/2023'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-01 20:15:51.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 20/05/2023 e 20/05/2023'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-02 18:58:54.000000', 'Denis Ferraz', '05336888508', 'Criou a consulta CSQIWCQABT'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-02 19:14:13.000000', 'Denis Ferraz', '05336888508', 'Finalizou a consulta CIIAWBLSET'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 09:41:31.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQLCKWSNRT'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 18:35:39.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CAZYIGWJGT'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 18:35:42.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CAZYIGWJGT'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 18:35:48.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CAZYIGWJGT'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:00:04.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.380,00 na Confirmação CRMMPLBQIT'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:16:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Produtos Grandha no valor de R$252,00 na Confirmação CRMMPLBQIT'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:23:26.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$2.880,00 na Confirmação CSUGPXUYHT'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:24:02.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Produtos Grandha no valor de R$85,00 na Confirmação CSUGPXUYHT'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:26:16.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$590,00 na Confirmação CIIAWBLSET'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:35:05.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.870,00 na Confirmação CLVLRIRGHT'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:39:53.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.050,00 na Confirmação CPWJIMXWIT'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-07 19:45:39.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 11/05/2023 e 11/05/2023'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-09 20:44:51.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 16/05/2023 e 16/05/2023'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-10 20:53:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-10 20:53:27.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-10 20:53:27.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-11 16:52:33.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 17/05/2023 e 17/05/2023'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-12 15:53:54.000000', 'Denis Ferraz', '25336889511', 'Criou a consulta CTEBJEIWTT'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-12 17:19:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-13 06:53:29.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CRMMPLBQIT'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-13 18:43:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLXTHQIIET'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 21:46:20.000000', 'Denis Ferraz', '05336889511', 'Criou a consulta CBUHAMJXJT'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 21:47:30.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo teste.pdf na Confirmação CBUHAMJXJT'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 21:59:50.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo teste.pdf na Confirmação CBUHAMJXJT'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 21:59:52.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo teste.pdf na Confirmação CBUHAMJXJT'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 22:00:40.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo Arquivo Teste.pdf na Confirmação CBUHAMJXJT'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 22:01:37.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo 121213.pdf na Confirmação CBUHAMJXJT'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 22:02:49.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo teste.pdf na Confirmação CBUHAMJXJT'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 22:04:23.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo Arquivo Teste.pdf na Confirmação CBUHAMJXJT'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-14 22:04:50.000000', 'Denis Ferraz', '05336889511', 'Cadastrou um novo Arquivo Arquivo Teste.pdf na Confirmação CBUHAMJXJT'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-15 09:34:23.000000', 'Denis Ferraz', '05336889511', 'Criou a consulta CQSNJOZTIT'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-15 09:34:34.000000', 'Denis Ferraz', '05336889511', 'Finalizou a consulta CQSNJOZTIT'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-15 15:15:51.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CXEALQWPWT'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-16 18:10:44.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDVXDWVRST'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17 15:23:26.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17 15:23:29.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17 15:38:38.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/05/2023 e 19/05/2023'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-17 19:11:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 15:12:16.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 15:12:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 17:32:08.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CXEALQWPWT'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 17:32:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CXEALQWPWT'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 17:38:53.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CXEALQWPWT'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 17:44:44.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.050,00 na Confirmação CXEALQWPWT'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-18 17:45:02.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$300,00 na Confirmação CXEALQWPWT'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-21 14:07:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-21 14:07:43.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-21 16:16:39.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 22/05/2023 e 22/05/2023'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:07:57.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$500,00 na Confirmação CPWJIMXWIT'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:08:33.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$550,00 na Confirmação CPWJIMXWIT'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:17:23.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.050,00 na Confirmação CUFRKHLJBT'),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:27:28.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 16/06/2023 e 16/06/2023'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:27:45.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 24/06/2023 e 24/06/2023'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-23 19:29:09.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CRMMPLBQIT'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-24 18:55:21.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-24 18:59:10.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CRAKGXBGIT'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-25 15:39:00.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CSUGPXUYHT'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-25 17:17:10.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CUFRKHLJBT'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-25 19:33:34.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/05/2023 e 26/05/2023'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-26 17:04:03.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDVXDWVRST'),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-05-27 11:31:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01 14:50:55.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01 19:29:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CRAKGXBGIT'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01 19:29:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CUFRKHLJBT'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-01 19:29:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-03 13:31:50.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDVXDWVRST'),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-04 18:48:47.000000', 'Denis Ferraz', '05336889511', 'Finalizou a consulta CPWJIMXWIT'),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-06 18:44:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-06 19:06:49.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$997,00 na Confirmação CWAUOQHTUT'),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-06 20:07:57.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 08/06/2023 e 08/06/2023'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-07 17:35:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 08:50:25.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CFNJFQKMYT'),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:08:11.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 14/06/2023 e 14/06/2023'),
(79, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:08:40.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 21/06/2023 e 21/06/2023'),
(80, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:09:13.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 28/06/2023 e 28/06/2023'),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:17:59.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 24/06/2023 e 24/06/2023'),
(82, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:18:58.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 05/07/2023 e 05/07/2023'),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:20:01.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 09/07/2023 e 09/07/2023'),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:20:40.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 08/07/2023 e 08/07/2023'),
(85, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:21:15.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 14/06/2023 e 14/06/2023'),
(86, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:21:48.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 21/06/2023 e 21/06/2023'),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:22:38.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/07/2023 e 15/07/2023'),
(88, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:23:13.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/07/2023 e 19/07/2023'),
(89, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:23:55.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/07/2023 e 26/07/2023'),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:24:39.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 28/07/2023 e 28/07/2023'),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-11 10:25:55.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 14/06/2023 e 14/06/2023'),
(92, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-14 11:11:14.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CUFRKHLJBT'),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-14 15:16:01.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CSUGPXUYHT'),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-14 19:19:56.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-15 15:29:56.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(96, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 10:32:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 11:53:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 11:54:17.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$330,00 na Confirmação CSUGPXUYHT'),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:03:11.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 23/06/2023 e 23/06/2023'),
(100, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:03:47.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/06/2023 e 19/06/2023'),
(101, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:04:26.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 23/06/2023 e 23/06/2023'),
(102, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:04:38.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/06/2023 e 19/06/2023'),
(103, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:05:12.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 24/06/2023 e 24/06/2023'),
(104, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:06:03.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 01/07/2023 e 01/07/2023'),
(105, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:06:57.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 03/07/2023 e 03/07/2023'),
(106, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:08:56.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 05/07/2023 e 06/07/2023'),
(107, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-17 12:11:47.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFNJFQKMYT'),
(108, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-20 16:58:14.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(109, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-20 16:59:45.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CUFRKHLJBT'),
(110, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-20 18:24:57.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CEMKOYZRGT'),
(111, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-20 20:14:23.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CCNYZSPFNT'),
(112, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-20 20:14:47.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CCNYZSPFNT'),
(113, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23 10:21:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CUFRKHLJBT'),
(114, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23 10:22:05.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta CEMKOYZRGT'),
(115, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-23 16:59:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(116, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-27 19:02:41.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(117, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-28 17:56:16.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 08/07/2023 e 08/06/2023'),
(118, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-28 17:56:31.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 08/07/2023 e 08/07/2023'),
(119, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-28 17:56:53.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 08/07/2023 e 08/07/2023'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-28 19:33:42.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CCMSPTOEET'),
(121, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29 11:50:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(122, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29 11:52:51.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CJEEFANJVT'),
(123, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29 18:30:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-29 18:30:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CJEEFANJVT'),
(125, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-30 15:42:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(126, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-06-30 15:43:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CSUGPXUYHT'),
(127, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-01 10:32:58.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLMMKNRHPT'),
(128, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-01 15:24:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDVXDWVRST'),
(129, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-02 22:52:56.000000', 'Denis Ferraz', '05336888508', 'Cadastrou um novo Arquivo exame.pdf na Confirmação CFSSUBKPQT'),
(130, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:44:28.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 03/07/2023 e 03/07/2023'),
(131, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:46:50.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 06/07/2023 e 06/07/2023'),
(132, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:47:37.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 12/07/2023 e 12/07/2023'),
(133, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:48:09.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/07/2023 e 09/07/2023'),
(134, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:48:59.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/07/2023 e 26/07/2023'),
(135, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:49:22.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 28/07/2023 e 28/07/2023'),
(136, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:50:37.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/07/2023 e 15/07/2023'),
(137, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:51:25.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 22/07/2023 e 22/07/2023'),
(138, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-03 19:52:01.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 29/07/2023 e 29/07/2023'),
(139, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-05 17:50:47.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta CLMMKNRHPT'),
(140, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-05 19:10:40.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(141, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-07 17:51:43.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDECGKTCCT'),
(142, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-08 10:29:55.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(143, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-10 22:13:00.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 17/07/2023 e 17/07/2023'),
(144, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-11 18:36:37.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDECGKTCCT'),
(145, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-11 18:38:18.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(146, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-12 14:42:41.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(147, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-13 14:12:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(148, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-13 15:34:57.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(149, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-15 13:00:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(150, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-18 17:44:13.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCMSPTOEET'),
(151, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-18 19:24:14.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(152, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-19 19:33:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(153, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-22 09:14:57.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(154, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-22 10:05:37.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CBFBWKNNVT'),
(155, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-24 17:53:41.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CBFBWKNNVT'),
(156, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-24 17:58:22.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CTCOCNBMVT'),
(157, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-25 19:09:44.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(158, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-27 17:41:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTCOCNBMVT'),
(159, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-27 17:48:24.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$976,00 na Confirmação CTCOCNBMVT'),
(160, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-27 18:21:36.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(161, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-07-31 20:49:59.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWAUOQHTUT'),
(162, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01 10:50:25.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CTCOCNBMVT'),
(163, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01 11:40:01.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(164, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01 17:09:58.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTCOCNBMVT'),
(165, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01 17:12:56.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CTCOCNBMVT'),
(166, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-01 19:03:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(167, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-02 16:59:56.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$980,00 na Confirmação CPWJIMXWIT'),
(168, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-02 18:09:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(169, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-05 09:08:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(170, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-05 09:18:11.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.990,00 na Confirmação CCMSPTOEET'),
(171, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-06 21:59:32.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(172, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 16:56:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTCOCNBMVT'),
(173, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 16:59:06.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 15/08/2023 e 15/08/2023'),
(174, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:08:19.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 15/08/2023 e 15/08/2023'),
(175, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:08:45.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/08/2023 e 15/08/2023'),
(176, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:09:07.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(177, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:09:36.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 11/08/2023 e 11/08/2023'),
(178, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:10:42.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 11/08/2023 e 11/08/2023'),
(179, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:11:36.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 09/08/2023 e 09/08/2023'),
(180, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:11:57.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 09/08/2023 e 09/08/2023'),
(181, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:12:30.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 10/08/2023 e 10/08/2023'),
(182, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:13:18.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 12/08/2023 e 12/08/2023'),
(183, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:13:59.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/08/2023 e 15/08/2023'),
(184, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:14:19.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 16/08/2023 e 16/08/2023'),
(185, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:14:50.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 17/08/2023 e 17/08/2023'),
(186, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:15:14.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/08/2023 e 19/08/2023'),
(187, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:15:44.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 21/08/2023 e 21/08/2023'),
(188, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:16:19.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 18/08/2023 e 18/08/2023'),
(189, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:16:39.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 18/08/2023 e 18/08/2023'),
(190, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:16:39.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 18/08/2023 e 18/08/2023'),
(191, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:17:34.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 17/08/2023 e 17/08/2023'),
(192, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:17:46.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 17/08/2023 e 17/08/2023'),
(193, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:18:36.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 22/08/2023 e 22/08/2023'),
(194, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:18:49.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 22/08/2023 e 22/08/2023'),
(195, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:19:24.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 23/08/2023 e 23/08/2023'),
(196, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:20:03.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 22/08/2023 e 22/08/2023'),
(197, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:20:04.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 22/08/2023 e 22/08/2023'),
(198, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:20:54.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 22/08/2023 e 22/08/2023'),
(199, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:21:47.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 24/08/2023 e 24/08/2023'),
(200, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:22:01.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 24/08/2023 e 24/08/2023'),
(201, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:22:28.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 25/08/2023 e 25/08/2023'),
(202, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:22:57.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/08/2023 e 26/08/2023'),
(203, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:23:28.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 28/08/2023 e 28/08/2023'),
(204, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:23:48.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 28/08/2023 e 28/08/2023'),
(205, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:24:24.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 29/08/2023 e 29/08/2023'),
(206, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:24:47.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 29/08/2023 e 29/08/2023'),
(207, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:25:40.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/08/2023 e 26/08/2023'),
(208, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:26:00.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 30/08/2023 e 30/08/2023'),
(209, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:26:13.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 31/08/2023 e 31/08/2023'),
(210, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-08 17:26:43.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 30/08/2023 e 30/08/2023'),
(211, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-09 15:22:36.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CVKOPTXUTT'),
(212, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-10 09:55:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(213, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-10 09:58:46.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CTJFLLTPHT'),
(214, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-10 19:02:48.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(215, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-10 19:06:04.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 17/08/2023 e 17/08/2023'),
(216, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-11 10:54:47.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWAUOQHTUT'),
(217, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-11 20:23:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTJFLLTPHT'),
(218, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-12 11:20:56.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(219, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-16 17:11:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTCOCNBMVT'),
(220, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17 10:44:12.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 18/08/2023 e 18/08/2023'),
(221, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17 10:44:30.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CVVNNUFWST'),
(222, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17 19:24:44.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(223, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17 19:25:28.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CPGVPNZGKT'),
(224, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-17 21:28:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(225, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-18 17:28:04.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(226, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-18 17:43:26.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 01/09/2023 e 01/09/2023'),
(227, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-19 08:56:09.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(228, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-21 12:15:51.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CTCOCNBMVT'),
(229, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-21 12:16:46.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Devolução de valor pago no valor de R$90,00 na Confirmação CTCOCNBMVT'),
(230, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-21 17:08:59.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(231, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-23 19:14:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(232, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-24 10:38:09.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CYTAJSKYAT'),
(233, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-25 18:02:04.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CYTAJSKYAT'),
(234, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-29 18:00:05.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGZNCSJRRT'),
(235, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-08-29 18:57:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(236, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-01 17:02:26.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(237, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-01 19:46:18.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 04/09/2023 e 30/09/2023'),
(238, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-03 17:26:42.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$330,00 na Confirmação CIIAWBLSET'),
(239, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-03 17:27:16.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$980,00 na Confirmação CPWJIMXWIT'),
(240, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-03 17:27:44.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.550,00 na Confirmação CVVNNUFWST'),
(241, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-05 09:51:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVKOPTXUTT'),
(242, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-05 09:51:31.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGZNCSJRRT'),
(243, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-07 13:31:02.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CZSCCYZIKT'),
(244, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-08 11:40:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(245, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-09 09:09:40.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(246, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-09 11:32:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(247, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:43:07.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CZSCCYZIKT'),
(248, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:57:49.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/09/2023 e 16/09/2023'),
(249, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:58:29.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 12/09/2023 e 12/09/2023'),
(250, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:59:02.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 13/09/2023 e 13/09/2023'),
(251, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:59:35.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 18/09/2023 e 18/09/2023'),
(252, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 15:59:58.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/09/2023 e 19/09/2023'),
(253, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 16:00:26.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/09/2023 e 19/09/2023'),
(254, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 16:00:49.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 20/09/2023 e 20/09/2023'),
(255, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 16:01:15.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 25/09/2023 e 25/09/2023'),
(256, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 16:01:41.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 26/09/2023 e 26/09/2023'),
(257, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-11 16:01:59.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 27/09/2023 e 27/09/2023'),
(258, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-14 14:11:04.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(259, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-18 06:05:52.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CUMJEGAMUT'),
(260, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-20 14:28:11.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CUMJEGAMUT'),
(261, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-20 19:11:04.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(262, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-23 09:20:10.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta CUMJEGAMUT'),
(263, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-23 09:24:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCMSPTOEET'),
(264, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-23 09:41:17.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CCBEYXQLWT'),
(265, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-27 18:30:08.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCBEYXQLWT'),
(266, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 14:58:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(267, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:09:13.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 02/10/2023 e 30/09/2023'),
(268, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:09:41.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 02/10/2023 e 31/10/2023'),
(269, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:10:01.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 02/10/2023 e 31/10/2023'),
(270, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:10:59.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 02/10/2023 e 31/10/2023'),
(271, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:11:47.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 02/10/2023 e 31/10/2023'),
(272, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-09-29 15:12:20.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVVNNUFWST'),
(273, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-03 15:48:25.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(274, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-03 16:59:30.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGFNOXOBMT'),
(275, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-03 21:36:27.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVVNNUFWST'),
(276, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-06 08:56:16.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(277, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-06 08:58:30.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(278, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-09 09:44:37.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLZQMHSHLT'),
(279, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-09 17:10:04.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CKWLHJFBNT'),
(280, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-09 17:11:50.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CKWLHJFBNT'),
(281, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-09 17:15:38.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(282, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-10 16:05:44.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZQMHSHLT'),
(283, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-10 17:03:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGFNOXOBMT'),
(284, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-11 23:17:13.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 14/10/2023 e 14/10/2023'),
(285, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-11 23:17:41.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(286, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-11 23:53:06.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CVOBMZWAAT'),
(287, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-11 23:55:34.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CQSKGITZQT'),
(288, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-11 23:57:40.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLZCLCYKCT'),
(289, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-13 12:05:45.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(290, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-13 12:13:53.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.740,00 na Confirmação CVOBMZWAAT'),
(291, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-13 12:19:57.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CCTSQJNNTT'),
(292, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-14 11:50:50.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQSKGITZQT'),
(293, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-16 15:09:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZCLCYKCT'),
(294, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-17 17:07:54.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CGFNOXOBMT'),
(295, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-18 18:08:14.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$980,00 na Confirmação CPWJIMXWIT'),
(296, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-18 18:09:16.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CKLJUWNMMT'),
(297, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-18 19:23:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(298, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-19 16:21:49.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(299, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-20 09:05:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(300, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-21 10:34:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(301, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-21 10:39:52.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta CGFNOXOBMT'),
(302, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-24 10:52:42.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 01/11/2023 e 30/11/2023'),
(303, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-24 15:10:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZCLCYKCT'),
(304, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25 12:01:32.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 02/11/2023 e 02/11/2023'),
(305, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25 12:02:21.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 28/10/2023 e 28/10/2023'),
(306, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25 12:03:21.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 03/11/2023 e 03/11/2023'),
(307, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25 12:03:40.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 15/11/2023 e 15/11/2023'),
(308, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-25 18:06:20.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(309, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-27 19:55:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(310, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-27 19:55:52.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(311, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-30 14:57:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZCLCYKCT'),
(312, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-30 15:48:36.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CEMWGTZIXT'),
(313, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-10-30 16:00:07.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 06/11/2023 e 06/11/2023'),
(314, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-01 08:14:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CGFNOXOBMT'),
(315, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-01 19:16:26.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(316, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-01 19:16:36.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(317, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-06 15:53:19.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDBOXULSST'),
(318, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 15:30:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZCLCYKCT'),
(319, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 17:24:43.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGFNOXOBMT'),
(320, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 18:33:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(321, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 18:49:41.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$270,00 na Confirmação CKLJUWNMMT'),
(322, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 18:54:55.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Alimentação.pdf na Confirmação CKLJUWNMMT');
INSERT INTO `historico_atendimento` (`id`, `token_emp`, `quando`, `quem`, `unico`, `oque`) VALUES
(323, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:23:29.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo GUIA HAIR CARE.pdf na Confirmação CKLJUWNMMT'),
(324, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:24:19.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo TRICOBOX ALIMENTAÇÃO.pdf na Confirmação CVOBMZWAAT'),
(325, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:28:19.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo GUIA HAIR CARE.pdf na Confirmação CVOBMZWAAT'),
(326, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:29:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(327, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:32:48.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$240,00 na Confirmação CLZCLCYKCT'),
(328, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-07 19:35:09.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$931,00 na Confirmação CGFNOXOBMT'),
(329, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-08 23:42:30.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CTQPCRCJST'),
(330, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-09 15:48:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDBOXULSST'),
(331, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-10 15:06:49.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(332, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-10 15:09:46.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CCGLDKJHHT'),
(333, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-16 15:04:44.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDBOXULSST'),
(334, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-16 15:09:46.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CVANZIMDGT'),
(335, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-16 17:53:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLZCLCYKCT'),
(336, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-17 16:55:27.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CARBIHLHGT'),
(337, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-17 17:00:24.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.740,00 na Confirmação CVANZIMDGT'),
(338, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-17 18:03:45.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(339, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21 17:06:49.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(340, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21 17:07:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGFNOXOBMT'),
(341, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21 17:47:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVVNNUFWST'),
(342, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21 17:48:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLVLRIRGHT'),
(343, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-21 17:49:21.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTCOCNBMVT'),
(344, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-22 19:44:50.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(345, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-24 23:35:41.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGTTQNPEBT'),
(346, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-27 17:31:18.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGTTQNPEBT'),
(347, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-28 16:00:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(348, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-28 16:56:08.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGFNOXOBMT'),
(349, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 12:27:10.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 07/12/2023 e 08/12/2023'),
(350, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 12:27:31.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 16/12/2023 e 16/12/2023'),
(351, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 12:27:48.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 25/12/2023 e 25/12/2023'),
(352, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 12:28:27.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 07/12/2023 e 08/12/2023'),
(353, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 12:28:44.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 25/12/2023 e 25/12/2023'),
(354, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 15:04:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(355, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-11-30 16:10:43.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(356, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-02 11:26:17.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(357, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-02 11:26:39.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(358, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-04 14:44:40.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/12/2023 e 19/12/2023'),
(359, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-04 14:44:56.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/12/2023 e 19/12/2023'),
(360, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-05 12:09:42.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta COYAURDPRT'),
(361, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-05 17:02:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGFNOXOBMT'),
(362, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-05 17:24:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(363, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-07 11:24:31.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CCLUJMFGQT'),
(364, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-07 11:24:55.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta COYAURDPRT'),
(365, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09 10:08:08.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCLUJMFGQT'),
(366, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09 10:10:47.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHCNROKNGT'),
(367, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09 11:43:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(368, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09 12:09:10.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(369, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-09 12:09:51.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(370, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11 16:06:14.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(371, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11 16:57:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHCNROKNGT'),
(372, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11 17:08:26.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(373, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11 17:34:00.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CWCQHMMRVT'),
(374, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-11 17:36:16.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$290,00 na Confirmação CWCQHMMRVT'),
(375, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-12 18:09:37.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(376, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-13 00:56:31.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CBSDNOJIAT'),
(377, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-14 15:20:47.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(378, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-18 18:07:58.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CBSDNOJIAT'),
(379, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-27 06:36:38.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWCQHMMRVT'),
(380, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2023-12-27 06:40:06.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CUBGAZDHUT'),
(381, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-01 20:47:23.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(382, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-02 08:03:01.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 18/01/2024 e 18/01/2024'),
(383, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-02 12:15:30.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(384, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-02 12:18:12.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(385, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-02 12:20:13.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CGFNOXOBMT'),
(386, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-03 22:19:11.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLKOHJWVYT'),
(387, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-03 22:20:11.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(388, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-04 17:10:23.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CPXOCVGPST'),
(389, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-04 17:10:47.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Consulta Capilar  no valor de R$250,00 na Confirmação CPXOCVGPST'),
(390, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-07 15:54:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLKOHJWVYT'),
(391, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-07 15:54:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPXOCVGPST'),
(392, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-08 12:12:34.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGUJOMFYHT'),
(393, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-08 12:30:19.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(394, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-09 11:54:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(395, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-09 12:02:40.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(396, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-09 20:22:56.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVOBMZWAAT'),
(397, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-11 15:22:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(398, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-11 23:58:37.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVOBMZWAAT'),
(399, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13 08:12:09.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou No-Show na consulta CGUJOMFYHT'),
(400, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13 09:07:21.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPWJIMXWIT'),
(401, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13 11:31:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(402, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-13 11:35:22.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CIIAWBLSET'),
(403, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-15 18:18:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(404, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-16 16:01:36.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(405, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-16 16:20:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CCTSQJNNTT'),
(406, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-16 16:21:22.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CGFNOXOBMT'),
(407, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-18 10:54:07.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CUBGAZDHUT'),
(408, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-29 09:57:39.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(409, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-01-29 11:10:35.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGXPJYXTLT'),
(410, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-02 13:40:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(411, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-02 16:40:46.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CTFUXQHZWT'),
(412, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-06 14:59:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGXPJYXTLT'),
(413, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-08 12:14:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CUBGAZDHUT'),
(414, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-14 15:08:15.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(415, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-15 22:00:49.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPWJIMXWIT'),
(416, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-19 11:29:17.000000', 'Denis Ferraz', '05336888508', 'Cadastrou No-Show na consulta CPWJIMXWIT'),
(417, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-19 11:29:27.000000', 'Denis Ferraz', '05336888508', 'Finalizou a consulta CTFUXQHZWT'),
(418, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-23 15:39:28.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(419, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-02-29 19:30:48.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(420, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-07 10:46:22.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CUVJJPPGGT'),
(421, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-07 15:11:38.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CUVJJPPGGT'),
(422, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-07 17:00:56.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CUVJJPPGGT'),
(423, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-12 09:39:19.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CWOOFMADHT'),
(424, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-12 09:51:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(425, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-12 09:54:02.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CWOOFMADHT'),
(426, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-18 19:35:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(427, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-18 19:37:23.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(428, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-21 16:49:43.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(429, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-26 13:09:13.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CRIWOWVWVT'),
(430, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-26 13:09:37.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CRIWOWVWVT'),
(431, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-28 15:36:41.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$300,00 na Confirmação CRIWOWVWVT'),
(432, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-28 15:37:10.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CRIWOWVWVT'),
(433, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-03-31 20:47:06.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(434, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-04 21:25:07.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(435, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-08 13:01:29.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(436, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-11 11:59:12.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(437, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-11 12:39:08.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(438, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-19 01:26:00.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(439, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-19 01:26:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(440, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20 15:46:24.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CIIAWBLSET'),
(441, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20 15:46:57.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(442, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20 15:56:15.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CWOOFMADHT'),
(443, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20 15:57:48.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(444, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-20 15:59:01.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(445, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-23 06:03:56.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(446, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-24 19:38:24.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(447, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-26 10:39:00.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CKERFDBTFT'),
(448, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-04-26 17:22:19.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(449, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-02 09:14:23.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKERFDBTFT'),
(450, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-02 16:13:58.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(451, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-02 17:37:46.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CZKUORSPFT'),
(452, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-02 18:06:25.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CCTSQJNNTT'),
(453, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-08 12:43:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(454, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-09 21:00:51.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLEHYUPLDT'),
(455, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-20 16:44:38.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(456, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-20 16:52:01.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CIEXHSVZUT'),
(457, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:47:09.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CIIAWBLSET'),
(458, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:47:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(459, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:48:10.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CJILZEKMRT'),
(460, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:48:41.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CJILZEKMRT'),
(461, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:50:37.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(462, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:51:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(463, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:52:40.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CWOOFMADHT'),
(464, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:53:36.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$250,00 na Confirmação CLEHYUPLDT'),
(465, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 13:54:25.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$300,00 na Confirmação CIEXHSVZUT'),
(466, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 14:01:29.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.680,00 na Confirmação CZKUORSPFT'),
(467, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 14:02:27.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CZKUORSPFT'),
(468, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 14:03:03.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CKLJUWNMMT'),
(469, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-05-25 14:04:19.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(470, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-04 14:02:18.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.187,00 na Confirmação CIEXHSVZUT'),
(471, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:06:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(472, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:07:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(473, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:07:29.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CZKUORSPFT'),
(474, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:08:48.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHIKHHJDVT'),
(475, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:12:58.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CVPGYXDOQT'),
(476, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:13:27.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CLEHYUPLDT'),
(477, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 13:13:35.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKERFDBTFT'),
(478, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-05 18:28:21.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(479, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-07 09:59:47.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(480, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-14 08:49:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(481, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-19 16:20:26.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(482, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-19 16:21:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(483, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-29 23:26:42.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CZKUORSPFT'),
(484, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-06-29 23:27:05.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(485, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-03 18:20:07.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(486, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-05 14:36:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(487, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-06 08:59:36.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(488, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-06 10:21:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CZKUORSPFT'),
(489, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-13 13:02:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(490, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-15 11:13:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(491, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-15 11:14:31.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(492, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-15 18:29:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(493, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 15:15:56.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(494, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:38:36.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 05/08/2024 e 05/08/2024'),
(495, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:38:55.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 05/08/2024 e 05/08/2024'),
(496, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:39:43.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 03/08/2024 e 03/08/2024'),
(497, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:39:58.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 03/08/2024 e 03/08/2024'),
(498, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:40:48.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 03/08/2024 e 03/08/2024'),
(499, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:41:18.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 03/08/2024 e 03/08/2024'),
(500, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-17 19:41:52.000000', 'Caroline Ferraz', '03326635583', 'Abriu disponibilidade entre as datas 03/08/2024 e 03/08/2024'),
(501, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-18 15:48:55.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(502, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-22 11:20:55.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CROQCOMPVT'),
(503, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-22 16:04:15.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(504, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-25 11:53:58.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(505, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-25 17:13:03.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CJPNQUOPPT'),
(506, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-26 19:57:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(507, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-07-30 11:25:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(508, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-06 12:18:07.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$520,00 na Confirmação CROQCOMPVT'),
(509, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-06 12:18:25.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(510, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-06 12:54:41.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(511, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-06 13:23:53.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(512, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-09 20:25:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(513, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-12 18:00:55.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHIKHHJDVT'),
(514, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-15 10:06:22.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(515, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-15 10:08:00.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CROQCOMPVT'),
(516, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:18:45.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(517, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:19:43.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(518, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:21:57.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(519, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:22:30.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQSKGITZQT'),
(520, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:35:28.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CROQCOMPVT'),
(521, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 10:47:44.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDOHGXDWST'),
(522, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-22 11:24:21.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(523, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-23 18:43:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$300,00 na Confirmação CDOHGXDWST'),
(524, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-23 18:43:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDOHGXDWST'),
(525, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-23 18:45:05.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDTQUBQOAT'),
(526, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-08-29 10:07:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(527, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-01 12:23:52.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(528, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-01 12:24:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(529, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-01 12:25:31.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVPGYXDOQT'),
(530, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-01 12:30:06.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CJPNQUOPPT'),
(531, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-01 12:32:40.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(532, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-06 06:22:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(533, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-06 06:23:06.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQSKGITZQT'),
(534, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-12 00:45:18.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(535, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-14 02:32:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(536, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-17 13:40:24.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CCTSQJNNTT'),
(537, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-17 13:40:36.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CHIKHHJDVT'),
(538, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-17 13:40:50.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQSKGITZQT'),
(539, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-09-17 15:03:52.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(540, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-03 20:17:19.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CNDPGFADAT'),
(541, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 10:56:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(542, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 10:58:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(543, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 10:59:08.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(544, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 11:39:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQSKGITZQT'),
(545, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 11:43:08.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(546, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 13:00:20.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQSKGITZQT'),
(547, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-08 14:55:54.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(548, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-14 15:50:50.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CNDPGFADAT'),
(549, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-15 18:02:12.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(550, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-16 12:39:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$25.000,00 na Confirmação CKLJUWNMMT'),
(551, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-16 12:40:06.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CKLJUWNMMT'),
(552, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-25 12:12:18.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQSKGITZQT'),
(553, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-25 12:36:56.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(554, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-25 12:38:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CROQCOMPVT'),
(555, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-25 12:52:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(556, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-29 17:00:44.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$29.000,00 na Confirmação CVANZIMDGT'),
(557, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-29 17:01:12.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$290,00 na Confirmação CVANZIMDGT'),
(558, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-29 17:02:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(559, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-10-29 17:03:07.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CROQCOMPVT'),
(560, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-04 17:09:56.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(561, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-04 17:11:52.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CNDPGFADAT'),
(562, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-05 20:05:47.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(563, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-05 20:06:34.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQSKGITZQT'),
(564, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-08 16:01:22.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(565, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-09 10:16:04.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHWWPXUEVT'),
(566, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-09 11:05:07.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQSKGITZQT'),
(567, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-11 09:02:29.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CPNFDOANKT'),
(568, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-12 16:40:43.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDOWAKAXHT'),
(569, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-13 12:19:22.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CIXENYMRFT'),
(570, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-21 10:48:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIXENYMRFT'),
(571, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-22 16:07:54.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(572, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-22 17:09:06.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPNFDOANKT'),
(573, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-24 23:36:57.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(574, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-11-24 23:40:04.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIXENYMRFT'),
(575, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-05 10:54:42.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(576, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 18:48:38.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIXENYMRFT'),
(577, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 18:49:33.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(578, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 18:50:05.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CTGJWCAUIT'),
(579, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 18:55:10.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQSKGITZQT'),
(580, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:12:11.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(581, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:16:23.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPNFDOANKT'),
(582, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:20:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(583, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:22:59.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDTQUBQOAT'),
(584, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:25:05.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(585, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:28:51.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(586, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-09 19:38:39.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CIFOQYKQAT'),
(587, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:27:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$31.300,00 na Confirmação CIEXHSVZUT'),
(588, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:27:51.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$313,00 na Confirmação CIEXHSVZUT'),
(589, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:28:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(590, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:41:23.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.980,00 na Confirmação CTGJWCAUIT'),
(591, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:41:34.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$150,00 na Confirmação CTGJWCAUIT'),
(592, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:41:45.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(593, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 12:42:42.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(594, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-14 13:39:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CTGJWCAUIT'),
(595, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-16 22:46:34.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(596, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2024-12-23 13:42:00.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIFOQYKQAT'),
(597, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-02 17:34:15.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(598, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-02 17:34:36.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHWWPXUEVT'),
(599, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-02 17:34:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDOWAKAXHT'),
(600, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-02 17:51:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(601, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-03 12:41:54.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(602, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-03 12:42:17.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPNFDOANKT'),
(603, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-07 17:53:38.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPNFDOANKT'),
(604, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-08 19:22:11.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CIFOQYKQAT'),
(605, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-10 18:12:49.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CTGJWCAUIT'),
(606, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-14 15:36:48.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CTGJWCAUIT'),
(607, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-14 15:39:30.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(608, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-14 15:41:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDTQUBQOAT'),
(609, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15 11:08:41.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CQKCYDXMTT'),
(610, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15 11:19:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CVANZIMDGT'),
(611, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15 11:21:29.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(612, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-15 18:06:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(613, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-17 15:40:19.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CKLJUWNMMT'),
(614, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-17 15:40:37.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CQSKGITZQT'),
(615, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-29 14:25:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(616, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-29 14:36:07.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CEBWUUZVNT'),
(617, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-29 14:38:30.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(618, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-30 10:35:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(619, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31 07:09:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(620, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31 14:00:14.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(621, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31 14:01:19.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CIZCGDRMPT'),
(622, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31 14:01:50.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPNFDOANKT'),
(623, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-04 22:59:08.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(624, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05 15:25:24.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQKCYDXMTT'),
(625, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05 17:19:57.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CPNFDOANKT'),
(626, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-05 18:34:14.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(627, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-10 10:35:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(628, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-10 10:37:51.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 20/03/2025 e 30/03/2025'),
(629, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-10 10:38:09.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 20/03/2025 e 20/03/2025'),
(630, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-10 10:43:17.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CUXORVFYDT'),
(631, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-11 14:52:20.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CEBWUUZVNT'),
(632, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-11 14:53:00.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHOWFDEDGT'),
(633, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-11 15:12:31.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CYCDHVRRET'),
(634, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-11 18:33:23.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CKNVZIMAJT'),
(635, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-12 15:49:50.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CUXORVFYDT'),
(636, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-13 20:16:02.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIZCGDRMPT'),
(637, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-13 20:17:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHOWFDEDGT'),
(638, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-13 20:19:17.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIXENYMRFT'),
(639, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-18 15:51:39.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(640, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-20 18:33:29.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CKNVZIMAJT'),
(641, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-21 18:08:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(642, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-25 11:06:43.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CIKRNLUZTT'),
(643, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-26 09:38:24.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CIKRNLUZTT'),
(644, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-26 09:42:18.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CYCDHVRRET'),
(645, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-26 09:46:45.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIXENYMRFT'),
(646, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-05 14:16:58.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQKCYDXMTT'),
(647, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-05 14:17:53.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPNFDOANKT'),
(648, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-06 11:18:40.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(649, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-06 14:28:52.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CFKGKVUCWT'),
(650, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-08 16:00:35.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(651, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-08 16:00:59.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(652, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-10 18:40:14.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consultoria Cosmetica capilar no valor de R$335,00 na Confirmação CFKGKVUCWT'),
(653, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-10 18:40:32.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFKGKVUCWT'),
(654, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-14 20:19:25.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(655, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-14 20:23:53.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(656, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-14 20:26:37.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(657, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-14 20:27:15.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CIIAWBLSET'),
(658, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-15 18:27:53.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(659, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-15 18:28:11.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CCTSQJNNTT'),
(660, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-17 12:49:14.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(661, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-17 15:21:38.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(662, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-18 15:14:40.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQKCYDXMTT'),
(663, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-18 15:54:53.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(664, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-19 18:32:35.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT');
INSERT INTO `historico_atendimento` (`id`, `token_emp`, `quando`, `quem`, `unico`, `oque`) VALUES
(665, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-19 18:42:04.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(666, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-29 11:38:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CPNFDOANKT'),
(667, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-29 11:46:05.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CLJVPVORQT'),
(668, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-29 11:47:05.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIZCGDRMPT'),
(669, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-02 15:04:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(670, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-07 14:18:59.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIXENYMRFT'),
(671, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-11 09:58:33.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CFKGKVUCWT'),
(672, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-11 18:01:44.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CDLSWGZXZT'),
(673, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 08:47:17.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CLJVPVORQT'),
(674, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 08:47:32.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CPNFDOANKT'),
(675, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 08:48:40.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$250,00 na Confirmação CIIAWBLSET'),
(676, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 12:11:53.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(677, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 21:22:39.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CWOOFMADHT'),
(678, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 21:24:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CFKGKVUCWT'),
(679, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-15 21:25:28.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIIAWBLSET'),
(680, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16 12:29:08.000000', 'Denis Ferraz', '05336888508', 'Criou a consulta CBDXMHKDHT'),
(681, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16 13:00:47.000000', 'Denis Ferraz', '05336888508', 'Cancelou a consulta CBDXMHKDHT'),
(682, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16 15:09:19.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(683, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-16 16:22:04.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(684, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 13:11:19.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 18/04/2025 e 18/04/0258'),
(685, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 13:11:46.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 19/04/2025 e 19/04/2025'),
(686, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 13:12:19.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(687, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 13:12:46.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(688, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 14:06:42.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(689, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 15:26:21.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 17/04/2025 e 17/04/2025'),
(690, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 15:26:44.000000', 'Denis Ferraz', '05336888508', 'Abriu disponibilidade entre as datas 17/04/2025 e 17/04/2025'),
(691, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-17 15:28:43.000000', 'Denis Ferraz', '05336888508', 'Abriu disponibilidade entre as datas 17/04/2025 e 17/04/2025'),
(692, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-22 18:03:46.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDLSWGZXZT'),
(693, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-23 11:50:25.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CCTSQJNNTT'),
(694, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-23 11:50:44.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(695, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-23 11:51:01.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(696, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-24 16:02:09.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQKCYDXMTT'),
(697, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-24 16:53:59.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(698, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-25 00:15:55.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta CIXENYMRFT'),
(699, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-25 19:52:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(700, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-25 19:53:23.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CFKGKVUCWT'),
(701, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:13:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CFKGKVUCWT'),
(702, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:14:14.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHKLEUJYLT'),
(703, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:14:50.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Sinal Consulta no valor de R$50,00 na Confirmação CHKLEUJYLT'),
(704, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:16:22.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar + consultoria no valor de R$500,00 na Confirmação CDLSWGZXZT'),
(705, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:19:34.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CHBQMOXYQT'),
(706, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30 20:20:01.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Sinal Consulta no valor de R$50,00 na Confirmação CHBQMOXYQT'),
(707, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-05 11:08:23.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CHOWFDEDGT'),
(708, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-05 14:03:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHKLEUJYLT'),
(709, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-05 14:03:09.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDLSWGZXZT'),
(710, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-05 14:03:16.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFKGKVUCWT'),
(711, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-06 15:46:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CCTSQJNNTT'),
(712, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-06 17:59:17.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHBQMOXYQT'),
(713, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-07 10:46:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(714, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 10:38:19.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIEXHSVZUT'),
(715, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 14:43:11.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO  no valor de R$1.000,00 na Confirmação CDLSWGZXZT'),
(716, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 15:24:03.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação .pdf na Confirmação CDLSWGZXZT'),
(717, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 15:24:52.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo PósIntradermoterapia.pdf na Confirmação CDLSWGZXZT'),
(718, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 21:55:32.000000', 'Denis Ferraz', '05336888508', 'Criou a consulta CLUDUHHKLT'),
(719, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-12 22:08:52.000000', 'Denis Ferraz', '05336888508', 'Finalizou a consulta CLUDUHHKLT'),
(720, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-20 15:35:33.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CVANZIMDGT'),
(721, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-20 15:40:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CIEXHSVZUT'),
(722, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-20 17:31:36.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CGEDGWFVMT'),
(723, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-20 17:41:46.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(724, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-20 17:55:37.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CIIAWBLSET'),
(725, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21 10:51:37.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CKLJUWNMMT'),
(726, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21 21:57:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CTGJWCAUIT'),
(727, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-21 21:57:11.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGEDGWFVMT'),
(728, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22 16:02:31.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CQKCYDXMTT'),
(729, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22 16:05:21.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CQKCYDXMTT'),
(730, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22 17:03:36.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta CFUVLGPLZT'),
(731, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-22 17:17:07.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDLSWGZXZT'),
(732, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 09:20:37.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDLSWGZXZT'),
(733, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 09:47:43.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta CDTQUBQOAT'),
(734, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:12:15.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/06/2025 e 19/06/2025'),
(735, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:12:47.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 19/06/2025 e 19/06/2025'),
(736, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:13:37.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 23/06/2025 e 23/06/2025'),
(737, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:13:52.000000', 'Caroline Ferraz', '03326635583', 'Fechou disponibilidade entre as datas 23/06/2025 e 23/06/2025'),
(738, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:18:42.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHOWFDEDGT'),
(739, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-23 11:20:01.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(740, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-24 09:50:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHKLEUJYLT'),
(741, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-24 10:50:20.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CDLSWGZXZT'),
(742, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-26 13:38:00.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFUVLGPLZT'),
(743, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-27 13:29:02.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo PósIntradermoterapia.pdf na Confirmação CQKCYDXMTT'),
(744, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-27 13:34:55.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(745, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-27 13:40:37.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(746, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-27 13:43:17.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(747, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:09:33.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(748, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:09:46.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFUVLGPLZT'),
(749, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:09:54.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CGEDGWFVMT'),
(750, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:10:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHOWFDEDGT'),
(751, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:10:13.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CHBQMOXYQT'),
(752, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:10:20.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CFKGKVUCWT'),
(753, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-28 21:10:38.000000', 'Caroline Ferraz', '03326635583', 'Alterou as Configurações'),
(754, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-29 17:52:15.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CKLJUWNMMT'),
(755, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:48:28.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$25.000,00 na Confirmação CHKLEUJYLT'),
(756, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:48:53.000000', 'Caroline Ferraz', '03326635583', 'Lançou  consulta capilar no valor de R$250,00 na Confirmação CHKLEUJYLT'),
(757, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:49:10.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Transferencia no valor de R$250,00 na Confirmação CHKLEUJYLT'),
(758, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:49:21.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Transferencia no valor de R$50,00 na Confirmação CHKLEUJYLT'),
(759, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:49:55.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação micro.pdf na Confirmação CHKLEUJYLT'),
(760, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 11:50:15.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação micro.pdf na Confirmação CHKLEUJYLT'),
(761, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-30 12:02:40.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta CWOOFMADHT'),
(762, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31 18:00:26.000000', 'Denis Ferraz', '05336888508', 'Fechou disponibilidade entre as datas 31/05/2025 e 31/12/2025'),
(763, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 17:41:02.000000', 'Denis Ferraz', '05336888508', 'Lançou  SERVIÇO DE TERAPIA CAPILAR no valor de R$100,00 na Confirmação 78'),
(764, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 17:41:47.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta 1'),
(765, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:04:55.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(766, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:06:25.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Intradermoterapia sessão avulsa no valor de R$250,00 na Confirmação 421'),
(767, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:06:45.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Cartão no valor de R$125,00 na Confirmação 421'),
(768, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:07:01.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Transferencia no valor de R$125,00 na Confirmação 421'),
(769, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:07:30.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Cartão no valor de R$250,00 na Confirmação 421'),
(770, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:09:17.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Ebook Pós Consulta.pdf na Consulta 110'),
(771, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:10:10.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Pós Microagulhamento.pdf na Consulta 110'),
(772, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:16:06.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Transferencia no valor de R$1.000,00 na Confirmação 108'),
(773, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:16:23.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação micro.pdf na Consulta 108'),
(774, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:17:28.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Ebook Pós Consulta.pdf na Consulta 108'),
(775, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:17:58.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Guia HairCARE.pdf na Consulta 108'),
(776, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:20:11.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Ebook 10 hábitos.pdf na Consulta 108'),
(777, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:40:59.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(778, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:45:34.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo EBOOK 10 HABITOS.pdf na Consulta 422'),
(779, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-01 18:45:56.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação micro.pdf na Consulta 422'),
(780, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-03 13:57:01.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(781, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-05 17:28:02.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta 78'),
(782, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-06 16:01:56.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(783, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07 10:10:39.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(784, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07 10:17:03.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta 110'),
(785, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-07 10:19:41.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(786, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:14:40.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.782,00 no Cadastro livia.carvalho@msn.com'),
(787, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:14:50.000000', 'Caroline Ferraz', '03326635583', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$600,00 no Cadastro livia.carvalho@msn.com'),
(788, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:15:00.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Cartão no valor de R$1.782,00 no Cadastro livia.carvalho@msn.com'),
(789, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:15:09.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Dinheiro no valor de R$600,00 no Cadastro livia.carvalho@msn.com'),
(790, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:15:55.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Recomendação intrade.pdf na Consulta '),
(791, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 11:16:18.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Ebook 10 hábitos.pdf na Consulta '),
(792, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-09 12:08:24.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(793, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13 12:46:57.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 108'),
(794, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13 16:53:05.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta 100'),
(795, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13 16:55:43.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(796, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13 18:06:09.000000', 'Caroline Ferraz', '03326635583', 'Finalizou a consulta 90'),
(797, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-13 18:06:27.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(798, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-14 23:54:50.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(799, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:36:36.000000', 'Denis Ferraz', '05336888508', 'Lançou  SERVIÇO DE TERAPIA CAPILAR no valor de R$120,00 no Cadastro priscilaeve@hotmail.com'),
(800, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:37:59.000000', 'Denis Ferraz', '05336888508', 'Lançou  Pagamento em Cartão no valor de R$120,00 no Cadastro priscilaeve@hotmail.com'),
(801, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:42:11.000000', 'Denis Ferraz', '05336888508', 'Lançou  SERVIÇO DE TERAPIA CAPILAR no valor de R$100,00 no Cadastro priscilaeve@hotmail.com'),
(802, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:45:43.000000', 'Denis Ferraz', '05336888508', 'Lançou  SERVIÇO DE TERAPIA CAPILAR no valor de R$100,00 no Cadastro luciana_gomes@hotmail.com'),
(803, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:47:05.000000', 'Denis Ferraz', '05336888508', 'Lançou  Oleo de Amendoas no valor de R$200,00 no Cadastro luciana_gomes@hotmail.com'),
(804, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:49:17.000000', 'Denis Ferraz', '05336888508', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$125,00 no Cadastro luciana_gomes@hotmail.com'),
(805, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 09:56:16.000000', 'Denis Ferraz', '05336888508', 'Lançou  Oleo de Amendoas no valor de R$120,00 no Cadastro luciana_gomes@hotmail.com'),
(806, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 10:26:22.000000', 'Denis Ferraz', '05336888508', 'Lançou  PLANO DE TRATAMENTO EM CONSULTÓRIO no valor de R$1.500,00 no Cadastro luciana_gomes@hotmail.com'),
(807, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 12:38:02.000000', 'Denis Ferraz', '05336888508', 'Lançou  6 no valor de R$100,00 no Cadastro luciana_gomes@hotmail.com'),
(808, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 12:40:11.000000', 'Denis Ferraz', '05336888508', 'Lançou  6 no valor de R$12,00 no Cadastro luciana_gomes@hotmail.com'),
(809, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 22:42:15.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Receituário Fase1.pdf na Consulta '),
(810, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-15 22:42:37.000000', 'Caroline Ferraz', '03326635583', 'Cadastrou um novo Arquivo Ebook pos consulta.pdf na Consulta '),
(811, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-16 11:17:15.000000', 'Caroline Ferraz', '03326635583', 'Criou a consulta '),
(812, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 09:58:04.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(813, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 10:19:39.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(814, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 10:20:13.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(815, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 10:20:39.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(816, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 10:20:54.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(817, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 10:57:56.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$100,00 no Cadastro luciana_gomes@hotmail.com'),
(818, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 11:01:39.000000', 'Caroline Ferraz', '03326635583', 'Cancelou a consulta 96'),
(819, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 11:16:21.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 57'),
(820, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 11:49:11.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 83'),
(821, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 12:03:32.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 108'),
(822, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 12:30:49.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$120,00 no Cadastro luciana_gomes@hotmail.com'),
(823, '1d0decbc35d94ce603d3ab6baedafc90', '2025-06-17 14:28:55.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(824, '1d0decbc35d94ce603d3ab6baedafc90', '2025-06-17 14:29:02.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(825, '1d0decbc35d94ce603d3ab6baedafc90', '2025-06-17 14:30:18.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(826, '1d0decbc35d94ce603d3ab6baedafc90', '2025-06-17 14:32:01.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(827, '1d0decbc35d94ce603d3ab6baedafc90', '2025-06-17 14:32:10.000000', 'Denis Ferraz', '05336888508', 'Alterou as Configurações'),
(828, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 15:52:26.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 424'),
(829, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-17 15:53:09.000000', 'Caroline Ferraz', '03326635583', 'Alterou a consulta 98'),
(830, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 10:11:48.000000', 'Sandra Maria de Assis Costa', '10735100500', 'Finalizou a consulta 83'),
(831, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 12:50:10.000000', 'Sandra Maria de Assis Costa', '10735100500', 'Criou a consulta '),
(832, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 12:55:53.000000', 'Caroline Ferraz', '03326635583', 'Lançou  Pagamento em Dinheiro no valor de R$264,00 no Cadastro sand11cost@gmail.com'),
(833, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 12:59:41.000000', 'Karla Malta Amorim Soares ', '02403075507', 'Finalizou a consulta 429'),
(834, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:12:09.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$100,00 no Cadastro sand11cost@gmail.com'),
(835, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:18:08.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$100,00 no Cadastro sand11cost@gmail.com'),
(836, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:18:16.000000', 'Denis Ferraz', '05336888508', 'Lançou  teste no valor de R$160,00 no Cadastro sand11cost@gmail.com'),
(837, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:20:59.000000', 'Denis Ferraz', '05336888508', 'Lançou  Teleconsulta com Prescrição no valor de R$160,00 no Cadastro sand11cost@gmail.com'),
(838, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:21:09.000000', 'Denis Ferraz', '05336888508', 'Lançou  Microagulhamento Lauro no valor de R$350,00 no Cadastro sand11cost@gmail.com'),
(839, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:21:18.000000', 'Denis Ferraz', '05336888508', 'Lançou  Microagulhamento SSA no valor de R$390,00 no Cadastro sand11cost@gmail.com'),
(840, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:22:35.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$100,00 no Cadastro sand11cost@gmail.com'),
(841, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 13:22:57.000000', 'Denis Ferraz', '05336888508', 'Lançou  17 alfa estradiol cx com 10un no valor de R$100,00 no Cadastro sand11cost@gmail.com'),
(842, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 15:14:42.000000', 'Denis Ferraz', '05336888508', 'Lançou 5 17 alfa estradiol cx com 10un no valor de R$120,00 no Cadastro luciana_gomes@hotmail.com'),
(843, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 15:23:36.000000', 'Denis Ferraz', '05336888508', 'Lançou 8 17 alfa estradiol cx com 10un no valor de R$10,00 no Cadastro luciana_gomes@hotmail.com'),
(844, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 15:25:31.000000', 'Denis Ferraz', '05336888508', 'Lançou 9 17 alfa estradiol cx com 10un no valor de R$10,00 no Cadastro luciana_gomes@hotmail.com'),
(845, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 15:27:30.000000', 'Denis Ferraz', '05336888508', 'Lançou 5 17 alfa estradiol cx com 10un no valor de R$10,00 no Cadastro luciana_gomes@hotmail.com'),
(846, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 18:19:23.000000', 'Larissa Dias dos Santos ', '10800007557', 'Finalizou a consulta 428'),
(847, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18 18:19:38.000000', 'Larissa Dias dos Santos ', '10800007557', 'Criou a consulta '),
(848, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:14:10.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Microagulhamento Lauro no valor de R$264,00 no Cadastro sand11cost@gmail.com'),
(849, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:14:52.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Consulta Capilar Lauro no valor de R$270,00 no Cadastro karla.amorim@gmail.com'),
(850, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:15:03.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Pagamento em Dinheiro no valor de R$50,00 no Cadastro karla.amorim@gmail.com'),
(851, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:15:08.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Pagamento em Cartão no valor de R$220,00 no Cadastro karla.amorim@gmail.com'),
(852, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:16:08.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Consulta Capilar Lauro no valor de R$270,00 no Cadastro raquelsinal3@gmail.com'),
(853, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:16:16.000000', 'Caroline Ferraz', '03326635583', 'Lançou 1 Pagamento em Transferencia no valor de R$270,00 no Cadastro raquelsinal3@gmail.com'),
(854, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:16:44.000000', 'Raquel Freire', '88810089553', 'Finalizou a consulta 423'),
(855, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 11:27:25.000000', 'Luciana Gomes', '02853888517', 'Alterou a consulta 426'),
(856, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20 13:49:57.000000', 'Weslen Vinicius de Souza goes', '07148847583', 'Criou a consulta ');

-- --------------------------------------------------------

--
-- Table structure for table `lancamentos`
--

CREATE TABLE `lancamentos` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
  `data_lancamento` date NOT NULL,
  `conta_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `recorrente` varchar(15) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `feitopor` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lancamentos`
--

INSERT INTO `lancamentos` (`id`, `token_emp`, `data_lancamento`, `conta_id`, `descricao`, `recorrente`, `valor`, `observacoes`, `feitopor`, `created_at`, `updated_at`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 16, 'Agulha', 'nao', 128.01, '', 'Caroline Ferraz', '2025-06-15 05:36:03', '2025-06-21 01:29:04'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 32, 'Sala', 'nao', 488.00, '', 'Caroline Ferraz', '2025-06-15 05:37:01', '2025-06-21 01:29:04'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 33, 'Plano', 'nao', 37.81, '', 'Caroline Ferraz', '2025-06-15 05:37:21', '2025-06-21 01:29:04'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 36, 'Gasolina', 'nao', 200.00, '', 'Caroline Ferraz', '2025-06-15 05:37:42', '2025-06-21 01:29:04'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 38, 'Site', 'nao', 126.95, '', 'Caroline Ferraz', '2025-06-15 05:38:05', '2025-06-21 01:29:04'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 39, 'Estacionamento', 'nao', 64.00, '', 'Caroline Ferraz', '2025-06-15 05:38:26', '2025-06-21 01:29:04'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 58, 'Cursos', 'nao', 525.22, '', 'Caroline Ferraz', '2025-06-15 05:38:53', '2025-06-21 01:29:04'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-01-31', 1, 'Consultas', 'nao', 1180.00, '', 'Caroline Ferraz', '2025-06-15 05:39:35', '2025-06-21 01:29:04'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 1, 'Todos os serviços da clinica', 'nao', 5739.28, '', 'Caroline Ferraz', '2025-06-18 15:41:34', '2025-06-21 01:29:04'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 12, 'investimento', 'nao', 183.16, '', 'Caroline Ferraz', '2025-06-18 15:42:12', '2025-06-21 01:29:04'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 14, 'Parcerias', 'nao', 195.01, '', 'Caroline Ferraz', '2025-06-18 15:42:43', '2025-06-21 01:29:04'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 16, 'Matérias Primas', 'nao', 746.42, '', 'Caroline Ferraz', '2025-06-18 15:43:02', '2025-06-21 01:29:04'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 18, 'Pro-labore', 'nao', 300.00, '', 'Caroline Ferraz', '2025-06-18 15:43:19', '2025-06-21 01:29:04'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 32, 'Coworking', 'nao', 444.00, '', 'Caroline Ferraz', '2025-06-18 15:43:35', '2025-06-21 01:29:04'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 33, 'Conta de Celular', 'nao', 40.77, '', 'Caroline Ferraz', '2025-06-18 15:43:48', '2025-06-21 01:29:04'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 36, 'Gasolina', 'nao', 100.00, '', 'Caroline Ferraz', '2025-06-18 15:44:05', '2025-06-21 01:29:04'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 38, 'Software', 'nao', 94.05, '', 'Caroline Ferraz', '2025-06-18 15:44:17', '2025-06-21 01:29:04'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 39, 'Estacionamento', 'nao', 133.00, '', 'Caroline Ferraz', '2025-06-18 15:44:29', '2025-06-21 01:29:04'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 48, 'Despesas e Tarifas Bancárias', 'nao', 19.04, '', 'Caroline Ferraz', '2025-06-18 15:44:41', '2025-06-21 01:29:04'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 58, 'Cursos e Materiais de Estudo', 'nao', 371.56, '', 'Caroline Ferraz', '2025-06-18 15:44:59', '2025-06-21 01:29:04'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-02-28', 59, 'Máquinas e Equipamentos', 'nao', 211.41, '', 'Caroline Ferraz', '2025-06-18 15:45:12', '2025-06-21 01:29:04'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 1, 'Todos os serviços da clinica', 'nao', 1189.05, '', 'Caroline Ferraz', '2025-06-18 15:47:08', '2025-06-21 01:29:04'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 12, 'investimento', 'nao', 229.15, '', 'Caroline Ferraz', '2025-06-18 15:47:24', '2025-06-21 01:29:04'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 16, 'Matérias Primas', 'nao', 107.38, '', 'Caroline Ferraz', '2025-06-18 15:47:43', '2025-06-21 01:29:04'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 32, 'Coworking', 'nao', 390.00, '', 'Caroline Ferraz', '2025-06-18 15:52:24', '2025-06-21 01:29:04'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 33, 'Conta de Celular', 'nao', 40.79, '', 'Caroline Ferraz', '2025-06-18 15:52:36', '2025-06-21 01:29:04'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 36, 'Gasolina', 'nao', 125.30, '', 'Caroline Ferraz', '2025-06-18 15:52:47', '2025-06-21 01:29:04'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 38, 'Software', 'nao', 102.55, '', 'Caroline Ferraz', '2025-06-18 15:53:00', '2025-06-21 01:29:04'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 39, 'Estacionamento', 'nao', 147.00, '', 'Caroline Ferraz', '2025-06-18 15:53:10', '2025-06-21 01:29:04'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 40, 'Alimentacao', 'nao', 112.62, '', 'Caroline Ferraz', '2025-06-18 15:54:54', '2025-06-21 01:29:04'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 58, 'Cursos e Materiais de Estudo', 'nao', 430.00, '', 'Caroline Ferraz', '2025-06-18 15:55:07', '2025-06-21 01:29:04'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-03-31', 59, 'Máquinas e Equipamentos', 'nao', 211.41, '', 'Caroline Ferraz', '2025-06-18 15:55:17', '2025-06-21 01:29:04'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 1, 'Todos os serviços da clinica', 'nao', 1410.52, '', 'Caroline Ferraz', '2025-06-18 15:56:15', '2025-06-21 01:29:04'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 12, 'investimento', 'nao', 255.11, '', 'Caroline Ferraz', '2025-06-18 15:56:28', '2025-06-21 01:29:04'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 14, 'Parcerias', 'nao', 255.00, '', 'Caroline Ferraz', '2025-06-18 15:56:39', '2025-06-21 01:29:04'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 18, 'Pro-labore', 'nao', 80.00, '', 'Caroline Ferraz', '2025-06-18 15:56:51', '2025-06-21 01:29:04'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 27, 'Brindes pacientes', 'nao', 19.20, '', 'Caroline Ferraz', '2025-06-18 15:57:03', '2025-06-21 01:29:04'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 32, 'Coworking', 'nao', 250.01, '', 'Caroline Ferraz', '2025-06-18 15:57:15', '2025-06-21 01:29:04'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 33, 'Conta de Celular', 'nao', 40.84, '', 'Caroline Ferraz', '2025-06-18 15:57:23', '2025-06-21 01:29:04'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 36, 'Gasolina', 'nao', 190.73, '', 'Caroline Ferraz', '2025-06-18 15:57:33', '2025-06-21 01:29:04'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 38, 'Software', 'nao', 102.55, '', 'Caroline Ferraz', '2025-06-18 15:57:43', '2025-06-21 01:29:04'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 39, 'Estacionamento', 'nao', 47.00, '', 'Caroline Ferraz', '2025-06-18 15:57:51', '2025-06-21 01:29:04'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 40, 'Alimentacao', 'nao', 112.62, '', 'Caroline Ferraz', '2025-06-18 15:57:59', '2025-06-21 01:29:04'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 58, 'Cursos e Materiais de Estudo', 'nao', 429.76, '', 'Caroline Ferraz', '2025-06-18 15:58:13', '2025-06-21 01:29:04'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-04-30', 59, 'Máquinas e Equipamentos', 'nao', 211.41, '', 'Caroline Ferraz', '2025-06-18 15:58:22', '2025-06-21 01:29:04'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 1, 'Todos os serviços da clinica', 'nao', 3044.47, '', 'Caroline Ferraz', '2025-06-18 15:59:56', '2025-06-21 01:29:04'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 12, 'investimento', 'nao', 297.04, '', 'Caroline Ferraz', '2025-06-18 16:00:11', '2025-06-21 01:29:04'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 14, 'Parcerias', 'nao', 555.00, '', 'Caroline Ferraz', '2025-06-18 16:00:21', '2025-06-21 01:29:04'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 16, 'Matérias Primas', 'nao', 519.34, '', 'Caroline Ferraz', '2025-06-18 16:00:32', '2025-06-21 01:29:04'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 24, 'Tráfego Google Ads', 'nao', 62.06, '', 'Caroline Ferraz', '2025-06-18 16:00:47', '2025-06-21 01:29:04'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 27, 'Brindes pacientes', 'nao', 7.93, '', 'Caroline Ferraz', '2025-06-18 16:00:58', '2025-06-21 01:29:04'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 32, 'Coworking', 'nao', 978.00, '', 'Caroline Ferraz', '2025-06-18 16:01:18', '2025-06-21 01:29:04'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 33, 'Conta de Celular', 'nao', 39.99, '', 'Caroline Ferraz', '2025-06-18 16:01:26', '2025-06-21 01:29:04'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 36, 'Gasolina', 'nao', 100.00, '', 'Caroline Ferraz', '2025-06-18 16:01:36', '2025-06-21 01:29:04'),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 38, 'Software', 'nao', 94.98, '', 'Caroline Ferraz', '2025-06-18 16:01:47', '2025-06-21 01:29:04'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 39, 'Estacionamento', 'nao', 91.00, '', 'Caroline Ferraz', '2025-06-18 16:01:56', '2025-06-21 01:29:04'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 40, 'Alimentacao', 'nao', 50.71, '', 'Caroline Ferraz', '2025-06-18 16:02:04', '2025-06-21 01:29:04'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 52, 'Imposto Federal/Estadual/Mun', 'nao', 9.00, '', 'Caroline Ferraz', '2025-06-18 16:02:17', '2025-06-21 01:29:04'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 58, 'Cursos e Materiais de Estudo', 'nao', 449.64, '', 'Caroline Ferraz', '2025-06-18 16:02:30', '2025-06-21 01:29:04'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-05-31', 59, 'Máquinas e Equipamentos', 'nao', 211.41, '', 'Caroline Ferraz', '2025-06-18 16:02:42', '2025-06-21 01:29:04'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 1, 'Todos os serviços da clinica', 'nao', 2560.38, '', 'Caroline Ferraz', '2025-06-18 16:04:39', '2025-06-21 01:29:04'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 14, 'Parcerias', 'nao', 300.00, '', 'Caroline Ferraz', '2025-06-18 16:04:50', '2025-06-21 01:29:04'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 24, 'Tráfego Google Ads', 'nao', 31.00, '', 'Caroline Ferraz', '2025-06-18 16:05:03', '2025-06-21 01:29:04'),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 36, 'Gasolina', 'nao', 300.00, '', 'Caroline Ferraz', '2025-06-18 16:05:17', '2025-06-21 01:29:04'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 38, 'Software', 'nao', 359.88, '', 'Caroline Ferraz', '2025-06-18 16:05:30', '2025-06-21 01:29:04'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 39, 'Estacionamento', 'nao', 166.00, '', 'Caroline Ferraz', '2025-06-18 16:05:39', '2025-06-21 01:29:04'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 40, 'Alimentacao', 'nao', 54.78, '', 'Caroline Ferraz', '2025-06-18 16:05:48', '2025-06-21 01:29:04'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 58, 'Cursos e Materiais de Estudo', 'nao', 514.42, '', 'Caroline Ferraz', '2025-06-18 16:06:06', '2025-06-21 01:29:04'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 59, 'Máquinas e Equipamentos', 'nao', 211.41, '', 'Caroline Ferraz', '2025-06-18 16:06:18', '2025-06-21 01:29:04'),
(119, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20', 1, 'Microagulhamento Lauro', 'nao', 264.00, '', 'Caroline Ferraz', '2025-06-20 14:14:10', '2025-06-21 01:29:04'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20', 69, '[Estornado] Microagulhamento Lauro', 'nao', -264.00, '', 'Caroline Ferraz', '2025-06-20 14:14:25', '2025-06-21 01:29:04'),
(121, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20', 1, 'Consulta Capilar Lauro', 'nao', 270.00, '', 'Caroline Ferraz', '2025-06-20 14:14:52', '2025-06-21 01:29:04'),
(122, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20', 1, 'Consulta Capilar Lauro', 'nao', 270.00, '', 'Caroline Ferraz', '2025-06-20 14:16:08', '2025-06-21 01:29:04'),
(123, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-18', 32, 'Pagamento mensal', 'nao', 443.00, '', 'Caroline Ferraz', '2025-06-20 14:22:08', '2025-06-21 01:29:04'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '2025-06-20', 16, 'Máscara descartável cx com 50un', '', 1300.00, '', 'Caroline Ferraz', '2025-06-21 01:42:05', '2025-06-21 01:42:05');

-- --------------------------------------------------------

--
-- Table structure for table `lancamentos_atendimento`
--

CREATE TABLE `lancamentos_atendimento` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `doc_email` varchar(100) NOT NULL,
  `produto` text NOT NULL,
  `quantidade` int(5) NOT NULL,
  `valor` float NOT NULL,
  `quando` datetime(6) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `feitopor` varchar(30) DEFAULT NULL,
  `doc_nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `lancamentos_atendimento`
--

INSERT INTO `lancamentos_atendimento` (`id`, `token_emp`, `doc_email`, `produto`, `quantidade`, `valor`, `quando`, `tipo`, `feitopor`, `doc_nome`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1380, '2023-05-07 19:00:00.000000', 'Produto', 'Caroline Ferraz', 'Aline da Rocha Santos'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Produtos Grandha', 1, 252, '2023-05-07 19:16:00.000000', 'Produto', 'Caroline Ferraz', 'Aline da Rocha Santos'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Pagamento em Cartão', 1, -1632, '2023-05-07 19:17:00.000000', 'Pagamento', 'Caroline Ferraz', 'Aline da Rocha Santos'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 2880, '2023-05-07 19:23:00.000000', 'Produto', 'Caroline Ferraz', 'Carina de Santana'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Produtos Grandha', 1, 85, '2023-05-07 19:24:00.000000', 'Produto', 'Caroline Ferraz', 'Carina de Santana'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Pagamento em Cartão', 1, -2965, '2023-05-07 19:24:00.000000', 'Pagamento', 'Caroline Ferraz', 'Carina de Santana'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Pagamento em Cartão', 1, -2965, '2023-05-07 19:24:00.000000', 'Pagamento', 'Caroline Ferraz', 'Carina de Santana'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Pagamento em Cartão [ Estornado ]', 1, 2965, '2023-05-07 19:25:00.000000', 'Estorno', 'Caroline Ferraz', 'Carina de Santana'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 590, '2023-05-07 19:26:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -590, '2023-05-07 19:26:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1870, '2023-05-07 19:35:00.000000', 'Produto', 'Caroline Ferraz', 'Everton Pinheiro de Santana Sa'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Pagamento em Cartão', 1, -1870, '2023-05-07 19:35:00.000000', 'Pagamento', 'Caroline Ferraz', 'Everton Pinheiro de Santana Sa'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1050, '2023-05-07 19:39:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Dinheiro', 1, -500, '2023-05-07 19:41:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Cartão', 1, -550, '2023-05-07 19:41:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1050, '2023-05-18 17:44:00.000000', 'Produto', 'Caroline Ferraz', 'Henrique Amon Silva Freitas'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'consulta capilar', 1, 300, '2023-05-18 17:45:00.000000', 'Produto', 'Caroline Ferraz', 'Henrique Amon Silva Freitas'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Pagamento em Outros', 1, -300, '2023-05-18 17:45:00.000000', 'Pagamento', 'Caroline Ferraz', 'Henrique Amon Silva Freitas'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 500, '2023-05-23 19:07:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 550, '2023-05-23 19:08:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Dinheiro', 1, -500, '2023-05-23 19:08:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Cartão', 1, -550, '2023-05-23 19:09:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1050, '2023-05-23 19:17:00.000000', 'Produto', 'Caroline Ferraz', 'Henrique Amon Silva Freitas '),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Pagamento em Cartão', 1, -1050, '2023-05-23 19:17:00.000000', 'Pagamento', 'Caroline Ferraz', 'Henrique Amon Silva Freitas '),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 997, '2023-06-06 19:06:00.000000', 'Produto', 'Caroline Ferraz', 'Aline da Rocha Santos'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Pagamento em Dinheiro', 1, -997, '2023-06-06 19:07:00.000000', 'Pagamento', 'Caroline Ferraz', 'Aline da Rocha Santos'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 330, '2023-06-17 11:54:00.000000', 'Produto', 'Caroline Ferraz', 'Carina de Santana'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Pagamento em Cartão', 1, -330, '2023-06-17 11:54:00.000000', 'Pagamento', 'Caroline Ferraz', 'Carina de Santana'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 976, '2023-07-27 17:48:00.000000', 'Produto', 'Caroline Ferraz', 'Michel Oliveira da Silva Souza'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Pagamento em Dinheiro', 1, -976, '2023-07-27 17:48:00.000000', 'Pagamento', 'Caroline Ferraz', 'Michel Oliveira da Silva Souza'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 980, '2023-08-02 16:59:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Cartão', 1, -980, '2023-08-02 18:11:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1990, '2023-08-05 09:18:00.000000', 'Produto', 'Caroline Ferraz', 'Evanilson dos Santos Oliveira '),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Pagamento em Cartão', 1, -1990, '2023-08-05 09:18:00.000000', 'Pagamento', 'Caroline Ferraz', 'Evanilson dos Santos Oliveira '),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Devolução de valor pago', 1, 90, '2023-08-21 12:16:00.000000', 'Produto', 'Caroline Ferraz', 'Michel Oliveira da Silva Souza'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Pagamento em Dinheiro', 1, -90, '2023-08-21 12:17:00.000000', 'Pagamento', 'Caroline Ferraz', 'Michel Oliveira da Silva Souza'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 330, '2023-09-03 17:26:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -330, '2023-09-03 17:26:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 980, '2023-09-03 17:27:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Pagamento em Cartão', 1, -980, '2023-09-03 17:27:00.000000', 'Pagamento', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1550, '2023-09-03 17:27:00.000000', 'Produto', 'Caroline Ferraz', 'Priscila Albuquerque Adorno'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Pagamento em Cartão', 1, -1550, '2023-09-03 17:27:00.000000', 'Pagamento', 'Caroline Ferraz', 'Priscila Albuquerque Adorno'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1740, '2023-10-13 12:13:00.000000', 'Produto', 'Caroline Ferraz', 'Priscila Eve Silva dos Santos '),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Pagamento em Cartão', 1, -1740, '2023-10-13 12:14:00.000000', 'Pagamento', 'Caroline Ferraz', 'Priscila Eve Silva dos Santos '),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2023-10-13 12:19:00.000000', 'Produto', 'Caroline Ferraz', 'Breno Almeida Santana'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Pagamento em Dinheiro', 1, -250, '2023-10-13 12:20:00.000000', 'Pagamento', 'Caroline Ferraz', 'Breno Almeida Santana'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 980, '2023-10-18 18:08:00.000000', 'Produto', 'Caroline Ferraz', 'Manuela de Cássia Filgueiras F'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 270, '2023-11-07 18:49:00.000000', 'Produto', 'Caroline Ferraz', 'Rosana da silva santos'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Pagamento em Dinheiro', 1, -270, '2023-11-07 18:49:00.000000', 'Pagamento', 'Caroline Ferraz', 'Rosana da silva santos'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 240, '2023-11-07 19:32:00.000000', 'Produto', 'Caroline Ferraz', 'Jailson Lopes dos Santos Júnio'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Pagamento em Cartão', 1, -240, '2023-11-07 19:32:00.000000', 'Pagamento', 'Caroline Ferraz', 'Jailson Lopes dos Santos Júnio'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 931, '2023-11-07 19:35:00.000000', 'Produto', 'Caroline Ferraz', 'Kaique paulo da silva Araújo'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Pagamento em Dinheiro', 1, -931, '2023-11-07 19:35:00.000000', 'Pagamento', 'Caroline Ferraz', 'Kaique paulo da silva Araújo'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1740, '2023-11-17 17:00:00.000000', 'Produto', 'Caroline Ferraz', 'Bruno da Hora Ferreira'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Pagamento em Cartão', 1, -1740, '2023-11-17 17:00:00.000000', 'Pagamento', 'Caroline Ferraz', 'Bruno da Hora Ferreira'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'filipeferreira99@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 290, '2023-12-11 17:36:00.000000', 'Produto', 'Caroline Ferraz', 'Filipe Nascimento Ferreira '),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'filipeferreira99@hotmail.com', 'Pagamento em Cartão', 1, -290, '2023-12-11 17:36:00.000000', 'Pagamento', 'Caroline Ferraz', 'Filipe Nascimento Ferreira '),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'tiago.manicoba@gmail.com', 'Consulta Capilar ', 1, 250, '2024-01-04 17:10:00.000000', 'Produto', 'Caroline Ferraz', 'Tiago Amorim'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'tiago.manicoba@gmail.com', 'Pagamento em Outros', 1, -250, '2024-01-04 17:11:00.000000', 'Pagamento', 'Caroline Ferraz', 'Tiago Amorim'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-01-13 11:35:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -250, '2024-01-13 11:35:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-01-16 16:20:00.000000', 'Produto', 'Caroline Ferraz', 'Breno Almeida Santana'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Pagamento em Transferencia', 1, -250, '2024-01-16 16:20:00.000000', 'Pagamento', 'Caroline Ferraz', 'Breno Almeida Santana'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-03-12 09:54:00.000000', 'Produto', 'Caroline Ferraz', 'Luciana Gomes'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'emersonsc1000@gmail.com', 'consulta capilar', 1, 300, '2024-03-28 15:36:00.000000', 'Produto', 'Caroline Ferraz', ' Emerson Soares Conrado '),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'emersonsc1000@gmail.com', 'Pagamento em Cartão', 1, -300, '2024-03-28 15:36:00.000000', 'Pagamento', 'Caroline Ferraz', ' Emerson Soares Conrado '),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Dinheiro', 1, -125, '2024-04-20 15:45:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-04-20 15:46:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -125, '2024-04-20 15:46:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-04-20 15:56:00.000000', 'Produto', 'Caroline Ferraz', 'Luciana Gomes'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Pagamento em Dinheiro', 1, -250, '2024-04-20 15:56:00.000000', 'Pagamento', 'Caroline Ferraz', 'Luciana Gomes'),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Pagamento em Dinheiro', 1, -250, '2024-04-20 15:57:00.000000', 'Pagamento', 'Caroline Ferraz', 'Luciana Gomes'),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-05-02 18:06:00.000000', 'Produto', 'Caroline Ferraz', 'Breno Almeida Santana'),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Pagamento em Dinheiro', 1, -250, '2024-05-02 18:06:00.000000', 'Pagamento', 'Caroline Ferraz', 'Breno Almeida Santana'),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-05-25 13:47:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Dinheiro', 1, -250, '2024-05-25 13:47:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-05-25 13:52:00.000000', 'Produto', 'Caroline Ferraz', 'Luciana Gomes'),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Pagamento em Transferencia', 1, -250, '2024-05-25 13:52:00.000000', 'Pagamento', 'Caroline Ferraz', 'Luciana Gomes'),
(79, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'betamerces38@gmail.com', 'consulta capilar', 1, 250, '2024-05-25 13:53:00.000000', 'Produto', 'Caroline Ferraz', 'Betânia Merces de Araujo'),
(80, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'betamerces38@gmail.com', 'Pagamento em Dinheiro', 1, -200, '2024-05-25 13:53:00.000000', 'Pagamento', 'Caroline Ferraz', 'Betânia Merces de Araujo'),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'betamerces38@gmail.com', 'Pagamento em Cartão', 1, -50, '2024-05-25 13:54:00.000000', 'Pagamento', 'Caroline Ferraz', 'Betânia Merces de Araujo'),
(82, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'consulta capilar', 1, 300, '2024-05-25 13:54:00.000000', 'Produto', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Pagamento em Cartão', 1, -150, '2024-05-25 13:54:00.000000', 'Pagamento', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Pagamento em Transferencia', 1, -150, '2024-05-25 13:54:00.000000', 'Pagamento', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(85, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'exemplo@exemplo.com.br', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1680, '2024-05-25 14:01:00.000000', 'Produto', 'Caroline Ferraz', 'Caroline da Cruz Lordêlo'),
(86, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'exemplo@exemplo.com.br', 'Pagamento em Cartão', 1, -1680, '2024-05-25 14:01:00.000000', 'Pagamento', 'Caroline Ferraz', 'Caroline da Cruz Lordêlo'),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO [ Estornad', 0, 0, '2024-05-25 14:03:00.000000', 'Produto', 'Caroline Ferraz', 'Rosana da silva santos'),
(88, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Pagamento em Transferencia', 1, -250, '2024-05-25 14:03:00.000000', 'Pagamento', 'Caroline Ferraz', 'Rosana da silva santos'),
(89, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1187, '2024-06-04 14:02:00.000000', 'Produto', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'neilsonrabelo@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 520, '2024-08-06 12:18:00.000000', 'Produto', 'Caroline Ferraz', 'Neilson Bernardo Rabelo'),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Pagamento em Cartão', 1, -1890, '2024-08-06 13:09:00.000000', 'Pagamento', 'Caroline Ferraz', 'Caroline da Cruz Lordelo'),
(92, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', 'consulta capilar', 1, 300, '2024-08-23 18:43:00.000000', 'Produto', 'Caroline Ferraz', 'Larissa Dias dos Santos '),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Pagamento em Transferencia', 1, -250, '2024-10-16 12:38:00.000000', 'Pagamento', 'Caroline Ferraz', 'Rosana da silva santos'),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO [ Estornad', 0, 0, '2024-10-16 12:39:00.000000', 'Produto', 'Caroline Ferraz', 'Rosana da silva santos'),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2024-10-16 12:40:00.000000', 'Produto', 'Caroline Ferraz', 'Rosana da silva santos'),
(96, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO [ Estornad', 0, 0, '2024-10-29 17:00:00.000000', 'Produto', 'Caroline Ferraz', 'Bruno da Hora Ferreira'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 290, '2024-10-29 17:01:00.000000', 'Produto', 'Caroline Ferraz', 'Bruno da Hora Ferreira'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Pagamento em Dinheiro', 1, -313, '2024-12-14 12:26:00.000000', 'Pagamento', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO [ Estornad', 0, 0, '2024-12-14 12:27:00.000000', 'Produto', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(100, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 313, '2024-12-14 12:27:00.000000', 'Produto', 'Caroline Ferraz', 'Sandra Maria de Assis Costa '),
(101, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1980, '2024-12-14 12:41:00.000000', 'Produto', 'Caroline Ferraz', 'Everton Pinheiro de Santana Sa'),
(102, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'consulta capilar', 1, 150, '2024-12-14 12:41:00.000000', 'Produto', 'Caroline Ferraz', 'Everton Pinheiro de Santana Sa'),
(103, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'Pagamento em Transferencia', 1, -332, '2025-01-07 17:53:00.000000', 'Pagamento', 'Caroline Ferraz', 'Alexandre da Silva Oliveira '),
(104, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Pagamento em Transferencia', 1, -250, '2025-01-15 18:06:00.000000', 'Pagamento', 'Caroline Ferraz', 'Breno Almeida Santana'),
(105, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Pagamento em Dinheiro', 1, -285, '2025-02-04 23:26:00.000000', 'Pagamento', 'Caroline Ferraz', 'Elisangela Jesus da Silva '),
(106, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquel.d.santos110194@gmail.com', 'Pagamento em Cartão', 1, -50, '2025-02-11 19:02:00.000000', 'Pagamento', 'Caroline Ferraz', 'Raquel dos santos '),
(107, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'adrianamagalhaes026@gmail.com', 'Pagamento em Cartão', 1, -300, '2025-02-12 15:49:00.000000', 'Pagamento', 'Caroline Ferraz', 'Adriana trindade Magalhães Tav'),
(108, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquel.d.santos110194@gmail.com', 'Pagamento em Cartão', 1, -250, '2025-02-13 20:16:00.000000', 'Pagamento', 'Caroline Ferraz', 'Raquel dos santos '),
(109, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'lucassimas160@hotmail.com', 'Pagamento em Cartão', 1, -200, '2025-02-13 20:17:00.000000', 'Pagamento', 'Caroline Ferraz', 'Lucas Santos da Silva Simas'),
(110, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Pagamento em Cartão', 1, -1460, '2025-02-21 17:36:00.000000', 'Pagamento', 'Caroline Ferraz', 'Bruno da Hora Ferreira'),
(111, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'emersonsc1000@gmail.com', 'Pagamento em Cartão', 1, -150, '2025-02-26 09:38:00.000000', 'Pagamento', 'Caroline Ferraz', 'Emerson Soares Conrado'),
(112, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'arianedasilvasantos2@gmail.com', 'consultoria Cosmetica capilar', 1, 335, '2025-03-10 18:40:00.000000', 'Produto', 'Caroline Ferraz', 'Ariane da Silva Santos Sampaio'),
(113, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Pagamento em Dinheiro', 1, -250, '2025-03-14 20:22:00.000000', 'Pagamento', 'Caroline Ferraz', 'Rosana da silva santos'),
(114, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'danyelporto@gmail.com', 'Pagamento em Dinheiro', 1, -427, '2025-03-17 12:53:00.000000', 'Pagamento', 'Caroline Ferraz', 'Daniel Porto de Araujo '),
(115, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Pagamento em Cartão', 1, -2580, '2025-03-19 19:07:00.000000', 'Pagamento', 'Caroline Ferraz', 'Elisangela Jesus da Silva '),
(116, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 250, '2025-04-15 08:48:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(117, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Sinal Consulta', 1, 50, '2025-04-30 20:14:00.000000', 'Produto', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(118, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Pagamento em Dinheiro', 1, -500, '2025-04-30 20:15:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érico Silva do Nascimento'),
(119, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'consulta capilar + consultoria', 1, 500, '2025-04-30 20:16:00.000000', 'Produto', 'Caroline Ferraz', 'Érico Silva do Nascimento'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'iuryforte15@gmail.com', 'Sinal Consulta', 1, 50, '2025-04-30 20:20:00.000000', 'Produto', 'Caroline Ferraz', 'Iury Silva Brandão '),
(121, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'iuryforte15@gmail.com', 'Pagamento em Dinheiro', 1, -150, '2025-05-06 17:58:00.000000', 'Pagamento', 'Caroline Ferraz', 'Iury Silva Brandão '),
(122, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO ', 1, 1000, '2025-05-12 14:43:00.000000', 'Produto', 'Caroline Ferraz', 'Érico Silva do Nascimento'),
(123, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jecsantos20@gmail.com', 'Pagamento em Transferencia', 1, -50, '2025-05-20 17:32:00.000000', 'Pagamento', 'Caroline Ferraz', 'JESSICA CERQUEIRA DOS SANTOS'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'consulta capilar [ Estornado - 30/05/2025 ]', 0, 0, '2025-05-30 11:48:00.000000', 'Produto', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(125, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'consulta capilar', 1, 250, '2025-05-30 11:48:00.000000', 'Produto', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(126, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Pagamento em Transferencia', 1, -250, '2025-05-30 11:49:00.000000', 'Pagamento', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(127, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Pagamento em Transferencia', 1, -50, '2025-05-30 11:49:00.000000', 'Pagamento', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(129, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Intradermoterapia sessão avulsa', 1, 250, '2025-06-01 18:06:00.000000', 'Produto', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(130, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -125, '2025-06-01 18:06:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(131, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Transferencia', 1, -125, '2025-06-01 18:07:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(132, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Pagamento em Cartão', 1, -250, '2025-06-01 18:07:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érika Dourado Cardeal'),
(133, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Pagamento em Transferencia', 1, -1000, '2025-06-01 18:16:00.000000', 'Pagamento', 'Caroline Ferraz', 'Érico Silva do Nascimento'),
(134, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 1782, '2025-06-09 11:14:00.000000', 'Produto', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(135, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'PLANO DE TRATAMENTO EM CONSULTÓRIO', 1, 600, '2025-06-09 11:14:00.000000', 'Produto', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(136, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Pagamento em Cartão', 1, -1782, '2025-06-09 11:15:00.000000', 'Pagamento', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(137, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Pagamento em Dinheiro', 1, -600, '2025-06-09 11:15:00.000000', 'Pagamento', 'Caroline Ferraz', 'Livia Oliveira Carvalho '),
(150, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Pagamento em Dinheiro', 1, -264, '2025-06-18 12:55:00.000000', 'Pagamento', 'Caroline Ferraz', 'Sandra Maria de Assis Costa'),
(151, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '[Estornado] Microagulhamento Lauro', 0, 0, '2025-06-20 11:14:00.000000', 'Produto', 'Caroline Ferraz', 'Sandra Maria de Assis Costa'),
(152, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'karla.amorim@gmail.com', 'Consulta Capilar Lauro', 1, 270, '2025-06-20 11:14:00.000000', 'Produto', 'Caroline Ferraz', 'Karla Malta Amorim Soares '),
(153, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'karla.amorim@gmail.com', 'Pagamento em Dinheiro', 1, -50, '2025-06-20 11:15:00.000000', 'Pagamento', 'Caroline Ferraz', 'Karla Malta Amorim Soares '),
(154, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'karla.amorim@gmail.com', 'Pagamento em Cartão', 1, -220, '2025-06-20 11:15:00.000000', 'Pagamento', 'Caroline Ferraz', 'Karla Malta Amorim Soares '),
(155, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquelsinal3@gmail.com', 'Consulta Capilar Lauro', 1, 270, '2025-06-20 11:16:00.000000', 'Produto', 'Caroline Ferraz', 'Raquel Freire'),
(156, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquelsinal3@gmail.com', 'Pagamento em Transferencia', 1, -270, '2025-06-20 11:16:00.000000', 'Pagamento', 'Caroline Ferraz', 'Raquel Freire');

-- --------------------------------------------------------

--
-- Table structure for table `lancamentos_recorrentes`
--

CREATE TABLE `lancamentos_recorrentes` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
  `data_lancamento` date NOT NULL,
  `repeticoes` int(11) NOT NULL,
  `periodo` varchar(15) NOT NULL,
  `conta_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `feitopor` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `data_recebida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mensagens`
--

INSERT INTO `mensagens` (`id`, `token_emp`, `numero`, `nome`, `mensagem`, `data_recebida`) VALUES
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71992604877', 'Denis Ferraz', 'Ola Mundo!', '2025-06-15 02:53:41'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71992110787', 'Karla Malta', 'Obrigada!', '2025-06-18 15:46:54'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71996244528', 'Pharmapele Vilas', '* 😉 É um prazer poder lhe atender através do nosso canal WhatsApp*. Aguardamos um próximo contato.', '2025-06-17 18:54:30'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71996025222', 'Magispharma Salvador', '*Caroline*, Queremos seguir *evoluindo* e oferecendo um *atendimento cada vez melhor pra você*.\r\nPor isso, posso te pedir *um minutinho* pra responder uma pesquisa rápida sobre o atendimento que recebeu por aqui?\r\n\r\nSua opinião é muito importante pra gente! 😊 https://s22.chatguru.app/nps/6852ea85d9bff1f086c2c03d', '2025-06-18 16:34:21'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71983200714', 'Raquel Santos', 'Obrigada', '2025-06-16 17:27:03'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71982019397', 'Weslen', 'Obrigado', '2025-06-20 17:13:32'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71996500462', 'Magispharma Salvador', '*Jéssica:* \nOlá Caroline, vamos dar tratativa a seu pedido?', '2025-06-17 12:49:13'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71992796862', 'Bárbara Nicole', 'Obrigada!', '2025-06-17 14:31:24'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71996594751', 'lari', 'Estou aguardando Uber', '2025-06-18 20:10:19'),
(72, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '90916953106', 'Sandra', 'Cheguei', '2025-06-18 11:44:04'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71992271212', 'Raquel Freire', 'Qual a sala?', '2025-06-18 15:59:39'),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71992066476', 'Everton Pinheiro🔴⚫', 'Certo.🫱🏾‍🫲🏾', '2025-06-17 14:16:04'),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71988944423', 'Rosana', 'Bom diaaaa minha amada!', '2025-06-17 14:15:34'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71999811829', 'Elisângela', 'Certo', '2025-06-17 15:27:32'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71988446597', 'Érico', 'Sei qual é', '2025-06-18 18:20:45'),
(102, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71988433613', 'Fabiana', 'Obrigada', '2025-06-17 15:54:47'),
(181, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71999471575', 'magis club', '*CAROLINE*, Sua opinião é muito importante para nós. \r\n\r\nResponda nossa pesquisa de satisfação clicando no link abaixo ou envie uma nota de 1 a 10 por aqui: 🗣️ \r\n\r\nParticipar: https://magispharma.fidelimax.com.br/Consumidor?pIdPesquisa=PwwBAA2 \r\n\r\nPara tirar dúvidas, consultar seu saldo ou realizar resgates, fale com nossa equipe pelo WhatsApp principal: 👉 https://wa.link/nysu3g\r\n', '2025-06-18 21:48:21'),
(206, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71997396450', 'Ariane 💞', 'Eu trouxe todos', '2025-06-21 13:20:36'),
(229, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '71981668883', 'OLHA O DRICO AI👨🏾‍🦱', 'Queria saber o motivo', '2025-06-21 14:25:25');

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

--
-- Dumping data for table `modelos_anamnese`
--

INSERT INTO `modelos_anamnese` (`id`, `token_emp`, `titulo`, `criado_em`) VALUES
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'FICHA DE ANAMNESE SOBRE À SAÚDE CAPILAR', '2025-06-12 19:45:34'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'FICHA DE ANAMNESE-HISTÓRICO DE SAÚDE', '2025-06-12 20:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `modelos_prontuario`
--

CREATE TABLE `modelos_prontuario` (
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
  `token_emp` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `dados_painel_users` text NOT NULL,
  `tipo` varchar(15) DEFAULT NULL,
  `senha` varchar(50) NOT NULL,
  `token` varchar(35) NOT NULL,
  `codigo` int(8) NOT NULL,
  `tentativas` int(2) NOT NULL,
  `origem` varchar(50) DEFAULT NULL,
  `tema_painel` varchar(10) NOT NULL DEFAULT 'colorido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `painel_users`
--

INSERT INTO `painel_users` (`id`, `token_emp`, `email`, `dados_painel_users`, `tipo`, `senha`, `token`, `codigo`, `tentativas`, `origem`, `tema_painel`) VALUES
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'denis_ferraz359@hotmail.com', 'RHNDZ2wzS3RELytWT25CNndPQnhhaEFMZGxWQUczVzd5UCtndktOOXNzcE1MUUpwSjloSUw2di9abjEzbmhiV3ltd0wxVU53Zy84NGdkRWNvQVpWMXJQQnl2bHFGTnB3R2hqMWdES2dIaTlhWjE2cEEyV2VIZnRQVmFRb3NyLzF4dkxZZGQ2OEgzMEsrRDJaQmZMRkdRK2M4S1pwbjFRcFVqN0l3UkxQQUZHcVFPZ1dTRVRJdmtZRHF2THN4TklX', 'Owner', 'd753f0b2743ac9a5a0e356a4cc08d072', '24774953ab53456d38dfdd421a995b51', 0, 0, NULL, 'colorido'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'carolineferraz.tricologia@gmail.com', 'YkxTZndyWENiVWlNTG00RkNTblV3MWpPcS9JODVYYk5FV095SkprUU1oNDJEeEZYMGROSWVHTSt4Nk1mM3kxSTJoNGZmYnZ6VzVDamd4MHlqVmZGNVE9PQ==', 'Admin', 'cc34136b61ee3dc7f4dd6c37d4a376bd', 'd6b0ab7f1c8ab8f514db9a6d85de160a', 0, 0, NULL, 'colorido'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'bEgycDJXWkMrOUR6Y043S3pyeXFZWS94L3E3MTdweVJ4NnM5ck1TSDVZNHlrUkNMTGxveXJPLzhjRzBlSnlBZncwbU9UQmhMWU1Dc24zSlhZN1FoeXE5UHlRSTVoUFgzK1hlN0JUTkZLcEU9', 'Paciente', '18c83837a9253d17e3f48dfc42f234b1', '18c83837a9253d17e3f48dfc42f234b1', 0, 0, NULL, 'colorido'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'bU5aYkxVRHB6NGZyWHVHTXc0RTJvblVuQXJwbkZWaHlmM1VMTzlySHBWSHptdkQwT3NwcTkyU2hua3U2RWJmVVBXeitPTENvUkFUNnRjTEVzc3VqYXc9PQ==', 'Paciente', '19efafdc7ebded6e7cd80d80d5938096', '61d9dd04f34a43d526902411cb36abeb', 0, 0, NULL, 'colorido'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'N2FvZEtFRFBUMDJPTFlHREYrS1BnYmxRMkYwclNIdlhFV1p3V0ZjK2c1OG1Nc2xHTnpVNEtMbzRsRndQMGpiaFdBbzJ5MHRLM0s3RWZaK2laNWxNVlkxdVIwVklkR2toSEFtWDB1Rzh1YmJrejhKRFp0UmxHcXBmMmJtQ0dlVFQ=', 'Paciente', 'c1071cae646cb8d4646468f595cf7d8c', '28aafc457b20de21a14e145645eeee33', 0, 0, NULL, 'colorido'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'WnBuRm1INEV4NzBWMktNdGkxdmhFQkNCRHhVVXVsamN4cFZKaDJ3bWg0K25FRm15eVMyMjRXLzc1UkhLOEdIRmJxZy9wQXduNHN2VkZDK0ZlczNwaUxiOXpSeGFUT1U4NXlPMmVYLytZNkE9', 'Paciente', 'b9c067e9b4df381c65f26d16a065fa7c', '015de2e58d6ba5f5fcacbe6f618cac66', 0, 0, NULL, 'colorido'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'fernandaandradeteixeira@gmail.com', 'TmV2M0tQRERjczZhK3dwcGZkenMrWFlSRGRBeSs3NDVmaFlYN2ZKajgxa3pmRU5iYTA4TVQ2Z0I1WWRuZStOWlNrd1kwSU9idkY1cURRamJmRHRvMFdGYm03a2JvVDF0dTZrZ0xuTEFENVhPNHBLYlF4T0lDbk5tK014YWN3Z0s=', 'Paciente', 'b7ae3b6a661da12b763ca8b4b069421c', '1d0decbc35d94ce603d3ab6baedafc90', 0, 0, NULL, 'colorido'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'RkExNHhMaGhPV3FFeFBJcU96VjFZTzFoR09iT2FXTitHK20wczd3T2NENjJxSVZzNlFJMW1VbERCdkkvYlY1azlrOEdGRDZaMWNpQ2JaUG4rT1BYaE5uL05JNFNhdHB1TWpHYk9Vam1GUm89', 'Paciente', '5359eeac047fb26508c48550c99de8db', 'e4e2110ce60b6f4e8d8ed0630d04ca2b', 0, 0, NULL, 'colorido'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'andreacrb03@gmail.com', 'OTZpTUttdlBDb1J5NEd4dXovT3pENSs3TElJZWdVUlcwMklSanZUWFE1SWp5a3hYWnVVQlhtc2FZWU8xT3dkNGRHZ0paYmFhZkNyYjBvZkFyS3k0Mk0xeXdacTVQaFptNEowKzcxWVFueXFnZUFjSDIyWU1tQU50RlR4Qno1MC8=', 'Paciente', '4b87f7eb1d1cec915f7a4fba0a6c235b', 'c078c0b95c22ea5265c68f4a4e6b0d1d', 0, 0, NULL, 'colorido'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'M2tSbFBkRFZzVENSUVFTb3VxTVlCSzdMSDhRT2JkN2FJWUpqakVPK1RuWVcxOFpuMCtxa041NUpqdWtNNEtwVlNpaDhjVnhiZGxVQmZacEFEWSswTUIyeDZ2M3pDTzVSb2xpL1dqdjc5anVTdzk2bGNDYTV5aDZCQ3NiWU9ITzY=', 'Paciente', '081daa8f8185ba472185b47fada5fc37', '66b2e479b0eb81a70c9d3d667e8a4393', 0, 0, NULL, 'colorido'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'fernandinhacruz2017@gmail.com', 'bkZVckZjeWZFU2g5TzRVM3N6eTU4czFjajlZayt2Znc1a0E1bEJ5eUozNDUycHdjZjY2QnRjZ1dOSUkvR0J5YXpPUUZQL2wrc1ZGSkJJNDN2bld3TmR4YmpCMEUyWGlyZXFQMzgwWFQxMEpwaWVTZkR5TUJiQlJwVk5zM3MyVUo=', 'Paciente', '034a2a13133c9c665c53659bcd2ec669', '85a27afc5d83a555807752dfa6ef77ff', 0, 0, NULL, 'colorido'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'amandalgarcez@hotmail.com', 'TXNKb0VleExUVXVuNVhWSHR0TnN6SXZmcStCK2p3U2RFbEp2NUJGZjVlWVdKYWdRcFp6K2F5dmdtK2dvY1Rnd1hIUkdpbHVYRERmVXdMTU1wRDJCbkYxdGU3UjNsSnR1dVZDMkNIb0tFRlE9', 'Paciente', '45190921397588326b247ed11036aad4', '573310ef5270b63eaab96cb062e1a768', 48483685, 0, NULL, 'colorido'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Z2FZTWViSkpZYWpjb0tseUpPeHdnR0h4emJEYUpCK2oyM3lKakoza1hPcm1PV3owcndxUUVyaTdqb2lLWlV3TVhBYVBYc3JaNlVFYXdWWk1wT01sRXQ3ZURjdWRyVnNYMjloa3dYWVRHTjI2RVNVMTV6ZjdtZzhtQmRTS1FVeEw=', 'Paciente', '47cdf87ed315e5d7c709ac600618f3d5', '0ef270d79f4f49b740086fd688543fb5', 0, 0, NULL, 'colorido'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'danyelporto@gmail.com', 'NVFVS1NBN0dYY2lwemY5aml5dzZLL010bVlyM0QzU1NEMGVhYVVnWE0rYzA1MnFuK04zTkxmWkswNDhrOGxSK1g1NmJUY3NCS0c0Z05iOFNnZ2Y1a2Q5OFNFcEtHak91RS9ja2Q0WWlBWGs9', 'Paciente', 'd767bb158cd06e2b3898e563c3be217b', '4cd1d067c7cfc7b5077857cc2ba4dd1c', 0, 0, NULL, 'colorido'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'ZXpqaldkQU5ydFAxN1FZRFgrQnFSaE1sakVvVzhmNkZoRjNOZkNBNE5zaVBSYlNpanl6Q0FvaUVESnVXSkFLV05veGd1eUY0OGp6TngyWG13T3dmK21mcjZ1a3c3bG51dTllaHh3QXVVbzlEWjQ2aDNJaU1NN0JwNnhzcGdDcG0=', 'Paciente', 'de9606df89f927a2058d207ef08ddad1', 'aba9294ea5e7d225a1961eeb79d411fa', 0, 0, NULL, 'colorido'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'L1BFUVQ1K0NQN29rNW96TkFhN2NLbTVjRHlBa29wbEVUR2JqWUFyU0xoM2R1VXZoSWVxbUZoRDk0dDhnbjZ4Kzd4Z2F4ZXg2UUxZV1gvK3RXMkJaVHdVY0xDQ0E4dUljcCtwLzlWSG9Ud0YyK1R4RldCSHQvYlYxWEhlT0NkNkU=', 'Paciente', 'cc92852aa3a25e11d04148044047aa32', 'c795967694d8ec142be04f9c579b53db', 0, 0, NULL, 'colorido'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'NlExVUhXM2E0UGlHdWROYWtQZlJiMVhTbHNYeVJvZFM2RU4xajJNSHhuZjh0ZVc5ZTkrMnVyU2daVTVwb0hIMmtHbXNGTk14MHhYZ1ZwNXlkbnZycE5JNTZQbDZPN3FuVGlRYXFmNjhRejg9', 'Paciente', '1587965fb4d4b5afe8428a4a024feb0d', 'c8be214375ee44c428774a41041a2183', 0, 0, NULL, 'colorido'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'ZXhoV2RwVzZYQS9QM2hMaFM2bndEWTQzOGE0cjJHYVEwQXJMdGY1WStXWHRDZlg3d1hITFp5KzhuMzhzRFI1amRVWmwzRlFlZHhFUE9WZTRDalo2TnAwb2VaY0pFRXNqSkV0SThPU0gvV289', 'Paciente', 'b7087e09eaa11e7964b0a66f72fe702d', '8a4b742494d4bc872c10a7c3beea9a9a', 0, 0, NULL, 'colorido'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'UDRYK3UrUU9MLzZ1a0k1Yjl1TFZCVFMzOWJwckM5S2ljamcwNkcyWGc2alJKWWJTUEE2VWRKVzBJUTNxdXJ1UWJUK1NxYzVJMnY1SjFER0xKdFMreXl2OFVaMjl6U0tOOXA0TVlxUUFvbEN2djdMc2tneG1ZRlFtWG5xbWFScHM=', 'Paciente', 'd779366a15181b5b211485faa4bd2bb3', '8ab02ec3dae28a6166f2a8fa4c0da37a', 0, 0, NULL, 'colorido'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'kaiqueeecr7@gmail.com', 'bTRJOCtVcE52WFVZb0Z2WHlHdGxqRVQzekZ6V3dwb0daWnBwbXRlaWlzK1RQbXE1aXI2ZlpQamVrZExkR0xjcUpOZXBpN0F2Z251bWUwRjJNZHE0Ty9PWHNHQVhDaUZhSStZUVoxMHRlaEFPM0JhNjVqdjVWQnl1ZFp6aUZld1k=', 'Paciente', '05591e785743d3ba75dcce9e9d83b4d0', '921df0e7d92be2ac9be2c561c7ff46a3', 36752908, 0, NULL, 'colorido'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'czUzbjJZYlR4YTkyTU9kR2tqMWI1K0Y3RmdSbkJ3c2dyaHZyNXBWN0FLU3lNeEwvK0h1UmNHenFwN0ZzNGJiT2pxV1hrK25mZ3c3K2JOcFM0QytnZ1FIWnZna2RYSG80ZVBhdzIrdWw0cnUrbHVoWU5xWlZwTGJZV0NJenF4Zmw=', 'Paciente', '5fb9b23e79bd946cf2a5da1cdca4f5f4', 'd753f0b2743ac9a5a0e356a4cc08d072', 0, 0, NULL, 'colorido'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'eGhzQWdCZ0d6VmRXRzl4VFBrZ2l3dS9MZXVzckIxaU5vUjRScW5zRTd4V2doajkxc25pZEhkUEUzczNNZEJkUUxBRTY0R25XUk1VRmJGSGlLRWxSS24wQUljNTk5QUVlMkhXdU51d1Y1c3M9', 'Paciente', '15610a4e8cd4351c86df941c84d16de0', '75343cd5002d0cfd5df48b173ef35e8c', 0, 2, NULL, 'colorido'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camila-pita@hotmail.com', 'ZXJFRFVUV3dQc0Fkd1hiQWRvVklqZ1E1cHBmRDlwK1VrN1VRSWJxSFROd0hjRWhRYVdsamZ5ZTkrTUFVVWdUMFJmUmFLSTE2Wm1jT1U0TG9SQ2N5ZGFjaFllOXFOMGdhRXBmSVQ1VkdQU2c9', 'Paciente', 'e0fd1a363a8f69d37f8e261eaa0e1867', '04d5f15a4ec8e8dd24ccf70d17047c0b', 0, 0, 'Google', 'colorido'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'N2orSEl0L2FpN2l1MnlrekFLRGRNcjRrUmp3VHo0SDRFTmw4dkdGaDB1SHdPQ3V6ekJMSVpROUcwYkdDK0VsV2RqR3ZKa3hYeVE1Z2UvRC95NmF0R3NIZFNrQ3lqWXdvczNGQ29rSFZtRzlTbUZUcFJid25ieWtFcjgzNTRMK1ZldG1jUittazh2NHczRVVOTXBocXhEdHlUR3N3MnYveU95MkNvWk1HNDBWVlFhNjBRRXhKUFZyNEVvbTQyQzhv', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '9269af88dfe72d05cf048df977760d51', 0, 0, 'Indicação', 'colorido'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'N1IvVTVwdXFQbCs5bkRyUEsrMG11SFNHT2doTTlwdmg1SlgvQ2VGVWlhNGd3RjdPRzJUV1VnTXdjc3dYVWZHQVdyM0ZUNnV2OGVhQkRYYlJHZzhXNnNzbHRCS1N1UzdybDZVR09BOHM1Q0U9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '49ac44749f1a55a8fdf27245e9a64a53', 0, 0, 'Indicação', 'colorido'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'WFVXK05kMXJYV1dXSjdhdGMxQXhCUXpySGprR0Mybit2WEduODN4OWN6V29zbXVienZodG8rNWczdkV3TW1ic21KN2Q0eHJoa3plVEV6UlRYV0kwck9TVXAzRmFRbTZtSm1OUXVrZWdlQ2NjRGVZbWo2Ulp5dXJjNVVtRmJoWmI=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '76e5d2a967ab1c3bc353ebeeeb1c9506', 0, 0, 'Instagram', 'colorido'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'neilsonrabelo@hotmail.com', 'UUFpNUV4NGxqUWJQd2ltUGo4UjBvZFd4RUExSGhnYmtiL0xvdG9ZSExQWjA3YjlneDJTY3liRjMwYUtEaVJ5RXpRTVdsdFR5WVk1SkZMN04xbFUwQ0E0VDNEYjBYM3lXd21MSGFYUXhRT1U9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '5ea60829e8251e97c3f90e46e181619b', 0, 0, 'Google', 'colorido'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'aU55aUd6UnorRitsYjIweGxQQWdkL0ZLNFlwUldCTWtQeEpVay92bjJpTy9OQ1h0L0VPdHRtNW14K0FkTnpNcUpYOGVkSUVJNVVDNytINmdYaDZZS2NmaVBlRFBMa2pDWFByb1BpY1B6N0U9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '6d8aa8bfc64acfaed633a82f5c719192', 0, 0, 'Indicação', 'colorido'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', 'WU45OW1kRkRJZ1lLbjQ4RWRQODl3M2VOWWwvQy9yTkdGbnN2aEJVT3VPSnJJQS90RitTMFozQW1pSHcySlVCN0N1d2xwTFpaUklReUNRdGRxOThQNkNoRHJwbFd4ZS9XSUsvK1Q4Tm9QaU09', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'af674abbf6759fa4b33511e826f77077', 0, 0, 'Google', 'colorido'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'vej2305@gmail.com', 'Qi9ESVI5Z2llNHdsZms3THZLZjNhV1daa3JLRVh6UmpiYTJDbzNISDA1L1cweHdIRjhsejJhV0RpK3h1VVVSYzZXaXFmQ0FCcTFhOWp4dm9GaFcvaUF4a21JRmR5cVZPK1BDMU9CQWJsZk5CTHZUaVhJLzVkeDJJYTcvSzFJNlU=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '181b26d8d17465d43c1e7e2827aba33c', 0, 0, 'Google', 'colorido'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'maria.jdr@gmail.com', 'dkg4SzQ0UkpaSWhacEk2Q0tubWdxSSt3ZWlPanpMNkU1d3VlTmt5TEg3ZGRkbGQ0SVE2NHlDV09VVHBHWjd2Ri9SWlJuUmhKcW9TZTNrVmI2OSs1Vmdva2ZITzArTFZaaXBza2xnVmd3akE9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'cac05ec42bd8026c6af66c608603b036', 0, 0, 'Indicação', 'colorido'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'elisamaandrade.m@gmail.com', 'UGNRWGtSQ1RMZ014TFpDL1JlWVltQ0I2N3Y0cURFWHdoTGdSaUppaGI4Um1wdnN4c3RRWUhzRzVDb0dOTG9EOXliOEdUM1J1OGZXRVhpaUhSancvbHozVDAyZ0tkRTdCb1Y1T05xYXpxeWI5TE1RN3pMWmR4dndnRmJ6bFhleHY=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '133e981a3eed8ade1cb631de26a9e746', 0, 0, 'Indicação', 'colorido'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'YjNMeXBBZXN4R3lQTld1a29RcXRHOVI2ZjBhZXExS3RqT1pERHhjZ1ZiQm1YbkFLdXV6akFjWHY4UGU0VHhtYzdweXNTUFR5TC8xTTIrZ1I1YS9hRUZCWXNLWHF5bFd2cEZDbGNVY3kzWnNsTFZJNE1adXY4RjVmZnBnWFlMUWE=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'cf0ae99ba8ac0e67844aaeaa740fb650', 0, 0, 'Instagram', 'colorido'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'lambiasefisica@gmail.com', 'VlVodHBMcEtCbWlNZXNUWkhTN2lkdmVhYzdJVjVaNmp3TkFkUkkwQk5Mb3Y1b3JobG8wYng5QmlhV3FqWUVhZG1VcTJ2SGljOG9iSEFCd2VHVzBWN1Z1TVNqc0R5akVFMFhadDZESW8vWjQ9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '759d03f4cbcbc04d472e9b2a2befae1b', 0, 0, 'Indicação', 'colorido'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'paterson.franco@gmail.com', 'aUxhUjRYSmdGR2FtTXdsL1RHZityd1lqWUxpeDRtU1J2SEJhamR6eElUMEVEZFdTczJGUVhjOUo0ZTRUSVN5NlkxaUpvc3ZWUVhKNHU4ZzNZazAxSW9mQTBrRHozQnBka0hRUlp4eGtpTnM9', 'Paciente', '25f9e794323b453885f5181f1b624d0b', '40f8006e6149f542df10120d98263bb3', 0, 0, 'Instagram', 'colorido'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'ampNMkVnVkRvQkVDWlJHdlFqRFBhQnZNQXFEM3ZBSWlXa1ZOU29EOWVJajl3M0hjVFgveW1rSU1EcUlmamFjZEFJR05uaXY5MjhRT3JQMXp4QW1NV0JHRzc1NkhRK09LK3k1UWZmRHQwVWs9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'c4a8a53832bb4e5a080db691413413c2', 0, 0, 'Instagram', 'colorido'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquel.d.santos110194@gmail.com', 'SURJdjh2Yk40VHRIYndkRysxWlhudWJtemRxN3F0cHZRS1pzTER5d0NSaW0yTWFYak5wZlJ3U0xSSG9NWTEzZGl3TXFPbXo5WCtpMEIxcGJmZWQydGl3WldRODZIWnlkcXB3MnJDTHBrY009', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '6ee65ee535cec9a9a0dc3155cbab746a', 0, 0, 'Instagram', 'colorido'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'adrianamagalhaes026@gmail.com', 'QXZmcW1sNEhsRFBSODJQc1I1STY1NnBZMWVGSHlOSy9LVTF6cGRWU1FvR0RjdTg2blhxY01MUnlPVVVIVWdiMHpsenpQKzlsNmg0bzBTUlVPWFhOR0R0VkppakJ6eFdKYVJVL0d4THArSWZjUzIxZ3V5dGtYL25oTlVvelNLbTQ=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '81e438ec60c7c47af15796f214bfe9c9', 0, 0, 'Instagram', 'colorido'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'lucassimas160@hotmail.com', 'cEtnRDNaSkNZdnhiVVFqNTlkcXdBTXBnR3kyb0p2UnJEN05jOEozcEEvK0xKMURZM1ZCTWcxOVdYU3ZPcE11NGVseGJ2S3U5VDhKSUp1NnFiNzhjbVRYRXdHWnVXbHFvOW9peTNIaW1xZmd4K1RNaEJzMnBXR0ZWUWw5RUk4WjRyVmplektQYVBFaUVjSnB6aHVZakxTSDIxRThUS1Z1ejZ1WFpiQmVURStBZlREZGZ5U3gzcXNZbkxYaTByd0Q1N29KdEhEbFNIdldUTGJBbzVSeWVHUT09', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '940a7aa82e132b3ac471335cf8e2b274', 0, 0, 'Instagram', 'colorido'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camilacarine@outlook.com', 'MGZFcGxacUlla1VENWwycDZWRE9mRTdGOEZCam5tSTR2ckgycnVtUENRZzlTVXVjQWFTUkR2ZDdZd1QwQjJqZmZsYzlkTUVwNktaWTlSMlc1QzVEdy9BSGRGU2JYNkdqMTQzTlBTYVVvS2pkQmxtTzl5UzhOTms1Y21SUThkVVM=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'bd31e616f4d23ceea44907ce9eaa0472', 0, 0, 'Indicação', 'colorido'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'arianedasilvasantos2@gmail.com', 'aFZqZStQb3FVZnVEbjVXMVpUbXhSaytlMEorME0wMWZjMWlFcmp3clE4WThVRkFaZWM2ZlNYei9SUWUzMHhMZ3JERkNLNWdpRDkxZ2J3TmtMWlBZR0JQRkRvNnZwWlFDaldrWXBJTkk5bHh3RFQ2Yzlwc2tNQkF1VFJveTBoQ1g=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '1ad42852dbca823bae51de0a9c1db74a', 0, 0, 'Instagram', 'colorido'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'leideadrianonogieirasouza@gmail.com', 'R2tIT05UcW11TWdsYjVuSWlEOG1kR1lyVWZkcFVjU3EvWmI4RG1ZYllWRFpwK0pMRy9KZE9mODNKRzFuM0xMN0hiemdOTEdYN2FWT2xaZWlkUlYwY3JDaE8rMFRLakpuT0lvKzk1aWZrakU9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '993c4db0be3cc7944c33a30d308ff5a1', 0, 0, 'Instagram', 'colorido'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'T0N5dGYvN3FMYkVuTFMzZTMwT3ppQVZLV3ZGZ2dVM0hSaTBiRE9ZK0hITVFaQ2FGbTRjQlg4eUpsRHd4NDA5MXozMHQ5a0tucm1VdmswOGg1WStpQlMwaDdDdHpKdkg5L21KTjY4blkvSEVxaVBFdE5GNnoyNHpiaE1WTDNhT1hDYnd0VHIzWUhKOEs5YytZd3lwYUV4dG9jbm1mb3J2WXFkK0RZS3NoTmowWjZ6dVplSExuK3lrdE5GL3pTVmVKSmpyV1crWVhNRXBPQ25SaHNIcEY1bGtSSmhkYkNrcEZBdERWNE5hTWhRaz0=', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '44eb38c24c42294397f7929839b5adf4', 0, 0, 'Instagram', 'colorido'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'WlVxbHRjc0NtSFY3ektHSi85djYrbkdDSUQwYTlzdk12SXZ2Sll5MjB5bDJuSDBaVGt1TWxuZUdFTFVWMndZdXFOWWhCYlpYNUk3VS9CM2oyYndLYUZMUFUveEVBL3prcW1IaEVCenJOazhqbDRORFRoLzZReExIUFJITUk0MkwzZmNNa0VnZjlkRGkzOWRtM3h5d3hYMUFuMWVMaGpsN1RiMWxNRUZoZEhLNG9zcVNSQzlxMEliTXIvWHUwaUgwc2lvaEc0L2ZBN0VjS3ExRFlscFB2Zz09', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '2ae5632521831fccc32c9e2d7bea5f43', 0, 0, 'Instagram', 'colorido'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'iuryforte15@gmail.com', 'Kys0U2ZmMnQvU2UzeHR5N1VscUZLM0xlcTJ5OWVvT1RPUy85aC9MTDN1S29LamNFNmVSR3U0TS9Cem5VK29YNDRuSHg3cXN3OHk2cU53aFV2RlNydWc9PQ==', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'ab350c24a342ef1325bda7ebe64f7f71', 0, 0, 'Google', 'colorido'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jecsantos20@gmail.com', 'TnZzU1lZbVYwTFZGNzdNTENPb1UxaTNwb3NsTjdBU2RpdnFsTGRuaUxzWjNjemhPcWZGZGNrMFltSGhUdUI4ZGh0bUNCQ0dnWVVOclUvU2tuNmMvZWc9PQ==', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'bd626f437e266e2e633760dc17a9d8e4', 0, 0, 'Google', 'colorido'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'weslenvini99@hotmail.com', 'NGxSNkRBbGNKTk9pWlEvZVJSRVVGZEFPT0daWUgzK1dKYWdRTWdPV1dmMjI5emhhTkl3NnJwbUs1RjA1cTYzYlczUWxIMVQrL1FyamcxTmRhdmxRdlQ5ekdUR1R2UUFlbGZJSW11c0xaUzhsZFVjVW5IdC9rM1c3WERnYmN5Zjgyam9NWHRjancvTHo5UEM1aVFZRFdpT2NUUnBRL2hiZXY1OVdjaTU3V3ZTUGxQRDNGc3pCaHlZUWptaVdheXQw', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'e7847d60fd0ee81ac60c7c1e5bad83a8', 0, 0, 'Google', 'colorido'),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'pacienteteste@pacienteteste.com', 'aHZPZng3RHJwY08rT0JSczVOTk14TTArQXNJS2ZCTUo0SGIxb3M4elFsS3Uwb1U2ajNQbWU3QTZqTVBLYmpaNGJqdzBaTW5YSEp4ZThWVllhWjdJRVJ1MHZmNmJtZDc4KzlQcVhhU1J4SWk2MXpIYXRGOHVvMDFKcXJTK25HQkJxQUlhRXkxcHdhY1l5emxVWUwrMFpXN0FQdllTU2FLcUUxT014WENxaGI1L2hSTk5scGZaRWlCZXdBSGZYU2dQ', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'e7847d60fd0ee81ac60c7c1e5bad83a7', 0, 0, 'Instagram', 'colorido'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'raquelsinal3@gmail.com', 'QTVpVStWV3VZQ1gwV0paQmRtWjFZMlU4dHJ1VU5Ecit4SHVaNTBYeC9RUkI1ellLQVlQMmVMYXZuTVVoTEhaaG9zOWtLemhHVUxacEZFMTd4V2ZydlE9PQ==', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'df6bb71cff3520711ac28d362f3b2078', 0, 0, 'Indicação', 'colorido'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'fauqueiroz@hotmail.com', 'UFBoMWNtVEpuak53SUlXbVpPUUNaVVNpMkQ2TFlnSUlzN1lQNzZkVnVGTUlUYTNvYkZ0WWdzQ053UCttclRGUEFNaXdSM0ROVEp3dlhBWHh5bkNPTERqeUlDL2Z2VVhJbk1MV3g1L2ZNb289', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', 'cb20fd2d12c61d56b1afa74213bb7baf', 0, 0, 'Instagram', 'colorido'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'lene-chagas@hotmail.com', 'anJnTTltWFRkYUhONFZhWEhNRTRHOGU1emxBWlZDL2dBdWdudHluNkhsVit2YWt3dVphT29yd0p4dVp3WE5FenpkQktMTFVVY2llZkt3RjhZd2N4T3c9PQ==', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '305933169d6aecd561cd50c83dc010e3', 0, 0, 'Indicação', 'colorido'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'karla.amorim@gmail.com', 'Z1BQVi82S2xsTTQ4Yit2dGg3YXhPWmdYazBhT1BhZmVUaFY0SzU4MHdEbkFUUEJIUXQvV1pZVU1tdEhsbUx3S05rUytvdXg0WmxtaklSKzRuMndUMUhRc3IwSHJCTzRMMk9PMnUyZ21ML0U9', 'Paciente', 'e10adc3949ba59abbe56e057f20f883e', '57e391e33e3b427c04b4dd53fb394d61', 0, 1, 'Indicação', 'colorido');

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

--
-- Dumping data for table `perguntas_modelo`
--

INSERT INTO `perguntas_modelo` (`id`, `token_emp`, `modelo_id`, `ordem`, `tipo`, `pergunta`, `opcoes`) VALUES
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 0, 'text', 'QUAL FOI O MOTIVO QUE TE FEZ ENTRAR EM CONTATO', ''),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 1, 'radio', 'Percebe Queda Acentuada ', 'SIM;NÃO'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 2, 'text', 'Há Quanto Tempo Percebe', ''),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 3, 'text', 'Houve Algum Evento Gatilho', ''),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 4, 'radio', 'Tiveram Períodos Que A Queda Piorou ', 'Não;Sim;Não Notou'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 5, 'radio', 'Houve Perda De Volume ', 'Não;Sim'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 6, 'radio', 'Há Perda De Pelos Em Outras Partes Do Corpo ', 'Não;Sim;Não Notou'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 7, 'radio', 'Percebe Afinamento Dos Cabelos ', 'Sim;Não'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 8, 'text', 'Há Quanto Tempo Percebe', ''),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 9, 'select', 'Como Considera Seu Couro Cabeludo? ', 'Seco;Normal;Oleoso'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 10, 'radio', 'Sente Coceira No Couro Cabeludo ', 'Sim;Não  '),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 11, 'radio', 'Sente Dor No Couro Cabeludo ', 'Não;Sim'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 12, 'radio', 'Descamação, Presença De Caspa', 'Sim;Não '),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 13, 'text', 'Possui Histórico De Cálvicie Na Familia?Se sim, quem?', 'Sim;Não'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 14, 'select', 'Alteração Na Haste Capilar?', '(Tricoptilose (Pontas Duplas);Tricorrexe Nodosa;Triconodose (Nós);Porosidade;Fios frágeis e Quebradiços'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 15, 'radio', 'Já Foi Previamente Diagnosticado Por Outro Profissional', 'Sim;Não'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 16, 'radio', 'Já Realizou Algum Tratamento/Usou Algum Medicamento Antes ', 'Sim;Não'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 17, 'text', 'Se sim, quais? e obteve resultado?', ''),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 18, 'text', 'Com Que Frequência Lava O Cabelo?', ''),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 19, 'text', 'Costuma Usar Secador/Chapinha?Se sim, com que frequência', 'Sim;Não'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 20, 'text', 'Faz Alisamentos? Se sim, com que frequência?', ''),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 21, 'text', 'Quais Produtos Usa No Cabelo?', ''),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 22, 'radio', 'Usa Rabo De Cavalo/Trança/Ou Qualquer Outro Penteado Que Cause Tração?', 'Sim;Não'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 23, 'text', '', ''),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 0, 'text', 'Possui Algum Problema De Saúde Atual?', ''),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 1, 'text', 'Teve Covid? Se sim, quando?', ''),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 2, 'radio', 'Teve Dengue Ou Chickugunha Recentemente?  ', 'Sim;Não'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 3, 'text', 'Possui Alguma Alergia? Se sim, quais?', ''),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 4, 'text', 'Já Realizou Alguma Cirurgia? Se sim quais e quando?', ''),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 5, 'radio', 'Possui prótese metálica pelo corpo?', 'Sim;Não'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 6, 'text', 'Possui Algum Problema Renal/Hepático/Gastrointestinal? Se sim, quais?', ''),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 7, 'radio', 'Intolerante à Lactose?', 'Sim;Não'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 8, 'radio', 'Intolerante á glúten?', 'Sim;Não'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 9, 'select', 'Como está o funcionamento intestinal?', 'Normal, todos os dias;Pelo menos 3-4x na semana ; 2-3x na semana; 1x na semana ou menos;Várias vezes ao dia'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 10, 'select', 'Qual a aparência das fezes?', 'Ressecadas, endurecidas; Normal;Amolecidas, Aquosas'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 11, 'radio', 'Possui Algum Problema De Circulação/Histórico De Trombose/Embolia?', 'Sim;Não'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 12, 'radio', 'Sente Falhas Na Memória? Esquecimento?', 'Sim;Não'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 13, 'radio', 'Sente Muito Cansaço?  ', 'Sim;não'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 14, 'radio', 'Gestante?', 'Sim;Não'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 15, 'checkbox', 'Como é a menstruação?', 'Ciclo Regular;Ciclo Irregular; Fluxo intenso;Fluxo leve;Fluxo moderado;Não menstruo'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 16, 'radio', 'Síndrome Do Ovário Policístico?', 'Sim;Não'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 17, 'text', 'Sabe seu peso atual?', ''),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 18, 'radio', 'Como Considera Sua Alimentação?', 'Saudável;Meio termo;Gordurosa'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 19, 'text', 'Descreva sua alimentação (café da manhã; Almoço e jantar)', ''),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 20, 'radio', 'Faz acompanhamento com Nutricionista?', 'Sim;Não'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 21, 'radio', 'Já realizou cirurgia Bariátrica ', 'Sim;Não'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 22, 'text', 'Faz uso de suplemento alimentares? Se sim, quais?', ''),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 23, 'text', 'Realiza atividade física? Se sim, quais e quantas vezes por semana?', ''),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 24, 'number', 'Quanto L ingere de água por dia?', ''),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 25, 'number', 'Quantas horas de sono dorme por noite?', ''),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 26, 'radio', 'Ingere bebida álcoolicas?', 'Sim;Não'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 27, 'radio', 'Fumante?', 'Sim;Não'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 28, 'text', 'Usa algum medicamento contínuo? Se sim, quais e quanto tempo faz uso de cada um?', ''),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 29, 'text', 'Tem dificuldade de ingerir alguma forma Farmacêutica?Se sim,qual?', ''),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 30, 'radio', 'Se julga uma pessoa estressada?', 'Sim;Não'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 31, 'radio', 'Se julga uma pessoa ansiosa?', 'Sim;Não'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 32, 'radio', 'Realiza acompanhamento com psicologo?', 'Sim;Não'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 33, 'text', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `perguntas_modelo_prontuario`
--

CREATE TABLE `perguntas_modelo_prontuario` (
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
-- Table structure for table `plano_contas`
--

CREATE TABLE `plano_contas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo` enum('Receita','Despesa') DEFAULT NULL,
  `grupo_conta` varchar(255) DEFAULT NULL,
  `subgrupo_conta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receituarios`
--

CREATE TABLE `receituarios` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
  `doc_email` varchar(100) DEFAULT NULL,
  `conteudo` text DEFAULT NULL,
  `titulo` varchar(50) NOT NULL,
  `criado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `respostas_anamnese`
--

INSERT INTO `respostas_anamnese` (`id`, `token_emp`, `modelo_id`, `paciente_id`, `pergunta_id`, `resposta`, `criado_em`) VALUES
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 3, 'Descamação e aumento dos cabelos brancos. Coceira em uso do shampoo detox que percebeu que piorou e coçava mais ', '2025-06-20 18:18:13'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 4, 'NÃO', '2025-06-20 18:18:13'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 5, '', '2025-06-20 18:18:13'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 6, '', '2025-06-20 18:18:13'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 7, 'Não', '2025-06-20 18:18:13'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 8, 'Não', '2025-06-20 18:18:13'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 9, 'Não', '2025-06-20 18:18:13'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 10, 'Não', '2025-06-20 18:18:13'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 11, '', '2025-06-20 18:18:13'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 12, 'Seco', '2025-06-20 18:18:13'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 13, 'Sim', '2025-06-20 18:18:13'),
(26, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 14, 'Não', '2025-06-20 18:18:13'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 15, 'Sim', '2025-06-20 18:18:13'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 16, 'sim mãe e avó ', '2025-06-20 18:18:13'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 18, 'Não', '2025-06-20 18:18:13'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 19, 'Não', '2025-06-20 18:18:13'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 20, '', '2025-06-20 18:18:13'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 21, '1x por semana ', '2025-06-20 18:18:13'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 22, 'sim em todas as lavagens', '2025-06-20 18:18:13'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 23, 'não ', '2025-06-20 18:18:13'),
(35, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 24, 'shampoo + condicionador +mascara +  óleo finalizador; faz cronograma aas vezes', '2025-06-20 18:18:13'),
(36, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 25, 'Não', '2025-06-20 18:18:13'),
(37, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 33, 61, 26, '', '2025-06-20 18:18:13'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 27, 'não', '2025-06-20 18:23:13'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 28, 'não', '2025-06-20 18:23:13'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 29, 'Não', '2025-06-20 18:23:13'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 30, 'sim, dipirona, aas antiinflamatório ', '2025-06-20 18:23:13'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 31, 'sim parto há 1 ano', '2025-06-20 18:23:13'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 32, 'Não', '2025-06-20 18:23:13'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 33, 'refluxo', '2025-06-20 18:23:13'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 34, 'Sim', '2025-06-20 18:23:13'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 35, 'Não', '2025-06-20 18:23:13'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 36, 'Pelo menos 3-4x na semana', '2025-06-20 18:23:13'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 37, 'Normal', '2025-06-20 18:23:13'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 38, 'Não', '2025-06-20 18:23:13'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 39, 'Sim', '2025-06-20 18:23:13'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 40, 'Sim', '2025-06-20 18:23:13'),
(52, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 41, 'Não', '2025-06-20 18:23:13'),
(53, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 42, 'Ciclo Regular;Fluxo leve', '2025-06-20 18:23:13'),
(54, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 43, 'Não', '2025-06-20 18:23:13'),
(55, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 44, '74kgs 167cm ', '2025-06-20 18:23:13'),
(56, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 45, 'Meio termo', '2025-06-20 18:23:13'),
(57, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 46, 'leite cafe soluvel , pao com queijo ovo, almoço: carne maioria frango, feijão;pao ou sopa, queijo, coisas de milho na amamentação.', '2025-06-20 18:23:13'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 47, 'Não', '2025-06-20 18:23:13'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 48, 'Não', '2025-06-20 18:23:13'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 49, 'para lactação', '2025-06-20 18:23:13'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 50, 'não', '2025-06-20 18:23:13'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 51, '2', '2025-06-20 18:23:13'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 52, '5', '2025-06-20 18:23:13'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 53, 'Não', '2025-06-20 18:23:13'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 54, 'Não', '2025-06-20 18:23:13'),
(66, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 55, 'não', '2025-06-20 18:23:13'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 56, 'não', '2025-06-20 18:23:13'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 57, 'Não', '2025-06-20 18:23:13'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 58, 'Sim', '2025-06-20 18:23:13'),
(70, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 59, 'Não', '2025-06-20 18:23:13'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 34, 61, 60, 'paciente lactante ', '2025-06-20 18:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `respostas_prontuario`
--

CREATE TABLE `respostas_prontuario` (
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
-- Table structure for table `sub_grupos_contas`
--

CREATE TABLE `sub_grupos_contas` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(50) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_grupos_contas`
--

INSERT INTO `sub_grupos_contas` (`id`, `token_emp`, `nome`, `grupo_id`, `created_at`) VALUES
(1, NULL, 'Todos os serviços da clinica', 1, '2025-06-15 04:14:33'),
(2, NULL, 'Investimento', 2, '2025-06-15 04:14:33'),
(3, NULL, 'Parcerias', 3, '2025-06-15 04:14:33'),
(4, NULL, 'Matérias Primas', 4, '2025-06-15 04:14:33'),
(5, NULL, 'Pro-labore', 5, '2025-06-15 04:14:33'),
(6, NULL, 'Social Media', 6, '2025-06-15 04:14:33'),
(7, NULL, 'Tráfego Google Ads', 6, '2025-06-15 04:14:33'),
(8, NULL, 'Video Maker', 6, '2025-06-15 04:14:33'),
(9, NULL, 'Impressos e papelaria', 6, '2025-06-15 04:14:33'),
(10, NULL, 'Brindes pacientes', 6, '2025-06-15 04:14:33'),
(11, NULL, 'Gestor de Trafego', 6, '2025-06-15 04:14:33'),
(12, NULL, 'Coworking', 7, '2025-06-15 04:14:33'),
(13, NULL, 'Conta de Celular', 7, '2025-06-15 04:14:33'),
(14, NULL, 'Contabilidade', 7, '2025-06-15 04:14:33'),
(15, NULL, 'Seguros', 7, '2025-06-15 04:14:33'),
(16, NULL, 'Gasolina', 7, '2025-06-15 04:14:33'),
(17, NULL, 'Dominio site', 7, '2025-06-15 04:14:33'),
(18, NULL, 'Software', 7, '2025-06-15 04:14:33'),
(19, NULL, 'Estacionamento', 7, '2025-06-15 04:14:33'),
(20, NULL, 'Aluguel', 7, '2025-06-15 04:14:33'),
(21, NULL, 'Despesas e Tarifas Bancárias', 8, '2025-06-15 04:14:33'),
(22, NULL, 'DAS', 9, '2025-06-15 04:14:33'),
(23, NULL, 'Imposto Federal/Estadual/Mun', 9, '2025-06-15 04:14:33'),
(24, NULL, 'INSS', 9, '2025-06-15 04:14:33'),
(25, NULL, 'IOF', 9, '2025-06-15 04:14:33'),
(26, NULL, 'Cursos e Materiais de Estudo', 10, '2025-06-15 04:14:33'),
(27, NULL, 'Máquinas e Equipamentos', 10, '2025-06-15 04:14:33'),
(28, NULL, 'Parcerias', 10, '2025-06-15 04:14:33'),
(29, NULL, 'Investimento Financeiro', 10, '2025-06-15 04:14:33');

-- --------------------------------------------------------

--
-- Table structure for table `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipos`
--

INSERT INTO `tipos` (`id`, `nome`, `created_at`) VALUES
(1, 'Receita', '2025-06-15 04:14:33'),
(2, 'Despesa', '2025-06-15 04:14:33');

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

--
-- Dumping data for table `tratamento`
--

INSERT INTO `tratamento` (`id`, `token_emp`, `email`, `plano_descricao`, `comentario`, `plano_data`, `sessao_atual`, `sessao_total`, `sessao_status`, `token`) VALUES
(1, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Laser', '', '2023-04-29 00:00:00', 1, 1, 'Finalizada', '1'),
(2, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão de Microagulhamento', '', '2023-04-29 00:00:00', 1, 1, 'Em Andamento', '2'),
(3, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', '4 Sessões de Fotobioestimulação com Laser', '', '2023-04-14 00:00:00', 4, 4, 'Em Andamento', '3'),
(4, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessões de Spa dos Fios', '', '2023-04-28 00:00:00', 2, 2, 'Em Andamento', '4'),
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessões de Blend de óleo couro cabeludo', '', '2023-04-28 00:00:00', 2, 2, 'Em Andamento', '5'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-03-20 00:00:00', 12, 12, 'Em Andamento', '6'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'fernandaandradeteixeira@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-02-25 00:00:00', 3, 3, 'Finalizada', '7'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'fernandaandradeteixeira@gmail.com', 'Sessão Microagulhamento', '', '2023-03-18 00:00:00', 3, 3, 'Em Andamento', '8'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-05-06 00:00:00', 3, 3, 'Em Andamento', '9'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Sessão Microagulhamento', '', '2023-05-06 00:00:00', 3, 3, 'Em Andamento', '10'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-04-12 00:00:00', 3, 3, 'Finalizada', '11'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Microagulhamento', '', '2023-04-12 00:00:00', 1, 1, 'Finalizada', '12'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-05-17 00:00:00', 3, 3, 'Em Andamento', '13'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Microagulhamento', '', '2023-05-24 00:00:00', 1, 1, 'Finalizada', '14'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Sessão de Fotobioestimulação', '', '2023-05-25 00:00:00', 0, 4, 'Em Andamento', '15'),
(19, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Sessão de Fotobioestimulação', '', '2023-05-25 00:00:00', 3, 3, 'Em Andamento', '16'),
(20, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Microagulhamento', '', '2023-06-17 00:00:00', 1, 1, 'Em Andamento', '17'),
(21, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-06-06 00:00:00', 3, 3, 'Em Andamento', '18'),
(22, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessão Microagulhamento', '', '2023-06-17 00:00:00', 1, 1, 'Em Andamento', '19'),
(23, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-07-27 00:00:00', 1, 1, 'Em Andamento', '20'),
(24, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'krinasantana@gmail.com', 'Sessão Microagulhamento', '', '2023-06-17 00:00:00', 1, 1, 'Finalizada', '21'),
(25, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Microagulhamento', '', '2023-07-15 00:00:00', 1, 1, 'Em Andamento', '22'),
(27, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Microagulhamento', '', '2023-06-28 00:00:00', 1, 1, 'Em Andamento', '23'),
(28, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-07-05 00:00:00', 3, 3, 'Em Andamento', '24'),
(29, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'henrique.amon@saude.ba.gov.br', 'Sessão Microagulhamento', '', '2023-07-06 00:00:00', 0, 1, 'Em Andamento', '25'),
(30, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'denis_ferraz359@hotmail.com', 'Laser', '', '2023-07-02 00:00:00', 0, 10, 'Em Andamento', '26'),
(31, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Microagulhamento', '', '2023-08-12 00:00:00', 1, 1, 'Em Andamento', '27'),
(32, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-07-18 00:00:00', 3, 3, 'Em Andamento', '28'),
(33, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'alinerochas@hotmail.com', 'Sessão Microagulhamento', '', '2023-07-25 00:00:00', 1, 1, 'Em Andamento', '29'),
(34, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-07-27 00:00:00', 3, 3, 'Finalizada', '30'),
(38, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-07-08 00:00:00', 3, 3, 'Em Andamento', '34'),
(39, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Sessão Microagulhamento', '', '2023-07-22 00:00:00', 3, 3, 'Em Andamento', '35'),
(40, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'laser vermelho 8 joules', '2023-08-02 00:00:00', 2, 2, 'Em Andamento', 'f800246191d309d9a1f1872cb4a7761a'),
(41, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão intradermoterapia', 'aplicação de injetáveis couro cabeludo', '2023-08-09 00:00:00', 2, 2, 'Em Andamento', '4676b17d8b0105e2bfb6cfd683d9366e'),
(42, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'Laser Vermelho 8 joules', '2023-08-02 00:00:00', 0, 0, 'Em Andamento', 'f800246191d309d9a1f1872cb4a7761a'),
(43, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão intradermoterapia', 'PHD CAPILAR LOTE 002-484 V:03/25', '2023-08-10 00:00:00', 0, 0, 'Em Andamento', '4676b17d8b0105e2bfb6cfd683d9366e'),
(44, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'Laser vermelho 8 joules', '2023-08-17 00:00:00', 0, 0, 'Em Andamento', 'f800246191d309d9a1f1872cb4a7761a'),
(45, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Sessão de Fotobioestimulação', 'sessão de laser 6joules', '2023-08-17 00:00:00', 1, 1, 'Finalizada', '0c6e70140669e7a7890d7c5e512efbe6'),
(46, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'michel.oliveira2701@gmail.com', 'Sessão de Fotobioestimulação', 'Sessão de laser 6 joules', '2023-08-17 00:00:00', 0, 0, 'Em Andamento', '0c6e70140669e7a7890d7c5e512efbe6'),
(47, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão intradermoterapia', 'Mescla:\\r\\n17 alfaestradiol 0,25mg/ml L:CAM15062301S VAL 06/24 +BFGF/IGF/VEGF/COPPER PEPTIDEO 12MG/ML L:CFA11072301S VAL 07/24 + SILICIO P3% L:CAM12072304S VAL 07/24+ COMPLEXO VITAMINICO L:CAM13072306S VAL 07/24', '2023-08-23 00:00:00', 0, 0, 'Em Andamento', '4676b17d8b0105e2bfb6cfd683d9366e'),
(48, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'Definir Joules', '2023-08-29 00:00:00', 2, 2, 'Em Andamento', '0071a138a04c6a56b52577c1af1fd100'),
(49, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Definir mesclas', '2023-09-06 00:00:00', 2, 2, 'Em Andamento', '295e7ee355af6b7d8eb17276754f9d32'),
(50, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Sessão Microagulhamento', 'Microagulhamento com drug delivery ', '2023-08-19 00:00:00', 0, 0, 'Em Andamento', '35'),
(51, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'Laser Vermelho 8 joules', '2023-08-29 00:00:00', 0, 0, 'Em Andamento', '0071a138a04c6a56b52577c1af1fd100'),
(58, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Mescla 17 alfa estradiol lote 15062301s Val 06/24+ complexo vitamínico lote 13072306s Val 07/24+ fator de crescimento 11072301S Val 07/24+ lidocaína 1% lote 29062302S Val 06/24', '2023-09-08 00:00:00', 0, 0, 'Em Andamento', '295e7ee355af6b7d8eb17276754f9d32'),
(59, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Sessão Microagulhamento', 'Minoxidil 7%+ finasteride 1mg +Betametasona 0,1%+tretinoina 0,01%+ trichoxidil 1,5%+ Trichosol  Lote:2306004008 val 10/23', '2023-09-09 00:00:00', 0, 0, 'Em Andamento', '35'),
(60, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', '', '2023-09-09 00:00:00', 1, 1, 'Em Andamento', '0189dffa0fa3d355b5a31b0078602c87'),
(61, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', 'complexo vitaminico lote 13072306s val 07/24 + silicio p lote 12072304S val 07/24 + fator de crescimento lote 11072301S VAL 07/24 + lidocaina 29062302s val 06/24', '2023-09-09 00:00:00', 0, 0, 'Em Andamento', '0189dffa0fa3d355b5a31b0078602c87'),
(62, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Zinco+lidocaína+compl vitamínico+fator de crescimento ', '2023-09-20 00:00:00', 0, 0, 'Em Andamento', '295e7ee355af6b7d8eb17276754f9d32'),
(63, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'evanilsonsoliveira@gmail.com', 'Sessão de Fotobioestimulação', 'laser vermelho 4 joules \\r\\nlaser azul + vermelho 26 joules occiptal', '2023-09-23 00:00:00', 0, 0, 'Em Andamento', '34'),
(64, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia', '', '2023-10-03 00:00:00', 1, 1, 'Em Andamento', '98c845016cab73e1dc0df5ab52581949'),
(65, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia', 'Finasterida lote:30032304s val 06/24 + minoxidil + lidocaina ', '2023-10-03 00:00:00', 0, 0, 'Em Andamento', '98c845016cab73e1dc0df5ab52581949'),
(67, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-10-04 00:00:00', 0, 0, 'Em Andamento', '0071a138a04c6a56b52577c1af1fd100'),
(68, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessões de laserterapia ', '', '2023-10-10 00:00:00', 4, 4, 'Em Andamento', 'e8c63b4e7c914736766da0e08edb365b'),
(69, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessões de laserterapia ', 'Laser vermelho 5joules + azul 20s ', '2023-10-10 00:00:00', 0, 0, 'Em Andamento', 'e8c63b4e7c914736766da0e08edb365b'),
(71, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-10-13 00:00:00', 2, 2, 'Em Andamento', 'a32eebcff0f33faa305829538f7b5f99'),
(73, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Microagulhamento', '', '2023-11-10 00:00:00', 2, 2, 'Em Andamento', 'd97fd2eb94827ff587170bca260b82d5'),
(74, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Intradermoterapia', '', '2023-11-17 00:00:00', 2, 2, 'Em Andamento', '030cd8cab7871bbb617845e4ecf917d1'),
(75, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', '', '2023-10-14 00:00:00', 1, 1, 'Em Andamento', 'f402655883f661ebc387fcc10c9ab8ac'),
(76, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-10-24 00:00:00', 2, 2, 'Em Andamento', '44eb4ced71054d472627afb08c9a543e'),
(77, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', '', '2023-10-18 00:00:00', 2, 2, 'Em Andamento', '440b0c1e57aa0440147aaa35c89b1491'),
(78, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', 'copper peptideo+ fator de cresc lote:FA2009231S VAL 09/24\\r\\nSilicio P Lote: 12072304S Val 07/24\\r\\n17 alfa estradiol Lote: 15062301S val:06/24 \\r\\nLidocaina 2906232S val 06/24', '2023-10-21 00:00:00', 0, 0, 'Em Andamento', 'f402655883f661ebc387fcc10c9ab8ac'),
(81, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão de Fotobioestimulação', 'Laser vermelho 5 joules', '2023-10-27 00:00:00', 0, 0, 'Em Andamento', 'a32eebcff0f33faa305829538f7b5f99'),
(82, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessões de laserterapia ', '', '2023-10-16 00:00:00', 0, 0, 'Em Andamento', 'e8c63b4e7c914736766da0e08edb365b'),
(83, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessões de laserterapia ', '', '2023-10-24 00:00:00', 0, 0, 'Em Andamento', 'e8c63b4e7c914736766da0e08edb365b'),
(84, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessões de laserterapia ', '', '2023-10-30 00:00:00', 0, 0, 'Em Andamento', 'e8c63b4e7c914736766da0e08edb365b'),
(87, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-09-01 00:00:00', 2, 2, 'Em Andamento', 'f79dfd660dd55f4bbe4011a8b3fb315a'),
(90, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Microagulhamento', '', '2023-09-14 00:00:00', 1, 1, 'Em Andamento', '4594a23d27f97482a1adb5db96f16e70'),
(91, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Microagulhamento', '', '2023-09-14 00:00:00', 0, 0, 'Em Andamento', '4594a23d27f97482a1adb5db96f16e70'),
(93, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-08-18 00:00:00', 0, 0, 'Em Andamento', 'f79dfd660dd55f4bbe4011a8b3fb315a'),
(94, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-09-10 00:00:00', 0, 0, 'Em Andamento', 'f79dfd660dd55f4bbe4011a8b3fb315a'),
(95, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Intradermoterapia', '', '2023-10-04 00:00:00', 3, 3, 'Em Andamento', 'ce27562888feac5a57e4beb7564523e9'),
(97, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Intradermoterapia', '', '2023-10-06 00:00:00', 0, 0, 'Em Andamento', 'ce27562888feac5a57e4beb7564523e9'),
(98, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Intradermoterapia', '', '2023-10-20 00:00:00', 0, 0, 'Em Andamento', 'ce27562888feac5a57e4beb7564523e9'),
(99, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', '', '2023-10-18 00:00:00', 0, 0, 'Em Andamento', '440b0c1e57aa0440147aaa35c89b1491'),
(100, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-11-01 00:00:00', 0, 0, 'Em Andamento', '44eb4ced71054d472627afb08c9a543e'),
(105, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessão de Fotobioestimulação', '', '2023-11-07 00:00:00', 1, 1, 'Em Andamento', '8891c4e74ee38a6507d226eca8eb392b'),
(106, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'jr_losant@hotmail.com', 'Sessão de Fotobioestimulação', '4 joules laser infravermelho\\r\\n5 joules laser vermelho', '2023-11-07 00:00:00', 0, 0, 'Em Andamento', '8891c4e74ee38a6507d226eca8eb392b'),
(110, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Alfa estradiol + lidocaina +biotina +fator de crescimento', '2023-11-07 00:00:00', 0, 0, 'Em Andamento', '440b0c1e57aa0440147aaa35c89b1491'),
(111, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão de Fotobioestimulação', '', '2023-11-16 00:00:00', 2, 2, 'Em Andamento', 'b5beff7ce2acdd0358f0a602405c3ea0'),
(112, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Microagulhamento', '', '2023-12-14 00:00:00', 2, 2, 'Em Andamento', 'f241f5f806e4208e5c49b8f45ce08e10'),
(113, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Intradermoterapia', '', '2023-12-28 00:00:00', 2, 2, 'Em Andamento', 'ca138ef702e9b136957239a52075a6c9'),
(114, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão de Fotobioestimulação', 'Alta frequência  5min\r\nLaser vermelho + laser azul 19J\r\nTonico green tea gradha ', '2023-11-16 00:00:00', 0, 0, 'Em Andamento', 'b5beff7ce2acdd0358f0a602405c3ea0'),
(115, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Microagulhamento', '', '2023-11-10 00:00:00', 0, 0, 'Em Andamento', 'd97fd2eb94827ff587170bca260b82d5'),
(117, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão de Fotobioestimulação', 'Laser vermelho 5 joules', '2023-11-17 00:00:00', 0, 0, 'Em Andamento', '44eb4ced71054d472627afb08c9a543e'),
(118, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', '', '2023-12-01 00:00:00', 2, 2, 'Em Andamento', '353fda78ccfa2100816ee826cbac4d86'),
(119, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2023-11-30 00:00:00', 1, 1, 'Em Andamento', 'f2fac31f7d8212176017486516ab72d9'),
(120, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2023-12-09 00:00:00', 1, 1, 'Em Andamento', 'f6d8af475efdf6fab272d073ee382197'),
(123, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão Intradermoterapia', '', '2023-11-21 00:00:00', 2, 2, 'Em Andamento', '607f6dd2b488fd36197d671ef4e442c1'),
(124, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-11-07 00:00:00', 2, 2, 'Em Andamento', '2cd878d2cd40b89590cce41345454c60'),
(125, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão de Fotobioestimulação', '[Laser vermelho + azul 30s]', '2023-11-07 00:00:00', 0, 0, 'Em Andamento', '2cd878d2cd40b89590cce41345454c60'),
(126, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão Intradermoterapia', 'Finasterida+ biotina+ minoxidil+ lidocaina', '2023-11-21 00:00:00', 0, 0, 'Em Andamento', '607f6dd2b488fd36197d671ef4e442c1'),
(127, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscila_nutri89@hotmail.com', 'Sessão Intradermoterapia', '', '2023-11-01 00:00:00', 0, 0, 'Em Andamento', 'ce27562888feac5a57e4beb7564523e9'),
(130, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão de Fotobioestimulação', '', '2023-11-28 00:00:00', 0, 0, 'Em Andamento', '2cd878d2cd40b89590cce41345454c60'),
(131, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Intradermoterapia', '', '2023-11-28 00:00:00', 0, 0, 'Em Andamento', '030cd8cab7871bbb617845e4ecf917d1'),
(132, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão de Fotobioestimulação', 'Laser vermelho 5 joules ', '2023-11-30 00:00:00', 0, 0, 'Em Andamento', 'b5beff7ce2acdd0358f0a602405c3ea0'),
(133, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão Intradermoterapia', 'silicio+ finasterida+ biotina', '2023-12-05 00:00:00', 0, 0, 'Em Andamento', '607f6dd2b488fd36197d671ef4e442c1'),
(134, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Silicio Lote 12072304S val 07/24\\r\\nLidocaina 1% L 05092306S val 09/24\\r\\n17 Alfa Estradiol L15062301S Val 06/24\\r\\nCopper pept+fator de crescimento; L 20092301s VAL 09/24', '2023-12-09 00:00:00', 0, 0, 'Em Andamento', '353fda78ccfa2100816ee826cbac4d86'),
(135, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2023-11-30 00:00:00', 0, 0, 'Em Andamento', 'f2fac31f7d8212176017486516ab72d9'),
(136, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Microagulhamento', 'COMPL VITAMINICO+SILICIO+ ALFA ESTRADIOL', '2023-12-11 00:00:00', 0, 0, 'Em Andamento', 'd97fd2eb94827ff587170bca260b82d5'),
(137, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'filipeferreira99@hotmail.com', 'Sessão Intradermoterapia', '', '2023-12-11 00:00:00', 1, 3, 'Em Andamento', '5948e8bf78bc2eb17f8e579e323c2085'),
(138, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'filipeferreira99@hotmail.com', 'Sessão Intradermoterapia', 'idocaina L: 05092306S VAL 09/24 + Zinco L 25072301S VAL 07/24 + COMP VITAMIN L 25092303S VAL 09/24', '2023-12-11 00:00:00', 0, 0, 'Em Andamento', '5948e8bf78bc2eb17f8e579e323c2085'),
(140, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Microagulhamento', '', '2023-12-14 00:00:00', 0, 0, 'Em Andamento', 'f241f5f806e4208e5c49b8f45ce08e10'),
(141, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Intradermoterapia', 'Finasterida L 180923025 val:09/24\\r\\nLidocaina 231123075 Val 11/2024\\r\\nComplexo vitaminico: 250923035 Val:09/24\\r\\nCopper pep + fator de cresc: 200923015 Val 09/24', '2023-12-28 00:00:00', 0, 0, 'Em Andamento', 'ca138ef702e9b136957239a52075a6c9'),
(142, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão Intradermoterapia', '', '2023-12-28 00:00:00', 1, 1, 'Em Andamento', 'd1c5f26fb8f354de8911d1ab0883c973'),
(143, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Kaiqueeecr7@gmail.com', 'Sessão Intradermoterapia', 'Biotina L 28062301S V 06/24\\r\\nLicocaina: 231123075 11/24\\r\\nFinasterida 180923025 Val 09/24\\r\\nMinoxidil 231123095 Val:11/24', '2023-12-28 00:00:00', 0, 0, 'Em Andamento', 'd1c5f26fb8f354de8911d1ab0883c973'),
(144, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Microagulhamento', 'Finasterida 1809230s val 09/24\\r\\nZinco 25072301s val 07/24\\r\\nMinoxidil 23112309s val 11/24', '2023-01-11 00:00:00', 0, 0, 'Em Andamento', 'f241f5f806e4208e5c49b8f45ce08e10'),
(145, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'priscilaeve@hotmail.com', 'Sessão Intradermoterapia', '', '2024-01-11 00:00:00', 0, 0, 'Em Andamento', '030cd8cab7871bbb617845e4ecf917d1'),
(146, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia Mensal', 'Silicio P Lote 05092305S Val:09/24\\r\\nFator de Crescimento Lote 20092301S Val 09/24\\r\\n17 Alfa Estradiol Lote: 27092304S Val 09/24\\r\\nLidocaina 1% Lote 23112307S Val 11/24', '2024-01-13 00:00:00', 0, 0, 'Em Andamento', 'f6d8af475efdf6fab272d073ee382197'),
(147, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'manucassia@gmail.com', 'Sessão Intradermoterapia', 'Silicio P Lote 05092305S Val:09/24\\r\\nFator de Crescimento Lote 20092301S Val 09/24\\r\\n17 Alfa Estradiol Lote: 27092304S Val 09/24\\r\\nLidocaina 1% Lote 23112307S Val 11/24', '2024-01-13 00:00:00', 0, 0, 'Em Andamento', '353fda78ccfa2100816ee826cbac4d86'),
(148, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2024-01-16 00:00:00', 1, 1, 'Em Andamento', '12df83098bd045314fbc4afa4c61e2ef'),
(149, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', 'Zinco lote 25072301S VAL 07/24\\r\\nMinoxidil 0,5% 23112309S VAL 11/24\\r\\nLidocaina 1% 23112307S Val 11/24\\r\\nFinasterida 0,05% 18092302S Val 09/24', '2024-01-16 00:00:00', 0, 0, 'Em Andamento', '12df83098bd045314fbc4afa4c61e2ef'),
(151, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Sessão Intradermoterapia', 'Silicio P L: 05092305S VAL 09/24\\r\\nLIDOCAINA 23112307S VAL 11/24\\r\\nMINOXIDIL 23112309S VAL 11/24\\r\\nFINASTERIDA 18092302S VAL 09/24', '2024-02-01 00:00:00', 0, 0, 'Em Andamento', 'ca138ef702e9b136957239a52075a6c9'),
(152, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia ', 'Sessão mensal ', '2024-03-12 00:00:00', 1, 1, 'Em Andamento', '3a449afb4139fd95326f4434d1747b72'),
(153, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia ', 'fator de crescimento Lote:27112301s Val: 11/24 \\r\\nLidocaína 14122304S Val;12/24\\r\\nPill food Lote:14122301S VAL 12/24', '2024-03-12 00:00:00', 0, 0, 'Em Andamento', '3a449afb4139fd95326f4434d1747b72'),
(154, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', '6 Sessões de microagulhamento associado a laser + intradermotrerapia ', '2024-03-14 00:00:00', 5, 5, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(155, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Pool de fator de crescimento L; 27112301S Val: 11/24\\r\\nMinoxidil L: 2311239S Val 11/24\\r\\nD pantenol L:28062302S VAL 06/24\\r\\nComp Vitaminico L: 25092303S Val: 09/24', '2024-03-14 00:00:00', 0, 0, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(157, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', '', '2024-04-20 00:00:00', 1, 1, 'Em Andamento', '84d2db4aa7dfc86f283ab47d94c0eb5e'),
(158, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', 'comp vitaminico Lote:22112303s val 11/24\\r\\n17 estradiol lote: 27092304s val 09/24\\r\\nLidocaina L: 14112304S VAL 12/24\\r\\nMinoxidil L:06022404S VAL:02/25', '2024-04-20 00:00:00', 0, 0, 'Em Andamento', '84d2db4aa7dfc86f283ab47d94c0eb5e'),
(159, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', '', '2024-04-18 00:00:00', 1, 1, 'Em Andamento', '2fd6691d6bd4537b55de02f5f85260e7'),
(160, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'mICROAGULHAMENTO COM DRUG DELIVERY\\r\\nMinoxidil L: 06022404S VAL 02/25 +Pil food L 14122301s val 12/24 +fINASTERIDA 18092302S VAL 09/24\\r\\nFotibioestimulaçao com laservermelho 4 jaules por ponto', '2023-04-18 00:00:00', 0, 0, 'Em Andamento', '2fd6691d6bd4537b55de02f5f85260e7'),
(161, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2024-04-19 00:00:00', 6, 6, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(162, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'pool de fator de crescimento L: 27112301S VAL 11/24 +Comp vitaminico L:22112303S val:11/24+ Lidocaina L:14122304s val 12/24', '2024-04-19 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(163, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2024-05-02 00:00:00', 4, 4, 'Em Andamento', 'fcdcf7d8c49d4878c582fcbdf68b60d3'),
(164, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', 'finasterida lote 07022404S VAL 02/25\\r\\nD PANTENOL LOTE 28062302S VAL 06/24\\r\\nMINOXIDIL LOTE 06022404S VAL 02-25\\r\\nLIDOCAINA LOTE 14122304S VAL 12/24', '2024-05-02 00:00:00', 0, 0, 'Em Andamento', 'fcdcf7d8c49d4878c582fcbdf68b60d3'),
(166, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Minoxidil l:06022404s val 02/25\\r\\npool Fator de crescimento:27112301s val 11/24\\r\\nD pantenol:28062302s val 06/24\\r\\nDutasterida:07022403s val 02/25', '2024-05-16 00:00:00', 0, 0, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(167, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2024-05-25 00:00:00', 1, 1, 'Em Andamento', 'f0a77e3a3a9482735a5f233e69bf4b27'),
(168, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia Mensal', 'MINOXIDIL L 06022404S VAL 02/25\\r\\nFINASTERIDA L 07022404S VAL 02/25\\r\\nPILL FOOD L 14122301S VAL 12/24\\r\\nLIDOCAINA L14122304S VAL 12/24', '2024-05-25 00:00:00', 0, 0, 'Em Andamento', 'f0a77e3a3a9482735a5f233e69bf4b27'),
(169, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2024-05-17 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(170, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'exemplo@exemplo.com.br', 'Programa HaiRecupere', '', '2024-05-11 00:00:00', 1, 6, 'Em Andamento', '8595ed74a1a7c763d50e12990f7b2976'),
(171, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'exemplo@exemplo.com.br', 'Programa HaiRecupere', 'Microagulhamento com drug delivery +Fotobioestimulação 4 joules por ponto ', '2024-05-11 00:00:00', 0, 0, 'Em Andamento', '8595ed74a1a7c763d50e12990f7b2976'),
(172, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '4 Sessões de Fotobioestimulação com Laser', '', '2024-05-31 00:00:00', 4, 4, 'Em Andamento', '314d6ae25d9bddf3f48e771943216301'),
(173, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-06-14 00:00:00', 1, 1, 'Em Andamento', 'c0d9d18ebe317aa5eb4ab077720d4f11'),
(174, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '4 Sessões de Fotobioestimulação com Laser', 'Utilizado laser vermelho associado ao led azul totalizando 13 joule, finalizado sessão com tônico de fator de crescimento.', '2024-05-31 00:00:00', 0, 0, 'Em Andamento', '314d6ae25d9bddf3f48e771943216301'),
(176, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '4 Sessões de Fotobioestimulação com Laser', 'laser vermelho 4 joules, alta frequencia 5 min e finalizando com tonico de fator de crescimento', '2024-06-07 00:00:00', 0, 0, 'Em Andamento', '314d6ae25d9bddf3f48e771943216301'),
(177, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Programa HaiRecupere', '', '2024-06-03 00:00:00', 3, 3, 'Em Andamento', '47a0f3541da4e02cac498bcf19c07b58'),
(178, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Programa HaiRecupere', 'fobioestimulação', '2024-06-05 00:00:00', 0, 0, 'Em Andamento', '47a0f3541da4e02cac498bcf19c07b58'),
(179, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Programa HaiRecupere', 'fobioestimulação', '2024-06-17 00:00:00', 0, 0, 'Em Andamento', '47a0f3541da4e02cac498bcf19c07b58'),
(180, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Programa HaiRecupere', 'Intradermoterapia Silicio P +Comp Vitaminico+ Lidocaina', '2024-07-03 00:00:00', 0, 0, 'Em Andamento', '47a0f3541da4e02cac498bcf19c07b58'),
(181, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '4 Sessões de Fotobioestimulação com Laser', '', '2024-06-14 00:00:00', 0, 0, 'Em Andamento', '314d6ae25d9bddf3f48e771943216301'),
(182, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão de Fotobioestimulação', '', '2024-07-15 00:00:00', 1, 1, 'Em Andamento', '1236570e8bc2014bf8fd8a268e15a3c6'),
(183, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão Intradermoterapia', '', '2024-07-29 00:00:00', 1, 1, 'Em Andamento', 'd7f203b3e013b8a25fc0218344b3dc9e'),
(184, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão de Fotobioestimulação', 'laser vermelho 4 joules', '2024-07-15 00:00:00', 0, 0, 'Em Andamento', '1236570e8bc2014bf8fd8a268e15a3c6'),
(185, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'neilsonrabelo@hotmail.com', 'Sessão de Fotobioestimulação', '', '2024-08-01 00:00:00', 1, 2, 'Em Andamento', '7b60f1809de5814d8ad87f5f19474826'),
(186, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'neilsonrabelo@hotmail.com', 'Sessão de Fotobioestimulação', 'Sessão de Laserterapia vermelho + azul', '2024-08-01 00:00:00', 0, 0, 'Em Andamento', '7b60f1809de5814d8ad87f5f19474826'),
(187, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão Intradermoterapia', 'Sessão de intradermoterapia ', '2024-07-29 00:00:00', 0, 0, 'Em Andamento', 'd7f203b3e013b8a25fc0218344b3dc9e'),
(188, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão Intradermoterapia', '', '2024-08-12 00:00:00', 1, 1, 'Em Andamento', '86a9e27f8f0a845e6e18fee37de00fa6'),
(189, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', '', '2024-05-11 00:00:00', 6, 6, 'Finalizada', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(190, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', '', '2024-05-11 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(191, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', '', '2024-07-06 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(192, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Fator de crescimento+ lidocaina+ biotina+silicio+pantotenato ', '2024-08-09 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(193, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'camile.ac@hotmail.com', 'Sessão Intradermoterapia', 'Lidocaina+ comp vitaminico+ fator de crescimento+silicio p', '2024-08-12 00:00:00', 0, 0, 'Em Andamento', '86a9e27f8f0a845e6e18fee37de00fa6'),
(194, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Microagulhamento+ laserterapia 4joules', '2024-06-13 00:00:00', 0, 0, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(195, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Microagulhamento + Laserterapia 4joules', '2024-07-23 00:00:00', 0, 0, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(196, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Intradermoterapia:Dutasterida+ Lidocaina+Min oxidil+Silicio + Laserterapia 4joules', '2024-08-29 00:00:00', 0, 0, 'Em Andamento', 'f344839c921e9dfd53d2a586155e2930'),
(197, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere Manutenção', '', '2024-10-29 00:00:00', 1, 1, 'Em Andamento', '6b682b29478ffaf28be685de018b1b25'),
(198, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-10-03 00:00:00', 0, 0, 'Em Andamento', 'c0d9d18ebe317aa5eb4ab077720d4f11'),
(199, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', '4 Sessões de Fotobioestimulação com Laser', '', '2024-07-05 00:00:00', 0, 0, 'Em Andamento', '314d6ae25d9bddf3f48e771943216301'),
(200, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-07-26 00:00:00', 6, 6, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(201, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-07-26 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(202, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-08-30 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(203, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2024-09-30 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(204, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', '', '2024-08-17 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(205, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', '', '2024-09-28 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(206, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Sessão Microagulhamento', '', '2024-10-15 00:00:00', 1, 1, 'Em Andamento', '42e6d4ebd8005e77272d2c4493b5e74f'),
(207, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Sessão Microagulhamento', '', '2024-10-15 00:00:00', 0, 0, 'Em Andamento', '42e6d4ebd8005e77272d2c4493b5e74f'),
(208, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere Manutenção', 'Intradermoterapia com mescla de Lidocaina+ dutasterida+ minoxidil+comp vitaminico', '2024-10-29 00:00:00', 0, 0, 'Em Andamento', '6b682b29478ffaf28be685de018b1b25'),
(209, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'danyelporto@gmail.com', 'Programa HaiRecupere', 'Sesão de intradermoterapia ou Microagulhamento mensal por 6 meses', '2024-11-09 00:00:00', 2, 6, 'Em Andamento', '960f9fd9ef9d7e3ecf216bcdeadf0ba4'),
(210, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'danyelporto@gmail.com', 'Programa HaiRecupere', 'Realizado sessão de intradermoterapia com mescla contendo minoxidil + lidocaina + comp vitaminico + dutasteruda', '2024-11-09 00:00:00', 0, 0, 'Em Andamento', '960f9fd9ef9d7e3ecf216bcdeadf0ba4'),
(211, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Intradermoterapia ', '2024-11-19 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(212, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', 'Intradermoterapia ', '2024-11-09 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(213, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Sessão Microagulhamento', 'Intradermoterapia aplicado mescla de finasterida + minoxidil +  lidocaina +  comp vitaminico', '2024-12-07 00:00:00', 0, 0, 'Em Andamento', 'bcc1f9290d6cf13390874cc39c98ecd9'),
(214, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'caroline_lordelo@hotmail.com', 'Programa HaiRecupere( manutenção)', '', '2025-01-18 00:00:00', 0, 6, 'Em Andamento', '584a1b7ef991eb3b9b4898e9c78632ae'),
(215, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'Programa HaiRecupere', '', '2024-12-06 00:00:00', 3, 5, 'Em Andamento', '2d3de4bba2fb659e32fc83b2f9fce354'),
(216, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'Programa HaiRecupere', 'Microagulhamento com ativos ', '2024-12-06 00:00:00', 0, 0, 'Em Andamento', '2d3de4bba2fb659e32fc83b2f9fce354'),
(217, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', ' finasterida + Minoxidil + pill food+ lidocaína  aplicado 5 ml', '2024-12-06 00:00:00', 0, 0, 'Em Andamento', 'fcdcf7d8c49d4878c582fcbdf68b60d3'),
(218, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', '', '2024-11-14 00:00:00', 5, 6, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(219, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', 'Intradermoterapia ', '2024-11-14 00:00:00', 0, 0, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(220, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', 'Sessao de microagulhamento com ativos ', '2024-12-12 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(221, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'PROGRAMAÇÃO :\\r\\n3-4 SESSÕES DE MICROAGULHAMENTO \\r\\n2-3 SESSÕES DE INTRADERMOTERAPIA ', '2024-12-16 00:00:00', 6, 6, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(222, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'intradermoterapia ', '2024-07-08 00:00:00', 0, 0, 'Em Andamento', 'c5ce2227c5bc016a6ff6638a17f22fd8'),
(223, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Microagulhamento', '', '2024-09-10 00:00:00', 2, 5, 'Em Andamento', 'c1777aeca4e59949073b73495644126d'),
(224, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Microagulhamento', '', '2024-09-10 00:00:00', 0, 0, 'Em Andamento', 'c1777aeca4e59949073b73495644126d'),
(225, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'Laserterapia 4 joules + microagulhamento com drug delivery ', '2024-12-16 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(226, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2024-12-17 00:00:00', 1, 1, 'Em Andamento', 'f32f8e7a2c59df0087e511fa03819549'),
(227, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2024-12-17 00:00:00', 0, 0, 'Em Andamento', 'f32f8e7a2c59df0087e511fa03819549'),
(228, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'Programa HaiRecupere', 'FATOR DE CRESCIMENTO ANTES + ZINCO + SOLUÇÃO DE MICRO (DURANTE) +  SOLUÇÃO MICRO (APÓS) + LASER VERMELHO 4 JOULES', '2024-01-07 00:00:00', 0, 0, 'Em Andamento', '2d3de4bba2fb659e32fc83b2f9fce354'),
(229, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', 'lidocaina + finasterida+ minoxidil + silicio P', '2025-01-15 00:00:00', 0, 0, 'Em Andamento', 'fcdcf7d8c49d4878c582fcbdf68b60d3'),
(230, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', ' Laserterapia 4 joules + microagulhamento com drug delivery ]', '2025-01-20 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(231, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2024-11-01 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(232, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2025-01-31 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(233, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Programa HaiRecupere', '6 SESSÕES DE INTRADERMOTERAPIA + 6 SESSÕES DE LASERTERAPIA ', '2025-02-05 00:00:00', 4, 6, 'Em Andamento', 'dce3f608d84516fbd1d2625080824c5f'),
(234, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Programa HaiRecupere', 'Intradermoterapia VEGF+BFGF+IGF+ COPPER LAM11112401S V:11/25 (2ML) + PILL FOOD L:AM17052404S V 05/25 2ML +LIDOCAINA 1% L:AM11112403S V:11/25 (1ML)\\r\\nfOTOBIOESTIMULAÇÃO LASER VERMELHO + LED AZUL 20S 13 J', '2024-02-05 00:00:00', 0, 0, 'Em Andamento', 'dce3f608d84516fbd1d2625080824c5f'),
(235, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', '', '2025-02-08 00:00:00', 2, 5, 'Em Andamento', '6997f1b219643e54836d16ed93b5f7e2'),
(236, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', 'LIDOCAINA AM11112403S 11/25 MINOXIDIL  13112407S V 11/25 SILICIO 07112404S 11/25 FINASTERIDA 19112404S V11//25', '2025-02-08 00:00:00', 0, 0, 'Em Andamento', '6997f1b219643e54836d16ed93b5f7e2'),
(237, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'Laserterapia 4 joules + Microagulhamento com drug delivery ( Finasterida + minoxidil + zinco + fator de crescimento) ', '2025-02-17 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(238, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', '', '2025-02-21 00:00:00', 3, 3, 'Em Andamento', '095ee3abc5eb9bcb7ef4ef68e13fa775'),
(240, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'intradermoterapia com mescla de Lidocaina+ finasterida+ minoxidil+silicio P\\r\\nLaserterapia vermelho 4 joules + infravermelho 2 joules', '2025-02-21 00:00:00', 0, 0, 'Em Andamento', '095ee3abc5eb9bcb7ef4ef68e13fa775'),
(242, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'danyelporto@gmail.com', 'Programa HaiRecupere', 'Laserterapia vermelho 4 joules + microagulhamento com drug delivery ( finasterida + silicio P + Minoxidil)', '2025-02-22 00:00:00', 0, 0, 'Em Andamento', '960f9fd9ef9d7e3ecf216bcdeadf0ba4'),
(243, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Programa HaiRecupere', '[Intradermoterapia comp vitamínico L:AM18112404S V 11/25+ COPPER PE 13012503S V 01/26 LIDO 11112403S V 11/25 + fOTOBIOESTIMULAÇÃO LASER VERMELHO 4JOULES', '2025-02-26 00:00:00', 0, 0, 'Em Andamento', 'dce3f608d84516fbd1d2625080824c5f'),
(246, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', 'Microagulhamento: Finasterida + pil food + minoxidil', '2025-03-13 00:00:00', 0, 0, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(247, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', 'Intradermoterapia', '2025-02-05 00:00:00', 0, 0, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(248, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', 'Laser vermelho 4 joules + laser led azul na area occiptal + laser vermelho área de topo e lateral de cabeça +  microagulhamento com drug delivery com velocidade 6(finasterida + zinco+ minoxidil)', '2025-03-14 00:00:00', 0, 0, 'Em Andamento', '2cab43adc2e3120ee61a759a5725c7b4'),
(249, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'email@gmail.com', 'Programa HaiRecupere', '', '2025-02-05 00:00:00', 0, 0, 'Em Andamento', '2d3de4bba2fb659e32fc83b2f9fce354'),
(250, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Programa HaiRecupere', '[[Intradermoterapia comp vitamínico L:AM18112404S V 11/25+ COPPER PE 13012503S V 01/26 LIDO 11112403S V 11/25 + fOTOBIOESTIMULAÇÃO LASER VERMELHO 4JOULES]', '2025-03-18 00:00:00', 0, 0, 'Em Andamento', 'dce3f608d84516fbd1d2625080824c5f'),
(251, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', 'Intradermoterapia :minoxidil + zinco+ dutasterida + lidocaina', '2025-03-18 00:00:00', 0, 0, 'Em Andamento', 'fcdcf7d8c49d4878c582fcbdf68b60d3'),
(252, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2025-03-18 00:00:00', 1, 1, 'Em Andamento', '26220e326f7aa1702843fe70521694cb'),
(253, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Minoxidil + Finasterida + comp vitaminico + lidocaina', '2025-03-18 00:00:00', 0, 0, 'Em Andamento', '26220e326f7aa1702843fe70521694cb'),
(255, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'Microagulhamento: Finasterida + minoxidil + zinco + laserterapia vermelho 4 joules]', '2025-03-17 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(257, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Licocaina AM11112403S V: 11/25\\r\\nFinasterida L:  AM 19112404S V: 11/25\\r\\nZinco L: AM 01112402S V: 11/25\\r\\nMinoxidil L: AM13112407S V: 11/25', '2025-04-02 00:00:00', 0, 0, 'Em Andamento', '095ee3abc5eb9bcb7ef4ef68e13fa775'),
(258, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'eli.trevo@gmail.com', 'Programa HaiRecupere', 'copper peptideo + fator de crescimento+pill food + Lidocaina', '2025-04-24 00:00:00', 0, 0, 'Em Andamento', 'dce3f608d84516fbd1d2625080824c5f'),
(259, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', 'Lidocaina +Silicio +finasterida+Minoxidil', '2025-04-24 00:00:00', 0, 0, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(260, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', '', '2025-04-24 00:00:00', 4, 5, 'Em Andamento', '9b2e42eb4b0c80139282254bc32ec053'),
(261, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Minoxidil + Finasterida + comp vitaminico', '2025-04-24 00:00:00', 0, 0, 'Em Andamento', '9b2e42eb4b0c80139282254bc32ec053'),
(262, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', '', '2025-05-06 00:00:00', 1, 4, 'Em Andamento', '0e6b338ff4c27ee2de0aa33524a73922'),
(263, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'brenoalmeidasantana@gmail.com', 'Sessão Intradermoterapia Mensal', 'minoxidil + finasterida + pil food +  lidocaina', '2025-05-06 00:00:00', 0, 0, 'Em Andamento', '0e6b338ff4c27ee2de0aa33524a73922'),
(264, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Biotina+ d pantenol+  silicio ', '2025-05-06 00:00:00', 0, 0, 'Em Andamento', '9b2e42eb4b0c80139282254bc32ec053'),
(267, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Programa HaiRecupere Clinica ', '3 SESSÕES DE INJETÁVEIS', '2025-06-01 00:00:00', 0, 3, 'Em Andamento', 'd952169955644106cf2906390fbcd8d9'),
(268, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Sessão de Fotobioestimulação (BRINDE)', '', '2025-05-24 00:00:00', 1, 1, 'Em Andamento', 'cd1204ce667de509e091177363a78283'),
(269, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'bruno.ferreira88@icloud.com', 'Programa HaiRecupere', 'Intradermoterapia com mescla de ativos\\r\\n Minoxidil  Lote: 03062407S VAL: 06/25; Lidocaina  Lote: 05062407S VAL:06/25; Silicio Lote: 07112404S VAL: 11/25 Dutasterida 14012504S VAL: 01/261', '2025-05-19 00:00:00', 0, 0, 'Em Andamento', '095ee3abc5eb9bcb7ef4ef68e13fa775'),
(270, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', '', '2025-05-19 00:00:00', 2, 4, 'Em Andamento', 'f39287dd220069a70fcbfb952957087c'),
(271, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', 'Laserterapia 5 joules  + microagulhamento com drug delivery Fator de crescimento + copper peptideo Lote:  09052401S VAL: 05/25 \\r\\n Complexo Vitaminico L: 18112404S Val: 11/25; Minoxidil L:13112407S Val: 11/25; Finasterida L: 07062403S Val:06/25', '2025-05-19 00:00:00', 0, 0, 'Em Andamento', 'f39287dd220069a70fcbfb952957087c'),
(272, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'Microagulhamento + drug delivery + laserterapia 4 joules', '2025-04-16 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(273, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'everton.pinheiro@hotmail.com', 'Programa HaiRecupere', 'Laserterapia 4 joules + microagulhamento com drug delivery', '2025-05-21 00:00:00', 0, 0, 'Em Andamento', '5ec6d35fcf7ea36856ef5cb17ac376c8'),
(274, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Microagulhamento com drug delivery: biotina +fator de crescimento+ silicio + finasterida ', '2025-05-22 00:00:00', 0, 0, 'Em Andamento', '9b2e42eb4b0c80139282254bc32ec053'),
(275, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Rosana_silva654@hotmail.com', 'Programa HaiRecupere', 'Intradermoterapia: Minoxidil L AM 13112407S VAL: 11/25 +PILL FOOD LAM 11122408S VAL:12/25+LIdocaina  AM 30122401S V: 12/25 + fINASTERIDA  L: AM07062403S V: 06/25', '2025-05-29 00:00:00', 0, 0, 'Em Andamento', '2920cc4141856fb7f56a41b71bf09e97'),
(276, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Sessão de Fotobioestimulação(Brinde)', '', '2025-05-24 00:00:00', 1, 1, 'Em Andamento', '68fe609933092032d7446d95562ed276'),
(277, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Programa HaiRecupere', '', '2025-06-07 00:00:00', 1, 6, 'Em Andamento', '55cc44f0babac3f2144c37c647643f70'),
(278, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Sessão de Fotobioestimulação(Brinde)', 'Sessão de Laserterapia ', '2025-05-24 00:00:00', 0, 0, 'Em Andamento', '68fe609933092032d7446d95562ed276'),
(279, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Microagulhamento', 'Microagulhamento com drug delivery', '2025-05-22 00:00:00', 0, 0, 'Em Andamento', 'c1777aeca4e59949073b73495644126d');
INSERT INTO `tratamento` (`id`, `token_emp`, `email`, `plano_descricao`, `comentario`, `plano_data`, `sessao_atual`, `sessao_total`, `sessao_status`, `token`) VALUES
(280, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erikadourado14@gmail.com', 'Sessão Intradermoterapia', 'Intradermoterapia sessão avulsa: Minoxidil + finasterida + Silicio P + Lidocaina', '2025-05-30 00:00:00', 0, 0, 'Em Andamento', '6997f1b219643e54836d16ed93b5f7e2'),
(281, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'erico_nascimento@hotmail.com', 'Sessão de Fotobioestimulação (BRINDE)', 'Laser de baixa potência Vermelho associado a LED azul por 20s', '2025-05-24 00:00:00', 0, 0, 'Em Andamento', 'cd1204ce667de509e091177363a78283'),
(282, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '', 'Sessão de Fotobioestimulação', '', '2025-06-02 00:00:00', 0, 4, 'Em Andamento', '47a64d1720eafaae5c6be61f1feeb719'),
(283, 'd6b0ab7f1c8ab8f514db9a6d85de160a', '', 'Sessão de Fotobioestimulação', '', '2025-06-02 00:00:00', 0, 5, 'Em Andamento', '40867c13866b5e9a18bfcb8a44394e88'),
(285, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'luciana_gomes@hotmail.com', 'Sessão Intradermoterapia', 'Intradermoterapia: Mescla: Finasterida + lidocaina + Minoxidil +Complexo Vitaminico', '2025-06-04 00:00:00', 0, 0, 'Em Andamento', '9b2e42eb4b0c80139282254bc32ec053'),
(286, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'livia.carvalho@msn.com', 'Programa HaiRecupere', 'Fotobiomodulação com Laser vermelho 5 joules/cm + Intradermoterapia com aplicação de minoxidil + Pill food + Silicio + Lidocaina', '2025-06-07 00:00:00', 0, 0, 'Em Andamento', '55cc44f0babac3f2144c37c647643f70'),
(287, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'sand11cost@gmail.com', 'Sessão Microagulhamento', 'Sessão de microagulhamento com drug delivery + Fotobioestimulação com Laser vermelho ', '2025-06-18 00:00:00', 0, 0, 'Em Andamento', 'f39287dd220069a70fcbfb952957087c'),
(288, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', 'Programa HaiRecupere', '', '2025-06-18 00:00:00', 1, 7, 'Em Andamento', '424111d0fb8195ecbe27a05dfc1700d8'),
(289, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'ldiasamino7@gmail.com', 'Programa HaiRecupere', 'Sessão de laserterapia (Brinde) ', '2025-06-18 00:00:00', 0, 0, 'Em Andamento', '424111d0fb8195ecbe27a05dfc1700d8');

-- --------------------------------------------------------

--
-- Table structure for table `tratamentos`
--

CREATE TABLE `tratamentos` (
  `id` int(11) NOT NULL,
  `token_emp` varchar(35) NOT NULL,
  `tratamento` text NOT NULL,
  `tratamento_quem` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tratamentos`
--

INSERT INTO `tratamentos` (`id`, `token_emp`, `tratamento`, `tratamento_quem`) VALUES
(5, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Pacote Haircupere SSA', 'Denis Ferraz'),
(6, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Pacote Haircupere Lauro', 'Denis Ferraz'),
(7, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Mesoterapia SSA', 'Denis Ferraz'),
(8, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Mesoterapia Lauro', 'Denis Ferraz'),
(9, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Microagulhamento SSA', 'Denis Ferraz'),
(10, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Microagulhamento Lauro', 'Denis Ferraz'),
(11, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Intradermo Presencial', 'Denis Ferraz'),
(12, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Analise de Exames e Prescrição', 'Denis Ferraz'),
(13, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Teleconsulta com Prescrição', 'Denis Ferraz'),
(14, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Fototerapia SSA', 'Denis Ferraz'),
(15, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Fototerapia Lauro', 'Denis Ferraz'),
(16, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Consulta Capilar SSA', 'Denis Ferraz'),
(17, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Consulta Capilar Lauro', 'Denis Ferraz'),
(18, 'd6b0ab7f1c8ab8f514db9a6d85de160a', 'Consulta Online', 'Denis Ferraz');

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
-- Indexes for table `atestados`
--
ALTER TABLE `atestados`
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
-- Indexes for table `contas`
--
ALTER TABLE `contas`
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
-- Indexes for table `custos_tratamentos`
--
ALTER TABLE `custos_tratamentos`
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
-- Indexes for table `grupos_contas`
--
ALTER TABLE `grupos_contas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lancamentos`
--
ALTER TABLE `lancamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lancamentos_recorrentes`
--
ALTER TABLE `lancamentos_recorrentes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Indexes for table `modelos_anamnese`
--
ALTER TABLE `modelos_anamnese`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modelos_prontuario`
--
ALTER TABLE `modelos_prontuario`
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
-- Indexes for table `perguntas_modelo_prontuario`
--
ALTER TABLE `perguntas_modelo_prontuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modelo_id` (`modelo_id`);

--
-- Indexes for table `receituarios`
--
ALTER TABLE `receituarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `respostas_anamnese`
--
ALTER TABLE `respostas_anamnese`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `respostas_prontuario`
--
ALTER TABLE `respostas_prontuario`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `tratamento`
--
ALTER TABLE `tratamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tratamentos`
--
ALTER TABLE `tratamentos`
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
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atestados`
--
ALTER TABLE `atestados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT for table `contas`
--
ALTER TABLE `contas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `custos`
--
ALTER TABLE `custos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `custos_tratamentos`
--
ALTER TABLE `custos_tratamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `disponibilidade`
--
ALTER TABLE `disponibilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `estoque_item`
--
ALTER TABLE `estoque_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `evolucoes`
--
ALTER TABLE `evolucoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grupos_contas`
--
ALTER TABLE `grupos_contas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `historico_atendimento`
--
ALTER TABLE `historico_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=857;

--
-- AUTO_INCREMENT for table `lancamentos`
--
ALTER TABLE `lancamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `lancamentos_atendimento`
--
ALTER TABLE `lancamentos_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `lancamentos_recorrentes`
--
ALTER TABLE `lancamentos_recorrentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `modelos_anamnese`
--
ALTER TABLE `modelos_anamnese`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `modelos_prontuario`
--
ALTER TABLE `modelos_prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `painel_users`
--
ALTER TABLE `painel_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `perguntas_modelo`
--
ALTER TABLE `perguntas_modelo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `perguntas_modelo_prontuario`
--
ALTER TABLE `perguntas_modelo_prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receituarios`
--
ALTER TABLE `receituarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `respostas_anamnese`
--
ALTER TABLE `respostas_anamnese`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `respostas_prontuario`
--
ALTER TABLE `respostas_prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tratamento`
--
ALTER TABLE `tratamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT for table `tratamentos`
--
ALTER TABLE `tratamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `perguntas_modelo`
--
ALTER TABLE `perguntas_modelo`
  ADD CONSTRAINT `perguntas_modelo_ibfk_1` FOREIGN KEY (`modelo_id`) REFERENCES `modelos_anamnese` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `perguntas_modelo_prontuario`
--
ALTER TABLE `perguntas_modelo_prontuario`
  ADD CONSTRAINT `perguntas_modelo_prontuario_ibfk_1` FOREIGN KEY (`modelo_id`) REFERENCES `modelos_prontuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
