<?php 


function lista_usuario_acesso($chave_usu)
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    $sql = "SELECT f.codigo, f.sigla, f.descricao from sgrp.funcionalidade f where f.codigo in ( select fp.codigo_funcionalidade from sgrp.funcionalidade_perfil fp where fp.codigo_perfil in ( select up.codigo_perfil from sgrp.usuario_perfil up where up.codigo_usuario = $chave_usu ) );";
    $resultado = $cone->query($sql);
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();

    return $resultado;
}


function verificação_acesso($chave_usu, $codi_categoria_de_acesso, $retorno=1, $msg='Acesso negado!', $local='cMenu.php' )
{
    // retorno 1 e que se vc deseja retorna ou 2+ seja redirecionado para a tela de açesso negado
    // msg que vc que que apareça 
    // local para onde vc que que seja jogado
    $acesso = lista_usuario_acesso($chave_usu);
    foreach($acesso as $permicao)
    {
        if($permicao['sigla']== $codi_categoria_de_acesso)
        {
            return true;
        }
    }
    if($retorno !=1){
        header("Location: $local?msg=$msg");
        exit();

    }else {
        return false;
    }
    
}

function Esta_logado($mandar='cLogin.php' , $msg='Usuario desconectado!'){
    session_start();
    if(!isset($_SESSION['codigo_usuario']))
    {   
        // Se o usuario não fez login jogue ele para logar
        header("Location: $mandar?msg=$msg");
        exit();
    }

}


function mandar_options($lista, $marcar=''){

    $opt = '<option value="NULL">...</option>';
    
    
        foreach($lista as $dados)
        {
            if($marcar==$dados['codigo']){
               
                $opt .= '<option selected value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 

            }else{
                $opt .= '<option value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 
            }
              
        }
    return $opt;
    
    
}

?>
