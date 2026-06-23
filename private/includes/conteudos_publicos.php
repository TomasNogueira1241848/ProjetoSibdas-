<?php

require_once __DIR__ . '/basedados.php';

$GLOBALS['conteudos_publicos_ultimo_erro'] = '';

function definir_erro_conteudos_publicos($mensagem)
{
    $GLOBALS['conteudos_publicos_ultimo_erro'] = $mensagem;
    error_log('Erro nos conteúdos públicos: ' . $mensagem);
}

function conteudos_publicos_ultimo_erro()
{
    return $GLOBALS['conteudos_publicos_ultimo_erro'] ?? '';
}

function conteudos_publicos_padrao()
{
    return [
        'hero_titulo' => 'Soluções digitais para a gestão hospitalar',
        'hero_subtitulo' => 'Desenvolvemos sistemas de informação especializados para instituições de saúde, com foco na rastreabilidade, segurança e eficiência operacional.',
        'sobre_titulo' => 'Sobre Nós',
        'sobre_texto_1' => 'A MedInfo Solutions é uma empresa portuguesa especializada no desenvolvimento de software para a área da saúde. Fundada em 2015 e sediada no Porto, temos como missão modernizar a gestão tecnológica das instituições de saúde nacionais.',
        'sobre_texto_2' => 'A nossa equipa é composta por engenheiros biomédicos, engenheiros informáticos e especialistas em sistemas de informação hospitalares, o que nos permite desenvolver soluções tecnicamente sólidas e clinicamente adequadas.',
        'sobre_texto_3' => 'Trabalhamos com hospitais públicos e privados, clínicas e centros de saúde em todo o território nacional, com especial foco em sistemas de gestão de inventário e manutenção de equipamentos médicos.',
        'servicos_titulo' => 'Serviços',
        'servicos_texto' => 'Desenvolvemos soluções adaptadas às necessidades específicas de cada instituição.',
        'clientes_titulo' => 'Os Nossos Clientes',
        'clientes_texto' => 'Trabalhamos com algumas das principais instituições de saúde nacionais.',
        'contactos_titulo' => 'Contactos',
        'contactos_texto' => 'Entre em contacto connosco para mais informações ou para agendar uma demonstração.',
        'morada' => "Rua Dr. António Bernardino de Almeida, 431\n4200-072 Porto",
        'telefone' => '+351 222 123 456',
        'email' => 'geral@medinfosolutions.pt',
        'website' => 'www.medinfosolutions.pt',
        'horario' => "2ª a 6ª Feira: 9h — 18h\nSábado, Domingo e Feriados: Encerrado",
        'rodape_texto' => 'MedInfo Solutions © 2025 — Todos os direitos reservados'
    ];
}

function campos_conteudos_publicos()
{
    return array_keys(conteudos_publicos_padrao());
}

function normalizar_conteudos_publicos($registo)
{
    $conteudos = conteudos_publicos_padrao();

    if (!$registo) {
        return $conteudos;
    }

    foreach ($conteudos as $campo => $valorPadrao) {
        if (isset($registo->$campo) && $registo->$campo !== null && $registo->$campo !== '') {
            $conteudos[$campo] = $registo->$campo;
        }
    }

    return $conteudos;
}

function preparar_dados_conteudos_publicos($dados)
{
    $padrao = conteudos_publicos_padrao();
    $conteudos = [];

    foreach ($padrao as $campo => $valorPadrao) {
        $valor = isset($dados[$campo]) ? trim((string) $dados[$campo]) : $valorPadrao;
        $conteudos[$campo] = $valor;
    }

    return $conteudos;
}

function garantir_linha_conteudos_publicos($ligacao)
{
    $existe = (int) $ligacao->query('SELECT COUNT(*) FROM conteudos_publicos WHERE id = 1')->fetchColumn();

    if ($existe > 0) {
        return true;
    }

    $padrao = conteudos_publicos_padrao();
    $campos = campos_conteudos_publicos();
    $placeholders = array_map(function ($campo) {
        return ':' . $campo;
    }, $campos);

    $sql = 'INSERT INTO conteudos_publicos (id, ' . implode(', ', $campos) . ')
            VALUES (1, ' . implode(', ', $placeholders) . ')';

    $consulta = $ligacao->prepare($sql);

    foreach ($padrao as $campo => $valor) {
        $consulta->bindValue(':' . $campo, $valor);
    }

    return $consulta->execute();
}

function obter_conteudos_publicos()
{
    $ligacao = ligar_base_dados();

    if ($ligacao === null) {
        definir_erro_conteudos_publicos('Não foi possível ligar à base de dados.');
        return conteudos_publicos_padrao();
    }

    try {
        garantir_linha_conteudos_publicos($ligacao);

        $consulta = $ligacao->query('SELECT * FROM conteudos_publicos WHERE id = 1 LIMIT 1');
        $registo = $consulta->fetch();
        $ligacao = null;

        return normalizar_conteudos_publicos($registo);
    } catch (PDOException $erro) {
        definir_erro_conteudos_publicos($erro->getMessage());
        $ligacao = null;
        return conteudos_publicos_padrao();
    }
}

function guardar_conteudos_publicos($dados, $utilizadorId = null)
{
    $ligacao = ligar_base_dados();

    if ($ligacao === null) {
        definir_erro_conteudos_publicos('Não foi possível ligar à base de dados.');
        return false;
    }

    try {
        garantir_linha_conteudos_publicos($ligacao);

        $conteudos = preparar_dados_conteudos_publicos($dados);
        $sets = [];

        foreach (campos_conteudos_publicos() as $campo) {
            $sets[] = $campo . ' = :' . $campo;
        }

        $sets[] = 'atualizado_por = :atualizado_por';
        $sets[] = 'atualizado_em = NOW()';

        $sql = 'UPDATE conteudos_publicos SET ' . implode(', ', $sets) . ' WHERE id = 1';
        $consulta = $ligacao->prepare($sql);

        foreach ($conteudos as $campo => $valor) {
            $consulta->bindValue(':' . $campo, $valor);
        }

        $utilizadorId = is_numeric($utilizadorId) ? (int) $utilizadorId : null;
        $consulta->bindValue(':atualizado_por', $utilizadorId, $utilizadorId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $consulta->execute();

        $ligacao = null;
        return true;
    } catch (PDOException $erro) {
        definir_erro_conteudos_publicos($erro->getMessage());
        $ligacao = null;
        return false;
    }
}

function h($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function nl2br_h($valor)
{
    return nl2br(h($valor));
}