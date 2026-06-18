<?php

$pageTitle = 'MedInfo Solutions — Garantias e Contratos';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'contratos';

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
    const opcoesDataTable = {
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
            emptyTable: 'Sem registos.',
            info: 'A mostrar _START_ a _END_ de _TOTAL_ registos',
            infoEmpty: 'Sem registos para mostrar',
            infoFiltered: '(filtrado de _MAX_ registos)',
            loadingRecords: 'A carregar...',
            processing: 'A processar...',
            zeroRecords: 'Nenhum registo encontrado.',
            paginate: {
                next: 'Seguinte',
                previous: 'Anterior'
            },
            aria: {
                sortAscending: ': ordenar de forma crescente',
                sortDescending: ': ordenar de forma decrescente'
            }
        }
    };

    const tabelaGarantias = $('#tabelaGarantias').DataTable(opcoesDataTable);
    const tabelaContratos = $('#tabelaContratos').DataTable(opcoesDataTable);

    function aplicarFiltrosContratosGarantias() {
        const pesquisa = $('#pesquisaContratosDT').val();
        const tipo = $('#filtroTipoContratoDT').val();
        const fornecedor = $('#filtroFornecedorContratoDT').val();
        const estado = $('#filtroEstadoContratoDT').val();

        tabelaGarantias.search(pesquisa).column(2).search(tipo).column(4).search(fornecedor).column(7).search(estado).draw();
        tabelaContratos.search(pesquisa).column(2).search(tipo).column(4).search(fornecedor).column(7).search(estado).draw();
    }

    $('#pesquisaContratosDT').on('input', aplicarFiltrosContratosGarantias);
    $('#filtroTipoContratoDT').on('change', aplicarFiltrosContratosGarantias);
    $('#filtroFornecedorContratoDT').on('change', aplicarFiltrosContratosGarantias);
    $('#filtroEstadoContratoDT').on('change', aplicarFiltrosContratosGarantias);
});
JS;

require_once __DIR__ . '/../../includes/basedados.php';

