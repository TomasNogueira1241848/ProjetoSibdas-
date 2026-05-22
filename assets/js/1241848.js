/* Validação do formulário de contacto da área pública */
const formContacto = document.getElementById('formContacto');

if (formContacto) {
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
const formLogin = document.getElementById('formLogin');

if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formLogin.checkValidity()) {
            formLogin.classList.add('was-validated');
            return;
        }

        window.location.href = '../../private/area-reservada/index.html';
    });
}

/* Inicializa os componentes interativos existentes em cada página */
document.addEventListener('DOMContentLoaded', function () {
    inicializarTooltips();
    inicializarToastPublic();

    inicializarPesquisaTabela('pesquisaTabela', 'tabelaGarantiasConteudo');
    inicializarPesquisaTabela('pesquisaEquipamentos', 'tabelaEquipamentos');

    inicializarFiltrosEquipamentos();
    inicializarFormularioEquipamento();

    inicializarGraficosDashboard();
});

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

/* Pesquisa reutilizável em tabelas */
function inicializarPesquisaTabela(idPesquisa, idTabela) {
    const pesquisa = document.getElementById(idPesquisa);
    const tabela = document.getElementById(idTabela);

    if (!pesquisa || !tabela) return;

    pesquisa.addEventListener('input', function () {
        const termo = pesquisa.value.trim().toLowerCase();
        const linhas = tabela.querySelectorAll('tbody tr');

        linhas.forEach(function (linha) {
            const textoLinha = linha.textContent.toLowerCase();
            linha.classList.toggle('d-none', termo !== '' && !textoLinha.includes(termo));
        });
    });
}

/* Filtros da página de equipamentos */
function inicializarFiltrosEquipamentos() {
    const tabela = document.getElementById('tabelaEquipamentos');
    const filtroCategoria = document.getElementById('filtroCategoriaEquipamentos');
    const filtroEstado = document.getElementById('filtroEstadoEquipamentos');
    const filtroLocalizacao = document.getElementById('filtroLocalizacaoEquipamentos');

    if (!tabela || !filtroCategoria || !filtroEstado || !filtroLocalizacao) return;

    function aplicarFiltros() {
        const categoria = filtroCategoria.value;
        const estado = filtroEstado.value;
        const localizacao = filtroLocalizacao.value;

        tabela.querySelectorAll('tbody tr').forEach(function (linha) {
            const categoriaLinha = linha.children[2].textContent.trim();
            const localizacaoLinha = linha.children[5].textContent.trim();
            const estadoLinha = linha.children[6].textContent.trim();

            const categoriaOk = categoria === '' || categoriaLinha === categoria;
            const estadoOk = estado === '' || estadoLinha === estado;
            const localizacaoOk = localizacao === '' || localizacaoLinha === localizacao;

            linha.classList.toggle('d-none', !(categoriaOk && estadoOk && localizacaoOk));
        });
    }

    filtroCategoria.addEventListener('change', aplicarFiltros);
    filtroEstado.addEventListener('change', aplicarFiltros);
    filtroLocalizacao.addEventListener('change', aplicarFiltros);
}

/* Validação do formulário de novo/editar equipamento */
function inicializarFormularioEquipamento() {
    const formEquipamento = document.getElementById('formEquipamento');
    const mensagemEquipamento = document.getElementById('mensagemEquipamento');

    if (!formEquipamento) return;

    formEquipamento.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!formEquipamento.checkValidity()) {
            formEquipamento.classList.add('was-validated');
            return;
        }

        formEquipamento.classList.remove('was-validated');

        if (mensagemEquipamento) {
            mensagemEquipamento.classList.remove('d-none');
        }

        setTimeout(function () {
            window.location.href = 'equipamentos.html';
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