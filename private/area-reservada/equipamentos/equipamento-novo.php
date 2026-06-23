<?php
$pageTitle = 'MedInfo Solutions — Novo Equipamento';
$assetPath = '../../../assets';
$areaPath = '../';
$activeMenu = 'equipamentos';
 
$pageScript = '';
 
require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/basedados.php';
 
redirect_if_not_logged();
exigir_permissao('equipamentos', 'criar');
 
$erros = [];
$erroSistema = '';
 
$categorias = [];
$estadosEquipamento = [];
$criticidades = [];
$tiposEntrada = [];
$fornecedores = [];
$localizacoes = [];
$equipamentosPai = [];
$areasDocumento = [];
$estadosDocumento = [];
$estadosGarantia = [];
$estadosContrato = [];
$contratosExistentes = [];
$estadosManutencao = [];
$prioridadesManutencao = [];
$tiposDocumento = [];
$tiposContrato = [];
$proximoCodigoEquipamento = 'EQ-0001';
$proximoCodigoDocumento = 'DOC-0001';
$proximoCodigoGarantia = 'GAR-0001';
$proximoCodigoContrato = 'CON-0001';
$documentosMinimosObrigatorios = [
    'ManualUtilizador' => 'Manual de utilizador',
    'ManualServico' => 'Manual de serviço',
    'CertificadoCalibracao' => 'Certificado de calibração',
    'FaturaGuiaAquisicao' => 'Fatura ou guia de aquisição',
    'DeclaracaoConformidade' => 'Declaração de conformidade',
    'RelatorioTecnico' => 'Relatório técnico'
];
 
$valores = [
    'codigo_equipamento' => '',
    'designacao_equipamento' => '',
    'categoria_id' => '',
    'marca_equipamento' => '',
    'modelo_equipamento' => '',
    'numero_serie_equipamento' => '',
    'estado_id' => '',
    'criticidade_id' => '',
    'data_aquisicao' => '',
    'custo_aquisicao' => '',
    'ano_fabrico' => '',
    'tipo_entrada_id' => '',
    'observacoes_equipamento' => '',
    'fornecedor_principal_id' => '',
    'fabricante_id' => '',
    'prestador_assistencia_id' => '',
    'componente_equipamento' => 'Não',
    'equipamento_pai_id' => '',
    'tem_consumiveis' => 'Não',
    'consumiveis_descricao' => '',
    'localizacao_id' => '',
    'servico' => '',
    'piso' => '',
    'sala' => ''
];
 
function valor_formulario($campo, $valores)
{
    return htmlspecialchars($valores[$campo] ?? '');
}
 
function selected_formulario($valor, $valorAtual)
{
    return (string) $valor === (string) $valorAtual ? 'selected' : '';
}
 
/*
 * Gera as <option> de fornecedores a partir da lista carregada da base de dados.
 * Usa o NOME do fornecedor como value, porque o processamento (obter_id_por_nome)
 * associa os documentos/garantias/contratos ao fornecedor pelo nome.
 * Assim, qualquer fornecedor criado na área de fornecedores aparece automaticamente
 * em todos os seletores do formulário de equipamento.
 */
function options_fornecedores_por_nome($fornecedores, $incluirSemFornecedor = false, $selecionado = '')
{
    $html = '<option value="" ' . selected_formulario('', $selecionado) . '>Selecionar</option>';
 
    if ($incluirSemFornecedor) {
        $html .= '<option value="Sem fornecedor associado" ' . selected_formulario('Sem fornecedor associado', $selecionado) . '>Sem fornecedor associado</option>';
    }
 
    foreach ($fornecedores as $fornecedor) {
        $nomeOriginal = $fornecedor->nome;
        $nome = htmlspecialchars($nomeOriginal);
        $html .= '<option value="' . $nome . '" ' . selected_formulario($nomeOriginal, $selecionado) . '>' . $nome . '</option>';
    }
 
    return $html;
}
 
/*
 * Gera <option> a partir de uma lista de lookup carregada da base de dados.
 * Usa o NOME como value (o processamento resolve depois o id pelo nome).
 */
function options_lista_nome($lista, $selecionado = '')
{
    $html = '<option value="" ' . selected_formulario('', $selecionado) . '>Selecionar</option>';
 
    foreach ($lista as $item) {
        $nomeOriginal = $item->nome;
        $nome = htmlspecialchars($nomeOriginal);
        $html .= '<option value="' . $nome . '" ' . selected_formulario($nomeOriginal, $selecionado) . '>' . $nome . '</option>';
    }
 
    return $html;
}
 
function validar_data_formulario($data)
{
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        return false;
    }
 
    $partes = explode('-', $data);
 
    return checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0]);
}
 
function obter_opcoes($ligacao, $sql)
{
    if ($ligacao === null) {
        return [];
    }
 
    return $ligacao->query($sql)->fetchAll();
}

/*
 * Calcula o proximo codigo sequencial (ex: EQ-0006 -> EQ-0007).
 */
function proximo_codigo($ligacao, $tabela, $coluna, $prefixo, $largura = 4)
{
    $codigoInicial = $prefixo . '-' . str_pad('1', $largura, '0', STR_PAD_LEFT);
    if ($ligacao === null) {
        return $codigoInicial;
    }
    try {
        $sql = "SELECT MAX(CAST(SUBSTRING($coluna, " . (strlen($prefixo) + 2) . ") AS UNSIGNED)) AS maximo FROM $tabela WHERE $coluna LIKE :prefixo";
        $stmt = $ligacao->prepare($sql);
        $stmt->execute([':prefixo' => $prefixo . '-%']);
        $maximo = (int) $stmt->fetchColumn();
    } catch (PDOException $erro) {
        return $codigoInicial;
    }
    return $prefixo . '-' . str_pad((string) ($maximo + 1), $largura, '0', STR_PAD_LEFT);
}
/*
 * Devolve um codigo de documento sequencial com um deslocamento.
 * Ex: se o proximo for DOC-0016, offset 0 -> DOC-0016, offset 1 -> DOC-0017.
 */
function codigo_documento_offset($proximoCodigoDocumento, $offset)
{
    if (!preg_match('/^DOC-(\d+)$/', $proximoCodigoDocumento, $m)) {
        return $proximoCodigoDocumento;
    }
    $largura = strlen($m[1]);
    return 'DOC-' . str_pad((string) ((int) $m[1] + $offset), $largura, '0', STR_PAD_LEFT);
}
 
 
function obter_ficheiros_documento($chaveDocumento)
{
    if (!isset($_FILES['documentosMinimos']['name'][$chaveDocumento]['ficheiros'])) {
        return [];
    }
 
    $ficheiros = [];
    $nomes = $_FILES['documentosMinimos']['name'][$chaveDocumento]['ficheiros'];
 
    foreach ($nomes as $indice => $nomeOriginal) {
        if ($nomeOriginal === '') {
            continue;
        }
 
        $ficheiros[] = [
            'name' => $nomeOriginal,
            'type' => $_FILES['documentosMinimos']['type'][$chaveDocumento]['ficheiros'][$indice] ?? '',
            'tmp_name' => $_FILES['documentosMinimos']['tmp_name'][$chaveDocumento]['ficheiros'][$indice] ?? '',
            'error' => $_FILES['documentosMinimos']['error'][$chaveDocumento]['ficheiros'][$indice] ?? UPLOAD_ERR_NO_FILE,
            'size' => $_FILES['documentosMinimos']['size'][$chaveDocumento]['ficheiros'][$indice] ?? 0
        ];
    }
 
    return $ficheiros;
}
 
function validar_documentos_minimos($documentosMinimosObrigatorios, $documentosPost, &$erros)
{
    foreach ($documentosMinimosObrigatorios as $chaveDocumento => $nomeDocumento) {
        $documento = $documentosPost[$chaveDocumento] ?? [];
 
        $codigo = trim($documento['codigo'] ?? '');
        $titulo = trim($documento['titulo'] ?? '');
        $area = trim($documento['area'] ?? '');
        $dataDocumento = trim($documento['data_documento'] ?? '');
        $validade = trim($documento['validade'] ?? '');
        $estado = trim($documento['estado'] ?? '');
        $fornecedor = trim($documento['fornecedor'] ?? '');
        $ficheiros = obter_ficheiros_documento($chaveDocumento);
 
        if ($codigo === '') {
            $erros[] = 'O código do documento "' . $nomeDocumento . '" é obrigatório.';
        } elseif (!preg_match('/^DOC-\d{3,}$/', strtoupper($codigo))) {
            $erros[] = 'O código do documento "' . $nomeDocumento . '" deve seguir o formato DOC-000.';
        }
 
        if ($titulo === '') {
            $erros[] = 'O nome do documento "' . $nomeDocumento . '" é obrigatório.';
        }
 
        if ($area === '') {
            $erros[] = 'Selecione a área do documento "' . $nomeDocumento . '".';
        }
 
        if ($dataDocumento === '') {
            $erros[] = 'A data do documento "' . $nomeDocumento . '" é obrigatória.';
        } elseif (!validar_data_formulario($dataDocumento)) {
            $erros[] = 'A data do documento "' . $nomeDocumento . '" não é válida.';
        }
 
        if ($validade === '') {
            $erros[] = 'A validade do documento "' . $nomeDocumento . '" é obrigatória.';
        } elseif (!validar_data_formulario($validade)) {
            $erros[] = 'A validade do documento "' . $nomeDocumento . '" não é válida.';
        }
 
        if ($estado === '') {
            $erros[] = 'Selecione o estado do documento "' . $nomeDocumento . '".';
        }
 
        if ($fornecedor === '') {
            $erros[] = 'Selecione o fornecedor associado ao documento "' . $nomeDocumento . '".';
        }
 
        if (empty($ficheiros)) {
            $erros[] = 'Adicione pelo menos um PDF para o documento "' . $nomeDocumento . '".';
            continue;
        }
 
        foreach ($ficheiros as $ficheiro) {
            if ($ficheiro['error'] !== UPLOAD_ERR_OK) {
                $erros[] = 'Ocorreu um erro ao carregar o PDF do documento "' . $nomeDocumento . '".';
                continue;
            }
 
            $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
 
            if ($extensao !== 'pdf') {
                $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" deve estar em formato PDF.';
            }
 
            if ((int) $ficheiro['size'] <= 0) {
                $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" está vazio.';
            }
 
            if ((int) $ficheiro['size'] > 10 * 1024 * 1024) {
                $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" não pode ter mais de 10 MB.';
            }
        }
    }
}
 

function extrair_detalhe_observacoes($texto, $rotulo)
{
    $texto = (string) ($texto ?? '');
    $rotulo = (string) $rotulo;

    foreach (preg_split('/\R/u', $texto) as $linha) {
        $linha = trim($linha);
        if (stripos($linha, $rotulo . ':') === 0) {
            return trim(substr($linha, strlen($rotulo) + 1));
        }
    }

    return '';
}

function extrair_observacoes_livres($texto)
{
    $texto = trim((string) ($texto ?? ''));
    if ($texto === '') {
        return '';
    }

    $linhasLivres = [];
    foreach (preg_split('/\R/u', $texto) as $linha) {
        $linha = trim($linha);
        if ($linha === '') {
            continue;
        }

        if (stripos($linha, 'Responsável:') === 0 || stripos($linha, 'Responsavel:') === 0 || stripos($linha, 'Associado a:') === 0 || stripos($linha, 'Periodicidade:') === 0) {
            continue;
        }

        if (stripos($linha, 'Observações:') === 0) {
            $linhasLivres[] = trim(substr($linha, strlen('Observações:')));
            continue;
        }

        if (stripos($linha, 'Observacoes:') === 0) {
            $linhasLivres[] = trim(substr($linha, strlen('Observacoes:')));
            continue;
        }

        $linhasLivres[] = $linha;
    }

    return trim(implode("\n", array_filter($linhasLivres, static function ($linha) {
        return trim((string) $linha) !== '';
    })));
}

function preparar_observacoes_com_detalhes($observacoes, array $detalhes = [])
{
    $linhas = [];

    foreach ($detalhes as $rotulo => $valor) {
        $valor = trim((string) ($valor ?? ''));
        if ($valor !== '') {
            $linhas[] = $rotulo . ': ' . $valor;
        }
    }

    $observacoes = trim((string) ($observacoes ?? ''));
    if ($observacoes !== '') {
        $linhas[] = 'Observações: ' . $observacoes;
    }

    return implode("\n", $linhas);
}

function preparar_observacoes_documento($documento)
{
    return preparar_observacoes_com_detalhes($documento['observacoes'] ?? '', [
        'Responsável' => $documento['responsavel'] ?? ''
    ]);
}

function preparar_observacoes_garantia($garantia)
{
    return preparar_observacoes_com_detalhes($garantia['observacoes'] ?? '', [
        'Responsável' => $garantia['responsavel'] ?? ''
    ]);
}

function preparar_observacoes_contrato($contrato)
{
    return preparar_observacoes_com_detalhes($contrato['observacoes'] ?? '', [
        'Associado a' => $contrato['associado'] ?? 'Equipamento atual',
        'Responsável' => $contrato['responsavel'] ?? '',
        'Periodicidade' => $contrato['periodicidade'] ?? ''
    ]);
}

function obter_id_por_nome($ligacao, $tabela, $nome)
{
    $tabelasPermitidas = [
        'tipos_documento',
        'areas_documento',
        'estados_documento',
        'fornecedores',
        'tipos_contrato',
        'estados_contrato',
        'estados_garantia',
        'tipos_manutencao',
        'estados_manutencao',
        'prioridades_manutencao'
    ];
 
    if (!in_array($tabela, $tabelasPermitidas, true)) {
        return null;
    }
 
    $stmt = $ligacao->prepare("SELECT id FROM {$tabela} WHERE nome = :nome LIMIT 1");
    $stmt->execute([':nome' => $nome]);
    $resultado = $stmt->fetch();
 
    return $resultado ? $resultado->id : null;
}
 
