<?php 

function cadastra_acesso_recurso($cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $data_fim)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $lis_sema = str_replace(',', '', $lis_sema);
        $a = (strlen($data_fim) == 10 ? "$data_fim" : "null");
        $s = (strlen($data_fim) == 10 ? 's' : 'i');
        $sql = "INSERT INTO acesso_recurso (codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) values (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conecxao->prepare($sql);
        $stmt->bind_param("iissss$s", $cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $a);
        
        $stmt->execute();
        $stmt->close();
        $conecxao->close();
    }
}

function recurso_carrega($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparando a consulta SQL
    $sql = "SELECT perfil_usuario.nome AS 'perfil', 
                   acesso_recurso.hr_inicial AS 'ini', 
                   acesso_recurso.hr_final AS 'fim', 
                   acesso_recurso.codigo AS 'cod'
            FROM acesso_recurso 
            INNER JOIN perfil_usuario ON perfil_usuario.codigo = acesso_recurso.codigo_perfil
            WHERE acesso_recurso.codigo_recurso = ?";

    // Preparando o statement
    $stmt = $conecxao->prepare($sql);

    // Vinculando o parâmetro
    $stmt->bind_param("i", $codigo);

    // Executando a consulta
    $stmt->execute();
    
    // Obtendo o resultado
    $resultado = $stmt->get_result();

    // Retornando os resultados como um array associativo
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);

    // Fechando o statement e a conexão
    $stmt->close();
    $conecxao->close();

    return $resultado;
}

function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparando a consulta SQL
    $sql = "DELETE FROM acesso_recurso WHERE codigo = ?";

    // Preparando o statement
    $stmt = $conecxao->prepare($sql);

    // Vinculando o parâmetro
    $stmt->bind_param("i", $chave_pri);

    // Executando a consulta
    $stmt->execute();

    // Fechando o statement e a conexão
    $stmt->close();
    $conecxao->close();
}

function Existe_essa_chave_na_tabela($chave, $tabela, $jogar_pra_onde)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparando a consulta SQL
    $sql = "SELECT * FROM $tabela WHERE codigo = ?";

    // Preparando o statement
    $stmt = $conecxao->prepare($sql);

    // Vinculando o parâmetro
    $stmt->bind_param("i", $chave);

    // Executando a consulta
    $stmt->execute();
    
    // Obtendo o resultado
    $resultado = $stmt->get_result();

    // Se não encontrar a chave, redireciona
    if ($resultado->num_rows == 0) {
        header("Location: $jogar_pra_onde");
        exit();
    }

    // Fechando o statement e a conexão
    $stmt->close();
    $conecxao->close();
}

function carrega_opition()
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparando a consulta SQL
    $sql = "SELECT codigo, nome FROM perfil_usuario";

    // Executando a consulta
    $resultado = $conecxao->query($sql);

    // Obtendo os resultados como um array associativo
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);

    // Fechando a conexão
    $conecxao->close();

    return $resultado;
}

function nome_recurso($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparando a consulta SQL
    $sql = "SELECT nome FROM recurso WHERE codigo = ?";

    // Preparando o statement
    $stmt = $conecxao->prepare($sql);

    // Vinculando o parâmetro
    $stmt->bind_param("i", $codigo);

    // Executando a consulta
    $stmt->execute();

    // Obtendo o resultado
    $resultado = $stmt->get_result();
    
    // Fechando o statement e a conexão
    $stmt->close();
    $conecxao->close();

    return $resultado->fetch_assoc()['nome'];
}

?>



