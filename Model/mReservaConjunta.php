<?php

function insere_reserva($justificativa, $usuario_agendador, $usuario_utilizador, $recurso) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false; // Retorna falso caso haja erro na conexão
    }

    // Preparar e executar a inserção da reserva
    $stmt = $conexao->prepare("INSERT INTO reserva (justificativa, codigo_usuario_agendador, codigo_usuario_utilizador, codigo_recurso) 
                               VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $justificativa, $usuario_agendador, $usuario_utilizador, $recurso);
    $stmt->execute();

    // Obter o último ID inserido
    $stmt = $conexao->query("SELECT LAST_INSERT_ID() as codigo");
    $id = $stmt->fetch_assoc()['codigo'];

    // Fechar a conexão e retornar o ID
    $stmt->close();
    $conexao->close();

    return $id;
}

function insere_data_reserva($data, $hora_ini, $hora_fim, $reserva) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false; // Retorna falso caso haja erro na conexão
    }

    // Preparar e executar a inserção da data de reserva
    $stmt = $conexao->prepare("INSERT INTO data_reserva (codigo_reserva, data, hora_inicial, hora_final) 
                               VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $reserva, $data, $hora_ini, $hora_fim);
    $stmt->execute();

    // Fechar a conexão e retornar o sucesso
    $stmt->close();
    $conexao->close();

    return true;
}

function consta_reserva($recurso, $data, $hora_ini, $hora_fim) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false; // Retorna falso caso haja erro na conexão
    }

    // Preparar e executar a consulta para verificar se já existe uma reserva na mesma data e horário
    $stmt = $conexao->prepare("SELECT codigo_reserva FROM data_reserva 
                               WHERE data = ? AND hora_inicial = ? AND hora_final = ?");
    $stmt->bind_param("sss", $data, $hora_ini, $hora_fim);
    $stmt->execute();
    $resulta = $stmt->get_result()->fetch_assoc();

    // Se não houver reserva, retorna false
    if (!$resulta) {
        $stmt->close();
        $conexao->close();
        return false;
    }

    // Verificar se o recurso tem a reserva associada
    $codigo_reserva = $resulta['codigo_reserva'];
    $stmt = $conexao->prepare("SELECT COUNT(reserva.codigo) AS total 
                               FROM reserva WHERE reserva.codigo_recurso = ? AND reserva.codigo = ?");
    $stmt->bind_param("ii", $recurso, $codigo_reserva);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    // Fechar a conexão e retornar o resultado
    $stmt->close();
    $conexao->close();

    return $resultado['total'] > 0;
}

?>
