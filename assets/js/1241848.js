/* Inicializa os componentes interativos existentes em cada página */
document.addEventListener('DOMContentLoaded', function () {
    aplicarConteudosPublicos();

    inicializarFormularioContacto();
    inicializarLogin();

    inicializarTooltips();
    inicializarToastPublic();

    inicializarTabelas();
    inicializarFormulariosSimulados();
    inicializarGestaoConteudosPublicos();

    inicializarGraficosDashboard();
});


/* Configuração reutilizável das tabelas */
function inicializarTabelas() {
    const tabelas = [
        {
            idTabela: 'tabelaGarantiasConteudo',
            idPesquisa: 'pesquisaTabela'
        },
        {
            idTabela: 'tabelaEquipamentos',
            idPesquisa: 'pesquisaEquipamentos',
            filtros: [
                { filtroId: 'filtroCategoriaEquipamentos', coluna: 2 },
                { filtroId: 'filtroLocalizacaoEquipamentos', coluna: 5 },
                { filtroId: 'filtroEstadoEquipamentos', coluna: 6 },
                { filtroId: 'filtroManutencaoEquipamentos', coluna: 9 }
            ]
        },
        {
            idTabela: 'tabelaFornecedores',
            idPesquisa: 'pesquisaFornecedores',
            filtros: [
                { filtroId: 'filtroTipoFornecedor', coluna: 2 },
                { filtroId: 'filtroContratoFornecedor', coluna: 5 },
                { filtroId: 'filtroEstadoFornecedor', coluna: 6 }
            ]
        },
        {
            idTabela: 'tabelaLocalizacoes',
            idPesquisa: 'pesquisaLocalizacoes',
            filtros: [
                { filtroId: 'filtroTipoLocalizacao', coluna: 2 },
                { filtroId: 'filtroPisoLocalizacao', coluna: 3 },
                { filtroId: 'filtroEstadoLocalizacao', coluna: 6 }
            ]
        },
        {
            idTabela: 'tabelaDocumentacao',
            idPesquisa: 'pesquisaDocumentacao',
            filtros: [
                { filtroId: 'filtroTipoDocumento', coluna: 2 },
                { filtroId: 'filtroAreaDocumento', coluna: 4 },
                { filtroId: 'filtroEstadoDocumento', coluna: 6 }
            ]
        },
        {
            idTabela: 'tabelaGarantias',
            idPesquisa: 'pesquisaContratos',
            filtros: [
                { filtroId: 'filtroTipoContrato', coluna: 2 },
                { filtroId: 'filtroFornecedorContrato', coluna: 4 },
                { filtroId: 'filtroEstadoContrato', coluna: 7 }
            ]
        },
        {
            idTabela: 'tabelaContratos',
            idPesquisa: 'pesquisaContratos',
            filtros: [
                { filtroId: 'filtroTipoContrato', coluna: 2 },
                { filtroId: 'filtroFornecedorContrato', coluna: 4 },
                { filtroId: 'filtroEstadoContrato', coluna: 7 }
            ]
        }
    ];

    tabelas.forEach(function (tabela) {
        inicializarTabela(tabela.idTabela, tabela.idPesquisa, tabela.filtros);
    });
}


/* Configuração reutilizável dos formulários */
function inicializarFormulariosSimulados() {
    const formularios = [
        {
            idFormulario: 'formEquipamento',
            idMensagem: 'mensagemEquipamento',
            paginaDestino: 'equipamentos.html',
            validar: true
        },
        {
            idFormulario: 'formFornecedor',
            idMensagem: 'mensagemFornecedor',
            paginaDestino: 'fornecedores.html',
            validar: true
        },
        {
            idFormulario: 'formLocalizacao',
            idMensagem: 'mensagemLocalizacao',
            paginaDestino: 'localizacoes.html',
            validar: true
        },
        {
            idFormulario: 'formEliminarEquipamento',
            idMensagem: 'mensagemEliminarEquipamento',
            paginaDestino: 'equipamentos.html',
            validar: false
        },
        {
            idFormulario: 'formEliminarFornecedor',
            idMensagem: 'mensagemEliminarFornecedor',
            paginaDestino: 'fornecedores.html',
            validar: false
        },
        {
            idFormulario: 'formEliminarLocalizacao',
            idMensagem: 'mensagemEliminarLocalizacao',
            paginaDestino: 'localizacoes.html',
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

        if (!formContacto.checkValidity()) {
            e.stopPropagation();
            formContacto.classList.add('was-validated');
            return;
        }

        formContacto.classList.remove('was-validated');
        formContacto.reset();

        const mensagemSucesso = document.getElementById('mensagemSucesso');

        if (mensagemSucesso) {
            mensagemSucesso.classList.remove('d-none');

            setTimeout(function () {
                mensagemSucesso.classList.add('d-none');
            }, 4000);
        }
    });
}


