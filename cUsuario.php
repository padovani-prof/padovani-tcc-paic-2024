<?php
session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
# verificação de acesso da fucionalidade
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_perfil');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}
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
$html = str_replace('{{usuarios}}', $usuarios, $html);
echo $html;
?>
