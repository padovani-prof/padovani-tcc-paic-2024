<?php 



function apagar_diciplina($chave_pri)
{
    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conecxao->query("DELETE from disciplina where codigo=$chave_pri");
    return $resulta;
   

    

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

   if (mb_strlen($nome) < 3 or mb_strlen($nome) > 50) 
   {
        return 2 ; // numero de caracter do nome invalido
   }
   
   if (mb_strlen($curso) > 100  or (mb_strlen($curso) < 5 ))
   {
        return 1; // nome do curso invalido
   }
   
   
   
   return true; // recurso valido
   
}



function insere_disciplina($nome, $curso, $codi_pere)
{

    // Trata os dados
    $nome = mb_strtoupper(trim($nome));
    $curso = mb_strtoupper(trim($curso));
    $validar = Validar_recurso($nome, $curso);
    
    if ($validar === true)
    {
        include 'confg_banco.php';
    
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conecxao->connect_error)
        {
            $resulta = $conecxao->query ("SELECT * FROM disciplina WHERE nome='$nome'");
            if ($resulta->num_rows == 0)
            {

                $resulta = $conecxao->query ("INSERT INTO disciplina (nome, curso,codigo_periodo ) values ('$nome', '$curso', '$codi_pere')");
                // Adicionou no banco
                $validar = 0; // inserido corretamente
            }
            else
            {
                $validar = 3;
                // nome repetido
            }
            
        }
    }

    
    // não adicionou no banco
    return $validar;

}


function mandar_informações($chave, $tabela){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $resulata = $conecxao->query("SELECT * from $tabela where codigo=$chave");
    return $resulata->fetch_assoc();

}

function atualizar_disciplina($chave, $nome, $curso, $peri){
     // Trata os dados
     $nome = mb_strtoupper(trim($nome));
     $curso = mb_strtoupper(trim($curso));
     $validar = Validar_recurso($nome, $curso);
     
     if ($validar === true)
     {
         include 'confg_banco.php';
     
         $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
 
         if(!$conecxao->connect_error)
         {
             $resulta = $conecxao->query ("SELECT * FROM disciplina WHERE nome='$nome' and codigo!=$chave");
             if ($resulta->num_rows == 0)
             {
 
                 $resulta = $conecxao->query ("UPDATE disciplina set nome='$nome', curso='$curso', codigo_periodo=$peri where codigo=$chave");
                 // Adicionou no banco
                 $validar = 0; // inserido corretamente
             }
             else
             {
                 $validar = 3;
                 // nome repetido
             }
             
         }
     }
 
     
     // não adicionou no banco
     return $validar;

}

?>

