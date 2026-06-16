<?php
$pageTitle = 'MedInfo Solutions — Eliminar Fornecedor';
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
                    <h4 class="fw-bold mb-1">Eliminar fornecedor</h4>
                    <p class="text-muted small mb-0">Confirmação da eliminação do fornecedor selecionado.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card p-4 border-danger">
                        <div class="d-flex gap-3 align-items-start mb-3">
                            <div class="dashboard-icon dashboard-icon-danger">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">Tem a certeza que pretende eliminar este fornecedor?</h5>
                                <p class="text-muted mb-0">
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Nome</p>
                                <p class="fw-bold mb-0">Philips Healthcare Portugal</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado</p>
                                <p class="mb-0"><span class="badge badge-ativo">Ativo</span></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">NIF</p>
                                <p class="mb-0">501234567</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Fabricante</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Email</p>
                                <p class="mb-0">suporte@philips.pt</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Contrato ativo</p>
                                <p class="mb-0">Sim</p>
                            </div>
                        </div>

                        <div id="mensagemEliminarFornecedor" class="alert alert-success d-none">
                            <i class="fa-solid fa-check me-1"></i> Fornecedor eliminado com sucesso.
                        </div>

                        <form id="formEliminarFornecedor">
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="fornecedores.php" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>

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