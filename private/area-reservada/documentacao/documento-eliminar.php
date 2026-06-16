<?php
$pageTitle = 'MedInfo Solutions — Eliminar Documento';
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
                    <h4 class="fw-bold mb-1">Eliminar documento</h4>
                    <p class="text-muted small mb-0">Confirmação da eliminação do documento selecionado.</p>
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
                                <h5 class="fw-bold mb-1">Tem a certeza que pretende eliminar este documento?</h5>
                                <p class="text-muted mb-0">
                                    Esta ação é apenas simulada nesta fase frontend. Na fase de backend, o registo
                                    será removido da base de dados.
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Código</p>
                                <p class="fw-bold mb-0">DOC-001</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado</p>
                                <p class="mb-0"><span class="badge badge-ativo">Válido</span></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Título</p>
                                <p class="mb-0">Manual do Monitor Multiparamétrico</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo</p>
                                <p class="mb-0">Manual de utilizador</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Associado a</p>
                                <p class="mb-0">EQ-0042</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Ficheiro</p>
                                <p class="mb-0">manual-monitor-multiparametrico.pdf</p>
                            </div>
                        </div>

                        <div id="mensagemEliminarDocumento" class="alert alert-success d-none">
                            <i class="fa-solid fa-check me-1"></i> Documento eliminado com sucesso.
                        </div>

                        <form id="formEliminarDocumento">
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="documentacao.php" class="btn btn-outline-secondary">Cancelar</a>

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