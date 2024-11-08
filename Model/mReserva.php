<?php 
function listar_reserva() {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $sql ="SELECT res.codigo AS codigo_reserva, rec.nome AS recurso, us.nome AS usuario
           FROM reserva AS res
           JOIN recurso AS rec ON rec.codigo = res.codigo_recurso
           JOIN usuario AS us ON us.codigo = res.codigo_usuario_utilizador
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

function listar_datas($codigo_reserva){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $consulta = $conexao->prepare("SELECT * FROM data_reserva WHERE codigo_reserva = ?");
    $consulta->bind_param('i', $codigo_reserva); // Tipo 'i' para integer
    $consulta->execute();
    $resultado = $consulta->get_result();
    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $consulta->close();
    $conexao->close();
    return $todos_dados;
}
?>
