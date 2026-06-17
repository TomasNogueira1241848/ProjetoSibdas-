<?php
$pageTitle = 'MedInfo Solutions — Localizações';
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
                        <h4 class="fw-bold mb-1">Localizações</h4>
                        <p class="text-muted small mb-0">Gestão das localizações principais do hospital onde os equipamentos podem estar associados.</p>
                    </div>

                    <a href="localizacao-nova.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Nova localização
                    </a>
                </div>

                <section class="mb-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Total</p>
                                <h4 class="fw-bold mb-0">12</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-success-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Ativas</p>
                                <h4 class="fw-bold mb-0">10</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Em manutenção</p>
                                <h4 class="fw-bold mb-0">1</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Inativas</p>
                                <h4 class="fw-bold mb-0">1</h4>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="card p-3">
                        <div class="row g-3">
                            <div class="col-lg-5">
                                <label for="pesquisaLocalizacoes" class="form-label">Pesquisar</label>
                                <input type="search" class="form-control" id="pesquisaLocalizacoes"
                                    placeholder="Código, nome, tipo, edifício ou responsável">
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <label for="filtroTipoLocalizacao" class="form-label">Tipo de localização</label>
                                <select class="form-select" id="filtroTipoLocalizacao">
                                    <option value="">Todos</option>
                                    <option value="Edifício principal">Edifício principal</option>
                                    <option value="UCI">UCI</option>
                                    <option value="Urgência">Urgência</option>
                                    <option value="Bloco operatório">Bloco operatório</option>
                                    <option value="Consulta externa">Consulta externa</option>
                                    <option value="Laboratório">Laboratório</option>
                                    <option value="Armazém">Armazém</option>
                                    <option value="Área técnica">Área técnica</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label for="filtroEdificioLocalizacao" class="form-label">Edifício</label>
                                <select class="form-select" id="filtroEdificioLocalizacao">
                                    <option value="">Todos</option>
                                    <option value="Edifício Central">Edifício Central</option>
                                    <option value="Edifício Consultas Externas">Edifício Consultas Externas</option>
                                    <option value="Edifício Técnico">Edifício Técnico</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label for="filtroEstadoLocalizacao" class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstadoLocalizacao">
                                    <option value="">Todos</option>
                                    <option value="Ativa">Ativa</option>
                                    <option value="Em Manutenção">Em Manutenção</option>
                                    <option value="Inativa">Inativa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="card p-3">
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaLocalizacoes">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Edifício</th>
                                        <th>Piso principal</th>
                                        <th>Equipamentos</th>
                                        <th>Estado</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>LOC-001</td>
                                        <td>Unidade de Cuidados Intensivos</td>
                                        <td>UCI</td>
                                        <td>Edifício Central</td>
                                        <td>2</td>
                                        <td>26</td>
                                        <td><span class="badge badge-ativo">Ativa</span></td>
                                        <td class="text-center">
                                            <a href="localizacao-detalhes.php" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="localizacao-editar.php" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="tooltip" data-bs-title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <a href="localizacao-eliminar.php" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" data-bs-title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
