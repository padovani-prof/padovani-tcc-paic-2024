<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'list_categoria_rec');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}


include_once 'Model/mCategoriaRecurso.php';
if (isset($_GET['apagar']))
{
    $codi = $_GET['codigo_da_categoria'];
    apagar_categoria($codi);
}

$categoria = carrega_categorias_recurso();
// Substitui os recursos no template HTML
$categorias = '<tbody>';
foreach ($categoria as $nome) {
    $categorias = $categorias . '<tr>
        <td>' .$nome["nome"] . '</td>
        <td> #Alterar  </td>                                
        <td> 
            <form action="cCategoria.php">   
                <input type="hidden" name="codigo_da_categoria" value="' . $nome['codigo'] . '"> 
                <input type="submit" name="apagar" value="Apagar">
            </form> 
        </td>

        <td>   </td>
    </tr>';
}
$categorias = $categorias . '<tbody/>';

$html = file_get_contents('View/vCategoria.php');
$html = str_replace('{{Categoria}}', $categorias, $html);
echo $html; // Exibe o HTML atualizado

?>
