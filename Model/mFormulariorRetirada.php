<?php 

function carrega_retirada_disponivel()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query("SELECT * from(
    select codigo, nome, (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'D' or rec.ultima_movimentacao is NULL;");
    $todos_dados = [];
    while ($dados = $resulta->fetch_assoc())
    {
        
        $todos_dados[] = $dados;
    }
    return $todos_dados;
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
    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();

    return $todos_dados;
}

function insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim, $dr)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $sql = "INSERT INTO retirada_devolucao(codigo_usuario, codigo_recurso, datahora, tipo, ativo, hora_final) VALUES ($retirante, $recurso,  '$data_hora', '$dr', 'S','$hora_fim')";
    $resultado = $conexao->query($sql);
    return $resultado;
}

function carrega_recursos_emprestados()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query("SELECT * from(
    select codigo, nome, (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'R' ;");
    $todos_dados = [];
    while ($dados = $resulta->fetch_assoc())
    {
        
        $todos_dados[] = $dados;
    }
    return $todos_dados;
}



function optios($dados){
    $opt = '<option value="NULL">...</option>';
    foreach($dados as $dado)
    {
        $opt .= '<option value="'. $dado['codigo'].'">'.mb_strtoupper($dado['nome'] ).'</option>';
    }
    return $opt;

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
