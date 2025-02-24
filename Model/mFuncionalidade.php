<?php 
function listar_funcionalidades(){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM funcionalidade");

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();

    // Retorna o array com todos os dados
    return $resultado;
}

?>
