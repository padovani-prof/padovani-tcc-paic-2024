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


function tabe_html($dados)
{
    $ht_dados = '';
    $id = -1;
    for ($i=0; $i < count($dados); $i++){
        $recurso = explode(',', $dados[$i]);
        $id_recu = $recurso[0];
        $data = explode('-', $recurso[2]) ;

        if ($id!= $id_recu){
            if($id != -1){
                $ht_dados.= '</tr>';
            }
            $ht_dados.= '<tr> <td> '.$recurso[1].'</td> <td>';
            $id = $id_recu;
        }
        else{
            $ht_dados.='<br>';
        }
        $ht_dados.= $data[2].'/'.$data[1].'/'.$data[0].'<br>'.$recurso[3] . ' - '.$recurso[4];
    }
    return $ht_dados;

}


function dados_hidem($dados){
    $ht_dados = '';
    foreach($dados as $dado){
        $ht_dados.= '<input type="hidden" name="marcas[]" value="'. $dado.'" >';
    }
    return $ht_dados;
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


function verificar_reservar($dados){
    foreach( $dados as $dado){
        $td_dados = explode(',', $dado);
        if (consta_reserva($td_dados[0], $td_dados[2], $td_dados[3].':00', $td_dados[4].':00')==true){
            return true;

        }


    }
    return false;

}


function Reserva_conjunta ($dados, $agendador, $utilizador, $justific){
    foreach( $dados as $dado){
        $td_dados = explode(',', $dado);
        $id_reserva = insere_reserva($justific, $agendador, $utilizador, $td_dados[0]);
        $certo = insere_data_reserva($td_dados[2], $td_dados[3].':00', $td_dados[4].':00', $id_reserva);

    }
}




?>


