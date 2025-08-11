<?php
function cabecalho($html, $codigo){
    include 'Model/mUsuario.php';
    $nome = strtoupper(carregar_dados($codigo)['nome']);
    $cabecario = file_get_contents('View/vHeader.php');
    $cabecario = str_replace('{{usuario}}', $nome, $cabecario);
    $html = str_replace('{{cabecario}}', $cabecario, $html);
    return $html;    
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

        $opt = '<option  value="NULL">...</option>';
        
        if (isset($lista[0]['descricao'])){
            foreach($lista as $dados)
            {
                if($marcar==$dados['codigo']){
                    
                    $opt .= '<option selected title="'.$dados['descricao'].'" value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 
        
                }else{
                    $opt .= '<option title="'.$dados['descricao'].'" value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 
                }
                    
            }
    
        }else{
            
            foreach($lista as $dados)
            {
                if($marcar==$dados['codigo']){
                    
                    $opt .= '<option selected value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 
        
                }else{
                    $opt .= '<option  value="'. $dados['codigo'].'">'.mb_strtoupper($dados['nome'] ) .'</option>'; 
                }
                    
            }
        }
       
        return $opt;
        
        
    }

function Verificar_codigo($tabela, $codigo){
    $dados = Existe_esse_dado_para_atualizar($tabela, $codigo);
    if (count($dados)==0) {
        header('Location: cMenu.php');
        exit();
    }


}



?>


