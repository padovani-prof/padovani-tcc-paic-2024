<?php



include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_periodo', 2);


include_once 'Model/mPeriodo.php';

$men = '';
$idmen = 'nada';

if(isset($_GET['apagar']))
{
    $chave = $_GET['codigo_do_periodo'];
    Existe_essa_chave_na_tabela($chave, "periodo", "cPeriodo.php");
    $men = apagar_periodo($chave);
    $idmen = ($men)?'sucesso':'erro';
    $men = ($men)?'Perido apagado com Sucesso!!':'Esse periodo não pode ser apagado pois está sendo referenciado na Disciplina.';


}
elseif(isset($_GET['atualizar'])){
    $codigo = $_GET['codigo_do_periodo'];
    header("Location: cFormularioPeriodo.php?codigo=$codigo");
    exit();
}

$periodo = carrega_periodo();

// Substitui os recursos no template HTML
$periodos = '<tbody>';
foreach ($periodo as $nome)
{
    $periodos = $periodos. '<tr>
        <td>'. $nome["nome"].'</td>                           
        <td> 
            <form action="cPeriodo.php">   
                <input type="hidden" name="codigo_do_periodo" value="' .$nome['codigo'].  '"> 
                <input type="submit" name="atualizar" value="Atualizar">
                <input type="submit" name="apagar" value="Apagar">
            </form> 
        </td>

    </tr>';
    
}
$periodos = $periodos. '<tbody/>';

$html = file_get_contents('View/vPeriodo.php');

$html = str_replace('{{mensagem}}', $men, $html); // mensagem de apagado
$html = str_replace('{{retorno}}', $idmen, $html); // id de estilização da mensagem

$html = str_replace('{{Categoria}}', $periodos, $html);
echo $html; // Exibe o HTML atualizado
?>
