<?php
    include_once 'Model/mUsuario.php';  
    $perfil = listar_perfil();

    // Primeiro, carregue o HTML
    $html = file_get_contents('View/vFormularioUsuario.php'); 
    
    // Armazena os perfis selecionados
    $perfis_selecionados = isset($_GET['perfis']) ? $_GET['perfis'] : [];

    if (isset($_GET["salvar"])) {
        include_once 'Model/mUsuario.php';
 
        $nome = $_GET['nome'];
        $email = $_GET['email'];
        $senha = $_GET['senha'];
        $conf_senha = $_GET['conf_senha'];
    
        if ($senha !== $conf_senha){
            $html = str_replace('{{mensagem}}', 'As senhas não correspondem', $html );
            $html = str_replace('{{campoNome}}', $nome, $html);
            $html = str_replace('{{campoEmail}}', $email, $html);
            $html = str_replace('{{campoSenha}}', $senha, $html);
            $html = str_replace('{{campoConfirma}}', $conf_senha, $html);

        }else{
        
            // Validação de usuário e senha
            $resposta = cadastrar_usuario($nome, $email, $senha);

            // Definindo mensagens para mostrar
            $men = ['Nome inválido', 'Senha está Vazia', 'Senha Inválida', 'Usuário cadastrado com Sucesso!'];

            // Substitui as variáveis no HTML
            $html = str_replace('{{campoNome}}', $nome, $html);
            $html = str_replace('{{campoEmail}}', $email, $html);
            $html = str_replace('{{campoSenha}}', $senha, $html);
            $html = str_replace('{{campoConfirma}}', $conf_senha, $html);
            $html = str_replace('{{mensagem}}', $men[$resposta], $html); 
        }


    } else {
        $html = str_replace('{{campoNome}}', '', $html);
        $html = str_replace('{{campoEmail}}', '', $html);
        $html = str_replace('{{campoSenha}}', '', $html);
        $html = str_replace('{{campoConfirma}}', '', $html);
        $html = str_replace('{{mensagem}}', '', $html);
    }

    if (is_array($perfil)) {
        $perfis = '';
        foreach ($perfil as $linha) {
            $checked = in_array($linha['codigo'], $perfis_selecionados) ? 'checked' : '';
            $perfis .= "<input type='checkbox' name='perfis[]' value='" . $linha['codigo'] . "' $checked> " . $linha['nome'] . "<br>"; 
        }
    } else {
        $perfis = "<input type='checkbox' name='perfis'> Não há nenhum perfil cadastrado <br>";
    }
    $html = str_replace('{{perfis}}', $perfis, $html);
    echo $html;
?>
