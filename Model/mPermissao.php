<?php 
function dias_da_semana()
{
    $semana = '';
    $cont = 0;
    for ($i=0; $i < 7; $i++){
        if (isset($_GET["dia$i"]))
        {
            $semana = $semana.'S,';

            
        }
        else
        {
            $cont +=1;
            $semana = $semana.'N,';
        }
    }
    if ($cont>=7)
    {
        $semana = '';
    }
    return $semana;
    
}





function cadastra_acesso_recurso($cod_re, $codigo_per, $h_ini, $h_fim, $lis_sema, $data_ini, $data_fim)
{
    
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $lis_sema = str_replace(',','',$lis_sema);

        $sql = "INSERT INTO acesso_recurso (codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) values ($cod_re, $codigo_per, '$h_ini', ' $h_fim', '$lis_sema', '$data_ini', ".(strlen($data_fim) == 10?"'$data_fim'":"null" ).")";

        $resposta = $conecxao->query($sql);
        

        
    }


}

function Tabela_acesso_recurso_carrega($codigo)
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
        WHERE acesso_recurso.codigo_recurso =$codigo;");

    $informa = '';
    while ($dados = $resultado->fetch_assoc())
    {
        $informa .= '<tr>';
        $informa .= '<td> '. $dados ["perfio"].'</td>'; // coluna nome
        $informa .= '<td> '.$dados ['ini'] . ' - '.$dados ['fim'].'</td>'; // coluna horarios
        $informa .=  '<td> <form action="cPermissaoRecurso.php">   
                        <input type="hidden" name="codigo_recurso" value="' .$codigo.'"> 
                        <input type="hidden" name="codigo_acesso_ao_recurso" value="'.$dados['cod'].  '"> 
                        <input type="submit" name="apagar" value="Apagar">
                    </form> </td>'; // coluna de ação para apagar
        $informa = $informa . '<tr/>';
        
    }
        
    
    return $informa;
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

function opition($perfi){

    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $lista = $conecxao->query("SELECT codigo, nome from perfil_usuario");

    $usua = '<option value="NULL">...</option>';
    while ($dados = $lista->fetch_assoc())
    {

        $usua .='<option value="' .$dados ['codigo'].'"' . ($dados ['codigo'] ==  $perfi? ' selected' : '') . '> '.$dados ['nome'].'</option>';
    }
    
    return $usua;
}


function nome_recurso($codigo){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $lista = $conecxao->query("SELECT nome from recurso where codigo=$codigo;");
    return $lista->fetch_assoc()['nome'];

}




function marcar_semana($semanas, $html){
    $semanas = explode(',', $semanas);
   
    for ($i=0; $i < 7; $i++) { 
        if($semanas[$i] == 'S'){
            
            $html = str_replace("{{{$i}}}", "checked", $html);
        }
    }
    return $html;
    

}



?>

