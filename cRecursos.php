<?php

include_once 'Model/mRecurso.php';
mb_internal_encoding("UTF-8");


if (isset($_GET['codigo_do_recurso']))
{
   
    $cod_recurso = $_GET['codigo_do_recurso'];
    apagar_recurso($cod_recurso);
}


$recurso = Carregar_recursos();

// Substitui os recursos no template HTML
$recursos = '<tbody>';
foreach ($recurso as $nome) {
    $recursos = $recursos. '<tr>
        <td>'. mb_strtoupper($nome["nome"]).'</td>
        <td>   </td>                                
        
        
        <td> <form action="cRecursos.php">   
                            <input type="hidden" name="codigo_do_recurso" value="' .$nome['codigo'].  '"> 
                            <input type="submit" name="apagar" value="Apagar">
                            </form> 
                    </td>

        <td>   </td>
        <td> <a href="cChecklist.php?codigo=' . $nome["codigo"] . ' "> Checklist</a> </td>
        <td> <a href="cPermissaoRecurso.php?codigo=' . $nome["codigo"] . ' ">Permissões</a> </td>
    </tr>';
    
}
$recursos = $recursos. '<tbody/>';

$html = file_get_contents('View/vRecursos.php');
$html = str_replace('{{recursos}}', $recursos, $html); // Substitui cada recurso
echo $html; // Exibe o HTML atualizado

?>



