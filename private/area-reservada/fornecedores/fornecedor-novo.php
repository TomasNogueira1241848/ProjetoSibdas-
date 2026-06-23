<?php
$pageTitle = 'MedInfo Solutions — Novo Fornecedor';
$assetPath = '../../../assets';
$loginPath = '../../../public/login.php';
$areaPath = '../';
$activeMenu = 'fornecedores';
$pageScript = '';

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';

redirect_if_not_logged();
exigir_permissao('fornecedores', 'criar');

$erros = [];
$erroSistema = '';
$mensagemSucesso = '';

$valores = [
    'nomeFornecedor' => '',
    'nifFornecedor' => '',
    'tipoFornecedor' => '',
    'estadoFornecedor' => '',
    'emailFornecedor' => '',
    'telefoneFornecedor' => '',
    'contratoFornecedor' => '',
    'areaFornecedor' => '',
    'websiteFornecedor' => '',
    'pessoaContactoFornecedor' => '',
    'telefoneContactoFornecedor' => '',
    'moradaFornecedor' => '',
    'observacoesFornecedor' => ''
];

$tiposFornecedor = [];

function valor_fornecedor($campo, $valores)
{
    return htmlspecialchars($valores[$campo] ?? '');
}

/*
 * Valida um NIF português: 9 dígitos, primeiro dígito válido e dígito de
 * controlo correto (módulo 11). Devolve true se for um NIF válido.
 */
function validar_nif($nif)
{
    if (!preg_match('/^\d{9}$/', $nif)) {
        return false;
    }

    // O primeiro dígito identifica o tipo de contribuinte (valores válidos comuns).
    if (!in_array($nif[0], ['1', '2', '3', '5', '6', '8', '9'], true)) {
        return false;
    }

    $soma = 0;
    for ($i = 0; $i < 8; $i++) {
        $soma += (int) $nif[$i] * (9 - $i);
    }

    $resto = $soma % 11;
    $digitoControlo = ($resto < 2) ? 0 : 11 - $resto;

    return (int) $nif[8] === $digitoControlo;
}

function selecionado_fornecedor($valor, $valorAtual)
{
    return (string) $valor === (string) $valorAtual ? 'selected' : '';
}

