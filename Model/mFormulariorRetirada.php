
<?php 



function carrega_retirada_disponivel()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT * from(
    select codigo, nome, (
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
        on data_reserva.codigo_reserva = reserva.codigo
        WHERE reserva.codigo_recurso = $recurso and reserva.codigo_usuario_utilizador = $retirante 
        and data_reserva.data = '$data' and  data_reserva.hora_final > '$h_ini' and data_reserva.hora_inicial < '$hora_fim';";
        
        $resultado = $conexao->query($sql);
        $id = $resultado ->fetch_assoc()['codigo'];
        
        $sql = "UPDATE data_reserva set hora_final = '$i' where codigo_reserva = $id;";
        $resultado = $conexao->query($sql);

    }
    
    $sql = "INSERT INTO retirada_devolucao(codigo_usuario, codigo_recurso, datahora, tipo, ativo, hora_final) VALUES ($retirante, $recurso,  '$data_hora', '$dr', 'S','$hora_fim')";
    $resultado = $conexao->query($sql);
    return $resultado;
}

function carrega_recursos_emprestados()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT * from(
    select codigo, nome, (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'R' ;");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resultado;
}







function verificar_reserva_do_retirante($periodo, $retirante, $recurso){

    $data = str_replace('/','-', $periodo[0]);
    $h_ini = $periodo[1];
    $h_fim = $periodo[2];
    $sql = "SELECT * from reserva WHERE codigo_recurso=$recurso and codigo_usuario_utilizador = $retirante and codigo in (SELECT data_reserva.codigo_reserva from data_reserva where data_reserva.data = '$data' and hora_inicial <='$h_fim' and hora_final >= '$h_ini');";
    include 'confg_banco.php';
    //echo $sql;
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query($sql);

    if ($resulta->num_rows == 1){
        return true;

    }
    return false;
    
}


?>
