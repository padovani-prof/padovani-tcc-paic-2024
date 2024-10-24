<?php 

function tem_banco($categoria)
{

    # não consegui integra com o config_banco.php
    include 'confg_banco.php';
     
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error) {
        $consulta = $conecxao->prepare("SELECT * FROM `recurso` WHERE `codigo_categoria` = ?");
        $consulta->bind_param('i', $categoria);
        $consulta->execute();
        $resultado = $consulta->get_result();
        
        if ($resultado->num_rows > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
}
    
}




function Validar_recurso($nome, $desc, $cCatego)
{
    // retorna se o dado é valido

   if ( strlen($nome) < 3 or strlen($nome) > 50) 
   {
        return 0 ; // numero de caracter do nome invalido
   }
   
   if (strlen($desc) > 100 )
   {
        return 1; // passou do numero maximo de caracter da descrição
   }
   
   
   if (tem_banco($cCatego) === false)
   {
        return 2; // categoria não consta no banco
   }
   
   
   return true; // recurso valido
   
}




function insere_no_banco($nome, $descre, $cCatego)
{
    // insere no banco
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("INSERT INTO recurso (nome, descricao, codigo_categoria) values ('$nome', '$descre', $cCatego)");

        // Adicionou no banco

        return $resulta;

        
    }
    // não adicionou no banco
    return false;

}



function cadastrar_recurso($nome, $des, $cCatego)
{
    
    $nome = trim(strtoupper($nome));
    $descre = trim($des);

    
    // ver se os dados estão condisentes retornando true ou false
    $valido = Validar_recurso($nome, $descre, $cCatego);

    
    

    
    if ($valido === true )
 
    {
        $insere = insere_no_banco($nome, $descre, $cCatego);
        // retorna o dado 3 que foi adicionado com sucesso
        return 3;

    }
    return $valido;
}


function Carregar_recursos()
{   
    // função que retorna uma lista com todos os dados dos recursos

    // conecxao com o banco
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Executar a consulta
    $resultado = $conecxao->query("SELECT * FROM recurso");

    $todos_dados = [];

    while ($linha = $resultado->fetch_assoc())
    {
        $todos_dados[] = $linha;
    }
    return $todos_dados; // retorna todos os daddos da tabela recurso do banco em formato de lista
   
    
    
}


function apagar_recurso($chave_pri)
{
   
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $resulata = $conecxao->query("DELETE from recurso where codigo=$chave_pri");

}

   


   




?>
