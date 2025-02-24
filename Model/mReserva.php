<?php

function carregar_reserva(){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    $sql = "SELECT 
	reserva.codigo as 'codigo',
	recurso.nome as 'recurso',
	data_re.data as 'data',
    data_re.hora_inicial as 'h_ini',
    data_re.hora_final as 'h_fim',
    usuario_ultilizador.nome as 'utilizador'
from reserva 
	INNER join data_reserva as data_re
    on data_re.codigo_reserva = reserva.codigo
	INNER JOIN usuario as usuario_agendador
    on usuario_agendador.codigo=reserva.codigo_usuario_agendador
    inner join recurso
    on recurso.codigo= reserva.codigo_recurso
    inner join usuario as usuario_ultilizador
    on reserva.codigo_usuario_utilizador = usuario_ultilizador.codigo
    ORDER by data_re.data DESC;";

    $resulta = $cone->query($sql);

    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $cone->close();

    return $resulta;
   


}


function carregar_recurso() {
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT * FROM recurso');
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resulta;
}

function carregar_usuario() {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conexao->query('SELECT * FROM usuario');
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $conexao->close();

    return $resulta;
}

function Validar_reserva($justificativa, $data, $hora_inicial, $hora_final, $recurso) {

    if (empty($justificativa)) {
        return 0; // Justificativa Vazia
    }
    if (mb_strlen($justificativa) > 100 or mb_strlen($justificativa) < 3){
        return 1; //Justificativa Inválida
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
    $hora_inicial.=':00';
    $hora_final.=':00';
    
    
    if(count(Disponibilidade([$data, $hora_inicial, $hora_final], [], [$recurso]))==0){
        // verifica se esta disponivel
        return 6;
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


    $verifica = Validar_reserva($justificativa, $lista_datas[0]['data'], $lista_datas[0]['hora_inicial'], $lista_datas[0]['hora_final'], $recurso);
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

function apagar_reserva($codigo_reserva) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    $resposta = $conexao->query("SELECT * FROM reserva_ensalamento WHERE codigo_reserva = $codigo_reserva");
    if ($resposta->num_rows == 0) {
        $conexao->query("DELETE FROM data_reserva WHERE codigo_reserva =  $codigo_reserva");
        $conexao->query("DELETE FROM reserva WHERE codigo = $codigo_reserva");
        return true;
    }
    return false;
}

?>

