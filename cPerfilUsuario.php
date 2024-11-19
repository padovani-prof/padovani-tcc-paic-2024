<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login joge ele para logar
    header('Location: cLogin.php');
    exit();
}

    include_once 'Model/mPerfilUsuario.php';

    $perfil = listar_perfis();

    if (isset($_GET['codigo_do_perfil'])) {

        $cod_perfil = $_GET['codigo_do_perfil'];
        apagar_perfil($cod_perfil);
    }

        
    $perfis = '<tbody>';
    foreach ($perfil as $p) {
        $perfis .= '<tr>
            <td>'.$p["nome"].'</td>
            <td>'.$p["descricao"].'</td>
            <td><a href="#">alterar</a></td>
            <td>
                <form action="cPerfilUsuario.php">   
                    <input type="hidden" name="codigo_do_perfil" value="'. $p["codigo"] . '"> 
                    <input type="submit" name="apagar" value="Apagar">
                </form> 
            </td>
        </tr>';
    }
    $perfis .= '</tbody>';

    $html = file_get_contents('View/vPerfilUsuario.php');
    $html = str_replace('{{perfis}}', $perfis, $html);
    echo $html;
?>
