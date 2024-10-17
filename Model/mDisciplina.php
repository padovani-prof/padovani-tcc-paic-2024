<?php 



function carrega_disciplina()
{
    include_once 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);


    $resulta = $cone->query('SELECT *  from disciplina ');

    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc())
    {
        $todos_dados[] = $dados;
    }

    return $todos_dados; 
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}





function insere_disciplina($nome, $curso, $codi_pere)
{
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("INSERT INTO disciplina (nome, curso,codigo_periodo ) values ('$nome', '$curso', '$codi_pere')");

        // Adicionou no banco

        return $resulta;

        
    }
    // não adicionou no banco
    return false;

}





?>