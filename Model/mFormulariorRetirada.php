
<?php 


function carrega_retirada_disponivel()
{
    
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT * from(
    select codigo, nome,  recurso.descricao, (
        select tipo from retirada_devolucao where recurso.codigo = codigo_recurso order by datahora desc limit 1
        ) as ultima_movimentacao from recurso) as rec
    where rec.ultima_movimentacao = 'D'  or rec.ultima_movimentacao is NULL;");
   
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resultado;
}


function criar_reserva_retirada($usuario_utilizador, $recurso, $data, $hora_ini, $hora_fim){
    $justi = 'Retirada sem reserva';
    $usuario_agendador = 1;  // sera colocado um codigo da pessoa que fez a retirada para o usuario que no caso o agp 1
    include 'mReservaConjunta.php';
    $id = insere_reserva($justi, $usuario_agendador, $usuario_utilizador, $recurso);
    insere_data_reserva($data, $hora_ini, $hora_fim, $id);
    return $id;
}


function verificar_usuario_devolucao($cod_recu, $cod_usua){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query("SELECT r.codigo as codigo_recurso, codigo_usuario, r.nome as nome_recurso, tipo, rd.codigo_reserva from sgrp.recurso r,

    sgrp.retirada_devolucao rd
    where
        r.codigo = rd.codigo_recurso and
        r.codigo = $cod_recu
    order by rd.datahora desc
    limit 1");
    $resulta = $resulta->fetch_assoc();
    if($resulta['codigo_usuario']==$cod_usua)
    {
        return [true, $resulta['codigo_reserva']];
    }
    return [false, null ];
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

function insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim, $dr, $reserva)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    if($dr== 'D'){
        // atuliza para o horario devolvido
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
        
        $id = $resultado->fetch_assoc();
        if (!$id==null){
            $id = $id['codigo'];
            $sql = "UPDATE data_reserva SET hora_final = ? WHERE codigo_reserva = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("si", $i, $id);

            $stmt->execute();

        }
        
        

    }
    
    $sql = "INSERT INTO retirada_devolucao(codigo_usuario, codigo_recurso, datahora, tipo, ativo, hora_final, codigo_reserva) VALUES (?, ?, ?, ?, 'S', ?, ?)";



    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("iisssi", $retirante, $recurso, $data_hora, $dr, $hora_fim, $reserva);
    $stmt->execute();
    return $conexao->insert_id ;
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

function Verificar_se_pertence_a_lista($chave_recusso, $marcados){
    foreach ($marcados as $acessorio){
        if ($acessorio == $chave_recusso){
            return true;
        }
    }
    return false;
}

function retirada_checklist($checklist ,$marcados,  $id_retirada){
    // essa função ela percorre os dados selecionados e insere uma retirada de checklist na tabela de devolução de checklist 
    // mando o dado como não devolvido
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    foreach ($checklist as $acessorio){
            $retirado = 'N';
            $chave_recusso = $acessorio['codigo'];
            if (Verificar_se_pertence_a_lista($chave_recusso, $marcados)){
                $retirado = 'S';
                $indice = array_search($chave_recusso, $marcados);
                unset($marcados[$indice]);
                $marcados = array_values($marcados);
            }
            $stmt = $conecxao->prepare("INSERT INTO devolucao_checklist (codigo_checklist, codigo_retirada_devolucao, retirado_devolvido) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $chave_recusso, $id_retirada, $retirado);  //  "i" para inteiro
            $stmt->execute();
    }    
}



function cancelaRetirada($recurso, $devolvente){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $executa = $conecxao->prepare("SELECT retirada_devolucao.codigo, retirada_devolucao.codigo_reserva, reserva.justificativa from retirada_devolucao, reserva where retirada_devolucao.codigo_usuario = ? and retirada_devolucao.codigo_recurso = ? and retirada_devolucao.tipo = 'R' ORDER by retirada_devolucao.datahora desc LIMIT 1;");
    $executa->bind_param('ii', $devolvente, $recurso);
    $executa->execute();
    $resultado = $executa->get_result(); 
    if($resultado->num_rows > 0){
        // apaga o checklist e apos a retirada
        $resultado = $resultado->fetch_assoc();
        $codigo = $resultado['codigo'];
        $retirada_sem_reseva = $resultado['justificativa']=='Retirada sem reserva';
        
        $codigo_reserva =  $resultado['codigo_reserva'];
        $conecxao->query("DELETE from devolucao_checklist WHERE devolucao_checklist.codigo_retirada_devolucao = $codigo;");
        $conecxao->query("DELETE from retirada_devolucao WHERE retirada_devolucao.codigo = $codigo;");
        if($retirada_sem_reseva == true){
            $conecxao->query("DELETE from data_reserva WHERE data_reserva.codigo_reserva = $codigo_reserva;");
            $conecxao->query("DELETE from reserva WHERE reserva.codigo = $codigo_reserva;");
        }
        

        
        $resposta =  true;

    }else{
        
        $resposta =  false;
    }
    $executa->close();
    $conecxao->close();
    return $resposta;

    

}




function carrega_recurssos_devolvidos(){

    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->query("SELECT recurso.codigo, recurso.nome, recurso.descricao
    FROM retirada_devolucao
    INNER JOIN recurso
    on recurso.codigo = retirada_devolucao.codigo_recurso
    WHERE datahora = (SELECT MAX(datahora) FROM retirada_devolucao) and retirada_devolucao.tipo = 'D'");
   
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $resultado;

}

function verificar_devolucao_usuario($recuso, $devolvente){

    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->prepare("SELECT retirada_devolucao.codigo, retirada_devolucao.codigo_usuario, retirada_devolucao.tipo FROM retirada_devolucao   WHERE retirada_devolucao.codigo_recurso = ? ORDER by retirada_devolucao.datahora desc LIMIT 1;");
    $resultado->bind_param('i', $recuso);
    $resultado->execute(); 
    $resultado = $resultado->get_result();
    $resposta = false;
    if($resultado->num_rows > 0){
        $resultado = $resultado->fetch_assoc();
        if($resultado['tipo']=='D' and $resultado['codigo_usuario']==$devolvente){
            $id_devolucao = $resultado['codigo'];
            $cone->query("DELETE from devolucao_checklist WHERE devolucao_checklist.codigo_retirada_devolucao = $id_devolucao;");
            $cone->query("DELETE from retirada_devolucao WHERE retirada_devolucao.codigo = $id_devolucao;");


            $resposta = true;
        }
    }

    $cone->close();
    return $resposta;
}




function Confirmar_usuario_retirada($chave_usu, $senha_usuario){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->prepare("SELECT * FROM usuario WHERE usuario.codigo = ? and usuario.senha = ?; ");
    $resultado->bind_param('is', $chave_usu, $senha_usuario);
    $resultado->execute(); 
    $resultado = $resultado->get_result();

    $resultado = $resultado->num_rows > 0;

    $cone->close();
    return $resultado;

}


?>