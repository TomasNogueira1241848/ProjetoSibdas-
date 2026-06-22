<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
$idEncrypted = $_GET['id_contrato'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : null;
if (!$id || !is_numeric($id)) {
    header('Location: contratos.php?erro=1');
    exit;
}
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    header('Location: contratos.php?erro=1');
    exit;
}
try {
    $stmt = $ligacao->prepare("SELECT id FROM estados_contrato WHERE nome='Cancelado' LIMIT 1");
    $stmt->execute();
    $estado = $stmt->fetch();
    if (!$estado) {
        throw new RuntimeException('Estado Cancelado não encontrado.');
    }
    $stmt = $ligacao->prepare('UPDATE contratos SET estado_contrato_id=:estado WHERE id=:id');
    $stmt->execute([':estado' => (int)$estado->id, ':id' => (int)$id]);
    header('Location: contratos.php?cancelado=1');
    exit;
} catch (Throwable $e) {
    header('Location: contratos.php?erro=1');
    exit;
}
