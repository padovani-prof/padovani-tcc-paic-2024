<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login joge ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'list_recurso');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}

include_once 'Model/mRecurso.php';
//mb_internal_encoding("UTF-8");

$id_msg = '';
$msg = '';

if (isset($_GET['apagar']))
{
    $cod_recurso = $_GET['codigo_do_recurso'];
    $msg = apagar_recurso($cod_recurso);
    $id_msg = ($msg)?'sucesso':'erro';
    $msg = ($msg)?'Recurso apagado com Sucesso.':'Esse recurso não pode ser apagado por esta sendo ultilizados por outros serviços dentro do sistema.';
}elseif(isset($_GET['altera'])){
    $cod_recurso = $_GET['codigo_do_recurso'];
    header("Location: cFormularioRecurso.php?codigo=$cod_recurso");
    exit();
}



$recurso = Carregar_recursos();
// Substitui os recursos no template HTML
$recursos = '<tbody>';
foreach ($recurso as $nome) {
    $recursos = $recursos. '<tr>
        <td>'.mb_strtoupper($nome["nome"]).'</td>
        <td> <form action="cRecursos.php"> 
                <input type="hidden" name="codigo_do_recurso" value="' .$nome['codigo'].'"> 
                <input type="submit" name="altera" value="Alterar"> 
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
$html = str_replace('{{repos}}', $id_msg, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{recursos}}', $recursos, $html); // Substitui cada recurso
echo $html; // Exibe o HTML atualizado

?>




