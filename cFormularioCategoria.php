<?php
include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
include_once 'Model/mCategoria.php';
$html = file_get_contents('View/vFormularioCategoria.php');
$nome = '';
$descre = '';
$retorno = (isset($_GET['id_msg']))?$_GET['id_msg']:'';
$mensagem = (isset($_GET['msg']))?$_GET['msg']:'';
$Ambiente = '';
$tipo = '';


if(isset($_GET["codigo"])){
    // preechendo dados
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_categoria_rec', 2);
    $chave = $_GET["codigo"];
    Verificar_codigo('categoria_recurso', $chave);
    $dados = pegar_dados($chave);
    $nome = $dados['nome'];
    $descre = $dados['descricao'];
    $Ambiente = ($dados['ambiente_fisico']=='S')?'on':'';
    $tipo = '<input type="hidden" name="chave" value="'.$chave.'">';
    
}elseif(isset($_GET['chave'])){
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_categoria_rec', 2);
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
            $mensagem = ['Categoria Atualizada com Sucesso!!', 'A descrição deve conter pelo menos 3 e no máximo 100 caracteres','Nome da Categoria ínvalida', 'Nome existente. Insira um novo' ];
    
            // respostas
            $mensagem = $mensagem[$resposta];
            $retorno =  ($resposta>0)?'danger':'success';
    
            if($resposta == 0)
            {
                // salvo com sucesso
                header("Location: cFormularioCategoria.php?chave=$chave&msg=$mensagem&id_msg=$retorno");
                exit();
    
                
            }
        }
    }

}

else{
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_categoria_rec', 2);
    if (isset($_GET['salvar']))
    {
        if (isset($_GET['nome']) and isset($_GET['descricao'])) 
        {
            // cadastrar recurso
            $nome = $_GET['nome'];
            $descre = $_GET['descricao'];
            $Ambiente = (isset($_GET['ambiente_fisico']))?$_GET['ambiente_fisico']:null;
            $resposta = insere_categoria_recurso($nome, $descre, $Ambiente);
            $mensagem = ['Categoria Cadastrada com Sucesso!!', 'Numero maximo de caracter na descrição é 100','Nome da Categoria ínvalida', 'Nome existente. Insira um novo.' ];
    
            // respostas
            $mensagem = $mensagem[$resposta];
            $retorno =  ($resposta>0)?'danger':'success';
    
            if($resposta == 0)
            {
                // salvo com sucesso
                $nome = '';
                $descre = '';
                $Ambiente = '';
                header("Location: cFormularioCategoria.php?msg=$mensagem&id_msg=$retorno");
                exit(); 
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
