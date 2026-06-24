document.addEventListener('DOMContentLoaded', function () {
    const inicializadores = [
        inicializarFlatpickrGlobal,
        inicializarDataTablesAreaReservada,
        inicializarFormularioContacto,
        inicializarTooltips,
        inicializarToastPublic,
        inicializarNavegacaoAbasEquipamento,
        preencherFormularioEquipamentoEditar,
        inicializarBlocosDinamicosEquipamento,
        inicializarInputsPDFs,
        inicializarBotoesRemoverPDFs,
        inicializarCamposCondicionaisEquipamento,
        inicializarPesquisaFornecedoresAssociados,
        inicializarGraficosDashboard
    ];
 
    inicializadores.forEach(function (inicializador) {
        try {
            if (typeof inicializador === 'function') {
                inicializador();
            }
        } catch (erro) {
            console.error('Erro ao inicializar componente:', erro);
        }
    });
});
 
/* Validação do formulário de contacto da área pública */
// Liga o formulario de contacto publico: valida no envio e mostra feedback.
function inicializarFormularioContacto() {
    const formContacto = document.getElementById('formContacto');
 
    if (!formContacto) return;
 
    formContacto.addEventListener('submit', function (e) {
        if (!validarFormulario(formContacto)) {
            e.preventDefault();
        }
    });
}
 
 
/* Ativa os tooltips do Bootstrap */
// Ativa os tooltips do Bootstrap em todos os elementos marcados.
function inicializarTooltips() {
    if (typeof bootstrap === 'undefined') return;
 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
 
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
 
 
/* Mostra o toast da página pública */
// Mostra as notificacoes (toasts) da area publica, se existirem.
function inicializarToastPublic() {
    if (typeof bootstrap === 'undefined') return;
 
    const toastEl = document.getElementById('toastPublic');
 
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }
}
 
 
/* Valida qualquer formulário HTML de forma reutilizável */
// Valida um formulario combinando as regras HTML e a validacao manual.
function validarFormulario(formulario) {
    return validarFormularioHTML(formulario);
}
 
 
/* Validação base dos formulários com required e invalid-feedback no HTML */
// Corre a validacao nativa do browser e devolve se o formulario e valido.
function validarFormularioHTML(formulario) {
    limparValidacaoManual(formulario);
 
    if (formulario.checkValidity()) {
        return true;
    }
 
    marcarCamposInvalidos(formulario);
    abrirAbaComCampoInvalido(formulario);
    return false;
}
 
 
/* Remove apenas as classes colocadas pelo nosso JavaScript */
// Remove as marcas de erro (is-invalid) de todos os campos do formulario.
function limparValidacaoManual(formulario) {
    formulario.classList.remove('was-validated');
 
    formulario.querySelectorAll('.is-invalid').forEach(function (campo) {
        campo.classList.remove('is-invalid');
    });
}
 
 
/* Marca apenas campos obrigatórios inválidos */
// Percorre os campos e assinala visualmente os que estao invalidos.
function marcarCamposInvalidos(formulario) {
    const campos = formulario.querySelectorAll('input, select, textarea');
 
    campos.forEach(function (campo) {
        if (!campo.required || campo.disabled) return;
 
        if (!campo.checkValidity()) {
            campo.classList.add('is-invalid');
        }
    });
}
 
 
/* Limpa o erro quando o campo obrigatório fica válido */
// Atualiza a marca de valido/invalido de um campo individual.
function atualizarEstadoCampo(campo) {
    if (!campo.required || campo.disabled) return;
 
    if (campo.checkValidity()) {
        campo.classList.remove('is-invalid');
    }
}
 
 
/* Abre automaticamente a aba onde existe o primeiro campo inválido */
// Abre automaticamente a aba que contem o primeiro campo invalido.
function abrirAbaComCampoInvalido(formulario) {
    const campoInvalido = formulario.querySelector(':invalid');
 
    if (!campoInvalido) return;
 
    const aba = campoInvalido.closest('.tab-pane');
 
    if (!aba) {
        focarCampo(campoInvalido);
        return;
    }
 
    const botaoAba = document.querySelector(`[data-bs-target="#${aba.id}"]`);
 
    if (botaoAba && typeof bootstrap !== 'undefined') {
        const tab = new bootstrap.Tab(botaoAba);
        tab.show();
 
        setTimeout(function () {
            focarCampo(campoInvalido);
        }, 250);
 
        return;
    }
 
    focarCampo(campoInvalido);
}
 
 
/* Coloca o foco no campo inválido e aproxima-o no ecrã */
// Coloca o cursor (foco) num campo especifico.
function focarCampo(campo) {
    campo.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
 
    campo.focus({ preventScroll: true });
}
 
 
/* Controla as abas do formulário de equipamento: Anterior, Seguinte e Guardar */
// Controla a navegacao Anterior/Seguinte entre as abas do formulario de equipamento.
function inicializarNavegacaoAbasEquipamento() {
    const abas = Array.from(document.querySelectorAll('#abasEquipamento button[data-bs-toggle="tab"]'));
    const botaoAnterior = document.getElementById('btnAbaAnteriorEquipamento');
    const botaoSeguinte = document.getElementById('btnAbaSeguinteEquipamento');
    const botaoGuardar = document.getElementById('btnGuardarEquipamento');
 
    if (!abas.length || !botaoAnterior || !botaoSeguinte || !botaoGuardar || typeof bootstrap === 'undefined') {
        return;
    }
 
    // Devolve o indice da aba atualmente ativa.
    function obterIndiceAtual() {
        return abas.findIndex(function (aba) {
            return aba.classList.contains('active');
        });
    }
 
    // Mostra a aba correspondente ao indice indicado.
    function mostrarAba(indice) {
        if (indice < 0 || indice >= abas.length) return;
 
        const aba = new bootstrap.Tab(abas[indice]);
        aba.show();
    }
 
    // Mostra/esconde os botoes Anterior, Seguinte e Guardar conforme a aba atual.
    function atualizarBotoes() {
        const indice = obterIndiceAtual();
        const primeiraAba = indice <= 0;
        const ultimaAba = indice === abas.length - 1;
 
        botaoAnterior.classList.toggle('d-none', primeiraAba);
        botaoSeguinte.classList.toggle('d-none', ultimaAba);
        botaoGuardar.classList.toggle('d-none', !ultimaAba);
    }
 
    botaoAnterior.addEventListener('click', function () {
        mostrarAba(obterIndiceAtual() - 1);
    });
 
    botaoSeguinte.addEventListener('click', function () {
        mostrarAba(obterIndiceAtual() + 1);
    });
 
    abas.forEach(function (aba) {
        aba.addEventListener('shown.bs.tab', atualizarBotoes);
    });
 
    atualizarBotoes();
}
 
 
/* Inicializa blocos dinâmicos de documentos e contratos opcionais do equipamento */
// Configura os blocos que se podem adicionar/remover (documentos e contratos extra).
function inicializarBlocosDinamicosEquipamento() {
    inicializarBlocoDinamico({
        botao: 'btnAdicionarOutroDocumento',
        container: 'containerOutrosDocumentos',
        template: 'templateOutroDocumento',
        remover: '.btn-remover-outro-documento',
        bloco: '.bloco-outro-documento'
    });
 
    inicializarBlocoDinamico({
        botao: 'btnAdicionarOutroContrato',
        container: 'containerOutrosContratos',
        template: 'templateOutroContrato',
        remover: '.btn-remover-outro-contrato',
        bloco: '.bloco-outro-contrato'
    });
}
 
 
/* Clona templates HTML e prepara calendários/PDFs dos blocos criados */
// Logica generica para clonar e remover um bloco dinamico a partir de um template.
function inicializarBlocoDinamico(configuracao) {
    const botaoAdicionar = document.getElementById(configuracao.botao);
    const container = document.getElementById(configuracao.container);
    const template = document.getElementById(configuracao.template);
 
    if (!botaoAdicionar || !container || !template) return;
 
    let indice = parseInt(container.dataset.proximoIndice || '0', 10);
 
    if (Number.isNaN(indice)) {
        indice = 0;
    }
 
    botaoAdicionar.addEventListener('click', function () {
        const html = template.innerHTML.replace(/__IDX__/g, indice);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
 
        const bloco = wrapper.firstElementChild;
        if (!bloco) return;
 
        container.appendChild(bloco);
 
        if (typeof flatpickr !== 'undefined') {
            bloco.querySelectorAll('.flatpickr-data').forEach(function (campo) {
                flatpickr(campo, { dateFormat: 'Y-m-d', allowInput: true });
            });
        }
 
        bloco.querySelectorAll('.input-pdf-multiplo').forEach(function (input) {
            inicializarInputPDF(input);
        });
 
        indice++;
        container.dataset.proximoIndice = String(indice);
    });
 
    container.addEventListener('click', function (evento) {
        const botaoRemover = evento.target.closest(configuracao.remover);
 
        if (!botaoRemover) return;
 
        const bloco = botaoRemover.closest(configuracao.bloco);
 
        if (bloco) {
            bloco.remove();
        }
    });
}
 
 
/* Preenche campos do editar equipamento quando a página disponibiliza dados em window.dadosFormularioEquipamentoEditar */
// No editar, preenche os campos do formulario com os dados ja existentes do equipamento.
function preencherFormularioEquipamentoEditar() {
    const formulario = document.getElementById('formEquipamento');
    const dados = window.dadosFormularioEquipamentoEditar || {};
 
    if (!formulario || !Object.keys(dados).length) return;
 
    formulario.querySelectorAll('input[name], select[name], textarea[name]').forEach(function (campo) {
        if (campo.type === 'file') return;
 
        const valor = obterValorPorNomeFormulario(dados, campo.name);
 
        if (valor === undefined || valor === null) return;
 
        if (campo.type === 'checkbox') {
            campo.checked = Array.isArray(valor)
                ? valor.map(String).includes(String(campo.value))
                : String(valor) === String(campo.value);
            return;
        }
 
        if (campo.type === 'radio') {
            campo.checked = String(valor) === String(campo.value);
            return;
        }
 
        if (Array.isArray(valor)) return;
 
        campo.value = valor;
    });
}
 
 
/* Permite ler nomes como documentosMinimos[ManualUtilizador][responsavel] dentro de um objeto JS */
// Le um valor de um objeto a partir do nome composto do campo (ex: garantia[codigo]).
function obterValorPorNomeFormulario(objeto, nomeCampo) {
    const partes = [];
 
    nomeCampo.replace(/([^\[\]]+)|\[([^\]]*)\]/g, function (_, simples, composto) {
        partes.push(simples !== undefined ? simples : composto);
    });
 
    let atual = objeto;
 
    for (let i = 0; i < partes.length; i++) {
        const parte = partes[i];
 
        if (parte === '') return atual;
 
        if (atual === undefined || atual === null || typeof atual !== 'object' || !(parte in atual)) {
            return undefined;
        }
 
        atual = atual[parte];
    }
 
    return atual;
}
 
 
/* Inicializa campos condicionais dos formulários de equipamentos */
// Ativa os campos que so aparecem mediante certas escolhas (ex: consumiveis, equipamento pai).
function inicializarCamposCondicionaisEquipamento() {
    configurarCampoCondicional({
        idControlo: 'componenteEquipamento',
        valorAtivo: 'Sim',
        idGrupo: 'grupoEquipamentoPai',
        idsCampos: ['equipamentoPaiEquipamento']
    });
 
    configurarCampoCondicional({
        idControlo: 'temConsumiveisEquipamento',
        valorAtivo: 'Sim',
        idGrupo: 'grupoConsumiveisEquipamento',
        idsCampos: ['consumiveisEquipamento']
    });
}
 
 
/* Mostra ou esconde um grupo de campos, ativando o required apenas quando necessário */
// Configura um campo condicional especifico (mostra/esconde conforme o valor).
function configurarCampoCondicional(configuracao) {
    const controlo = document.getElementById(configuracao.idControlo);
    const grupo = document.getElementById(configuracao.idGrupo);
 
    if (!controlo || !grupo) return;
 
    const campos = configuracao.idsCampos
        .map(function (idCampo) {
            return document.getElementById(idCampo);
        })
        .filter(function (campo) {
            return campo !== null;
        });
 
    // Mostra ou esconde o grupo de campos consoante a opcao selecionada.
    function atualizarGrupoCondicional() {
        const ativo = controlo.value === configuracao.valorAtivo;
 
        grupo.classList.toggle('d-none', !ativo);
 
        campos.forEach(function (campo) {
            campo.required = ativo;
            campo.disabled = !ativo;
 
            if (!ativo) {
                if (campo.type === 'checkbox' || campo.type === 'radio') {
                    campo.checked = false;
                } else {
                    campo.value = '';
                }
                campo.classList.remove('is-invalid');
            }
        });
    }
 
    controlo.addEventListener('change', atualizarGrupoCondicional);
    atualizarGrupoCondicional();
}
 
 
 
