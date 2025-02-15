<?php

$acao = 'recuperarTarefasPendentes';
require "./tarefa_controller.php";

function compararTarefas($a, $b)
{
    return strcmp($a->tarefa, $b->tarefa);
}

function compararDatas($a, $b)
{
    return strtotime($a->data_cadastrado) - strtotime($b->data_cadastrado);
}

function compararPrioridades($a, $b)
{
    return intval($a->prioridade) - intval($b->prioridade);
}

$ordenacao = ($_GET['ordenacao'] ?? '');

if ($ordenacao === 'data_cadastro') {
    usort($tarefas, 'compararDatas');
} else if ($ordenacao === 'prioridade') {
    usort($tarefas, 'compararPrioridades');
} else {
    // Default: ordenar alfabeticamente
    usort($tarefas, 'compararTarefas');
}

?>




<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Lista Tarefas</title>

    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <script>
        function editar(id, txt_tarefa) {

            let form = document.createElement('form')
            form.action = 'index.php?pag=index&acao=atualizar'
            form.method = 'post'
            form.className = 'row'

            
            let inputTarefa = document.createElement('input')
            inputTarefa.type = 'text'
            inputTarefa.name = 'tarefa'
            inputTarefa.className = 'col-9 form-control'
            inputTarefa.value = txt_tarefa

            
            let inputId = document.createElement('input')
            inputId.type = 'hidden'
            inputId.name = 'id'
            inputId.value = id

            
            let button = document.createElement('button')
            button.type = 'submit'
            button.className = 'col-3 btn btn-info'
            button.innerHTML = 'Atualizar'

            
            form.appendChild(inputTarefa)

            
            form.appendChild(inputId)

            //incluir button no form
            form.appendChild(button)

           
            let tarefa = document.getElementById('tarefa_' + id)

            
            tarefa.innerHTML = ''

            //incluir form na página
            tarefa.insertBefore(form, tarefa[0])

        }

        function remover(id) {
            location.href = 'index.php?pag=index&acao=remover&id=' + id;
        }

        function marcarRealizada(id) {
            location.href = 'index.php?pag=index&acao=marcarRealizada&id=' + id;
        }
    </script>

</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
                App Lista Tarefas
            </a>
        </div>
    </nav>

    <div class="container app">
        <div class="row">
            <div class="col-md-3 menu">
                <ul class="list-group">
                    <li class="list-group-item active"><a href="#">Tarefas pendentes</a></li>
                    <li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
                    <li class="list-group-item"><a href="todas_tarefas.php">Todas tarefas</a></li>
                    <li class="list-group-item "><a href="arquivadas.php">Tarefas Arquivadas</a></li>

                </ul>
            </div>

            <div class="col-md-9">
                <div class="container pagina">
                    <div class="row">
                        <div class="col">
                            <h4>Tarefas pendentes</h4>
                            <hr />

                            <form id="formOrdenar" method="get">
                                <label for="ordenacao">Ordenar por:</label>
                                <select id="ordenacao" name="ordenacao" onchange="this.form.submit()">
                                    <option value="alfabetica" <?php echo $ordenacao === 'alfabetica' ? 'selected' : ''; ?>>Ordem alfabética</option>
                                    <option value="data_cadastro" <?php echo $ordenacao === 'data_cadastro' ? 'selected' : ''; ?>>Data de criação</option>
                                    <option value="prioridade" <?php echo $ordenacao === 'prioridade' ? 'selected' : ''; ?>>Prioridade</option>
                                </select>
                            </form>

                            <?php
                            
                            foreach ($tarefas as $indice => $tarefa) { ?>
                                <div class="row mb-3 d-flex align-items-center tarefa">
                                    <div class="col-sm-9" id="tarefa_<?= $tarefa->id ?>">
                                        <?= $tarefa->tarefa ?> - Prioridade:
                                        <?php
                                        if ($tarefa->prioridade == 1) {
                                            echo 'Alta';
                                        } elseif ($tarefa->prioridade == 2) {
                                            echo 'Média';
                                        } elseif ($tarefa->prioridade == 3) {
                                            echo 'Baixa';
                                        }
                                        ?>
                                        <br>
                                        Criado em: <?= date('d/m/Y H:i', strtotime($tarefa->data_cadastrado)) ?>
                                    </div>
                                    <div class="col-sm-3 mt-2 d-flex justify-content-between">
                                        <i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->id ?>)"></i>

                                        <?php
                                        if ($tarefa->status === 'pendente') { ?>
                                            <i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
                                        <?php } ?>

                                        <i class="fas fa-edit fa-lg text-info" onclick="editar(<?= $tarefa->id ?>, '<?= $tarefa->tarefa ?>')"></i>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>