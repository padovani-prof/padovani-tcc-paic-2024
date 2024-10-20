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
        $msm = 'Falha no banco';
        if(insere_periodo($nomeP, $dataIn, $dataFim)===true)
        {
            $msm = 'Período cadastrado com Sucesso!!';
            $nomeP = '';
            $dataIn = '';
            $dataFim = '';
            $respo = 'sucessso';

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




echo $html; // Exibe o HTML atualizado
?>

