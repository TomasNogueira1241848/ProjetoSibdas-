<?php
$pageTitle = 'MedInfo Solutions — Descontinuar Fornecedor';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'fornecedores';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function sim_nao($v)
{
    return (int) $v === 1 ? 'Sim' : 'Não';
}

$idEncrypted = $_GET['id_fornecedor'] ?? null;
$idFornecedor = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: fornecedores.php');
    exit;
}
$idFornecedor = (int) $idFornecedor;
$idEncrypted = aes_encrypt($idFornecedor);

$erroBD = '';
$fornecedor = null;
$resumo = ['equipamentos' => 0, 'documentos' => 0, 'contratos' => 0, 'garantias' => 0];
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('SELECT f.*, tf.nome AS tipo_nome FROM fornecedores f INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id WHERE f.id = :id LIMIT 1');
        $stmt->execute([':id' => $idFornecedor]);
        $fornecedor = $stmt->fetch();
        if (!$fornecedor) {
            header('Location: fornecedores.php');
            exit;
        }
        $stmt = $ligacao->prepare('SELECT COUNT(DISTINCT e.id) AS total FROM equipamentos e LEFT JOIN equipamento_fornecedores ef ON ef.equipamento_id = e.id WHERE e.fornecedor_principal_id = :id OR ef.fornecedor_id = :id');
        $stmt->execute([':id' => $idFornecedor]);
        $resumo['equipamentos'] = (int) $stmt->fetch()->total;
        $stmt = $ligacao->prepare('SELECT COUNT(*) AS total FROM documentos WHERE fornecedor_id = :id');
        $stmt->execute([':id' => $idFornecedor]);
        $resumo['documentos'] = (int) $stmt->fetch()->total;
        $stmt = $ligacao->prepare('SELECT COUNT(*) AS total FROM contratos WHERE fornecedor_id = :id');
        $stmt->execute([':id' => $idFornecedor]);
        $resumo['contratos'] = (int) $stmt->fetch()->total;
        $stmt = $ligacao->prepare('SELECT COUNT(*) AS total FROM garantias WHERE fornecedor_id = :id');
        $stmt->execute([':id' => $idFornecedor]);
        $resumo['garantias'] = (int) $stmt->fetch()->total;
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar o fornecedor: ' . $erro->getMessage();
    }
}
$descontinuado = $fornecedor && in_array(mb_strtolower((string) $fornecedor->estado), ['descontinuado', 'abatido'], true);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?>
        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Descontinuar fornecedor</h4>
                    <p class="text-muted small mb-0">Confirmação antes de retirar o fornecedor de uso.</p>
                </div>
            </div>
            <?php if ($erroBD !== ''): ?><div class="alert alert-danger"><?php echo e($erroBD); ?></div><?php elseif ($fornecedor): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card p-4 border-danger">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                <div class="dashboard-icon dashboard-icon-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                <div>
                                    <h5 class="fw-bold mb-1">Tem a certeza que pretende descontinuar este fornecedor?</h5>
                                    <p class="text-muted mb-0">O estado do fornecedor será alterado para <strong>Descontinuado</strong>, contudo continuará disponível para visualização dos detalhes e histórico. Esta ação é recomendada para fornecedores que já não se encontram ativos ou que já não mantêm relação contratual com a instituição, mas cuja informação deve ser preservada devido aos equipamentos, documentos, garantias e contratos associados.</p>
                                </div>
                            </div>
                            <hr>
                            <?php if ($descontinuado): ?><div class="alert alert-warning">Este fornecedor já está descontinuado.</div><?php endif; ?>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Nome</p>
                                    <p class="fw-bold mb-0"><?php echo e($fornecedor->nome); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge <?php echo $descontinuado ? 'badge-inativo' : 'badge-ativo'; ?>"><?php echo e($fornecedor->estado); ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">NIF</p>
                                    <p class="mb-0"><?php echo e($fornecedor->nif); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Tipo</p>
                                    <p class="mb-0"><?php echo e($fornecedor->tipo_nome); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Email</p>
                                    <p class="mb-0"><?php echo e($fornecedor->email); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Contacto</p>
                                    <p class="mb-0"><?php echo e($fornecedor->telefone); ?></p>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="fornecedores.php" class="btn btn-outline-secondary">Cancelar</a>
                                <?php if (!$descontinuado): ?><a href="fornecedor-confirmar-eliminar.php?id_fornecedor=<?php echo urlencode($idEncrypted); ?>" class="btn btn-danger"><i class="fa-solid fa-box-archive me-1"></i> Sim, descontinuar</a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>