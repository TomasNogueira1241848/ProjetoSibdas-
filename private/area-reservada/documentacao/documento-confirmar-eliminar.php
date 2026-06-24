<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
$idEncrypted = $_GET['id_documento'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : null;
if (!$id || !is_numeric($id)) {
    header('Location: documentacao.php?erro=1');
    exit;
}
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    header('Location: documentacao.php?erro=1');
    exit;
}
try {
    $ligacao->exec("INSERT IGNORE INTO estados_documento (nome) VALUES ('Inválido')");
    $stmt = $ligacao->prepare("SELECT id FROM estados_documento WHERE nome = 'Inválido' LIMIT 1");
    $stmt->execute();
    $estado = $stmt->fetch();
    if (!$estado) {
        throw new RuntimeException('Estado Inválido não encontrado.');
    }
    $stmt = $ligacao->prepare('UPDATE documentos SET estado_documento_id = :estado WHERE id = :id');
    $stmt->execute([':estado' => (int)$estado->id, ':id' => (int)$id]);
    registar_evento_sistema('dados', 'documentacao', 'invalidar', 'Documento invalidado.', ['id' => (int) $id]);
    header('Location: documentacao.php?eliminado=1');
    exit;
} catch (Throwable $e) {
    header('Location: documentacao.php?erro=1');
    exit;
}
