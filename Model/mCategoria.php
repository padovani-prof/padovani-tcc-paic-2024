<?php 

function Validar_categoria($nome, $desc)
{
    // retorna se os nomes dos dados são validos é para a inserção

    if (strlen($nome) < 3 or strlen($nome) > 50) {
        return 0; // numero de caracter do nome invalido
    }

    if (strlen($desc) > 100) {
        return 1; // passou do numero maximo de caracter da descrição
    }

    return true; // recurso valido

}


function insere_categoria_recurso($nome, $descre, $ambF)
{
    // cadastra / retorna um numeros para a mensagem

    $nome = trim(strtoupper($nome));
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
        if (!$conecxao->connect_error) {
            $resulta = $conecxao->query("INSERT INTO categoria_recurso (nome, descricao, ambiente_fisico) values ('$nome', '$descre', '$ambF')");

            // Adicionou no banco

            if($resulta==true)
            {
                return 2;
            }
        }

    }
    return $valido;

    
   
}



?>
