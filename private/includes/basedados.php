<?php

require_once __DIR__ . '/../../config/config.php';

/* Cria e devolve uma ligação PDO à base de dados */
function ligar_base_dados()
{
    try {
        $porta = defined('MYSQL_PORT') ? MYSQL_PORT : '10464';

        $dsn = 'mysql:host=' . MYSQL_HOST .
            ';port=' . $porta .
            ';dbname=' . MYSQL_DATABASE .
            ';charset=' . MYSQL_CHARSET;

        $opcoes = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, MYSQL_USERNAME, MYSQL_PASSWORD, $opcoes);
    } catch (PDOException $erro) {
        return null;
    }
}
