<?php

require_once __DIR__ . '/../../private/includes/funcoes.php';
require_once __DIR__ . '/../../private/includes/basedados.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}

$email = isset($_POST['text_username']) ? trim($_POST['text_username']) : '';
$password = isset($_POST['text_password']) ? trim($_POST['text_password']) : '';

$erros = [];

if ($email === '') {
    $erros[] = 'Introduza o email.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'Introduza um email válido.';
}

if ($password === '') {
    $erros[] = 'Introduza a password.';
}

if (!empty($erros)) {
    $_SESSION['erros_login'] = $erros;
    $_SESSION['old_login'] = [
        'email' => $email
    ];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $_SESSION['erros_login'] = [
        'Não foi possível ligar à base de dados.'
    ];

    $_SESSION['old_login'] = [
        'email' => $email
    ];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}

try {
    $sql = '
        SELECT 
            u.id,
            u.nome,
            u.email,
            u.password_hash,
            u.estado,
            p.nome AS perfil
        FROM utilizadores u
        INNER JOIN perfis_utilizador p ON p.id = u.perfil_id
        WHERE u.email = :email
        LIMIT 1
    ';

    $consulta = $ligacao->prepare($sql);
    $consulta->bindValue(':email', $email);
    $consulta->execute();

    $utilizador = $consulta->fetch();

    if (!$utilizador || $utilizador->estado !== 'Ativo' || !password_verify($password, $utilizador->password_hash)) {
        $_SESSION['erros_login'] = [
            'Credenciais inválidas.'
        ];

        $_SESSION['old_login'] = [
            'email' => $email
        ];

        header('Location: ' . BASE_URL . '/public/login/login.php');
        exit;
    }

    $_SESSION['utilizador'] = [
        'id' => $utilizador->id,
        'nome' => $utilizador->nome,
        'email' => $utilizador->email,
        'perfil' => $utilizador->perfil
    ];

    $atualizarLogin = $ligacao->prepare('
        UPDATE utilizadores
        SET ultimo_login = NOW()
        WHERE id = :id
    ');

    $atualizarLogin->bindValue(':id', $utilizador->id);
    $atualizarLogin->execute();

    $ligacao = null;

    header('Location: ' . BASE_URL . '/private/area-reservada/index.php');
    exit;
} catch (PDOException $erro) {
    $ligacao = null;

    $_SESSION['erros_login'] = [
        'Ocorreu um erro ao validar o login.'
    ];

    $_SESSION['old_login'] = [
        'email' => $email
    ];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}