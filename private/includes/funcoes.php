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
 
// Funcao: normalizar perfil.
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
 
// Funcao: perfil nome.
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
 
// Funcao: utilizador email.
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
 
// Funcao: utilizador nome.
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
 
// Funcao: permissoes por perfil.
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
        ],
 
        'logs' => [
            'ver' => ['admin']
        ]
    ];
}
 
// Funcao: tem permissao.
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
 
// Funcao auxiliar: xigir prmissao.
function exigir_permissao($modulo, $acao = 'ver')
{
    redirect_if_not_logged();
 
    if (!tem_permissao($modulo, $acao)) {
        $_SESSION['erros_permissao'] = ['Não tem permissão para aceder a esta funcionalidade.'];
        header('Location: ' . BASE_URL . '/private/area-reservada/index.php');
        exit;
    }
}
 
/* Alerta vermelho reutilizável, com o mesmo aspeto em todas as páginas */
function mostrar_alerta_erro($titulo, $mensagem)
{
    $mensagem = trim((string) $mensagem);
 
    if ($mensagem === '') {
        return;
    }
 
    $tituloSeguro = htmlspecialchars((string) $titulo, ENT_QUOTES, 'UTF-8');
    $mensagemSegura = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');
 
    echo '<div class="alert alert-danger d-flex align-items-start gap-2" role="alert">';
    echo '<i class="fa-solid fa-circle-exclamation mt-1"></i>';
    echo '<div>';
    echo '<strong class="d-block">' . $tituloSeguro . '</strong>';
    echo '<span>' . $mensagemSegura . '</span>';
    echo '</div>';
    echo '</div>';
}
 
/* Alerta padrão para falhas de ligação/consulta à base de dados */
function mostrar_alerta_erro_base_dados($mensagem)
{
    registar_evento_sistema('erro', 'base_dados', 'erro', (string) $mensagem);
    mostrar_alerta_erro('Erro na base de dados', $mensagem);
}
 
 
 
/* Registo de eventos relevantes do sistema na base de dados.
   A tabela logs fica com um registo sempre que existe login, logout, alteração de dados ou erro registável. */
function ligar_base_dados_logs()
{
    try {
        $porta = defined('MYSQL_PORT') ? MYSQL_PORT : '3306';
 
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
    } catch (Throwable $erro) {
        return null;
    }
}
 
// Funcao: garantir tabela logs.
function garantir_tabela_logs($ligacao)
{
    try {
        $ligacao->exec("
            CREATE TABLE IF NOT EXISTS logs (
                id INT NOT NULL AUTO_INCREMENT,
                tipo_evento VARCHAR(50) NOT NULL,
                descricao TEXT NOT NULL,
                agente_id INT NULL,
                ip VARCHAR(45) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                INDEX idx_logs_tipo_evento (tipo_evento),
                INDEX idx_logs_agente_id (agente_id),
                INDEX idx_logs_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    } catch (Throwable $erro) {
        // Se a tabela não puder ser criada, o registo do log é simplesmente ignorado.
    }
}
 
// Funcao: tipo evento log.
function tipo_evento_log($tipo, $modulo, $acao)
{
    $tipo = mb_strtolower((string) $tipo, 'UTF-8');
    $modulo = mb_strtolower((string) $modulo, 'UTF-8');
    $acao = mb_strtolower((string) $acao, 'UTF-8');
 
    if ($tipo === 'autenticacao' && $modulo === 'login' && $acao === 'sucesso') {
        return 'LOGIN_OK';
    }
 
    if ($tipo === 'autenticacao' && $modulo === 'login' && $acao === 'falha') {
        return 'LOGIN_FALHOU';
    }
 
    if ($tipo === 'autenticacao' && $modulo === 'login' && $acao === 'logout') {
        return 'LOGOUT';
    }
 
    if ($tipo === 'erro') {
        return 'ERRO';
    }
 
    if ($tipo === 'dados') {
        return 'DADOS_ALTERADOS';
    }
 
    return strtoupper(trim(($tipo !== '' ? $tipo : 'evento')));
}
 
// Funcao: descricao evento log.
function descricao_evento_log($modulo, $acao, $descricao, $extra = [])
{
    $descricao = trim((string) $descricao);
    $modulo = trim((string) $modulo);
    $acao = trim((string) $acao);
 
    if ($descricao === '') {
        $descricao = 'Evento registado no sistema.';
    }
 
    $partes = [];
 
    if ($modulo !== '') {
        $partes[] = 'Módulo: ' . $modulo;
    }
 
    if ($acao !== '') {
        $partes[] = 'Ação: ' . $acao;
    }
 
    if (!empty($extra) && is_array($extra)) {
        $extraLimpo = array_filter($extra, static function ($valor) {
            return $valor !== null && $valor !== '';
        });
 
        if (!empty($extraLimpo)) {
            $partes[] = 'Dados: ' . json_encode($extraLimpo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }
 
    if (!empty($partes)) {
        $descricao .= ' (' . implode(' | ', $partes) . ')';
    }
 
    return $descricao;
}
 
// Funcao: registar evento sistema.
function registar_evento_sistema($tipo, $modulo, $acao, $descricao, $extra = [])
{
    $ligacao = ligar_base_dados_logs();
 
    if ($ligacao === null) {
        return;
    }
 
    try {
        garantir_tabela_logs($ligacao);
 
        $tipoEvento = tipo_evento_log($tipo, $modulo, $acao);
        $descricaoEvento = descricao_evento_log($modulo, $acao, $descricao, $extra);
        $agenteId = null;
 
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['utilizador_id'])) {
            $agenteId = (int) $_SESSION['utilizador_id'];
        }
 
        $stmt = $ligacao->prepare("
            INSERT INTO logs (tipo_evento, descricao, agente_id, ip, created_at)
            VALUES (:tipo_evento, :descricao, :agente_id, :ip, NOW())
        ");
 
        $stmt->bindValue(':tipo_evento', $tipoEvento, PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $descricaoEvento, PDO::PARAM_STR);
 
        if ($agenteId === null) {
            $stmt->bindValue(':agente_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':agente_id', $agenteId, PDO::PARAM_INT);
        }
 
        $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR'] ?? null, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Throwable $erro) {
        // O log não deve interromper a aplicação.
    }
}