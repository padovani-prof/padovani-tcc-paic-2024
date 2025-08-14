<?php 

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
include_once 'Model/mCategoriaRecurso.php';
include_once 'Model/mRecurso.php';

$Lcategorias = carrega_categorias_recurso();
$html = file_get_contents('View/vNovoRecurso.php');
$html = cabecalho($html, 'Recurso');

$categoria = '';
$tipo_tela = '';

if(isset($_GET['codigo']) or isset($_GET['cod'])){
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_recurso', 2);
    
    // Atualização de recurso
    if(isset($_GET['codigo'])){
        $chave = $_GET['codigo'];
        $dados = mandar_dados($chave);
        $html = str_replace('{{campoNome}}',$dados['nome'],$html);
        $html = str_replace('{{campoDescricao}}',$dados['descricao'], $html);
        $tipo_tela = '<input type="hidden" name="cod" value="'.$_GET['codigo'].'">';
        $categoria = $dados['codigo_categoria'];
        $html = str_replace('{{retorno}}', '', $html);
        $html = str_replace('{{mensagem}}','', $html);

        if(!Existe_esse_recurso($chave)){
            header("Location: cRecursos.php");
            exit();
        }

    } else {
        // Salvar atualização
        $chave = $_GET['cod'];
        if(!Existe_esse_recurso($chave)){
            header("Location: cRecursos.php");
            exit();
        }
        $nome = trim($_GET['nome']);
        $descre = trim($_GET['descricao']);
        $categoria = $_GET['categoria'];
        $resposta = verificar_atualizar($chave, $nome, $descre, $categoria);

        $mensagens = [
            'Recurso atualizado com sucesso.',
            'A descrição deve conter entre 5 e 100 caracteres.',
            'Por favor, selecione uma categoria válida.',
            'O nome do recurso informado é inválido.',
            'Nome do recurso já existente. Insira um novo nome.'
        ];

        $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
        $retorno = ($resposta > 0) ? 'danger' : 'success';
        $html = str_replace('{{retorno}}', $retorno, $html);

        if($resposta > 0){
            $html = str_replace('{{campoNome}}', $nome, $html);
            $html = str_replace('{{campoDescricao}}', $descre, $html);
            $tipo_tela = '<input type="hidden" name="cod" value="'.$chave.'">';
        } else {
            $categoria = '';
            $html = str_replace('{{campoNome}}','',$html);
            $html = str_replace('{{campoDescricao}}','', $html);
            $html = str_replace('{{retorno}}', '', $html);
        }
    }
} elseif (isset($_GET['salvar'])) {
    // Cadastrar recurso
    $nome = trim($_GET['nome']);
    $descre = trim($_GET['descricao']);
    $categoria = $_GET['categoria'];

    $resposta = cadastrar_recurso($nome, $descre, $categoria);

    $mensagens = [
        'Recurso cadastrado com sucesso.',
        'A descrição deve conter entre 5 e 100 caracteres.',
        'Por favor, selecione uma categoria válida.',
        'O nome do recurso informado é inválido.',
        'Nome do recurso já existente. Insira um novo nome.'
    ];

    $html = str_replace('{{mensagem}}', $mensagens[$resposta], $html);
    $retorno = ($resposta > 0) ? 'danger' : 'success';
    $html = str_replace('{{retorno}}', $retorno, $html);

    if($resposta > 0){
        $html = str_replace('{{campoNome}}', $nome, $html);
        $html = str_replace('{{campoDescricao}}', $descre, $html);
    } else {
        $categoria = '';
        $html = str_replace('{{campoNome}}','',$html);
        $html = str_replace('{{campoDescricao}}','', $html);
        $html = str_replace('{{retorno}}', '', $html);
    }
} else {
    $verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_recurso', 2);
    $html = str_replace('{{campoNome}}','',$html);
    $html = str_replace('{{campoDescricao}}','', $html);
    $html = str_replace('{{retorno}}', '', $html);
    $html = str_replace('{{mensagem}}','', $html);
}

$catego = mandar_options($Lcategorias,  $categoria);
$html = str_replace('{{tipo_tela}}', $tipo_tela, $html);
$html = str_replace('{{categoriarecurso}}', $catego, $html);

echo $html;

?>
