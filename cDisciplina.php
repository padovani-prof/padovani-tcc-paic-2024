<?php

//fazer mano

include_once 'Model/mDisciplina.php';

$disciplina = carrega_disciplina();

// Substitui os recursos no template HTML
$disciplinas = '<tbody>';
foreach ($disciplina as $nome) {
    $disciplinas = $disciplinas. '<tr>
        <td>'. mb_strtoupper($nome["nome"]).'</td>
        <td>   </td>                                
        
        
        <td> <form action="cDisciplina.php">   
                            <input type="hidden" name="codigo_da_disciplina" value="' .$nome['codigo'].  '"> 
                            <input type="submit" name="apagar" value="Apagar">
                            </form> 
                    </td>

        <td>   </td>
    </tr>';

}
$disciplinas = $disciplinas. '<tbody/>';

$html = file_get_contents('View/vDisciplina.php');
$html = str_replace('{{disciplina}}', $disciplinas, $html);
echo $html; // Exibe o HTML atualizado