function inserir_documentos_minimos($ligacao, $equipamentoId, $documentosMinimosObrigatorios, $documentosPost)
{
    $diretorioUploads = __DIR__ . '/../../../assets/uploads/documentos';
 
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0775, true)) {
        throw new RuntimeException('Não foi possível criar a pasta para guardar os PDFs.');
    }
 
    $stmtDocumento = $ligacao->prepare("\n        INSERT INTO documentos (\n            codigo,\n            titulo,\n            tipo_documento_id,\n            area_documento_id,\n            equipamento_id,\n            fornecedor_id,\n            responsavel,\n            data_documento,\n            validade,\n            estado_documento_id,\n            obrigatorio,\n            observacoes\n        ) VALUES (\n            :codigo,\n            :titulo,\n            :tipo_documento_id,\n            :area_documento_id,\n            :equipamento_id,\n            :fornecedor_id,\n            :responsavel,\n            :data_documento,\n            :validade,\n            :estado_documento_id,\n            1,\n            :observacoes\n        )\n    ");
 
    $stmtFicheiro = $ligacao->prepare("\n        INSERT INTO ficheiros_pdf (\n            nome_original,\n            nome_guardado,\n            caminho_ficheiro,\n            tipo_mime,\n            tamanho_bytes,\n            carregado_por\n        ) VALUES (\n            :nome_original,\n            :nome_guardado,\n            :caminho_ficheiro,\n            :tipo_mime,\n            :tamanho_bytes,\n            :carregado_por\n        )\n    ");
 
    $stmtDocumentoFicheiro = $ligacao->prepare("\n        INSERT INTO documento_ficheiros (\n            documento_id,\n            ficheiro_id\n        ) VALUES (\n            :documento_id,\n            :ficheiro_id\n        )\n    ");
 
    $utilizadorId = $_SESSION['utilizador']['id'] ?? null;
    $utilizadorId = is_numeric($utilizadorId) ? (int) $utilizadorId : null;
 
    foreach ($documentosMinimosObrigatorios as $chaveDocumento => $nomeDocumento) {
        $documento = $documentosPost[$chaveDocumento];
 
        $tipoDocumentoId = obter_id_por_nome($ligacao, 'tipos_documento', $nomeDocumento);
        $areaDocumentoId = obter_id_por_nome($ligacao, 'areas_documento', trim($documento['area']));
        $estadoDocumentoId = obter_id_por_nome($ligacao, 'estados_documento', trim($documento['estado']));
 
        $fornecedorNome = trim($documento['fornecedor'] ?? '');
        $fornecedorId = null;
 
        if ($fornecedorNome !== '' && $fornecedorNome !== 'Sem fornecedor associado') {
            $fornecedorId = obter_id_por_nome($ligacao, 'fornecedores', $fornecedorNome);
        }
 
        if (!$tipoDocumentoId || !$areaDocumentoId || !$estadoDocumentoId) {
            throw new RuntimeException('Não foi possível associar corretamente o documento "' . $nomeDocumento . '" às tabelas auxiliares.');
        }
 
        $stmtDocumento->execute([
            ':codigo' => strtoupper(trim($documento['codigo'])),
            ':titulo' => trim($documento['titulo']),
            ':tipo_documento_id' => $tipoDocumentoId,
            ':area_documento_id' => $areaDocumentoId,
            ':equipamento_id' => $equipamentoId,
            ':fornecedor_id' => $fornecedorId,
            ':responsavel' => trim((string) ($documento['responsavel'] ?? '')),
            ':data_documento' => trim($documento['data_documento']),
            ':validade' => trim($documento['validade']),
            ':estado_documento_id' => $estadoDocumentoId,
            ':observacoes' => preparar_observacoes_documento($documento)
        ]);
 
        $documentoId = $ligacao->lastInsertId();
        $ficheiros = obter_ficheiros_documento($chaveDocumento);
 
        foreach ($ficheiros as $ficheiro) {
            $nomeGuardado = uniqid('pdf_', true) . '.pdf';
            $destino = $diretorioUploads . '/' . $nomeGuardado;
 
            if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
                throw new RuntimeException('Não foi possível guardar o ficheiro "' . $ficheiro['name'] . '".');
            }
 
            $stmtFicheiro->execute([
                ':nome_original' => $ficheiro['name'],
                ':nome_guardado' => $nomeGuardado,
                ':caminho_ficheiro' => 'assets/uploads/documentos/' . $nomeGuardado,
                ':tipo_mime' => 'application/pdf',
                ':tamanho_bytes' => $ficheiro['size'],
                ':carregado_por' => $utilizadorId
            ]);
 
            $ficheiroId = $ligacao->lastInsertId();
 
            $stmtDocumentoFicheiro->execute([
                ':documento_id' => $documentoId,
                ':ficheiro_id' => $ficheiroId
            ]);
        }
    }
}
 
 
function obter_ficheiros_documento_extra($indice)
{
    if (!isset($_FILES['outrosDocumentos']['name'][$indice]['ficheiros'])) {
        return [];
    }
    $ficheiros = [];
    $nomes = $_FILES['outrosDocumentos']['name'][$indice]['ficheiros'];
    foreach ($nomes as $i => $nomeOriginal) {
        if ($nomeOriginal === '') {
            continue;
        }
        $ficheiros[] = [
            'name' => $nomeOriginal,
            'type' => $_FILES['outrosDocumentos']['type'][$indice]['ficheiros'][$i] ?? '',
            'tmp_name' => $_FILES['outrosDocumentos']['tmp_name'][$indice]['ficheiros'][$i] ?? '',
            'error' => $_FILES['outrosDocumentos']['error'][$indice]['ficheiros'][$i] ?? UPLOAD_ERR_NO_FILE,
            'size' => $_FILES['outrosDocumentos']['size'][$indice]['ficheiros'][$i] ?? 0
        ];
    }
    return $ficheiros;
}

/*
 * Insere os documentos extra (opcionais) que o utilizador adicionou.
 * Cada documento so e gravado se tiver pelo menos codigo e titulo preenchidos.
 * Os PDFs sao opcionais. Devolve o numero de documentos inseridos.
 */
