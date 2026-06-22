<?php
$pageTitle = 'MedInfo Solutions — Eliminar Documento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'documentacao';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
$idEncrypted = $_GET['id_documento'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$id || !is_numeric($id)) {
    header('Location: documentacao.php');
    exit;
}
$id = (int)$id;
$idEncrypted = aes_encrypt($id);
$erroBD = '';
$documento = null;
$bloqueado = false;
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('SELECT d.*, td.nome AS tipo, ed.nome AS estado, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, ee.nome AS equipamento_estado, f.nome AS fornecedor_nome, f.estado AS fornecedor_estado, el.nome AS localizacao_estado FROM documentos d INNER JOIN tipos_documento td ON td.id=d.tipo_documento_id INNER JOIN estados_documento ed ON ed.id=d.estado_documento_id INNER JOIN equipamentos e ON e.id=d.equipamento_id INNER JOIN estados_equipamento ee ON ee.id=e.estado_id INNER JOIN localizacoes l ON l.id=e.localizacao_id INNER JOIN estados_localizacao el ON el.id=l.estado_localizacao_id LEFT JOIN fornecedores f ON f.id=d.fornecedor_id WHERE d.id=:id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $documento = $stmt->fetch();
        if (!$documento) {
            header('Location: documentacao.php');
            exit;
        }
        $bloqueado = mb_strtolower((string)$documento->equipamento_estado) === 'abatido' || in_array(mb_strtolower((string)$documento->estado), ['inválido', 'invalido'], true);
    } catch (PDOException $e) {
        $erroBD = 'Erro ao carregar documento: ' . $e->getMessage();
    }
}
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Eliminar documento</h4>
                    <p class="text-muted small mb-0">Confirmação antes de remover/substituir o documento.</p>
                </div>
            </div><?php if ($erroBD): ?><div class="alert alert-danger"><?php echo e($erroBD); ?></div><?php elseif ($documento): ?><div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card p-4 border-danger">
                            <h5 class="fw-bold mb-2">Tem a certeza que pretende eliminar este documento?</h5>
                            <p class="text-muted">O registo será marcado como <strong>Inválido</strong>, mantendo o histórico.</p><?php if ($bloqueado): ?><div class="alert alert-warning">Este documento já está inválido ou está associado a um equipamento abatido. Por segurança, a ação fica bloqueada.</div><?php endif; ?><div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="fw-bold mb-0"><?php echo e($documento->codigo); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><?php echo e($documento->estado); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Título</p>
                                    <p class="mb-0"><?php echo e($documento->titulo); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Tipo</p>
                                    <p class="mb-0"><?php echo e($documento->tipo); ?></p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted small mb-1">Equipamento</p>
                                    <p class="mb-0"><?php echo e($documento->equipamento_codigo . ' — ' . $documento->equipamento_designacao); ?></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2"><a href="documentacao.php" class="btn btn-outline-secondary">Cancelar</a><?php if (!$bloqueado): ?><a href="documento-confirmar-eliminar.php?id_documento=<?php echo urlencode($idEncrypted); ?>" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i> Sim, eliminar</a><?php endif; ?></div>
                        </div>
                    </div>
                </div><?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>