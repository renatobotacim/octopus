<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt-br">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta charset="utf-80 "/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <title>	Prefeitura Municipal de Venda Nova do Imigrante</title>
        <link rel="icon" href="componentes/imagens/favicon.ico"/>

        <meta name="language" content="Portuguese" />
        <meta name="description" content="A primeira colonização do vale do rio Castelo, afluente do Itapemirim, se deu no início do domínio português, através de entradistas que buscavam as tão afamadas minas de ouro e de esmeraldas." />
        <meta name="Keywords" content="venda nova, prefeitura venda nova, venda nova do imigrante, vni, pmvni" />
        <meta name="reply-to" content="comunicacao@vendanova.es.gov.br" />
        <meta name="copyright" content="PMVNI" />
        <meta name="category" content="Internet" />
        <meta name="Robots" content="Index,Follow" />
        <meta name="author" content="Infire Soluções Digitais" />
        <meta name="Revisit-After" content="1 days" /></head>


    <!-- Bootstrap CSS -->
    <link href="componentes/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="componentes/js/bootstrap.bundle.min.js"></script>

    <!-- jquery -->
    <script src="componentes/js/jquery-3.6.0.slim.min.js" type="text/javascript"></script>


    <!-- Font Awesome -->
    <link href="componentes/fremeworks/fontawesome/css/all.css" rel="stylesheet"/> <!--load all styles -->

    <link rel="preconnect" href="https://fonts.gstatic.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet"/>

    <!-- Estilo CSS -->
    <link href="componentes/css/estilo.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="componentes/js/script.js"></script>
    <link rel="stylesheet" href="componentes/css/estiloAssessibilidade.css"/>
    <script type="text/javascript" src="componentes/js/cookie.js"></script>


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-90201549-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-90201549-1');
    </script>

</head>
<?php
require_once('componentes/_app/Config.inc.php');
?>

