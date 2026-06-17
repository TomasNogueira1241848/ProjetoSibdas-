<?php
$pageTitle = 'MedInfo Solutions — Detalhes da Garantia';
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
                    <h4 class="fw-bold mb-1">Detalhes da garantia</h4>
                    <p class="text-muted small mb-0">Consulta da garantia associada ao equipamento selecionado.</p>
                </div>

                <a href="contratos.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <div class="row g-3">

                <!-- DADOS DA GARANTIA -->
                <div class="col-lg-5">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Garantia Monitor Multiparamétrico</h5>
                                <p class="text-muted small mb-0">Código: GAR-001</p>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Ativo</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Designação</p>
                                <p class="mb-0">Garantia Monitor Multiparamétrico</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Garantia</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Fornecedor</p>
                                <p class="mb-0">Philips Healthcare Portugal</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Equipamento associado</p>
                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Responsável</p>
                                <p class="mb-0">Téc. João Ferreira</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Contrato associado</p>
                                <p class="mb-0">CON-001 — Contrato de Manutenção UCI</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Data de início</p>
                                <p class="mb-0">10/06/2023</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Data de fim</p>
                                <p class="mb-0">10/06/2026</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado</p>
                                <p class="mb-0">Ativo</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Documento da garantia</p>
                                <p class="mb-0">
                                    <i class="fa-solid fa-file-pdf text-danger me-1"></i>
                                    garantia-monitor-multiparametrico.pdf
                                </p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Cobertura</p>
                                <p class="mb-0">
                                    Cobre avarias técnicas, substituição de peças e apoio técnico durante o período de validade.
                                </p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0">
                                    Garantia associada ao monitor multiparamétrico utilizado na UCI.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EQUIPAMENTOS ASSOCIADOS -->
                <div class="col-lg-7">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">Equipamentos abrangidos por esta garantia</h6>

                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Localização</th>
                                        <th>Contrato associado</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>EQ-0042</td>
                                        <td>Monitor Multiparamétrico</td>
                                        <td>UCI — Sala UCI-02</td>
                                        <td>CON-001</td>
                                        <td><span class="badge badge-ativo">Ativo</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
