<?php
$pageTitle = 'MedInfo Solutions — Dashboard';
$assetPath = '../../assets';
$loginPath = '../../public/login.php';
$areaPath = '';
$activeMenu = 'dashboard';
$extraCss = [$assetPath . '/bootstrap/dataTables.bootstrap5.min.css'];
$extraScripts = [
    $assetPath . '/jquery/jquery-3.7.1.min.js',
    $assetPath . '/js/jquery.dataTables.min.js',
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'
];

require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../includes/basedados.php';

redirect_if_not_logged();
exigir_permissao('dashboard', 'ver');

function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function contar_dashboard($ligacao, $sql, $params = [])
{
    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function linhas_dashboard($ligacao, $sql, $params = [])
{
    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function percentagem_dashboard($parte, $total)
{
    $total = (int) $total;
    if ($total <= 0) {
        return 0;
    }

    return round(((int) $parte / $total) * 100, 1);
}

function data_dashboard($data)
{
    if (empty($data) || $data === '0000-00-00') {
        return '—';
    }

    $timestamp = strtotime((string) $data);
    return $timestamp ? date('d/m/Y', $timestamp) : (string) $data;
}

function badge_estado_dashboard($estado)
{
    $estado = trim((string) ($estado ?? ''));
    $texto = $estado !== '' ? $estado : 'Não indicado';
    $normalizado = strtolower($texto);
    $classe = 'badge-inativo';

    if (in_array($normalizado, ['ativo', 'ativa', 'válido', 'valido', 'em dia'], true)) {
        $classe = 'badge-ativo';
    } elseif (str_contains($normalizado, 'manutenção') || str_contains($normalizado, 'manutencao') || str_contains($normalizado, 'expirar') || str_contains($normalizado, 'agendada') || str_contains($normalizado, 'pendente') || str_contains($normalizado, 'por rever')) {
        $classe = 'badge-manutencao';
    }

    return '<span class="badge ' . $classe . '">' . e($texto) . '</span>';
}

function grafico_dashboard($linhas)
{
    $labels = [];
    $valores = [];

    foreach ($linhas as $linha) {
        $labels[] = (string) ($linha['nome'] ?? 'Sem dados');
        $valores[] = (int) ($linha['total'] ?? 0);
    }

    return [
        'labels' => $labels,
        'valores' => $valores
    ];
}

function link_equipamento_dashboard($id)
{
    return 'equipamentos/equipamento-detalhes.php?id_equipamento=' . urlencode(aes_encrypt((int) $id));
}

function link_garantia_dashboard($id)
{
    return 'contratos/garantia-detalhes.php?id_garantia=' . urlencode(aes_encrypt((int) $id));
}

$erroBD = '';

$indicadores = [
    'total_equipamentos' => 0,
    'ativos' => 0,
    'em_manutencao' => 0,
    'inativos' => 0,
    'garantias_expiradas' => 0,
    'sem_documentacao' => 0,
    'garantias_30_dias' => 0,
    'criticidade_alta' => 0,
    'suporte_vida' => 0,
    'fornecedores' => 0,
    'fornecedores_contrato_ativo' => 0,
];

$graficos = [
    'estados' => ['labels' => [], 'valores' => []],
    'categorias' => ['labels' => [], 'valores' => []],
    'localizacoes' => ['labels' => [], 'valores' => []],
];

$equipamentosPorServico = [];
$suporteVidaPorServico = [];
$garantiasMonitorizacao = [];
$manutencoesMonitorizacao = [];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados. O dashboard não conseguiu carregar os indicadores.';
} else {
    try {
        $indicadores['total_equipamentos'] = contar_dashboard($ligacao, 'SELECT COUNT(*) FROM equipamentos');

        $indicadores['ativos'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            WHERE LOWER(ee.nome) IN ('ativo', 'ativa')
        ");

        $indicadores['em_manutencao'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            WHERE LOWER(ee.nome) IN ('em manutenção', 'em manutencao')
        ");

        $indicadores['inativos'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            WHERE LOWER(ee.nome) IN ('inativo', 'inativa')
        ");

        $indicadores['garantias_expiradas'] = contar_dashboard($ligacao, "
            SELECT COUNT(DISTINCT g.equipamento_id)
            FROM garantias g
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            WHERE g.equipamento_id IS NOT NULL
              AND LOWER(eg.nome) <> 'cancelado'
              AND (LOWER(eg.nome) IN ('expirado', 'expirada') OR (g.data_fim IS NOT NULL AND g.data_fim < CURDATE()))
        ");

        $indicadores['sem_documentacao'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            WHERE NOT EXISTS (
                SELECT 1
                FROM documentos d
                WHERE d.equipamento_id = e.id
            )
        ");

        $indicadores['garantias_30_dias'] = contar_dashboard($ligacao, "
            SELECT COUNT(DISTINCT g.equipamento_id)
            FROM garantias g
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            WHERE g.equipamento_id IS NOT NULL
              AND g.data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
              AND LOWER(eg.nome) NOT IN ('cancelado', 'expirado', 'expirada')
        ");

        $indicadores['criticidade_alta'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM equipamentos e
            INNER JOIN criticidades c ON c.id = e.criticidade_id
            WHERE LOWER(c.nome) = 'alta'
        ");

        $indicadores['suporte_vida'] = contar_dashboard($ligacao, "
            SELECT COUNT(DISTINCT e.id)
            FROM equipamentos e
            LEFT JOIN criticidades cr ON cr.id = e.criticidade_id
            LEFT JOIN categorias_equipamento ce ON ce.id = e.categoria_id
            WHERE LOWER(COALESCE(cr.nome, '')) LIKE '%suporte%'
               OR LOWER(COALESCE(ce.nome, '')) LIKE '%suporte%'
        ");

        $indicadores['fornecedores'] = contar_dashboard($ligacao, 'SELECT COUNT(*) FROM fornecedores');

        $indicadores['fornecedores_contrato_ativo'] = contar_dashboard($ligacao, "
            SELECT COUNT(*)
            FROM fornecedores
            WHERE contrato_ativo = 1
              AND LOWER(COALESCE(estado, '')) <> 'descontinuado'
        ");

        $graficos['estados'] = grafico_dashboard(linhas_dashboard($ligacao, "
            SELECT ee.nome AS nome, COUNT(*) AS total
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            GROUP BY ee.nome
            ORDER BY total DESC, ee.nome
        "));

        $graficos['categorias'] = grafico_dashboard(linhas_dashboard($ligacao, "
            SELECT ce.nome AS nome, COUNT(*) AS total
            FROM equipamentos e
            INNER JOIN categorias_equipamento ce ON ce.id = e.categoria_id
            GROUP BY ce.nome
            ORDER BY total DESC, ce.nome
        "));

        $graficos['localizacoes'] = grafico_dashboard(linhas_dashboard($ligacao, "
            SELECT l.nome AS nome, COUNT(*) AS total
            FROM equipamentos e
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            GROUP BY l.nome
            ORDER BY total DESC, l.nome
            LIMIT 8
        "));

        $equipamentosPorServico = linhas_dashboard($ligacao, "
            SELECT COALESCE(NULLIF(TRIM(e.servico), ''), 'Sem serviço') AS nome, COUNT(*) AS total
            FROM equipamentos e
            GROUP BY COALESCE(NULLIF(TRIM(e.servico), ''), 'Sem serviço')
            ORDER BY total DESC, nome
            LIMIT 8
        ");

        $suporteVidaPorServico = linhas_dashboard($ligacao, "
            SELECT COALESCE(NULLIF(TRIM(e.servico), ''), 'Sem serviço') AS nome, COUNT(DISTINCT e.id) AS total
            FROM equipamentos e
            LEFT JOIN criticidades cr ON cr.id = e.criticidade_id
            LEFT JOIN categorias_equipamento ce ON ce.id = e.categoria_id
            WHERE LOWER(COALESCE(cr.nome, '')) LIKE '%suporte%'
               OR LOWER(COALESCE(ce.nome, '')) LIKE '%suporte%'
            GROUP BY COALESCE(NULLIF(TRIM(e.servico), ''), 'Sem serviço')
            ORDER BY total DESC, nome
            LIMIT 8
        ");

        $garantiasMonitorizacao = linhas_dashboard($ligacao, "
            SELECT
                g.id AS garantia_id,
                e.id AS equipamento_id,
                e.codigo,
                e.designacao,
                COALESCE(NULLIF(TRIM(e.servico), ''), '—') AS servico,
                g.data_fim,
                CASE WHEN eg.nome = 'Expirada' THEN 'Expirado' ELSE eg.nome END AS estado
            FROM garantias g
            INNER JOIN equipamentos e ON e.id = g.equipamento_id
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            WHERE LOWER(eg.nome) <> 'cancelado'
              AND g.data_fim IS NOT NULL
              AND (
                    g.data_fim <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                 OR LOWER(eg.nome) IN ('expirado', 'expirada', 'a expirar')
              )
            ORDER BY
                CASE WHEN g.data_fim < CURDATE() THEN 0 ELSE 1 END,
                g.data_fim ASC,
                e.codigo ASC
            LIMIT 8
        ");

        $manutencoesMonitorizacao = linhas_dashboard($ligacao, "
            SELECT
                m.id AS manutencao_id,
                e.id AS equipamento_id,
                e.codigo,
                e.designacao,
                COALESCE(NULLIF(TRIM(l.nome), ''), NULLIF(TRIM(e.servico), ''), '—') AS localizacao,
                m.proxima_manutencao,
                COALESCE(pm.nome, 'Normal') AS prioridade,
                COALESCE(em.nome, '—') AS estado
            FROM manutencoes m
            INNER JOIN equipamentos e ON e.id = m.equipamento_id
            LEFT JOIN localizacoes l ON l.id = e.localizacao_id
            LEFT JOIN prioridades_manutencao pm ON pm.id = m.prioridade_id
            LEFT JOIN estados_manutencao em ON em.id = m.estado_manutencao_id
            WHERE m.proxima_manutencao IS NOT NULL
            ORDER BY m.proxima_manutencao ASC, e.codigo ASC
            LIMIT 8
        ");
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar os indicadores do dashboard.';
    }
}

$totalEquipamentos = $indicadores['total_equipamentos'];
$percentAtivos = percentagem_dashboard($indicadores['ativos'], $totalEquipamentos);
$percentManutencao = percentagem_dashboard($indicadores['em_manutencao'], $totalEquipamentos);
$percentSemDocumentacao = percentagem_dashboard($indicadores['sem_documentacao'], $totalEquipamentos);
$percentGarantiaExpirada = percentagem_dashboard($indicadores['garantias_expiradas'], $totalEquipamentos);
$percentSuporteVida = percentagem_dashboard($indicadores['suporte_vida'], $totalEquipamentos);

$alertas = [];

if ($indicadores['garantias_expiradas'] > 0 || $indicadores['garantias_30_dias'] > 0) {
    $alertas[] = [
        'classe' => 'alerta-critico',
        'icone' => 'fa-shield-halved',
        'titulo' => 'Rever garantias críticas',
        'texto' => $indicadores['garantias_expiradas'] . ' equipamento(s) com garantia expirada e ' . $indicadores['garantias_30_dias'] . ' garantia(s) a expirar nos próximos 30 dias.'
    ];
}

if ($indicadores['em_manutencao'] > 0) {
    $alertas[] = [
        'classe' => 'alerta-aviso',
        'icone' => 'fa-screwdriver-wrench',
        'titulo' => 'Acompanhar equipamentos em manutenção',
        'texto' => $indicadores['em_manutencao'] . ' equipamento(s) encontram-se com estado de manutenção.'
    ];
}

if ($indicadores['sem_documentacao'] > 0) {
    $alertas[] = [
        'classe' => 'alerta-info',
        'icone' => 'fa-file-medical',
        'titulo' => 'Completar documentação técnica',
        'texto' => 'Existem ' . $indicadores['sem_documentacao'] . ' equipamento(s) sem documentação associada.'
    ];
}

if ($indicadores['criticidade_alta'] > 0) {
    $alertas[] = [
        'classe' => 'alerta-aviso',
        'icone' => 'fa-heart-pulse',
        'titulo' => 'Validar equipamentos críticos',
        'texto' => $indicadores['criticidade_alta'] . ' equipamento(s) estão classificados com criticidade elevada.'
    ];
}

$pageScript = 'window.dashboardData = ' . json_encode($graficos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h4 class="fw-bold mb-0">Bem-vindo, <?php echo e(utilizador_nome()); ?></h4>
                        <span class="badge bg-light text-dark border"><?php echo e(perfil_nome()); ?></span>
                    </div>
                    <p class="text-muted mb-0">Visão rápida do estado global do parque tecnológico hospitalar.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#tabelaManutencoesDashboard" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="Ver próximas manutenções">
                        <i class="fa-solid fa-calendar-check text-primary me-2"></i> Manutenções
                    </a>
                    <a href="#tabelaGarantiasDashboard" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="Ver garantias expiradas ou próximas do fim">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Garantias
                    </a>
                    <?php if (tem_permissao('equipamentos', 'criar')): ?>
                        <a href="equipamentos/equipamento-novo.php" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="Adicionar equipamento">
                            <i class="fa-solid fa-plus me-1"></i> Novo equipamento
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($_SESSION['erros_permissao'])): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fa-solid fa-lock me-2"></i><?php echo e($_SESSION['erros_permissao'][0]); unset($_SESSION['erros_permissao']); ?>
                </div>
            <?php endif; ?>

            <?php if ($erroBD !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo e($erroBD); ?>
                </div>
            <?php endif; ?>

            <section aria-labelledby="tituloIndicadores" class="mb-4">
                <h5 id="tituloIndicadores" class="fw-bold mb-3">Indicadores principais</h5>
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Total de equipamentos</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['total_equipamentos']); ?></h4>
                                    <span class="small text-muted">Registos na base de dados</span>
                                </div>
                                <span class="dashboard-icon"><i class="fa-solid fa-stethoscope"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-success-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Ativos</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['ativos']); ?></h4>
                                    <span class="small text-muted"><?php echo e(str_replace('.', ',', (string) $percentAtivos)); ?>% do inventário</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-success"><i class="fa-solid fa-circle-check"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-warning-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Em manutenção</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['em_manutencao']); ?></h4>
                                    <span class="small text-muted"><?php echo e(str_replace('.', ',', (string) $percentManutencao)); ?>% do inventário</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-warning"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-secondary-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Inativos</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['inativos']); ?></h4>
                                    <span class="small text-muted">Equipamentos fora de utilização</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-secondary"><i class="fa-solid fa-circle-xmark"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-danger-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Garantia expirada</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['garantias_expiradas']); ?></h4>
                                    <span class="small text-danger"><?php echo e(str_replace('.', ',', (string) $percentGarantiaExpirada)); ?>% do inventário</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-danger"><i class="fa-solid fa-triangle-exclamation"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-info-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Sem documentação</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['sem_documentacao']); ?></h4>
                                    <span class="small text-muted"><?php echo e(str_replace('.', ',', (string) $percentSemDocumentacao)); ?>% do inventário</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-info"><i class="fa-solid fa-file-circle-xmark"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-orange-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Garantias a 30 dias</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['garantias_30_dias']); ?></h4>
                                    <span class="small text-muted">A expirar brevemente</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-orange"><i class="fa-solid fa-hourglass-half"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-danger-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Criticidade elevada</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['criticidade_alta']); ?></h4>
                                    <span class="small text-muted">Equipamentos críticos</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-danger"><i class="fa-solid fa-circle-exclamation"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-orange-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Suporte de vida</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['suporte_vida']); ?></h4>
                                    <span class="small text-muted"><?php echo e(str_replace('.', ',', (string) $percentSuporteVida)); ?>% do inventário</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-orange"><i class="fa-solid fa-heart-pulse"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-dashboard p-3 h-100 border-purple-dashboard">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted small mb-1">Fornecedores</p>
                                    <h4 class="fw-bold mb-0"><?php echo e($indicadores['fornecedores']); ?></h4>
                                    <span class="small text-muted"><?php echo e($indicadores['fornecedores_contrato_ativo']); ?> com contrato ativo</span>
                                </div>
                                <span class="dashboard-icon dashboard-icon-purple"><i class="fa-solid fa-truck"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-labelledby="tituloGraficos" class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 id="tituloGraficos" class="fw-bold mb-0">Análise do inventário</h5>
                </div>

                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6">
                        <div class="card chart-card p-3 h-100">
                            <h6 class="fw-bold mb-3">Equipamentos por estado</h6>
                            <div class="chart-wrapper">
                                <canvas id="graficoEstado" aria-label="Gráfico de equipamentos por estado" role="img"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6">
                        <div class="card chart-card p-3 h-100">
                            <h6 class="fw-bold mb-3">Equipamentos por categoria</h6>
                            <div class="chart-wrapper">
                                <canvas id="graficoCategoria" aria-label="Gráfico de equipamentos por categoria" role="img"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card chart-card p-3 h-100">
                            <h6 class="fw-bold mb-3">Distribuição por localização</h6>
                            <div class="chart-wrapper">
                                <canvas id="graficoLocalizacao" aria-label="Gráfico de equipamentos por localização" role="img"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-labelledby="tituloOperacional" class="mb-4">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <div class="card p-3 h-100">
                            <h6 id="tituloOperacional" class="fw-bold mb-3">
                                <i class="fa-solid fa-chart-simple me-2 text-primary"></i> Estado operacional
                            </h6>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Equipamentos ativos</span><strong><?php echo e(str_replace('.', ',', (string) $percentAtivos)); ?>%</strong>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Equipamentos ativos" aria-valuenow="<?php echo e($percentAtivos); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-success" style="width: <?php echo e($percentAtivos); ?>%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Manutenção em curso</span><strong><?php echo e(str_replace('.', ',', (string) $percentManutencao)); ?>%</strong>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Equipamentos em manutenção" aria-valuenow="<?php echo e($percentManutencao); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-warning" style="width: <?php echo e($percentManutencao); ?>%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Documentação incompleta</span><strong><?php echo e(str_replace('.', ',', (string) $percentSemDocumentacao)); ?>%</strong>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Documentação incompleta" aria-valuenow="<?php echo e($percentSemDocumentacao); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-info" style="width: <?php echo e($percentSemDocumentacao); ?>%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Garantias expiradas</span><strong><?php echo e(str_replace('.', ',', (string) $percentGarantiaExpirada)); ?>%</strong>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Garantias expiradas" aria-valuenow="<?php echo e($percentGarantiaExpirada); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-danger" style="width: <?php echo e($percentGarantiaExpirada); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fa-solid fa-bell me-2 text-warning"></i> Ações prioritárias
                                </h6>
                                <span class="badge text-bg-warning"><?php echo e(count($alertas)); ?> alerta(s)</span>
                            </div>

                            <div class="list-group list-group-flush dashboard-alertas">
                                <?php if (empty($alertas)): ?>
                                    <div class="list-group-item px-0 d-flex gap-3 align-items-start">
                                        <span class="alerta-icone alerta-info"><i class="fa-solid fa-circle-check"></i></span>
                                        <div>
                                            <strong>Sem alertas prioritários</strong>
                                            <p class="small text-muted mb-0">Não existem garantias críticas, equipamentos sem documentação ou equipamentos em manutenção para destacar.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($alertas as $alerta): ?>
                                        <div class="list-group-item px-0 d-flex gap-3 align-items-start">
                                            <span class="alerta-icone <?php echo e($alerta['classe']); ?>"><i class="fa-solid <?php echo e($alerta['icone']); ?>"></i></span>
                                            <div>
                                                <strong><?php echo e($alerta['titulo']); ?></strong>
                                                <p class="small text-muted mb-0"><?php echo e($alerta['texto']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-labelledby="tituloServicos" class="mb-4">
                <h5 id="tituloServicos" class="fw-bold mb-3">Resumo por serviço</h5>
                <div class="row g-3">
                    <div class="col-xl-6">
                        <div class="card p-3 h-100">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                <h6 class="fw-bold mb-0"><i class="fa-solid fa-hospital me-2 text-primary"></i> Equipamentos por serviço</h6>
                                <input type="search" class="form-control form-control-sm pesquisa-dashboard" id="pesquisaServicoDashboard" placeholder="Pesquisar serviço" aria-label="Pesquisar serviço">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaServicosDashboard">
                                    <thead>
                                        <tr>
                                            <th>Serviço</th>
                                            <th class="text-end">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($equipamentosPorServico as $linha): ?>
                                            <tr>
                                                <td><?php echo e($linha['nome']); ?></td>
                                                <td class="text-end fw-semibold"><?php echo e($linha['total']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card p-3 h-100">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                <h6 class="fw-bold mb-0"><i class="fa-solid fa-heart-pulse me-2 text-danger"></i> Suporte de vida por serviço</h6>
                                <input type="search" class="form-control form-control-sm pesquisa-dashboard" id="pesquisaSuporteVidaDashboard" placeholder="Pesquisar serviço" aria-label="Pesquisar suporte de vida por serviço">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaSuporteVidaDashboard">
                                    <thead>
                                        <tr>
                                            <th>Serviço</th>
                                            <th class="text-end">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($suporteVidaPorServico as $linha): ?>
                                            <tr>
                                                <td><?php echo e($linha['nome']); ?></td>
                                                <td class="text-end fw-semibold"><?php echo e($linha['total']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-labelledby="tituloTabelas" class="mb-4">
                <h5 id="tituloTabelas" class="fw-bold mb-3">Monitorização operacional</h5>

                <div class="row g-3">
                    <div class="col-xl-6">
                        <div class="card p-3 h-100" id="tabelaGarantiasDashboard">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>
                                    Garantias expiradas ou a expirar
                                </h6>

                                <input type="search" class="form-control form-control-sm pesquisa-dashboard" id="pesquisaGarantiasDashboard" placeholder="Pesquisar na tabela" aria-label="Pesquisar garantias">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaGarantiasConteudo">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Designação</th>
                                            <th>Serviço</th>
                                            <th>Fim</th>
                                            <th>Estado</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($garantiasMonitorizacao as $garantia): ?>
                                            <tr>
                                                <td><?php echo e($garantia['codigo']); ?></td>
                                                <td><?php echo e($garantia['designacao']); ?></td>
                                                <td><?php echo e($garantia['servico']); ?></td>
                                                <td data-order="<?php echo e($garantia['data_fim']); ?>"><?php echo e(data_dashboard($garantia['data_fim'])); ?></td>
                                                <td><?php echo badge_estado_dashboard($garantia['estado']); ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo e(link_equipamento_dashboard($garantia['equipamento_id'])); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Ver equipamento">
                                                        <i class="fa-solid fa-stethoscope"></i>
                                                    </a>
                                                    <a href="<?php echo e(link_garantia_dashboard($garantia['garantia_id'])); ?>" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-title="Ver garantia">
                                                        <i class="fa-solid fa-shield"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card p-3 h-100" id="tabelaManutencoesDashboard">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="fa-solid fa-calendar-check text-primary me-2"></i>
                                    Próxima manutenção preventiva
                                </h6>

                                <input type="search" class="form-control form-control-sm pesquisa-dashboard" id="pesquisaManutencoesDashboard" placeholder="Pesquisar na tabela" aria-label="Pesquisar manutenções">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaManutencoes">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Equipamento</th>
                                            <th>Local</th>
                                            <th>Data</th>
                                            <th>Prioridade</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($manutencoesMonitorizacao as $manutencao): ?>
                                            <tr>
                                                <td><?php echo e($manutencao['codigo']); ?></td>
                                                <td><?php echo e($manutencao['designacao']); ?></td>
                                                <td><?php echo e($manutencao['localizacao']); ?></td>
                                                <td data-order="<?php echo e($manutencao['proxima_manutencao']); ?>"><?php echo e(data_dashboard($manutencao['proxima_manutencao'])); ?></td>
                                                <td><?php echo badge_estado_dashboard($manutencao['prioridade']); ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo e(link_equipamento_dashboard($manutencao['equipamento_id'])); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Ver equipamento">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>