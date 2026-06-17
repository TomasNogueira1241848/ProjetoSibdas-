<?php

require_once __DIR__ . '/../../config/config.php';

/* Cria e devolve uma ligação PDO à base de dados */
function ligar_base_dados()
{
    try {
        $dsn = 'mysql:host=' . MYSQL_HOST .
            ';dbname=' . MYSQL_DATABASE .
            ';charset=' . MYSQL_CHARSET;

        $ligacao = new PDO($dsn, MYSQL_USERNAME, MYSQL_PASSWORD);

        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ligacao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $ligacao;
    } catch (PDOException $erro) {
        return null;
    }
}