<?php
$pageTitle = 'MedInfo Solutions — Dashboard';
$assetPath = '../../assets';
$loginPath = '../../public/login.php';
$areaPath = '';
$activeMenu = 'dashboard';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

<main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

                <!-- TÍTULO DA PÁGINA -->
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold mb-0">Dashboard</h4>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#tabelaManutencoesDashboard" class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="tooltip" data-bs-title="Ver equipamentos com necessidade de manutenção">
                            <i class="fa-solid fa-calendar-check text-primary me-2"></i> Manutenções
                        </a>
                        <a href="#tabelaGarantiasDashboard" class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="tooltip" data-bs-title="Ver equipamentos com garantia próxima do fim">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Garantias
                        </a>
                        <a href="equipamentos/equipamento-novo.php" class="btn btn-primary btn-sm"
                            data-bs-toggle="tooltip" data-bs-title="Adicione aqui um equipamento">
                            <i class="fa-solid fa-plus me-1"></i> Novo equipamento
                        </a>
                    </div>
                </div>

                <!-- INDICADORES -->
                <section aria-labelledby="tituloIndicadores" class="mb-4">
                    <h5 id="tituloIndicadores" class="fw-bold mb-3">Indicadores principais</h5>
                    <div class="row g-3">
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total de Equipamentos</p>
                                        <h4 class="fw-bold mb-0">142</h4>
                                        <span class="small text-success"><i class="fa-solid fa-arrow-up me-1"></i>+6
                                            este mês</span>
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
                                        <h4 class="fw-bold mb-0">118</h4>
                                        <span class="small text-muted">83,1% do inventário</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-success"><i
                                            class="fa-solid fa-circle-check"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-warning-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Em Manutenção</p>
                                        <h4 class="fw-bold mb-0">14</h4>
                                        <span class="small text-muted">4 críticas</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-warning"><i
                                            class="fa-solid fa-screwdriver-wrench"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-secondary-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Inativos</p>
                                        <h4 class="fw-bold mb-0">10</h4>
                                        <span class="small text-muted">A aguardar decisão</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-secondary"><i
                                            class="fa-solid fa-circle-xmark"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-danger-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Garantia Expirada</p>
                                        <h4 class="fw-bold mb-0">23</h4>
                                        <span class="small text-danger">Prioridade alta</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-danger"><i
                                            class="fa-solid fa-triangle-exclamation"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-info-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Sem Documentação</p>
                                        <h4 class="fw-bold mb-0">8</h4>
                                        <span class="small text-muted">Manuais/certificados</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-info"><i
                                            class="fa-solid fa-file-circle-xmark"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-orange-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Suporte de Vida</p>
                                        <h4 class="fw-bold mb-0">31</h4>
                                        <span class="small text-muted">22% do inventário</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-orange"><i
                                            class="fa-solid fa-heart-pulse"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card card-dashboard p-3 h-100 border-purple-dashboard">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Fornecedores</p>
                                        <h4 class="fw-bold mb-0">17</h4>
                                        <span class="small text-muted">5 com contrato ativo</span>
                                    </div>
                                    <span class="dashboard-icon dashboard-icon-purple"><i
                                            class="fa-solid fa-truck"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- GRÁFICOS -->
                <section aria-labelledby="tituloGraficos" class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="tituloGraficos" class="fw-bold mb-0">Análise do inventário</h5>
                    </div>

                    <div class="row g-3">
                        <div class="col-xl-4 col-lg-6">
                            <div class="card chart-card p-3 h-100">
                                <h6 class="fw-bold mb-3">Equipamentos por Estado</h6>
                                <div class="chart-wrapper">
                                    <canvas id="graficoEstado" aria-label="Gráfico de equipamentos por estado"
                                        role="img"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <div class="card chart-card p-3 h-100">
                                <h6 class="fw-bold mb-3">Equipamentos por Categoria</h6>
                                <div class="chart-wrapper">
                                    <canvas id="graficoCategoria" aria-label="Gráfico de equipamentos por categoria"
                                        role="img"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card chart-card p-3 h-100">
                                <h6 class="fw-bold mb-3">Distribuição por Localização</h6>
                                <div class="chart-wrapper">
                                    <canvas id="graficoLocalizacao" aria-label="Gráfico de equipamentos por localização"
                                        role="img"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- PROGRESSOS E ALERTAS -->
                <section aria-labelledby="tituloOperacional" class="mb-4">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div class="card p-3 h-100">
                                <h6 id="tituloOperacional" class="fw-bold mb-3">
                                    <i class="fa-solid fa-chart-simple me-2 text-primary"></i> Estado operacional
                                </h6>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Equipamentos ativos</span><strong>83%</strong>
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="Equipamentos ativos"
                                        aria-valuenow="83" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success" style="width: 83%"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Manutenção em curso</span><strong>10%</strong>
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="Equipamentos em manutenção"
                                        aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-warning" style="width: 10%"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Documentação incompleta</span><strong>6%</strong>
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="Documentação incompleta"
                                        aria-valuenow="6" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-info" style="width: 6%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Garantias expiradas</span><strong>16%</strong>
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="Garantias expiradas"
                                        aria-valuenow="16" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-danger" style="width: 16%"></div>
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
                                    <span class="badge text-bg-warning">3 alertas</span>
                                </div>

                                <div class="list-group list-group-flush dashboard-alertas">
                                    <div class="list-group-item px-0 d-flex gap-3 align-items-start">
                                        <span class="alerta-icone alerta-critico"><i
                                                class="fa-solid fa-shield-halved"></i></span>
                                        <div>
                                            <strong>Renovar garantias críticas</strong>
                                            <p class="small text-muted mb-0">23 equipamentos têm garantia expirada ou
                                                próxima do fim. Rever contratos associados.</p>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0 d-flex gap-3 align-items-start">
                                        <span class="alerta-icone alerta-aviso"><i
                                                class="fa-solid fa-screwdriver-wrench"></i></span>
                                        <div>
                                            <strong>Validar manutenções pendentes</strong>
                                            <p class="small text-muted mb-0">4 intervenções em equipamentos de suporte
                                                de vida devem ser acompanhadas.</p>
                                        </div>
                                    </div>

                                    <div class="list-group-item px-0 d-flex gap-3 align-items-start">
                                        <span class="alerta-icone alerta-info"><i
                                                class="fa-solid fa-file-medical"></i></span>
                                        <div>
                                            <strong>Completar documentação técnica</strong>
                                            <p class="small text-muted mb-0">Existem 8 registos sem manual, certificado
                                                ou contrato associado.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- TABELAS -->
                <section aria-labelledby="tituloTabelas" class="mb-4">
                    <h5 id="tituloTabelas" class="fw-bold mb-3">Monitorização operacional</h5>

                    <div class="row g-3">

                        <!-- GARANTIAS A EXPIRAR -->
                        <div class="col-xl-6">
                            <div class="card p-3 h-100" id="tabelaGarantiasDashboard">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>
                                        Garantia do equipamento
                                    </h6>

                                    <input type="search" class="form-control form-control-sm pesquisa-dashboard"
                                        id="pesquisaTabela" placeholder="Pesquisar na tabela"
                                        aria-label="Pesquisar garantias">
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-dashboard table-hover align-middle mb-0"
                                        id="tabelaGarantiasConteudo">
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
                                            <tr>
                                                <td>EQ-0042</td>
                                                <td>Monitor Multiparamétrico</td>
                                                <td>UCI</td>
                                                <td>2026-06-10</td>
                                                <td><span class="badge badge-ativo">Ativo</span></td>
                                                <td class="text-center">
                                                    <a href="equipamentos/equipamento-detalhes.php"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-title="Ver equipamento">
                                                        <i class="fa-solid fa-stethoscope"></i>
                                                    </a>

                                                    <a href="contratos/garantia-detalhes.php"
                                                        class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                        data-bs-title="Ver garantia">
                                                        <i class="fa-solid fa-shield"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- PRÓXIMA MANUTENÇÃO PREVENTIVA -->
                        <div class="col-xl-6">
                            <div class="card p-3 h-100" id="tabelaManutencoesDashboard">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fa-solid fa-calendar-check text-primary me-2"></i>
                                        Próxima manutenção preventiva
                                    </h6>

                                    <a href="equipamentos/equipamentos.php#manutencoes"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-list me-1"></i> Ver nos equipamentos
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-dashboard table-hover align-middle mb-0"
                                        id="tabelaManutencoes">
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
                                            <tr>
                                                <td>EQ-0042</td>
                                                <td>Monitor Multiparamétrico</td>
                                                <td>UCI</td>
                                                <td>2025-12-15</td>
                                                <td><span class="badge text-bg-success">Normal</span></td>
                                                <td class="text-center">
                                                    <a href="equipamentos/equipamento-detalhes.php"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-title="Ver equipamento">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <a href="equipamentos/equipamentos.php#manutencoes"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="Ver na tabela de equipamentos">
                                                        <i class="fa-solid fa-calendar-check"></i>
                                                    </a>
                                                </td>
                                            </tr>
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
