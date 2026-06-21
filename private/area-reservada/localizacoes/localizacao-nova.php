<?php
$pageTitle = 'MedInfo Solutions — Nova Localização';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'localizacoes';
$pageScript = '';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();

$erros = [];
$erroSistema = '';
$proximoCodigoLocalizacao = 'LOC-001';

$tiposLocalizacao = [];
$estadosLocalizacao = [];

$valores = [
    'codigoLocalizacao' => '',
    'nomeLocalizacao' => '',
    'tipoLocalizacao' => '',
    'edificioLocalizacao' => '',
    'pisoPrincipalLocalizacao' => '',
    'numeroAndaresLocalizacao' => '',
    'estadoLocalizacao' => '',
    'responsavelLocalizacao' => '',
    'telefoneLocalizacao' => '',
    'descricaoLocalizacao' => ''
];

function valor_localizacao($campo, $valores)
{
    return htmlspecialchars($valores[$campo] ?? '');
}

/* Proximo codigo sequencial de localizacao (ex: LOC-003 -> LOC-004). */
function proximo_codigo_localizacao($ligacao, $largura = 3)
{
    $inicial = 'LOC-' . str_pad('1', $largura, '0', STR_PAD_LEFT);
    if ($ligacao === null) {
        return $inicial;
    }
    try {
        $stmt = $ligacao->prepare("SELECT MAX(CAST(SUBSTRING(codigo, 5) AS UNSIGNED)) FROM localizacoes WHERE codigo LIKE 'LOC-%'");
        $stmt->execute();
        $maximo = (int) $stmt->fetchColumn();
    } catch (PDOException $erro) {
        return $inicial;
    }
    return 'LOC-' . str_pad((string) ($maximo + 1), $largura, '0', STR_PAD_LEFT);
}

function selecionado_localizacao($valor, $valorAtual)
{
    return (string) $valor === (string) $valorAtual ? 'selected' : '';
}

