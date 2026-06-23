<?php
$pageTitle = 'MedInfo Solutions — Documentação';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'documentacao';

$extraCss = [$assetPath . '/bootstrap/dataTables.bootstrap5.min.css'];
$extraScripts = [
    $assetPath . '/jquery/jquery-3.7.1.min.js',
    $assetPath . '/js/jquery.dataTables.min.js',
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'
];

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
exigir_permissao('documentacao', 'ver');

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function badge_estado($estado)
{
    $n = mb_strtolower((string) $estado);
    if (in_array($n, ['ativo', 'ativa', 'válido', 'valido'], true)) return 'badge-ativo';
    if (str_contains($n, 'manutenção') || str_contains($n, 'expirar') || str_contains($n, 'rever')) return 'badge-manutencao';
    return 'badge-inativo';
}
function abatido_equipamento($estado)
{
    return mb_strtolower((string) $estado) === 'abatido';
}

$documentos = [];
$tiposDocumento = [];
$areasDocumento = [];
$estadosDocumento = [];
$erroBD = '';
$indicadores = ['total' => 0, 'validos' => 0, 'expirados' => 0, 'obrigatorios' => 0];

$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposDocumento = $ligacao->query('SELECT nome FROM tipos_documento ORDER BY nome')->fetchAll();
        $areasDocumento = $ligacao->query('SELECT nome FROM areas_documento ORDER BY nome')->fetchAll();
        $estadosDocumento = $ligacao->query('SELECT nome FROM estados_documento ORDER BY nome')->fetchAll();

        $sqlDocumentos = "
            SELECT
                d.id, d.codigo, d.titulo, td.nome AS tipo, ad.nome AS area,
                CONCAT(e.codigo, ' — ', e.designacao) AS associado,
                d.data_documento, d.validade, ed.nome AS estado, d.obrigatorio,
                f.nome AS fornecedor_nome, f.estado AS fornecedor_estado,
                ee.nome AS equipamento_estado,
                l.nome AS localizacao_nome, el.nome AS localizacao_estado
            FROM documentos d
            INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id
            INNER JOIN areas_documento ad ON ad.id = d.area_documento_id
            INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id
            INNER JOIN equipamentos e ON e.id = d.equipamento_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            LEFT JOIN fornecedores f ON f.id = d.fornecedor_id
            ORDER BY d.codigo
        ";
        $documentos = $ligacao->query($sqlDocumentos)->fetchAll();

        $sqlIndicadores = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN ed.nome = 'Válido' THEN 1 ELSE 0 END) AS validos,
                SUM(CASE WHEN ed.nome = 'Expirado' THEN 1 ELSE 0 END) AS expirados,
                SUM(CASE WHEN d.obrigatorio = 1 THEN 1 ELSE 0 END) AS obrigatorios
            FROM documentos d
            INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id
        ";
        $indicadores = $ligacao->query($sqlIndicadores)->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar a documentação.';
        $documentos = [];
    }
}
$ligacao = null;

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?>
        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Documentação</h4>
                    <p class="text-muted small mb-0">Gestão dos documentos técnicos, administrativos e legais associados aos equipamentos.</p>
                </div>
            </div>
            <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == '1'): ?><div class="alert alert-success d-flex align-items-start gap-2"><i class="fa-solid fa-circle-check mt-1"></i>
                    <div><strong class="d-block">Documento invalidado</strong><span>O documento foi marcado como inválido, mantendo o histórico.</span></div>
                </div><?php endif; ?>
            <?php if ($erroBD !== ''): ?><div class="alert alert-danger"><strong>Erro na base de dados</strong><br><?php echo e($erroBD); ?></div><?php endif; ?>

            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['total'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Válidos</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['validos'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Expirados</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['expirados'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-info-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Obrigatórios</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['obrigatorios'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5"><label for="pesquisaDocumentacaoDT" class="form-label">Pesquisar</label><input type="search" class="form-control" id="pesquisaDocumentacaoDT" placeholder="Código, título, tipo, área ou associação"></div>
                        <div class="col-md-4 col-lg-3"><label for="filtroTipoDocumentoDT" class="form-label">Tipo</label><select class="form-select" id="filtroTipoDocumentoDT">
                                <option value="">Todos</option><?php foreach ($tiposDocumento as $tipo): ?><option value="<?php echo e($tipo->nome); ?>"><?php echo e($tipo->nome); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-4 col-lg-2"><label for="filtroAreaDocumentoDT" class="form-label">Área</label><select class="form-select" id="filtroAreaDocumentoDT">
                                <option value="">Todas</option><?php foreach ($areasDocumento as $area): ?><option value="<?php echo e($area->nome); ?>"><?php echo e($area->nome); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-4 col-lg-2"><label for="filtroEstadoDocumentoDT" class="form-label">Estado</label><select class="form-select" id="filtroEstadoDocumentoDT">
                                <option value="">Todos</option><?php foreach ($estadosDocumento as $estado): ?><option value="<?php echo e($estado->nome); ?>"><?php echo e($estado->nome); ?></option><?php endforeach; ?>
                            </select></div>
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaDocumentacao">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Área</th>
                                    <th>Associado a</th>
                                    <th>Data</th>
                                    <th>Validade</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $documento): ?>
                                    <?php
                                    $equipamentoAbatido = abatido_equipamento($documento->equipamento_estado ?? '');
                                    $documentoInvalido = in_array(mb_strtolower((string) ($documento->estado ?? '')), ['inválido', 'invalido'], true);
                                    $bloquearEliminar = $equipamentoAbatido || $documentoInvalido;
                                    ?>
                                    <tr>
                                        <td><?php echo e($documento->codigo); ?></td>
                                        <td><?php echo e($documento->titulo); ?>
                                            <?php if ($equipamentoAbatido): ?><br><span class="badge badge-inativo">Equipamento abatido</span><?php endif; ?>
                                        </td>
                                        <td><?php echo e($documento->tipo); ?></td>
                                        <td><?php echo e($documento->area); ?></td>
                                        <td><?php echo e($documento->associado); ?></td>
                                        <td><?php echo e($documento->data_documento ?? '—'); ?></td>
                                        <td><?php echo e($documento->validade ?? '—'); ?></td>
                                        <td><span class="badge <?php echo badge_estado($documento->estado); ?>"><?php echo e($documento->estado); ?></span></td>
                                        <td class="text-center"><a href="documento-detalhes.php?id_documento=<?php echo urlencode(aes_encrypt($documento->id)); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i class="fa-solid fa-eye"></i></a><?php if (!$bloquearEliminar && tem_permissao('documentacao', 'remover')): ?> <a href="documento-eliminar.php?id_documento=<?php echo urlencode(aes_encrypt($documento->id)); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-title="Eliminar"><i class="fa-solid fa-trash"></i></a><?php endif; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>