<?php


include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();




include_once 'Model/mUsuario.php';  
$perfil = listar_perfil();


$nome = '';
$email = '';
$senha = '';
$conf_senha = '';
$perfis_selecionados = [];
$mensagem = '';
$id_resposta = 'nada';

$tela = isset($_GET['codigo'])?'<input type="hidden" name="codigo" value="'.$_GET['codigo'].'">':'';


$html = file_get_contents('View/vFormularioUsuario.php');
if(isset($_GET['codigo']) and !isset($_POST['salvar'])){
    // carregra dados
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_usuario', 2);
    $codigo = $_GET['codigo'];
    $dados = carregar_dados($codigo);
    $nome = $dados['nome'];
    $email = $dados['email'];
    $perfis_selecionados = carrega_perfil_do_usuario($codigo);
}else if(!isset($_GET['codigo']) and !isset($_POST['salvar'])){
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_usuario', 2);

}

if (isset($_POST["salvar"])) {
    $resposta = -1;
    $id_resposta = 'erro';
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $conf_senha = $_POST['conf_senha'];
    // Captura os perfis selecionados
    $perfis_selecionados = isset($_POST['perfis']) ? $_POST['perfis'] : [];
    
    if(isset($_POST['codigo'])){
        //validar atualizações
        $codigo = $_POST['codigo'];
        if (empty($nome)){
            $mensagem = 'O nome é obrigatório';
        } elseif (empty($email)){
            $mensagem = 'O email é obrigatório';
        }  elseif (!empty($conf_senha) and empty($senha)){
            $mensagem = 'Crie uma nova senha.';
        }elseif (!empty($senha) and empty($conf_senha)){
            $mensagem = 'Confirme sua nova senha.';
        }
        elseif ($senha !== $conf_senha) {
            $mensagem = 'As senhas não correspondem';
        }
        elseif (empty($perfis_selecionados)) {
            $mensagem = 'Você deve selecionar pelo menos um perfil!';
        } else{
            $resposta =  atualizar_usuario($codigo, $nome, $email, $senha, $perfis_selecionados);
        
            // Definindo mensagens para mostrar
            $men = ['O nome informado é inválido. Ele deve ter no mínimo 3 caracteres e no máximo 50.', 'Senha está Vazia.', 'Senha Inválida.', 'Usuário atualizado com Sucesso!','Nome existente. Insira um novo.', 'E-mail existente. Insira um novo endereço.'];
            $mensagem = $men[$resposta];

        }
        
        

    }else{
        // Validação do cadastro
        if (empty($nome)){
            $mensagem = 'O nome é obrigatório';
        } elseif (empty($email)){
            $mensagem = 'O email é obrigatório';
        } elseif (empty($senha)){
            $mensagem = 'Crie uma senha';
        }elseif ((mb_strlen($senha) < 3 || mb_strlen($senha) > 50)) {
            $mensagem = 'A senha precisa ter no mínimo 4 e no máximo 50 caracteres';
        }elseif (empty($conf_senha)){
            $mensagem = 'Confirme a senha';
        }elseif ($senha !== $conf_senha) {
            $mensagem = 'As senhas não correspondem';
        } elseif (empty($perfis_selecionados)) {
            $mensagem = 'Você deve selecionar pelo menos um perfil!';
        }else{
            $resposta = cadastrar_usuario($nome, $email, $senha, $perfis_selecionados);
            // Definindo mensagens para mostrar
            $men = ['Nome inválido', 'Senha está Vazia', 'Senha Inválida', 'Usuário cadastrado com Sucesso!','Nome existente. Insira um novo.', 'E-mail existente. Insira um novo endereço.'];
            $mensagem = $men[$resposta];

        }
    }

    if($resposta == 3) {
        $nome = '';
        $email= '';
        $senha = '';
        $conf_senha = '';
        $perfis_selecionados = []; // Limpa a seleção de perfis ao cadastrar com sucesso
        $id_resposta = 'sucesso';
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
    $perfis = "<tr>"; 
    $cont = 1;
    foreach ($perfil as $linha) {
        $checked = in_array($linha['codigo'], $perfis_selecionados) ? "checked" : "";
        $perfis .= '
        <td title="'.$linha['descricao'] .'" alt="'.$linha['descricao'] .'"><input type="checkbox" name="perfis[]"'.$checked.' value="'. $linha['codigo'] .'"/>'.$linha['nome'].($cont%3==0?'<br>&nbsp;</td></tr>':'<br>&nbsp;</td>');
        $cont ++;
    }
    
} else { 
    $perfis = "<input type='checkbox' name='perfis'> Não há nenhum perfil cadastrado <br>";
}

$html = str_replace('{{tipo}}', $tela, $html);
$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoEmail}}', $email, $html);
$html = str_replace('{{campoSenha}}', $senha, $html);
$html = str_replace('{{campoConfirma}}', $conf_senha, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = str_replace('{{perfis}}', $perfis, $html);
$html = str_replace('{{retorno}}', $id_resposta, $html);
echo $html;
?>
