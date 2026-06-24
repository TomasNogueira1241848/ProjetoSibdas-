<?php
$pageTitle = 'MedInfo Solutions — Abater Localização';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'localizacoes';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}

$idEncrypted = $_GET['id_localizacao'] ?? null;
$idLocalizacao = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$idLocalizacao || !is_numeric($idLocalizacao)) {
    header('Location: localizacoes.php');
    exit;
}
$idLocalizacao = (int) $idLocalizacao;
$idEncrypted = aes_encrypt($idLocalizacao);

$erroBD = '';
$localizacao = null;
$totalEquipamentos = 0;
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('SELECT l.*, tl.nome AS tipo_nome, el.nome AS estado_nome FROM localizacoes l INNER JOIN tipos_localizacao tl ON tl.id = l.tipo_localizacao_id INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id WHERE l.id = :id LIMIT 1');
        $stmt->execute([':id' => $idLocalizacao]);
        $localizacao = $stmt->fetch();
        if (!$localizacao) {
            header('Location: localizacoes.php');
            exit;
        }
        $stmt = $ligacao->prepare('SELECT COUNT(*) AS total FROM equipamentos WHERE localizacao_id = :id');
        $stmt->execute([':id' => $idLocalizacao]);
        $totalEquipamentos = (int) $stmt->fetch()->total;
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar a localização: ' . $erro->getMessage();
    }
}
$abatida = $localizacao && in_array(mb_strtolower((string) $localizacao->estado_nome), ['inativa', 'abatida'], true);
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Abater localização</h4>
                    <p class="text-muted small mb-0">Confirmação antes de retirar a localização de uso.</p>
                </div>
            </div>
            <?php if ($erroBD !== ''): ?><?php mostrar_alerta_erro_base_dados($erroBD); ?><?php elseif ($localizacao): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card p-4 border-danger">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                <div class="dashboard-icon dashboard-icon-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                <div>
                                    <h5 class="fw-bold mb-1">Tem a certeza que pretende abater esta localização?</h5>
                                    <p class="text-muted mb-0">O estado da localização será alterado para <strong>Abatida</strong>, contudo continuará disponível para visualização dos detalhes e histórico. Esta ação é recomendada para localizações que já não estão em uso. É recomendado alterar, nos equipamentos associados, a localização para uma que esteja ativa.</p>
                                </div>
                            </div>
                            <hr><?php if ($abatida): ?><div class="alert alert-warning">Esta localização já está abatida/inativa.</div><?php endif; ?><div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="fw-bold mb-0"><?php echo e($localizacao->codigo); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge <?php echo $abatida ? 'badge-inativo' : 'badge-ativo'; ?>"><?php echo e($localizacao->estado_nome); ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Nome</p>
                                    <p class="mb-0"><?php echo e($localizacao->nome); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Tipo</p>
                                    <p class="mb-0"><?php echo e($localizacao->tipo_nome); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Responsável</p>
                                    <p class="mb-0"><?php echo e($localizacao->responsavel); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Equipamentos associados</p>
                                    <p class="mb-0"><?php echo e($totalEquipamentos); ?></p>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2"><a href="localizacoes.php" class="btn btn-outline-secondary">Cancelar</a><?php if (!$abatida): ?><a href="localizacao-confirmar-eliminar.php?id_localizacao=<?php echo urlencode($idEncrypted); ?>" class="btn btn-danger"><i class="fa-solid fa-box-archive me-1"></i> Sim, abater</a><?php endif; ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>