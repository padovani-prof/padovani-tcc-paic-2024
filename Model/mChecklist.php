
<?php 


function carrega_recurso($codigo)
{
    
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT nome from recurso where codigo=$codigo");

    return $resultado->fetch_assoc()['nome'];
    // vai retorna uma lista com o nome do recurso

}


function cerrega_dados_checklist($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resultado = $conecxao->query("SELECT * from checklist where codigo_recurso= $codigo");


    $dados = '';
    while ($linha = $resultado->fetch_assoc())
    {
        $dados .='<tr>';
        $dados .= '<td>  '.mb_strtoupper($linha['item']).'</td>';
        $dados .='<td> <form action="cChecklist.php">   
                                    <input type="hidden" name="codigo_item" value="' .$linha['codigo'].  '"> 
                                    <input type="hidden" name="codigo" value="'  .$codigo.  '"> 
                                    <input type="submit" name="apagar" value="Apagar">
                                    </form> 
                            </td>';
        $dados .='</tr>';
    }

    return $dados; // mada os dados em html

}


function salva_no_banco($item, $codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conecxao->prepare("INSERT INTO checklist (item, codigo_recurso
    ) values(?, ?)");
    $resulta->bind_param("si", $item, $codigo);
    $resulta->execute();


}


function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $conecxao->query("DELETE from checklist where codigo=$chave_pri");

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
