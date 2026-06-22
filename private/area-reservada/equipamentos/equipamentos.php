<?php

$pageTitle = 'MedInfo Solutions — Equipamentos';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'equipamentos';

$extraCss = [
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.css'
];

$extraScripts = [
    $assetPath . '/jquery/jquery-3.7.1.min.js',
    $assetPath . '/js/jquery.dataTables.min.js',
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'
];


require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();

$equipamentos = [];
$categorias = [];
$estados = [];
$localizacoes = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'ativos' => 0,
    'manutencao' => 0,
    'abatidos' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $sqlEquipamentos = "
            SELECT
                e.id,
                e.codigo,
                e.designacao,
                c.nome AS categoria,
                e.marca,
                e.modelo,
                e.numero_serie,
                l.nome AS localizacao,
                el.nome AS localizacao_estado,
                ee.nome AS estado,
                g.data_fim AS fim_garantia
            FROM equipamentos e
            INNER JOIN categorias_equipamento c ON c.id = e.categoria_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            LEFT JOIN garantias g ON g.equipamento_id = e.id
            ORDER BY e.codigo ASC
        ";

        $equipamentos = $ligacao->query($sqlEquipamentos)->fetchAll();

        $categorias = $ligacao
            ->query('SELECT nome FROM categorias_equipamento ORDER BY nome')
            ->fetchAll();

        $estados = $ligacao
            ->query('SELECT nome FROM estados_equipamento ORDER BY nome')
            ->fetchAll();

        $localizacoes = $ligacao
            ->query('SELECT nome FROM localizacoes ORDER BY nome')
            ->fetchAll();

        $sqlIndicadores = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN ee.nome = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
                SUM(CASE WHEN ee.nome = 'Em Manutenção' THEN 1 ELSE 0 END) AS manutencao,
                SUM(CASE WHEN ee.nome = 'Abatido' THEN 1 ELSE 0 END) AS abatidos
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
        ";

        $indicadores = $ligacao->query($sqlIndicadores)->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar os equipamentos.';
        $equipamentos = [];
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

            <!-- TÍTULO -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Equipamentos</h4>
                    <p class="text-muted small mb-0">
                        Listagem e gestão dos equipamentos médicos registados no inventário.
                    </p>
                </div>

                <a href="equipamento-novo.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo equipamento
                </a>
            </div>

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>

                    <div>
                        <strong class="d-block">Equipamento adicionado</strong>
                        <span>O equipamento foi adicionado com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['editado']) && $_GET['editado'] == '1'): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>

                    <div>
                        <strong class="d-block">Equipamento atualizado</strong>
                        <span>O equipamento foi atualizado com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (((isset($_GET['abatido']) && $_GET['abatido'] == '1') || (isset($_GET['eliminado']) && $_GET['eliminado'] == '1'))): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>

                    <div>
                        <strong class="d-block">Equipamento abatido</strong>
                        <span>O equipamento foi colocado como abatido com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro_eliminar']) && $_GET['erro_eliminar'] == '1'): ?>
                <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>

                    <div>
                        <strong class="d-block">Não foi possível abater</strong>
                        <span>Verifique se o equipamento existe e se existe o estado Abatido na base de dados.</span>
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
                            <h4 class="fw-bold mb-0">
                                <?php echo htmlspecialchars($indicadores['total'] ?? 0); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Ativos</p>
                            <h4 class="fw-bold mb-0">
                                <?php echo htmlspecialchars($indicadores['ativos'] ?? 0); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Em manutenção</p>
                            <h4 class="fw-bold mb-0">
                                <?php echo htmlspecialchars($indicadores['manutencao'] ?? 0); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Abatidos</p>
                            <h4 class="fw-bold mb-0">
                                <?php echo htmlspecialchars($indicadores['abatidos'] ?? 0); ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label for="pesquisaEquipamentosDT" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaEquipamentosDT"
                                placeholder="Código, designação, marca, modelo ou n.º de série">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroCategoriaEquipamentosDT" class="form-label">Categoria</label>
                            <select class="form-select" id="filtroCategoriaEquipamentosDT">
                                <option value="">Todas</option>

                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo htmlspecialchars($categoria->nome); ?>">
                                        <?php echo htmlspecialchars($categoria->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoEquipamentosDT" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoEquipamentosDT">
                                <option value="">Todos</option>

                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?php echo htmlspecialchars($estado->nome); ?>">
                                        <?php echo htmlspecialchars($estado->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroLocalizacaoEquipamentosDT" class="form-label">Localização</label>
                            <select class="form-select" id="filtroLocalizacaoEquipamentosDT">
                                <option value="">Todas</option>

                                <?php foreach ($localizacoes as $localizacao): ?>
                                    <option value="<?php echo htmlspecialchars($localizacao->nome); ?>">
                                        <?php echo htmlspecialchars($localizacao->nome); ?>
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
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaEquipamentos">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>N.º Série</th>
                                    <th>Localização</th>
                                    <th>Estado</th>
                                    <th>Garantia</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($equipamentos as $equipamento): ?>
                                    <?php
                                    $estadoNormalizado = strtolower((string) $equipamento->estado);
                                    $equipamentoAbatido = $estadoNormalizado === 'abatido';
                                    $localizacaoAbatida = in_array(strtolower((string) ($equipamento->localizacao_estado ?? '')), ['inativa', 'abatida'], true);
                                    $classeEstado = 'badge-inativo';

                                    if ($estadoNormalizado === 'ativo') {
                                        $classeEstado = 'badge-ativo';
                                    } elseif ($estadoNormalizado === 'em manutenção' || $estadoNormalizado === 'em manutencao') {
                                        $classeEstado = 'badge-manutencao';
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($equipamento->codigo); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->designacao); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->categoria); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->marca); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->modelo); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->numero_serie); ?></td>
                                        <td><?php echo htmlspecialchars($equipamento->localizacao); ?><?php if ($localizacaoAbatida): ?><br><span class="badge badge-inativo">Localização abatida</span><?php endif; ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($equipamento->estado); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($equipamento->fim_garantia)): ?>
                                                <?php echo htmlspecialchars($equipamento->fim_garantia); ?>
                                            <?php else: ?>
                                                <span class="text-muted small">Sem garantia</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="equipamento-detalhes.php?id_equipamento=<?php echo urlencode(aes_encrypt($equipamento->id)); ?>"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <?php if (!$equipamentoAbatido): ?>
                                                <a href="equipamento-editar.php?id_equipamento=<?php echo urlencode(aes_encrypt($equipamento->id)); ?>"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="equipamento-eliminar.php?id_equipamento=<?php echo urlencode(aes_encrypt($equipamento->id)); ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Abater">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge text-bg-secondary" data-bs-toggle="tooltip" data-bs-title="Equipamento já abatido. Só é possível consultar os detalhes.">
                                                    Sem ações
                                                </span>
                                            <?php endif; ?>
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