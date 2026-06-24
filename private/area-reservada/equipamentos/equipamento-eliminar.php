<?php
$pageTitle = 'MedInfo Solutions — Abater Equipamento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'equipamentos';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();
exigir_permissao('equipamentos', 'remover');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: equipamentos.php');
    exit;
}

function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function mostrar_valor($valor, $vazio = 'Não indicado')
{
    $texto = trim((string) ($valor ?? ''));
    return $texto !== '' ? e($texto) : '<span class="text-muted small">' . e($vazio) . '</span>';
}

function badge_estado_eliminar($estado)
{
    $estado = (string) ($estado ?? '');
    $normalizado = strtolower($estado);
    $classe = 'badge-inativo';

    if ($normalizado === 'ativo') {
        $classe = 'badge-ativo';
    } elseif ($normalizado === 'em manutenção' || $normalizado === 'em manutencao') {
        $classe = 'badge-manutencao';
    }

    return '<span class="badge ' . $classe . '">' . e($estado !== '' ? $estado : 'Não indicado') . '</span>';
}

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$equipamentoId = null;

if ($idEquipamentoEncrypted !== null) {
    $idDesencriptado = aes_decrypt($idEquipamentoEncrypted);
    $equipamentoId = ($idDesencriptado !== false && is_numeric($idDesencriptado)) ? (int) $idDesencriptado : null;
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    /* Compatibilidade com links antigos, caso existam. */
    $equipamentoId = (int) $_GET['id'];
    $idEquipamentoEncrypted = aes_encrypt($equipamentoId);
}

if (!$equipamentoId) {
    header('Location: equipamentos.php');
    exit;
}

$equipamento = null;
$erroBD = '';

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('
            SELECT
                e.id,
                e.codigo,
                e.designacao,
                e.marca,
                e.modelo,
                e.numero_serie,
                c.nome AS categoria,
                ee.nome AS estado,
                l.nome AS localizacao
            FROM equipamentos e
            INNER JOIN categorias_equipamento c ON c.id = e.categoria_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            WHERE e.id = :id
            LIMIT 1
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $equipamento = $stmt->fetch();

        if (!$equipamento) {
            header('Location: equipamentos.php');
            exit;
        }
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar o equipamento: ' . $erro->getMessage();
    }
}

$ligacao = null;

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Abater equipamento</h4>
                    <p class="text-muted small mb-0">Confirmação do abate do equipamento selecionado.</p>
                </div>
            </div>

            <?php if ($erroBD !== ''): ?>
                <?php mostrar_alerta_erro_base_dados($erroBD); ?>
            <?php elseif ($equipamento): ?>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card p-4 border-danger">
                        <div class="d-flex gap-3 align-items-start mb-3">
                            <div class="dashboard-icon dashboard-icon-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">Tem a certeza que pretende abater este equipamento?</h5>
                                <p class="text-muted mb-0">
                                   O estado do equipamento será alterado para <strong>Abatido</strong>, contudo estará disponível para visualização dos detalhes e histórico. Esta ação é recomendada para equipamentos que já não estão em uso, mas se pretende manter um registo histórico do equipamento e respetiva documentação associada.
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Código</p>
                                <p class="fw-bold mb-0"><?php echo e($equipamento->codigo); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado atual</p>
                                <p class="mb-0"><?php echo badge_estado_eliminar($equipamento->estado); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Designação</p>
                                <p class="mb-0"><?php echo mostrar_valor($equipamento->designacao); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Categoria</p>
                                <p class="mb-0"><?php echo mostrar_valor($equipamento->categoria); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Marca / Modelo</p>
                                <p class="mb-0"><?php echo e(trim(($equipamento->marca ?? '') . ' ' . ($equipamento->modelo ?? ''))); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Localização</p>
                                <p class="mb-0"><?php echo mostrar_valor($equipamento->localizacao); ?></p>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="equipamentos.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-xmark me-1"></i> Não, cancelar
                            </a>

                            <a href="equipamento-confirmar-eliminar.php?id_equipamento=<?php echo urlencode($idEquipamentoEncrypted); ?>" class="btn btn-danger">
                                <i class="fa-solid fa-check me-1"></i> Sim, abater
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <?php endif; ?>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
