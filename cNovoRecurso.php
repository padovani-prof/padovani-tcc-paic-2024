

<?php 


include_once 'Model/mCategoriaRecurso.php';


$Lcategorias = carrega_categorias_recurso();
$html = file_get_contents('View/vNovoRecurso.php');


// carrega todos as categorias de recurso na teg option 
$catego = '';
foreach ($Lcategorias as $dados)
{
    if ($dados['codigo'] == '2'){
        $catego = $catego."<option value='".$dados['codigo']."' selected>".$dados['nome']."</opton>";    
    }else{
        $catego = $catego."<option value='".$dados['codigo']."' >".$dados['nome']."</opton>";
    }
    
}




// se a pagina foi requerida pra receber dados e salvar
if (isset($_GET['nome']) and isset($_GET['descricao']) and isset($_GET['categoria'])) 
{
    // cadastrar recurso
    $nome = $_GET['nome'];
    $descre = $_GET['descricao'];
    $categoria = $_GET['categoria'];

    include_once 'Model/mRecurso.php';

    
    $resposta = cadastrar_recurso($nome, $descre, $categoria);

    $mensagens = ['Nome do recurso ínvalido', 'Numero maximo de caracter na descrição é 100', 'Categoria ínvalida', 'Recurso cadastrado com Sucesso!!'];
    
    $retorno =  ($resposta<3)?'erro':'sucesso';

    $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
    $html = str_replace('{{retorno}}', $retorno, $html);

}

// A pagina for requerida pela primeira vez vai entra no else
else
{
    // subistiti coloca essas dados no html e mostra
    $html = str_replace('{{mensagem}}','',$html);
    

}

$html = str_replace('{{categoriarecurso}}', $catego, $html);
echo $html;








?>

