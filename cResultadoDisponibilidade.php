<?php 



include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);


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
    // 01/10/2024 das 12:00:00 às 15:00:00

    $data = explode('-', $periodos[$i]);
    $coluna = $coluna.'<th>'.$data[2].'/'.$data[1].'/'.$data[0].' das '. $periodos[$i + 1] . ' ás ' . $periodos[$i+2].'</th>';
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
            <input type="checkbox" name="marcas[]" value="'.$recurso_catego[$i]['codigo_recurso'].','.$recurso_catego[$i]['nome_recurso'].','. $periodos[$d].','.$periodos[$d+1].','.$periodos[$d+2].'">

            </label>
        </td>';
        }
        else{
            $recurs_dados .='X';
        }

        
    }
    $recurs_dados .= '</tr>';
}

$html = str_replace('{{Colunas}}',$coluna,$html);
$html = str_replace('{{Disponibilidades}}', $recurs_dados, $html);




echo $html;

?>




     
