<?php 


include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'adm_checklist_rec', 2);


include_once 'Model/mChecklist.php';

$item = '';
$msg = '';
$id_msg = 'erro';
$codigo = $_GET ['codigo'];
Existe_essa_chave_na_tabela($codigo, 'recurso', "cRecursos.php");

if (isset($_GET['apagar']))
{
    // apagar dado do check list
    $chave_pri = $_GET['codigo_item'];
    Existe_essa_chave_na_tabela($chave_pri, 'checklist', "cChecklist.php?codigo=$codigo");
    apagar_acesso_ao_recurso($chave_pri);
    $msg = 'Item removido do recurso com Sucesso.';
    $id_msg = 'sucesso';

}

else if(isset($_GET['adicionar'])){
    $item = $_GET['txtitem'];
    if(strlen($_GET['txtitem'])>=3){
        //adiciona no banco
        salva_no_banco($item, $codigo);
        $msg = 'Novo item adicionado ao recurso com Sucesso.';
        $id_msg = 'sucesso';
        $item = '';
    }else{
        // erro
        $msg = 'O nome do item precisa ter no minimo 3 caracter.';
        
    }
    
}

$recurso_nome = carrega_recurso($codigo);
$dados_checlist = cerrega_dados_checklist($codigo);


$html = file_get_contents('View/vChecklist.php');
$html = str_replace('{{nomerecurso}}', $recurso_nome, $html);
$html = str_replace('{{codigo}}', $codigo, $html);
$html = str_replace('{{itens}}',$dados_checlist, $html);
$html = str_replace('{{respe}}',$id_msg, $html);
$html = str_replace('{{msg}}',$msg, $html);
$html = str_replace('{{item}}',$item, $html);

echo $html;






?>

