/* Inicializa os componentes interativos existentes em cada página */
document.addEventListener('DOMContentLoaded', function () {
    inicializarFormularioContacto();
    inicializarLogin();

    inicializarTooltips();
    inicializarToastPublic();

    inicializarTabelas();
    inicializarFormulariosSimulados();

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
                { filtroId: 'filtroEstadoEquipamentos', coluna: 6 }
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
            idTabela: 'tabelaContratos',
            idPesquisa: 'pesquisaContratos',
            filtros: [
                { filtroId: 'filtroTipoContrato', coluna: 2 },
                { filtroId: 'filtroFornecedorContrato', coluna: 4 },
                { filtroId: 'filtroEstadoContrato', coluna: 7 }
            ]
        },
        {
            idTabela: 'tabelaConteudos',
            idPesquisa: 'pesquisaConteudos',
            filtros: [
                { filtroId: 'filtroSecaoConteudo', coluna: 1 },
                { filtroId: 'filtroTipoConteudo', coluna: 3 },
                { filtroId: 'filtroEstadoConteudo', coluna: 5 }
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
            idFormulario: 'formDocumento',
            idMensagem: 'mensagemDocumento',
            paginaDestino: 'documentacao.html',
            validar: true
        },
        {
            idFormulario: 'formContrato',
            idMensagem: 'mensagemContrato',
            paginaDestino: 'contratos.html',
            validar: true
        },
        {
            idFormulario: 'formConteudo',
            idMensagem: 'mensagemConteudo',
            paginaDestino: 'conteudos.html',
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
        },
        {
            idFormulario: 'formEliminarDocumento',
            idMensagem: 'mensagemEliminarDocumento',
            paginaDestino: 'documentacao.html',
            validar: false
        },
        {
            idFormulario: 'formEliminarContrato',
            idMensagem: 'mensagemEliminarContrato',
            paginaDestino: 'contratos.html',
            validar: false
        },
        {
            idFormulario: 'formEliminarConteudo',
            idMensagem: 'mensagemEliminarConteudo',
            paginaDestino: 'conteudos.html',
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

        window.location.href = '../../private/area-reservada/index.html';
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