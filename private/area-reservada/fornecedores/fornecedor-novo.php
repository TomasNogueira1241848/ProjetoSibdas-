<?php
$pageTitle = 'MedInfo Solutions — Novo Fornecedor';
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
                    <h4 class="fw-bold mb-1">Novo fornecedor</h4>
                    <p class="text-muted small mb-0">Registo de um novo fornecedor ou entidade de assistência
                        técnica.</p>
                </div>
            </div>

            <!-- FORMULÁRIO -->
            <section class="mb-4">
                <div class="card p-4">
                    <form id="formFornecedor" novalidate>
                        <div class="row g-3">

                            <div class="col-md-8">
                                <label for="nomeFornecedor" class="form-label">
                                    Nome do fornecedor
                                </label>
                                <input type="text" class="form-control" id="nomeFornecedor" name="nomeFornecedor"
                                    required>
                                <div class="invalid-feedback">Introduza o nome do fornecedor.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="nifFornecedor" class="form-label">
                                    NIF
                                </label>
                                <input type="number" class="form-control" id="nifFornecedor" name="nifFornecedor"
                                    maxlength="9" min="0" step="1" required>
                                <div class="invalid-feedback">Introduza o NIF do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="tipoFornecedor" class="form-label"> Tipo </label>
                                <select class="form-select" id="tipoFornecedor" name="tipoFornecedor" required>
                                    <option value="">Selecionar tipo</option>
                                    <option value="Fabricante">Fabricante</option>
                                    <option value="Distribuidor">Distribuidor</option>
                                    <option value="Assistência Técnica">Assistência Técnica</option>
                                </select>
                                <div class="invalid-feedback">Selecione o tipo de fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="estadoFornecedor" class="form-label">
                                    Estado
                                </label>
                                <select class="form-select" id="estadoFornecedor" name="estadoFornecedor" required>
                                    <option value="">Selecionar estado</option>
                                    <option value="Ativo">Ativo</option>
                                    <option value="Inativo">Inativo</option>
                                </select>
                                <div class="invalid-feedback">Selecione o estado do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="emailFornecedor" class="form-label"> Email </label>
                                <input type="email" class="form-control" id="emailFornecedor" name="emailFornecedor"
                                    required>
                                <div class="invalid-feedback">Introduza um email válido.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefoneFornecedor" class="form-label">
                                    Telefone
                                </label>
                                <input type="number" class="form-control" id="telefoneFornecedor"
                                    name="telefoneFornecedor" min="0" step="1" required>
                                <div class="invalid-feedback">Introduza o telefone do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="contratoFornecedor" class="form-label">Contrato ativo</label>
                                <select class="form-select" id="contratoFornecedor" name="contratoFornecedor" required>
                                    <option value="">Selecionar</option>
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                                <div class="invalid-feedback">Selecione o sim ou não.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="areaFornecedor" class="form-label">Área de atuação</label>
                                <input type="text" class="form-control" id="areaFornecedor" name="areaFornecedor"
                                    placeholder="Ex: ventilação, monitorização, calibração" required>
                                <div class="invalid-feedback">Introduza a área de atuação.</div>
                            </div>

                            <div class="col-12">
                                <label for="moradaFornecedor" class="form-label">Morada</label>
                                <input type="text" class="form-control" id="moradaFornecedor" name="moradaFornecedor"
                                    required>
                                <div class="invalid-feedback">Introduza a morada.</div>
                            </div>

                            <div class="col-12">
                                <label for="observacoesFornecedor" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoesFornecedor" name="observacoesFornecedor"
                                    rows="3" required></textarea>
                                <div class="invalid-feedback">Preencha o campo de observações.</div>
                            </div>

                            <div class="col-12">
                                <div id="mensagemFornecedor" class="alert alert-success d-none mb-0">
                                    <i class="fa-solid fa-check me-1"></i> Fornecedor guardado com sucesso.
                                </div>
                            </div>

                            <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="fornecedores.php" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar fornecedor
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>