$garantias = [];
$contratos = [];
$tiposContrato = [];
$fornecedores = [];
$estadosContratosGarantias = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'garantias' => 0,
    'contratos' => 0,
    'a_expirar' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposContrato = $ligacao
            ->query('SELECT nome FROM tipos_contrato ORDER BY nome')
            ->fetchAll();

        $fornecedores = $ligacao
            ->query('SELECT nome FROM fornecedores ORDER BY nome')
            ->fetchAll();

        $estadosContratosGarantias = $ligacao
            ->query("SELECT nome FROM estados_garantia UNION SELECT nome FROM estados_contrato ORDER BY nome")
            ->fetchAll();

        $sqlGarantias = "
            SELECT
                g.id,
                g.codigo,
                g.designacao,
                'Garantia' AS tipo,
                e.codigo AS equipamento,
                f.nome AS fornecedor,
                g.data_inicio,
                g.data_fim,
                eg.nome AS estado
            FROM garantias g
            INNER JOIN equipamentos e ON e.id = g.equipamento_id
            INNER JOIN fornecedores f ON f.id = g.fornecedor_id
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            ORDER BY g.codigo
        ";

        $garantias = $ligacao->query($sqlGarantias)->fetchAll();

        $sqlContratos = "
            SELECT
                c.id,
                c.codigo,
                c.designacao,
                tc.nome AS tipo,
                COALESCE(GROUP_CONCAT(e.codigo ORDER BY e.codigo SEPARATOR ', '), 'Sem equipamentos') AS associado_a,
                f.nome AS fornecedor,
                c.data_inicio,
                c.data_fim,
                ec.nome AS estado
            FROM contratos c
            INNER JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
            INNER JOIN fornecedores f ON f.id = c.fornecedor_id
            INNER JOIN estados_contrato ec ON ec.id = c.estado_contrato_id
            LEFT JOIN contrato_equipamentos ce ON ce.contrato_id = c.id
            LEFT JOIN equipamentos e ON e.id = ce.equipamento_id
            GROUP BY
                c.id,
                c.codigo,
                c.designacao,
                tc.nome,
                f.nome,
                c.data_inicio,
                c.data_fim,
                ec.nome
            ORDER BY c.codigo
        ";

        $contratos = $ligacao->query($sqlContratos)->fetchAll();

        $sqlIndicadores = "
            SELECT
                ((SELECT COUNT(*) FROM garantias) + (SELECT COUNT(*) FROM contratos)) AS total,
                (SELECT COUNT(*) FROM garantias) AS garantias,
                (SELECT COUNT(*) FROM contratos) AS contratos,
                (
                    (SELECT COUNT(*) FROM garantias WHERE data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY)) +
                    (SELECT COUNT(*) FROM contratos WHERE data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY))
                ) AS a_expirar
        ";

        $indicadores = $ligacao->query($sqlIndicadores)->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar garantias e contratos.';
        $garantias = [];
        $contratos = [];
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
                    <h4 class="fw-bold mb-1">Garantias e Contratos</h4>
                    <p class="text-muted small mb-0">
                        Gestão separada das garantias dos equipamentos e dos contratos associados.
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
                            <p class="text-muted small mb-1">Garantias</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['garantias'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-info-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Contratos</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['contratos'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">A expirar</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['a_expirar'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label for="pesquisaContratosDT" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaContratosDT"
                                placeholder="Código, designação, fornecedor ou equipamento">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroTipoContratoDT" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoContratoDT">
                                <option value="">Todos</option>
                                <option value="Garantia">Garantia</option>
                                <?php foreach ($tiposContrato as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo->nome); ?>">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroFornecedorContratoDT" class="form-label">Fornecedor</label>
                            <select class="form-select" id="filtroFornecedorContratoDT">
                                <option value="">Todos</option>
                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <option value="<?php echo htmlspecialchars($fornecedor->nome); ?>">
                                        <?php echo htmlspecialchars($fornecedor->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoContratoDT" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoContratoDT">
                                <option value="">Todos</option>
                                <?php foreach ($estadosContratosGarantias as $estado): ?>
                                    <option value="<?php echo htmlspecialchars($estado->nome); ?>">
                                        <?php echo htmlspecialchars($estado->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA DE GARANTIAS -->
            <section class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Garantias</h5>
                    <p class="text-muted small mb-0">Garantias associadas diretamente a equipamentos médicos.</p>
                </div>

                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaGarantias">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Tipo</th>
                                    <th>Equipamento</th>
                                    <th>Fornecedor</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($garantias as $garantia): ?>
                                    <?php
                                    $classeEstado = 'badge-inativo';

                                    if ($garantia->estado === 'Ativo') {
                                        $classeEstado = 'badge-ativo';
                                    } elseif ($garantia->estado === 'A expirar') {
                                        $classeEstado = 'badge-manutencao';
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($garantia->codigo); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->designacao); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->tipo); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->equipamento); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->fornecedor); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->data_inicio); ?></td>
                                        <td><?php echo htmlspecialchars($garantia->data_fim); ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($garantia->estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="garantia-detalhes.php?id=<?php echo htmlspecialchars($garantia->id); ?>"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="garantia-eliminar.php?id=<?php echo htmlspecialchars($garantia->id); ?>"
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

            <!-- TABELA DE CONTRATOS -->
            <section class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Contratos</h5>
                    <p class="text-muted small mb-0">Contratos de manutenção, assistência técnica e seguros.</p>
                </div>

                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaContratos">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Tipo</th>
                                    <th>Associado a</th>
                                    <th>Fornecedor</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($contratos as $contrato): ?>
                                    <?php
                                    $classeEstado = 'badge-inativo';

                                    if ($contrato->estado === 'Ativo') {
                                        $classeEstado = 'badge-ativo';
                                    } elseif ($contrato->estado === 'A expirar') {
                                        $classeEstado = 'badge-manutencao';
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($contrato->codigo); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->designacao); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->tipo); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->associado_a); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->fornecedor); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->data_inicio); ?></td>
                                        <td><?php echo htmlspecialchars($contrato->data_fim); ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($contrato->estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="contrato-detalhes.php?id=<?php echo htmlspecialchars($contrato->id); ?>"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="contrato-eliminar.php?id=<?php echo htmlspecialchars($contrato->id); ?>"
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

