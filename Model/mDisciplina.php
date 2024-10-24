<?php 


function apagar_diciplina($chave_pri)
{
   
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    
    $resulta = $conecxao->query("DELETE from disciplina where codigo=$chave_pri");

    

}


function carrega_disciplina()
{
    include 'confg_banco.php';
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





function Validar_recurso($nome, $curso)
{
    // retorna se o dado é valido

   if (strlen($nome) < 3 or strlen($nome) > 50) 
   {
        return 0 ; // numero de caracter do nome invalido
   }
   
   if (strlen($curso) > 100  or (strlen($curso) < 5 ))
   {
        return 1; // nome do curso invalido
   }
   
   
   
   return true; // recurso valido
   
}



function insere_disciplina($nome, $curso, $codi_pere)
{

    // Trata os dados
    $nome = strtoupper(trim($nome));
    $curso = strtoupper(trim($curso));
    $validar = Validar_recurso($nome, $curso);


    
    if ($validar === true)
    {
        include 'confg_banco.php';
    
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conecxao->connect_error)
        {
            $resulta = $conecxao->query ("INSERT INTO disciplina (nome, curso,codigo_periodo ) values ('$nome', '$curso', '$codi_pere')");

            // Adicionou no banco

            return 2; // inserido corretamente
            
        }
    }

    
    // não adicionou no banco
    return $validar;

}


?>
