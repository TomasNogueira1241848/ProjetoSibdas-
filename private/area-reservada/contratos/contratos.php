<?php
$pageTitle = 'MedInfo Solutions — Garantias e Contratos';
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
                        <h4 class="fw-bold mb-1">Garantias e Contratos</h4>
                        <p class="text-muted small mb-0">Gestão separada das garantias dos equipamentos e dos contratos
                            associados.</p>
                    </div>
                </div>

                <!-- INDICADORES -->
                <section class="mb-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Total</p>
                                <h4 class="fw-bold mb-0">4</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-success-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Garantias</p>
                                <h4 class="fw-bold mb-0">2</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-info-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">Contratos</p>
                                <h4 class="fw-bold mb-0">2</h4>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                                <p class="text-muted small mb-1">A expirar</p>
                                <h4 class="fw-bold mb-0">1</h4>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- FILTROS -->
                <section class="mb-4">
                    <div class="card p-3">
                        <div class="row g-3">
                            <div class="col-lg-5">
                                <label for="pesquisaContratos" class="form-label">Pesquisar</label>
                                <input type="search" class="form-control" id="pesquisaContratos"
                                    placeholder="Código, designação, fornecedor ou equipamento">
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <label for="filtroTipoContrato" class="form-label">Tipo</label>
                                <select class="form-select" id="filtroTipoContrato">
                                    <option value="">Todos</option>
                                    <option value="Garantia">Garantia</option>
                                    <option value="Manutenção">Manutenção</option>
                                    <option value="Seguro">Seguro</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label for="filtroFornecedorContrato" class="form-label">Fornecedor</label>
                                <select class="form-select" id="filtroFornecedorContrato">
                                    <option value="">Todos</option>
                                    <option value="Philips Healthcare Portugal">Philips</option>
                                    <option value="Dräger Portugal">Dräger</option>
                                    <option value="MedRepair Norte">MedRepair</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label for="filtroEstadoContrato" class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstadoContrato">
                                    <option value="">Todos</option>
                                    <option value="Ativo">Ativo</option>
                                    <option value="A expirar">A expirar</option>
                                    <option value="Expirado">Expirado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- TABELA DE GARANTIAS -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h5 class="fw-bold mb-1">Garantias</h5>
                        <p class="text-muted small mb-0">Garantias associadas diretamente a equipamentos médicos.</p>
                    </div>

                    <div class="card p-3">
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaGarantias">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Tipo</th>
                                        <th>Equipamento</th>
                                        <th>Fornecedor</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Estado</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>GAR-001</td>
                                        <td>Garantia Monitor Multiparamétrico</td>
                                        <td>Garantia</td>
                                        <td>EQ-0042</td>
                                        <td>Philips Healthcare Portugal</td>
                                        <td>10/06/2023</td>
                                        <td>10/06/2026</td>
                                        <td><span class="badge badge-ativo">Ativo</span></td>
                                        <td class="text-center">
                                            <a href="garantia-detalhes.php" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="garantia-eliminar.php" class="btn btn-sm btn-outline-danger"
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

                <!-- TABELA DE CONTRATOS -->
                <section class="mb-4">
                    <div class="mb-3">
                        <h5 class="fw-bold mb-1">Contratos</h5>
                        <p class="text-muted small mb-0">Contratos de manutenção, assistência técnica e seguros.</p>
                    </div>

                    <div class="card p-3">
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaContratos">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Tipo</th>
                                        <th>Associado a</th>
                                        <th>Fornecedor</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Estado</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>CON-001</td>
                                        <td>Contrato de Manutenção UCI</td>
                                        <td>Manutenção</td>
                                        <td>Equipamentos UCI</td>
                                        <td>Dräger Portugal</td>
                                        <td>01/01/2024</td>
                                        <td>31/12/2025</td>
                                        <td><span class="badge badge-ativo">Ativo</span></td>
                                        <td class="text-center">
                                            <a href="contrato-detalhes.php" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="contrato-eliminar.php" class="btn btn-sm btn-outline-danger"
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
