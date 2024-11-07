<?php 
function listar_reserva() {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $sql = "
        SELECT res.codigo, rec.nome, us.nome 
        FROM reserva as res, recurso as rec, usuario as us 
        WHERE rec.codigo = res.codigo_recurso AND us.codigo = res.codigo_usuario_utilizador 
    ";
    
    $resultado = $conexao->query($sql);
    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();
    return $todos_dados;
}
function listar_datas($data){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $resultado = $conexao->query($sql);
    $resultado = $conexao->query("SELECT * FROM data_reserva WHERE codigo_reserva = ?");
    $consulta->bind_param('s', $data);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();
    return $todos_dados;

}

?>
