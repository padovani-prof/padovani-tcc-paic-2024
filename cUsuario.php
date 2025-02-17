<?php

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_usuario', 2);

$msg = '';
$id_msg = 'nada';
include_once 'Model/mUsuario.php';
if (isset($_GET['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'list_usuario', 2); // apagar_usu
    
    $l_msg = ['O usuário foi removido com sucesso.','O usuário não pode ser excluído, pois possui retiradas vinculadas a ele.','O usuário não pode ser removido, pois há reservas associadas a ele.'];
    $cod_usuario = $_GET['codigo_do_usuario'];
    $msg = apagar_usuario($cod_usuario);
    
    $id_msg = ($msg==0)?'sucesso':'erro';
    $msg = $l_msg[$msg];


}elseif(isset($_GET["atualizar"])){
    $cod_usuario = $_GET['codigo_do_usuario'];
    header("Location: cFormularioUsuario.php?codigo=$cod_usuario");
    exit();
}

$usuarios = listar_usuarios();
$usuarios = tabela_usuarios($usuarios);

$html = file_get_contents('View/vUsuario.php');
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{resp}}', $id_msg, $html);

$html = str_replace('{{usuarios}}', $usuarios, $html);
echo $html;
?>
