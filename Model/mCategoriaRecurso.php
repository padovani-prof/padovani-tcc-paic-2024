<?php 


function carrega_categorias_recurso()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT *  from categoria_recurso');
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    
    return $resulta;
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}



function apagar_categoria($chave_pri)
{

    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);


    $resulta = $conecxao->query("DELETE from categoria_recurso where codigo=$chave_pri");
    return $resulta;
}





?>
