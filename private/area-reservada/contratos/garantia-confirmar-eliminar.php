<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
$idEncrypted = $_GET['id_garantia'] ?? null;
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
    $ligacao->exec("INSERT IGNORE INTO estados_garantia (nome) VALUES ('Cancelado')");
    $stmt = $ligacao->prepare("SELECT id FROM estados_garantia WHERE nome='Cancelado' LIMIT 1");
    $stmt->execute();
    $estado = $stmt->fetch();
    if (!$estado) {
        throw new RuntimeException('Estado Cancelado não encontrado.');
    }
    $stmt = $ligacao->prepare('UPDATE garantias SET estado_garantia_id=:estado WHERE id=:id');
    $stmt->execute([':estado' => (int)$estado->id, ':id' => (int)$id]);
    registar_evento_sistema('dados', 'garantias', 'cancelar', 'Garantia cancelada.', ['id' => (int) $id]);
    header('Location: contratos.php?garantia_cancelada=1');
    exit;
} catch (Throwable $e) {
    header('Location: contratos.php?erro=1');
    exit;
}
