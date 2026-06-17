<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Documento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'documentacao';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do documento</h4>
                    <p class="text-muted small mb-0">Consulta da informação associada ao documento selecionado.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="documentacao.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Manual do Monitor Multiparamétrico</h5>
                                <p class="text-muted small mb-0">Código: DOC-001</p>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Válido</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Manual de utilizador</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Área</p>
                                <p class="mb-0">Equipamento</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Associado a</p>
                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Responsável</p>
                                <p class="mb-0">Téc. João Ferreira</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                <p class="mb-0">Philips Healthcare Portugal</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Data do documento</p>
                                <p class="mb-0">2025-03-12</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Validade</p>
                                <p class="mb-0">2027-03-12</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Ficheiro</p>
                                <p class="mb-0">
                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i>
                                    manual-monitor-multiparametrico.pdf
                                </p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0">
                                    Manual técnico associado ao monitor multiparamétrico utilizado na UCI.
                                    Documento necessário para consulta técnica e procedimentos de manutenção.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-circle-info me-2 text-primary"></i> Informação rápida
                        </h6>

                        <div class="alert alert-success small">
                            <i class="fa-solid fa-check me-1"></i>
                            Documento válido e disponível.
                        </div>

                        <div class="alert alert-light border small">
                            <i class="fa-solid fa-stethoscope me-1"></i>
                            Associado ao equipamento EQ-0042.
                        </div>

                        <div class="alert alert-light border small mb-0">
                            <i class="fa-solid fa-file-pdf me-1"></i>
                            manual-monitor-multiparametrico.pdf
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>