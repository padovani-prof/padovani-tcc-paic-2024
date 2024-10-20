<?php
    include_once 'Model/mUsuario.php';

    if (isset($_GET['codigo_do_usuario'])) {

        $cod_usuario = $_GET['codigo_do_usuario'];
        apagar_usuario($cod_usuario);
    }

    $usuario = listar_usuarios();
        
    $usuarios = '<tbody>';
    foreach ($usuario as $user) {
        
            $usuarios = $usuarios. '<tr>
                <td>'.$user["nome"].'</td>
                <td>'.$user["email"].'</td>
                <td><a href="#">alterar</a></td>
                <td>
                    <form action="cUsuario.php">   
                        <input type="hidden" name="codigo_do_usuario" value="'. $user["codigo"] . '"> 
                        <input type="submit" name="apagar" value="Apagar">
                    </form> 
                </td>
            </tr>';
    } 
    $usuarios = $usuarios. '<tbody/>';

    $html = file_get_contents('View/vUsuario.php');

    // Substitui {{perfis}} pelo conteúdo da variável $perfis
    if ($html !== false) {
        $html = str_replace('{{usuarios}}', $usuarios, $html);
        echo $html;
    } else {
        echo "Erro ao carregar o HTML da página.";
    }
?>
