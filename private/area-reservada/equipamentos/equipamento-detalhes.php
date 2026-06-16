<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Equipamento';
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

            <!-- TÍTULO E AÇÕES -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do equipamento</h4>
                    <p class="text-muted small mb-0">Consulta dos dados principais e associações do equipamento
                        selecionado.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="equipamento-editar.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen me-1"></i> Editar
                    </a>

                    <a href="equipamentos.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- RESUMO DO EQUIPAMENTO -->
            <section class="mb-4">
                <div class="card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Monitor Multiparamétrico</h5>
                            <p class="text-muted small mb-0">Código: EQ-0042</p>
                        </div>

                        <div class="d-flex flex-wrap gap-2 align-items-start">
                            <span class="badge badge-ativo">Ativo</span>
                            <span class="badge badge-ativo">Manutenção em dia</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ABAS DE DETALHES -->
            <section class="mb-4">
                <div class="card p-4">

                    <!-- ABAS -->
                    <ul class="nav nav-pills abas-equipamento mb-4" id="abasDetalhesEquipamento" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="detalhes-dados-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-dados" type="button" role="tab">
                                <span class="aba-numero">1</span>
                                <span>
                                    <strong>Dados</strong>
                                    <small>Informação principal</small>
                                </span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalhes-localizacao-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-localizacao" type="button" role="tab">
                                <span class="aba-numero">2</span>
                                <span>
                                    <strong>Localização</strong>
                                    <small>Serviço e sala</small>
                                </span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalhes-fornecedor-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-fornecedor" type="button" role="tab">
                                <span class="aba-numero">3</span>
                                <span>
                                    <strong>Fornecedor</strong>
                                    <small>Entidade associada</small>
                                </span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalhes-documentacao-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-documentacao" type="button" role="tab">
                                <span class="aba-numero">4</span>
                                <span>
                                    <strong>Documentação</strong>
                                    <small>PDFs técnicos</small>
                                </span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalhes-garantia-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-garantia" type="button" role="tab">
                                <span class="aba-numero">5</span>
                                <span>
                                    <strong>Garantia</strong>
                                    <small>Garantia e contrato</small>
                                </span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalhes-manutencao-tab" data-bs-toggle="tab"
                                data-bs-target="#detalhes-manutencao" type="button" role="tab">
                                <span class="aba-numero">6</span>
                                <span>
                                    <strong>Manutenção</strong>
                                    <small>Preventiva</small>
                                </span>
                            </button>
                        </li>
                    </ul>

                    <!-- CONTEÚDO DAS ABAS -->
                    <div class="tab-content">

                        <!-- DADOS -->
                        <div class="tab-pane fade show active" id="detalhes-dados" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Designação</p>
                                    <p class="mb-0">Monitor Multiparamétrico</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Categoria</p>
                                    <p class="mb-0">Monitorização</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Marca</p>
                                    <p class="mb-0">Philips</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Modelo</p>
                                    <p class="mb-0">IntelliVue</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">N.º de série</p>
                                    <p class="mb-0">SN-90821</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge badge-ativo">Ativo</span></p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Data de aquisição</p>
                                    <p class="mb-0">10/06/2023</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Custo de aquisição</p>
                                    <p class="mb-0">12 500,00 €</p>
                                </div>

                                <div class="col-12">
                                    <p class="text-muted small mb-1">Observações</p>
                                    <p class="mb-0">
                                        Equipamento usado como monitor multiparamétrico.
                                    </p>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                    <h6 class="fw-bold mb-3">Relações e consumíveis</h6>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">É componente de outro equipamento?</p>
                                    <p class="mb-0">Não</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Equipamento principal</p>
                                    <p class="mb-0">Não aplicável</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Tem consumíveis?</p>
                                    <p class="mb-0"><span class="badge bg-info text-dark">Sim</span></p>
                                </div>

                                <div class="col-12">
                                    <p class="text-muted small mb-1">Consumíveis associados</p>
                                    <p class="mb-0">Elétrodos ECG, sensores SpO2, cabos de ECG e papel térmico.</p>
                                </div>

                            </div>
                        </div>

                        <!-- LOCALIZAÇÃO -->
                        <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Código</p>
                                    <p class="mb-0">LOC-001</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Localização</p>
                                    <p class="mb-0">Unidade de Cuidados Intensivos</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Serviço</p>
                                    <p class="mb-0">UCI</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">N.º do andar</p>
                                    <p class="mb-0">2</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Sala</p>
                                    <p class="mb-0">UCI-02</p>
                                </div>

                                <div class="col-12">
                                    <a href="../localizacoes/localizacao-detalhes.php"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-location-dot me-1"></i> Ver localização
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- FORNECEDOR -->
                        <div class="tab-pane fade" id="detalhes-fornecedor" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Fornecedor</p>
                                    <p class="mb-0">Philips Healthcare Portugal</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Tipo</p>
                                    <p class="mb-0">Fabricante</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Contacto</p>
                                    <p class="mb-0">support.pt@philips.com</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Telefone</p>
                                    <p class="mb-0">+351 222 456 789</p>
                                </div>

                                <div class="col-12">
                                    <a href="../fornecedores/fornecedor-detalhes.php"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-truck me-1"></i> Ver fornecedor
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- DOCUMENTAÇÃO -->
                        <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-1">Documentação mínima necessária</h6>
                                    <p class="text-muted small mb-0">
                                        Checklist dos documentos mínimos associados a este equipamento.
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Manual de utilizador</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>manual-utilizador-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Manual de serviço</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>manual-servico-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Certificado de calibração</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>certificado-calibracao-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Fatura ou guia de aquisição</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>fatura-aquisicao-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Declaração de conformidade</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>declaracao-conformidade-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <h6 class="fw-bold mb-0">Relatório técnico</h6>

                                        </div>

                                        <div class="pdf-lista">
                                            <div class="pdf-item">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                    <span>relatorio-tecnico-monitor.pdf</span>
                                                </div>

                                                <a href="../documentacao/documento-detalhes.php"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <hr class="my-2">
                                    <h6 class="fw-bold mb-3">Outros documentos associados</h6>

                                    <div class="pdf-lista">
                                        <div class="pdf-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>ficha-tecnica-monitor-multiparametrico.pdf</span>
                                            </div>

                                            <a href="../documentacao/documento-detalhes.php"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye me-1"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <a href="../documentacao/documentacao.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fa-solid fa-folder-open me-1"></i> Ver documentação
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- GARANTIA E CONTRATO -->
                        <div class="tab-pane fade" id="detalhes-garantia" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Garantia</p>
                                    <p class="mb-0">GAR-001 — Garantia Monitor Multiparamétrico</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Fornecedor</p>
                                    <p class="mb-0">Philips Healthcare Portugal</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Início da garantia</p>
                                    <p class="mb-0">10/06/2023</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Fim da garantia</p>
                                    <p class="mb-0">10/06/2026</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge badge-ativo">Ativo</span></p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Contrato associado</p>
                                    <p class="mb-0">CON-001 — Contrato de Manutenção UCI</p>
                                </div>

                                <div class="col-12">
                                    <p class="text-muted small mb-1">Cobertura de garantia</p>
                                    <p class="mb-0">
                                        Cobertura de peças, mão de obra e assistência técnica durante o período de
                                        garantia.
                                    </p>
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-bold mb-3">PDFs associados</h6>

                                    <div class="pdf-lista">
                                        <div class="pdf-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>garantia-monitor-multiparametrico.pdf</span>
                                            </div>

                                            <a href="../contratos/garantia-detalhes.php"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye me-1"></i> Ver
                                            </a>
                                        </div>

                                        <div class="pdf-item">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>contrato-manutencao-uci.pdf</span>
                                            </div>

                                            <a href="../contratos/contrato-detalhes.php"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-eye me-1"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <a href="../documentacao/documentacao.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fa-solid fa-file-contract me-1"></i> Ver garantias e contratos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- MANUTENÇÃO -->
                        <div class="tab-pane fade" id="detalhes-manutencao" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Última manutenção</p>
                                    <p class="mb-0">15/12/2024</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Próxima manutenção</p>
                                    <p class="mb-0">15/12/2025</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Estado</p>
                                    <p class="mb-0"><span class="badge badge-ativo">Em dia</span></p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Periodicidade</p>
                                    <p class="mb-0">Anual</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Responsável</p>
                                    <p class="mb-0">Serviço de Engenharia Clínica</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Prioridade</p>
                                    <p class="mb-0">Normal</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>