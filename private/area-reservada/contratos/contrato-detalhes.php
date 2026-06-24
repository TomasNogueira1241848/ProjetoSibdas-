<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Contrato';
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
function link_equipamento($id)
{
    return '../equipamentos/equipamento-detalhes.php?id_equipamento=' . urlencode(aes_encrypt((int)$id));
}
$idEncrypted = $_GET['id_contrato'] ?? null;
$id = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);
if (!$id || !is_numeric($id)) {
    header('Location: contratos.php');
    exit;
}
$id = (int)$id;
$idEncrypted = aes_encrypt($id);
$erroBD = '';
$contrato = null;
$equipamentos = [];
$ficheiros = [];
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $resp = 'NULL AS contrato_responsavel';
        $per = 'NULL AS contrato_periodicidade';
        foreach (['responsavel' => 'contrato_responsavel', 'periodicidade' => 'contrato_periodicidade'] as $col => $alias) {
            $c = $ligacao->prepare("SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='contratos' AND COLUMN_NAME='{$col}'");
            $c->execute();
            if ((int)$c->fetch()->total > 0) {
                if ($col === 'responsavel') $resp = 'c.responsavel AS contrato_responsavel';
                else $per = 'c.periodicidade AS contrato_periodicidade';
            }
        }
        $stmt = $ligacao->prepare("SELECT c.*, {$resp}, {$per}, tc.nome AS tipo_nome, CASE WHEN ec.nome IN ('Inválido','Invalido') THEN 'Cancelado' ELSE ec.nome END AS estado_nome, f.nome AS fornecedor_nome, f.nif AS fornecedor_nif, f.email AS fornecedor_email, f.telefone AS fornecedor_telefone, f.estado AS fornecedor_estado, tf.nome AS fornecedor_tipo FROM contratos c INNER JOIN tipos_contrato tc ON tc.id=c.tipo_contrato_id INNER JOIN estados_contrato ec ON ec.id=c.estado_contrato_id INNER JOIN fornecedores f ON f.id=c.fornecedor_id INNER JOIN tipos_fornecedor tf ON tf.id=f.tipo_fornecedor_id WHERE c.id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $contrato = $stmt->fetch();
        if (!$contrato) {
            header('Location: contratos.php');
            exit;
        }
        $stmt = $ligacao->prepare('SELECT e.id,e.codigo,e.designacao,e.marca,e.modelo,ee.nome AS estado,l.codigo AS localizacao_codigo,l.nome AS localizacao_nome,el.nome AS localizacao_estado FROM contrato_equipamentos ce INNER JOIN equipamentos e ON e.id=ce.equipamento_id INNER JOIN estados_equipamento ee ON ee.id=e.estado_id INNER JOIN localizacoes l ON l.id=e.localizacao_id INNER JOIN estados_localizacao el ON el.id=l.estado_localizacao_id WHERE ce.contrato_id=:id ORDER BY e.codigo');
        $stmt->execute([':id' => $id]);
        $equipamentos = $stmt->fetchAll();
        $stmt = $ligacao->prepare('SELECT fp.* FROM contrato_ficheiros cf INNER JOIN ficheiros_pdf fp ON fp.id=cf.ficheiro_id WHERE cf.contrato_id=:id ORDER BY fp.carregado_em DESC');
        $stmt->execute([':id' => $id]);
        $ficheiros = $stmt->fetchAll();
    } catch (PDOException $e) {
        $erroBD = 'Erro ao carregar contrato: ' . $e->getMessage();
    }
}
$temAssocAbatida = false;
foreach ($equipamentos as $eq) {
    if (mb_strtolower((string)$eq->estado) === 'abatido') {
        $temAssocAbatida = true;
        break;
    }
}
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do contrato</h4>
                    <p class="text-muted small mb-0">Consulta do contrato, fornecedor e equipamentos associados.</p>
                </div>
                <div class="d-flex gap-2"><a href="contratos.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a><?php if ($contrato && !$temAssocAbatida): ?>
                        <?php if (tem_permissao('contratos', 'remover')): ?><a href="contrato-eliminar.php?id_contrato=<?php echo urlencode($idEncrypted); ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash me-1"></i> Eliminar</a><?php endif; ?>
                    <?php endif; ?>
                </div>
            </div><?php if ($erroBD): ?>
                <?php mostrar_alerta_erro_base_dados($erroBD); ?><?php elseif ($contrato): ?><?php if ($temAssocAbatida): ?>
                <div class="alert alert-warning"><strong>Atenção:</strong> existe equipamento abatido associado a este contrato.</div>
            <?php endif; ?><div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <div class="d-flex justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($contrato->codigo); ?></h5>
                                <p class="text-muted small mb-0"><?php echo e($contrato->designacao); ?></p>
                            </div><span class="badge <?php echo badge_estado($contrato->estado_nome); ?> align-self-start"><?php echo e($contrato->estado_nome); ?></span>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <?php campo('Tipo', $contrato->tipo_nome);
                            campo('Responsável', $contrato->contrato_responsavel ?? '');
                            campo('Data de início', data_pt($contrato->data_inicio));
                            campo('Data de fim', data_pt($contrato->data_fim));
                            campo('Valor anual', $contrato->valor_anual !== null ? number_format((float)$contrato->valor_anual, 2, ',', '.') . ' €' : '');
                            campo('Periodicidade', $contrato->contrato_periodicidade ?? '');
                            campo('Renovação automática', (int)$contrato->renovacao_automatica === 1 ? 'Sim' : 'Não'); ?><div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0"><?php echo nl2br(e(v(limpar_observacoes_sistema($contrato->observacoes)))); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h6 class="fw-bold mb-3">Fornecedor associado</h6>
                        <div class="row g-3">
                            <?php campo('Nome', $contrato->fornecedor_nome);
                                campo('Tipo', $contrato->fornecedor_tipo);
                                campo('NIF', $contrato->fornecedor_nif);
                                campo('Email', $contrato->fornecedor_email);
                                campo('Contacto', $contrato->fornecedor_telefone);
                                campo('Estado', $contrato->fornecedor_estado); ?></div>
                    </div>
                </div>
            </div>
            <section class="mb-4">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Equipamentos associados</h6>
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Equipamento</th>
                                    <th>Localização</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody><?php if (empty($equipamentos)): ?><tr>
                                        <td colspan="5" class="text-muted text-center">Sem equipamentos associados.</td>
                                    </tr><?php endif; ?><?php foreach ($equipamentos as $eq): ?><tr>
                                        <td><?php echo e($eq->codigo); ?></td>
                                        <td><?php echo e($eq->designacao); ?><?php if (mb_strtolower((string)$eq->estado) === 'abatido'): ?><br><span class="badge badge-inativo">Equipamento abatido</span><?php endif; ?><div class="text-muted small"><?php echo e($eq->marca . ' ' . $eq->modelo); ?></div>
                                        </td>
                                        <td><?php echo e($eq->localizacao_codigo . ' — ' . $eq->localizacao_nome); ?></td>
                                        <td><span class="badge <?php echo badge_estado($eq->estado); ?>"><?php echo e($eq->estado); ?></span></td>
                                        <td><a href="<?php echo e(link_equipamento($eq->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                    </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="mb-4">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">PDFs associados</h6><?php if (empty($ficheiros)): ?><p class="text-muted small mb-0">Sem PDFs associados.</p><?php else: ?><?php foreach ($ficheiros as $f): ?>
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <span><i class="fa-solid fa-file-pdf me-2"></i><?php echo e($f->nome_original); ?></span>
                            <a href="<?php echo e(link_pdf($f)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                        </div>
                        <?php endforeach; ?><?php endif; ?>
                </div>
            </section><?php endif; ?>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>