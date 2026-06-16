<?php
$pageTitle = 'MedInfo Solutions — Editar Localização';
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
                    <h4 class="fw-bold mb-1">Editar localização</h4>
                    <p class="text-muted small mb-0">Atualização dos dados da localização selecionada.</p>
                </div>
            </div>

            <section class="mb-4">
                <div class="card p-4">
                    <form id="formLocalizacao" novalidate>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="codigoLocalizacao" class="form-label">Código </label>
                                <input type="text" class="form-control" id="codigoLocalizacao" name="codigoLocalizacao"
                                    value="LOC-001" required>
                                <div class="invalid-feedback">Introduza o código da localização.</div>
                            </div>

                            <div class="col-md-8">
                                <label for="nomeLocalizacao" class="form-label">Nome </label>
                                <input type="text" class="form-control" id="nomeLocalizacao" name="nomeLocalizacao"
                                    value="Unidade de Cuidados Intensivos" required>
                                <div class="invalid-feedback">Introduza o nome da localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="tipoLocalizacao" class="form-label">Tipo </label>
                                <select class="form-select" id="tipoLocalizacao" name="tipoLocalizacao" required>
                                    <option value="">Selecionar</option>
                                    <option value="Serviço">Serviço</option>
                                    <option value="Unidade" selected>Unidade</option>
                                    <option value="Sala">Sala</option>
                                    <option value="Armazém">Armazém</option>
                                </select>
                                <div class="invalid-feedback">Selecione o tipo de localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="numeroAndaresLocalizacao" class="form-label">N.º de andares </label>
                                <input type="number" class="form-control" id="numeroAndaresLocalizacao"
                                    name="numeroAndaresLocalizacao" min="1" step="1" value="4" required>
                                <div class="invalid-feedback">Introduza o número de andares.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="estadoLocalizacao" class="form-label">Estado </label>
                                <select class="form-select" id="estadoLocalizacao" name="estadoLocalizacao" required>
                                    <option value="">Selecionar</option>
                                    <option value="Ativa" selected>Ativa</option>
                                    <option value="Em Manutenção">Em Manutenção</option>
                                    <option value="Inativa">Inativa</option>
                                </select>
                                <div class="invalid-feedback">Selecione o estado da localização.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="responsavelLocalizacao" class="form-label">Responsável </label>
                                <input type="text" class="form-control" id="responsavelLocalizacao"
                                    name="responsavelLocalizacao" value="Enf. Ana Martins" required>
                                <div class="invalid-feedback">Introduza o responsável pela localização.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefoneLocalizacao" class="form-label">Contacto interno</label>
                                <input type="text" class="form-control" id="telefoneLocalizacao"
                                    name="telefoneLocalizacao" value="Ext. 2045" required>
                                <div class="invalid-feedback">Introduza o contacto interno.</div>
                            </div>

                            <div class="col-12">
                                <label for="descricaoLocalizacao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricaoLocalizacao" name="descricaoLocalizacao"
                                    rows="3"
                                    required>Unidade hospitalar destinada a doentes críticos, com equipamentos de monitorização, ventilação e suporte de vida.</textarea>
                                <div class="invalid-feedback">Preencha o campo de descrição.</div>
                            </div>

                            <div class="col-12">
                                <div id="mensagemLocalizacao" class="alert alert-success d-none mb-0">
                                    <i class="fa-solid fa-check me-1"></i> Alterações guardadas com sucesso.
                                </div>
                            </div>

                            <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="localizacoes.php" class="btn btn-outline-secondary">Cancelar</a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar alterações
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