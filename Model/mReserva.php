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
    $consulta->bind_param('i', $codigo_reserva);
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

function carregar_recurso()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT *  from recurso');
    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc()) {
        $todos_dados[] = $dados;
    }

    
    return $todos_dados;
    

}

function carregar_usuario()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT *  from usuario');
    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc()) {
        $todos_dados[] = $dados;
    }

    
    return $todos_dados;

}

function Validar_reserva($justificativa, $data, $hora_inicial, $hora_final) {
    if (mb_strlen($justificativa) < 5 || mb_strlen($justificativa) > 100) {
        return 1;
    }
    if (empty($data)) {
        return 2; 
    }

    if (empty($hora_inicial) || empty($hora_final)) {
        return 3;
    }
    if ($hora_inicial >= $hora_final) {
        return 4;
    }
    return true; 
}

function inserir_reserva($justificativa, $usuario_agendador, $recurso, $usuario_utilizador, $data, $hora_inicial, $hora_final) {

    $justificativa = trim($justificativa);

    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conexao->connect_error) {
        return "Erro na conexão: " . $conexao->connect_error;
    }

    foreach ($datas_reservas as $data_horario) {
        $data = $data_horario['data'];
        $hora_inicial = $data_horario['hora_inicial'];
        $hora_final = $data_horario['hora_final'];

        $validar = Validar_reserva($justificativa, $data, $hora_inicial, $hora_final);
        if ($validar !== true) {
            return $validar;
        }

        $reserva = "INSERT INTO justificativa (codigo_usuario_agendador, codigo_recurso, codigo_usuario_utilizador)
                          VALUES ('$justificativa', '$usuario_agendador', '$recurso', '$usuario_utilizador')";

        if (!$conexao->query($reserva_query)) {
            return "Erro ao inserir justificativa: " . $conexao->error;
        }

        $data_reserva = "INSERT INTO data_reserva (data, hora_inicial, hora_final)
                               VALUES ('$data', '$hora_inicial', '$hora_final')";

        if (!$conexao->query($data_reserva)) {
            return "Erro ao inserir data da reserva: " . $conexao->error;
        }
    }

    return 5; // Código de sucesso
}


function apagar_data($codigo_data) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $conexao->query("DELETE FROM data_reserva WHERE codigo = '$codigo_data'");
    }
}

?>


