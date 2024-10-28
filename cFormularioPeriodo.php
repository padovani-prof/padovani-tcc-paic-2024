<?php


$html = file_get_contents('View/vFormularioPeriodo.php');


$nomeP = '';
$dataIn = '';
$dataFim = '';
$msm = '';
$respo = '';


if(isset($_GET['salvar']))
{
    $respo = 'erro';
    $nomeP =  $_GET['txtnome'];
    $dataIn = $_GET['data_ini'];
    $dataFim = $_GET['data_fim'];

    


    
    if (  mb_strlen($_GET['data_ini'])>=10 and mb_strlen($_GET['data_fim'])>=10  and mb_strlen($_GET['txtnome'])>=6)
    {
        // preecheu tudo 
        include_once 'Model/mPeriodo.php';
        $ano_atual = date('Y');
        $mes_atual = date('m');
        $dia_atual = date('d');

        
        // so vai cadastra o periodo se a data inicial  for maior igual que a data atual 

        $quebra_data_ini = new DateTime($dataIn);
        $ano_ini = $quebra_data_ini->format('Y');
        $mes_ini = $quebra_data_ini->format('m');
        $dia_ini = $quebra_data_ini->format('d');
        
        $resposta = 2;
        if ($ano_ini==$ano_atual and $mes_ini>=$mes_atual or $ano_ini>$ano_atual)
        {
            // data inicial precisa ser maior que data final
            $quebra_data_ini = new DateTime($dataFim);
            $ano_fim = $quebra_data_ini->format('Y');
            $mes_fim = $quebra_data_ini->format('m');
            $dia_fim = $quebra_data_ini->format('d');
            $resposta = 3;
            if ($ano_fim==$ano_ini and $mes_fim>$mes_ini  or $ano_fim > $ano_ini)
            {
                $resposta = insere_periodo($nomeP, $dataIn, $dataFim);
            }
            
        }


        
        
        $lMensagens = [ 'Período cadastrado com Sucesso!!','Não podemos salvar nomes de períodos repetidos', 'Data inicial ínvalida','Data final invalida'];
        $msm = $lMensagens[$resposta];
        
        if($resposta==0)
        {
            $respo = 'sucessso';
            $nomeP = '';
            $dataIn = '';
            $dataFim = '';
        }
        
    }
    else
    {
        $msm = 'Porfavor, preecha todas as datas'; // data não pechidas

        if(mb_strlen($_GET['txtnome'])<6)
        {
            $msm = 'Nome do período inválido'; // nome do periodo invalido
        }
       
    }

}

$html = str_replace('{{mandan}}',$respo, $html);
$html = str_replace('{{nomePeriodo}}',$nomeP, $html);
$html = str_replace('{{dataIni}}',$dataIn, $html);
$html = str_replace('{{dataFim}}',$dataFim, $html);



$html = str_replace('{{mensagem}}',$msm, $html);
$html = str_replace('{{msg}}',$respo , $html);




echo $html; // Exibe o HTML atualizado
?>

