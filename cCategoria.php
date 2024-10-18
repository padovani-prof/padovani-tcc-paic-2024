<?php

include_once 'Model/mCategoria.php';

$categoria = carrega_categorias_recurso();

// Substitui os recursos no template HTML
$categorias = '<tbody>';
foreach ($categoria as $nome) {
    $categorias = $categorias. '<tr>
        <td>'. mb_strtoupper($nome["nome"]).'</td>
        <td>   </td>                                
        
        
        <td> <form action="cCategoria.php">   
                            <input type="hidden" name="codigo_da_categoria" value="' .$nome['codigo'].  '"> 
                            <input type="submit" name="apagar" value="Apagar">
                            </form> 
                    </td>

        <td>   </td>
    </tr>';
    
}
$categorias = $categorias. '<tbody/>';

$html = file_get_contents('View/vCategoria.php');
$html = str_replace('{{categoria}}', $categorias, $html);
echo $html; // Exibe o HTML atualizado