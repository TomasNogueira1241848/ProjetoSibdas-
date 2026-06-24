================================================================================
 MEDINFO SOLUTIONS
 Sistema Web de Apoio ao Inventario Hospitalar de Equipamentos Medicos
================================================================================

Unidade Curricular: Sistemas de Informacao e Base de Dados Aplicados a Saude
Curso: Licenciatura em Engenharia Biomedica (LEBIOM)
Instituicao: ISEP - Instituto Superior de Engenharia do Porto
Ano letivo: 2025/2026

Versao da aplicacao: 1.0.0


--------------------------------------------------------------------------------
1. DESCRICAO GERAL
--------------------------------------------------------------------------------

O MedInfo Solutions e uma aplicacao web desenvolvida no ambito do projeto final
da unidade curricular, simulando o trabalho de uma empresa de desenvolvimento
de software especializada em sistemas de informacao para a area da saude.

O projeto e composto por duas componentes distintas:

  - Front Office  -> website institucional da empresa MedInfo Solutions,
                      de acesso publico (apresentacao, servicos e contactos).

  - Back Office   -> aplicacao funcional, de acesso reservado, utilizada
                      para gerir o inventario hospitalar de equipamentos
                      medicos, fornecedores, localizacoes, documentacao
                      tecnica, garantias, contratos e manutencoes.


--------------------------------------------------------------------------------
2. TECNOLOGIAS UTILIZADAS
--------------------------------------------------------------------------------

  Front-end : HTML5, CSS3, Bootstrap, JavaScript, jQuery 3.7.1,
              DataTables (tema Bootstrap 5), Flatpickr, Font Awesome
  Back-end  : PHP (PDO para acesso a base de dados)
  Base de dados : MySQL
  Seguranca : password_hash() / password_verify() (hashing de passwords),
              AES_ENCRYPT/AES_DECRYPT do MySQL (encriptacao de emails),
              OpenSSL AES-256-CBC (encriptacao de identificadores em URL)


--------------------------------------------------------------------------------
3. ESTRUTURA DE DIRETORIOS
--------------------------------------------------------------------------------

  config/
      config.php            -> configuracao global da aplicacao
                                (nome, versao, URL base, ligacao a base de
                                dados e chaves de encriptacao)

  public/
      index.php             -> pagina inicial do Front Office
      login/                -> formulario de autenticacao e logout

  private/
      includes/             -> ficheiros de suporte reutilizados em toda
                                a aplicacao (ligacao a base de dados,
                                funcoes auxiliares, cabecalho, menu,
                                barra lateral, rodape)
      area-reservada/
          index.php          -> dashboard (pagina inicial do Back Office)
          equipamentos/      -> gestao de equipamentos medicos
          fornecedores/      -> gestao de fornecedores
          localizacoes/      -> gestao de localizacoes hospitalares
          documentacao/      -> gestao de documentos tecnicos
          contratos/         -> gestao de garantias e contratos
          conteudos/         -> gestao de conteudos do site publico

  assets/
      css/, js/, img/        -> recursos proprios da aplicacao
      bootstrap/, jquery/,
      fontawesome/,
      flatpickr/             -> bibliotecas de terceiros
      uploads/               -> ficheiros carregados pelos utilizadores
                                (documentacao tecnica associada aos
                                equipamentos)


--------------------------------------------------------------------------------
4. REQUISITOS PARA EXECUCAO
--------------------------------------------------------------------------------

  - Servidor com suporte a PHP (versao 8.x recomendada)
  - Extensao PDO MySQL ativa
  - Servidor de base de dados MySQL
  - Navegador web atualizado (Chrome, Firefox, Edge ou equivalente)


--------------------------------------------------------------------------------
5. INSTALACAO E CONFIGURACAO
--------------------------------------------------------------------------------

  1. Copiar a pasta do projeto para o servidor web (por exemplo, htdocs,
     no caso de ambientes XAMPP/WAMP, ou a pasta publica do servidor).

  2. Criar a base de dados em MySQL e importar o ficheiro de script SQL
     fornecido em anexo ao projeto, que cria todas as tabelas e respetivos
     dados iniciais (tabelas de dominio, agentes de teste, etc.).

  3. Editar o ficheiro config/config.php e definir os seguintes valores
     de acordo com o ambiente de alojamento utilizado:

         MYSQL_HOST       -> endereco do servidor MySQL
         MYSQL_PORT       -> porta do servidor MySQL
         MYSQL_DATABASE   -> nome da base de dados criada
         MYSQL_USERNAME   -> utilizador de acesso a base de dados
         MYSQL_PASSWORD   -> password de acesso a base de dados
         BASE_URL         -> URL base onde a aplicacao ficara acessivel

     Por motivos de seguranca, as credenciais reais utilizadas durante o
     desenvolvimento nao estao incluidas neste documento. Os valores de
     OPENSSL_KEY, OPENSSL_IV e MYSQL_AES_KEY devem ser gerados de forma
     unica para cada ambiente e nunca partilhados publicamente.

  4. Garantir que a pasta assets/uploads/ tem permissoes de escrita,
     de forma a permitir o carregamento de documentos tecnicos.

  5. Aceder a aplicacao atraves do navegador, no endereco definido em
     BASE_URL, para visualizar o Front Office. O acesso ao Back Office
     e feito atraves de public/login/login.php.


--------------------------------------------------------------------------------
6. PERFIS DE UTILIZADOR
--------------------------------------------------------------------------------

  A aplicacao distingue quatro perfis de acesso, com permissoes distintas
  sobre os modulos do sistema:

    - admin                -> acesso total a todos os modulos
    - tecnico               -> gestao de equipamentos, fornecedores,
                               localizacoes, documentacao e contratos
    - profissional_saude    -> apenas consulta, sem permissoes de edicao
    - gestor_logistica      -> gestao de fornecedores, localizacoes,
                               documentacao e contratos

  As credenciais de acesso para teste devem ser consultadas no script de
  base de dados fornecido com o projeto (tabela "agents").


--------------------------------------------------------------------------------
7. FUNCIONALIDADES PRINCIPAIS
--------------------------------------------------------------------------------

  - Autenticacao de utilizadores com controlo de acesso por perfil
  - Dashboard com indicadores de sintese do parque tecnologico
  - Gestao de equipamentos medicos (inserir, listar, editar, consultar
    ficha detalhada e remover), incluindo a representacao de relacoes
    entre um equipamento e os respetivos componentes
  - Gestao de fornecedores, com distincao por tipo (fabricante,
    distribuidor, assistencia tecnica, fornecedor de consumiveis)
  - Gestao de localizacoes hospitalares
  - Gestao de documentacao tecnica associada a equipamentos
  - Gestao de garantias e contratos de manutencao
  - Pesquisa e filtragem de equipamentos por multiplos criterios
  - Gestao de conteudos do website institucional (area publica)
  - Receção de mensagens de contacto submetidas pelo website publico


--------------------------------------------------------------------------------
8. AUTORIA
--------------------------------------------------------------------------------

  Projeto desenvolvido individualmente no ambito da unidade curricular de
  Sistemas de Informacao e Base de Dados Aplicados a Saude, LEBIOM,
  ISEP, ano letivo 2025/2026, por Tomás Filipe Ribeiro Nogueira 1241848

================================================================================