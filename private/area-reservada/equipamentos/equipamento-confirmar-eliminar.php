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

    $ligacao->beginTransaction();

    $stmtUpdate = $ligacao->prepare('UPDATE equipamentos SET estado_id = :estado_id WHERE id = :id');
    $stmtUpdate->execute([
        ':estado_id' => (int) $estadoAbatido->id,
        ':id' => $equipamentoId
    ]);

    $ligacao->exec("INSERT IGNORE INTO estados_documento (nome) VALUES ('Inválido')");
    $stmtEstadoDocumento = $ligacao->prepare("SELECT id FROM estados_documento WHERE nome = 'Inválido' LIMIT 1");
    $stmtEstadoDocumento->execute();
    $estadoDocumentoInvalido = $stmtEstadoDocumento->fetch();

    if ($estadoDocumentoInvalido) {
        $stmtDocs = $ligacao->prepare('UPDATE documentos SET estado_documento_id = :estado WHERE equipamento_id = :equipamento_id');
        $stmtDocs->execute([
            ':estado' => (int) $estadoDocumentoInvalido->id,
            ':equipamento_id' => $equipamentoId
        ]);
    }

    $ligacao->exec("INSERT IGNORE INTO estados_garantia (nome) VALUES ('Cancelado')");
    $stmtEstadoGarantia = $ligacao->prepare("SELECT id FROM estados_garantia WHERE nome = 'Cancelado' LIMIT 1");
    $stmtEstadoGarantia->execute();
    $estadoGarantiaCancelado = $stmtEstadoGarantia->fetch();

    if ($estadoGarantiaCancelado) {
        $stmtGarantias = $ligacao->prepare('UPDATE garantias SET estado_garantia_id = :estado WHERE equipamento_id = :equipamento_id');
        $stmtGarantias->execute([
            ':estado' => (int) $estadoGarantiaCancelado->id,
            ':equipamento_id' => $equipamentoId
        ]);
    }

    $ligacao->exec("INSERT IGNORE INTO estados_contrato (nome) VALUES ('Cancelado')");
    $stmtEstadoContrato = $ligacao->prepare("SELECT id FROM estados_contrato WHERE nome = 'Cancelado' LIMIT 1");
    $stmtEstadoContrato->execute();
    $estadoContratoCancelado = $stmtEstadoContrato->fetch();

    if ($estadoContratoCancelado) {
        $stmtContratos = $ligacao->prepare('
            UPDATE contratos c
            INNER JOIN contrato_equipamentos ce ON ce.contrato_id = c.id
            SET c.estado_contrato_id = :estado
            WHERE ce.equipamento_id = :equipamento_id
        ');
        $stmtContratos->execute([
            ':estado' => (int) $estadoContratoCancelado->id,
            ':equipamento_id' => $equipamentoId
        ]);
    }

    $ligacao->commit();

    header('Location: equipamentos.php?abatido=1');
    exit;
} catch (Throwable $erro) {
    if (isset($ligacao) && $ligacao instanceof PDO && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }
    header('Location: equipamentos.php?erro_eliminar=1');
    exit;
}
