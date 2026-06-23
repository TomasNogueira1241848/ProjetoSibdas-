<?php

$pageTitle = 'MedInfo Solutions — Fornecedores';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'fornecedores';

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

exigir_permissao('fornecedores', 'ver');

$fornecedores = [];
$tiposFornecedores = [];
$erroBD = '';

$indicadores = [
    'total' => 0,
    'ativos' => 0,
    'contrato_ativo' => 0,
    'assistencia' => 0
];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposFornecedores = $ligacao
            ->query('SELECT nome FROM tipos_fornecedor ORDER BY nome')
            ->fetchAll();

        $sqlFornecedores = "
            SELECT
                f.id,
                f.nome,
                f.nif,
                tf.nome AS tipo,
                f.email,
                f.telefone,
                CASE WHEN f.contrato_ativo = 1 THEN 'Sim' ELSE 'Não' END AS contrato,
                f.estado
            FROM fornecedores f
            INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id
            ORDER BY f.nome
        ";

        $fornecedores = $ligacao->query($sqlFornecedores)->fetchAll();

        $sqlIndicadores = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN f.estado = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
                SUM(CASE WHEN f.contrato_ativo = 1 THEN 1 ELSE 0 END) AS contrato_ativo,
                SUM(CASE WHEN tf.nome = 'Prestador de assistência técnica' THEN 1 ELSE 0 END) AS assistencia
            FROM fornecedores f
            INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id
        ";

        $indicadores = $ligacao->query($sqlIndicadores)->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar os fornecedores.';
        $fornecedores = [];
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
                    <h4 class="fw-bold mb-1">Fornecedores</h4>
                    <p class="text-muted small mb-0">
                        Listagem e gestão dos fornecedores associados aos equipamentos médicos.
                    </p>
                </div>

                <?php if (tem_permissao('fornecedores', 'criar')): ?>
                    <a href="fornecedor-novo.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Novo fornecedor
                    </a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>
                    <div>
                        <strong class="d-block">Fornecedor adicionado</strong>
                        <span>O fornecedor foi adicionado com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>


            <?php if (isset($_GET['editado']) && $_GET['editado'] == '1'): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>
                    <div>
                        <strong class="d-block">Fornecedor atualizado</strong>
                        <span>O fornecedor foi atualizado com sucesso.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($_GET['descontinuado']) && $_GET['descontinuado'] == '1') || (isset($_GET['abatido']) && $_GET['abatido'] == '1')): ?>
                <div class="alert alert-success d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-check mt-1"></i>
                    <div>
                        <strong class="d-block">Fornecedor descontinuado</strong>
                        <span>O fornecedor foi descontinuado com sucesso. Os equipamentos, documentos, garantias e contratos associados mantêm-se disponíveis.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ((isset($_GET['erro_descontinuar']) && $_GET['erro_descontinuar'] == '1') || (isset($_GET['erro_abate']) && $_GET['erro_abate'] == '1')): ?>
                <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>
                    <div>
                        <strong class="d-block">Não foi possível descontinuar</strong>
                        <span>Verifique se o fornecedor existe.</span>
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
                            <p class="text-muted small mb-1">Ativos</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['ativos'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-info-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Contrato ativo</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['contrato_ativo'] ?? 0); ?></h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Assistência técnica</p>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($indicadores['assistencia'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label for="pesquisaFornecedoresDT" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaFornecedoresDT"
                                placeholder="Nome, NIF, email ou telefone">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroTipoFornecedorDT" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoFornecedorDT">
                                <option value="">Todos</option>
                                <?php foreach ($tiposFornecedores as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo->nome); ?>">
                                        <?php echo htmlspecialchars($tipo->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoFornecedorDT" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoFornecedorDT">
                                <option value="">Todos</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Descontinuado">Descontinuado</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroContratoFornecedorDT" class="form-label">Contrato</label>
                            <select class="form-select" id="filtroContratoFornecedorDT">
                                <option value="">Todos</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaFornecedores">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>NIF</th>
                                    <th>Tipo</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Contrato</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <?php
                                    $fornecedorDescontinuado = in_array(mb_strtolower((string) $fornecedor->estado), ['descontinuado', 'abatido'], true);
                                    $classeEstado = $fornecedor->estado === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                                    ?>

                                    <tr>
                                        <td><?php echo htmlspecialchars($fornecedor->nome); ?></td>
                                        <td><?php echo htmlspecialchars($fornecedor->nif); ?></td>
                                        <td><?php echo htmlspecialchars($fornecedor->tipo); ?></td>
                                        <td><?php echo htmlspecialchars($fornecedor->email); ?></td>
                                        <td><?php echo htmlspecialchars($fornecedor->telefone); ?></td>
                                        <td><?php echo htmlspecialchars($fornecedor->contrato); ?></td>
                                        <td>
                                            <span class="badge <?php echo $classeEstado; ?>">
                                                <?php echo htmlspecialchars($fornecedor->estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="fornecedor-detalhes.php?id_fornecedor=<?php echo urlencode(aes_encrypt($fornecedor->id)); ?>"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <?php if (!$fornecedorDescontinuado && tem_permissao('fornecedores', 'editar')): ?>
                                                <a href="fornecedor-editar.php?id_fornecedor=<?php echo urlencode(aes_encrypt($fornecedor->id)); ?>"
                                                    class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                    data-bs-title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!$fornecedorDescontinuado && tem_permissao('fornecedores', 'remover')): ?>
                                                <a href="fornecedor-eliminar.php?id_fornecedor=<?php echo urlencode(aes_encrypt($fornecedor->id)); ?>"
                                                    class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip"
                                                    data-bs-title="Descontinuar">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </a>
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