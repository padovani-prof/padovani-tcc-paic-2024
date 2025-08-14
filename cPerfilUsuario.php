<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';

Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_perfil', 2);

include_once 'Model/mPerfilUsuario.php';

$resposta = 'nada'; 
$msg = '';

// Exclusão de perfil
if (isset($_GET['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_perfil', 2);

    $cod_perfil = $_GET['codigo_do_perfil'];
    $resposta = apagar_perfil($cod_perfil);

    if ($resposta) {
        $msg = 'O perfil de usuário foi apagado com sucesso.';
        $resposta = 'success';
    } else {
        $msg = 'Este perfil não pode ser removido, pois existem usuários vinculados a ele.';
        $resposta = 'danger';
    }
}

// Atualização de perfil
else if (isset($_GET['atualizar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_perfil', 2);
    $codigo = $_GET['codigo_do_perfil'];
    header("Location: cFormularioPerfil.php?codigo=$codigo");
    exit();
}

// Listagem de perfis
$perfil = listar_perfis();  
$perfis = '';
foreach ($perfil as $p) {
    $perfis .= '<tr>
        <td>'.mb_strtoupper($p["nome"]).'</td>
        <td>'.$p["descricao"].'</td>
        <form action="cPerfilUsuario.php">   
            <input type="hidden" name="codigo_do_perfil" value="'. $p["codigo"] . '"> 
            <td>
                <input class="btn btn-outline-secondary" name="atualizar" type="submit" value="Alterar">&nbsp;
                <input class="btn btn-outline-danger" name="apagar" type="submit" value="Apagar">
            </td> 
        </form> 
    </tr>';
}

// Substitui as variáveis no HTML
$html = file_get_contents('View/vPerfilUsuario.php');
$html = cabecalho($html, 'Perfis de Usuários');
$html = str_replace('{{perfis}}', $perfis, $html);
$html = str_replace('{{resp}}', $resposta, $html);
$html = str_replace('{{msg}}', $msg, $html);

echo $html;

?>
