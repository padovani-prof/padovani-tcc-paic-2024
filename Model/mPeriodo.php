<?php 



function apagar_periodo($chave_pri)
{
   
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    
    $resulta = $conecxao->query("DELETE from periodo where codigo=$chave_pri");
    return $resulta;

    

}

function carrega_periodo()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);


    $resulta = $cone->query('SELECT *  from periodo ');

    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc())
    {
        $todos_dados[] = $dados;
    }

    return $todos_dados; 
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}





function insere_periodo($nome, $data_ini, $data_final)
{
    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("SELECT * FROM periodo WHERE nome='$nome'");
        if ($resulta->num_rows == 0)
        {
            $resulta = $conecxao->query ("INSERT INTO periodo (nome, dt_inicial, dt_final) VALUES ('$nome', '$data_ini', '$data_final');");
            
            return 0;
            // salvo

        }
        else
        {
            return 1;
            // nome repetido
        }

    }
    

}











?>
