<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}


include_once 'Model/mUsuario.php';  
$perfil = listar_perfil();

$nome = '';
$email = '';
$senha = '';
$conf_senha = '';
$perfis_selecionados = [];
$mensagem = '';


$html = file_get_contents('View/vFormularioUsuario.php'); 

if (isset($_GET["salvar"])) {
    include_once 'Model/mUsuario.php';

    $nome = $_GET['nome'];
    $email = $_GET['email'];
    $senha = $_GET['senha'];
    $conf_senha = $_GET['conf_senha'];
    
    // Captura os perfis selecionados
    $perfis_selecionados = isset($_GET['perfis']) ? $_GET['perfis'] : [];
    

    // Validação do cadastro
    if (empty($nome)){
        $mensagem = 'O nome é obrigatório';
    } elseif (empty($email)){
        $mensagem = 'O email é obrigatório';
    } elseif (empty($senha)){
        $mensagem = 'A senha é obrigatório';
    } elseif (empty($conf_senha)){
        $mensagem = 'Você esqueceu de confirmar sua senha';
    } elseif ($senha !== $conf_senha) {
        $mensagem = 'As senhas não correspondem';
    } elseif (empty($perfis_selecionados)) {
        $mensagem = 'Você deve selecionar pelo menos um perfil!';
    } else {

        $resposta = cadastrar_usuario($nome, $email, $senha, $perfis_selecionados);

        if($resposta == 3) {
            $nome = '';
            $email= '';
            $senha = '';
            $conf_senha = '';
            $perfis_selecionados = []; // Limpa a seleção de perfis ao cadastrar com sucesso
        }

        // Definindo mensagens para mostrar
        $men = ['Nome inválido', 'Senha está Vazia', 'Senha Inválida', 'Usuário cadastrado com Sucesso!'];
        $mensagem = $men[$resposta];
    }

    // Substitui as variáveis no HTML, mesmo se houver erro
    $html = str_replace('{{campoNome}}', $nome, $html);
    $html = str_replace('{{campoEmail}}', $email, $html);
    $html = str_replace('{{campoSenha}}', $senha, $html);
    $html = str_replace('{{campoConfirma}}', $conf_senha, $html);
    $html = str_replace('{{mensagem}}', $mensagem, $html);
} 

// Gera os checkboxes de perfis, mantendo os selecionados
    if (is_array($perfil)) {
        $perfis = '';
        foreach ($perfil as $linha) {
            $checked = in_array($linha['codigo'], $perfis_selecionados) ? 'checked' : '';
            $perfis .= "<input type='checkbox' name='perfis[]' value='" . $linha['codigo'] . "' $checked> " . $linha['nome'] . "<br>"; 
        }
    } else {
        $perfis = "<input type='checkbox' name='perfis'> Não há nenhum perfil cadastrado <br>";
    }

$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoEmail}}', $email, $html);
$html = str_replace('{{campoSenha}}', $senha, $html);
$html = str_replace('{{campoConfirma}}', $conf_senha, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = str_replace('{{perfis}}', $perfis, $html);
echo $html;
?>
