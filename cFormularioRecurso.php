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
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_recurso');
if ($verificar== false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}


include_once 'Model/mCategoriaRecurso.php';
include_once 'Model/mRecurso.php';
$Lcategorias = carrega_categorias_recurso();
$html = file_get_contents('View/vNovoRecurso.php');


$categoria = '';
$tipo_tela = '';


if(isset($_GET['codigo']) or isset($_GET['cod'])){
    // vai atualizar recursos
    // preexer com o que ja esta salvo
    if(isset($_GET['codigo'])){
        $dados = mandar_dados($_GET['codigo']);
        $html = str_replace('{{campoNome}}',$dados['nome'],$html);
        $html = str_replace('{{campoDescricao}}',$dados['descricao'], $html);
        $tipo_tela = '<input type="hidden" name="cod" value="'.$_GET['codigo'].'">';
        $categoria = $dados['codigo_categoria'];
        $html = str_replace('{{retorno}}', '', $html);
        $html = str_replace('{{mensagem}}','', $html);



    }else{
        // salvar atualização
        $chave = $_GET['cod'];
        $nome = $_GET['nome'];
        $descre = $_GET['descricao'];
        $categoria = $_GET['categoria'];
        $resposta = verificar_atualizar($chave, $nome, $descre, $categoria);

        $mensagens = ['Recurso atualizado com Sucesso!!' , 'Numero maximo de caracter na descrição é 100', 'Categoria ínvalida',  'Nome do recurso ínvalido', 'Nome do recurso já cadastrado'];
        $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
        $retorno =  ($resposta>0)?'erro':'sucesso';
        $html = str_replace('{{retorno}}', $retorno, $html);
        
        if($resposta>0)
        {
            $html = str_replace('{{campoNome}}', $nome, $html);
            $html = str_replace('{{campoDescricao}}', $descre, $html);
            $tipo_tela = '<input type="hidden" name="cod" value="'.$chave.'">';
            
        }
        else
        {
            $categoria = '';
            $html = str_replace('{{campoNome}}','',$html);
            $html = str_replace('{{campoDescricao}}','', $html);
            $html = str_replace('{{retorno}}', '', $html);
        }

    }
    
    


}

elseif (isset($_GET['salvar'])) 
{
    // cadastrar recurso
    $nome = $_GET['nome'];
    $descre = $_GET['descricao'];
    $categoria = $_GET['categoria'];

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
        $categoria = '';
        $html = str_replace('{{campoNome}}','',$html);
        $html = str_replace('{{campoDescricao}}','', $html);
        $html = str_replace('{{retorno}}', '', $html);
    }
}
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

$html = str_replace('{{tipo_tela}}', $tipo_tela, $html);
$html = str_replace('{{categoriarecurso}}', $catego, $html);
echo $html;


?>

