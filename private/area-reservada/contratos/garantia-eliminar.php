<?php
$pageTitle = 'MedInfo Solutions — Remover Garantia';
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
$idEncrypted = $_GET['id_garantia'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$id || !is_numeric($id)) {
    header('Location: contratos.php');
    exit;
}
$id = (int)$id;
$idEncrypted = aes_encrypt($id);
$erroBD = '';
$garantia = null;
$bloqueado = false;
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('SELECT g.*, eg.nome AS estado, f.nome AS fornecedor, f.estado AS fornecedor_estado, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, ee.nome AS equipamento_estado FROM garantias g INNER JOIN estados_garantia eg ON eg.id=g.estado_garantia_id INNER JOIN fornecedores f ON f.id=g.fornecedor_id INNER JOIN equipamentos e ON e.id=g.equipamento_id INNER JOIN estados_equipamento ee ON ee.id=e.estado_id WHERE g.id=:id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $garantia = $stmt->fetch();
        if (!$garantia) {
            header('Location: contratos.php');
            exit;
        }
        $bloqueado = in_array(mb_strtolower((string)$garantia->estado), ['cancelado', 'expirado'], true) || mb_strtolower((string)$garantia->equipamento_estado) === 'abatido';
    } catch (PDOException $e) {
        $erroBD = 'Erro ao carregar garantia: ' . $e->getMessage();
    }
}
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Remover garantia</h4>
                    <p class="text-muted small mb-0">Confirmação antes de remover a garantia.</p>
                </div>
            </div><?php if ($erroBD): ?><div class="alert alert-danger"><?php echo e($erroBD); ?></div><?php elseif ($garantia): ?><div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card p-4 border-danger">
                            <h5 class="fw-bold mb-2">Tem a certeza que pretende remover esta garantia?</h5>
                            <p class="text-muted mb-4">A garantia não será apagada. Ficará com estado <strong>Cancelado</strong>.</p><?php if ($bloqueado): ?>
                                <div class="alert alert-warning">Esta garantia já não pode ser removida por estar cancelada/expirada ou associada a equipamento abatido.</div>
                                <?php endif; ?><div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="fw-bold mb-0"><?php echo e($garantia->codigo); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><?php echo e($garantia->estado); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Designação</p>
                                    <p class="mb-0"><?php echo e($garantia->designacao); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Fornecedor</p>
                                    <p class="mb-0"><?php echo e($garantia->fornecedor); ?></p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted small mb-1">Equipamento</p>
                                    <p class="mb-0"><?php echo e($garantia->equipamento_codigo . ' — ' . $garantia->equipamento_designacao); ?></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2"><a href="contratos.php" class="btn btn-outline-secondary">Cancelar</a><?php if (!$bloqueado): ?>
                                <a href="garantia-confirmar-eliminar.php?id_garantia=<?php echo urlencode($idEncrypted); ?>" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i> Sim, remover</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div><?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>