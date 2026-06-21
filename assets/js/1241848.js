document.addEventListener('DOMContentLoaded', function () {
    aplicarConteudosPublicos();

    inicializarFormularioContacto();

    inicializarTooltips();
    inicializarToastPublic();

    inicializarFormulariosSimulados();
    inicializarGestaoConteudosPublicos();
    inicializarInputsPDFs();
    inicializarBotoesRemoverPDFs();
    inicializarCamposCondicionaisEquipamento();
    inicializarPesquisaFornecedoresAssociados();

    inicializarGraficosDashboard();
});


/* Configuração reutilizável dos formulários */
function inicializarFormulariosSimulados() {
    const formularios = [
        {
            idFormulario: 'formEliminarEquipamento',
            idMensagem: 'mensagemEliminarEquipamento',
            paginaDestino: 'equipamentos.php',
            validar: false
        },
        {
            idFormulario: 'formEliminarFornecedor',
            idMensagem: 'mensagemEliminarFornecedor',
            paginaDestino: 'fornecedores.php',
            validar: false
        },
        {
            idFormulario: 'formEliminarLocalizacao',
            idMensagem: 'mensagemEliminarLocalizacao',
            paginaDestino: 'localizacoes.php',
            validar: false
        },
        {
            idFormulario: 'formEliminarDocumento',
            idMensagem: 'mensagemEliminarDocumento',
            paginaDestino: 'documentacao.php',
            validar: false
        },
        {
            idFormulario: 'formEliminarContrato',
            idMensagem: 'mensagemEliminarContrato',
            paginaDestino: 'contratos.php',
            validar: false
        },
        {
            idFormulario: 'formEliminarGarantia',
            idMensagem: 'mensagemEliminarGarantia',
            paginaDestino: 'contratos.php',
            validar: false
        }
    ];

    formularios.forEach(function (formulario) {
        inicializarFormularioSimulado(
            formulario.idFormulario,
            formulario.idMensagem,
            formulario.paginaDestino,
            formulario.validar
        );
    });
}


/* Validação do formulário de contacto da área pública */
function inicializarFormularioContacto() {
    const formContacto = document.getElementById('formContacto');

    if (!formContacto) return;

    formContacto.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validarFormulario(formContacto)) return;
        formContacto.reset();
        limparValidacaoManual(formContacto);

        const mensagemSucesso = document.getElementById('mensagemSucesso');

        if (mensagemSucesso) {
            mensagemSucesso.classList.remove('d-none');

            setTimeout(function () {
                mensagemSucesso.classList.add('d-none');
            }, 4000);
        }
    });
}


/* Ativa os tooltips do Bootstrap */
function inicializarTooltips() {
    if (typeof bootstrap === 'undefined') return;

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}


/* Mostra o toast da página pública */
function inicializarToastPublic() {
    if (typeof bootstrap === 'undefined') return;

    const toastEl = document.getElementById('toastPublic');

    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }
}


/* Validação reutilizável para formulários simulados */
function inicializarFormularioSimulado(idFormulario, idMensagem, paginaDestino, validarFormulario) {
    const formulario = document.getElementById(idFormulario);
    const mensagem = document.getElementById(idMensagem);

    if (!formulario) return;

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validarFormulario && !validarFormularioHTML(formulario)) return;

        limparValidacaoManual(formulario);

        if (mensagem) {
            mensagem.classList.remove('d-none');
        }

        setTimeout(function () {
            window.location.href = paginaDestino;
        }, 1200);
    });
}


/* Valida qualquer formulário HTML de forma reutilizável */
function validarFormulario(formulario) {
    return validarFormularioHTML(formulario);
}


/* Validação base dos formulários com required e invalid-feedback no HTML */
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
function limparValidacaoManual(formulario) {
    formulario.classList.remove('was-validated');

    formulario.querySelectorAll('.is-invalid').forEach(function (campo) {
        campo.classList.remove('is-invalid');
    });
}


/* Marca apenas campos obrigatórios inválidos */
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
function atualizarEstadoCampo(campo) {
    if (!campo.required || campo.disabled) return;

    if (campo.checkValidity()) {
        campo.classList.remove('is-invalid');
    }
}


/* Abre automaticamente a aba onde existe o primeiro campo inválido */
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
function focarCampo(campo) {
    campo.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });

    campo.focus({ preventScroll: true });
}


/* Inicializa campos condicionais dos formulários de equipamentos */
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

    function atualizarGrupoCondicional() {
        const ativo = controlo.value === configuracao.valorAtivo;

        grupo.classList.toggle('d-none', !ativo);

        campos.forEach(function (campo) {
            campo.required = ativo;
            campo.disabled = !ativo;

            if (!ativo) {
                campo.classList.remove('is-invalid');
            }
        });
    }

    controlo.addEventListener('change', atualizarGrupoCondicional);
    atualizarGrupoCondicional();
}



