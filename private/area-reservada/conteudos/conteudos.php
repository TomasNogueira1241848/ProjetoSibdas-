<?php
$pageTitle = 'MedInfo Solutions — Gestão de Conteúdos';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'conteudos';

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
                    <h4 class="fw-bold mb-1">Gestão de Conteúdos</h4>
                    <p class="text-muted small mb-0">Alteração dos textos apresentados na página principal pública.
                    </p>
                </div>

                <a href="../../../public/index.php" class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver página pública
                </a>
            </div>

            <!-- FORMULÁRIO -->
            <section class="mb-4">
                <div class="card p-4">
                    <form id="formConteudosPublicos" novalidate>

                        <!-- HERO -->
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-house me-2 text-primary"></i> Secção inicial
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoHeroTitulo" class="form-label">Título principal </label>
                                <input type="text" class="form-control" id="conteudoHeroTitulo" required>
                                <div class="invalid-feedback">Introduza o título principal.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoHeroSubtitulo" class="form-label">Texto principal </label>
                                <textarea class="form-control" id="conteudoHeroSubtitulo" rows="3"
                                    required></textarea>
                                <div class="invalid-feedback">Introduza o texto principal.</div>
                            </div>
                        </div>

                        <hr>

                        <!-- SOBRE NÓS -->
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-building me-2 text-primary"></i> Sobre Nós
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoSobreTitulo" class="form-label">Título da secção </label>
                                <input type="text" class="form-control" id="conteudoSobreTitulo" required>
                                <div class="invalid-feedback">Introduza o título da secção.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto1" class="form-label">Texto 1 </label>
                                <textarea class="form-control" id="conteudoSobreTexto1" rows="3"
                                    required></textarea>
                                <div class="invalid-feedback">Introduza o primeiro texto.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto2" class="form-label">Texto 2</label>
                                <textarea class="form-control" id="conteudoSobreTexto2" rows="3"></textarea>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto3" class="form-label">Texto 3</label>
                                <textarea class="form-control" id="conteudoSobreTexto3" rows="3"></textarea>
                            </div>
                        </div>

                        <hr>

                        <!-- SERVIÇOS E CLIENTES -->
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-briefcase-medical me-2 text-primary"></i> Serviços e clientes
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="conteudoServicosTitulo" class="form-label">Título dos serviços </label>
                                <input type="text" class="form-control" id="conteudoServicosTitulo" required>
                                <div class="invalid-feedback">Introduza o título dos serviços.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoClientesTitulo" class="form-label">Título dos clientes </label>
                                <input type="text" class="form-control" id="conteudoClientesTitulo" required>
                                <div class="invalid-feedback">Introduza o título dos clientes.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoServicosTexto" class="form-label">Texto dos serviços </label>
                                <textarea class="form-control" id="conteudoServicosTexto" rows="2"
                                    required></textarea>
                                <div class="invalid-feedback">Introduza o texto dos serviços.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoClientesTexto" class="form-label">Texto dos clientes </label>
                                <textarea class="form-control" id="conteudoClientesTexto" rows="2"
                                    required></textarea>
                                <div class="invalid-feedback">Introduza o texto dos clientes.</div>
                            </div>
                        </div>

                        <hr>

                        <!-- CONTACTOS -->
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-address-card me-2 text-primary"></i> Contactos
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="conteudoContactosTitulo" class="form-label">Título dos contactos
                                </label>
                                <input type="text" class="form-control" id="conteudoContactosTitulo" required>
                                <div class="invalid-feedback">Introduza o título dos contactos.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoTelefone" class="form-label">Telefone </label>
                                <input type="text" class="form-control" id="conteudoTelefone" required>
                                <div class="invalid-feedback">Introduza o telefone.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoContactosTexto" class="form-label">Texto dos contactos </label>
                                <textarea class="form-control" id="conteudoContactosTexto" rows="2"
                                    required></textarea>
                                <div class="invalid-feedback">Introduza o texto dos contactos.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoEmail" class="form-label">Email </label>
                                <input type="email" class="form-control" id="conteudoEmail" required>
                                <div class="invalid-feedback">Introduza um email válido.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoWebsite" class="form-label">Website </label>
                                <input type="text" class="form-control" id="conteudoWebsite" required>
                                <div class="invalid-feedback">Introduza o website.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoMorada" class="form-label">Morada </label>
                                <textarea class="form-control" id="conteudoMorada" rows="2" required></textarea>
                                <div class="invalid-feedback">Introduza a morada.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoHorario" class="form-label">Horário </label>
                                <textarea class="form-control" id="conteudoHorario" rows="2" required></textarea>
                                <div class="invalid-feedback">Introduza o horário.</div>
                            </div>
                        </div>

                        <hr>

                        <!-- RODAPÉ -->
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-window-minimize me-2 text-primary"></i> Rodapé
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoRodapeTexto" class="form-label">Texto do rodapé </label>
                                <input type="text" class="form-control" id="conteudoRodapeTexto" required>
                                <div class="invalid-feedback">Introduza o texto do rodapé.</div>
                            </div>
                        </div>

                        <!-- MENSAGEM -->
                        <div id="mensagemConteudosPublicos" class="alert alert-success d-none">
                            <i class="fa-solid fa-check me-1"></i> Conteúdos atualizados com sucesso.
                        </div>

                        <!-- AÇÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-danger" id="btnReporConteudosPublicos">
                                <i class="fa-solid fa-rotate-left me-1"></i> Repor originais
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Guardar alterações
                            </button>
                        </div>

                    </form>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>