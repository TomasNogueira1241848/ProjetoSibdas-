<?php

$pageTitle = 'MedInfo Solutions — Documentação';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'documentacao';

$extraCss = [
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.css'
];

$extraScripts = [
    $assetPath . '/jquery/jquery-3.7.1.min.js',
    $assetPath . '/js/jquery.dataTables.min.js',
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'
];

$pageScript = <<<'JS'
$(document).ready(function () {
    const tabelaDocumentacao = $('#tabelaDocumentacao').DataTable({
        pageLength: 5,
        lengthChange: false,
        pagingType: 'simple_numbers',
        ordering: true,
        autoWidth: false,
        order: [[0, 'asc']],
        columnDefs: [
            {
                orderable: false,
                targets: -1
            }
        ],
        dom: 't' + '<"datatable-footer d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3"ip>',
        language: {
            decimal: '',
            emptyTable: 'Sem documentos registados.',
            info: 'A mostrar _START_ a _END_ de _TOTAL_ documentos',
            infoEmpty: 'Sem documentos para mostrar',
            infoFiltered: '(filtrado de _MAX_ documentos)',
            loadingRecords: 'A carregar...',
            processing: 'A processar...',
            zeroRecords: 'Nenhum documento encontrado.',
            paginate: {
                next: 'Seguinte',
                previous: 'Anterior'
            },
            aria: {
                sortAscending: ': ordenar de forma crescente',
                sortDescending: ': ordenar de forma decrescente'
            }
        }
    });

    $('#pesquisaDocumentacaoDT').on('input', function () {
        tabelaDocumentacao.search(this.value).draw();
    });

    $('#filtroTipoDocumentoDT').on('change', function () {
        tabelaDocumentacao.column(2).search(this.value).draw();
    });

    $('#filtroAreaDocumentoDT').on('change', function () {
        tabelaDocumentacao.column(3).search(this.value).draw();
    });

    $('#filtroEstadoDocumentoDT').on('change', function () {
        tabelaDocumentacao.column(7).search(this.value).draw();
    });
});
JS;

require_once __DIR__ . '/../../includes/basedados.php';

$documentos = [];
$tiposDocumento = [];
$areasDocumento = [];
$estadosDocumento = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'validos' => 0,
    'expirados' => 0,
    'obrigatorios' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposDocumento = $ligacao
            ->query('SELECT nome FROM tipos_documento ORDER BY nome')
            ->fetchAll();

        $areasDocumento = $ligacao
            ->query('SELECT nome FROM areas_documento ORDER BY nome')
            ->fetchAll();

        $estadosDocumento = $ligacao
            ->query('SELECT nome FROM estados_documento ORDER BY nome')
            ->fetchAll();

        $sqlDocumentos = "
            SELECT
                d.id,
                d.codigo,
                d.titulo,
                td.nome AS tipo,
                ad.nome AS area,
                COALESCE(
                    CONCAT(e.codigo, ' — ', e.designacao),
                    f.nome,
                    CONCAT('Manutenção #', m.id),
                    'Sem associação'
                ) AS associado,
                d.data_documento,
                d.validade,
                ed.nome AS estado,
                d.obrigatorio
            FROM documentos d
            INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id
            INNER JOIN areas_documento ad ON ad.id = d.area_documento_id
            INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id
            LEFT JOIN equipamentos e ON e.id = d.equipamento_id
            LEFT JOIN fornecedores f ON f.id = d.fornecedor_id
            LEFT JOIN manutencoes m ON m.id = d.manutencao_id
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
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <!-- TÍTULO E AÇÕES -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Documentação</h4>
                    <p class="text-muted small mb-0">
                        Gestão dos documentos técnicos, administrativos e legais associados aos equipamentos.
                    </p>
                </div>
            </div>

            <?php if ($erroBD !== ''): ?>
                <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>
                    <div>
                        <strong class="d-block">Erro na base de dados</strong>
                        <span><?php echo htmlspecialchars($erroBD); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- INDICADORES -->
            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['total'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Válidos</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['validos'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Expirados</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['expirados'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-info-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Obrigatórios</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['obrigatorios'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label for="pesquisaDocumentacaoDT" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaDocumentacaoDT"
                                placeholder="Código, título, tipo, área ou associação">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroTipoDocumentoDT" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoDocumentoDT">
                                <option value="">Todos</option>
                                <?php foreach ($tiposDocumento as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo->nome); ?>">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroAreaDocumentoDT" class="form-label">Área</label>
                            <select class="form-select" id="filtroAreaDocumentoDT">
                                <option value="">Todas</option>
                                <?php foreach ($areasDocumento as $area): ?>
                                    <option value="<?php echo htmlspecialchars($area->nome); ?>">
                                        <?php echo htmlspecialchars($area->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoDocumentoDT" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoDocumentoDT">
                                <option value="">Todos</option>
                                <?php foreach ($estadosDocumento as $estado): ?>
                                    <option value="<?php echo htmlspecialchars($estado->nome); ?>">
                                        <?php echo htmlspecialchars($estado->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA -->
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
                                    $classeEstado = 'badge-inativo';

                                    if ($documento->estado === 'Válido') {
                                        $classeEstado = 'badge-ativo';
                                    } elseif ($documento->estado === 'Pendente') {
                                        $classeEstado = 'badge-manutencao';
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($documento->codigo); ?></td>
                                        <td><?php echo htmlspecialchars($documento->titulo); ?></td>
                                        <td><?php echo htmlspecialchars($documento->tipo); ?></td>
                                        <td><?php echo htmlspecialchars($documento->area); ?></td>
                                        <td><?php echo htmlspecialchars($documento->associado); ?></td>
                                        <td><?php echo htmlspecialchars($documento->data_documento ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($documento->validade ?? '—'); ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($documento->estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="documento-detalhes.php?id=<?php echo htmlspecialchars($documento->id); ?>"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="documento-eliminar.php?id=<?php echo htmlspecialchars($documento->id); ?>"
                                                class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip"
                                                data-bs-title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
