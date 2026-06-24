<?php

define('APP_NAME', 'MedInfo Solutions');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', 'MedInfo Solutions © 2026 — Todos os direitos reservados');
define('APP_SUBTITLE', 'Gestão de Inventário Hospitalar');

define('BASE_URL', 'http://127.0.0.1/sibdas/1241848/medinfo-solutions');

define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('MYSQL_PORT', '10464');
define('MYSQL_DATABASE', 'db1241848');
define('MYSQL_USERNAME', '1241848');
define('MYSQL_PASSWORD', 'nogueira_848');
define('MYSQL_CHARSET', 'utf8mb4');

/* Segurança - Encriptação com OpenSSL */
define('OPENSSL_METHOD', 'AES-256-CBC');
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa');
define('OPENSSL_IV', 'BzKAbjuREsHgnw56');

/* Chave AES MySQL para emails dos agentes, conforme Ficha 14 */
define('MYSQL_AES_KEY', 'medinfo_agents_chave_local_2026');
