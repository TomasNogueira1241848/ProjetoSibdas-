<?php

require_once __DIR__ . '/../../config/config.php';

/* Inicia a sessão apenas se ainda não estiver iniciada */
function start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/* Verifica se existe utilizador autenticado */
function check_session()
{
    return isset($_SESSION['utilizador']);
}

/* Redireciona para o login se não existir sessão */
function redirect_if_not_logged($redirect_to = '/public/login/login.php')
{
    start_session();

    if (!check_session()) {
        header('Location: ' . BASE_URL . $redirect_to);
        exit;
    }
}

/* Termina a sessão e redireciona para o login */
function logout_and_redirect($redirect_to = '/public/login/login.php')
{
    start_session();

    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . $redirect_to);
    exit;
}

/* Encripta IDs para enviar por GET */
function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt(
        (string) $value,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    ));
}

/* Desencripta IDs recebidos por GET */
function aes_decrypt($value)
{
    if (!is_string($value) || strlen($value) % 2 !== 0) {
        return false;
    }

    $binario = hex2bin($value);

    if ($binario === false) {
        return false;
    }

    return openssl_decrypt(
        $binario,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
}