function validar_id_existente($ligacao, $tabela, $id)
{
    $tabelasPermitidas = ['tipos_fornecedor'];

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
        $tiposFornecedor = $ligacao
            ->query('SELECT id, nome FROM tipos_fornecedor ORDER BY nome')
            ->fetchAll();
    } catch (PDOException $erro) {
        $erroSistema = 'Ocorreu um erro ao carregar os tipos de fornecedor.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($valores as $campo => $valorDefeito) {
        $valores[$campo] = trim($_POST[$campo] ?? $valorDefeito);
    }

    /* Normalização dos dados principais, tal como no equipamento-novo.php */
    $valores['nomeFornecedor'] = ucwords(strtolower($valores['nomeFornecedor']));
    $valores['emailFornecedor'] = strtolower($valores['emailFornecedor']);
    $valores['nifFornecedor'] = preg_replace('/\D/', '', $valores['nifFornecedor']);

    /* Validações em PHP */
    if ($valores['nomeFornecedor'] === '') {
        $erros[] = 'O nome do fornecedor é obrigatório.';
    } elseif (preg_match('/\d/', $valores['nomeFornecedor'])) {
        $erros[] = 'O nome do fornecedor não deve conter números.';
    }

    if ($valores['nifFornecedor'] === '') {
        $erros[] = 'O NIF é obrigatório.';
    } elseif (!preg_match('/^\d{9}$/', $valores['nifFornecedor'])) {
        $erros[] = 'O NIF deve ter exatamente 9 dígitos.';
    } elseif (!validar_nif($valores['nifFornecedor'])) {
        $erros[] = 'O NIF introduzido não é válido.';
    }

    if ($valores['tipoFornecedor'] === '') {
        $erros[] = 'Selecione o tipo de fornecedor.';
    } elseif (!ctype_digit($valores['tipoFornecedor'])) {
        $erros[] = 'O tipo de fornecedor selecionado não é válido.';
    }

    if ($valores['estadoFornecedor'] === '') {
        $erros[] = 'Selecione o estado do fornecedor.';
    } elseif (!in_array($valores['estadoFornecedor'], ['Ativo', 'Inativo'], true)) {
        $erros[] = 'O estado do fornecedor selecionado não é válido.';
    }

    if ($valores['emailFornecedor'] === '') {
        $erros[] = 'O email é obrigatório.';
    } elseif (!filter_var($valores['emailFornecedor'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'O email não é válido.';
    }

    $contactoFornecedor = preg_replace('/[\s.\-]/', '', $valores['telefoneFornecedor']);

    if ($valores['telefoneFornecedor'] === '') {
        $erros[] = 'O contacto é obrigatório.';
    } elseif (!preg_match('/^[239]\d{8}$/', $contactoFornecedor)) {
        $erros[] = 'O contacto deve ter 9 dígitos e começar por 2, 3 ou 9.';
    }

    if ($valores['contratoFornecedor'] === '') {
        $erros[] = 'Indique se o contrato está ativo.';
    } elseif (!in_array($valores['contratoFornecedor'], ['Sim', 'Não'], true)) {
        $erros[] = 'O valor do campo contrato ativo não é válido.';
    }

    if ($valores['areaFornecedor'] === '') {
        $erros[] = 'A área de atuação é obrigatória.';
    }

    if ($valores['websiteFornecedor'] === '') {
        $erros[] = 'O website é obrigatório.';
    } else {
        $websiteParaValidar = preg_match('#^https?://#i', $valores['websiteFornecedor'])
            ? $valores['websiteFornecedor']
            : 'https://' . $valores['websiteFornecedor'];
        if (!filter_var($websiteParaValidar, FILTER_VALIDATE_URL)) {
            $erros[] = 'O website não tem um formato válido (ex: www.empresa.pt).';
        }
    }

    if ($valores['pessoaContactoFornecedor'] === '') {
        $erros[] = 'A pessoa responsável/contacto é obrigatória.';
    } elseif (preg_match('/\d/', $valores['pessoaContactoFornecedor'])) {
        $erros[] = 'A pessoa responsável/contacto não deve conter números.';
    }

    $contactoPessoaResponsavel = preg_replace('/[\s.\-]/', '', $valores['telefoneContactoFornecedor']);

    if ($valores['telefoneContactoFornecedor'] === '') {
        $erros[] = 'O contacto da pessoa responsável é obrigatório.';
    } elseif (!preg_match('/^[239]\d{8}$/', $contactoPessoaResponsavel)) {
        $erros[] = 'O contacto da pessoa responsável deve ter 9 dígitos e começar por 2, 3 ou 9.';
    }

    if ($valores['moradaFornecedor'] === '') {
        $erros[] = 'A morada é obrigatória.';
    }

    if ($valores['observacoesFornecedor'] === '') {
        $erros[] = 'As observações são obrigatórias.';
    }

    $tipoFornecedorId = null;

    if ($ligacao !== null && $valores['tipoFornecedor'] !== '' && ctype_digit($valores['tipoFornecedor'])) {
        $tipoFornecedorId = (int) $valores['tipoFornecedor'];

        if (!validar_id_existente($ligacao, 'tipos_fornecedor', $tipoFornecedorId)) {
            $erros[] = 'O tipo de fornecedor selecionado não existe na base de dados.';
        }
    }

    if (empty($erros) && $ligacao !== null) {
        try {
            $sql = "
                INSERT INTO fornecedores (
                    nome,
                    nif,
                    tipo_fornecedor_id,
                    email,
                    telefone,
                    contrato_ativo,
                    area_atuacao,
                    morada,
                    website,
                    pessoa_contacto,
                    telefone_contacto,
                    observacoes,
                    estado
                ) VALUES (
                    :nome,
                    :nif,
                    :tipo_fornecedor_id,
                    :email,
                    :telefone,
                    :contrato_ativo,
                    :area_atuacao,
                    :morada,
                    :website,
                    :pessoa_contacto,
                    :telefone_contacto,
                    :observacoes,
                    :estado
                )
            ";

            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                ':nome' => $valores['nomeFornecedor'],
                ':nif' => $valores['nifFornecedor'],
                ':tipo_fornecedor_id' => $tipoFornecedorId,
                ':email' => $valores['emailFornecedor'],
                ':telefone' => $valores['telefoneFornecedor'],
                ':contrato_ativo' => $valores['contratoFornecedor'] === 'Sim' ? 1 : 0,
                ':area_atuacao' => $valores['areaFornecedor'],
                ':morada' => $valores['moradaFornecedor'],
                ':website' => $valores['websiteFornecedor'],
                ':pessoa_contacto' => $valores['pessoaContactoFornecedor'],
                ':telefone_contacto' => $valores['telefoneContactoFornecedor'],
                ':observacoes' => $valores['observacoesFornecedor'],
                ':estado' => $valores['estadoFornecedor']
            ]);

            header('Location: fornecedores.php?sucesso=1');
            exit;
        } catch (PDOException $erro) {
            if ($erro->getCode() === '23000') {
                $erroSistema = 'Não foi possível guardar: já existe um fornecedor com esse NIF.';
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

            <!-- TÍTULO -->
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Novo fornecedor</h4>
                    <p class="text-muted small mb-0">Registo de um novo fornecedor ou entidade de assistência
                        técnica.</p>
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

            <!-- FORMULÁRIO -->
            <section class="mb-4">
                <div class="card p-4">
                    <form id="formFornecedor" action="#" method="post" novalidate>
                        <div class="row g-3">

                            <div class="col-md-8">
                                <label for="nomeFornecedor" class="form-label">
                                    Nome do fornecedor
                                </label>
                                <input type="text" class="form-control" id="nomeFornecedor" name="nomeFornecedor"
                                    value="<?php echo valor_fornecedor('nomeFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o nome do fornecedor.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="nifFornecedor" class="form-label">
                                    NIF
                                </label>
                                <input type="text" class="form-control" id="nifFornecedor" name="nifFornecedor"
                                    maxlength="9" inputmode="numeric" placeholder="Ex: 501234567"
                                    value="<?php echo valor_fornecedor('nifFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o NIF do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="tipoFornecedor" class="form-label"> Tipo </label>
                                <select class="form-select" id="tipoFornecedor" name="tipoFornecedor" required>
                                    <option value="">Selecionar tipo</option>
                                    <?php foreach ($tiposFornecedor as $tipoFornecedor): ?>
                                        <option value="<?php echo htmlspecialchars($tipoFornecedor->id); ?>" <?php echo selecionado_fornecedor($tipoFornecedor->id, $valores['tipoFornecedor']); ?>>
                                            <?php echo htmlspecialchars($tipoFornecedor->nome); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Selecione o tipo de fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="estadoFornecedor" class="form-label">
                                    Estado
                                </label>
                                <select class="form-select" id="estadoFornecedor" name="estadoFornecedor" required>
                                    <option value="">Selecionar estado</option>
                                    <option value="Ativo" <?php echo selecionado_fornecedor('Ativo', $valores['estadoFornecedor']); ?>>Ativo</option>
                                    <option value="Inativo" <?php echo selecionado_fornecedor('Inativo', $valores['estadoFornecedor']); ?>>Inativo</option>
                                </select>
                                <div class="invalid-feedback">Selecione o estado do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="emailFornecedor" class="form-label"> Email </label>
                                <input type="email" class="form-control" id="emailFornecedor" name="emailFornecedor"
                                    value="<?php echo valor_fornecedor('emailFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza um email válido.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefoneFornecedor" class="form-label">
                                    Contacto
                                </label>
                                <input type="text" class="form-control" id="telefoneFornecedor"
                                    name="telefoneFornecedor" placeholder="Ex: 912345678 ou 212345678" value="<?php echo valor_fornecedor('telefoneFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o contacto do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="contratoFornecedor" class="form-label">Contrato ativo</label>
                                <select class="form-select" id="contratoFornecedor" name="contratoFornecedor" required>
                                    <option value="">Selecionar</option>
                                    <option value="Sim" <?php echo selecionado_fornecedor('Sim', $valores['contratoFornecedor']); ?>>Sim</option>
                                    <option value="Não" <?php echo selecionado_fornecedor('Não', $valores['contratoFornecedor']); ?>>Não</option>
                                </select>
                                <div class="invalid-feedback">Selecione o sim ou não.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="areaFornecedor" class="form-label">Área de atuação</label>
                                <input type="text" class="form-control" id="areaFornecedor" name="areaFornecedor"
                                    value="<?php echo valor_fornecedor('areaFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza a área de atuação.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="websiteFornecedor" class="form-label">Website</label>
                                <input type="text" class="form-control" id="websiteFornecedor" name="websiteFornecedor" placeholder="Ex: www.empresa.pt"
                                    value="<?php echo valor_fornecedor('websiteFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o website do fornecedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="pessoaContactoFornecedor" class="form-label">Pessoa responsável / contacto</label>
                                <input type="text" class="form-control" id="pessoaContactoFornecedor" name="pessoaContactoFornecedor"
                                    value="<?php echo valor_fornecedor('pessoaContactoFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza a pessoa responsável.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="telefoneContactoFornecedor" class="form-label">Contacto da pessoa responsável</label>
                                <input type="text" class="form-control" id="telefoneContactoFornecedor" name="telefoneContactoFornecedor" placeholder="Ex: 912345678 ou 212345678"
                                    value="<?php echo valor_fornecedor('telefoneContactoFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza o contacto da pessoa responsável.</div>
                            </div>

                            <div class="col-12">
                                <label for="moradaFornecedor" class="form-label">Morada</label>
                                <input type="text" class="form-control" id="moradaFornecedor" name="moradaFornecedor"
                                    value="<?php echo valor_fornecedor('moradaFornecedor', $valores); ?>" required>
                                <div class="invalid-feedback">Introduza a morada.</div>
                            </div>

                            <div class="col-12">
                                <label for="observacoesFornecedor" class="form-label">Observações</label>
                                <textarea class="form-control" id="observacoesFornecedor" name="observacoesFornecedor"
                                    rows="3" required><?php echo valor_fornecedor('observacoesFornecedor', $valores); ?></textarea>
                                <div class="invalid-feedback">Preencha o campo de observações.</div>
                            </div>

                            <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <a href="fornecedores.php" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary" name="guardar_fornecedor" value="1">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar fornecedor
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