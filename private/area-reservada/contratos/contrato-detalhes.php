<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Contrato';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'contratos';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <!-- TÍTULO E AÇÕES -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do contrato</h4>
                    <p class="text-muted small mb-0">Consulta da informação do contrato selecionado.</p>
                </div>

                <a href="contratos.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <div class="row g-3">

                <!-- DADOS DO CONTRATO -->
                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Contrato de Manutenção UCI</h5>
                                <p class="text-muted small mb-0">Código: CON-001</p>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Ativo</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Manutenção</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Associado a</p>
                                <p class="mb-0">Equipamentos UCI</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Fornecedor</p>
                                <p class="mb-0">Dräger Portugal</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Responsável</p>
                                <p class="mb-0">Eng.ª Mariana Silva</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Data de início</p>
                                <p class="mb-0">01/01/2024</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Data de fim</p>
                                <p class="mb-0">31/12/2025</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Valor anual</p>
                                <p class="mb-0">4 800€</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Renovação automática</p>
                                <p class="mb-0">Sim</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0">
                                    Contrato associado à manutenção preventiva dos equipamentos da UCI, incluindo
                                    assistência técnica programada e apoio em caso de avaria.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFORMAÇÃO RÁPIDA -->
                <div class="col-lg-4">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i> Informação rápida
                        </h6>

                        <div class="alert alert-success small">
                            <i class="fa-solid fa-check me-1"></i>
                            Contrato ativo.
                        </div>

                        <div class="alert alert-light border small">
                            <i class="fa-solid fa-truck-medical me-1"></i>
                            Fornecedor: Dräger Portugal.
                        </div>

                        <div class="alert alert-light border small">
                            <i class="fa-solid fa-rotate me-1"></i>
                            Renovação automática: Sim.
                        </div>

                        <div class="alert alert-warning small mb-0">
                            <i class="fa-solid fa-calendar-days me-1"></i>
                            Termina em 31/12/2025.
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>