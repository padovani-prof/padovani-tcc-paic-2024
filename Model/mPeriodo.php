<?php 



function apagar_periodo($chave_pri)
{
   
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    
    $resulta = $conecxao->query("DELETE from periodo where codigo=$chave_pri");
    return $resulta;

    

}

function carrega_periodo()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);


    $resultado = $cone->query('SELECT *  from periodo ');

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $cone->close();

    return $resultado; 
    // retorna todos os dados da tabela categoria_recurso do banco em forma de lista com nome e o codigo

}





function insere_periodo($nome, $data_ini, $data_final)
{
    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("SELECT * FROM periodo WHERE nome='$nome'");
        if ($resulta->num_rows == 0)
        {
            $resulta = $conecxao->query ("INSERT INTO periodo (nome, dt_inicial, dt_final) VALUES ('$nome', '$data_ini', '$data_final');");
            
            return 0;
            // salvo

        }
        else
        {
            return 1;
            // nome repetido
        }

    }
    

}


function mandar_dados($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from periodo where codigo=$chave");
    $resulata = $resulata->fetch_assoc();
    return $resulata;

}


function verificar_periodo($codigo , $dataine, $data_fim){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $sql = "SELECT * from periodo where codigo=$codigo and dt_inicial = '$dataine' and dt_final= '$data_fim';";
    
    $resulata = $conecxao->query($sql);

    
    return $resulata->num_rows == 1;


}

function atualizar_periodo($chave, $nome, $dataIn, $dataFim){

    include 'confg_banco.php';

    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("SELECT * FROM periodo WHERE nome='$nome' and codigo!=$chave ");
        if ($resulta->num_rows == 0)
        {
            $conecxao->query ("UPDATE periodo set nome='$nome',  dt_inicial='$dataIn', dt_final='$dataFim' WHERE codigo=$chave");
            return 0;
            // salvo

        }
        else
        {
            return 1;
            // nome repetido
        }

    }


}


function Existe_esse_periodo($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from periodo where codigo=$chave");
    if($resulata->num_rows == 0){
        return false;
    }
    return true;

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


?>
