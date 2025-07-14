<?php

function tabela_usuarios(){
    $usuario = listar_usuarios();
    $usuarios = '';
    foreach ($usuario as $user) {
            $usuarios = $usuarios. '<tr>
                <td>'.mb_strtoupper($user["nome"]).'</td>
                <td>'.$user["email"].'</td>
                <td>
                    <form action="cUsuario.php" method="post">   
                        <input type="hidden" name="codigo_do_usuario" value="'. $user["codigo"] . '"> 
                        <input class="btn btn-outline-secondary" type="submit" name="atualizar" value="Alterar">&nbsp;
                        <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar" onclick="deseja_apagar()"> 
                    </form> 
                </td>
            </tr>';
    } 
    
    
    return $usuarios;
    }

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_usuario', 2);

$msg = '';
$id_msg = 'nada';
include_once 'Model/mUsuario.php';
if (isset($_POST['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_usuario', 2); // apagar_usu
    
    $l_msg = ['O usuário foi removido com Sucesso.','O usuário não pode ser excluído, pois possui retiradas vinculadas a ele.','O usuário não pode ser removido, pois há reservas associadas a ele.'];
    $cod_usuario = $_POST['codigo_do_usuario'];
    $msg = apagar_usuario($cod_usuario);
    
    $id_msg = ($msg==0)?'success':'danger';
    $msg = $l_msg[$msg];


}elseif(isset($_POST["atualizar"])){
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_usuario', 2);
    $cod_usuario = $_POST['codigo_do_usuario'];
    header("Location: cFormularioUsuario.php?codigo=$cod_usuario");
    exit();
}


$usuarios = tabela_usuarios();

$html = file_get_contents('View/vUsuario.php');
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{resp}}', $id_msg, $html);

$html = str_replace('{{usuarios}}', $usuarios, $html);
echo $html;


// msg estilizadas ok
?>