/* Mostra os PDFs escolhidos nos formulários */
function inicializarInputsPDFs() {
    const inputsPDF = document.querySelectorAll('.input-pdf-multiplo');

    inputsPDF.forEach(function (input) {
        atualizarListaPDFs(input);

        input.addEventListener('change', function () {
            atualizarListaPDFs(input);
            atualizarEstadoCampo(input);
        });
    });
}


/* Inicializa botões para remover PDFs já existentes nas páginas de edição */
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
function formatarTamanhoFicheiro(bytes) {
    if (bytes < 1024) return `${bytes} B`;

    const kb = bytes / 1024;

    if (kb < 1024) return `${kb.toFixed(1)} KB`;

    const mb = kb / 1024;

    return `${mb.toFixed(1)} MB`;
}


/* Normaliza texto para pesquisas simples, ignorando maiúsculas e acentos */
function normalizarTextoPesquisa(texto) {
    return (texto || '')
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}


/* Pesquisa reutilizável nos fornecedores associados por checkbox */
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


/* Conteúdos originais da página pública */
const conteudosPublicosOriginais = {
    heroTitulo: 'Soluções digitais para a gestão hospitalar',
    heroSubtitulo: 'Desenvolvemos sistemas de informação especializados para instituições de saúde, com foco na rastreabilidade, segurança e eficiência operacional.',

    sobreTitulo: 'Sobre Nós',
    sobreTexto1: 'A MedInfo Solutions é uma empresa portuguesa especializada no desenvolvimento de software para a área da saúde. Fundada em 2015 e sediada no Porto, temos como missão modernizar a gestão tecnológica das instituições de saúde nacionais.',
    sobreTexto2: 'A nossa equipa é composta por engenheiros biomédicos, engenheiros informáticos e especialistas em sistemas de informação hospitalares, o que nos permite desenvolver soluções tecnicamente sólidas e clinicamente adequadas.',
    sobreTexto3: 'Trabalhamos com hospitais públicos e privados, clínicas e centros de saúde em todo o território nacional, com especial foco em sistemas de gestão de inventário e manutenção de equipamentos médicos.',

    servicosTitulo: 'Serviços',
    servicosTexto: 'Desenvolvemos soluções adaptadas às necessidades específicas de cada instituição.',

    clientesTitulo: 'Os Nossos Clientes',
    clientesTexto: 'Trabalhamos com algumas das principais instituições de saúde nacionais.',

    contactosTitulo: 'Contactos',
    contactosTexto: 'Entre em contacto connosco para mais informações ou para agendar uma demonstração.',
    morada: 'Rua Dr. António Bernardino de Almeida, 431\n4200-072 Porto',
    telefone: '+351 222 123 456',
    email: 'geral@medinfosolutions.pt',
    website: 'www.medinfosolutions.pt',
    horario: '2ª a 6ª Feira: 9h — 18h\nSábado, Domingo e Feriados: Encerrado',

    rodapeTexto: 'MedInfo Solutions © 2025 — Todos os direitos reservados'
};


/* Obtém os conteúdos públicos guardados */
function obterConteudosPublicos() {
    const conteudosGuardados = localStorage.getItem('conteudosPublicos');

    if (!conteudosGuardados) {
        return conteudosPublicosOriginais;
    }

    try {
        return {
            ...conteudosPublicosOriginais,
            ...JSON.parse(conteudosGuardados)
        };
    } catch (erro) {
        return conteudosPublicosOriginais;
    }
}


/* Guarda os conteúdos públicos */
function guardarConteudosPublicos(conteudos) {
    localStorage.setItem('conteudosPublicos', JSON.stringify(conteudos));
}


