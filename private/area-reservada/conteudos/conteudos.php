<?php
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/conteudos_publicos.php';

redirect_if_not_logged();

$pageTitle = 'MedInfo Solutions — Gestão de Conteúdos';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'conteudos';
$jsVersion = 'conteudos-bd-1';

$erros = [];
$mensagemSucesso = '';
$camposObrigatorios = [
    'hero_titulo' => 'Introduza o título principal.',
    'hero_subtitulo' => 'Introduza o texto principal.',
    'sobre_titulo' => 'Introduza o título da secção Sobre Nós.',
    'sobre_texto_1' => 'Introduza o primeiro texto da secção Sobre Nós.',
    'servicos_titulo' => 'Introduza o título dos serviços.',
    'servicos_texto' => 'Introduza o texto dos serviços.',
    'clientes_titulo' => 'Introduza o título dos clientes.',
    'clientes_texto' => 'Introduza o texto dos clientes.',
    'contactos_titulo' => 'Introduza o título dos contactos.',
    'contactos_texto' => 'Introduza o texto dos contactos.',
    'morada' => 'Introduza a morada.',
    'telefone' => 'Introduza o telefone.',
    'email' => 'Introduza o email.',
    'website' => 'Introduza o website.',
    'horario' => 'Introduza o horário.',
    'rodape_texto' => 'Introduza o texto do rodapé.'
];