function inserir_documentos_extra($ligacao, $equipamentoId, $outrosDocumentos)
{
    if (empty($outrosDocumentos) || !is_array($outrosDocumentos)) {
        return 0;
    }

    $diretorioUploads = __DIR__ . '/../../../assets/uploads/documentos';
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0775, true)) {
        throw new RuntimeException('Nao foi possivel criar a pasta para guardar os PDFs.');
    }

    $stmtDocumento = $ligacao->prepare("
        INSERT INTO documentos (codigo, titulo, tipo_documento_id, area_documento_id, equipamento_id, fornecedor_id, responsavel, data_documento, validade, estado_documento_id, obrigatorio, observacoes)
        VALUES (:codigo, :titulo, :tipo_documento_id, :area_documento_id, :equipamento_id, :fornecedor_id, :responsavel, :data_documento, :validade, :estado_documento_id, 0, :observacoes)
    ");
    $stmtFicheiro = $ligacao->prepare("
        INSERT INTO ficheiros_pdf (nome_original, nome_guardado, caminho_ficheiro, tipo_mime, tamanho_bytes, carregado_por)
        VALUES (:nome_original, :nome_guardado, :caminho_ficheiro, :tipo_mime, :tamanho_bytes, :carregado_por)
    ");
    $stmtDocumentoFicheiro = $ligacao->prepare("
        INSERT INTO documento_ficheiros (documento_id, ficheiro_id) VALUES (:documento_id, :ficheiro_id)
    ");

    $utilizadorId = $_SESSION['utilizador']['id'] ?? null;
    $utilizadorId = is_numeric($utilizadorId) ? (int) $utilizadorId : null;
    $inseridos = 0;

    foreach ($outrosDocumentos as $indice => $documento) {
        if (!is_array($documento)) {
            continue;
        }
        $codigo = strtoupper(trim($documento['codigo'] ?? ''));
        $titulo = trim($documento['titulo'] ?? '');

        // Ignora blocos vazios (o utilizador adicionou mas nao preencheu).
        if ($codigo === '' && $titulo === '') {
            continue;
        }

        $tipoDocumentoId = obter_id_por_nome($ligacao, 'tipos_documento', trim($documento['tipo'] ?? ''));
        $areaDocumentoId = obter_id_por_nome($ligacao, 'areas_documento', trim($documento['area'] ?? ''));
        $estadoDocumentoId = obter_id_por_nome($ligacao, 'estados_documento', trim($documento['estado'] ?? ''));

        $fornecedorNome = trim($documento['fornecedor'] ?? '');
        $fornecedorId = null;
        if ($fornecedorNome !== '' && $fornecedorNome !== 'Sem fornecedor associado') {
            $fornecedorId = obter_id_por_nome($ligacao, 'fornecedores', $fornecedorNome);
        }

        $stmtDocumento->execute([
            ':codigo' => $codigo,
            ':titulo' => $titulo,
            ':tipo_documento_id' => $tipoDocumentoId ?: null,
            ':area_documento_id' => $areaDocumentoId ?: null,
            ':equipamento_id' => $equipamentoId,
            ':fornecedor_id' => $fornecedorId,
            ':responsavel' => trim((string) ($documento['responsavel'] ?? '')),
            ':data_documento' => trim($documento['data_documento'] ?? '') ?: null,
            ':validade' => trim($documento['validade'] ?? '') ?: null,
            ':estado_documento_id' => $estadoDocumentoId ?: null,
            ':observacoes' => preparar_observacoes_documento($documento)
        ]);
        $documentoId = $ligacao->lastInsertId();
        $inseridos++;

        foreach (obter_ficheiros_documento_extra($indice) as $ficheiro) {
            if (($ficheiro['error'] ?? 4) !== 0) {
                continue;
            }
            $nomeGuardado = uniqid('pdf_', true) . '.pdf';
            $destino = $diretorioUploads . '/' . $nomeGuardado;
            if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
                continue;
            }
            $stmtFicheiro->execute([
                ':nome_original' => $ficheiro['name'],
                ':nome_guardado' => $nomeGuardado,
                ':caminho_ficheiro' => 'assets/uploads/documentos/' . $nomeGuardado,
                ':tipo_mime' => 'application/pdf',
                ':tamanho_bytes' => $ficheiro['size'],
                ':carregado_por' => $utilizadorId
            ]);
            $stmtDocumentoFicheiro->execute([
                ':documento_id' => $documentoId,
                ':ficheiro_id' => $ligacao->lastInsertId()
            ]);
        }
    }

    return $inseridos;
}



function obter_ficheiros_contrato_extra($indice)
{
    if (!isset($_FILES['outrosContratos']['name'][$indice]['ficheiros'])) {
        return [];
    }

    $ficheiros = [];
    $nomes = $_FILES['outrosContratos']['name'][$indice]['ficheiros'];

    foreach ($nomes as $i => $nomeOriginal) {
        if ($nomeOriginal === '') {
            continue;
        }

        $ficheiros[] = [
            'name' => $nomeOriginal,
            'type' => $_FILES['outrosContratos']['type'][$indice]['ficheiros'][$i] ?? '',
            'tmp_name' => $_FILES['outrosContratos']['tmp_name'][$indice]['ficheiros'][$i] ?? '',
            'error' => $_FILES['outrosContratos']['error'][$indice]['ficheiros'][$i] ?? UPLOAD_ERR_NO_FILE,
            'size' => $_FILES['outrosContratos']['size'][$indice]['ficheiros'][$i] ?? 0
        ];
    }

    return $ficheiros;
}

function contrato_extra_tem_dados($contrato)
{
    if (!is_array($contrato)) {
        return false;
    }

    foreach ($contrato as $valor) {
        if (is_array($valor)) {
            continue;
        }

        if (trim((string) $valor) !== '') {
            return true;
        }
    }

    return false;
}

function validar_contratos_extra($outrosContratos, &$erros, $ligacao = null)
{
    if (empty($outrosContratos) || !is_array($outrosContratos)) {
        return;
    }

    foreach ($outrosContratos as $indice => $contrato) {
        if (!contrato_extra_tem_dados($contrato)) {
            continue;
        }

        $prefixo = 'Contrato opcional #' . ((int) $indice + 1) . ': ';

        $codigo = strtoupper(trim($contrato['codigo'] ?? ''));
        $designacao = trim($contrato['designacao'] ?? '');
        $tipo = trim($contrato['tipo'] ?? '');
        $fornecedor = trim($contrato['fornecedor'] ?? '');
        $dataInicio = trim($contrato['data_inicio'] ?? '');
        $dataFim = trim($contrato['data_fim'] ?? '');
        $valorAnual = trim($contrato['valor_anual'] ?? '');
        $renovacao = trim($contrato['renovacao_automatica'] ?? '');
        $estado = trim($contrato['estado'] ?? '');

        if ($codigo === '') {
            $erros[] = $prefixo . 'o código é obrigatório.';
        }

        if ($designacao === '') {
            $erros[] = $prefixo . 'a designação é obrigatória.';
        }

        if ($tipo === '') {
            $erros[] = $prefixo . 'selecione o tipo de contrato.';
        } elseif ($ligacao !== null && !obter_id_por_nome($ligacao, 'tipos_contrato', $tipo)) {
            $erros[] = $prefixo . 'o tipo de contrato não existe na base de dados.';
        }

        if ($fornecedor === '') {
            $erros[] = $prefixo . 'selecione o fornecedor.';
        } elseif ($ligacao !== null && !obter_id_por_nome($ligacao, 'fornecedores', $fornecedor)) {
            $erros[] = $prefixo . 'o fornecedor não existe na base de dados.';
        }

        if ($dataInicio === '') {
            $erros[] = $prefixo . 'a data de início é obrigatória.';
        } elseif (!validar_data_formulario($dataInicio)) {
            $erros[] = $prefixo . 'a data de início não é válida.';
        }

        if ($dataFim === '') {
            $erros[] = $prefixo . 'a data de fim é obrigatória.';
        } elseif (!validar_data_formulario($dataFim)) {
            $erros[] = $prefixo . 'a data de fim não é válida.';
        }

        if ($dataInicio !== '' && $dataFim !== '' && validar_data_formulario($dataInicio) && validar_data_formulario($dataFim) && $dataFim < $dataInicio) {
            $erros[] = $prefixo . 'a data de fim não pode ser anterior à data de início.';
        }

        if ($valorAnual === '') {
            $erros[] = $prefixo . 'o valor anual é obrigatório.';
        } elseif (!is_numeric($valorAnual) || (float) $valorAnual < 0) {
            $erros[] = $prefixo . 'o valor anual deve ser um número igual ou superior a zero.';
        }

        if ($renovacao === '') {
            $erros[] = $prefixo . 'indique se existe renovação automática.';
        }

        if ($estado === '') {
            $erros[] = $prefixo . 'selecione o estado.';
        } elseif ($ligacao !== null && !obter_id_por_nome($ligacao, 'estados_contrato', $estado)) {
            $erros[] = $prefixo . 'o estado selecionado não existe na base de dados.';
        }

        foreach (obter_ficheiros_contrato_extra($indice) as $ficheiro) {
            if (($ficheiro['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $erros[] = $prefixo . 'ocorreu um erro ao carregar o PDF "' . ($ficheiro['name'] ?? '') . '".';
                continue;
            }

            $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));

            if ($extensao !== 'pdf') {
                $erros[] = $prefixo . 'o ficheiro "' . $ficheiro['name'] . '" deve estar em formato PDF.';
            }

            if ((int) ($ficheiro['size'] ?? 0) <= 0) {
                $erros[] = $prefixo . 'o ficheiro "' . $ficheiro['name'] . '" está vazio.';
            }

            if ((int) ($ficheiro['size'] ?? 0) > 10 * 1024 * 1024) {
                $erros[] = $prefixo . 'o ficheiro "' . $ficheiro['name'] . '" não pode ter mais de 10 MB.';
            }
        }
    }
}

function inserir_contratos_extra($ligacao, $equipamentoId, $outrosContratos)
{
    if (empty($outrosContratos) || !is_array($outrosContratos)) {
        return 0;
    }

    $stmtContrato = $ligacao->prepare("
        INSERT INTO contratos (
            codigo,
            designacao,
            tipo_contrato_id,
            fornecedor_id,
            responsavel,
            data_inicio,
            data_fim,
            valor_anual,
            periodicidade,
            renovacao_automatica,
            estado_contrato_id,
            observacoes
        ) VALUES (
            :codigo,
            :designacao,
            :tipo_contrato_id,
            :fornecedor_id,
            :responsavel,
            :data_inicio,
            :data_fim,
            :valor_anual,
            :periodicidade,
            :renovacao_automatica,
            :estado_contrato_id,
            :observacoes
        )
    ");

    $stmtLigacao = $ligacao->prepare("
        INSERT INTO contrato_equipamentos (
            contrato_id,
            equipamento_id
        ) VALUES (
            :contrato_id,
            :equipamento_id
        )
    ");

    $inseridos = 0;

    foreach ($outrosContratos as $indice => $contrato) {
        if (!contrato_extra_tem_dados($contrato)) {
            continue;
        }

        $tipoContratoId = obter_id_por_nome($ligacao, 'tipos_contrato', trim($contrato['tipo'] ?? ''));
        $fornecedorId = obter_id_por_nome($ligacao, 'fornecedores', trim($contrato['fornecedor'] ?? ''));
        $estadoContratoId = obter_id_por_nome($ligacao, 'estados_contrato', trim($contrato['estado'] ?? ''));

        if (!$tipoContratoId || !$fornecedorId || !$estadoContratoId) {
            throw new RuntimeException('Não foi possível associar corretamente o contrato opcional #' . ((int) $indice + 1) . ' às tabelas auxiliares.');
        }

        $observacoes = trim($contrato['observacoes'] ?? '');
        $detalhes = [];

        if (trim($contrato['associado'] ?? '') !== '') {
            $detalhes[] = 'Associado a: ' . trim($contrato['associado']);
        }

        if (trim($contrato['responsavel'] ?? '') !== '') {
            $detalhes[] = 'Responsável: ' . trim($contrato['responsavel']);
        }

        if (trim($contrato['periodicidade'] ?? '') !== '') {
            $detalhes[] = 'Periodicidade: ' . trim($contrato['periodicidade']);
        }

        if ($observacoes !== '') {
            $detalhes[] = 'Observações: ' . $observacoes;
        }

        $stmtContrato->execute([
            ':codigo' => strtoupper(trim($contrato['codigo'])),
            ':designacao' => trim($contrato['designacao']),
            ':tipo_contrato_id' => $tipoContratoId,
            ':fornecedor_id' => $fornecedorId,
            ':responsavel' => trim((string) ($contrato['responsavel'] ?? '')),
            ':data_inicio' => trim($contrato['data_inicio']),
            ':data_fim' => trim($contrato['data_fim']),
            ':valor_anual' => trim($contrato['valor_anual']),
            ':periodicidade' => trim((string) ($contrato['periodicidade'] ?? '')),
            ':renovacao_automatica' => (trim($contrato['renovacao_automatica'] ?? '') === 'Sim') ? 1 : 0,
            ':estado_contrato_id' => $estadoContratoId,
            ':observacoes' => implode("
", $detalhes)
        ]);

        $contratoId = $ligacao->lastInsertId();

        $stmtLigacao->execute([
            ':contrato_id' => $contratoId,
            ':equipamento_id' => $equipamentoId
        ]);

        $ficheiros = obter_ficheiros_contrato_extra($indice);

        if (!empty($ficheiros)) {
            $ficheiroIds = guardar_ficheiros_pdf($ligacao, $ficheiros);
            ligar_ficheiros($ligacao, 'contrato_ficheiros', 'contrato_id', $contratoId, $ficheiroIds);
        }

        $inseridos++;
    }

    return $inseridos;
}

function obter_ficheiros_grupo($grupo)
{
    if (!isset($_FILES[$grupo]['name']['ficheiros'])) {
        return [];
    }
 
    $ficheiros = [];
    $nomes = $_FILES[$grupo]['name']['ficheiros'];
 
    foreach ($nomes as $indice => $nomeOriginal) {
        if ($nomeOriginal === '') {
            continue;
        }
 
        $ficheiros[] = [
            'name' => $nomeOriginal,
            'type' => $_FILES[$grupo]['type']['ficheiros'][$indice] ?? '',
            'tmp_name' => $_FILES[$grupo]['tmp_name']['ficheiros'][$indice] ?? '',
            'error' => $_FILES[$grupo]['error']['ficheiros'][$indice] ?? UPLOAD_ERR_NO_FILE,
            'size' => $_FILES[$grupo]['size']['ficheiros'][$indice] ?? 0
        ];
    }
 
    return $ficheiros;
}
 
function validar_ficheiros_pdf($ficheiros, $nomeCampo, &$erros, $obrigatorio = true)
{
    if ($obrigatorio && empty($ficheiros)) {
        $erros[] = 'Adicione pelo menos um PDF em "' . $nomeCampo . '".';
        return;
    }
 
    foreach ($ficheiros as $ficheiro) {
        if ($ficheiro['error'] !== UPLOAD_ERR_OK) {
            $erros[] = 'Ocorreu um erro ao carregar o PDF em "' . $nomeCampo . '".';
            continue;
        }
 
        $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
 
        if ($extensao !== 'pdf') {
            $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" deve estar em formato PDF.';
        }
 
        if ((int) $ficheiro['size'] <= 0) {
            $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" está vazio.';
        }
 
        if ((int) $ficheiro['size'] > 10 * 1024 * 1024) {
            $erros[] = 'O ficheiro "' . $ficheiro['name'] . '" não pode ter mais de 10 MB.';
        }
    }
}
 
function validar_garantia($garantia, &$erros)
{
    $camposObrigatorios = [
        'codigo' => 'O código da garantia é obrigatório.',
        'designacao' => 'A designação da garantia é obrigatória.',
        'fornecedor' => 'Selecione o fornecedor da garantia.',
        'data_inicio' => 'A data de início da garantia é obrigatória.',
        'data_fim' => 'A data de fim da garantia é obrigatória.',
        'estado' => 'Selecione o estado da garantia.',
        'cobertura' => 'A cobertura da garantia é obrigatória.',
        'observacoes' => 'As observações da garantia são obrigatórias.'
    ];
 
    foreach ($camposObrigatorios as $campo => $mensagem) {
        if (trim($garantia[$campo] ?? '') === '') {
            $erros[] = $mensagem;
        }
    }
 
    if (trim($garantia['codigo'] ?? '') !== '' && !preg_match('/^GAR-\d{3,}$/', strtoupper(trim($garantia['codigo'])))) {
        $erros[] = 'O código da garantia deve seguir o formato GAR-000.';
    }

    if (!empty($garantia['data_inicio']) && !validar_data_formulario($garantia['data_inicio'])) {
        $erros[] = 'A data de início da garantia não é válida.';
    }
 
    if (!empty($garantia['data_fim']) && !validar_data_formulario($garantia['data_fim'])) {
        $erros[] = 'A data de fim da garantia não é válida.';
    }
 
    if (!empty($garantia['data_inicio']) && !empty($garantia['data_fim']) &&
        validar_data_formulario($garantia['data_inicio']) && validar_data_formulario($garantia['data_fim']) &&
        $garantia['data_fim'] < $garantia['data_inicio']) {
        $erros[] = 'A data de fim da garantia não pode ser anterior à data de início.';
    }
 
    validar_ficheiros_pdf(obter_ficheiros_grupo('garantia'), 'PDFs da garantia', $erros, true);
}
 
function validar_contrato_manutencao($contrato, &$erros)
{
    $camposObrigatorios = [
        'codigo' => 'O código do contrato de manutenção é obrigatório.',
        'designacao' => 'A designação do contrato de manutenção é obrigatória.',
        'fornecedor' => 'Selecione o fornecedor do contrato de manutenção.',
        'data_inicio' => 'A data de início do contrato de manutenção é obrigatória.',
        'data_fim' => 'A data de fim do contrato de manutenção é obrigatória.',
        'valor_anual' => 'O valor anual do contrato de manutenção é obrigatório.',
        'renovacao_automatica' => 'Indique se o contrato tem renovação automática.',
        'estado' => 'Selecione o estado do contrato de manutenção.',
        'observacoes' => 'As observações do contrato de manutenção são obrigatórias.'
    ];
 
    foreach ($camposObrigatorios as $campo => $mensagem) {
        if (trim($contrato[$campo] ?? '') === '') {
            $erros[] = $mensagem;
        }
    }
 
    if (trim($contrato['codigo'] ?? '') !== '' && !preg_match('/^CON-\d{3,}$/', strtoupper(trim($contrato['codigo'])))) {
        $erros[] = 'O código do contrato de manutenção deve seguir o formato CON-000.';
    }

    if (!empty($contrato['data_inicio']) && !validar_data_formulario($contrato['data_inicio'])) {
        $erros[] = 'A data de início do contrato de manutenção não é válida.';
    }
 
    if (!empty($contrato['data_fim']) && !validar_data_formulario($contrato['data_fim'])) {
        $erros[] = 'A data de fim do contrato de manutenção não é válida.';
    }
 
    if (!empty($contrato['data_inicio']) && !empty($contrato['data_fim']) &&
        validar_data_formulario($contrato['data_inicio']) && validar_data_formulario($contrato['data_fim']) &&
        $contrato['data_fim'] < $contrato['data_inicio']) {
        $erros[] = 'A data de fim do contrato de manutenção não pode ser anterior à data de início.';
    }
 
    if (!empty($contrato['valor_anual']) && (!is_numeric($contrato['valor_anual']) || (float) $contrato['valor_anual'] < 0)) {
        $erros[] = 'O valor anual do contrato de manutenção deve ser um número igual ou superior a zero.';
    }
 
    validar_ficheiros_pdf(obter_ficheiros_grupo('contratoManutencao'), 'PDFs do contrato de manutenção', $erros, true);
}
 
function validar_manutencao($manutencao, &$erros)
{
    $camposObrigatorios = [
        'ultima_manutencao' => 'A data da última manutenção é obrigatória.',
        'proxima_manutencao' => 'A data da próxima manutenção é obrigatória.',
        'estado' => 'Selecione o estado da manutenção.',
        'periodicidade' => 'A periodicidade da manutenção é obrigatória.',
        'responsavel' => 'O responsável da manutenção é obrigatório.',
        'prioridade' => 'Selecione a prioridade da manutenção.'
    ];
 
    foreach ($camposObrigatorios as $campo => $mensagem) {
        if (trim($manutencao[$campo] ?? '') === '') {
            $erros[] = $mensagem;
        }
    }
 
    if (!empty($manutencao['ultima_manutencao']) && !validar_data_formulario($manutencao['ultima_manutencao'])) {
        $erros[] = 'A data da última manutenção não é válida.';
    }
 
    if (!empty($manutencao['proxima_manutencao']) && !validar_data_formulario($manutencao['proxima_manutencao'])) {
        $erros[] = 'A data da próxima manutenção não é válida.';
    }

    if (!empty($manutencao['ultima_manutencao']) && !empty($manutencao['proxima_manutencao']) &&
        validar_data_formulario($manutencao['ultima_manutencao']) && validar_data_formulario($manutencao['proxima_manutencao']) &&
        $manutencao['proxima_manutencao'] < $manutencao['ultima_manutencao']) {
        $erros[] = 'A data da próxima manutenção não pode ser anterior à da última manutenção.';
    }
}
 
function guardar_ficheiros_pdf($ligacao, $ficheiros)
{
    $diretorioUploads = __DIR__ . '/../../../assets/uploads/documentos';
 
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0775, true)) {
        throw new RuntimeException('Não foi possível criar a pasta para guardar os PDFs.');
    }
 
    $stmtFicheiro = $ligacao->prepare("\n        INSERT INTO ficheiros_pdf (\n            nome_original,\n            nome_guardado,\n            caminho_ficheiro,\n            tipo_mime,\n            tamanho_bytes,\n            carregado_por\n        ) VALUES (\n            :nome_original,\n            :nome_guardado,\n            :caminho_ficheiro,\n            :tipo_mime,\n            :tamanho_bytes,\n            :carregado_por\n        )\n    ");
 
    $utilizadorId = $_SESSION['utilizador']['id'] ?? null;
    $utilizadorId = is_numeric($utilizadorId) ? (int) $utilizadorId : null;
    $ficheiroIds = [];
 
    foreach ($ficheiros as $ficheiro) {
        $nomeGuardado = uniqid('pdf_', true) . '.pdf';
        $destino = $diretorioUploads . '/' . $nomeGuardado;
 
        if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
            throw new RuntimeException('Não foi possível guardar o ficheiro "' . $ficheiro['name'] . '".');
        }
 
        $stmtFicheiro->execute([
            ':nome_original' => $ficheiro['name'],
            ':nome_guardado' => $nomeGuardado,
            ':caminho_ficheiro' => 'assets/uploads/documentos/' . $nomeGuardado,
            ':tipo_mime' => 'application/pdf',
            ':tamanho_bytes' => $ficheiro['size'],
            ':carregado_por' => $utilizadorId
        ]);
 
        $ficheiroIds[] = $ligacao->lastInsertId();
    }
 
    return $ficheiroIds;
}
 
function ligar_ficheiros($ligacao, $tabela, $campoId, $idPrincipal, $ficheiroIds)
{
    $tabelasPermitidas = [
        'documento_ficheiros' => 'documento_id',
        'garantia_ficheiros' => 'garantia_id',
        'contrato_ficheiros' => 'contrato_id'
    ];
 
    if (!isset($tabelasPermitidas[$tabela]) || $tabelasPermitidas[$tabela] !== $campoId) {
        throw new RuntimeException('Ligação de ficheiros inválida.');
    }
 
    $stmt = $ligacao->prepare("\n        INSERT INTO {$tabela} (\n            {$campoId},\n            ficheiro_id\n        ) VALUES (\n            :id_principal,\n            :ficheiro_id\n        )\n    ");
 
    foreach ($ficheiroIds as $ficheiroId) {
        $stmt->execute([
            ':id_principal' => $idPrincipal,
            ':ficheiro_id' => $ficheiroId
        ]);
    }
}
 
function inserir_contrato_manutencao($ligacao, $equipamentoId, $contrato)
{
    $tipoContratoId = obter_id_por_nome($ligacao, 'tipos_contrato', 'Manutenção');
    $fornecedorId = obter_id_por_nome($ligacao, 'fornecedores', trim($contrato['fornecedor']));
    $estadoContratoId = obter_id_por_nome($ligacao, 'estados_contrato', trim($contrato['estado']));
 
    if (!$tipoContratoId || !$fornecedorId || !$estadoContratoId) {
        throw new RuntimeException('Não foi possível associar corretamente o contrato de manutenção às tabelas auxiliares.');
    }
 
    $stmt = $ligacao->prepare("\n        INSERT INTO contratos (\n            codigo,\n            designacao,\n            tipo_contrato_id,\n            fornecedor_id,\n            responsavel,\n            data_inicio,\n            data_fim,\n            valor_anual,\n            periodicidade,\n            renovacao_automatica,\n            estado_contrato_id,\n            observacoes\n        ) VALUES (\n            :codigo,\n            :designacao,\n            :tipo_contrato_id,\n            :fornecedor_id,\n            :responsavel,\n            :data_inicio,\n            :data_fim,\n            :valor_anual,\n            :periodicidade,\n            :renovacao_automatica,\n            :estado_contrato_id,\n            :observacoes\n        )\n    ");
 
    $stmt->execute([
        ':codigo' => strtoupper(trim($contrato['codigo'])),
        ':designacao' => trim($contrato['designacao']),
        ':tipo_contrato_id' => $tipoContratoId,
        ':fornecedor_id' => $fornecedorId,
        ':responsavel' => trim((string) ($contrato['responsavel'] ?? '')),
        ':data_inicio' => trim($contrato['data_inicio']),
        ':data_fim' => trim($contrato['data_fim']),
        ':valor_anual' => trim($contrato['valor_anual']),
        ':periodicidade' => trim((string) ($contrato['periodicidade'] ?? '')),
        ':renovacao_automatica' => ($contrato['renovacao_automatica'] ?? '') === 'Sim' ? 1 : 0,
        ':estado_contrato_id' => $estadoContratoId,
        ':observacoes' => preparar_observacoes_contrato($contrato)
    ]);
 
    $contratoId = $ligacao->lastInsertId();
 
    $stmtLigacao = $ligacao->prepare("\n        INSERT INTO contrato_equipamentos (\n            contrato_id,\n            equipamento_id\n        ) VALUES (\n            :contrato_id,\n            :equipamento_id\n        )\n    ");
 
    $stmtLigacao->execute([
        ':contrato_id' => $contratoId,
        ':equipamento_id' => $equipamentoId
    ]);
 
    $ficheiroIds = guardar_ficheiros_pdf($ligacao, obter_ficheiros_grupo('contratoManutencao'));
    ligar_ficheiros($ligacao, 'contrato_ficheiros', 'contrato_id', $contratoId, $ficheiroIds);
 
    return $contratoId;
}
 
function inserir_garantia($ligacao, $equipamentoId, $garantia, $contratoId = null)
{
    $fornecedorId = obter_id_por_nome($ligacao, 'fornecedores', trim($garantia['fornecedor']));
    $estadoGarantiaId = obter_id_por_nome($ligacao, 'estados_garantia', trim($garantia['estado']));
 
    if (!$fornecedorId || !$estadoGarantiaId) {
        throw new RuntimeException('Não foi possível associar corretamente a garantia às tabelas auxiliares.');
    }
 
    $stmt = $ligacao->prepare("\n        INSERT INTO garantias (\n            codigo,\n            designacao,\n            equipamento_id,\n            fornecedor_id,\n            responsavel,\n            contrato_id,\n            data_inicio,\n            data_fim,\n            estado_garantia_id,\n            cobertura,\n            observacoes\n        ) VALUES (\n            :codigo,\n            :designacao,\n            :equipamento_id,\n            :fornecedor_id,\n            :responsavel,\n            :contrato_id,\n            :data_inicio,\n            :data_fim,\n            :estado_garantia_id,\n            :cobertura,\n            :observacoes\n        )\n    ");
 
    $stmt->execute([
        ':codigo' => strtoupper(trim($garantia['codigo'])),
        ':designacao' => trim($garantia['designacao']),
        ':equipamento_id' => $equipamentoId,
        ':fornecedor_id' => $fornecedorId,
        ':responsavel' => trim((string) ($garantia['responsavel'] ?? '')),
        ':contrato_id' => $contratoId,
        ':data_inicio' => trim($garantia['data_inicio']),
        ':data_fim' => trim($garantia['data_fim']),
        ':estado_garantia_id' => $estadoGarantiaId,
        ':cobertura' => trim($garantia['cobertura']),
        ':observacoes' => preparar_observacoes_garantia($garantia)
    ]);
 
    $garantiaId = $ligacao->lastInsertId();
 
    $ficheiroIds = guardar_ficheiros_pdf($ligacao, obter_ficheiros_grupo('garantia'));
    ligar_ficheiros($ligacao, 'garantia_ficheiros', 'garantia_id', $garantiaId, $ficheiroIds);
 
    return $garantiaId;
}
 
function inserir_manutencao($ligacao, $equipamentoId, $manutencao)
{
    $tipoManutencaoId = obter_id_por_nome($ligacao, 'tipos_manutencao', 'Preventiva');
    $estadoManutencaoId = obter_id_por_nome($ligacao, 'estados_manutencao', trim($manutencao['estado']));
    $prioridadeId = obter_id_por_nome($ligacao, 'prioridades_manutencao', trim($manutencao['prioridade']));
 
    if (!$tipoManutencaoId || !$estadoManutencaoId || !$prioridadeId) {
        throw new RuntimeException('Não foi possível associar corretamente a manutenção às tabelas auxiliares.');
    }
 
    $stmt = $ligacao->prepare("\n        INSERT INTO manutencoes (\n            equipamento_id,\n            tipo_manutencao_id,\n            ultima_manutencao,\n            proxima_manutencao,\n            periodicidade,\n            estado_manutencao_id,\n            prioridade_id,\n            responsavel,\n            observacoes\n        ) VALUES (\n            :equipamento_id,\n            :tipo_manutencao_id,\n            :ultima_manutencao,\n            :proxima_manutencao,\n            :periodicidade,\n            :estado_manutencao_id,\n            :prioridade_id,\n            :responsavel,\n            :observacoes\n        )\n    ");
 
    $stmt->execute([
        ':equipamento_id' => $equipamentoId,
        ':tipo_manutencao_id' => $tipoManutencaoId,
        ':ultima_manutencao' => trim($manutencao['ultima_manutencao']),
        ':proxima_manutencao' => trim($manutencao['proxima_manutencao']),
        ':periodicidade' => trim($manutencao['periodicidade']),
        ':estado_manutencao_id' => $estadoManutencaoId,
        ':prioridade_id' => $prioridadeId,
        ':responsavel' => trim($manutencao['responsavel']),
        ':observacoes' => 'Registo criado na inserção do equipamento.'
    ]);
}
 
function validar_opcoes_bd($ligacao, &$erros, $garantia, $contrato, $manutencao)
{
    if (!obter_id_por_nome($ligacao, 'fornecedores', trim($garantia['fornecedor'] ?? ''))) {
        $erros[] = 'O fornecedor da garantia não existe na base de dados.';
    }
 
    if (!obter_id_por_nome($ligacao, 'estados_garantia', trim($garantia['estado'] ?? ''))) {
        $erros[] = 'O estado da garantia não existe na base de dados.';
    }
 
    if (!obter_id_por_nome($ligacao, 'fornecedores', trim($contrato['fornecedor'] ?? ''))) {
        $erros[] = 'O fornecedor do contrato de manutenção não existe na base de dados.';
    }
 
    if (!obter_id_por_nome($ligacao, 'estados_contrato', trim($contrato['estado'] ?? ''))) {
        $erros[] = 'O estado do contrato de manutenção não existe na base de dados.';
    }
 
    if (!obter_id_por_nome($ligacao, 'estados_manutencao', trim($manutencao['estado'] ?? ''))) {
        $erros[] = 'O estado da manutenção não existe na base de dados.';
    }
 
    if (!obter_id_por_nome($ligacao, 'prioridades_manutencao', trim($manutencao['prioridade'] ?? ''))) {
        $erros[] = 'A prioridade da manutenção não existe na base de dados.';
    }
}
 
$ligacao = ligar_base_dados();
 
if ($ligacao === null) {
    $erroSistema = 'Não foi possível ligar à base de dados.';
} else {
    try {
        $categorias = obter_opcoes($ligacao, 'SELECT id, nome FROM categorias_equipamento ORDER BY nome');
        $estadosEquipamento = obter_opcoes($ligacao, 'SELECT id, nome FROM estados_equipamento ORDER BY nome');
        $criticidades = obter_opcoes($ligacao, 'SELECT id, nome FROM criticidades ORDER BY id');
        $tiposEntrada = obter_opcoes($ligacao, 'SELECT id, nome FROM tipos_entrada ORDER BY nome');
        $fornecedores = obter_opcoes(
            $ligacao,
            'SELECT f.id, f.nome, tf.nome AS tipo
             FROM fornecedores f
             INNER JOIN tipos_fornecedor tf ON tf.id = f.tipo_fornecedor_id
             ORDER BY f.nome'
        );
        $localizacoes = obter_opcoes($ligacao, 'SELECT id, codigo, nome, numero_andares FROM localizacoes ORDER BY codigo');
        $equipamentosPai = obter_opcoes($ligacao, 'SELECT id, codigo, designacao FROM equipamentos ORDER BY codigo');
 
        /* Listas de valores controlados (lookups) carregadas da base de dados */
        $areasDocumento = obter_opcoes($ligacao, 'SELECT id, nome FROM areas_documento ORDER BY id');
        $estadosDocumento = obter_opcoes($ligacao, 'SELECT id, nome FROM estados_documento ORDER BY id');
        $ligacao->exec("INSERT IGNORE INTO estados_garantia (nome) VALUES ('Expirado')");
        $ligacao->exec("INSERT IGNORE INTO estados_garantia (nome) VALUES ('Cancelado')");
        $ligacao->exec("INSERT IGNORE INTO estados_contrato (nome) VALUES ('Cancelado')");
        $estadosGarantia = obter_opcoes($ligacao, "SELECT id, CASE WHEN nome = 'Expirada' THEN 'Expirado' ELSE nome END AS nome FROM estados_garantia WHERE nome <> 'Expirada' ORDER BY id");
        $estadosContrato = obter_opcoes($ligacao, "SELECT id, nome FROM estados_contrato WHERE nome NOT IN ('Inválido', 'Invalido') ORDER BY id");
        $contratosExistentes = obter_opcoes($ligacao, 'SELECT id, codigo, designacao FROM contratos ORDER BY codigo');
        $estadosManutencao = obter_opcoes($ligacao, 'SELECT id, nome FROM estados_manutencao ORDER BY id');
        $prioridadesManutencao = obter_opcoes($ligacao, 'SELECT id, nome FROM prioridades_manutencao ORDER BY id');
        $tiposDocumento = obter_opcoes($ligacao, 'SELECT id, nome FROM tipos_documento ORDER BY nome');
        $tiposContrato = obter_opcoes($ligacao, 'SELECT id, nome FROM tipos_contrato ORDER BY nome');

        /* Proximos codigos sequenciais (so para pre-preencher quando o formulario abre vazio) */
        $proximoCodigoEquipamento = proximo_codigo($ligacao, 'equipamentos', 'codigo', 'EQ', 4);
        $proximoCodigoDocumento = proximo_codigo($ligacao, 'documentos', 'codigo', 'DOC', 4);
        $proximoCodigoGarantia = proximo_codigo($ligacao, 'garantias', 'codigo', 'GAR', 4);
        $proximoCodigoContrato = proximo_codigo($ligacao, 'contratos', 'codigo', 'CON', 4);
    } catch (PDOException $erro) {
        $erroSistema = 'Ocorreu um erro ao carregar os dados do formulário.';
    }
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($valores as $campo => $valorDefeito) {
        $valores[$campo] = trim((string) ($_POST[$campo] ?? $valorDefeito ?? ''));
    }
 
    /* Normalização dos dados principais */
    $valores['codigo_equipamento'] = strtoupper($valores['codigo_equipamento']);
    $valores['numero_serie_equipamento'] = strtoupper($valores['numero_serie_equipamento']);
    $valores['designacao_equipamento'] = ucwords(strtolower($valores['designacao_equipamento']));
    $valores['marca_equipamento'] = ucwords(strtolower($valores['marca_equipamento']));
 
    if ($valores['codigo_equipamento'] === '') {
        $erros[] = 'O código interno é obrigatório.';
    } elseif (!preg_match('/^EQ-\d{3,}$/', $valores['codigo_equipamento'])) {
        $erros[] = 'O código interno deve seguir o formato EQ-0000 (ex: EQ-0001).';
    }
 
    if ($valores['designacao_equipamento'] === '') {
        $erros[] = 'A designação do equipamento é obrigatória.';
    }
 
    if ($valores['categoria_id'] === '') {
        $erros[] = 'Selecione a categoria do equipamento.';
    }
 
    if ($valores['marca_equipamento'] === '') {
        $erros[] = 'A marca do equipamento é obrigatória.';
    }
 
    if ($valores['modelo_equipamento'] === '') {
        $erros[] = 'O modelo do equipamento é obrigatório.';
    }
 
    if ($valores['numero_serie_equipamento'] === '') {
        $erros[] = 'O número de série é obrigatório.';
    }
 
    if ($valores['estado_id'] === '') {
        $erros[] = 'Selecione o estado do equipamento.';
    }
 
    if ($valores['criticidade_id'] === '') {
        $erros[] = 'Selecione a criticidade do equipamento.';
    }
 
    if ($valores['data_aquisicao'] === '') {
        $erros[] = 'A data de aquisição é obrigatória.';
    } elseif (!validar_data_formulario($valores['data_aquisicao'])) {
        $erros[] = 'A data de aquisição não é válida.';
    }
 
    if ($valores['custo_aquisicao'] === '') {
        $erros[] = 'O custo de aquisição é obrigatório.';
    } elseif (!is_numeric($valores['custo_aquisicao']) || (float) $valores['custo_aquisicao'] < 0) {
        $erros[] = 'O custo de aquisição deve ser um número igual ou superior a zero.';
    }
 
    $anoAtual = (int) date('Y') + 1;
 
    if ($valores['ano_fabrico'] === '') {
        $erros[] = 'O ano de fabrico é obrigatório.';
    } elseif (!ctype_digit($valores['ano_fabrico']) || (int) $valores['ano_fabrico'] < 1990 || (int) $valores['ano_fabrico'] > $anoAtual) {
        $erros[] = 'O ano de fabrico deve estar entre 1990 e ' . $anoAtual . '.';
    }
 
    if ($valores['tipo_entrada_id'] === '') {
        $erros[] = 'Selecione o tipo de entrada.';
    }
 
    if ($valores['fornecedor_principal_id'] === '') {
        $erros[] = 'Selecione o fornecedor principal.';
    }
 
    if ($valores['fabricante_id'] === '') {
        $erros[] = 'Selecione o fabricante principal.';
    }
 
    if ($valores['prestador_assistencia_id'] === '') {
        $erros[] = 'Selecione o prestador de assistência técnica.';
    }
 
    if ($valores['componente_equipamento'] === 'Sim' && $valores['equipamento_pai_id'] === '') {
        $erros[] = 'Selecione o equipamento principal.';
    }
 
    if ($valores['tem_consumiveis'] === 'Sim' && $valores['consumiveis_descricao'] === '') {
        $erros[] = 'Indique os consumíveis associados.';
    }
 
    if ($valores['localizacao_id'] === '') {
        $erros[] = 'Selecione a localização principal.';
    }
 
    if ($valores['servico'] === '') {
        $erros[] = 'O departamento ou serviço é obrigatório.';
    }
 
    if ($valores['piso'] === '' || !is_numeric($valores['piso'])) {
        $erros[] = 'O número do andar é obrigatório e deve ser numérico.';
    } elseif ((int) $valores['piso'] < -1) {
        $erros[] = 'O número do andar não pode ser inferior a -1.';
    } elseif ($ligacao !== null) {
        $localizacaoSelecionada = null;

        foreach ($localizacoes as $localizacao) {
            if ((string) $localizacao->id === (string) $valores['localizacao_id']) {
                $localizacaoSelecionada = $localizacao;
                break;
            }
        }

        if ($valores['localizacao_id'] !== '' && $localizacaoSelecionada === null) {
            $erros[] = 'A localização selecionada não existe na base de dados.';
        } elseif ($localizacaoSelecionada !== null && (int) $valores['piso'] > (int) $localizacaoSelecionada->numero_andares) {
            $erros[] = 'O número do andar não pode ser superior ao número de andares da localização selecionada (' . (int) $localizacaoSelecionada->numero_andares . ').';
        }
    }
 
    if ($valores['sala'] === '') {
        $erros[] = 'A sala ou gabinete é obrigatória.';
    }
 
    if ($valores['observacoes_equipamento'] === '') {
        $erros[] = 'As observações gerais são obrigatórias.';
    }
 
    $garantiaPost = $_POST['garantia'] ?? [];
    $contratoManutencaoPost = $_POST['contratoManutencao'] ?? [];
    $manutencaoPost = $_POST['manutencao'] ?? [];
 
    validar_garantia($garantiaPost, $erros);
    validar_contrato_manutencao($contratoManutencaoPost, $erros);
    validar_manutencao($manutencaoPost, $erros);

    /*
     * Coerencia das datas com a data de aquisicao do equipamento:
     * nada relacionado com o equipamento pode comecar antes de ele ser adquirido.
     */
    $dataAquisicao = $valores['data_aquisicao'];
    if (validar_data_formulario($dataAquisicao)) {
        if (!empty($garantiaPost['data_inicio']) && validar_data_formulario($garantiaPost['data_inicio'])
            && $garantiaPost['data_inicio'] < $dataAquisicao) {
            $erros[] = 'A garantia nao pode comecar antes da data de aquisicao do equipamento.';
        }
        if (!empty($contratoManutencaoPost['data_inicio']) && validar_data_formulario($contratoManutencaoPost['data_inicio'])
            && $contratoManutencaoPost['data_inicio'] < $dataAquisicao) {
            $erros[] = 'O contrato de manutencao nao pode comecar antes da data de aquisicao do equipamento.';
        }
        if (!empty($manutencaoPost['ultima_manutencao']) && validar_data_formulario($manutencaoPost['ultima_manutencao'])
            && $manutencaoPost['ultima_manutencao'] < $dataAquisicao) {
            $erros[] = 'A ultima manutencao nao pode ser anterior a data de aquisicao do equipamento.';
        }

        foreach (($_POST['outrosContratos'] ?? []) as $indiceContratoExtra => $contratoExtra) {
            if (!is_array($contratoExtra) || !contrato_extra_tem_dados($contratoExtra)) {
                continue;
            }

            if (!empty($contratoExtra['data_inicio']) && validar_data_formulario($contratoExtra['data_inicio'])
                && $contratoExtra['data_inicio'] < $dataAquisicao) {
                $erros[] = 'Contrato opcional #' . ((int) $indiceContratoExtra + 1) . ': a data de início não pode ser anterior à data de aquisição do equipamento.';
            }
        }
    }
 
    if ($ligacao !== null) {
        validar_opcoes_bd($ligacao, $erros, $garantiaPost, $contratoManutencaoPost, $manutencaoPost);
    }
 
    $documentosPost = $_POST['documentosMinimos'] ?? [];
    validar_documentos_minimos($documentosMinimosObrigatorios, $documentosPost, $erros);

    $outrosContratosPost = $_POST['outrosContratos'] ?? [];
    validar_contratos_extra($outrosContratosPost, $erros, $ligacao);
 
    if (empty($erros) && $ligacao !== null) {
        try {
            $ligacao->beginTransaction();
 
            $sql = "
                INSERT INTO equipamentos (
                    codigo,
                    designacao,
                    categoria_id,
                    marca,
                    modelo,
                    numero_serie,
                    data_aquisicao,
                    ano_fabrico,
                    custo_aquisicao,
                    tipo_entrada_id,
                    estado_id,
                    criticidade_id,
                    fornecedor_principal_id,
                    localizacao_id,
                    servico,
                    piso,
                    sala,
                    equipamento_pai_id,
                    tem_consumiveis,
                    consumiveis_descricao,
                    observacoes
                ) VALUES (
                    :codigo,
                    :designacao,
                    :categoria_id,
                    :marca,
                    :modelo,
                    :numero_serie,
                    :data_aquisicao,
                    :ano_fabrico,
                    :custo_aquisicao,
                    :tipo_entrada_id,
                    :estado_id,
                    :criticidade_id,
                    :fornecedor_principal_id,
                    :localizacao_id,
                    :servico,
                    :piso,
                    :sala,
                    :equipamento_pai_id,
                    :tem_consumiveis,
                    :consumiveis_descricao,
                    :observacoes
                )
            ";
 
            $stmt = $ligacao->prepare($sql);
 
            $stmt->execute([
                ':codigo' => $valores['codigo_equipamento'],
                ':designacao' => $valores['designacao_equipamento'],
                ':categoria_id' => $valores['categoria_id'],
                ':marca' => $valores['marca_equipamento'],
                ':modelo' => $valores['modelo_equipamento'],
                ':numero_serie' => $valores['numero_serie_equipamento'],
                ':data_aquisicao' => $valores['data_aquisicao'],
                ':ano_fabrico' => $valores['ano_fabrico'],
                ':custo_aquisicao' => $valores['custo_aquisicao'],
                ':tipo_entrada_id' => $valores['tipo_entrada_id'],
                ':estado_id' => $valores['estado_id'],
                ':criticidade_id' => $valores['criticidade_id'],
                ':fornecedor_principal_id' => $valores['fornecedor_principal_id'],
                ':localizacao_id' => $valores['localizacao_id'],
                ':servico' => $valores['servico'],
                ':piso' => $valores['piso'],
                ':sala' => $valores['sala'],
                ':equipamento_pai_id' => $valores['equipamento_pai_id'] !== '' ? $valores['equipamento_pai_id'] : null,
                ':tem_consumiveis' => $valores['tem_consumiveis'] === 'Sim' ? 1 : 0,
                ':consumiveis_descricao' => $valores['tem_consumiveis'] === 'Sim' ? $valores['consumiveis_descricao'] : null,
                ':observacoes' => $valores['observacoes_equipamento']
            ]);
 
            $equipamentoId = $ligacao->lastInsertId();
 
            $sqlFornecedorEquipamento = "
                INSERT INTO equipamento_fornecedores (
                    equipamento_id,
                    fornecedor_id,
                    funcao_fornecedor_id,
                    observacoes
                ) VALUES (
                    :equipamento_id,
                    :fornecedor_id,
                    :funcao_fornecedor_id,
                    :observacoes
                )
            ";
 
            $stmtFornecedor = $ligacao->prepare($sqlFornecedorEquipamento);
 
            $associacoesFornecedores = [
                [
                    'fornecedor_id' => $valores['fornecedor_principal_id'],
                    'funcao_fornecedor_id' => 1,
                    'observacoes' => 'Fornecedor principal'
                ],
                [
                    'fornecedor_id' => $valores['fabricante_id'],
                    'funcao_fornecedor_id' => 2,
                    'observacoes' => 'Fabricante principal'
                ],
                [
                    'fornecedor_id' => $valores['prestador_assistencia_id'],
                    'funcao_fornecedor_id' => 4,
                    'observacoes' => 'Assistência técnica principal'
                ]
            ];
 
            foreach ($associacoesFornecedores as $associacao) {
                $stmtFornecedor->execute([
                    ':equipamento_id' => $equipamentoId,
                    ':fornecedor_id' => $associacao['fornecedor_id'],
                    ':funcao_fornecedor_id' => $associacao['funcao_fornecedor_id'],
                    ':observacoes' => $associacao['observacoes']
                ]);
            }
 
            /*
             * Fornecedores adicionais selecionados nos checkboxes.
             * Vêm por NOME, por isso resolvemos o id pela base de dados.
             * Usamos INSERT IGNORE para não falhar se a associação já existir.
             */
            $fornecedoresAdicionais = $_POST['fornecedoresAssociadosEquipamento'] ?? [];
 
            if (!empty($fornecedoresAdicionais)) {
                $stmtFornecedorAdicional = $ligacao->prepare("
                    INSERT IGNORE INTO equipamento_fornecedores (
                        equipamento_id,
                        fornecedor_id,
                        funcao_fornecedor_id,
                        observacoes
                    ) VALUES (
                        :equipamento_id,
                        :fornecedor_id,
                        :funcao_fornecedor_id,
                        :observacoes
                    )
                ");
 
                foreach ($fornecedoresAdicionais as $nomeFornecedorAdicional) {
                    $fornecedorAdicionalId = obter_id_por_nome($ligacao, 'fornecedores', trim($nomeFornecedorAdicional));
 
                    if ($fornecedorAdicionalId) {
                        $stmtFornecedorAdicional->execute([
                            ':equipamento_id' => $equipamentoId,
                            ':fornecedor_id' => $fornecedorAdicionalId,
                            ':funcao_fornecedor_id' => 3, // Distribuidor / fornecedor adicional
                            ':observacoes' => 'Fornecedor adicional associado'
                        ]);
                    }
                }
            }
 
            inserir_documentos_minimos($ligacao, $equipamentoId, $documentosMinimosObrigatorios, $documentosPost);
            inserir_documentos_extra($ligacao, $equipamentoId, $_POST['outrosDocumentos'] ?? []);
            inserir_contratos_extra($ligacao, $equipamentoId, $outrosContratosPost);
 
            $contratoManutencaoId = inserir_contrato_manutencao($ligacao, $equipamentoId, $contratoManutencaoPost);
            inserir_garantia($ligacao, $equipamentoId, $garantiaPost, $contratoManutencaoId);
            inserir_manutencao($ligacao, $equipamentoId, $manutencaoPost);
 
            $ligacao->commit();
 
            header('Location: equipamentos.php?sucesso=1');
            exit;
        } catch (Throwable $erro) {
            if ($ligacao->inTransaction()) {
                $ligacao->rollBack();
            }
 
            if ($erro instanceof PDOException && $erro->getCode() === '23000') {
                $erroSistema = 'Não foi possível guardar: já existe um código, número de série ou código de documento repetido.';
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
                    <h4 class="fw-bold mb-1">Novo equipamento</h4>
                    <p class="text-muted small mb-0">Registo de um novo equipamento médico no inventário hospitalar.
                    </p>
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
                    <form id="formEquipamento" action="#" method="post" enctype="multipart/form-data" novalidate>
 
                        <!-- ABAS DO FORMULÁRIO -->
                        <ul class="nav nav-pills abas-equipamento mb-4" id="abasEquipamento" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="aba-dados-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-dados" type="button" role="tab">
                                    <span class="aba-numero">1</span>
                                    <span>
                                        <strong>Dados principais</strong>
                                        <small>Identificação do equipamento</small>
                                    </span>
                                </button>
                            </li>
 
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-localizacao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-localizacao" type="button" role="tab">
                                    <span class="aba-numero">2</span>
                                    <span>
                                        <strong>Localização</strong>
                                        <small>Serviço, piso e sala</small>
                                    </span>
                                </button>
                            </li>
 
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-documentacao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-documentacao" type="button" role="tab">
                                    <span class="aba-numero">3</span>
                                    <span>
                                        <strong>Documentação</strong>
                                        <small>Manuais e certificados PDF</small>
                                    </span>
                                </button>
                            </li>
 
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-garantia-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-garantia" type="button" role="tab">
                                    <span class="aba-numero">4</span>
                                    <span>
                                        <strong>Garantia e contrato</strong>
                                        <small>Associação e PDFs</small>
                                    </span>
                                </button>
                            </li>
 
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="aba-manutencao-tab" data-bs-toggle="tab"
                                    data-bs-target="#aba-manutencao" type="button" role="tab">
                                    <span class="aba-numero">5</span>
                                    <span>
                                        <strong>Manutenção</strong>
                                        <small>Preventiva e prioridade</small>
                                    </span>
                                </button>
                            </li>
                        </ul>
 
                        <!-- CONTEÚDO DAS ABAS -->
                        <div class="tab-content">
 
                            <!-- DADOS PRINCIPAIS -->
                            <div class="tab-pane fade show active" id="aba-dados" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Dados do Equipamento</h6>
                                        <p class="text-muted small mb-0">Registe a identificação, características e entidades associadas ao equipamento.</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="codigoEquipamento" class="form-label">Código </label>
                                        <input type="text" class="form-control" id="codigoEquipamento" name="codigo_equipamento" placeholder="Ex: EQ-0001"
                                            value="<?php echo htmlspecialchars($valores['codigo_equipamento'] !== '' ? $valores['codigo_equipamento'] : $proximoCodigoEquipamento); ?>">
                                    </div>
 
                                    <div class="col-md-8">
                                        <label for="designacaoEquipamento" class="form-label">Designação </label>
                                        <input type="text" class="form-control" id="designacaoEquipamento" name="designacao_equipamento"
                                            value="<?php echo valor_formulario('designacao_equipamento', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="categoriaEquipamento" class="form-label">Categoria </label>
                                        <select class="form-select" id="categoriaEquipamento" name="categoria_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($categorias as $categoria): ?>
                                                <option value="<?php echo htmlspecialchars($categoria->id); ?>" <?php echo selected_formulario($valores['categoria_id'], $categoria->id); ?>>
                                                    <?php echo htmlspecialchars($categoria->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="marcaEquipamento" class="form-label">Marca </label>
                                        <input type="text" class="form-control" id="marcaEquipamento" name="marca_equipamento"
                                            value="<?php echo valor_formulario('marca_equipamento', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="modeloEquipamento" class="form-label">Modelo </label>
                                        <input type="text" class="form-control" id="modeloEquipamento" name="modelo_equipamento"
                                            value="<?php echo valor_formulario('modelo_equipamento', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="numeroSerieEquipamento" class="form-label">N.º de série </label>
                                        <input type="text" class="form-control" id="numeroSerieEquipamento" name="numero_serie_equipamento"
                                            value="<?php echo valor_formulario('numero_serie_equipamento', $valores); ?>" placeholder="Ex: SN-1234, ABC123 ou outro">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="estadoEquipamento" class="form-label">Estado </label>
                                        <select class="form-select" id="estadoEquipamento" name="estado_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($estadosEquipamento as $estado): ?>
                                                <option value="<?php echo htmlspecialchars($estado->id); ?>" <?php echo selected_formulario($valores['estado_id'], $estado->id); ?>>
                                                    <?php echo htmlspecialchars($estado->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="criticidadeEquipamento" class="form-label">Criticidade</label>
                                        <select class="form-select" id="criticidadeEquipamento" name="criticidade_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($criticidades as $criticidade): ?>
                                                <option value="<?php echo htmlspecialchars($criticidade->id); ?>" <?php echo selected_formulario($valores['criticidade_id'], $criticidade->id); ?>>
                                                    <?php echo htmlspecialchars($criticidade->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                     <div class="col-md-4">
                                        <label for="dataAquisicaoEquipamento" class="form-label">Data de aquisição</label>
                                        <input type="text" class="form-control flatpickr-data" id="dataAquisicaoEquipamento" name="data_aquisicao"
                                            value="<?php echo valor_formulario('data_aquisicao', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="custoAquisicaoEquipamento" class="form-label">Custo de aquisição
                                            (€)</label>
                                        <input type="number" class="form-control" id="custoAquisicaoEquipamento" name="custo_aquisicao" min="0"
                                            step="0.01" value="<?php echo valor_formulario('custo_aquisicao', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="anoFabricoEquipamento" class="form-label">Ano de fabrico</label>
                                        <input type="number" class="form-control" id="anoFabricoEquipamento" name="ano_fabrico" min="1990"
                                            max="2026" step="1" value="<?php echo valor_formulario('ano_fabrico', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="tipoEntradaEquipamento" class="form-label">Tipo de entrada</label>
                                        <select class="form-select" id="tipoEntradaEquipamento" name="tipo_entrada_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($tiposEntrada as $tipoEntrada): ?>
                                                <option value="<?php echo htmlspecialchars($tipoEntrada->id); ?>" <?php echo selected_formulario($valores['tipo_entrada_id'], $tipoEntrada->id); ?>>
                                                    <?php echo htmlspecialchars($tipoEntrada->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                    <div class="col-12">
                                        <label for="observacoesEquipamento" class="form-label">Observações
                                            gerais</label>
                                        <textarea class="form-control" id="observacoesEquipamento" name="observacoes_equipamento" rows="3"><?php echo valor_formulario('observacoes_equipamento', $valores); ?></textarea>
                                    </div>
 
                                    <div class="col-12 mt-2">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Entidades associadas ao equipamento</h6>
                                        <p class="text-muted small mb-0">Registe o fornecedor principal, fabricante, prestador de assistência técnica e restantes fornecedores associados.</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fornecedorEquipamento" class="form-label">Fornecedor principal</label>
                                        <select class="form-select" id="fornecedorEquipamento" name="fornecedor_principal_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($fornecedores as $fornecedor): ?>
                                                <option value="<?php echo htmlspecialchars($fornecedor->id); ?>" <?php echo selected_formulario($valores['fornecedor_principal_id'], $fornecedor->id); ?>>
                                                    <?php echo htmlspecialchars($fornecedor->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="fabricanteEquipamento" class="form-label">Fabricante principal</label>
                                        <select class="form-select" id="fabricanteEquipamento" name="fabricante_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($fornecedores as $fornecedor): ?>
                                                <option value="<?php echo htmlspecialchars($fornecedor->id); ?>" <?php echo selected_formulario($valores['fabricante_id'], $fornecedor->id); ?>>
                                                    <?php echo htmlspecialchars($fornecedor->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="prestadorAssistenciaEquipamento" class="form-label">Prestador de assistência técnica principal</label>
                                        <select class="form-select" id="prestadorAssistenciaEquipamento" name="prestador_assistencia_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($fornecedores as $fornecedor): ?>
                                                <option value="<?php echo htmlspecialchars($fornecedor->id); ?>" <?php echo selected_formulario($valores['prestador_assistencia_id'], $fornecedor->id); ?>>
                                                    <?php echo htmlspecialchars($fornecedor->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                            
                                    </div>
 
                                    <div class="col-12">
                                        <label for="pesquisaFornecedoresAssociadosEquipamento" class="form-label">Fornecedores associados adicionais</label>
                                        <input type="search" class="form-control mb-2 pesquisa-fornecedores-associados"
                                            id="pesquisaFornecedoresAssociadosEquipamento"
                                            placeholder="Pesquisar fornecedor, fabricante, assistência técnica ou consumíveis..."
                                            data-fornecedores-container="listaFornecedoresAssociadosEquipamento">
 
                                        <div class="border rounded p-3" id="listaFornecedoresAssociadosEquipamento">
                                            <div class="row g-2">
                                                <?php if (empty($fornecedores)): ?>
                                                    <div class="col-12">
                                                        <p class="text-muted small mb-0">Ainda não existem fornecedores registados. Crie fornecedores na área de Fornecedores para os poder associar aqui.</p>
                                                    </div>
                                                <?php else: ?>
                                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                                        <?php
                                                            $fornNome = htmlspecialchars($fornecedor->nome);
                                                            $fornTipo = htmlspecialchars($fornecedor->tipo ?? '');
                                                            $checkboxId = 'fornecedorAssociado' . preg_replace('/[^A-Za-z0-9]/', '', $fornecedor->nome) . 'Equipamento';
                                                            $associadosSelecionados = $_POST['fornecedoresAssociadosEquipamento'] ?? [];
                                                            $estaSelecionado = in_array($fornecedor->nome, $associadosSelecionados, true) ? 'checked' : '';
                                                        ?>
                                                        <div class="col-md-6 fornecedor-associado-item" data-fornecedor-item="<?php echo $fornNome . ' ' . $fornTipo; ?>">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="fornecedoresAssociadosEquipamento[]"
                                                                    id="<?php echo $checkboxId; ?>"
                                                                    value="<?php echo $fornNome; ?>" <?php echo $estaSelecionado; ?>>
                                                                <label class="form-check-label" for="<?php echo $checkboxId; ?>">
                                                                    <strong><?php echo $fornNome; ?></strong><br>
                                                                    <span class="text-muted small"><?php echo $fornTipo; ?></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-text">Pode selecionar vários fornecedores adicionais associados ao equipamento.</div>
                                    </div>
 
                                    <div class="col-12 mt-2">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Relações e consumíveis</h6>
                                        <p class="text-muted small mb-0">
                                            Indique se este equipamento pertence a outro equipamento e se utiliza
                                            consumíveis.
                                        </p>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="componenteEquipamento" class="form-label">É componente de outro
                                            equipamento?</label>
                                        <select class="form-select" id="componenteEquipamento" name="componente_equipamento">
                                            <option value="Não" <?php echo selected_formulario($valores['componente_equipamento'], 'Não'); ?>>Não</option>
                                            <option value="Sim" <?php echo selected_formulario($valores['componente_equipamento'], 'Sim'); ?>>Sim</option>
                                        </select>
                                    </div>
 
                                    <div class="col-md-8 d-none" id="grupoEquipamentoPai">
                                        <label for="equipamentoPaiEquipamento" class="form-label">Equipamento
                                            principal</label>
                                        <select class="form-select" id="equipamentoPaiEquipamento" name="equipamento_pai_id">
                                            <option value="">Selecionar equipamento principal</option>
                                            <?php foreach ($equipamentosPai as $equipamentoPai): ?>
                                                <option value="<?php echo htmlspecialchars($equipamentoPai->id); ?>" <?php echo selected_formulario($valores['equipamento_pai_id'], $equipamentoPai->id); ?>>
                                                    <?php echo htmlspecialchars($equipamentoPai->codigo . ' — ' . $equipamentoPai->designacao); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="temConsumiveisEquipamento" class="form-label">Tem
                                            consumíveis?</label>
                                        <select class="form-select" id="temConsumiveisEquipamento" name="tem_consumiveis">
                                            <option value="Não" <?php echo selected_formulario($valores['tem_consumiveis'], 'Não'); ?>>Não</option>
                                            <option value="Sim" <?php echo selected_formulario($valores['tem_consumiveis'], 'Sim'); ?>>Sim</option>
                                        </select>
                                    </div>
 
                                    <div class="col-md-8 d-none" id="grupoConsumiveisEquipamento">
                                        <label for="consumiveisEquipamento" class="form-label">Consumíveis
                                            associados</label>
                                        <textarea class="form-control" id="consumiveisEquipamento" name="consumiveis_descricao" rows="3"
                                            placeholder="Ex: elétrodos ECG, sensores SpO2, papel térmico"><?php echo valor_formulario('consumiveis_descricao', $valores); ?></textarea>
                                        <div class="form-text">Separe os consumíveis por vírgulas ou por linhas.
                                        </div>
                                    </div>
                                </div>
                            </div>
 
                            <!-- LOCALIZAÇÃO -->
                            <div class="tab-pane fade" id="aba-localizacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Localização física do equipamento</h6>
                                        <p class="text-muted small mb-0">
                                            Associe o equipamento a uma localização principal e indique a posição específica dentro dessa localização.
                                        </p>
                                    </div>
 
                                    <div class="col-md-6">
                                        <label for="localizacaoEquipamento" class="form-label">Localização principal</label>
                                        <select class="form-select" id="localizacaoEquipamento" name="localizacao_id">
                                            <option value="">Selecionar</option>
                                            <?php foreach ($localizacoes as $localizacao): ?>
                                                <option value="<?php echo htmlspecialchars($localizacao->id); ?>" <?php echo selected_formulario($valores['localizacao_id'], $localizacao->id); ?>>
                                                    <?php echo htmlspecialchars($localizacao->codigo . ' — ' . $localizacao->nome); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
 
                                    <div class="col-md-6">
                                        <label for="departamentoServicoEquipamento" class="form-label">Departamento / serviço</label>
                                        <input type="text" class="form-control" id="departamentoServicoEquipamento"
                                            name="servico" value="<?php echo valor_formulario('servico', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-6">
                                        <label for="pisoEquipamento" class="form-label">N.º do andar</label>
                                        <input type="number" class="form-control" id="pisoEquipamento" name="piso"
                                            min="-1" step="1" value="<?php echo valor_formulario('piso', $valores); ?>">
                                    </div>
 
                                    <div class="col-md-6">
                                        <label for="salaGabineteEquipamento" class="form-label">Sala / gabinete</label>
                                        <input type="text" class="form-control" id="salaGabineteEquipamento"
                                            name="sala" value="<?php echo valor_formulario('sala', $valores); ?>">
                                    </div>
                                </div>
                            </div>
 
                            <!-- DOCUMENTAÇÃO -->
                            <div class="tab-pane fade" id="aba-documentacao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Documentação mínima necessária</h6>
                                        <p class="text-muted small mb-0">
                                            Preencha os dados dos documentos obrigatórios e associe pelo menos um PDF a cada documento.
                                        </p>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de utilizador
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoManualUtilizadorEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['ManualUtilizador']['codigo']) ? $_POST['documentosMinimos']['ManualUtilizador']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 0)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoManualUtilizadorEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoManualUtilizadorEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][tipo]" value="Manual de utilizador" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoManualUtilizadorEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoManualUtilizadorEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoManualUtilizadorEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoManualUtilizadorEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoManualUtilizadorEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoManualUtilizadorEquipamento"
                                                       
                                                        name="documentosMinimos[ManualUtilizador][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoManualUtilizadorEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoManualUtilizadorEquipamento"
                                                        name="documentosMinimos[ManualUtilizador][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoManualUtilizadorEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoManualUtilizadorEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[ManualUtilizador][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroManualUtilizadorEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroManualUtilizadorEquipamento"
                                                    name="documentosMinimos[ManualUtilizador][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaManualUtilizadorEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaManualUtilizadorEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Manual de serviço
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoManualServicoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['ManualServico']['codigo']) ? $_POST['documentosMinimos']['ManualServico']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 1)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoManualServicoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoManualServicoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][tipo]" value="Manual de serviço" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoManualServicoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoManualServicoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoManualServicoEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoManualServicoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoManualServicoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoManualServicoEquipamento"
                                                       
                                                        name="documentosMinimos[ManualServico][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoManualServicoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoManualServicoEquipamento"
                                                        name="documentosMinimos[ManualServico][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoManualServicoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoManualServicoEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[ManualServico][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroManualServicoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroManualServicoEquipamento"
                                                    name="documentosMinimos[ManualServico][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaManualServicoEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaManualServicoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Certificado de calibração
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['CertificadoCalibracao']['codigo']) ? $_POST['documentosMinimos']['CertificadoCalibracao']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 2)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoCertificadoCalibracaoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][tipo]" value="Certificado de calibração" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoCertificadoCalibracaoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoCertificadoCalibracaoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoCertificadoCalibracaoEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoCertificadoCalibracaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoCertificadoCalibracaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoCertificadoCalibracaoEquipamento"
                                                       
                                                        name="documentosMinimos[CertificadoCalibracao][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoCertificadoCalibracaoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoCertificadoCalibracaoEquipamento"
                                                        name="documentosMinimos[CertificadoCalibracao][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoCertificadoCalibracaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoCertificadoCalibracaoEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[CertificadoCalibracao][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroCertificadoCalibracaoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroCertificadoCalibracaoEquipamento"
                                                    name="documentosMinimos[CertificadoCalibracao][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaCertificadoCalibracaoEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaCertificadoCalibracaoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Fatura ou guia de aquisição
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['FaturaGuiaAquisicao']['codigo']) ? $_POST['documentosMinimos']['FaturaGuiaAquisicao']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 3)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][tipo]" value="Fatura ou guia de aquisição" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoFaturaGuiaAquisicaoEquipamento"
                                                       
                                                        name="documentosMinimos[FaturaGuiaAquisicao][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoFaturaGuiaAquisicaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoFaturaGuiaAquisicaoEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[FaturaGuiaAquisicao][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroFaturaGuiaAquisicaoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroFaturaGuiaAquisicaoEquipamento"
                                                    name="documentosMinimos[FaturaGuiaAquisicao][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaFaturaGuiaAquisicaoEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaFaturaGuiaAquisicaoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Declaração de conformidade
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['DeclaracaoConformidade']['codigo']) ? $_POST['documentosMinimos']['DeclaracaoConformidade']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 4)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][tipo]" value="Declaração de conformidade" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoDeclaracaoConformidadeEquipamento"
                                                       
                                                        name="documentosMinimos[DeclaracaoConformidade][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoDeclaracaoConformidadeEquipamento"
                                                        name="documentosMinimos[DeclaracaoConformidade][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoDeclaracaoConformidadeEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoDeclaracaoConformidadeEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[DeclaracaoConformidade][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroDeclaracaoConformidadeEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroDeclaracaoConformidadeEquipamento"
                                                    name="documentosMinimos[DeclaracaoConformidade][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaDeclaracaoConformidadeEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaDeclaracaoConformidadeEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Relatório técnico
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados do documento e ficheiro PDF associado.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoDocumentoRelatorioTecnicoEquipamento" class="form-label">Código</label>
                                                    <input type="text" class="form-control" id="codigoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][codigo]" value="<?php echo htmlspecialchars(!empty($_POST['documentosMinimos']['RelatorioTecnico']['codigo']) ? $_POST['documentosMinimos']['RelatorioTecnico']['codigo'] : codigo_documento_offset($proximoCodigoDocumento, 5)); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="tituloDocumentoRelatorioTecnicoEquipamento" class="form-label">Nome do documento</label>
                                                    <input type="text" class="form-control" id="tituloDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][titulo]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoDocumentoRelatorioTecnicoEquipamento" class="form-label">Tipo de documento</label>
                                                    <input type="text" class="form-control" id="tipoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][tipo]" value="Relatório técnico" readonly>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="areaDocumentoRelatorioTecnicoEquipamento" class="form-label">Área</label>
                                                    <select class="form-select" id="areaDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][area]">
                                                        <?php echo options_lista_nome($areasDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="dataDocumentoRelatorioTecnicoEquipamento" class="form-label">Data do documento</label>
                                                    <input type="text" class="form-control flatpickr-data" id="dataDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][data_documento]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="validadeDocumentoRelatorioTecnicoEquipamento" class="form-label">Validade</label>
                                                    <input type="text" class="form-control flatpickr-data" id="validadeDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][validade]">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoDocumentoRelatorioTecnicoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][estado]">
                                                        <?php echo options_lista_nome($estadosDocumento); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelDocumentoRelatorioTecnicoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelDocumentoRelatorioTecnicoEquipamento"
                                                       
                                                        name="documentosMinimos[RelatorioTecnico][responsavel]">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorDocumentoRelatorioTecnicoEquipamento" class="form-label">Fornecedor associado</label>
                                                    <select class="form-select" id="fornecedorDocumentoRelatorioTecnicoEquipamento"
                                                        name="documentosMinimos[RelatorioTecnico][fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesDocumentoRelatorioTecnicoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesDocumentoRelatorioTecnicoEquipamento"
                                                        rows="2"
                                                        name="documentosMinimos[RelatorioTecnico][observacoes]"></textarea>
                                                </div>
                                            </div>
 
                                            <div class="mt-3">
                                                <label for="ficheiroRelatorioTecnicoEquipamento" class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDF do documento
                                                </label>
                                                <input type="file" class="form-control input-pdf-multiplo"
                                                    id="ficheiroRelatorioTecnicoEquipamento"
                                                    name="documentosMinimos[RelatorioTecnico][ficheiros][]"
                                                    accept="application/pdf,.pdf" multiple
                                                    data-lista="listaRelatorioTecnicoEquipamento">
                                                
                                                <div class="pdf-lista mt-3" id="listaRelatorioTecnicoEquipamento">
                                                    <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12 mt-3">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">Outros documentos</h6>
                                        <p class="text-muted small mb-0">
                                            Área opcional. Pode adicionar vários documentos com o botão abaixo.
                                        </p>
                                    </div>

                                    <div class="col-12">
                                        <div id="containerOutrosDocumentos"></div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnAdicionarOutroDocumento">
                                            <i class="fa-solid fa-plus me-1"></i> Adicionar outro documento
                                        </button>
                                    </div>

                                    <template id="templateOutroDocumento">
                                        <div class="row g-3 border rounded p-3 mb-3 bloco-outro-documento">
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold">Documento adicional</span>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remover-outro-documento">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" class="form-control" name="outrosDocumentos[__IDX__][codigo]">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Tipo de documento</label>
                                                <select class="form-select" name="outrosDocumentos[__IDX__][tipo]">
                                                    <?php echo options_lista_nome($tiposDocumento); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nome do documento</label>
                                                <input type="text" class="form-control" name="outrosDocumentos[__IDX__][titulo]">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Data do documento</label>
                                                <input type="text" class="form-control flatpickr-data" name="outrosDocumentos[__IDX__][data_documento]">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Validade</label>
                                                <input type="text" class="form-control flatpickr-data" name="outrosDocumentos[__IDX__][validade]">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Área</label>
                                                <select class="form-select" name="outrosDocumentos[__IDX__][area]">
                                                    <?php echo options_lista_nome($areasDocumento); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="outrosDocumentos[__IDX__][estado]">
                                                    <?php echo options_lista_nome($estadosDocumento); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Fornecedor associado</label>
                                                <select class="form-select" name="outrosDocumentos[__IDX__][fornecedor]">
                                                    <?php echo options_fornecedores_por_nome($fornecedores, true); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Responsável</label>
                                                <input type="text" class="form-control" name="outrosDocumentos[__IDX__][responsavel]">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="outrosDocumentos[__IDX__][observacoes]" rows="2"></textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs deste documento
                                                </label>
                                                <input type="file" class="form-control" name="outrosDocumentos[__IDX__][ficheiros][]" accept="application/pdf,.pdf" multiple>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>


                            <!-- GARANTIA E CONTRATO -->
                            <div class="tab-pane fade" id="aba-garantia" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Garantias e contratos do equipamento</h6>
                                        <p class="text-muted small mb-0">
                                            Registe a informação completa da garantia, do contrato de manutenção e de outros contratos associados.
                                        </p>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-shield-halved me-1 text-primary"></i>
                                                        Garantia
                                                    </h6>
                                                    <p class="text-muted small mb-0">Dados de acordo com a ficha de detalhes da garantia.</p>
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoGarantiaEquipamento" class="form-label">Código da garantia</label>
                                                    <input type="text" class="form-control" id="codigoGarantiaEquipamento" name="garantia[codigo]" value="<?php echo htmlspecialchars(!empty($_POST['garantia']['codigo']) ? $_POST['garantia']['codigo'] : $proximoCodigoGarantia); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="designacaoGarantiaEquipamento" class="form-label">Designação da garantia</label>
                                                    <input type="text" class="form-control" id="designacaoGarantiaEquipamento" name="garantia[designacao]" value="">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoGarantiaEquipamento" class="form-label">Tipo</label>
                                                    <input type="text" class="form-control" id="tipoGarantiaEquipamento" name="garantia[tipo]" value="Garantia" readonly>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorGarantiaEquipamento" class="form-label">Fornecedor</label>
                                                    <select class="form-select" id="fornecedorGarantiaEquipamento" name="garantia[fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, false); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="equipamentoAssociadoGarantia" class="form-label">Equipamento associado</label>
                                                    <input type="text" class="form-control" id="equipamentoAssociadoGarantia" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelGarantiaEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelGarantiaEquipamento" name="garantia[responsavel]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="inicioGarantiaEquipamento" class="form-label">Data de início</label>
                                                    <input type="text" class="form-control flatpickr-data" id="inicioGarantiaEquipamento" name="garantia[data_inicio]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="fimGarantiaEquipamento" class="form-label">Data de fim</label>
                                                    <input type="text" class="form-control flatpickr-data" id="fimGarantiaEquipamento" name="garantia[data_fim]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoGarantiaEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoGarantiaEquipamento" name="garantia[estado]">
                                                        <?php echo options_lista_nome($estadosGarantia); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="contratoAssociadoGarantia" class="form-label">Contrato associado</label>
                                                    <select class="form-select" id="contratoAssociadoGarantia" name="garantia[contrato_codigo]">
                                                        <option value="" selected>Selecionar</option>
                                                        <?php foreach ($contratosExistentes as $contratoExistente): ?>
                                                            <option value="<?php echo htmlspecialchars($contratoExistente->codigo); ?>">
                                                                <?php echo htmlspecialchars($contratoExistente->codigo . ' — ' . $contratoExistente->designacao); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="coberturaGarantiaEquipamento" class="form-label">Cobertura</label>
                                                    <textarea class="form-control" id="coberturaGarantiaEquipamento" name="garantia[cobertura]" rows="3"></textarea>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesGarantiaEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesGarantiaEquipamento" name="garantia[observacoes]" rows="2"></textarea>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="ficheirosGarantiaEquipamento" class="form-label fw-semibold">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs da garantia
                                                    </label>
                                                    <input type="file" class="form-control input-pdf-multiplo" id="ficheirosGarantiaEquipamento"
                                                        name="garantia[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                        data-lista="listaFicheirosGarantiaEquipamento" data-removivel="true">
                                                    <div class="pdf-lista mt-3" id="listaFicheirosGarantiaEquipamento">
                                                        <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <div class="pdf-upload-card">
                                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        <i class="fa-solid fa-file-contract me-1 text-danger"></i>
                                                        Contrato de manutenção
                                                    </h6>
                                                    <p class="text-muted small mb-0">Contrato obrigatório com os dados usados no módulo de contratos.</p>
                                                </div>
                                                <span class="badge bg-danger-subtle text-danger">Obrigatório</span>
                                            </div>
 
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="codigoContratoManutencaoEquipamento" class="form-label">Código do contrato</label>
                                                    <input type="text" class="form-control" id="codigoContratoManutencaoEquipamento" name="contratoManutencao[codigo]" value="<?php echo htmlspecialchars(!empty($_POST['contratoManutencao']['codigo']) ? $_POST['contratoManutencao']['codigo'] : $proximoCodigoContrato); ?>">
                                                </div>
 
                                                <div class="col-md-5">
                                                    <label for="designacaoContratoManutencaoEquipamento" class="form-label">Designação</label>
                                                    <input type="text" class="form-control" id="designacaoContratoManutencaoEquipamento" name="contratoManutencao[designacao]" value="">
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="tipoContratoManutencaoEquipamento" class="form-label">Tipo de contrato</label>
                                                    <input type="text" class="form-control" id="tipoContratoManutencaoEquipamento" name="contratoManutencao[tipo]" value="Manutenção" readonly>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="fornecedorContratoManutencaoEquipamento" class="form-label">Fornecedor</label>
                                                    <select class="form-select" id="fornecedorContratoManutencaoEquipamento" name="contratoManutencao[fornecedor]">
                                                        <?php echo options_fornecedores_por_nome($fornecedores, false); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="associadoContratoManutencaoEquipamento" class="form-label">Associado a</label>
                                                    <input type="text" class="form-control" id="associadoContratoManutencaoEquipamento" value="Equipamento atual" readonly>
                                                </div>
 
                                                <div class="col-md-4">
                                                    <label for="responsavelContratoManutencaoEquipamento" class="form-label">Responsável</label>
                                                    <input type="text" class="form-control" id="responsavelContratoManutencaoEquipamento" name="contratoManutencao[responsavel]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="inicioContratoManutencaoEquipamento" class="form-label">Data de início</label>
                                                    <input type="text" class="form-control flatpickr-data" id="inicioContratoManutencaoEquipamento" name="contratoManutencao[data_inicio]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="fimContratoManutencaoEquipamento" class="form-label">Data de fim</label>
                                                    <input type="text" class="form-control flatpickr-data" id="fimContratoManutencaoEquipamento" name="contratoManutencao[data_fim]" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="valorContratoManutencaoEquipamento" class="form-label">Valor anual (€)</label>
                                                    <input type="number" class="form-control" id="valorContratoManutencaoEquipamento" name="contratoManutencao[valor_anual]" min="0" step="0.01" value="">
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="periodicidadeContratoManutencaoEquipamento" class="form-label">Periodicidade</label>
                                                    <select class="form-select" id="periodicidadeContratoManutencaoEquipamento" name="contratoManutencao[periodicidade]" >
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Mensal">Mensal</option>
                                                        <option value="Trimestral">Trimestral</option>
                                                        <option value="Semestral">Semestral</option>
                                                        <option value="Anual">Anual</option>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="renovacaoContratoManutencaoEquipamento" class="form-label">Renovação automática</label>
                                                    <select class="form-select" id="renovacaoContratoManutencaoEquipamento" name="contratoManutencao[renovacao_automatica]">
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Sim">Sim</option>
                                                        <option value="Não">Não</option>
                                                    </select>
                                                </div>
 
                                                <div class="col-md-3">
                                                    <label for="estadoContratoManutencaoEquipamento" class="form-label">Estado</label>
                                                    <select class="form-select" id="estadoContratoManutencaoEquipamento" name="contratoManutencao[estado]">
                                                        <?php echo options_lista_nome($estadosContrato); ?>
                                                    </select>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="observacoesContratoManutencaoEquipamento" class="form-label">Observações</label>
                                                    <textarea class="form-control" id="observacoesContratoManutencaoEquipamento" name="contratoManutencao[observacoes]" rows="3"></textarea>
                                                </div>
 
                                                <div class="col-12">
                                                    <label for="ficheirosContratoManutencaoEquipamento" class="form-label fw-semibold">
                                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs do contrato de manutenção
                                                    </label>
                                                    <input type="file" class="form-control input-pdf-multiplo" id="ficheirosContratoManutencaoEquipamento"
                                                        name="contratoManutencao[ficheiros][]" accept="application/pdf,.pdf" multiple
                                                        data-lista="listaFicheirosContratoManutencaoEquipamento" data-removivel="true">
                                                    <div class="pdf-lista mt-3" id="listaFicheirosContratoManutencaoEquipamento">
                                                        <p class="text-muted small mb-0">Nenhum ficheiro selecionado.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
 
                                    <div class="col-12">
                                        <hr class="my-2">
                                        <h6 class="fw-bold mb-1">
                                            <i class="fa-solid fa-folder-plus me-1 text-primary"></i> Outros contratos
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            Área opcional. Pode adicionar vários contratos com o botão abaixo.
                                        </p>
                                    </div>

                                    <div class="col-12">
                                        <div id="containerOutrosContratos"></div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnAdicionarOutroContrato">
                                            <i class="fa-solid fa-plus me-1"></i> Adicionar outro contrato
                                        </button>
                                    </div>

                                    <template id="templateOutroContrato">
                                        <div class="row g-3 border rounded p-3 mb-3 bloco-outro-contrato">
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <span class="fw-semibold">Contrato adicional</span>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remover-outro-contrato">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Código</label>
                                                <input type="text" class="form-control" name="outrosContratos[__IDX__][codigo]">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Tipo de contrato</label>
                                                <select class="form-select" name="outrosContratos[__IDX__][tipo]">
                                                    <?php echo options_lista_nome($tiposContrato); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Designação</label>
                                                <input type="text" class="form-control" name="outrosContratos[__IDX__][designacao]">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Entidade responsável / fornecedor</label>
                                                <select class="form-select" name="outrosContratos[__IDX__][fornecedor]">
                                                    <?php echo options_fornecedores_por_nome($fornecedores, false); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Associado a</label>
                                                <input type="text" class="form-control" name="outrosContratos[__IDX__][associado]" value="Equipamento atual" readonly>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Responsável</label>
                                                <input type="text" class="form-control" name="outrosContratos[__IDX__][responsavel]">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Data de início</label>
                                                <input type="text" class="form-control flatpickr-data" name="outrosContratos[__IDX__][data_inicio]">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Data de fim</label>
                                                <input type="text" class="form-control flatpickr-data" name="outrosContratos[__IDX__][data_fim]">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Valor anual (€)</label>
                                                <input type="number" class="form-control" name="outrosContratos[__IDX__][valor_anual]" min="0" step="0.01">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Periodicidade</label>
                                                <select class="form-select" name="outrosContratos[__IDX__][periodicidade]">
                                                    <option value="" selected>Selecionar</option>
                                                    <option value="Mensal">Mensal</option>
                                                    <option value="Trimestral">Trimestral</option>
                                                    <option value="Semestral">Semestral</option>
                                                    <option value="Anual">Anual</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Renovação automática</label>
                                                <select class="form-select" name="outrosContratos[__IDX__][renovacao_automatica]">
                                                    <option value="">Selecionar</option>
                                                    <option value="Não">Não</option>
                                                    <option value="Sim">Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Estado</label>
                                                <select class="form-select" name="outrosContratos[__IDX__][estado]">
                                                    <?php echo options_lista_nome($estadosContrato); ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Observações</label>
                                                <textarea class="form-control" name="outrosContratos[__IDX__][observacoes]" rows="2"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> PDFs deste contrato
                                                </label>
                                                <input type="file" class="form-control" name="outrosContratos[__IDX__][ficheiros][]" accept="application/pdf,.pdf" multiple>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
 
                        <!-- MANUTENÇÃO -->
                            <div class="tab-pane fade" id="aba-manutencao" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-1">Manutenção</h6>
                                        <p class="text-muted small mb-0">Defina o acompanhamento técnico, periodicidade e prioridade de manutenção do equipamento.</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ultimaManutencaoEquipamento" class="form-label">Última
                                            manutenção</label>
                                        <input type="text" class="form-control flatpickr-data" id="ultimaManutencaoEquipamento" name="manutencao[ultima_manutencao]">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="proximaManutencaoEquipamento" class="form-label">Próxima
                                            manutenção</label>
                                        <input type="text" class="form-control flatpickr-data" id="proximaManutencaoEquipamento" name="manutencao[proxima_manutencao]">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="estadoManutencaoEquipamento" class="form-label">Estado da
                                            manutenção</label>
                                        <select class="form-select" id="estadoManutencaoEquipamento" name="manutencao[estado]">
                                            <?php echo options_lista_nome($estadosManutencao); ?>
                                        </select>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="periodicidadeManutencaoEquipamento"
                                            class="form-label">Periodicidade</label>
                                        <select class="form-select" id="periodicidadeManutencaoEquipamento" name="manutencao[periodicidade]">
                                                        <option value="" selected>Selecionar</option>
                                                        <option value="Mensal">Mensal</option>
                                                        <option value="Trimestral">Trimestral</option>
                                                        <option value="Semestral">Semestral</option>
                                                        <option value="Anual">Anual</option>
                                                    </select>
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="responsavelManutencaoEquipamento"
                                            class="form-label">Responsável</label>
                                        <input type="text" class="form-control" id="responsavelManutencaoEquipamento" name="manutencao[responsavel]">
                                    </div>
 
                                    <div class="col-md-4">
                                        <label for="prioridadeManutencaoEquipamento"
                                            class="form-label">Prioridade</label>
                                        <select class="form-select" id="prioridadeManutencaoEquipamento" name="manutencao[prioridade]">
                                            <?php echo options_lista_nome($prioridadesManutencao); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        <!-- MENSAGEM -->
                        <div id="mensagemEquipamento" class="alert alert-success d-none mt-4">
                            <i class="fa-solid fa-check me-1"></i> Equipamento guardado com sucesso.
                        </div>
 
                        <!-- AÇÕES -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                            <a href="equipamentos.php" class="btn btn-outline-secondary">Cancelar</a>
 
                            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary d-none" id="btnAbaAnteriorEquipamento">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Anterior
                                </button>
 
                                <button type="button" class="btn btn-primary" id="btnAbaSeguinteEquipamento">
                                    Próximo <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
 
                                <button type="submit" class="btn btn-primary d-none" id="btnGuardarEquipamento" name="guardar_equipamento" value="1">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar equipamento
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