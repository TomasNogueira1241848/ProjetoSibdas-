<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Fornecedor';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'fornecedores';

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
                    <h4 class="fw-bold mb-1">Detalhes do fornecedor</h4>
                    <p class="text-muted small mb-0">Consulta da ficha do fornecedor selecionado e dos equipamentos associados.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="fornecedor-editar.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen me-1"></i> Editar
                    </a>

                    <a href="fornecedores.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="row g-3">

                <!-- DADOS DO FORNECEDOR -->
                <div class="col-lg-5">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Philips Healthcare Portugal</h5>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Ativo</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">NIF</p>
                                <p class="mb-0">501234567</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo de fornecedor</p>
                                <p class="mb-0">Fabricante</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Email</p>
                                <p class="mb-0">suporte@philips.pt</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Telefone</p>
                                <p class="mb-0">+351 211 234 567</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Website</p>
                                <p class="mb-0">www.philips.pt/healthcare</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Contrato ativo</p>
                                <p class="mb-0">Sim</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Pessoa responsável</p>
                                <p class="mb-0">Eng.ª Ana Martins</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Telefone da pessoa responsável</p>
                                <p class="mb-0">+351 913 456 789</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Área de atuação</p>
                                <p class="mb-0">Monitorização e diagnóstico</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Equipamentos associados</p>
                                <p class="mb-0">18 equipamentos</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Morada</p>
                                <p class="mb-0">Av. da República, 90, 1050-190 Lisboa</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0">
                                    Entidade associada ao fornecimento e fabrico de equipamentos de monitorização hospitalar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EQUIPAMENTOS ASSOCIADOS -->
                <div class="col-lg-7">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">Equipamentos associados a este fornecedor</h6>

                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Ligação</th>
                                        <th>Localização</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>EQ-0042</td>
                                        <td>Monitor Multiparamétrico</td>
                                        <td>Fornecedor principal / fabricante</td>
                                        <td>UCI — Sala UCI-02</td>
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

