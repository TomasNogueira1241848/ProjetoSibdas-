
<?php
$pageTitle = 'MedInfo Solutions — Eliminar Localização';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'localizacoes';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Eliminar localização</h4>
                    <p class="text-muted small mb-0">Confirmação da eliminação da localização selecionada.</p>
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
                                <h5 class="fw-bold mb-1">Tem a certeza que pretende eliminar esta localização?</h5>
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
                                <p class="fw-bold mb-0">LOC-001</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado</p>
                                <p class="mb-0"><span class="badge badge-ativo">Ativa</span></p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Nome</p>
                                <p class="mb-0">Unidade de Cuidados Intensivos</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Tipo de localização</p>
                                <p class="mb-0">UCI</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Edifício</p>
                                <p class="mb-0">Edifício Central</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Piso principal</p>
                                <p class="mb-0">2</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">N.º de andares</p>
                                <p class="mb-0">4</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Equipamentos associados</p>
                                <p class="mb-0">26</p>
                            </div>
                        </div>

                        <div id="mensagemEliminarLocalizacao" class="alert alert-success d-none">
                            <i class="fa-solid fa-check me-1"></i> Localização eliminada com sucesso.
                        </div>

                        <form id="formEliminarLocalizacao">
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="localizacoes.php" class="btn btn-outline-secondary">Cancelar</a>

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
