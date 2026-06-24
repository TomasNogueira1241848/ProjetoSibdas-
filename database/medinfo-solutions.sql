-- ========================================================
-- MedInfo Solutions - estrutura da base de dados
-- Gerado a partir de BASEDADOSFINAL(1).sql
-- ========================================================

-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar estrutura da base de dados para db1241848
CREATE DATABASE IF NOT EXISTS `db1241848` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db1241848`;

-- A despejar estrutura para tabela db1241848.agents
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varbinary(255) NOT NULL,
  `passwrd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.areas_documento
CREATE TABLE IF NOT EXISTS `areas_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.categorias_equipamento
CREATE TABLE IF NOT EXISTS `categorias_equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.conteudos_publicos
CREATE TABLE IF NOT EXISTS `conteudos_publicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hero_titulo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hero_subtitulo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sobre_titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sobre_texto_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sobre_texto_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sobre_texto_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `servicos_titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `servicos_texto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientes_titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientes_texto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactos_titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactos_texto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `morada` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rodape_texto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `atualizado_por` int DEFAULT NULL,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.contratos
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designacao` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_contrato_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `responsavel` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `valor_anual` decimal(10,2) DEFAULT NULL,
  `periodicidade` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `renovacao_automatica` tinyint(1) DEFAULT '0',
  `estado_contrato_id` int NOT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_contratos_tipo` (`tipo_contrato_id`),
  KEY `fk_contratos_fornecedor` (`fornecedor_id`),
  KEY `fk_contratos_estado` (`estado_contrato_id`),
  CONSTRAINT `fk_contratos_estado` FOREIGN KEY (`estado_contrato_id`) REFERENCES `estados_contrato` (`id`),
  CONSTRAINT `fk_contratos_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_contratos_tipo` FOREIGN KEY (`tipo_contrato_id`) REFERENCES `tipos_contrato` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7021 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.contrato_equipamentos
CREATE TABLE IF NOT EXISTS `contrato_equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contrato_id` int NOT NULL,
  `equipamento_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contrato_id` (`contrato_id`,`equipamento_id`),
  KEY `fk_conteq_equipamento` (`equipamento_id`),
  CONSTRAINT `fk_conteq_contrato` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_conteq_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5024 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.contrato_ficheiros
CREATE TABLE IF NOT EXISTS `contrato_ficheiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contrato_id` int NOT NULL,
  `ficheiro_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contrato_id` (`contrato_id`,`ficheiro_id`),
  KEY `fk_contfich_ficheiro` (`ficheiro_id`),
  CONSTRAINT `fk_contfich_contrato` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_contfich_ficheiro` FOREIGN KEY (`ficheiro_id`) REFERENCES `ficheiros_pdf` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6021 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.criticidades
CREATE TABLE IF NOT EXISTS `criticidades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.documentos
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento_id` int NOT NULL,
  `area_documento_id` int NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `responsavel` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manutencao_id` int DEFAULT NULL,
  `data_documento` date DEFAULT NULL,
  `validade` date DEFAULT NULL,
  `estado_documento_id` int NOT NULL,
  `obrigatorio` tinyint(1) DEFAULT '0',
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_documentos_tipo` (`tipo_documento_id`),
  KEY `fk_documentos_area` (`area_documento_id`),
  KEY `fk_documentos_equipamento` (`equipamento_id`),
  KEY `fk_documentos_fornecedor` (`fornecedor_id`),
  KEY `fk_documentos_manutencao` (`manutencao_id`),
  KEY `fk_documentos_estado` (`estado_documento_id`),
  CONSTRAINT `fk_documentos_area` FOREIGN KEY (`area_documento_id`) REFERENCES `areas_documento` (`id`),
  CONSTRAINT `fk_documentos_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_documentos_estado` FOREIGN KEY (`estado_documento_id`) REFERENCES `estados_documento` (`id`),
  CONSTRAINT `fk_documentos_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_documentos_manutencao` FOREIGN KEY (`manutencao_id`) REFERENCES `manutencoes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_documentos_tipo` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3041 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.documento_ficheiros
