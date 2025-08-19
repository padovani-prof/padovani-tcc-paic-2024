<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();

include_once 'Model/mUsuario.php'; 
$ultilazador = $_SESSION['codigo_usuario'];
$dados_perfis = carrega_perfis_expecificos_do_dastrante_ou_adm($ultilazador);

$titulo = $dados_perfis[1];
$perfil = $dados_perfis[0];
$nome = '';
$email = '';
$senha = '';
$conf_senha = '';
$perfis_selecionados = [];
$mensagem = '';
$id_resposta = 'danger';


$tela = '';
$html = file_get_contents('View/vFormularioUsuario.php');

if (isset($_GET['codigo']) && !isset($_POST['salvar'])) {
    // Carregar dados do usuário
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_usuario', 2);
    $tela = '<input type="hidden" name="codigo" value="'.$_GET['codigo'].'">';
    $codigo = $_GET['codigo'];
    Verificar_codigo('usuario', $codigo);
    $dados = carregar_dados($codigo);
    $nome = $dados['nome'];
    $email = $dados['email'];
    $perfis_selecionados = carrega_perfil_do_usuario($codigo);
} elseif (!isset($_GET['codigo']) && !isset($_POST['salvar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_usuario', 2);
    
}

if (isset($_POST["salvar"])) {
    $resposta = -1;
    
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $conf_senha = trim($_POST['conf_senha']);
    $perfis_selecionados = isset($_POST['perfis']) ? $_POST['perfis'] : [];

    if (isset($_POST['codigo'])) {
        // Atualização de usuário

        $tela = '<input type="hidden" name="codigo" value="'.$_POST['codigo'].'">';
        $codigo = $_POST['codigo'];
        if (empty($nome)) {
            $mensagem = 'O campo "Nome" é obrigatório.';
        } elseif (empty($email)) {
            $mensagem = 'O campo "E-mail" é obrigatório.';
        } elseif (empty($senha) && !empty($conf_senha)) {
            $mensagem = 'Por favor, preencha todos os campos de senha.';
        } elseif (!empty($senha) && empty($conf_senha)) {
            $mensagem = 'Confirme a nova senha.';
        }  elseif ($senha !== $conf_senha) {
            $mensagem = 'As senhas informadas não correspondem.';
        } 
         else {
            $resposta = Validar_usuario_atualizado($codigo, $nome, $senha, $email);
            if($dados_perfis[2] == true and $resposta == true){
                // atualiza os perfis do usuario tambem
                novo_usuario_atualizado($codigo, $nome, $email, $senha);
                $resposta = atualizar_usuario_com_permicao($codigo, $perfis_selecionados);
            }else if ($resposta==true){
                novo_usuario_atualizado($codigo, $nome, $email, $senha);
                $resposta = 3;
            }

            
            $men = [
                'O nome informado é inválido. Deve ter entre 3 e 50 caracteres.',
                'A senha está vazia.',
                'Senha inválida.',
                'Usuário atualizado com sucesso!',
                'Nome já existente. Insira um novo.',
                'E-mail já existente. Insira um endereço diferente.'
            ];
            $mensagem = $men[$resposta];
        }
    } else {
        // Cadastro de novo usuário
        if (empty($nome)) {
            $mensagem = 'O campo "Nome" é obrigatório.';
        } elseif (empty($email)) {
            $mensagem = 'O campo "E-mail" é obrigatório.';
        } elseif (empty($senha) && !empty($conf_senha)) {
            $mensagem = 'Por favor, preencha todos os campos de senha.';
        } elseif (empty($senha)) {
            $mensagem = 'Crie uma senha para o usuário.';
        } elseif (mb_strlen($senha) < 4 || mb_strlen($senha) > 50) {
            $mensagem = 'A senha deve ter entre 4 e 50 caracteres.';
        } elseif (empty($conf_senha)) {
            $mensagem = 'Confirme a senha.';
        } elseif ($senha !== $conf_senha) {
            $mensagem = 'As senhas informadas não correspondem.';
        }else if(count($perfis_selecionados) == 0){
            $mensagem = 'Selecione algun perfil de usuário.'; 
        }else {
            $resposta = cadastrar_usuario($nome, $email, $senha, $perfis_selecionados);
            $men = [
                'Nome inválido. Deve ter entre 3 e 50 caracteres.',
                'A senha está vazia.',
                'Senha inválida.',
                'Usuário cadastrado com sucesso!',
                'Nome já existente. Insira um novo.',
                'E-mail já existente. Insira um endereço diferente.'
            ];
            $mensagem = $men[$resposta];
        }
    }

    if ($resposta == 3) {
        $nome = '';
        $email = '';
        $senha = '';
        $conf_senha = '';
        $perfis_selecionados = [];
        $id_resposta = 'success';
    }

    // Atualiza campos no HTML
    $html = str_replace('{{campoNome}}', $nome, $html);
    $html = str_replace('{{campoEmail}}', $email, $html);
    $html = str_replace('{{campoSenha}}', $senha, $html);
    $html = str_replace('{{campoConfirma}}', $conf_senha, $html);
    $html = str_replace('{{mensagem}}', $mensagem, $html);
} 

// Gera os checkboxes de perfis
$perfis = '';
if (is_array($perfil)) {
    $perfis .= "<tr>"; 
    $cont = 1;
    foreach ($perfil as $linha) {
        $checked = in_array($linha['codigo'], $perfis_selecionados) ? "checked" : "";
        $perfis .= '
        <td title="'.$linha['descricao'].'" alt="'.$linha['descricao'].'"><input type="checkbox" name="perfis[]" '.$checked.' value="'. $linha['codigo'] .'"/>'.$linha['nome'].($cont%3==0?'<br>&nbsp;</td></tr>':'<br>&nbsp;</td>');
        $cont++;
    }
}

// Substitui variáveis no HTML
$html = str_replace('{{tipo}}', $tela, $html);
$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoEmail}}', $email, $html);
$html = str_replace('{{campoSenha}}', $senha, $html);
$html = str_replace('{{campoConfirma}}', $conf_senha, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = str_replace('{{perfis}}', $perfis, $html);
$html = str_replace('{{retorno}}', $id_resposta, $html);
$html = cabecalho($html, 'Usuário');
$html = str_replace('{{titulo}}', $titulo, $html);

echo $html;

?>

