<?php 

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
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

include_once 'Model/mDisponibilidade.php';
$html = file_get_contents('View/vResultadoDisponibilidade.php');



$categorias =  $_GET['categorias'];
$recursos = $_GET['recursos'];
$periodos = $_GET['periodos'];







$categorias = transformar_em_lista($categorias);
$recursos = transformar_em_lista($recursos);
$periodos = transformar_em_lista($periodos);







$chaves_cate = chaves($categorias);
$chaves_recursos =  chaves($recursos);




$disponives = Disponibilidade($periodos, $chaves_cate, $chaves_recursos);


$recurso_catego = adicionados($chaves_recursos, $chaves_cate);





$coluna = '';


for($i = 0; $i < count($periodos); $i += 3)
{
    $data = explode('-', $periodos[$i]);
    $coluna = $coluna.'<th>'. $periodos[$i + 1] . ' até ' . $periodos[$i+2].' de '.$data[2].'/'.$data[1].'/'.$data[0].'</th>';
}



$recurs_dados = '';
for ($i=0; $i < count($recurso_catego); $i++) 
{ 
    $recurs_dados .= ' <tr> <td>'. $recurso_catego[$i]['nome_recurso'].'</td>';
    for ($d=0; $d < count($periodos) ; $d+=3) 
    { 

        $recurs_dados .= ' <td> ';
        if( ta_livre($recurso_catego[$i]['codigo_recurso'], $periodos[$d], $periodos[$d+1].':00', $periodos[$d+2].':00', $disponives))
        {
            $recurs_dados .='<label>
            <input type="checkbox" name="marcas[]" value="'.'">

            </label>
        </td>';
        }
        else{
            $recurs_dados .='X';
        }

        
    }
    $recurs_dados .= '</tr>';
}


/*
// SELECT rec.codigo AS codigo_recurso, rec.nome AS nome_recurso FROM recurso rec WHERE rec.codigo IN (1) OR rec.codigo_categoria IN (1);

SELECT rec.codigo AS codigo_recurso, rec.nome AS nome_recurso FROM sgrp.recurso rec WHERE rec.codigo IN (1) OR rec.codigo_categoria IN (1)
*/



$html = str_replace('{{Colunas}}',$coluna,$html);
$html = str_replace('{{Disponibilidades}}', $recurs_dados, $html);




echo $html;

?>




     
