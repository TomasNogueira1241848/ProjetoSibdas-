<?php
$pageTitle = 'MedInfo Solutions — Fornecedores';
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
                    <h4 class="fw-bold mb-1">Fornecedores</h4>
                    <p class="text-muted small mb-0">Listagem e gestão dos fornecedores associados aos equipamentos
                        médicos.</p>
                </div>

                <a href="fornecedor-novo.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo fornecedor
                </a>
            </div>

            <!-- INDICADORES -->
            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0">17</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Ativos</p>
                            <h4 class="fw-bold mb-0">14</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Com contrato</p>
                            <h4 class="fw-bold mb-0">5</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Inativos</p>
                            <h4 class="fw-bold mb-0">3</h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label for="pesquisaFornecedores" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaFornecedores"
                                placeholder="Nome, NIF, email ou telefone">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroTipoFornecedor" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoFornecedor">
                                <option value="">Todos</option>
                                <option value="Fabricante">Fabricante</option>
                                <option value="Distribuidor">Distribuidor</option>
                                <option value="Assistência Técnica">Assistência Técnica</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoFornecedor" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoFornecedor">
                                <option value="">Todos</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroContratoFornecedor" class="form-label">Contrato</label>
                            <select class="form-select" id="filtroContratoFornecedor">
                                <option value="">Todos</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaFornecedores">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>NIF</th>
                                    <th>Tipo</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Contrato</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Philips Healthcare Portugal</td>
                                    <td>501234567</td>
                                    <td>Fabricante</td>
                                    <td>suporte@philips.pt</td>
                                    <td>+351 211 234 567</td>
                                    <td>Sim</td>
                                    <td><span class="badge badge-ativo">Ativo</span></td>
                                    <td class="text-center">
                                        <a href="fornecedor-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="fornecedor-editar.php" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="tooltip" data-bs-title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <a href="fornecedor-eliminar.php" class="btn btn-sm btn-outline-danger"
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