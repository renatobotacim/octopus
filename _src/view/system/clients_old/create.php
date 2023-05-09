<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (isset($Data)):
    $Data['clients_logo'] = $_FILES['clients_logo'];
endif;

var_dump($Data);
if (!empty($Data['sendClients'])):
    unset($Data['sendClients']);
    require ('_models/AdminClients.class.php');
//    $cadastra = new AdminClients;
//    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=clients_old/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;
endif;
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-university"></i> &nbsp; Cadastro de Clientes</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel">Dashboard</a>
            </li>
            <li>
                <i class="fa fa-user"></i> <a href="painel.php?exe=clients/index">Clientes</a>
            </li>
            <li class="active">
                <i class="fa fa-plus"></i> Cadastro
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Cadastro de Clientes</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                        <div class="flot-chart-content" id="flot-moving-line-chart">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" name="clients_name" id="clients_name" class="form-control" placeholder="Informe o Nome" value="<?php if (isset($Data)) echo $Data['clients_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Site</label>
                                        <input type="text" name="clients_link" id="clients_link" class="form-control" placeholder="Link do Site" value="<?php if (isset($Data)) echo $Data['clients_link']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">    
                                        <label>Nível</label>
                                        <select id="clients_clients_level_id" class="form-control chzn-select" name="clients_clients_level_id" value="<?php if (!isset($Data['clients_clients_level_id'])) $Data['clients_clients_level_id'] = ''; ?>">
                                            <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                            <?php
                                            $readLevel = new Read;
                                            $readLevel->ExeRead("inf_clients_levels", "WHERE clients_levels_id");
                                            foreach ($readLevel->getResult() as $clients_levels_id):
                                                extract($clients_levels_id);
                                                echo"<option ";
                                                if ($Data['clients_clients_level_id'] == $clients_levels_id):
                                                    echo "selected=\"selected\"";
                                                endif;
                                                echo "value=\"{$clients_levels_id}\"> {$clients_levels_name} </option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>                          
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Situação</label>
                                        <select id="clients_status" class="form-control chzn-select" name="clients_status" value="<?php if (!isset($Data['clients_status'])) $Data['clients_status'] = ''; ?>">
                                            <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                            <option value="T" <?php echo ($Data['clients_status'] == "T") ? "selected" : "" ?>>Ativo</option>                                     
                                            <option value="F" <?php echo ($Data['clients_status'] == "F") ? "selected" : "" ?>>Inativo</option>                                     
                                        </select>
                                    </div>
                                </div>           
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Logo</label><br>
                                        <input type="file" name="clients_logo" accept="image/*" id="clients_logo" class="inputfile" onchange="previewImage()"/>
                                        <label class="uploadArquivo" for="clients_logo">
                                            <?php
                                            if (!isset($Data['clients_logo']) || ($Data['clients_logo']['size'] == 0)):
                                                echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"100px\" heigth=\"auto\"";   
                                                echo "<img id=\"preview\" width=\"100px\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;\">";
                                            else:
                                                echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"100px\" heigth=\"auto\"";   
                                                echo "<img id=\"preview\" width=\"100px\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;\">";
                                            endif;
                                            ?>     
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success" value="Cadastrar" name="sendClients"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a type="submit" class="btn btn-info" value="" name="SendAlter" href="painel.php?exe=clients/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning" value="" name="SendAlter"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a type="submit" class="btn btn-danger" value="" name="SendAlter" href="painel.php?exe=clients/index"/><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<style>
    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }
    .uploadArquivo {text-align: center;cursor: pointer;}
    .uploadArquivo .mudarfoto {
        position: absolute;
        font-size: 1.5em;
        top: 50%;
        left: 40%;
        transform: translateY(-50%);
        display: none;        
    }
    .inputfile + label:hover, .inputfile + label img{opacity: 0.9}
</style>
<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>
