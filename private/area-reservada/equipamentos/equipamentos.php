<?php

$pageTitle = 'MedInfo Solutions — Equipamentos';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'equipamentos';

require_once __DIR__ . '/../../includes/basedados.php';

$equipamentos = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'ativos' => 0,
    'manutencao' => 0,
    'inativos' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $sqlEquipamentos = "
            SELECT *
            FROM vw_equipamentos_listagem
            ORDER BY codigo
        ";

        $equipamentos = $ligacao->query($sqlEquipamentos)->fetchAll();

        $sqlIndicadores = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN ee.nome = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
                SUM(CASE WHEN ee.nome = 'Em Manutenção' THEN 1 ELSE 0 END) AS manutencao,
                SUM(CASE WHEN ee.nome = 'Inativo' THEN 1 ELSE 0 END) AS inativos
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
                            <p class="text-muted small mb-1">Inativos</p>
                            <h4 class="fw-bold mb-0">
                                <?php echo htmlspecialchars($indicadores['inativos'] ?? 0); ?>
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
                            <label for="pesquisaEquipamentos" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaEquipamentos"
                                placeholder="Código, nome, marca, modelo ou n.º de série">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroCategoriaEquipamentos" class="form-label">Categoria</label>
                            <select class="form-select" id="filtroCategoriaEquipamentos">
                                <option value="">Todas</option>
                                <option value="Monitorização">Monitorização</option>
                                <option value="Suporte de Vida">Suporte de Vida</option>
                                <option value="Terapia">Terapia</option>
                                <option value="Diagnóstico">Diagnóstico</option>
                                <option value="Laboratório">Laboratório</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoEquipamentos" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoEquipamentos">
                                <option value="">Todos</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Em Manutenção">Em Manutenção</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Em Calibração">Em Calibração</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroLocalizacaoEquipamentos" class="form-label">Localização</label>
                            <select class="form-select" id="filtroLocalizacaoEquipamentos">
                                <option value="">Todas</option>
                                <option value="UCI">UCI</option>
                                <option value="Urgência">Urgência</option>
                                <option value="Bloco Operatório">Bloco Operatório</option>
                                <option value="Medicina Interna">Medicina Interna</option>
                                <option value="Consulta Externa">Consulta Externa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaEquipamentos">
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
                                <?php if (empty($equipamentos)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted small">
                                            Não existem equipamentos registados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($equipamentos as $equipamento): ?>
                                        <?php
                                        $classeEstado = 'badge-inativo';

                                        if ($equipamento->estado === 'Ativo') {
                                            $classeEstado = 'badge-ativo';
                                        } elseif ($equipamento->estado === 'Em Manutenção') {
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
                                            <td><?php echo htmlspecialchars($equipamento->localizacao); ?></td>
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
                                                <a href="equipamento-detalhes.php?id=<?php echo htmlspecialchars($equipamento->id); ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Ver detalhes">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <a href="equipamento-editar.php?id=<?php echo htmlspecialchars($equipamento->id); ?>"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="equipamento-eliminar.php?id=<?php echo htmlspecialchars($equipamento->id); ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>