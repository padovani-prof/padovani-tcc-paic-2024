
<?php 


function carrega_retirada_disponivel()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT * from(
    select codigo, nome,  recurso.descricao, (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'D' or rec.ultima_movimentacao is NULL;");
   
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resultado;
}


function criar_reserva_retirada($usuario_utilizador, $recurso, $data, $hora_ini, $hora_fim){
    $justi = 'Retirada sem reserva';
    $usuario_agendador = 1;  // sera colocado um codigo da pessoa que fez a retirada para o usuario
    include 'mReservaConjunta.php';
    $id = insere_reserva($justi, $usuario_agendador, $usuario_utilizador, $recurso);
    return insere_data_reserva($data, $hora_ini, $hora_fim, $id);
}


function verificar_usuario_devolucao($cod_recu, $cod_usua){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query("SELECT r.codigo as codigo_recurso, codigo_usuario, r.nome as nome_recurso, tipo from sgrp.recurso r, sgrp.retirada_devolucao rd
    where
        r.codigo = rd.codigo_recurso and
        r.codigo = $cod_recu
    order by rd.datahora desc
    limit 1");
    $resulta = $resulta->fetch_assoc();
    if($resulta['codigo_usuario']==$cod_usua)
    {
        return true;
    }
    return false;
}


function listar_usuarios(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT codigo, nome 
    FROM usuario
    ORDER BY nome ASC;");

    if ($resultado) {
        $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
        
        
    }

    $conexao->close();

    return $resultado;
}

function insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim, $dr)
{

    
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    if($dr== 'D'){
        $data = explode(' ', $data_hora);
        date_default_timezone_set('America/Manaus');
        $i = date('H:i:s');
        $h_ini = $data[1];
        $data = $data[0];
        $sql = "SELECT reserva.codigo FROM reserva
        INNER JOIN data_reserva
        ON data_reserva.codigo_reserva = reserva.codigo
        WHERE reserva.codigo_recurso = ? AND reserva.codigo_usuario_utilizador = ?
        AND data_reserva.data = ? AND data_reserva.hora_final > ? AND data_reserva.hora_inicial < ?";
        
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iisss", $recurso, $retirante, $data, $h_ini, $hora_fim);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $id = $resultado->fetch_assoc()['codigo'];

        $sql = "UPDATE data_reserva SET hora_final = ? WHERE codigo_reserva = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $i, $id);
        $stmt->execute();

    }
    
    $sql = "INSERT INTO retirada_devolucao(codigo_usuario, codigo_recurso, datahora, tipo, ativo, hora_final) VALUES (?, ?, ?, ?, 'S', ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iisss", $retirante, $recurso, $data_hora, $dr, $hora_fim);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

function carrega_recursos_emprestados()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT * from(
    select codigo, nome, recurso.descricao , (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'R' ;");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resultado;
}






function verificar_reserva_do_retirante($periodo, $retirante, $recurso)
{
    $data = str_replace('/', '-', $periodo[0]);
    $h_ini = $periodo[1];
    $h_fim = $periodo[2];

    // Conectar ao banco de dados
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparar a consulta usando prepared statements
    $sql = "
        SELECT * 
        FROM reserva 
        WHERE codigo_recurso = ? 
        AND codigo_usuario_utilizador = ? 
        AND codigo IN (
            SELECT data_reserva.codigo_reserva 
            FROM data_reserva 
            WHERE data_reserva.data = ? 
            AND hora_inicial <= ? 
            AND hora_final >= ?
        )
    ";

    $stmt = $cone->prepare($sql);
    
    // Verificar se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $cone->error);
    }

    // Vincular os parâmetros ao prepared statement
    $stmt->bind_param("iisss", $recurso, $retirante, $data, $h_fim, $h_ini);

    // Executar a consulta
    $stmt->execute();

    // Obter o resultado da consulta
    $resulta = $stmt->get_result();

    // Verificar se foi encontrado algum registro
    if ($resulta->num_rows == 1) {
        $stmt->close();
        $cone->close();
        return true;
    }

    $stmt->close();
    $cone->close();
    return false;
}



?>
