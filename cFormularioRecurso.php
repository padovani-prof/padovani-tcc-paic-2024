<?php 

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login joge ele para logar
    header('Location: cLogin.php');
    exit();
}



include_once 'Model/mCategoriaRecurso.php';


$Lcategorias = carrega_categorias_recurso();
$html = file_get_contents('View/vNovoRecurso.php');


// carrega todos as categorias de recurso na teg option 

//<<<<<<< HEAD:cNovoRecurso.php
//=======

 
$categoria = ' ';

// se a pagina foi requerida pra receber dados e salvar
if (isset($_GET['nome']) and isset($_GET['descricao']) and isset($_GET['categoria'])) 
{
    // cadastrar recurso
    $nome = $_GET['nome'];
    $descre = $_GET['descricao'];
    $categoria = $_GET['categoria'];

    include_once 'Model/mRecurso.php';

    
    $resposta = cadastrar_recurso($nome, $descre, $categoria);

    $mensagens = ['Recurso cadastrado com Sucesso!!' , 'Numero maximo de caracter na descrição é 100', 'Categoria ínvalida',  'Nome do recurso ínvalido', 'Nome do recurso já cadastrado'];
    

    $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
    $retorno =  ($resposta>0)?'erro':'sucesso';
    $html = str_replace('{{retorno}}', $retorno, $html);
    
    if($resposta>0)
    {
        $html = str_replace('{{campoNome}}', $nome, $html);
        $html = str_replace('{{campoDescricao}}', $descre, $html);
        

        
    }
    else
    {
        $categoria = '1';
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


$catego ='';
foreach ($Lcategorias as $dados)
{

    $catego = $catego.'<option value="' .$dados['codigo'].'"' . ($dados['codigo'] == $categoria ? ' selected' : '') . '> '.$dados['nome'].'</option>';
  
}

$html = str_replace('{{categoriarecurso}}', $catego, $html);
echo $html;


?>
