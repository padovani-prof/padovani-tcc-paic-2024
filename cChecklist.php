<?php 

function cerrega_dados_checklist($lista, $codigo){
    $dados = '';
    foreach($lista as $linha){
        $dados .='<tr>';
        $dados .= '<td>  '.mb_strtoupper($linha['item']).'</td>';
        $dados .='<td> <form action="cChecklist.php">   
                        <input type="hidden" name="codigo_item" value="' .$linha['codigo'].  '"> 
                        <input type="hidden" name="codigo" value="' .$codigo.  '"> 
                        <input class="btn btn-outline-danger" type="submit" value="Apagar" name="apagar"></form> 
                </td>';
        $dados .='</tr>';

    }
    return $dados;
        
}


include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'adm_checklist_rec', 2);
include_once 'Model/mChecklist.php';
$item = '';
$msg = (isset($_GET['msg']))?$_GET['msg']: '';
$id_msg = (isset($_GET['msgId']))?$_GET['msgId']:'danger';
$codigo = $_GET ['codigo'];
Existe_essa_chave_na_tabela($codigo, 'recurso', "cRecursos.php");
if (isset($_GET['apagar']))
{
    // apagar dado do check list
    $chave_pri = $_GET['codigo_item'];
    $resposta = apagar_acessorio_ao_recurso($chave_pri);
    $msg = ($resposta)?'Item removido do recurso com Sucesso.': 'Esse item não pode ser apagado, pois possui Retirada.';
    $id_msg = ($resposta)?'success':'danger';
    header("Location: cChecklist.php?msg=$msg&codigo=$codigo&msgId=$id_msg");
    exit();

}

else if(isset($_GET['adicionar'])){
    $item = $_GET['txtitem'];
    if(strlen($_GET['txtitem'])>=3){
        //adiciona no banco
        salva_no_banco($item, $codigo);
        $msg = 'Novo item Adicionado ao Recurso com Sucesso.';
        $id_msg = 'success';
        header("Location: cChecklist.php?msg=$msg&codigo=$codigo&msgId=$id_msg");
        exit();
    }else{
        // erro
        $msg = 'O nome do Item precisa ter no minimo 3 caracter.';
        
    }
    
}

$recurso_nome = carrega_recurso($codigo);
$lista = carrega_dados($codigo);
$dados_checlist = cerrega_dados_checklist($lista, $codigo);


$html = file_get_contents('View/vChecklist.php');
$html = str_replace('{{nomerecurso}}', $recurso_nome, $html);
$html = str_replace('{{codigo}}', $codigo, $html);
$html = str_replace('{{itens}}',$dados_checlist, $html);
$html = str_replace('{{respe}}',$id_msg, $html);
$html = str_replace('{{msg}}',$msg, $html);
$html = str_replace('{{nome}}',$item, $html);
$html = cabecalho($html, 'Checklist do Recurso');
echo $html;






?>
