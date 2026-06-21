<?php

$pageTitle = 'MedInfo Solutions — Localizações';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'localizacoes';

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
    const tabelaLocalizacoes = $('#tabelaLocalizacoes').DataTable({
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
            emptyTable: 'Sem localizações registadas.',
            info: 'A mostrar _START_ a _END_ de _TOTAL_ localizações',
            infoEmpty: 'Sem localizações para mostrar',
            infoFiltered: '(filtrado de _MAX_ localizações)',
            loadingRecords: 'A carregar...',
            processing: 'A processar...',
            zeroRecords: 'Nenhuma localização encontrada.',
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

    $('#pesquisaLocalizacoesDT').on('input', function () {
        tabelaLocalizacoes.search(this.value).draw();
    });

    $('#filtroTipoLocalizacaoDT').on('change', function () {
        tabelaLocalizacoes.column(2).search(this.value).draw();
    });

    $('#filtroEstadoLocalizacaoDT').on('change', function () {
        tabelaLocalizacoes.column(6).search(this.value).draw();
    });
});
JS;

require_once __DIR__ . '/../../includes/basedados.php';

$localizacoes = [];
$tiposLocalizacao = [];
$estadosLocalizacao = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'ativas' => 0,
    'manutencao' => 0,
    'inativas' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposLocalizacao = $ligacao
            ->query('SELECT nome FROM tipos_localizacao ORDER BY nome')
            ->fetchAll();

        $estadosLocalizacao = $ligacao
            ->query('SELECT nome FROM estados_localizacao ORDER BY nome')
            ->fetchAll();

        $sqlLocalizacoes = "
            SELECT
                l.id,
                l.codigo,
                l.nome,
                tl.nome AS tipo,
                l.numero_andares,
                l.responsavel,
                l.telefone,
                el.nome AS estado,
                COUNT(e.id) AS total_equipamentos
            FROM localizacoes l
            INNER JOIN tipos_localizacao tl ON tl.id = l.tipo_localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            LEFT JOIN equipamentos e ON e.localizacao_id = l.id
            GROUP BY
                l.id,
                l.codigo,
                l.nome,
                tl.nome,
                l.numero_andares,
                l.responsavel,
                l.telefone,
                el.nome
            ORDER BY l.codigo
        ";

        $localizacoes = $ligacao->query($sqlLocalizacoes)->fetchAll();

        $sqlIndicadores = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN el.nome = 'Ativa' THEN 1 ELSE 0 END) AS ativas,
                SUM(CASE WHEN el.nome = 'Em Manutenção' THEN 1 ELSE 0 END) AS manutencao,
                SUM(CASE WHEN el.nome = 'Inativa' THEN 1 ELSE 0 END) AS inativas
            FROM localizacoes l
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
        ";

        $indicadores = $ligacao->query($sqlIndicadores)->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar as localizações.';
        $localizacoes = [];
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
                    <h4 class="fw-bold mb-1">Localizações</h4>
                    <p class="text-muted small mb-0">
                        Gestão das localizações principais do hospital onde os equipamentos podem estar associados.
                    </p>
                </div>

                <a href="localizacao-nova.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Nova localização
                </a>
            </div>

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>
                    <div>
                        <strong class="d-block">Localização adicionada</strong>
                        <span>A localização foi adicionada com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>

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
                            <p class="text-muted small mb-1">Ativas</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['ativas'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Em manutenção</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['manutencao'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Inativas</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['inativas'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="pesquisaLocalizacoesDT" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaLocalizacoesDT"
                                placeholder="Código, nome, tipo, responsável ou telefone">
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <label for="filtroTipoLocalizacaoDT" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoLocalizacaoDT">
                                <option value="">Todos</option>
                                <?php foreach ($tiposLocalizacao as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo->nome); ?>">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <label for="filtroEstadoLocalizacaoDT" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoLocalizacaoDT">
                                <option value="">Todos</option>
                                <?php foreach ($estadosLocalizacao as $estado): ?>
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
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaLocalizacoes">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>N.º andares</th>
                                    <th>Responsável</th>
                                    <th>Equipamentos</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($localizacoes as $localizacao): ?>
                                    <?php
                                    $classeEstado = 'badge-inativo';

                                    if ($localizacao->estado === 'Ativa') {
                                        $classeEstado = 'badge-ativo';
                                    } elseif ($localizacao->estado === 'Em Manutenção') {
                                        $classeEstado = 'badge-manutencao';
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($localizacao->codigo); ?></td>
                                        <td><?php echo htmlspecialchars($localizacao->nome); ?></td>
                                        <td><?php echo htmlspecialchars($localizacao->tipo); ?></td>
                                        <td><?php echo htmlspecialchars($localizacao->numero_andares); ?></td>
                                        <td><?php echo htmlspecialchars($localizacao->responsavel); ?></td>
                                        <td><?php echo htmlspecialchars($localizacao->total_equipamentos); ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($localizacao->estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="localizacao-detalhes.php?id=<?php echo htmlspecialchars($localizacao->id); ?>"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="localizacao-editar.php?id=<?php echo htmlspecialchars($localizacao->id); ?>"
                                                class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                data-bs-title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <a href="localizacao-eliminar.php?id=<?php echo htmlspecialchars($localizacao->id); ?>"
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
