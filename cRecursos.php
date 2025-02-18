<?php


include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_recurso', 2);


include_once 'Model/mRecurso.php';
//mb_internal_encoding("UTF-8");

$id_msg = '';
$msg = '';

if (isset($_GET['apagar']))
{
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_recurso', 2);


    $cod_recurso = $_GET['codigo_do_recurso'];
    $msg = apagar_recurso($cod_recurso);
    $id_msg = ($msg==0)?'sucesso':'erro';
    $lista_msg = ['Recurso apagado com Sucesso.', 'Esse recurso não pode ser apagado, pois possui Retirada.','Esse recurso não pode ser apagado, pois possui Ensalamento.','Esse recurso não pode ser apagado, pois possui Reserva.'];
    $msg = $lista_msg[$msg];
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

                <input class="btn btn-outline-secondary" type="submit" name="altera" value="Alterar">&nbsp;
                <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar" onclick="deseja_apagar()"> 

            </form> 
        </td>

        
        <td> <a href="cChecklist.php?codigo=' . $nome["codigo"] . ' "> Checklist</a> </td>
        <td> <a href="cPermissaoRecurso.php?codigo_recurso=' . $nome["codigo"] . ' ">Permissões</a> </td>
    </tr>';
    
}
$recursos = $recursos. '<tbody/>';



$html = file_get_contents('View/vRecursos.php');
$html = str_replace('{{repos}}', $id_msg, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{recursos}}', $recursos, $html); // Substitui cada recurso
echo $html; // Exibe o HTML atualizado

?>


