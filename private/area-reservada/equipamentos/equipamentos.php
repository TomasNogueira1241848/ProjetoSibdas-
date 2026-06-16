<?php
$pageTitle = 'MedInfo Solutions — Equipamentos';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'equipamentos';

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
                    <h4 class="fw-bold mb-1">Equipamentos</h4>
                    <p class="text-muted small mb-0">Listagem e gestão dos equipamentos médicos registados no
                        inventário.</p>
                </div>

                <a href="equipamento-novo.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Novo equipamento
                </a>
            </div>

            <!-- INDICADORES -->
            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0">142</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Ativos</p>
                            <h4 class="fw-bold mb-0">118</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Em manutenção</p>
                            <h4 class="fw-bold mb-0">14</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Inativos</p>
                            <h4 class="fw-bold mb-0">10</h4>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTROS -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label for="pesquisaEquipamentos" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaEquipamentos"
                                placeholder="Código, nome, marca, modelo ou n.º de série">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroCategoriaEquipamentos" class="form-label">Categoria</label>
                            <select class="form-select" id="filtroCategoriaEquipamentos">
                                <option value="">Todas</option>
                                <option value="Monitorização">Monitorização</option>
                                <option value="Suporte de Vida">Suporte de Vida</option>
                                <option value="Terapia">Terapia</option>
                                <option value="Diagnóstico">Diagnóstico</option>
                                <option value="Laboratório">Laboratório</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoEquipamentos" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoEquipamentos">
                                <option value="">Todos</option>
                                <option value="Ativo">Ativo</option>
                                <option value="Em Manutenção">Em Manutenção</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Em Calibração">Em Calibração</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroLocalizacaoEquipamentos" class="form-label">Localização</label>
                            <select class="form-select" id="filtroLocalizacaoEquipamentos">
                                <option value="">Todas</option>
                                <option value="UCI">UCI</option>
                                <option value="Urgência">Urgência</option>
                                <option value="Bloco Operatório">Bloco Operatório</option>
                                <option value="Medicina Interna">Medicina Interna</option>
                                <option value="Consulta Externa">Consulta Externa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TABELA -->
            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaEquipamentos">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Categoria</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>N.º Série</th>
                                    <th>Localização</th>
                                    <th>Estado</th>
                                    <th>Garantia</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>EQ-0042</td>
                                    <td>Monitor Multiparamétrico</td>
                                    <td>Monitorização</td>
                                    <td>Philips</td>
                                    <td>IntelliVue</td>
                                    <td>SN-90821</td>
                                    <td>UCI</td>
                                    <td><span class="badge badge-ativo">Ativo</span></td>
                                    <td>2026-06-10</td>
                                    <td class="text-center">
                                        <a href="equipamento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="equipamento-editar.php" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="tooltip" data-bs-title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <a href="equipamento-eliminar.php" class="btn btn-sm btn-outline-danger"
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