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
                    <p class="text-muted small mb-0">Consulta da ficha do fornecedor selecionado.</p>
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

                <!-- DADOS PRINCIPAIS -->
                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Philips Healthcare Portugal</h5>
                                <p class="text-muted small mb-0">NIF: 501234567</p>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Ativo</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Fabricante</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Contrato ativo</p>
                                <p class="mb-0">Sim</p>
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
                                <p class="text-muted small mb-1">Área de atuação</p>
                                <p class="mb-0">Monitorização e equipamentos de diagnóstico</p>
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
                                    Fornecedor principal de equipamentos de monitorização. Possui contrato de
                                    assistência técnica ativo.
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
                            Fornecedor ativo no sistema.
                        </div>

                        <div class="alert alert-warning small">
                            <i class="fa-solid fa-file-contract me-1"></i>
                            Contrato de manutenção associado a equipamentos críticos.
                        </div>

                        <div class="alert alert-light border small mb-0">
                            <i class="fa-solid fa-stethoscope me-1"></i>
                            Associado a monitores multiparamétricos e equipamentos de diagnóstico.
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>