CREATE TABLE IF NOT EXISTS `documento_ficheiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `documento_id` int NOT NULL,
  `ficheiro_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_id` (`documento_id`,`ficheiro_id`),
  KEY `fk_docfich_ficheiro` (`ficheiro_id`),
  CONSTRAINT `fk_docfich_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_docfich_ficheiro` FOREIGN KEY (`ficheiro_id`) REFERENCES `ficheiros_pdf` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4041 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designacao` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` int NOT NULL,
  `marca` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_serie` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `ano_fabrico` int DEFAULT NULL,
  `custo_aquisicao` decimal(10,2) DEFAULT NULL,
  `tipo_entrada_id` int DEFAULT NULL,
  `estado_id` int NOT NULL,
  `criticidade_id` int NOT NULL,
  `fornecedor_principal_id` int NOT NULL,
  `localizacao_id` int NOT NULL,
  `servico` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piso` int DEFAULT NULL,
  `sala` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `equipamento_pai_id` int DEFAULT NULL,
  `tem_consumiveis` tinyint(1) DEFAULT '0',
  `consumiveis_descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `uq_equipamento_serie` (`marca`,`modelo`,`numero_serie`),
  KEY `fk_equipamentos_categoria` (`categoria_id`),
  KEY `fk_equipamentos_estado` (`estado_id`),
  KEY `fk_equipamentos_criticidade` (`criticidade_id`),
  KEY `fk_equipamentos_tipo_entrada` (`tipo_entrada_id`),
  KEY `fk_equipamentos_fornecedor` (`fornecedor_principal_id`),
  KEY `fk_equipamentos_localizacao` (`localizacao_id`),
  KEY `fk_equipamentos_pai` (`equipamento_pai_id`),
  CONSTRAINT `fk_equipamentos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_equipamento` (`id`),
  CONSTRAINT `fk_equipamentos_criticidade` FOREIGN KEY (`criticidade_id`) REFERENCES `criticidades` (`id`),
  CONSTRAINT `fk_equipamentos_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados_equipamento` (`id`),
  CONSTRAINT `fk_equipamentos_fornecedor` FOREIGN KEY (`fornecedor_principal_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_equipamentos_localizacao` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`),
  CONSTRAINT `fk_equipamentos_pai` FOREIGN KEY (`equipamento_pai_id`) REFERENCES `equipamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_equipamentos_tipo_entrada` FOREIGN KEY (`tipo_entrada_id`) REFERENCES `tipos_entrada` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1021 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.equipamento_fornecedores
CREATE TABLE IF NOT EXISTS `equipamento_fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `funcao_fornecedor_id` int NOT NULL,
  `observacoes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipamento_id` (`equipamento_id`,`fornecedor_id`,`funcao_fornecedor_id`),
  KEY `fk_eqforn_fornecedor` (`fornecedor_id`),
  KEY `fk_eqforn_funcao` (`funcao_fornecedor_id`),
  CONSTRAINT `fk_eqforn_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_eqforn_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `fk_eqforn_funcao` FOREIGN KEY (`funcao_fornecedor_id`) REFERENCES `funcoes_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2073 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_contrato
CREATE TABLE IF NOT EXISTS `estados_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_documento
CREATE TABLE IF NOT EXISTS `estados_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_equipamento
CREATE TABLE IF NOT EXISTS `estados_equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_garantia
CREATE TABLE IF NOT EXISTS `estados_garantia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_localizacao
CREATE TABLE IF NOT EXISTS `estados_localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.estados_manutencao
CREATE TABLE IF NOT EXISTS `estados_manutencao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.ficheiros_pdf
CREATE TABLE IF NOT EXISTS `ficheiros_pdf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_original` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_guardado` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caminho_ficheiro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_mime` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'application/pdf',
  `tamanho_bytes` int DEFAULT NULL,
  `carregado_por` int DEFAULT NULL,
  `carregado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ficheiros_agent` (`carregado_por`),
  CONSTRAINT `fk_ficheiros_agent` FOREIGN KEY (`carregado_por`) REFERENCES `agents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9083 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.fornecedores
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_fornecedor_id` int NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrato_ativo` tinyint(1) DEFAULT '0',
  `area_atuacao` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pessoa_contacto` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_contacto` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ativo',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nif` (`nif`),
  KEY `fk_fornecedores_tipo` (`tipo_fornecedor_id`),
  CONSTRAINT `fk_fornecedores_tipo` FOREIGN KEY (`tipo_fornecedor_id`) REFERENCES `tipos_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.funcoes_fornecedor
CREATE TABLE IF NOT EXISTS `funcoes_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.garantias
CREATE TABLE IF NOT EXISTS `garantias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designacao` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `responsavel` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contrato_id` int DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `estado_garantia_id` int NOT NULL,
  `cobertura` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_garantias_equipamento` (`equipamento_id`),
  KEY `fk_garantias_fornecedor` (`fornecedor_id`),
  KEY `fk_garantias_contrato` (`contrato_id`),
  KEY `fk_garantias_estado` (`estado_garantia_id`),
  CONSTRAINT `fk_garantias_contrato` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_garantias_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_garantias_estado` FOREIGN KEY (`estado_garantia_id`) REFERENCES `estados_garantia` (`id`),
  CONSTRAINT `fk_garantias_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7521 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.garantia_ficheiros
CREATE TABLE IF NOT EXISTS `garantia_ficheiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `garantia_id` int NOT NULL,
  `ficheiro_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `garantia_id` (`garantia_id`,`ficheiro_id`),
  KEY `fk_garfich_ficheiro` (`ficheiro_id`),
  CONSTRAINT `fk_garfich_ficheiro` FOREIGN KEY (`ficheiro_id`) REFERENCES `ficheiros_pdf` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_garfich_garantia` FOREIGN KEY (`garantia_id`) REFERENCES `garantias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6523 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.localizacoes
CREATE TABLE IF NOT EXISTS `localizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_localizacao_id` int NOT NULL,
  `numero_andares` int NOT NULL DEFAULT '1',
  `edificio` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piso_principal` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsavel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado_localizacao_id` int NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `fk_localizacoes_tipo` (`tipo_localizacao_id`),
  KEY `fk_localizacoes_estado` (`estado_localizacao_id`),
  CONSTRAINT `fk_localizacoes_estado` FOREIGN KEY (`estado_localizacao_id`) REFERENCES `estados_localizacao` (`id`),
  CONSTRAINT `fk_localizacoes_tipo` FOREIGN KEY (`tipo_localizacao_id`) REFERENCES `tipos_localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_evento` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `agente_id` int DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_tipo_evento` (`tipo_evento`),
  KEY `idx_logs_agente_id` (`agente_id`),
  KEY `idx_logs_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.manutencoes
CREATE TABLE IF NOT EXISTS `manutencoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `tipo_manutencao_id` int NOT NULL,
  `ultima_manutencao` date DEFAULT NULL,
  `proxima_manutencao` date DEFAULT NULL,
  `periodicidade` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_manutencao_id` int NOT NULL,
  `prioridade_id` int NOT NULL,
  `responsavel` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_manutencoes_equipamento` (`equipamento_id`),
  KEY `fk_manutencoes_tipo` (`tipo_manutencao_id`),
  KEY `fk_manutencoes_estado` (`estado_manutencao_id`),
  KEY `fk_manutencoes_prioridade` (`prioridade_id`),
  CONSTRAINT `fk_manutencoes_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_manutencoes_estado` FOREIGN KEY (`estado_manutencao_id`) REFERENCES `estados_manutencao` (`id`),
  CONSTRAINT `fk_manutencoes_prioridade` FOREIGN KEY (`prioridade_id`) REFERENCES `prioridades_manutencao` (`id`),
  CONSTRAINT `fk_manutencoes_tipo` FOREIGN KEY (`tipo_manutencao_id`) REFERENCES `tipos_manutencao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8021 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.mensagens_contacto
CREATE TABLE IF NOT EXISTS `mensagens_contacto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instituicao` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assunto` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensagem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Nova',
  `recebido_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.prioridades_manutencao
CREATE TABLE IF NOT EXISTS `prioridades_manutencao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_contrato
CREATE TABLE IF NOT EXISTS `tipos_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_documento
CREATE TABLE IF NOT EXISTS `tipos_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_entrada
CREATE TABLE IF NOT EXISTS `tipos_entrada` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_fornecedor
CREATE TABLE IF NOT EXISTS `tipos_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_localizacao
CREATE TABLE IF NOT EXISTS `tipos_localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar estrutura para tabela db1241848.tipos_manutencao
CREATE TABLE IF NOT EXISTS `tipos_manutencao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
