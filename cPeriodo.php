<?php

//fazer mano

include_once 'Model/mPeriodo.php';

$periodo = carrega_periodo();

// Substitui os recursos no template HTML
$periodos = '<tbody>';
foreach ($periodo as $nome) {
    $periodos = $periodos. '<tr>
        <td>'. mb_strtoupper($nome["nome"]).'</td>
        <td>   </td>                                
        
        
        <td> <form action="cPeriodo.php">   
                            <input type="hidden" name="codigo_do_periodo" value="' .$nome['codigo'].  '"> 
                            <input type="submit" name="apagar" value="Apagar">
                            </form> 
                    </td>

        <td>   </td>
    </tr>';
    
}
$periodos = $periodos. '<tbody/>';

$html = file_get_contents('View/vPeriodo.php');
$html = str_replace('{{periodo}}', $periodos, $html);
echo $html; // Exibe o HTML atualizado