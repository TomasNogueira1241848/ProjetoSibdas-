<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: equipamentos.php');
    exit;
}

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$equipamentoId = null;

if ($idEquipamentoEncrypted !== null) {
    $idDesencriptado = aes_decrypt($idEquipamentoEncrypted);
    $equipamentoId = ($idDesencriptado !== false && is_numeric($idDesencriptado)) ? (int) $idDesencriptado : null;
}

if (!$equipamentoId) {
    header('Location: equipamentos.php?erro_eliminar=1');
    exit;
}

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    header('Location: equipamentos.php?erro_eliminar=1');
    exit;
}

try {
    $stmt = $ligacao->prepare('
        SELECT e.id, ee.nome AS estado
        FROM equipamentos e
        INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
        WHERE e.id = :id
        LIMIT 1
    ');
    $stmt->execute([':id' => $equipamentoId]);
    $equipamento = $stmt->fetch();

    if (!$equipamento) {
        header('Location: equipamentos.php?erro_eliminar=1');
        exit;
    }

    if (strtolower((string) ($equipamento->estado ?? '')) === 'abatido') {
        header('Location: equipamentos.php?abatido=1');
        exit;
    }

    $stmtEstado = $ligacao->prepare('SELECT id FROM estados_equipamento WHERE nome = :nome LIMIT 1');
    $stmtEstado->execute([':nome' => 'Abatido']);
    $estadoAbatido = $stmtEstado->fetch();

    if (!$estadoAbatido) {
        header('Location: equipamentos.php?erro_eliminar=1');
        exit;
    }

    $stmtUpdate = $ligacao->prepare('UPDATE equipamentos SET estado_id = :estado_id WHERE id = :id');
    $stmtUpdate->execute([
        ':estado_id' => (int) $estadoAbatido->id,
        ':id' => $equipamentoId
    ]);

    header('Location: equipamentos.php?abatido=1');
    exit;
} catch (PDOException $erro) {
    header('Location: equipamentos.php?erro_eliminar=1');
    exit;
}