/* Aplica os conteúdos guardados na página pública */
function aplicarConteudosPublicos() {
    const conteudos = obterConteudosPublicos();

    const heroTitulo = document.querySelector('.hero-titulo');
    const heroSubtitulo = document.querySelector('.hero-subtitulo');

    if (heroTitulo) heroTitulo.textContent = conteudos.heroTitulo;
    if (heroSubtitulo) heroSubtitulo.textContent = conteudos.heroSubtitulo;

    const sobreTitulo = document.querySelector('#sobre-nos h2');
    const sobreParagrafos = document.querySelectorAll('#sobre-nos .col-lg-6 p');

    if (sobreTitulo) sobreTitulo.textContent = conteudos.sobreTitulo;
    if (sobreParagrafos[0]) sobreParagrafos[0].textContent = conteudos.sobreTexto1;
    if (sobreParagrafos[1]) sobreParagrafos[1].textContent = conteudos.sobreTexto2;
    if (sobreParagrafos[2]) sobreParagrafos[2].textContent = conteudos.sobreTexto3;

    const servicosTitulo = document.querySelector('#servicos h2');
    const servicosTexto = document.querySelector('#servicos .row.mb-4 p');

    if (servicosTitulo) servicosTitulo.textContent = conteudos.servicosTitulo;
    if (servicosTexto) servicosTexto.textContent = conteudos.servicosTexto;

    const clientesTitulo = document.querySelector('#clientes h2');
    const clientesTexto = document.querySelector('#clientes .row.mb-4 p');

    if (clientesTitulo) clientesTitulo.textContent = conteudos.clientesTitulo;
    if (clientesTexto) clientesTexto.textContent = conteudos.clientesTexto;

    const contactosTitulo = document.querySelector('#contactos h2');
    const contactosTexto = document.querySelector('#contactos .row.mb-4 p');

    if (contactosTitulo) contactosTitulo.textContent = conteudos.contactosTitulo;
    if (contactosTexto) contactosTexto.textContent = conteudos.contactosTexto;

    aplicarDadosContacto(conteudos);

    const rodape = document.querySelector('#footer .col-md-6');

    if (rodape) {
        rodape.innerHTML = `<i class="fa-solid fa-heart-pulse me-1"></i> <strong>${conteudos.rodapeTexto}</strong>`;
    }
}


/* Aplica os dados de contacto na página pública */
function aplicarDadosContacto(conteudos) {
    const cartaoContactos = document.querySelector('#contactos .card.p-4.h-100');

    if (!cartaoContactos) return;

    const blocosContacto = cartaoContactos.querySelectorAll('.d-flex.gap-3');

    atualizarBlocoContacto(blocosContacto[0], conteudos.morada);
    atualizarBlocoContacto(blocosContacto[1], conteudos.telefone);
    atualizarBlocoContacto(blocosContacto[2], conteudos.email);
    atualizarBlocoContacto(blocosContacto[3], conteudos.website);
    atualizarBlocoContacto(blocosContacto[4], conteudos.horario);
}


/* Atualiza um bloco de contacto mantendo o título original */
function atualizarBlocoContacto(bloco, texto) {
    if (!bloco) return;

    const conteudo = bloco.querySelector('div:last-child');
    const titulo = conteudo ? conteudo.querySelector('.fw-bold') : null;

    if (!conteudo || !titulo) return;

    conteudo.innerHTML = '';
    conteudo.appendChild(titulo);
    conteudo.appendChild(document.createElement('br'));

    texto.split('\n').forEach(function (linha, indice) {
        conteudo.appendChild(document.createTextNode(linha));

        if (indice < texto.split('\n').length - 1) {
            conteudo.appendChild(document.createElement('br'));
        }
    });
}


/* Inicializa a gestão de conteúdos públicos */
function inicializarGestaoConteudosPublicos() {
    const formulario = document.getElementById('formConteudosPublicos');

    if (!formulario) return;

    preencherFormularioConteudosPublicos();

    const botaoRepor = document.getElementById('btnReporConteudosPublicos');

    if (botaoRepor) {
        botaoRepor.addEventListener('click', function () {
            localStorage.removeItem('conteudosPublicos');
            preencherFormularioConteudosPublicos();
            mostrarMensagemConteudosPublicos('Conteúdos originais repostos com sucesso.');
        });
    }

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validarFormulario(formulario)) return;

        const conteudos = {
            heroTitulo: obterValorCampo('conteudoHeroTitulo'),
            heroSubtitulo: obterValorCampo('conteudoHeroSubtitulo'),

            sobreTitulo: obterValorCampo('conteudoSobreTitulo'),
            sobreTexto1: obterValorCampo('conteudoSobreTexto1'),
            sobreTexto2: obterValorCampo('conteudoSobreTexto2'),
            sobreTexto3: obterValorCampo('conteudoSobreTexto3'),

            servicosTitulo: obterValorCampo('conteudoServicosTitulo'),
            servicosTexto: obterValorCampo('conteudoServicosTexto'),

            clientesTitulo: obterValorCampo('conteudoClientesTitulo'),
            clientesTexto: obterValorCampo('conteudoClientesTexto'),

            contactosTitulo: obterValorCampo('conteudoContactosTitulo'),
            contactosTexto: obterValorCampo('conteudoContactosTexto'),
            morada: obterValorCampo('conteudoMorada'),
            telefone: obterValorCampo('conteudoTelefone'),
            email: obterValorCampo('conteudoEmail'),
            website: obterValorCampo('conteudoWebsite'),
            horario: obterValorCampo('conteudoHorario'),

            rodapeTexto: obterValorCampo('conteudoRodapeTexto')
        };

        guardarConteudosPublicos(conteudos);
        aplicarConteudosPublicos();
        mostrarMensagemConteudosPublicos('Conteúdos atualizados com sucesso.');
    });
}


