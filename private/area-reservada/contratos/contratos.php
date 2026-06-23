<?php
$pageTitle = 'MedInfo Solutions — Garantias e Contratos';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'contratos';

$extraCss = [$assetPath . '/bootstrap/dataTables.bootstrap5.min.css'];
$extraScripts = [$assetPath . '/jquery/jquery-3.7.1.min.js', $assetPath . '/js/jquery.dataTables.min.js', $assetPath . '/bootstrap/dataTables.bootstrap5.min.js'];

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
redirect_if_not_logged();
exigir_permissao('contratos', 'ver');

function e($v)
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
function badge_estado($estado)
{
    $n = mb_strtolower((string)$estado);
    if (in_array($n, ['ativo', 'ativa', 'válido', 'valido'], true)) return 'badge-ativo';
    if (str_contains($n, 'expirar') || str_contains($n, 'manutenção')) return 'badge-manutencao';
    return 'badge-inativo';
}
function abate($estado)
{
    return mb_strtolower((string)$estado) === 'abatido';
}
function cancelado($estado)
{
    return mb_strtolower((string)$estado) === 'cancelado';
}

$garantias = [];
$contratos = [];
$tiposContrato = [];
$fornecedores = [];
$estadosContratosGarantias = [];
$erroBD = '';
$indicadores = ['total' => 0, 'garantias' => 0, 'contratos' => 0, 'a_expirar' => 0];
$ligacao = ligar_base_dados();
if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposContrato = $ligacao->query('SELECT nome FROM tipos_contrato ORDER BY nome')->fetchAll();
        $fornecedores = $ligacao->query('SELECT nome FROM fornecedores ORDER BY nome')->fetchAll();
        $estadosContratosGarantias = $ligacao->query("SELECT DISTINCT nome FROM (SELECT CASE WHEN nome = 'Expirada' THEN 'Expirado' ELSE nome END AS nome FROM estados_garantia WHERE nome <> 'Expirada' UNION SELECT nome FROM estados_contrato WHERE nome NOT IN ('Inválido', 'Invalido')) estados ORDER BY nome")->fetchAll();
        $sqlGarantias = "SELECT g.id,g.codigo,g.designacao,'Garantia' AS tipo,CONCAT(e.codigo,' — ',e.designacao) AS equipamento,f.nome AS fornecedor,g.data_inicio,g.data_fim,CASE WHEN eg.nome = 'Expirada' THEN 'Expirado' ELSE eg.nome END AS estado,ee.nome AS equipamento_estado,f.estado AS fornecedor_estado,el.nome AS localizacao_estado FROM garantias g INNER JOIN equipamentos e ON e.id=g.equipamento_id INNER JOIN estados_equipamento ee ON ee.id=e.estado_id INNER JOIN localizacoes l ON l.id=e.localizacao_id INNER JOIN estados_localizacao el ON el.id=l.estado_localizacao_id INNER JOIN fornecedores f ON f.id=g.fornecedor_id INNER JOIN estados_garantia eg ON eg.id=g.estado_garantia_id ORDER BY g.codigo";
        $garantias = $ligacao->query($sqlGarantias)->fetchAll();
        $sqlContratos = "SELECT c.id,c.codigo,c.designacao,tc.nome AS tipo,COALESCE(GROUP_CONCAT(CONCAT(e.codigo,' — ',e.designacao) ORDER BY e.codigo SEPARATOR ', '),'Sem equipamentos') AS associado_a,f.nome AS fornecedor,c.data_inicio,c.data_fim,CASE WHEN ec.nome IN ('Inválido','Invalido') THEN 'Cancelado' ELSE ec.nome END AS estado,f.estado AS fornecedor_estado,MAX(CASE WHEN ee.nome='Abatido' THEN 1 ELSE 0 END) AS tem_equipamento_abatido,MAX(CASE WHEN el.nome IN ('Inativa','Abatida') THEN 1 ELSE 0 END) AS tem_localizacao_abatida FROM contratos c INNER JOIN tipos_contrato tc ON tc.id=c.tipo_contrato_id INNER JOIN fornecedores f ON f.id=c.fornecedor_id INNER JOIN estados_contrato ec ON ec.id=c.estado_contrato_id LEFT JOIN contrato_equipamentos ce ON ce.contrato_id=c.id LEFT JOIN equipamentos e ON e.id=ce.equipamento_id LEFT JOIN estados_equipamento ee ON ee.id=e.estado_id LEFT JOIN localizacoes l ON l.id=e.localizacao_id LEFT JOIN estados_localizacao el ON el.id=l.estado_localizacao_id GROUP BY c.id,c.codigo,c.designacao,tc.nome,f.nome,c.data_inicio,c.data_fim,ec.nome,f.estado ORDER BY c.codigo";
        $contratos = $ligacao->query($sqlContratos)->fetchAll();
        $indicadores = $ligacao->query("SELECT ((SELECT COUNT(*) FROM garantias)+(SELECT COUNT(*) FROM contratos)) AS total,(SELECT COUNT(*) FROM garantias) AS garantias,(SELECT COUNT(*) FROM contratos) AS contratos,((SELECT COUNT(*) FROM garantias WHERE data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY))+(SELECT COUNT(*) FROM contratos WHERE data_fim BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY))) AS a_expirar")->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $erroBD = 'A ligação foi feita, mas ocorreu um erro ao carregar garantias e contratos.';
        $garantias = [];
        $contratos = [];
    }
}
$ligacao = null;
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php'; ?>
<div class="container-fluid">
    <div class="row"><?php include __DIR__ . '/../../includes/sidebar.php'; ?><main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Garantias e Contratos</h4>
                    <p class="text-muted small mb-0">Gestão separada das garantias dos equipamentos e dos contratos associados.</p>
                </div>
            </div>
            <?php if (isset($_GET['cancelado']) && $_GET['cancelado'] == '1'): ?><div class="alert alert-success"><strong>Contrato cancelado</strong><br>O contrato foi atualizado com sucesso.</div><?php endif; ?><?php if ((isset($_GET['garantia_cancelada']) && $_GET['garantia_cancelada'] == '1') || (isset($_GET['garantia_expirada']) && $_GET['garantia_expirada'] == '1')): ?><div class="alert alert-success"><strong>Garantia cancelada</strong><br>A garantia foi atualizada com estado Cancelado.</div><?php endif; ?><?php if ($erroBD): ?><div class="alert alert-danger"><?php echo e($erroBD); ?></div><?php endif; ?>
            <section class="mb-4">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Total</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['total'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-success-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Garantias</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['garantias'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-info-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">Contratos</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['contratos'] ?? 0); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-dashboard border-warning-dashboard p-3 h-100">
                            <p class="text-muted small mb-1">A expirar</p>
                            <h4 class="fw-bold mb-0"><?php echo e($indicadores['a_expirar'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mb-4">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="col-lg-5"><label for="pesquisaContratosDT" class="form-label">Pesquisar</label><input type="search" class="form-control" id="pesquisaContratosDT" placeholder="Código, designação, fornecedor ou equipamento"></div>
                        <div class="col-md-4 col-lg-3"><label for="filtroTipoContratoDT" class="form-label">Tipo</label><select class="form-select" id="filtroTipoContratoDT">
                                <option value="">Todos</option>
                                <option value="Garantia">Garantia</option><?php foreach ($tiposContrato as $tipo): ?>
                                    <option value="<?php echo e($tipo->nome); ?>"><?php echo e($tipo->nome); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-4 col-lg-2"><label for="filtroFornecedorContratoDT" class="form-label">Fornecedor</label><select class="form-select" id="filtroFornecedorContratoDT">
                                <option value="">Todos</option><?php foreach ($fornecedores as $fornecedor): ?>
                                    <option value="<?php echo e($fornecedor->nome); ?>"><?php echo e($fornecedor->nome); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-4 col-lg-2"><label for="filtroEstadoContratoDT" class="form-label">Estado</label><select class="form-select" id="filtroEstadoContratoDT">
                                <option value="">Todos</option><?php foreach ($estadosContratosGarantias as $estado): ?>
                                    <option value="<?php echo e($estado->nome); ?>"><?php echo e($estado->nome); ?></option><?php endforeach; ?>
                            </select></div>
                    </div>
                </div>
            </section>
            <section class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Garantias</h5>
                    <p class="text-muted small mb-0">Garantias associadas diretamente a equipamentos médicos.</p>
                </div>
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaGarantias">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Tipo</th>
                                    <th>Equipamento</th>
                                    <th>Fornecedor</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach ($garantias as $garantia): ?><?php $equipamentoAbatido = abate($garantia->equipamento_estado); $garantiaCancelada = cancelado($garantia->estado); $bloquear = $equipamentoAbatido || $garantiaCancelada; ?><tr>
                                    <td><?php echo e($garantia->codigo); ?></td>
                                    <td><?php echo e($garantia->designacao); ?><?php if ($equipamentoAbatido): ?><br><span class="badge badge-inativo">Equipamento abatido</span><?php endif; ?></td>
                                    <td><?php echo e($garantia->tipo); ?></td>
                                    <td><?php echo e($garantia->equipamento); ?></td>
                                    <td><?php echo e($garantia->fornecedor); ?></td>
                                    <td><?php echo e($garantia->data_inicio ?? '—'); ?></td>
                                    <td><?php echo e($garantia->data_fim ?? '—'); ?></td>
                                    <td><span class="badge <?php echo badge_estado($garantia->estado); ?>"><?php echo e($garantia->estado); ?></span></td>
                                    <td class="text-center"><a href="garantia-detalhes.php?id_garantia=<?php echo urlencode(aes_encrypt($garantia->id)); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="fa-solid fa-eye"></i></a><?php if (!$bloquear && tem_permissao('contratos', 'remover')): ?> 
                                            <a href="garantia-eliminar.php?id_garantia=<?php echo urlencode(aes_encrypt($garantia->id)); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                            <?php endif; ?></td>
                                </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Contratos</h5>
                    <p class="text-muted small mb-0">Contratos de manutenção, assistência, seguros ou aluguer associados aos equipamentos.</p>
                </div>
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle mb-0 tabela-datatable" id="tabelaContratos">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Designação</th>
                                    <th>Tipo</th>
                                    <th>Associado a</th>
                                    <th>Fornecedor</th>
                                    <th>Início</th>
                                    <th>Fim</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody><?php foreach ($contratos as $contrato): ?><?php $equipamentoAbatido = ((int)$contrato->tem_equipamento_abatido === 1); $contratoCancelado = cancelado($contrato->estado); $bloquear = $equipamentoAbatido || $contratoCancelado; ?><tr>
                                    <td><?php echo e($contrato->codigo); ?></td>
                                    <td><?php echo e($contrato->designacao); ?><?php if ($equipamentoAbatido): ?><br><span class="badge badge-inativo">Equipamento abatido</span><?php endif; ?></td>
                                    <td><?php echo e($contrato->tipo); ?></td>
                                    <td><?php echo e($contrato->associado_a); ?></td>
                                    <td><?php echo e($contrato->fornecedor); ?></td>
                                    <td><?php echo e($contrato->data_inicio ?? '—'); ?></td>
                                    <td><?php echo e($contrato->data_fim ?? '—'); ?></td>
                                    <td><span class="badge <?php echo badge_estado($contrato->estado); ?>"><?php echo e($contrato->estado); ?></span></td>
                                    <td class="text-center"><a href="contrato-detalhes.php?id_contrato=<?php echo urlencode(aes_encrypt($contrato->id)); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Ver detalhes">
                                        <i class="fa-solid fa-eye"></i></a><?php if (!$bloquear && tem_permissao('contratos', 'remover')): ?> <a href="contrato-eliminar.php?id_contrato=<?php echo urlencode(aes_encrypt($contrato->id)); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                                            <?php endif; ?></td>
                                </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div><?php include __DIR__ . '/../../includes/footer.php'; ?>