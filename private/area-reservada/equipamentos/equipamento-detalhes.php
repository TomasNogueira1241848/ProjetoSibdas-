<?php
$pageTitle = 'MedInfo Solutions — Detalhes do Equipamento';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'equipamentos';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();
exigir_permissao('equipamentos', 'ver');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: equipamentos.php');
    exit;
}

function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function mostrar_valor($valor, $vazio = 'Não indicado')
{
    $texto = trim((string) ($valor ?? ''));
    return $texto !== '' ? e($texto) : '<span class="text-muted small">' . e($vazio) . '</span>';
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

function mostrar_observacoes($valor)
{
    return mostrar_valor(limpar_observacoes_sistema($valor));
}

function formatar_data($data)
{
    if (empty($data) || $data === '0000-00-00') {
        return '<span class="text-muted small">Não indicada</span>';
    }

    $timestamp = strtotime((string) $data);
    return $timestamp ? date('d/m/Y', $timestamp) : e($data);
}

function formatar_moeda($valor)
{
    if ($valor === null || $valor === '') {
        return '<span class="text-muted small">Não indicado</span>';
    }

    return number_format((float) $valor, 2, ',', ' ') . ' €';
}

function sim_nao($valor)
{
    return ((int) $valor === 1) ? 'Sim' : 'Não';
}

function badge_estado($estado)
{
    $estado = (string) ($estado ?? '');
    $normalizado = strtolower($estado);
    $classe = 'badge-inativo';

    if ($normalizado === 'ativo' || $normalizado === 'ativa' || $normalizado === 'válido' || $normalizado === 'valido' || $normalizado === 'em dia') {
        $classe = 'badge-ativo';
    } elseif ($normalizado === 'em manutenção' || $normalizado === 'em manutencao' || $normalizado === 'agendada' || $normalizado === 'pendente' || $normalizado === 'por rever' || $normalizado === 'a expirar') {
        $classe = 'badge-manutencao';
    }

    return '<span class="badge ' . $classe . '">' . e($estado !== '' ? $estado : 'Não indicado') . '</span>';
}

function coluna_existe($ligacao, $tabela, $coluna)
{
    $stmt = $ligacao->prepare('
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = :tabela
          AND COLUMN_NAME = :coluna
    ');
    $stmt->execute([':tabela' => $tabela, ':coluna' => $coluna]);
    return (int) $stmt->fetchColumn() > 0;
}

function campo_detalhe($rotulo, $valor, $classe = 'col-md-6 col-xl-4')
{
    echo '<div class="' . e($classe) . '">';
    echo '<p class="text-muted small mb-1">' . e($rotulo) . '</p>';
    echo '<p class="mb-0">' . $valor . '</p>';
    echo '</div>';
}

function link_ficheiro_pdf($ficheiro)
{
    $caminho = trim((string) ($ficheiro->caminho_ficheiro ?? ''));
    if ($caminho === '') {
        return '#';
    }

    if (preg_match('/^https?:\/\//', $caminho)) {
        return $caminho;
    }

    return BASE_URL . '/' . ltrim(str_replace('\\', '/', $caminho), '/');
}

function ficheiro_pdf_existe($ficheiro)
{
    $caminho = trim((string) ($ficheiro->caminho_ficheiro ?? ''));
    if ($caminho === '' || preg_match('/^https?:\/\//', $caminho)) {
        return true;
    }

    $raizProjeto = realpath(__DIR__ . '/../../../');
    if ($raizProjeto === false) {
        return false;
    }

    return is_file($raizProjeto . '/' . ltrim(str_replace('\\', '/', $caminho), '/'));
}

function renderizar_pdfs($ficheiros)
{
    if (empty($ficheiros)) {
        echo '<p class="text-muted small mb-0">Nenhum PDF associado.</p>';
        return;
    }

    echo '<div class="pdf-lista mt-2">';
    foreach ($ficheiros as $ficheiro) {
        $existe = ficheiro_pdf_existe($ficheiro);
        echo '<div class="pdf-item">';
        echo '<div class="d-flex align-items-center gap-2">';
        echo '<i class="fa-solid fa-file-pdf text-danger"></i>';
        if ($existe) {
            echo '<a href="' . e(link_ficheiro_pdf($ficheiro)) . '" target="_blank" rel="noopener">' . e($ficheiro->nome_original ?? 'PDF') . '</a>';
        } else {
            echo '<span>' . e($ficheiro->nome_original ?? 'PDF') . '</span>';
        }
        echo '</div>';
        echo $existe
            ? '<span class="badge text-bg-secondary">PDF</span>'
            : '<span class="badge text-bg-warning">Ficheiro em falta</span>';
        echo '</div>';
    }
    echo '</div>';
}

function agrupar_por_id_principal($linhas, $campo)
{
    $agrupado = [];
    foreach ($linhas as $linha) {
        $id = (int) ($linha->{$campo} ?? 0);
        if (!isset($agrupado[$id])) {
            $agrupado[$id] = [];
        }
        $agrupado[$id][] = $linha;
    }
    return $agrupado;
}


function fornecedor_badge_classe($funcaoNome)
{
    $funcao = strtolower((string) ($funcaoNome ?? ''));

    if (str_contains($funcao, 'principal')) {
        return 'bg-primary';
    }
    if (str_contains($funcao, 'fabricante')) {
        return 'bg-secondary';
    }
    if (str_contains($funcao, 'assistência') || str_contains($funcao, 'assistencia')) {
        return 'bg-info text-dark';
    }
    if (str_contains($funcao, 'consum')) {
        return 'bg-warning text-dark';
    }

    return 'bg-dark';
}

function contrato_ativo_texto($valor)
{
    if ($valor === null || $valor === '') {
        return 'Não indicado';
    }

    return ((int) $valor === 1) ? 'Sim' : 'Não';
}

function link_fornecedor_detalhes($fornecedorId)
{
    if (!$fornecedorId || !is_numeric($fornecedorId)) {
        return '../fornecedores/fornecedores.php';
    }

    return '../fornecedores/fornecedor-detalhes.php?id_fornecedor=' . urlencode(aes_encrypt((int) $fornecedorId));
}

function normalizar_fornecedor_para_card($fornecedor, $funcaoNome = null, $observacoesAssociacao = null)
{
    if (!$fornecedor) {
        return null;
    }

    return (object) [
        'id' => $fornecedor->fornecedor_id ?? $fornecedor->id ?? null,
        'nome' => $fornecedor->nome ?? $fornecedor->fornecedor_nome ?? '',
        'funcao_nome' => $funcaoNome ?? $fornecedor->funcao_nome ?? 'Fornecedor',
        'tipo_fornecedor_nome' => $fornecedor->tipo_fornecedor_nome ?? $fornecedor->fornecedor_tipo ?? '',
        'nif' => $fornecedor->nif ?? $fornecedor->fornecedor_nif ?? '',
        'email' => $fornecedor->email ?? $fornecedor->fornecedor_email ?? '',
        'telefone' => $fornecedor->telefone ?? $fornecedor->fornecedor_telefone ?? '',
        'website' => $fornecedor->website ?? $fornecedor->fornecedor_website ?? '',
        'morada' => $fornecedor->morada ?? $fornecedor->fornecedor_morada ?? '',
        'pessoa_contacto' => $fornecedor->pessoa_contacto ?? $fornecedor->fornecedor_pessoa_contacto ?? '',
        'telefone_contacto' => $fornecedor->telefone_contacto ?? $fornecedor->fornecedor_telefone_contacto ?? '',
        'contrato_ativo' => $fornecedor->contrato_ativo ?? $fornecedor->fornecedor_contrato_ativo ?? null,
        'area_atuacao' => $fornecedor->area_atuacao ?? $fornecedor->fornecedor_area_atuacao ?? '',
        'estado' => $fornecedor->estado ?? $fornecedor->fornecedor_estado ?? '',
        'observacoes' => $observacoesAssociacao ?? $fornecedor->observacoes_associacao ?? $fornecedor->observacoes ?? $fornecedor->fornecedor_observacoes ?? ''
    ];
}

function fornecedor_tem_detalhes($fornecedor)
{
    if (!$fornecedor) {
        return false;
    }

    foreach (['nif', 'email', 'telefone', 'website', 'morada', 'pessoa_contacto', 'telefone_contacto', 'area_atuacao', 'observacoes'] as $campo) {
        if (trim((string) ($fornecedor->{$campo} ?? '')) !== '') {
            return true;
        }
    }

    return false;
}

function renderizar_card_fornecedor($fornecedor, $titulo = null, $classeColuna = 'col-md-6 col-xl-4')
{
    if (!$fornecedor) {
        return;
    }

    $funcao = $fornecedor->funcao_nome ?: 'Fornecedor';
    $titulo = $titulo ?: $funcao;

    echo '<div class="' . e($classeColuna) . '">';
    echo '<div class="border rounded p-3 h-100 bg-light">';
    echo '<div class="d-flex justify-content-between align-items-start gap-2 mb-2">';
    echo '<div>';
    echo '<h6 class="fw-bold mb-1">' . e($titulo) . '</h6>';
    echo '<p class="text-muted small mb-0">' . mostrar_valor($fornecedor->nome, 'Fornecedor não indicado') . '</p>';
    echo '</div>';
    echo '<span class="badge ' . e(fornecedor_badge_classe($funcao)) . '">' . e($funcao) . '</span>';
    echo '</div>';

    echo '<p class="small mb-1"><strong>Tipo:</strong> ' . mostrar_valor($fornecedor->tipo_fornecedor_nome) . '</p>';
    echo '<p class="small mb-1"><strong>NIF:</strong> ' . mostrar_valor($fornecedor->nif) . '</p>';
    echo '<p class="small mb-1"><strong>Email:</strong> ' . mostrar_valor($fornecedor->email) . '</p>';
    echo '<p class="small mb-1"><strong>Contacto:</strong> ' . mostrar_valor($fornecedor->telefone) . '</p>';
    echo '<p class="small mb-1"><strong>Website:</strong> ' . mostrar_valor($fornecedor->website) . '</p>';
    echo '<p class="small mb-1"><strong>Morada:</strong> ' . mostrar_valor($fornecedor->morada) . '</p>';
    echo '<p class="small mb-1"><strong>Área de atuação:</strong> ' . mostrar_valor($fornecedor->area_atuacao) . '</p>';
    echo '<p class="small mb-1"><strong>Contrato ativo:</strong> ' . e(contrato_ativo_texto($fornecedor->contrato_ativo)) . '</p>';
    echo '<p class="small mb-1"><strong>Estado:</strong> ' . mostrar_valor($fornecedor->estado) . '</p>';
    echo '<p class="small mb-1"><strong>Pessoa responsável:</strong> ' . mostrar_valor($fornecedor->pessoa_contacto) . '</p>';
    echo '<p class="small mb-1"><strong>Contacto da pessoa responsável:</strong> ' . mostrar_valor($fornecedor->telefone_contacto) . '</p>';
    echo '<p class="small mb-0"><strong>Observações:</strong> ' . mostrar_valor($fornecedor->observacoes) . '</p>';

    echo '<a href="' . e(link_fornecedor_detalhes($fornecedor->id)) . '" class="btn btn-outline-primary btn-sm mt-3">';
    echo '<i class="fa-solid fa-truck me-1"></i> Ver fornecedor';
    echo '</a>';
    echo '</div>';
    echo '</div>';
}

function primeiro_fornecedor_por_funcao($fornecedores, $funcaoId, $excluirFornecedorId = null)
{
    foreach ($fornecedores as $fornecedor) {
        if ((int) ($fornecedor->funcao_fornecedor_id ?? 0) !== (int) $funcaoId) {
            continue;
        }
        if ($excluirFornecedorId !== null && (int) ($fornecedor->fornecedor_id ?? 0) === (int) $excluirFornecedorId) {
            continue;
        }
        return normalizar_fornecedor_para_card($fornecedor, $fornecedor->funcao_nome ?? null, $fornecedor->observacoes_associacao ?? null);
    }

    return null;
}

$idEquipamentoEncrypted = $_GET['id_equipamento'] ?? null;
$equipamentoId = null;

if ($idEquipamentoEncrypted !== null) {
    $idDesencriptado = aes_decrypt($idEquipamentoEncrypted);
    $equipamentoId = ($idDesencriptado !== false && is_numeric($idDesencriptado)) ? (int) $idDesencriptado : null;
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    /* Compatibilidade com links antigos, caso existam. */
    $equipamentoId = (int) $_GET['id'];
    $idEquipamentoEncrypted = aes_encrypt($equipamentoId);
}

if (!$equipamentoId) {
    header('Location: equipamentos.php');
    exit;
}

$equipamento = null;
$fornecedorPrincipal = null;
$fornecedoresAssociados = [];
$documentos = [];
$ficheirosDocumentos = [];
$garantias = [];
$ficheirosGarantias = [];
$contratos = [];
$ficheirosContratos = [];
$manutencoes = [];
$erroBD = '';

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroBD = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $documentosResponsavel = coluna_existe($ligacao, 'documentos', 'responsavel') ? 'd.responsavel' : 'NULL';
        $garantiasResponsavel = coluna_existe($ligacao, 'garantias', 'responsavel') ? 'g.responsavel' : 'NULL';
        $contratosResponsavel = coluna_existe($ligacao, 'contratos', 'responsavel') ? 'c.responsavel' : 'NULL';
        $contratosPeriodicidade = coluna_existe($ligacao, 'contratos', 'periodicidade') ? 'c.periodicidade' : 'NULL';

        $stmt = $ligacao->prepare('
            SELECT
                e.*,
                cat.nome AS categoria_nome,
                ee.nome AS estado_nome,
                cr.nome AS criticidade_nome,
                te.nome AS tipo_entrada_nome,
                fp.nome AS fornecedor_principal_nome,
                fp.nif AS fornecedor_principal_nif,
                tfp.nome AS fornecedor_principal_tipo,
                fp.email AS fornecedor_principal_email,
                fp.telefone AS fornecedor_principal_telefone,
                fp.website AS fornecedor_principal_website,
                fp.morada AS fornecedor_principal_morada,
                fp.pessoa_contacto AS fornecedor_principal_pessoa_contacto,
                fp.telefone_contacto AS fornecedor_principal_telefone_contacto,
                fp.contrato_ativo AS fornecedor_principal_contrato_ativo,
                fp.area_atuacao AS fornecedor_principal_area_atuacao,
                fp.estado AS fornecedor_principal_estado,
                fp.observacoes AS fornecedor_principal_observacoes,
                l.codigo AS localizacao_codigo,
                l.nome AS localizacao_nome,
                el.nome AS localizacao_estado,
                l.edificio AS localizacao_edificio,
                l.piso_principal AS localizacao_piso_principal,
                l.numero_andares AS localizacao_numero_andares,
                ep.codigo AS equipamento_pai_codigo,
                ep.designacao AS equipamento_pai_designacao
            FROM equipamentos e
            INNER JOIN categorias_equipamento cat ON cat.id = e.categoria_id
            INNER JOIN estados_equipamento ee ON ee.id = e.estado_id
            INNER JOIN criticidades cr ON cr.id = e.criticidade_id
            LEFT JOIN tipos_entrada te ON te.id = e.tipo_entrada_id
            INNER JOIN fornecedores fp ON fp.id = e.fornecedor_principal_id
            INNER JOIN tipos_fornecedor tfp ON tfp.id = fp.tipo_fornecedor_id
            INNER JOIN localizacoes l ON l.id = e.localizacao_id
            INNER JOIN estados_localizacao el ON el.id = l.estado_localizacao_id
            LEFT JOIN equipamentos ep ON ep.id = e.equipamento_pai_id
            WHERE e.id = :id
            LIMIT 1
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $equipamento = $stmt->fetch();

        if (!$equipamento) {
            header('Location: equipamentos.php');
            exit;
        }

        $fornecedorPrincipal = normalizar_fornecedor_para_card((object) [
            'id' => $equipamento->fornecedor_principal_id,
            'nome' => $equipamento->fornecedor_principal_nome,
            'tipo_fornecedor_nome' => $equipamento->fornecedor_principal_tipo,
            'nif' => $equipamento->fornecedor_principal_nif,
            'email' => $equipamento->fornecedor_principal_email,
            'telefone' => $equipamento->fornecedor_principal_telefone,
            'website' => $equipamento->fornecedor_principal_website,
            'morada' => $equipamento->fornecedor_principal_morada,
            'pessoa_contacto' => $equipamento->fornecedor_principal_pessoa_contacto,
            'telefone_contacto' => $equipamento->fornecedor_principal_telefone_contacto,
            'contrato_ativo' => $equipamento->fornecedor_principal_contrato_ativo,
            'area_atuacao' => $equipamento->fornecedor_principal_area_atuacao,
            'estado' => $equipamento->fornecedor_principal_estado,
            'observacoes' => $equipamento->fornecedor_principal_observacoes
        ], 'Fornecedor principal');

        $stmt = $ligacao->prepare('
            SELECT
                ef.*,
                ef.observacoes AS observacoes_associacao,
                f.id AS fornecedor_id,
                f.nome AS fornecedor_nome,
                f.nif AS fornecedor_nif,
                tf.nome AS tipo_fornecedor_nome,
                f.email AS fornecedor_email,
                f.telefone AS fornecedor_telefone,
                f.website AS fornecedor_website,
                f.morada AS fornecedor_morada,
                f.pessoa_contacto AS fornecedor_pessoa_contacto,
                f.telefone_contacto AS fornecedor_telefone_contacto,
                f.contrato_ativo AS fornecedor_contrato_ativo,
                f.area_atuacao AS fornecedor_area_atuacao,
                f.estado AS fornecedor_estado,
                f.observacoes AS fornecedor_observacoes,
                ff.nome AS funcao_nome
            FROM equipamento_fornecedores ef
            INNER JOIN fornecedores f ON f.id = ef.fornecedor_id
            INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id
            INNER JOIN funcoes_fornecedor ff ON ff.id = ef.funcao_fornecedor_id
            WHERE ef.equipamento_id = :id
            ORDER BY ff.id, f.nome
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $fornecedoresAssociados = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT
                d.*,
                td.nome AS tipo_documento_nome,
                ad.nome AS area_documento_nome,
                ed.nome AS estado_documento_nome,
                f.nome AS fornecedor_nome,
                ' . $documentosResponsavel . ' AS responsavel_documento
            FROM documentos d
            INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id
            INNER JOIN areas_documento ad ON ad.id = d.area_documento_id
            INNER JOIN estados_documento ed ON ed.id = d.estado_documento_id
            LEFT JOIN fornecedores f ON f.id = d.fornecedor_id
            WHERE d.equipamento_id = :id
            ORDER BY d.obrigatorio DESC, td.nome ASC, d.id ASC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $documentos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT df.documento_id, fp.*
            FROM documento_ficheiros df
            INNER JOIN documentos d ON d.id = df.documento_id
            INNER JOIN ficheiros_pdf fp ON fp.id = df.ficheiro_id
            WHERE d.equipamento_id = :id
            ORDER BY fp.carregado_em DESC, fp.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $ficheirosDocumentos = agrupar_por_id_principal($stmt->fetchAll(), 'documento_id');

        $stmt = $ligacao->prepare('
            SELECT
                g.*,
                f.nome AS fornecedor_nome,
                eg.nome AS estado_garantia_nome,
                c.codigo AS contrato_codigo,
                c.designacao AS contrato_designacao,
                ' . $garantiasResponsavel . ' AS responsavel_garantia
            FROM garantias g
            INNER JOIN fornecedores f ON f.id = g.fornecedor_id
            INNER JOIN estados_garantia eg ON eg.id = g.estado_garantia_id
            LEFT JOIN contratos c ON c.id = g.contrato_id
            WHERE g.equipamento_id = :id
            ORDER BY g.data_fim DESC, g.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $garantias = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT gf.garantia_id, fp.*
            FROM garantia_ficheiros gf
            INNER JOIN garantias g ON g.id = gf.garantia_id
            INNER JOIN ficheiros_pdf fp ON fp.id = gf.ficheiro_id
            WHERE g.equipamento_id = :id
            ORDER BY fp.carregado_em DESC, fp.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $ficheirosGarantias = agrupar_por_id_principal($stmt->fetchAll(), 'garantia_id');

        $stmt = $ligacao->prepare('
            SELECT
                c.*,
                tc.nome AS tipo_contrato_nome,
                f.nome AS fornecedor_nome,
                ec.nome AS estado_contrato_nome,
                ' . $contratosResponsavel . ' AS responsavel_contrato,
                ' . $contratosPeriodicidade . ' AS periodicidade_contrato
            FROM contratos c
            INNER JOIN contrato_equipamentos ce ON ce.contrato_id = c.id
            INNER JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
            INNER JOIN fornecedores f ON f.id = c.fornecedor_id
            INNER JOIN estados_contrato ec ON ec.id = c.estado_contrato_id
            WHERE ce.equipamento_id = :id
            ORDER BY (tc.nome = "Manutenção") DESC, c.data_fim DESC, c.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $contratos = $stmt->fetchAll();

        $stmt = $ligacao->prepare('
            SELECT cf.contrato_id, fp.*
            FROM contrato_ficheiros cf
            INNER JOIN contrato_equipamentos ce ON ce.contrato_id = cf.contrato_id
            INNER JOIN ficheiros_pdf fp ON fp.id = cf.ficheiro_id
            WHERE ce.equipamento_id = :id
            ORDER BY fp.carregado_em DESC, fp.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $ficheirosContratos = agrupar_por_id_principal($stmt->fetchAll(), 'contrato_id');

        $stmt = $ligacao->prepare('
            SELECT
                m.*,
                tm.nome AS tipo_manutencao_nome,
                em.nome AS estado_manutencao_nome,
                pm.nome AS prioridade_nome
            FROM manutencoes m
            INNER JOIN tipos_manutencao tm ON tm.id = m.tipo_manutencao_id
            INNER JOIN estados_manutencao em ON em.id = m.estado_manutencao_id
            INNER JOIN prioridades_manutencao pm ON pm.id = m.prioridade_id
            WHERE m.equipamento_id = :id
            ORDER BY m.proxima_manutencao DESC, m.id DESC
        ');
        $stmt->execute([':id' => $equipamentoId]);
        $manutencoes = $stmt->fetchAll();
    } catch (PDOException $erro) {
        $erroBD = 'Ocorreu um erro ao carregar os detalhes do equipamento: ' . $erro->getMessage();
    }
}

$ligacao = null;
$equipamentoAbatido = $equipamento && strtolower((string) ($equipamento->estado_nome ?? '')) === 'abatido';

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/nav.php';
?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-4 overflow-hidden" id="dashboard">

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Detalhes do equipamento</h4>
                    <p class="text-muted small mb-0">Consulta dos dados principais e associações do equipamento selecionado.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <?php if (!$equipamentoAbatido && tem_permissao('equipamentos', 'editar')): ?>
                        <a href="equipamento-editar.php?id_equipamento=<?php echo urlencode($idEquipamentoEncrypted); ?>" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-pen me-1"></i> Editar
                        </a>
                    <?php endif; ?>

                    <?php if (!$equipamentoAbatido && tem_permissao('equipamentos', 'remover')): ?>
                        <a href="equipamento-eliminar.php?id_equipamento=<?php echo urlencode($idEquipamentoEncrypted); ?>" class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-trash me-1"></i> Abater
                        </a>
                    <?php endif; ?>

                    <a href="equipamentos.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <?php if ($equipamentoAbatido): ?>
                <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>
                    <div>
                        <strong class="d-block">Equipamento abatido</strong>
                        <span>Este equipamento foi retirado do inventário ativo. Os dados e documentos ficam apenas disponíveis para consulta.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($erroBD !== ''): ?>
                <?php mostrar_alerta_erro_base_dados($erroBD); ?>
            <?php elseif ($equipamento): ?>

                <section class="mb-4">
                    <div class="card p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($equipamento->designacao); ?></h5>
                                <p class="text-muted small mb-0">Código: <?php echo e($equipamento->codigo); ?></p>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-start">
                                <?php echo badge_estado($equipamento->estado_nome); ?>
                                <span class="badge bg-warning text-dark"><?php echo e($equipamento->criticidade_nome); ?></span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="card p-4">

                        <ul class="nav nav-pills abas-equipamento mb-4" id="abasDetalhesEquipamento" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="detalhes-dados-tab" data-bs-toggle="tab" data-bs-target="#detalhes-dados" type="button" role="tab">
                                    <span class="aba-numero">1</span><span><strong>Dados</strong><small>Informação principal</small></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalhes-localizacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-localizacao" type="button" role="tab">
                                    <span class="aba-numero">2</span><span><strong>Localização</strong><small>Serviço e sala</small></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalhes-fornecedor-tab" data-bs-toggle="tab" data-bs-target="#detalhes-fornecedor" type="button" role="tab">
                                    <span class="aba-numero">3</span><span><strong>Fornecedores</strong><small>Principal e associados</small></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalhes-documentacao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-documentacao" type="button" role="tab">
                                    <span class="aba-numero">4</span><span><strong>Documentação</strong><small>PDFs técnicos</small></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalhes-garantia-tab" data-bs-toggle="tab" data-bs-target="#detalhes-garantia" type="button" role="tab">
                                    <span class="aba-numero">5</span><span><strong>Garantia e contrato</strong><small>Garantias, contratos e PDFs</small></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalhes-manutencao-tab" data-bs-toggle="tab" data-bs-target="#detalhes-manutencao" type="button" role="tab">
                                    <span class="aba-numero">6</span><span><strong>Manutenção</strong><small>Preventiva</small></span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="detalhes-dados" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Dados do Equipamento</h6>
                                        <p class="text-muted small mb-0">Consulta da identificação, características e entidades associadas ao equipamento.</p>
                                    </div>
                                    <?php
                                    campo_detalhe('Designação', mostrar_valor($equipamento->designacao));
                                    campo_detalhe('Categoria', mostrar_valor($equipamento->categoria_nome));
                                    campo_detalhe('Marca', mostrar_valor($equipamento->marca));
                                    campo_detalhe('Modelo', mostrar_valor($equipamento->modelo));
                                    campo_detalhe('N.º de série', mostrar_valor($equipamento->numero_serie));
                                    campo_detalhe('Estado', badge_estado($equipamento->estado_nome));
                                    campo_detalhe('Data de aquisição', formatar_data($equipamento->data_aquisicao));
                                    campo_detalhe('Custo de aquisição', formatar_moeda($equipamento->custo_aquisicao));
                                    campo_detalhe('Ano de fabrico', mostrar_valor($equipamento->ano_fabrico));
                                    campo_detalhe('Tipo de entrada', mostrar_valor($equipamento->tipo_entrada_nome));
                                    campo_detalhe('Criticidade', '<span class="badge bg-warning text-dark">' . e($equipamento->criticidade_nome) . '</span>');
                                    campo_detalhe('Observações', mostrar_valor($equipamento->observacoes), 'col-12');
                                    ?>

                                    <div class="col-12">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-3">Relações e consumíveis</h6>
                                    </div>
                                    <?php
                                    campo_detalhe('É componente de outro equipamento?', $equipamento->equipamento_pai_id ? 'Sim' : 'Não');
                                    $pai = $equipamento->equipamento_pai_id ? e($equipamento->equipamento_pai_codigo . ' — ' . $equipamento->equipamento_pai_designacao) : '<span class="text-muted small">Não aplicável</span>';
                                    campo_detalhe('Equipamento principal', $pai);
                                    campo_detalhe('Tem consumíveis?', sim_nao($equipamento->tem_consumiveis));
                                    campo_detalhe('Consumíveis associados', mostrar_valor($equipamento->consumiveis_descricao), 'col-12');
                                    ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="detalhes-localizacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Localização</h6>
                                        <p class="text-muted small mb-0">Consulta da localização física do equipamento.</p>
                                    </div>
                                    <?php
                                    campo_detalhe('Código da localização', mostrar_valor($equipamento->localizacao_codigo));
                                    campo_detalhe('Localização', mostrar_valor($equipamento->localizacao_nome));
                                    campo_detalhe('Estado da localização', mostrar_valor($equipamento->localizacao_estado));
                                    campo_detalhe('Edifício', mostrar_valor($equipamento->localizacao_edificio));
                                    campo_detalhe('Número de andares', mostrar_valor($equipamento->localizacao_numero_andares));
                                    campo_detalhe('Piso principal', mostrar_valor($equipamento->localizacao_piso_principal));
                                    campo_detalhe('Serviço / Departamento', mostrar_valor($equipamento->servico));
                                    campo_detalhe('Piso / Andar', mostrar_valor($equipamento->piso));
                                    campo_detalhe('Sala / Gabinete', mostrar_valor($equipamento->sala));
                                    ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="detalhes-fornecedor" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Entidades associadas ao equipamento</h6>
                                        <p class="text-muted small mb-0">
                                            Consulta do fornecedor principal, fabricante, prestador de assistência técnica e restantes fornecedores associados.
                                        </p>
                                    </div>

                                    <?php
                                    $fabricantePrincipal = primeiro_fornecedor_por_funcao($fornecedoresAssociados, 2);
                                    $prestadorAssistencia = primeiro_fornecedor_por_funcao($fornecedoresAssociados, 4);
                                    $idsCardsPrincipais = array_filter([
                                        (int) ($fornecedorPrincipal->id ?? 0),
                                        (int) ($fabricantePrincipal->id ?? 0),
                                        (int) ($prestadorAssistencia->id ?? 0)
                                    ]);

                                    renderizar_card_fornecedor($fornecedorPrincipal, 'Fornecedor principal');

                                    if ($fabricantePrincipal) {
                                        renderizar_card_fornecedor($fabricantePrincipal, 'Fabricante principal');
                                    } else {
                                        echo '<div class="col-md-6 col-xl-4"><div class="alert alert-light border h-100 mb-0"><strong>Fabricante principal</strong><br><span class="text-muted small">Sem fabricante associado.</span></div></div>';
                                    }

                                    if ($prestadorAssistencia) {
                                        renderizar_card_fornecedor($prestadorAssistencia, 'Prestador de assistência técnica principal');
                                    } else {
                                        echo '<div class="col-md-6 col-xl-4"><div class="alert alert-light border h-100 mb-0"><strong>Prestador de assistência técnica principal</strong><br><span class="text-muted small">Sem prestador de assistência associado.</span></div></div>';
                                    }
                                    ?>

                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Fornecedores associados adicionais</h6>
                                        <p class="text-muted small mb-0">
                                            Outros fornecedores, distribuidores comerciais ou entidades de consumíveis associados ao equipamento.
                                        </p>
                                    </div>

                                    <?php
                                    $temFornecedoresAdicionais = false;
                                    foreach ($fornecedoresAssociados as $fornecedorAssociado) {
                                        $funcaoId = (int) ($fornecedorAssociado->funcao_fornecedor_id ?? 0);
                                        $fornecedorId = (int) ($fornecedorAssociado->fornecedor_id ?? 0);

                                        if (in_array($funcaoId, [1, 2, 4], true) && in_array($fornecedorId, $idsCardsPrincipais, true)) {
                                            continue;
                                        }

                                        $temFornecedoresAdicionais = true;
                                        renderizar_card_fornecedor(
                                            normalizar_fornecedor_para_card($fornecedorAssociado, $fornecedorAssociado->funcao_nome ?? null, $fornecedorAssociado->observacoes_associacao ?? null),
                                            $fornecedorAssociado->fornecedor_nome ?? 'Fornecedor associado'
                                        );
                                    }

                                    if (!$temFornecedoresAdicionais) {
                                        echo '<div class="col-12"><div class="alert alert-light border mb-0">Sem fornecedores adicionais associados.</div></div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="detalhes-documentacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Documentação</h6>
                                        <p class="text-muted small mb-0">Consulta dos documentos e PDFs associados ao equipamento.</p>
                                    </div>

                                    <div class="col-12">
                                        <?php if (empty($documentos)): ?>
                                            <div class="alert alert-light border mb-0">Sem documentos associados.</div>
                                        <?php else: ?>
                                            <?php foreach ($documentos as $documento): ?>
                                                <div class="card border mb-3 p-3">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                                        <div>
                                                            <h6 class="fw-bold mb-1"><?php echo e($documento->titulo); ?></h6>
                                                            <p class="text-muted small mb-0"><?php echo e($documento->codigo); ?> · <?php echo e($documento->tipo_documento_nome); ?></p>
                                                        </div>
                                                        <div class="d-flex gap-2 align-items-start">
                                                            <?php if ((int) $documento->obrigatorio === 1): ?>
                                                                <span class="badge text-bg-primary">Obrigatório</span>
                                                            <?php else: ?>
                                                                <span class="badge text-bg-info">Adicional</span>
                                                            <?php endif; ?>
                                                            <?php echo badge_estado($documento->estado_documento_nome); ?>
                                                            <?php if ($equipamentoAbatido): ?>
                                                                <span class="badge text-bg-warning">Equipamento abatido</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <?php
                                                        campo_detalhe('Área', mostrar_valor($documento->area_documento_nome));
                                                        campo_detalhe('Fornecedor', mostrar_valor($documento->fornecedor_nome));
                                                        campo_detalhe('Responsável', mostrar_valor($documento->responsavel_documento));
                                                        campo_detalhe('Data do documento', formatar_data($documento->data_documento));
                                                        campo_detalhe('Validade', formatar_data($documento->validade));
                                                        campo_detalhe('Observações', mostrar_observacoes($documento->observacoes), 'col-12');
                                                        ?>
                                                        <div class="col-12">
                                                            <p class="text-muted small mb-1">PDFs associados</p>
                                                            <?php renderizar_pdfs($ficheirosDocumentos[(int) $documento->id] ?? []); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="detalhes-garantia" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Garantias</h6>
                                        <p class="text-muted small mb-0">Consulta das garantias associadas ao equipamento.</p>
                                    </div>

                                    <div class="col-12">
                                        <?php if (empty($garantias)): ?>
                                            <div class="alert alert-light border mb-0">Sem garantias associadas.</div>
                                        <?php else: ?>
                                            <?php foreach ($garantias as $garantia): ?>
                                                <div class="card border mb-3 p-3">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                                        <div>
                                                            <h6 class="fw-bold mb-1"><?php echo e($garantia->designacao); ?></h6>
                                                            <p class="text-muted small mb-0"><?php echo e($garantia->codigo); ?></p>
                                                        </div>
                                                        <?php echo badge_estado($garantia->estado_garantia_nome); ?>
                                                    </div>
                                                    <div class="row g-3">
                                                        <?php
                                                        campo_detalhe('Equipamento associado', 'Equipamento atual');
                                                        campo_detalhe('Fornecedor', mostrar_valor($garantia->fornecedor_nome));
                                                        campo_detalhe('Responsável', mostrar_valor($garantia->responsavel_garantia));
                                                        campo_detalhe('Contrato associado', $garantia->contrato_codigo ? e($garantia->contrato_codigo . ' — ' . $garantia->contrato_designacao) : '<span class="text-muted small">Sem contrato associado</span>');
                                                        campo_detalhe('Data de início', formatar_data($garantia->data_inicio));
                                                        campo_detalhe('Data de fim', formatar_data($garantia->data_fim));
                                                        campo_detalhe('Cobertura', mostrar_valor($garantia->cobertura), 'col-12');
                                                        campo_detalhe('Observações', mostrar_observacoes($garantia->observacoes), 'col-12');
                                                        ?>
                                                        <div class="col-12">
                                                            <p class="text-muted small mb-1">PDFs associados</p>
                                                            <?php renderizar_pdfs($ficheirosGarantias[(int) $garantia->id] ?? []); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-12">
                                        <hr>
                                        <h6 class="fw-bold mb-1">Contratos</h6>
                                        <p class="text-muted small mb-3">Consulta dos contratos de manutenção e contratos adicionais.</p>

                                        <?php if (empty($contratos)): ?>
                                            <div class="alert alert-light border mb-0">Sem contratos associados.</div>
                                        <?php else: ?>
                                            <?php foreach ($contratos as $contrato): ?>
                                                <div class="card border mb-3 p-3">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                                        <div>
                                                            <h6 class="fw-bold mb-1"><?php echo e($contrato->designacao); ?></h6>
                                                            <p class="text-muted small mb-0"><?php echo e($contrato->codigo); ?> · <?php echo e($contrato->tipo_contrato_nome); ?></p>
                                                        </div>
                                                        <?php echo badge_estado($contrato->estado_contrato_nome); ?>
                                                    </div>
                                                    <div class="row g-3">
                                                        <?php
                                                        campo_detalhe('Associado a', 'Equipamento atual');
                                                        campo_detalhe('Fornecedor', mostrar_valor($contrato->fornecedor_nome));
                                                        campo_detalhe('Responsável', mostrar_valor($contrato->responsavel_contrato));
                                                        campo_detalhe('Data de início', formatar_data($contrato->data_inicio));
                                                        campo_detalhe('Data de fim', formatar_data($contrato->data_fim));
                                                        campo_detalhe('Valor anual', formatar_moeda($contrato->valor_anual));
                                                        campo_detalhe('Periodicidade', mostrar_valor($contrato->periodicidade_contrato));
                                                        campo_detalhe('Renovação automática', sim_nao($contrato->renovacao_automatica));
                                                        campo_detalhe('Observações', mostrar_observacoes($contrato->observacoes), 'col-12');
                                                        ?>
                                                        <div class="col-12">
                                                            <p class="text-muted small mb-1">PDFs associados</p>
                                                            <?php renderizar_pdfs($ficheirosContratos[(int) $contrato->id] ?? []); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="detalhes-manutencao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Manutenção</h6>
                                        <p class="text-muted small mb-0">Consulta do plano e histórico de manutenção registado.</p>
                                    </div>

                                    <div class="col-12">
                                        <?php if (empty($manutencoes)): ?>
                                            <div class="alert alert-light border mb-0">Sem manutenção registada.</div>
                                        <?php else: ?>
                                            <?php foreach ($manutencoes as $manutencao): ?>
                                                <div class="card border mb-3 p-3">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                                        <div>
                                                            <h6 class="fw-bold mb-1"><?php echo e($manutencao->tipo_manutencao_nome); ?></h6>
                                                            <p class="text-muted small mb-0">Registo de manutenção</p>
                                                        </div>
                                                        <?php echo badge_estado($manutencao->estado_manutencao_nome); ?>
                                                    </div>
                                                    <div class="row g-3">
                                                        <?php
                                                        campo_detalhe('Última manutenção', formatar_data($manutencao->ultima_manutencao));
                                                        campo_detalhe('Próxima manutenção', formatar_data($manutencao->proxima_manutencao));
                                                        campo_detalhe('Periodicidade', mostrar_valor($manutencao->periodicidade));
                                                        campo_detalhe('Responsável', mostrar_valor($manutencao->responsavel));
                                                        campo_detalhe('Prioridade', mostrar_valor($manutencao->prioridade_nome));
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>