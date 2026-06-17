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
                                    <strong>Fornecedores</strong>
                                    <small>Principal e associados</small>
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
                                    <strong>Garantia e contrato</strong>
                                    <small>Garantias, contratos e PDFs</small>
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

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Ano de fabrico</p>
                                    <p class="mb-0">2020</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Tipo de entrada</p>
                                    <p class="mb-0">Compra</p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <p class="text-muted small mb-1">Criticidade</p>
                                    <p class="mb-0"><span class="badge bg-warning text-dark">Alta</span></p>
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
                                <div class="col-12">
                                    <h6 class="fw-bold mb-1">Entidades associadas ao equipamento</h6>
                                    <p class="text-muted small mb-0">
                                        Informação alinhada com os campos de fornecedores existentes no novo e editar equipamento.
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <p class="text-muted small mb-1">Fornecedor principal</p>
                                        <h6 class="fw-bold mb-1">Philips Healthcare Portugal</h6>
                                        <p class="text-muted small mb-2">Fornecedor comercial principal</p>
                                        <p class="small mb-1"><strong>NIF:</strong> 501234567</p>
                                        <p class="small mb-1"><strong>Email:</strong> support.pt@philips.com</p>
                                        <p class="small mb-1"><strong>Telefone:</strong> +351 222 456 789</p>
                                        <p class="small mb-0"><strong>Pessoa de contacto:</strong> Eng.ª Ana Martins</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <p class="text-muted small mb-1">Fabricante principal</p>
                                        <h6 class="fw-bold mb-1">Philips Healthcare Portugal</h6>
                                        <p class="text-muted small mb-2">Fabricante do equipamento</p>
                                        <p class="small mb-1"><strong>Website:</strong> www.philips.pt/healthcare</p>
                                        <p class="small mb-1"><strong>Email:</strong> support.pt@philips.com</p>
                                        <p class="small mb-0"><strong>Morada:</strong> Rua da Saúde, 120 — Porto</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <p class="text-muted small mb-1">Prestador de assistência técnica principal</p>
                                        <h6 class="fw-bold mb-1">MedRepair Norte</h6>
                                        <p class="text-muted small mb-2">Assistência técnica e manutenção</p>
                                        <p class="small mb-1"><strong>Email:</strong> assistencia@medrepair.pt</p>
                                        <p class="small mb-1"><strong>Telefone:</strong> +351 221 900 300</p>
                                        <p class="small mb-0"><strong>Pessoa de contacto:</strong> Téc. Carlos Almeida</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <hr class="my-2">
                                    <h6 class="fw-bold mb-1">Fornecedores associados adicionais</h6>
                                    <p class="text-muted small mb-0">
                                        Outras entidades associadas ao equipamento, como distribuidores, assistência técnica ou fornecedores de consumíveis.
                                    </p>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <h6 class="fw-bold mb-1">Dräger Portugal</h6>
                                        <p class="text-muted small mb-2">Distribuidor / assistência técnica</p>
                                        <p class="small mb-1"><strong>Email:</strong> geral@draeger.pt</p>
                                        <p class="small mb-0"><strong>Telefone:</strong> +351 214 123 000</p>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <h6 class="fw-bold mb-1">ForneConsumíveis SA</h6>
                                        <p class="text-muted small mb-2">Consumíveis e acessórios</p>
                                        <p class="small mb-1"><strong>Email:</strong> encomendas@forneconsumiveis.pt</p>
                                        <p class="small mb-0"><strong>Telefone:</strong> +351 225 300 400</p>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <a href="../fornecedores/fornecedor-detalhes.php" class="btn btn-outline-primary btn-sm">
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
                                        Informação completa dos documentos mínimos associados ao equipamento.
                                    </p>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Manual de utilizador</h6>
                                                <p class="text-muted small mb-0">Manual do Monitor Multiparamétrico</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-001</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Manual de utilizador</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Téc. João Ferreira</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2025-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">2027-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Manual associado ao equipamento para consulta de utilização.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>manual-monitor-multiparametrico.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Manual de serviço</h6>
                                                <p class="text-muted small mb-0">Manual de Serviço do Monitor Multiparamétrico</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-002</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Manual de serviço</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Téc. João Ferreira</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2025-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">2027-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Manual técnico para procedimentos de assistência e manutenção.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>manual-servico-monitor.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Certificado de calibração</h6>
                                                <p class="text-muted small mb-0">Certificado de Calibração do Monitor</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-003</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Certificado de calibração</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Engenharia Clínica</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2025-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">2026-03-12</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">MedRepair Norte</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Certificado associado ao processo de calibração do equipamento.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>certificado-calibracao-monitor.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Fatura ou guia de aquisição</h6>
                                                <p class="text-muted small mb-0">Fatura de Aquisição do Monitor</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-004</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Fatura ou guia de aquisição</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Serviço Administrativo</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2023-06-10</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">—</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Documento comprovativo da aquisição do equipamento.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>fatura-aquisicao-monitor.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Declaração de conformidade</h6>
                                                <p class="text-muted small mb-0">Declaração de Conformidade do Monitor</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-005</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Declaração de conformidade</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Téc. João Ferreira</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2023-06-10</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">—</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Declaração de conformidade do fabricante.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>declaracao-conformidade-monitor.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light h-100">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-1"></i> Relatório técnico</h6>
                                                <p class="text-muted small mb-0">Relatório Técnico de Inspeção</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Válido</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-006</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Relatório técnico</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Área</p>
                                                <p class="mb-0">Equipamento</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Engenharia Clínica</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data do documento</p>
                                                <p class="mb-0">2024-12-15</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">—</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">MedRepair Norte</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Relatório técnico associado à inspeção do equipamento.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>relatorio-tecnico-monitor.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <hr class="my-2">
                                    <h6 class="fw-bold mb-1">Outros documentos associados</h6>
                                    <p class="text-muted small mb-3">Documentos facultativos associados ao equipamento.</p>

                                    <div class="border rounded p-3 bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Código</p>
                                                <p class="mb-0">DOC-007</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Ficha técnica</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor associado</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Estado</p>
                                                <p class="mb-0"><span class="badge badge-ativo">Válido</span></p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>ficha-tecnica-monitor-multiparametrico.pdf</span>
                                                        </div>

                                                        <a href="../documentacao/documento-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
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
                                <div class="col-12">
                                    <h6 class="fw-bold mb-1">Garantia e contratos do equipamento</h6>
                                    <p class="text-muted small mb-0">Consulta da garantia, do contrato de manutenção obrigatório e de outros contratos associados.</p>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-shield text-primary me-1"></i> Garantia</h6>
                                                <p class="text-muted small mb-0">GAR-001 — Garantia Monitor Multiparamétrico</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Ativo</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Garantia</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor</p>
                                                <p class="mb-0">Philips Healthcare Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Equipamento associado</p>
                                                <p class="mb-0">EQ-0042 — Monitor Multiparamétrico</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Téc. João Ferreira</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data de início</p>
                                                <p class="mb-0">10/06/2023</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data de fim</p>
                                                <p class="mb-0">10/06/2026</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Contrato associado</p>
                                                <p class="mb-0">CON-001 — Contrato de Manutenção UCI</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Cobertura</p>
                                                <p class="mb-0">Cobertura de peças, mão de obra e assistência técnica durante o período de garantia.</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Garantia associada ao monitor multiparamétrico utilizado na UCI.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>garantia-monitor-multiparametrico.pdf</span>
                                                        </div>

                                                        <a href="../contratos/garantia-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1"><i class="fa-solid fa-file-contract text-danger me-1"></i> Contrato de manutenção</h6>
                                                <p class="text-muted small mb-0">CON-001 — Contrato de Manutenção UCI</p>
                                            </div>
                                            <span class="badge badge-ativo align-self-start">Ativo</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Manutenção</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor</p>
                                                <p class="mb-0">Dräger Portugal</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Associado a</p>
                                                <p class="mb-0">Equipamentos UCI</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Responsável</p>
                                                <p class="mb-0">Eng.ª Mariana Silva</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data de início</p>
                                                <p class="mb-0">01/01/2024</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Data de fim</p>
                                                <p class="mb-0">31/12/2025</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Valor anual</p>
                                                <p class="mb-0">4 800€</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Periodicidade</p>
                                                <p class="mb-0">Anual</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Renovação automática</p>
                                                <p class="mb-0">Sim</p>
                                            </div>

                                            <div class="col-12">
                                                <p class="text-muted small mb-1">Observações</p>
                                                <p class="mb-0">Contrato associado à manutenção preventiva dos equipamentos da UCI, incluindo assistência técnica programada e apoio em caso de avaria.</p>
                                            </div>

                                            <div class="col-12">
                                                <div class="pdf-lista">
                                                    <div class="pdf-item">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                            <span>contrato-manutencao-uci.pdf</span>
                                                        </div>

                                                        <a href="../contratos/contrato-detalhes.php" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i> Ver
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="fw-bold mb-3">Outros contratos associados</h6>

                                        <div class="row g-3">
                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Tipo</p>
                                                <p class="mb-0">Assistência técnica</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Fornecedor</p>
                                                <p class="mb-0">MedRepair Norte</p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Estado</p>
                                                <p class="mb-0"><span class="badge badge-ativo">Ativo</span></p>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <p class="text-muted small mb-1">Validade</p>
                                                <p class="mb-0">31/12/2025</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <a href="../contratos/contratos.php" class="btn btn-outline-secondary btn-sm">
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