/* Preenche o formulário com os conteúdos guardados */
function preencherFormularioConteudosPublicos() {
    const conteudos = obterConteudosPublicos();

    preencherCampo('conteudoHeroTitulo', conteudos.heroTitulo);
    preencherCampo('conteudoHeroSubtitulo', conteudos.heroSubtitulo);

    preencherCampo('conteudoSobreTitulo', conteudos.sobreTitulo);
    preencherCampo('conteudoSobreTexto1', conteudos.sobreTexto1);
    preencherCampo('conteudoSobreTexto2', conteudos.sobreTexto2);
    preencherCampo('conteudoSobreTexto3', conteudos.sobreTexto3);

    preencherCampo('conteudoServicosTitulo', conteudos.servicosTitulo);
    preencherCampo('conteudoServicosTexto', conteudos.servicosTexto);

    preencherCampo('conteudoClientesTitulo', conteudos.clientesTitulo);
    preencherCampo('conteudoClientesTexto', conteudos.clientesTexto);

    preencherCampo('conteudoContactosTitulo', conteudos.contactosTitulo);
    preencherCampo('conteudoContactosTexto', conteudos.contactosTexto);
    preencherCampo('conteudoMorada', conteudos.morada);
    preencherCampo('conteudoTelefone', conteudos.telefone);
    preencherCampo('conteudoEmail', conteudos.email);
    preencherCampo('conteudoWebsite', conteudos.website);
    preencherCampo('conteudoHorario', conteudos.horario);

    preencherCampo('conteudoRodapeTexto', conteudos.rodapeTexto);
}


/* Preenche um campo se existir */
function preencherCampo(idCampo, valor) {
    const campo = document.getElementById(idCampo);

    if (campo) {
        campo.value = valor;
    }
}


/* Obtém o valor de um campo se existir */
function obterValorCampo(idCampo) {
    const campo = document.getElementById(idCampo);

    if (!campo) return '';

    return campo.value.trim();
}


/* Mostra mensagem da gestão de conteúdos */
function mostrarMensagemConteudosPublicos(texto) {
    const mensagem = document.getElementById('mensagemConteudosPublicos');

    if (!mensagem) return;

    mensagem.innerHTML = `<i class="fa-solid fa-check me-1"></i> ${texto}`;
    mensagem.classList.remove('d-none');

    setTimeout(function () {
        mensagem.classList.add('d-none');
    }, 4000);
}


/* Inicializa os gráficos estatísticos da dashboard */
function inicializarGraficosDashboard() {
    if (typeof Chart === 'undefined') return;

    Chart.defaults.font.family = 'Arial, Helvetica, sans-serif';
    Chart.defaults.color = '#4f5b67';

    criarGraficoEstado();
    criarGraficoCategoria();
    criarGraficoLocalizacao();
}


/* Gráfico circular com a distribuição dos equipamentos por estado */
function criarGraficoEstado() {
    const graficoEstado = document.getElementById('graficoEstado');

    if (!graficoEstado) return;

    new Chart(graficoEstado, {
        type: 'doughnut',
        data: {
            labels: ['Ativo', 'Em Manutenção', 'Inativo', 'Em Calibração', 'Abatido'],
            datasets: [{
                data: [118, 14, 10, 5, 3],
                backgroundColor: ['#198754', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545'],
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
function criarGraficoCategoria() {
    const graficoCategoria = document.getElementById('graficoCategoria');

    if (!graficoCategoria) return;

    new Chart(graficoCategoria, {
        type: 'bar',
        data: {
            labels: ['Monitorização', 'Suporte de Vida', 'Terapia', 'Diagnóstico', 'Laboratório'],
            datasets: [{
                label: 'Equipamentos',
                data: [42, 31, 28, 24, 17],
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
function criarGraficoLocalizacao() {
    const graficoLocalizacao = document.getElementById('graficoLocalizacao');

    if (!graficoLocalizacao) return;

    new Chart(graficoLocalizacao, {
        type: 'bar',
        data: {
            labels: ['UCI', 'Urgência', 'Bloco', 'Med. Interna', 'Consulta', 'Laboratório'],
            datasets: [{
                label: 'Equipamentos',
                data: [26, 24, 22, 20, 18, 15],
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

