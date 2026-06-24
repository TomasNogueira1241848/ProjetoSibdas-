<?php
$pageTitle = 'MedInfo Solutions — Detalhes da Localização';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'localizacoes';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
exigir_permissao('localizacoes', 'ver');

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function mostrar_valor($v)
{
    $v = trim((string) ($v ?? ''));
    return $v === '' ? '—' : $v;
}
function data_pt($d)
{
    return $d ? date('d/m/Y', strtotime($d)) : '—';
}
function badge_estado($estado)
{
    $n = mb_strtolower((string) $estado);
    if (in_array($n, ['ativo', 'ativa', 'válido', 'valido'], true)) return 'badge-ativo';
    if (str_contains($n, 'manutenção') || str_contains($n, 'expirar')) return 'badge-manutencao';
    return 'badge-inativo';
}
function campo($r, $v)
{
    echo '<div class="col-md-6"><p class="text-muted small mb-1">' . e($r) . '</p><p class="mb-0">' . e(mostrar_valor($v)) . '</p></div>';
}
function link_equipamento($id)
{
    return '../equipamentos/equipamento-detalhes.php?id_equipamento=' . urlencode(aes_encrypt((int) $id));
}
function link_documento($id)
{
    return '../documentacao/documento-detalhes.php?id_documento=' . urlencode(aes_encrypt((int) $id));
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
$equipamentos = [];
$documentos = [];
$contratos = [];

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

        $stmt = $ligacao->prepare('SELECT e.id, e.codigo, e.designacao, e.marca, e.modelo, e.servico, e.piso, e.sala, ee.nome AS estado FROM equipamentos e INNER JOIN estados_equipamento ee ON ee.id = e.estado_id WHERE e.localizacao_id = :id ORDER BY e.codigo');
        $stmt->execute([':id' => $idLocalizacao]);
        $equipamentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('SELECT d.id, d.codigo, d.titulo, td.nome AS tipo, ed.nome AS estado, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao FROM documentos d INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id INNER JOIN equipamentos e ON e.id = d.equipamento_id WHERE e.localizacao_id = :id ORDER BY d.codigo');
        $stmt->execute([':id' => $idLocalizacao]);
        $documentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('SELECT DISTINCT c.id, c.codigo, c.designacao, tc.nome AS tipo, ec.nome AS estado, f.nome AS fornecedor FROM contratos c INNER JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id INNER JOIN estados_contrato ec ON ec.id = c.estado_contrato_id INNER JOIN fornecedores f ON f.id = c.fornecedor_id INNER JOIN contrato_equipamentos ce ON ce.contrato_id = c.id INNER JOIN equipamentos e ON e.id = ce.equipamento_id WHERE e.localizacao_id = :id ORDER BY c.codigo');
        $stmt->execute([':id' => $idLocalizacao]);
        $contratos = $stmt->fetchAll();
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar a localização: ' . $erro->getMessage();
    }
}
$localizacaoAbatida = $localizacao && in_array(mb_strtolower((string) $localizacao->estado_nome), ['inativa', 'abatida'], true);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?>
        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes da localização</h4>
                    <p class="text-muted small mb-0">Consulta da localização e dos registos associados.</p>
                </div>
                <div class="d-flex gap-2"><?php if (!$localizacaoAbatida && tem_permissao('localizacoes', 'editar')): ?><a href="localizacao-editar.php?id_localizacao=<?php echo urlencode($idEncrypted); ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i> Editar</a><?php endif; ?><?php if (!$localizacaoAbatida && tem_permissao('localizacoes', 'remover')): ?><a href="localizacao-eliminar.php?id_localizacao=<?php echo urlencode($idEncrypted); ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-box-archive me-1"></i> Abater</a><?php endif; ?><a href="localizacoes.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a></div>
            </div>
            <?php if ($erroBD !== ''): ?><?php mostrar_alerta_erro_base_dados($erroBD); ?><?php elseif ($localizacao): ?>
                <?php if ($localizacaoAbatida): ?><div class="alert alert-warning"><strong>Localização abatida.</strong> Os equipamentos associados passam a apresentar esta indicação.</div><?php endif; ?>
                <section class="mb-4">
                    <div class="card p-4">
                        <div class="d-flex justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($localizacao->codigo . ' — ' . $localizacao->nome); ?></h5>
                                <p class="text-muted small mb-0"><?php echo e($localizacao->tipo_nome); ?></p>
                            </div><span class="badge <?php echo badge_estado($localizacao->estado_nome); ?> align-self-start"><?php echo e($localizacao->estado_nome); ?></span>
                        </div>
                        <hr>
                        <div class="row g-3"><?php campo('Edifício', $localizacao->edificio);
                                                                                                        campo('Piso principal', $localizacao->piso_principal);
                                                                                                        campo('Número de andares', $localizacao->numero_andares);
                                                                                                        campo('Responsável', $localizacao->responsavel);
                                                                                                        campo('Contacto', $localizacao->telefone); ?><div class="col-12">
                                <p class="text-muted small mb-1">Descrição</p>
                                <p class="mb-0"><?php echo nl2br(e(mostrar_valor($localizacao->descricao))); ?></p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="mb-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Equipamentos nesta localização</h6>
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Serviço</th>
                                        <th>Piso/Sala</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody><?php if (empty($equipamentos)): ?><tr>
                                            <td colspan="6" class="text-muted text-center">Sem equipamentos associados.</td>
                                        </tr><?php endif; ?><?php foreach ($equipamentos as $eq): ?><tr>
                                            <td><?php echo e($eq->codigo); ?></td>
                                            <td><?php echo e($eq->designacao); ?><?php if ($localizacaoAbatida): ?><br><span class="badge badge-inativo">Localização abatida</span><?php endif; ?><div class="text-muted small"><?php echo e($eq->marca . ' ' . $eq->modelo); ?></div>
                                            </td>
                                            <td><?php echo e(mostrar_valor($eq->servico)); ?></td>
                                            <td><?php echo e(mostrar_valor($eq->piso) . ' / ' . mostrar_valor($eq->sala)); ?></td>
                                            <td><span class="badge <?php echo badge_estado($eq->estado); ?>"><?php echo e($eq->estado); ?></span></td>
                                            <td><a href="<?php echo e(link_equipamento($eq->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                        </tr><?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <section class="mb-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Documentos associados aos equipamentos desta localização</h6>
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Documento</th>
                                        <th>Tipo</th>
                                        <th>Equipamento</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody><?php if (empty($documentos)): ?><tr>
                                            <td colspan="6" class="text-muted text-center">Sem documentos associados.</td>
                                        </tr><?php endif; ?><?php foreach ($documentos as $doc): ?><tr>
                                            <td><?php echo e($doc->codigo); ?></td>
                                            <td><?php echo e($doc->titulo); ?><?php if ($localizacaoAbatida): ?><br><span class="badge badge-inativo">Localização abatida</span><?php endif; ?></td>
                                            <td><?php echo e($doc->tipo); ?></td>
                                            <td><?php echo e($doc->equipamento_codigo . ' — ' . $doc->equipamento_designacao); ?></td>
                                            <td><span class="badge <?php echo badge_estado($doc->estado); ?>"><?php echo e($doc->estado); ?></span></td>
                                            <td><a href="<?php echo e(link_documento($doc->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                        </tr><?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <section class="mb-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Contratos dos equipamentos desta localização</h6>
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Contrato</th>
                                        <th>Tipo</th>
                                        <th>Fornecedor</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody><?php if (empty($contratos)): ?><tr>
                                            <td colspan="5" class="text-muted text-center">Sem contratos associados.</td>
                                        </tr><?php endif; ?><?php foreach ($contratos as $cont): ?><tr>
                                            <td><?php echo e($cont->codigo); ?></td>
                                            <td><?php echo e($cont->designacao); ?></td>
                                            <td><?php echo e($cont->tipo); ?></td>
                                            <td><?php echo e($cont->fornecedor); ?></td>
                                            <td><span class="badge <?php echo badge_estado($cont->estado); ?>"><?php echo e($cont->estado); ?></span></td>
                                        </tr><?php endforeach; ?></tbody>
                            </table>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>