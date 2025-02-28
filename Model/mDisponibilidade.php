
<?php 



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





function transformar_em_lista($str)

{
    $lista  = explode(',', $str);
    for ($i=0; $i < count($lista); $i++) { 
        $dado =  str_replace('[','',$lista[$i]);
        $dado =  str_replace(']','',$dado);
        $dado = str_replace('"', '',$dado);

        $lista[$i] = $dado;

    }
    return $lista;
}



function chaves($lista)
{
    $lista_chaves = array();
    if(count($lista)>=3)
    {
        
        for ($i=0; $i < count($lista); $i = $i + 3) 
        { 
            $lista_chaves[] = $lista[$i+2];
        }
    }
    return $lista_chaves;

}

function ta_livre($codigo, $data, $h_ini, $h_fim, $disponives)
{
    
    foreach($disponives as $livre)
    {
        if($livre['codigo_recurso']===$codigo and trim($livre["data_alvo"])=== trim($data) and trim($livre['hora_inicial_alvo'])===trim($h_ini) and trim($livre['hora_final_alvo'])===trim($h_fim))
        {
            return true;
        }
    }
    return false;
}



function mandar_hindem($lista, $name){
    $inpu = ''; 
    foreach($lista as $dado){
        $inpu.="<input type='hidden' name='$name' value='$dado'>";
    }
    return $inpu;
    

}
?>
