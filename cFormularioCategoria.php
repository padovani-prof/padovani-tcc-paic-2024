<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
# verificação de acesso da fucionalidade
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_categoria_rec');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}



include_once 'Model/mCategoria.php';


$html = file_get_contents('View/vFormularioCategoria.php');

$nome = '';
$descre = '';
$retorno = '';
$mensagem = '';
$Ambiente = '';

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
        $mensagem = ['Categoria cadastrada com Sucesso!!', 'Numero maximo de caracter na descrição é 100','Nome da categoria ínvalido', 'Categoria de recurso já cadastrado' ];

        // respostas
        $mensagem = $mensagem[$resposta];
        $retorno =  ($resposta>0)?'erro':'sucesso';

        if($resposta == 0)
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
