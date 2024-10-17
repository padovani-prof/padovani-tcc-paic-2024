<?php
    include_once 'Model/mUsuario.php';  
    $perfil = listar_perfil();

    if (is_array($perfil)){

        $perfis='';

        foreach ($perfil as $linha) {
            $perfis .= "<input type='checkbox' name='perfis[]' value='" . $linha['nome'] . "'> " . $linha['nome'] . "<br>"; 
        }

    } else{
        $perfis .= "<input type='checkbox' name='perfis'> Não há nenhum perfil cadastrado <br>";
    }

    $html = file_get_contents('View/vFormularioUsuario.php');
    $html = str_replace('{{perfis}}', $perfis, $html);
    echo $html;
?>