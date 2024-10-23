<?php

include_once 'Model/mCategoria.php';


$html = file_get_contents('View/vFormularioCategoria.php');

$nome = '';
$descre = '';
$retorno = '';
$mensagem = '';
$Ambiente = '';

//<<<<<<< HEAD

//=======
//>>>>>>> 9d066070f353532c9af116dbb8919897e112bc8c

// se a pagina foi requerida pra receber dados e salvar

if (isset($_GET['salvar']))
{
    if (isset($_GET['nome']) and isset($_GET['descricao'])) 
    {
        // cadastrar recurso
        $nome = $_GET['nome'];
        $descre = $_GET['descricao'];
        $Ambiente = (isset($_GET['ambiente_fisico']))?$_GET['ambiente_fisico']:null;
        $resposta = insere_categoria_recurso($nome, $descre, $Ambiente);
        $mensagem = ['Nome da categoria ínvalido', 'Numero maximo de caracter na descrição é 100', 'Categoria cadastrada com Sucesso!!'];

        // respostas
        $mensagem = $mensagem[$resposta];
        $retorno =  ($resposta<2)?'erro':'sucesso';


        
        if($resposta == 2)
        {
            // salvo com sucesso
            $nome = '';
            $descre = '';
            $Ambiente = '';

            
        }
    }

}
// subistiti coloca essas dados no html e mostra
$html = str_replace('{{Camponome}}',$nome,$html);
$html = str_replace('{{Campodescricao}}',$descre, $html);
$html = str_replace('{{Campoambiente}}', ($Ambiente==='on')?'checked':'', $html); 
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = str_replace('{{resposta}}', $retorno, $html);
    


echo $html;

?>