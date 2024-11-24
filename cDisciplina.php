<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}


include_once 'Model/mDisciplina.php';
$html = file_get_contents('View/vDisciplina.php');

if (isset($_GET['codigoPrim']))
{
    $chave = $_GET['codigoPrim'];
    apagar_diciplina($chave);
    
}


$disciplina = carrega_disciplina();



// Substitui os recursos no template HTML
$disciplinas = '<tbody>';
foreach ($disciplina as $nome)
{
    $disciplinas = $disciplinas. '<tr>
        <td>'. $nome["nome"].'</td>
        <td>  Altera  </td>                                
        <td> 
            <form action="cDisciplina.php">
                <input type="hidden" name="codigoPrim" value="'.$nome['codigo']. '">
                <input type="submit" value="Apagar">
            </form> 
        </td>
    </tr>';

}



$disciplinas = $disciplinas. '<tbody/>';


$html = str_replace('{{disciplinas}}', $disciplinas, $html);
echo $html; // Exibe o HTML atualizado
?>
