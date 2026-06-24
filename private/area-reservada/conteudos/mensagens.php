<?php
$pageTitle = 'Mensagens do site — MedInfo Solutions';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'conteudos';
$extraCss = [$assetPath . '/bootstrap/dataTables.bootstrap5.min.css'];
$extraScripts = [
    $assetPath . '/jquery/jquery-3.7.1.min.js',
    $assetPath . '/js/jquery.dataTables.min.js',
    $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'
];

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

exigir_permissao('conteudos', 'ver');

function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function data_mensagem_site($data)
{
    if (empty($data)) {
        return '—';
    }

    $timestamp = strtotime((string) $data);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : (string) $data;
}

$erroBD = '';
$mensagens = [];

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados para carregar as mensagens do site.';
} else {
    try {
        $consulta = $ligacao->query("
            SELECT id, nome, email, instituicao, assunto, mensagem, estado, recebido_em
            FROM mensagens_contacto
            ORDER BY recebido_em DESC, id DESC
        ");
        $mensagens = $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $erro) {
        $erroBD = 'A ligação foi feita, mas não foi possível carregar a tabela mensagens_contacto.';
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Mensagens do site</h4>
                    <p class="text-muted mb-0">Mensagens enviadas através do formulário de contacto da página pública.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="../index.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar à dashboard
                    </a>
                    <a href="../../../public/index.php#contactos" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fa-solid fa-up-right-from-square me-1"></i> Ver página pública
                    </a>
                </div>
            </div>

            <?php if ($erroBD !== ''): ?>
                <?php mostrar_alerta_erro_base_dados($erroBD); ?>
            <?php endif; ?>

            <div class="card p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fa-solid fa-envelope-open-text me-2 text-primary"></i>
                        Mensagens recebidas
                    </h6>
                    <span class="badge text-bg-primary align-self-start align-self-md-center"><?php echo e(count($mensagens)); ?> mensagem(ns)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dashboard table-hover align-middle mb-0" id="tabelaMensagensSite">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Instituição</th>
                                <th>Assunto</th>
                                <th>Mensagem</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mensagens as $mensagem): ?>
                                <tr>
                                    <td data-order="<?php echo e($mensagem['recebido_em']); ?>"><?php echo e(data_mensagem_site($mensagem['recebido_em'])); ?></td>
                                    <td><?php echo e($mensagem['nome']); ?></td>
                                    <td><a href="mailto:<?php echo e($mensagem['email']); ?>"><?php echo e($mensagem['email']); ?></a></td>
                                    <td><?php echo e($mensagem['instituicao'] ?: '—'); ?></td>
                                    <td><?php echo e($mensagem['assunto']); ?></td>
                                    <td style="min-width: 280px;"><?php echo nl2br(e($mensagem['mensagem'])); ?></td>
                                    <td><span class="badge text-bg-info"><?php echo e($mensagem['estado'] ?: 'Nova'); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
