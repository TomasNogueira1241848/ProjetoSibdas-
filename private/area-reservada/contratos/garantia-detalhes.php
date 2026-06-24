<?php
$pageTitle = 'MedInfo Solutions — Detalhes da Garantia';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'contratos';
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
exigir_permissao('contratos', 'ver');
function e($v)
{
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function v($v)
{
    $v = trim((string)($v ?? ''));
    return $v === '' ? '—' : $v;
}

function limpar_observacoes_sistema($texto)
{
    $texto = trim((string) ($texto ?? ''));

    if ($texto === '') {
        return '';
    }

    $texto = str_replace(["\r\n", "\r"], "\n", $texto);

    if (preg_match('/Observa(?:ções|coes)\s*:\s*(.*)$/isu', $texto, $match)) {
        return trim($match[1]);
    }

    $linhasLimpas = [];
    foreach (preg_split('/\R/u', $texto) as $linha) {
        $linha = trim($linha);

        if ($linha === '') {
            continue;
        }

        if (preg_match('/^(Responsável|Responsavel|Associado a|Periodicidade)\s*:/iu', $linha)) {
            continue;
        }

        $linhasLimpas[] = $linha;
    }

    return trim(implode("\n", $linhasLimpas));
}
function data_pt($d)
{
    return $d ? date('d/m/Y', strtotime($d)) : '—';
}
function badge_estado($estado)
{
    $n = mb_strtolower((string)$estado);
    if (in_array($n, ['ativo', 'ativa', 'válido', 'valido'], true)) return 'badge-ativo';
    if (str_contains($n, 'expirar') || str_contains($n, 'manutenção')) return 'badge-manutencao';
    return 'badge-inativo';
}
function campo($r, $v)
{
    echo '<div class="col-md-6"><p class="text-muted small mb-1">' . e($r) . '</p><p class="mb-0">' . e(v($v)) . '</p></div>';
}
function link_pdf($f)
{
    $c = trim((string)($f->caminho_ficheiro ?? ''));
    return $c === '' ? '#' : BASE_URL . '/' . ltrim($c, '/');
}
$idEncrypted = $_GET['id_garantia'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$id || !is_numeric($id)) {
    header('Location: contratos.php');
    exit;
}
$id = (int)$id;
$idEncrypted = aes_encrypt($id);
$erroBD = '';
$garantia = null;
$ficheiros = [];
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $resp = 'NULL AS garantia_responsavel';
        $c = $ligacao->prepare("SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='garantias' AND COLUMN_NAME='responsavel'");
        $c->execute();
        if ((int)$c->fetch()->total > 0) $resp = 'g.responsavel AS garantia_responsavel';
        $stmt = $ligacao->prepare("SELECT g.*, {$resp}, CASE WHEN eg.nome = 'Expirada' THEN 'Expirado' ELSE eg.nome END AS estado_nome, f.nome AS fornecedor_nome, f.nif AS fornecedor_nif, f.email AS fornecedor_email, f.telefone AS fornecedor_telefone, f.estado AS fornecedor_estado, tf.nome AS fornecedor_tipo, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, e.marca AS equipamento_marca, e.modelo AS equipamento_modelo, e.numero_serie AS equipamento_numero_serie, e.servico AS equipamento_servico, e.piso AS equipamento_piso, e.sala AS equipamento_sala, ee.nome AS equipamento_estado, l.codigo AS localizacao_codigo, l.nome AS localizacao_nome, el.nome AS localizacao_estado, c.codigo AS contrato_codigo, c.designacao AS contrato_designacao FROM garantias g INNER JOIN estados_garantia eg ON eg.id=g.estado_garantia_id INNER JOIN fornecedores f ON f.id=g.fornecedor_id INNER JOIN tipos_fornecedor tf ON tf.id=f.tipo_fornecedor_id INNER JOIN equipamentos e ON e.id=g.equipamento_id INNER JOIN estados_equipamento ee ON ee.id=e.estado_id INNER JOIN localizacoes l ON l.id=e.localizacao_id INNER JOIN estados_localizacao el ON el.id=l.estado_localizacao_id LEFT JOIN contratos c ON c.id=g.contrato_id WHERE g.id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $garantia = $stmt->fetch();
        if (!$garantia) {
            header('Location: contratos.php');
            exit;
        }
        $stmt = $ligacao->prepare('SELECT fp.* FROM garantia_ficheiros gf INNER JOIN ficheiros_pdf fp ON fp.id=gf.ficheiro_id WHERE gf.garantia_id=:id ORDER BY fp.carregado_em DESC');
        $stmt->execute([':id' => $id]);
        $ficheiros = $stmt->fetchAll();
    } catch (PDOException $e) {
        $erroBD = 'Erro ao carregar garantia: ' . $e->getMessage();
    }
}
$equipamentoAbatido = $garantia && mb_strtolower((string)$garantia->equipamento_estado) === 'abatido';
$garantiaCancelada = $garantia && mb_strtolower((string)$garantia->estado_nome) === 'cancelado';
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes da garantia</h4>
                    <p class="text-muted small mb-0">Consulta da garantia, equipamento e fornecedor associado.</p>
                </div>
                <div class="d-flex gap-2"><a href="contratos.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a><?php if ($garantia && !$equipamentoAbatido && !$garantiaCancelada): ?>
                        <?php if (tem_permissao('contratos', 'remover')): ?><a href="garantia-eliminar.php?id_garantia=<?php echo urlencode($idEncrypted); ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash me-1"></i> Eliminar</a><?php endif; ?>
                    <?php endif; ?>
                </div>

            </div><?php if ($erroBD): ?><?php mostrar_alerta_erro_base_dados($erroBD); ?>
                <?php elseif ($garantia): ?><?php if ($equipamentoAbatido): ?>
                <div class="alert alert-warning"><strong>Atenção:</strong> o equipamento associado a esta garantia está abatido.</div>
            <?php endif; ?><div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($garantia->codigo); ?></h5>
                                <p class="text-muted small mb-0"><?php echo e($garantia->designacao); ?></p>
                            </div><span class="badge <?php echo badge_estado($garantia->estado_nome); ?> align-self-start"><?php echo e($garantia->estado_nome); ?></span>
                        </div>
                        <hr>
                        <div class="row g-3"><?php campo('Tipo', 'Garantia');
                                                campo('Responsável', $garantia->garantia_responsavel ?? '');
                                                campo('Data de início', data_pt($garantia->data_inicio));
                                                campo('Data de fim', data_pt($garantia->data_fim));
                                                campo('Contrato associado', $garantia->contrato_codigo ? $garantia->contrato_codigo . ' — ' . $garantia->contrato_designacao : 'Sem contrato associado'); ?><div class="col-12">
                                <p class="text-muted small mb-1">Cobertura</p>
                                <p class="mb-0"><?php echo nl2br(e(v($garantia->cobertura))); ?></p>
                            </div>
                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0"><?php echo nl2br(e(v(limpar_observacoes_sistema($garantia->observacoes)))); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">PDFs associados</h6><?php if (empty($ficheiros)): ?>
                            <p class="text-muted small mb-0">Sem PDFs associados.</p><?php else: ?>
                                <?php foreach ($ficheiros as $f): ?>
                                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                        <span><i class="fa-solid fa-file-pdf me-2"></i><?php echo e($f->nome_original); ?></span>
                                        <a href="<?php echo e(link_pdf($f)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                                    </div><?php endforeach; ?><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">Dados principais do equipamento</h6>
                        <div class="row g-3">
                            <?php campo('Código', $garantia->equipamento_codigo);
                            campo('Designação', $garantia->equipamento_designacao);
                            campo('Marca', $garantia->equipamento_marca);
                            campo('Modelo', $garantia->equipamento_modelo);
                            campo('N.º série', $garantia->equipamento_numero_serie);
                            campo('Estado', $garantia->equipamento_estado);
                            campo('Serviço', $garantia->equipamento_servico);
                            campo('Localização', $garantia->localizacao_codigo . ' — ' . $garantia->localizacao_nome); ?></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">Fornecedor associado</h6>
                        <div class="row g-3">
                            <?php campo('Nome', $garantia->fornecedor_nome);
                            campo('Tipo', $garantia->fornecedor_tipo);
                            campo('NIF', $garantia->fornecedor_nif);
                            campo('Email', $garantia->fornecedor_email);
                            campo('Contacto', $garantia->fornecedor_telefone);
                            campo('Estado', $garantia->fornecedor_estado); ?></div>
                    </div>
                </div>
            </div><?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>