<body>
    <header>
        <div id="navAcessibilidade">
            <div class="container">

                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li><a class="text2" href="https://falabr.cgu.gov.br/publico/ES/VendaNovadoImigrante/Manifestacao/RegistrarManifestacao" target="_black">e-OUV</a></li>
                            <li><a class="text2" href="https://vendanovadoimigrante-es.portaltp.com.br/" target="_black">TRANSPARÊNCIA</a></li>
                            <li><a class="text2" href="./servicos/legislacoes.php" target="_black">LEGISLAÇÃO</a></li>
                            <li><a class="text2" href="covid.php" >CORONAVÍRUS (COVID-19)</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <a href="https://vendanovadoimigrante-es.portaltp.com.br/acessibilidade.aspx"><div class="test" ><i class="fa fa-wheelchair" aria-hidden="true"></i></div></a>
                        <a href="https://www.nvaccess.org"><div class="test" ><i class="fa fa-assistive-listening-systems" aria-hidden="true"></i></div></a>
                        <a href="https://www.gov.br/governodigital/pt-br/vlibras"><div class="test"><i class="fa fa-sign-language" aria-hidden="true"></i></div></a>
                        <div class="test" title="Acesso ao Web Mail"><a target="_blank" class="text2" href="http://webmail.vendanova.es.gov.br/"><i class="fas fa-envelope"></i></a></div>
                        <div class="test" type="button" title="Aplicar contraste" onclick="toggleConstrast()"><i class="fas fa-adjust"></i></div>
                        <div class="test" name="decrease-font" id="decrease-font" title="Diminuir fonte"><i class="fas fa-font"></i><i class="fas fa-chevron-down"></i></div>
                        <div class="test" name="increase-font" id="increase-font" title="Aumentar fonte"><i class="fas fa-font"></i><i class="fas fa-chevron-up"></i></div>
                        <div class="test" name="reset-font" id="reset-font" title="Tamanho padrão"><i class="fas fa-font"></i><i class="fas fa-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-12 text-center">
                    <a class="logo_menu" target="_parent" href="./">
                        <img src="componentes/imagens/logo_vni.png" alt="Climed Sul especialidades e diagnosticos"/>
                    </a>
                </div>
                <div class="col-lg-4 col-md-12">
                    <form action="noticias.php" method="post" enctype="multipart/form-data">
                        <div class="input-group my-4">
                            <input type="text" class="form-control pesquisa" name="search" placeholder="Digite o que procura" aria-label="Recipient's username" aria-describedby="button-addon2" id="search">
                                <button class="btn btn-sm" name="sendform" type="submit">Pesquisar</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-2 col-md-6 col-6 pt-2 text-center">
                    <a class="" target="_blank" href="https://falabr.cgu.gov.br/publico/ES/VendaNovadoImigrante/Manifestacao/RegistrarManifestacao">
                        <img src="componentes/imagens/banner_acesso_informacao_new.png" width="160px"  alt="Acesso a informação"/>
                    </a>

                </div>
                <div class="col-lg-1 col-md-6 col-6 pt-2 text-center">
                    <a class="" href="lgpd.php">
                        <img src="componentes/imagens/lgpd.png" width="110px" alt="Ouvidoria"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="navbar-content">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./">Inicial</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Município
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="https://cidades.ibge.gov.br/brasil/es/venda-nova-do-imigrante/panorama" target="_blank">Dados IBGE</a></li>
                                        <li><a class="dropdown-item" href="historico.php">Histórico</a></li>
                                        <li><a class="dropdown-item" href="formacaoadministrativa.php">Formação Administrativa</a></li>
                                        <li><a class="dropdown-item" href="simbolosoficiais.php">Símbolos Oficiais</a></li>
                                        <li class="dropdown-dividers"> Turismo</li>
                                        <li><a class="dropdown-item" href="agroturismo.php">Agroturismo</a></li>
                                        <li><a class="dropdown-item" href="pontosturisticos.php">Pontos Turísticos</a></li>
                                        <li><a class="dropdown-item" href="calendario-eventos.php">Calendário de Eventos</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Prefeitura
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="estuturaadministrativa.php">Estrutura Administrativa</a></li>
                                        <li><a class="dropdown-item" href="prefeito.php">Prefeito</a></li>
                                        <li><a class="dropdown-item" href="https://vendanovadoimigrante-es.portaltp.com.br/consultas/orcamento.aspx" target="_blank">Contas Públicas</a></li>
                                        <li><a class="dropdown-item" href="localizacao.php">Localização</a></li>
                                        <!--<li><a class="dropdown-item" href="https://www.diariomunicipal.es.gov.br/" target="_black">Diário Oficial do Municípios</a></li>-->
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Secretarias
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="secretaria.php?id=8">Administração</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=1">Agricultura</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=4">Assistência social</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=10">Educação</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=138">Esporte e Lazer</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=2">Finanças</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=7">Interior e Transportes</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=3">Meio Ambiente</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=6">Obras e Infraestrutura Urbana</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=9">Turismo, Cultura e Artesanato</a></li>
                                        <li><a class="dropdown-item" href="secretaria.php?id=5">Saúde</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Serviços Online
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="https://edu.cloud.el.com.br/es-vendanovadoimigrante-pm-edu/paginas/portalEstudante/index.xhtml" target="_blank">Portal do Aluno</a></li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/almoxarifado/login" target="_blank">Requisição de Materiais</a></li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/services/" target="_blank">Protocolo</a></li>
                                        <li><a class="dropdown-item" href="proconMunicipal.php">PROCON municipal</a></li>

                                        <li class="dropdown-dividers"> Alvará</li>
                                        <li><a class="dropdown-item" href="http://sistemas.vendanova.es.gov.br/alvara/vendanova/index.php" target="_blank">Solicitação</a></li>
                                        <li><a class="dropdown-item" href="http://sistemas.vendanova.es.gov.br/alvara/vendanova/consultar.php" target="_blank">Consulta</a></li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/services/index.php" target="_blank">Emissão de Alvará</a></li>

                                        <li class="dropdown-dividers"> Certidão Negativa</li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/services/index.php" target="_blank">Certidão negativa</a></li>

                                        <li class="dropdown-dividers"> Funcionários</li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/portal/" target="_blank">Comprovante de Rendimento (Declaração IR)</a></li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/portal/autenticar-contracheque" target="_blank">Recibo de Pagamento (Contra-cheque)</a></li>

                                        <li class="dropdown-dividers"> Emissão de Taxas</li>
                                        <li><a class="dropdown-item" href="https://servicos.cloud.el.com.br/es-vendanovadoimigrante-pm/services/index.php" target="_blank">Emissão de DAM</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Utilidades
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="links.php">Links</a></li>
                                        <li><a class="dropdown-item" href="farmacias.php">Farmácias de plantão</a></li>
                                        <li><a class="dropdown-item" href="ondeFicar.php">Onde ficar</a></li>
                                        <li><a class="dropdown-item" href="ondeComer.php">Onde comer</a></li>
                                        <li><a class="dropdown-item" href="telefonesUteis.php">Telefones úteis</a></li>
                                        <li><a class="dropdown-item" href="taxistas.php">Taxistas</a></li>

                                        <li class="dropdown-dividers" > Emissão de documentos</li>
                                        <li><a class="dropdown-item" href="carteira-de-trabalho.php">Carteira de Trabalho</a></li>
                                        <li><a class="dropdown-item" href="alistamento-militar.php">Alistamento militar</a></li>
                                        <li><a class="dropdown-item" href="carteira-de-identidade.php">Carteira de Identidade</a></li>
                                        <li class="dropdown-dividers" >Legislação online</li>
                                        <li><a class="dropdown-item" href="http://www3.camaravni.es.gov.br/legislacao/" target="_black">Municipal</a></li>
                                        <li><a class="dropdown-item" href="http://www3.al.es.gov.br/legislacao/" target="_black">Estadual</a></li>
                                        <li><a class="dropdown-item" href="http://www4.planalto.gov.br/legislacao/"target="_black">Federal</a></li>
                                        <li><a class="dropdown-item" href="./servicos/infralegal.php" target="_black">Infralegal</a></li>
                                        <li class="dropdown-dividers" >Outros</li>
                                        <li><a class="dropdown-item" href="./servicos/concursos-e-selecoes.php"target="_black">Concursos e seleções</a></li>
                                        <li><a class="dropdown-item" href="fia.php">Doações para o FIA</a></li>
                                        <li><a class="dropdown-item" href="http://c2sisweb.tecnologia.ws/SisWeb/Repositorio/Arquivos/0/e5f18f3a-1.pdf"target="_black">Defesa Civil - Relatório de Risco Geológico</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Controle e Transparência
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="ucci.php" target="_black">Controle Interno - UCCI</a></li>
                                        <li><a class="dropdown-item" href="https://vendanovadoimigrante-es.portaltp.com.br/" target="_black">Portal da Transparência</a></li>
                                        <li><a class="dropdown-item" href="ouvidoria.php" target="_black">Ouvidoria </a></li>
                                        <li><a class="dropdown-item" href="http://vendanova.es.gov.br/site/acessoAInformacao.php" target="_black">Acesso à Informação</a></li>
                                        <li><a class="dropdown-item" href="https://paineldecontrole.tcees.tc.br/" target="_black">Painel de Controle - TCEES</a></li>
                                        <li><a class="dropdown-item" href="https://geoobras.tce.es.gov.br/" target="_black">Geo-Obras - TCEES</a></li>
                                        <li><a class="dropdown-item" href="https://ioes.dio.es.gov.br/dom" target="_black">Diário Oficial dos Municípios</a></li>
                                        <li><a class="dropdown-item" href="dadosabertos.php" target="_black">Dados Abertos</a></li>
                                        <li><a class="dropdown-item" href="https://coronavirus.es.gov.br/painel-covid-19-es" target="_black">Painel COVID-19 ES</a></li>
                                        <li><a class="dropdown-item" href="https://vendanovadoimigrante-es.portaltp.com.br/consultas/documentos.aspx?id=18" target="_black">Audiências/Orçamento</a></li>
                                    </ul>
                                </li>
                                <!--                                <li class="nav-item">
                                                                    <a class="nav-link" href="https://falabr.cgu.gov.br/publico/ES/VendaNovadoImigrante/Manifestacao/RegistrarManifestacao" target="_black">Ouvidoria</a>
                                                                </li>-->
                                <li class="nav-item" >
                                    <a class="nav-link" href="covid.php" >COVID-19</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="faleconosco.php">Fale Conosco</a>
                                </li>
                                <li class="nav-item">
                                    &nbsp;&nbsp;&nbsp;
                                </li>
                                <li class="nav-item" style="background-color: #fccb6f; color: #fff!important">
                                    <a class="nav-link text2"  target="_blank" href="https://www.instagram.com/vendanova.es/">
                                         &nbsp;&nbsp;<i class="fab fa-instagram"></i> &nbsp;&nbsp;
                                    </a>
                                </li>

                            </ul>
                            <!--                            <form class="d-flex">
                                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                                        </form>-->
                        </div>
                    </div>
                </nav>

            </div>
        </div>

    </header>
