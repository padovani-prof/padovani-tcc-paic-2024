<?php 

function Validar_categoria($nome, $desc)
{
    // retorna se os nomes dos dados são validos é para a inserção

    if (mb_strlen($nome) < 3 or mb_strlen($nome) > 50) {
        return 2; // numero de caracter do nome invalido
    }

    if (mb_strlen($desc)<3 or mb_strlen($desc) > 100) {
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
        
        if (!$conecxao->connect_error) {

            // Usando prepared statement para evitar SQL Injection
            $stmt = $conecxao->prepare("SELECT * FROM categoria_recurso WHERE nome = ?");
            $stmt->bind_param("s", $nome);  // "s" para string
            $stmt->execute();
            $resulta = $stmt->get_result();

            if ($resulta->num_rows == 0) {
                // Inserindo com prepared statement
                $stmt = $conecxao->prepare("INSERT INTO categoria_recurso (nome, descricao, ambiente_fisico) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome, $descre, $ambF);  // "sss" para três strings
                $stmt->execute();

                // Adicionou no banco
                $valido = 0;
            } else {
                $valido = 3; // nome repetido
            }

            $stmt->close();
        }


    }
    return $valido;

    
   
}

function pegar_dados($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $stmt = $conecxao->prepare("SELECT * FROM categoria_recurso WHERE codigo = ?");
    $stmt->bind_param("i", $chave);  // "i" para inteiro
    $stmt->execute();
    $resulta = $stmt->get_result();

    $return = $resulta->fetch_assoc();
    $stmt->close();

    return $return;

    

}

function atualizar_dados($chave, $nome, $descre, $ambF){
    $nome = trim(mb_strtoupper($nome));
    $descre = trim($descre);

    $valido = Validar_categoria($nome, $descre);

    if($valido === true) {
        if($ambF === 'on') {
            $ambF = 'S';
        } else {
            $ambF = 'N';
        }

        include 'confg_banco.php';
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

        if (!$conecxao->connect_error) {
            // Usando prepared statement para evitar SQL Injection
            $stmt = $conecxao->prepare("SELECT codigo FROM categoria_recurso WHERE nome = ?");
            $stmt->bind_param("s", $nome);  // "s" para string
            $stmt->execute();
            $resulta = $stmt->get_result();

            if ($resulta->num_rows == 0 || ($resulta->num_rows == 1 && $resulta->fetch_assoc()['codigo'] == $chave)) {
                // Usando prepared statement para o UPDATE
                $stmt = $conecxao->prepare("UPDATE categoria_recurso SET nome = ?, descricao = ?, ambiente_fisico = ? WHERE codigo = ?");
                $stmt->bind_param("sssi", $nome, $descre, $ambF, $chave);  // "sss" para strings, "i" para inteiro
                $stmt->execute();

                // Atualização bem-sucedida
                $valido = 0;
            } else {
                $valido = 3; // nome repetido
            }

            $stmt->close();
        }
    }
    
    return $valido;
}




?>
