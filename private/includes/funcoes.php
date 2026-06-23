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

/* Perfil autenticado, guardado no login segundo a Ficha 14 */
function perfil_atual()
{
    start_session();

    if (!empty($_SESSION['profile'])) {
        return $_SESSION['profile'];
    }

    if (!empty($_SESSION['utilizador']['profile'])) {
        return $_SESSION['utilizador']['profile'];
    }

    if (!empty($_SESSION['utilizador']['perfil'])) {
        return normalizar_perfil($_SESSION['utilizador']['perfil']);
    }

    return '';
}

function normalizar_perfil($perfil)
{
    $perfil = trim((string) $perfil);
    $perfilLower = mb_strtolower($perfil, 'UTF-8');

    $mapa = [
        'administrador' => 'admin',
        'admin' => 'admin',
        'técnico' => 'tecnico',
        'tecnico' => 'tecnico',
        'profissional de saúde' => 'profissional_saude',
        'profissional de saude' => 'profissional_saude',
        'profissional_saude' => 'profissional_saude',
        'gestor de logística' => 'gestor_logistica',
        'gestor de logistica' => 'gestor_logistica',
        'gestor_logistica' => 'gestor_logistica'
    ];

    return $mapa[$perfilLower] ?? $perfilLower;
}

function perfil_nome($perfil = null)
{
    $perfil = $perfil ?? perfil_atual();

    $nomes = [
        'admin' => 'Administrador',
        'tecnico' => 'Técnico',
        'profissional_saude' => 'Profissional de saúde',
        'gestor_logistica' => 'Gestor de logística'
    ];

    return $nomes[$perfil] ?? 'Utilizador';
}

function utilizador_email()
{
    start_session();

    if (!empty($_SESSION['utilizador_email'])) {
        return $_SESSION['utilizador_email'];
    }

    if (!empty($_SESSION['utilizador']['email'])) {
        return $_SESSION['utilizador']['email'];
    }

    if (is_string($_SESSION['utilizador'] ?? null)) {
        return $_SESSION['utilizador'];
    }

    return 'Utilizador';
}

function utilizador_nome()
{
    start_session();

    if (!empty($_SESSION['utilizador_nome'])) {
        return $_SESSION['utilizador_nome'];
    }

    if (!empty($_SESSION['utilizador']['nome'])) {
        return $_SESSION['utilizador']['nome'];
    }

    return utilizador_email();
}

function permissoes_por_perfil()
{
    $todos = ['admin', 'tecnico', 'profissional_saude', 'gestor_logistica'];
    $operacionaisComEdicaoTecnica = ['admin', 'tecnico'];
    $operacionaisComEdicaoLogistica = ['admin', 'tecnico', 'gestor_logistica'];

    return [
        'dashboard' => [
            'ver' => $todos
        ],

        'equipamentos' => [
            'ver' => $todos,
            'criar' => $operacionaisComEdicaoTecnica,
            'editar' => $operacionaisComEdicaoTecnica,
            'remover' => $operacionaisComEdicaoTecnica
        ],

        'fornecedores' => [
            'ver' => $todos,
            'criar' => $operacionaisComEdicaoLogistica,
            'editar' => $operacionaisComEdicaoLogistica,
            'remover' => $operacionaisComEdicaoLogistica
        ],

        'localizacoes' => [
            'ver' => $todos,
            'criar' => $operacionaisComEdicaoLogistica,
            'editar' => $operacionaisComEdicaoLogistica,
            'remover' => $operacionaisComEdicaoLogistica
        ],

        'documentacao' => [
            'ver' => $todos,
            'criar' => $operacionaisComEdicaoLogistica,
            'editar' => $operacionaisComEdicaoLogistica,
            'remover' => $operacionaisComEdicaoLogistica
        ],

        'contratos' => [
            'ver' => $todos,
            'criar' => $operacionaisComEdicaoLogistica,
            'editar' => $operacionaisComEdicaoLogistica,
            'remover' => $operacionaisComEdicaoLogistica
        ],

        'conteudos' => [
            'ver' => ['admin'],
            'editar' => ['admin']
        ]
    ];
}

function tem_permissao($modulo, $acao = 'ver')
{
    $perfil = perfil_atual();
    $permissoes = permissoes_por_perfil();

    if ($perfil === 'admin') {
        return true;
    }

    if (!isset($permissoes[$modulo][$acao])) {
        return false;
    }

    return in_array($perfil, $permissoes[$modulo][$acao], true);
}

function exigir_permissao($modulo, $acao = 'ver')
{
    redirect_if_not_logged();

    if (!tem_permissao($modulo, $acao)) {
        $_SESSION['erros_permissao'] = ['Não tem permissão para aceder a esta funcionalidade.'];
        header('Location: ' . BASE_URL . '/private/area-reservada/index.php');
        exit;
    }
}
