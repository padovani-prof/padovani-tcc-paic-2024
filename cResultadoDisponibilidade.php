<?php 



function chaves($lista)
{
    $lista_chaves = array();

    for ($i=0; $i < count($lista); $i++) 
    { 
        $chave = explode(',', $lista[$i]);
        $lista_chaves[] = $chave[0];
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

    $inpu.="<input type='hidden' name='$name' value='". urlencode(json_encode($lista))."'>";
    
    return $inpu;
    

}


include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);


include_once 'Model/mDisponibilidade.php';
$html = file_get_contents('View/vResultadoDisponibilidade.php');


$msg = '';



$categorias = isset($_GET['categorias'])?json_decode(urldecode($_GET['categorias'])):[];
$recursos = isset($_GET['recursos'])?json_decode(urldecode($_GET['recursos'])):[];
$periodos = isset($_GET['periodos'])?json_decode(urldecode($_GET['periodos'])):[];


if(isset($_GET['reserva']) and isset($_GET['marcas'])){
    $dados = $_GET['marcas'];

    header("Location: cReservaConjunta.php?marcas=".urlencode(json_encode($dados)));
    exit();
    
}elseif (isset($_GET['reserva'])) {

    $msg = 'Por favor selecione algum recurso disponivel.';
}
$hid_recu = mandar_hindem($recursos, 'recursos');
$hid_cate = mandar_hindem($periodos, 'periodos');
$hid_peri = mandar_hindem($categorias, 'categorias');
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
$qdt = count($recurso_catego);
$qdt_ind = 0;
for ($i=0; $i < $qdt; $i++) 
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
            $qdt_ind ++;
            if($qdt == $qdt_ind){
                $msg = (($qdt > 1)?'Todos os recursos já estão reservados para '.((count($periodos)>3)?'esses períodos.':'esse período.'):'O recurso já está reservado para  '.((count($periodos)>3)?'esses períodos.':'esse período.')).' Por favor, volte e selecione outros períodos ou recursos.';
            }
        }

        
    }
    $recurs_dados .= '</tr>';
}
$html = str_replace('{{msg}}',$msg, $html);
$html = str_replace('{{cate}}',$hid_cate, $html);
$html = str_replace('{{recu}}', $hid_recu, $html);
$html = str_replace('{{periodo}}',$hid_peri, $html);
$html = str_replace('{{Colunas}}',$coluna,$html);
$html = str_replace('{{Disponibilidades}}', $recurs_dados, $html);
echo $html;

?>




     
