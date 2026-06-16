<?php
$pageTitle = 'MedInfo Solutions — Documentação';
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
                    <h4 class="fw-bold mb-1">Documentação</h4>
                    <p class="text-muted small mb-0">Gestão de manuais, certificados, relatórios técnicos e
                        documentos associados aos equipamentos.</p>
                </div>
            </div>

            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0">10</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Válidos</p>
                            <h4 class="fw-bold mb-0">8</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Por rever</p>
                            <h4 class="fw-bold mb-0">1</h4>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-secondary-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Expirados</p>
                            <h4 class="fw-bold mb-0">1</h4>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label for="pesquisaDocumentacao" class="form-label">Pesquisar</label>
                            <input type="search" class="form-control" id="pesquisaDocumentacao"
                                placeholder="Código, título, equipamento ou data">
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <label for="filtroTipoDocumento" class="form-label">Tipo</label>
                            <select class="form-select" id="filtroTipoDocumento">
                                <option value="">Todos</option>
                                <option value="Manual de utilizador">Manual de utilizador</option>
                                <option value="Manual de serviço">Manual de serviço</option>
                                <option value="Certificado de calibração">Certificado de calibração</option>
                                <option value="Fatura ou guia de aquisição">Fatura ou guia de aquisição</option>
                                <option value="Declaração de conformidade">Declaração de conformidade</option>
                                <option value="Relatório técnico">Relatório técnico</option>
                                <option value="Relatório de manutenção">Relatório de manutenção</option>
                                <option value="Ficha técnica">Ficha técnica</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroAreaDocumento" class="form-label">Área</label>
                            <select class="form-select" id="filtroAreaDocumento">
                                <option value="">Todas</option>
                                <option value="Equipamento">Equipamento</option>
                                <option value="Fornecedor">Fornecedor</option>
                                <option value="Manutenção">Manutenção</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-lg-2">
                            <label for="filtroEstadoDocumento" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstadoDocumento">
                                <option value="">Todos</option>
                                <option value="Válido">Válido</option>
                                <option value="Por rever">Por rever</option>
                                <option value="Expirado">Expirado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaDocumentacao">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Associado a</th>
                                    <th>Área</th>
                                    <th>Data</th>
                                    <th>Validade</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>DOC-001</td>
                                    <td>Manual de utilizador do Monitor Multiparamétrico</td>
                                    <td>Manual de utilizador</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2025-03-12</td>
                                    <td>2027-03-12</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-002</td>
                                    <td>Manual de serviço do Monitor Multiparamétrico</td>
                                    <td>Manual de serviço</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2025-03-12</td>
                                    <td>2027-03-12</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-003</td>
                                    <td>Certificado de calibração do Monitor Multiparamétrico</td>
                                    <td>Certificado de calibração</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2025-02-20</td>
                                    <td>2026-02-20</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-004</td>
                                    <td>Fatura de aquisição do Monitor Multiparamétrico</td>
                                    <td>Fatura ou guia de aquisição</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2023-06-10</td>
                                    <td>—</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-005</td>
                                    <td>Declaração de conformidade do Monitor Multiparamétrico</td>
                                    <td>Declaração de conformidade</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2023-06-10</td>
                                    <td>—</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-006</td>
                                    <td>Relatório técnico inicial do Monitor Multiparamétrico</td>
                                    <td>Relatório técnico</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>>
                                    <td>2023-06-15</td>
                                    <td>—</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-007</td>
                                    <td>Relatório de manutenção preventiva</td>
                                    <td>Relatório de manutenção</td>
                                    <td>EQ-0042</td>
                                    <td>Manutenção</td>
                                    <td>2024-12-15</td>
                                    <td>—</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-008</td>
                                    <td>Relatório de intervenção corretiva</td>
                                    <td>Relatório de manutenção</td>
                                    <td>EQ-0042</td>
                                    <td>Manutenção</td>
                                    <td>2025-01-20</td>
                                    <td>—</td>
                                    <td><span class="badge badge-manutencao">Por rever</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-009</td>
                                    <td>Ficha técnica do Monitor Multiparamétrico</td>
                                    <td>Ficha técnica</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2023-06-10</td>
                                    <td>—</td>
                                    <td><span class="badge badge-ativo">Válido</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>DOC-010</td>
                                    <td>Procedimento de limpeza e conservação</td>
                                    <td>Outro</td>
                                    <td>EQ-0042</td>
                                    <td>Equipamento</td>
                                    <td>2022-01-08</td>
                                    <td>2025-01-08</td>
                                    <td><span class="badge badge-inativo">Expirado</span></td>
                                    <td class="text-center">
                                        <a href="documento-detalhes.php" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="tooltip" data-bs-title="Ver detalhes"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="documento-eliminar.php" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip" data-bs-title="Eliminar"><i
                                                class="fa-solid fa-trash"></i></a>
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