<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}


include_once 'Model/mPerfilUsuario.php';  
$funcionalidades = listar_funcionalidade();

$nome = '';
$descricao = '';
$mensagem = '';
$funcionalidades_selecionadas = [];

// Verifica se o formulário foi enviado e captura as informações
if (isset($_GET["salvar"])) {

    include_once 'Model/mPerfilUsuario.php';

    $nome = $_GET['nome'];
    $descricao = $_GET['descricao'];

    if (isset($_GET['funcionalidades']) && is_array($_GET['funcionalidades'])) {
        $funcionalidades_selecionadas = $_GET['funcionalidades'];
    }

    if (empty($nome)) {
        $mensagem = "O nome é obrigatório!";
    } elseif (empty($descricao)) {
        $mensagem = "A descrição é obrigatória!";
    } elseif (empty($funcionalidades_selecionadas)) {
        $mensagem = "Você deve selecionar pelo menos uma funcionalidade!";

    } else {
        $resposta = insere_perfil($nome, $descricao);

        if ($resposta == 2) {
            $nome = '';
            $descricao = '';
            $funcionalidades_selecionadas = []; 
            $mensagem = "Perfil cadastrado com sucesso!";
        } else {
            $mensagens = ['Nome Inválido', 'Descrição Inválida'];
            $mensagem = $mensagens[$resposta];
        }
    }
}

if (is_array($funcionalidades)) {
    $aux = ""; 
    foreach ($funcionalidades as $linha) {
        $checked = in_array($linha['codigo'], $funcionalidades_selecionadas) ? "checked" : "";
        $aux .= "<input type='checkbox' name='funcionalidades[]' value='" . $linha['codigo'] . "' $checked> " . $linha['nome'] . "<br>";
    }
} else {
    $aux .= "<input type='checkbox' name='funcionalidades'> Não há nenhuma funcionalidade cadastrada <br>";
}


$html = file_get_contents('View/vFormularioPerfil.php');
$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoDescricao}}', $descricao, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);  
$html = str_replace('{{funcionalidades}}', $aux, $html);
echo $html;
?>
