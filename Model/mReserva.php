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
           JOIN usuario AS us ON us.codigo = res.codigo_usuario_utilizador";
    
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

function listar_datas($codigo_reserva) {
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

function carregar_recurso() {
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT * FROM recurso');
    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc()) {
        $todos_dados[] = $dados;
    }

    return $todos_dados;
}

function carregar_usuario() {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conexao->query('SELECT * FROM usuario');
    $todos_dados = [];

    while ($dados = $resulta->fetch_assoc()) {
        $todos_dados[] = $dados;
    }

    return $todos_dados;
}

function Validar_reserva($justificativa, $data, $hora_inicial, $hora_final) {
    if (mb_strlen($justificativa) < 3 || mb_strlen($justificativa) > 100) {
        return 1; // Justificativa inválida
    }
    if (empty($data)) {
        return 2; // Data não fornecida
    }

    if (empty($hora_inicial) || empty($hora_final)) {
        return 3; // Horário não fornecido
    }
    if ($hora_inicial >= $hora_final) {
        return 4; // Hora inicial não pode ser maior ou igual a hora final
    }
    return true;
}

function inserir_reserva($justificativa, $recurso, $usuario_utilizador, $lista_datas) {
    
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $justificativa = trim($justificativa);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }


    $verifica = Validar_reserva($justificativa, $lista_datas[0]['data'], $lista_datas[0]['hora_inicial'], $lista_datas[0]['hora_final']);
    if ($verifica !== true) {
        return $verifica; 
    }

    if (isset($_SESSION['codigo_usuario'])) {
        $usuario_agendador = $_SESSION['codigo_usuario'];
    } else {
        return "Erro: Usuário não autenticado."; 
    }

    
    $stmt = $conexao->prepare("INSERT INTO reserva (justificativa, codigo_usuario_agendador, codigo_usuario_utilizador, codigo_recurso)
            VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $justificativa, $usuario_agendador, $usuario_utilizador, $recurso);

    if (!$stmt->execute()) {
        echo "Erro: " . $conexao->error;
        return false; 
    }


    $reserva_id = $stmt->insert_id; 
    $stmt = $conexao->prepare("INSERT INTO data_reserva (codigo_reserva, data, hora_inicial, hora_final) 
                 VALUES (?, ?, ?, ?)");
    
    // Insere as datas da reserva
    foreach ($lista_datas as $data) {
        $stmt->bind_param('isss', $reserva_id, $data['data'], $data['hora_inicial'], $data['hora_final']);
        if (!$stmt->execute()) {
            $conexao->rollback(); 
            $stmt->close();
            $conexao->close();
            return 6; 
        }
    }

    $conexao->commit(); 
    $stmt->close();
    $conexao->close();
    return 5; 
}

function apagar_reserva($codigo) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $conexao->query("DELETE FROM data_reserva WHERE codigo_reserva = $codigo_reserva");
        $conexao->query("DELETE FROM reserva WHERE codigo_reserva = $codigo_reserva");
    } else {
        echo "Erro de conexão: " . $conexao->connect_error;
    }
}

?>