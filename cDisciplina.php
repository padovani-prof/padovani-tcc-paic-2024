<?php


function tabela_disciplina($disciplina){
    
    // Substitui os recursos no template HTML
    $disciplinas = '';
    foreach ($disciplina as $nome)
    {
        $disciplinas .= '<tr>
            <td>'. mb_strtoupper($nome["nome"]).'</td>                             
            <td> 
                <form action="cDisciplina.php">
                    <input type="hidden" name="codigoPrim" value="'.$nome['codigo']. '">
                    <input class="btn btn-outline-secondary" type="submit" value="Alterar" name="alterar">&nbsp;
                    <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar">
                </form> 
            </td>
        </tr>';

    }
    return $disciplinas;
}

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_disciplina', 2);


include_once 'Model/mDisciplina.php';
$html = file_get_contents('View/vDisciplina.php');

$msg = '';
$id_msg = 'nada';
if (isset($_GET['apagar']))
{
    
    $msg = apagar_diciplina($_GET['codigoPrim']);
    $id_msg = ($msg)?'sucesso':'erro';
    $msg = ($msg)?'Disciplina apagada com Sucesso':'Essa disciplina não pode ser apagada por esta sendo referênciada no Ensalamento.';

    // apagar ta ok
}else if(isset($_GET['alterar'])){
    $codigo = $_GET['codigoPrim'];
    header("Location: cFormularioDisciplina.php?codigo=$codigo");
    exit();
}


$disciplinas = carrega_disciplina();
$disciplinas = tabela_disciplina($disciplinas);





$html = str_replace('{{resp}}', $id_msg, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{disciplinas}}', $disciplinas, $html);
echo $html; // Exibe o HTML atualizado
?>
