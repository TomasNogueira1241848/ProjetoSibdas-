<?php
$pageTitle = 'MedInfo Solutions — Novo Equipamento';
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
                    <h4 class="fw-bold mb-1">Novo equipamento</h4>
                    <p class="text-muted small mb-0">Registo de um novo equipamento médico no inventário hospitalar.
                    </p>
                </div>
            </div>

            <!-- FORMULÁRIO -->
            <section class="mb-4">
                <div class="card p-4">
                    <form id="formEquipamento" novalidate>

                        <!-- ABAS DO FORMULÁRIO -->
                        <ul class="nav nav-pills abas-equipamento mb-4" id="abasEquipamento" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="aba-dados-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-dados" type="button" role="tab">
                                    <span class="aba-numero">1</span>
                                    <span>
                                        <strong>Dados principais</strong>
                                        <small>Identificação do equipamento</small>
                                    </span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-localizacao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-localizacao" type="button" role="tab">
                                    <span class="aba-numero">2</span>
                                    <span>
                                        <strong>Localização</strong>
                                        <small>Serviço, piso e sala</small>
                                    </span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-documentacao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-documentacao" type="button" role="tab">
                                    <span class="aba-numero">3</span>
                                    <span>
                                        <strong>Documentação</strong>
                                        <small>Manuais e certificados PDF</small>
                                    </span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-garantia-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-garantia" type="button" role="tab">
                                    <span class="aba-numero">4</span>
                                    <span>
                                        <strong>Garantia e contrato</strong>
                                        <small>Associação e PDFs</small>
                                    </span>
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-manutencao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-manutencao" type="button" role="tab">
                                    <span class="aba-numero">5</span>
                                    <span>
                                        <strong>Manutenção</strong>
                                        <small>Preventiva e prioridade</small>
                                    </span>
                                </button>
                            </li>
                        </ul>

                        <!-- CONTEÚDO DAS ABAS -->
                        <div class="tab-content">

                            <!-- DADOS PRINCIPAIS -->
                            <div class="tab-pane fade show active" id="aba-dados" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="codigoEquipamento" class="form-label">Código </label>
                                        <input type="text" class="form-control" id="codigoEquipamento" value="EQ-0043"
                                            required>
                                        <div class="invalid-feedback">Introduza o código.</div>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="designacaoEquipamento" class="form-label">Designação </label>
                                        <input type="text" class="form-control" id="designacaoEquipamento" required>
                                        <div class="invalid-feedback">Introduza a designação.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="categoriaEquipamento" class="form-label">Categoria </label>
                                        <select class="form-select" id="categoriaEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Monitorização">Monitorização</option>
                                            <option value="Suporte de Vida">Suporte de Vida</option>
                                            <option value="Terapia">Terapia</option>
                                            <option value="Diagnóstico">Diagnóstico</option>
                                            <option value="Laboratório">Laboratório</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione a categoria.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="marcaEquipamento" class="form-label">Marca </label>
                                        <input type="text" class="form-control" id="marcaEquipamento" required>
                                        <div class="invalid-feedback">Introduza a marca.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="modeloEquipamento" class="form-label">Modelo </label>
                                        <input type="text" class="form-control" id="modeloEquipamento" required>
                                        <div class="invalid-feedback">Introduza o modelo.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="numeroSerieEquipamento" class="form-label">N.º de série </label>
                                        <input type="text" class="form-control" id="numeroSerieEquipamento"
                                            placeholder="Ex: SN-12345" required>
                                        <div class="invalid-feedback">Introduza o número de série.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="estadoEquipamento" class="form-label">Estado </label>
                                        <select class="form-select" id="estadoEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Em Manutenção">Em Manutenção</option>
                                            <option value="Inativo">Inativo</option>
                                            <option value="Em Calibração">Em Calibração</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o estado.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="fornecedorEquipamento" class="form-label">Fornecedor </label>
                                        <select class="form-select" id="fornecedorEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Philips Healthcare Portugal">Philips Healthcare Portugal
                                            </option>
                                            <option value="Dräger Portugal">Dräger Portugal</option>
                                            <option value="MedRepair Norte">MedRepair Norte</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o fornecedor.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="dataAquisicaoEquipamento" class="form-label">Data de
                                            aquisição</label>
                                        <input type="date" class="form-control" id="dataAquisicaoEquipamento" required>
                                        <div class="invalid-feedback">Introduza a data de aquisição.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="custoAquisicaoEquipamento" class="form-label">Custo de aquisição
                                            (€)</label>
                                        <input type="number" class="form-control" id="custoAquisicaoEquipamento" min="0"
                                            step="0.01" required>
                                        <div class="invalid-feedback">Introduza o custo de aquisição.</div>
                                    </div>


                                    <div class="col-12 mt-2">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Relações e consumíveis</h6>
                                        <p class="text-muted small mb-0">
                                            Indique se este equipamento pertence a outro equipamento e se utiliza
                                            consumíveis.
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="componenteEquipamento" class="form-label">É componente de outro
                                            equipamento?</label>
                                        <select class="form-select" id="componenteEquipamento" required>
                                            <option value="Não" selected>Não</option>
                                            <option value="Sim">Sim</option>
                                        </select>
                                        <div class="invalid-feedback">Indique se o equipamento é componente de
                                            outro.</div>
                                    </div>

                                    <div class="col-md-8 d-none" id="grupoEquipamentoPai">
                                        <label for="equipamentoPaiEquipamento" class="form-label">Equipamento
                                            principal</label>
                                        <select class="form-select" id="equipamentoPaiEquipamento">
                                            <option value="">Selecionar equipamento principal</option>
                                            <option value="EQ-0042">EQ-0042 — Monitor Multiparamétrico</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o equipamento principal.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="temConsumiveisEquipamento" class="form-label">Tem
                                            consumíveis?</label>
                                        <select class="form-select" id="temConsumiveisEquipamento" required>
                                            <option value="Não" selected>Não</option>
                                            <option value="Sim">Sim</option>
                                        </select>
                                        <div class="invalid-feedback">Indique se o equipamento tem consumíveis.
                                        </div>
                                    </div>

                                    <div class="col-md-8 d-none" id="grupoConsumiveisEquipamento">
                                        <label for="consumiveisEquipamento" class="form-label">Consumíveis
                                            associados</label>
                                        <textarea class="form-control" id="consumiveisEquipamento" rows="3"
                                            placeholder="Ex: elétrodos ECG, sensores SpO2, papel térmico"></textarea>
                                        <div class="invalid-feedback">Indique os consumíveis associados.</div>
                                        <div class="form-text">Separe os consumíveis por vírgulas ou por linhas.
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="observacoesEquipamento" class="form-label">Observações
                                            gerais</label>
                                        <textarea class="form-control" id="observacoesEquipamento" rows="3"
                                            required></textarea>
                                        <div class="invalid-feedback">Preencha o campo de observações gerais.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- LOCALIZAÇÃO -->
                            <div class="tab-pane fade" id="aba-localizacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="localizacaoEquipamento" class="form-label">Localização </label>
                                        <select class="form-select" id="localizacaoEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="UCI">UCI</option>
                                            <option value="Urgência">Urgência</option>
                                            <option value="Bloco Operatório">Bloco Operatório</option>
                                            <option value="Medicina Interna">Medicina Interna</option>
                                            <option value="Consulta Externa">Consulta Externa</option>
                                            <option value="Laboratório">Laboratório</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione a localização.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="servicoEquipamento" class="form-label">Serviço</label>
                                        <input type="text" class="form-control" id="servicoEquipamento"
                                            placeholder="Ex: Unidade de Cuidados Intensivos" required>
                                        <div class="invalid-feedback">Introduza o serviço.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pisoEquipamento" class="form-label">Andar</label>
                                        <input type="number" class="form-control" id="pisoEquipamento" min="0" step="1"
                                            required>
                                        <div class="invalid-feedback">Introduza o número do andar.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="salaEquipamento" class="form-label">Sala</label>
                                        <input type="text" class="form-control" id="salaEquipamento"
                                            placeholder="Ex: UCI-02" required>
                                        <div class="invalid-feedback">Introduza a sala.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTAÇÃO -->
                            <div class="tab-pane fade" id="aba-documentacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Documentação mínima necessária</h6>
                                        <p class="text-muted small mb-0">
                                            Adicione os PDFs obrigatórios para que a ficha do equipamento fique
                                            completa.
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroManualUtilizadorEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de
                                                utilizador
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroManualUtilizadorEquipamento"
                                                name="documentosMinimos[manual_utilizador][]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaManualUtilizadorEquipamento" required>
                                            <div class="invalid-feedback">Adicione o manual de utilizador em PDF.
                                            </div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaManualUtilizadorEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroManualServicoEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de
                                                serviço
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroManualServicoEquipamento"
                                                name="documentosMinimos[manual_servico][]" accept="application/pdf,.pdf"
                                                multiple data-lista="listaManualServicoEquipamento" required>
                                            <div class="invalid-feedback">Adicione o manual de serviço em PDF.</div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaManualServicoEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroCertificadoCalibracaoEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Certificado de
                                                calibração
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroCertificadoCalibracaoEquipamento"
                                                name="documentosMinimos[certificado_calibracao][]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaCertificadoCalibracaoEquipamento" required>
                                            <div class="invalid-feedback">Adicione o certificado de calibração em
                                                PDF.</div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaCertificadoCalibracaoEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroFaturaGuiaAquisicaoEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Fatura ou guia
                                                de aquisição
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroFaturaGuiaAquisicaoEquipamento"
                                                name="documentosMinimos[fatura_guia_aquisicao][]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaFaturaGuiaAquisicaoEquipamento" required>
                                            <div class="invalid-feedback">Adicione a fatura ou guia de aquisição em
                                                PDF.</div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaFaturaGuiaAquisicaoEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroDeclaracaoConformidadeEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Declaração de
                                                conformidade
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroDeclaracaoConformidadeEquipamento"
                                                name="documentosMinimos[declaracao_conformidade][]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaDeclaracaoConformidadeEquipamento" required>
                                            <div class="invalid-feedback">Adicione a declaração de conformidade em
                                                PDF.</div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaDeclaracaoConformidadeEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheiroRelatorioTecnicoEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Relatório
                                                técnico
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheiroRelatorioTecnicoEquipamento"
                                                name="documentosMinimos[relatorio_tecnico][]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaRelatorioTecnicoEquipamento" required>
                                            <div class="invalid-feedback">Adicione o relatório técnico em PDF.</div>

                                            <div class="form-text">Pode selecionar um ou mais PDFs.</div>

                                            <div class="pdf-lista mt-3" id="listaRelatorioTecnicoEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Outros documentos</h6>
                                        <p class="text-muted small mb-0">
                                            Área opcional para ficheiros adicionais, como ficha técnica, imagens
                                            técnicas ou outros anexos.
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="tipoOutroDocumentoEquipamento" class="form-label">Tipo de
                                            documento</label>
                                        <select class="form-select" id="tipoOutroDocumentoEquipamento">
                                            <option value="">Selecionar</option>
                                            <option value="Ficha técnica">Ficha técnica</option>
                                            <option value="Registo de manutenção">Registo de manutenção</option>
                                            <option value="Relatório de intervenção">Relatório de intervenção
                                            </option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="tituloOutroDocumentoEquipamento" class="form-label">Título do
                                            documento</label>
                                        <input type="text" class="form-control" id="tituloOutroDocumentoEquipamento"
                                            placeholder="Ex: Ficha técnica complementar">
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <label for="ficheirosOutrosDocumentosEquipamento"
                                                class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs
                                                adicionais
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheirosOutrosDocumentosEquipamento"
                                                name="outrosDocumentosEquipamento[]" accept="application/pdf,.pdf"
                                                multiple data-lista="listaOutrosDocumentosEquipamento"
                                                data-removivel="true">

                                            <div class="form-text">Campo opcional.</div>

                                            <div class="pdf-lista mt-3" id="listaOutrosDocumentosEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GARANTIA E CONTRATO -->
                            <div class="tab-pane fade" id="aba-garantia" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="codigoGarantiaEquipamento" class="form-label">Código da
                                            garantia</label>
                                        <input type="text" class="form-control" id="codigoGarantiaEquipamento"
                                            value="GAR-003" required>
                                        <div class="invalid-feedback">Introduza o código da garantia.</div>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="designacaoGarantiaEquipamento" class="form-label">Designação da
                                            garantia</label>
                                        <input type="text" class="form-control" id="designacaoGarantiaEquipamento"
                                            required>
                                        <div class="invalid-feedback">Introduza a designação da garantia.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="inicioGarantiaEquipamento" class="form-label">Início da
                                            garantia</label>
                                        <input type="date" class="form-control" id="inicioGarantiaEquipamento" required>
                                        <div class="invalid-feedback">Introduza a data de início da garantia.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fimGarantiaEquipamento" class="form-label">Fim da
                                            garantia</label>
                                        <input type="date" class="form-control" id="fimGarantiaEquipamento" required>
                                        <div class="invalid-feedback">Introduza a data de fim da garantia.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contratoEquipamento" class="form-label">Contrato
                                            associado</label>
                                        <select class="form-select" id="contratoEquipamento" required>
                                            <option value="">Sem contrato associado</option>
                                            <option value="CON-001">CON-001 — Contrato de Manutenção UCI</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o contrato associado.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="estadoGarantiaEquipamento" class="form-label">Estado da
                                            garantia</label>
                                        <select class="form-select" id="estadoGarantiaEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="A expirar">A expirar</option>
                                            <option value="Expirado">Expirado</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o estado da garantia.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="coberturaGarantiaEquipamento" class="form-label">Cobertura de
                                            garantia</label>
                                        <textarea class="form-control" id="coberturaGarantiaEquipamento" rows="3"
                                            required></textarea>
                                        <div class="invalid-feedback">Introduza o campo de cobertura de garantia.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheirosGarantiaEquipamento" class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs da
                                                garantia
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheirosGarantiaEquipamento" name="ficheirosGarantiaEquipamento[]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaFicheirosGarantiaEquipamento" required
                                                data-removivel="true">
                                            <div class="invalid-feedback">Selecione um ficheiro.</div>

                                            <p class="text-muted small mt-2 mb-3">Pode adicionar vários PDFs da
                                                garantia.</p>

                                            <div class="pdf-lista mt-3" id="listaFicheirosGarantiaEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-upload-card h-100">
                                            <label for="ficheirosContratoEquipamento" class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs do
                                                contrato
                                            </label>

                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheirosContratoEquipamento" name="ficheirosContratoEquipamento[]"
                                                accept="application/pdf,.pdf" multiple
                                                data-lista="listaFicheirosContratoEquipamento" required
                                                data-removivel="true">
                                            <div class="invalid-feedback">Selecione um ficheiro.</div>

                                            <p class="text-muted small mt-2 mb-3">Pode adicionar vários PDFs do
                                                contrato.</p>

                                            <div class="pdf-lista mt-3" id="listaFicheirosContratoEquipamento">
                                                <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MANUTENÇÃO -->
                            <div class="tab-pane fade" id="aba-manutencao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="ultimaManutencaoEquipamento" class="form-label">Última
                                            manutenção</label>
                                        <input type="date" class="form-control" id="ultimaManutencaoEquipamento"
                                            required>
                                        <div class="invalid-feedback">Introduza a data da última manutenção.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="proximaManutencaoEquipamento" class="form-label">Próxima
                                            manutenção</label>
                                        <input type="date" class="form-control" id="proximaManutencaoEquipamento"
                                            required>
                                        <div class="invalid-feedback">Introduza a data da próxima manutenção.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="estadoManutencaoEquipamento" class="form-label">Estado da
                                            manutenção</label>
                                        <select class="form-select" id="estadoManutencaoEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Em dia">Em dia</option>
                                            <option value="Agendada">Agendada</option>
                                            <option value="Pendente">Pendente</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o estado da manutenção.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="periodicidadeManutencaoEquipamento"
                                            class="form-label">Periodicidade</label>
                                        <input type="text" class="form-control" id="periodicidadeManutencaoEquipamento"
                                            placeholder="Ex: Anual" required>
                                        <div class="invalid-feedback">Introduza a periodicidade da manutenção.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="responsavelManutencaoEquipamento"
                                            class="form-label">Responsável</label>
                                        <input type="text" class="form-control" id="responsavelManutencaoEquipamento"
                                            required>
                                        <div class="invalid-feedback">Introduza o resposável da manutenção.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="prioridadeManutencaoEquipamento"
                                            class="form-label">Prioridade</label>
                                        <select class="form-select" id="prioridadeManutencaoEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Média">Média</option>
                                            <option value="Alta">Alta</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione a prioridade da manutenção.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MENSAGEM -->
                        <div id="mensagemEquipamento" class="alert alert-success d-none mt-4">
                            <i class="fa-solid fa-check me-1"></i> Equipamento guardado com sucesso.
                        </div>

                        <!-- AÇÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                            <a href="equipamentos.php" class="btn btn-outline-secondary">Cancelar</a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Guardar equipamento
                            </button>
                        </div>

                    </form>

                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>