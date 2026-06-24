<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();

$idEncrypted = $_GET['id_fornecedor'] ?? null;
$idFornecedor = $idEncrypted ? aes_decrypt($idEncrypted) : null;
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: fornecedores.php?erro_descontinuar=1');
    exit;
}

$ligacao = ligar_base_dados();
if ($ligacao === null) {
    header('Location: fornecedores.php?erro_descontinuar=1');
    exit;
}

try {
    $stmt = $ligacao->prepare("UPDATE fornecedores SET estado = 'Descontinuado', contrato_ativo = 0 WHERE id = :id");
    $stmt->execute([':id' => (int) $idFornecedor]);
    registar_evento_sistema('dados', 'fornecedores', 'descontinuar', 'Fornecedor descontinuado.', ['id' => (int) $idFornecedor]);
    header('Location: fornecedores.php?descontinuado=1');
    exit;
} catch (PDOException $erro) {
    header('Location: fornecedores.php?erro_descontinuar=1');
    exit;
}
