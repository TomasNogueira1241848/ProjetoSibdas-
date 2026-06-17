<?php
$pageTitle = 'MedInfo Solutions — Novo Equipamento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login/login.php';
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
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Dados do Equipamento</h6>
                                        <p class="text-muted small mb-0">Registe a identificação, características e entidades associadas ao equipamento.</p>
                                    </div>
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
                                        <label for="criticidadeEquipamento" class="form-label">Criticidade</label>
                                        <select class="form-select" id="criticidadeEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Baixa">Baixa</option>
                                            <option value="Média">Média</option>
                                            <option value="Alta">Alta</option>
                                            <option value="Crítica">Crítica</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione a criticidade.</div>
                                    </div>

                                     <div class="col-md-4">
                                        <label for="dataAquisicaoEquipamento" class="form-label">Data de aquisição</label>
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

                                    <div class="col-md-4">
                                        <label for="anoFabricoEquipamento" class="form-label">Ano de fabrico</label>
                                        <input type="number" class="form-control" id="anoFabricoEquipamento" min="1990"
                                            max="2026" step="1" required>
                                        <div class="invalid-feedback">Introduza o ano de fabrico.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="tipoEntradaEquipamento" class="form-label">Tipo de entrada</label>
                                        <select class="form-select" id="tipoEntradaEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Compra">Compra</option>
                                            <option value="Doação">Doação</option>
                                            <option value="Aluguer">Aluguer</option>
                                            <option value="Empréstimo">Empréstimo</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o tipo de entrada.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="observacoesEquipamento" class="form-label">Observações
                                            gerais</label>
                                        <textarea class="form-control" id="observacoesEquipamento" rows="3"
                                            required></textarea>
                                        <div class="invalid-feedback">Preencha o campo de observações gerais.</div>
                                    </div>

                                    <div class="col-12" mt-2>
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Entidades associadas ao equipamento</h6>
                                        <p class="text-muted small mb-0">Registe o fornecedor principal, fabricante, prestador de assistência técnica e restantes fornecedores associados.</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fornecedorEquipamento" class="form-label">Fornecedor principal</label>
                                        <select class="form-select" id="fornecedorEquipamento" name="fornecedorEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                            <option value="Dräger Portugal">Dräger Portugal</option>
                                            <option value="MedRepair Norte">MedRepair Norte</option>
                                            <option value="ForneConsumíveis SA">ForneConsumíveis SA</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o fornecedor principal.</div>
                                    
                                    </div>

                                    <div class="col-md-4">
                                        <label for="fabricanteEquipamento" class="form-label">Fabricante principal</label>
                                        <select class="form-select" id="fabricanteEquipamento" name="fabricanteEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                            <option value="Dräger Portugal">Dräger Portugal</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o fabricante.</div>
                                    
                                    </div>

                                    <div class="col-md-4">
                                        <label for="prestadorAssistenciaEquipamento" class="form-label">Prestador de assistência técnica principal</label>
                                        <select class="form-select" id="prestadorAssistenciaEquipamento" name="prestadorAssistenciaEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="Dräger Portugal">Dräger Portugal</option>
                                            <option value="MedRepair Norte">MedRepair Norte</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione o prestador de assistência técnica.</div>
                            
                                    </div>

                                    <div class="col-12">
                                        <label for="pesquisaFornecedoresAssociadosEquipamento" class="form-label">Fornecedores associados adicionais</label>
                                        <input type="search" class="form-control mb-2 pesquisa-fornecedores-associados"
                                            id="pesquisaFornecedoresAssociadosEquipamento"
                                            placeholder="Pesquisar fornecedor, fabricante, assistência técnica ou consumíveis..."
                                            data-fornecedores-container="listaFornecedoresAssociadosEquipamento">

                                        <div class="border rounded p-3" id="listaFornecedoresAssociadosEquipamento">
                                            <div class="row g-2">
                                                <div class="col-md-6 fornecedor-associado-item" data-fornecedor-item="Philips Healthcare Portugal Fabricante fornecedor comercial">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="fornecedoresAssociadosEquipamento[]"
                                                            id="fornecedorAssociadoPhilipsEquipamento"
                                                            value="Philips Healthcare Portugal">
                                                        <label class="form-check-label" for="fornecedorAssociadoPhilipsEquipamento">
                                                            <strong>Philips Healthcare Portugal</strong><br>
                                                            <span class="text-muted small">Fabricante / fornecedor comercial</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 fornecedor-associado-item" data-fornecedor-item="Dräger Portugal Distribuidor fornecedor comercial assistência técnica fabricante">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="fornecedoresAssociadosEquipamento[]"
                                                            id="fornecedorAssociadoDragerEquipamento"
                                                            value="Dräger Portugal">
                                                        <label class="form-check-label" for="fornecedorAssociadoDragerEquipamento">
                                                            <strong>Dräger Portugal</strong><br>
                                                            <span class="text-muted small">Distribuidor / fornecedor comercial</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 fornecedor-associado-item" data-fornecedor-item="MedRepair Norte Prestador assistência técnica manutenção">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="fornecedoresAssociadosEquipamento[]"
                                                            id="fornecedorAssociadoMedrepairEquipamento"
                                                            value="MedRepair Norte">
                                                        <label class="form-check-label" for="fornecedorAssociadoMedrepairEquipamento">
                                                            <strong>MedRepair Norte</strong><br>
                                                            <span class="text-muted small">Prestador de assistência técnica</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 fornecedor-associado-item" data-fornecedor-item="ForneConsumíveis SA Fornecedor consumíveis acessórios">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="fornecedoresAssociadosEquipamento[]"
                                                            id="fornecedorAssociadoForneconsumiveisEquipamento"
                                                            value="ForneConsumíveis SA">
                                                        <label class="form-check-label" for="fornecedorAssociadoForneconsumiveisEquipamento">
                                                            <strong>ForneConsumíveis SA</strong><br>
                                                            <span class="text-muted small">Fornecedor de consumíveis e acessórios</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-text">Pode selecionar vários fornecedores adicionais associados ao equipamento.</div>
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
                                </div>
                            </div>

                            <!-- LOCALIZAÇÃO -->
                            <div class="tab-pane fade" id="aba-localizacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Localização física do equipamento</h6>
                                        <p class="text-muted small mb-0">
                                            Associe o equipamento a uma localização principal e indique a posição específica dentro dessa localização.
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="localizacaoEquipamento" class="form-label">Localização principal</label>
                                        <select class="form-select" id="localizacaoEquipamento" name="localizacaoEquipamento" required>
                                            <option value="">Selecionar</option>
                                            <option value="LOC-001">LOC-001 — Unidade de Cuidados Intensivos</option>
                                        </select>
                                        <div class="invalid-feedback">Selecione a localização principal.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="departamentoServicoEquipamento" class="form-label">Departamento / serviço</label>
                                        <input type="text" class="form-control" id="departamentoServicoEquipamento"
                                            name="departamentoServicoEquipamento" required>
                                        <div class="invalid-feedback">Introduza o departamento ou serviço.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pisoEquipamento" class="form-label">N.º do andar</label>
                                        <input type="number" class="form-control" id="pisoEquipamento" name="pisoEquipamento"
                                            min="-1" step="1" required>
                                        <div class="invalid-feedback">Introduza o número do andar.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="salaGabineteEquipamento" class="form-label">Sala / gabinete</label>
                                        <input type="text" class="form-control" id="salaGabineteEquipamento"
                                            name="salaGabineteEquipamento" required>
                                        <div class="invalid-feedback">Introduza a sala ou gabinete.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTAÇÃO -->
                            <div class="tab-pane fade" id="aba-documentacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Documentação mínima necessária</h6>
                                        <p class="text-muted small mb-0">
                                            Adicione os dados e PDFs obrigatórios para que a ficha do equipamento fique completa.
                                        </p>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de utilizador
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoManualUtilizadorEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoManualUtilizadorEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoManualUtilizadorEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][tipo]" value="Manual de utilizador" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoManualUtilizadorEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento">Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoManualUtilizadorEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoManualUtilizadorEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoManualUtilizadorEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido">Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoManualUtilizadorEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoManualUtilizadorEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoManualUtilizadorEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroManualUtilizadorEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroManualUtilizadorEquipamento"
                                                    name="documentosMinimos[ManualUtilizador][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaManualUtilizadorEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaManualUtilizadorEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de serviço
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoManualServicoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoManualServicoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoManualServicoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][tipo]" value="Manual de serviço" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoManualServicoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento">Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoManualServicoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoManualServicoEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoManualServicoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido">Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoManualServicoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoManualServicoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoManualServicoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroManualServicoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroManualServicoEquipamento"
                                                    name="documentosMinimos[ManualServico][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaManualServicoEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaManualServicoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Certificado de calibração
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoCertificadoCalibracaoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][tipo]" value="Certificado de calibração" readonly required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoCertificadoCalibracaoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento">Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoCertificadoCalibracaoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoCertificadoCalibracaoEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido">Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoCertificadoCalibracaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoCertificadoCalibracaoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoCertificadoCalibracaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroCertificadoCalibracaoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroCertificadoCalibracaoEquipamento"
                                                    name="documentosMinimos[CertificadoCalibracao][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaCertificadoCalibracaoEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaCertificadoCalibracaoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Fatura ou guia de aquisição
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][tipo]" value="Fatura ou guia de aquisição" readonly required>
                                                    <div class="invalid-feedback">Introduza o tipo de documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento">Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido">Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroFaturaGuiaAquisicaoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroFaturaGuiaAquisicaoEquipamento"
                                                    name="documentosMinimos[FaturaGuiaAquisicao][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaFaturaGuiaAquisicaoEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaFaturaGuiaAquisicaoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Declaração de conformidade
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][tipo]" value="Declaração de conformidade" readonly required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento" >Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido" >Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroDeclaracaoConformidadeEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroDeclaracaoConformidadeEquipamento"
                                                    name="documentosMinimos[DeclaracaoConformidade][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaDeclaracaoConformidadeEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaDeclaracaoConformidadeEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Relatório técnico
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoRelatorioTecnicoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][codigo]" required>
                                                    <div class="invalid-feedback">Introduza o código do documento.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoRelatorioTecnicoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][titulo]" required>
                                                    <div class="invalid-feedback">Introduza o nome do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoRelatorioTecnicoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][tipo]" value="Relatório técnico" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="areaDocumentoRelatorioTecnicoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][area]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Equipamento">Equipamento</option>
                                                        <option value="Fornecedor">Fornecedor</option>
                                                        <option value="Manutenção">Manutenção</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a área.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataDocumentoRelatorioTecnicoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="date" class="form-control" id="dataDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][data_documento]" required>
                                                    <div class="invalid-feedback">Introduza a data do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoRelatorioTecnicoEquipamento" class="form-label">Validade</label>
                                                    <input type="date" class="form-control" id="validadeDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][validade]" required>
                                                    <div class="invalid-feedback">Introduza a validade do documento.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoRelatorioTecnicoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Válido">Válido</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Substituído">Substituído</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do documento.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoRelatorioTecnicoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][responsavel]" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoRelatorioTecnicoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesDocumentoRelatorioTecnicoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do documento.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label for="ficheiroRelatorioTecnicoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroRelatorioTecnicoEquipamento"
                                                    name="documentosMinimos[RelatorioTecnico][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaRelatorioTecnicoEquipamento" required>
                                                <div class="invalid-feedback">Adicione o PDF do documento.</div>
                                                <div class="form-text">Pode selecionar um ou mais PDFs deste documento.</div>
                                                <div class="pdf-lista mt-3" id="listaRelatorioTecnicoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Outros documentos</h6>
                                        <p class="text-muted small mb-0">
                                            Área opcional para ficheiros adicionais, como ficha técnica, imagens técnicas ou outros anexos.
                                        </p>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="codigoOutroDocumentoEquipamento" class="form-label">Código</label>
                                        <input type="text" class="form-control" id="codigoOutroDocumentoEquipamento"
                                            name="outrosDocumentos[codigo]">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="tipoOutroDocumentoEquipamento" class="form-label">Tipo de documento</label>
                                        <select class="form-select" id="tipoOutroDocumentoEquipamento" name="outrosDocumentos[tipo]">
                                            <option value="">Selecionar</option>
                                            <option value="Ficha técnica">Ficha técnica</option>
                                            <option value="Registo de manutenção">Registo de manutenção</option>
                                            <option value="Relatório de intervenção">Relatório de intervenção</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tituloOutroDocumentoEquipamento" class="form-label">Nome do documento</label>
                                        <input type="text" class="form-control" id="tituloOutroDocumentoEquipamento"
                                            name="outrosDocumentos[titulo]">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="dataOutroDocumentoEquipamento" class="form-label">Data do documento</label>
                                        <input type="date" class="form-control" id="dataOutroDocumentoEquipamento"
                                            name="outrosDocumentos[data_documento]">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="validadeOutroDocumentoEquipamento" class="form-label">Validade</label>
                                        <input type="date" class="form-control" id="validadeOutroDocumentoEquipamento"
                                            name="outrosDocumentos[validade]">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="areaOutroDocumentoEquipamento" class="form-label">Área</label>
                                        <select class="form-select" id="areaOutroDocumentoEquipamento" name="outrosDocumentos[area]">
                                                <option value="">Selecionar</option>
                                            <option value="Equipamento">Equipamento</option>
                                            <option value="Fornecedor">Fornecedor</option>
                                            <option value="Manutenção">Manutenção</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="estadoOutroDocumentoEquipamento" class="form-label">Estado</label>
                                        <select class="form-select" id="estadoOutroDocumentoEquipamento" name="outrosDocumentos[estado]">
                                            <option value="">Selecionar</option>
                                            <option value="Válido">Válido</option>
                                            <option value="A expirar">A expirar</option>
                                            <option value="Expirado">Expirado</option>
                                            <option value="Substituído">Substituído</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fornecedorOutroDocumentoEquipamento" class="form-label">Fornecedor associado</label>
                                        <select class="form-select" id="fornecedorOutroDocumentoEquipamento" name="outrosDocumentos[fornecedor]">
                                            <option value="">Selecionar</option>
                                            <option value="Sem fornecedor associado">Sem fornecedor associado</option>
                                            <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                            <option value="Dräger Portugal">Dräger Portugal</option>
                                            <option value="MedRepair Norte">MedRepair Norte</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="responsavelOutroDocumentoEquipamento" class="form-label">Responsável</label>
                                        <input type="text" class="form-control" id="responsavelOutroDocumentoEquipamento"
                                            name="outrosDocumentos[responsavel]">
                                    </div>

                                    <div class="col-12">
                                        <label for="observacoesOutroDocumentoEquipamento" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoesOutroDocumentoEquipamento"
                                            name="outrosDocumentos[observacoes]" rows="2"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <label for="ficheirosOutrosDocumentosEquipamento" class="form-label fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs dos outros documentos
                                            </label>
                                            <input type="file" class="form-control input-pdf-multiplo"
                                                id="ficheirosOutrosDocumentosEquipamento"
                                                name="outrosDocumentos[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                data-lista="listaOutrosDocumentosEquipamento" data-removivel="true">
                                            <div class="form-text">Pode selecionar vários PDFs opcionais e remover antes de guardar.</div>
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
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Garantias e contratos do equipamento</h6>
                                        <p class="text-muted small mb-0">
                                            Registe a informação completa da garantia, do contrato de manutenção e de outros contratos associados.
                                        </p>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-shield-halved me-1 text-primary"></i>
                                                        Garantia
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados de acordo com a ficha de detalhes da garantia.</p>
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoGarantiaEquipamento" class="form-label">Código da garantia</label>
                                                    <input type="text" class="form-control" id="codigoGarantiaEquipamento" name="garantia[codigo]" value="" required>
                                                    <div class="invalid-feedback">Introduza o código da garantia.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="designacaoGarantiaEquipamento" class="form-label">Designação da garantia</label>
                                                    <input type="text" class="form-control" id="designacaoGarantiaEquipamento" name="garantia[designacao]" value="" required>
                                                    <div class="invalid-feedback">Introduza a designação da garantia.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoGarantiaEquipamento" class="form-label">Tipo</label>
                                                    <input type="text" class="form-control" id="tipoGarantiaEquipamento" name="garantia[tipo]" value="Garantia" readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorGarantiaEquipamento" class="form-label">Fornecedor</label>
                                                    <select class="form-select" id="fornecedorGarantiaEquipamento" name="garantia[fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor da garantia.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="equipamentoAssociadoGarantia" class="form-label">Equipamento associado</label>
                                                    <input type="text" class="form-control" id="equipamentoAssociadoGarantia" name="garantia[equipamento]" value="" required>
                                                    <div class="invalid-feedback">Indique o equipamento associado.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelGarantiaEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelGarantiaEquipamento" name="garantia[responsavel]" value="" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="inicioGarantiaEquipamento" class="form-label">Data de início</label>
                                                    <input type="date" class="form-control" id="inicioGarantiaEquipamento" name="garantia[data_inicio]" value="" required>
                                                    <div class="invalid-feedback">Introduza a data de início.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="fimGarantiaEquipamento" class="form-label">Data de fim</label>
                                                    <input type="date" class="form-control" id="fimGarantiaEquipamento" name="garantia[data_fim]" value="" required>
                                                    <div class="invalid-feedback">Introduza a data de fim.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoGarantiaEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoGarantiaEquipamento" name="garantia[estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Ativo">Ativo</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado da garantia.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="contratoAssociadoGarantia" class="form-label">Contrato associado</label>
                                                    <select class="form-select" id="contratoAssociadoGarantia" name="garantia[contrato_associado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="CON-001">CON-001 — Contrato de Manutenção UCI</option>
                                                        <option value="CON-002">CON-002 — Seguro de Equipamentos</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o contrato associado à garantia.</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="coberturaGarantiaEquipamento" class="form-label">Cobertura</label>
                                                    <textarea class="form-control" id="coberturaGarantiaEquipamento" name="garantia[cobertura]" rows="3" required></textarea>
                                                    <div class="invalid-feedback">Introduza a cobertura da garantia.</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesGarantiaEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesGarantiaEquipamento" name="garantia[observacoes]" rows="2" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações da garantia.</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="ficheirosGarantiaEquipamento" class="form-label fw-semibold">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs da garantia
                                                    </label>
                                                    <input type="file" class="form-control input-pdf-multiplo" id="ficheirosGarantiaEquipamento"
                                                        name="garantia[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                        data-lista="listaFicheirosGarantiaEquipamento" data-removivel="true" required>
                                                    <div class="invalid-feedback">Adicione pelo menos um PDF da garantia.</div>
                                                    <div class="form-text">Pode selecionar vários PDFs da garantia.</div>
                                                    <div class="pdf-lista mt-3" id="listaFicheirosGarantiaEquipamento">
                                                        <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-contract me-1 text-danger"></i>
                                                        Contrato de manutenção
                                                    </h6>
                                                    <p class="text-muted small mb-0">Contrato obrigatório com os dados usados no módulo de contratos.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoContratoManutencaoEquipamento" class="form-label">Código do contrato</label>
                                                    <input type="text" class="form-control" id="codigoContratoManutencaoEquipamento" name="contratoManutencao[codigo]" value="" required>
                                                    <div class="invalid-feedback">Introduza o código do contrato.</div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="designacaoContratoManutencaoEquipamento" class="form-label">Designação</label>
                                                    <input type="text" class="form-control" id="designacaoContratoManutencaoEquipamento" name="contratoManutencao[designacao]" value="" required>
                                                    <div class="invalid-feedback">Introduza a designação do contrato.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tipoContratoManutencaoEquipamento" class="form-label">Tipo de contrato</label>
                                                    <input type="text" class="form-control" id="tipoContratoManutencaoEquipamento" name="contratoManutencao[tipo]" value="Manutenção" readonly required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorContratoManutencaoEquipamento" class="form-label">Fornecedor</label>
                                                    <select class="form-select" id="fornecedorContratoManutencaoEquipamento" name="contratoManutencao[fornecedor]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o fornecedor do contrato.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="associadoContratoManutencaoEquipamento" class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" id="associadoContratoManutencaoEquipamento" name="contratoManutencao[associado_a]" value="" required>
                                                    <div class="invalid-feedback">Indique a associação do contrato.</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelContratoManutencaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelContratoManutencaoEquipamento" name="contratoManutencao[responsavel]" value="" required>
                                                    <div class="invalid-feedback">Introduza o responsável.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="inicioContratoManutencaoEquipamento" class="form-label">Data de início</label>
                                                    <input type="date" class="form-control" id="inicioContratoManutencaoEquipamento" name="contratoManutencao[data_inicio]" value="" required>
                                                    <div class="invalid-feedback">Introduza a data de início.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="fimContratoManutencaoEquipamento" class="form-label">Data de fim</label>
                                                    <input type="date" class="form-control" id="fimContratoManutencaoEquipamento" name="contratoManutencao[data_fim]" value="" required>
                                                    <div class="invalid-feedback">Introduza a data de fim.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="valorContratoManutencaoEquipamento" class="form-label">Valor anual (€)</label>
                                                    <input type="number" class="form-control" id="valorContratoManutencaoEquipamento" name="contratoManutencao[valor_anual]" min="0" step="0.01" value="" required>
                                                    <div class="invalid-feedback">Introduza o valor anual.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="periodicidadeContratoManutencaoEquipamento" class="form-label">Periodicidade</label>
                                                    <select class="form-select" id="periodicidadeContratoManutencaoEquipamento" name="contratoManutencao[periodicidade]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Mensal">Mensal</option>
                                                        <option value="Trimestral">Trimestral</option>
                                                        <option value="Semestral">Semestral</option>
                                                        <option value="Anual">Anual</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione a periodicidade.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="renovacaoContratoManutencaoEquipamento" class="form-label">Renovação automática</label>
                                                    <select class="form-select" id="renovacaoContratoManutencaoEquipamento" name="contratoManutencao[renovacao_automatica]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sim">Sim</option>
                                                        <option value="Não">Não</option>
                                                    </select>
                                                    <div class="invalid-feedback">Indique a renovação automática.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoContratoManutencaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoContratoManutencaoEquipamento" name="contratoManutencao[estado]" required>
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Ativo">Ativo</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                    </select>
                                                    <div class="invalid-feedback">Selecione o estado do contrato.</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesContratoManutencaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesContratoManutencaoEquipamento" name="contratoManutencao[observacoes]" rows="3" required></textarea>
                                                    <div class="invalid-feedback">Introduza as observações do contrato.</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="ficheirosContratoManutencaoEquipamento" class="form-label fw-semibold">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs do contrato de manutenção
                                                    </label>
                                                    <input type="file" class="form-control input-pdf-multiplo" id="ficheirosContratoManutencaoEquipamento"
                                                        name="contratoManutencao[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                        data-lista="listaFicheirosContratoManutencaoEquipamento" data-removivel="true" required>
                                                    <div class="invalid-feedback">Adicione pelo menos um PDF do contrato.</div>
                                                    <div class="form-text">Pode selecionar vários PDFs do contrato de manutenção.</div>
                                                    <div class="pdf-lista mt-3" id="listaFicheirosContratoManutencaoEquipamento">
                                                        <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <h6 class="fw-bold mb-1">
                                                <i class="fa-solid fa-folder-plus me-1 text-primary"></i> Outros contratos
                                            </h6>
                                            <p class="text-muted small mb-3">Área opcional para seguros, assistência técnica ou outros contratos.</p>

                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoOutroContratoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoOutroContratoEquipamento" name="outrosContratos[codigo]">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="tipoOutroContratoEquipamento" class="form-label">Tipo de contrato</label>
                                                    <select class="form-select" id="tipoOutroContratoEquipamento" name="outrosContratos[tipo]">
                                                        <option value="">Selecionar</option>
                                                        <option value="Seguro">Seguro</option>
                                                        <option value="Assistência Técnica">Assistência Técnica</option>
                                                        <option value="Aluguer">Aluguer</option>
                                                        <option value="Outro">Outro</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="designacaoOutroContratoEquipamento" class="form-label">Designação</label>
                                                    <input type="text" class="form-control" id="designacaoOutroContratoEquipamento" name="outrosContratos[designacao]">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="fornecedorOutroContratoEquipamento" class="form-label">Entidade responsável / fornecedor</label>
                                                    <select class="form-select" id="fornecedorOutroContratoEquipamento" name="outrosContratos[fornecedor]">
                                                        <option value="">Selecionar</option>
                                                        <option value="Philips Healthcare Portugal">Philips Healthcare Portugal</option>
                                                        <option value="Dräger Portugal">Dräger Portugal</option>
                                                        <option value="MedRepair Norte">MedRepair Norte</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="associadoOutroContratoEquipamento" class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" id="associadoOutroContratoEquipamento" name="outrosContratos[associado]">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="responsavelOutroContratoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelOutroContratoEquipamento" name="outrosContratos[responsavel]">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataInicioOutroContratoEquipamento" class="form-label">Data de início</label>
                                                    <input type="date" class="form-control" id="dataInicioOutroContratoEquipamento" name="outrosContratos[data_inicio]">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="dataFimOutroContratoEquipamento" class="form-label">Data de fim</label>
                                                    <input type="date" class="form-control" id="dataFimOutroContratoEquipamento" name="outrosContratos[data_fim]">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="valorAnualOutroContratoEquipamento" class="form-label">Valor anual (€)</label>
                                                    <input type="number" class="form-control" id="valorAnualOutroContratoEquipamento" name="outrosContratos[valor_anual]" min="0" step="0.01">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="periodicidadeOutroContratoEquipamento" class="form-label">Periodicidade</label>
                                                    <input type="text" class="form-control" id="periodicidadeOutroContratoEquipamento" name="outrosContratos[periodicidade]">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="renovacaoOutroContratoEquipamento" class="form-label">Renovação automática</label>
                                                    <select class="form-select" id="renovacaoOutroContratoEquipamento" name="outrosContratos[renovacao_automatica]">
                                                        <option value="">Selecionar</option>
                                                        <option value="Não">Não</option>
                                                        <option value="Sim">Sim</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="estadoOutroContratoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoOutroContratoEquipamento" name="outrosContratos[estado]">
                                                        <option value="">Selecionar</option>
                                                        <option value="Ativo">Ativo</option>
                                                        <option value="A expirar">A expirar</option>
                                                        <option value="Expirado">Expirado</option>
                                                        <option value="Cancelado">Cancelado</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoesOutroContratoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesOutroContratoEquipamento" name="outrosContratos[observacoes]" rows="2"></textarea>
                                                </div>

                                                <div class="col-12">
                                                    <label for="ficheirosOutrosContratosEquipamento" class="form-label fw-semibold">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs dos outros contratos
                                                    </label>
                                                    <input type="file" class="form-control input-pdf-multiplo" id="ficheirosOutrosContratosEquipamento"
                                                        name="outrosContratos[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                        data-lista="listaFicheirosOutrosContratosEquipamento" data-removivel="true">
                                                    <div class="form-text">Pode selecionar vários PDFs opcionais.</div>
                                                    <div class="pdf-lista mt-3" id="listaFicheirosOutrosContratosEquipamento">
                                                        <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- MANUTENÇÃO -->
                            <div class="tab-pane fade" id="aba-manutencao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Manutenção</h6>
                                        <p class="text-muted small mb-0">Defina o acompanhamento técnico, periodicidade e prioridade de manutenção do equipamento.</p>
                                    </div>
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

