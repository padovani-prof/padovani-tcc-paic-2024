<?php 



function carrega_categorias_recurso()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement para segurança (embora a consulta não tenha parâmetros dinâmicos, é uma boa prática)
    $stmt = $cone->prepare('SELECT * FROM categoria_recurso');
    $stmt->execute();
    $resulta = $stmt->get_result();
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $cone->close();
    
    return $resulta; // Retorna todos os dados da tabela categoria_recurso em forma de lista
}

function apagar_categoria($chave_pri)
{
    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement para prevenir SQL Injection
    $stmt = $conecxao->prepare("DELETE FROM categoria_recurso WHERE codigo = ?");
    $stmt->bind_param("i", $chave_pri);  // "i" para inteiro
    $resulta = $stmt->execute();
    $stmt->close();

    return $resulta;
}
?>




