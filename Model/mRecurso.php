<?php 



function tem_banco($categoria)
{

    # não consegui integra com o config_banco.php
    include 'confg_banco.php';
     
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error) {
        $consulta = $conecxao->prepare("SELECT * FROM `categoria_recurso` WHERE `codigo` = ?");
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

   if ( mb_strlen($nome) < 3 or mb_strlen($nome) > 50) 
   {
        return 3 ; // numero de caracter do nome invalido
   }
   
   if (mb_strlen($desc) > 100 )
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

        $resultado = $conecxao->query("SELECT * FROM recurso where nome= '$nome'");
        if ($resultado->num_rows == 0 )
        
        {
            $resulta = $conecxao->query ("INSERT INTO recurso (nome, descricao, codigo_categoria) values ('$nome', '$descre', $cCatego)");
            // Adicionou no banco
            return 0;
        }
       else
       {
            return 4;
       }
   
    }
    
}



function cadastrar_recurso($nome, $des, $cCatego)
{
    
    $nome = trim(mb_strtoupper($nome));
    $descre = trim($des);

    
    // ver se os dados estão condisentes retornando true ou false
    $valido = Validar_recurso($nome, $descre, $cCatego);
    
    if ($valido === true )
 
    {
        return insere_no_banco($nome, $descre, $cCatego);
        // retorna o dado 0 que foi adicionado com sucesso
        // retona 3 nome repetido
        
    }
    return $valido;
}




function apagar_recurso($chave_pri)
{
   


    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);



    $resulata = $conecxao->query("SELECT * FROM retirada_devolucao where codigo_recurso=$chave_pri");
    if($resulata->num_rows >0){
        return 1; // possui retirada
    } 

    $resulata = $conecxao->query("SELECT * FROM ensalamento where codigo_sala=$chave_pri");
    if($resulata->num_rows >0){
        return 2; // possui ensalamento
    }

    $resulata = $conecxao->query("SELECT * FROM reserva where codigo_recurso=$chave_pri");
    if($resulata->num_rows > 0){
        return 3;// possui reserva
    }
    
    
    $conecxao->query("DELETE from checklist where codigo_recurso=$chave_pri");
    $conecxao->query("DELETE from acesso_recurso where codigo_recurso=$chave_pri");
    $resulata = $conecxao->query("DELETE from recurso where codigo=$chave_pri");
    if($resulata == true){
        return 0; // recurso apagado

    }
    
    

}



function mandar_dados($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from recurso where codigo=$chave");
    $resulata = $resulata->fetch_assoc();
    return $resulata;

}

function atualizar_dados($chave, $nome, $descre, $cCatego){
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resulta = $conecxao->query ("SELECT codigo FROM recurso  WHERE nome='$nome'");
    if ($resulta->num_rows == 0 or $resulta->num_rows == 1 and $resulta->fetch_assoc()['codigo']==$chave){
        $resulta = $conecxao->query("UPDATE recurso set nome='$nome', descricao='$descre', codigo_categoria=$cCatego where codigo=$chave");
        return 0;
    }else{
        return 4;

    }
    

}



function verificar_atualizar($chave, $nome, $des, $cCatego){
   
    $nome = trim(mb_strtoupper($nome));
    $descre = trim($des);

    
    // ver se os dados estão condisentes retornando true ou false
    $valido = Validar_recurso($nome, $descre, $cCatego);
    
    if ($valido === true )
 
    {
        return atualizar_dados($chave, $nome, $descre, $cCatego);
    }
    return $valido;
}



   
function Existe_esse_recurso($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from recurso where codigo=$chave");
    if($resulata->num_rows == 0){
        return false;
    }
    return true;

}


   
function Carregar_recursos_dados(){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $conecxao->query("SELECT * from recurso");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conecxao->close();
    return $resultado;
}




?>
