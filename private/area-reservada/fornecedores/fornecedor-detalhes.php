<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Fornecedor';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'fornecedores';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();

function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}
function mostrar_valor($valor, $vazio = '—')
{
    $valor = trim((string) ($valor ?? ''));
    return $valor === '' ? $vazio : $valor;
}
function sim_nao($valor)
{
    return (int) $valor === 1 ? 'Sim' : 'Não';
}
function data_pt($data)
{
    return $data ? date('d/m/Y', strtotime($data)) : '—';
}
function badge_estado($estado)
{
    $estado = (string) $estado;
    if (in_array(mb_strtolower($estado), ['ativo', 'ativa', 'válido', 'valido'], true)) return 'badge-ativo';
    if (str_contains(mb_strtolower($estado), 'manutenção') || str_contains(mb_strtolower($estado), 'expirar')) return 'badge-manutencao';
    return 'badge-inativo';
}
function linha_campo($rotulo, $valor)
{
    echo '<div class="col-md-6"><p class="text-muted small mb-1">' . e($rotulo) . '</p><p class="mb-0">' . e(mostrar_valor($valor)) . '</p></div>';
}
function link_equipamento($id)
{
    return '../equipamentos/equipamento-detalhes.php?id_equipamento=' . urlencode(aes_encrypt((int) $id));
}
function link_documento($id)
{
    return '../documentacao/documento-detalhes.php?id_documento=' . urlencode(aes_encrypt((int) $id));
}
function link_contrato($id)
{
    return '../contratos/contrato-detalhes.php?id_contrato=' . urlencode(aes_encrypt((int) $id));
}
function link_garantia($id)
{
    return '../contratos/garantia-detalhes.php?id_garantia=' . urlencode(aes_encrypt((int) $id));
}

$idEncrypted = $_GET['id_fornecedor'] ?? null;
$idFornecedor = $idEncrypted ? aes_decrypt($idEncrypted) : ($_GET['id'] ?? null);

if (!$idFornecedor || !is_numeric($idFornecedor)) {
    header('Location: fornecedores.php');
    exit;
}
$idFornecedor = (int) $idFornecedor;
$idEncrypted = aes_encrypt($idFornecedor);

$erroBD = '';
$fornecedor = null;
$equipamentos = [];
$documentos = [];
$garantias = [];
$contratos = [];

$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $stmt = $ligacao->prepare('
            SELECT f.*, tf.nome AS tipo_nome
            FROM fornecedores f
            INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id
            WHERE f.id = :id
            LIMIT 1
        ');
        $stmt->execute([':id' => $idFornecedor]);
        $fornecedor = $stmt->fetch();
        if (!$fornecedor) {
            header('Location: fornecedores.php');
            exit;
        }

        $stmt = $ligacao->prepare('
            SELECT DISTINCT e.id, e.codigo, e.designacao, e.marca, e.modelo, ff.nome AS funcao,
                   l.nome AS localizacao, el.nome AS localizacao_estado, ee.nome AS estado
            FROM equipamento_fornecedores ef
            INNER JOIN equipamentos e ON e.id = ef.equipamento_id
            INNER JOIN funcoes_fornecedor ff ON ff.id = ef.funcao_fornecedor_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            WHERE ef.fornecedor_id = :id
            UNION
            SELECT e.id, e.codigo, e.designacao, e.marca, e.modelo, "Fornecedor principal" AS funcao,
                   l.nome AS localizacao, el.nome AS localizacao_estado, ee.nome AS estado
            FROM equipamentos e
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            WHERE e.fornecedor_principal_id = :id
            ORDER BY codigo
        ');
        $stmt->execute([':id' => $idFornecedor]);
        $equipamentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT d.id, d.codigo, d.titulo, td.nome AS tipo, ed.nome AS estado,
                   e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, ee.nome AS equipamento_estado,
                   d.validade
            FROM documentos d
            INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id
            INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id
            INNER JOIN equipamentos e ON e.id = d.equipamento_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            WHERE d.fornecedor_id = :id
            ORDER BY d.codigo
        ');
        $stmt->execute([':id' => $idFornecedor]);
        $documentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT g.id, g.codigo, g.designacao, eg.nome AS estado, e.codigo AS equipamento_codigo, e.designacao AS equipamento_designacao, g.data_inicio, g.data_fim
            FROM garantias g
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            INNER JOIN equipamentos e ON e.id = g.equipamento_id
            WHERE g.fornecedor_id = :id
            ORDER BY g.codigo
        ');
        $stmt->execute([':id' => $idFornecedor]);
        $garantias = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT c.id, c.codigo, c.designacao, tc.nome AS tipo, ec.nome AS estado, c.data_inicio, c.data_fim,
                   COALESCE(GROUP_CONCAT(e.codigo ORDER BY e.codigo SEPARATOR ", "), "Sem equipamentos") AS equipamentos
            FROM contratos c
            INNER JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
            INNER JOIN estados_contrato ec ON ec.id = c.estado_contrato_id
            LEFT JOIN contrato_equipamentos ce ON ce.contrato_id = c.id
            LEFT JOIN equipamentos e ON e.id = ce.equipamento_id
            WHERE c.fornecedor_id = :id
            GROUP BY c.id, c.codigo, c.designacao, tc.nome, ec.nome, c.data_inicio, c.data_fim
            ORDER BY c.codigo
        ');
        $stmt->execute([':id' => $idFornecedor]);
        $contratos = $stmt->fetchAll();
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar o fornecedor: ' . $erro->getMessage();
    }
}

