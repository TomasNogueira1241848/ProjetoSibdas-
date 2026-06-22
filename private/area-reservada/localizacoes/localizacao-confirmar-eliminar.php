<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();

$idEncrypted = $_GET['id_localizacao'] ?? null;
$idLocalizacao = $idEncrypted ? aes_decrypt($idEncrypted) : null;
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: localizacoes.php?erro_abate=1');
    exit;
}

$ligacao = ligar_base_dados();
if ($ligacao === null) {
    header('Location: localizacoes.php?erro_abate=1');
    exit;
}

try {
    $stmt = $ligacao->prepare("SELECT id FROM estados_localizacao WHERE nome = 'Abatida' LIMIT 1");
    $stmt->execute();
    $estado = $stmt->fetch();
    if (!$estado) {
        $stmt = $ligacao->prepare("SELECT id FROM estados_localizacao WHERE nome = 'Inativa' LIMIT 1");
        $stmt->execute();
        $estado = $stmt->fetch();
    }
    if (!$estado) {
        throw new RuntimeException('Estado de localização não encontrado.');
    }
    $stmt = $ligacao->prepare('UPDATE localizacoes SET estado_localizacao_id = :estado WHERE id = :id');
    $stmt->execute([':estado' => (int) $estado->id, ':id' => (int) $idLocalizacao]);
    header('Location: localizacoes.php?abatida=1');
    exit;
} catch (Throwable $erro) {
    header('Location: localizacoes.php?erro_abate=1');
    exit;
}
