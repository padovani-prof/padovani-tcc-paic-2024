<?php

// =========================
// FUNÇÃO AUXILIAR
// =========================
function tabela_usuarios(){
    $usuario = listar_usuarios();
    $usuarios = '';

    foreach ($usuario as $user) {
        $usuarios .= '<tr>
            <td>'.mb_strtoupper($user["nome"]).'</td>
            <td>'.$user["email"].'</td>
            <td>
                <form action="cUsuario.php" method="post">   
                    <input type="hidden" name="codigo_do_usuario" value="'. $user["codigo"] . '"> 
                    <input class="btn btn-outline-secondary" type="submit" name="atualizar" value="Alterar">&nbsp;
                    <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar" onclick="return deseja_apagar()"> 
                </form> 
            </td>
        </tr>';
    } 

    return $usuarios;
}

// =========================
// INCLUSÃO DE MODELOS E VERIFICAÇÃO DE ACESSO
// =========================
include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_usuario', 2);

include_once 'Model/mUsuario.php';

// =========================
// TRATAMENTO DE AÇÕES (APAGAR / ATUALIZAR)
// =========================
$msg = '';
$id_msg = 'nada';

if (isset($_POST['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_usuario', 2);

    $l_msg = [
        'O usuário foi removido com sucesso.',
        'O usuário não pode ser excluído, pois possui retiradas vinculadas a ele.',
        'O usuário não pode ser removido, pois há reservas associadas a ele.'
    ];

    $cod_usuario = $_POST['codigo_do_usuario'];
    $resultado = apagar_usuario($cod_usuario);

    $id_msg = ($resultado == 0) ? 'success' : 'danger';
    $msg = $l_msg[$resultado];

} elseif(isset($_POST["atualizar"])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_usuario', 2);
    $cod_usuario = $_POST['codigo_do_usuario'];
    header("Location: cFormularioUsuario.php?codigo=$cod_usuario");
    exit();
}

// =========================
// GERAÇÃO DA TABELA DE USUÁRIOS
// =========================
$usuarios = tabela_usuarios();

// =========================
// CARREGAMENTO DO HTML
// =========================
$html = file_get_contents('View/vUsuario.php');
$html = cabecalho($html, 'Usuários');
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{resp}}', $id_msg, $html);
$html = str_replace('{{usuarios}}', $usuarios, $html);

// =========================
// EXIBIÇÃO FINAL
// =========================
echo $html;
?>
