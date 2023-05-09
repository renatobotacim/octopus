<?php
require ('./componentes/includes/header.php');
$read = new Read;
$read->ExeRead("inf_arquivos", "WHERE arquivo_categoria_id = :cat and arquivo_empresa_id = :idEmpresa ORDER BY arquivo_identificador ASC, arquivo_id DESC ", "cat=3&idEmpresa=4");
$content = $read->getResult();
echo "<session><article class=\"container pages\">";
echo "<div class=\"title\">Arquivos referêntes ao Conselho municipal de Meio Ambiente</div>";

if (!empty($content['conteudo_subtitulo'])):
endif;
?>
<nav>
    <div class="nav nav-tabs my-5" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ATAS-tab" data-bs-toggle="tab" data-bs-target="#nav-ATAS" type="button" role="tab" aria-controls="nav-ATAS" aria-selected="true">ATAS</button>
        <button class="nav-link" id="nav-DECRETOS-tab" data-bs-toggle="tab" data-bs-target="#nav-DECRETOS" type="button" role="tab" aria-controls="nav-DECRETOS" aria-selected="false">DECRETOS</button>
        <button class="nav-link" id="nav-RESOLUCOES-tab" data-bs-toggle="tab" data-bs-target="#nav-RESOLUCOES" type="button" role="tab" aria-controls="nav-RESOLUCOES" aria-selected="false">RESOLUÇÕES</button>
        <button class="nav-link" id="nav-EDITAIS-tab" data-bs-toggle="tab" data-bs-target="#nav-EDITAIS" type="button" role="tab" aria-controls="nav-EDITAIS" aria-selected="false">EDITAIS</button>
        <button class="nav-link" id="nav-LEIS-tab" data-bs-toggle="tab" data-bs-target="#nav-LEIS" type="button" role="tab" aria-controls="nav-LEIS" aria-selected="false">LEIS DE CRIAÇÃO</button>
        <button class="nav-link" id="nav-REGIMENTO-tab" data-bs-toggle="tab" data-bs-target="#nav-REGIMENTO" type="button" role="tab" aria-controls="nav-REGIMENTO" aria-selected="false">REGIMENTO INTERNO</button>
        <button class="nav-link" id="nav-DATAS-tab" data-bs-toggle="tab" data-bs-target="#nav-DATAS" type="button" role="tab" aria-controls="nav-DATAS" aria-selected="false">DATAS DAS REUNIÕES</button>
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-ATAS" role="tabpanel" aria-labelledby="nav-ATAS-tab">
        <ul class="list-group my-4">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Atas CMS'):
                    echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-DECRETOS" role="tabpanel" aria-labelledby="nav-DECRETOS-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Decretos CMS'):
                   echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-RESOLUCOES" role="tabpanel" aria-labelledby="nav-RESOLUCOES-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Resoluções CMS'):
                   echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-EDITAIS" role="tabpanel" aria-labelledby="nav-EDITAIS-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Editais CMS'):
                 echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-LEIS" role="tabpanel" aria-labelledby="nav-LEIS-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Leis de criação do CMS'):
                  echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-REGIMENTO" role="tabpanel" aria-labelledby="nav-REGIMENTO-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Regimento Interno do CMS'):
                  echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-DATAS" role="tabpanel" aria-labelledby="nav-DATAS-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Reuniões CMS'):
                  echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-BOLETINS" role="tabpanel" aria-labelledby="nav-BOLETINS-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Boletins Epidemiológicos'):
                   echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <div class="tab-pane fade" id="nav-PRESTADORES" role="tabpanel" aria-labelledby="nav-PRESTADORES-tab">
        <ul class="list-group my-5">
            <?php
            $i = 0;
            foreach ($content as $x):
                extract($x);
                if ($arquivo_identificador == 'Contratos-Prestadores de Serviço'):
                 echo "<a target=\"_blank\" href=\"http://{$arquivo_host}/{$arquivo_link}\"><li class=\"list-group-item\">{$arquivo_identificador} - {$arquivo_nome}</li></a>";
                endif;
            endforeach;
            ?>
        </ul>
    </div>
</div>
</article>
</session>;
<?php
require ('./componentes/includes/footer.php');