$conteudos = obter_conteudos_publicos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'guardar';

    if ($acao === 'repor') {
        $conteudos = conteudos_publicos_padrao();
    } else {
        foreach (conteudos_publicos_padrao() as $campo => $valorPadrao) {
            $conteudos[$campo] = isset($_POST[$campo]) ? trim((string) $_POST[$campo]) : '';
        }

        foreach ($camposObrigatorios as $campo => $mensagem) {
            if (($conteudos[$campo] ?? '') === '') {
                $erros[] = $mensagem;
            }
        }

        if (!empty($conteudos['email']) && !filter_var($conteudos['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Introduza um email válido.';
        }
    }

    if (empty($erros)) {
        $utilizadorId = $_SESSION['utilizador']['id'] ?? null;

        if (guardar_conteudos_publicos($conteudos, $utilizadorId)) {
            $mensagemSucesso = $acao === 'repor'
                ? 'Conteúdos originais repostos com sucesso.'
                : 'Conteúdos atualizados com sucesso.';

            $conteudos = obter_conteudos_publicos();
        } else {
            $erros[] = 'Não foi possível guardar os conteúdos na base de dados.';
            $erroTecnicoConteudos = conteudos_publicos_ultimo_erro();
            if ($erroTecnicoConteudos !== '') {
                $erros[] = 'Detalhe técnico: ' . $erroTecnicoConteudos;
            }
        }
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Gestão de Conteúdos</h4>
                    <p class="text-muted small mb-0">Alteração dos textos apresentados na página principal pública, guardados diretamente na base de dados.</p>
                </div>

                <a href="../../../public/index.php" class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver página pública
                </a>
            </div>

            <section class="mb-4">
                <div class="card p-4">
                    <?php if (!empty($erros)): ?>
                        <div class="alert alert-danger">
                            <strong>Corrige os seguintes campos:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($erros as $erro): ?>
                                    <li><?php echo h($erro); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($mensagemSucesso !== ''): ?>
                        <div class="alert alert-success">
                            <i class="fa-solid fa-check me-1"></i> <?php echo h($mensagemSucesso); ?>
                        </div>
                    <?php endif; ?>

                    <form id="formConteudosPublicos" method="post" action="" novalidate>

                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-house me-2 text-primary"></i> Secção inicial
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoHeroTitulo" class="form-label">Título principal</label>
                                <input type="text" class="form-control" id="conteudoHeroTitulo" name="hero_titulo" value="<?php echo h($conteudos['hero_titulo']); ?>" required>
                                <div class="invalid-feedback">Introduza o título principal.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoHeroSubtitulo" class="form-label">Texto principal</label>
                                <textarea class="form-control" id="conteudoHeroSubtitulo" name="hero_subtitulo" rows="3" required><?php echo h($conteudos['hero_subtitulo']); ?></textarea>
                                <div class="invalid-feedback">Introduza o texto principal.</div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-building me-2 text-primary"></i> Sobre Nós
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoSobreTitulo" class="form-label">Título da secção</label>
                                <input type="text" class="form-control" id="conteudoSobreTitulo" name="sobre_titulo" value="<?php echo h($conteudos['sobre_titulo']); ?>" required>
                                <div class="invalid-feedback">Introduza o título da secção.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto1" class="form-label">Texto 1</label>
                                <textarea class="form-control" id="conteudoSobreTexto1" name="sobre_texto_1" rows="3" required><?php echo h($conteudos['sobre_texto_1']); ?></textarea>
                                <div class="invalid-feedback">Introduza o primeiro texto.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto2" class="form-label">Texto 2</label>
                                <textarea class="form-control" id="conteudoSobreTexto2" name="sobre_texto_2" rows="3"><?php echo h($conteudos['sobre_texto_2']); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label for="conteudoSobreTexto3" class="form-label">Texto 3</label>
                                <textarea class="form-control" id="conteudoSobreTexto3" name="sobre_texto_3" rows="3"><?php echo h($conteudos['sobre_texto_3']); ?></textarea>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-briefcase-medical me-2 text-primary"></i> Serviços e clientes
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="conteudoServicosTitulo" class="form-label">Título dos serviços</label>
                                <input type="text" class="form-control" id="conteudoServicosTitulo" name="servicos_titulo" value="<?php echo h($conteudos['servicos_titulo']); ?>" required>
                                <div class="invalid-feedback">Introduza o título dos serviços.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoClientesTitulo" class="form-label">Título dos clientes</label>
                                <input type="text" class="form-control" id="conteudoClientesTitulo" name="clientes_titulo" value="<?php echo h($conteudos['clientes_titulo']); ?>" required>
                                <div class="invalid-feedback">Introduza o título dos clientes.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoServicosTexto" class="form-label">Texto dos serviços</label>
                                <textarea class="form-control" id="conteudoServicosTexto" name="servicos_texto" rows="2" required><?php echo h($conteudos['servicos_texto']); ?></textarea>
                                <div class="invalid-feedback">Introduza o texto dos serviços.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoClientesTexto" class="form-label">Texto dos clientes</label>
                                <textarea class="form-control" id="conteudoClientesTexto" name="clientes_texto" rows="2" required><?php echo h($conteudos['clientes_texto']); ?></textarea>
                                <div class="invalid-feedback">Introduza o texto dos clientes.</div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-address-card me-2 text-primary"></i> Contactos
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="conteudoContactosTitulo" class="form-label">Título dos contactos</label>
                                <input type="text" class="form-control" id="conteudoContactosTitulo" name="contactos_titulo" value="<?php echo h($conteudos['contactos_titulo']); ?>" required>
                                <div class="invalid-feedback">Introduza o título dos contactos.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoTelefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="conteudoTelefone" name="telefone" value="<?php echo h($conteudos['telefone']); ?>" required>
                                <div class="invalid-feedback">Introduza o telefone.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoContactosTexto" class="form-label">Texto dos contactos</label>
                                <textarea class="form-control" id="conteudoContactosTexto" name="contactos_texto" rows="2" required><?php echo h($conteudos['contactos_texto']); ?></textarea>
                                <div class="invalid-feedback">Introduza o texto dos contactos.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="conteudoEmail" name="email" value="<?php echo h($conteudos['email']); ?>" required>
                                <div class="invalid-feedback">Introduza um email válido.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="conteudoWebsite" class="form-label">Website</label>
                                <input type="text" class="form-control" id="conteudoWebsite" name="website" value="<?php echo h($conteudos['website']); ?>" required>
                                <div class="invalid-feedback">Introduza o website.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoMorada" class="form-label">Morada</label>
                                <textarea class="form-control" id="conteudoMorada" name="morada" rows="2" required><?php echo h($conteudos['morada']); ?></textarea>
                                <div class="invalid-feedback">Introduza a morada.</div>
                            </div>

                            <div class="col-12">
                                <label for="conteudoHorario" class="form-label">Horário</label>
                                <textarea class="form-control" id="conteudoHorario" name="horario" rows="2" required><?php echo h($conteudos['horario']); ?></textarea>
                                <div class="invalid-feedback">Introduza o horário.</div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-window-minimize me-2 text-primary"></i> Rodapé
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="conteudoRodapeTexto" class="form-label">Texto do rodapé</label>
                                <input type="text" class="form-control" id="conteudoRodapeTexto" name="rodape_texto" value="<?php echo h($conteudos['rodape_texto']); ?>" required>
                                <div class="invalid-feedback">Introduza o texto do rodapé.</div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="submit" class="btn btn-outline-danger" name="acao" value="repor">
                                <i class="fa-solid fa-rotate-left me-1"></i> Repor originais
                            </button>

                            <button type="submit" class="btn btn-primary" name="acao" value="guardar">
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