/* Mostra os PDFs escolhidos nos formulários */
// Prepara todos os campos de upload de PDF da pagina.
function inicializarInputsPDFs() {
    const inputsPDF = document.querySelectorAll('.input-pdf-multiplo');
 
    inputsPDF.forEach(function (input) {
        inicializarInputPDF(input);
    });
}
 
 
/* Inicializa um input PDF individual, incluindo os que são criados dinamicamente */
// Liga um campo de upload de PDF para mostrar a lista de ficheiros escolhidos.
function inicializarInputPDF(input) {
    if (!input || input.dataset.pdfInicializado === '1') return;
 
    input.dataset.pdfInicializado = '1';
    atualizarListaPDFs(input);
 
    input.addEventListener('change', function () {
        atualizarListaPDFs(input);
        atualizarEstadoCampo(input);
    });
}
 
 
/* Inicializa botões para remover PDFs já existentes nas páginas de edição */
// Ativa os botoes que removem ficheiros PDF ja selecionados.
function inicializarBotoesRemoverPDFs() {
    const botoes = document.querySelectorAll('.btn-remover-pdf-existente');
 
    botoes.forEach(function (botao) {
        botao.addEventListener('click', function () {
            const item = botao.closest('.pdf-item');
 
            if (item) {
                item.remove();
            }
        });
    });
}
 
 
/* Atualiza a lista visual de PDFs selecionados */
// Atualiza a lista visivel de ficheiros PDF associados a um campo.
function atualizarListaPDFs(input) {
    const lista = document.getElementById(input.dataset.lista);
 
    if (!lista) return;
 
    lista.innerHTML = '';
 
    if (!input.files || input.files.length === 0) {
        lista.innerHTML = '<p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>';
        return;
    }
 
    const permitirRemover = input.dataset.removivel === 'true';
 
    Array.from(input.files).forEach(function (ficheiro, indice) {
        lista.appendChild(criarItemPDF(ficheiro, input, indice, permitirRemover));
    });
}
 
 
/* Cria um item visual para um ficheiro PDF */
// Cria o elemento visual de um ficheiro PDF na lista (nome, tamanho, remover).
function criarItemPDF(ficheiro, input, indice, permitirRemover) {
    const item = document.createElement('div');
    item.className = 'pdf-item';
 
    const blocoNome = document.createElement('div');
    blocoNome.className = 'd-flex align-items-center gap-2';
 
    const icone = document.createElement('i');
    icone.className = 'fa-solid fa-file-pdf';
 
    const nome = document.createElement('span');
    nome.textContent = ficheiro.name;
 
    blocoNome.appendChild(icone);
    blocoNome.appendChild(nome);
 
    const blocoAcoes = document.createElement('div');
    blocoAcoes.className = 'd-flex align-items-center gap-2';
 
    const tamanho = document.createElement('span');
    tamanho.className = 'text-muted small';
    tamanho.textContent = formatarTamanhoFicheiro(ficheiro.size);
 
    blocoAcoes.appendChild(tamanho);
 
    if (permitirRemover) {
        const botaoRemover = document.createElement('button');
        botaoRemover.type = 'button';
        botaoRemover.className = 'btn btn-sm btn-outline-danger';
        botaoRemover.innerHTML = '<i class="fa-solid fa-trash me-1"></i> Remover';
 
        botaoRemover.addEventListener('click', function () {
            removerFicheiroSelecionado(input, indice);
        });
 
        blocoAcoes.appendChild(botaoRemover);
    }
 
    item.appendChild(blocoNome);
    item.appendChild(blocoAcoes);
 
    return item;
}
 
 
/* Remove um PDF selecionado sem repetir lógica por cada input */
// Remove um ficheiro especifico da selecao de um campo de upload.
function removerFicheiroSelecionado(input, indiceRemover) {
    if (!input.files || input.files.length === 0) return;
 
    const transferencia = new DataTransfer();
 
    Array.from(input.files).forEach(function (ficheiro, indice) {
        if (indice !== indiceRemover) {
            transferencia.items.add(ficheiro);
        }
    });
 
    input.files = transferencia.files;
    atualizarListaPDFs(input);
    atualizarEstadoCampo(input);
}
 
 
/* Formata o tamanho do ficheiro */
// Converte um tamanho em bytes para um texto legivel (KB, MB...).
function formatarTamanhoFicheiro(bytes) {
    if (bytes < 1024) return `${bytes} B`;
 
    const kb = bytes / 1024;
 
    if (kb < 1024) return `${kb.toFixed(1)} KB`;
 
    const mb = kb / 1024;
 
    return `${mb.toFixed(1)} MB`;
}
 
 
/* Normaliza texto para pesquisas simples, ignorando maiúsculas e acentos */
// Normaliza texto (minusculas, sem acentos) para comparacoes de pesquisa.
function normalizarTextoPesquisa(texto) {
    return (texto || '')
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}
 
 
/* Pesquisa reutilizável nos fornecedores associados por checkbox */
// Liga a caixa de pesquisa que filtra a lista de fornecedores associados.
function inicializarPesquisaFornecedoresAssociados() {
    const pesquisas = document.querySelectorAll('.pesquisa-fornecedores-associados');
 
    pesquisas.forEach(function (pesquisa) {
        const idContainer = pesquisa.dataset.fornecedoresContainer;
        const container = document.getElementById(idContainer);
 
        if (!container) return;
 
        pesquisa.addEventListener('input', function () {
            const termo = normalizarTextoPesquisa(pesquisa.value.trim());
            const itens = container.querySelectorAll('.fornecedor-associado-item, [data-fornecedor-item]');
 
            itens.forEach(function (item) {
                const textoItem = normalizarTextoPesquisa(
                    (item.dataset.fornecedorItem || '') + ' ' + item.textContent
                );
 
                item.classList.toggle('d-none', termo !== '' && !textoItem.includes(termo));
            });
        });
    });
}
 
 
/* Inicializa os gráficos estatísticos da dashboard */
// Cria todos os graficos da dashboard.
function inicializarGraficosDashboard() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js não foi carregado. Confirma se assets/js/chart.umd.min.js existe e se o footer.php está atualizado.');
        return;
    }
 
    Chart.defaults.font.family = 'Arial, Helvetica, sans-serif';
    Chart.defaults.color = '#4f5b67';
 
    criarGraficoEstado();
    criarGraficoCategoria();
    criarGraficoLocalizacao();
}
 
 
/* Cria um gráfico Chart.js sem duplicar instâncias no mesmo canvas */
// Cria um grafico individual no canvas indicado com a configuracao dada.
function criarGraficoDashboard(canvas, configuracao) {
    if (!canvas || typeof Chart === 'undefined') return;
 
    const graficoExistente = Chart.getChart(canvas);
    if (graficoExistente) {
        graficoExistente.destroy();
    }
 
    new Chart(canvas, configuracao);
}
 
 
/* Obtém dados vindos da base de dados para os gráficos da dashboard */
// Le os dados de um grafico a partir dos atributos data-* (ou usa valores por defeito).
function obterDadosDashboard(chave, labelsPadrao, valoresPadrao) {
    const dadosDashboard = window.dashboardData || {};
    const dados = dadosDashboard[chave] || {};
 
    if (Array.isArray(dados.labels) && Array.isArray(dados.valores) && dados.labels.length > 0) {
        return {
            labels: dados.labels,
            valores: dados.valores
        };
    }
 
    return {
        labels: labelsPadrao,
        valores: valoresPadrao
    };
}
 
 
/* Gráfico circular com a distribuição dos equipamentos por estado */
// Cria o grafico de equipamentos por estado.
function criarGraficoEstado() {
    const graficoEstado = document.getElementById('graficoEstado');
 
    if (!graficoEstado) return;
 
    const dados = obterDadosDashboard('estados', ['Sem dados'], [0]);
 
    criarGraficoDashboard(graficoEstado, {
        type: 'doughnut',
        data: {
            labels: dados.labels,
            datasets: [{
                data: dados.valores,
                backgroundColor: ['#198754', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545', '#6610f2', '#fd7e14', '#1a6fa8'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.parsed} equipamentos`;
                        }
                    }
                }
            },
            cutout: '62%'
        }
    });
}
 
 
/* Gráfico de barras com os equipamentos por categoria */
// Cria o grafico de equipamentos por categoria.
function criarGraficoCategoria() {
    const graficoCategoria = document.getElementById('graficoCategoria');
 
    if (!graficoCategoria) return;
 
    const dados = obterDadosDashboard('categorias', ['Sem dados'], [0]);
 
    criarGraficoDashboard(graficoCategoria, {
        type: 'bar',
        data: {
            labels: dados.labels,
            datasets: [{
                label: 'Equipamentos',
                data: dados.valores,
                backgroundColor: '#1a6fa8',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.parsed.y} equipamentos`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
 
 
/* Gráfico horizontal com os equipamentos por localização */
// Cria o grafico de equipamentos por localizacao.
function criarGraficoLocalizacao() {
    const graficoLocalizacao = document.getElementById('graficoLocalizacao');
 
    if (!graficoLocalizacao) return;
 
    const dados = obterDadosDashboard('localizacoes', ['Sem dados'], [0]);
 
    criarGraficoDashboard(graficoLocalizacao, {
        type: 'bar',
        data: {
            labels: dados.labels,
            datasets: [{
                label: 'Equipamentos',
                data: dados.valores,
                backgroundColor: '#0a2540',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.parsed.x} equipamentos`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
 
 
/* Ativa o Flatpickr nos campos de data de forma global */
// Ativa o seletor de datas (Flatpickr) em todos os campos de data.
function inicializarFlatpickrGlobal() {
    if (typeof flatpickr === 'undefined') return;
 
    document.querySelectorAll('.flatpickr-data').forEach(function (campo) {
        if (!campo.getAttribute('placeholder')) {
            campo.setAttribute('placeholder', 'AAAA-MM-DD');
        }
    });
 
    flatpickr('.flatpickr-data', {
        dateFormat: 'Y-m-d',
        allowInput: true
    });
}
 
 
/* Inicializa as DataTables das listagens principais e das tabelas da dashboard */
// Inicializa todas as tabelas DataTables da area reservada.
function inicializarDataTablesAreaReservada() {
    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) return;
 
    inicializarTabelaEquipamentos();
    inicializarTabelaFornecedores();
    inicializarTabelaLocalizacoes();
    inicializarTabelaDocumentacao();
    inicializarTabelaContratosGarantias();
    inicializarTabelaMensagensSite();
    inicializarDataTablesDashboard();
}
 
 
/* Opções comuns para tabelas DataTables */
// Monta as opcoes de configuracao (idioma, ordenacao) de uma DataTable.
function criarOpcoesDataTable(mensagens, ordemInicial, colunasSemOrdenacao) {
    const colunasBloqueadas = Array.isArray(colunasSemOrdenacao) ? colunasSemOrdenacao : [-1];
 
    const opcoes = {
        pageLength: 5,
        lengthChange: false,
        pagingType: 'simple_numbers',
        ordering: true,
        autoWidth: false,
        order: ordemInicial || [[0, 'asc']],
        dom: 't' + '<"datatable-footer d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3"ip>',
        language: {
            decimal: '',
            emptyTable: mensagens.emptyTable,
            info: mensagens.info,
            infoEmpty: mensagens.infoEmpty,
            infoFiltered: mensagens.infoFiltered,
            loadingRecords: 'A carregar...',
            processing: 'A processar...',
            zeroRecords: mensagens.zeroRecords,
            paginate: {
                next: 'Seguinte',
                previous: 'Anterior'
            },
            aria: {
                sortAscending: ': ordenar de forma crescente',
                sortDescending: ': ordenar de forma decrescente'
            }
        }
    };
 
    if (colunasBloqueadas.length > 0) {
        opcoes.columnDefs = [{
            orderable: false,
            targets: colunasBloqueadas
        }];
    }
 
    return opcoes;
}
 
/* Liga um input/select a um filtro de coluna de uma DataTable */
// Liga uma caixa de pesquisa externa a uma coluna de uma DataTable.
function ligarFiltroDataTable(tabela, seletor, coluna) {
    const $ = window.jQuery;
    const elemento = $(seletor);
 
    if (!elemento.length) return;
 
    elemento.on('input change', function () {
        tabela.column(coluna).search(this.value).draw();
    });
}
 
 
/* Cria um único botão "Guardar dados" para exportar a tabela filtrada em JSON, CSV/Excel ou PDF */
// Adiciona o botao para exportar/guardar os dados de uma tabela.
function adicionarBotaoGuardarDadosDataTable(tabela, dataTable, titulo, colunasIgnoradas) {
    const $ = window.jQuery;
    const tabelaJQ = $(tabela);
 
    if (!tabelaJQ.length || !dataTable) return;
 
    const tabelaHtml = tabelaJQ[0];
    const idTabela = tabelaHtml.id || titulo.replace(/\s+/g, '-').toLowerCase();
    const card = tabelaJQ.closest('.card');
 
    if (!card.length) return;
 
    let grupo = card.find(`[data-exportacao-tabela="${idTabela}"]`).first();
 
    if (!grupo.length) {
        grupo = $(criarHtmlBotaoGuardarDados(idTabela));
 
        const responsivo = card.children('.table-responsive').first();
 
        if (responsivo.length) {
            responsivo.before(grupo);
        } else {
            card.prepend(grupo);
        }
    }
 
    grupo.find('[data-exportacao]')
        .off('click.exportacaoTabela')
        .on('click.exportacaoTabela', function () {
            exportarDataTable(dataTable, tabelaHtml, titulo, this.dataset.exportacao, colunasIgnoradas);
        });
}
 
// Gera o HTML do botao de guardar dados de uma tabela.
function criarHtmlBotaoGuardarDados(idTabela) {
    return `
        <div class="d-flex justify-content-end mb-3 exportacoes-tabela" data-exportacao-tabela="${idTabela}">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-download me-1"></i> Guardar dados
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" data-exportacao="json">
                            <i class="fa-solid fa-file-code me-2"></i> Guardar em JSON
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" type="button" data-exportacao="csv">
                            <i class="fa-solid fa-file-csv me-2"></i> Guardar em CSV/Excel
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" type="button" data-exportacao="pdf">
                            <i class="fa-solid fa-file-pdf me-2"></i> Guardar em PDF
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    `;
}
 
/* Exporta apenas as linhas visíveis depois da pesquisa/filtros aplicados pelo DataTables */
// Exporta os dados de uma tabela no formato escolhido (CSV/PDF).
function exportarDataTable(dataTable, tabelaHtml, titulo, formato, colunasIgnoradas) {
    const dados = obterDadosDataTableParaExportar(dataTable, tabelaHtml, colunasIgnoradas);
    const nomeFicheiro = gerarNomeExportacao(titulo, formato);
 
    if (formato === 'json') {
        descarregarFicheiro(JSON.stringify(dados.linhas, null, 2), nomeFicheiro, 'application/json;charset=utf-8');
        return;
    }
 
    if (formato === 'csv') {
        descarregarFicheiro('\ufeff' + criarCsvExportacao(dados.cabecalhos, dados.linhas), nomeFicheiro, 'text/csv;charset=utf-8');
        return;
    }
 
    if (formato === 'pdf') {
        abrirTabelaParaGuardarPDF(titulo, dados.cabecalhos, dados.linhas);
    }
}
 
// Recolhe cabecalhos e linhas de uma tabela para exportacao.
function obterDadosDataTableParaExportar(dataTable, tabelaHtml, colunasIgnoradas) {
    const cabecalhosOriginais = Array.from(tabelaHtml.querySelectorAll('thead th')).map(function (th) {
        return limparTextoExportacao(th.textContent);
    });
 
    const ignoradas = normalizarColunasIgnoradas(colunasIgnoradas, cabecalhosOriginais.length);
    const indicesUsados = cabecalhosOriginais
        .map(function (_, indice) { return indice; })
        .filter(function (indice) { return !ignoradas.includes(indice); });
 
    const cabecalhos = indicesUsados.map(function (indice) {
        return cabecalhosOriginais[indice] || `Coluna ${indice + 1}`;
    });
 
    const linhas = dataTable.rows({ search: 'applied', order: 'applied' }).nodes().toArray().map(function (linha) {
        const celulas = Array.from(linha.children);
        const registo = {};
 
        indicesUsados.forEach(function (indice, posicao) {
            registo[cabecalhos[posicao]] = limparTextoExportacao(celulas[indice] ? celulas[indice].innerText : '');
        });
 
        return registo;
    });
 
    return { cabecalhos, linhas };
}
 
// Calcula que colunas devem ser ignoradas na exportacao.
function normalizarColunasIgnoradas(colunasIgnoradas, totalColunas) {
    const lista = Array.isArray(colunasIgnoradas) ? colunasIgnoradas : [-1];
 
    return lista.map(function (indice) {
        return indice < 0 ? totalColunas + indice : indice;
    });
}
 
// Limpa texto para exportacao (remove espacos e quebras a mais).
function limparTextoExportacao(texto) {
    return String(texto || '').replace(/\s+/g, ' ').trim();
}
 
// Constroi o conteudo de um ficheiro CSV a partir de cabecalhos e linhas.
function criarCsvExportacao(cabecalhos, linhas) {
    const separador = ';';
    const linhasCsv = [cabecalhos.map(escaparCampoCsv).join(separador)];
 
    linhas.forEach(function (linha) {
        linhasCsv.push(cabecalhos.map(function (cabecalho) {
            return escaparCampoCsv(linha[cabecalho]);
        }).join(separador));
    });
 
    return linhasCsv.join('\n');
}
 
// Escapa um valor para ser seguro dentro de um CSV.
function escaparCampoCsv(valor) {
    const texto = String(valor ?? '');
 
    if (/[";\n\r]/.test(texto)) {
        return '"' + texto.replace(/"/g, '""') + '"';
    }
 
    return texto;
}
 
// Forca o download de um ficheiro gerado no browser.
function descarregarFicheiro(conteudo, nomeFicheiro, tipo) {
    const blob = new Blob([conteudo], { type: tipo });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
 
    link.href = url;
    link.download = nomeFicheiro;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
}
 
// Abre uma janela formatada com a tabela para o utilizador guardar como PDF.
function abrirTabelaParaGuardarPDF(titulo, cabecalhos, linhas) {
    const janela = window.open('', '_blank');
 
    if (!janela) {
        alert('Não foi possível abrir a janela de impressão. Permita pop-ups para guardar em PDF.');
        return;
    }
 
    const linhasHtml = linhas.map(function (linha) {
        const celulas = cabecalhos.map(function (cabecalho) {
            return `<td>${escaparHtmlExportacao(linha[cabecalho])}</td>`;
        }).join('');
 
        return `<tr>${celulas}</tr>`;
    }).join('');
 
    const cabecalhosHtml = cabecalhos.map(function (cabecalho) {
        return `<th>${escaparHtmlExportacao(cabecalho)}</th>`;
    }).join('');
 
    janela.document.write(`
        <!doctype html>
        <html lang="pt">
        <head>
            <meta charset="utf-8">
            <title>${escaparHtmlExportacao(titulo)}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 24px; color: #222; }
                h1 { font-size: 20px; margin-bottom: 4px; }
                p { margin-top: 0; color: #555; }
                table { width: 100%; border-collapse: collapse; font-size: 11px; }
                th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
                th { background: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1>${escaparHtmlExportacao(titulo)}</h1>
            <p>Exportação gerada em ${new Date().toLocaleString('pt-PT')}.</p>
            <table>
                <thead><tr>${cabecalhosHtml}</tr></thead>
                <tbody>${linhasHtml || `<tr><td colspan="${cabecalhos.length}">Sem registos para exportar.</td></tr>`}</tbody>
            </table>
            <script>
                window.onload = function () { window.print(); };
            <\/script>
        </body>
        </html>
    `);
 
    janela.document.close();
}
 
// Escapa texto para ser inserido com seguranca em HTML.
function escaparHtmlExportacao(valor) {
    return String(valor ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
 
// Gera o nome do ficheiro de exportacao a partir do titulo e formato.
function gerarNomeExportacao(titulo, formato) {
    const base = String(titulo || 'exportacao')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '') || 'exportacao';
 
    const data = new Date().toISOString().slice(0, 10);
    return `${base}_${data}.${formato}`;
}
 
 
/* Tabelas da dashboard com DataTables e pesquisas externas */
// Inicializa as tabelas especificas da dashboard.
function inicializarDataTablesDashboard() {
    const tabelasDashboard = [
        {
            tabela: '#tabelaServicosDashboard',
            pesquisa: '#pesquisaServicoDashboard',
            ordem: [[1, 'desc']],
            colunasSemOrdenacao: [],
            mensagens: {
                emptyTable: 'Sem dados de serviço.',
                info: 'A mostrar _START_ a _END_ de _TOTAL_ serviços',
                infoEmpty: 'Sem serviços para mostrar',
                infoFiltered: '(filtrado de _MAX_ serviços)',
                zeroRecords: 'Nenhum serviço encontrado.'
            }
        },
        {
            tabela: '#tabelaSuporteVidaDashboard',
            pesquisa: '#pesquisaSuporteVidaDashboard',
            ordem: [[1, 'desc']],
            colunasSemOrdenacao: [],
            mensagens: {
                emptyTable: 'Sem equipamentos de suporte de vida.',
                info: 'A mostrar _START_ a _END_ de _TOTAL_ serviços',
                infoEmpty: 'Sem serviços para mostrar',
                infoFiltered: '(filtrado de _MAX_ serviços)',
                zeroRecords: 'Nenhum serviço encontrado.'
            }
        },
        {
            tabela: '#tabelaGarantiasConteudo',
            pesquisa: '#pesquisaGarantiasDashboard',
            ordem: [[3, 'asc']],
            colunasSemOrdenacao: [-1],
            mensagens: {
                emptyTable: 'Sem garantias expiradas ou próximas do fim.',
                info: 'A mostrar _START_ a _END_ de _TOTAL_ garantias',
                infoEmpty: 'Sem garantias para mostrar',
                infoFiltered: '(filtrado de _MAX_ garantias)',
                zeroRecords: 'Nenhuma garantia encontrada.'
            }
        },
        {
            tabela: '#tabelaManutencoes',
            pesquisa: '#pesquisaManutencoesDashboard',
            ordem: [[3, 'asc']],
            colunasSemOrdenacao: [-1],
            mensagens: {
                emptyTable: 'Sem manutenções preventivas agendadas.',
                info: 'A mostrar _START_ a _END_ de _TOTAL_ manutenções',
                infoEmpty: 'Sem manutenções para mostrar',
                infoFiltered: '(filtrado de _MAX_ manutenções)',
                zeroRecords: 'Nenhuma manutenção encontrada.'
            }
        }
    ];
 
    tabelasDashboard.forEach(inicializarDataTableDashboard);
}
 
 
/* Inicializa uma tabela da dashboard sem repetir a lógica de pesquisa */
// Configura uma tabela individual da dashboard.
function inicializarDataTableDashboard(configuracao) {
    const $ = window.jQuery;
    const tabela = $(configuracao.tabela);
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    const opcoes = criarOpcoesDataTable(
        configuracao.mensagens,
        configuracao.ordem,
        configuracao.colunasSemOrdenacao
    );
 
    const dataTable = tabela.DataTable(opcoes);
    const pesquisa = $(configuracao.pesquisa);
 
    if (pesquisa.length) {
        pesquisa.on('input', function () {
            dataTable.search(this.value).draw();
        });
    }
}
 
 
/* Tabela das mensagens recebidas no formulário público */
// Inicializa a tabela de mensagens recebidas do site.
function inicializarTabelaMensagensSite() {
    const $ = window.jQuery;
    const tabela = $('#tabelaMensagensSite');
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    tabela.DataTable(criarOpcoesDataTable({
        emptyTable: 'Ainda não existem mensagens recebidas.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ mensagens',
        infoEmpty: 'Sem mensagens para mostrar',
        infoFiltered: '(filtrado de _MAX_ mensagens)',
        zeroRecords: 'Nenhuma mensagem encontrada.'
    }, [[0, 'desc']], []));
}
 
 
/* Listagem de equipamentos */
// Inicializa a tabela de listagem de equipamentos.
function inicializarTabelaEquipamentos() {
    const $ = window.jQuery;
    const tabela = $('#tabelaEquipamentos');
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    const tabelaEquipamentos = tabela.DataTable(criarOpcoesDataTable({
        emptyTable: 'Sem equipamentos registados.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ equipamentos',
        infoEmpty: 'Sem equipamentos para mostrar',
        infoFiltered: '(filtrado de _MAX_ equipamentos)',
        zeroRecords: 'Nenhum equipamento encontrado.'
    }, []));
 
    $('#pesquisaEquipamentosDT').on('input', function () {
        tabelaEquipamentos.search(this.value).draw();
    });
 
    ligarFiltroDataTable(tabelaEquipamentos, '#filtroCategoriaEquipamentosDT', 2);
    ligarFiltroDataTable(tabelaEquipamentos, '#filtroLocalizacaoEquipamentosDT', 6);
    ligarFiltroDataTable(tabelaEquipamentos, '#filtroEstadoEquipamentosDT', 7);
    adicionarBotaoGuardarDadosDataTable(tabela, tabelaEquipamentos, 'Equipamentos', [-1]);
}
 
 
/* Listagem de fornecedores */
// Inicializa a tabela de listagem de fornecedores.
function inicializarTabelaFornecedores() {
    const $ = window.jQuery;
    const tabela = $('#tabelaFornecedores');
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    const tabelaFornecedores = tabela.DataTable(criarOpcoesDataTable({
        emptyTable: 'Sem fornecedores registados.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ fornecedores',
        infoEmpty: 'Sem fornecedores para mostrar',
        infoFiltered: '(filtrado de _MAX_ fornecedores)',
        zeroRecords: 'Nenhum fornecedor encontrado.'
    }));
 
    $('#pesquisaFornecedoresDT').on('input', function () {
        tabelaFornecedores.search(this.value).draw();
    });
 
    ligarFiltroDataTable(tabelaFornecedores, '#filtroTipoFornecedorDT', 2);
    ligarFiltroDataTable(tabelaFornecedores, '#filtroContratoFornecedorDT', 5);
    ligarFiltroDataTable(tabelaFornecedores, '#filtroEstadoFornecedorDT', 6);
    adicionarBotaoGuardarDadosDataTable(tabela, tabelaFornecedores, 'Fornecedores', [-1]);
}
 
 
/* Listagem de localizações */
// Inicializa a tabela de listagem de localizacoes.
function inicializarTabelaLocalizacoes() {
    const $ = window.jQuery;
    const tabela = $('#tabelaLocalizacoes');
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    const tabelaLocalizacoes = tabela.DataTable(criarOpcoesDataTable({
        emptyTable: 'Sem localizações registadas.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ localizações',
        infoEmpty: 'Sem localizações para mostrar',
        infoFiltered: '(filtrado de _MAX_ localizações)',
        zeroRecords: 'Nenhuma localização encontrada.'
    }));
 
    $('#pesquisaLocalizacoesDT').on('input', function () {
        tabelaLocalizacoes.search(this.value).draw();
    });
 
    ligarFiltroDataTable(tabelaLocalizacoes, '#filtroTipoLocalizacaoDT', 2);
    ligarFiltroDataTable(tabelaLocalizacoes, '#filtroEstadoLocalizacaoDT', 6);
    adicionarBotaoGuardarDadosDataTable(tabela, tabelaLocalizacoes, 'Localizações', [-1]);
}
 
 
/* Listagem de documentação */
// Inicializa a tabela de listagem de documentacao.
function inicializarTabelaDocumentacao() {
    const $ = window.jQuery;
    const tabela = $('#tabelaDocumentacao');
 
    if (!tabela.length || $.fn.dataTable.isDataTable(tabela[0])) return;
 
    const tabelaDocumentacao = tabela.DataTable(criarOpcoesDataTable({
        emptyTable: 'Sem documentos registados.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ documentos',
        infoEmpty: 'Sem documentos para mostrar',
        infoFiltered: '(filtrado de _MAX_ documentos)',
        zeroRecords: 'Nenhum documento encontrado.'
    }));
 
    $('#pesquisaDocumentacaoDT').on('input', function () {
        tabelaDocumentacao.search(this.value).draw();
    });
 
    ligarFiltroDataTable(tabelaDocumentacao, '#filtroTipoDocumentoDT', 2);
    ligarFiltroDataTable(tabelaDocumentacao, '#filtroAreaDocumentoDT', 3);
    ligarFiltroDataTable(tabelaDocumentacao, '#filtroEstadoDocumentoDT', 7);
    adicionarBotaoGuardarDadosDataTable(tabela, tabelaDocumentacao, 'Documentação', [-1]);
}
 
 
/* Listagem de contratos e garantias */
// Inicializa a tabela de contratos e garantias com os seus filtros.
function inicializarTabelaContratosGarantias() {
    const $ = window.jQuery;
    const tabelaGarantiasEl = $('#tabelaGarantias');
    const tabelaContratosEl = $('#tabelaContratos');
 
    if (!tabelaGarantiasEl.length || !tabelaContratosEl.length) return;
    if ($.fn.dataTable.isDataTable(tabelaGarantiasEl[0]) || $.fn.dataTable.isDataTable(tabelaContratosEl[0])) return;
 
    const opcoesDataTable = criarOpcoesDataTable({
        emptyTable: 'Sem registos.',
        info: 'A mostrar _START_ a _END_ de _TOTAL_ registos',
        infoEmpty: 'Sem registos para mostrar',
        infoFiltered: '(filtrado de _MAX_ registos)',
        zeroRecords: 'Nenhum registo encontrado.'
    });
 
    const tabelaGarantias = tabelaGarantiasEl.DataTable(opcoesDataTable);
    const tabelaContratos = tabelaContratosEl.DataTable(opcoesDataTable);
 
    // Aplica os filtros selecionados a tabela de contratos e garantias.
    function aplicarFiltrosContratosGarantias() {
        const pesquisa = $('#pesquisaContratosDT').val();
        const tipo = $('#filtroTipoContratoDT').val();
        const fornecedor = $('#filtroFornecedorContratoDT').val();
        const estado = $('#filtroEstadoContratoDT').val();
 
        tabelaGarantias.search(pesquisa).column(2).search(tipo).column(4).search(fornecedor).column(7).search(estado).draw();
        tabelaContratos.search(pesquisa).column(2).search(tipo).column(4).search(fornecedor).column(7).search(estado).draw();
    }
 
    $('#pesquisaContratosDT').on('input', aplicarFiltrosContratosGarantias);
    $('#filtroTipoContratoDT').on('change', aplicarFiltrosContratosGarantias);
    $('#filtroFornecedorContratoDT').on('change', aplicarFiltrosContratosGarantias);
    $('#filtroEstadoContratoDT').on('change', aplicarFiltrosContratosGarantias);
 
    adicionarBotaoGuardarDadosDataTable(tabelaGarantiasEl, tabelaGarantias, 'Garantias', [-1]);
    adicionarBotaoGuardarDadosDataTable(tabelaContratosEl, tabelaContratos, 'Contratos', [-1]);
}