/* Login simulado para a área reservada */
function inicializarLogin() {
    const formLogin = document.getElementById('formLogin');

    if (!formLogin) return;

    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formLogin.checkValidity()) {
            formLogin.classList.add('was-validated');
            return;
        }

        window.location.href = '../area-reservada/index.html';
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


/* Pesquisa e filtros reutilizáveis em tabelas */
function inicializarTabela(idTabela, idPesquisa, filtros) {
    const tabela = document.getElementById(idTabela);

    if (!tabela) return;

    const pesquisa = document.getElementById(idPesquisa);
    const corpoTabela = tabela.querySelector('tbody') || tabela;

    const filtrosAtivos = (filtros || [])
        .map(function (filtro) {
            return {
                elemento: document.getElementById(filtro.filtroId),
                coluna: filtro.coluna
            };
        })
        .filter(function (filtro) {
            return filtro.elemento !== null;
        });

    function aplicarPesquisaEFiltros() {
        const termo = pesquisa ? pesquisa.value.trim().toLowerCase() : '';
        const linhas = corpoTabela.querySelectorAll('tr');

        linhas.forEach(function (linha) {
            const textoLinha = linha.textContent.toLowerCase();

            const pesquisaOk = termo === '' || textoLinha.includes(termo);

            const filtrosOk = filtrosAtivos.every(function (filtro) {
                const valorFiltro = filtro.elemento.value;

                if (valorFiltro === '') return true;

                const celula = linha.children[filtro.coluna];

                if (!celula) return true;

                const valorLinha = celula.textContent.trim();

                return valorLinha === valorFiltro;
            });

            linha.classList.toggle('d-none', !(pesquisaOk && filtrosOk));
        });
    }

    if (pesquisa) {
        pesquisa.addEventListener('input', aplicarPesquisaEFiltros);
    }

    filtrosAtivos.forEach(function (filtro) {
        filtro.elemento.addEventListener('change', aplicarPesquisaEFiltros);
    });
}


/* Validação reutilizável para formulários simulados */
function inicializarFormularioSimulado(idFormulario, idMensagem, paginaDestino, validarFormulario) {
    const formulario = document.getElementById(idFormulario);
    const mensagem = document.getElementById(idMensagem);

    if (!formulario) return;

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validarFormulario && !formulario.checkValidity()) {
            formulario.classList.add('was-validated');
            abrirAbaComCampoInvalido(formulario);
            return;
        }

        formulario.classList.remove('was-validated');

        if (mensagem) {
            mensagem.classList.remove('d-none');
        }

        setTimeout(function () {
            window.location.href = paginaDestino;
        }, 1200);
    });
}


/* Abre automaticamente a aba onde existe o primeiro campo inválido */
function abrirAbaComCampoInvalido(formulario) {
    const campoInvalido = formulario.querySelector(':invalid');

    if (!campoInvalido) return;

    const aba = campoInvalido.closest('.tab-pane');

    if (!aba) return;

    const botaoAba = document.querySelector(`[data-bs-target="#${aba.id}"]`);

    if (botaoAba && typeof bootstrap !== 'undefined') {
        const tab = new bootstrap.Tab(botaoAba);
        tab.show();
    }
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

        if (!formulario.checkValidity()) {
            formulario.classList.add('was-validated');
            return;
        }

        formulario.classList.remove('was-validated');

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