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
    $_SESSION['old_login'] = ['email' => $email];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    registar_evento_sistema('erro', 'autenticacao', 'ligacao_bd', 'Falha ao ligar à base de dados durante o login.', ['email' => $email]);
    $_SESSION['erros_login'] = ['Não foi possível ligar à base de dados.'];
    $_SESSION['old_login'] = ['email' => $email];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}

try {
    /*
     * Ficha 14:
     * - o email do agente está guardado na coluna name com AES_ENCRYPT
     * - no login é comparado usando AES_DECRYPT
     *
     * Nota técnica:
     * como a ligação PDO usa ATTR_EMULATE_PREPARES = false,
     * não se deve reutilizar o mesmo placeholder (:chave) duas vezes.
     * Por isso usamos :chave_select e :chave_where.
     */
    $comando = $ligacao->prepare("
        SELECT
            id,
            nome,
            CAST(AES_DECRYPT(name, :chave_select) AS CHAR(255)) AS email,
            passwrd,
            profile,
            last_login
        FROM agents
        WHERE CAST(AES_DECRYPT(name, :chave_where) AS CHAR(255)) = :email
          AND (deleted_at IS NULL)
        LIMIT 1
    ");

    $comando->execute([
        ':chave_select' => MYSQL_AES_KEY,
        ':chave_where' => MYSQL_AES_KEY,
        ':email' => $email
    ]);

    $agente = $comando->fetch();

    if (!$agente || $password !== $agente->passwrd) {
        registar_evento_sistema('autenticacao', 'login', 'falha', 'Tentativa de login com credenciais inválidas.', ['email' => $email]);
        $_SESSION['erros_login'] = ['Credenciais inválidas.'];
        $_SESSION['old_login'] = ['email' => $email];

        header('Location: ' . BASE_URL . '/public/login/login.php');
        exit;
    }

    $atualizarLogin = $ligacao->prepare('UPDATE agents SET last_login = NOW() WHERE id = :id');
    $atualizarLogin->bindValue(':id', $agente->id, PDO::PARAM_INT);
    $atualizarLogin->execute();

    $_SESSION['utilizador'] = $agente->email;
    $_SESSION['utilizador_id'] = $agente->id;
    $_SESSION['utilizador_nome'] = $agente->nome;
    $_SESSION['utilizador_email'] = $agente->email;
    $_SESSION['profile'] = normalizar_perfil($agente->profile);

    registar_evento_sistema('autenticacao', 'login', 'sucesso', 'Login efetuado com sucesso.', ['email' => $agente->email, 'perfil' => $_SESSION['profile']]);

    $ligacao = null;

    header('Location: ' . BASE_URL . '/private/area-reservada/index.php');
    exit;
} catch (PDOException $erro) {
    $ligacao = null;

    registar_evento_sistema('erro', 'autenticacao', 'erro_login', 'Erro ao validar o login na base de dados.', ['email' => $email, 'erro' => $erro->getMessage()]);
    $_SESSION['erros_login'] = ['Ocorreu um erro ao validar o login na base de dados.'];
    $_SESSION['old_login'] = ['email' => $email];

    header('Location: ' . BASE_URL . '/public/login/login.php');
    exit;
}
