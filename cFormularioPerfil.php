<?php
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

    // Verifica se há funcionalidades selecionadas
    if (isset($_GET['funcionalidades']) && is_array($_GET['funcionalidades'])) {
        $funcionalidades_selecionadas = $_GET['funcionalidades'];
    }

    // Verifica se o nome, descrição e funcionalidades foram fornecidos
    if (empty($nome)) {
        $mensagem = "O nome é obrigatório!";
    } elseif (empty($descricao)) {
        $mensagem = "A descrição é obrigatória!";
    } elseif (empty($funcionalidades_selecionadas)) {
        $mensagem = "Você deve selecionar pelo menos uma funcionalidade!";

    } else {
        // Se todos os campos forem válidos, tente inserir o perfil
        $resposta = insere_perfil($nome, $descricao);

        if ($resposta == 2) {
            $nome = '';
            $descricao = '';
            $funcionalidades_selecionadas = []; // Limpa a seleção se o perfil foi cadastrado com sucesso
            $mensagem = "Perfil cadastrado com sucesso!";
        } else {
            $mensagens = ['Nome Inválido', 'Descrição Inválida'];
            $mensagem = $mensagens[$resposta];
        }
    }
}

// Inicializa a variável que conterá o HTML dos checkboxes

if (is_array($funcionalidades)) {
    $aux = ""; 
    foreach ($funcionalidades as $linha) {
        // Verifica se a funcionalidade está na lista de selecionadas
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
