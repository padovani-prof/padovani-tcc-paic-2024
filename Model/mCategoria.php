<?php 



    

function Validar_categoria($nome, $desc)
{
    // retorna se os nomes dos dados são validos é para a inserção

    if (mb_strlen($nome) < 3 or mb_strlen($nome) > 50) {
        return 2; // numero de caracter do nome invalido
    }

    if (mb_strlen($desc) > 100) {
        return 1; // passou do numero maximo de caracter da descrição
    }

    return true; // recurso valido

}


function insere_categoria_recurso($nome, $descre, $ambF)
{
    // cadastra / retorna um numeros para a mensagem

    $nome = trim(mb_strtoupper($nome));
    $descre = trim($descre);

    $valido = Validar_categoria($nome, $descre);

    if($valido===true)
    {
        if($ambF=== 'on')
        {
            $ambF = 'S';
        }
        else
        {
            $ambF = 'N';
        }
        include 'confg_banco.php';
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
        if (!$conecxao->connect_error) 
        {
            
            
            $resulta = $conecxao->query ("SELECT * FROM categoria_recurso WHERE nome='$nome'");
            if ($resulta->num_rows == 0)
            {

                $resulta = $conecxao->query("INSERT INTO categoria_recurso (nome, descricao, ambiente_fisico) values ('$nome', '$descre', '$ambF')");

                // Adicionou no banco
                $valido = 0;
            
            }
            else
            {
                $valido = 3; // nome repetido
            }
        }

    }
    return $valido;

    
   
}



?>
