<?php 
function dias_da_semana($lista_sema)
{
    $semana = '';
    foreach($lista_sema as $dia)
    {
        if ($dia == NULL)
        {
            $semana = $semana.'N';
        }
        else
        {
            $semana = $semana.'S';
        }
    }
    return $semana;
}





function cadastra_acesso_recurso($cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $data_fim , $op)
{
    $lis_sema = dias_da_semana($lis_sema);
    


    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->prepare("INSERT INTO acesso_recurso (codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) values (?, ?, ?, ?, ?, ?, ?)");


        $resulta->bind_param("iissss$op", $cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $data_fim);
        
        $resulta->execute();

        
    }


}




function carrega_perfil_usuario()
{
    
    

    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT nome, codigo from perfil_usuario");

    $perfil = [];
    while ($linha = $resultado->fetch_assoc())
    {
        $perfil[] = $linha;
    }
    // vai retorna uma lista com todas as informações do perfil de usuario
    return $perfil;
}


function carrega_recurso($codigo)
{
    
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT nome from recurso where codigo=$codigo");

    return $resultado->fetch_assoc();
    // vai retorna uma lista com o nome do recurso

}


function carrega_acesso_recurso($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT * from acesso_recurso where codigo_recurso=$codigo");

    $acesso_rec = [];
    while ($linha = $resultado->fetch_assoc())
    {
        $acesso_rec[] = $linha;
    }
    // vai retorna uma lista com todas as informações dos acessos aos recursos
    return $acesso_rec;
}



function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $conecxao->query("DELETE from acesso_recurso where codigo=$chave_pri");

}

?>
