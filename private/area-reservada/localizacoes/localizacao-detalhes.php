<?php
$pageTitle = 'MedInfo Solutions — Detalhes da Localização';
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
                    <h4 class="fw-bold mb-1">Detalhes da localização</h4>
                    <p class="text-muted small mb-0">Consulta da ficha da localização selecionada.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="localizacao-editar.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen me-1"></i> Editar
                    </a>
                    <a href="localizacoes.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-lg-5">
                    <div class="card p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Unidade de Cuidados Intensivos</h5>
                                <p class="text-muted small mb-0">Código: LOC-001</p>
                            </div>

                            <div>
                                <span class="badge badge-ativo">Ativa</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
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
                                <p class="text-muted small mb-1">Responsável</p>
                                <p class="mb-0">Enf. Ana Martins</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Contacto interno</p>
                                <p class="mb-0">Ext. 2045</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Equipamentos associados</p>
                                <p class="mb-0">26 equipamentos</p>
                            </div>

                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Estado</p>
                                <p class="mb-0">Ativa</p>
                            </div>

                            <div class="col-12">
                                <p class="text-muted small mb-1">Descrição</p>
                                <p class="mb-0">
                                    Localização principal da UCI no Edifício Central, usada para equipamentos de
                                    monitorização, ventilação e suporte de vida.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-7">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Equipamentos nesta localização</h6>

                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Departamento/serviço</th>
                                        <th>Sala/gabinete</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EQ-0042</td>
                                        <td>Monitor Multiparamétrico</td>
                                        <td>Cuidados Intensivos</td>
                                        <td>UCI-02</td>
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
