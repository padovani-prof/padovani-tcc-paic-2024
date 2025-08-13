<?php

function  Carregar_recursos_htm(){
    $dados = Carregar_recursos_dados();
    $recursos = '';
    // Substitui os recursos no template HTML
   foreach($dados as $nome)
    {
        // onclick="deseja_apagar()">  chama a função de java escripit
        $recursos .= '<tr>
        <td>'.$nome['nome'].'</td>
        <td>
        <div class="d-flex gap-2 align-items-center">
        <a href="cChecklist.php?codigo='. $nome["codigo"].'" class="btn btn-outline-success">Ver Check List</a>
        <a href="cPermissaoRecurso.php?codigo_recurso='.$nome["codigo"] .'" class="btn btn-outline-primary">Ver Permissões</a>

        <a href="cHistoricoRetiradaRecurso.php?codigo_recurso='.$nome["codigo"] .'" class="btn btn-outline-primary">HISTORICO</a>
        <form action="cRecursos.php" class="d-flex gap-2">
            <input type="hidden" name="codigo_do_recurso" value="'. $nome['codigo'] .'"> 
            <input class="btn btn-outline-secondary" name="altera" type="submit" value="Alterar">
            <input class="btn btn-outline-danger" name="apagar" type="submit" value="Apagar" onclick="return deseja_apagar()">
        </form>
    </div>
    </td> 
        
        
        
    </tr>';
       
    }
    return $recursos;

}


include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_recurso', 2);
include_once 'Model/mRecurso.php';

$id_msg = '';
$msg = '';

if (isset($_GET['apagar']))
{
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_recurso', 2);
    $cod_recurso = $_GET['codigo_do_recurso'];
    $msg = apagar_recurso($cod_recurso);
    $id_msg = ($msg==0)?'success':'danger';
    $lista_msg = ['Recurso Apagado com Sucesso.', 'Esse recurso não pode ser apagado, pois possui Retirada.','Esse Recurso não pode ser Apagado, pois possui Ensalamento.','Esse Recurso não pode ser Apagado, pois possui Reserva.'];
    $msg = $lista_msg[$msg];
}elseif(isset($_GET['altera'])){
    $cod_recurso = $_GET['codigo_do_recurso'];
    header("Location: cFormularioRecurso.php?codigo=$cod_recurso");
    exit();
}
$recursos = Carregar_recursos_htm();




$html = file_get_contents('View/vRecursos.php');
$html = cabecalho($html, 'Recursos');
$html = str_replace('{{repos}}', $id_msg, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{recursos}}', $recursos, $html); // Substitui cada recurso
echo $html; // Exibe o HTML atualizado

?>

