   

<?php


include_once 'Model/mVerificacao_acesso.php';

Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_categoria_rec', 2);


$id_resposta = '';
$resposta = '';
include_once 'Model/mCategoriaRecurso.php';
if (isset($_GET['apagar']))
{
   verificação_acesso($_SESSION['codigo_usuario'], 'apag_categoria_rec', 2);

    $codi = $_GET['codigo_da_categoria'];
    $resposta = apagar_categoria($codi);

    // $retorno =  ($resposta>0)?'erro':'sucesso';
    $id_resposta = ($resposta)?'sucesso':'erro';
    $resposta = ($resposta)?'A categoria foi removida com sucesso.':'Esta categoria não pode ser apagada, pois está vinculada a recursos.';
    
}
if(isset($_GET['alterar'])){
    $codi = $_GET['codigo_da_categoria'];
    header("Location: cFormularioCategoria.php?codigo=$codi");
    exit();
}

$categoria = carrega_categorias_recurso();
// Substitui os recursos no template HTML
$categorias = '';
foreach ($categoria as $nome) {
    $categorias = $categorias . '<tr>
        <td>' .mb_strtoupper($nome["nome"], "UTF-8") . '</td>                              
        <td> 
            <form action="cCategoria.php">   
                <input type="hidden" name="codigo_da_categoria" value="'.$nome['codigo'].'"> 
                <input class="btn btn-outline-secondary" type="submit" name="alterar" value="Alterar">&nbsp;
                <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar" onclick="deseja_apagar()"> 
            </form> 
        </td>

        
    </tr>';
}


$html = file_get_contents('View/vCategoria.php');
$html = str_replace('{{Categoria}}', $categorias, $html);
$html = str_replace('{{resposta}}', $id_resposta,$html);
$html = str_replace('{{msg}}',$resposta,$html);

echo $html; // Exibe o HTML atualizado

?>

