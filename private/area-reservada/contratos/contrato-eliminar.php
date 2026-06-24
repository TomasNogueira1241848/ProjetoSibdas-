<?php
$pageTitle = 'MedInfo Solutions — Cancelar Contrato';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'contratos';
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
function e($v)
{
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
$idEncrypted = $_GET['id_contrato'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$id || !is_numeric($id)) {
    header('Location: contratos.php');
    exit;
}
$id = (int)$id;
$idEncrypted = aes_encrypt($id);
$erroBD = '';
$contrato = null;
$bloqueado = false;
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare("SELECT c.*, tc.nome AS tipo, CASE WHEN ec.nome IN ('Inválido','Invalido') THEN 'Cancelado' ELSE ec.nome END AS estado, f.nome AS fornecedor, f.estado AS fornecedor_estado FROM contratos c INNER JOIN tipos_contrato tc ON tc.id=c.tipo_contrato_id INNER JOIN estados_contrato ec ON ec.id=c.estado_contrato_id INNER JOIN fornecedores f ON f.id=c.fornecedor_id WHERE c.id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $contrato = $stmt->fetch();
        if (!$contrato) {
            header('Location: contratos.php');
            exit;
        }
        $bloqueado = in_array(mb_strtolower((string)$contrato->estado), ['cancelado'], true) || mb_strtolower((string)$contrato->fornecedor_estado) === 'abatido';
    } catch (PDOException $e) {
        $erroBD = 'Erro ao carregar contrato: ' . $e->getMessage();
    }
}
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Cancelar contrato</h4>
                    <p class="text-muted small mb-0">Confirmação antes de cancelar o contrato.</p>
                </div>
            </div><?php if ($erroBD): ?><?php mostrar_alerta_erro_base_dados($erroBD); ?><?php elseif ($contrato): ?><div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card p-4 border-danger">
                            <h5 class="fw-bold mb-2">Tem a certeza que pretende cancelar este contrato?</h5>
                            <p class="text-muted mb-4">O contrato não será apagado. Ficará com estado <strong>Cancelado</strong>.</p>
                            <?php if ($bloqueado): ?><div class="alert alert-warning">Este contrato já não pode ser cancelado por estar cancelado ou associado a fornecedor abatido.</div><?php endif; ?><div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="fw-bold mb-0"><?php echo e($contrato->codigo); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><?php echo e($contrato->estado); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Designação</p>
                                    <p class="mb-0"><?php echo e($contrato->designacao); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Fornecedor</p>
                                    <p class="mb-0"><?php echo e($contrato->fornecedor); ?></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="contratos.php" class="btn btn-outline-secondary">Cancelar</a>
                                <?php if (!$bloqueado): ?>
                                    <a href="contrato-confirmar-eliminar.php?id_contrato=<?php echo urlencode($idEncrypted); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-1"></i> Sim, cancelar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div><?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>