$fornecedorDescontinuado = $fornecedor && in_array(mb_strtolower((string) $fornecedor->estado), ['descontinuado', 'abatido'], true);

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do fornecedor</h4>
                    <p class="text-muted small mb-0">Consulta da ficha do fornecedor e dos registos associados.</p>
                </div>
                <div class="d-flex gap-2">
                    <?php if (!$fornecedorDescontinuado): ?>
                        <a href="fornecedor-editar.php?id_fornecedor=<?php echo urlencode($idEncrypted); ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i> Editar</a>
                        <a href="fornecedor-eliminar.php?id_fornecedor=<?php echo urlencode($idEncrypted); ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-box-archive me-1"></i> Descontinuar</a>
                    <?php endif; ?>
                    <a href="fornecedores.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
                </div>
            </div>

            <?php if ($erroBD !== ''): ?>
                <div class="alert alert-danger"><?php echo e($erroBD); ?></div>
            <?php elseif ($fornecedor): ?>
                <?php if ($fornecedorDescontinuado): ?>
                    <div class="alert alert-warning d-flex align-items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                        <div><strong class="d-block">Fornecedor descontinuado</strong><span>Este fornecedor permanece no histórico, mas já não deve ser usado em novos registos. Os equipamentos e documentos associados continuam disponíveis.</span></div>
                    </div>
                <?php endif; ?>

                <section class="mb-4">
                    <div class="card p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($fornecedor->nome); ?></h5>
                                <p class="text-muted small mb-0"><?php echo e($fornecedor->tipo_nome); ?></p>
                            </div>
                            <span class="badge <?php echo badge_estado($fornecedor->estado); ?> align-self-start"><?php echo e($fornecedor->estado); ?></span>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <?php
                            linha_campo('NIF', $fornecedor->nif);
                            linha_campo('Email', $fornecedor->email);
                            linha_campo('Contacto', $fornecedor->telefone);
                            linha_campo('Contrato ativo', sim_nao($fornecedor->contrato_ativo));
                            linha_campo('Website', $fornecedor->website);
                            linha_campo('Área de atuação', $fornecedor->area_atuacao);
                            linha_campo('Pessoa responsável', $fornecedor->pessoa_contacto);
                            linha_campo('Contacto da pessoa responsável', $fornecedor->telefone_contacto);
                            ?>
                            <div class="col-12">
                                <p class="text-muted small mb-1">Morada</p>
                                <p class="mb-0"><?php echo e(mostrar_valor($fornecedor->morada)); ?></p>
                            </div>
                            <div class="col-12">
                                <p class="text-muted small mb-1">Observações</p>
                                <p class="mb-0"><?php echo nl2br(e(mostrar_valor($fornecedor->observacoes))); ?></p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Equipamentos associados</h6>
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Ligação</th>
                                        <th>Localização</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($equipamentos)): ?><tr>
                                            <td colspan="6" class="text-muted text-center">Sem equipamentos associados.</td>
                                        </tr><?php endif; ?>
                                    <?php foreach ($equipamentos as $eq): ?>
                                        <?php $locAbatida = in_array(mb_strtolower((string) $eq->localizacao_estado), ['inativa', 'abatida'], true); ?>
                                        <tr>
                                            <td><?php echo e($eq->codigo); ?></td>
                                            <td><?php echo e($eq->designacao); ?><div class="text-muted small"><?php echo e($eq->marca . ' ' . $eq->modelo); ?></div>
                                            </td>
                                            <td><?php echo e($eq->funcao); ?></td>
                                            <td><?php echo e($eq->localizacao); ?><?php if ($locAbatida): ?><br><span class="badge badge-inativo">Localização abatida</span><?php endif; ?></td>
                                            <td><span class="badge <?php echo badge_estado($eq->estado); ?>"><?php echo e($eq->estado); ?></span></td>
                                            <td class="text-end"><a href="<?php echo e(link_equipamento($eq->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Documentos associados ao fornecedor</h6>
                        <div class="table-responsive">
                            <table class="table table-dashboard table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Documento</th>
                                        <th>Tipo</th>
                                        <th>Equipamento</th>
                                        <th>Validade</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($documentos)): ?><tr>
                                            <td colspan="7" class="text-muted text-center">Sem documentos associados.</td>
                                        </tr><?php endif; ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td><?php echo e($doc->codigo); ?></td>
                                            <td><?php echo e($doc->titulo); ?></td>
                                            <td><?php echo e($doc->tipo); ?></td>
                                            <td><?php echo e($doc->equipamento_codigo . ' — ' . $doc->equipamento_designacao); ?></td>
                                            <td><?php echo e(data_pt($doc->validade)); ?></td>
                                            <td><span class="badge <?php echo badge_estado($doc->estado); ?>"><?php echo e($doc->estado); ?></span></td>
                                            <td class="text-end"><a href="<?php echo e(link_documento($doc->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h6 class="fw-bold mb-3">Garantias associadas</h6>
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Designação</th>
                                            <th>Equipamento</th>
                                            <th>Fim</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($garantias)): ?><tr>
                                                <td colspan="6" class="text-muted text-center">Sem garantias.</td>
                                            </tr><?php endif; ?>
                                        <?php foreach ($garantias as $gar): ?><tr>
                                                <td><?php echo e($gar->codigo); ?></td>
                                                <td><?php echo e($gar->designacao); ?></td>
                                                <td><?php echo e($gar->equipamento_codigo . ' — ' . $gar->equipamento_designacao); ?></td>
                                                <td><?php echo e(data_pt($gar->data_fim)); ?></td>
                                                <td><span class="badge <?php echo badge_estado($gar->estado); ?>"><?php echo e($gar->estado); ?></span></td>
                                                <td><a href="<?php echo e(link_garantia($gar->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                            </tr><?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h6 class="fw-bold mb-3">Contratos associados</h6>
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Designação</th>
                                            <th>Tipo</th>
                                            <th>Equipamentos</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($contratos)): ?><tr>
                                                <td colspan="6" class="text-muted text-center">Sem contratos.</td>
                                            </tr><?php endif; ?>
                                        <?php foreach ($contratos as $cont): ?><tr>
                                                <td><?php echo e($cont->codigo); ?></td>
                                                <td><?php echo e($cont->designacao); ?></td>
                                                <td><?php echo e($cont->tipo); ?></td>
                                                <td><?php echo e($cont->equipamentos); ?></td>
                                                <td><span class="badge <?php echo badge_estado($cont->estado); ?>"><?php echo e($cont->estado); ?></span></td>
                                                <td><a href="<?php echo e(link_contrato($cont->id)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></a></td>
                                            </tr><?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>