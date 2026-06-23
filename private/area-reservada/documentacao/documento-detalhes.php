<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Documento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'documentacao';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
exigir_permissao('documentacao', 'ver');

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function v($v)
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
    if (str_contains($n, 'manutenção') || str_contains($n, 'expirar') || str_contains($n, 'rever')) return 'badge-manutencao';
    return 'badge-inativo';
}
function campo($r, $v)
{
    echo '<div class="col-md-6"><p class="text-muted small mb-1">' . e($r) . '</p><p class="mb-0">' . e(v($v)) . '</p></div>';
}
function link_pdf($f)
{
    $c = trim((string) ($f->caminho_ficheiro ?? ''));
    return $c === '' ? '#' : BASE_URL . '/' . ltrim($c, '/');
}

$idEncrypted = $_GET['id_documento'] ?? null;
$idDocumento = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$idDocumento || !is_numeric($idDocumento)) {
    header('Location: documentacao.php');
    exit;
}
$idDocumento = (int) $idDocumento;
$idEncrypted = aes_encrypt($idDocumento);

$erroBD = '';
$documento = null;
$ficheiros = [];
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $respCol = 'NULL AS documento_responsavel';
        $col = $ligacao->prepare("SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'documentos' AND COLUMN_NAME = 'responsavel'");
        $col->execute();
        if ((int) $col->fetch()->total > 0) $respCol = 'd.responsavel AS documento_responsavel';
        $stmt = $ligacao->prepare("SELECT d.*, {$respCol}, td.nome AS tipo_nome, ad.nome AS area_nome, ed.nome AS estado_nome, f.nome AS fornecedor_nome, f.nif AS fornecedor_nif, f.email AS fornecedor_email, f.telefone AS fornecedor_telefone, f.estado AS fornecedor_estado, tf.nome AS fornecedor_tipo, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, e.marca AS equipamento_marca, e.modelo AS equipamento_modelo, e.numero_serie AS equipamento_numero_serie, e.servico AS equipamento_servico, e.piso AS equipamento_piso, e.sala AS equipamento_sala, ee.nome AS equipamento_estado, l.codigo AS localizacao_codigo, l.nome AS localizacao_nome, el.nome AS localizacao_estado FROM documentos d INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id INNER JOIN areas_documento ad ON ad.id = d.area_documento_id INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id INNER JOIN equipamentos e ON e.id = d.equipamento_id INNER JOIN estados_equipamento ee ON ee.id = e.estado_id INNER JOIN localizacoes l ON l.id = e.localizacao_id INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id LEFT JOIN fornecedores f ON f.id = d.fornecedor_id LEFT JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id WHERE d.id = :id LIMIT 1");
        $stmt->execute([':id' => $idDocumento]);
        $documento = $stmt->fetch();
        if (!$documento) {
            header('Location: documentacao.php');
            exit;
        }
        $stmt = $ligacao->prepare('SELECT fp.* FROM documento_ficheiros df INNER JOIN ficheiros_pdf fp ON fp.id = df.ficheiro_id WHERE df.documento_id = :id ORDER BY fp.carregado_em DESC');
        $stmt->execute([':id' => $idDocumento]);
        $ficheiros = $stmt->fetchAll();
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar o documento: ' . $erro->getMessage();
    }
}
$equipamentoAbatido = $documento && mb_strtolower((string) $documento->equipamento_estado) === 'abatido';
$documentoInvalido = $documento && in_array(mb_strtolower((string) $documento->estado_nome), ['inválido', 'invalido'], true);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do documento</h4>
                    <p class="text-muted small mb-0">Consulta do documento, equipamento e fornecedor associado.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="documentacao.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
                    <?php if ($documento && !$equipamentoAbatido && !$documentoInvalido && tem_permissao('documentacao', 'remover')): ?>
                        <a href="documento-eliminar.php?id_documento=<?php echo urlencode($idEncrypted); ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash me-1"></i> Eliminar</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($erroBD !== ''): ?><div class="alert alert-danger"><?php echo e($erroBD); ?></div><?php elseif ($documento): ?>
                <?php if ($equipamentoAbatido): ?>
                    <div class="alert alert-warning"><strong>Atenção:</strong> este documento está associado a um equipamento abatido.</div>
                <?php endif; ?>
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <div class="d-flex justify-content-between gap-2 mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1"><?php echo e($documento->codigo); ?></h5>
                                    <p class="text-muted small mb-0"><?php echo e($documento->titulo); ?></p>
                                </div><span class="badge <?php echo badge_estado($documento->estado_nome); ?> align-self-start"><?php echo e($documento->estado_nome); ?></span>
                            </div>
                            <hr>
                            <div class="row g-3"><?php campo('Tipo', $documento->tipo_nome);
                                        campo('Área', $documento->area_nome);
                                        campo('Data do documento', data_pt($documento->data_documento));
                                        campo('Validade', data_pt($documento->validade));
                                        campo('Responsável', $documento->documento_responsavel ?? '');
                                        campo('Obrigatório', (int) $documento->obrigatorio === 1 ? 'Sim' : 'Não'); ?><div class="col-12">
                                    <p class="text-muted small mb-1">Observações</p>
                                    <p class="mb-0"><?php echo nl2br(e(v($documento->observacoes))); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h6 class="fw-bold mb-3">PDFs associados</h6>
                            <?php if (empty($ficheiros)): ?><p class="text-muted small mb-0">Sem PDFs associados.

                                </p><?php else: ?><?php foreach ($ficheiros as $ficheiro): ?>
                                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                    <span>
                                        <i class="fa-solid fa-file-pdf me-2"></i><?php echo e($ficheiro->nome_original); ?></span>
                                    <a href="<?php echo e(link_pdf($ficheiro)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                                </div><?php endforeach; ?><?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h6 class="fw-bold mb-3">Dados principais do equipamento</h6>
                            <div class="row g-3"><?php campo('Código', $documento->equipamento_codigo);
                                campo('Designação', $documento->equipamento_designacao);
                                campo('Marca', $documento->equipamento_marca);
                                campo('Modelo', $documento->equipamento_modelo);
                                campo('N.º de série', $documento->equipamento_numero_serie);
                                campo('Estado', $documento->equipamento_estado);
                                campo('Serviço', $documento->equipamento_servico);
                                campo('Localização', $documento->localizacao_codigo . ' — ' . $documento->localizacao_nome); ?></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h6 class="fw-bold mb-3">Fornecedor associado</h6>
                            <?php if (!$documento->fornecedor_nome): ?><p class="text-muted small mb-0">Sem fornecedor associado.</p>
                            <?php else: ?><div class="row g-3"><?php campo('Nome', $documento->fornecedor_nome);
                                campo('Tipo', $documento->fornecedor_tipo);
                                campo('NIF', $documento->fornecedor_nif);
                                campo('Email', $documento->fornecedor_email);
                                campo('Contacto', $documento->fornecedor_telefone);
                                campo('Estado', $documento->fornecedor_estado); ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>