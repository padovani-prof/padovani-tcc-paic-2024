
<?php 


function verificar_permicao_recurso($data, $h_ini, $h_fim, $recurso, $retirador, $dia_semana){
    include 'confg_banco.php';
    // verifica se em um recurso em um determinado periodo de reitrada ele tem permição para verirar o recurso
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    $sql = "SELECT usuario.nome FROM usuario WHERE usuario.codigo IN (
        SELECT usuario_perfil.codigo_usuario FROM usuario_perfil 
        WHERE usuario_perfil.codigo_perfil IN (
            SELECT acesso_recurso.codigo_perfil FROM acesso_recurso 
            WHERE acesso_recurso.hr_inicial <= ?
            AND acesso_recurso.hr_final >= ?
            AND acesso_recurso.dt_inicial <= ?
            AND (acesso_recurso.dt_final >= ? OR acesso_recurso.dt_final IS NULL)
            AND acesso_recurso.codigo_recurso = ?
            AND SUBSTRING(acesso_recurso.dias_semana, ?, 1) = 'S'
        )
    ) AND usuario.codigo = ?";




    $stmt = $cone->prepare($sql);
    $stmt->bind_param("ssssiii", $h_ini, $h_fim, $data, $data, $recurso, $dia_semana, $retirador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    return ($resultado->num_rows > 0);

}


function carrega_categorias_recurso()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $cone->query('SELECT *  from categoria_recurso');
    $resulta = $resulta->fetch_all(MYSQLI_ASSOC);
    $cone->close();
    
    return $resulta;
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}
   
function Carregar_recursos_dados(){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $conecxao->query("SELECT * from recurso");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conecxao->close();
    return $resultado;
}


function Disponibilidade($periodo, $categorias, $recursos)
{

    
   
	include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    $sql = 'SELECT 
        rec.codigo AS codigo_recurso, 
        rec.nome AS nome_recurso, 
        dts.data AS data_alvo, 
        dts.hora_inicial AS hora_inicial_alvo, 
        dts.hora_final AS hora_final_alvo 
    FROM sgrp.recurso rec
    LEFT JOIN sgrp.categoria_recurso cat 
        ON rec.codigo_categoria = cat.codigo
    CROSS JOIN (';

    $t = count($periodo);
    for ($i = 0; $i < $t; $i += 3) 
    { 
        $data = trim(str_replace('[', '', $periodo[$i]));
        $hora_in = trim($periodo[$i + 1]);
        $hora_fim = trim(str_replace(']', '', $periodo[$i + 2]));

        // Aspas simples para delimitar valores de data e hora
        $sql .= "SELECT CAST('$data' AS date) AS data, CAST('$hora_in' AS TIME) AS hora_inicial, CAST('$hora_fim' AS TIME) AS hora_final";
        if ($i < $t - 3) {
            $sql .= " UNION ALL ";
        }
    }

    $sql .= ') AS dts
    LEFT JOIN sgrp.reserva res
        ON res.codigo_recurso = rec.codigo
    LEFT JOIN sgrp.data_reserva dtr
        ON res.codigo = dtr.codigo_reserva AND
        dtr.data = dts.data AND
        (
            (dts.hora_inicial >= dtr.hora_inicial AND dts.hora_inicial < dtr.hora_final) OR
            (dts.hora_final > dtr.hora_inicial AND dts.hora_final <= dtr.hora_final) OR
            (dts.hora_inicial <= dtr.hora_inicial AND dts.hora_final >= dtr.hora_final)
        )
    WHERE ';

    // Construindo listas de categorias e recursos
    $cate = implode(',', array_map('intval', $categorias !== NULL ? $categorias : array()));
    $recu = implode(',', array_map('intval', $recursos !== NULL? $recursos : array()));
  
    if ($recu == ""){
        $sql .= "rec.codigo_categoria IN ($cate)";
        
    } else if ($cate == ""){
        $sql .= "rec.codigo IN ($recu)";
    } else{
        $sql .= "rec.codigo IN ($recu) OR rec.codigo_categoria IN ($cate)";
    }
    $sql .= "GROUP BY 1, 2, 3, 4, 5 HAVING COUNT(dtr.data) = 0 order by 1,3,4,5";

    //echo $sql;
    
    
    $resultado = $cone->query($sql);
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();



    return $resultado;
    



}



function adicionados($recurso, $categorias)
{


    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    $recurso = implode(',', array_map('intval', $recurso !== NULL ? $recurso : array()));
    $categorias = implode(',', array_map('intval', $categorias !== NULL ? $categorias : array()));



    
    $sql = "SELECT rec.codigo AS codigo_recurso, rec.nome AS nome_recurso FROM sgrp.recurso rec WHERE " ;
    if ($recurso == ""){
        $sql .= "rec.codigo_categoria IN ($categorias)";
        
    } else if ($categorias == ""){
        $sql .= "rec.codigo IN ($recurso)";
    } else{
        $sql .= "rec.codigo IN ($recurso) OR rec.codigo_categoria IN ($categorias)";
    }


    $resultado = $cone->query($sql);
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();



    return $resultado;
    
}








?>
