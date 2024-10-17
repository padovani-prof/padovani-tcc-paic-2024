<?php 


mb_internal_encoding("UTF-8");


include_once 'Model/mChecklist.php';


if (isset($_GET['apagar']))
{
    $chave_pri = $_GET['codigo_item'];

    apagar_acesso_ao_recurso($chave_pri);

}

if(isset($_GET['adicionar']) and isset($_GET['txtitem']) and strlen($_GET['txtitem'])>=3)
{
    //adiciona no banco
    $item = $_GET['txtitem'];
    $codigo = $_GET['codigo'];
    
    salva_no_banco($item, $codigo);

    header('Location: cChecklist.php?codigo='.$codigo);

}



$codigo = $_GET ['codigo'];
$recurso = carrega_recurso($codigo);
$dados_checlist = cerrega_dados_checklist($codigo);

if (count($dados_checlist) == 0)
{
    $dados = 'Não há nenhum item adicionado no momento';
}
else
{

    $dados = '<tbody>';

    foreach($dados_checlist as $linha)
    {
        $dados = $dados.'<tr>';
        $dados = $dados. '<td>  '.mb_strtoupper($linha['item']).'</td>';
        $dados = $dados. '<td> <form action="cChecklist.php">   
                                    <input type="hidden" name="codigo_item" value="' .$linha['codigo'].  '"> 
                                    <input type="hidden" name="codigo" value="'  .$codigo.  '"> 
                                    <input type="submit" name="apagar" value="Apagar">
                                    </form> 
                            </td>'; // coluna de ação para apagar
        $dados = $dados.'</tr>';
    }

    $dados = $dados. '<tbody/>';
}






$html = file_get_contents('View/vChecklist.php');

$html = str_replace('{{nomerecurso}}', $recurso['nome'], $html);

$html = str_replace('{{codigo}}', $codigo, $html);

$html = str_replace('{{itens}}',$dados, $html);


echo $html;






?>




