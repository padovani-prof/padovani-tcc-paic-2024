<?php

include_once 'Model/mCategoria.php';

$Lcategorias = carrega_categorias_recurso();
$html = file_get_contents('View/vNovaCategoria.php');



// se a pagina foi requerida pra receber dados e salvar
if (isset($_GET['nome']) and isset($_GET['descricao']) and isset($_GET['ambiente_fisico'])) 
{
    // cadastrar recurso
    $nome = $_GET['nome'];
    $descre = $_GET['descricao'];
    $Ambiente = $_GET['ambiente_fisico'];

    
    $resposta = cadastrar_categoria($nome, $descre, $ambiente);

    $mensagens = ['Nome da categoria ínvalido', 'Numero maximo de caracter na descrição é 100', 'categoria cadastrado com Sucesso!!'];
    

    $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
    $html = str_replace('{{retorno}}', $retorno, $html);
    if($resposta < 3)
    {
        $html = str_replace('{{campoNome}}', $nome, $html);
        $html = str_replace('{{campoDescricao}}', $descre, $html);
        $retorno =  ($resposta<3)?'erro':'sucesso';



        
        
    }
    else
    {
        $html = str_replace('{{campoNome}}','',$html);
        $html = str_replace('{{campoDescricao}}','', $html);
        $html = str_replace('{{retorno}}', '', $html);
    }

    

}

// A pagina for requerida pela primeira vez vai entra no else
else
{
    // subistiti coloca essas dados no html e mostra
    $html = str_replace('{{campoNome}}','',$html);
    $html = str_replace('{{campoDescricao}}','', $html);
    $html = str_replace('{{retorno}}', '', $html);
    $html = str_replace('{{mensagem}}','', $html);
    

}



?>

