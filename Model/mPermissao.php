<?php 

function cadastra_acesso_recurso($cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $data_fim)
{
    
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $lis_sema = str_replace(',','',$lis_sema);

        $sql = "INSERT INTO acesso_recurso (codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) values ($cod_re, $codigo_per, '$h_ini', ' $h_fim', '$lis_sema', '$data_ini', ".(strlen($data_fim) == 10?"'$data_fim'":"null" ).")";

        $resposta = $conecxao->query($sql);
        $conecxao->close();
        

        
    }


}

function recurso_carrega($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT perfil_usuario.nome as 'perfio', 
            acesso_recurso.hr_inicial as 'ini', 
            acesso_recurso.hr_final as 'fim', 
            acesso_recurso.codigo as 'cod'
            FROM `acesso_recurso` 
        INNER JOIN perfil_usuario
        on perfil_usuario.codigo = acesso_recurso.codigo_perfil
        WHERE acesso_recurso.codigo_recurso=$codigo;");

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conecxao->close();
    
    
    return $resultado;
}  


function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $conecxao->query("DELETE from acesso_recurso where codigo=$chave_pri");

}

function Existe_essa_chave_na_tabela($chave, $tabela, $jogar_pra_onde){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from $tabela where codigo=$chave");
    if($resulata->num_rows == 0){
        header("Location: $jogar_pra_onde");
        exit();
    }

}

function carrega_opition(){

    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $conecxao->query("SELECT codigo, nome from perfil_usuario");

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);

   
    $conecxao->close();
    
    return $resultado;
}


function nome_recurso($codigo){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $lista = $conecxao->query("SELECT nome from recurso where codigo=$codigo;");
    $conecxao->close();
    return $lista->fetch_assoc()['nome'];

}

?>

