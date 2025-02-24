<?php 


function insere_reserva($justificativa, $usuario_agendador, $usuario_utilizador, $recurso){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conexao->query("INSERT INTO reserva (justificativa, codigo_usuario_agendador, codigo_usuario_utilizador, codigo_recurso)
            VALUES ( '$justificativa', $usuario_agendador, $usuario_utilizador, $recurso);");
    $resulta = $conexao->query("SELECT LAST_INSERT_ID() as codigo;");
    $id = $resulta->fetch_assoc()['codigo'];
    return $id;
}


function insere_data_reserva($data, $hora_ini, $hora_fim, $reseva){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conexao->query("INSERT INTO data_reserva (codigo_reserva, data, hora_inicial, hora_final) 
                 VALUES ($reseva, '$data', '$hora_ini', '$hora_fim')");
    return $resulta;
    


}




function consta_reserva($recurso, $data, $hora_ini, $hora_fim){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conexao->query("SELECT codigo_reserva FROM data_reserva WHERE data_reserva.data='$data' and data_reserva.hora_inicial='$hora_ini' AND data_reserva.hora_final='$hora_fim';");
    $resulta = $resulta->fetch_assoc();
     if ($resulta!=null){
        $resulta = $resulta['codigo_reserva'];
        $resultado = $conexao->query("SELECT COUNT(reserva.codigo)as 'total' from reserva WHERE reserva.codigo_recurso=$recurso and reserva.codigo=$resulta;");
        return $resultado->fetch_assoc()['total'] > 0;
        
    }
    return false;
    

}






?>


