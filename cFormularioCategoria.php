<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
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
$tipo = '';


if(isset($_GET["codigo"])){
    // preechendo dados
    $chave = $_GET["codigo"];
    $dados = pegar_dados($chave);
    $nome = $dados['nome'];
    $descre = $dados['descricao'];
    $Ambiente = ($dados['ambiente_fisico']=='S')?'on':'';
    $tipo = '<input type="hidden" name="chave" value="'.$chave.'">';
    
}elseif(isset($_GET['chave'])){
    $chave = $_GET['chave'];
    $tipo = '<input type="hidden" name="chave" value="'.$chave.'">';
    
    if (isset($_GET['salvar']))
    {
        if (isset($_GET['nome']) and isset($_GET['descricao'])) 
        {
            // atualizar dados
            $nome = $_GET['nome'];
            $descre = $_GET['descricao'];
            $Ambiente = (isset($_GET['ambiente_fisico']))?$_GET['ambiente_fisico']:null;
            $resposta = atualizar_dados($chave, $nome, $descre, $Ambiente);
            $mensagem = ['Categoria atualizado com Sucesso!!', 'Numero maximo de caracter na descrição é 100','Nome da categoria ínvalido', 'Categoria de recurso já ultilizado' ];
    
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

}

else{
    $html = str_replace('{{tipo}}','cadastrar',$html);
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
   
 

}
 // subistiti coloca essas dados no html e mostra
 $html = str_replace('{{tipo_tela}}', $tipo, $html);
 $html = str_replace('{{Camponome}}',$nome,$html);
 $html = str_replace('{{Campodescricao}}',$descre, $html);
 $html = str_replace('{{Campoambiente}}', ($Ambiente==='on')?'checked':'', $html); 
 $html = str_replace('{{mensagem}}', $mensagem, $html);
 $html = str_replace('{{resposta}}', $retorno, $html);


echo $html;


?>
