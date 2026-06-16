<?php
$pageTitle = 'MedInfo Solutions — Eliminar Contrato';
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
                        <h4 class="fw-bold mb-1">Eliminar contrato</h4>
                        <p class="text-muted small mb-0">Confirmação da eliminação do contrato selecionado.</p>
                    </div>
                </div>

                <!-- CONFIRMAÇÃO DE ELIMINAÇÃO -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        <div class="card p-4 border-danger">
                            <h5 class="fw-bold mb-2">Tem a certeza que pretende eliminar este contrato?</h5>
                            <p class="text-muted mb-4">Esta ação é simulada nesta fase frontend.</p>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="fw-bold mb-0">CON-001</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge badge-ativo">Ativo</span></p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Designação</p>
                                    <p class="mb-0">Contrato de Manutenção UCI</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Fornecedor</p>
                                    <p class="mb-0">Dräger Portugal</p>
                                </div>
                            </div>

                            <div id="mensagemEliminarContrato" class="alert alert-success d-none">
                                <i class="fa-solid fa-check me-1"></i> Contrato eliminado com sucesso.
                            </div>

                            <form id="formEliminarContrato">
                                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                    <a href="contratos.php" class="btn btn-outline-secondary">Cancelar</a>

                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash me-1"></i> Confirmar eliminação
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
