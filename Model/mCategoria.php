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


function insere_categoria_recurso($nome, $descre, $ambF)
{
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("INSERT INTO categoria_recurso (nome, descricao, ambiente_fisico) values ('$nome', '$descre', '$ambF')");

        // Adicionou no banco

        return $resulta;

        
    }
    // não adicionou no banco
    return false;

}





?>