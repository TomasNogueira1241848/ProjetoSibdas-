<?php

require_once __DIR__ . '/../../private/includes/funcoes.php';

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

if ($email === 'admin@medinfosolutions.pt' && $password === 'admin123') {
    $_SESSION['utilizador'] = [
        'nome' => 'Administrador',
        'email' => $email,
        'perfil' => 'Administrador'
    ];

    header('Location: ' . BASE_URL . '/private/area-reservada/index.php');
    exit;
}

$_SESSION['erros_login'] = [
    'Credenciais inválidas.'
];

$_SESSION['old_login'] = [
    'email' => $email
];

header('Location: ' . BASE_URL . '/public/login/login.php');
exit;