function validar_id_localizacao_existente($ligacao, $tabela, $id)
{
    $tabelasPermitidas = ['tipos_localizacao', 'estados_localizacao'];

    if (!in_array($tabela, $tabelasPermitidas, true)) {
        return false;
    }

    $stmt = $ligacao->prepare("SELECT id FROM {$tabela} WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    return (bool) $stmt->fetch();
}

$ligacao = ligar_base_dados();

if ($ligacao === null) {
    $erroSistema = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $tiposLocalizacao = $ligacao
            ->query('SELECT id, nome FROM tipos_localizacao ORDER BY nome')
            ->fetchAll();

        $estadosLocalizacao = $ligacao
            ->query('SELECT id, nome FROM estados_localizacao ORDER BY id')
            ->fetchAll();

        $proximoCodigoLocalizacao = proximo_codigo_localizacao($ligacao);
    } catch (PDOException $erro) {
        $erroSistema = 'Ocorreu um erro ao carregar os dados do formulário.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($valores as $campo => $valorDefeito) {
        $valores[$campo] = trim($_POST[$campo] ?? $valorDefeito);
    }

    /* Normalização dos dados principais, tal como no equipamento-novo.php */
    $valores['codigoLocalizacao'] = strtoupper($valores['codigoLocalizacao']);
    $valores['nomeLocalizacao'] = ucwords(strtolower($valores['nomeLocalizacao']));
    $valores['edificioLocalizacao'] = ucwords(strtolower($valores['edificioLocalizacao']));
    $valores['responsavelLocalizacao'] = ucwords(strtolower($valores['responsavelLocalizacao']));

    /* Validações em PHP */
    if ($valores['codigoLocalizacao'] === '') {
        $erros[] = 'O código da localização é obrigatório.';
    } elseif (!preg_match('/^LOC-\d{3,}$/', $valores['codigoLocalizacao'])) {
        $erros[] = 'O código deve seguir o formato LOC-000 (ex: LOC-001).';
    }

    if ($valores['nomeLocalizacao'] === '') {
        $erros[] = 'O nome da localização é obrigatório.';
    }

    if ($valores['tipoLocalizacao'] === '') {
        $erros[] = 'Selecione o tipo de localização.';
    } elseif (!ctype_digit($valores['tipoLocalizacao'])) {
        $erros[] = 'O tipo de localização selecionado não é válido.';
    }

    if ($valores['edificioLocalizacao'] === '') {
        $erros[] = 'O edifício é obrigatório.';
    }

    if ($valores['pisoPrincipalLocalizacao'] === '') {
        $erros[] = 'O piso principal é obrigatório.';
    } elseif (!preg_match('/^-?\d+$/', $valores['pisoPrincipalLocalizacao'])) {
        $erros[] = 'O piso principal deve ser um número inteiro.';
    }

    if ($valores['numeroAndaresLocalizacao'] === '') {
        $erros[] = 'O número de andares é obrigatório.';
    } elseif (!ctype_digit($valores['numeroAndaresLocalizacao']) || (int) $valores['numeroAndaresLocalizacao'] < 1) {
        $erros[] = 'O número de andares deve ser um número igual ou superior a 1.';
    }

    if ($valores['estadoLocalizacao'] === '') {
        $erros[] = 'Selecione o estado da localização.';
    } elseif (!ctype_digit($valores['estadoLocalizacao'])) {
        $erros[] = 'O estado da localização selecionado não é válido.';
    }

    if ($valores['responsavelLocalizacao'] === '') {
        $erros[] = 'O responsável é obrigatório.';
    } elseif (preg_match('/\d/', $valores['responsavelLocalizacao'])) {
        $erros[] = 'O responsável não deve conter números.';
    }

    $contactoLocalizacao = preg_replace('/[\s.\-]/', '', $valores['telefoneLocalizacao']);

    if ($valores['telefoneLocalizacao'] === '') {
        $erros[] = 'O contacto é obrigatório.';
    } elseif (!preg_match('/^[239]\d{8}$/', $contactoLocalizacao)) {
        $erros[] = 'O contacto deve ter 9 dígitos e começar por 2, 3 ou 9.';
    }

    if ($valores['descricaoLocalizacao'] === '') {
        $erros[] = 'A descrição é obrigatória.';
    }

    /*
     * Coerência: o piso principal não pode ser superior ao número de andares.
     * (Só se ambos forem números válidos; o piso pode ser 0 = rés-do-chão.)
     */
    if (preg_match('/^-?\d+$/', $valores['pisoPrincipalLocalizacao'])
        && ctype_digit($valores['numeroAndaresLocalizacao'])
        && (int) $valores['pisoPrincipalLocalizacao'] > (int) $valores['numeroAndaresLocalizacao']) {
        $erros[] = 'O piso principal não pode ser superior ao número de andares.';
    }

    $tipoLocalizacaoId = null;
    $estadoLocalizacaoId = null;

    if ($ligacao !== null) {
        if ($valores['tipoLocalizacao'] !== '' && ctype_digit($valores['tipoLocalizacao'])) {
            $tipoLocalizacaoId = (int) $valores['tipoLocalizacao'];

            if (!validar_id_localizacao_existente($ligacao, 'tipos_localizacao', $tipoLocalizacaoId)) {
                $erros[] = 'O tipo de localização selecionado não existe na base de dados.';
            }
        }

        if ($valores['estadoLocalizacao'] !== '' && ctype_digit($valores['estadoLocalizacao'])) {
            $estadoLocalizacaoId = (int) $valores['estadoLocalizacao'];

            if (!validar_id_localizacao_existente($ligacao, 'estados_localizacao', $estadoLocalizacaoId)) {
                $erros[] = 'O estado da localização selecionado não existe na base de dados.';
            }
        }
    }

    if (empty($erros) && $ligacao !== null) {
        try {
            $sql = "
                INSERT INTO localizacoes (
                    codigo,
                    nome,
                    tipo_localizacao_id,
                    numero_andares,
                    edificio,
                    piso_principal,
                    responsavel,
                    telefone,
                    descricao,
                    estado_localizacao_id
                ) VALUES (
                    :codigo,
                    :nome,
                    :tipo_localizacao_id,
                    :numero_andares,
                    :edificio,
                    :piso_principal,
                    :responsavel,
                    :telefone,
                    :descricao,
                    :estado_localizacao_id
                )
            ";

            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':codigo' => $valores['codigoLocalizacao'],
                ':nome' => $valores['nomeLocalizacao'],
                ':tipo_localizacao_id' => $tipoLocalizacaoId,
                ':numero_andares' => (int) $valores['numeroAndaresLocalizacao'],
                ':edificio' => $valores['edificioLocalizacao'],
                ':piso_principal' => $valores['pisoPrincipalLocalizacao'],
                ':responsavel' => $valores['responsavelLocalizacao'],
                ':telefone' => $valores['telefoneLocalizacao'],
                ':descricao' => $valores['descricaoLocalizacao'],
                ':estado_localizacao_id' => $estadoLocalizacaoId
            ]);

            header('Location: localizacoes.php?sucesso=1');
            exit;
        } catch (PDOException $erro) {
            if ($erro->getCode() === '23000') {
                $erroSistema = 'Não foi possível guardar: já existe uma localização com esse código.';
            } else {
                $erroSistema = 'Erro ao gravar os dados: ' . $erro->getMessage();
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
                    <h4 class="fw-bold mb-1">Nova localização</h4>
                    <p class="text-muted small mb-0">Registo de uma nova localização hospitalar.</p>
                </div>
            </div>

            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Foram encontrados os seguintes erros:</strong>
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro): ?>
                            <li><?php echo htmlspecialchars($erro); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($erroSistema !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Erro:</strong>
                    <p class="mb-0"><?php echo htmlspecialchars($erroSistema); ?></p>
                </div>
            <?php endif; ?>

            <section class="mb-4">
                <div class="card p-4">
                    <form id="formLocalizacao" action="#" method="post" novalidate>
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="codigoLocalizacao" class="form-label">Código</label>
                                <input type="text" class="form-control" id="codigoLocalizacao" name="codigoLocalizacao" placeholder="Ex: LOC-001"
                                    value="<?php echo htmlspecialchars($valores['codigoLocalizacao'] !== '' ? $valores['codigoLocalizacao'] : $proximoCodigoLocalizacao); ?>" required>
                                <div class="invalid-feedback">Introduza o código da localização.</div>
                            </div>

                            <div class="col-md-8">
                                <label for="nomeLocalizacao" class="form-label">Nome da localização</label>
                                <input type="text" class="form-control" id="nomeLocalizacao" name="nomeLocalizacao"
                                    value="<?php echo valor_localizacao('nomeLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o nome da localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="tipoLocalizacao" class="form-label">Tipo de localização</label>
                                <select class="form-select" id="tipoLocalizacao" name="tipoLocalizacao" required>
                                    <option value="">Selecionar</option>
                                    <?php foreach ($tiposLocalizacao as $tipoLocalizacao): ?>
                                        <option value="<?php echo htmlspecialchars($tipoLocalizacao->id); ?>" <?php echo selecionado_localizacao($tipoLocalizacao->id, $valores['tipoLocalizacao']); ?>>
                                            <?php echo htmlspecialchars($tipoLocalizacao->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Selecione o tipo de localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="edificioLocalizacao" class="form-label">Edifício</label>
                                <input type="text" class="form-control" id="edificioLocalizacao"
                                    name="edificioLocalizacao" value="<?php echo valor_localizacao('edificioLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o edifício da localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="pisoPrincipalLocalizacao" class="form-label">Piso principal</label>
                                <input type="number" class="form-control" id="pisoPrincipalLocalizacao"
                                    name="pisoPrincipalLocalizacao" min="-1" step="1"
                                    value="<?php echo valor_localizacao('pisoPrincipalLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o piso principal.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="numeroAndaresLocalizacao" class="form-label">N.º de andares</label>
                                <input type="number" class="form-control" id="numeroAndaresLocalizacao"
                                    name="numeroAndaresLocalizacao" min="1" step="1"
                                    value="<?php echo valor_localizacao('numeroAndaresLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o número de andares.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="estadoLocalizacao" class="form-label">Estado</label>
                                <select class="form-select" id="estadoLocalizacao" name="estadoLocalizacao" required>
                                    <option value="">Selecionar</option>
                                    <?php foreach ($estadosLocalizacao as $estadoLocalizacao): ?>
                                        <option value="<?php echo htmlspecialchars($estadoLocalizacao->id); ?>" <?php echo selecionado_localizacao($estadoLocalizacao->id, $valores['estadoLocalizacao']); ?>>
                                            <?php echo htmlspecialchars($estadoLocalizacao->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Selecione o estado da localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="responsavelLocalizacao" class="form-label">Responsável</label>
                                <input type="text" class="form-control" id="responsavelLocalizacao"
                                    name="responsavelLocalizacao" value="<?php echo valor_localizacao('responsavelLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o responsável pela localização.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="telefoneLocalizacao" class="form-label">Contacto</label>
                                <input type="text" class="form-control" id="telefoneLocalizacao"
                                    name="telefoneLocalizacao" placeholder="Ex: 912345678 ou 212345678" value="<?php echo valor_localizacao('telefoneLocalizacao', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o contacto.</div>
                            </div>

                            <div class="col-12">
                                <label for="descricaoLocalizacao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricaoLocalizacao" name="descricaoLocalizacao"
                                    rows="3" required><?php echo valor_localizacao('descricaoLocalizacao', $valores); ?></textarea>
                                <div class="invalid-feedback">Preencha o campo de descrição.</div>
                            </div>

                            <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="localizacoes.php" class="btn btn-outline-secondary">Cancelar</a>

                                <button type="submit" class="btn btn-primary" name="guardar_localizacao" value="1">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar localização
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </section>

        </main>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>