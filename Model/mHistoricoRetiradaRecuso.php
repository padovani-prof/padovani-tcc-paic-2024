<?php 



function dados_retirada($recurso){


    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->prepare("SELECT retirada_devolucao.codigo, 
usuario.nome,  usuario.codigo as cod_usuario,
retirada_devolucao.codigo_reserva,
DATE_FORMAT(retirada_devolucao.datahora, '%d/%m/%Y') as data_retira,
DATE_FORMAT(retirada_devolucao.datahora, '%H:%i:%s') as hora_retira,
retirada_devolucao.hora_final
FROM retirada_devolucao 
INNER JOIN usuario 
on usuario.codigo = retirada_devolucao.codigo_usuario
INNER JOIN recurso
on recurso.codigo = retirada_devolucao.codigo_recurso
where retirada_devolucao.codigo_recurso = ? and retirada_devolucao.tipo = 'R' ORDER by retirada_devolucao.datahora DESC;");
    $resultado->bind_param('i', $recurso);
    $resultado->execute(); 
    $resultado = $resultado->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $dados;

}



function tem_devolucao($recurso, $data, $usu, $hora_retira, $retirada){
    include 'confg_banco.php';


   

    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    if($retirada==null) {
        $sql = "SELECT retirada_devolucao.codigo, TIME(retirada_devolucao.datahora) AS hora_devolu FROM retirada_devolucao 
    WHERE retirada_devolucao.tipo = 'D'
    and retirada_devolucao.codigo_recurso = ? 
    and retirada_devolucao.codigo_usuario = ?
    and retirada_devolucao.codigo_reserva is null
    and ? <=  retirada_devolucao.hora_final
    and DATE_FORMAT(DATE(retirada_devolucao.datahora), '%d/%m/%Y') = ? ORDER by retirada_devolucao.hora_final asc  LIMIT 1 ;";
    $resultado = $cone->prepare($sql);
    $resultado->bind_param('iiss', $recurso, $usu,  $hora_retira, $data);

    }else{
        $sql = "SELECT retirada_devolucao.codigo, TIME(retirada_devolucao.datahora) AS hora_devolu FROM retirada_devolucao WHERE  retirada_devolucao.codigo_reserva = ? and retirada_devolucao.tipo = 'D';";
    $resultado = $cone->prepare($sql);
    $resultado->bind_param('i', $retirada );

    }
    $resultado->execute(); 
    $resultado = $resultado->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $dados;

}






function carregar_checlist_retirado($codigo){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->prepare("SELECT devolucao_checklist.codigo_checklist, checklist.item from devolucao_checklist 
    INNER JOIN checklist
    on checklist.codigo = devolucao_checklist.codigo_checklist
    WHERE devolucao_checklist.codigo_retirada_devolucao = ? and devolucao_checklist.retirado_devolvido = 'S';");
    $resultado->bind_param('i',$codigo);
    $resultado->execute(); 
    $resultado = $resultado->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $dados;





}


function carregar_checlist_devolvido($codigo){
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $cone->prepare("SELECT devolucao_checklist.codigo_checklist, checklist.item from devolucao_checklist 
    INNER JOIN checklist
    on checklist.codigo = devolucao_checklist.codigo_checklist
    WHERE devolucao_checklist.codigo_retirada_devolucao = ?;");
    $resultado->bind_param('i',$codigo);
    $resultado->execute(); 
    $resultado = $resultado->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    return $dados;





}

























?>