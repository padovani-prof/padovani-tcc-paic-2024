<?php 

function apagar_diciplina($chave_pri)
{
    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("DELETE FROM disciplina WHERE codigo = ?");
    $stmt->bind_param("i", $chave_pri);  // "i" para inteiro
    $resulta = $stmt->execute();
    $stmt->close();
    
    return $resulta;
}

function carrega_disciplina()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement (embora não haja parâmetros dinâmicos)
    $stmt = $cone->prepare('SELECT * FROM disciplina ORDER BY nome ASC');
    $stmt->execute();
    $resulta = $stmt->get_result();
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $cone->close();
    
    return $resulta; 
}

function Validar_recurso($nome, $curso, $peri)
{
    // Retorna se o dado é válido
    if (mb_strlen($nome) < 3 or mb_strlen($nome) > 50) 
    {
        return 2; // Número de caracteres do nome inválido
    }

    if (mb_strlen($curso) > 100  or (mb_strlen($curso) < 5 ))
    {
        return 1; // Nome do curso inválido
    }

    if($peri == 'NULL'){
        return 4; // Período inválido
    }

    return true; // Recurso válido
}

function insere_disciplina($nome, $curso, $codi_pere)
{
    // Trata os dados
    $nome = mb_strtoupper(trim($nome));
    $curso = mb_strtoupper(trim($curso));
    $validar = Validar_recurso($nome, $curso, $codi_pere);
    
    if ($validar === true)
    {
        include 'confg_banco.php';
    
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conecxao->connect_error)
        {
            // Usando prepared statement para verificar nome repetido
            $stmt = $conecxao->prepare("SELECT * FROM disciplina WHERE nome = ?");
            $stmt->bind_param("s", $nome);  // "s" para string
            $stmt->execute();
            $resulta = $stmt->get_result();

            if ($resulta->num_rows == 0)
            {
                // Usando prepared statement para inserir dados
                $stmt = $conecxao->prepare("INSERT INTO disciplina (nome, curso, codigo_periodo) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $nome, $curso, $codi_pere);  // "s" para string, "i" para inteiro
                $stmt->execute();
                $validar = 0; // Inserido corretamente
            }
            else
            {
                $validar = 3; // Nome repetido
            }

            $stmt->close();
        }
    }

    return $validar; // Não adicionou no banco
}

function mandar_informações($chave, $tabela)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("SELECT * FROM $tabela WHERE codigo = ?");
    $stmt->bind_param("i", $chave);  // "i" para inteiro
    $stmt->execute();
    $resulata = $stmt->get_result();
    $stmt->close();

    return $resulata->fetch_assoc();
}

function atualizar_disciplina($chave, $nome, $curso, $peri)
{
    // Trata os dados
    $nome = mb_strtoupper(trim($nome));
    $curso = mb_strtoupper(trim($curso));
    $validar = Validar_recurso($nome, $curso, $peri);
    
    if ($validar === true)
    {
        include 'confg_banco.php';
    
        $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conecxao->connect_error)
        {
            // Usando prepared statement para verificar nome repetido
            $stmt = $conecxao->prepare("SELECT * FROM disciplina WHERE nome = ? AND codigo != ?");
            $stmt->bind_param("si", $nome, $chave);  // "s" para string, "i" para inteiro
            $stmt->execute();
            $resulta = $stmt->get_result();

            if ($resulta->num_rows == 0)
            {
                // Usando prepared statement para atualizar dados
                $stmt = $conecxao->prepare("UPDATE disciplina SET nome = ?, curso = ?, codigo_periodo = ? WHERE codigo = ?");
                $stmt->bind_param("ssii", $nome, $curso, $peri, $chave);  // "ssii" para strings e inteiros
                $stmt->execute();
                $validar = 0; // Atualizado corretamente
            }
            else
            {
                $validar = 3; // Nome repetido
            }

            $stmt->close();
        }
    }

    return $validar; // Não atualizou no banco
}

?>
