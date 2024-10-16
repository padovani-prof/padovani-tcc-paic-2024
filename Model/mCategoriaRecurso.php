<?php 


function carrega_categorias_recurso()
{
    include_once 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);


    $resulta = $cone->query('SELECT *  from categoria_recurso');

    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc())
    {
        $todos_dados[] = $dados;
    }

    return $todos_dados; 
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}





?>