<?php




include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_periodo', 2);

$html = file_get_contents('View/vFormularioPeriodo.php');


$nomeP = '';
$dataIn = '';
$dataFim = '';
$msm = '';
$respo = 'nada';
$tela = '';
$tipo_tela = 'Cadastrar';

include_once 'Model/mPeriodo.php';
if(isset($_GET['codigo']) or isset($_GET['t_codigo'])){

    // é possivel atualizar o periodo com ele ja sendo referencia?
    $tipo_tela = 'Atualizar';
    if(isset($_GET['codigo'])){
        $codigo = $_GET['codigo'];
        $dados = mandar_dados($codigo);
        $nomeP = $dados['nome'];
        $dataIn = $dados['dt_inicial'];
        $dataFim = $dados['dt_final'];
        $tela = '<input type="hidden" name="t_codigo" value="' .$codigo.'">';
        if(!Existe_esse_periodo($codigo)){
            header('Location: cPeriodo.php');
            exit();

        }
        

    }else{
        $codigo = $_GET['t_codigo'];
        if(!Existe_esse_periodo($codigo)){
            header('Location: cPeriodo.php');
            exit();

        }
        $tela = '<input type="hidden" name="t_codigo" value="' .$codigo.'">';
        $respo = 'erro';
        $nomeP =  $_GET['txtnome'];
        $dataIn = $_GET['data_ini'];
        $dataFim = $_GET['data_fim'];
        

        if (  mb_strlen($_GET['data_ini'])>=10 and mb_strlen($_GET['data_fim'])>=10  and mb_strlen($_GET['txtnome'])>=6)
        {
            // preecheu tudo 
            
            $ano_atual = date('Y');
            $mes_atual = date('m');
            $dia_atual = date('d');

            
            // so vai cadastra o periodo se a data inicial  for maior igual que a data atual 

            $quebra_data_ini = new DateTime($dataIn);
            $ano_ini = $quebra_data_ini->format('Y');
            $mes_ini = $quebra_data_ini->format('m');
            $dia_ini = $quebra_data_ini->format('d');
            
            $vereficar = verificar_periodo($codigo , $dataIn, $dataFim);
            $resposta = 2;
            if ($ano_ini==$ano_atual and $mes_ini>=$mes_atual or $ano_ini>$ano_atual or $vereficar)
            {
                // data inicial precisa ser maior que data final
                $quebra_data_ini = new DateTime($dataFim);
                $ano_fim = $quebra_data_ini->format('Y');
                $mes_fim = $quebra_data_ini->format('m');
                $dia_fim = $quebra_data_ini->format('d');
                $resposta = 3;
                if ($ano_fim==$ano_ini and $mes_fim==$mes_ini and $dataIn<$dia_fim  or $ano_fim > $ano_ini or $ano_fim==$ano_ini and $mes_fim>$mes_ini or $vereficar)
                {
                    $resposta = atualizar_periodo($codigo, $nomeP, $dataIn, $dataFim);
                }
                
            }

            $lMensagens = [ 'Período atualizado com Sucesso!!','Não podemos salvar nomes de períodos repetidos', 'Data inicial ínvalida','Data final invalida'];
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
    
}
else if(isset($_GET['salvar']))
{
    $respo = 'erro';
    $nomeP =  $_GET['txtnome'];
    $dataIn = $_GET['data_ini'];
    $dataFim = $_GET['data_fim'];

    if (  mb_strlen($_GET['data_ini'])>=10 and mb_strlen($_GET['data_fim'])>=10  and mb_strlen($_GET['txtnome'])>=6)
    {
        // preecheu tudo 
        
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
            if ($ano_fim==$ano_ini and $mes_fim==$mes_ini and $dataIn<$dia_fim  or $ano_fim > $ano_ini or $ano_fim==$ano_ini and $mes_fim>$mes_ini)
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
$html = str_replace('{{tipo_tela}}', $tela, $html);
$html = str_replace('{{tela}}', $tipo_tela, $html);

$html = str_replace('{{mensagem}}',$msm, $html);
$html = str_replace('{{msg}}',$respo , $html);




echo $html; // Exibe o HTML atualizado
?>

