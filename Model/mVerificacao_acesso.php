<?php 


function lista_usuario_acesso($chave_usu)
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $sql = "SELECT f.codigo, f.sigla, f.descricao from sgrp.funcionalidade f where f.codigo in ( select fp.codigo_funcionalidade from sgrp.funcionalidade_perfil fp where fp.codigo_perfil in ( select up.codigo_perfil from sgrp.usuario_perfil up where up.codigo_usuario = $chave_usu ) );";
    $resultado = $cone->query($sql);
    $todos_dados = [];
    while ($dados = $resultado->fetch_assoc()) {
        $todos_dados[] = $dados;
    }
    return $todos_dados;
}


function verificação_acesso($chave_usu, $codi_categoria_de_acesso)
{
    $acesso = lista_usuario_acesso($chave_usu);
    foreach($acesso as $permicao)
    {
        if($permicao['sigla']== $codi_categoria_de_acesso)
        {
            return true;
        }
    }

    return false;
}

?>
